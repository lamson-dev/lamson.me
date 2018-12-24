<?php
$tfmm_err_level = error_reporting(E_ALL);

//
// Credit to Otto Strassen, ostrassen@gmail.com, for discovering bug in URL regex javascript
// Credit to giesau@yahoo.de, for discovering and fixing bug in area map when image is larger then template specified dimensions
//

$addon_name = "The World in 35mm";
$addon_version = "2.7.4";

$tfmm_default_copyright = '&copy; ' . date('Y') . '. All Rights Reserved';
$tfmm_db_table = "{$pixelpost_db_prefix}35mm";

define('CSSTHEMES_DIR', dirname(__FILE__) . '/../templates/theworldin35mm/themes');
define('ABOUTIMGS_DIR', dirname(__FILE__) . '/../templates/theworldin35mm/images/about');
define('NAVARROWS_DIR', dirname(__FILE__) . '/../templates/theworldin35mm/images/navarrows');
define('HTACCESS_FILE', dirname(__FILE__) . '/../.htaccess');
define('TFMM_IMGBORDERSIZE', 20);

function tfmm_sql_array($query, $result_type = MYSQL_BOTH)
{
	$result = mysql_query($query)
		or die("<pre>{$query} :: " . mysql_error() . "</pre>");
	$row = mysql_fetch_array($result, $result_type);
	mysql_free_result($result);
	return $row;
}

function tfmm_sql_escape_string($str)
{
	$str = trim($str);
	
	if (empty($str)) {
		return "NULL";
	}
	else if (is_numeric($str)) {
		return $str;
	}
	
	return "'" . mysql_real_escape_string($str) . "'";
}

//
// TRY AND CREATE TABLE
//

