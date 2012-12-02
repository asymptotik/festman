function cmkyf_checkform() {

  var icpForm554 = document.getElementById('ccsfg');
  var val = icpForm554.elements['EmailAddress'].value;
  
  if (val == "" || jQuery('#EmailAddress').hasClass("watermark")) {
    alert("Please enter your email address.");
    icpForm554.elements['EmailAddress'].focus();
    return false;
  }
  
  return true;
}

function cmkyf_submitToMailingList()
{
	if(cmkyf_checkform() == true)
	{
		var icpForm554 = document.getElementById('ccsfg');
		icpForm554.submit();
	}
}

jQuery('#EmailAddress').watermark('enter email to join');
