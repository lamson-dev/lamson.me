<?php

// Dark Matter Pro Support add-on
// DO NOT REDISTRIBUTE

$addon_name = 'Dark Matter Pro support addon';
$addon_description = 'Markup improvements for comments, navigation and archives and backend functionality for DM thumbnail browser. You MUST enable this addon for the Dark Matter theme to function properly.';
$addon_version = "1.1.1";

// Set $dev_mode to true when you're customizing
// this will load uncompressed javascript and CSS
//
// You can then edit photoblog.css / photoblog-light.css
// and scripts.js
//
// Make sure to compress your CSS and JS when you're done,
// save the files as: photoblog-compressed.css, photoblog-light-compressed.css
// and scripts_yuicompressed.js
//
// After that you can set $dev_mode to false again in order to
// minimize the amount of data your visitors will have to load
// in CSS and Javascript
//
// Note that scripts_yuicompressed.js is a rollup of the following files:
// - cookie-beta-min.js
// - tools-effects-min.js
// - scripts.js
//
// You can get the YUI Javascript compressor here:
//
// http://developer.yahoo.com/yui/compressor/

$dev_mode = false;

if(file_exists('templates/darkmatter/i18n.php')) {
	require_once('templates/darkmatter/i18n.php');
	require_once('templates/darkmatter/dm_config.php');
	$bIncluded = true;
}
else {
	if(file_exists('../templates/darkmatter/i18n.php')) {
		require_once('../templates/darkmatter/i18n.php');
		require_once('../templates/darkmatter/dm_config.php');
		$bIncluded = true;
	}
}

if(!$bIncluded) {
	echo '<div style="background: #c00;color: #fcc; padding: 10px; border: 1px #f00 solid; margin: 20px;"><strong>ERROR:</strong>You have installed the Dark Matter add-on but not the actual template files. Please upload the folder named <strong>darkmatter</strong> into your templates directory. If you don\'t wish to use the Dark Matter template please remove <strong>dm_support.php</strong> from your addons directory to get rid of this message.</div>';
}

$aDefaults = Array();
$aDefaults['fadein'] = (DM_CONFIG_FADE == 1) ? 'yes' : 'no';
$aDefaults['fadespeed'] = DM_CONFIG_FADETIME;
$aDefaults['carousel'] = strtolower(DM_CONFIG_CAROUSEL);
$aDefaults['tags'] = (DM_SHOW_TAGS == 1) ? 'yes' : 'no';

function dm_jsconfig() {

	global $previous_row;
	global $next_row;
	global $cfgrow;
	global $aDefaults;

	$sOut = "<script type=\"text/javascript\">";
	$sOut .= "YAHOO.namespace(\"photoblog\");";
	$sOut .= "YAHOO.photoblog.oConfig = {";
	$sOut .= "STR_PHOTO_STREAM: \"" . STR_PHOTO_STREAM . "\",";
	$sOut .= "STR_IMAGE_INFO: \"" . STR_IMAGE_INFO . "\",";
	$sOut .= "STR_VIEW_COMMENTS: \"" . STR_VIEW_COMMENTS . "\",";
	$sOut .= "STR_COMMENT_FORM: \"" . STR_COMMENT_FORM . "\",";
	$sOut .= "STR_TAGS: \"" . STR_TAGS . "\",";
	$sOut .= "thumbsheight: " . ($cfgrow['thumbheight'] + 60) . ",";
	$sOut .= "fade: \"" . $aDefaults['fadein'] . "\",";
	$pageOffset = 60; 
	$sOut .= "offsetleft: " . $pageOffset . ",";
	$sOut .= "newest_id: \"" . dm_newest_id() . "\",";
	$sOut .= "oldest_id: \"" . dm_oldest_id() . "\",";
	$sOut .= "previous_image: \"" . $previous_row['image'] . "\",";
	$sOut .= "next_image: \"" . $next_row['image'] . "\",";
	$sOut .= "thumbs: Array(" . dm_get_thumbs_js() . "),";
	$sOut .= "fadespeed: \"" . $aDefaults['fadespeed'] . "\"};";
	$sOut .= "</script>";
	return $sOut;
}

