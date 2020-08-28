<?php
$page['title']			= isset($page['title']) ? filter_var($page['title'], FILTER_SANITIZE_STRING) : filter_var($page['name'],FILTER_SANITIZE_STRING);
$page['description']	= isset($page['description']) ? filter_var($page['description'], FILTER_SANITIZE_STRING) : $page['title'];
$page['keywords']		= isset($page['keywords']) ? filter_var($page['keywords'], FILTER_SANITIZE_STRING) : $page['title'];

if(in_array($html['module'],array($modules['registration'],$modules['login'],$modules['remind']))){
  include(ROOT_DIR.$config['style'].'/includes/common/template_p.php');exit;
}

?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title><?=$page['title']?></title>
<meta name="description" content="<?=$page['description']?>" />
<meta name="keywords" content="<?=$page['keywords']?>" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/png" href="favicon.png" />
<?=html_sources('return','font.css common.css jquery.js sisyphus.js')?>
<?=i18n('common|txt_meta')?>
<?=access('editable scripts') ? html_sources('return','tinymce.js editable.js') : ''?>
<?=html_sources('head')?>
<?=html_sources('return','common.js')?>
</head>

<body>
<div class="backdrop"></div>
  <div class='container header'>
    <a class='logo' href='/'>
      <img src='/templates/images/logo.png' alt='Англиус. Международный портал дистанционных проектов по английскому языку' title='Англиус. Международный портал дистанционных проектов по английскому языку'>
    </a>
      <div class="mobile-menu__btn">
        <div class="mobile-menu__icon"></div>
      </div>
      <div class="mobile-menu menu">
        <div class="mobile-menu__btn_close">
          <div class="mobile-menu__icon_close"></div>
        </div>
        <h3 class="mobile-menu__title">Меню</h3>
        <?php echo html_query('menu/top',"
          SELECT name,url,id,img
          FROM pages
          WHERE level=1 AND display=1 AND menu = 1
          ORDER BY left_key
        ",'',60*60,'json');
        ?>	
        <?php echo html_query('menu/top2',"
          SELECT name,url,id,img
          FROM pages
          WHERE level=1 AND display=1 AND menu3 = 1
          ORDER BY left_key
        ",'',60*60,'json');
        ?>
    </div>
    <a class='mail' href='mailto:info@anglius.ru'>info@anglius.ru</a>
<?php if (access('user auth')) {
  $userfields=unserialize($user['fields']);
?>
    <div class='loggedu'>
      <div class='float-left'><a href="/<?=$modules['profile']?>/"><img src='/templates/images/user.png'></a></div>
      <div class='float-left'><a href="/<?=$modules['profile']?>/"><?=$userfields[1][0]?> <?=$userfields[3][0]?><br><span><?=$user['email']?></span></a><a class='exit' href='/<?=$modules['login']?>/exit/'><span>(Выйти)</span></a></div>
      <div class='clear-both'></div>
    </div>

<?php } else { ?>
    <a href='/<?=$modules['registration']?>/'><div class='register'>Зарегистрироваться</div></a>
    <a href='/<?=$modules['profile']?>/'><div class='enter'>Войти</div></a>
<?php } ?>
    <div class='clear-both'></div>
	<div class="header-text-top"><?=i18n('common|smi')?></div>
  </div>
  <div class='menu'>
    <div class='container'>		
		<?php echo html_query('menu/top',"
			SELECT name,url,id,img
			FROM pages
			WHERE level=1 AND display=1 AND menu = 1
			ORDER BY left_key
		",'',60*60,'json');
		?>		
    </div>
  </div>
  <div class='menu'>
    <div class='container'>		
		<?php echo html_query('menu/top2',"
			SELECT name,url,id,img
			FROM pages
			WHERE level=1 AND display=1 AND menu3 = 1
			ORDER BY left_key
		",'',60*60,'json');
		?>
    </div>
  </div>

<?php
if ($html['module']=='index') include(ROOT_DIR.$config['style'].'/includes/common/index.php');
elseif (file_exists(ROOT_DIR.$config['style'].'/includes/'.$html['module'].'/template.php')) {
  if (isset($html['condition'])) {$page['condition']=1;}
  if (isset($breadcrumb)) echo html_array('common/breadcrumb',$breadcrumb);
  include(ROOT_DIR.$config['style'].'/includes/'.$html['module'].'/template.php');
}
?>

  <div class='footer'>
    <div class='container'>
      <div class='copyright'>
        &copy; 2015 Все права защищены. Копирование контента без разрешения автора строго ЗАПРЕЩЕНО!<br>
        Все конкурсы, олимпиады и викторины являются авторскими и права на них принадлежат владельцам сайта
      </div>
      <div class='ccards'><img src='/templates/images/ccards.png'></div>
      <div class='devep'>Разработка сайта<br><a href='http://devep.ru/'>devep.ru</a></div>
      <div class='clear-both'></div>
    </div>
  </div>
  <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {
        //открытие меню при клике на бургер
        $('.mobile-menu__btn').on('click', function () {
            $('.mobile-menu').addClass('active');
            $('.backdrop').addClass('active');
            $('body').addClass('locked');
        });
        //закрытие меню при клике на крестик
        $('.mobile-menu__btn_close').on('click', function () {
            $('.mobile-menu').removeClass('active');
            $('.backdrop').removeClass('active');
            $('body').removeClass('locked');
        });
    });
</script>
  <script type="text/javascript">(function() {
  if (window.pluso)if (typeof window.pluso.start == "function") return;
  if (window.ifpluso==undefined) { window.ifpluso = 1;
    var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
    s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
    s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
    var h=d[g]('body')[0];
    h.appendChild(s);
  }})();</script>
  <div style='position:fixed;right:10px;bottom:10px;' class="pluso" data-background="none;" data-options="medium,square,line,horizontal,counter,sepcounter=1,theme=14" data-services="vkontakte,odnoklassniki,facebook,google,moimir,yandex,email,print" data-url="http://anglius.ru/"; data-title="МЕЖДУНАРОДНЫЙ ПОРТАЛ ДИСТАНЦИОННЫХ ПРОЕКТОВ ПО АНГЛИЙСКОМУ ЯЗЫКУ" data-description="Международные олимпиады, конкурсы, викторины по английскому языку. Конкурсы и олимпиады для учителей английского языка."></div> 

</body>
</html>