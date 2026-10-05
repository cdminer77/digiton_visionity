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
cur.execute("SELECT ID, post_title, post_name, post_status, post_type FROM qhaupsn_posts WHERE ID = 41222")
row = cur.fetchone()
print("Post 41222:", row)

cur.execute("SELECT ID, post_title, post_name, post_status, post_type FROM qhaupsn_posts WHERE post_title LIKE '%membership%' OR post_name LIKE '%membership%'")
for r in cur.fetchall():
    print("Found membership:", r)

cur.execute("SELECT p.ID, p.post_title, m.meta_key, m.meta_value FROM qhaupsn_posts p JOIN qhaupsn_postmeta m ON p.ID = m.post_id WHERE p.ID = 41754")
for r in cur.fetchall():
    print("Menu item meta:", r)

conn.close()
