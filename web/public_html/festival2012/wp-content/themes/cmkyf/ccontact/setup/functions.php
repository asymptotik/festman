<?php

	
	$all_fields = array(
		'EmailAddress'	=>	array('Email Address', 'text'), 
		'FirstName'		=>	array('First Name', 'text'), 
		'LastName'		=>	array('Last Name', 'text'), 
		'MiddleName'	=>	array('Middle Name', 'text'), 
		'HomePhone'		=>	array('Home Phone', 'text'), 
		'Addr1'			=>	array('Address Line 1', 'text'), 
		'Addr2'			=>	array('Address Line 2', 'text'), 
		'Addr3'			=>	array('Address Line 3', 'text'), 
		'City'			=>	array('City', 'text'),
		'StateCode'		=>	array('State/Province (US/Canada)',  'states'), 
		'StateName'		=>	array('State/Province (Other)',  'text'), 
		'CountryCode'	=>	array('Country',  'countries'), 
		'PostalCode'	=>	array('Zip/Postal Code',  'text'), 
		'SubPostalCode'	=>	array('Sub Zip/Postal Code',  'text'), 
		'EmailType'		=>	array('Email Format',  'formats'), 
		'Notes'			=>	array('Notes',  'text'), 
		'CompanyName'	=>	array('Company Name',  'text'), 
		'JobTitle'		=>	array('Job Title',  'text'), 
		'WorkPhone'		=>	array('Work Phone',  'text')
	);


$us_states = array("AL"=>"Alabama","AK"=>"Alaska","AZ"=>"Arizona","AR"=>"Arkansas","AA"=>"Armed Forces Americas","AE"=>"Armed Forces Europe","AP"=>"Armed Forces Pacific","CA"=>"California","CO"=>"Colorado","CT"=>"Connecticut","DE"=>"Delaware","DC"=>"District of Columbia","FL"=>"Florida","GA"=>"Georgia","HI"=>"Hawaii","ID"=>"Idaho","IL"=>"Illinois","IN"=>"Indiana","IA"=>"Iowa","KS"=>"Kansas","KY"=>"Kentucky","LA"=>"Louisiana","ME"=>"Maine","MD"=>"Maryland","MA"=>"Massachusetts","MI"=>"Michigan","MN"=>"Minnesota","MS"=>"Mississippi","MO"=>"Missouri","MT"=>"Montana","NE"=>"Nebraska","NV"=>"Nevada","NH"=>"New Hampshire","NJ"=>"New Jersey","NM"=>"New Mexico","NY"=>"New York","NC"=>"North Carolina","ND"=>"North Dakota","OH"=>"Ohio","OK"=>"Oklahoma","OR"=>"Oregon","PA"=>"Pennsylvania","RI"=>"Rhode Island","SC"=>"South Carolina","SD"=>"South Dakota","TN"=>"Tennessee","TX"=>"Texas","UT"=>"Utah","VT"=>"Vermont","VA"=>"Virginia","WA"=>"Washington","WV"=>"West Virginia","WI"=>"Wisconsin","WY"=>"Wyoming");
        
$ca_states = array("AB"=>"Alberta","BC"=>"British Columbia","NB"=>"New Brunswick","NL"=>"Newfoundland and Labrador","NT"=>"Northwest Territories","NS"=>"Nova Scotia","NU"=>"Nunavut","ON"=>"Ontario","PE"=>"Prince Edward Island","QC"=>"Quebec","SK"=>"Saskatchewan","YT"=>"Yukon Territory");

$states = array_merge($us_states, $ca_states);
asort($states);
        
