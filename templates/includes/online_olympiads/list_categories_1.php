<?php if($i==1) { 
    $category_id = FALSE;
    $category_name = $q['oc_name'];    
?>
    <div class='container'>
<?php if($q['type']==1&&i18n('common|pedagog_txt')) echo '<div style="margin-bottom:30px;">'.i18n('common|pedagog_txt').'</div>'; ?>
        <div class='list olimp'>
<?php } ?>
        <?
            $link='/online_tests/'.$q['testid'].'/';    
        ?>
        <? if ($category_id !== $q['category']) : 
                $category_id = $q['category'];
                $category_name = ($q['oc_name']) ? $q['oc_name'] : i18n('common|olimp_category_empty');                
            ?>
            <? if($i>1): ?>
                        </div>
                    <div class='clear-both'></div>
                </div>
            <? endif; ?>
            <div class='block1'>
                <div class='h'><?=$category_name?></div>                
                <? if($q['oc_text']): ?>
                    <div class='txt'>
                        <?=$q['oc_text']?>                    
                    </div>  
                <? endif; ?>
                <? if($q['oc_img']): ?><img class="img" src="/files/online_olympiads_categories/<?=$q['category']?>/img/p-<?=$q['oc_img']?>"><? endif; ?>                               
                <div class='clear-both'></div>
                <button type="button"><?=i18n('common|olimp_see_all')?></button>                                
                <div class='clear-both'></div>
                    <div class="o_list">
        <? endif; ?>
            
            <div class="o_item">
                <div class='o_name'><?=$q['name']?></div>
                <div class='txt'>
                    <a href='<?=$link?>' class='particip' style='margin-top:0'><?=i18n('common|olimp_accept')?> <span>></span></a>
                </div>
                <div class='clear-both'></div>
            </div>
        
<?php if($i==$num_rows) { ?>
                <div class='clear-both'></div>
            </div>
        </div>
    </div>  
    
    <script>
        $('.olimp .block1 button').on('click', function(){
            $(this).closest('.block1').find('.o_list').slideToggle();
        });
    </script>
<?php } ?>