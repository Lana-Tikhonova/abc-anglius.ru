<?php    
  
    if($u[3]) $error++;
    if($u[2]) {
            if($type>=92) {
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
                            if ($type == 92) {
                                // сразу редиректим на тест
                                $test = mysql_select($query . ' LIMIT 1', 'row');
                                if ($test) {
                                    header('Location: /online_tests/' . $test['id'] . '/');
                                    exit();
                                } else {
                                    $error++;
                                }
                            } else {
                                $html['content']=html_query('online_olympiads/list_klasses',$query,' ');
                            }
                    }
                    else $error++;
            }
            else $error++;
    } else {
    //список записей
            $where='';    						
			
			if($type==93) {
			  if(isset($_GET['filter'])&&in_array($_GET['filter'],array('kl1','kl2','kl3')))
				$where=' AND o.'.$_GET['filter'].'=1 ';
			};
			
            $query = "
                    SELECT o.*,ot.id testid, ot.klass klass, oc.name oc_name, o.text oc_text, o.img oc_img  
                    FROM online_olympiads o
                    LEFT JOIN online_olympiads_tests ot ON ot.olympiad=o.id
                    LEFT JOIN online_olympiads_categories oc ON oc.id=o.category  
                    WHERE o.type=$type AND o.display=1 AND ot.display=1 $where
                    GROUP BY o.id
                    ORDER BY oc.rank DESC, o.rank DESC
                ";// echo $query;
                $html['content'] = ($type==91) ? html_query('online_olympiads/list_categories_pedagog',$query,' ') : html_query('online_olympiads/list_categories_other',$query,' ');
    }



?>