<?php
  if ($i==1) {$olympiad=0;
?>
<div class='list_cont orders'>
<?php }
  if(($q['type']==1||$q['type']==8)&&!$q['teacher']) {
//print_r($q);
    if($olympiad!=$q['parent']){
      if($olympiad!=0) echo '</div></div>';
      $olympiad=$q['parent'];
      echo '<div class="olymp"><a href="#" class="showol" data-i="'.$q['parent'].'">'.$q['name'].'</a><div id="ol'.$q['parent'].'" class="hid">';
    }
  }
  elseif($olympiad!=0){
    echo '</div></div>';
    $olympiad=0;
  }
?>
          <div class='block1'>
            <div class='img'><?=($q['type']==1||$q['type']==8)?"<img src='/files/".$config['types_modules'][$q['type']]."/".$q['parent']."/img/p-".$q['img']."'>":''?></div>
            <div class='txt'>
              <span><?=$q['type']==8?$config['types'][1]:$config['types'][$q['type']]?></span><br>
<?php
if($q['type']==3){
	$basket['name']=mysql_select('SELECT name FROM '.$config['types_modules'][$q['type']].' WHERE id='.$q['parent'],'string');
} else {
	if(isset($q['basket'])) $basket=unserialize($q['basket']);
	if($q['type']==1) {
//          if($q['teacher']) {
//            $basket['name']=$q['name'];
//          }
//          else $basket['name']=$q['klass']>20?($q['klass']-20).' курс':$q['klass'].' класс';
          $basket['name']=$q['name'];
	}
}
?>
              <b><?=$basket['name']?></b>
              <div><a class='particip gradient1' href='/<?=$modules['profile']?>/orders/<?=$q['id']?>/'>Подробнее</a></div>
              <?=($q['type']==1||$q['type']==8)?"<div><a class='particip gradient1' href='/".$modules['olympiads'].'/'.$q['parent']."/download/'>Задание</a></div>":''?>
              <?=(!$q['paid'])?"<div><a class='particip gradient1' href='/".$modules['profile']."/orders/".$q['id']."/delete/'>Удалить заявку</a></div>":''?>
            </div>
            <div class='clear-both'></div>
          </div>

<?php
  if ($i==$num_rows) {
    if(($q['type']==1||$q['type']==8)&&!$q['teacher']) echo '</div></div>';
?>
</div>
<script>
$(".showol").click(function(){
   $("#ol"+$(this).data('i')).toggle();
   return false;
});
</script>
<?php } ?>
