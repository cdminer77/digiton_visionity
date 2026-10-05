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

# 1. Update post_status to 'publish' for 41222
cur.execute("UPDATE qhaupsn_posts SET post_status = 'publish' WHERE ID = 41222")
conn.commit()
print("Updated post 41222 to publish. Rows affected:", cur.rowcount)

# 2. Check postmeta for 41222
cur.execute("SELECT meta_key, meta_value FROM qhaupsn_postmeta WHERE post_id = 41222 AND (meta_key LIKE '%elementor%' OR meta_key LIKE '%template%')")
for k, v in cur.fetchall():
    print(f"Meta {k}: {v[:100] if v and len(v) > 100 else v}")

# 3. Check if any old domains remain in post 41222 content or meta
cur.execute("SELECT post_content FROM qhaupsn_posts WHERE ID = 41222")
content = cur.fetchone()[0]
print("Has visionity in post_content:", 'visionity' in (content or ''))
print("Has 127.0.0.1 in post_content:", '127.0.0.1' in (content or ''))

cur.execute("SELECT meta_key, meta_value FROM qhaupsn_postmeta WHERE post_id = 41222 AND (meta_value LIKE '%visionity%' OR meta_value LIKE '%127.0.0.1%')")
rows = cur.fetchall()
print(f"Postmeta rows with old domain: {len(rows)}")
for r in rows:
    print(r[0], r[1][:100])

conn.close()