function dm_jsfiles() {
	global $dev_mode;
	if($dev_mode) {
		$sOut = '<script type="text/javascript" src="templates/darkmatter/js/yahoo-dom-event.js"></script>';
		$sOut .= "\n";
		$sOut .= '<script type="text/javascript" src="templates/darkmatter/js/animation-min.js"></script>';
		$sOut .= "\n";
		$sOut .= '<script type="text/javascript" src="templates/darkmatter/js/connection-min.js"></script>';
		$sOut .= "\n";
		$sOut .= '<script type="text/javascript" src="templates/darkmatter/js/cookie-beta-min.js"></script>';		
		$sOut .= "\n";
		$sOut .= '<script type="text/javascript" src="templates/darkmatter/js/tools-effects-min.js"></script>';
		$sOut .= "\n";
		$sOut .= dm_jsconfig();
		$sOut .= "\n";
		$sOut .= '<script type="text/javascript" src="templates/darkmatter/js/scripts.js"></script>';
	}
	else {
		$sOut = '<script type="text/javascript" src="http://yui.yahooapis.com/2.5.2/build/utilities/utilities.js"></script>';
		$sOut .= "\n";
		$sOut .= dm_jsconfig();
		$sOut .= "\n";
		$sOut .= '<script src="templates/darkmatter/js/scripts_yuicompressed.js" type="text/javascript"></script>';
	}
	return $sOut;
}

function dm_histogram() {

	global $aDefaults;
	global $image_name;
	global $lang_histogram;
	
	$filename = 'histograms/hist_' . $image_name;
	if (file_exists($filename)) {
		$histogram = $lang_histogram . '<p id="RGBHistogram"><img src="histograms/hist_' . $image_name . '" alt="Histogram"/></p>';
	} else {
		$histogram = '<p>N/A</p>';
	}
		$sOut = <<<HTML
		<div class="infobox" id="histogram">
			{$histogram}
		</div>
HTML;
	return $sOut;
}

function dm_cssfile() {
	global $aDefaults;
	global $dev_mode;
	$sOut = '';

	if($dev_mode) {
		$sOut = '<link rel="stylesheet" type="text/css" href="templates/darkmatter/css/photoblog.css" media="screen" />';
	}
	else {
		$sOut = '<link rel="stylesheet" type="text/css" href="templates/darkmatter/css/photoblog-compressed.css" media="screen" />';			
	}
	$sOut .= '<!--[if IE 6]>
		<link rel="stylesheet" href="templates/darkmatter/css/ie6.css" type="text/css" media="screen" />
			<![endif]-->';		
	return $sOut;
}

function dm_description() {
	
	global $aDefaults;
	global $image_notes;
	
	global $image_categoryword;
	global $image_category_all;
	
	$class = ' all';

	$description = STR_DESCRIPTION;
	$posted_in = STR_POSTED_IN;
	
	$category = $image_categoryword." ".$image_category_all;
	
	$sOut = <<<HTML
	<div class="infobox{$class} description">
		<h5>{$description}</h5>
		<p>{$posted_in} {$category}</p>
		<div id="image-notes">{$image_notes}</div>
	</div>
HTML;
	return $sOut;
}

function dm_exif() {

	global $aDefaults;
	if(function_exists('replace_exif_tags')) {
		$s_summary = STR_EXIF_SUMMARY;
		$s_focal_length = STR_FOCAL_LENGTH;
		$s_aperture = STR_APERTURE;
		$s_shutter_speed = STR_SHUTTER_SPEED;
		$s_exif = STR_EXIF;

		global $image_exif;
		global $language_full;
		$aperture = replace_exif_tags ($language_full, $image_exif, '<EXIF_APERTURE>');
		$focal_length = replace_exif_tags ($language_full, $image_exif, '<EXIF_FOCAL_LENGTH>');
		$shutter_speed = replace_exif_tags ($language_full, $image_exif, '<EXIF_EXPOSURE_TIME>');
		$iso = replace_exif_tags ($language_full, $image_exif, '<EXIF_ISO>');
		$sOut = <<<HTML
		<div class="infobox exif">
			<h5>{$s_exif}</h5>
			<table border="0" id="exif-info" summary="{$s_summary}">
				<tbody>
					<tr>
						<th scope="row">{$s_focal_length}:</th><td>{$focal_length}</td>
					</tr>
					<tr>
						<th scope="row">{$s_aperture}:</th><td>{$aperture}</td>
					</tr>
					<tr>
						<th scope="row">{$s_shutter_speed}:</th><td>{$shutter_speed}</td>
					</tr>
					<tr>
						<th scope="row"><abbr title="International Standards Organization">ISO</abbr>:</th><td>{$iso}</td>
					</tr>	
				</tbody>
			</table>
		</div>
HTML;
	return $sOut;
	}
}


