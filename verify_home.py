import urllib.request

html = urllib.request.urlopen('http://127.0.0.1:8080/').read().decode('utf-8', errors='ignore')

print('HTML length:', len(html))

# Check for new images in HTML
for num in ['Verso-lorizzonte', 'Riunione-dirigenziale', 'Ufficio-al-tramonto', 'Corridoio-futuristico', 'Networking-nellelegante', 'Portale-di-luce']:
    found = num.lower() in html.lower()
    print(f"Checking {num}: {'FOUND' if found else 'NOT FOUND'}")

# Check if fake reviews and fake blog were removed
print('Fake blog (Latest news) present:', 'Latest news' in html)
print('Fake testimonials (Why leaders choose Visionity) present:', 'Why leaders choose Visionity' in html)
