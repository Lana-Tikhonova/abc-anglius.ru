<?php $basket= (is_array($q['basket'])) ? $q['basket'] : unserialize($q['basket']); //$basket=unserialize($q['basket']);  ?>
      <div class='questions testdiv result'>
        <div class='congrats'>Поздравляем, Вы ответили на все вопросы!</div>
        <div class='blitz'>Блиц олимпиада «<?=$q['more']['olname']?>»</div>
        <div class='result'>Ваш результат: 
        <b><?=($q['place']==0?'Дипломант':'Победитель '.str_pad('',$q['place'],'I').' степени')?></b></div>
        
        <?if(!$q['paid']):?>
            <div style='margin:0 0px 40px 0px;text-align:justify;'><p>Вам подготовлен наградной документ с уникальным идентификатором "ONL-<?=$u[3]?>".</p>
            <p>После оплаты организационного взноса, в размере <?=$config['price'.$q['type']]?> рублей, документ станет доступным для загрузки в личном кабинете.
            Также будет произведена регистрация Вашего результата в итоговой базе портала.</p>
            <? /*
            <p>Для завершения формирования текущего документа
            необходимо выбрать макет диплома и указать данные участника. Внимательно заполните соответствующие поля:</p>
            */ ?>
            </div>
            <form id='diplomsel' class='form' method='POST' action='/<?=$u[1]?>/<?=$u[2]?>/<?=$u[3]?>/pay/'>
              
                <div class='choise'>Выберите диплом</div>
            <div style='width:100%; display: flex; justify-content: center;'>
            <?php
				//$diploms= mysql_select("SELECT * FROM cert_templates WHERE display = 1 AND type = {$q['type']}", 'rows');				
				$diploms= mysql_select("SELECT * FROM cert_templates WHERE display = 1 AND type = 9", 'rows');				
				foreach(is_array($diploms)?$diploms:[] as $d) { ?>
					<label class='rad'><input type='radio' name='template' value='<?=$d['id']?>' <?=((reset($diploms)==$d)?' checked':'')?> <? //=($d['id']==$basket['template']?' checked':'')?>><i></i>
					<img src="/files/cert_templates/<?=$d['id']?>/diplom/p-<?=$d['diplom']?>"></label>
            <?php } ?>
                
            <div class='clear-both'></div>
            </div> 
                              
            <div class='rightornot'>
                      <input type='submit' class='btn' value='ОПЛАТИТЬ ОРГАНИЗАЦИОННЫЙ ВЗНОС И СКАЧАТЬ ДОКУМЕНТ'>
            </div>
            </form>
        <? else: ?>
            <?= html_array('online_olympiads/results',@$q)?>
        <? endif; ?>
      </div>
