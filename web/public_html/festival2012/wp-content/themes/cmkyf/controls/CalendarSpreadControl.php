<?php

	require_once CMKYF_PLUGIN_BASE_DIR.'/objects/IControl.php';
	
class CalendarSpreadControl implements IControl
{

	public function __construct()
	{		
	}
	
	public function render()
	{	
		echo "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\"";
			echo "id=\"\${application}\" width=\"590\" height=\"300\"";
			echo "codebase=\"http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab\">";
			echo "<param name=\"movie\" value=\"CalendarSpread.swf\" />";
			echo "<param name=\"quality\" value=\"high\" />";
			echo "<param name=\"bgcolor\" value=\"FFFFFF\" />";
			echo "<param name=\"scale\" value=\"noscale\" />";
			echo "<param name=\"allowScriptAccess\" value=\"sameDomain\" />";
			echo "<param name=\"flashVars\" value=\"xmlsource=schedule_xml.php&startingHour=9&hoursPastStart=21&ColorHourBkg=5f9f0b&ColorMainBkg=8cc63f&ColorHilite1=ec008c&ColorHilite2=5f9f0b&ColorLine=ffffff\"/>";
			echo "<embed flashVars=\"xmlsource=schedule_xml.php&startingHour=9&hoursPastStart=21&ColorHourBkg=5f9f0b&ColorMainBkg=8cc63f&ColorHilite1=ec008c&ColorHilite2=5f9f0b&ColorLine=ffffff\"";
			echo "	src=\"CalendarSpread.swf\" quality=\"high\" bgcolor=\"ffffff\"";
			echo "	width=\"590\" height=\"300\" name=\"Main\" align=\"middle\"";
			echo "	play=\"true\"";
			echo "	loop=\"false\"";
			echo "	scale=\"noscale\"";
			echo "	quality=\"high\"";
			echo "	allowScriptAccess=\"sameDomain\"";
			echo "	type=\"application/x-shockwave-flash\"";
			echo "	pluginspage=\"http://www.adobe.com/go/getflashplayer\">";
			echo "</embed>";
		echo "</object>";
	}
}

?>