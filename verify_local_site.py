import urllib.request
import urllib.parse
import http.cookiejar
import re

cj = http.cookiejar.CookieJar()
opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj))

# 1. Test Homepage
print("--- 1. Testing Homepage (http://127.0.0.1:8080/) ---")
try:
    resp_home = opener.open(urllib.request.Request('http://127.0.0.1:8080/', headers={'User-Agent': 'Mozilla/5.0'}))
    print("Homepage Status:", resp_home.status)
    print("Homepage URL:", resp_home.geturl())
    html_home = resp_home.read().decode('utf-8', errors='ignore')
    titles = re.findall(r'<title>(.*?)</title>', html_home)
    print("Homepage Title:", titles)
    print("Length:", len(html_home))
except Exception as e:
    print("Homepage Error:", e)

# 2. Test Login
print("\n--- 2. Testing Login to Admin ---")
login_url = 'http://127.0.0.1:8080/wp-login.php'
opener.open(login_url)
login_data = urllib.parse.urlencode({
    'log': 'Digiton',
    'pwd': 'jhd0_44M2',
    'wp-submit': 'Log In',
    'redirect_to': 'http://127.0.0.1:8080/wp-admin/',
    'testcookie': '1'
}).encode('utf-8')

try:
    resp_login = opener.open(urllib.request.Request(login_url, data=login_data, headers={'User-Agent': 'Mozilla/5.0'}))
    print("Post-Login URL:", resp_login.geturl())
    print("Post-Login Status:", resp_login.status)
    html_login = resp_login.read().decode('utf-8', errors='ignore')
    admin_titles = re.findall(r'<title>(.*?)</title>', html_login)
    print("Dashboard Title:", admin_titles)
except Exception as e:
    print("Login Error:", e)

# 3. Test Pages screen
print("\n--- 3. Testing Pages Admin Screen ---")
try:
    resp_pages = opener.open(urllib.request.Request('http://127.0.0.1:8080/wp-admin/edit.php?post_type=page', headers={'User-Agent': 'Mozilla/5.0'}))
    print("Pages Screen Status:", resp_pages.status)
    html_pages = resp_pages.read().decode('utf-8', errors='ignore')
    pages_title = re.findall(r'<title>(.*?)</title>', html_pages)
    print("Pages Screen Title:", pages_title)
    # Check some page titles present in HTML
    found_pages = re.findall(r'class="row-title">([^<]+)<', html_pages)
    print("Sample Pages in Admin:", found_pages[:6])
except Exception as e:
    print("Pages Error:", e)
