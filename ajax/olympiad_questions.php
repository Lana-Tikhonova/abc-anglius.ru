<?php
require_once(ROOT_DIR.'functions/mysql_func.php');	//функции для работы с БД
$teacher=mysql_select('select teacher from olympiads where id='.$_GET['id'],'string');
if($teacher&&($teacher+0))	echo 20;
			else	echo 20;
?>