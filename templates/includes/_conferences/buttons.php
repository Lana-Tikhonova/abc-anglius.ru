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
	foreach(explode(',',$config['yandex_paymethods']) as $v1) $config_pm[]=trim($v1);
  }
  $flag=0;
  foreach($config_pm as $v1) {
    if(isset($pm[$v1])) {echo "<a href='/".$modules['profile']."/orders/".$v['id']."/payonline/?method=$v1'>".$pm[$v1]."</a><br>";$flag++;}
  }
  if(!$flag){echo "<a href='/".$modules['profile']."/orders/".$v['id']."/payonline/?method=PC'>".$pm['PC']."</a><br>";}
?>
</div>
</div>
<a class="leftitem" href="/<?=$modules['profile']?>/orders/<?=$v['id']?>/download/">Скачать квитанцию</a>
<a class="leftitem upload" href="#">Загрузить квитанцию</a>
<form method="post" action="/<?=$modules['profile']?>/orders/<?=$v['id']?>/upload/" enctype="multipart/form-data" class="uploadform" style="display:none;">
<input type="file" name="attaches" class="uploadfile">
</form>
</div>
