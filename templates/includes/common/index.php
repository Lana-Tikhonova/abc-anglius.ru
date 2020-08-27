  <div class='bigimg2'>
    <div class="index-six">
        <img src="/templates/images/6.png" alt="6+">
    </div>
      
    <div class='container text-center'>
      <div class='headidx1'>МЕЖДУНАРОДНЫЙ ПОРТАЛ</div>
      <div class='headidx2'>ДИСТАНЦИОННЫХ ПРОЕКТОВ ПО АНГЛИЙСКОМУ ЯЗЫКУ "АНГЛИУС"</div>
      <div class='headtbl'>
        <div class='tr'>
          <div class='td'><img src='/templates/images/icon-1.png'><div>МЕЖДУНАРОДНЫЕ КОНКУРСЫ</div></div>
          <div class='td'><img src='/templates/images/icon-2.png'><div>ДИСТАНЦИОННОЕ УЧАСТИЕ</div></div>
          <div class='td'><img src='/templates/images/icon-3.png'><div>СЕРТИФИКАТЫ И ДИПЛОМЫ</div></div>
        </div>
      </div>
    </div>
  </div>

  <div class='container lastk'>
    <div class='col1'>
      <div class='head'>ПОСЛЕДНИЕ КОНКУРСЫ</div>
<?=html_query('olympiads/list','
	select id,name,url,img,shortdesc,price,price2,date2,teacher from olympiads
	where display=1 order by rank desc',
'');
?>
    </div>
    <div class='col2'>
<?php include(ROOT_DIR.'/templates/includes/common/col2.php');?>
    </div>
    <div class='clear-both'></div>
    <div class='tower'>
      <div class='head'>СМОТРЕТЬ ВСЕ</div>
      <div class='buttons'>
        <a href='/<?=$modules['olympiads']?>/' class='border5 gradient1'>ОЛИМПИАДЫ</a>
        <a href='/<?=$modules['tests']?>/' class='border5 gradient1'>КОНКУРСЫ УЧИТЕЛЕЙ</a>
        <a href='/<?=$modules['publications']?>/' class='border5 gradient1'>ПУБЛИКАЦИИ</a>
        <div style='margin:auto;width:494px;'>
        <a href='/<?=$modules['contests']?>/' class='border5 gradient1'>ТВОРЧЕСКИЕ КОНКУРСЫ</a>
        <a href='/<?=$config['conferences']?>/' class='border5 gradient1'>КОНФЕРЕНЦИИ</a>
        </div>
      </div>
    </div>
  </div>

  <div class='container conditions1'></div>
  <div class='conditions2'>
      <div class='container'>
        <ul>
          <li>Участники, набравшие наибольшее количество баллов становятся Победителями (не менее 90 % от максимального количества баллов).</li>
          <li>Участники, набравшие более 60% от максимального количества баллов, становятся Лауреатами.</li>
          <li>Участники, набравшие менее 60 % становятся Участниками.</li>
          <li>Победители и Лауреаты награждаются электронными дипломами победителей и лауреатов, Участники награждаются электронными сертификатами за участие.</li>
          <li>Педагоги, подготовившие Лауреатов и Победителей, получают Благодарственные письма.</li>
        </ul>
      </div>
  </div>
