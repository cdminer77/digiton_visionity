import subprocess

sql = """
USE visionity_staging;
UPDATE qhaupsn_options 
SET option_value = 'a:7:{i:0;s:36:"contact-form-7/wp-contact-form-7.php";i:1;s:33:"duplicate-post/duplicate-post.php";i:2;s:25:"duplicator/duplicator.php";i:3;s:23:"elementor/elementor.php";i:4;s:23:"revslider/revslider.php";i:5;s:25:"trx_addons/trx_addons.php";i:6;s:24:"wordpress-seo/wp-seo.php";}'
WHERE option_name = 'active_plugins';
"""

p = subprocess.Popen([r"C:\xampp\mysql\bin\mysql.exe", "-u", "root"], stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
stdout, stderr = p.communicate(sql.encode('utf-8'))
print("Result:", stderr.decode('utf-8', errors='ignore') or "OK")
