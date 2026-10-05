import urllib.request
import re

url = "https://test100.afterbit.it/membership/"
req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
res = urllib.request.urlopen(req)
html = res.read().decode('utf-8', errors='ignore')

# Check images
imgs = re.findall(r'<img[^>]+src=["\']([^"\']+)["\']', html)
print(f"Total images found: {len(imgs)}")
broken_imgs = []
for img in imgs[:15]:
    print("IMG:", img)

# Check if there are any remaining old domains in html
old_domains = [m for m in re.findall(r'https?://[^\s"\'<>]+', html) if 'visionity.digiton.it' in m or '127.0.0.1' in m]
print(f"Old domain URLs found in rendered page: {len(old_domains)}")
for u in old_domains[:10]:
    print("  ->", u)
