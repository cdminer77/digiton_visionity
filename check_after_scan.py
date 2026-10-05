with open('step3_after_scan.html', encoding='utf-8') as f:
    html = f.read()

import re

# check for packages table or active build
tables = re.findall(r'<table[^>]+class=["\'][^"\']*dup-pack-table[^"\']*["\'][^>]*>([\s\S]*?)</table>', html)
for m in tables:
    print('Found dup-pack-table!')
    rows = re.findall(r'<tr[^>]*>([\s\S]*?)</tr>', m)
    for r in rows:
        clean = ' '.join(re.sub(r'<[^>]+>', ' ', r).split())
        print('  ROW:', clean[:200])

# check for any progress or building indicator
for m in re.findall(r'class=["\'][^"\']*(?:building|progress|active|dup-pack)[^"\']*["\']', html):
    print('Class match:', m)

# check for any download buttons or links
for a in re.findall(r'<a[^>]+href=["\']([^"\']*(?:download|archive|installer|daf|zip)[^"\']*)["\'][^>]*>([\s\S]*?)</a>', html, re.I):
    print('DOWNLOAD/LINK:', a[0], 'TEXT:', ' '.join(re.sub(r'<[^>]+>', '', a[1]).split()))

# Check for package id or package name
pkg_info = re.findall(r'(?:package_id|package_hash|package_name|pack_id)[\'"]?\s*[:=]\s*[\'"]?([a-zA-Z0-9_\-]+)', html)
print('Package info found:', pkg_info)
