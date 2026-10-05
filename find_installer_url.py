with open('step3_after_scan.html', encoding='utf-8') as f:
    html = f.read()

import re

matches = re.findall(r'DownloadFile\(["\']([^"\']+)["\']', html)
for m in set(matches):
    print("Download URL:", m)
