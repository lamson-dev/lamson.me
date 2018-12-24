<?PHP
/*
Copyright (C) 2009 by Armin Grewe <armin@grewe.co.uk>

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

-------------------------------------------------------------------------------

Simple addon to allow anyone to share an image on Facebook, Twitter, Posterous and Delicious. Uses
text only, no unsightly icon to destroy your design. That's what I wanted, so I created this
addon from a combination of other addons.

Just add <TEXTSHARE> in the image_template.html file of your template.

Changelog:
0.1 : inital release.

*/
$addon_name="Share on social networks addon";
$addon_description="To share a blog entry on Delicious, Facebook, Twitter and Posterous. Uses text only and encourages visitors to share your images.<br /><br />Add the &lt;TEXTSHARE&gt; tag where you want to see the links in your image_template.html file of your template.";
$addon_version="0.1";

$URL=urlencode($cfgrow['siteurl']."index.php?showimage=".$image_id);
$TITLE=urlencode($cfgrow['feed_title']." - Photoblog: ".$image_title);

$twitter_link = "<a href='http://twitter.com/home?status=$TITLE $URL' title='Tweet This'>Twitter</a>";

$facebook_link = "<a href='http://www.facebook.com/share.php?u=$URL&amp;t=$TITLE' title='Share on Facebook'>Facebook</a>";

$delicious_link = "<a href='http://del.icio.us/post?url=$URL&amp;title=$TITLE' title='Save to Delicious'>Delicious</a>";

$share_txt = "Share on: ".$twitter_link." - ".$facebook_link." - ".$delicious_link;

$tpl = str_replace("<TEXTSHARE>",$share_txt,$tpl);

?>