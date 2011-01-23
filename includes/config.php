<?php
define('DEBUG', false);

# page settings
define('BASE_URL', get_site_path());
define('SITENAME', 'Example Company');

# site settings
define('USE_DATABASE', FALSE);

# email settings
define('FROM_EMAIL_ADDRESS', '');

# mysql database settings
if (USE_DATABASE) {
	if (!strstr(BASE_URL, 'projects')) {
		define('DB_HOST', '');
		define('DB_USER', '');
		define('DB_PASS', '');
		define('DB_DATABASE', '');
	} else {
		define('DB_HOST', '127.0.0.1');
		define('DB_USER', 'root');
		define('DB_PASS', 'admin');
		define('DB_DATABASE', 'admin_test');
	}
	connect_database();
}

# data paths
define('EXPORT_PATH', get_root_path().'data/exports/');
