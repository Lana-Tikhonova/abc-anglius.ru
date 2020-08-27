<?php
if ($i==1) {
  echo '<ul>'; 
}
$title = htmlspecialchars($q['name']);
//$url = $q['module']=='index' ? '/' : '/'.$q['url'].'/';
$url='/'.$q['url'].'/';
?>
<li><<?=$u[1]!=$q['url']?"a href='$url' title='$title'":'div'?>><?=$title?></a></li>
<?php
if ($i==$num_rows) echo '</ul>';
?>