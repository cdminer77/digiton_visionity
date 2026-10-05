with open('step2.html', 'w', encoding='utf-8') as f:
    pass

import urllib.request
import urllib.parse
import http.cookiejar
import html as html_lib
import re

cj = http.cookiejar.CookieJar()
opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj))

# Login
login_url = 'https://visionity.digiton.it/wp-login.php'
opener.open(login_url)
login_data = urllib.parse.urlencode({
    'log': 'Digiton',
    'pwd': 'jhd0_44M2',
    'wp-submit': 'Log In',
    'redirect_to': 'https://visionity.digiton.it/wp-admin/admin.php?page=duplicator',
    'testcookie': '1'
}).encode('utf-8')
req = urllib.request.Request(login_url, data=login_data, headers={'User-Agent': 'Mozilla/5.0'})
resp = opener.open(req)
html = resp.read().decode('utf-8', errors='ignore')

# Step 1
m = re.search(r'href=[\'"]([^\'"]*inner_page=new1[^\'"]*)[\'"]', html)
raw_url = html_lib.unescape(m.group(1))
new1_url = urllib.parse.urljoin('https://visionity.digiton.it', raw_url)
resp1 = opener.open(urllib.request.Request(new1_url, headers={'User-Agent': 'Mozilla/5.0'}))
html1 = resp1.read().decode('utf-8', errors='ignore')

# Step 2
m_form = re.search(r'<form[^>]+action=[\'"]([^\'"]*inner_page=new2[^\'"]*)[\'"][^>]*>([\s\S]*?)</form>', html1)
form_html = m_form.group(0)
action_url = html_lib.unescape(re.search(r'action=[\'"]([^\'"]*)[\'"]', form_html).group(1))
full_action_url = urllib.parse.urljoin('https://visionity.digiton.it', action_url)

inputs = {}
for inp in re.findall(r'<input[^>]+>', form_html):
    name_m = re.search(r'name=[\'"]([^\'"]+)[\'"]', inp)
    val_m = re.search(r'value=[\'"]([^\'"]*)[\'"]', inp)
    if name_m:
        inputs[name_m.group(1)] = val_m.group(1) if val_m else ''

post_data = urllib.parse.urlencode(inputs).encode('utf-8')
resp2 = opener.open(urllib.request.Request(full_action_url, data=post_data, headers={'User-Agent': 'Mozilla/5.0'}))
html2 = resp2.read().decode('utf-8', errors='ignore')

with open('step2.html', 'w', encoding='utf-8') as f:
    f.write(html2)

print("Saved step2.html, size:", len(html2))

# Find scan script or ajax calls in html2
scripts = re.findall(r'<script[^>]*>([\s\S]*?)</script>', html2)
print("Scripts count:", len(scripts))
for i, s in enumerate(scripts):
    if 'scan' in s.lower() or 'package' in s.lower() or 'duplijs' in s.lower():
        print(f"--- Script {i} has matches ({len(s)} chars) ---")
        lines = [line for line in s.split('\n') if any(w in line.lower() for w in ['ajax', 'action', 'url', 'scan', 'build', 'nonce'])]
        for l in lines[:10]:
            print("  ", l.strip()[:120])
