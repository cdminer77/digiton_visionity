import urllib.request
import os

test_id = "1qvJa4LPsDWA_KRlFSoXPaFOW0ny_Y_wY"
test_url = f"https://lh3.googleusercontent.com/d/{test_id}"
headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'}

req = urllib.request.Request(test_url, headers=headers)
try:
    with urllib.request.urlopen(req) as resp:
        content = resp.read()
        print(f"Downloaded {len(content)} bytes, content-type: {resp.headers.get('content-type')}")
        with open("test_download.png", "wb") as f:
            f.write(content)
        print("Success!")
except Exception as e:
    print(f"Error with lh3: {e}")
    # Try uc?export=download
    uc_url = f"https://drive.google.com/uc?export=download&id={test_id}"
    req2 = urllib.request.Request(uc_url, headers=headers)
    try:
        with urllib.request.urlopen(req2) as resp:
            content = resp.read()
            print(f"UC Downloaded {len(content)} bytes, content-type: {resp.headers.get('content-type')}")
    except Exception as e2:
        print(f"Error with uc: {e2}")
