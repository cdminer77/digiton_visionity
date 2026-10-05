import os
from PIL import Image

root = r'c:\Users\Utente\.gemini\antigravity\scratch\visionity_digiton\local_staging\wp-content\uploads\2024\08'
for name in ['Risorsa-9.png', 'Risorsa-10.png', 'Risorsa-11.png', 'Risorsa-12.png']:
    p = os.path.join(root, name)
    img = Image.open(p)
    img_rgb = img.convert('RGBA')
    pixels = [px for px in img_rgb.getdata() if px[3] > 50]
    if pixels:
        avg_r = sum(px[0] for px in pixels) / len(pixels)
        avg_g = sum(px[1] for px in pixels) / len(pixels)
        avg_b = sum(px[2] for px in pixels) / len(pixels)
        brightness = (avg_r*299 + avg_g*587 + avg_b*114) / 1000
        desc = "LIGHT/WHITE" if brightness > 128 else "DARK/BLACK"
        print(f"{name}: size={img.size}, Avg RGB=({avg_r:.0f},{avg_g:.0f},{avg_b:.0f}), Brightness={brightness:.0f} ({desc})")
