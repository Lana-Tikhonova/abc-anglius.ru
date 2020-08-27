<div style='margin-bottom:10px;'><?=i18n('conferences|text_list')?></div>
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
  echo '<tr><td><b>Конференция</b></td><td>'.$basket['confname'].'</td></tr>';
  echo '<tr><td><b>'.i18n('conferences|fio').'</b></td><td>'.$basket['fio'].'</td></tr>';
  echo '<tr><td><b>'.i18n('conferences|workplace').'</b></td><td>'.$basket['workplace'].'</td></tr>';
  echo '<tr><td><b>'.i18n('conferences|position').'</b></td><td>'.$basket['position'].'</td></tr>';
  echo '<tr><td><b>'.i18n('conferences|section').'</b></td><td>'.$basket['section'].'</td></tr>';
  echo '<tr><td><b>'.i18n('conferences|name').'</b></td><td>'.$basket['name'].'</td></tr>';
  echo '</table>';
//.$basket['fio'].'</td><td>'.$basket['workplace'].
  echo '</td><td style="width:283px;">';
  if($v['paid']){
//сертификаты
    echo '<a target="_blank" class="down" href="/certificate.php?key='.base64_encode($v['id'].'.1').'">Диплом</a><br>';
    echo '<a target="_blank" class="down" href="/certificate.php?key='.base64_encode($v['id'].'.2').'">Сертификат</a><br>';
    echo '<a target="_blank" class="down" href="/certificate.php?key='.base64_encode($v['id'].'.3').'">Свидетельство о публикации</a><br>';
  }
  else {
    if($v['receipt']){echo 'ожидает подтверждения';}
    else include('buttons.php');
  }
  echo '</td></tr>';
  $i++;
}
?>
</table>
</div></div>
<a class='leftitem' style='margin:20px auto;text-align:center;' href='add/'><?=i18n('conferences|add')?></a>
