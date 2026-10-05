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

# Extract 'Aggiungi' link with nonce
m = re.search(r'href=[\'"]([^\'"]*inner_page=new1[^\'"]*)[\'"]', html)
if m:
    raw_url = html_lib.unescape(m.group(1))
    new1_url = urllib.parse.urljoin('https://visionity.digiton.it', raw_url)
    print("Opening step 1:", new1_url)
    req2 = urllib.request.Request(new1_url, headers={
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
        'Referer': 'https://visionity.digiton.it/wp-admin/admin.php?page=duplicator'
    })
    resp2 = opener.open(req2)
    html2 = resp2.read().decode('utf-8', errors='ignore')
    
    m2 = re.search(r'<div id="wpbody-content"[^>]*>([\s\S]*?)<div class="clear">', html2)
    if m2:
        body = m2.group(1)
        text = ' '.join(re.sub(r'<[^>]+>', ' ', body).split())
        print("STEP 1 TEXT:")
        print(text[:800])
        
    for form in re.findall(r'<form[\s\S]*?</form>', html2):
        print("\nFORM action:", re.findall(r'action=[\'"]([^\'"]*)[\'"]', form))
        inputs = re.findall(r'<input[^>]+name=[\'"]([^\'"]+)[\'"][^>]*value=[\'"]([^\'"]*)[\'"]', form)
        print("FORM hidden inputs:", [(k, v) for k, v in inputs if len(v) < 100])
        submits = re.findall(r'<input[^>]+type=[\'"]submit[\'"][^>]*value=[\'"]([^\'"]*)[\'"]', form)
        print("Submit buttons:", submits)
        btns = re.findall(r'<button[^>]*>([\s\S]*?)</button>', form)
        print("Buttons:", btns)
