<?php
############################################################################
#
# this script is a port of Jerry Wilkins wxusradars-hanis3.php Version 5a (20190511)
# with additional mods by Ken True for inclusion in the Saratoga Base-Canada template.
# Many thanks to Jerry for allowing the kind use of his code!
#
# Version 1.00 - 14-Apr-2021 - initial release
# Version 1.01 - 01-Nov-2022 - update default to CASKR from WKR
############################################################################
$Version = 'wxecradar-inc.php V1.01 - 01-Nov-2022';
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
}?>
<div id="selectorsarea">
<div>
<?php
// ini_set("allow_url_fopen", true);
	if (!isset($radar)) { // For debugging set some defaults if 'wxecradar-inc.php' is called alone
    // you DON'T need to customize these.. change wxecradar.php instead -- this is for testing
		echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js" type="text/javascript"></script>';
		$radar = 'RAIN'; // Default radar type
		$radarLoc = 'CASKR'; // IMPORTAMT!!! Default radar location is set here
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
		$smoothingOn = true; // Enable image smoothing - new in HAniS 2.5
		$defaultLang = 'en'; // ='en' English, ='fr' French
	}
  if (isset($_POST['radar'])) {
    $radar = $_POST['radar'];
  }
  if (isset($_POST['radarLoc'])) {
    $radarLoc = $_POST['radarLoc'];
  }
  if (isset($_POST['imageWidth'])) {
    $imageWidth = $_POST['imageWidth'];
  }
  if (isset($_POST['iframeWidth'])) {
    $iframeWidth = $_POST['iframeWidth'];
  }
  if (isset($_POST['iframeHeight'])) {
    $iframeHeight = $_POST['iframeHeight'];
  }
  if (isset($_POST['autoRefresh'])) {
    $autoRefresh = $_POST['autoRefresh'];
  }
  if (isset($_POST['autoRefreshTime'])) {
    $autoRefreshTime = $_POST['autoRefreshTime'];
  }
  if (isset($_POST['bgndColor'])) {
    $bgndColor = $_POST['bgndColor'];
  }
  if (isset($_POST['autoRefreshOff'])) {
    $autoRefreshOff = $_POST['autoRefreshOff'];
  }
  if (isset($_POST['btnColor'])) {
    $btnColor = $_POST['btnColor'];
  }
  if (isset($_POST['btnTextColor'])) {
    $btnTextColor = $_POST['btnTextColor'];
  }
  if (isset($_POST['animRate'])) {
    $animRate = $_POST['animRate'];
  }
  if (isset($_POST['pauseSeconds'])) {
    $pauseSeconds = $_POST['pauseSeconds'];
  }
  if (isset($_POST['numbImages'])) {
    $numbImages = $_POST['numbImages'];
  }
  if (isset($_POST['smoothingOn'])) {
    $smoothingOn = $_POST['smoothingOn'];
  }
  if (isset($SITE['lang']) ) {    $Lang = strtolower($SITE['lang']); }
  if (isset($_SESSION['lang'])) { $Lang = strtolower($_SESSION['lang']); }
  if (isset($_REQUEST['lang'])) { $Lang = strtolower($_REQUEST['lang']); }
	if (isset($doLang)) {$Lang = $doLang;};
	if (! isset($Lang)) {$Lang = $defaultLang;};
	
	print "\n<!-- $Version -->\n";

?>
    <table width="600px">
    
    <tr style="vertical-align:bottom"><td width="<?php echo $autoRefresh?25:32?>%" align="center" style="padding:3px;">
    
    <span style="color:black; font-size:13px; font-family:arial, times new roman;">
    <b><?php echo ($Lang=='fr')?'Site Radar':'Radar Site';?></b> &nbsp;
    </span>
    <select id="radarLoc">
