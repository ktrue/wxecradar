<?php
############################################################################
#
# this script is a port of Jerry Wilkins wxusradars-hanis3.php Version 5a (20190511)
# with many mods by Ken True for inclusion in the Saratoga Base-Canada template.
# Many thanks to Jerry for allowing the kind use of his code!
#
# This script generates the contents of the <iframe> to display Environment Canada
# legacy radar images from https://dd.weather.gc.ca/radar/PRECIPET/GIF/{sitename}/ 
# using HAniS JavaScript display animation
#
# Version 1.00 - 14-Apr-2021 - initial release
# Version 1.01 - 19-Apr-2021 - added diagnostic output re curl fetch results
# Version 1.02 - 08-Jul-2021 - added overlays in ./radar/ (thanks to M. Romer)
# Version 1.03 - 02-Nov-2022 - added NATIONAL map (thanks to M. Romer)
############################################################################
$Version = 'wxecradar-iframe.php V1.03 - 02-Nov-2022';
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
} ?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
  <title>HAniS EC Radar Animation</title>
  <script type="text/javascript" src="./hanis_min.js"> </script>
<?php if (!isset($radar)) { // Load jquery library if needed
echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js" type="text/javascript"></script>';
} 
print "\n<!-- $Version -->\n";
?>
  </head>
<?php 
if (!isset($radar)) { // To test load some defaults if not called by 'wxecradar-inc.php'
  // you DON'T need to customize these.. change wxecradar.php instead.
	$radar = 'RAIN'; // Default radar type is set here
	$radarLoc = 'CASKR'; // IMPORTAMT!!! Default radar location is set here
	$imageWidth = 600; // Width of radar images
	$iframeWidth = 617; // Default IFrame Width -- adjust as needed
	$iframeHeight = 620; // Default IFrame Height -- adjust as needed
	$autoRefresh = true; // Use Autorefresh? true or false -- Determines whether AutoRefresh even appears
	$autoRefreshTime = 8; // Number of minutes between autorefreshes.  IMPORTANT: use 2, 3, 4, 5, 6, 8, 10, 15, 20, or 30 ONLY!!!
	$autoRefreshOff = false; // Begin with Autorefresh Off? true or false -- 'OFF' or 'ON"
	$bgndColor = 'silver'; // Set HAniS Background Color Here
	$btnColor = 'darkslategray'; // Set Button Color here
	$btnTextColor = 'white'; // Set Button Text Color here
	############################ New in Version 2 ##########################
	$pauseSeconds = 2; // Pause on last image, in seconds
	$animRate = 20; // Frame Rate of animation: 5 is glacial, 10 is slow, 15 is leisurely, 20 is good, and 50 is fast - set with integer
	$numbImages = 10; // Number of Radar Images to Animate - 3 to around 10
	############################ New in Version 3 ##########################
	$smoothingOn = true; // Enable image smoothing - new in HAniS 2.5
}
	if (isset($_GET['radar'])) {
    $radar = $_GET['radar'];
  }
  if (isset($_GET['imageDir'])) {
    $imageDir = $_GET['imageDir'];
  }
  if (isset($_GET['radarLoc'])) {
    $radarLoc = $_GET['radarLoc'];
  }
  if (isset($_GET['imageWidth'])) {
    $imageWidth = $_GET['imageWidth'];
  }
  if (isset($_GET['bgndColor'])) {
    $bgndColor = $_GET['bgndColor'];
  }
  if (isset($_GET['btnColor'])) {
    $btnColor = $_GET['btnColor'];
  }
  if (isset($_GET['btnTextColor'])) {
    $btnTextColor = $_GET['btnTextColor'];
  }
  if (isset($_GET['animRate'])) {
    $animRate = $_GET['animRate'];
  }
  if (isset($_GET['pauseSeconds'])) {
    $pauseSeconds = $_GET['pauseSeconds'];
  }
  if (isset($_GET['numbImages'])) {
    $numbImages = $_GET['numbImages'];
  }
  if (isset($_GET['smoothingOn'])) {
    $smoothingOn = $_GET['smoothingOn'];
  }
$radInfo = array();

$GifLoc = 'PRECIPET';
if (substr($radarLoc, 0, 3) === 'CAS') { // Determine if site is CAS** or old one, use CAPPI for CAS** and PRECIPET for old ones
  $GifLoc = 'CAPPI'; // Comment out to disable CAPPI data
}
if (substr($radarLoc, 0, 3) === 'NAT') { // Determine if site is National, use CAPPI only for this option
  $GifLoc = 'CAPPI'; // Comment out to disable CAPPI data
}

