import urllib.request
import re
import json

url = 'http://127.0.0.1:8080/installer.php'
resp = urllib.request.urlopen(url)
html = resp.read().decode('utf-8', errors='ignore')

action = re.search(r'action="([^"]+)"', html).group(1)
csrf = re.search(r'name="ctrl_csrf_token"\s+value="([^"]+)"', html).group(1)

step1_url = f"{action}?ctrl_action=ctrl-step1&ctrl_csrf_token={csrf}&step_action=init"
resp1 = urllib.request.urlopen(step1_url)
html1 = resp1.read().decode('utf-8', errors='ignore')

with open('step1_rendered.html', 'w', encoding='utf-8') as f:
    f.write(html1)

print("Saved step1_rendered.html, size:", len(html1))

m_form = re.search(r'<form[^>]+id=["\']s1-input-form["\'][^>]*>([\s\S]*?)</form>', html1)
if m_form:
    form_html = m_form.group(0)
    print("Found s1-input-form!")
    
    # Extract all inputs
    inputs = {}
    for inp in re.findall(r'<input[^>]+>', form_html):
        name_m = re.search(r'name=["\']([^"\']+)["\']', inp)
        val_m = re.search(r'value=["\']([^"\']*)["\']', inp)
        type_m = re.search(r'type=["\']([^"\']+)["\']', inp)
        if name_m:
            name = name_m.group(1)
            val = val_m.group(1) if val_m else ''
            itype = type_m.group(1).lower() if type_m else 'text'
            if itype in ('checkbox', 'radio'):
                if 'checked' in inp:
                    inputs[name] = val
            else:
                inputs[name] = val
                
    # Extract all selects
    for sel in re.findall(r'<select[^>]+name=["\']([^"\']+)["\'][^>]*>([\s\S]*?)</select>', form_html):
        sel_name = sel[0]
        # find selected option
        opt = re.search(r'<option[^>]+value=["\']([^"\']*)["\'][^>]*selected', sel[1])
        if opt:
            inputs[sel_name] = opt.group(1)
        else:
            first_opt = re.search(r'<option[^>]+value=["\']([^"\']*)["\']', sel[1])
            if first_opt:
                inputs[sel_name] = first_opt.group(1)
                
    print("Extracted fields count:", len(inputs))
    for k, v in sorted(inputs.items()):
        print(f"  {k} = {v}")
else:
    print("s1-input-form not found!")
