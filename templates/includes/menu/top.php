<?php
if ($i==1) {
  echo '<ul>';
  echo '<li><'.($u[1]!=''?"a href='/' title='ГЛАВНАЯ'":'div').'>ГЛАВНАЯ</a></li>';
}
$title = htmlspecialchars($q['name']);
//$url = $q['module']=='index' ? '/' : '/'.$q['url'].'/';
$url='/'.$q['url'].'/';
?>
<li><<?=$u[1]!=$q['url']?"a href='$url' title='$title'":'div'?>><?=$title?></a></li>
<?php
if ($i==$num_rows) echo '</ul>';
?>