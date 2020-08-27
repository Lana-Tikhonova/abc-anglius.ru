<div style='margin-bottom:10px;'><?=i18n('reviews|text_list')?></div>
<div class='curorder'><div class='block1' style='padding-bottom:25px;'>
<table style="width:97%">
<?php
$i=0;
foreach ($q as $k=>$v) {
  $basket=unserialize($v['basket']);
  if($i!=0) {
    echo '<tr><td colspan=2><hr></td></tr>';
  }
  echo '<tr><td>';
  echo '<table style="width:100%;padding:0 15px;">';
  echo '<tr><td colspan=2><b>Рецензия на '.($basket['type']==1?'методическую разработку урока (мероприятия)':'рабочую программу').'</b></td></tr>';
  echo '<tr><td><b>'.i18n('reviews|fio').'</b></td><td>'.$basket['fio'].'</td></tr>';
  echo '<tr><td><b>'.i18n('reviews|workplace').'</b></td><td>'.$basket['workplace'].'</td></tr>';
  echo '<tr><td><b>'.i18n('reviews|position').'</b></td><td>'.$basket['position'].'</td></tr>';
  echo '<tr><td><b>'.i18n('reviews|name').'</b></td><td>'.$basket['name'].'</td></tr>';
  echo '</table>';
//.$basket['fio'].'</td><td>'.$basket['workplace'].
  echo '</td><td style="width:283px;">';
  if($v['paid']) {
    if($v['file']){
      echo '<a target="blank" class="down" href="/files/orders/'.$k.'/file/'.$v['file'].'">Скачать рецензию</a>';
    }
    else{echo 'ожидает подтверждения';}
  }
  else include('buttons.php');
  echo '</td></tr>';
  $i++;
}
?>
</table>
</div></div>
<a class='leftitem' style='margin:20px auto;text-align:center;' href='add/'><?=i18n('reviews|add')?></a>
