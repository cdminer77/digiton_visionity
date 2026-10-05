import urllib.request
import urllib.parse
import http.cookiejar
import html as html_lib
import json
import time
import re

cj = http.cookiejar.CookieJar()
opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj))

def log(msg):
    print(f"[{time.strftime('%H:%M:%S')}] {msg}", flush=True)

# 1. Login
log("Logging in to WP Admin...")
login_url = 'https://visionity.digiton.it/wp-login.php'
opener.open(login_url)
login_data = urllib.parse.urlencode({
    'log': 'Digiton',
    'pwd': 'jhd0_44M2',
    'wp-submit': 'Log In',
    'redirect_to': 'https://visionity.digiton.it/wp-admin/admin.php?page=duplicator',
    'testcookie': '1'
}).encode('utf-8')
resp = opener.open(urllib.request.Request(login_url, data=login_data, headers={'User-Agent': 'Mozilla/5.0'}))
html_dup = resp.read().decode('utf-8', errors='ignore')
log("Logged in.")

# 2. Get Step 1 URL
m1 = re.search(r'href=[\'"]([^\'"]*inner_page=new1[^\'"]*)[\'"]', html_dup)
if not m1:
    log("Error: could not find step 1 link")
    exit(1)

new1_url = urllib.parse.urljoin('https://visionity.digiton.it', html_lib.unescape(m1.group(1)))
log(f"Navigating to Step 1: {new1_url}")
resp1 = opener.open(urllib.request.Request(new1_url, headers={'User-Agent': 'Mozilla/5.0'}))
html1 = resp1.read().decode('utf-8', errors='ignore')

# 3. Submit Step 1 to Step 2
m_form1 = re.search(r'<form[^>]+action=[\'"]([^\'"]*inner_page=new2[^\'"]*)[\'"][^>]*>([\s\S]*?)</form>', html1)
form1_html = m_form1.group(0)
action1_url = html_lib.unescape(re.search(r'action=[\'"]([^\'"]*)[\'"]', form1_html).group(1))
full_action1_url = urllib.parse.urljoin('https://visionity.digiton.it', action1_url)

inputs1 = {}
for inp in re.findall(r'<input[^>]+>', form1_html):
    name_m = re.search(r'name=[\'"]([^\'"]+)[\'"]', inp)
    val_m = re.search(r'value=[\'"]([^\'"]*)[\'"]', inp)
    type_m = re.search(r'type=[\'"]([^\'"]+)[\'"]', inp)
    if name_m:
        name = name_m.group(1)
        val = val_m.group(1) if val_m else ''
        itype = type_m.group(1).lower() if type_m else 'text'
        if itype in ('checkbox', 'radio'):
            if 'checked' in inp:
                inputs1[name] = val
        else:
            inputs1[name] = val

log("Submitting Step 1 to Step 2...")
resp2 = opener.open(urllib.request.Request(full_action1_url, data=urllib.parse.urlencode(inputs1).encode('utf-8'), headers={'User-Agent': 'Mozilla/5.0'}))
html2 = resp2.read().decode('utf-8', errors='ignore')
log("Reached Step 2.")

# 4. Extract Scan AJAX Nonce
scan_nonce_m = re.search(r'action:\s*[\'"]duplicator_package_scan[\'"],\s*(?:firstChunk:\s*[a-zA-Z0-9]+,\s*)?nonce:\s*[\'"]([a-zA-Z0-9]+)[\'"]', html2)
scan_nonce = scan_nonce_m.group(1) if scan_nonce_m else None
log(f"Scan Nonce: {scan_nonce}")

ajax_url = 'https://visionity.digiton.it/wp-admin/admin-ajax.php'

# Run scan loop
log("Starting Duplicator Scan...")
first_chunk = 'true'
chunk_count = 0
scan_success = False

while chunk_count < 30:
    chunk_count += 1
    scan_data = urllib.parse.urlencode({
        'action': 'duplicator_package_scan',
        'firstChunk': first_chunk,
        'nonce': scan_nonce
    }).encode('utf-8')
    
    req_scan = urllib.request.Request(ajax_url, data=scan_data, headers={
        'User-Agent': 'Mozilla/5.0',
        'X-Requested-With': 'XMLHttpRequest',
        'Referer': resp2.geturl()
    })
    
    scan_resp = opener.open(req_scan)
    scan_json = json.loads(scan_resp.read().decode('utf-8'))
    
    func_data = scan_json.get('data', {}).get('funcData', {})
    status = func_data.get('Status', 0)
    log(f"Scan chunk {chunk_count}: status = {status}")
    
    if status == 1: # Pass!
        log("Scan PASS (100% complete)!")
        scan_success = True
        payload = func_data.get('Payload', {})
        arc_info = payload.get('ARC', {})
        db_info = payload.get('DB', {})
        log(f"Files size: {arc_info.get('Size')}, DB size: {db_info.get('Size')}")
        break
    elif status == 4: # MoreToScan
        log(f"More to scan... progressing (chunk {chunk_count})")
        first_chunk = 'false'
        time.sleep(1)
    else:
        log(f"Status not Pass or MoreToScan: {scan_json}")
        break

if not scan_success:
    log("Scan did not succeed, aborting.")
    exit(1)

# 5. Extract form-duplicator from Step 2
m_form2 = re.search(r'<form[^>]+id=[\'"]form-duplicator[\'"][^>]*>([\s\S]*?)</form>', html2)
form2_html = m_form2.group(0)
action2_url = html_lib.unescape(re.search(r'action=[\'"]([^\'"]*)[\'"]', form2_html).group(1))
full_action2_url = urllib.parse.urljoin('https://visionity.digiton.it', action2_url)

inputs2 = {}
for inp in re.findall(r'<input[^>]+>', form2_html):
    name_m = re.search(r'name=[\'"]([^\'"]+)[\'"]', inp)
    val_m = re.search(r'value=[\'"]([^\'"]*)[\'"]', inp)
    if name_m:
        inputs2[name_m.group(1)] = val_m.group(1) if val_m else ''

log(f"Submitting create-from-temp to: {full_action2_url}")
log(f"Fields: {inputs2}")

resp3 = opener.open(urllib.request.Request(full_action2_url, data=urllib.parse.urlencode(inputs2).encode('utf-8'), headers={
    'User-Agent': 'Mozilla/5.0',
    'Referer': resp2.geturl()
}))
html3 = resp3.read().decode('utf-8', errors='ignore')
log(f"Response URL: {resp3.geturl()}")

with open('step3_after_scan.html', 'w', encoding='utf-8') as f:
    f.write(html3)

log("Saved step3_after_scan.html. Checking content...")
# Look for build progress or active backup in html3
if 'build' in html3.lower() or 'costruzione' in html3.lower() or 'creazione' in html3.lower():
    log("Build screen or message detected in HTML!")

# Check if a package is now being built or completed
m_build = re.findall(r'action:\s*[\'"]([^\'"]*build[^\'"]*)[\'"]', html3)
log(f"Build actions found: {m_build}")