$errorMessage = get_image_fnames($radar,$radarLoc,$listFiles=false,$GifLoc);
if ($errorMessage=='Radar Images Currently Unavailable!') {
  echo '<b>No Radar Images Are Currently Available From Here!</b></div>';
  exit;
} else if ($errorMessage=='Too Many Images Requested') {
  echo '<b>Too Many Images Requested from here.  Try Requesting '.$goodImages.' Images.</b></div>';
	exit;
}

if (isset($_REQUEST['lang'])) {
$Lang = strtolower($_REQUEST['lang']);
}
if (isset($doLang)) {$Lang = $doLang;};
//if (! isset($Lang)) {$Lang = $defaultLang;};

if (isset ($Lang) and $Lang == 'fr') {
  $ECNAME = "Environnement Canada";
  $ECHEAD = 'Radar météo';
  $ECNO = 'N/O - Non opérationnel';
  $LNoJS = 'Pour voir l\'animation, il faut que JavaScript soit en fonction.';
	$labelsLang = 'startstop_labels = Animer, Pause \n\
speed_labels = Plus lent, Plus vite \n\
step_labels = Image pr&#233;c&#233;dente,Prochaine image \n\
';
  $labelsOverlay = 'overlay_labels = Villes/on, Villes additionnelles, Routes/on, Num&eacute;ros de route, Rivi&egrave;res, Cercles de Radar \n\
';
  $labelsComposite = 'overlay_labels = Villes/on \n\
';
} else {
  $Lang = 'en';
  $ECNAME = "Environment Canada";
  $ECHEAD = 'Weather Radar';
  $ECNO = 'N/O - Non-operational';
  $LNoJS = 'Please enable JavaScript to view the animation.';
	$labelsLang = 'startstop_labels = Run, Stop \n\
speed_labels = Slower, Faster \n\
step_labels = &lt;, &gt; \n\
';
  $labelsOverlay = 'overlay_labels = Cities/on, More Cities, Roads/on, Road Numbers, Rivers, Radar Circles \n\
';
  $labelsComposite = 'overlay_labels = Cities/on \n\
';
}

?><?php echo "<!-- $Version -->\n"; ?>

  <body style="width:<?php echo $imageWidth?>px" onload="HAniS.setup(
'filenames = <?php get_file_names($radarLoc,$radar,', ')?> \n\
image_base = https://dd.weather.gc.ca/radar/<?php echo $GifLoc; ?>/GIF/<?php echo $radarLoc; ?>/ \n\
controls = startstop, speed, step \n\
<?php echo $labelsLang; ?>
controls_style = display:flex;flex-flow:row;background-color:<?php echo $bgndColor?>; \n\
rate = <?php echo $animRate?> \n\
pause = <?php echo ($pauseSeconds*1000)?> \n\
skip_missing = 0 \n\
skip_missing_color = #800000 \n\
enable_smoothing = <?php echo ($smoothingOn?'t':'f')?> \n\
overlay_labels_style=font-family:arial;font-size:12px;color:<?php echo $btnColor?>;background-color:<?php echo $bgndColor?>; \n\
background_static = y \n\
buttons_style = flex:auto;margin:2px;background-color:<?php echo $btnColor?>;border-radius:7px;color:<?php echo $btnTextColor?>; \n\
<?php echo gen_overlay($radarLoc,$radar,', ',$labelsOverlay,$labelsComposite); ?>
bottom_controls = toggle, overlay \n\
toggle_colors = <?php echo $btnColor?>, red, orange \n\
bottom_controls_tooltip = Toggle frames on/off \n\
bottom_controls_style = background-color:<?php echo $bgndColor?>;' ,
'handiv')">
  <?php echo "<!-- $Version -->\n"; ?>
  <div id="handiv" style="width:<?php echo $imageWidth?>px;background-color:#808080;">
  <noscript><?php echo $LNoJS;?></noscript>
  </div>

   <noscript><h2><?php echo $LNoJS;?></h2></noscript>
 
  </body>
  
 </html>
<?php
############## functions ##############
// ------------------------------------------------------------------
/* begin get_file_names */
function get_file_names($radarLoc,$overlay,$separator){

	global $numbImages, $goodImages;
	for ($i=$numbImages-1; $i>=0; $i--) {
		echo $goodImages[$i];
		echo $i>0?$separator:'';
	}

}
/* end get_file_names */

