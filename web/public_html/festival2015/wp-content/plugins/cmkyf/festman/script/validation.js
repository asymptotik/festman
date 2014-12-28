function fmValidator()
{
    this.validators = new Array();
    
    this.addValidator = function(validator)
    {
        this.validators.push(validator);
    }
    
    this.validate = function(str)
    {
        var ret = "";

        for(i = 0; i < this.validators.length; i++)
        {
            var errorMessage = this.validators[i].validate(str);
            ret = fmAppendLine(ret, errorMessage);
        }
        return ret;
    }
}

function fmAppendLine(str1, str2)
{
    if(str1.length > 0 && str2.length > 0 && str1[str1.length - 1] != '\n')
    {
        str1 += "\n";
    }
    
    return str1 + str2;
}

function fmGetFileSuffix(str)
{
	var ret = null;

	if(filename.length > 0)
	{
		var index = filename.lastIndexOf(".");
		if(index == -1 || filename.length - (index + 1) == 0)
		{
			return null;
		}
		ret = filename.substr(index + 1, filename.length - (index + 1));
	}

	return ret;
}

function fmRequiredValidator(message)
{
    this.message = message;
    this.validate = function(str)
    {
        ret = "";
        if(str.length == 0)
        {
            ret = this.message;
        }
        return ret;
    }
}

function fmMaxLengthValidator(maxLength, message)
{
    this.maxLength = maxLength;
    this.message = message;
    this.validate = function(str)
    {
        ret = "";
        if(str.length > this.maxLength)
        {
            ret = this.message;
        }
        return ret;
    }
}
    
function fmDateTimeValidator(message)
{
    this.regex = /^\d{1,2}\/\d{1,2}\/\d{2,4} \d{1,2}\:\d{2}(?::\d{2})?$/;
    this.message = message;
    this.validate = function(str)
    {
        ret = "";
        if(str.match(this.regex) == null)
        {
            ret = this.message;
        }
        return ret;
    }
}




function fmCheckEmail (strng) {
	var error="";
	
	if (strng == "") {
		error = "You didn't enter an email address.\n";
	}
	
	var emailFilter=/^.+@.+\..{2,3}$/;
	if (!(emailFilter.test(strng))) { 
	  error = "Please enter a valid email address.\n";
	}
	else
	{
	  //test email for illegal characters
	  var illegalChars= /[\(\)\<\>\,\;\:\\\"\[\]]/;
	  if (strng.match(illegalChars)) {
	      error = "The email address contains illegal characters.\n";
	  }
	}
	return error;    
}

// phone number - strip out delimiters and check for 10 digits

function fmCheckPhone (strng) {
	var error = "";
	if (strng == "") {
	   error = "You didn't enter a phone number.\n";
	}
	
	var stripped = strng.replace(/[\(\)\.\-\ ]/g, ''); //strip out acceptable non-numeric characters
    if (isNaN(parseInt(stripped))) {
       error = "The phone number contains illegal characters.";
    }
    
    if (!(stripped.length == 10)) {
		error = "The phone number is the wrong length. Make sure you included an area code.\n";
    } 
	return error;
}


// password - between 6-8 chars, uppercase, lowercase, and numeral

function fmCheckPassword (strng) {
	var error = "";
	if (strng == "") {
	   error = "You didn't enter a password.\n";
	}
	
    var illegalChars = /[\W_]/; // allow only letters and numbers
    
    if ((strng.length < 6) || (strng.length > 8)) {
       error = "The password is the wrong length.\n";
    }
    else if (illegalChars.test(strng)) {
      error = "The password contains illegal characters.\n";
    } 
    else if (!((strng.search(/(a-z)+/)) && (strng.search(/(A-Z)+/)) && (strng.search(/(0-9)+/)))) {
       error = "The password must contain at least one uppercase letter, one lowercase letter, and one numeral.\n";
    }
    
	return error;    
}    


// username - 4-10 chars, uc, lc, and underscore only.

function fmCheckUsername (strng) {
	var error = "";
	if (strng == "") {
	   error = "You didn't enter a username.\n";
	}
	
    var illegalChars = /\W/; // allow letters, numbers, and underscores
    if ((strng.length < 4) || (strng.length > 10)) {
       error = "The username is the wrong length.\n";
    }
    else if (illegalChars.test(strng)) {
  	  error = "The username contains illegal characters.\n";
    } 
	return error;
}       


// non-empty textbox

function fmIsEmpty(strng) {
	var error = "";
	if (strng.length == 0) {
	   error = "The mandatory text area has not been filled in.\n"
	}
	return error;	  
}

// was textbox altered

function fmIsDifferent(strng) {
	var error = ""; 
	if (strng != "Can\'t touch this!") {
	   error = "You altered the inviolate text area.\n";
	}
	return error;
}

// exactly one radio button is chosen

function fmCheckRadio(checkvalue) {
	var error = "";
	if (!(checkvalue)) {
	    error = "Please check a radio button.\n";
	 }
	return error;
}

// valid selector from dropdown list

function fmCheckDropdown(choice) {
	var error = "";
	if (choice == 0) {
		error = "You didn't choose an option from the drop-down list.\n";
	}    
	return error;
}    