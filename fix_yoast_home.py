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
cur.execute("SELECT id, object_type, object_sub_type, permalink FROM qhaupsn_yoast_indexable WHERE permalink LIKE '%127.0.0.1%' OR canonical LIKE '%127.0.0.1%'")
rows = cur.fetchall()
print('Yoast 127.0.0.1:', rows)
if rows:
    cur.execute("UPDATE qhaupsn_yoast_indexable SET permalink = REPLACE(permalink, 'http://127.0.0.1:8080', 'https://test100.afterbit.it') WHERE permalink LIKE '%127.0.0.1%'")
    cur.execute("UPDATE qhaupsn_yoast_indexable SET canonical = REPLACE(canonical, 'http://127.0.0.1:8080', 'https://test100.afterbit.it') WHERE canonical LIKE '%127.0.0.1%'")
    conn.commit()
    print('Updated Yoast 127.0.0.1 rows!')

conn.close()
