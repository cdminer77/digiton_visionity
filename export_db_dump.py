import pymysql
import os
import datetime

db_host = '148.251.75.142'
db_user = 'afterbit-test100'
db_pass = '33fvBnIVpx0PGnLhxTXT'
db_name = 'afterbit-test100'

conn = pymysql.connect(
    host=db_host,
    user=db_user,
    password=db_pass,
    database=db_name,
    port=3306,
    charset='utf8mb4'
)

output_file = 'database_visionity_test100.sql'
print(f"Exporting WordPress tables from {db_name} to {output_file}...")

cur = conn.cursor()
cur.execute("SHOW TABLES LIKE 'qhaupsn_%'")
tables = [row[0] for row in cur.fetchall()]

with open(output_file, 'w', encoding='utf-8') as f:
    f.write(f"-- Visionity WordPress Database Dump (test100.afterbit.it)\n")
    f.write(f"-- Generated: {datetime.datetime.now().isoformat()}\n")
    f.write(f"-- Host: {db_host} Database: {db_name}\n")
    f.write(f"-- Prefix: qhaupsn_\n\n")
    f.write("SET NAMES utf8mb4;\n")
    f.write("SET FOREIGN_KEY_CHECKS = 0;\n\n")

    for table in tables:
        print(f"Dumping table {table}...")
        cur.execute(f"SHOW CREATE TABLE `{table}`")
        create_table = cur.fetchone()[1]
        f.write(f"DROP TABLE IF EXISTS `{table}`;\n")
        f.write(f"{create_table};\n\n")

        cur.execute(f"SELECT * FROM `{table}`")
        rows = cur.fetchall()
        if not rows:
            continue

        columns = [desc[0] for desc in cur.description]
        col_names = ", ".join([f"`{c}`" for c in columns])

        batch_size = 100
        for i in range(0, len(rows), batch_size):
            batch = rows[i:i + batch_size]
            values_list = []
            for row in batch:
                row_vals = []
                for val in row:
                    if val is None:
                        row_vals.append("NULL")
                    elif isinstance(val, (int, float)):
                        row_vals.append(str(val))
                    elif isinstance(val, bytes):
                        row_vals.append(f"0x{val.hex()}")
                    else:
                        escaped = conn.escape_string(str(val))
                        row_vals.append(f"'{escaped}'")
                values_list.append(f"({', '.join(row_vals)})")
            f.write(f"INSERT INTO `{table}` ({col_names}) VALUES\n" + ",\n".join(values_list) + ";\n")
        f.write("\n")

    f.write("SET FOREIGN_KEY_CHECKS = 1;\n")

conn.close()
# Remove old dump if exists
if os.path.exists('database_dump_test100.sql'):
    os.remove('database_dump_test100.sql')
print(f"Export finished successfully! File size: {os.path.getsize(output_file)} bytes")
