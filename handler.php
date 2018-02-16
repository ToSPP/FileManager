<?php 
include_once 'controller.php';

// --- GET folder ---
if (isset($_GET['fold'])) {
	showFolders($_GET['fold']);
}
//-------------------

// --- GET file ---
if (isset($_GET['file'])) {
	openFile($_GET['file']);
}
//-------------------

// --- POST save changed file ---
if (isset($_POST['save'])) {
	saveFile($_POST['save'], $_POST['content']);
}
//-------------------

// --- POST add watermark to image ---
if (isset($_POST['text'])) {
	addWatermark($_POST['image'], $_POST['text']);
}
//-------------------

// --- POST resize image ---
if (isset($_POST['width'])) {
	resizeImg($_POST['image'], $_POST['width'], $_POST['height']);
}
//-------------------

// --- POST make new folder ---
if (isset($_POST['newFolderName'])) {
	mkFolder($_POST['newFolderName'], $_POST['mkPath']);
}
//-------------------

// --- POST rename element ---
if (isset($_POST['rename'])) {
	renameElement($_POST['basePath'], $_POST['baseElement'], $_POST['rename']);
}
//-------------------

// --- POST delete element ---
if (isset($_POST['delElement'])) {
	deleteElement($_POST['basePath'], $_POST['delElement']);
}
//-------------------

// --- POST copy file ---
if (isset($_POST['pathToCopy'])) {
	copyFile($_POST['basePath'], $_POST['baseFile'], $_POST['pathToCopy']);
}
//-------------------

 ?>