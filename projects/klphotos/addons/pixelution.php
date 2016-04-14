<?php

$addon_name = "Pixelution Template Addon";
$addon_version = "1.0";
$addon_description = "Outputs HTML required for Pixelution Template";



// OUTPUT CATEGORIES MENU

if (preg_match("<PIXELUTION_CATEGORIES_MENU>", $tpl)) {
	if ($language_abr == $default_language_abr) {
 		$query = "select * from ".$pixelpost_db_prefix."categories order by name";
	} else {
		$query = "select * from ".$pixelpost_db_prefix."categories order by alt_name";
	}

	$results= mysql_query($query);
	$categorymenu = '<ul id="categoryMenu">';
	
 	$categorymenu .= '<li><a href="index.php?x=browse&amp;pagenum=1">' . $lang_browse_all . '</a></li>';
	
	while ($row=mysql_fetch_array($results)) {
		if ($language_abr == $default_language_abr) {
			$categorymenu .= "<li><a href='index.php?x=browse&amp;category=$row[id]&amp;pagenum=1'>$row[name]</a></li>";
		} else {
			$categorymenu .= "<li><a href='index.php?x=browse&amp;category=$row[id]&amp;pagenum=1'>$row[alt_name]</a></li>";
		}
	}
	$categorymenu .= '</ul>';
	
	
	
	$tpl = str_replace("<PIXELUTION_CATEGORIES_MENU>",$categorymenu,$tpl);
}


// OUTPUT IMAGE CATEGORIES AND TAGS

if ($image_id) {
	$query = "SELECT * FROM ".$pixelpost_db_prefix."catassoc, ".$pixelpost_db_prefix."categories WHERE (image_id =$image_id) AND (cat_id = ".$pixelpost_db_prefix."categories.id) order by name";
	$results= mysql_query ($query);
	$num_rows = mysql_num_rows($results);
	if ($num_rows > 0) {
		$output = "<div id='photocategories'><p>" . $lang_category_plural . "<br />";
		while ($row=mysql_fetch_array($results)) {
			if ($language_abr == $default_language_abr) {
				$temp1[] = "<a href='index.php?x=browse&amp;category=$row[cat_id]&amp;pagenum=1'>$row[name]</a>";
			} else {
				$temp1[] = "<a href='index.php?x=browse&amp;category=$row[cat_id]&amp;pagenum=1'>$row[alt_name]</a>";
			}
		}
	
	
		$tmp = join($temp1, ", ");
		$output .=$tmp . "</p></div>";
	}
	$query = "SELECT * FROM ".$pixelpost_db_prefix."tags where img_id = $image_id";
	$results= mysql_query ($query);
	$num_rows = mysql_num_rows($results);
	if ($num_rows > 0) {
		$output .= "<div id='phototags'><p>" . $lang_tags;
		while ($row=mysql_fetch_array($results)) {
			if ($language_abr == $default_language_abr) {
				$temp2[] = "<a href='index.php?x=browse&amp;tag=$row[tag]&amp;pagenum=1'>$row[tag]</a>";
			} else {
				$temp2[] = "<a href='index.php?x=browse&amp;tag=$row[alt_tag]&amp;pagenum=1'>$row[alt_tag]</a>";

			}
		}
		$tmp = join($temp2, ", ");
		$output .= $tmp . "</p></div>";
	}
	$tpl = str_replace("<PIXELUTION_IMAGE_CATEGORIES>",$output,$tpl);
}


// RESIZE IMAGE
if ($image_id) {
$maxW = 520;
$maxH = 520;
if($image_width > $image_height) {
	if ($image_width > $maxW) {
		$scale = $maxW/$image_width;
		$newW = ceil($image_width * $scale);
		$newH = ceil($image_height * $scale);
	}
} else {
	if ($image_height > $maxH) {
		$scale = $maxH/$image_height;
		$newW = ceil($image_width * $scale);
		$newH = ceil($image_height * $scale);
	}
}

$tpl = str_replace("<PIXELUTION_IMAGE_WIDTH>",$newW,$tpl);
$tpl = str_replace("<PIXELUTION_IMAGE_HEIGHT>",$newH,$tpl);
}
?>