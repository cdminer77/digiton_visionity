import urllib.request
import re

req = urllib.request.Request('https://test100.afterbit.it/', headers={'User-Agent': 'Mozilla/5.0'})
res = urllib.request.urlopen(req)
html = res.read().decode('utf-8', errors='ignore')

matches = re.findall(r'<a[^>]+href=["\']([^"\']*membership[^"\']*)["\'][^>]*>(.*?)</a>', html, re.I)
for href, text in matches:
    print(f'Link: href="{href}", text="{text.strip()}"')

# Also check for 41222
matches2 = re.findall(r'<a[^>]+href=["\']([^"\']*41222[^"\']*)["\'][^>]*>(.*?)</a>', html, re.I)
for href, text in matches2:
    print(f'ID Link: href="{href}", text="{text.strip()}"')
