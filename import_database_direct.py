import gzip
import subprocess
import glob
import os
import sys

base_dir = r"c:\Users\Utente\.gemini\antigravity\scratch\visionity_digiton\local_staging"
dump_files = glob.glob(os.path.join(base_dir, "dup-installer", "dup_descriptors_*", "db_dumps", "*.sql.gz"))

if not dump_files:
    # Try looking for uncompressed .sql
    dump_files = glob.glob(os.path.join(base_dir, "dup-installer", "dup_descriptors_*", "db_dumps", "*.sql"))

if not dump_files:
    print("Error: could not find SQL dump file!")
    sys.exit(1)

dump_file = dump_files[0]
print(f"Found SQL dump: {dump_file} ({os.path.getsize(dump_file)} bytes)")

# Decompress if needed
if dump_file.endswith(".gz"):
    sql_path = dump_file[:-3]
    print(f"Decompressing to {sql_path}...")
    with gzip.open(dump_file, "rb") as f_in, open(sql_path, "wb") as f_out:
        f_out.write(f_in.read())
    print(f"Decompressed SQL size: {os.path.getsize(sql_path)} bytes")
else:
    sql_path = dump_file

mysql_exe = r"C:\xampp\mysql\bin\mysql.exe"
print(f"Importing {sql_path} into visionity_staging database...")

with open(sql_path, "r", encoding="utf-8", errors="ignore") as sql_in:
    proc = subprocess.Popen([mysql_exe, "-u", "root", "visionity_staging"], stdin=sql_in, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    stdout, stderr = proc.communicate()

if proc.returncode != 0:
    print("MySQL Error:", stderr.decode("utf-8", errors="ignore"))
else:
    print("MySQL Import SUCCESSFUL!")
