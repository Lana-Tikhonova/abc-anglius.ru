  <div class="cols2">
    <div class='container' style='line-height:1.5;'>
<?php if ($page['url']==$config['conferences']) { ?>
<div class='head text-center'><?=i18n('conferences|confname')?></div>
<div class='text-center'><?=i18n('conferences|text_form')?>
<a href='/<?=$modules['profile']?>/conferences/' class='leftitem' style='margin:12px auto;float:none;'>Принять участие</a>
</div>
<?php } ?>
<?=$page['text']?>
    </div>
  </div>
