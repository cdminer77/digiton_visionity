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

# Search for https://visionity.digiton.it/contact-us/
cur.execute("SELECT post_id, meta_key FROM qhaupsn_postmeta WHERE meta_value LIKE '%visionity.digiton.it/contact-us/%'")
for r in cur.fetchall():
    print("In postmeta:", r)

cur.execute("SELECT ID, post_title, post_type FROM qhaupsn_posts WHERE post_content LIKE '%visionity.digiton.it/contact-us/%'")
for r in cur.fetchall():
    print("In posts:", r)

cur.execute("SELECT option_name FROM qhaupsn_options WHERE option_value LIKE '%visionity.digiton.it/contact-us/%'")
for r in cur.fetchall():
    print("In options:", r)

# Search for 127.0.0.1:8080 in options
cur.execute("SELECT option_name FROM qhaupsn_options WHERE option_value LIKE '%127.0.0.1:8080%'")
for r in cur.fetchall():
    print("127.0.0.1 in options:", r)

conn.close()
