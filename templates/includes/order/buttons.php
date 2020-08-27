<div class="bparent">
<div class="leftitem"><a href="#" class="payonline">Оплатить онлайн</a><br>
<div class="paymethods">
<?php
  $pm=array(
	'PC'=>'Яндекс.Деньги',
	'AC'=>'Банковская карта',
	'MC'=>'Мобильный телефон',
	'GP'=>'Терминал наличными',
	'WM'=>'WebMoney',
	'SB'=>'Сбербанк-Онлайн',
	'MP'=>'Мобильный терминал',
	'AB'=>'Альфа-Клик',
	'МА'=>'MasterPass',
	'PB'=>'Промсвязьбанк',
	'QW'=>'QIWI Wallet',
	'KV'=>'КупиВкредит (Тинькофф)'
  );
  $config_pm=array();
  if($config['yandex_paymethods']){
	foreach(explode(',',$config['yandex_paymethods']) as $v) $config_pm[]=trim($v);
  }
  $flag=0;
  foreach($config_pm as $v) {
    if(isset($pm[$v])) {echo "<a href='payonline/?method=$v'>".$pm[$v]."</a><br>";$flag++;}
  }
  if(!$flag){echo "<a href='payonline/?method=PC'>".$pm['PC']."</a><br>";}
?>
</div>
</div>
<a class="leftitem" href="download/">Скачать квитанцию</a>
<a class="leftitem upload" href="#">Загрузить квитанцию</a>
<form method="post" action="<?=$_SERVER['REQUEST_URI']?>upload/" enctype="multipart/form-data" class="uploadform" style="display:none;">
<input type="file" name="attaches" class="uploadfile">
</form>
</div>
