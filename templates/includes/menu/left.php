<?php
$title = htmlspecialchars($q['name']);
$url = $q['module']=='index' ? '/' : '/'.$q['url'].'/';

if($u[1]!=$q['url']) echo "<a class='leftitem' href='$url' title='$title'>$title</a>";
else {
//  $r=mysql_select('select id,name from '.$q['module'].'_categories where display=1 order by rank desc','rows_id');
  $q='
	select mc.id,mc.name from '.$q['module'].'_categories mc
	left join '.$q['module'].' m on m.category=mc.id
	where m.display=1 and mc.display=1
	order by mc.rank desc,mc.id asc,m.rank desc';
  $r=mysql_select($q,'rows_id');

  if(!$r) {echo "<div class='leftitem'>$title</div>";}
  else {
    if($u[2]){echo "<div class='leftitem parent'><a href='$url' title='$title' class='big'>$title</a>";}
    else {echo "<div class='leftitem parent'><span>$title</span>";}
    foreach ($r as $k=>$v) {
      if($k==$u[2]){echo '<div><span>'.$v['name'].'</span><img src="/templates/images/rarrow.png"></div>';}
      else {echo '<a href="#category'.$v['id'].'"><span>'.$v['name'].'</span><img src="/templates/images/darrow.png"></a>';}
    }
    echo '</div>';
  }
}
?>