mysql_query(
	"CREATE TABLE IF NOT EXISTS `{$tfmm_db_table}` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`author` VARCHAR(64) DEFAULT 'CHANGE ME',
		`comments_display` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
		`copyright` VARCHAR(64) DEFAULT NULL,
		`email` VARCHAR(128) DEFAULT 'bounce@example.com',
		`googleanalytics` VARCHAR(128) DEFAULT NULL,
		`googlewebmaster` VARCHAR(128) DEFAULT NULL,
		`title_separator` VARCHAR(128) DEFAULT '|',
		`width` INT(11) UNSIGNED NOT NULL DEFAULT 930,
		PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8")
	or die("<pre>Internal error creating 35mm table for this addon. " . mysql_error() . "</pre>");

//
// new fields
//

$tfmm_fields_sql = array(
	"maxthumbs" => "ALTER TABLE `{$tfmm_db_table}` ADD `maxthumbs` INT(11) UNSIGNED NOT NULL DEFAULT 12",                              // added v2.6.7b
	"thumbs_animation" => "ALTER TABLE `{$tfmm_db_table}` ADD `thumbs_animation` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1",              // added v2.6.7b
	"expand_img_width" => "ALTER TABLE `{$tfmm_db_table}` ADD `expand_img_width` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0",              // added v2.6.8b
	"mod_rewrite" => "ALTER TABLE `{$tfmm_db_table}` ADD `mod_rewrite` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0",                        // added v2.6.8b
	"css_file" => "ALTER TABLE `{$tfmm_db_table}` ADD `css_file` VARCHAR(255) NOT NULL DEFAULT 'light.css'",                           // added v2.6.8b
	"arrow_position" => "ALTER TABLE `{$tfmm_db_table}` ADD `arrow_position` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0",                  // added v2.6.8b
	"arrow_prev" => "ALTER TABLE `{$tfmm_db_table}` ADD `arrow_prev` VARCHAR(255) NOT NULL DEFAULT 'round_white_prev.gif'",            // added v2.6.8b
	"arrow_next" => "ALTER TABLE `{$tfmm_db_table}` ADD `arrow_next` VARCHAR(255) NOT NULL DEFAULT 'round_white_next.gif'",            // added v2.6.8b
	"thumbs_hover_animation" => "ALTER TABLE `{$tfmm_db_table}` ADD `thumbs_hover_animation` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1",  // added v2.6.9b
	"title_format" => "ALTER TABLE `{$tfmm_db_table}` CHANGE `title_separator` `title_format` VARCHAR(255) DEFAULT '%P | %S | %T'",    // added v2.6.9b
	"thumbs_loader" => "ALTER TABLE `{$tfmm_db_table}` ADD `thumbs_loader` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1",                    // added v2.7.2
);

foreach ($tfmm_fields_sql as $fieldname => $fieldaltersql) {
	$tfmm_column = mysql_query("SHOW COLUMNS FROM `{$tfmm_db_table}` LIKE '{$fieldname}'")
		or die("<pre>SHOW COLUMNS :: " . mysql_error() . "</pre>");
	
	if (mysql_num_rows($tfmm_column) == 0) {
		mysql_query($fieldaltersql)
			or die("<pre>ALTER TABLE :: " . mysql_error() . "</pre>");
	}
}

//
// GET 35MM CONFIG
//

$tfmmconfig = tfmm_sql_array("SELECT * FROM `{$tfmm_db_table}`", MYSQL_ASSOC);

//
// CREATE DEFAULTS
//

if ($tfmmconfig == null) {
	mysql_query(
		"INSERT INTO `{$tfmm_db_table}`
			(`copyright`) VALUES ('{$tfmm_default_copyright}')")
		or die("<pre>INSERT :: " . mysql_error() . "</pre>");
		
	$tfmmconfig = sql_array("SELECT * FROM `{$pixelpost_db_prefix}35mm`");
}

$tfmm_author                 = $tfmmconfig['author'];
$tfmm_comments_display       = $tfmmconfig['comments_display'];
$tfmm_copyright              = $tfmmconfig['copyright'];
$tfmm_email                  = $tfmmconfig['email'];
$tfmm_googleanalytics        = $tfmmconfig['googleanalytics'];
$tfmm_googlewebmaster        = $tfmmconfig['googlewebmaster'];
$tfmm_title_format           = $tfmmconfig['title_format'];
$tfmm_width                  = intval($tfmmconfig['width']);
$tfmm_maxthumbs              = intval($tfmmconfig['maxthumbs']);
$tfmm_thumbs_animation       = intval($tfmmconfig['thumbs_animation']);
$tfmm_expand_img_width       = intval($tfmmconfig['expand_img_width']);
$tfmm_mod_rewrite            = intval($tfmmconfig['mod_rewrite']);
$tfmm_css_file               = $tfmmconfig['css_file'];
$tfmm_css_url                = $cfgrow['siteurl'] . 'templates/theworldin35mm/themes/' . $tfmm_css_file;
$tfmm_arrow_position         = intval($tfmmconfig['arrow_position']);
$tfmm_arrow_prev             = $tfmmconfig['arrow_prev'];
$tfmm_arrow_next             = $tfmmconfig['arrow_next'];
$tfmm_thumbs_hover_animation = intval($tfmmconfig['thumbs_hover_animation']);
$tfmm_thumbs_loader          = intval($tfmmconfig['thumbs_loader']);

//
// HELPER FUNCTIONS FOR 35MMM
//

function tfmm_googleanalytics($siteid = null)
{
	if (empty($siteid) || $siteid == "XX-0000000-0") {
		return null;
	}
	
	if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") {
		$gaproto = "http://www.";
	}
	else {
		$gaproto = "http://ssl.";
	}
	
	$ga  = <<<EOD
<script type="text/javascript" src="${gaproto}google-analytics.com/ga.js"></script>
<script type="text/javascript">
try{var pageTracker=_gat._getTracker("${siteid}");pageTracker._trackPageview();}catch(err){}
</script>
EOD;
	
	return $ga;
}

function tfmm_googlewebmaster($verifyid = null)
{
	if (empty($verifyid)) {
		return null;
	}
	
	return "<meta name=\"google-site-verification\" content=\"{$verifyid}\" />";
}

function tfmm_sitemap()
{
	global $cfgrow, $pixelpost_db_prefix, $cdate;
	
	$output = null;
	
	list($index_modified_time) = sql_array(
		"SELECT DATE_FORMAT(`datetime`, '%Y-%m-%d') AS `datetime`
			FROM `{$pixelpost_db_prefix}pixelpost`
			WHERE `datetime` <= '{$cdate}'
			ORDER BY `datetime` DESC
			LIMIT 0,1");
	
	$browse_modified_time = date("Y-m-d", filemtime(dirname($_SERVER['SCRIPT_FILENAME']) . "/templates/{$cfgrow['template']}/browse_template.html"));
	$about_modified_time = date("Y-m-d", filemtime(dirname($_SERVER['SCRIPT_FILENAME']) . "/templates/{$cfgrow['template']}/about_template.html"));
	
	$mapheader = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<url>
		<lastmod>{$index_modified_time}</lastmod>
		<loc>{$cfgrow['siteurl']}</loc>
		<priority>1.0</priority>
	</url>
EOT;
	
	$query = mysql_query(
		"SELECT `id`, DATE_FORMAT(`datetime`, '%Y-%m-%d') AS `datetime`
			FROM `{$pixelpost_db_prefix}pixelpost`
			WHERE `datetime` <= '{$cdate}'
			ORDER BY `datetime` DESC");

	while (list($id, $datetime) = mysql_fetch_row($query)) {
		$output .= <<<EOT
	<url>
		<lastmod>{$datetime}</lastmod>
		<loc>{$cfgrow['siteurl']}p/{$id}</loc>
		<priority>0.9</priority>
	</url>
EOT;
	}
	mysql_free_result($query);
	
	$output .= <<<EOT
	<url>
		<lastmod>{$browse_modified_time}</lastmod>
		<loc>{$cfgrow['siteurl']}browse/1</loc>
		<priority>0.8</priority>
	</url>
	<url>
		<lastmod>{$about_modified_time}</lastmod>
		<loc>{$cfgrow['siteurl']}about</loc>
		<priority>0.7</priority>
	</url>
</urlset>
EOT;
	
	header("Content-type: application/xml");
	echo $mapheader . $output;
	exit;
}

function tfmm_mediarss()
{
	global $cfgrow;
	
	$mapheader = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
EOT;
	
	$output = '';
	
	header("Content-type: application/xml");
	echo $mapheader . $output;
	exit;
}

function tfmm_browse_url($pagenum, $option_key = null, $option_val = null) {
	global $cfgrow, $tfmm_mod_rewrite, $tfmm_maxthumbs;
	
	if ($tfmm_maxthumbs == 0) {
		$pagenum = '';
	}
	else if (is_numeric($pagenum)) {
		$pagenum = sprintf("%d", $pagenum);
	}
	
	if ($option_key != null && $option_val != null) {
		return $cfgrow['siteurl'] . sprintf(($tfmm_mod_rewrite) ? "browse/%s/%s/%s" : "index.php?x=browse&amp;%s=%s&amp;pagenum=%s", $option_key, $option_val, $pagenum);
	}
	else {
		return $cfgrow['siteurl'] . sprintf(($tfmm_mod_rewrite) ? "browse/%s" : "index.php?x=browse&amp;pagenum=%s", $pagenum);
	}
}

function tfmm_image_url($imgid) {
	global $cfgrow, $tfmm_mod_rewrite;
	
	return $cfgrow['siteurl'] . sprintf(($tfmm_mod_rewrite) ? "p/%d" : "index.php?showimage=%d", $imgid);
}

//
// generate css file options
//

$cf_options = array();

if ($handle = opendir(CSSTHEMES_DIR)) {
	while (false !== ($file = readdir($handle))) {
		if ($file != '.' && $file != '..' && strtolower(substr(strrchr($file, '.'), 1)) == 'css') {
			$cf_options[] = $file;
		}
	}
	closedir($handle);
	sort($cf_options);
}

//
// ADMIN INTERFACE SIDE
//

if (isset($admin_panel) && $admin_panel == 1) {
	$siteurl35mmwarning = null;
	$isupdated35mm = null;
	
	$_tmp_siteurl = trim($cfgrow['siteurl']);
	if (empty($_tmp_siteurl)) {
		$siteurl35mmwarning = <<<EOD
<span style="color:#ff0000;font-size:14px;font-weight:bold;">
	**&nbsp;Warning: Pixelpost installation problem.
	Site URL not set. Please set site URL in Pixelpost ADMIN options section.
	This addon will not work correctly until the site URL is set.&nbsp;**
</span>
EOD;
	}
	
	if (isset($_GET['x']) && $_GET['x'] == "updatetheworldin35mm") {
		$tfmm_author = htmlentities(trim($_POST['tfmm_author']), ENT_QUOTES, "UTF-8");
		$tfmm_comments_display = intval(trim($_POST['tfmm_comments_display']));
		$tfmm_copyright = htmlentities(trim($_POST['tfmm_copyright']), ENT_QUOTES, "UTF-8");
		$tfmm_email = htmlentities(trim($_POST['tfmm_email']), ENT_QUOTES, "UTF-8");
		$tfmm_googleanalytics = htmlentities(trim($_POST['tfmm_googleanalytics']), ENT_QUOTES, "UTF-8");
		$tfmm_googlewebmaster = htmlentities(trim($_POST['tfmm_googlewebmaster']), ENT_QUOTES, "UTF-8");
		$tfmm_title_format = htmlentities(trim($_POST['tfmm_title_format']), ENT_QUOTES, "UTF-8");
		$tfmm_width = intval(trim($_POST['tfmm_width']));
		$tfmm_maxthumbs = intval(trim($_POST['tfmm_maxthumbs']));
		$tfmm_thumbs_animation = intval(trim($_POST['tfmm_thumbs_animation']));
		$tfmm_expand_img_width = intval(trim($_POST['tfmm_expand_img_width']));
		$tfmm_mod_rewrite = intval(trim($_POST['tfmm_mod_rewrite']));
		$tfmm_css_file = htmlentities(trim($_POST['tfmm_css_file']), ENT_QUOTES, "UTF-8");
		$tfmm_arrow_position = intval(trim($_POST['tfmm_arrow_position']));
		$tfmm_arrow_prev = htmlentities(trim($_POST['tfmm_arrow_prev']), ENT_QUOTES, "UTF-8");
		$tfmm_arrow_next = htmlentities(trim($_POST['tfmm_arrow_next']), ENT_QUOTES, "UTF-8");
		$tfmm_thumbs_hover_animation = intval(trim($_POST['tfmm_thumbs_hover_animation']));
		$tfmm_thumbs_loader = intval(trim($_POST['tfmm_thumbs_loader']));
		
		if ($tfmm_width <= 500) {
			$tfmm_width = 500;
		}
		
		if ($tfmm_maxthumbs < 0) {
			$tfmm_maxthumbs = 0;
		}
		
		// update db
		$query = sprintf(
			"UPDATE `{$tfmm_db_table}`
				SET `author` = %s,
					`comments_display` = %d,
					`copyright` = %s,
					`email` = %s,
					`googleanalytics` = %s,
					`googlewebmaster` = %s,
					`title_format` = %s,
					`width` = %d,
					`maxthumbs` = %d,
					`thumbs_animation` = %d,
					`expand_img_width` = %d,
					`mod_rewrite` = %d,
					`css_file` = %s,
					`arrow_position` = %d,
					`arrow_prev` = %s,
					`arrow_next` = %s,
					`thumbs_hover_animation` = %d,
					`thumbs_loader` = %d",
			tfmm_sql_escape_string($tfmm_author),
			tfmm_sql_escape_string($tfmm_comments_display),
			tfmm_sql_escape_string($tfmm_copyright),
			tfmm_sql_escape_string($tfmm_email),
			tfmm_sql_escape_string($tfmm_googleanalytics),
			tfmm_sql_escape_string($tfmm_googlewebmaster),
			tfmm_sql_escape_string($tfmm_title_format),
			tfmm_sql_escape_string($tfmm_width),
			tfmm_sql_escape_string($tfmm_maxthumbs),
			tfmm_sql_escape_string($tfmm_thumbs_animation),
			tfmm_sql_escape_string($tfmm_expand_img_width),
			tfmm_sql_escape_string($tfmm_mod_rewrite),
			tfmm_sql_escape_string($tfmm_css_file),
			tfmm_sql_escape_string($tfmm_arrow_position),
			tfmm_sql_escape_string($tfmm_arrow_prev),
			tfmm_sql_escape_string($tfmm_arrow_next),
			tfmm_sql_escape_string($tfmm_thumbs_hover_animation),
			tfmm_sql_escape_string($tfmm_thumbs_loader));
		
		mysql_query($query)
			or die("<pre>UPDATE :: " . mysql_error() . "</pre>");
		
		$isupdated35mm = '<div style="color: #cc0000; padding: 4px 0px; font-weight: bold;">Update Successful!</div>';
	}
	
	if ($tfmm_mod_rewrite == 0) {
		$tfmm_mr_options_no = "selected=\"selected\" ";
		$tfmm_mr_options_yes = "";
	}
	else {
		$tfmm_mr_options_no = "";
		$tfmm_mr_options_yes = "selected=\"selected\" ";
	}
	
	if ($tfmm_comments_display == 0) {
		$tfmm_cd_options_hide = "selected=\"selected\" ";
		$tfmm_cd_options_show = "";
	}
	else {
		$tfmm_cd_options_hide = "";
		$tfmm_cd_options_show = "selected=\"selected\" ";
	}
	
	if ($tfmm_thumbs_animation == 0) {
		$tfmm_ta_options_no = "selected=\"selected\" ";
		$tfmm_ta_options_yes = "";
	}
	else {
		$tfmm_ta_options_no = "";
		$tfmm_ta_options_yes = "selected=\"selected\" ";
	}
	
	if ($tfmm_thumbs_loader == 0) {
		$tfmm_tl_options_no = "selected=\"selected\" ";
		$tfmm_tl_options_yes = "";
	}
	else {
		$tfmm_tl_options_no = "";
		$tfmm_tl_options_yes = "selected=\"selected\" ";
	}
	
	if ($tfmm_expand_img_width == 0) {
		$tfmm_eiw_options_no = "selected=\"selected\" ";
		$tfmm_eiw_options_yes = "";
	}
	else {
		$tfmm_eiw_options_no = "";
		$tfmm_eiw_options_yes = "selected=\"selected\" ";
	}
	
	if ($tfmm_arrow_position == 0) {
		$tfmm_ap_options_outside = "selected=\"selected\" ";
		$tfmm_ap_options_inside = "";
	}
	else {
		$tfmm_ap_options_outside = "";
		$tfmm_ap_options_inside = "selected=\"selected\" ";
	}
	
	if ($tfmm_thumbs_hover_animation == 0) {
		$tfmm_ha_options_no = "selected=\"selected\" ";
		$tfmm_ha_options_yes = "";
	}
	else {
		$tfmm_ha_options_no = "";
		$tfmm_ha_options_yes = "selected=\"selected\" ";
	}
	
	$tfmm_cf_options = '';
	
	foreach ($cf_options as $option) {
		if ($option == $tfmm_css_file) {
			$tfmm_cf_options .= "<option selected=\"selected\" value=\"{$option}\">{$option}</option>";
		}
		else {
			$tfmm_cf_options .= "<option value=\"{$option}\">{$option}</option>";
		}
	}
	
	//
	// navigation arrows
	//
	
	$arr_options = array();
	$tfmm_arrprev_options = '';
	$tfmm_arrnext_options = '';
	
	if ($handle = opendir(NAVARROWS_DIR)) {
		while (false !== ($file = readdir($handle))) {
			$ext = strtolower(substr(strrchr($file, '.'), 1));
			if ($file != '.' && $file != '..' && ($ext == 'gif' || $ext == 'jpg' || $ext == 'png')) {
				$arr_options[] = $file;
			}
		}
		closedir($handle);
	}
	
	sort($arr_options);
	
	foreach ($arr_options as $option) {
		if ($option == $tfmm_arrow_prev) {
			$tfmm_arrprev_options .= "<option selected=\"selected\" value=\"{$option}\">{$option}</option>";
		}
		else {
			$tfmm_arrprev_options .= "<option value=\"{$option}\">{$option}</option>";
		}
		if ($option == $tfmm_arrow_next) {
			$tfmm_arrnext_options .= "<option selected=\"selected\" value=\"{$option}\">{$option}</option>";
		}
		else {
			$tfmm_arrnext_options .= "<option value=\"{$option}\">{$option}</option>";
		}
	}
	
	//
	// mod_rewrite and htaccess checks
	//
	
	if ($tfmm_mod_rewrite == 0) {
		$tfmm_modrewrite_status = '';
	}
	else {
		$tfmm_modrewrite_rules = array(
			"RewriteEngine on",
			"RewriteBase /",
			"RewriteRule ^(browse|about)/?$ index.php?x=$1 [L]",
			"RewriteRule ^(p|photo)/([1-9][0-9]*)$ index.php?showimage=$2 [L]",
			"RewriteRule ^comment/([1-9][0-9]*)$ index.php?popup=comment&showimage=$1 [L]",
			"RewriteRule ^comment/submit$ index.php?x=save_comment [L]",
			"RewriteRule ^browse/?$ index.php?x=browse&pagenum=1 [L]",
			"RewriteRule ^browse/([1-9][0-9]*)/?$ index.php?x=browse&pagenum=$1 [L]",
			"RewriteRule ^browse/category/([1-9][0-9]*)/?$ index.php?x=browse&category=$1&pagenum=1 [L]",
			"RewriteRule ^browse/category/([1-9][0-9]*)/([1-9][0-9]*)/?$ index.php?x=browse&category=$1&pagenum=$2 [L]",
			"RewriteRule ^browse/tag/([^/]+)/?$ index.php?x=browse&tag=$1&pagenum=1 [L]",
			"RewriteRule ^browse/tag/([^/]+)/([1-9][0-9]*)/?$ index.php?x=browse&tag=$1&pagenum=$2 [L]",
			"RewriteRule ^browse/archivedate/([0-9]{4})-(0[1-9]|1[0-2])/?$ index.php?x=browse&archivedate=$1-$2&pagenum=1 [L]",
			"RewriteRule ^browse/archivedate/([0-9]{4})-(0[1-9]|1[0-2])/([1-9][0-9]*)/?$ index.php?x=browse&archivedate=$1-$2&pagenum=$3 [L]",
			"RewriteRule ^feeds/(rss|atom).xml$ index.php?x=$1 [L]",
			"RewriteRule ^feeds/mediarss.xml$ index.php?z=mediarss [L]",
			"RewriteRule ^sitemap.xml$ index.php?z=sitemap [L]",
		);
		
		$tfmm_modrewrite_status = "<span style=\"color: green; font-weight: bold;\">OK</span>";
		
		if (!file_exists(HTACCESS_FILE)) {
			$tfmm_modrewrite_status = "<span style=\"color: red; font-weight: bold;\">Detected missing .htaccess file. Please upload your .htaccess file to the root folder of your pixelpost installation.</span>";
		}
		else if (function_exists('apache_get_modules') === true && !in_array('mod_rewrite', apache_get_modules())) {
			$tfmm_modrewrite_status = "<span style=\"color: red; font-weight: bold;\">Apache module mod_rewrite is not loaded. Please contact your hosting company about mod_rewrite support.</span>";
		}
		else {
			$mod_rewrite_file = file_get_contents(HTACCESS_FILE);
		
			if (preg_match("/RewriteBase[ \t]+(.+)/", $mod_rewrite_file, $mod_matches) !== 1) {
				$tfmm_modrewrite_status = "<span style=\"color: red; font-weight: bold;\">Invalid RewriteBase rule not found in .htaccess file.</span>";
			}
			else {
				$url = parse_url($cfgrow['siteurl']);
				if ($url['path'] != $mod_matches[1]) {
					$tfmm_modrewrite_status = "<span style=\"color: red; font-weight: bold;\">Invalid RewriteBase rule.</span> Rule ({$mod_matches[1]}) does not match Site URL path ({$url['path']}).";
				}
				else {
					foreach ($tfmm_modrewrite_rules as $rule) {
						if (strpos($mod_rewrite_file, $rule) === false) {
							$tfmm_modrewrite_status = "<span style=\"color: red; font-weight: bold;\">Detected missing rewrite rule(s). Please make sure your .htaccess file is correctly setup.</span>";
							break;
						}
					}
				}
			}
		}
		
		$addon_mod_rewrite_txt = "<IfModule mod_rewrite.c>\n" . implode("\n", $tfmm_modrewrite_rules) . "\n</IfModule>";
		$addon_mod_rewrite_txt = htmlentities($addon_mod_rewrite_txt, ENT_QUOTES, "UTF-8");
		
		$tfmm_modrewrite_status = <<<EOT
<h3>URL Rewrite Rules (mod_rewrite)</h3>

<p><b>Status:</b>&nbsp;{$tfmm_modrewrite_status}</p>

<p>Copy-and-Paste the following text into your <b>.htaccess</b> file in the root folder of your pixelpost installation on your webserver.
<b>RewriteBase</b> should be equal the your site&#39;s relative path. For example, if your site was hosted on http://www.example.com/,
then your <b>RewriteBase</b> would be / (as shown below). If your pixelpost installation was hosted on http://www.example.com/pixelpost/,
your <b>RewriteBase</b> would be /pixelpost/.</p>

<pre style="background-color: #fff; border: 1px solid #cccccc; font-family: monospace; color: #333; font-size: 9px; margin: 6px 0px; padding: 6px; overflow: visible;">
{$addon_mod_rewrite_txt}
</pre>

EOT;
	}
	
	$addon_description = <<<EOD
{$siteurl35mmwarning}{$isupdated35mm}
<form method="post" action="index.php?view=addons&amp;x=updatetheworldin35mm" accept-charset="UTF-8">
	<fieldset style="border: none;">
		<table border="0" cellspacing="2" cellpadding="2">
			<tbody>
				<tr>
					<td style="text-align: right; white-space: nowrap;"><label for="tfmm_mod_rewrite">Use Clean URLs:</label>&nbsp;</td>
					<td>
						<select name="tfmm_mod_rewrite">
							<option {$tfmm_mr_options_no}value="0">No</option>
							<option {$tfmm_mr_options_yes}value="1">Yes</option>
						</select>&nbsp;<span style="font-size: 10px; font-weight: bold;"><span style="color: #c00;">*</span> requires Rewrite URL support in webserver (Apache is only officially supported).</span>
					</td>
				</tr>
				<tr>
					<td style="text-align: right; white-space: nowrap;"><label for="tfmm_author">Site Author(s):</label>&nbsp;</td>
					<td><input type="text" name="tfmm_author" value="{$tfmm_author}" size="32" maxlength="64" /></td>
				</tr>
				<tr>
					<td style="text-align: right; white-space: nowrap;"><label for="tfmm_email">Site Email:</label>&nbsp;</td>
					<td><input type="text" name="tfmm_email" value="{$tfmm_email}" size="32" maxlength="128" /></td>
				</tr>
				<tr>
					<td style="text-align: right; white-space: nowrap;"><label for="tfmm_copyright">Site Copyright Message:</label>&nbsp;</td>
					<td><input type="text" name="tfmm_copyright" value="{$tfmm_copyright}" size="32" maxlength="64" /></td>
				</tr>
				<tr>
					<td style="text-align: right; white-space: nowrap;"><label for="tfmm_googleanalytics">Google Analytics ID:</label>&nbsp;</td>
					<td><input type="text" name="tfmm_googleanalytics" value="{$tfmm_googleanalytics}" size="16" maxlength="128" /></td>
				</tr>
				<tr>
					<td style="text-align: right; white-space: nowrap;"><label for="tfmm_googlewebmaster">Google Webmaster Verify ID:</label>&nbsp;</td>
					<td><input type="text" name="tfmm_googlewebmaster" value="{$tfmm_googlewebmaster}" size="44" maxlength="64" /></td>
				</tr>
				<tr>
					<td style="text-align: right; white-space: nowrap;"><label for="tfmm_title_format">Title Format:</label>&nbsp;</td>
					<td><input type="text" name="tfmm_title_format" value="{$tfmm_title_format}" size="32" maxlength="255" /></td>
				</tr>
				<tr>
					<td style="text-align: right; white-space: nowrap;">&nbsp;</td>
					<td>%P for page title (varies by page)<br />%S for site title<br/>%T for site subtitle</td>
				</tr>
				<tr>
					<td style="text-align: right; white-space: nowrap;"><label for="tfmm_comments_display">Default Comments+Info Visibility:</label>&nbsp;</td>
					<td>
						<select name="tfmm_comments_display">
							<option {$tfmm_cd_options_hide}value="0">Hide</option>
							<option {$tfmm_cd_options_show}value="1">Show</option>
						</select>
					</td>
				</tr>
				<tr>
					<td style="text-align: right; white-space: nowrap;"><label for="tfmm_width">Maximum Image Width:</label>&nbsp;</td>
					<td><input type="text" name="tfmm_width" value="{$tfmm_width}" size="5" maxlength="5" />&nbsp;500px minimum, image size without borders, actual page size will be wider due to other HTML elements.</td>
				</tr>
				<tr>
					<td style="text-align: right; white-space: nowrap;"><label for="tfmm_expand_img_width">Expand Image Pages to Width of Image:</label>&nbsp;</td>
					<td>
						<select name="tfmm_expand_img_width">
							<option {$tfmm_eiw_options_no}value="0">No</option>
							<option {$tfmm_eiw_options_yes}value="1">Yes</option>
						</select>&nbsp;Overrides Page Width option (above) for image pages making the width of the page equal to the displayed image width
					</td>
				</tr>
				<tr>
					<td style="text-align: right; white-space: nowrap;"><label for="tfmm_maxthumbs">Maximum Thumbnails Per Page:</label>&nbsp;</td>
					<td><input type="text" name="tfmm_maxthumbs" value="{$tfmm_maxthumbs}" size="5" maxlength="5" />&nbsp;Value of 0 means no paging, display all thumbnails on single page</td>
				</tr>
				<tr>
					<td style="text-align: right; white-space: nowrap;"><label for="tfmm_thumbs_animation">Thumnbails Fade-In Animation:</label>&nbsp;</td>
					<td>
						<select name="tfmm_thumbs_animation">
							<option {$tfmm_ta_options_no}value="0">No</option>
							<option {$tfmm_ta_options_yes}value="1">Yes</option>
						</select>
					</td>
				</tr>
				<tr>
					<td style="text-align: right; white-space: nowrap;"><label for="tfmm_thumbs_loader">Thumnbails Loading Icon:</label>&nbsp;</td>
					<td>
						<select name="tfmm_thumbs_loader">
							<option {$tfmm_tl_options_no}value="0">No</option>
							<option {$tfmm_tl_options_yes}value="1">Yes</option>
						</select>
					</td>
				</tr>
				<tr>
					<td style="text-align: right; white-space: nowrap;"><label for="tfmm_thumbs_hover_animation">Thumnbails Hover Animation:</label>&nbsp;</td>
					<td>
						<select name="tfmm_thumbs_hover_animation">
							<option {$tfmm_ha_options_no}value="0">No</option>
							<option {$tfmm_ha_options_yes}value="1">Yes</option>
						</select>
					</td>
				</tr>
				<tr>
					<td style="text-align: right; white-space: nowrap;"><label for="tfmm_css_file">Theme CSS Stylesheet:</label>&nbsp;</td>
					<td>
						<select name="tfmm_css_file">
							{$tfmm_cf_options}
						</select>
					</td>
				</tr>
				<tr>
					<td style="text-align: right; white-space: nowrap;"><label for="tfmm_arrow_position">Navigation Arrow Position:</label>&nbsp;</td>
					<td>
						<select name="tfmm_arrow_position">
							<option {$tfmm_ap_options_outside}value="0">Outside</option>
							<option {$tfmm_ap_options_inside}value="1">Inside</option>
						</select>
					</td>
				</tr>
				<tr>
					<td style="text-align: right; white-space: nowrap;"><label for="tfmm_arrow_prev">Previous Navigation Arrow:</label>&nbsp;</td>
					<td>
						<select name="tfmm_arrow_prev">
							{$tfmm_arrprev_options}
						</select>
					</td>
				</tr>
				<tr>
					<td style="text-align: right; white-space: nowrap;"><label for="tfmm_arrow_next">Next Navigation Arrow:</label>&nbsp;</td>
					<td>
						<select name="tfmm_arrow_next">
							{$tfmm_arrnext_options}
						</select>
					</td>
				</tr>
				<tr>
					<td style="padding: 10px 5px;" colspan="2" style="white-space: nowrap;"><input type="submit" value="update" /></td>
				</tr>
			</tbody>
		</table>
	</fieldset>
</form>

{$tfmm_modrewrite_status}

<h3>About Page Image(s)</h3>
<p>Images for the About page are randomly selected from the 
<span style="font-family: monospace;">theworldin35mm/images/about/</span> 
directory. Put as many images as you want. Each time the about page is loaded, 
it will randomly select an image.</p>

<h3>Themed CSS File</h3>
<p>Custom CSS themes are located in
<span style="font-family: monospace;">theworldin35mm/themes/</span> 
directory. Put any custom CSS files in that directory and
than select the CSS theme from the dropdown menu above.</p>

<h3>Navigation Arrows</h3>
<p>Images for the navigation arrows are located in
<span style="font-family: monospace;">theworldin35mm/images/navarrows/</span> 
directory. Put any custom navigation arrow images in that directory and
than select them from the dropdown menu above.</p>

<h3>Pixelpost Template Tags</h3>
<table border="0" cellpadding="4" cellspacing="1" style="border: 1px solid #ccc; background-color: #fff; font-size: 9px;">
	<tr>
		<td style="white-space: nowrap; background-color: #ccc; line-height: 12px;"><b>Tag</b></td>
		<td style="white-space: nowrap; background-color: #ccc; line-height: 12px;"><b>Description</b></td>
	</tr>
	<tr>
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_ABOUT_IMAGE&gt;</td>
		<td>Selects random image URL from templates/theworldin35mm/images/about/ directory</td>
	</tr>
	<tr style="background-color: #e8f0fe;">
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_ABOUT_IMAGE_HEIGHT&gt;</td>
		<td>Selected about image height</td>
	</tr>
	<tr>
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_ABOUT_IMAGE_WIDTH&gt;</td>
		<td>Selected about image width</td>
	</tr>
	<tr style="background-color: #e8f0fe;">
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_AUTHOR&gt;</td>
		<td>Use your name/photographer(s) name all over your template?</td>
	</tr>
	<tr>
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_EMAIL&gt;</td>
		<td>A site wide email, usually the email of the &lt;35MM_AUTHOR&gt;</td>
	</tr>
	<tr style="background-color: #e8f0fe;">
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_COPYRIGHT&gt;</td>
		<td>A general purpose copyright message, used in throughout <em>The World in 35mm</em> template.</td>
	</tr>
	<tr>
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_COPYRIGHT_FOR_FOOTER&gt;</td>
		<td>Copyright message above with all commas and periods replaced with dots. Used in the footer of <em>The World in 35mm</em> template.</td>
	</tr>
	<tr style="background-color: #e8f0fe;">
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_GOOGLEANALYTICS&gt;</td>
		<td>Google Analytics web property ID</td>
	</tr>
	<tr>
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_GOOGLEWEBMASTER&gt;</td>
		<td>Google Webmaster Verify ID</td>
	</tr>
	<tr style="background-color: #e8f0fe;">
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_META_KEYWORDS&gt;</td>
		<td>Meta tag keywords</td>
	</tr>
	<tr>
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_META_DESCRIPTION&gt;</td>
		<td>Meta tag description</td>
	</tr>
	<tr style="background-color: #e8f0fe;">
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_PREFETCH_LINKS&gt;</td>
		<td>Inserts prefetch link tags (currently only Firefox uses them, other browsers just ignore them)</td>
	</tr>
	<tr>
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_TITLE&gt;</td>
		<td>Title format for template pages</td>
	</tr>
	<tr style="background-color: #e8f0fe;">
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_THUMBNAILS&gt;</td>
		<td></td>
	</tr>
	<tr>
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_THUMBNAILS_COUNT&gt;</td>
		<td></td>
	</tr>
	<tr style="background-color: #e8f0fe;">
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_THUMBNAILS_JS_SRC&gt;</td>
		<td></td>
	</tr>
	<tr>
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_THUMBNAILS_PAGES_FLICKR&gt;</td>
		<td></td>
	</tr>
	<tr style="background-color: #e8f0fe;">
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_THUMBNAILS_WIDTH&gt;</td>
		<td></td>
	</tr>
	<tr>
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_CATEGORY_LINKS_AS_LIST&gt;</td>
		<td></td>
	</tr>
	<tr style="background-color: #e8f0fe;">
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_CATEGORY_LINKS_AS_SELECT&gt;</td>
		<td></td>
	</tr>
	<tr>
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_CURRENT_ARCHIVE&gt;</td>
		<td></td>
	</tr>
	<tr style="background-color: #e8f0fe;">
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_MONTHLY_ARCHIVE_AS_LIST&gt;</td>
		<td></td>
	</tr>
	<tr>
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_MONTHLY_ARCHIVE_AS_SELECT&gt;</td>
		<td></td>
	</tr>
	<tr style="background-color: #e8f0fe;">
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_IMAGE_CATEGORIES&gt;</td>
		<td>List of categories of the currently displayed image. Uses the pixelpost category glue from the Pixelpost ADMIN &gt; Options &gt; Template section</td>
	</tr>
	<tr>
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_IMAGE_CATEGORIES_TITLE&gt;</td>
		<td>Pixelpost language translation for 'Categories:'</td>
	</tr>
	<tr style="background-color: #e8f0fe;">
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_TAG_CLOUD_LIST&gt;</td>
		<td></td>
	</tr>
	<tr>
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_TAG_LINKS_AS_LIST&gt;</td>
		<td></td>
	</tr>
	<tr style="background-color: #e8f0fe;">
		<td style="white-space: nowrap; font-family: monospace;">&lt;35MM_IMAGE_TAG_CLOUD_LIST&gt;</td>
		<td></td>
	</tr>
</table>
EOD;
}

//
// FRONTEND SIDE
//

if (!isset($admin_panel) || $admin_panel != 1) {
	$tfmm_meta_keywords = array();
	$tfmm_meta_description = array();
	$tfmm_prefetches = array();
	$tfmm_title = '';
	$tfmm_title_p = '';
	$tfmm_title_s = '';
	$tfmm_title_t = '';
	$tfmm_image_categories = '';
	$tfmm_image_categories_title = '';
	$tfmm_image_comments = null;
	$tfmm_adj_img_height = 0;
	$tfmm_adj_img_width = 0;
	$tfmm_image_prev_coords = "9998,9998,9999,9999";
	$tfmm_image_next_coords = "9998,9998,9999,9999";
	$tfmm_img_tags_output = null;
	$tfmm_tags_output = null;
	$tfmm_tags_list_output = null;
	$tfmm_facebook_link = null;
	$tfmm_cookiepath = null;
	
	$is_firefox = preg_match('/Firefox/i', $_SERVER['HTTP_USER_AGENT']);
	
	$image_date_month_full = null;
	
	if (!empty($_GET['z']) && $_GET['z'] == 'sitemap') {
		tfmm_sitemap();
	}
	else if (!empty($_GET['z']) && $_GET['z'] == 'mediarss') {
		tfmm_mediarss();
	}
	
	$sitetitle = trim($cfgrow['sitetitle']);
	$subtitle = trim($cfgrow['subtitle']);
	
	if ($language_abr == $default_language_abr) {
		$name_selection = 'name';
		$headline_selection = 'headline';
		$tag_selection = 'tag';
	}
	else {
		$name_selection = 'alt_name';
		$headline_selection = 'alt_headline';
		$tag_selection = 'alt_tag';
	}
	
	if (!empty($_GET['x'])) {
		$tfmm_title_p = ucfirst("{$_GET['x']}");
		$tfmm_meta_keywords[] = $tfmm_title_p;
	}
	
	if (!empty($_GET['x']) && $_GET['x'] == 'browse') {
		$tfmm_meta_keywords[] = 'archive';
		
		if (!empty($_GET['category']) && is_numeric($_GET['category'])) {
			// SQL for category name
			$catID = preg_replace("/^[^0-9]$/", '', $_GET['category']);
			$sql_cat = sql_array("SELECT `{$name_selection}` AS `name` FROM `{$pixelpost_db_prefix}categories` WHERE `id` = {$catID}");
			$tfmm_title_p .= " - " . trim(stripslashes($sql_cat['name']));
			$tfmm_meta_keywords[] = trim(stripslashes($sql_cat['name']));
		}
		else if (!empty($_GET['tag'])) {
			$tfmm_title_p .= " - " . trim($_GET['tag']);
			$tfmm_meta_keywords[] = trim($_GET['tag']);
		}
		else if (!empty($_GET['archivedate'])) {
			$tmp = mktime(0, 0, 0, substr($_GET['archivedate'], 5, 2), 1, substr($_GET['archivedate'], 0, 4));
			$tfmm_meta_keywords[] = date('F,Y', $tmp);
		}
		
		if (!empty($_GET['pagenum']) && is_numeric($_GET['pagenum'])) {
			$tfmm_title_p .= " - Page {$_GET['pagenum']}";
		}
	}
	else if (!empty($image_title)) {
		$tfmm_title_p = trim($image_title);
		$tfmm_meta_keywords[] = $image_title;
	}
	
	if (!empty($image_datetime)) {
		$tmp = strtotime($image_datetime);
		$tfmm_meta_keywords[] = date('F,jS,Y', $tmp);
		$image_date_month_full = date('F', $tmp);
	}
	
	if (!empty($sitetitle)) {
		$tfmm_title_s = "{$sitetitle}";
	}
	
	if (!empty($subtitle)) {
		$tfmm_title_t = "{$subtitle}";
	}
	
	if ($is_firefox && !empty($image_previous_name)) {
		$tfmm_prefetches[] = "<link rel=\"prefetch\" href=\"{$cfgrow['siteurl']}images/{$image_previous_name}\" />";
	}
	
	if ($is_firefox && !empty($image_next_name)) {
		$tfmm_prefetches[] = "<link rel=\"prefetch\" href=\"{$cfgrow['siteurl']}images/{$image_next_name}\" />";
	}
	
	// 35MM META KEYWORDS
	if (!empty($sitetitle)) {
		$tfmm_meta_keywords[] = $sitetitle;
		$tfmm_meta_description[] = $sitetitle;
	}
	if (!empty($subtitle)) {
		$tfmm_meta_keywords[] = $subtitle;
		$tfmm_meta_description[] = $subtitle;
	}
	
	$tfmm_meta_keywords[] = 'blog';
	$tfmm_meta_keywords[] = 'photography';
	$tfmm_meta_keywords[] = 'pixelpost';
	$tfmm_meta_keywords[] = "theworldin35mm v{$addon_version}";
	
	if (!empty($image_id) && is_numeric($image_id)) {
		// get comments
		$tfmm_imgcmt_sql = mysql_query(
			"SELECT `datetime`, `message`, `name`, `url`, `email`
				FROM `{$pixelpost_db_prefix}comments`
				WHERE `parent_id` = {$image_id}
				  AND `publish` = 'yes'
				ORDER BY `datetime` ASC");
		
		while (list($cmt_datetime, $cmt_msg, $cmt_name, $cmt_url, $cmt_email) = mysql_fetch_row($tfmm_imgcmt_sql)) {
			//$cmt_datetime = date($cfgrow['dateformat'], strtotime($cmt_datetime));
			$cmt_datetime = date('jS F Y @ g:ia', strtotime($cmt_datetime));
			$cmt_msg = pullout($cmt_msg);
			$cmt_name = pullout($cmt_name);
			
			if (!empty($cmt_url)) {
				if (preg_match('/^(http|https):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}((:[0-9]{1,5})?\/.*)?$/i', $cmt_url)) {
					$cmt_name = "<a href=\"{$cmt_url}\" title=\"{$lang_visit_homepage}\" onclick=\"window.open(this.href); return false;\" rel=\"nofollow\">{$cmt_name}</a>";
				}
				else {
					unset($cmt_url);
					$cmt_name = "{$cmt_name}";
				}
			}
			
			$cmt_admin_class = '';
			
			if ($cmt_email == $cfgrow['email'] || $cmt_email == $tfmm_email) {
				$cmt_admin_class = ' owner';
			}
			
			$tfmm_image_comments .= sprintf(
				"<div class=\"bubble%s\">
						<blockquote>
							<p>%s</p>
						</blockquote>
						<div class=\"tip\"></div>
					<p><strong>%s</strong> on %s</p>
				</div>\n",
				$cmt_admin_class,
				$cmt_msg,
				$cmt_name,
				$cmt_datetime);
		}
		mysql_free_result($tfmm_imgcmt_sql);
		
		if (empty($tfmm_image_comments)) {
			$tfmm_image_comments = "<div>{$lang_no_comments_yet}</div>";
		}
		
		//
		// Image Tags
		//
		
		$query =
			"SELECT t.`{$tag_selection}`
				FROM `{$pixelpost_db_prefix}tags` AS t
				WHERE t.`img_id` = {$image_id}
				  AND t.`{$tag_selection}` IS NOT NULL
				  AND LTRIM(RTRIM(t.`{$tag_selection}`)) != ''
				ORDER BY t.`{$tag_selection}`";
		
		$result = mysql_query($query);
		if (mysql_num_rows($result) > 0) {
			while (list($tag) = mysql_fetch_row($result)) {
				$tfmm_img_tags_output .= "<a href=\"" . tfmm_browse_url(1, 'tag', $tag) . "\">{$tag}</a> ";
				$tfmm_meta_keywords[] = str_replace('_', ' ', $tag);
			}
		}
		mysql_free_result($result);
		
		if (empty($tfmm_img_tags_output)) {
			$tfmm_img_tags_output = $lang_none;
		}
		$tfmm_img_tags_output = trim($tfmm_img_tags_output);
		
		// facebook link
		$tfmm_facebook_link = "<link rel=\"image_src\" type=\"image/jpeg\" href=\"{$cfgrow['siteurl']}thumbnails/thumb_{$image_name}\" />";
		
		// cookie path
		$tfmm_cookiepath = parse_url($cfgrow['siteurl']);
		$tfmm_cookiepath = $tfmm_cookiepath['path'];
		
		//
		// image categories
		//
		
		$query =
			"SELECT t1.`cat_id`, t2.`{$name_selection}`
				FROM `{$pixelpost_db_prefix}catassoc` as t1
					INNER JOIN `{$pixelpost_db_prefix}categories` t2 on t1.`cat_id` = t2.`id`
				WHERE t1.`image_id` = {$image_id}
				ORDER BY t2.`{$name_selection}`";
		
		$result = mysql_query($query);
		
		switch (mysql_num_rows($result)) {
			case 0: {
				$tfmm_image_categories_title = "{$lang_category_plural} ";
				break;
			}
			case 1: {
				$tfmm_image_categories_title = "{$lang_category_singular} ";
				break;
			}
			default: {
				$tfmm_image_categories_title = "{$lang_category_plural} ";
				break;
			}
		}
		
		if (mysql_num_rows($result) > 0) {
			while (list($cat_id,$name) = mysql_fetch_row($result)) {
				$name = pullout($name);
				$tfmm_image_categories .= "<a href=\"" . tfmm_browse_url(1, 'category', $cat_id) . "\" title=\"\">{$cfgrow['catgluestart']}{$name}{$cfgrow['catglueend']}</a>&nbsp;";
			}
		}
		else {
			$tfmm_image_categories = "${lang_none}";
		}
	}
	
	if (isset($_GET['x']) && $_GET['x'] == 'about') {
		$tfmm_about_image = null;
		$tfmm_about_img_width = $tfmm_width;
		$tfmm_about_img_height = 300;
		
		$about_images = array();
		
		if (is_dir(ABOUTIMGS_DIR)) {
			if ($dh = opendir(ABOUTIMGS_DIR)) {
				while (($file = readdir($dh)) !== false) {
					if (substr($file, strrpos($file, ".")) == ".jpg") {
						$about_images[] = $file;
					}
				}
				closedir($dh);
			}
		}
		
		if (($cnt = count($about_images)) > 0) {
			$tfmm_about_image = $about_images[(rand() % $cnt)];
			list($tfmm_about_img_width, $tfmm_about_img_height) = getimagesize(ABOUTIMGS_DIR . '/' . $tfmm_about_image);
			
			// scale about image if too big
			if ($tfmm_about_img_width > $tfmm_width) {
				$tfmm_about_img_height = round($tfmm_width * $tfmm_about_img_height / $tfmm_about_img_width);
				$tfmm_about_img_width = $tfmm_width;
			}
		}
		
		$tpl = str_replace(
			array(
				'<35MM_ABOUT_IMAGE>',
				'<35MM_ABOUT_IMAGE_HEIGHT>',
				'<35MM_ABOUT_IMAGE_WIDTH>',
			),
			array(
				"{$cfgrow['siteurl']}templates/theworldin35mm/images/about/{$tfmm_about_image}",
				$tfmm_about_img_height,
				$tfmm_about_img_width,
			),
			$tpl);
		
		// facebook link
		$tfmm_facebook_link = "<link rel=\"image_src\" type=\"image/jpeg\" href=\"{$cfgrow['siteurl']}templates/theworldin35mm/images/about/{$tfmm_about_image}\" />";
	}
	
	//
	// Paged Archive Stuff
	//
	
	// normalize params, pagenum, archive date, category, tags
	
	if ($tfmm_maxthumbs > 0 && !empty($_GET['pagenum']) && preg_match("/^[1-9][0-9]*$/", $_GET['pagenum'])) {
		$param_pagenum = intval($_GET['pagenum']);
	}
	else {
		unset($param_pagenum);
	}
	
	if (isset($_GET['archivedate']) && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])$/", $_GET['archivedate'])) {
		$param_archivedate = $_GET['archivedate'];
	}
	else {
		unset($param_archivedate);
	}
	
	if (isset($_GET['category']) && preg_match("/^[0-9]+$/", $_GET['category'])) {
		$param_category = intval($_GET['category']);
	}
	else {
		unset($param_category);
	}
	
	if (!empty($_GET['tag'])) {
		$_GET['tag'] = urldecode($_GET['tag']);
		//if (preg_match("/([a-zA-Z 0-9_-\pL]+)/u", $_GET['tag']) || preg_match("/([a-zA-Z 0-9_-]+)/", $_GET['tag'])) {
		if (preg_match("/([a-zA-Z 0-9_-]+)/", $_GET['tag'])) {
			$param_tag = $_GET['tag'];
		}
	}
	else {
		unset($param_tag);
	}
	
	// number of all photos
	list($tfmm_all_thumbcount) = tfmm_sql_array(
		"SELECT COUNT(*)
			FROM `{$pixelpost_db_prefix}pixelpost`
			WHERE `datetime` <= '{$cdate}'", MYSQL_NUM);
	
	//
	// <35MM_CATEGORY_LINKS_AS_LIST>
	// <35MM_CATEGORY_LINKS_AS_SELECT>
	//
	
	$tfmm_categories_select =
		"<select id=\"categoryselect\" name=\"browse\" onchange=\"self.location.href=this.options[this.selectedIndex].value;\"><option value=\"\">{$lang_browse_select_category}</option><option value=\"" . tfmm_browse_url(1) . "\">{$lang_browse_all} ({$tfmm_all_thumbcount})</option>";
	$tfmm_categories_list =
		"<ul id=\"categorylist\"><li><a href=\"" . tfmm_browse_url(1) . "\" title=\"{$lang_browse_all}\">{$lang_browse_all} ({$tfmm_all_thumbcount})</a></li>";
	
	$result = mysql_query(
		"SELECT c.`id`, c.`{$name_selection}`, COUNT(p.`id`) AS `catcount`
			FROM `{$pixelpost_db_prefix}categories` AS c
				INNER JOIN `{$pixelpost_db_prefix}catassoc` AS ca on ca.`cat_id` = c.`id`
				INNER JOIN `{$pixelpost_db_prefix}pixelpost` AS p on p.`id` = ca.`image_id`
			WHERE p.`datetime` <= '{$cdate}'
			GROUP BY c.`id` HAVING `catcount` > 0
			ORDER BY c.`{$name_selection}`");
	
	while (list($id,$name,$count) = mysql_fetch_row($result)) {
		$name = htmlentities(pullout($name), ENT_QUOTES, "UTF-8");
		$tfmm_categories_select .= "<option value=\"" . tfmm_browse_url(1, 'category', $id) . "\">{$name} ({$count})</option>";
		$tfmm_categories_list .= "<li><a href=\"". tfmm_browse_url(1, 'category', $id) . "\" title=\"{$name}\">{$name} ({$count})</a></li>";
	}
	mysql_free_result($result);
	
	$tfmm_categories_select .= "</select>";
	$tfmm_categories_list .= "</ul>";
	
	//
	// <35MM_MONTHLY_ARCHIVE_AS_LIST>
	// <35MM_MONTHLY_ARCHIVE_AS_SELECT>
	//
	
	$thismonth = gmdate("Y-m", strtotime($cdate));
	
	$tfmm_monthly_archive_select = "<select id=\"monthselect\" name=\"browse\" onchange=\"self.location.href=this.options[this.selectedIndex].value;\"><option value=\"\">Monthly Archive</option><option value=\"" . tfmm_browse_url(1) . "\">{$lang_browse_all} ({$tfmm_all_thumbcount})</option>";
	$tfmm_monthly_archive_list = "<ul id=\"monthlist\"><li><a href=\"" . tfmm_browse_url(1) . "\">{$lang_browse_all} ({$tfmm_all_thumbcount})</a></li>";
	
	$result = mysql_query(
		"SELECT DISTINCT DATE_FORMAT(p.`datetime`, '%Y-%m') AS `pdate`, COUNT(p.`id`) AS `pcount`
			FROM `{$pixelpost_db_prefix}pixelpost` AS p
			WHERE DATE_FORMAT(p.`datetime`, '%Y-%m') <= '{$thismonth}'
			  and p.`datetime` <= '{$cdate}'
			GROUP BY `pdate` HAVING `pcount` > 0
			ORDER BY 1 DESC");
	
	while (list($thedate, $count) = mysql_fetch_row($result)) {
		$select_display_date = ucfirst(${"lang_" . strtolower(date("F", strtotime($thedate . "-01")))}) . date(", Y", strtotime($thedate . "-01"));
		$tfmm_monthly_archive_select .= "<option value=\"" . tfmm_browse_url(1, 'archivedate', $thedate) . "\">{$select_display_date} ({$count})</option>";
		$tfmm_monthly_archive_list .= "<li><a href=\"" . tfmm_browse_url(1, 'archivedate', $thedate) . "\" title=\"{$select_display_date}\">{$select_display_date} ({$count})</a></li>";
	}
	mysql_free_result($result);
	
	$tfmm_monthly_archive_select .= "</select>";
	$tfmm_monthly_archive_list .= "</ul>";
	
	//
	// <35MM_CURRENT_ARCHIVE>
	//
	
	if (isset($param_archivedate)) {
		$tfmm_current_archive = strtotime($param_archivedate . '-01');
		$tfmm_current_archive = date("F, Y", $tfmm_current_archive);
	}
	else if (isset($param_category)) {
		list($tfmm_current_archive) = tfmm_sql_array(
			"SELECT `{$name_selection}`
				FROM `{$pixelpost_db_prefix}categories`
				WHERE `id` = {$param_category}", MYSQL_NUM);
		$tfmm_current_archive = htmlentities(pullout($tfmm_current_archive), ENT_QUOTES, "UTF-8");
	}
	else if (isset($param_tag)) {
		$tfmm_current_archive = htmlentities(mysql_real_escape_string($param_tag), ENT_QUOTES, "UTF-8");
	}
	else {
		$tfmm_current_archive = $lang_browse_all;
	}
	
	//
	// <35MM_THUMBNAILS>
	// <35MM_THUMBNAILS_PAGES_FLICKR>
	//
	
	if (isset($_GET['x']) && $_GET['x'] == 'browse') {
		// initialize variables
		$archive_pages_flickr = '';
		$join_stmt = '';
		$where = '';
		$order_by = "ORDER BY p.`{$cfgrow['display_sort_by']}` " . (($cfgrow['display_order'] == 'default') ? 'DESC' : 'ASC');
		$limit = '';
		
		$thumb_query =
			"SELECT %s
				FROM `{$pixelpost_db_prefix}pixelpost` p %s
				WHERE (p.`datetime` <= '{$cdate}') %s %s %s";
		
		if (isset($param_archivedate)) {
			$archivedate_start = $param_archivedate . "-01 00:00:00";
			$archivedate_end = $param_archivedate . "-31 23:59:59";
			
			$where = sprintf("AND (p.`datetime` <= '{$cdate}' AND p.`datetime` >= '%s' AND p.`datetime` <= '%s')",
				mysql_real_escape_string($archivedate_start),
				mysql_real_escape_string($archivedate_end));
		}
		else if (isset($param_category)) {
			$join_stmt = "INNER JOIN {$pixelpost_db_prefix}catassoc AS ca ON ca.`image_id` = p.`id`";
			$where = "AND (ca.`cat_id` = {$param_category})";
		}
		else if (isset($param_tag)) {
			$join_stmt = "INNER JOIN {$pixelpost_db_prefix}tags AS t ON t.`img_id` = p.`id`";
			$where = sprintf("AND (t.`{$tag_selection}` = '%s')", mysql_real_escape_string($param_tag));
		}
		
		// if paging set SQL LIMIT
		if (isset($param_pagenum) && $param_pagenum > 0) {
			$start = $tfmm_maxthumbs * ($param_pagenum - 1);
			$limit = "LIMIT {$start}, {$tfmm_maxthumbs}";
		}
		
		$result = mysql_query(
			sprintf($thumb_query,
				"p.`id`, p.`{$headline_selection}`, p.`image`",
				$join_stmt, $where, $order_by, $limit));
		
		$thumb_count = 0;
		$thumb_output = '';
		
		// for each record ...
		while (list($id, $title, $name) = mysql_fetch_row($result)) {
			// from the thumbnail row. This could be build by tables too.
			$title = htmlentities(pullout($title), ENT_QUOTES, "UTF-8");
			
			if ($tfmm_thumbs_animation == 1) {
				if ($tfmm_thumbs_loader == 0) {
					$thumb_border_style = 'background-image: none; ';
				}
				else {
					$thumb_border_style = '';
				}
				$thumb_style = "style=\"visibility: hidden;\" onload=\"thumbLoaded('thumbnail-{$thumb_count}','thumbnail-border-{$thumb_count}');\" ";
			}
			else {
				$thumb_border_style = 'background-image: none; ';
				$thumb_style = '';
			}
			
			if ($tfmm_thumbs_hover_animation == 1) {
				$thumb_animation = "onmouseover=\"fadeThumb('thumbnail-{$thumb_count}', 0.75);\" onmouseout=\"fadeThumb('thumbnail-{$thumb_count}', 1.0);\" ";
			}
			else {
				$thumb_animation = "";
			}
			
			$thumb_output .= "
<div id=\"thumbnail-border-{$thumb_count}\" class=\"border-frame thumbnail-border-frame\" style=\"{$thumb_border_style}float: left;\">
	<a href=\"" . tfmm_image_url($id) . "\" title=\"{$title}\">
		<img id=\"thumbnail-{$thumb_count}\" src=\"{$cfgrow['siteurl']}" . ltrim($cfgrow['thumbnailpath'], "./") . "thumb_{$name}\" alt=\"{$title}\" title=\"{$title}\" width=\"{$cfgrow['thumbwidth']}\" height=\"{$cfgrow['thumbheight']}\" class=\"thumbnails border-matte\" {$thumb_style}{$thumb_animation}/>
	</a>
</div>";
			
			$thumb_count++;
		}
		mysql_free_result($result);
		
		list($tfmm_thumbcount) = tfmm_sql_array(sprintf($thumb_query, "COUNT(*)", $join_stmt, $where, '', ''));
		
		if ($tfmm_maxthumbs > 0 && isset($param_pagenum) && $param_pagenum > 0) {
			$num_browse_pages = ceil($tfmm_thumbcount / $tfmm_maxthumbs);
			$num_browse_links = min($num_browse_pages, 9);
			
			if ($num_browse_links > 1) {
				if (isset($param_archivedate)) {
					$archive_pages_ahref = "<a %s href=\"" . tfmm_browse_url("%d", 'archivedate', $param_archivedate) . "\" title=\"%s\">%s</a>";
				}
				else if (isset($param_category)) {
					$archive_pages_ahref = "<a %s href=\"" . tfmm_browse_url("%d", 'category', $param_category) . "\" title=\"%s\">%s</a>";
				}
				else if (isset($param_tag)) {
					$archive_pages_ahref = "<a %s href=\"" . tfmm_browse_url("%d", 'tag', $param_tag) . "\" title=\"%s\">%s</a>";
				}
				else {
					$archive_pages_ahref = "<a %s href=\"" . tfmm_browse_url("%d") . "\" title=\"%s\">%s</a>";
				}
				
				$archive_pages_flickr_links = "";
				
				// first, last links
				$archive_pages_flickr_links_first = sprintf($archive_pages_ahref, "class=\"page\"", 1, "Go To First Page", "&larr;&nbsp;{$lang_first}&nbsp;");
				$archive_pages_flickr_links_last = sprintf($archive_pages_ahref, "class=\"page\"", $num_browse_pages, "Go to Last Page", "&nbsp;{$lang_latest}&nbsp;&rarr;");
				
				// prev, next links
				$temp = ($param_pagenum > 1) ? $param_pagenum - 1 : 1;
				$archive_pages_flickr_links_prev = sprintf($archive_pages_ahref, "class=\"page\"", $temp, "Go To Previous Page", "&lsaquo;&nbsp;{$lang_previous}&nbsp;");
				
				$temp = ($param_pagenum >= $num_browse_pages) ? $num_browse_pages : $param_pagenum + 1;
				$archive_pages_flickr_links_next = sprintf($archive_pages_ahref, "class=\"page\"", $temp, "Go To Next Page", "&nbsp;{$lang_next}&nbsp;&rsaquo;");
				
				// handle pages
				if ($param_pagenum <= ceil($num_browse_links / 2)) {
					$pi = 1;
					$pn = $num_browse_links;
				}
				else if ($param_pagenum > ($num_browse_pages - floor($num_browse_links / 2))) {
					$pi = $num_browse_pages - $num_browse_links + 1;
					$pn = $num_browse_pages;
				}
				else {
					$pi = $param_pagenum - floor($num_browse_links / 2);
					$pn = $pi + $num_browse_links - 1;
				}
				
				while ($pi <= $pn) {
					if ($pi == $param_pagenum) {
						$archive_pages_flickr_links .= "<span class=\"this-page page\">{$pi}</span>";
					}
					else {
						$archive_pages_flickr_links .= sprintf($archive_pages_ahref, "class=\"page\"", $pi, "Go to Page {$pi}", "{$pi}");
					}
					$pi++;
				}
				
				$archive_pages_flickr = <<<EOT
<div class="paginator">{$archive_pages_flickr_links_first}{$archive_pages_flickr_links_prev}{$archive_pages_flickr_links}{$archive_pages_flickr_links_next}{$archive_pages_flickr_links_last}</div>

EOT;
			}
		}
		
		$archive_pages_flickr .= "<div class=\"results\">({$tfmm_thumbcount} items)</div>";
		
		$tpl = str_replace(
			array(
				'<35MM_THUMBNAILS>',
				'<35MM_THUMBNAILS_PAGES_FLICKR>',
				'<35MM_THUMBNAILS_WIDTH>',
			),
			array(
				$thumb_output,
				$archive_pages_flickr,
				$tfmm_width + TFMM_IMGBORDERSIZE - 210, // margin-left is 206px, padding-right 4px
			),
			$tpl);
	}
	
	//
	// Image Sizes and Navigation Arrow Sizes/Positions
	//
	
	if (!empty($image_id) && is_numeric($image_id)) {
		// scale image viewport if image is too large to fit inside tfmm_width
		// and we are not expanding tfmm_width to fit (match) image width
		if ($tfmm_expand_img_width == 0 && $image_width > $tfmm_width) {
			$tfmm_adj_img_height = round($tfmm_width * $image_height / $image_width);
			$tfmm_adj_img_width = $tfmm_width;
		}
		else {
			$tfmm_adj_img_height = $image_height;
			$tfmm_adj_img_width = $image_width;
		}
		
		// adjust tfmm width if we are scaling to image size
		// and set royal width with final calculated width
		if ($tfmm_expand_img_width == 1) {
			$tfmm_width = $image_width;
		}
		$tfmm_royalwidth = $tfmm_width;
		
		// get nav arrow dimensions
		$leftarrow_size = getimagesize(NAVARROWS_DIR . '/' . $tfmm_arrow_prev);
		$rightarrow_size = getimagesize(NAVARROWS_DIR . '/' . $tfmm_arrow_next);
		
		if ($tfmm_arrow_position == 0) {
			// outside placement
			$tfmm_arrow_leftpos = -$leftarrow_size[0];
			$tfmm_arrow_rightpos = -$rightarrow_size[0];
			
			// update royal width to include space for nav arrows
			$tfmm_royalwidth += $leftarrow_size[0] + $rightarrow_size[0];
		}
		else {
			// inside placement, just inside image border + padding (1px + 9px)
			$tfmm_arrow_leftpos = 10;
			$tfmm_arrow_rightpos = 10;
			
			// update pos if we are not matching image width to keep the arrows
			// flush with inside of the frame padding + border
			if ($tfmm_expand_img_width == 0 && $image_width < $tfmm_width) {
				$tfmm_arrow_leftpos += ($tfmm_width - $image_width) / 2;
				$tfmm_arrow_rightpos += ($tfmm_width - $image_width) / 2;
			}
		}
		
		// (image + borders + padding) middle - arrow middle
		$tfmm_arrow_topleftpos = intval((($tfmm_adj_img_height + TFMM_IMGBORDERSIZE) / 2) - ($leftarrow_size[1] / 2));
		
		// (image + borders + padding) middle - arrow middle
		$tfmm_arrow_toprightpos = intval((($tfmm_adj_img_height + TFMM_IMGBORDERSIZE) / 2) - ($rightarrow_size[1] / 2));
		
		//
		// coordinates for image map
		//
		
		if ($image_previous_id != $image_id) {
			$tfmm_image_prev_coords = sprintf("%d,%d,%d,%d", 0, 0, $tfmm_adj_img_width / 2, $tfmm_adj_img_height);
		}
		
		if ($image_next_id != $image_id) {
			$tfmm_image_next_coords = sprintf("%d,%d,%d,%d", $tfmm_adj_img_width / 2, 0, $tfmm_adj_img_width, $tfmm_adj_img_height);
		}
	}
	else {
		$tfmm_adj_img_height = 0;
		$tfmm_adj_img_width = 0;
		$tfmm_arrow_leftpos = 0;
		$tfmm_arrow_rightpos = 0;
		$tfmm_arrow_topleftpos = 0;
		$tfmm_arrow_toprightpos = 0;
		$tfmm_royalwidth = $tfmm_width;
	}
	
	// update widths to include border size
	$tfmm_width += TFMM_IMGBORDERSIZE;
	$tfmm_royalwidth += TFMM_IMGBORDERSIZE;
	
	// setup title
	$tfmm_title = str_replace('%P', $tfmm_title_p, $tfmm_title_format);
	$tfmm_title = str_replace('%S', $tfmm_title_s, $tfmm_title);
	$tfmm_title = str_replace('%T', $tfmm_title_t, $tfmm_title);
	
	//
	// Paged Archive Mods (TAG CLOUD)
	//
	
	$query =
		"SELECT COUNT(*) AS `max`
			FROM `{$pixelpost_db_prefix}pixelpost` AS p
				INNER JOIN `{$pixelpost_db_prefix}tags` AS t ON t.`img_id` = p.`id`
			WHERE t.`{$tag_selection}` IS NOT NULL
			  AND LTRIM(RTRIM(t.`{$tag_selection}`)) != ''
			  AND p.`datetime` <= '{$cdate}'";
	
	list($tag_max) = tfmm_sql_array($query, MYSQL_NUM);
	
	if (!empty($tag_max) && is_numeric($tag_max) && $tag_max > 0) {
		$query =
			"SELECT ROUND(COUNT(*)/{$tag_max}, 1) AS `rank`, t.`{$tag_selection}`, COUNT(*) as `cnt`
				FROM `{$pixelpost_db_prefix}pixelpost` AS p
					INNER JOIN `{$pixelpost_db_prefix}tags` AS t ON t.`img_id` = p.`id`
				WHERE t.`{$tag_selection}` IS NOT NULL
				  AND LTRIM(RTRIM(t.`{$tag_selection}`)) != ''
				  AND p.`datetime` <= '{$cdate}'
				GROUP BY t.`{$tag_selection}`
				ORDER BY t.`{$tag_selection}`";
		
		$tfmm_tags_list_output = "<ul id=\"taglist\"><li><a href=\"" . tfmm_browse_url(1) . "\">{$lang_browse_all} ({$tfmm_all_thumbcount})</a></li>";
		$tfmm_tags_output = '<div id="tag_cloud">';
		$result = mysql_query($query) or die("<pre>{$query}</pre>");
		while (list($rank,$tag,$cnt) = mysql_fetch_row($result)) {
			$_tmp_tags_link = "<a href=\"" . tfmm_browse_url(1, 'tag', $tag) . "\" class=\"tags{$rank[0]}{$rank[2]}\">{$tag}</a> ";
			$tfmm_tags_output .= $_tmp_tags_link;
			$tfmm_tags_list_output .= "<li>{$_tmp_tags_link}</li>";
		}
		mysql_free_result($result);
		$tfmm_tags_output = trim($tfmm_tags_output);
		$tfmm_tags_output .= '</div>';
		$tfmm_tags_list_output .= '</ul>';
	}
	
	$tpl = str_replace(
		array(
			'<35MM_ADJUSTED_IMAGE_BORDER_HEIGHT>',
			'<35MM_ADJUSTED_IMAGE_BORDER_WIDTH>',
			'<35MM_ADJUSTED_IMAGE_HEIGHT>',
			'<35MM_ADJUSTED_IMAGE_WIDTH>',
			'<35MM_AUTHOR>',
			'<35MM_BROWSE_URL>',
			'<35MM_COOKIEPATH>',
			'<35MM_CSS_URL>',
			'<35MM_EMAIL>',
			'<35MM_CATEGORY_LINKS_AS_LIST>',
			'<35MM_CATEGORY_LINKS_AS_SELECT>',
			'<35MM_COMMENTS_DISPLAY>',
			'<35MM_COPYRIGHT>',
			'<35MM_COPYRIGHT_FOR_FOOTER>',
			'<35MM_CURRENT_ARCHIVE>',
			'<35MM_FACEBOOK_LINK>',
			'<35MM_GOOGLEANALYTICS>',
			'<35MM_GOOGLEWEBMASTER>',
			'<35MM_IMAGE_CATEGORIES>',
			'<35MM_IMAGE_CATEGORIES_TITLE>',
			'<35MM_IMAGE_COMMENTS>',
			'<35MM_IMAGE_NAV_LEFTPOS>',
			'<35MM_IMAGE_NAV_NEXTIMG>',
			'<35MM_IMAGE_NAV_PREVIMG>',
			'<35MM_IMAGE_NAV_RIGHTPOS>',
			'<35MM_IMAGE_NAV_TOPLEFTPOS>',
			'<35MM_IMAGE_NAV_TOPRIGHTPOS>',
			'<35MM_IMAGE_NEXT_COORDS>',
			'<35MM_IMAGE_PREV_COORDS>',
			'<35MM_IMAGE_TAG_CLOUD_LIST>',
			'<35MM_META_KEYWORDS>',
			'<35MM_META_DESCRIPTION>',
			'<35MM_MONTHLY_ARCHIVE_AS_LIST>',
			'<35MM_MONTHLY_ARCHIVE_AS_SELECT>',
			'<35MM_PREFETCH_LINKS>',
			'<35MM_ROYALWIDTH>',
			'<35MM_TAG_CLOUD_LIST>',
			'<35MM_TAG_LINKS_AS_LIST>',
			'<35MM_TITLE>',
			'<35MM_URLPREFIX_COMMENTSUBMIT>',
			'<35MM_URLPREFIX_IMAGE>',
			'<35MM_URLPREFIX_PAGE>',
			'<35MM_VERSION>',
			'<35MM_WIDTH>',
			'<IMAGE_DATE_MONTH_FULL>',
		),
		array(
			$tfmm_adj_img_height + 2,
			$tfmm_adj_img_width + 2,
			$tfmm_adj_img_height,
			$tfmm_adj_img_width,
			$tfmm_author,
			tfmm_browse_url(1),
			$tfmm_cookiepath,
			$tfmm_css_url,
			$tfmm_email,
			$tfmm_categories_list,
			$tfmm_categories_select,
			$tfmm_comments_display,
			$tfmm_copyright,
			str_replace(array('.', ','), ' &middot;', $tfmm_copyright),
			$tfmm_current_archive,
			$tfmm_facebook_link,
			tfmm_googleanalytics($tfmm_googleanalytics),
			tfmm_googlewebmaster($tfmm_googlewebmaster),
			$tfmm_image_categories,
			$tfmm_image_categories_title,
			$tfmm_image_comments,
			$tfmm_arrow_leftpos,
			$tfmm_arrow_next,
			$tfmm_arrow_prev,
			$tfmm_arrow_rightpos,
			$tfmm_arrow_topleftpos,
			$tfmm_arrow_toprightpos,
			$tfmm_image_next_coords,
			$tfmm_image_prev_coords,
			$tfmm_img_tags_output,
			implode(',', $tfmm_meta_keywords),
			implode('. ', $tfmm_meta_description),
			$tfmm_monthly_archive_list,
			$tfmm_monthly_archive_select,
			implode("\n", $tfmm_prefetches),
			$tfmm_royalwidth,
			$tfmm_tags_output,
			$tfmm_tags_list_output,
			$tfmm_title,
			($tfmm_mod_rewrite == 0) ? "index.php?x=save_comment" : "comment/submit",
			($tfmm_mod_rewrite == 0) ? "index.php?showimage=" : "p/",
			($tfmm_mod_rewrite == 0) ? "index.php?x=" : "",
			"v{$addon_version}",
			$tfmm_width,
			$image_date_month_full,
		),
		$tpl);
}

// restore previous error reporting level
error_reporting($tfmm_err_level);
?>
