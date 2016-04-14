<?php


/*
Requires Pixelpost version 1.5 or newer
ADDON-Version 1.0

Written by: Mahbubur Rahman
Copyright 2009 http://blog.mrahmanphoto.com/

Pixelpost www: http://www.pixelpost.org/

License: http://www.gnu.org/copyleft/gpl.html

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.

============================================================================
*/

$default_language_abr = strtolower($PP_supp_lang[$cfgrow['langfile']][0]);

/*
=================================================================================
this creates the fields in the DB to store the fields for this add-on
=================================================================================
*/

$configTable = $pixelpost_db_prefix.'config';

$fieldsql = array(
	"commentTableWidth" => "ALTER TABLE $configTable ADD `commentTableWidth` VARCHAR(6) DEFAULT '100%' NOT NULL",
	"commentNumberOfColumns" => "ALTER TABLE $configTable ADD `commentNumberOfColumns` INTEGER DEFAULT '1' NOT NULL",
	"commentCellPadding" => "ALTER TABLE $configTable ADD `commentCellPadding` INTEGER DEFAULT '10' NOT NULL",
	"commentTableStyle" => "ALTER TABLE $configTable ADD `commentTableStyle` VARCHAR(100) DEFAULT '' NOT NULL",
	"commentTableRowStyle" => "ALTER TABLE $configTable ADD `commentTableRowStyle` VARCHAR(100) DEFAULT '' NOT NULL",
	"commentTableColumnStyle" => "ALTER TABLE $configTable ADD `commentTableColumnStyle` VARCHAR(100) DEFAULT '' NOT NULL",
	"commentLinkStyle" => "ALTER TABLE $configTable ADD `commentLinkStyle` VARCHAR(100) DEFAULT '' NOT NULL",
	"commentShowUnlinkedURL" => "ALTER TABLE $configTable ADD `commentShowUnlinkedURL` INTEGER DEFAULT '0' NOT NULL",
);

$result_id = mysql_list_fields($pixelpost_db_pixelpost, $configTable);

foreach ($fieldsql as $fieldname => $fieldaltersql) {
	$fieldexists = false;
	
	for ($t = 0; $t < mysql_num_fields($result_id); $t++) {
		if (strcasecmp($fieldname, mysql_field_name($result_id, $t)) == 0) {
			$fieldexists = true;
			break;
		}
	}
	
	// if the field does not exist: Create it!
	if ($fieldexists == false) {
		//echo $fieldaltersql;
		$result = mysql_query($fieldaltersql);
	}
}


/*
==================================================================================
Get the config values from the DB
==================================================================================
*/
$configResult = mysql_query("select commentTableWidth, commentNumberOfColumns, commentCellPadding, commentTableStyle, commentTableRowStyle, commentTableColumnStyle, commentLinkStyle, commentShowUnlinkedURL from $configTable");
$configRow = mysql_fetch_row($configResult);


/*
==================================================================================
variables to control the display
==================================================================================
*/
$tableWidth = $configRow[0]; //this the width of the table
$numberOfColumns = $configRow[1]; //this the number of columns that the comments shows up in.
$cellPadding = $configRow[2];
$tableStyle = $configRow[3];
$tableColumnStyle = $configRow[4];
$tableRowStyle = $configRow[5];
$commentLinkStyle = $configRow[6];
$commentShowUnlinkedURL = $configRow[7];

/*
==================================================================================
update the DB with the values from the form
==================================================================================
*/
if (isset($_GET['x']) && $_GET['x'] == 'updatecommentconfig') {
	$commentTableWidth_post = $_POST['tableWidth'];
	$commentNumberOfColumns_post = $_POST['numberOfColumns'];
	$commentCellPadding_post = $_POST['cellpadding'];
	$commentTableStyle_post = $_POST['tableStyle'];
	$commentTableRowStyle_post = $_POST['rowStyle'];
	$commentTableColumnStyle_post = $_POST['columnStyle'];
	$commentLinkStyle_post = $_POST['commentLinkStyle'];
	$commentShowUnlinkedURL_post = $_POST['commentShowUnlinkedURL'];
	
	//check the values
	if(!is_numeric($commentNumberOfColumns_post))
		$commentNumberOfColumns_post = $numberOfColumns;

	if(!is_numeric($commentCellPadding_post))
		$commentCellPadding_post = $cellPadding;
		
		
	if(strpos($commentTableWidth_post, "%") > 0)
	{	
		$commentTableWidth_post = str_replace("%","",$commentTableWidth_post);
		
		if(!is_numeric($commentTableWidth_post))
			$commentTableWidth_post = $tableWidth;
		else
			$commentTableWidth_post .= "%";
	}
	else if(is_numeric($commentTableWidth_post))
	{
		$commentTableWidth_post = $commentTableWidth_post;
	}
	else
	{
		$commentTableWidth_post = $tableWidth;
	}
	
	if($commentShowUnlinkedURL_post == "1")
		$commentShowUnlinkedURL_post = 1;
	else
		$commentShowUnlinkedURL_post = 0;
		
	// update db
	$query = "update $configTable set `commentTableWidth`='$commentTableWidth_post', `commentNumberOfColumns`=$commentNumberOfColumns_post, `commentCellPadding`=$commentCellPadding_post, `commentTableStyle`='$commentTableStyle_post', `commentTableRowStyle`='$commentTableRowStyle_post', `commentTableColumnStyle`='$commentTableColumnStyle_post', `commentLinkStyle`='$commentLinkStyle_post', `commentShowUnlinkedURL`=$commentShowUnlinkedURL_post";
	//echo $query;
	$update = mysql_query($query);
	
	//reset the values
	$tableWidth = $commentTableWidth_post; //this the width of the table
	$numberOfColumns = $commentNumberOfColumns_post; //this the number of columns that the comments shows up in.
	$cellPadding = $commentCellPadding_post;
	$tableStyle = $commentTableStyle_post;
	$tableColumnStyle = $commentTableColumnStyle_post;
	$tableRowStyle = $commentTableRowStyle_post;
	$commentLinkStyle = $commentLinkStyle_post;
	$commentShowUnlinkedURL = $commentShowUnlinkedURL_post;
		
}




