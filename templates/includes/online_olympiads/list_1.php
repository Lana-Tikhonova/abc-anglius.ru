<?php if($i==1) { ?>
    <div class='container'>
<?php if($q['type']==1&&i18n('common|pedagog_txt')) echo '<div style="margin-bottom:30px;">'.i18n('common|pedagog_txt').'</div>'; ?>
        <div class='list olimp'>
<?php
  if($q['type']==3) { ?>
<select id="scholsel" style="display: none; border:2px solid #57b78e;font-size:16px;color:#454545;padding:4px;margin-bottom:30px;">
<option value="all"<?=(!isset($_GET['filter']))?' selected':''?>>Все олимпиады</option>
<option value="kl1"<?=(isset($_GET['filter'])&&$_GET['filter']=='kl1')?' selected':''?>>Олимпиады для 1-4 классов</option>
<option value="kl2"<?=(isset($_GET['filter'])&&$_GET['filter']=='kl2')?' selected':''?>>Олимпиады для 5-9 классов</option>
<option value="kl3"<?=(isset($_GET['filter'])&&$_GET['filter']=='kl3')?' selected':''?>>Олимпиады для 10-11 классов</option>
</select>
    
            <div class="olympiads_list__filter">
                <a class="olympiads_list__filter_button <?=(!isset($_GET['filter']))?' active':''?>" href="/<?=@$modules['online_olympiads'.$q['type']]?>/">Все олимпиады</a>
                <a class="olympiads_list__filter_button <?=(isset($_GET['filter'])&&$_GET['filter']=='kl1')?' active':''?>" href="/<?=@$modules['olympiads'.$q['type']]?>/?filter=kl1">Для 1 - 4 классов</a>
                <a class="olympiads_list__filter_button <?=(isset($_GET['filter'])&&$_GET['filter']=='kl2')?' active':''?>" href="/<?=@$modules['olympiads'.$q['type']]?>/?filter=kl2">Для 5 - 9 классов</a>
                <a class="olympiads_list__filter_button <?=(isset($_GET['filter'])&&$_GET['filter']=='kl3')?' active':''?>" href="/<?=@$modules['olympiads'.$q['type']]?>/?filter=kl3">Для 10 - 11 классов</a>
            </div>
        
<?php
  }
} ?>
<?php
	if($q['type']<3) $link='/tests/'.$q['testid'].'/';
	else {
          $count=mysql_select('select * from olympiads_tests where olympiad='.$q['id'].' and display=1','num_rows');
          if($count==1) $link='/tests/'.$q['testid'].'/';
          else $link='/'.$u[1].'/'.$q['id'].'-'.$q['url'].'/';
        }
?>
          <div class='block1'>
<?php if($q['type']!=1) { ?>
            <div class='h'><?=$q['name']?></div>
            <div class='txt'><?=$q['text']?>
            <a href='<?=$link?>' class='particip'>Принять участие <span>></span></a>
            </div>
            <div class='img'><img src='<?=($q['img']?'/files/olympiads/'.$q['id'].'/img/p-'.$q['img']:'/templates/images/nophoto.jpg')?>'></div>
            <div class='clear-both'></div>
<?php } else { ?>
            <div class='h'><?=$q['name']?></div>
            <div class='txt'><a href='<?=$link?>' class='particip' style='margin-top:0'>Принять участие <span>></span></a>
            </div>
            <div class='clear-both'></div>
<?php } ?>
          </div>
<?php if($i==$num_rows) { ?>
        </div>
    </div>
    <script>
    $("#scholsel").change(function(){
      var str=$("#scholsel").val();
      if(str=='all') {str='';} else {str='?filter='+str;}
      location.replace("/<?=$u[1]?>/"+str);
    });
    </script>
<?php } ?>
