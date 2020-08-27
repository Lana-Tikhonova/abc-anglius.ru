<?if(!empty($q['more']['cert_change']) && ($diploms = mysql_select("SELECT * FROM cert_templates WHERE type = {$q['type']} AND display = 1 ORDER BY sort DESC, name ASC"))): ?> 
    <p style="padding: 30px 0 15px;">Выберите желаемый дизайн диплома:</p>
    <div class="cert_change_list">
        <label for="diplom_template_0"><input id="diplom_template_0" name="cert_template" type="radio" value="0" <?=(empty($diplom['id']))?'checked':''?>> <img width="90px" title="По умолчанию" alt="По умолчанию" src="/templates/images/preview/diplom_<?=$q['type']?>.jpg"></label>
        <? foreach ($diploms as $diplom): ?>
        <label for="diplom_template_<?=$diplom['id']?>"><input id="diplom_template_<?=$diplom['id']?>" name="cert_template" type="radio" value="<?=$diplom['id']?>" <?=($q['cert_template'] == $diplom['id'])?'checked':''?> > <img width="90px" title="<?=$diplom['name']?>" alt="<?=$diplom['name']?>" src="/files/cert_templates/<?=$diplom['id']?>/diplom/p-<?=$diplom['diplom']?>"></label>
        <? endforeach; ?>
    </div>
<? endif;?>