<?php
$basket=unserialize($q['basket']);
if($i==1) { ?>
<a name="spisok"></a>
<div class="info3 list_cont pub" style='margin-bottom:60px;'><p style='text-align:center;color:#000'><b>Сборник участников конференции "<?=$basket['confname']?>"</b></p>
<?php }?>
<div class="block1">
 <div class='img'><img src='/templates/assets/imgs/icon-2.png'></div>
 <div class="txt">
  <span><?=$q['id']?></span><br>
  <b><?=$basket['section']?> / <?=$basket['name']?></b><br>
  <?=$basket['fio']?>
 </div>
 <a class="arrow" href="/files/orders/<?=$q['id']?>/file/<?=$q['file']?>">&darr;</a>
 <div class="clear-both"></div>
</div>
<?php if($i==$num_rows) { ?>
</div>
<?php } ?>
