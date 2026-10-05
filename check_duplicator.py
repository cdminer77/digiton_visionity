import urllib.request
import urllib.parse
import http.cookiejar
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

# Check text in #wpbody-content
m = re.search(r'<div id="wpbody-content"[^>]*>([\s\S]*?)<div class="clear">', html)
if m:
    body = m.group(1)
    # Strip HTML tags to see the visible text
    text = re.sub(r'<[^>]+>', ' ', body)
    text = ' '.join(text.split())
    print("VISIBLE TEXT IN DUPLICATOR:")
    print(text[:1000])

# Check forms
for form in re.findall(r'<form[\s\S]*?</form>', html):
    action = re.findall(r'action=[\'"]([^\'"]*)[\'"]', form)
    inputs = re.findall(r'name=[\'"]([^\'"]*)[\'"]', form)
    print("\nFORM action:", action)
    print("FORM inputs:", inputs)

# Check all links inside wpbody-content
if m:
    for a in re.findall(r'<a[^>]+href=[\'"]([^\'"]+)[\'"][^>]*>([\s\S]*?)</a>', m.group(1)):
        clean_text = ' '.join(re.sub(r'<[^>]+>', '', a[1]).split())
        if clean_text:
            print(f"LINK: '{clean_text}' -> {a[0]}")
