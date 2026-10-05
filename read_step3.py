with open('step3.html', encoding='utf-8') as f:
    s3 = f.read()

import re
m = re.search(r'<div id="wpbody-content"[^>]*>([\s\S]*?)<div class="clear">', s3)
if m:
    text = ' '.join(re.sub(r'<[^>]+>', ' ', m.group(1)).split())
    print("STEP 3 WP BODY:")
    print(text[:1500])

# Check any errors
for alert in re.findall(r'<div[^>]+class="[^"]*(?:error|notice|warning)[^"]*"[^>]*>([\s\S]*?)</div>', s3):
    clean = ' '.join(re.sub(r'<[^>]+>', ' ', alert).split())
    if clean:
        print("Alert:", clean)
