<?php 

const STDPATH 	= ['c:', 'xampp', 'htdocs']; // Your 'DocumentRoot'
const DOMEN		= ['http:', '', 'localhost'];

function showFolders(string $dir)
{

	$arrayDir = explode("/", $dir);

	if (count($arrayDir) < count(STDPATH) + 1) {
		$dir = rtrim(implode("/", STDPATH), "/\\");
	} else {
		$dir = rtrim(parseFolder($arrayDir, 0), "/\\");
	}
	
	$listDir = array();
	$listFiles = array();
	foreach (array_slice(scandir($dir, 0), 1) as $key => $value) {
		if (is_dir($dir . '/' . $value)) {
			$listDir[] = $value;
		} else {
			$listFiles[] = $value;
		}
	}
	asort($listDir, SORT_NATURAL);
	asort($listFiles, SORT_NATURAL);
	echo "<p id='currentPath'>$dir" . "/</p>";
	echo "<ul id='tree'>";
	foreach ($listDir as $dir) {
		echo "<li class='folder'>$dir</li>\n";
	}
	foreach ($listFiles as $file) {
		echo "<li class='file'>$file</li>\n";
	}
	echo "</ul>";
}

function parseFolder(array $folder, int $n)
{
	if ($n < count(STDPATH) && ($folder[$n] == STDPATH[$n])) {
		$str = parseFolder($folder, $n + 1);
	} elseif ($n == count(STDPATH)) {
		if (isset($folder[$n]) && $folder[$n] == "..") {
			$str = implode("/", STDPATH);
		} else {
			if ($folder[count($folder) - 1] == '..') {
				$folder = array_slice($folder, 0, count($folder) - 2);
			}
			$str = implode("/", $folder);
		}
	} else {
		$str = implode("/", STDPATH);
	}
	
	return $str;
}

function openFile($file)
{
	$path2array = explode("/", $file); 
	$newPath = implode("/", array_splice($path2array, count(STDPATH))); 

	$type = mime_content_type($file);
	
	switch ($type) {
		case 'image/gif':
		case 'image/jpeg':
		case 'image/png':
			echo "<div id='editorFrame'>\n
					<img id='shownImg' src='" . implode('/', DOMEN) . "/$newPath'>\n";
			echo "	<div id='editorBtn'>\n
						<input id='resize' type='button' value='Resize Image'>\n
						<input id='addWM' type='button' value='Add Watermark'>\n
						<input id='close' type='button' value='Close' onclick='cleanPanel()'>
				  	</div>\n
				  </div>\n";
			break;
		
		case 'text/cmd':
		case 'text/css':
		case 'text/csv':
		case 'text/html':
		case 'text/javascript':
		case 'text/plain':
		case 'text/php':
		case 'text/x-php':
		case 'text/xml':
			$output = htmlspecialchars(file_get_contents($file));
			echo "<div id='editorFrame'>\n
					<textarea id='editor' >";
			print_r($output);
			echo "</textarea>";
			echo "	<div id='editorBtn'>\n
						<input type='hidden' value='$file'>\n
						<input id='save' type='button' value='Save Changes'>\n
						<input id='close' type='button' value='Close' onclick='cleanPanel()'>
				  	</div>\n
				  </div>\n";
			break;
		
		case 'application/pdf':
			echo "<div id='editorFrame'>\n
					<embed id='plugin' src='" . implode('/', DOMEN) . "/$newPath' type='application/pdf'>\n
				  	<div id='editorBtn'>\n
						<input id='close' type='button' value='Close' onclick='cleanPanel()'>\n 
				  	</div>\n
				  </div>\n";
			break;	  	
	}
}

function saveFile($file, $content)
{
	file_put_contents($file, $content);
}

function addWatermark($image, $text)
{
	$image = pathToFS($image);

	$type = mime_content_type($image);
	switch ($type) {
		case 'image/gif':
			$im = imagecreatefromgif($image);
			imagestring($im, 5, 1, 1, $text, 14013909);
			$newImage = "wm_" . substr($image, strripos($image, "/") + 1);
			imagegif($im, substr_replace($image, $newImage, strripos($image, "/") + 1));
			break;
		
		case 'image/jpeg':
			$im = imagecreatefromjpeg($image);
			imagestring($im, 5, 1, 1, $text, 14013909);
			$newImage = "wm_" . substr($image, strripos($image, "/") + 1);
			imagejpeg($im, substr_replace($image, $newImage, strripos($image, "/") + 1));
			break;

		case 'image/png':
			$im = imagecreatefrompng($image);
			imagestring($im, 5, 1, 1, $text, 14013909);
			$newImage = "wm_" . substr($image, strripos($image, "/") + 1);
			imagepng($im, substr_replace($image, $newImage, strripos($image, "/") + 1));
			break;
	}

	imagedestroy($im);
	$image = pathToDomen($image);
	echo substr_replace($image, $newImage, strripos($image, '/') + 1);
	
}

