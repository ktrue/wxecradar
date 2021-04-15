<?php
############################################################################
# A Project of TNET Services, Inc. and Saratoga-Weather.org (WD-USA template set)
############################################################################
#
#   Project:    Sample Included Website Design
#   Module:     sample.php
#   Purpose:    Sample Page
#   Authors:    Kevin W. Reed <kreed@tnet.com>
#               TNET Services, Inc.
#
# 	Copyright:	(c) 1992-2007 Copyright TNET Services, Inc.
############################################################################
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA
############################################################################
#	This document uses Tab 4 Settings
############################################################################
#
# this script is a port of Jerry Wilkins wxusradars-hanis3.php Version 5a (20190511)
# with additional mods by Ken True for inclusion in the Saratoga Base-Canada template.
# Many thanks to Jerry for allowing the kind use of his code!
#
# Version 1.00 - 14-Apr-2021 - initial release
############################################################################
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
//--self downloader --
   $filenameReal = __FILE__;
   $download_size = filesize($filenameReal);
   header('Pragma: public');
   header('Cache-Control: private');
   header('Cache-Control: no-cache, must-revalidate');
   header("Content-type: text/plain");
   header("Accept-Ranges: bytes");
   header("Content-Length: $download_size");
   header('Connection: close');
   readfile($filenameReal);
   exit;
}
############################################################################
$standAlone				= true;			// false if we run in the template environment
if ( $standAlone ) { ?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<?php
	$SITE = array();
	$_SESSION = array();
	?>
	<head>
	<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1"/>
	<meta name="description" content="EC Radar Loops"/>
  <?php
}
else {
	require_once("Settings.php");
	require_once("common.php");
}
############################################################################
$divWidth = 660;
if ( !$standAlone ) {
	$TITLE = langtransstr($SITE['organ']) . " - " .langtransstr('GOES16 Satellite Loops');
	$showGizmo = true; // Set to false to exclude the gizmo
} else {
  $TITLE = 'Environment Canada Radar';
	echo '<title> '.$TITLE.'</title>';
}
############################################################################
/************************* Settings *****************************/
$radarLoc = 'WKR'; // IMPORTANT!!! Default radar location is set here
$radar    = 'RAIN'; // = 'RAIN' or ='SNOW'
$imageWidth = 600; // Width of radar images
$iframeWidth = 617; // Default IFrame Width -- adjust as needed
$iframeHeight = 600; // Default IFrame Height -- adjust as needed
$autoRefresh = true; // Use Autorefresh? true or false -- Determines whether AutoRefresh even appears
$autoRefreshTime = 8; // Number of minutes between autorefreshes.  IMPORTANT: use 2, 3, 4, 5, 6, 8, 10, 15, 20, or 30 ONLY!!!
$autoRefreshOff = false; // Begin with Autorefresh Off? true or false -- 'OFF' or 'ON"
$bgndColor = 'silver'; // Set HAniS Background Color Here
$btnColor = 'darkslategray'; // Set Button Color here
$btnTextColor = 'white'; // Set Button Text Color here
############################ New in Version 2 ##########################
$pauseSeconds = 2; // Pause on last image, in seconds
$animRate = 20; // Frame Rate of animation: 5 is glacial, 10 is slow, 15 is leisurely, 20 is good, and 50 is fast - set with integer
$numbImages = 10; // Number of Radar Images to Animate - 3 to 10
############################ New in Version 3 ##########################
$smoothingOn = false; // Enable image smoothing - new in HAniS 2.5
//$doLang = 'en';       // set default language: ='en' for English, ='fr' for French
/*********************** End Settings ***************************/
if (isset($SITE['ecradar'])) 	{$radarLoc = $SITE['ecradar'];}

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript"> {
	var $jq = jQuery.noConflict();
	function showRadarjs(radar,radarLoc,imageWidth,iframeWidth,iframeHeight,autoRefresh,autoRefreshTime,bgndColor,autoRefreshOff,btnColor,btnTextColor,pauseSeconds,animRate,numbImages,smoothingOn) { 
		$jq('#selectorsarea').load('wxecradar-inc.php', {radar:radar,radarLoc:radarLoc,imageWidth:imageWidth,iframeWidth:iframeWidth,iframeHeight:iframeHeight,autoRefresh:autoRefresh,autoRefreshTime:autoRefreshTime,bgndColor:bgndColor,autoRefreshOff:autoRefreshOff,btnColor:btnColor,btnTextColor:btnTextColor,pauseSeconds:pauseSeconds,animRate:animRate,numbImages:numbImages,smoothingOn:smoothingOn});
	}
}
</script>
<style type="text/css">
#selectorsarea optgroup {
  color: black;
  background: white;
}
#selectorsarea option {
  background-color:<?php echo $btnColor?>;
  border-radius:7px;
  color:<?php echo $btnTextColor?>;
}
#selectorsarea select {
  background-color:<?php echo $btnColor?>;
  border-radius:7px;
  color:<?php echo $btnTextColor?>;
}
</style>
</head>
<body>
<?php
if ( !$standAlone ) {
	############################################################################
	include("header.php");
	include("menubar.php");
	############################################################################
}
?>

<div id="main-copy">
  <div align="center">
  <!-- h2> align="center">Environment Canada Radar</h2 -->
    <?php include_once('wxecradar-inc.php');?>
  </div><!-- end align=center -->
</div><!-- end main-copy -->

<?php 
if ( !$standAlone ) {
	############################################################################
	include("footer.php");
} else {
	echo '</body></html>';
}
# End of Page
############################################################################
?>