<?php
$radarList = load_radarsitelist($Lang);  // load the State:Radar Site listing (array specified at end of script)
# Generate the radar selection list
foreach ($radarList as $S => $sList) {
	print '  <optgroup label="--'.$S.'--">'."\n";
	  foreach($sList as $R => $rName) {
			 $sel = ($radarLoc==$R)?' selected="selected"':'';
			 print '    <option value="'.$R.'"'.$sel.'>'.$rName.'</option>'."\n";
		}
	print "  </optgroup>\n";
}

?>
   </select>
	</td>
	<td width="<?php echo $autoRefresh?16:24?>%" align="center" style="padding:3px;">
    
    <span style="color:#000000; font-size:13px; font-family:arial, times new roman;">
    <b><?php echo ($Lang=='fr')?'Type de radar':'Radar Type';?></b> &nbsp;
    </span>
    <select id="radar">
    <option value="RAIN"<?php echo ($radar=="RAIN"?' selected="selected"':'')?>><?php echo ($Lang=='fr')?'Pluie':'Rain';?></option>
    <option value="SNOW"<?php echo ($radar=="SNOW"?' selected="selected"':'')?>><?php echo ($Lang=='fr')?'Neige':'Snow';?></option>
    </select>
		</td>

    <td width="<?php echo $autoRefresh?30:25?>%" align="center" style="padding:3px;">
      <span style="color:#000000; font-size:13px; font-family:arial, times new roman;">
      <b><?php echo ($Lang=='fr')?'# Images':'# Images';?></b> <br/>
			</span>  
      <select id="numbImages">
      <?php for ($i=3; $i<=10; $i++) {
				echo '<option value="'.$i.'"'.(($i==$numbImages)?' selected="selected"':'').'>'.$i.'</option>';
			} ?>
      </select>
    </td>

    <?php if ($autoRefresh==1) { ?>
    <td width="<?php echo $autoRefresh?16:28?>%" align="center" style="padding:3px;">
    
      <span style="color:#000000; font-size:13px; font-family:arial, times new roman;">
      <b><?php echo ($Lang=='fr')?'Actualisation<br/>automatique':'AutoRefresh';?></b> &nbsp;
			</span>  
      <select id="autorefreshoff">
      	<option value="0"<?php echo ($autoRefreshOff==0)?' selected="selected"':''?>><?php echo ($Lang=='fr')?'permettre':'On';?></option>
        <option value="1"<?php echo ($autoRefreshOff==1)?' selected="selected"':''?>><?php echo ($Lang=='fr')?'d&eacute;sactiver ':'Off';?></option>
      </select>
    </td>
    <td width="18%" align="center" style="padding:3px;">
      
      <span style="color:#000000; font-size:13px; font-family:arial, times new roman;">
      <b><?php echo ($Lang=='fr')?'Intervalle de<br/>rafra&icirc;chissement':'Refresh Interval';?></b> <br/>
      </span>
      <select id="interval">
        <option value="2"<?php echo $autoRefreshTime==2?' selected="selected"':''?>>2 Minutes</option>
        <option value="3"<?php echo $autoRefreshTime==3?' selected="selected"':''?>>3 Minutes</option>
        <option value="4"<?php echo $autoRefreshTime==4?' selected="selected"':''?>>4 Minutes</option>
        <option value="5"<?php echo $autoRefreshTime==5?' selected="selected"':''?>>5 Minutes</option>
        <option value="6"<?php echo $autoRefreshTime==6?' selected="selected"':''?>>6 Minutes</option>
        <option value="8"<?php echo $autoRefreshTime==8?' selected="selected"':''?>>8 Minutes</option>
        <option value="10"<?php echo $autoRefreshTime==10?' selected="selected"':''?>>10 Minutes</option>
        <option value="15"<?php echo $autoRefreshTime==15?' selected="selected"':''?>>15 Minutes</option>
        <option value="20"<?php echo $autoRefreshTime==20?' selected="selected"':''?>>20 Minutes</option>
        <option value="30"<?php echo $autoRefreshTime==30?' selected="selected"':''?>>30 Minutes</option>
      </select>
      

    <?php } else { ?>
      <td width="<?php echo $autoRefresh?12:20; ?>%" align="center" style="padding:3px;">
    
        <span style="color:#000000; font-size:13px; font-family:arial, times new roman;">
          &nbsp; <br/>
        </span>
        <button style="border:1px solid #000000; background-color:<?php echo $btnColor?>;border-radius:7px;color:<?php echo $btnTextColor?>;" id="refresh">
        <b>Refresh</b></button>
    
    <?php } ?>
    
	</td></tr></table>
  
  <?php 
	$jsConstants = array(
			'var lang = "'.$Lang.'";',
			'var imageWidth = "'.$imageWidth.'";',
			'var iframeWidth = "'.$iframeWidth.'";',
			'var iframeHeight = "'.$iframeHeight.'";',
			'var autoRefresh = "'.$autoRefresh.'";',
			'var bgndColor = "'.$bgndColor.'";',
			'var btnColor = "'.$btnColor.'";',
			'var btnTextColor = "'.$btnTextColor.'";',
			'var animRate = "'.$animRate.'";',
			'var pauseSeconds = "'.$pauseSeconds.'";',
			'var autoRefreshOff = "'.($autoRefreshOff?'1':'0').'";',
			'var smoothingOn = "'.($smoothingOn?'1':'0').'";'
	);?>
	
	<script type="text/javascript">
		var $jq = jQuery.noConflict();
    $jq("#radar").change(function () {
	
			<?php for ($i=0; $i<count($jsConstants); $i++) {
				echo $jsConstants[$i]."\n";
			}?>
			var numbImages = $jq("#numbImages").val();
			var autoRefreshTime = $jq("#interval").val();
			var radarLoc = $jq("#radarLoc").val();
      var radar = $jq(this).val();
      showRadarjs(radar,radarLoc,imageWidth,iframeWidth,iframeHeight,autoRefresh,autoRefreshTime,bgndColor,autoRefreshOff,btnColor,btnTextColor,pauseSeconds,animRate,numbImages,smoothingOn,lang);
		});

    $jq("#radarLoc").change(function () {
			<?php for ($i=0; $i<count($jsConstants); $i++) {
				echo $jsConstants[$i]."\n";
			}?>
			var numbImages = $jq("#numbImages").val();
			var autoRefreshTime = $jq("#interval").val();
			var radar = $jq("#radar").val();
      var radarLoc = $jq(this).val();
      showRadarjs(radar,radarLoc,imageWidth,iframeWidth,iframeHeight,autoRefresh,autoRefreshTime,bgndColor,autoRefreshOff,btnColor,btnTextColor,pauseSeconds,animRate,numbImages,smoothingOn,lang);
		});

    $jq("#interval").change(function () {
			<?php for ($i=0; $i<count($jsConstants); $i++) {
				echo $jsConstants[$i]."\n";
			}?>
			var numbImages = $jq("#numbImages").val();
      var radarLoc = $jq("#radarLoc").val();
 			var radar = $jq("#radar").val();
			var autoRefreshTime = $jq(this).val();
     showRadarjs(radar,radarLoc,imageWidth,iframeWidth,iframeHeight,autoRefresh,autoRefreshTime,bgndColor,autoRefreshOff,btnColor,btnTextColor,pauseSeconds,animRate,numbImages,smoothingOn,lang);
		});

    $jq("#refresh").bind("click", function () {
			<?php for ($i=0; $i<count($jsConstants); $i++) {
				echo $jsConstants[$i]."\n";
			}?>
			var numbImages = $jq("#numbImages").val();
			var autoRefreshTime = $jq("#interval").val();
			var radar = $jq("#radar").val();
			var radarLoc = $jq("#radarLoc").val();
      showRadarjs(radar,radarLoc,imageWidth,iframeWidth,iframeHeight,autoRefresh,autoRefreshTime,bgndColor,autoRefreshOff,btnColor,btnTextColor,pauseSeconds,animRate,numbImages,smoothingOn,lang);
		});

    $jq("#autorefreshoff").change(function () {
			<?php for ($i=0; $i<count($jsConstants); $i++) {
				echo $jsConstants[$i]."\n";
			}?>
			var autoRefreshTime = $jq("#interval").val();
			var numbImages = $jq("#numbImages").val();
      var radarLoc = $jq("#radarLoc").val();
			var radar = $jq("#radar").val();
			var autoRefreshOff = $jq(this).val();
      showRadarjs(radar,radarLoc,imageWidth,iframeWidth,iframeHeight,autoRefresh,autoRefreshTime,bgndColor,autoRefreshOff,btnColor,btnTextColor,pauseSeconds,animRate,numbImages,smoothingOn,lang);
		});

    $jq("#numbImages").change(function () {
			<?php for ($i=0; $i<count($jsConstants); $i++) {
				echo $jsConstants[$i]."\n";
			}?>
			var autoRefreshTime = $jq("#interval").val();
      var radarLoc = $jq("#radarLoc").val();
			var radar = $jq("#radar").val();
			var numbImages = $jq(this).val();
      showRadarjs(radar,radarLoc,imageWidth,iframeWidth,iframeHeight,autoRefresh,autoRefreshTime,bgndColor,autoRefreshOff,btnColor,btnTextColor,pauseSeconds,animRate,numbImages,smoothingOn,lang);
		});

    </script>

