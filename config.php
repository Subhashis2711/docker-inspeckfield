<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

if(!isset($_SESSION)){
	session_start();
}
define('PHP_LOG', true);
define('PHP_DEBUG_POST', true);
define('MODEL_LOG', true);
define('MODEL_LOG_TIME', true);
define('DB_HOST', '192.168.29.237');
define('DB_PORT', '3306');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'mindfire');
define('DB_DATABASE', 'bridgenew');

// Report generation
define('MAP_FILE_PATH', 'MasterTemplates/');
define('MAP_XLSX_FILE_NAME', 'RCTOutput_Mapping_Master (modified).xlsx');
define('MAP_DOCX_FILE_NAME', 'ReportOutput_Mapping_Master.docx');
define('OUTPUT_FILE_PATH', 'OutputResults/');
define('GALLERY_UPLOAD_PATH', 'assets/images/gallery/');

//API
define('JWT_SECRET', '3ED092FFA67CB7864A2008E7DF4EE97CEF5686C36BE103128161DD25899CC87E');
// define('API_URL', 'https://mfdev.inspektech.com/api/');
define('API_URL', 'http://inspektech-git.loc:90/api/');

define('BASIC_AUTH_USER', 'inspektech');
define('BASIC_AUTH_PASSWORD', 'Myyoxhhdhhwoie)kayzYL}d'); 
define('SMTP_USERNAME', 'subh@inspektech.net');
define('SMTP_PASSWORD', 'subh!8345!');
