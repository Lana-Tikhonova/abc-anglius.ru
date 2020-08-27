      <div class='block'>
        <img class='img' src='/files/contests/<?=$q['id']?>/img/p-<?=$q['img']?>'>
        <div class='txt'>
         <img class='float-left' src='/templates/images/flag.gif'><div class='ct text-center'>ДЛЯ <?=$q['teacher']?'ПЕДАГОГОВ':'УЧАЩИХСЯ И СТУДЕНТОВ'?></div><div class='clear-both'></div>
         <div class='name'><?=$q['name']?></div>
         <div><?=$q['shortdesc']?></div>
         <div class='info'><img src='/templates/images/info.png'><span><b>СТОИМОСТЬ УЧАСТИЯ</b> <?=$q['price']?> рублей.</span></div>
         <a href='/<?=$modules['contests']?>/<?=$q['id']?>-<?=$q['url']?>/' class='particip text-center gradient1 border5'>ПРИНЯТЬ УЧАСТИЕ</a>
        </div>
        <div class='clear-both'></div>
      </div>
