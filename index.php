<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>File Manager</title>
</head>
<body>

<?php include_once "controller.php"; ?>

<div id="wrapper">
	<div class="head">File Manager for Win
		<p id="author">by ToSPP</p>
	</div>
	
	<div class="leftPanel">
		<div id="panel">
			<?php 
		// --- GET show initial folder ---		
				if (!isset($_GET['fold'])) {
					showFolders(implode('/', STDPATH));
				} else {
					showFolders($_GET['fold']);
				}
		// ------
			?>
		</div>
		<div id="btnPanel"">
			<input id="mkFolderBtn" type="button" value="Make Folder">
			<input id="copyBtn" type="button" value="Copy File">
			<input id="renameBtn" type="button" value="Rename Dir/File">
			<input id="delBtn" type="button" value="Delete Dir/File">
		</div>
	</div>

	<div id="rightPanel">          
		<div id="editorFrame">
			<p>[ EDITOR FRAME ]</p>
			<div id="editorBtn">
			</div>
		</div>
	</div>

</div>

</body>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="script.js"></script>

</html>