function gen_overlay($radarLoc,$radar,$sep,$labelsOverlay,$labelsComposite) {
	$composite = array('NATIONAL','ERN','ATL','ONT','PNR','PYR','QUE');
	$r = $radarLoc;
	if(in_array($radarLoc,$composite)) {
	$out = 'overlay_base = ./radar/ \n\
'.$labelsComposite.' \n\
overlay_filenames = '.$r.'_composite.gif \n\
';
	} else {
	
	$out = 'overlay_base = ./radar/ \n\
'.$labelsOverlay.' \n\
overlay_filenames = '.$r.'_towns.gif, '.$r.'_addtowns.gif, '.$r.'_roads.gif, '.$r.'_labs.gif, '.$r.'_rivers.gif, radar_circle.gif \n\
';
	}
	return($out);
}
// ------------------------------------------------------------------
function get_image_fnames($radar,$radarLoc,$listFiles,$GifLoc) {

	global $numbImages,$goodImages,$GifLoc;
	$matches = array();
	$theData = get_data('https://dd.weather.gc.ca/radar/'.$GifLoc.'/GIF/'.$radarLoc.'/');
/*
<img src="/icons/image2.gif" alt="[IMG]"> <a href="202104101920_WKR_COMP_PRECIPET_SNOW_A11Y.gif">202104101920_WKR_COMP_PRECIPET_SNOW_A11Y.gif</a> 2021-04-10 19:28   23K  
<img src="/icons/image2.gif" alt="[IMG]"> <a href="202104101930_WKR_COMP_PRECIPET_RAIN.gif">202104101930_WKR_COMP_PRECIPET_RAIN.gif</a>      2021-04-10 19:38   21K  
<img src="/icons/image2.gif" alt="[IMG]"> <a href="202104101930_WKR_COMP_PRECIPET_RAIN_A11Y.gif">202104101930_WKR_COMP_PRECIPET_RAIN_A11Y.gif</a> 2021-04-10 19:38   21K  
<img src="/icons/image2.gif" alt="[IMG]"> <a href="202104101930_WKR_COMP_PRECIPET_SNOW.gif">202104101930_WKR_COMP_PRECIPET_SNOW.gif</a>      2021-04-10 19:38   23K  
<img src="/icons/image2.gif" alt="[IMG]"> <a href="202104101930_WKR_COMP_PRECIPET_SNOW_A11Y.gif">202104101930_WKR_COMP_PRECIPET_SNOW_A11Y.gif</a> 2021-04-10 19:38   23K  

<img src="/icons/image2.gif" alt="[IMG]"> <a href="202104102110_PYR_PRECIPET_SNOW_WT.gif">202104102110_PYR_PRECIPET_SNOW_WT.gif</a>   2021-04-10 21:24   20K  
<img src="/icons/image2.gif" alt="[IMG]"> <a href="202104102120_PYR_PRECIPET_RAIN_A11Y.gif">202104102120_PYR_PRECIPET_RAIN_A11Y.gif</a> 2021-04-10 21:30   18K  
<img src="/icons/image2.gif" alt="[IMG]"> <a href="202104102120_PYR_PRECIPET_RAIN_WT.gif">202104102120_PYR_PRECIPET_RAIN_WT.gif</a>   2021-04-10 21:30   18K  
<img src="/icons/image2.gif" alt="[IMG]"> <a href="202104102120_PYR_PRECIPET_SNOW_A11Y.gif">202104102120_PYR_PRECIPET_SNOW_A11Y.gif</a> 2021-04-10 21:30   20K  
<img src="/icons/image2.gif" alt="[IMG]"> <a href="202104102120_PYR_PRECIPET_SNOW_WT.gif">202104102120_PYR_PRECIPET_SNOW_WT.gif</a>   2021-04-10 21:30   20K  

*/
  print "<!-- theData returns ".strlen($theData). " bytes -->\n";	
	preg_match_all('!<a href="(.*\.gif)"!Usi', $theData, $matches);
	#print "<!-- matches\n".var_export($matches,true). " -->\n";
	
	$keepImages = array();   // final list of images to use
	$tImages = array();
	
	foreach ($matches[1] as $i => $img) {
		if(strpos($img,$radar) !== false
		   and strpos($img,'A11Y') == false) { // keep the ones we want
			$tImages[] = $img;
		}
	}

  if (count($tImages)<1) {// Revert back to default because no images were found, likely radar is offline
    if ($GifLoc === 'CAPPI') { 
        $GifLoc = 'PRECIPET'; 
    }
    $theData = get_data('https://dd.weather.gc.ca/radar/'.$GifLoc.'/GIF/'.$radarLoc.'/');
    print "<!-- theData returns ".strlen($theData). " bytes -->\n";	
    preg_match_all('!<a href="(.*\.gif)"!Usi', $theData, $matches);
    foreach ($matches[1] as $i => $img) {
      if(strpos($img,$radar) !== false
         and strpos($img,'A11Y') == false) { // keep the ones we want
        $tImages[] = $img;
      }
    }
  }

	#print "<!-- matches\n".var_export($tImages,true). " -->\n";
	print "<!-- found ".count($tImages)." images for radar $radarLoc -->\n";
  for ($i=1;$i<=$numbImages;$i++) {
		$keepImages[] = array_pop($tImages); // prune off the last ones in the list
	}
		
	print "<!-- using these ".count($keepImages)." images for display \n".var_export($keepImages,true). " -->\n";

	
	$imageNumber = count($keepImages);
  $goodImages  = $keepImages;
	if ($imageNumber<$numbImages) {
//    echo 'https://radar.weather.gov/ridge/RadarImg/'.$radar.'/'.strtoupper($radarLoc).'/';
    if ( $imageNumber==0) {
  		return 'Radar Images Currently Unavailable!';
		} /* else if ($imageNumber<=$numbImages) {
			$goodImages = $imageNumber;
			return 'Too Many Images Requested';
		} */
	} else if ($listFiles) {
  	$imageFile = array();
/*
	  for ($i=($imageNumber-1),$j=$numbImages; $i>=($imageNumber-$numbImages); $i--,$j--) {
		  $imageFile[$j] = $matches[1][$i];
	  }
*/
    $imageFile = array_reverse($keepImages);
			
	  for ($i=0; $i<$numbImages; $i++) {
		  $image = 'https://dd.weather.gc.ca/radar/'.$GifLoc.'/GIF/'.$radarLoc.'/'.$imageFile[$i];
		  echo $image;
		  echo '& ';
		  $radInfo[$i] = $imageFile[$i];
	  }
  }
}
/* end get_image_fnames */
// ------------------------------------------------------------------
/* Begin Function get_data */
function get_data($url)
{
	$Debug = "<!-- curl fetching '$url' -->\n";
  $ch = curl_init();
  $timeout = 5;
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);                 // don't verify peer certificate
  curl_setopt($ch, CURLOPT_TIMEOUT, 8);                        //  data timeout
  curl_setopt($ch, CURLOPT_NOBODY, false);                     // set nobody
  curl_setopt($ch, CURLOPT_HEADER, true);                      // include header information
  curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (wxecradar.php, saratoga-weather.org)');
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
  $data = curl_exec($ch);
  if(curl_error($ch) <> '') {                                  // IF there is an error
   $Debug .= "<!-- curl Error: ". curl_error($ch) ." -->\n";        //  display error notice
  }
  $cinfo = curl_getinfo($ch);                                  // get info on curl exec.

  $Debug .= "<!-- HTTP stats: " .
    " RC=".$cinfo['http_code'] .
    " dest=".$cinfo['primary_ip'] ;
	if(isset($cinfo['primary_port'])) { 
	  $Debug .= " port=".$cinfo['primary_port'] ;
	}
	if(isset($cinfo['local_ip'])) {
	  $Debug .= " (from sce=" . $cinfo['local_ip'] . ")";
	}
	$Debug .= 
	"\n      Times:" .
    " dns=".sprintf("%01.3f",round($cinfo['namelookup_time'],3)).
    " conn=".sprintf("%01.3f",round($cinfo['connect_time'],3)).
    " pxfer=".sprintf("%01.3f",round($cinfo['pretransfer_time'],3));
	if($cinfo['total_time'] - $cinfo['pretransfer_time'] > 0.0000) {
	  $Debug .=
	  " get=". sprintf("%01.3f",round($cinfo['total_time'] - $cinfo['pretransfer_time'],3));
	}
    $Debug .= " total=".sprintf("%01.3f",round($cinfo['total_time'],3)) .
    " secs -->\n";
	
  echo $Debug;	
	
  curl_close($ch);
  return $data;
}
/* End Function get_data */
// ------------------------------------------------------------------

############ end functions ############
?>