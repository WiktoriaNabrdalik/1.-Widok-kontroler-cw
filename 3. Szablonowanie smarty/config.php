<?php
define('_SERVER_NAME', 'localhost');
define('_SERVER_URL', 'http://'._SERVER_NAME);
define('_APP_ROOT', '/3.Szablonowanie_smarty');
define('_APP_URL', _SERVER_URL._APP_ROOT);
define("_ROOT_PATH", dirname(__FILE__));

//gdy korzysta się z bibliotek szablonowania funkcja out(&$param) nie jest już potrzebna
?>