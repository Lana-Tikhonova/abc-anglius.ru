<?php

require_once(ROOT_DIR.'functions/mysql_func.php');	//функции для работы с БД
//конект с базой
mysql_connect_db();
$date = date('Y-m-d H:i:s',time()-70*86400);
//$query = "SELECT * FROM orders WHERE date<'$date' AND paid=0 AND receipt='' ORDER BY id DESC";
$query = "DELETE FROM orders WHERE date<'$date' AND paid=0 AND receipt=''";
//echo $query;
$result= mysql_query($query);
//while ($q=mysql_fetch_assoc($result)) echo $q['id'].' ';
?>