function checkform() {

  if (document.subscribeform.elements['email'].value == "") {
    alert("Please enter your email address.");
    document.subscribeform.elements['email'].focus();
    return false;
  }
  
  return true;
}

function submitToMailingList()
{
	//document.location.href = 'servicing-communique.php';
	//return;
	
	if(checkform() == true)
	{
		document.subscribeform.elements['emailconfirm'].value = document.subscribeform.elements['email'].value;
		document.subscribeform.submit();
	}
}
