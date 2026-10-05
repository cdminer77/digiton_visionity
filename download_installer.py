import urllib.request
import urllib.parse
import http.cookiejar
import html as html_lib
import os

dest_dir = r"c:\Users\Utente\.gemini\antigravity\scratch\visionity_digiton\local_staging"
os.makedirs(dest_dir, exist_ok=True)
dest_path = os.path.join(dest_dir, "installer.php")

cj = http.cookiejar.CookieJar()
opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj))

# Login
login_url = 'https://visionity.digiton.it/wp-login.php'
opener.open(login_url)
login_data = urllib.parse.urlencode({
    'log': 'Digiton',
    'pwd': 'jhd0_44M2',
    'wp-submit': 'Log In',
    'testcookie': '1'
}).encode('utf-8')
opener.open(urllib.request.Request(login_url, data=login_data, headers={'User-Agent': 'Mozilla/5.0'}))

# Download installer
installer_url = "https://visionity.digiton.it/wp-admin/admin-ajax.php?action=duplicator_download_package_file&hash=6fbfc4192241a76a1455_20261002084329&token=2aed5a25e9cdd97e5c9cd577b04d9f73&fileType=0"

print(f"Downloading installer.php from {installer_url}...")
req = urllib.request.Request(installer_url, headers={
    'User-Agent': 'Mozilla/5.0',
    'Referer': 'https://visionity.digiton.it/wp-admin/admin.php?page=duplicator'
})

with opener.open(req) as resp, open(dest_path, 'wb') as out_f:
    data = resp.read()
    out_f.write(data)

print(f"Installer downloaded successfully: {len(data)} bytes to {dest_path}")
