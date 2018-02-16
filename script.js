//--- Select element
	$("div#panel").on("click", "li", function(){
		$("ul#tree").find("*").removeClass("chosen");
		$(this).addClass("chosen");
	});	
	$(document).on("keydown", function(event){
		var tree = document.getElementById("tree").children;
		switch (event.which) {
			case 38:
				var el = document.getElementsByClassName("chosen");
				if (el[0].previousElementSibling === null) break;
				el[0].previousElementSibling.classList.add("chosen");
				el[1].classList.remove("chosen");	
				break;
			case 40:
				var el = document.getElementsByClassName("chosen");
				if (el[0].nextElementSibling === null) break;
				el[0].nextElementSibling.classList.add("chosen");
				el[0].classList.remove("chosen");	
				break;		
		}		
	});	
//--------------------

//--- Open folder
	$("div#panel").on("dblclick", "li.folder", function(event){	    
	    $.ajax({
	    	url: "handler.php?fold=" + $("p#currentPath").text() + $(this).text()
	    }).done(function(response) {
	    	$("div#panel").html(response);
	    });
	});
//--------------------

//--- Open file
	$("div#panel").on("dblclick keydown{keyCode: 13}", "li.file", function(){
	    $.ajax({
	    	url: "handler.php?file=" + $("p#currentPath").text() + $(this).text()
	    }).done(function(response) {
	    	$("div#rightPanel").html(response);	
	    	var area = document.getElementById("editor");
			var areaHeight = area.scrollHeight;
			if (areaHeight > 560) { areaHeight = 560; }			
			area.style.height = areaHeight + "px";
	    });
	});
//--------------------

//--- Save changed file
	$(document).on("click", "input#save", function(){
	    $.ajax({
			method: "POST",
	    	url: "handler.php",
	    	data: { save: $("div#editorBtn > input:hidden").val(), 
	    			content: $("textarea#editor").val() }
	    }).done(function() {
	    	alert('File saved');
	    });
	});
//--------------------

//--- Add watermark to image
	$(document).on("click", "input#addWM", function(){
		var watermark = prompt("Type watermark text?", "");
	    $.ajax({
			method: "POST",
	    	url: "handler.php",
	    	data: { image: $("img#shownImg").attr("src"), 
	    			text: watermark }
	    }).done(function(response) {
	    	$("img#shownImg").attr({src: response});	    	
	    	refreshTree();
	    });
	});
//--------------------

//--- Resize image
	$(document).on("click", "input#resize", function(){
		var newWidth = +prompt("Enter new width?", "");
		var newHeight = +prompt("Enter new height?", "");
	    $.ajax({
			method: "POST",
	    	url: "handler.php",
	    	data: { image: $("img#shownImg").attr("src"), 
	    			width: newWidth,
	    			height: newHeight }
	    }).done(function(response) {
	    	$("img#shownImg").attr({src: response});
	    	refreshTree();
	    });
	});
//--------------------

//--- Make folder
	$(document).on("click", "input#mkFolderBtn", function(){
		var newFolder = prompt("Type new folder name?", "");
	    $.ajax({
			method: "POST",
	    	url: "handler.php",
	    	data: { mkPath: $("p#currentPath").text(),
	    			newFolderName: newFolder }
	    }).done(function(response) {
	    	if(response !== undefined) console.log(response);
	    	refreshTree();
	    });
	});
//--------------------

//--- Rename dir/file
	$(document).on("click", "input#renameBtn", function(){
		var newName = prompt("Type new folder/file name?", "");
	    $.ajax({
			method: "POST",
	    	url: "handler.php",
	    	data: { basePath: $("p#currentPath").text(),
	    			baseElement: $("div#panel > ul").find(".chosen").text(),
	    			rename: newName }
	    }).done(function(response) {
	    	if(response !== undefined) console.log(response);
	    	refreshTree();
	    });
	});
//--------------------

//--- Delete dir/file
	$(document).on("click", "input#delBtn", function(){
		if (!confirm("You want to delete '" + $("div#panel > ul").find(".chosen").text() + "'. Are you sure?")) return false;
	    $.ajax({
			method: "POST",
	    	url: "handler.php",
	    	data: { basePath: $("p#currentPath").text(),
	    			delElement: $("div#panel > ul").find(".chosen").text() }
	    }).done(function(response) {
	    	if(response !== undefined) console.log(response);
	    	refreshTree();
	    });
	});
//--------------------

//--- Copy file
	$(document).on("click", "input#copyBtn", function(){
	    if ($("div#panel > ul").find(".chosen").hasClass("folder")) return false;
	    var path = prompt("Type the path to copy:", "");
	    if (path === undefined) return false;
	    $.ajax({
			method: "POST",
	    	url: "handler.php",
	    	data: { basePath: $("p#currentPath").text(),
	    			baseFile: $("div#panel > ul").find(".chosen").text(),
	    			pathToCopy: path }
	    }).done(function(response) {
	    	if(response !== undefined) console.log(response);
	    	refreshTree();
	    });
	});
//--------------------

//--- Refresh Panel
function refreshTree() {
	$.ajax({
		url: "handler.php?fold=" + $("p#currentPath").text()
    }).done(function(responseNext) {
    	$("div#panel").html(responseNext);
    });
}
//--------------------

//--- Clean right panel 
function cleanPanel() 
{	
	$("div#rightPanel").html("<div id='editorFrame'><p>[ EDITOR FRAME ]</p></div><div id='editorBtn'></div>" ); 
}
//--------------------