function dm_tags_tab() {
	global $tags_output;
	global $aDefaults;
	if($aDefaults['tags'] == 'yes') {
		$sOut = '<div id="tags-tab" class="clearfix">';
		$sOut .= $tags_output;
		$sOut .= '</div>';	
		return $sOut;	
	}
}

//prevents sql injection in $_GET['not'] see below
//example the url &not=12,10,11))s,ss,124 will be converted to &not=12,10,11,0,124
function cleanup_not($not)
{
	$expl_arr = explode(',',$not);
	foreach($expl_arr as $id => $number)
	{
		$expl_arr[$id]= (int)$number;
	}
	$safe_str = implode(',',$expl_arr);
	return $safe_str;
}

function dm_oldest_id() {
	global $pixelpost_db_prefix;
	$query = "SELECT id from ". $pixelpost_db_prefix."pixelpost WHERE UNIX_TIMESTAMP(datetime) < ". time();
	$rs = mysql_query($query);
	$ra = mysql_fetch_array($rs);
	return $ra['id'];
}

function dm_newest_id() {
	global $pixelpost_db_prefix;
	$query = "SELECT id from ". $pixelpost_db_prefix."pixelpost ORDER BY datetime DESC limit 1";
	$rs = mysql_query($query);
	$ra = mysql_fetch_array($rs);
	return $ra['id'];
}

function dm_get_thumbs_js() {
	global $pixelpost_db_prefix;
	$sOut = '';
	$query = "SELECT image from ". $pixelpost_db_prefix."pixelpost ORDER BY datetime ASC";
	$rs = mysql_query($query);
	while ($ra = mysql_fetch_array($rs)) {
		$sOut = $sOut . '"'.$ra['image'].'",';
	}
	$sOut = substr($sOut, 0, strlen($sOut)-1);
	return($sOut);
}

// backend for the caroussel AJAX functionality

