<div class='payonline2'>
<div class='topblock'>
<div class='h'>Оплата онлайн</div>
<img src='/templates/images/orn.gif'><br>Комиссия не взимается, моментальное зачисление оплаты. Моментальное изготовление диплома.
</div>

<div style="display: flex; flex-wrap: wrap; justify-content: space-around;">
<?php
  $pm=array(
	'visa'=>array('yk'=>'AC','name'=>'Картами Visa'),
	'mastercard'=>array('yk'=>'AC','name'=>'Картами MasterCard'),
	'maestro'=>array('yk'=>'AC','name'=>'Картами Maestro'),
	'sberbank'=>array('yk'=>'SB','name'=>'Сбербанк Онлайн'),
	'yandex'=>array('yk'=>'PC','name'=>'Яндекс.Деньги'),
	'qiwi'=>array('yk'=>'QW','name'=>'QIWI'),
	'webmoney'=>array('yk'=>'WM','name'=>'WebMoney'),
	'alfa'=>array('yk'=>'AB','name'=>'Альфа-Клик'),
    'mir'=>array('yk'=>'AC','name'=>'Картами Мир'),
  );
  $config_pm=explode(',',$config['yandex_paymethods']);
  $flag=0;
  foreach($pm as $k=>$v) {
    if(in_array($v['yk'],$config_pm)) {
?>
      <div class="poblock">
      <? /* <a href='https://money.yandex.ru/eshop.xml?shopId=<?=$config['yandex_shopId']?>&scid=<?=$config['yandex_scid']?>&sum=<?=$q['total']?>&customerNumber=<?=$config['yandex_customerNumber']?>&orderNumber=<?=$q['id']?>&paymentType=<?=$v['yk']?>&ym_merchant_receipt=<?=getOnlineReceipt($q)?>'> */ ?>
      <a href='https://yoomoney.ru/eshop.xml?shopId=<?=$config['yandex_shopId']?>&scid=<?=$config['yandex_scid']?>&sum=<?=$q['total']?>&customerNumber=<?=$config['yandex_customerNumber']?>&orderNumber=<?=$q['id']?>&paymentType=<?=$v['yk']?>&ym_merchant_receipt=<?=getOnlineReceipt($q)?>'>
      <img src='/templates/images/<?=$k?>.jpg'><br><?=$v['name']?>
      </a>
      </div>
<?php
      $flag++;
    }
  }
  if(!$flag){ ?>
      <div class="poblock">
      <? /* <a href="https://money.yandex.ru/eshop.xml?shopId=<?=$config['yandex_shopId']?>&scid=<?=$config['yandex_scid']?>&sum=<?=$q['total']?>&customerNumber=<?=$config['yandex_customerNumber']?>&orderNumber=<?=$q['id']?>&paymentType=PC"> */ ?>
      <a href="https://yoomoney.ru/eshop.xml?shopId=<?=$config['yandex_shopId']?>&scid=<?=$config['yandex_scid']?>&sum=<?=$q['total']?>&customerNumber=<?=$config['yandex_customerNumber']?>&orderNumber=<?=$q['id']?>&paymentType=PC">
      <img src='/templates/images/yandex.jpg'><br><?=$pm['yandex']['name']?>
      </a>
      </div>
<?php
  }
?>
</div>
<div class='clear-both'></div>
<? /*
<div class='rightornot'><a href='/%d0%9a%d0%b2%d0%b8%d1%82%d0%b0%d0%bd%d1%86%d0%b8%d1%8f%20%d0%9e%d0%bd%d0%bb%d0%b0%d0%b9%d0%bd%20%d0%9e%d0%bb%d0%b8%d0%bc%d0%bf%d0%b8%d0%b0%d0%b4%d0%b0.docx' class='btn'>СКАЧАТЬ КВИТАНЦИЮ ДЛЯ ОПЛАТЫ ЧЕРЕЗ БАНК</a></div>
*/ ?>
</div>
