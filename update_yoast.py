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

columns = ['permalink', 'canonical', 'twitter_image', 'twitter_description', 'open_graph_image', 'open_graph_description', 'open_graph_image_meta']

for col in columns:
    query = f"UPDATE qhaupsn_yoast_indexable SET {col} = REPLACE({col}, 'https://visionity.digiton.it', 'https://test100.afterbit.it') WHERE {col} LIKE '%visionity.digiton.it%'"
    cur.execute(query)
    print(f"Updated {col}: {cur.rowcount} rows")

conn.commit()
conn.close()
