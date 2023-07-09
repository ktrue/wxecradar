<?php
####################################################################################
#
# list of EC Radar sites active for wxecradar-inc.php to use
#
# Version 1.00 - 14-Apr-2021 - initial release
# Version 1.01 - 01-Nov-2022 - updates for new/changed sites
# Version 1.02 - 01-Nov-2022 - updates for pacific sites
# Version 1.03 - 02-Nov-2022 - added NATIONAL map (thanks M. Romer)
# Version 1.04 - 13-Feb-2023 - added CASMA overlay maps (thanks M. Romer)
#
####################################################################################
#
# Please DO NOT update this list .. it will be updated periodically via 
#   https://saratoga-weather.org/wxtemplates/updates.php for Base-Canada template
#   as radar site codes change.
#
####################################################################################

$StateList = array (
  'National' => array(
    'NATIONAL' => 'National Map', # use CAPPI folder for normal background and PRECIPET folder for white background
  ),
  'Pacific' => array(
		'PYR' => 'Pacific Region',
		'CASAG' => 'Aldergrove (near Vancouver)',        # was WUJ V1.02
		'CASPG' => 'Prince George',                      # was XPG V1.02
		'CASSS' => 'Silver Star Mountain (near Vernon)', # was XSS V1.02
		'XSI' => 'Victoria',                             # will be Halfmoon Peak, BC (Vancouver Island replacement site) (CASHP)
#		'CASHP' => 'Halfmoon Peak',
	),
	'Prairies' => array(
		'PNR' => 'Prairies Region',
		'CASBE' => 'Bethune (near Regina)',
		'CASCV' => 'Carvel (near Edmonton)',           # was WHK V1.01
		'CASFM' => 'Fort McMurray',                    # new V1.01
		'CASFW' => 'Foxwarren (near Brandon)',
		'CASCL' => 'Cold Lake (near Jimmy Lake)',      # was WHN V1.01
		'CASRA' => 'Radisson (near Saskatoon)',
		'CASSU' => 'Schuler (near Medicine Hat)',
		'CASSR' => 'Spirit River (near Grande Prairie)',
		'CASSM' => 'Strathmore (near Calgary)',
		'CASWL' => 'Woodlands (near Winnipeg)',
	),
	'Ontario' => array(
		'ONT' => 'Ontario Region',
		'CASBI' => 'Britt (near Sudbury)',             # was WBI V1.01
		'CASDR' => 'Dryden',
		'CASET' => 'Exeter (near London)',
		'CASFT' => 'Franktown (near Ottawa)',          # was XFT V1.01
		'CASKR' => 'King City (near Toronto)',         # was WKR V1.01
		'CASMR' => 'Montreal River (near Sault Ste Marie)',
		'CASRF' => 'Smooth Rock Falls (near Timmins)',
		'CASSN' => 'Superior West (near Thunder Bay)', # was XNI V1.01
	),
	'Qu&eacute;bec' => array(
		'QUE' => 'Qu&eacute;bec Region',
    'CASMA' => 'Lac Castor / Mont Apica(near Saguenay)', # was WMB V1.04 
		'CASLA' => 'Landrienne (near Rouyn-Noranda)',
		'CASBV' => 'Blainville (near Montr&eacute;al)',
		'CASVD' => 'Val d\'Ir&egrave;ne(near Mont-Joli)',
		'CASSF' => 'Sainte-Fran&ccedil;oise (near Trois-Rivi&egrave;res)',
	),
	'Atlantic' => array(
		'ATL' => 'Atlantic Region',
		'CASCM' => 'Chipman (near Fredericton)',
		'CASGO' => 'Halifax',                          # was XGO V1.01
		'CASHR' => 'Holyrood (near St. John\'s)',
		'CASMM' => 'Marble Mountain',                  # was XME V1.01
		'CASMB' => 'Marion Bridge (near Sydney)',
	),
); // end of default $StateList
