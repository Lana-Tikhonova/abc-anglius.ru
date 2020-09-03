<?php
if ($i == 1) {
  $id = intval(explode2('-', $u[2]));
}
?>
<div class="card <?= $id == $q['id'] ? '-active' : '' ?>">
  <div><img src='/files/<?= $page['module'] ?>/<?= $q['id'] ?>/img/p-<?= $q['img'] ?>'></div>
  <div>
    <h3><?= $q['name'] ?></h3>
    <p><?= $q['shortdesc'] ?></p>
  </div>

  <?php if ($id != $q['id']) { ?>
    <div class="button-wrap">
      <a href="/<?= $modules['conferences'] ?>/<?= $q['id'] ?>-<?= $q['url'] ?>/" class="button -secondary">Принять участие</a>
    </div>
    <?php if (mysql_select('select * from orders where paid=1 and type=6 and parent=' . intval($q['id']), 'num_rows')) { ?>
      <div class="button-wrap">
        <a href="/<?= $modules['conferences'] ?>/<?= $q['id'] ?>-<?= $q['url'] ?>/#spisok" class="button -secondary">Работы участников конференции</a>
      </div>
    <?php } ?>
  <?php } ?>
</div>