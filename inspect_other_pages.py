import urllib.request
import re

for slug in ['onboarding', 'contact-us-2']:
    url = f"https://test100.afterbit.it/{slug}/"
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
    res = urllib.request.urlopen(req)
    html = res.read().decode('utf-8', errors='ignore')
    old_domains = [m for m in re.findall(r'https?://[^\s"\'<>]+', html) if 'visionity.digiton.it' in m or '127.0.0.1' in m]
    print(f"{slug}: HTTP {res.getcode()}, Old domain URLs: {len(old_domains)}")
    for u in old_domains[:5]:
        print("  ->", u)