<?php
$ridgeRadars = array (
    "RAIN" => "Rain",
    "SNOW" => "Snow",
);
$ourRadar = '';
$ourState = '';
$ourCity = '';
$ourRadar=$ridgeRadars[$radar];
foreach ($radarList as $key => $liststate) {
	$ourState = $key;
	foreach ($liststate as $key => $listcity) {
		if ($key === $radarLoc) {
			$ourCity = $listcity;
			break;
		}
	}
	if (strlen($ourCity)>0) break;
}
?>
<script type="text/javascript">
	function reloadIframe() {
		var now = new Date();
		var myframe;
		myframe = window.frames["wxradarshanis"];
		<?php if ($autoRefreshOff==0) { ?>
			if (myframe!==null) {
				myframe.location.reload();
			} else alert("No myframe found");
			setTimeout('reloadIframe()',<?php echo $autoRefreshTime?>*60000);
		<?php } ?>
	}
	<?php if ($autoRefreshOff==0) { ?>
	setTimeout('reloadIframe()',<?php echo $autoRefreshTime?>*60000);
	<?php } ?>
</script>
</div>
<div class="center" align="center" style="width:620px">
<h3 align="center"><?php echo $ourCity?>, <?php //echo $ourState?> <?php // echo $ourRadar?> Radar (<?php echo $radarLoc; ?>)</h3>
		<iframe name="wxradarshanis" width="<?php echo $iframeWidth?>" height="<?php echo $iframeHeight?>" src="./wxecradar-iframe.php?radar=<?php echo $radar?>&amp;radarLoc=<?php echo $radarLoc?>&amp;imageWidth=<?php echo $imageWidth?>&amp;bgndColor=<?php echo $bgndColor?>&amp;btnColor=<?php echo $btnColor?>&amp;btnTextColor=<?php echo $btnTextColor?>&amp;animRate=<?php echo $animRate?>&amp;pauseSeconds=<?php echo $pauseSeconds?>&amp;numbImages=<?php echo $numbImages?>&amp;smoothingOn=<?php echo $smoothingOn?>&amp;lang=<?php echo $Lang; ?>" scrolling="no" style="border:none"></iframe>