function dm_fetch_thumbs ($bNoJS=false, $sDir='', $nStart=0, $not=0, $nLimit=0){

	if(( !isset($_GET['x']) || $_GET['x'] !== 'caroussel') && (!$bNoJS)) {
		return '';
	}

	if(($_GET['direction'] !== 'forward') && ($_GET['direction'] !== 'backwards') && ($nLimit == 0)) {
		header ('Location: index.php'); // a request to /index.php?x=caroussel does not leave a blank page. you will be redirected to the frontend christian
		exit;
	}
	else {
		$sDirection = ($sDir == '') ? $_GET['direction'] : $sDir;
	}

	$sStartID = ($nStart == 0 ) ? intval($_GET['startid']) : $nStart;
	$nLimit = ($nLimit == 0 ) ? 5 : (int)$nLimit; // prevents an sql injection attack christian

	global $pixelpost_db_prefix;
	global $cfgrow;
	global $showprefix;
	global $local_width;
	global $local_height;


	$sNot = ($not == 0) ? cleanup_not($_GET['not']) : $not; 
	if(!preg_match('/[0-9,]$/', $_GET['not'])) {
		die('');
	}

	$startIMG = mysql_query("SELECT datetime FROM ".$pixelpost_db_prefix."pixelpost WHERE id='".$sStartID."'");
	list($datetime) = mysql_fetch_row($startIMG);

	$sStartDate = $datetime;

	if($sDirection == 'backwards') {
		$thumbs = mysql_query("SELECT id,headline,alt_headline,image,datetime FROM ".$pixelpost_db_prefix."pixelpost WHERE (datetime <= '$sStartDate') and (id not in (" . $sNot . ")) ORDER BY datetime desc limit 0, " . $nLimit);
	}	

	else {
		$thumbs = mysql_query("SELECT id,headline,alt_headline,image,datetime FROM ".$pixelpost_db_prefix."pixelpost WHERE (datetime >= '$sStartDate') and (id not in (" . $sNot . ")) ORDER BY datetime, id desc limit 0, " . $nLimit);
	}
	$nCount = 0;
	$aOut = array(); 
	while(list($id,$headline,$alt_headline,$image, $datetime) = mysql_fetch_row($thumbs)) {
		$nCount++;
		$imSRC = ltrim($cfgrow['thumbnailpath'], "./")."thumb_".$image;
		if(isset($_GET['current']) && ($_GET['current'] == $id)) {
			$sClass = 'current-thumbnail';
		}
		else {
			$sClass = 'thumbnails';
		}
		$aOut[] = "<a href='$showprefix$id'><img src='".ltrim($cfgrow['thumbnailpath'], "./")."thumb_".$image."' alt='$headline' title='$headline' class='" . $sClass . "' width='100' height='". $cfgrow['thumbheight'] ."' /></a>";
		$lastId = $id;
	}
	if($sDirection == 'backwards') {
		$aOut = array_reverse($aOut);
	}
	if($nCount == 5) {
		if($bNoJS) {
			return implode($aOut);
		}
		else {
			echo implode($aOut);
		}		
	}
	else {
		if($nCount == 0) {
			return '';
		}
		if($sDirection == 'backwards') {
			if(!$bNoJS) {
				for($i=$nCount;$i<5;$i++) {
					$sOut .= '<a href="#?showimage='.$lastId.'"><img class="dummy-element" width="100" height="' . $cfgrow['thumbheight'] . '" src="./templates/darkmatter/img/blank.gif"></a>';
				}
			}
			$sOut .= implode($aOut);
			if($bNoJS) {
				return $sOut;
			}
			else {
				echo $sOut;
			}
		}
		else {
			if(!$bNoJS) {
				for($i=$nCount;$i<5;$i++) {
					$sTempOut .= '<a href="#?showimage='.$lastId.'"><img class="dummy-element" width="100" height="' . $cfgrow['thumbheight'] .'" src="./templates/darkmatter/img/blank.gif"></a>';
				}		
				$sOut = implode($aOut).$sTempOut;
			}
			else
				$sOut = implode($aOut);
			if($bNoJS) {
				return $sOut;
			}
			else {
				echo $sOut;
			}		
		}		
	}
}

function dm_longdesc() {
	$sOut = $_SERVER['REQUEST_URI'] . '#longdescription';
	return $sOut;
}

function dm_self() {
	return $_SERVER['REQUEST_URI'];
}

function dm_thumbnail_row($thumbs, $image_id) {

	global $pixelpost_db_prefix;
	global $cfgrow;
	global $showprefix;	
	$prevThumb = false; 
	$aRawThumbs = explode("showimage=", $thumbs);
	foreach($aRawThumbs as $sThumb) {
		$this_id = explode("'", $sThumb); 
		$thisint = intval($this_id[0]);
		if(($thisint > 0) && ($thisint != $prevThumb)) {
			$aThumbs[] = $thisint;
			$prevThumb = $thisint;
		}
	}

	$nStartIDLeft = $aThumbs[0];
	$nStartIDRight = $aThumbs[sizeof($aThumbs)-1];

	if(isset($_GET['direction']) && ($_GET['direction'] == 'backwards') && (sizeof($aThumbs) < 5)) {
		$thumbs = dm_oldest_five();
	}
	if(isset($_GET['direction']) && ($_GET['direction'] == 'forward') && (sizeof($aThumbs) < 5)) {
		$thumbs = dm_newest_five();
	}
	if(isset($_GET['direction']) && ($_GET['direction'] == 'backwards') && (sizeof($aThumbs) < 5)) {
		$sHTML = '<a id="nav_left" type="prev" name="tnn" href="#tnn" class="end">older</a>';	
	}
	else {
		$sHTML = '<a id="nav_left" type="prev" name="tnn" href="index.php?showimage=' . $image_id . '&amp;not=' . implode(',', $aThumbs) . '&amp;startid=' . $nStartIDLeft . '&amp;direction=backwards&amp;current=' . $image_id . '#tnn">older</a>';
	}
	$sHTML .= "\n";

	$sThumbHTML = $thumbs; 
	$sThumbHTML = str_replace('<a', '<li><a', $sThumbHTML);
	$sThumbHTML = str_replace('</a>', '</a></li>', $sThumbHTML);
	$sThumbHTML = '<ul>' . $sThumbHTML . '</ul>';

	$sHTML .= '<div id="view-window" class="clearfix"><div id="slider" class="clearfix"><div class="tranche">' . $sThumbHTML . '</div></div></div>';
	$sHTML .= "\n";

	if(isset($_GET['direction']) && ($_GET['direction'] == 'forward') && (sizeof($aThumbs) < 5)) { 
		$sHTML .= '<a id="nav_right" type="next" class="end" href="#tnn">newer</a>';
	}
	else {
		$sHTML .= '<a id="nav_right" type="next" href="index.php?showimage=' . $image_id . '&amp;not=' . implode(',', $aThumbs) . '&amp;startid=' . $nStartIDRight .'&amp;direction=forward&amp;current=' . $image_id . '#tnn">newer</a>';	
	}
	return $sHTML;
}

