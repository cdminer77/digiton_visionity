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

# Check all post IDs where meta_value contains 'visionity.digiton.it/contact-us/'
cur.execute("""
SELECT p.ID, p.post_title, p.post_type, p.post_status
FROM qhaupsn_posts p
JOIN qhaupsn_postmeta m ON p.ID = m.post_id
WHERE m.meta_key = '_elementor_data' AND m.meta_value LIKE '%visionity.digiton.it/contact-us/%'
GROUP BY p.ID
""")
for r in cur.fetchall():
    print(r)

conn.close()