function resizeImg($image, $width, $height)
{
	$image = pathToFS($image);

	$image_resized = imagecreatetruecolor($width, $height);
	$type = mime_content_type($image);
	switch ($type) {
		case 'image/gif':
			$im = imagecreatefromgif($image);
			$wI = imagesx($im);
			$hI = imagesy($im);		
			$newImage = "resized_" . substr($image, strripos($image, "/") + 1);
			imagecopyresampled($image_resized, $im, 0, 0, 0, 0, $width, $height, $wI, $hI);
			imagegif($image_resized, substr_replace($image, $newImage, strripos($image, "/") + 1));
			break;
		
		case 'image/jpeg':
			$im = imagecreatefromjpeg($image);
			$wI = imagesx($im);
			$hI = imagesy($im);		
			$newImage = "resized_" . substr($image, strripos($image, "/") + 1);
			imagecopyresampled($image_resized, $im, 0, 0, 0, 0, $width, $height, $wI, $hI);
			imagejpeg($image_resized, substr_replace($image, $newImage, strripos($image, "/") + 1));
			break;

		case 'image/png':
			$im = imagecreatefrompng($image);
			$wI = imagesx($im);
			$hI = imagesy($im);		
			$newImage = "resized_" . substr($image, strripos($image, "/") + 1);
			imagecopyresampled($image_resized, $im, 0, 0, 0, 0, $width, $height, $wI, $hI);
			imagepng($image_resized, substr_replace($image, $newImage, strripos($image, "/") + 1));
			break;
	}

	imagedestroy($im);
	imagedestroy($image_resized);
	$image = pathToDomen($image);
	echo substr_replace($image, $newImage, strripos($image, '/') + 1);
}

function mkFolder($folder, $path)
{
	if (!mkdir($path . $folder)) {
		die('Could not create a directory.');
	};
}

function renameElement($path, $el, $name)
{
	$base 		= $path . $el;
	$newName 	= $path . $name;
	if (!rename($base, $newName)) {
		die('Could not rename the file or directory.');
	}
}

function deleteElement($path, $el)
{
	if (empty($el)) {
		die('Need choose a file or directory.');
	}
	$elem = $path . $el;
	if (is_dir($elem)) {
		if (!rmdir($elem)) {
			die('Could not delete the directory.');
		}
	} else {
		if (!unlink($elem)) {
			die('Could not delete the file.');
		}
	}
}

function copyFile($base, $file, $path)
{
	if (empty($file)) {
		die('Need choose a file.');
	}
	$initFile 	= $base . $file;
	$path 		= trim($path, "/");
	$target 	= implode('/', STDPATH) . "/" . $path . "/" . $file;
	if (!copy($initFile, $target)) {
		die('Could not copy the file.');	
	}
}

function pathToFS($path) 
{
	$pathToArr = explode("/", $path); 
	if (count(STDPATH) < count(DOMEN)) { 
		$pathToArr = array_slice($pathToArr, count(DOMEN) - count(STDPATH)); 
	} else {
		for ($i = 1; $i <= (count(STDPATH) - count(DOMEN)); $i++) {
			array_unshift($pathToArr, ""); 
		}
	}
	return implode("/", array_replace($pathToArr, STDPATH));
}

function pathToDomen($path) 
{
	$pathToArr = explode("/", $path); 
	if (count(STDPATH) < count(DOMEN)) { 
		for ($i = 1; $i <= (count(DOMEN) - count(STDPATH)); $i++) {
			array_unshift($pathToArr, ""); 
		}
	} else {
		$pathToArr = array_slice($pathToArr, count(STDPATH) - count(DOMEN));
	}	
	return implode("/", array_replace($pathToArr, DOMEN)); 
}


 ?> 