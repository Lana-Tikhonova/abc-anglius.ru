  <div class='container lastk'>
    <div class='filter'>
      <a class='border5 gradient3' href='/<?=$u[1]?>/'>Все олимпиады</a>
      <a class='border5 gradient3' style='margin-left:225px;margin-right:225px;' href='/<?=$u[1]?>/?filter=1'>Для учащихся и студентов</a>
      <a class='border5 gradient3' href='/<?=$u[1]?>/?filter=2'>Для педагогов</a>
      <div class='clear-both'></div>
    </div>
    <div class='col1'>
<?=html_query('olympiads/list',$q,'')?>
    </div>
    <div class='col2'>
<?php include(ROOT_DIR.'/templates/includes/common/col2.php');?>
    </div>
    <div class='clear-both'></div>
  </div>

  <div class='conditions_2'>
      <div class='container'>
        <div class='head'>УСЛОВИЯ НАГРАЖДЕНИЯ</div>
        <ul>
          <li>Участники, набравшие наибольшее количество баллов становятся Победителями (не менее 90 % от максимального количества баллов).</li>
          <li>Участники, набравшие более 60% от максимального количества баллов, становятся Лауреатами.</li>
          <li>Участники, набравшие менее 60 % становятся Участниками.</li>
          <li>Победители и Лауреаты награждаются электронными дипломами победителей и лауреатов, Участники награждаются электронными сертификатами за участие.</li>
          <li>Педагоги, подготовившие Лауреатов и Победителей, получают Благодарственные письма.</li>
        </ul>
      </div>
  </div>
