

<html>
<head>
  <title>cmky festival</title>
  <link rel="stylesheet" type="text/css" href="../../script/adminss.css" />
  <script type="text/javascript" src="../../script/validation.js"></script>
  <script type="text/javascript" src="../../script/utils.js"></script>

<script type="text/javascript">

function onSubmitCollateralSetForm()
{
    var form = document.forms["collateral_collection_form"];
    
    form.elements["action"].value = "add_selected_collateral";
    form.submit();
}

function checkExtension(extension)
{
	var found = false;
	var suffix_list = new Array(20);

	suffix_list[0] = "jpg";
	suffix_list[1] = "jpeg";
	suffix_list[2] = "jpe";
	suffix_list[3] = "png";
	suffix_list[4] = "gif";
	suffix_list[5] = "mpeg";
	suffix_list[6] = "mpg";
	suffix_list[7] = "mpe";
	suffix_list[8] = "mpv";
	suffix_list[9] = "vbs";
	suffix_list[10] = "mpegv";
	suffix_list[11] = "avi";
	suffix_list[12] = "mp3";
	suffix_list[13] = "doc";
	suffix_list[14] = "pdf";
	suffix_list[15] = "rtf";
	suffix_list[16] = "ogg";
	suffix_list[17] = "html";
	suffix_list[18] = "txt";
	suffix_list[19] = "wav";

	for(i = 0; i < suffix_list.length; i++)
	{
		if(extension == suffix_list[i])
		{
			found = true;
			break;
		}
	}
	
	return found;
}

function validateFileName(filename)
{
	if(filename.length > 0)
	{
		var suffix = getFileSuffix(filename);
		if(suffix == null || checkExtension(suffix) == false)
		{
			return "Invalid file: '" + filename + "'";
		}
	}
	
	return "";
}

function validateFileNames()
{
	var message = "";
	
	var form = document.forms["upload_form"];
	message = appendLine(message, validateFileName(form.elements["file"].value));
	
	if(message.length > 0)
	{
		alert(message);
	}	
}

function onSubmitUploadForm()
{
    var form = document.forms["upload_form"];
    validateFileNames();
    form.elements["action"].value = "upload_collateral";
    form.submit();
}


</script>
</head>
<body>

<?php

echo "POST: ";
print_r($_POST);
echo "<br>";

echo "GET: ";
print_r($_GET);
echo "<br>";

echo "FILES: ";
print_r($_FILES);
echo "<br>";
 ?>
 
 
<FORM NAME="upload_form" ID="upload_form" METHOD="POST" ACTION="handler_test.php" enctype="multipart/form-data">
<input type="hidden" name="action" value="upload_collateral" /> 
<input type="hidden" name="action_id" value="delete47e32ceba26ad" />
<input type="hidden" name="collateral_collection_type" value="program_item" />
<input type="hidden" name="MAX_FILE_SIZE" value="200000" />

<TABLE width="80%" align="center">

	<TR>
		<TD>&nbsp;</TD><TD>&nbsp;</TD><TD>&nbsp;</TD>
	</TR>

	<TR>
		<TD colspan="3" align="center" class="label">Upload Act Collateral</TD>
	</TR>
	<TR>
		<TD class="label">Collateral 1:</TD>
		<TD colspan="2"><INPUT type="file" name="file" size="40" /></TD>
	</TR>
	<TR>

		<TD>&nbsp;</TD>
		<TD colspan="2"><INPUT type="checkbox" name="overwrite_existing_collateral" />Overwrite Existing Collateral</TD>
		<TD align="right"><BUTTON type="submit">Upload</BUTTON>
		</TD>
	</TR>
</TABLE>
</FORM>
</body>