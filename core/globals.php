<?php
define("app_name", "Cafeteria");

function base_path($path = "") {
	$base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
	if ($base === '/' || $base === '.') {
		$base = '';
	}
	$path = ltrim($path, '/');
	return $base . ($path !== '' ? '/' . $path : '');
}
?>