<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title><?=$page['title']?></title>
<meta name="description" content="<?=$page['description']?>" />
<meta name="keywords" content="<?=$page['keywords']?>" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/png" href="favicon.png" />
<?=html_sources('return','font.css common.css jquery.js')?>
<?=i18n('common|txt_meta')?>
<?=access('editable scripts') ? html_sources('return','tinymce.js editable.js') : ''?>
<?=html_sources('head')?>
<?=html_sources('return','common.js')?>
</head>
<body class="reg">
  <div class='container header'>
    <a class='logo' href='/'>
      <img src='/templates/images/logo.png' alt='Англиус. Международный портал дистанционных проектов по английскому языку' title='English-Olimp. Международный портал дистанционных проектов по английскому языку' title='English-Olimp. Международный портал дистанционных проектов по английскому языку'>
    </a>
  </div>
  <div class="container">
    <?=$page['text']!=''?"<div class='message'>".$page['text'].'</div>':''?>
    <div class='about'><?=$page['title']?></div>
    <?=$html['content']?>
  </div>
</body>
</html>