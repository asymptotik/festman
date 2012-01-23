function cmkyf_checkform() {

  var icpForm554 = document.getElementById('icpsignup554');
  var val = icpForm554.elements['fields_email'].value;
  
  if (val == "" || jQuery('#ltxtMailingList').hasClass("watermark")) {
    alert("Please enter your email address.");
    icpForm554.elements['fields_email'].focus();
    return false;
  }
  
  return true;
}

function cmkyf_submitToMailingList()
{
	if(cmkyf_checkform() == true)
	{
		var icpForm554 = document.getElementById('icpsignup554');
		icpForm554.submit();
	}
}

jQuery('#ltxtMailingList').watermark('enter email to join');
