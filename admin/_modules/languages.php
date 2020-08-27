<?php
$locales = array(
	'en'	=>	'Английский',
	'ar'	=>	'Арабский',
	'bg'	=>	'Болгарский',
	'ca'	=>	'Каталанский',
	'cn'	=>	'Китайский',
	'cs'	=>	'Чешский',
	'da'	=>	'Датский',
	'de'	=>	'Немецкий',
	'el'	=>	'Греческий',
	'es'	=>	'Испанский',
	'eu'	=>	'Баскский',
	'fa'	=>	'Фарси',
	'fi'	=>	'Финский',
	'fr'	=>	'Французский',
	'he'	=>	'Иврит',
	'hu'	=>	'Венгерский',
	'it'	=>	'Итальянский',
	'ja'	=>	'Японский',
	'kk'	=>	'Казахский',
	'lt'	=>	'Литовский',
	'lv'	=>	'Латышский',
	'nl'	=>	'Голландский',
	'no'	=>	'Норвежский',
	'pl'	=>	'Польский',
	'ptbr'	=>	'Португальский (Бразилия)',
	'ptpt'	=>	'Португальский',
	'ro'	=>	'Румынский',
	'ru'	=>	'Русский',
	'si'	=>	'Словенский',
	'sk'	=>	'Словацкий',
	'sl'	=>	'Словенский',
	'sr'	=>	'Сербский',
	'th'	=>	'Таиландский',
	'tr'	=>	'Турецкий',
	'tw'	=>	'Тайванский',
	'ua'	=>	'Украинский',
	'vi'	=>	'Вьетнамский',
);

