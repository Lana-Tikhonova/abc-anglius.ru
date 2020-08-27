        <div class='list contests curorder'>
          <div class='block'>
           <div class='float-left'><img src='/files/contests/<?=$q['parent']?>/img/p-<?=$q['more']['img']?>'></div>
           <div class='text'>
             <b><?=$config['types'][$q['type']]?>: <?=$q['more']['name']?></b><br>
             <?=$q['more']['shortdesc']?>
           </div>
           <div class='clear-both'></div>
<hr>
<p><span><?=i18n('contests|fio1')?>:</span><b><?=$q['basket']['fio']?></b></p>
<p><span><?=i18n('contests|fio2')?>:</span><b><?=$q['basket']['fio2']?></b></p>
<p><span><?=i18n('contests|workplace')?>:</span><b><?=$q['basket']['workplace']?></b></p>
<p><span><?=i18n('contests|name')?>:</span><b><a class='down' href='/files/orders/<?=$q['id']?>/file/<?=$q['file']?>'><?=$q['basket']['name']?></a></b></p>
<?php if($q['paid']==0) { ?>
<div><b>Сумма к оплате <?=$q['total']?> руб.</b></div>
<?php if($q['receipt']!='') { ?><div><b>Загружена <a class='down' href='/files/orders/<?=$q['id']?>/receipt/<?=$q['receipt']?>'>квитанция</a></b></div><?php }
} elseif($q['place']) { ?>
<div><b><a target='_blank' class='down' href='/certificate.php?key=<?=base64_encode($q['id'].'.0')?>'><?=i18n('contests|certificate')?></a></b></div>
<?php } ?>
          </div>
<?php if($q['paid']==0) include('buttons.php');?>
        </div>
