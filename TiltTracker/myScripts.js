//Hans Li and David Xue

//This method updates the message(value and color) of the website based on the tiltscore.
function updateMessage(score){
	if(score>69){
		document.getElementById("tiltScore").style.color = "red";
		document.getElementById("message").style.color = "red";
		document.getElementById("message").innerHTML = "You are tilted!";
	}
	else{
		document.getElementById("tiltScore").style.color = "black";
    document.getElementById("message").style.color = "black";
		document.getElementById("message").innerHTML = "You are not tilted!";
	}
}

//This method updates the tilt score on the main page.
function updateTilt(value){
  
	document.getElementById("tiltScore").innerHTML = value;
  updateMessage(value);
}

//Sets the timeMessage
function setTimeMessage(){
  document.getElementById(timeMessage).innerHTML = "It's been 6 hours since you last played a game. How are you feeling now?";
}

//Checks the summoner name entered
function validateTaskDesc() 
{
	if (document.getElementById("taskdesc").value === '')
	{
        // if user needs to fix this element, put cursor to it (reduce excise task)
        // and tell user how to fix it
        document.getElementById("taskdesc").focus();
        document.getElementById("taskdesc-note").innerHTML = "Please enter summoner name";
    }
    else 
    	document.getElementById("taskdesc-note").innerHTML = ""; // if nothing is wrong, let's make sure no left-over message 
}

//Checks the tilt value entered
function validateTiltDesc() 
{
  if (document.getElementById("tiltdesc").value === '')
  {
        // if user needs to fix this element, put cursor to it (reduce excise task)
        // and tell user how to fix it
        document.getElementById("tiltdesc").focus();
        document.getElementById("tiltdesc-note").innerHTML = "Please enter tilt value";
    }
    else 
      document.getElementById("tiltdesc-note").innerHTML = ""; // if nothing is wrong, let's make sure no left-over message 
}