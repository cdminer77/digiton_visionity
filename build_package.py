import urllib.request
import urllib.parse
import http.cookiejar
import html as html_lib
import json
import time
import re
import os

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
if not m_form1:
    log("Error: could not find step 1 form")
    exit(1)

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

log("Submitting Step 1 to Step 2 (Scan setup)...")
resp2 = opener.open(urllib.request.Request(full_action1_url, data=urllib.parse.urlencode(inputs1).encode('utf-8'), headers={'User-Agent': 'Mozilla/5.0'}))
html2 = resp2.read().decode('utf-8', errors='ignore')
log("Reached Step 2.")

# 4. Extract Scan AJAX Nonce from Step 2
scan_nonce_m = re.search(r'action:\s*[\'"]duplicator_package_scan[\'"],\s*(?:firstChunk:\s*[a-zA-Z0-9]+,\s*)?nonce:\s*[\'"]([a-zA-Z0-9]+)[\'"]', html2)
if not scan_nonce_m:
    # Alternative search
    scan_nonce_m = re.search(r'[\'"]duplicator_package_scan[\'"][\s\S]*?nonce:\s*[\'"]([a-zA-Z0-9]+)[\'"]', html2)

scan_nonce = scan_nonce_m.group(1) if scan_nonce_m else None
log(f"Scan Nonce: {scan_nonce}")

ajax_url = 'https://visionity.digiton.it/wp-admin/admin-ajax.php'

# Run scan loop
log("Starting Duplicator Scan...")
first_chunk = 'true'
while True:
    scan_data = urllib.parse.urlencode({
        'action': 'duplicator_package_scan',
        'firstChunk': first_chunk,
        'nonce': scan_nonce
    }).encode('utf-8')
    
    req_scan = urllib.request.Request(ajax_url, data=scan_data, headers={
        'User-Agent': 'Mozilla/5.0',
        'X-Requested-With': 'XMLHttpRequest'
    })
    
    scan_resp = opener.open(req_scan)
    scan_json = json.loads(scan_resp.read().decode('utf-8'))
    
    status = scan_json.get('Status', 0)
    log(f"Scan status response: {status}")
    
    if status == 1: # Done / Success
        log("Scan completed successfully!")
        payload = scan_json.get('Payload', {})
        arc_size = payload.get('ARC', {}).get('Size', 'N/A')
        db_size = payload.get('DB', {}).get('Size', 'N/A')
        log(f"Archive estimated size: {arc_size}, DB size: {db_size}")
        break
    elif status == 2: # More to scan (next chunk)
        log("Scan chunk finished, requesting next chunk...")
        first_chunk = 'false'
        time.sleep(1)
    else:
        log(f"Unexpected scan status or warning: {scan_json}")
        break

# 5. Extract form-duplicator from Step 2 to advance to Step 3 (Build)
m_form2 = re.search(r'<form[^>]+id=[\'"]form-duplicator[\'"][^>]*>([\s\S]*?)</form>', html2)
if not m_form2:
    log("Error: could not find form-duplicator in step 2")
    exit(1)

form2_html = m_form2.group(0)
action2_url = html_lib.unescape(re.search(r'action=[\'"]([^\'"]*)[\'"]', form2_html).group(1))
full_action2_url = urllib.parse.urljoin('https://visionity.digiton.it', action2_url)

inputs2 = {}
for inp in re.findall(r'<input[^>]+>', form2_html):
    name_m = re.search(r'name=[\'"]([^\'"]+)[\'"]', inp)
    val_m = re.search(r'value=[\'"]([^\'"]*)[\'"]', inp)
    if name_m:
        inputs2[name_m.group(1)] = val_m.group(1) if val_m else ''

# Also ensure dupli-confirm-check is included if needed
inputs2['dupli-confirm-check'] = '1'

log(f"Submitting to Step 3: {full_action2_url}")
resp3 = opener.open(urllib.request.Request(full_action2_url, data=urllib.parse.urlencode(inputs2).encode('utf-8'), headers={'User-Agent': 'Mozilla/5.0'}))
html3 = resp3.read().decode('utf-8', errors='ignore')
log("Reached Step 3 (Build Screen).")

with open('step3.html', 'w', encoding='utf-8') as f:
    f.write(html3)

log("Saved step3.html. Inspecting build AJAX parameters...")
build_nonce_m = re.search(r'action:\s*[\'"]duplicator_package_build[\'"],\s*nonce:\s*[\'"]([a-zA-Z0-9]+)[\'"]', html3)
if not build_nonce_m:
    build_nonce_m = re.search(r'[\'"]duplicator_package_build[\'"][\s\S]*?nonce:\s*[\'"]([a-zA-Z0-9]+)[\'"]', html3)

build_nonce = build_nonce_m.group(1) if build_nonce_m else None
log(f"Build Nonce: {build_nonce}")
