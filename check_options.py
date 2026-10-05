with open('step1_rendered.html', encoding='utf-8') as f:
    html = f.read()

import re

m = re.search(r'<select[^>]+name=["\']archive_action["\'][^>]*>([\s\S]*?)</select>', html)
if m:
    opts = re.findall(r'<option[^>]+value=["\']([^"\']*)["\'][^>]*>([^<]*)', m.group(1))
    for val, text in opts:
        print(f"Value: '{val}' -> Label: '{text.strip()}'")
