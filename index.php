<?php
if(file_exists('./Install/installed.lock'))
{
define('THINK_PATH', './ThinkPHP/');
define('APP_PATH', './LeiBlog/');
define('APP_NAME','LeiBlog');
define('APP_DEBUG',true);
require THINK_PATH.'ThinkPHP.php';
}
else{
	header('Location:./Install');
}

?>