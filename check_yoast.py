import pymysql

conn = pymysql.connect(
    host='148.251.75.142',
    user='afterbit-test100',
    password='33fvBnIVpx0PGnLhxTXT',
    database='afterbit-test100',
    port=3306,
    charset='utf8mb4'
)
cur = conn.cursor()

# Check Yoast tables
cur.execute("SHOW TABLES LIKE '%yoast%'")
tables = cur.fetchall()
print("Yoast tables:", tables)

for t in tables:
    tname = t[0]
    try:
        cur.execute(f"SELECT count(*) FROM {tname} WHERE permalink LIKE '%visionity%' OR canonical LIKE '%visionity%'")
        print(f"{tname} with visionity:", cur.fetchone()[0])
    except Exception as e:
        pass

# Check postmeta yoast
cur.execute("SELECT meta_key, meta_value FROM qhaupsn_postmeta WHERE post_id = 41222 AND meta_key LIKE '%yoast%'")
for r in cur.fetchall():
    print("Postmeta yoast:", r)

conn.close()
