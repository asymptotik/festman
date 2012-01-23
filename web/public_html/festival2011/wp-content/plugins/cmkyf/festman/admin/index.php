<?php
require_once dirname(__FILE__).'/library/admin_prebody_header.php';
require_once dirname(__FILE__).'/../library/config.php';
require_once dirname(__FILE__).'/../library/opendb.php';
require_once dirname(__FILE__).'/../library/utils.php';
require_once dirname(__FILE__).'/library/admin_utils.php';

require_once dirname(__FILE__).'/library/admin_htmlhead_start.php';
?>



<?php
require_once dirname(__FILE__).'/library/admin_head_end.php';
require_once dirname(__FILE__).'/library/admin_body_start.php';

if(isset($_SESSION['error_message']))
{
	echo "<TABLE align=\"center\" width=\"400\" class=\"border\"><TR><TD class=\"error\">".$_SESSION['error_message']."</TD></TR></TABLE><BR/>";
}

?>



<TABLE CLASS="border">
<THEAD class="h1"><TR CLASS="border"><TD>&nbsp;Festival Manager</TD></TR></THEAD>
<TR><TD class="pallet">&nbsp;</TD></TR>
<TR><TD width="700" align="center" class="pallet">

<TABLE width="80%">
    <TR><TD align="center">
      <image src="../images/CMKY-flower--2inch.gif"/><br><br><br><br>
    </TD></TR>
</TABLE>
</TD></TR></TABLE>




<?php
if(isset($_SESSION['action_message']))
{
	echo "<BR/><TABLE align=\"center\" class=\"border\" WIDTH=\"400\"><TR><TD>".$_SESSION['action_message']."</TD></TR></TABLE>";
}

include dirname(__FILE__).'/../library/closedb.php';
include dirname(__FILE__).'/library/admin_bodyhtml_end.php';
?>