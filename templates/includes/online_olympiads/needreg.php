  <div class='testdiv'>
    <div class='container'>
      <div class='questnr'></div>
      <div class='questions'>
        <div class='q'>Вы сможете продолжить прохождение олимпиады только после регистрации или входа (если уже зарегистрированы)</div>
	<form method="POST" action="">
<?php
	foreach($_POST as $k=>$v) {
		echo "<input type='hidden' name='$k' value='$v'>";
	}
	echo "<input type='hidden' name='test' value='".$u[2]."'>";
?>
	<div style="width:500px;margin:auto;">
	<input class='radsub a' type="submit" value="Войти" style="float:left;margin-right:50px;">
	<input class='radsub b' type="submit" value="Зарегистрироваться" style="float:left;">
        <div class="clear-both"></div>
	</div>
	</form>
      </div>
    </div>
  </div>
  <script>
  $('.radsub.a').click(function(){
    $('form').attr('action','/<?=$modules['login']?>/');
  });
  $('.radsub.b').click(function(){
    $('form').attr('action','/<?=$modules['registration']?>/');
  });
  </script>