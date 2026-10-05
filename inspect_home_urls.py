import urllib.request
import re

url = "https://test100.afterbit.it/"
req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
res = urllib.request.urlopen(req)
html = res.read().decode('utf-8', errors='ignore')

old_domains = [m for m in re.findall(r'https?://[^\s"\'<>]+', html) if 'visionity.digiton.it' in m or '127.0.0.1' in m]
print(f"Old domain URLs found in homepage: {len(old_domains)}")
for u in old_domains[:10]:
    print("  ->", u)
