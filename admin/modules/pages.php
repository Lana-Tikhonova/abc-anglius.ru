<?php

$modules_site = array(
	'pages'			=> 'Текстовая страница',
	'index'			=> 'Главная',
	//'articles'		=> 'Статьи',
	'olympiads'		=> 'Олимпиады',
	'contests'		=> 'Творческие конкурсы',
	'publications'		=> 'Публикации',
	'tests'			=> 'Конкурс учителей',
	'online_olympiads91'	=> $config['online_types'][91],
	'online_olympiads92'	=> $config['online_types'][92],
	'online_olympiads93'	=> $config['online_types'][93],
	'online_olympiads94'	=> $config['online_types'][94],
	'online_tests'          => 'Онлайн тестирование',
//	'shop'			=> 'Каталог',
//	'basket'		=> 'Корзина',
	'feedback'		=> 'Обратная связь',
//	'sitemap'		=> 'Карта сайта',
	'profile'		=> 'Личный кабинет',
	'login'			=> 'Авторизация',
	'registration'		=> 'Регистрация',
	'remind'		=> 'Восстановление пароля',
//	'subscribe'		=> 'Подписка',
    'search_diplom'	=> 'Поиск диплома',
);

$a18n['menu3'] = 'меню 3';

if ($get['u']=='form') {
	if (empty($post['module'])) $post['module'] = 'pages';
	foreach ($modules_site as $k=>$v)
		if (!file_exists(ROOT_DIR.'modules/'.$k.'.php'))
			unset($modules_site[$k]);
}

$table = array(
	'_tree'		=> true,
	'_edit'		=> true,
	'id'		=> '',
	'img'		=> 'img',
	'name'		=> '',
	'title'		=> '',
	'url'		=> '',
	'module'	=> 'text',
	'menu'		=> 'boolean',
	'menu2'		=> 'boolean',
	'menu3'		=> 'boolean',
	'display'	=> 'display'
);

//только если многоязычный сайт
if ($config['multilingual']) {
	$languages = mysql_select("SELECT id,name FROM languages ORDER BY rank DESC", 'array');
	//приоритет пост над гет
	if (isset($post['language'])) $get['language'] = $post['language'];
	if (@$get['language'] == 0) $get['language'] = key($languages);
	$query = "
		SELECT pages.*
		FROM pages
		WHERE pages.language = '".$get['language']."'
	";
	$filter[] = array('language', $languages);
	$form[] = '<input name="language" type="hidden" value="'.$get['language'].'" />';
}

$delete['confirm'] = array('pages'=>'parent');

$form[] = array('input td7','name',true);
$form[] = array('select td3','module',array(true,$modules_site),array('help'=>'Модуль отвечает за тип информации на странице. Например, на странице модуля &quot;Новости&quot; будет отображатся список новостей.'));
$form[] = array('checkbox','display',true);
$form[] = array('parent td3 td4','parent',true);
$form[] = array('checkbox','menu',true,array('help'=>'верхнее меню'));
$form[] = array('checkbox','menu2',true,array('help'=>'левое меню'));
$form[] = array('checkbox','menu3',true,array('help'=>'верхнее второе меню'));
$form[] = array('tinymce td12','text',true);//,array('attr'=>'style="height:500px"'));
$form[] = array('file td6','img','иконка меню',array('p-'=>'resize 39x36'));
$form[] = array('seo','seo url title keywords description',true);
