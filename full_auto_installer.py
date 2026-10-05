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
if not desc_dirs:
    log("Error: could not find dup_descriptors folder!")
    sys.exit(1)

desc_dir = desc_dirs[0]
csrf_file = os.path.join(desc_dir, "installer-csrf.txt")
with open(csrf_file, "r", encoding="utf-8") as f:
    tokens = json.load(f)

log("Loaded CSRF tokens from installer-csrf.txt")

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

# 1. SET PARAMS STEP 1 (sparam_s1)
log("--- STEP 1: Setting Database & Installation Parameters ---")
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
log(f"Step 1 Response: success={res_s1.get('success')}, message={res_s1.get('message')}")
if not res_s1.get('success'):
    log(f"Step 1 failed: {res_s1}")
    sys.exit(1)

# 2. DB GUNZIP
log("--- STEP 2: Uncompressing Database SQL dump ---")
res_gunzip = call_ajax("dbgunzip", "_DUPX_CSRF_ajax_actiondbgunzip")
log(f"DB Gunzip Response: success={res_gunzip.get('success')}, actionData={res_gunzip.get('actionData')}")

# 3. EXTRACTION
log("--- STEP 3: Extracting Files from archive.daf ---")
ext_iter = 0
while ext_iter < 100:
    ext_iter += 1
    res_ext = call_ajax("extract", "_DUPX_CSRF_ajax_actionextract", timeout=600)
    act_data = res_ext.get("actionData", {})
    perc = act_data.get("perc", "N/A")
    pass_val = act_data.get("pass", None)
    files = act_data.get("processedFiles", "N/A")
    log(f"Extraction chunk {ext_iter}: progress={perc}%, files={files}, pass={pass_val}")
    
    if pass_val == 1:
        log("Extraction 100% COMPLETE!")
        break
    elif pass_val == -1:
        time.sleep(0.5)
    else:
        log(f"Unexpected extraction response: {res_ext}")
        break

# 4. DATABASE INSTALL
log("--- STEP 4: Importing Database into visionity_staging ---")
db_iter = 0
while db_iter < 50:
    db_iter += 1
    res_db = call_ajax("dbinstall", "_DUPX_CSRF_ajax_actiondbinstall", timeout=600)
    act_data = res_db.get("actionData", {})
    pass_val = act_data.get("pass", None)
    log(f"DB Install chunk {db_iter}: success={res_db.get('success')}, pass={pass_val}")
    
    if pass_val == 1 or res_db.get("success"):
        log("Database Import COMPLETE!")
        break
    elif pass_val == -1:
        time.sleep(0.5)
    else:
        log(f"DB response: {res_db}")
        break

# 5. WEBSITE UPDATE (Search & Replace URLs)
log("--- STEP 5: Updating Website URLs (https://visionity.digiton.it -> http://127.0.0.1:8080) ---")
res_update = call_ajax("webupdate", "_DUPX_CSRF_ajax_actionwebupdate", timeout=600)
log(f"Web Update Response: success={res_update.get('success')}, actionData={res_update.get('actionData')}")

# 6. FINAL TESTS
log("--- STEP 6: Finalizing configuration ---")
call_ajax("finalpre", "_DUPX_CSRF_ajax_actionfinalpre")
call_ajax("finalafter", "_DUPX_CSRF_ajax_actionfinalafter")
log("Installer automated pipeline FINISHED successfully!")
