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

cur.execute("SELECT meta_id, meta_value FROM qhaupsn_postmeta WHERE post_id = 41222 AND meta_key = '_elementor_data'")
row = cur.fetchone()
if row:
    meta_id, val = row
    print("Found _elementor_data, length:", len(val))
    # Replace visionity.digiton.it -> test100.afterbit.it
    # Also handle escaped slashes https:\/\/visionity.digiton.it -> https:\/\/test100.afterbit.it
    # and 127.0.0.1:8080
    new_val = val.replace('https://visionity.digiton.it', 'https://test100.afterbit.it')
    new_val = new_val.replace('https:\\/\\/visionity.digiton.it', 'https:\\/\\/test100.afterbit.it')
    new_val = new_val.replace('http://127.0.0.1:8080', 'https://test100.afterbit.it')
    new_val = new_val.replace('http:\\/\\/127.0.0.1:8080', 'https:\\/\\/test100.afterbit.it')
    
    if new_val != val:
        cur.execute("UPDATE qhaupsn_postmeta SET meta_value = %s WHERE meta_id = %s", (new_val, meta_id))
        conn.commit()
        print("Updated _elementor_data for post 41222!")
    else:
        print("No changes needed in _elementor_data")

conn.close()
