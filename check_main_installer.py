import urllib.request
import re

url = 'http://127.0.0.1:8080/installer.php'
resp = urllib.request.urlopen(url)
html = resp.read().decode('utf-8', errors='ignore')

# Extract form action and params
action = re.search(r'action="([^"]+)"', html).group(1)
csrf = re.search(r'name="ctrl_csrf_token"\s+value="([^"]+)"', html).group(1)

step1_url = f"{action}?ctrl_action=ctrl-step1&ctrl_csrf_token={csrf}&step_action=init"
print("Requesting Step 1:", step1_url)

resp1 = urllib.request.urlopen(step1_url)
html1 = resp1.read().decode('utf-8', errors='ignore')

print("Status:", resp1.status)
titles = re.findall(r'<title>(.*?)</title>', html1)
print("Title:", titles)

# Check validation checks in step 1
checks = re.findall(r'<div[^>]+class="[^"]*dup-box-title[^"]*"[^>]*>([\s\S]*?)</div>', html1)
for c in checks[:10]:
    clean = ' '.join(re.sub(r'<[^>]+>', '', c).split())
    if clean:
        print("  Check:", clean)

inputs = re.findall(r'<input[^>]+name="([^"]+)"[^>]*value="([^"]*)"', html1)
print("Form inputs:", [(k, v) for k, v in inputs if len(v) < 50][:15])