function dm_edge_five($bOldest=false) {

	global $pixelpost_db_prefix;
	global $cfgrow;
	global $showprefix;

	if($bOldest) {
		$thumbs = mysql_query("SELECT id,headline,alt_headline,image,datetime FROM ".$pixelpost_db_prefix."pixelpost WHERE (datetime > 0) ORDER BY datetime asc limit 0, 5");
	}
	else {
		$thumbs = mysql_query("SELECT id,headline,alt_headline,image,datetime FROM ".$pixelpost_db_prefix."pixelpost WHERE (datetime > 0) ORDER BY datetime desc limit 0, 5");		
	}
	$nCount = 0;
	while(list($id,$headline,$alt_headline,$image, $datetime) = mysql_fetch_row($thumbs)) {
		$nCount++;
		$imSRC = ltrim($cfgrow['thumbnailpath'], "./")."thumb_".$image;
		if(isset($_GET['current']) && ($_GET['current'] == $id)) {
			$sClass = 'current-thumbnail';
		}
		else {
			$sClass = 'thumbnails';
		}
		$aOut[] = "<a href='$showprefix$id'><img src='".ltrim($cfgrow['thumbnailpath'], "./")."thumb_".$image."' alt='$headline' title='$headline' class='" . $sClass . "' width='100' height='" . $cfgrow['thumbheight'] . "' /></a>";
		$lastId = $id;
	}
	if($bOldest) {
		return implode($aOut);
	}
	else {
		return implode(array_reverse($aOut));		
	}
}

function dm_oldest_five() {
	return dm_edge_five(true);
}

function dm_newest_five() {
	return dm_edge_five();
}

function dm_category_list() {
	global $pixelpost_db_prefix;
	global $cfgrow;	

	$output = '';

	$cquery = mysql_query('select * from ' .$pixelpost_db_prefix . 'categories order by name asc');

	while(list($category_id, $category_name) = mysql_fetch_row($cquery)) {
		$output .= '<li><a href="./index.php?x=browse&amp;category=' . $category_id . '">' . $category_name . '</a></li>';
		$output .= "\n";
	}
	if(strlen($output) > 0) {
		$output = '<ul id="category_list">' . $output . '</ul>';
	}

	return $output;

}

function dm_thumbnail_block() {
	global $thumbnail_row;
	global $image_id;
	global $aDefaults;

	if($aDefaults['carousel'] == 'none') {
		return;
	}
	if($aDefaults['carousel'] == 'pixelpost') {
		$thumbnail_row = str_replace('<a', '<li><a', $thumbnail_row);
		$thumbnail_row = str_replace('</a>', '</a></li>', $thumbnail_row);		
		return '<div id="thumbnail-navigator"><div id="thumbs" class="clearfix nocarousel"><ul>' . $thumbnail_row . '</ul></div></div>';
	}	
	if(!isset($_GET['not'])) {
		$dm_thumbnails = dm_thumbnail_row($thumbnail_row, $image_id);
	}
	else {
		$dm_thumbnails = dm_thumbnail_row(dm_fetch_thumbs(true), $image_id);
	}	
	$sThumbHTML = '<div id="thumbnail-navigator"><div id="thumbs" class="clearfix">' . $dm_thumbnails . '</div></div>';
	return $sThumbHTML;
}

// pretty comments with gravatars

