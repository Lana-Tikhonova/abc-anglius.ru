<?php $userfields=unserialize($user['fields']); ?>
<!--<div class='form7top'>-->
<!--  <div class='container'>-->
<!--    <div>Конференция</div>-->
<!--  </div>-->
<!--</div>-->

<div class='container form7' style="margin-bottom: 60px;">
<!--  <div class='head'>--><?//=i18n('conferences|filltheform')?><!--</div>-->
  <div class='about text-center' style="margin: 20px 0;"><?=$page['testname']?></div>

<?=html_sources('return','jquery_validate.js')?>
<?=isset($q['message']) ? html_array('form/message',$q['message']) : ''?>
<form method="post" class="form validate" enctype="multipart/form-data">
<?php
echo html_array('form/input',array(
	'caption'	=>	i18n('conferences|fio'),
	'name'	=>	'fio',
	'value'	=>	isset($q['fio']) ? $q['fio'] : $userfields[3][0].' '.$userfields[1][0],
	'attr'	=>	' required',
	'placeholder'	=>	'Иванов Иван'
));
echo html_array('form/input',array(
	'caption'	=>	i18n('conferences|workplace'),
	'name'	=>	'workplace',
	'value'	=>	isset($q['workplace']) ? $q['workplace'] : $userfields[4][0],
	'attr'	=>	' required',
));
echo html_array('form/input',array(
	'caption'	=>	i18n('conferences|position'),
	'name'	=>	'position',
	'value'	=>	isset($q['position']) ? $q['position'] : '',
	'attr'	=>	' required',
));
//echo html_array('form/input',array(
//	'caption'	=>	i18n('conferences|city'),
//	'name'	=>	'city',
//	'value'	=>	isset($q['city']) ? $q['city'] : '',
//	'attr'	=>	' required',
//));
echo html_array('form/input',array(
	'caption'	=>	i18n('conferences|section'),
	'name'	=>	'section',
	'value'	=>	isset($q['section']) ? $q['section'] : '',
	'attr'	=>	' required',
));
echo html_array('form/input',array(
	'caption'	=>	i18n('conferences|name'),
	'name'	=>	'name',
	'value'	=>	isset($q['name']) ? $q['name'] : '',
	'attr'	=>	' required',
));
//echo html_array('form/input',array(
//	'caption'	=>	i18n('conferences|email'),
//	'name'	=>	'email',
//	'value'	=>	isset($q['email']) ? $q['email'] : $user['email'],
//	'attr'	=>	' required email',
//));
echo html_array('form/file',array(
	'caption'	=>	i18n('conferences|upload'),
	'name'	=>	'attaches',
//class
	'attr'	=>	' required',
));
echo i18n('conferences|upload_txt');
echo html_array('form/captcha2');

echo "<div class='summ'><b>Сумма к оплате <span>".$q['price']."</span> руб.</b></div>";
echo "<div class='summ2' style='display:none;'><b>Скидка по промо-коду <span></span> руб.</b></div>";
echo "<div class='summ3' style='display:none;'><b>Итоговая сумма <span></span> руб.</b></div>";
//if(mysql_select('select id from promocodes where type=6 and display=1','num_rows')>0) {
if(mysql_select('select pr.percent from promocodes AS pr LEFT JOIN `promocodes-types` AS pt ON pt.child = pr.id where (pr.type=6 OR pt.parent=6) and pr.display=1','num_rows')>0) {
  echo '<center><input name="promocode" value="'.$q['promocode'].'" placeholder="Промо-Код (при наличии)" style="width:270px" />';
  echo "<span class='button -secondary' id='promocode' style='margin:0 0 0 20px;display:inline-block;width:170px;'>Применить</span></center>";
}

echo html_array('form/button',array(
	'name'	=>	i18n('conferences|send'),
	'class'	=>	' button -secondary gradient1'
));
?>
</form>
</div>
<script>
var promooff=0;
$('#promocode').click(function(){
        $.get('/ajax.php',{'file':'promocode','type':'6','promocode':$('input[name="promocode"]').val()},
        function(data){
		promooff=parseInt(data);
		if(promooff==0) {
			$('input[name="promocode"]').val('');$('.summ2').hide();$('.summ3').hide();
		}
		else {
			$('.summ2 span').text($('.summ span').text()*promooff/100);
			$('.summ3 span').text($('.summ span').text()*(100-promooff)/100);
			$('.summ2').show();$('.summ3').show();
		}
	});
	return false;
});
$('#promocode').trigger('click');
</script>
