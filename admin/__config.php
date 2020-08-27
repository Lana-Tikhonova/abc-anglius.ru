<?php

$config['cms_version'] = '1.0.5';

$config['admin_lang'] = 'ru'; //язык админпанели

$config['multilingual'] = false; //многоязычный сайт

$config['merchants'] = array(
	1 => 'наличный рассчет',
	2 => 'robokassa [все платежи]',
	3 => 'robokassa [yandex]',
	4 => 'robokassa [wmr]',
	5 => 'robokassa [qiwi]',
	6 => 'robokassa [терминал]',
	7 => 'robokassa [банковской картой]',
);
$config['payments'] = array(
	1 => 'наличный рассчет',
	2 => 'robokassa',
	3 => 'yandex'
);

$config['depend'] = array(
	//'shop_products'=>array('categories'=>'shop_products-categories'),
);

//зеркальные модули
$config['mirrors'] = array(
	'online_olympiads91'=>'online_olympiads',
	'online_olympiads92'=>'online_olympiads',
	'online_olympiads93'=>'online_olympiads',
	'online_olympiads94'=>'online_olympiads',
	'online_olympiads91_tests'=>'online_olympiads_tests',
	'online_olympiads92_tests'=>'online_olympiads_tests',
	'online_olympiads93_tests'=>'online_olympiads_tests',
	'online_olympiads94_tests'=>'online_olympiads_tests'
);

$config['boolean'] = array(
	'boolean','display','market','yandex_index',
);

$modules_admin = array(
	'pages'			=> 'pages',
	'olympiads' => array(
		'olympiads'=>'olympiads',
		'olympiads_tests'=>'olympiads_tests',
	),
	'contests'=>'contests',
	'publications' => 'publications',
	'tests'	=> 'tests',
        'online_olympiads' => [
            'online_olympiads91_tests'=>'online_olympiads91_tests',
            'online_olympiads91'=>'online_olympiads91',
            //'online_olympiads92_tests'=>'online_olympiads92_tests',
            //'online_olympiads92'=>'online_olympiads92',
            'online_olympiads93_tests'=>'online_olympiads93_tests',
            'online_olympiads93'=>'online_olympiads93',
            'online_olympiads94_tests'=>'online_olympiads94_tests',
            'online_olympiads94'=>'online_olympiads94',
            
            'online_olympiads_categories'=>'online_olympiads_categories',
        ],
//	'news'		=> 'news',
//	'gallery' => array(
//		'gallery'	=> 'gallery',
//		'slider'	=> 'slider',
//	),
	'dictionary'	=> 'languages',
	'feedback'=>'feedback',
//  
//	'catalog' => array(
//		'shop_products'	=> 'shop_products',
//		//'shop_items'	=> 'shop_items',
//		'shop_categories'	=> 'shop_categories',
//		'shop_brands'	=> 'shop_brands',
//		'shop_parameters'	=> 'shop_parameters',
//		'shop_reviews'	=> 'shop_reviews',
//	),
//	'synchronization'=>array(
//		'export'	=> 'shop_export',
//		'import'	=> 'shop_import',
//	),
//	'shop' => array(
		'orders'			=> 'orders',
//		'order_types'		=> 'order_types',
//		'order_deliveries'	=> 'order_deliveries',
//		'order_payments'	=> 'order_payments',
//	),
	'users' => array(
		'users'	=> 'users',
		'user_types'	=> 'user_types',
		'user_fields'	=> 'user_fields',
	),
//	'subscribe'=>array(
//		'subscribers'		=> 'subscribers',
//		'subscribe_letters'	=> 'subscribe_letters',
//		'letters'			=> 'letters',
//	),

	'config' => array(
		'config'			=> 'config',
                'cert_templates'	=> 'cert_templates',
		'letter_templates'	=> 'letter_templates',
		'logs'				=> 'logs',
	),
	'design' => array(
		'template_css'	=> 'template_css',
		'template_images'	=> 'template_images',
		'template_includes'	=> 'template_includes',
		'template_scripts'	=> 'template_scripts'
	),
	'backup' => array(
		'backup'	=> 'backup',
		'restore'	=> 'restore'
	),
//	'seo' => array(
//		'redirects'		=> 'redirects',
//		'robots.txt'	=> 'seo_robots',
//		'sitemap.xml'	=> 'seo_sitemap',
//		'.htaccess'		=> 'seo_htaccess',
//		'links'		=> 'seo_links',
//		'pages'		=> 'seo_pages',
//		'import'		=> 'seo_links_import',
//		'export'		=> 'seo_links_export',
//	),
);
?>