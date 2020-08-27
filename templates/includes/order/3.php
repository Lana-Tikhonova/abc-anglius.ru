        <div class='contests curorder'>
          <div class='block1'>
            <b><?=$config['types'][$q['type']]?>: <?=$q['more']['name']?></b><br>
<hr>
<p><span><?=i18n('publications|fio')?>:</span><b><?=$q['more']['fio']?></b></p>
<p><span><?=i18n('publications|workplace')?>:</span><b><?=$q['more']['workplace']?></b></p>
<p><span><?=i18n('publications|name')?>:</span><b><a class='down' href='/files/publications/<?=$q['more']['id']?>/file/<?=$q['more']['file']?>'><?=$q['more']['name']?></a></b></p>
<?php if($q['paid']==0) { ?>
<div><b>Сумма к оплате <?=$q['total']?> руб.</b></div>
<?php if($q['receipt']!='') { ?><div><b>Загружена <a class='down' href='/files/orders/<?=$q['id']?>/receipt/<?=$q['receipt']?>'>квитанция</a></b></div><?php }
} elseif($q['more']['display']>0) { ?>
<div><b><a target='_blank' class='down' href='/certificate.php?key=<?=base64_encode($q['id'].'.0')?>'><?=i18n('publications|certificate')?></a></b></div>
<?php } ?>
          </div>
<?php if($q['paid']==0) include('buttons.php');?>
        </div>