//многоязычный
if ($config['multilingual']) {
	$table = array(
		'id'			=>	'rank:desc name id',
		'name'			=>	'',
		'rank'			=>	'',
		'url'			=>	'',
		'localization'	=>	$locales,
		'display'		=>	'display'
	);
	$form[0][] = array('input td4','name',true);
	$form[0][] = array('input td2','rank',true);
	$form[0][] = array('input td2','url',true);
	$form[0][] = array('select td2','localization',array(true,$locales));
	$form[0][] = array('checkbox td2','display',true);
}
//одноязычный
else {
	$pattern = 'one_form';
	$get['id'] = 1;
	if ($get['u']!='edit') {
		$post = mysql_select("
			SELECT *
			FROM languages
			WHERE id = 1
			LIMIT 1
		",'row');
	}
}

$a18n['localization'] = 'localization';

//исключения
if ($get['u']=='edit') {
	unset($post['dictionary']);
}
else {
	if ($get['id']>0) {
		$root = ROOT_DIR . 'files/languages/' . $get['id'] . '/dictionary';
		if (is_dir($root) && $handle = opendir($root)) {
			while (false !== ($file = readdir($handle))) {
				if (strlen($file) > 2)
					include(ROOT_DIR . 'files/languages/' . $get['id'] . '/dictionary/' . $file);
			}
		}
	}
}

//правила удаления для многоязычного
$delete['confirm'] = array('pages'=>'language');
$delete['delete'] = array(
	"ALTER TABLE `shop_products` DROP `name".$get['id']."`",
	"ALTER TABLE `shop_products` DROP `text".$get['id']."`",
);


//вкладки
$tabs = array(
	0 => 'Общее',
	1 => 'Олимпиады',
	2 => 'Тв. конкурсы',
	3 => 'Публикации',
	4 => 'Конкурс учителей',
	5 => 'Сертификаты',
	6 => 'Рецензии',
	7 => 'Конференции',
	8 => 'Формы',
	9 => 'Профайл',
);

//$form[0][] = lang_form('input td12','common|site_name','название сайта');
$form[0][] = lang_form('textarea td12','common|txt_meta','metatag');
/*
$form[0][] = lang_form('textarea td12','common|txt_head','текст в шапке');
$form[0][] = lang_form('textarea td12','common|txt_index','текст на главной');
$form[0][] = lang_form('input td12','common|info','информация');
$form[0][] = lang_form('textarea td12','common|social','социальные кнопки');
$form[0][] = lang_form('textarea td12','common|txt_footer','текст в подвале');
*/
$form[0][] = lang_form('input td12','common|str_no_page_name','название страницы 404');
$form[0][] = lang_form('textarea td12','common|txt_no_page_text','текст страницы 404');
/*
$form[0][] = lang_form('input td12','common|wrd_more','подробнее');
$form[0][] = lang_form('input td12','common|msg_no_results','нет результатов');
$form[0][] = lang_form('input td12','common|wrd_no_photo','нет картинки');
$form[0][] = lang_form('input td4','common|breadcrumb_index','хлебные крошки: на главную');
$form[0][] = lang_form('input td4','common|breadcrumb_separator','хлебные крошки: разделитель');
$form[0][] = lang_form('input td4','common|make_selection','сделайте выбор');
*/
$form[1][] = lang_form('input td12','olympiads|summarizing','подведение итогов');
$form[1][] = '<h2>Сообщения в форме</h2>';
$form[1][] = lang_form('input td12','olympiads|fio1','ФИО преподавателя');
$form[1][] = lang_form('input td12','olympiads|fio2','ФИО участника');
$form[1][] = lang_form('input td12','olympiads|workplace','учреждение');
$form[1][] = lang_form('input td12','olympiads|add_result','добавить результат');
$form[1][] = lang_form('input td12','olympiads|add_teacher','добавить преподавателя');
$form[1][] = lang_form('input td12','olympiads|upload','загрузить');
$form[1][] = lang_form('input td12','olympiads|upload_txt','загрузите файл');
$form[1][] = lang_form('input td12','olympiads|send','отправить');

$form[2][] = lang_form('input td12','contests|certificate','скачать сертификат');
$form[2][] = '<h2>Сообщения в форме</h2>';
$form[2][] = lang_form('input td12','contests|filltheform','заполните форму');
$form[2][] = lang_form('input td12','contests|fio1','ФИО координатора');
$form[2][] = lang_form('input td12','contests|fio2','ФИО участника');
$form[2][] = lang_form('input td12','contests|workplace','учреждение');
$form[2][] = lang_form('input td12','contests|city','населенный пункт');
$form[2][] = lang_form('input td12','contests|name','название');
$form[2][] = lang_form('input td12','contests|comment','комментарий');
$form[2][] = lang_form('input td12','contests|upload','загрузить');
$form[2][] = lang_form('input td12','contests|upload_txt','загрузите файл');
$form[2][] = lang_form('input td12','contests|videolink','ссылка на видео');
$form[2][] = lang_form('input td12','contests|send','отправить');

$form[3][] = lang_form('textarea td12','common|publications_toptext','текст сверху');
$form[3][] = lang_form('input td6','common|publications_period','выдача свидетельства');
$form[3][] = lang_form('input td6','common|publications_price','стоимость публикации');
$form[3][] = lang_form('input td12','publications|certificate','скачать сертификат');
$form[3][] = '<h2>Сообщения в форме</h2>';
$form[3][] = lang_form('input td12','publications|filltheform','заполните форму');
$form[3][] = lang_form('input td12','publications|fio','ФИО');
$form[3][] = lang_form('input td12','publications|workplace','учреждение');
$form[3][] = lang_form('input td12','publications|name','название');
$form[3][] = lang_form('input td12','publications|upload','загрузить');
$form[3][] = lang_form('input td12','publications|upload_txt','загрузите файл');
$form[3][] = lang_form('input td12','publications|send','отправить');

$form[4][] = lang_form('input td6','common|tests_period1','прием работ');
$form[4][] = lang_form('input td6','common|tests_period2','подведение итогов');
$form[4][] = lang_form('input td12','tests|certificate','скачать сертификат');
$form[4][] = '<h2>Сообщения в форме</h2>';
$form[4][] = lang_form('input td12','tests|filltheform','заполните форму');
$form[4][] = lang_form('input td12','tests|fio','ФИО');
$form[4][] = lang_form('input td12','tests|workplace','учреждение');
$form[4][] = lang_form('input td12','tests|city','населенный пункт');
$form[4][] = lang_form('input td12','tests|name','название');
$form[4][] = lang_form('input td12','tests|comment','комментарий');
$form[4][] = lang_form('input td12','tests|upload','загрузить');
$form[4][] = lang_form('input td12','tests|upload_txt','загрузите файл');
$form[4][] = lang_form('input td12','tests|send','отправить');

$form[5][] = lang_form('input td12','certificates|text_form','текст над формой');
$form[5][] = lang_form('input td12','certificates|text_list','текст над списком');
$form[5][] = lang_form('input td12','certificates|price','стоимость');
$form[5][] = lang_form('input td12','certificates|add','добавить заявку');
$form[5][] = '<h2>Сообщения в форме</h2>';
$form[5][] = lang_form('input td12','certificates|filltheform','заполните форму');
$form[5][] = lang_form('input td12','certificates|fio','ФИО');
$form[5][] = lang_form('input td12','certificates|workplace','учреждение');
$form[5][] = lang_form('input td12','certificates|comment','комментарий');
$form[5][] = lang_form('input td12','certificates|send','отправить');

$form[6][] = lang_form('input td12','reviews|text_form','текст над формой');
$form[6][] = lang_form('input td12','reviews|text_list','текст над списком');
$form[6][] = lang_form('input td12','reviews|price1','стоимость 1');
$form[6][] = lang_form('input td12','reviews|price2','стоимость 2');
$form[6][] = lang_form('input td12','reviews|add','добавить заявку');
$form[6][] = '<h2>Сообщения в форме</h2>';
$form[6][] = lang_form('input td12','reviews|filltheform','заполните форму');
$form[6][] = lang_form('input td12','reviews|fio','ФИО');
$form[6][] = lang_form('input td12','reviews|workplace','учреждение');
$form[6][] = lang_form('input td12','reviews|position','должность');
$form[6][] = lang_form('input td12','reviews|name','название');
$form[6][] = lang_form('input td12','reviews|upload','загрузить');
$form[6][] = lang_form('input td12','reviews|upload_txt','загрузите файл');
$form[6][] = lang_form('input td12','reviews|send','отправить');

$form[7][] = lang_form('input td12','conferences|confname','название конференции');
$form[7][] = lang_form('input td12','conferences|text_form','краткое описание');
$form[7][] = lang_form('input td12','conferences|text_list','текст над списком');
$form[7][] = lang_form('input td12','conferences|price','стоимость');
$form[7][] = lang_form('input td12','conferences|add','добавить заявку');
$form[7][] = '<h2>Сообщения в форме</h2>';
//$form[7][] = lang_form('input td12','conferences|filltheform','заполните форму');
$form[7][] = lang_form('input td12','conferences|fio','ФИО');
$form[7][] = lang_form('input td12','conferences|workplace','учреждение');
$form[7][] = lang_form('input td12','conferences|position','должность');
//$form[7][] = lang_form('input td12','conferences|city','город');
$form[7][] = lang_form('input td12','conferences|section','секция');
$form[7][] = lang_form('input td12','conferences|name','название');
//$form[7][] = lang_form('input td12','conferences|email','email');
$form[7][] = lang_form('input td12','conferences|upload','загрузить');
$form[7][] = lang_form('input td12','conferences|upload_txt','загрузите файл');
$form[7][] = lang_form('input td12','conferences|send','отправить');



$form[8][] = '<h2>Форма обратной связи</h2>';
$form[8][] = lang_form('input td12','feedback|name','имя');
$form[8][] = lang_form('input td12','feedback|email','еmail');
$form[8][] = lang_form('input td12','feedback|text','сообщение');
$form[8][] = lang_form('input td12','feedback|send','отправить');
$form[8][] = lang_form('input td12','feedback|message_is_sent','сообщение отправлено');
$form[8][] = lang_form('input td12','feedback|error','ошибка');
$form[8][] = '<h2>Сообщения в формах</h2>';
$form[8][] = lang_form('input td12','validate|no_required_fields','не заполнены обязательные поля');
$form[8][] = lang_form('input td12','validate|short_login','короткий логин');
$form[8][] = lang_form('input td12','validate|not_valid_login','некорректный логин');
$form[8][] = lang_form('input td12','validate|not_valid_email','некорректный email');
$form[8][] = lang_form('input td12','validate|not_valid_password','некорректный пароль');
$form[8][] = lang_form('input td12','validate|not_valid_captcha','некорректный защитный код');
$form[8][] = lang_form('input td12','validate|not_valid_captcha2','отключены скрипты');
$form[8][] = lang_form('input td12','validate|error_email','ошибка при отправке письма');
$form[8][] = lang_form('input td12','validate|no_email','в базе нету такого email');
$form[8][] = lang_form('input td12','validate|duplicate_login','дублирование логина');
$form[8][] = lang_form('input td12','validate|duplicate_email','дублирование email');
$form[8][] = lang_form('input td12','validate|not_match_passwords','пароли не совпадают');
$form[8][] = lang_form('input td12','validate|unapproved_terms','несогласие с правилами');
$form[8][] = lang_form('input td12','validate|wrong_file','неверный формат файла');
$form[8][] = lang_form('input td12','validate|user_banned','аккаунт пользователя забанен');

$form[9][] = '<h2>Форма авторизации/регистрации/редактирования</h2>';
$form[9][] = lang_form('input td12','profile|hello','здравствуйте');
$form[9][] = lang_form('input td12','profile|link','личный кабинет');
$form[9][] = lang_form('input td12','profile|user_edit','личные данные');
$form[9][] = lang_form('input td12','profile|orders','заказы');
$form[9][] = lang_form('input td12','profile|certificates','сертификаты');
$form[9][] = lang_form('input td12','profile|reviews','рецензии');
$form[9][] = lang_form('input td12','profile|conferences','конференции');
$form[9][] = lang_form('input td12','profile|help','помощь');
$form[9][] = lang_form('input td12','profile|exit','выйти');
$form[9][] = lang_form('input td3','profile|email','еmail');
$form[9][] = lang_form('input td3','profile|password','пароль');
$form[9][] = lang_form('input td3','profile|password2','подтв. пароль');
$form[9][] = lang_form('input td3','profile|new_password','новый пароль');
$form[9][] = lang_form('input td3','profile|save','сохранить');
$form[9][] = lang_form('input td3','profile|registration','регистрация');
$form[9][] = lang_form('input td3','profile|enter','войти');
$form[9][] = lang_form('input td3','profile|remember_me','запомнить меня');
$form[9][] = lang_form('input td3','profile|auth','авторизация');
$form[9][] = lang_form('input td3','profile|remind','забыли пароль');
$form[9][] = lang_form('textarea td12','profile|terms','правила');
$form[9][] = lang_form('input td12','profile|successful_registration','успешная регистрация');
$form[9][] = lang_form('input td12','profile|successful_auth','успешная авторизация');
$form[9][] = lang_form('input td12','profile|error_auth','ошибка авторизации');
$form[9][] = lang_form('input td12','profile|msg_exit','Вы вышли!');
$form[9][] = lang_form('input td12','profile|go_to_profile','перейти в профиль');
$form[9][] = '<h2>Восстановление пароля</h2>';
$form[9][] = lang_form('input td12','profile|remind_button','отправить письмо по восстановлению пароля');
$form[9][] = lang_form('input td12','profile|successful_remind','отправлено письмо по восстановлению пароля');

/*
$form[4][] = '<h2>Оплата</h2>';
$form[4][] = lang_form('input td12','order|payments','оплата');
$form[4][] = lang_form('input td12','order|pay','оплатить');
$form[4][] = lang_form('input td12','order|paid','оплачен');
$form[4][] = lang_form('input td12','order|not_paid','не плачен');
$form[4][] = lang_form('textarea td12','order|success','успешная оплата');
$form[4][] = lang_form('textarea td12','order|fail','отказ оплаты');
*/

/*
$form[3][] = lang_form('input td3','shop|catalog','каталог');
$form[3][] = lang_form('input td3','shop|new','новинки');
$form[3][] = lang_form('input td3','shop|brand','производитель');
$form[3][] = lang_form('input td3','shop|article','артикул');
$form[3][] = lang_form('input td3','shop|parameters','параметры');
$form[3][] = lang_form('input td3','shop|price','цена');
$form[3][] = lang_form('input td3','shop|currency','валюта');
$form[3][] = lang_form('input td3','shop|product_random','случайный товар');
$form[3][] = lang_form('input td3','shop|filter_button','искать');
$form[3][] = '<h2>Отзывы</h2>';
$form[3][] = lang_form('input td3','shop|reviews','Отзывы');
$form[3][] = lang_form('input td3','shop|review_add','Оставить отзыв');
$form[3][] = lang_form('input td3','shop|review_name','имя');
$form[3][] = lang_form('input td3','shop|review_email','еmail');
$form[3][] = lang_form('input td3','shop|review_text','сообщение');
$form[3][] = lang_form('input td3','shop|review_send','отправить');
$form[3][] = lang_form('input td12','shop|review_is_sent','отзыв добавлен');

$form[4][] = lang_form('input td3','basket|buy','купить');
$form[4][] = lang_form('input td3','basket|basket','корзина');
$form[4][] = lang_form('input td12','basket|empty','пустая корзина');
$form[4][] = lang_form('input td12','basket|go_basket','перейти в корзину');
$form[4][] = lang_form('input td12','basket|go_next','продолжить покупки');
$form[4][] = lang_form('input td12','basket|product_added','товар добавлен');
$form[4][] = '<h2>Таблица товаров</h2>';
$form[4][] = lang_form('input td3','basket|product_id','id товара');
$form[4][] = lang_form('input td3','basket|product_name','название товара');
$form[4][] = lang_form('input td3','basket|product_price','цена');
$form[4][] = lang_form('input td3','basket|product_count','количество');
$form[4][] = lang_form('input td3','basket|product_summ','сумма');
$form[4][] = lang_form('input td3','basket|product_cost','стоимость');
$form[4][] = lang_form('input td3','basket|product_delete','удалить');
$form[4][] = lang_form('input td3','basket|total','итого');
$form[4][] = '<h2>Параметры заказа</h2>';
$form[4][] = lang_form('input td3','basket|profile','личные данные');
$form[4][] = lang_form('input td3','basket|delivery','доставка');
$form[4][] = lang_form('input td3','basket|delivery_cost','стоимость доставки');
$form[4][] = lang_form('input td3','basket|comment','коммен к заказу');
$form[4][] = lang_form('input td3','basket|order','оформить заказ');
$form[4][] = '<h2>Статистика заказов</h2>';
$form[4][] = lang_form('input td3','basket|orders','статистика заказов');
$form[4][] = lang_form('input td3','basket|order_name','заказ');
$form[4][] = lang_form('input td3','basket|order_from','от');
$form[4][] = lang_form('input td3','basket|order_status','статус');
$form[4][] = lang_form('input td3','basket|order_date','дата');
$form[4][] = lang_form('input td3','basket|view_order','просмотр заказа');

$form[5][] = 'Полное описание можно найти на странице <a target="_balnk" href="http://help.yandex.ru/partnermarket/shop.xml">http://help.yandex.ru/partnermarket/shop.xml</a><br /><br />';
$form[5][] = lang_form('input td12','market|name','Короткое название магазина');
$form[5][] = lang_form('input td12','market|company','Полное наименование компании');
$form[5][] = lang_form('input td12','market|currency','Валюта магазина');

$form[6][] = '<h2>Основной шаблон автоматического письма</h2>';
$form[6][] = lang_form('textarea td12','common|letter_top','Текст в шапке письма');
$form[6][] = lang_form('textarea td12','common|letter_footer','Текст в подвале письма');
$form[6][] = '<h2>Основной шаблон письма рассылки</h2>';
$form[6][] = lang_form('textarea td12','subscribe|top','Текст в шапке рассылки');
$form[6][] = lang_form('textarea td12','subscribe|bottom','Текст в подвале рассылки');
$form[6][] = lang_form('input td8','subscribe|letter_failure_str','Если вы хотите отписаться от рассылки нажмите на');
$form[6][] = lang_form('input td4','subscribe|letter_failure_link','ссылку');
$form[6][] = '<h2>Подписка</h2>';
$form[6][] = lang_form('input td12','subscribe|on_button','Подписаться');
$form[6][] = lang_form('input td12','subscribe|on_success','Вы успешно подписаны');
$form[6][] = lang_form('input td12','subscribe|failure_text','Подтвердите, что хотите отписаться');
$form[6][] = lang_form('input td12','subscribe|failure_button','Отписаться');
$form[6][] = lang_form('input td12','subscribe|failure_success','Вы отписаны');
*/

function lang_form($type,$key,$name) {
	global $lang;
	$key = explode('|',$key);
	return array ($type,'dictionary['.$key[0].']['.$key[1].']',isset($lang[$key[0]][$key[1]]) ? $lang[$key[0]][$key[1]] : '',array('name'=>$name.' <b>'.$key[0].'|'.$key[1].'</b>','title'=>$key[0].'|'.$key[1]));
}

function after_save() {
	global $get,$config;
	if (is_dir(ROOT_DIR . 'files/languages/' . $get['id'] . '/dictionary') || mkdir(ROOT_DIR . 'files/languages/' . $get['id'] . '/dictionary', 0755, true)) {
		foreach ($_POST['dictionary'] as $key => $val) {
			$str = '<?php' . PHP_EOL;
			$str .= '$lang[\'' . $key . '\'] = array(' . PHP_EOL;
			foreach ($val as $k => $v) {
				$str .= "	'" . $k . "'=>'" . str_replace("'", "\'", $v) . "'," . PHP_EOL;
			}
			$str .= ');';
			$str .= '?>';
			$fp = fopen(ROOT_DIR . 'files/languages/' . $get['id'] . '/dictionary/' . $key . '.php', 'w');
			fwrite($fp, $str);
			fclose($fp);
		}
	}
	//если мультиязычный то нужно добавлять колонки в мультиязычные таблицы
	if ($config['multilingual']) {
		if ($_GET['id'] == 'new') {
			mysql_query("ALTER TABLE `shop_products` ADD `name".$get['id']."` VARCHAR( 255 ) NOT NULL AFTER `name`");
			mysql_query("ALTER TABLE `shop_products` ADD `text".$get['id']."` TEXT NOT NULL AFTER `text`");
		}
	}
}

