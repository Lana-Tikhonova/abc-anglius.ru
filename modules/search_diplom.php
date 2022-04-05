<?php

$html['content'] = '';

$html['content'] = '<div class="container" style="padding-bottom: 30px;">' . $page['text'] . '</div>';
$html['content'] .= html_array('search_diplom/form');

$search = !empty($_GET['search']) ? intval($_GET['search']) : 0;

if ($search) {
	$where = '';
	//$where = (access('user admin')) ? '' : (' AND `user` = '.intval(@$user['id']) .' ');
	$order = mysql_select("SELECT * FROM `orders` WHERE `id` = '$search' AND `paid` = 1 $where LIMIT 1", 'row') ?: [];
//    var_dump($order); die();
	if ($order) {
		$order['basket'] = @unserialize($order['basket']) ?: [];
		switch ($order['type']) {						
			case '2':
			case '4':
				$order['basket']['results'] = [];
				$order['basket']['results'][] = [
					//'fio' => implode(', ', [$order['basket']['fio'], $order['basket']['fio']]),
					'fio' => check_fio($order['basket']['fio'], 'скачать диплом'),
					'url' => '/certificate.php?key='.base64_encode($order['id'].'.0')
				];				
				break;
			case '6':
				$order['basket']['results'] = [];
				$order['basket']['results'][] = [					
					'fio' => check_fio($order['basket']['fio'], 'скачать диплом'),
					'url' => '/certificate.php?key='.base64_encode($order['id'].'.1')
				];				
				$order['basket']['results'][] = [					
					'fio' => 'сертификат',
					'url' => '/certificate.php?key='.base64_encode($order['id'].'.2')
				];
				$order['basket']['results'][] = [					
					'fio' => 'свидетельство о публикации',
					'url' => '/certificate.php?key='.base64_encode($order['id'].'.3')
				];
				break;
			case '3':				
			case '10':
				$order['basket']['results'][] = [					
					'fio' => 'Свидетельство о публикации',
					'url' => '/certificate.php?key='.base64_encode($order['id'].'.0')
				];
				break;
			case '91':
			case '92':
			case '93':
			case '94':
				$order['basket']['results'] = [];
				$order['basket']['results'][] = [
					'fio' => check_fio(implode(' ', [$order['basket']['f'], $order['basket']['io']]), 'скачать диплом'),
					'url' => '/certificate'.(isOnlineOlympiads(@$order['type'])?'_online':'').'.php?key='.base64_encode($order['id'])
				];
				break;
			case '1':
			case '8':
			default:
				$order['basket']['results'] = !empty($order['basket']['results']) ? $order['basket']['results'] : [];
				$order['basket']['letters'] = [];
				foreach ($order['basket']['results'] as $k => $v) {
					$order['basket']['results'][$k]['url'] = '/certificate.php?key=' . base64_encode($order['id'] . '.' . $k);
				}
				//
				$fios = explode('|', @$order['basket']['fio']) ?: [];
				foreach ($fios as $k => $v) {
					$order['basket']['letters'][] = [
						'fio' => check_fio($v, 'скачать'),
						'url' => '/certificate.php?key=' . base64_encode($order['id'] . '.0.' . $k)
					];
				}
				break;
		}				
	}
//	var_dump($order);
	//
	$html['content'] .= html_array('search_diplom/results', $order);
	
}

function check_fio($str, $default)
{
    return (mb_strlen(trim($str)) > 3) ? $str : $default;
}