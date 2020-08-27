<?php
if (isset($q['page']) && isset($q['module']) && is_array(@$q['page']) && is_array(@$q['module'])) $breadcrumb = array_merge($q['module'],$q['page']);
elseif (isset($q['page']) && is_array($q['page']))  $breadcrumb = $q['page'];
elseif (isset($q['module']) && is_array($q['module'])) $breadcrumb = $q['module'];
if($breadcrumb){
  array_push($breadcrumb, array('Главная','/'));
  $count = count($breadcrumb)-1;
  $content = '';
  for ($i = $count; $i>0; $i--) {
	$content.= '<a href="'.$breadcrumb[$i][1].'">'.$breadcrumb[$i][0].'</a>&nbsp;&gt;&nbsp;';//ссылка
  }
  $content.= $breadcrumb[0][0];?>
  <div class='top'>
    <div class='container'>
      <div class='breadcrumbs'><?=$content?></div>
    <?=($u[2]&&in_array($u[1],array($modules['olympiads'],$modules['contests'])))?'':"<div class='about'>".$breadcrumb[0][0]."</div>"?>
<?php 
if (user('auth')&&$u[1]!=$modules['profile']) { ?>
      <a class='border5 gradient3 topbtn' href='/<?=$modules['profile']?>/orders/'>Мои заявки</a>
<?php } ?>
    </div>
  </div>
<?php } ?>
