<?php $current=$q[intval(explode2('-',$u[2]))];?>
  <div class='bigimg'>
    <div class='container'>
      <img src='/files/olympiads/<?=$current['id']?>/img/m-<?=$current['img']?>'>
      <div class='txt'>
        <div class='head'><?=mb_strtoupper($current['name'],'utf-8')?></div>
        <div class='block' style='background-image:url(/templates/images/sandclock.png)'>
          <div class='table'>
            <div class='tr'>
              <div class='td' style='width:275px;'><b>СРОК ПРИЕМА ЗАЯВОК И РАБОТ</b></div>
              <div class='td' style='width:345px;'>по <?=date2($current['date2'],'d month y')?> года.</div>
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
              <div class='td' style='width:345px;'><?=$current['price']?> руб. От 5 и более участников - <?=$current['price2']?> рублей.</div>
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
            <p>Сумма организационного взноса для одного участника по одному предмету<br><span><?=$current['price']?></span> руб.<br>
            От 5 участников<br><span><?=$current['price2']?></span> руб.</p>

<?php
  if($current['file']){
    if(!$current['teacher']) {
      echo '<label>Участников:</label><input type="number" min="1" name="count" value="1" class="number gradient2"><div class="clear-both"></div>';
    }
    echo '<center><a class="down_olymp" href="/'.$modules['olympiads'].'/'.$current['id'].'/download/?"><span class="arrow">&darr;</span>СКАЧАТЬ ЗАДАНИЕ</a></center>';
  }
?>
         </div>
<?php
  if($current['file']){
    if(isset($user['id'])&&($orderid=mysql_select('select id from orders where paid=0 and user='.$user['id']
    .' and type='.($current['offline']*7+1).' and parent='.$current['id'],'string'))) {
      echo '<div class="div2"><a href="/'.$modules['profile'].'/orders/'.$orderid.'/" class="gradient3 text-center border5">ЗАПОЛНИТЬ РЕЗУЛЬТАТЫ</a></div>';
    }
  }
?>
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
<?=html_query('olympiads/list2',$q,'')?>
    <div class='clear-both'></div>
  </div>
<script>
$("input[name=count]").change(function(){
  $(".down_olymp").each(function() { 
    this.href = this.href.replace(/\/\?(.*?)$/,"/?count="+$("input[name=count]").val());
  });
});

$(".down_olymp").click(function(){
  setTimeout("document.location.reload(true)", 1000);
});
</script>