function dm_print_comments($imageid)
{
	global $pixelpost_db_prefix;
	global $lang_no_comments_yet;
	global $lang_visit_homepage;
	global $cfgrow;

	$comment_count = 0;
	$image_comments = "<ul class=\"commentslist\" id=\"clist\">"; // comments stored in this string
	$cquery = mysql_query("select datetime, message, name, url, email  from ".$pixelpost_db_prefix."comments where parent_id='".$imageid."' and publish='yes' order by id asc");
	$fi=1;
	while(list($comment_datetime, $comment_message, $comment_name, $comment_url, $comment_email) = mysql_fetch_row($cquery))
	{
		$comment_message = pullout($comment_message);
		$comment_name = pullout($comment_name);
		$comment_email = pullout($comment_email);

		if($comment_url != "")
		{
			if( preg_match( '/^(http|https):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}'.'((:[0-9]{1,5})?\/.*)?$/i' ,$comment_url))
			{
				$comment_name = "<a href=\"$comment_url\" title=\"$lang_visit_homepage\">$comment_name</a>";
			}
			else
			{
				unset($comment_url);
				$comment_name = "$comment_name";
			}
		}

		$comment_datetime = strtotime($comment_datetime);
		$comment_datetime = date($cfgrow['dateformat'],$comment_datetime);
		$sFirst = "";



		if ($comment_email == $cfgrow['email']){
			// admin comment

			if($comment_count==0) {
				$sFirst = "first ";
			}

			$image_comments .= '<li class="'.$sFirst.'admin_comment"><h4><img class="gravatar" alt="" width="40" height="40" src="http://www.gravatar.com/avatar.php?gravatar_id=' . md5($comment_email) . '&amp;size=40&amp;default=' . $cfgrow['siteurl'] . '/templates/darkmatter/img/default_gravatar.png" />'. $comment_name . ' // ' . $comment_datetime . '</h4><div class="commentcontent">' . $comment_message . '</div></li>';
		} else {

			if($comment_count==0) {
				$sFirst = " class=\"first\"";
			}

			$image_comments .= '<li'.$sFirst.'><h4><img class="gravatar" alt="" width="40" height="40" src="http://www.gravatar.com/avatar.php?gravatar_id=' . md5($comment_email) . '&amp;size=40&amp;default=' . $cfgrow['siteurl'] . '/templates/darkmatter/img/default_gravatar.png" />'. $comment_name . ' // ' . $comment_datetime . '</h4><div class="commentcontent">' . $comment_message . '</div></li>';
		}
		$comment_count++;

	}

	$image_comments .= "</ul>";
	if($comment_count == 0) $image_comments = "";
	return $image_comments;
}

$output = '';