$countries = array("US"=>"United States","AF"=>"Afghanistan","AX"=>"Aland Islands","AL"=>"Albania","DZ"=>"Algeria","AS"=>"American Samoa","AD"=>"Andorra","AO"=>"Angola","AI"=>"Anguilla","AQ"=>"Antarctica","AG"=>"Antigua and Barbuda","AR"=>"Argentina","AM"=>"Armenia","AW"=>"Aruba","AU"=>"Australia","AT"=>"Austria","AZ"=>"Azerbaijan","BS"=>"Bahamas","BH"=>"Bahrain","BD"=>"Bangladesh","BB"=>"Barbados","BY"=>"Belarus","BE"=>"Belgium","BZ"=>"Belize","BJ"=>"Benin","BM"=>"Bermuda","BT"=>"Bhutan","BO"=>"Bolivia","BA"=>"Bosnia and Herzegovina","BW"=>"Botswana","BV"=>"Bouvet Island","BR"=>"Brazil","IO"=>"British Indian Ocean Territory","BN"=>"Brunei Darussalam","BG"=>"Bulgaria","BF"=>"Burkina Faso","BI"=>"Burundi","KH"=>"Cambodia","CM"=>"Cameroon","CA"=>"Canada","CV"=>"Cape Verde","KY"=>"Cayman Islands","CF"=>"Central African Republic","TD"=>"Chad","CL"=>"Chile","CN"=>"China","CX"=>"Christmas Island","CC"=>"Cocos (Keeling) Islands","CO"=>"Colombia","KM"=>"Comoros","CG"=>"Congo","CD"=>"Congo, Democratic Republic of","CK"=>"Cook Islands","CR"=>"Costa Rica","CI"=>"Cote D'Ivoire","HR"=>"Croatia","CY"=>"Cyprus","CZ"=>"Czech Republic","DK"=>"Denmark","DJ"=>"Djibouti","DM"=>"Dominica","DO"=>"Dominican Republic","TMP"=>"East Timor","EC"=>"Ecuador","EG"=>"Egypt","SV"=>"El Salvador","ENG"=>"England","GQ"=>"Equatorial Guinea","ER"=>"Eritrea","EE"=>"Estonia","ET"=>"Ethiopia","FO"=>"Faroe Islands","FK"=>"Faukland Islands","FJ"=>"Fiji","FI"=>"Finland","FR"=>"France","GF"=>"French Guyana","PF"=>"French Polynesia","TF"=>"French Southern Territories","GA"=>"Gabon","GM"=>"Gambia","GE"=>"Georgia","DE"=>"Germany","GH"=>"Ghana","GI"=>"Gibraltar","GR"=>"Greece","GL"=>"Greenland","GD"=>"Grenada","GP"=>"Guadeloupe","GU"=>"Guam","GT"=>"Guatemala","GG"=>"Guernsey","GN"=>"Guinea","GW"=>"Guinea-Bissau","GY"=>"Guyana","HT"=>"Haiti","HM"=>"Heard and McDonald Islands","HN"=>"Honduras","HK"=>"Hong Kong","HU"=>"Hungary","IS"=>"Iceland","IN"=>"India","ID"=>"Indonesia","IQ"=>"Iraq","IE"=>"Ireland","IM"=>"Isle of Man","IL"=>"Israel","IT"=>"Italy","JM"=>"Jamaica","JP"=>"Japan","JE"=>"Jersey","JO"=>"Jordan","KZ"=>"Kazakhstan","KE"=>"Kenya","KI"=>"Kiribati","KW"=>"Kuwait","KG"=>"Kyrgyzstan","LA"=>"Laos","LV"=>"Latvia","LB"=>"Lebanon","LS"=>"Lesotho","LR"=>"Liberia","LY"=>"Libya","LI"=>"Liechtenstein","LT"=>"Lithuania","LU"=>"Luxembourg","MO"=>"Macao","MK"=>"Macedonia","MG"=>"Madagascar","MW"=>"Malawi","MY"=>"Malaysia","MV"=>"Maldives","ML"=>"Mali","MT"=>"Malta","MH"=>"Marshall Islands","MQ"=>"Martinique","MR"=>"Mauritania","MU"=>"Mauritius","YT"=>"Mayotte","MX"=>"Mexico","FM"=>"Micronesia","MD"=>"Moldova","MC"=>"Monaco","MN"=>"Mongolia","ME"=>"Montenegro","MS"=>"Montserrat","MA"=>"Morocco","MZ"=>"Mozambique","MM"=>"Myanmar","NA"=>"Namibia","NR"=>"Nauru","NP"=>"Nepal","NL"=>"Netherlands","AN"=>"Netherlands Antilles","UNAVAILABLE"=>"Neutral Zone","NC"=>"New Caledonia","NZ"=>"New Zealand","NI"=>"Nicaragua","NE"=>"Niger","NG"=>"Nigeria","NU"=>"Niue","NF"=>"Norfolk Island","NIR"=>"Northern Ireland","MP"=>"Northern Mariana Islands","NO"=>"Norway","OM"=>"Oman","PK"=>"Pakistan","PW"=>"Palau","PS"=>"Palestinian Territory, Occupied","PA"=>"Panama","PG"=>"Papua New Guinea","PY"=>"Paraguay","PE"=>"Peru","PH"=>"Philippines","PN"=>"Pitcairn","PL"=>"Poland","PT"=>"Portugal","PR"=>"Puerto Rico","QA"=>"Qatar","RE"=>"Reunion","RO"=>"Romania","RU"=>"Russian Federation","RW"=>"Rwanda","BL"=>"Saint Barthelemy","SH"=>"Saint Helena","KN"=>"Saint Kitts and Nevis","LC"=>"Saint Lucia","MF"=>"Saint Martin","PM"=>"Saint Pierre and Miquelon","VC"=>"Saint Vincent &amp; the Grenadines","WS"=>"Samoa","SM"=>"San Marino","ST"=>"Sao Tome and Principe","SA"=>"Saudi Arabia","SCT"=>"Scotland","SN"=>"Senegal","RS"=>"Serbia","SC"=>"Seychelles","SL"=>"Sierra Leone","SG"=>"Singapore","SK"=>"Slovakia","SI"=>"Slovenia","SB"=>"Solomon Islands","SO"=>"Somalia","ZA"=>"South Africa","GS"=>"South Georgia &amp; S. Sandwich Is.","KR"=>"South Korea","ES"=>"Spain","LK"=>"Sri Lanka","SR"=>"Suriname","SJ"=>"Svalbard and Jan Mayen","SZ"=>"Swaziland","SE"=>"Sweden","CH"=>"Switzerland","TW"=>"Taiwan","TJ"=>"Tajikistan","TZ"=>"Tanzania","TH"=>"Thailand","TG"=>"Togo","TK"=>"Tokelau","TO"=>"Tonga","TT"=>"Trinidad and Tobago","TN"=>"Tunisia","TR"=>"Turkey","TM"=>"Turkmenistan","TC"=>"Turks and Caicos Islands","TV"=>"Tuvalu","UG"=>"Uganda","UA"=>"Ukraine","AE"=>"United Arab Emirates","GB"=>"United Kingdom","UM"=>"United States Minor Outlying Is.","UY"=>"Uruguay","UZ"=>"Uzbekistan","VU"=>"Vanuatu","VA"=>"Vatican City State","VE"=>"Venezuela","VN"=>"Viet Nam","VG"=>"Virgin Islands, British","VI"=>"Virgin Islands, U.S.","UK"=>"Wales","WF"=>"Wallis and Futuna","EH"=>"Western Sahara","YE"=>"Yemen","ZM"=>"Zambia","ZW"=>"Zimbabwe");



	function CustomFields(){
		$custom_fields = array();
		for($i=1; $i<16; $i++) {
			$custom_fields[] = 'Custom Field '.$i; 
		}
		return $custom_fields;
	}


	function createConfig(){
		$custom_fields = '"'.implode('","',CustomFields()).'"';
		$content .= '<?php'."\r\n";
		$content .= 'class CC_Config { '."\r\n";
		$content .= 'var $login = ""; '."\r\n";
		$content .= 'var $password = ""; '."\r\n";
		$content .= 'var $apikey = "bb54784e-41d3-4bb7-93f9-27805b114e4a"; '."\r\n";
		$content .= 'var $contact_lists = array(); '."\r\n";
		$content .= 'var $force_lists = false; '."\r\n";
		$content .= 'var $included_fields = array(); '."\r\n";
		$content .= 'var $custom_field_labels = array('.$custom_fields.'); '."\r\n";
		$content .= 'var $show_contact_lists = true; '."\r\n";
		$content .= 'var $actionBy = "ACTION_BY_CONTACT"; '."\r\n";
		$content .= 'var $success_url = ""; '."\r\n";
		$content .= 'var $failure_url = ""; '."\r\n";
		$content .= 'var $make_dialog = ""; '."\r\n";
		$content .= '} '."\r\n";
		$content .= '?>';
			
		if(updateFile("../config.php", $content)){
			updateFile("../ccsfg.log", date('c')." - Configuration file created successfully.\n", "a");
			return true;
		} else {
			updateFile("../ccsfg.log", date('c')." - Error generating configuration file.\n", "a");
			return false;
		}
			
	}
	
	
	
	function updateConfigCredentials($login, $pass, $type=''){
		$lines = file('../config.php');
		foreach ($lines as $key =>$line){
			if(strstr($line, '$login')){
				$line = 'var $login = \'' . $login . "'" . ";\r\n";
			}
			else if(strstr($line, '$password')){
				$line = 'var $password = \'' . $pass . "'" . ";\r\n";
			}
			$content .= $line;
		}
	
		if(updateFile("../config.php", $content)){
			if($type=='reset'){
				updateFile("../ccsfg.log", date('c')." - Credentials reset to blank in config file.\n", "a");
			}
			else {
				updateFile("../ccsfg.log", date('c')." - Credentials written to config file.\n", "a");
			}
			return true;
		} else {
			updateFile("../ccsfg.log", date('c')." - Error writing to configuration file (credentials).\n", "a");
			return false;
		}
	}
	
	
	
	function updateConfigOptions(){
		$lines = file('../config.php');
		foreach ($lines as $key =>$line){
			if(strstr($line, '$success_url')){
				$line = 'var $success_url = "'.$_REQUEST['success_url'].'"'.";\r\n";
			}
			else if(strstr($line, '$failure_url')){
				$line = 'var $failure_url = "'.$_REQUEST['failure_url'].'"'.";\r\n";
			}
			else if(strstr($line, '$make_dialog')){
				$line = 'var $make_dialog = "'.$_REQUEST['make_dialog'].'"'.";\r\n";
			}
			else if(strstr($line, '$included_fields')){
				$line = 'var $included_fields = array(';
				$line .= '"'.implode('","', $_REQUEST['fields']).'"';
				$line .= ');'."\r\n";
			}
			else if(strstr($line, '$custom_field_labels')){
				$line = 'var $custom_field_labels = array(';
				$line .= '"'.implode('","', $_REQUEST['custom_field_labels']).'"';
				$line .= ');'."\r\n";
			}
			$content .= $line;
		}
		
		if(updateFile("../config.php", $content)){
			updateFile("../ccsfg.log", date('c')." - Configuration options updated successfully.\n", "a");
			return true;
		} else {
			updateFile("../ccsfg.log", date('c')." - Error updating configuration options.\n", "a");
			return false;
		}
		
	}

	
	function updateConfigLists(){
		$lines = file('../config.php');
		foreach ($lines as $key =>$line){
			if(strstr($line, '$contact_lists')){
				$line = 'var $contact_lists = array(';
				$line .= $lists = '"'.implode('","', $_REQUEST['lists']).'"';
				$line .= ');'."\r\n";
			}
			else if(strstr($line, '$show_contact_lists')){
				$tf_val = ($_REQUEST['show_contact_lists'] ? "true" : "false");
				$line = 'var $show_contact_lists = '.$tf_val.''.";\r\n";
			}
			$content .= $line;
		}
		
		if(updateFile("../config.php", $content)){
			updateFile("../ccsfg.log", date('c')." - Configuration lists updated successfully.\n", "a");
			return true;
		} else {
			updateFile("../ccsfg.log", date('c')." - Error updating configuration lists.\n", "a");
			return false;
		};
	}
	
	function updateFile($filename, $content, $method='w'){
		/*
		if($method=="a"){		
			$filesize = filesize($filename);			
			if($filesize>2097152){				
				$lines = file($filename);
				foreach ($lines as $key =>$line){
					if($key>20){
						$tmp_content .= $line;
					}
				}
			$method = "w";	
			}
		}
		*/
		
		
		$file = fopen($filename, $method) or die("Cannot open $filename");
		$write_content = $tmp_content.$content;
		fwrite($file, $write_content);
		fclose($file);
		return true;
	}

?>
