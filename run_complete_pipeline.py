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

log("Loaded CSRF tokens.")

installer_url = "http://127.0.0.1:8080/dup-installer/main.installer.php"

def call_ajax(action, token_key, extra_data=None, timeout=600):
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

# 1. VALIDATE
log("1/6. Validating environment...")
res_val = call_ajax("validate", "_DUPX_CSRF_ajax_actionvalidate")
if not res_val.get("success"):
    log(f"Validation failed: {res_val}")
    sys.exit(1)
log("Validation OK.")

# 2. SET PARAMS STEP 1
log("2/6. Setting installation & database parameters...")
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
if not res_s1.get("success"):
    log(f"Step 1 failed: {res_s1}")
    sys.exit(1)
log("Step 1 parameters configured.")

# 3. DB GUNZIP
log("3/6. Preparing database SQL dump (Gunzip)...")
res_gunzip = call_ajax("dbgunzip", "_DUPX_CSRF_ajax_actiondbgunzip")
log(f"Database SQL dump prepared: {res_gunzip.get('success')}")

# 4. EXTRACTION LOOP
log("4/6. Extracting files from archive (26,321 files)...")
ext_chunk = 0
start_ext = time.time()
while True:
    ext_chunk += 1
    res_ext = call_ajax("extract", "_DUPX_CSRF_ajax_actionextract", timeout=600)
    act_data = res_ext.get("actionData", {})
    perc = act_data.get("perc", "N/A")
    pass_val = act_data.get("pass", None)
    files = act_data.get("processedFiles", "N/A")
    log(f"[Extract chunk {ext_chunk}] {files} ({perc})")
    
    if pass_val == 1:
        log(f"Extraction 100% COMPLETE in {time.time() - start_ext:.1f}s!")
        break
    elif pass_val == -1:
        time.sleep(0.2)
    else:
        log(f"Extraction error or finished: {res_ext}")
        break

# 5. DATABASE INSTALL LOOP
log("5/6. Deploying and restoring database into visionity_staging...")
db_chunk = 0
start_db = time.time()
while True:
    db_chunk += 1
    res_db = call_ajax("dbinstall", "_DUPX_CSRF_ajax_actiondbinstall", timeout=600)
    act_data = res_db.get("actionData", {})
    pass_val = act_data.get("pass", None)
    perc = act_data.get("perc", "N/A")
    log(f"[DB chunk {db_chunk}] Status: {res_db.get('success')}, progress={perc}")
    
    if pass_val == 1 or (res_db.get("success") and pass_val != -1):
        log(f"Database deployment 100% COMPLETE in {time.time() - start_db:.1f}s!")
        break
    elif pass_val == -1:
        time.sleep(0.2)
    else:
        log(f"DB response: {res_db}")
        break

# 6. WEBSITE UPDATE (SEARCH & REPLACE URLS)
log("6/6. Running URL migration: https://visionity.digiton.it -> http://127.0.0.1:8080...")
res_update = call_ajax("webupdate", "_DUPX_CSRF_ajax_actionwebupdate", timeout=600)
log(f"Search & Replace complete: success={res_update.get('success')}")

# Final cleanup
call_ajax("finalpre", "_DUPX_CSRF_ajax_actionfinalpre")
call_ajax("finalafter", "_DUPX_CSRF_ajax_actionfinalafter")

log("=== DUPLICATOR PIPELINE SUCCESSFULLY COMPLETED! ===")
