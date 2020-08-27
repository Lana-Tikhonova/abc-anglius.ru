<?php

require_once(ROOT_DIR.'functions/mysql_func.php');	//функции для работы с БД
//конект с базой
mysql_connect_db();
$date = date('Y-m-d H:i:s',time()-61*86400);
//$query = "SELECT * FROM orders WHERE date<'$date' AND paid=0 AND receipt='' ORDER BY id DESC";

$where = " date<'$date' AND paid=0 AND receipt='' ";

echo 'Будет удалено ' . mysql_select("SELECT id FROM `orders` WHERE {$where}", 'num_rows') . ' записей.<br><br>';
$query = "DELETE FROM orders WHERE {$where}";
//echo $query;
$result= mysql_query($query);
$error = mysql_error();
if ($error) {
    var_dump($error);
}
// checked
//while ($q=mysql_fetch_assoc($result)) echo $q['id'].' ';