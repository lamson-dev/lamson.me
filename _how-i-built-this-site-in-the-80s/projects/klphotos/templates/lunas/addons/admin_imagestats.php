<?php
$addon_version = "1.3";
/*
Admin Image Full Statsitic addon 1.3
Pixelpost 1.5 Final, 1.6 Final, 1.7 Final

Contact: Irek : iwanatko@gmail.com
Copyright © 2009 <http://wwww.dphoto.pl>


Based on Image Statsitic addon 2.0.1:
Admin Image Statsitic addon 2.0
Pixelpost 1.5 Final, 1.6 Final, 1.7 Final

Contact: Karin at 2@kg3.de
Copyright © 2007 <http://wwww.blog.uhlig.at>
_____________________
||// /_\ ||)) || |\||
||\\// \\||\\ || ||\|
¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
License: http://www.gnu.org/copyleft/gpl.html

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

================================================================
*/

// check if the image stats table exists!
if ($_GET['view']=='addons') {
	$query = "SELECT * from ".$pixelpost_db_prefix."imagestats";
	if (mysql_query($query)) $imgstattbl = 1;
	else $imgstattbl = 0;
}

// check if the long image stats table and image stats settings exists!
if ($_GET['view']=='addons' || $_GET['view']=='image-stats') {
	$query = "SELECT * FROM ".$pixelpost_db_prefix."imagestats_long";
	if(mysql_query($query)) $longistat = 1;
	else $longistat = 0;
	$query = "SELECT * FROM ".$pixelpost_db_prefix."imagestats_settings";
	if(mysql_query($query)) {
		$istatset = 1;
		if(!mysql_query("SELECT nrofimg from ".$pixelpost_db_prefix."imagestats_settings")) mysql_query("ALTER TABLE ".$pixelpost_db_prefix."imagestats_settings ADD nrofimg INT(3) NOT NULL default '25'") or die ('<span style="color:red"><b>Inserting column nrofimg into settings table failed!</b></span><br />Error: '. mysql_error());
	}
	else {
		mysql_query("
		CREATE TABLE IF NOT EXISTS ".$pixelpost_db_prefix."imagestats_settings (
		public_counter CHAR(3) NOT NULL default 'yes',
		count_bots CHAR(3) NOT NULL default 'yes',
		bot_tags VARCHAR(150) NOT NULL default 'bot,spider,slurp',
		nrofimg INT(3) NOT NULL default '25')")
		or die('<span style="color:red"><b>Creation of Image Stats Settings Table failed!</b></span><br />Error: '. mysql_error());
		mysql_query("INSERT INTO ".$pixelpost_db_prefix."imagestats_settings SET public_counter = 'yes', count_bots = 'yes', bot_tags = 'bot,spider,slurp'") or die ('<span style="color:red"><b>Insert of default values in settings table failed!</b></span><br />Error: '. mysql_error());
		$istatset = 1;
	}
}

if ($_POST['createimgstatsempty'] || $_POST['createimgstatsfull']) {
		mysql_query("
		CREATE TABLE IF NOT EXISTS ".$pixelpost_db_prefix."imagestats (
		ct_id INT NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(ct_id),
		img_id VARCHAR(11),
		datetime datetime NOT NULL default '0000-00-00 00:00:00',
		referer varchar(255) NOT NULL,
		ip varchar(255) NOT NULL,
		ua varchar(255) NOT NULL)")
		or die('<span style="color:red"><b>Creation of Image Stats Table failed!</b></span><br />Error: '. mysql_error());
		$tell_it = '<span style="color:red"><b>Successfully created tables for Image Stats.</b></span><br />';
		if ($_POST['createimgstatsfull']) {
			$myquery = mysql_query("SELECT id, datetime, referer, ua, ip, ruri FROM ".$pixelpost_db_prefix."visitors ORDER BY id asc");
			if ($myquery) {
				while(list($id, $datetime, $ref, $ua, $ip, $url) = mysql_fetch_row($myquery)) {
					if (preg_match('/.+showimage\=(\d+).*/', $url, $img_ids)) {
						mysql_query("INSERT INTO ".$pixelpost_db_prefix."imagestats SET ct_id='".$id."', img_id='".$img_ids[1]."', datetime='".$datetime."', referer='".$ref."', ip='".$ip."', ua='".$ua."'") or die('<span style="color:red"><b>Insert of values failed!</b></span><br />Error: '. mysql_error());
					}
				}
				$tell_it .= '<span style="color:red"><b>Successfully updated Image Stats Table with Visitors Table!</b></span><br />';
			}
			else $tell_it .= '<span style="color:red"><b>Insert of values failed! Table pixelpost_visitors is empty!</span><br />';
		}
		$tell_it .= '<br /><b>Have fun with it!</b><br /><br />';
		$imgstattbl = 1;
		
		$oldquery = "SELECT img_id from ".$pixelpost_db_prefix."visitors";
		if (mysql_query($oldquery)) mysql_query("ALTER TABLE ".$pixelpost_db_prefix."visitors DROP img_id");
}	
	
if ($_POST['deleteimgstats']) {
		if (mysql_query("SELECT * from ".$pixelpost_db_prefix."imagestats")) {
			mysql_query("DROP TABLE ".$pixelpost_db_prefix."imagestats") or die('<br /><span style="color:red"><b>Delete of Image Stats Table failed!</b></span><br />Error: '. mysql_error());
		}
		if (mysql_query("SELECT * from ".$pixelpost_db_prefix."imagestats_settings")) {
			mysql_query("DROP TABLE ".$pixelpost_db_prefix."imagestats_settings") or die('<br /><span style="color:red"><b>Delete of Image Stats Table failed!</b></span><br />Error: '. mysql_error());
		}
		$tell_it = '<span style="color:red"><b>Successfully deleted tables for Image Stats.</b></span><br />You can create them as new now.<br /><br />';
		$imgstattbl = 0;
		$istatset = 0;
}

if ($_POST['long_imgstats']) {
		if (mysql_query("SELECT * FROM ".$pixelpost_db_prefix."imagestats_long")) {
			mysql_query("DROP TABLE ".$pixelpost_db_prefix."imagestats_long") or die('<span style="color:red"><b>Delete of Long Image Stats Table failed!</b></span><br />Error: '. mysql_error());
			$tell_it = '<span style="color:red"><b>Successfully deleted Longtime Image Stats Table!</b></span><br /><br />';
			$longistat = 0;
		}
		else {
			$longtable = mysql_query("
			CREATE TABLE IF NOT EXISTS ".$pixelpost_db_prefix."imagestats_long (
			m_id INT NOT NULL AUTO_INCREMENT,
			PRIMARY KEY(m_id),
			month datetime NOT NULL default '0000-00-00 00:00:00',
			visits INT(11) NOT NULL,
			visitors INT(11) NOT NULL,
			img_id INT(11) NOT NULL,
			num_imgid INT(11) NOT NULL)")
			or die('<span style="color:red"><b>Creation of Long Image Stats Table failed!</b></span><br />Error: '. mysql_error());
			$tell_it = '<span style="color:red"><b>Successfully created Longtime Image Stats Table!</b></span><br /><br />';
			$longistat = 1;
		}
}

if ($_POST['imgstatupdate']) {	
		mysql_query("UPDATE ".$pixelpost_db_prefix."imagestats_settings SET public_counter = '".$_POST['pubimgct']."', count_bots = '".$_POST['botct']."', bot_tags = '".$_POST['searchbots']."', nrofimg = '".$_POST['numbentries']."'");
		$tell_it = '<span style="color:red"><b>Successfully updated Image Stats Settings!</b></span><br /><br />';
}
//preparing the text and buttons to show in addons menu
if ($_GET['view']=='addons') {
	if ($imgstattbl != 1) {
		$createtbl = 'Please create the tables for image statistics now!<br /><br />
			If you have set &quot;Visitor Booking&quot; in Admin Options to "yes", you can use the values of pixelpost_visitors table as starting point for image counting. Please view readmy.txt for details!
		<br /><br />
		<input type="submit" name="createimgstatsfull" value="Create Table with Visitor Values"><br /><br />
		You can also start with zero from now ('.gmdate("Y m d",gmdate("U")+(3600 * $cfgrow['timezone'])).').<br /><br />
		<input type="submit" name="createimgstatsempty" value="Create empty Table">';
	}
	else {
		$createtbl = 'If you don\'t want to use the image stats addon anymore or want to reset it, you can delete the tables created for image stats addon ('.$pixelpost_db_prefix.'imagestats and '.$pixelpost_db_prefix.'imagestats_settings).<br />
		<span style="color:red"><b>All your image statistics will be gone!</b></span><br /><br />
		<input type="submit" name="deleteimgstats" value="Delete Tables">';
	}	
	
	if ($longistat != 1) {
		$longbut = 'If you want to use the long time statistics, please create the table for it:<br />
		<br /><input type="submit" name="long_imgstats" value="Create Table">';
	}
	else {
		$longbut = 'If you don\'t want to use the long time statistics anymore, you can delete the table.<br /><span style="color:red"><b>Image statistics of past months will be gone!</b></span><br />
		<br /><input type="submit" name="long_imgstats" value="Delete Table">';
	}
	
	if ($istatset == 1) {
	$imgstatset = sql_array("SELECT public_counter, count_bots, bot_tags, nrofimg FROM ".$pixelpost_db_prefix."imagestats_settings");
	$imgstatsettings = '<fieldset style="width:600px; border:1px solid #666666"><legend><b>Settings for admin_imagestats: </b></legend>
Use public image counter: <b>yes</b><input type="radio" name="pubimgct" value="yes"'.($imgstatset['public_counter']=='yes'?'checked=" checked"':'').'>&nbsp;&nbsp;&nbsp;<input type="radio" name="pubimgct" value="no"'.($imgstatset['public_counter']=='no'?' checked="checked"':'').'><b>no</b><br />
Show visits of search engine bots in public counter: <b>yes</b><input type="radio" name="botct" value="yes"'.($imgstatset['count_bots']=='yes'?' checked="checked"':'').'>&nbsp;&nbsp;&nbsp;<input type="radio" name="botct" value="no"'.($imgstatset['count_bots']=='no'?'checked=" checked"':'').'><b>no</b><br />
List user agent tags of search engine bots: <input type="text" name="searchbots" value="'.$imgstatset['bot_tags'].'" style="width:140px; height:14px; border:1px solid #666666;background:white; font-size:9pt; margin:1px;"> (comma separated list, no quotes)<br />
Number of entries shown in admin image stats: <input type="text" name="numbentries" value="'.$imgstatset['nrofimg'].'"  style="width:14px; height:14px; border:1px solid #666666;background:white; font-size:9pt; margin:1px;"><br />
<input type="submit" name="imgstatupdate" value="update settings">
</fieldset><br />';
	}
	else $imgstatsettings = '';
}


// Same info as non admin addons
$addon_name = "Admin Image Full Statistics Addon";
$addon_description = '<a name="imagestats"></a>'.$tell_it.' Public: Visitor counter per image overall/day on image template.<br />
Admin: A lot of image statistics in menu &quot;<a href="index.php?view=image-stats">IMAGE-STATS</a>&quot;.<br />
Due to pixelpost script you can see the menu item &quot;<a href="index.php?view=image-stats">IMAGE-STATS</a>&quot; only when not viewing &quot;Addons&quot; page.<br /><br />
<b>New Tags:</b><br />&lt;IMAGE_COUNTER&gt; displays the number of visitors of this particular image<br />
 &lt;IMAGE_TODAY&gt; displays the number of visitors of this particular image today<br /><br />
<form name="form1" action="index.php?view=addons#imagestats" method="post">
'.$imgstatsettings.'
<fieldset style="width:600px; border:1px solid #666666"><legend><b>Tables for Image Statistics:</b></legend>
'.$createtbl.'
</fieldset>
<br />
<fieldset style="width:600px; border:1px solid #666666"><legend><b>Longtime Image Stats</b></legend>
'.$longbut.'
</fieldset></form>
<br /><br />Author: <a href="http://www.dphoto.pl" target="_blank">Dphoto</a> (<a href="mailto:iwanatko@gmail.com">iwanatko@gmail.com</a>)<br />
Check version at: <a href="http://www.pixelpost.org/extend/addons/image-full-statistics" target="_blank">Image Full Statistics</a> <br />';

// add Image Stats page title to the main menu
$addon_workspace = 'admin_main_menu';
$addon_menu = "Image-Stats";
add_admin_functions('',$addon_workspace,$addon_menu,'');

// assign a function name to make the content of the new page
$addon_workspace = 'admin_main_menu_contents';
$addon_function_name = 'show_image_stats';
$addon_admin_submenu = "";
add_admin_functions($addon_function_name,$addon_workspace,$addon_menu,$addon_admin_submenu);

function show_image_stats() {
	global $pixelpost_db_prefix, $cfgrow;
	if($_GET['view']=='image-stats') {
		$query = "SELECT * FROM ".$pixelpost_db_prefix."imagestats LIMIT 0,1";
		if(!mysql_query($query)) {
			echo '<span style="color:red"><b>Image Stats Table is not existing! Please return to addons menu to create and optionally update the table!</b></span><br /> ';
			exit;
		}
		$imgstatset = sql_array("SELECT bot_tags FROM ".$pixelpost_db_prefix."imagestats_settings");
		if ($imgstatset) {
			if (!preg_match('/.+,.*/',$imgstatset['bot_tags'])) {
				echo '<span style="color:red"><b>Bot tag list seems to be not comma separated list. Please return to addons menu to check!</b></span><br />';
				exit;
			}
			$bots = explode(',', $imgstatset['bot_tags']); //list the search engine bots to exclude them from statistic
			foreach ($bots as $bot) {
				$bot_excl .= "AND ua NOT LIKE '%".$bot."%' ";
				$bot_incl .= " ua LIKE '%".$bot."%' OR";
			}
			$bot_incl = rtrim($bot_incl, " OR");
			$bot_excl = ltrim($bot_excl, "AND ");
		}
		else {
			echo '<span style="color:red"><b>Image Stats Settings are not existing or bot tag list is not comma separated list. Please return to addons menu to check!</b></span><br /> ';
			exit;
		}
		$imgstatset = sql_array("SELECT nrofimg FROM ".$pixelpost_db_prefix."imagestats_settings");
		if ($imgstatset) $nrofimg = $imgstatset['nrofimg'];
		else $nrofimg = 25;
		
		// check if long image stats are ready
		// update long image stats
		$cmonth = gmdate("Y-m",gmdate("U")+(3600 * $cfgrow['timezone']));
		$cdatum = gmdate("Y-m-d",gmdate("U")+(3600 * $cfgrow['timezone']));
		$query = mysql_query("SELECT * FROM ".$pixelpost_db_prefix."imagestats WHERE datetime NOT LIKE '".$cmonth."%'");
		if (mysql_num_rows($query)!=0) 
		{
			$lquery = "SELECT * from ".$pixelpost_db_prefix."imagestats_long";
			if (mysql_query($lquery)) 
			{

			  $months = mysql_query("SELECT distinct SUBSTRING( datetime , 1,7) FROM " . $pixelpost_db_prefix . "imagestats");

			  while ($value = mysql_fetch_row($months)) 
			  {
				$all_images = sql_array("SELECT count(ct_id) as count FROM ".$pixelpost_db_prefix."imagestats WHERE datetime LIKE '".$value[0]."%'");
				$visits = $all_images['count'];


				$all_images = sql_array("SELECT count(ct_id) AS count FROM ".$pixelpost_db_prefix."imagestats WHERE ".$bot_excl." AND datetime LIKE '".$value[0]."%'");
				$visitors = $all_images['count'];

				$sum_up = sql_array("SELECT datetime, img_id, count(ct_id) AS count FROM ".$pixelpost_db_prefix."imagestats WHERE datetime LIKE '".$value[0]."%' AND ".$bot_excl." GROUP BY img_id ORDER BY count desc limit 0,1");
				$top_imgid = $sum_up['img_id'];
				$top_count = $sum_up['count'];
				$datefull = $sum_up['datetime'];


				$monthexist = sql_array("SELECT month FROM ".$pixelpost_db_prefix."imagestats_long WHERE month LIKE '".$value[0]."%'");
				if ($monthexist['month']) 
				{
				  $upd = mysql_query("UPDATE ".$pixelpost_db_prefix."imagestats_long SET visits='". $visits ."', visitors='". $visitors ."' WHERE month LIKE '". $value[0] ."%'") or die('<span style="color:red"><b>Update failed!</b></span><br />Error: '. mysql_error());
				}
 				else
				{
				  $upd = mysql_query("INSERT INTO ".$pixelpost_db_prefix."imagestats_long SET month='". $datefull ."', visits='".$visits."', visitors='".$visitors."', img_id='".$top_imgid."', num_imgid='".$top_count."'") or die('<span style="color:red"><b>Update failed!</b></span><br />Error: '. mysql_error());
				}
			  }
		
			}
			else 
			{
			  mysql_query("OPTIMIZE TABLE ".$pixelpost_db_prefix."imagestats");
			  echo '<span style="color:red"><b>Month Beginning: Image Stats start with zero for new month!</b></span><br /> ';
			}
		}
		echo '<div id="caption">Image Full Statistics</div>
					<div id="submenu">';

		$submenucssclass='selectedsubmenu';

		$submenucssclass='';
		if (!isset($_GET['image-statsview'])) $submenucssclass = 'selectedsubmenu';
		
		if (isset($_GET['image-statsview']) && ($_GET['image-statsview']=='overview' )) $submenucssclass = 'selectedsubmenu';
		echo "<a href='?view=image-stats&amp;image-statsview=overview' class='".$submenucssclass."' >OVERVIEW</a>\n";

		$submenucssclass = '';
		if (isset($_GET['image-statsview']) && ($_GET['image-statsview']=='last_viewed' ))
		$submenucssclass = 'selectedsubmenu';				
		echo "<a href='?view=image-stats&amp;image-statsview=last_viewed' class='".$submenucssclass."' >LAST VIEWED</a>\n";

		$submenucssclass = '';
		if (isset($_GET['image-statsview']) && ($_GET['image-statsview']=='most_viewed' ))
		$submenucssclass = 'selectedsubmenu';				
		echo "<a href='?view=image-stats&amp;image-statsview=most_viewed' class='".$submenucssclass."' >MOST VIEWED</a>\n";

		$submenucssclass = '';
		if (isset($_GET['image-statsview']) && ($_GET['image-statsview']=='most_viewed_monthly' ))
		$submenucssclass = 'selectedsubmenu';				
		echo "<a href='?view=image-stats&amp;image-statsview=most_viewed_monthly' class='".$submenucssclass."' >MONTHLY MOST VIEWED</a>\n";

		$submenucssclass = '';
		if (isset($_GET['image-statsview']) && ($_GET['image-statsview']=='terms' ))
		$submenucssclass = 'selectedsubmenu';				
		echo "<a href='?view=image-stats&amp;image-statsview=terms' class='".$submenucssclass."' >SEARCH TERMS</a>\n";
		
		$query = "SELECT * FROM ".$pixelpost_db_prefix."imagestats_long";
		if(mysql_query($query)) {
			$submenucssclass = '';
			if (isset($_GET['image-statsview']) && ($_GET['image-statsview']=='longistat' ))
			$submenucssclass = 'selectedsubmenu';				
			echo "<a href='?view=image-stats&amp;image-statsview=longistat' class='".$submenucssclass."' >LONG TIME STATS</a>\n";
		}
		echo "</div>\n";
		
		if ($_GET['image-statsview']=='')
			img_over($pixelpost_db_prefix, $bot_excl, $bot_incl, $cmonth, $cdatum);

		if (isset($_GET['image-statsview']) && $_GET['image-statsview']=='overview'  )
			img_over($pixelpost_db_prefix, $bot_excl, $bot_incl, $cmonth, $cdatum);
			
		if (isset($_GET['image-statsview']) && $_GET['image-statsview']=='last_viewed' )
			img_last($pixelpost_db_prefix, $bot_excl, $bot_incl, $nrofimg);
			
		if (isset($_GET['image-statsview']) && $_GET['image-statsview']=='most_viewed' )
			img_most($pixelpost_db_prefix, $bot_excl, $bot_incl, $nrofimg);
			
		if (isset($_GET['image-statsview']) && $_GET['image-statsview']=='most_viewed_monthly' )
			img_most_monthly($pixelpost_db_prefix, $bot_excl, $bot_incl, $nrofimg, $cmonth);

		if (isset($_GET['image-statsview']) && $_GET['image-statsview']=='terms' )
			img_sterms($pixelpost_db_prefix, $cdatum);
			
		if (isset($_GET['image-statsview']) && $_GET['image-statsview']=='longistat' )
			img_longistat($pixelpost_db_prefix, $bot_excl, $bot_incl, $cmonth);
	}
}
	
function img_over($pixelpost_db_prefix, $bot_excl, $bot_incl, $cmonth, $cdatum) {
        global $cfgrow;
        $Date_month = gmdate("F",gmdate("U")+(3600 * $cfgrow['timezone']));

		$all_images = sql_array("SELECT ct_id FROM ".$pixelpost_db_prefix."imagestats ORDER BY ct_id desc limit 0,1");
		echo '<div class="jcaption">Overview</div><div class="content"><b>Total visits:</b> '.$all_images['ct_id'].'<br /><br />';
		$global_img = $all_images['ct_id'];
		$all_images = sql_array("SELECT count(ct_id) as count FROM ".$pixelpost_db_prefix."imagestats WHERE datetime LIKE '".$cmonth."%'");
		echo '<b>Total visits in '.$Date_month.':</b> '.$all_images['count'].' (100%)<br />';

		$tot_img = ($global_img);
        $tot_month_img = $all_images['count'];

		$all_images = sql_array("SELECT count(ct_id) AS count FROM ".$pixelpost_db_prefix."imagestats WHERE ".$bot_excl." AND datetime LIKE '".$cmonth."%'");
		echo '<b>Visits from visitors in '.$Date_month.':</b> '.$all_images['count'].' ('.@round(($all_images['count']/$tot_month_img)*100).'%)<br />';
		$total_visitors = $all_images['count'];
		$all_images = sql_array("SELECT count(ct_id) AS count FROM ".$pixelpost_db_prefix."imagestats WHERE datetime LIKE '".$cmonth."%' AND (".$bot_incl.")");
		echo '<b>Visits from Bots &amp; Spiders in '.$Date_month.':</b> '.$all_images['count'].' ('.@round(($all_images['count']/$tot_month_img)*100).'%)<br /><br />';

		$all_images = sql_array("SELECT count(ct_id) AS count FROM ".$pixelpost_db_prefix."imagestats WHERE ".$bot_excl);
		echo '<b>Total visits from visitors:</b> '.$all_images['count'].' ('.@round(($all_images['count']/$tot_img)*100).'%)<br />';
		$total_visitors = $all_images['count'];
		$all_images = sql_array("SELECT count(ct_id) AS count FROM ".$pixelpost_db_prefix."imagestats WHERE (".$bot_incl.")");
		echo '<b>Total visits from Bots &amp; Spiders:</b> '.$all_images['count'].' ('.@round(($all_images['count']/$tot_img)*100).'%)<br /><br />';

		$all_images = sql_array("SELECT count(DISTINCT ip) AS count FROM ".$pixelpost_db_prefix."imagestats WHERE ".$bot_excl);
		echo '<b>Visits from Unique IPs:</b> '.$all_images['count'].' (excluding Bots &amp; Spiders)<br />';
		$avg = @round($total_visitors/$all_images['count'], 2);
		echo '<b>Average viewed images per Unique IP:</b> '.$avg.'<br /><br />';
		$all_images = sql_array("SELECT count(ct_id) AS count FROM ".$pixelpost_db_prefix."imagestats WHERE datetime LIKE '".$cdatum."%'");
		echo '<b>Total visits of images today:</b> '.$all_images['count'].' (100%)<br />';
		$tot_img = $all_images['count'];
		$all_images = sql_array("SELECT count(ct_id) AS count FROM ".$pixelpost_db_prefix."imagestats WHERE ".$bot_excl." AND datetime LIKE '".$cdatum."%'");
		echo '<b>Visits from visitors today:</b> '.$all_images['count'].' ('.@round(($all_images['count']/$tot_img)*100).'%)<br />';
		$all_images = sql_array("SELECT count(ct_id) AS count FROM ".$pixelpost_db_prefix."imagestats WHERE datetime LIKE '".$cdatum."%' AND(".$bot_incl.")");
		echo '<b>Visits from Bots &amp; Spiders today:</b> '.$all_images['count'].' ('.@round(($all_images['count']/$tot_img)*100).'%)<br /><br />';

		echo '<center><b>Image visits per day in '.gmdate("Y",gmdate("U")+(3600 * $cfgrow['timezone'])).' year</b><br /><br /></center>';
               $month_now  = gmdate("m");
               $month_last = ($month_now - 2);
               $year       = gmdate("Y");

           for ($month = $month_now; $month >= $month_last; $month--) 
           {
             if (strlen($month) == 1)
             {
               $month = '0' . $month; 
             } 

             if ($month <= 0)
             {
                $stat_month = ($year - 1) . '-' . (12 + $month);
             }
             else
             {
               $stat_month = $year . '-' .$month;
             }

		echo '<BR><b>Image visits per day in '. $stat_month .':</b><br /><br /><table border="0" height="25" cellspacing="0" cellpadding="0" align="center" style="border:1px solid #cccccc;"><tr><td style="border:1px solid #cccccc;">&nbsp;</td>';
		$days = gmdate("t",gmdate("U")+(3600 * $cfgrow['timezone']));
		$j = gmdate("j",gmdate("U")+(3600 * $cfgrow['timezone']));
		for ($i=1;$i<=$days;$i++) echo '<td style="border:1px solid '.($i==$j?'#999999':'#cccccc').'; width:25px;" align="center"><b>'.$i.'</b></td>';
		echo '</tr><tr><td style="border:1px solid #cccccc; padding:2px;" align="right"><b>Visitors</b></td>';

		for ($i=1;$i<=$days;$i++) {
			$d = ($i<10?'0'.$i:$i);
			$all_images = sql_array("SELECT count(ct_id) AS count FROM ".$pixelpost_db_prefix."imagestats WHERE ".$bot_excl." AND datetime LIKE '".$stat_month."-".$d."%'");
			echo '<td style="border:1px solid '.($i==$j?'#999999;':'#cccccc').'; '.($i==$j?'font-weight:bold; ':'').'width:25px;" align="center">'.$all_images['count'].'</td>';
		}
		echo '</tr><tr><td style="border:1px solid #cccccc; padding:2px;" align="right"><b>All</b></td>';
		for ($i=1;$i<=$days;$i++) {
			$d = ($i<10?'0'.$i:$i);
			$all_images = sql_array("SELECT count(ct_id) AS count FROM ".$pixelpost_db_prefix."imagestats WHERE datetime LIKE '".$stat_month."-".$d."%'");
			echo '<td style="border:1px solid '.($i==$j?'#999999;':'#cccccc').'; '.($i==$j?'font-weight:bold; ':'').'width:25px;" align="center">'.$all_images['count'].'</td>';
		}
		echo '</tr></table>';

           }		
		
		echo '</div>';
}				
function img_last($pixelpost_db_prefix, $bot_excl, $bot_incl, $nrofimg) {
		global $cfgrow;
		echo '<div class="jcaption">Last images viewed (no Bots &amp; Spiders)</div><div class="content"><ul>';
		$myquery = mysql_query("SELECT img_id,datetime,referer,ip,ua FROM ".$pixelpost_db_prefix."imagestats WHERE ".$bot_excl." ORDER BY ct_id desc limit 0,".$nrofimg);
		if (mysql_num_rows($myquery)!=0) {
			while(list($img_id, $datetime, $ref, $ip, $ua) = mysql_fetch_row($myquery)) {
				$stimage = sql_array("SELECT image, headline FROM ".$pixelpost_db_prefix."pixelpost WHERE id = ".$img_id);
				echo '<li><b>'.$datetime.'</b>:<br /><a href="'.$cfgrow['siteurl'].'index.php?showimage='.$img_id.'" target="_blank" title="'.stripslashes($stimage['headline']).'"><img src="../thumbnails/thumb_'.$stimage['image'].'" align="left" border="0" hspace="4" vspace="4"/><b>'.stripslashes($stimage['headline']).'</b></a><br />
				<b>IP-address:</b> '.$ip.' | <a href="http://www.dnsstuff.com/tools/city.ch?ip='.$ip.'" target="_blank">view IP at DNSstuff.com</a>&nbsp;|&nbsp;<a href="http://www.geoiptool.com/en/?IP='.$ip.'" target="_blank">view IP at GeoIpTool.com</a><br />';
				echo '<b>User Agent: </b>'.$ua.'<br /><b>Referrer: </b><a href="'.$ref.'" target="_blank" title="view referrer">'.$ref.'</a></li>';
			}
		}
		else echo 'Sorry, no visits until now!';
		echo '</ul></div>';
}		
function img_most($pixelpost_db_prefix, $bot_excl, $bot_incl, $nrofimg) {
		global $cfgrow;
		echo '<div class="jcaption">Most viewed images:</div><div class="content">';
		$myquery = mysql_query("SELECT img_id, count(ct_id) AS count FROM ".$pixelpost_db_prefix."imagestats WHERE ".$bot_excl." GROUP BY img_id ORDER BY count desc limit 0,".$nrofimg);
		$counter = 1;
		echo '<table align="center"><tr><td width="380" valign="top"><ul><li><b>Visitors</b> (excluding Bots &amp; Spiders):</li>';
		if (mysql_num_rows($myquery)!=0) {
			while(list($img_id, $count) = mysql_fetch_row($myquery)) {
				$stimage = sql_array("SELECT image, headline FROM ".$pixelpost_db_prefix."pixelpost WHERE id = ".$img_id);
				echo '<li><b>'.($counter<10?'&nbsp;'.$counter:$counter).'</b> <a href="'.$cfgrow['siteurl'].'index.php?showimage='.$img_id.'" target="_blank" title="'.stripslashes($stimage['headline']).'"><img src="../thumbnails/thumb_'.$stimage['image'].'" border="0" hspace="4" />'.stripslashes($stimage['headline']).'</a> ('.$count.')<br clear="all" /></li>';
				$counter++;
			}
		}
		else echo '<li>Sorry, no visits until now!</li>';
		echo '</ul></td><td width="380" valign="top"><ul><li><b>All Visits</b> (visits including Bots &amp; Spiders):</li>';
		$myquery = mysql_query("SELECT img_id, count(ct_id) AS count FROM ".$pixelpost_db_prefix."imagestats GROUP BY img_id ORDER BY count desc limit 0,".$nrofimg);
		$counter = 1;
		if (mysql_num_rows($myquery)!=0) {
			while(list($img_id, $count) = mysql_fetch_row($myquery)) {
				$stimage = sql_array("SELECT image, headline FROM ".$pixelpost_db_prefix."pixelpost WHERE id = ".$img_id);
				echo '<li><b>'.($counter<10?'&nbsp;'.$counter:$counter).'</b> <a href="'.$cfgrow['siteurl'].'index.php?showimage='.$img_id.'" target="_blank" title="'.$stimage['headline'].'"><img src="../thumbnails/thumb_'.$stimage['image'].'" border="0" hspace="4" />'.$stimage['headline'].'</a> ('.$count.')<br clear="all" /></li>';
				$counter++;
			}
		}
		else echo '<li>Sorry, no visits until now!</li>';
		echo '</ul></td></tr></table>';
		echo '</div>';
}
function img_most_monthly($pixelpost_db_prefix, $bot_excl, $bot_incl, $nrofimg, $cmonth) {
		global $cfgrow;
		echo '<div class="jcaption">Most viewed images in '.gmdate("F",gmdate("U")+(3600 * $cfgrow['timezone'])).':</div><div class="content">';
		$myquery = mysql_query("SELECT img_id, count(ct_id) AS count FROM ".$pixelpost_db_prefix."imagestats WHERE ".$bot_excl." AND datetime LIKE '".$cmonth."%' GROUP BY img_id ORDER BY count desc limit 0,".$nrofimg);
		$counter = 1;
		echo '<table align="center"><tr><td width="380" valign="top"><ul><li><b>Visitors</b> (excluding Bots &amp; Spiders):</li>';
		if (mysql_num_rows($myquery)!=0) {
			while(list($img_id, $count) = mysql_fetch_row($myquery)) {
				$stimage = sql_array("SELECT image, headline FROM ".$pixelpost_db_prefix."pixelpost WHERE id = ".$img_id);
				echo '<li><b>'.($counter<10?'&nbsp;'.$counter:$counter).'</b> <a href="'.$cfgrow['siteurl'].'index.php?showimage='.$img_id.'" target="_blank" title="'.stripslashes($stimage['headline']).'"><img src="../thumbnails/thumb_'.$stimage['image'].'" border="0" hspace="4" />'.stripslashes($stimage['headline']).'</a> ('.$count.')<br clear="all" /></li>';
				$counter++;
			}
		}
		else echo '<li>Sorry, no visits until now!</li>';
		echo '</ul></td><td width="380" valign="top"><ul><li><b>All Visits</b> (visits including Bots &amp; Spiders):</li>';
		$myquery = mysql_query("SELECT img_id, count(ct_id) AS count FROM ".$pixelpost_db_prefix."imagestats WHERE datetime LIKE '".$cmonth."%' GROUP BY img_id ORDER BY count desc limit 0,".$nrofimg);
		$counter = 1;
		if (mysql_num_rows($myquery)!=0) {
			while(list($img_id, $count) = mysql_fetch_row($myquery)) {
				$stimage = sql_array("SELECT image, headline FROM ".$pixelpost_db_prefix."pixelpost WHERE id = ".$img_id);
				echo '<li><b>'.($counter<10?'&nbsp;'.$counter:$counter).'</b> <a href="'.$cfgrow['siteurl'].'index.php?showimage='.$img_id.'" target="_blank" title="'.$stimage['headline'].'"><img src="../thumbnails/thumb_'.$stimage['image'].'" border="0" hspace="4" />'.$stimage['headline'].'</a> ('.$count.')<br clear="all" /></li>';
				$counter++;
			}
		}
		else echo '<li>Sorry, no visits until now!</li>';
		echo '</ul></td></tr></table>';
		echo '</div>';
}
function img_sterms($pixelpost_db_prefix, $cdatum) {
		global $cfgrow;
		echo '<div class="jcaption">Search Engine Terms:</div><div class="content">
		<table width="800" align="center" cellspacing="2" cellpadding="4">';
		$last_id='0';
		$start=1;
		$ct=0;
		$myquery = mysql_query("SELECT datetime, referer, img_id FROM ".$pixelpost_db_prefix."imagestats WHERE referer LIKE '%q=%' ORDER BY img_id, ct_id desc");
		if (mysql_num_rows($myquery)==0) echo '<tr><td>Sorry, no search engine terms found!</td></tr></table></div>';
		else {
			echo'<tr><td colspan="2">Search engine hits in '.gmdate("F",gmdate("U")+(3600 * $cfgrow['timezone'])).': <b>'.mysql_num_rows($myquery).'</b></td></tr>';
			while(list($date, $ref, $img_id) = mysql_fetch_row($myquery)) {
				if (preg_match('/.+?\Wq\=(.+?)(\&|\z)/', $ref, $terms)) {
					if ($img_id != $last_id) {
						if ($start!=1) {
							echo '<br /><b>'.$ct.' hit'.($ct>1?'s':'').'</b></td></tr>';
							$ct=0;
						}
						echo '<tr>
							<td align="left" valign="top" width="90" style="border:1px solid '.(substr($date, 0, 10)==$cdatum?'#999':'#E7E7E7').';">';
						$statimage = sql_array("SELECT image, headline FROM ".$pixelpost_db_prefix."pixelpost WHERE id = ".$img_id);
						echo '<a href="'.$cfgrow['siteurl'].'index.php?showimage='.$img_id.'" target="_blank" title="'.$statimage['headline'].'"><img src="../thumbnails/thumb_'.$statimage['image'].'" border="0" width="80" hspace="4" alt="'.$statimage['headline'].'" align="left" /></a></td>
					 		<td align="left" valign="top" style="border:1px solid '.(substr($date, 0, 10)==$cdatum?'#999':'#E7E7E7').';">'.(substr($date, 0, 10)==$cdatum?'<b>':'').''.$date.': <a href="'.$ref.'" target="_blank" title="'.$ref.'">'.urldecode($terms[1]).'</a>'.(substr($date, 0, 10)==$cdatum?'</b>':'').'<br />';
					 	$last_id = $img_id;
					 	$start = 0;
					 	$ct++;
					}
					else {
						echo (substr($date, 0, 10)==$cdatum?'<b>':'').''.$date.': <a href="'.$ref.'" target="_blank" title="'.$ref.'">'.urldecode($terms[1]).'</a>'.(substr($date, 0, 10)==$cdatum?'</b>':'').'<br />'."\n";
						$ct++;
					}
				}
			}
			echo '<br /><b>'.$ct.' hit'.($ct>1?'s':'').'</b></td></tr></table></div>';
		}
}

function img_longistat($pixelpost_db_prefix, $bot_excl, $bot_incl, $cmonth) {
	global $cfgrow;
	echo '<div class="jcaption">Long Time Stats</div><div class="content"><table cellpadding="2" cellspacing="4" align="center"><tr>
	<td width="80" style="border-bottom:1px solid #cccccc" align="center"><b>Month</b></td><td width="100" style="border-bottom:1px solid #cccccc" align="center"><b>Visitors</b></td>
	<td width="100" style="border-bottom:1px solid #cccccc" align="center"><b>All Visits</b></td><td width="300" style="border-bottom:1px solid #cccccc" align="center"><b>Visitors Most Viewed Image (visited)</b></td></tr>';
	$myquery = mysql_query("SELECT month, visits, visitors, img_id, num_imgid FROM ".$pixelpost_db_prefix."imagestats_long ORDER BY month desc");
	while(list($month, $visits, $visitors, $img_id, $num_imgid) = mysql_fetch_row($myquery)) {
		preg_match('/(\d{4}-\d{2})-.+/', $month, $m);
		if ($img_id) $stimage = sql_array("SELECT image, headline FROM ".$pixelpost_db_prefix."pixelpost WHERE id = ".$img_id);
		echo '<tr><td style="border-bottom:1px solid #cccccc" align="center"><b>'.$m[1].'</b></td>
		 <td style="border-bottom:1px solid #cccccc" align="center">'.$visitors.'</td>
		 <td style="border-bottom:1px solid #cccccc" align="center">'.$visits.'</td>
		 <td style="border-bottom:1px solid #cccccc" align="left">';
		 if (mysql_num_rows($myquery)!=0) echo '<a href="'.$cfgrow['siteurl'].'index.php?showimage='.$img_id.'" target="_blank" title="'.stripslashes($stimage['headline']).'"><img src="../thumbnails/thumb_'.$stimage['image'].'" border="0" hspace="4" height="20" />'.stripslashes($stimage['headline']).'</a> ('.$num_imgid.')</td></tr>';
		 else echo '&nbsp;</td></tr>';
		}
	
	echo '</table></div>';
}

// here is the part for working in image_template.html
if(! eregi('/admin/',$_SERVER["REQUEST_URI"]) &! $_GET['x']) {
	update_imagestats($image_id);
	$cmonth = gmdate("Y-m",gmdate("U")+(3600 * $cfgrow['timezone']));
	$cdatum = gmdate("Y-m-d",gmdate("U")+(3600 * $cfgrow['timezone']));
	$imgstatquery = "SELECT * FROM ".$pixelpost_db_prefix."imagestats_settings";
	if($imgstatset = sql_array($imgstatquery)) {
		if ($imgstatset['public_counter'] == 'yes') {
			$bot_excl = '';
			if ($imgstatset['count_bots'] == 'no') {
				$bots = explode(',', $imgstatset['bot_tags']); //search engine bots to exclude them from statistic
				foreach ($bots as $bot) {
					$bot_excl .= "AND ua NOT LIKE '%".$bot."%' ";
				}
			}
			if(isset($image_id)) {
				$imgstat_row = sql_array("SELECT count(ct_id) as count FROM ".$pixelpost_db_prefix."imagestats WHERE img_id = '".$image_id."' AND datetime LIKE '".$cdatum."%'".$bot_excl);

				$img_today = $imgstat_row['count'];
				$imgstat_row = sql_array("SELECT count(ct_id) as count FROM ".$pixelpost_db_prefix."imagestats WHERE img_id = '".$image_id."'");
				$img_counter = $imgstat_row['count'];
			}
			$tpl = str_replace("<IMAGE_COUNTER>",$img_counter,$tpl);
			$tpl = str_replace("<IMAGE_TODAY>",$img_today,$tpl);
		}
		else {
			$tpl = str_replace("<IMAGE_COUNTER>",'',$tpl);
			$tpl = str_replace("<IMAGE_TODAY>",'',$tpl);
		}
	}
	else {
		$tpl = str_replace("<IMAGE_COUNTER>",'',$tpl);
		$tpl = str_replace("<IMAGE_TODAY>",'',$tpl);
	}
}

function update_imagestats($image_id) {
	global $pixelpost_db_prefix;
	global $cfgrow;
	$datetime = gmdate("Y-m-d H:i:s",gmdate("U")+(3600 * $cfgrow['timezone']));
	$host = $_SERVER['HTTP_HOST'];
	$ref = addslashes($_SERVER['HTTP_REFERER']);
	$ua = addslashes($_SERVER['HTTP_USER_AGENT']);
	$ip = $_SERVER['REMOTE_ADDR'];
//don't count them when logged in as admin or coming from admin
	if(!eregi($cfgrow['siteurl'].'admin', $_SERVER['HTTP_REFERER']) &! isset($_SESSION["pixelpost_admin"]) &! eregi('x=save_comment', $_SERVER['HTTP_REFERER']) &! isset($_GET['x']) &! isset($_GET['popup'])) {
			//don't count them when same image viewed by same IP in same minute
			$entry = sql_array("SELECT count(img_id) as count FROM ".$pixelpost_db_prefix."imagestats WHERE datetime LIKE '".substr($datetime, 0, 16)."%' AND ip LIKE '$ip' AND img_id LIKE '$image_id'");
			if ($entry['count']<1) {
				mysql_query("INSERT INTO ".$pixelpost_db_prefix."imagestats SET img_id='".$image_id."', datetime='".$datetime."', referer='".$ref."', ip='".$ip."', ua='".$ua."'");
			}
	}
}

?>
 
