import re
import json

with open('step2.html', encoding='utf-8') as f:
    s2 = f.read()

m = re.search(r'<form[^>]+id=["\']form-duplicator["\'][^>]*>([\s\S]*?)</form>', s2)
if m:
    print("Found form-duplicator in step 2:")
    print("Tag:", re.findall(r'<form[^>]+>', m.group(0)))
    inputs = re.findall(r'<input[^>]+>', m.group(0))
    for inp in inputs:
        print("  input:", inp)

# Also check step3.html
with open('step3.html', encoding='utf-8') as f:
    s3 = f.read()

print("\n--- STEP 3 TITLE & CONTENT ---")
titles = re.findall(r'<title>(.*?)</title>', s3)
print("Title:", titles)
# Look for any duplicator errors or messages in step3
notices = re.findall(r'<div[^>]+class=["\'][^"\']*(?:notice|error|warning)[^"\']*["\'][^>]*>([\s\S]*?)</div>', s3)
for n in notices[:5]:
    clean_n = ' '.join(re.sub(r'<[^>]+>', ' ', n).split())
    if clean_n:
        print("Notice:", clean_n[:200])

# Look for packages list in step3 (if it redirected to packages list)
m_pkg = re.findall(r'dup-pack-[a-zA-Z0-9_\-]+', s3)
print("Packages in step3:", len(m_pkg))
