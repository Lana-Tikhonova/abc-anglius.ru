<div class="form-group checkbox form-checkbox <?=@$q['class']?>">
<?php
	$checked = (isset($q['value']) AND $q['value']==1) ? ' checked="checked" ' : '';
?>
	<div class="data">
		<input id="<?=$q['name']?>" name="<?=$q['name']?>" <?=isset($q['attr']) ? $q['attr'] : ''?> type="checkbox" value="1"<?=$checked?>/>
<?php	if (isset($q['caption'])) { ?>
		<label for="<?=$q['name']?>"><?=$q['caption']?></label>
<?php	} ?>
		<div class="clear"></div>
	</div>
</div>
