<?php
    session_start();
	$_SESSION['auth'] = isset($_SESSION['auth']) ? $_SESSION['auth'] : false;
	$_SESSION['error'] = isset($_SESSION['error']) ? $_SESSION['error'] : false;
define('LOGIN', 'admin');
define('PASSWORD', '12345');
define('DIR_TMPL', 'tmpl/');

define('HOST', 'localhost');
define('USER', 'root');
define('DB_PASSOWRD', 'root');
define('DB', 'zadanie1.loc');

define('ARCHIVE', 'archive/');