<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="http://code.jquery.com/jquery-1.8.1.js"></script>
<script type="text/javascript" src="js/nutritionPyramid.js"></script>
<link rel="stylesheet" type="text/css" href="css/nutritionPyramid.css"  />
</head>

<body>
<?php $rtnAddress = $_GET['rtnAddress']; ?>



<div id="pyramid">
	<div id="fat_part" ondrop="drop(event)" ondragover="allowDrop(event)">
    </div>
    <div id="protein_part" ondrop="drop(event)" ondragover="allowDrop(event)">
    </div>
    <div id="veget_part" ondrop="drop(event)" ondragover="allowDrop(event)">
    </div>
    <div id="rice_part" ondrop="drop(event)" ondragover="allowDrop(event)">
    </div>
  
</div>

<div id="veget_question" ondrop="drop(event)" ondragover="allowDrop(event)">
	<img id="vet" src="img/veget.png" />
    <caption><em>Vegetables and Fruits</em></caption> 
</div>

<div id="fat_question" ondrop="drop(event)" ondragover="allowDrop(event)">
	<img id="fat" src="img/fat.png"/>
    <caption><em>Fats, Oils and Sweets</em></caption>
</div>

<div id="rice_question" ondrop="drop(event)" ondragover="allowDrop(event)">
	<img id="rice" src="img/rice.png"/>
    <caption><em>Bread, Rice, Pasta and Cereal</em></caption>
</div>

<div id="protein_question" ondrop="drop(event)" ondragover="allowDrop(event)">
	<img id="protein" src="img/protein.png"/>
    <caption><em>Meat, Milk and Diary products</em></caption>
</div>

</body>
</html>
