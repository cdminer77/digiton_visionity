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

cur.execute("SELECT option_id, option_name, option_value FROM qhaupsn_options WHERE option_value LIKE '%127.0.0.1:8080%' OR option_value LIKE '%visionity.digiton.it%'")
rows = cur.fetchall()
print(f"Options matching: {len(rows)}")
for r in rows:
    print(r[0], r[1], r[2][:120])

conn.close()
