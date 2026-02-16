<?php
// HTTP
define('HTTP_SERVER', 'http://' . $_SERVER['HTTP_HOST'] . '/BSD/Vajebaat/vaj_form/');
define('HTTP_BASE', 'http://' . $_SERVER['HTTP_HOST'] . '/BSD/Vajebaat/vaj_form/app/');
define('HTTP_IMAGE', 'http://' . $_SERVER['HTTP_HOST'] . '/BSD/Vajebaat/vaj_form/image/');
define('HTTP_BARCODE_EJ', HTTP_IMAGE.'barcode/ej/');
define('HTTP_BARCODE_SF', HTTP_IMAGE.'barcode/sf/');

// HTTPS
define('HTTPS_SERVER', 'http://' . $_SERVER['HTTP_HOST'] . '/BSD/Vajebaat/vaj_form/');
define('HTTPS_BASE', 'http://' . $_SERVER['HTTP_HOST'] . '/BSD/Vajebaat/vaj_form/app/');
define('HTTPS_IMAGE', 'http://' . $_SERVER['HTTP_HOST'] . '/BSD/Vajebaat/vaj_form/image/');
define('HTTPS_BARCODE_EJ', HTTP_IMAGE.'barcode/ej/');
define('HTTPS_BARCODE_SF', HTTP_IMAGE.'barcode/sf/');

// DIR
define('DIR_ROOT', getcwd() . "/");
define('DIR_APPLICATION', DIR_ROOT . 'app/');
define('DIR_SYSTEM', DIR_ROOT . 'system/');
define('DIR_DATABASE', DIR_SYSTEM .  'database/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/template/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_IMAGE', DIR_ROOT . 'image/');
define('DIR_BARCODE_EJ', DIR_IMAGE . 'ej/');
define('DIR_BARCODE_SF', DIR_IMAGE . 'sf/');
define('DIR_CACHE', DIR_SYSTEM . 'cache/');
define('DIR_DOWNLOAD', DIR_ROOT . 'download/');
define('DIR_LOGS', DIR_SYSTEM . 'logs/');
define('DIR_CATALOG', DIR_ROOT . 'catalog/');
define('FPDF_FONTPATH',DIR_ROOT . 'fpdf_font/');
define('PROXY_SERVER','192.185.28.57/proxy/');
define('CURRENT_YEAR','1439');

// DB
define('DB_DRIVER', 'mysql');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'Bharmal786#');
define('DB_DATABASE', 'waj');
define('DB_PREFIX', '_vaj_form_');
?>