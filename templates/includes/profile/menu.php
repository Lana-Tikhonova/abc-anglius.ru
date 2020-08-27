<?php foreach ($q as $k=>$v) {
  if($u[2]==$k&&!intval($u[3])){echo '<div class="leftitem">'.$v.'</div>';}
  else {echo '<a class="leftitem'.($u[2]==$k&&intval($u[3])?' active':'').'"'.($k=='help'?' style="color:#f00"':'').' title="'.$v.'" href="/'.$modules['profile'].'/'.$k.'/">'.$v.'</a>';}
} ?>
