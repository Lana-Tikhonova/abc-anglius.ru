<div class='list curorder'><div class='block1'>
<b><?=$q['more']['name']?></b>
<table class="anstbl" style='float:none;'>
<tr><th valign=top class="fio"><?=i18n('olympiads|fio2')?></th>
<?php
if($q['more']['teacher']==0) echo '<th>Тест</th>';
if($q['paid']==1) {
	function cmp($a,$b) {
		if($a['place']==$b['place']) {
			if($a['fio']==$b['fio']) return 0;
			return($a['fio']<$b['fio'])?-1:1;
		}
		return($a['place']<$b['place'])?-1:1;
	}
	uasort($q['basket']['results'],'cmp');
	echo '<th>Скачать диплом</th></tr>';
	foreach($q['basket']['results'] as $key=>$val) {
		echo '<tr><td>'.$val['fio'].'</td>';
		if($q['more']['teacher']==0) {
//		  $klass=$q['basket']['klass'];
		  $klass=$val['klass'];
		  echo '<td>'.($klass>20?($klass-20).' курс':$klass.' класс').'</td>';
		}
                echo '<td>';
		if(isset($val['place'])&&$val['place']) {
		  echo '<a target="_blank" href="/certificate.php?key='.base64_encode($q['id'].'.'.$key).'">Скачать диплом ';
		  switch ($val['place']) {
			case 1:echo 'победителя I степени';break;
			case 2:echo 'победителя II степени';break;
			case 3:echo 'победителя III степени';break;
			default:echo 'участника';
		  }
		  echo '</a>';
		}
                else echo 'в обработке';
		echo '</td></tr>';
	} ?>
</table>
<?php
  if(isset($q['basket']['fio'])) {
    echo '<div style="text-align:right;padding-right:30px;">(<a class="down" href="/zip.php?key='.base64_encode($q['id'].'.0').'">Скачать архив с дипломами</a>)</div>';
    $fios=explode('|',$q['basket']['fio']);
    foreach($fios as $fiok=>$fiov) {
      echo '<div><a target="_blank" class="down" href="/certificate.php?key='.base64_encode($q['id'].'.0.'.$fiok).'">Скачать благодарственное письмо</a> - педагог '.$fiov.'</div>';
    }
 }
?>
</div>
<?php } else { ?>
<?php 
  $max=0;
  foreach ($q['basket']['results'] as $k=>$v) {
    $i=0;do {if($i>$max) $max=$i;$i++;} while(isset($v[$i]));
  }
  for($k=1;$k<=$max;$k++) {echo '<th>'.$k.'</th>';}
?>
</tr>
<?php foreach($q['basket']['results'] as $key=>$val) { ?>
<tr><td><?=$q['basket']['results'][$key]['fio']?></td><?php
if($q['more']['teacher']==0) {
//  $klass=$q['basket']['klass'];
  $klass=$val['klass'];
  echo '<td>'.($klass>20?($klass-20).' курс':$klass.' класс').'</td>';
}
for($k=1;$k<=$max;$k++) { ?>
<td><?=$val[$k]?></td>
<?php } ?>
</tr>
<?php } ?>
</table>
<div><a target="blank" class="down" href="/files/orders/<?=$q['id']?>/file/<?=$q['file']?>">Загруженная работа</a></div>
<div><b>Сумма к оплате <?=$q['total']?> руб.</b></div>
<?php if($q['receipt']!='') { ?><div><b>Загружена <a class='down' href='/files/orders/<?=$q['id']?>/receipt/<?=$q['receipt']?>'>квитанция</a></b></div><?php } ?>
</div>
<?php if($q['receipt']=='') { ?><div><center><a class="leftitem" href="edit/" style="float:none;">Редактировать результаты</a></center></div><?php } ?>
<?php include($ROOT_DIR.'templates/includes/order/buttons.php');?>
<?php } ?>
</div>