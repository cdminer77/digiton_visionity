import urllib.request
import urllib.parse
import json
import glob
import time
import os
import sys

def log(msg):
    print(f"[{time.strftime('%H:%M:%S')}] {msg}", flush=True)

base_dir = r"c:\Users\Utente\.gemini\antigravity\scratch\visionity_digiton\local_staging"
desc_dirs = glob.glob(os.path.join(base_dir, "dup-installer", "dup_descriptors_*"))
desc_dir = desc_dirs[0]
csrf_file = os.path.join(desc_dir, "installer-csrf.txt")
with open(csrf_file, "r", encoding="utf-8") as f:
    tokens = json.load(f)

installer_url = "http://127.0.0.1:8080/dup-installer/main.installer.php"

def call_ajax(action, token_key, extra_data=None, timeout=300):
    token = tokens.get(token_key, "")
    post_fields = {
        "ctrl_action": "ajax",
        "ajax_action": action,
        "ajax_csrf_token": token
    }
    if extra_data:
        post_fields.update(extra_data)
        
    encoded_data = urllib.parse.urlencode(post_fields).encode("utf-8")
    req = urllib.request.Request(installer_url, data=encoded_data, headers={
        "User-Agent": "Mozilla/5.0",
        "X-Requested-With": "XMLHttpRequest"
    })
    
    with urllib.request.urlopen(req, timeout=timeout) as resp:
        res_text = resp.read().decode("utf-8", errors="ignore")
        try:
            return json.loads(res_text)
        except Exception:
            return {"raw": res_text}

# STEP 0: VALIDATE
log("--- STEP 0: Validating installer environment ---")
res_val = call_ajax("validate", "_DUPX_CSRF_ajax_actionvalidate")
log(f"Validate Response: success={res_val.get('success')}")
act_val = res_val.get('actionData', {})
log(f"Validate mainLevel: {act_val.get('mainLevel')}")

# STEP 1: SET PARAMS
log("--- STEP 1: Setting Parameters ---")
s1_data = {
    "ctrl-step1": tokens.get("_DUPX_CSRF_ctrl-step1", ""),
    "install-type": "0",
    "view_mode": "basic",
    "dbaction": "empty",
    "dbhost": "localhost",
    "dbname": "visionity_staging",
    "dbuser": "root",
    "dbpass": "",
    "archive_action": "donothing",
    "accept-warnings": "1",
    "accept-warnings_is_input": "1",
    "blogname": "Visionity",
    "subsite_id": "1"
}
res_s1 = call_ajax("sparam_s1", "_DUPX_CSRF_ajax_actionsparam_s1", s1_data)
log(f"Step 1 Response: success={res_s1.get('success')}")

# STEP 2: DB GUNZIP
log("--- STEP 2: DB Gunzip ---")
res_gunzip = call_ajax("dbgunzip", "_DUPX_CSRF_ajax_actiondbgunzip")
log(f"DB Gunzip: {res_gunzip.get('success')}")

# STEP 3: EXTRACTION
log("--- STEP 3: Extraction ---")
res_ext = call_ajax("extract", "_DUPX_CSRF_ajax_actionextract")
log(f"Extraction response: {res_ext.get('success')}, actionData={res_ext.get('actionData')}")
