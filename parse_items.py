import re
import urllib.request
import os

path = r'C:\Users\Utente\.gemini\antigravity\brain\345ec628-2f43-45db-9ed8-90b0e2cdc263\.system_generated\steps\627\content.md'
with open(path, 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

pattern = r'aria-label="([^"]+?)\s+Image Shared"[^>]*?ssk=[\'"]5:auSv138:([a-zA-Z0-9_-]+)'
matches = re.findall(pattern, text)
print(f"Found {len(matches)} files:")
for name, fid in matches:
    print(f"- {name}: {fid}")

# Also check any other pattern if there are more
pattern2 = r'aria-label="([^"]+?\.(?:png|jpg|jpeg|webp))"[^>]*?ssk=[\'"]5:auSv138:([a-zA-Z0-9_-]+)'
matches2 = re.findall(pattern2, text)
print(f"Found {len(matches2)} with pattern 2")
