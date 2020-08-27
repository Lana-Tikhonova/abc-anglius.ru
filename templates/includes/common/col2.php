<?=html_sources('return','jquery_validate.js')?>
      <a href='http://mir-olimpiad.ru/?utm_source=english' target='_blank'><img src='/templates/images/bnr.jpg'></a>

      <div class='feedback gradient1' id='feed'>
        <div class='head text-center'>ЗАДАТЬ ВОПРОС</div>
        <div class='text-center'>Напишите свой вопрос и мы обязательно Вам ответим!</div>
        <form method="post" class="form validate">
<?php
echo html_array('form/input',array(
	'caption'	=>	i18n('feedback|name'),
	'name'	=>	'name',
	'value'	=>	isset($q['name']) ? $q['name'] : $userfields[3][0].' '.$userfields[1][0],
	'attr'	=>	' required gradient2',
	'placeholder'	=>	'Иванов Иван'
));
echo html_array('form/input',array(
	'caption'	=>	i18n('feedback|email'),
	'name'	=>	'email',
	'value'	=>	isset($q['email']) ? $q['email'] : $user['email'],
	'attr'	=>	' required email gradient2',
	'placeholder'	=>	'name@mail.com'
));
echo html_array('form/textarea',array(
	'caption'	=>	i18n('feedback|text'),
	'name'	=>	'text',
	'value'	=>	isset($q['text']) ? $q['text'] : '',
	'attr'	=>	' required gradient2'
));
echo html_array('form/captcha2');
echo html_array('form/button',array(
	'name'	=>	i18n('feedback|send'),
	'class'	=>	' gradient3 border5'
));
?>
        </form>
      </div>
