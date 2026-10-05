import re

path = r'C:\Users\Utente\.gemini\antigravity\brain\345ec628-2f43-45db-9ed8-90b0e2cdc263\.system_generated\steps\627\content.md'
with open(path, 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

# Let's search for file patterns and nearby IDs
# In Google Drive, IDs are 28-44 chars alphanumeric with _ and -
# e.g. ["1VkU84...", "filename"] or ["filename", "id"]
filenames = [
    "Conversazione in lounge aziendale moderno (5).png",
    "Corridoio futuristico verso lo skyline urbano (6).png",
    "Networking nell'elegante lounge panoramica (7).png",
    "Portale di luce sulla città (8).png",
    "Riunione dirigenziale con vista sulla città (2).png",
    "Riunione dirigenziale in ufficio moderno (3).png",
    "Strategia esecutiva sulla scrivania marmorea (9).png",
    "Ufficio al tramonto con skyline urbano (4).png",
    "Verso l'orizzonte della città (1).png",
    "grok-image-24344745-62cb-4cb4-beb9-c291a3b80cbd.jpg",
    "grok-image-d060ee36-7a88-4e24-ba91-d316c2b57e82.jpg",
    "grok-image-f6b69c4d-a94a-404c-9dec-b370e8769aa8.jpg",
    "grok-image-fe14476a-2ccd-4294-8ec4-ba01a76a66df.jpg"
]

for fn in filenames:
    prefix = fn[:15]
    pos = text.find(prefix)
    if pos != -1:
        chunk = text[max(0, pos-200):min(len(text), pos+300)]
        print(f"=== {fn} ===")
        print(chunk)
        # Find drive ID pattern [a-zA-Z0-9_-]{25,}
        ids = re.findall(r'[a-zA-Z0-9_-]{28,45}', chunk)
        print("Candidate IDs:", ids)
