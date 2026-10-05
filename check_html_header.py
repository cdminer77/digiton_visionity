import urllib.request
import re

html = urllib.request.urlopen('http://127.0.0.1:8080/').read().decode('utf-8', errors='ignore')

# Find logo images in header
for m in re.finditer(r'<img[^>]+src=[\'"]([^\'"]+Risorsa[^\'"]+)[\'"][^>]*>', html):
    pos = m.start()
    chunk = html[max(0, pos-200):min(len(html), pos+300)]
    print("--- LOGO MATCH ---")
    print(chunk)
