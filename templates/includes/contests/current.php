<?php $current=$q[intval(explode2('-',$u[2]))];?>
  <div class='bigimg'>
    <div class='container'>
      <img src='/files/contests/<?=$current['id']?>/img/m-<?=$current['img']?>'>
      <div class='txt'>
        <div class='head'><?=mb_strtoupper($current['name'],'utf-8')?></div>
        <div class='block' style='background-image:url(/templates/images/sandclock.png)'>
          <div class='table'>
            <div class='tr'>
              <div class='td' style='width:275px;'><b>СРОК ПРИЕМА ЗАЯВОК И РАБОТ</b></div>
              <div class='td' style='width:345px;'><?=$current['datesof']?>.</div>
            </div>
            <div class='tr'>
              <div class='td'><b>ПОДВЕДЕНИЕ ИТОГОВ</b></div>
              <div class='td'><?=$current['summarizing']?>.</div>
            </div>
          </div>
        </div>
        <div class='block' style='background-image:url(/templates/images/infored.png)'>
          <div class='table'>
            <div class='tr'>
              <div class='td' style='width:275px;'><b>СТОИМОСТЬ УЧАСТИЯ</b></div>
              <div class='td' style='width:345px;'><?=$current['price']?> рублей.</div>
            </div>
          </div>
        </div>
      </div>
      <div class='clear-both'></div>
    </div>
  </div>

  <div class='container current'>
    <div class='col1'>
       <div class='topline text-center'>ПРИНЯТЬ УЧАСТИЕ</div>
<?php if (access('user auth')) { ?>
       <form>
         <div class='div1'>
            <p>Сумма организационного взноса для одного участника по одному предмету<br><span><?=$current['price']?></span> руб.</p>
         </div>
         <div class="div2"><a href="add/" class="gradient3 text-center border5">ПРИНЯТЬ УЧАСТИЕ</a></div>
       </form>
<?php } else { ?>
       <div class='div3'>Данная операция доступна только для зарегистрированных пользователей.<br>
       Вам необходимо <a href='/<?=$modules['login']?>/?returl=<?=urlencode($_SERVER['REQUEST_URI'])?>'>войти</a><br>или <a href='/<?=$modules['registration']?>/'>зарегистрироваться</a></div>
<?php } ?>
    </div>
    <div class='col2'>
       <div><?=$current['text']?></div>
       <b class='head'>ДИПЛОМЫ ПОБЕДИТЕЛЕЙ</b>
       <img src='/templates/images/diplom1.jpg'><img src='/templates/images/diplom2.jpg'><img src='/templates/images/diplom3.jpg'>
    </div>
    <div class='clear-both'></div>
  </div>

  <div class='bottomline'>
    <div class='container'>
      <a href='/<?=$u[1]?>/' class='float-left'>&larr; Вернуться ко всем конкурсам</a>
<?php 
  $next=array();$f=0;
  foreach($q as $k=>$v){
    if($f) {$next=$q[$k];break;}
    if($k==$current['id']) $f=1;
  }
  if(!$next) $next=reset($q);
  unset($q[$current['id']]);
?>
      <a href='/<?=$u[1]?>/<?=$next['id']?>-<?=$next['url']?>/' class='float-right'>Следующий конкурс &rarr;</a>
      <div class='clear-both'></div>
    </div>
  </div>

  <div class='container others'>
    <div class='head text-center'>ДРУГИЕ КОНКУРСЫ</div>
<?=html_query('contests/list2',$q,'')?>
    <div class='clear-both'></div>
  </div>
