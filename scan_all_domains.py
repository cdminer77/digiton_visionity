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

cur.execute("SHOW TABLES")
tables = [r[0] for r in cur.fetchall()]

for t in tables:
    cur.execute(f"DESCRIBE {t}")
    text_cols = [r[0] for r in cur.fetchall() if any(tp in r[1].lower() for tp in ['varchar', 'text'])]
    for col in text_cols:
        try:
            cur.execute(f"SELECT COUNT(*) FROM {t} WHERE {col} LIKE '%visionity.digiton.it%'")
            c1 = cur.fetchone()[0]
            cur.execute(f"SELECT COUNT(*) FROM {t} WHERE {col} LIKE '%127.0.0.1:8080%'")
            c2 = cur.fetchone()[0]
            if c1 > 0 or c2 > 0:
                print(f"{t}.{col}: visionity={c1}, 127.0.0.1={c2}")
        except Exception as e:
            pass

conn.close()
