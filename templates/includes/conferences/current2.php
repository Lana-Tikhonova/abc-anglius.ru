<?php
$basket=unserialize($q['basket']);
if($i==1) { ?>
<a name="spisok"></a>
<div class="info3 list_cont" style='margin-bottom:60px;'><p style='text-align:center;color:#000'><b>Сборник участников конференции "<?=$basket['confname']?>"</b></p>
<?php }?>
<div class="block1">
 <div class='img'><img src='/templates/images/icon-4.png'></div>
 <div class="txt">
  <span><?=$q['id']?></span><br>
  <b><?=$basket['section']?> / <?=$basket['name']?></b><br>
  <?=$basket['fio']?>
 </div>
 <a class="arrow" href="/files/orders/<?=$q['id']?>/file/<?=$q['file']?>">&darr;</a>
</div>
<?php if($i==$num_rows) { ?>
</div>
<?php } ?>
