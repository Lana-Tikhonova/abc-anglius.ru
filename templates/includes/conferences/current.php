	<h1><?= $q['name'] ?></h1>
	<div class='info'>
	  <div>
	    <?php /*
          <div class='info1'><img src='/templates/assets/imgs/calendar.png'><i>Работы принимаются</i><span class='info2'><?=i18n('common|conferences_period1')?></span></div>
          <div class='info1 left28'><i>Подведение итогов</i><span class='info2'><?=i18n('common|conferences_period2')?></span></div>
*/ ?>
	    <div class='info3'>Приняв участие в конференции, Вы получаете: участие в двух мероприятиях и 3 документа международного уровня!!!<br>1.Свидетельство о публикации в электронном сборнике;<br>2. Диплом с указанием призового места соответствующего работе;<br>3. Сертификат участника конференции<br>Наградные документы будут доступны в личном кабинете сразу после оплаты участия в конференции.</div>
	    <div class='info3'><?= $q['text'] ?></div>
	  </div>

	  <div class="offer">
	    <h4>Стоимость участия</h4>
	    <div class="offer-price"><span><?= $q['price'] ?></span> руб.</div>
	    <a href="add" class="button -secondary">Принять участие</a>
	  </div>
	</div>
	<?php
  echo html_query('conferences/current2', "
    SELECT *
    FROM orders
    WHERE type=6 AND paid=1 AND parent=" . $q['id'] . "
    ORDER BY date_paid ASC", '', 60 * 60, 'json');
  ?>