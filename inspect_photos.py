import os
from PIL import Image

photo_dir = r"c:\Users\Utente\.gemini\antigravity\scratch\visionity_digiton\downloaded_photos"
for f in sorted(os.listdir(photo_dir)):
    p = os.path.join(photo_dir, f)
    if os.path.isfile(p):
        try:
            with Image.open(p) as img:
                print(f"{f}: {img.size} ({img.format}), {os.path.getsize(p)/(1024*1024):.2f} MB")
        except Exception as e:
            print(f"{f}: error {e}")
