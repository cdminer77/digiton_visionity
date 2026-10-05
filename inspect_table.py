with open('step3_after_scan.html', encoding='utf-8') as f:
    html = f.read()

import re

m = re.search(r'<table[^>]+class=["\'][^"\']*dup-packtbl[^"\']*["\'][^>]*>([\s\S]*?)</table>', html)
if m:
    print("Found dup-packtbl table:")
    # print all tr rows
    rows = re.findall(r'<tr[^>]*>([\s\S]*?)</tr>', m.group(1))
    for i, r in enumerate(rows):
        text = ' '.join(re.sub(r'<[^>]+>', ' ', r).split())
        print(f"Row {i}: {text[:300]}")
        # find any links or ids or data attributes
        data_attrs = re.findall(r'(?:data-[a-zA-Z0-9_\-]+|id|name)=["\'][^"\']+["\']', r)
        print("  Attrs:", data_attrs[:5])
