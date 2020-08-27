<?php 
  $qa=$q['qa'][$u[3]];
  $bukvy=array(1=>'а',2=>'б',3=>'в',4=>'г');
  $action='/'.$u[1].'/'.$u[2].'/'.($u[3]<count($q['qa'])?($u[3]+1):'result').'/';
//  $goreg=0;
  if(!access('user auth')&&$u[3]==3) {
    $action='/'.$u[1].'/'.$u[2].'/0/';
//    $goreg=1;
  }
?>
  <div class='testdiv' id="testdiv_scroll">
    <div class='container'>
      <div class='questnr'>ВОПРОС <?=$u[3]?> ИЗ <?=count($q['qa'])?></div>
      <div class='questions'>
        <div class='q'><?=$qa['q']?></div>
        <? if(!empty($qa['img'])): ?><div class='q_img' style="margin-bottom: 15px;"><img src="<?=$qa['img']?>" style="max-width: 100%;"></div><? endif; ?>
        <? if(!empty($qa['audio'])): ?>
            <div class='q_audio' style="margin-bottom: 15px;">
                <audio controls>                
                    <source src="<?=$qa['audio']?>" type="audio/mpeg">
                    Звуковой файл не поддерживается вашим браузером. 
                    <a href="<?=$qa['audio']?>">Скачайте звуковой файл</a>.
                </audio>
            </div>
        <? endif; ?>
        <? if(!empty($qa['video'])): ?>
            <div class='q_video' style="margin-bottom: 25px;">
                <video controls style="max-width: 300px;">                
                    <source src="<?=$qa['video']?>" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
                    Видео файл не поддерживается вашим браузером. 
                    <a href="<?=$qa['video']?>">Скачайте видео файл</a>.
                </video>
            </div>
        <? endif; ?>
        
	<form method="POST" action="<?=$action?>">
<?php
$answer=1;
if(access('user auth')&&$u[3]==4&&isset($_SESSION['test'])) {
	$test=unserialize($_SESSION['test']);
	unset($_SESSION['test']);
	foreach($test as $k=>$v) {
		if($k!='test') echo "<input type='hidden' name='$k' value='$v'>";
	}
}
else {
	foreach($_POST as $k=>$v) {
		if($k!='a'.$u[3]) echo "<input type='hidden' name='$k' value='$v'>";
		else $answer=$v;
	}
//	if($u[3]==0) {
//	//	echo "<input type='hidden' name='goreg' value='1'>";
//		echo "<input type='hidden' name='test' value='".$u[2]."'>";
//	}
}
for($i=1;$i<=4;$i++) if(isset($qa['a'.$i])&&$qa['a'.$i]) {
	echo "<label class='rad'><input type='radio' name='a".$u[3]."' value='$i'";
	if($i==$answer) echo ' checked';
	echo '><i></i>'.$qa['a'.$i].'</label>';
	//echo '><i></i>'.$bukvy[$i].') '.$qa['a'.$i].'</label>';
}
?>
	<input class='radsub' type="submit" value="Следующий вопрос">
	</form>
      </div>
    </div>
  </div>

<script>
	$(window).load(function(){		
		$('body,html').animate({
			scrollTop: $('#testdiv_scroll').offset().top
		}, 300);
		console.log('sdfds');
	});
</script>