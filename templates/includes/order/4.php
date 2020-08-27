        <div class='list tests curorder'>
         <div class='block'>
           <div class='float-left'><img src='/files/tests/<?=$q['parent']?>/img/p-<?=$q['more']['img']?>'></div>
           <div class='text'>
             <b><?=$config['types'][$q['type']]?>: <?=$q['more']['name']?></b><br>
             <?=$q['more']['shortdesc']?>
           </div>
           <div class='clear-both'></div>
<hr>
<p><span><?=i18n('tests|fio')?>:</span><b><?=$q['basket']['fio']?></b></p>
<p><span><?=i18n('tests|workplace')?>:</span><b><?=$q['basket']['workplace']?></b></p>
<?php
/*<p><span><?=i18n('tests|city')?>:</span><b><?=$q['basket']['address']?></b></p>*/
?>
<p><span><?=i18n('tests|name')?>:</span><b><a class='down' href='/files/orders/<?=$q['id']?>/file/<?=$q['file']?>'><?=$q['basket']['name']?></a></b></p>
<?php if($q['paid']==0) { ?>
<div><b>Сумма к оплате <?=$q['total']?> руб.</b></div>
<?php if($q['receipt']!='') { ?><div><b>Загружена <a class='down' href='/files/orders/<?=$q['id']?>/receipt/<?=$q['receipt']?>'>квитанция</a></b></div><?php }
} else if($q['place']) { ?>
<div><b><a target='_blank' class='down' href='/certificate.php?key=<?=base64_encode($q['id'].'.0')?>'><?=i18n('tests|certificate')?></a></b></div>
<?php } ?>
         </div>
<?php if($q['paid']==0) include('buttons.php');?>
        </div>
