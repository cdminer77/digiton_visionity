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

# 1. Update post_content in qhaupsn_posts
replacements = [
    ('https://visionity.digiton.it/contact-us/', 'https://test100.afterbit.it/contact-us-2/'),
    ('https://visionity.digiton.it/contact-us', 'https://test100.afterbit.it/contact-us-2/'),
    ('https://visionity.digiton.it', 'https://test100.afterbit.it'),
    ('http://127.0.0.1:8080', 'https://test100.afterbit.it'),
]

for old, new in replacements:
    cur.execute("UPDATE qhaupsn_posts SET post_content = REPLACE(post_content, %s, %s) WHERE post_content LIKE %s", (old, new, f"%{old}%"))
    print(f"Posts post_content replaced '{old}': {cur.rowcount} rows")

# 2. Update _elementor_data in qhaupsn_postmeta
meta_replacements = [
    ('https://visionity.digiton.it/contact-us/', 'https://test100.afterbit.it/contact-us-2/'),
    ('https:\\/\\/visionity.digiton.it\\/contact-us\\/', 'https:\\/\\/test100.afterbit.it\\/contact-us-2\\/'),
    ('https://visionity.digiton.it/contact-us', 'https://test100.afterbit.it/contact-us-2/'),
    ('https:\\/\\/visionity.digiton.it\\/contact-us', 'https:\\/\\/test100.afterbit.it\\/contact-us-2/'),
    ('https://visionity.digiton.it', 'https://test100.afterbit.it'),
    ('https:\\/\\/visionity.digiton.it', 'https:\\/\\/test100.afterbit.it'),
    ('http://127.0.0.1:8080', 'https://test100.afterbit.it'),
    ('http:\\/\\/127.0.0.1:8080', 'https:\\/\\/test100.afterbit.it'),
]

for old, new in meta_replacements:
    cur.execute("UPDATE qhaupsn_postmeta SET meta_value = REPLACE(meta_value, %s, %s) WHERE meta_key = '_elementor_data' AND meta_value LIKE %s", (old, new, f"%{old}%"))
    print(f"Postmeta _elementor_data replaced '{old}': {cur.rowcount} rows")

conn.commit()
conn.close()
