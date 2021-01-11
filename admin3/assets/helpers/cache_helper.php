<?php
	function writeCache($content, $filename){
		$fp = fopen($filename, 'w'); 
		fwrite($fp, $content); 
		fclose($fp); 
	}

	function readCache($filename, $expiry){
		if (file_exists($filename)) {
			if ((time() - $expiry) > filemtime($filename))
				return FALSE;
			$cache = file($filename);
			return implode('', $cache);
		}
		return FALSE; 
	}
?>