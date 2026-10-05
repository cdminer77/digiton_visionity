import os
import re
import urllib.request

output_dir = r"c:\Users\Utente\.gemini\antigravity\scratch\visionity_digiton\downloaded_photos"
os.makedirs(output_dir, exist_ok=True)

files = [
    ("Conversazione in lounge aziendale moderno (5).png", "1qvJa4LPsDWA_KRlFSoXPaFOW0ny_Y_wY"),
    ("Corridoio futuristico verso lo skyline urbano (6).png", "1tYxoPXklzvH4W_C0i6VtevK_1Ur-R0sA"),
    ("grok-image-24344745-62cb-4cb4-beb9-c291a3b80cbd.jpg", "1QFTYoOmTOhvSlcwIjVYRlvqfp7PY7jw0"),
    ("grok-image-d060ee36-7a88-4e24-ba91-d316c2b57e82.jpg", "1YCU1HU5BEJYV6wM32iWLRmf9VD-b8U8n"),
    ("grok-image-f6b69c4d-a94a-404c-9dec-b370e8769aa8.jpg", "1405QfuRuLdJYzEC-xA01Q4gJzHPF7FLS"),
    ("grok-image-fe14476a-2ccd-4294-8ec4-ba01a76a66df.jpg", "14JaVG77xnocy8mfqtceAir3rIm2yFSQh"),
    ("Networking nell'elegante lounge panoramica (7).png", "1NnRJ7IWe9YzmdZ9EYyhxFAlg9daGmx9k"),
    ("Portale di luce sulla citta (8).png", "1QuklZvPoNw4IRHz5yZa9t09cwTFW-278"),
    ("Riunione dirigenziale con vista sulla citta (2).png", "146jMXzMBT2PLfu0dzKdfvMR__NuN1HQk"),
    ("Riunione dirigenziale in ufficio moderno (3).png", "1a090M69tzOtsVJQ5pESM8uXbrh_fcRb-"),
    ("Strategia esecutiva sulla scrivania marmorea (9).png", "19LEbfJK_awJ_6HPUF2kS85Rmzi5um1sZ"),
    ("Ufficio al tramonto con skyline urbano (4).png", "1a0I6WZ7rD6qmyQFh5rhsvPpHC418eKTR"),
    ("Verso l'orizzonte della citta (1).png", "1yKj8MmP9bYwXG3LuakudFHqFkBG1TAVR")
]

headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'}

for fname, fid in files:
    url = f"https://lh3.googleusercontent.com/d/{fid}"
    target = os.path.join(output_dir, fname)
    print(f"Downloading {fname} ({fid})...")
    req = urllib.request.Request(url, headers=headers)
    try:
        with urllib.request.urlopen(req) as resp:
            content = resp.read()
            with open(target, "wb") as f:
                f.write(content)
            print(f" Saved {len(content)} bytes to {fname}")
    except Exception as e:
        print(f" Failed {fname}: {e}")

print("All downloads complete!")
