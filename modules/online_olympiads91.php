<?php
  $html['module']='online_olympiads';
  $type=91;
  //include('olympiads.php');
  
    if($u[3]) $error++;
    if($u[2]) {
            if($type>92) {
                    $id = intval(explode2('-',$u[2]));  //echo $id;
                    $real=mysql_select("select * from online_olympiads where id=$id",'row');
                    if($real['url']) {
                            if($u[2]!=$id.'-'.$real['url']) //переадреация на корректный урл
                                    die(header('location: /'.$u[1]."/$id-".$real['url'].'/'));
                            $query = "
                                    SELECT *
                                    FROM online_olympiads_tests
                                    WHERE olympiad = '".$id."' AND display = 1
                                    ORDER BY klass ASC
                            ";
                            $breadcrumb['module'][] = array($real['name'],'/'.$u[1].'/'.$u[2].'/');
                            $page['type']=$type;
                            $html['content']=html_query('online_olympiads/list_klasses',$query,' ');
                    }
                    else $error++;
            }
            else $error++;
    } else {
    //список записей
            $where='';            
            $query = "
                    SELECT o.*,ot.id testid, oc.name oc_name, o.text oc_text, o.img oc_img  
                    FROM online_olympiads o
                    LEFT JOIN online_olympiads_tests ot ON ot.olympiad=o.id
                    LEFT JOIN online_olympiads_categories oc ON oc.id=o.category  
                    WHERE o.type=$type AND o.display=1 AND ot.display=1 $where
                    GROUP BY o.id
                    ORDER BY oc.rank DESC, o.rank DESC
                ";// echo $query;
                $html['content'] = html_query('online_olympiads/list_categories_pedagog',$query,' ');
    }



?>