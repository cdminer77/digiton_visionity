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

# Check all items in nav menus and their target post status
query = """
SELECT p.ID, p.post_title, m_type.meta_value as type, m_obj.meta_value as obj_id, m_url.meta_value as url, target_p.post_title as target_title, target_p.post_status as target_status, target_p.post_name as target_slug
FROM qhaupsn_posts p
LEFT JOIN qhaupsn_postmeta m_type ON p.ID = m_type.post_id AND m_type.meta_key = '_menu_item_type'
LEFT JOIN qhaupsn_postmeta m_obj ON p.ID = m_obj.post_id AND m_obj.meta_key = '_menu_item_object_id'
LEFT JOIN qhaupsn_postmeta m_url ON p.ID = m_url.post_id AND m_url.meta_key = '_menu_item_url'
LEFT JOIN qhaupsn_posts target_p ON m_obj.meta_value = target_p.ID
WHERE p.post_type = 'nav_menu_item'
ORDER BY p.menu_order ASC
"""
cur.execute(query)
for r in cur.fetchall():
    print(r)

conn.close()