if((isset($_GET['x']) && $_GET['x'] == 'caroussel')){
	$sThumbs = dm_fetch_thumbs();
	$tpl = str_replace("<DM_CAROUSSEL>",$sThumbs,$tpl);
	exit;
	
}
else{
	if($image_previous_id != $image_id) { 
		$output .= '<a id="photo-prev" type="prev" href="./index.php?showimage=' . $image_previous_id . '" title="previous photo: ' . $image_previous_title . '">&laquo; previous</a>';
	}
	if($image_next_id != $image_id) { 
		$output .= '<a id="photo-next" type="next" href="./index.php?showimage=' . $image_next_id . '" title="next photo: ' . $image_next_title . '">next &raquo;</a>';
	}


	if((isset($_GET['x']) && $_GET['x'] == 'browse')){
		$tpl = str_replace("<DM_TAGS_TAB>", dm_tags_tab(), $tpl);
		$catlist = dm_category_list();
		$tpl = str_replace("<DM_CATEGORYLIST>",$catlist,$tpl);
		$tpl = str_replace("<DM_STR_ALL_CATEGORIES>", STR_ALL_CATEGORIES ,$tpl);
	}
	
	if(!isset($_GET['x'])) {
		$sComments = dm_print_comments($image_id); 
		$tpl = str_replace("<DM_IMAGE_HEIGHT>", $image_height, $tpl); 
		$tpl = str_replace("<DM_NAVLINKS>",$output,$tpl);
		$tpl = str_replace("<DM_COMMENTS>",$sComments,$tpl);
		$tpl = str_replace("<DM_NAV_HEIGHT>", ($image_height-25), $tpl); 
		$tpl = str_replace("<DM_STR_INFORMATION_ABOUT_IMAGE>", STR_INFORMATION_ABOUT_IMAGE, $tpl);
		$tpl = str_replace("<DM_STR_EXIF>", STR_EXIF ,$tpl);
		$tpl = str_replace("<DM_STR_EXIF_SUMMARY>", STR_EXIF_SUMMARY ,$tpl);
		$tpl = str_replace("<DM_STR_FOCAL_LENGTH>", STR_FOCAL_LENGTH ,$tpl);
		$tpl = str_replace("<DM_STR_APERTURE>", STR_APERTURE ,$tpl);
		$tpl = str_replace("<DM_STR_SHUTTER_SPEED>", STR_SHUTTER_SPEED ,$tpl);
		$tpl = str_replace("<DM_STR_DESCRIPTION>", STR_DESCRIPTION ,$tpl);
		$tpl = str_replace("<DM_STR_POSTED_IN>", STR_POSTED_IN ,$tpl);
		$tpl = str_replace("<DM_STR_COMMENTS_ON>", STR_COMMENTS_ON ,$tpl);
		$tpl = str_replace("<DM_STR_THERE_ARE>", STR_THERE_ARE ,$tpl);
		$tpl = str_replace("<DM_STR_COMMENTS_TEXT>", STR_COMMENTS_TEXT ,$tpl);
		$tpl = str_replace("<DM_STR_COMMENT_NOTICE>", STR_COMMENT_NOTICE ,$tpl);
		$tpl = str_replace("<DM_STR_FORMLABEL_NAME>", STR_FORMLABEL_NAME ,$tpl);
		$tpl = str_replace("<DM_STR_FORMLABEL_EMAIL>", STR_FORMLABEL_EMAIL ,$tpl);
		$tpl = str_replace("<DM_STR_FORMLABEL_URL>", STR_FORMLABEL_URL ,$tpl);
		$tpl = str_replace("<DM_STR_FORMLABEL_COMMENT>", STR_FORMLABEL_COMMENT ,$tpl);
		$tpl = str_replace("<DM_STR_FORMBUTTON_SUBMIT>", STR_FORMBUTTON_SUBMIT ,$tpl);
		$tpl = str_replace("<DM_STR_POST_COMMENT_ON>", STR_POST_COMMENT_ON ,$tpl);
		$tpl = str_replace("<DM_THUMBSTRIP_HEIGHT>", $cfgrow['thumbheight'] + 60 ,$tpl);
		$tpl = str_replace("<DM_IMAGE_INFO>", dm_histogram().dm_exif().dm_description(), $tpl);
		$tpl = str_replace("<DM_IMAGEBROWSER>", dm_thumbnail_block(), $tpl);
		$tpl = str_replace("<DM_LONGDESC_URL>", dm_longdesc(), $tpl);
	}
	$tpl = str_replace("<DM_STR_HOME>", STR_HOME ,$tpl);
	$tpl = str_replace("<DM_STR_YOU_ARE_VIEWING>", STR_YOU_ARE_VIEWING ,$tpl);	
	$tpl = str_replace("<DM_STR_COLLECTION>", STR_COLLECTION ,$tpl);
	$tpl = str_replace("<DM_STR_ABOUT>", STR_ABOUT ,$tpl);	
	$tpl = str_replace("<DM_SELF>", dm_self(), $tpl);
	$tpl = str_replace("<DM_JSCONFIG>", dm_jsconfig() ,$tpl);
	$page_width = 800;
	$half_page_width = $page_width / 2;
	$tpl = str_replace("<DM_BODY_WIDTH>", $page_width, $tpl);
	$tpl = str_replace("<DM_PARR_WIDTH>", $half_page_width, $tpl);
    $tpl = str_replace("<DM_FORMFIELDS_WIDTH>", ($page_width-385), $tpl);
	$tpl = str_replace("<DM_ARR_MARGIN>", (($page_width-700)/2), $tpl);
	$tpl = str_replace("<DM_JSFILES>", dm_jsfiles(), $tpl);
	$tpl = str_replace("<DM_CSSFILE>", dm_cssfile(), $tpl);
}
?>
