import urllib.request
import re
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

# Get the menu assigned to primary/main
cur.execute("SELECT option_value FROM qhaupsn_options WHERE option_name = 'theme_mods_paragon'")
theme_mods = cur.fetchone()
print("theme_mods_paragon:", theme_mods[0] if theme_mods else "None")

# Check all active menu items in menu 284 (MainMenuSemplificato)
cur.execute("""
SELECT tr.object_id, p.post_title, m_obj.meta_value, target_p.post_title, target_p.post_name, target_p.post_status
FROM qhaupsn_term_relationships tr
JOIN qhaupsn_posts p ON tr.object_id = p.ID
LEFT JOIN qhaupsn_postmeta m_obj ON p.ID = m_obj.post_id AND m_obj.meta_key = '_menu_item_object_id'
LEFT JOIN qhaupsn_posts target_p ON m_obj.meta_value = target_p.ID
WHERE tr.term_taxonomy_id = 284
ORDER BY p.menu_order ASC
""")
items = cur.fetchall()
print("Items in MainMenuSemplificato:")
for item in items:
    print(item)
    slug = item[4]
    if slug:
        url = f"https://test100.afterbit.it/{slug}/"
        try:
            req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
            res = urllib.request.urlopen(req)
            print(f"  -> {url}: {res.getcode()}")
        except Exception as e:
            print(f"  -> {url} ERROR: {e}")

conn.close()
