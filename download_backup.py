import urllib.request
import urllib.parse
import http.cookiejar
import os
import sys
import time

dest_dir = r"c:\Users\Utente\.gemini\antigravity\scratch\visionity_digiton\local_staging"
os.makedirs(dest_dir, exist_ok=True)

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

files_to_download = [
    ("installer.php", "https://visionity.digiton.it/wp-content/duplicator-backups/20261002_visionity_6fbfc4192241a76a1455_20261002084329_installer.php"),
    ("archive.daf", "https://visionity.digiton.it/wp-content/duplicator-backups/20261002_visionity_6fbfc4192241a76a1455_20261002084329_archive.daf")
]

for filename, url in files_to_download:
    dest_path = os.path.join(dest_dir, filename)
    print(f"\nDownloading {filename} from {url}...")
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
    
    try:
        with opener.open(req) as resp, open(dest_path, 'wb') as out_f:
            total_size = int(resp.headers.get('Content-Length', 0))
            downloaded = 0
            block_size = 1024 * 1024 # 1 MB
            start_time = time.time()
            last_print = start_time
            
            while True:
                chunk = resp.read(block_size)
                if not chunk:
                    break
                out_f.write(chunk)
                downloaded += len(chunk)
                
                curr_time = time.time()
                if curr_time - last_print >= 2 or downloaded == total_size:
                    percent = (downloaded / total_size * 100) if total_size > 0 else 0
                    speed = downloaded / (curr_time - start_time) / (1024 * 1024) if (curr_time - start_time) > 0 else 0
                    print(f"[{filename}] {downloaded / (1024*1024):.1f} MB / {total_size / (1024*1024):.1f} MB ({percent:.1f}%) @ {speed:.2f} MB/s", flush=True)
                    last_print = curr_time
                    
        print(f"Successfully downloaded {filename} ({os.path.getsize(dest_path)} bytes) to {dest_path}")
    except Exception as e:
        print(f"Error downloading {filename}: {e}")
