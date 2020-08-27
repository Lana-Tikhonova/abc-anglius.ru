<?php
if (is_array($q)) {
	?>
	<ul class="form_message bg-danger">
		<?php foreach ($q as $k=>$v) echo html_array('form/message',$v)?>
	</ul>
	<?php
}
else {
	?>
<ul class="form_message bg-danger"><li><?=$q?></li></ul>
	<?php
}
?>
