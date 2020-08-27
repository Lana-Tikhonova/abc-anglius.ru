  <div class='bigimg'>
    <div class='container'>
      <img src='/templates/images/current.jpg'>
      <div class='txt'>
        <div class='block' style='background-image:url(/templates/images/sandclock.png)'>
          <div class='table'>
            <div class='tr'>
              <div class='td' style='width:275px;'><b>СВИДЕТЕЛЬСТВО ВЫДАЕТСЯ</b></div>
              <div class='td' style='width:345px;'><?=i18n('common|publications_period')?></div>
            </div>
            <div class='tr'>
              <div class='td'><b>КАЖДОМУ УЧАСТНИКУ</b></div>
              <div class='td'>сертификат по ИКТ-компетентности в подарок!</div>
            </div>
          </div>
        </div>
        <div class='block' style='background-image:url(/templates/images/infored.png)'>
          <div class='table'>
            <div class='tr'>
              <div class='td' style='width:275px;'><b>СТОИМОСТЬ УЧАСТИЯ</b></div>
              <div class='td' style='width:345px;'><?=i18n('common|publications_price')?> рублей.</div>
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
            <p>Стоимость получения свидетельства о публикации<br><span><?=i18n('common|publications_price')?></span> руб.</p>
         </div>
         <div class="div2"><a href="add/" class="gradient3 text-center border5">ПРИНЯТЬ УЧАСТИЕ</a></div>
       </form>
<?php } else { ?>
       <div class='div3'>Данная операция доступна только для зарегистрированных пользователей.<br>
       Вам необходимо <a href='/<?=$modules['login']?>/?returl=<?=urlencode($_SERVER['REQUEST_URI'])?>'>войти</a><br>или <a href='/<?=$modules['registration']?>/'>зарегистрироваться</a></div>
<?php } ?>
    </div>             
      
    <div class='col2 pub'>
        <?= (empty($u[2])) ? $page['text'] : ''?>
        
<?php echo html_query('publications/list pub',"
    SELECT id,file,fio,name
    FROM publications
    WHERE display=1
    ORDER BY id DESC
",'',60*60,'json');
?>
    </div>
    <div class='clear-both'></div>
  </div>
