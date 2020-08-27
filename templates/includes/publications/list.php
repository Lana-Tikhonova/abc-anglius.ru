          <div class='block1'>
            <div class='txt'>
              <span><?=sprintf("%04d",$q['id'])?></span><br>
              <b><?=$q['name']?></b><br>
              <?=$q['fio']?>
            </div>
            <a class='particip gradient1 text-center border5' style='margin-left:0px;margin-top:6px' href='/<?=$modules['publications']?>/<?=$q['id']?>/'>Перейти к публикации</a>
            <div class='clear-both'></div>
          </div>
