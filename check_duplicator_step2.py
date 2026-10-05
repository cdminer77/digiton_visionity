import urllib.request
import urllib.parse
import http.cookiejar
import html as html_lib
import re

cj = http.cookiejar.CookieJar()
opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj))

# 1. Login
login_url = 'https://visionity.digiton.it/wp-login.php'
opener.open(login_url)

login_data = urllib.parse.urlencode({
    'log': 'Digiton',
    'pwd': 'jhd0_44M2',
    'wp-submit': 'Log In',
    'redirect_to': 'https://visionity.digiton.it/wp-admin/admin.php?page=duplicator',
    'testcookie': '1'
}).encode('utf-8')

req = urllib.request.Request(login_url, data=login_data, headers={
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
    'Referer': login_url
})
resp = opener.open(req)
html = resp.read().decode('utf-8', errors='ignore')

# 2. Get step 1 URL
m = re.search(r'href=[\'"]([^\'"]*inner_page=new1[^\'"]*)[\'"]', html)
if not m:
    print("Could not find step 1 link")
    exit(1)

raw_url = html_lib.unescape(m.group(1))
new1_url = urllib.parse.urljoin('https://visionity.digiton.it', raw_url)
req1 = urllib.request.Request(new1_url, headers={
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
    'Referer': 'https://visionity.digiton.it/wp-admin/admin.php?page=duplicator'
})
resp1 = opener.open(req1)
html1 = resp1.read().decode('utf-8', errors='ignore')

# 3. Find form action & hidden fields in step 1
m_form = re.search(r'<form[^>]+id=[\'"]dup-form-opts[\'"][^>]*>([\s\S]*?)</form>', html1)
if not m_form:
    m_form = re.search(r'<form[^>]+action=[\'"]([^\'"]*inner_page=new2[^\'"]*)[\'"][^>]*>([\s\S]*?)</form>', html1)

if m_form:
    form_html = m_form.group(0)
    action_match = re.search(r'action=[\'"]([^\'"]*)[\'"]', form_html)
    action_url = html_lib.unescape(action_match.group(1)) if action_match else ''
    full_action_url = urllib.parse.urljoin('https://visionity.digiton.it', action_url)
    
    # Collect all inputs
    inputs = {}
    for inp in re.findall(r'<input[^>]+>', form_html):
        name_m = re.search(r'name=[\'"]([^\'"]+)[\'"]', inp)
        val_m = re.search(r'value=[\'"]([^\'"]*)[\'"]', inp)
        type_m = re.search(r'type=[\'"]([^\'"]+)[\'"]', inp)
        if name_m:
            name = name_m.group(1)
            val = val_m.group(1) if val_m else ''
            itype = type_m.group(1).lower() if type_m else 'text'
            if itype in ('checkbox', 'radio'):
                if 'checked' in inp:
                    inputs[name] = val
            else:
                inputs[name] = val
                
    print("Action URL:", full_action_url)
    print("POST fields:", inputs)
    
    post_data = urllib.parse.urlencode(inputs).encode('utf-8')
    req2 = urllib.request.Request(full_action_url, data=post_data, headers={
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
        'Referer': new1_url
    })
    resp2 = opener.open(req2)
    html2 = resp2.read().decode('utf-8', errors='ignore')
    
    print("\n--- STEP 2 RESPONSE ---")
    print("Final URL:", resp2.geturl())
    # Find any JS variables or AJAX actions for scan
    ajax_vars = re.findall(r'(?:dup_ajax|ajaxurl|action|nonce)[\'"]?\s*[:=]\s*[\'"][^\'"]+[\'"]', html2)
    print("AJAX hints:", ajax_vars[:15])
    
    # Check visible text
    m_body = re.search(r'<div id="wpbody-content"[^>]*>([\s\S]*?)<div class="clear">', html2)
    if m_body:
        text = ' '.join(re.sub(r'<[^>]+>', ' ', m_body.group(1)).split())
        print("STEP 2 TEXT:", text[:1000])
else:
    print("Form not found in step 1")
