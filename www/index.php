<?php
define('APP_NAME','Practice');
define('APP_PATH','../Practice/');
define('RUNTIME_PATH','../Runtime/');

define('APP_DEBUG',false);

define('BUILD_DIR_SECURE',true);
define('DIR_SECUER_FILENAME','default.html');
define('DIR_SECURE_CONTENT','<h1>Deny Access!!!</h1>');

ini_set('date.timezone','PRC');
ini_set('session.cookie_lifetime', 18000);
ini_set('session.gc_probability',1);
ini_set('session.gc_divisor',3000);
ini_set('session.gc_maxlifetime',18000);

require '../ThinkPHP/ThinkPHP.php';
 ?>