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
cur.execute("SELECT meta_key, meta_value FROM qhaupsn_postmeta WHERE post_id = 41222 AND (meta_value LIKE '%visionity%' OR meta_value LIKE '%127.0.0.1%')")
for k, v in cur.fetchall():
    print("KEY:", k)
    # search where visionity or 127.0.0.1 appears
    lines = [part for part in v.split('"') if 'visionity' in part or '127.0.0.1' in part]
    print("Matched strings:", lines[:5])

conn.close()