/*
===================================================================================
form to control the configuration
===================================================================================
*/

if($commentShowUnlinkedURL == 1)
	$commentText = "checked";
else
	$commentText = "";


$addon_name = "Comment Link Generator";
$addon_version = "1.0";
$addon_description = "Generates a list of all the people who left comments, ordered by the number of comments in descending order.<p>Insert the tag &lt;COMMENT_LINK&gt; where you would like the comment link to appear";
$addon_description .= "<form method='post' action='index.php?view=addons&amp;x=updatecommentconfig'>
<input type='text' name='numberOfColumns' value='$numberOfColumns' style='width: 40px;' />: Number of columns<br />
<input type='text' name='tableWidth' value='$tableWidth' style='width: 40px;' />: Table width - you can put a percent width, like 80%, otherwise, its just numbers (as pixels)<br />
<input type='text' name='cellpadding' value='$cellPadding' style='width: 40px;' />: Table cell Padding<br />
<input type='text' name='tableStyle' value='$tableStyle' style='width: 100px;' />: Table style<br />
<input type='text' name='rowStyle' value='$tableRowStyle' style='width: 100px;' />: Table row style<br />
<input type='text' name='columnStyle' value='$tableColumnStyle' style='width: 100px;' />: Table column style<br />
<input type='text' name='commentLinkStyle' value='$commentLinkStyle' style='width: 100px;' />: Comment link style<br />
<input type='checkbox' value='1' name='commentShowUnlinkedURL' style='width: 100px;' $commentText>: Show unlinked Commenters<br /><input type='submit' value=' Submit '></form>";


//this is the query to get the comments
$query = "select name, url, count(*) as total from pixelpost_comments where publish = 'yes'   ";

if($commentShowUnlinkedURL == 0)
	$query .= " and url <> '' ";

$query .= " group by name, url order by count(*) desc, name";

//echo $query;

$result = mysql_query($query);

//do calculations for the columns and widths
$numRows = mysql_num_rows($result);
$counter = 1;
$numRowsEachColumn = ceil($numRows/$numberOfColumns);

if(strpos($tableWidth, "%") > 0)
{
	$eachColumnWidth = 100/$numberOfColumns; 
	$eachColumnWidth .= "%";
}
else
{
	$eachColumnWidth = ($tableWidth/$numberOfColumns); 
}

if(trim($tableStyle) != "")
	$tableStyle = " class=\"$tableStyle\"";

if(trim($tableColumnStyle) != "")
	$tableColumnStyle = " class=\"$tableColumnStyle\"";

if(trim($tableRowStyle) != "")
	$tableRowStyle = " class=\"$tableRowStyle\"";

if(trim($commentLinkStyle) != "")
	$commentLinkStyle = " class=\"$commentLinkStyle\"";
		
	
	
	
//build the comment string
$commentContent = "<table cellpadding=\"$cellPadding\" cellspacing=\"0\" width=\"$tableWidth\"$tableStyle><tr$tableRowStyle><td valign=\"top\" width=\"$eachColumnWidth\"$tableColumnStyle>";

while($row = mysql_fetch_row($result))
{
	//add additional styles here if you wish
	if($row[1] <> '')
		$commentContent .= "&nbsp;&middot;&nbsp;&nbsp;<a href=\"" . $row[1] . "\" $commentLinkStyle target=\"_blank\">" . $row[0] . "</a> (" . $row[2] . ")<br>";
	else	
		$commentContent .= "&nbsp;&middot;&nbsp;&nbsp;<span $commentLinkStyle>" . $row[0] . "</span> (" . $row[2] . ")<br>";
	
	if($counter == $numRowsEachColumn && $counter != $numRows) 
	{
		$commentContent .= "</td><td valign=\"top\" width=\"" . $eachColumnWidth . "\">";
		$counter = 0;
	}
	$counter++;
}
$commentContent .= "</td></tr></table>";			

//replace the comment tag in the template
$tpl = str_replace("<COMMENT_LINK>",$commentContent,$tpl); 
?>