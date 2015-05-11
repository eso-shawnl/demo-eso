<?php

// HTTP
define('HTTP_SERVER', 'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/');
define('HTTPS_SERVER', 'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/');
define('HTTP_CATALOG', 'http://www.eso.nz/');
define('HTTPS_CATALOG', 'http://www.eso.nz/');
// DIR

define('DIR_APPLICATION', '/var/www/html/event_admin_source_code/admin/');
define('DIR_SYSTEM', '/var/www/html/event_admin_source_code/system/');
define('DIR_LANGUAGE', '/var/www/html/event_admin_source_code/admin/language/');
define('DIR_TEMPLATE', '/var/www/html/event_admin_source_code/admin/view/template/');
define('DIR_CONFIG', '/var/www/html/event_admin_source_code/system/config/');
define('DIR_IMAGE', '/var/www/html/event_admin_source_code/image/');
define('DIR_CACHE', '/var/www/html/event_admin_source_code/system/cache/');
define('DIR_DOWNLOAD', '/var/www/html/event_admin_source_code/system/download/');
define('DIR_UPLOAD', '/var/www/html/event_admin_source_code/system/upload/');
define('DIR_LOGS', '/var/www/html/event_admin_source_code/system/logs/');
define('DIR_MODIFICATION', '/var/www/html/event_admin_source_code/system/modification/');
define('DIR_CATALOG', '/var/www/html/event_catalog_source_code/catalog/');

define('LANG', 'admin');


// DB
/*
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', '172.31.22.202');
define('DB_USERNAME', 'eso');
define('DB_PASSWORD', 'GcSY6VEaeaPswmBx');
define('DB_DATABASE', '3a_events_op');
define('DB_PREFIX', '3a_');
*/

define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'eso.c77oiajb5l6c.ap-southeast-2.rds.amazonaws.com');
define('DB_USERNAME', 'eso');
define('DB_PASSWORD', '0Y6eDUPho56Um');
define('DB_DATABASE', 'eso_events_new');
define('DB_PREFIX', '3a_');
