<?php

/**
 * рекурсивное удаление папок с содержимым
 * @param $dir - полный путь к папке
 * @param bool $i - удалять саму папу или нет
 * @return bool
 */
function delete_all($dir,$i = true) {
	if (is_file($dir)) return unlink($dir);
	if (!is_dir($dir)) return false;
	$dh = opendir($dir);
	while (false!==($file = readdir($dh))) {
		if ($file=='.' || $file=='..') continue;
		delete_all($dir.'/'.$file);
	}
	closedir($dh);
	if ($i==true) return rmdir($dir);
}


/**
 * копирование папок с файлами
 * @param $src - старый путь
 * @param $dst - новый путь
 */
function rcopy($src, $dst) {
	if (file_exists($dst)) delete_all($dst);
	if (is_dir($src)) {
		mkdir($dst);
		$files = scandir($src);
		foreach ($files as $file)
			if ($file != "." && $file != "..") rcopy("$src/$file", "$dst/$file");
	}
	else if (file_exists($src)) copy($src, $dst);
}
