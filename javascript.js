/*
	General Functions
*/
function checkFormNotEmpty(form){
	var returnValue = true;
	for (var i = 0; i < form.length; i++) {
		if (!form.elements[i].name==null || !form.elements[i].name==""){
			if(!checkNotEmpty(form.elements[i])){
				returnValue = false;
				return returnValue;
			}
		}		
	}
	return returnValue;
}

function checkNotEmpty(element){
	if(element.value==null || element.value==""){
		alert(element.name + " is a required field. Please modify and resubmit.");
		return false;
	} 
	return true;
}

// Adapted from http://www.w3schools.com/jsref/jsref_regexp_test.asp [Accessed 17 November 2013]
function checkMatchPattern(element, pattern, regexDescription){
	var pattern = new RegExp(pattern);
	if (!pattern.test(element.value)){
		alert(element.name + regexDescription);
		return false;
	}
	return true;
}

function checkLengthBetween(element, lowerBound, upperBound){
	if (element.value.length < lowerBound || element.value.length > upperBound){
		alert(element.name + " is not between " + lowerBound + " and " + upperBound + ". Please modify the field.");
		return false;
	}
	return true;
}

function checkLengthLessThan(element, length){
	if (element.value.length > length){
		alert(element.name + " is larger than the allowed character size of " + length + ". Please modify the field.");
		return false;
	}	
	return true;
}

function checkLengthEqualTo(element, length){
	if (element.value.length == length){
		alert(element.name + " should be " + length + " characters long. Please modify the field.");
		return false;
	}	
	return true;
}

function checkNaN(element){
	if (isNaN(element.value)){
		alert(element.name + " should be a number. Please modify the field.");
		return false;
	}	
	return true;
}

function checkBetween(element, lowerBound, upperBound){
	if (element.value < lowerBound || element.value > upperBound){
		alert(element.name + " should be between " + lowerBound + " and " + upperBound + ". Please modify the field.");
		return false;
	}
	return true;
}


/*
	JavaScript for login.php
*/

//	Code adapted from http://www.w3schools.com/js/js_form_validation.asp -->
// Username validation adapted from http://stackoverflow.com/questions/13392842/using-php-regex-to-validate-username [Accessed 15 November 2013]
// E-mail validation adapted from http://www.regular-expressions.info/email.html [Accessed 17 November 2013]
function validateCreateAccountForm(form){
	if (!checkFormNotEmpty(form)){
		return false;
	} else if (!checkMatchPattern(form["Username"], '^[A-Za-z0-9-_]{0,30}$'," must contain a-z, A-Z, 0-9, - or _ characters only, and be up to 30 characters long.")){
		return false;
	}  else if (!checkLengthBetween(form["Password"],8, 30)){
		return false;
	} else if (!checkLengthBetween(form["ConfirmPassword"],8, 30))	{
		return false;
	} else if (!checkLengthLessThan(form["E-mail"], 30)){
		return false;
	} else if(!checkMatchPattern(form["E-mail"], '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]+$', " must be a valid e-mail address, and up to 30 characters long.")){
		return false;
	} else if (!checkMatchPattern(form["Captcha"], '^[A-Za-z0-9]{8,8}$'," must contain a-z, A-Z, 0-9, - or _ characters only, and be 8 characters long.")){
		return false;
	} else if (form["Password"].value != form["ConfirmPassword"].value) {
		alert("Both passwords entered must match. Please try again.");
		return false;
	}
	return true;
}

function validateLoginForm(form){
	if (!checkFormNotEmpty(form)){
		return false;
	} else if (!checkMatchPattern(form["Username"], "^[A-Za-z0-9-_]{0,30}$"," must contain a-z, A-Z, 0-9, - or _ characters only, and be up to 30 characters long.")){
		return false;
	} else if (!checkLengthBetween(form["Password"],8, 30)){
		return false;
	}
	return true;
}

/*
	JavaScript for verifyAccount.php
*/
function validateVerifyAccountForm(form){
	if (!checkFormNotEmpty(form)){
		return false;
	} else if (!checkMatchPattern(form["Code"], '^[a-zA-Z0-9]{5,5}$', " must contain a-z, A-Z and 0-9 characters only, and be between 5 characters long.")){
		return false;
	}
	return true;
}

/*
	JavaScript for twitch.php
*/
function validateTwitch(form){
	if (!checkNotEmpty(form["Latitude"])){
		return false;
	} else if (!checkNotEmpty(form["Longitude"])){
	 	return false;
	} else if (!checkNotEmpty(form["Species"])){
	 	return false;
	} else if (!checkNotEmpty(form["Age"])){
	 	return false;
	} else if (!checkNotEmpty(form["Description"])){
	 	return false;
	} else if (!checkLengthBetween(form["Species"], 1, 30)){
		return false;
	} else if (!checkLengthBetween(form["Age"], 1, 30)){
		return false;
	}  else if (!checkLengthBetween(form["Description"], 1, 500)){
		return false;
	} else if (!checkNaN(form["Latitude"])){
		return false;
	} else if (!checkNaN(form["Longitude"])){
		return false;
	} else if (!checkBetween(form["Latitude"], 51.47, 51.51)){
		return false;
	} else if (!checkBetween(form["Longitude"], -0.02, 0.02)){
		return false;
	}
	return true;
}

/*
	JavaScript for viewtwitch.php
*/
function validateEditImage(form){
	for (var i=0; i<form.length; i++)	{
		var pattern = new RegExp("^[Caption][0-9]*&");
		if (pattern.test(form[i].name))	{
			if (!checkLengthLessThan(form[i], 31)){
				return false;
			}
		}
	}
	return true;
}

function validateUploadImage(form){
	if (!checkFormNotEmpty(form)){
		return false;
	} else if (!checkLengthLessThan(form["Caption"], 31)){
		return false;
	}
	return true;
}

/*
	Javascript for search.php
*/
function validateSearch(form){
	if (form["LatitudeLower"].value  != '' && form["LatitudeUpper"].value  != ''){
		if (form["LatitudeLower"].value  < 51.47 || form["LatitudeLower"].value > 51.51 || form["LatitudeUpper"].value < 51.47 || form["LatitudeUpper"].value > 51.51){
			alert("Both Latitude fields must be between 51.47 and 51.51.")
			return false;
		}
	} else if ( (form["LatitudeLower"].value  != '' && form["LatitudeUpper"].value  == '') || (form["LatitudeLower"].value  == '' && form["LatitudeUpper"].value != '') ){
		alert("Both Latitude fields must be between 51.47 and 51.51.")
		return false;
	}
	if (form["LongitudeLower"].value  != '' && form["LongitudeUpper"].value  != ''){
		if (form["LongitudeLower"].value < -0.02 || form["LongitudeLower"].value > 0.02 || form["LongitudeUpper"].value < -0.02 || form["LongitudeUpper"].value > 0.02){
			alert("Both Longitude fields must be between -0.02 and 0.02.")
			return false;
		}
	} else if ( (form["LongitudeLower"].value  != '' && form["LongitudeUpper"].value  == '') || (form["LongitudeLower"].value == '' && form["LongitudeUpper"].value  != '') ){
		alert("Both Longitude fields must be between -0.02 and 0.02.")
		return false;
	}
	return true;
}