<hr/>

  <span style="font-size:9px; text-align:center">The above images are produced by <a href="https://weather.gc.ca/map_e.html" target="_blank" title="Off Site">EC Radar</a> -- Animation by <a href="https://www.ssec.wisc.edu/hanis/index.html" target="_blank" title="New Tab">HAniS</a> &copy;2014-<?php echo date("Y")?> by Tom Whittaker<br/>
<a href="https://weather.gc.ca/map_e.html" target="_blank" title="New Tab">Environment Canada Radar</a>&nbsp;&nbsp;
<br/>
HAniS Script by <a href="https://www.gwwilkins.org" title="Off Site" target="_blank">SE Lincoln Weather</a> and 
 <a href="https://saratoga-weather.org/" target="_blank" title="Off Site">Saratoga-Weather</a><br/>
  </span>
</div> <!-- end center -->
</div> <!-- End #selectorsarea id -->
<?php 
############################################################################
	
function load_radarsitelist($lang='en') {

if(file_exists('wxecradar-list-inc.php')) {
	include_once('wxecradar-list-inc.php');
	print "<!-- using 'wxecradar-list-inc.php' radar site list -->\n";
} else {
// Builtin default list
	print "<!-- using builtin radar site list -->\n";
$StateList = array (
 // 'NAT' => 'National Map', # no longer available
  'Pacific' => array(
		'PYR' => 'Pacific Region',
		'WUJ' => 'Aldergrove (near Vancouver)',
		'XPG' => 'Prince George',
		'XSS' => 'Silver Star Mountain (near Vernon)',
		'XSI' => 'Victoria',
	),
	'Prairies' => array(
		'PNR' => 'Prairies Region',
		'CASBE' => 'Bethune (near Regina)',
		'WHK' => 'Carvel (near Edmonton)',
		'CASFW' => 'Foxwarren (near Brandon)',
		'WHN' => 'Jimmy Lake (near Cold Lake)',
		'CASRA' => 'Radisson (near Saskatoon)',
		'CASSU' => 'Schuler (near Medicine Hat)',
		'CASSR' => 'Spirit River (near Grande Prairie)',
		'CASSM' => 'Strathmore (near Calgary)',
		'CASWL' => 'Woodlands (near Winnipeg)',
	),
	'Ontario' => array(
		'ONT' => 'Ontario Region',
		'WBI' => 'Britt (near Sudbury)',
		'CASDR' => 'Dryden',
		'CASET' => 'Exeter (near London)',
		'XFT' => 'Franktown (near Ottawa)',
		'WKR' => 'King City (near Toronto)',
		'CASMR' => 'Montreal River (near Sault Ste Marie)',
		'CASRF' => 'Smooth Rock Falls (near Timmins)',
		'XNI' => 'Superior West (near Thunder Bay)',
	),
	'Qu&eacute;bec' => array(
		'QUE' => 'Qu&eacute;bec Region',
		'WMB' => 'Lac Castor (near Saguenay)',
		'CASLA' => 'Landrienne (near Rouyn-Noranda)',
		'CASBV' => 'Blainville (near Montr&eacute;al)',
		'CASVD' => 'Val d\'Ir&egrave;ne(near Mont-Joli)',
		'CASSF' => 'Sainte-Fran&ccedil;oise (near Trois-Rivi&egrave;res)',
	),
	'Atlantic' => array(
		'ATL' => 'Atlantic Region',
		'CASCM' => 'Chipman (near Fredericton)',
		'XGO' => 'Halifax',
		'CASHR' => 'Holyrood (near St. John\'s)',
		'XME' => 'Marble Mountain',
		'CASMB' => 'Marion Bridge (near Sydney)',
	),
); // end of default $StateList
} 
if ($lang == 'fr') {
	$enStrings = array('(near','Region','Atlantic','Pacific','Quebec','Praries');
	$frStrings = array('(pr&egrave;s de','R&eacute;gion','Atlantque','Pacifique','Qu&eacute;bec','des Prairies');
	$newList = array();
	foreach ($StateList as $name => $list) {
		$newName = str_replace($enStrings,$frStrings,$name);
	  $newList[$newName] = str_replace($enStrings,$frStrings,$list);
	}
	$StateList = $newList;
}
return($StateList);

}
?>
<!-- end wxecradar-inc.php -->
