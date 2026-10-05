import urllib.request
import re

resp = urllib.request.urlopen('http://127.0.0.1:8080/installer.php')
html = resp.read().decode('utf-8', errors='ignore')

# Check checks / validation
print("Checks / Warnings:")
for m in re.findall(r'<div[^>]+class="[^"]*(?:validation|warning|check)[^"]*"[^>]*>([\s\S]*?)</div>', html):
    clean = ' '.join(re.sub(r'<[^>]+>', ' ', m).split())
    if clean:
        print("  -", clean[:150])

# Check archive info in installer
arc_matches = re.findall(r'(?:archive_file|archive_path|archive_size|daf)[\'"]?\s*[:=]\s*[\'"]?([^\'",;]+)', html, re.I)
print("Archive info:", arc_matches[:10])

# Check next button
buttons = re.findall(r'<button[^>]*>([\s\S]*?)</button>', html)
for b in buttons:
    clean = ' '.join(re.sub(r'<[^>]+>', '', b).split())
    if clean:
        print("Button:", clean)
