  <div class="cols2" style="margin-top:-30px;">
    <div class='container'>
      <div class='leftcol'>
	<?=$html['profile_menu']?>
      </div>
      <div class='rightcol'>
<?php
if ($html['module2']) {
	echo $html['content'];
}
else { ?>
	<div<?=editable('pages|text|'.$page['id'])?>><?=$page['text']?></div>
<?php } ?>
      </div>
      <div class='clear-both'></div>
    </div>
  </div>
  <hr style='height:2px;background:#ececec;'>