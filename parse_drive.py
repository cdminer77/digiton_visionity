import re
import json

path = r'C:\Users\Utente\.gemini\antigravity\brain\345ec628-2f43-45db-9ed8-90b0e2cdc263\.system_generated\steps\627\content.md'
with open(path, 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

# Look for image filenames
images = re.findall(r'[\w,\s-]+\.(?:jpg|jpeg|png|webp|svg)', text, re.IGNORECASE)
print("Unique image names found:", set(images))

# Look for drive items / IDs
# Typically Google Drive embeds items in JS variables like [[..., "id", "name", ...]]
items = re.findall(r'\["([-\w]{25,})",\s*\["([^"]+)"', text)
print("Items with IDs:", items[:20])

# Also search for any occurrence of .png or .jpg with surrounding context
for m in re.finditer(r'([^\'"\\<>]{1,50}\.(?:jpg|jpeg|png|webp))', text, re.IGNORECASE):
    print("Match:", m.group(0))
