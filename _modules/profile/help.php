<?php
  if($result=mysql_select("select text from pages where display=1 and url='help'",'string')){$html['content']=$result;}
  else {$html['content']='Страница находится в процессе наполнения';}
?>