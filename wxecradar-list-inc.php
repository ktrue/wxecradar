<?php
####################################################################################
#
# list of EC Radar sites active for wxecradar-inc.php to use
#
# Version 1.00 - 14-Apr-2021 - initial release
#
####################################################################################
#
# Please DO NOT update this list .. it will be updated periodically via 
#   https://saratoga-weather.org/wxtemplates/updates.php for Base-Canada template
#   as radar site codes change.
#
####################################################################################

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
