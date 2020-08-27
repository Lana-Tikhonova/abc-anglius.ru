<?php if($i==1) { ?>
  <div class='testdiv testdivclasses tdiv<?=$page['type']?>'>
    <div class='container'>
      <div class='questions' style='font-size:28px;font-family:"Open Sans"'>
<?php }
$rows= 1;//($page['type']==3)?3:1;
if($i%$rows==1||$rows==1) { ?>
        <div class='col'>
<? } ?>
	  <a href='/online_tests/<?=$q['id']?>/'><?=$q['klass']?>й <?=($page['type']==93)?'класс':'курс'?></a>
<?php if($i%$rows==0||$rows==1) { ?>
        </div>
<? } ?>

<?php if($i==$num_rows) {
	if($i%$rows!=0) echo '</div>';
?>
        <div class='clear-both'></div>
      </div>
    </div>
  </div>
<?php } ?>