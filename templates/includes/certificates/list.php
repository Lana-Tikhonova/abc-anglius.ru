<?php
//print_r($q);
?>
<div style='margin-bottom:10px;'><?=i18n('certificates|text_list')?></div>
<div class='curorder'><div class='block1' style='padding-bottom:25px;'>
<table class="anstbl" style='float:none;'>
<tr><th valign=top class="fio"><?=i18n('certificates|fio')?></th><th><?=i18n('certificates|workplace')?></th>
<th style="width:283px;"></th></tr>
<?php
foreach ($q as $k=>$v) {
  $basket=unserialize($v['basket']);
  echo '<tr><td>'.$basket['fio'].'</td><td>'.$basket['workplace'].'</td><td>';
  if(!$v['paid']){include('buttons.php');}
  elseif($v['file']){echo '<a target="blank" href="/files/orders/'.$k.'/file/'.$v['file'].'">Скачать сертификат</a>';}
  else{echo 'ожидает подтверждения';}
  echo '</td></tr>';
}
?>
</table>
</div></div>
<a class='leftitem' style='margin:20px auto;text-align:center;' href='add/'><?=i18n('certificates|add')?></a>
