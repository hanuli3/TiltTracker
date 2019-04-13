//Hans Li and David Xue

//This method updates the message(value and color) of the website based on the tiltscore.
function updateMessage(score){
	if(score>75){
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

//Adds new friend to friends list
function addRow()
{
	 // use getElementById() method
	 // If the element is found, the method will return the element as an object 	 
	 // If the element is not found, a null is returned	  
	 var desc = document.getElementById("taskdesc").value;
	 var tilt = 100;
	 var status = "TILTED!";
	 var removeoption = "<input type=button value=' X ' onClick='delRow()'>";


     // Another way to validate taskdesc is to have the checking here 
     // (instead of using an addEventListener() and the validateTaskDesc() function above).       
     if (desc === '')    // check if appropriate data are entered
     {
     	document.getElementById("taskdesc").focus();
     	document.getElementById("taskdesc-note").innerHTML = "Please enter summoner name";
     } 
     else
     {
     	document.getElementById("taskdesc-note").innerHTML = ""; 

        var rowdata = [desc, tilt, status, removeoption];

           // clear data entries (in the form)
           document.getElementById("taskdesc").value = "";

           // find a <table> element to add row to (in this example, a table with id="todoTable")
           var tableRef = document.getElementById("todoTable");

           // create an empty <tr> element and add it to the table
           // using insertRow(index) method
           var newRow = tableRef.insertRow(tableRef.rows.length);    // table_object.rows.length returns the number of <tr> elements in the collection
           // add event listener, on mouseover, set row index. This will be used when deleting a row
           newRow.onmouseover = function() { 
        	  // rowIndex returns the position of a row in the rows collection of a table
        	  tableRef.clickedRowIndex = this.rowIndex;     
        	};    
           // alternatively, use data-index to store index of the line 
           //  (note: data-* attributes can store arbitrary data with elements)
           // eg: <div id="elem" data-index=3></div>

           var newCell = "";       
           var i = 0;
           // insert new cells (<td> elements) at the 1st, 2nd, 3rd, 4th position of the new <tr> element
           // using insertCell(method) method        	      
           while (i < 4)
           {
           	newCell = newRow.insertCell(i);
           	newCell.innerHTML = rowdata[i];
           	newCell.onmouseover = this.rowIndex;
           	i++;
       }
    }
}  

//Removes friend from friend list and gives a warning before removing
function delRow()
{
  // since deletion action is unrecoverable, add hesitation to minimize/avoid user error 
  if (confirm("Press OK to delete. This action is unrecoverable.") == true)   
  	document.getElementById("todoTable").deleteRow(document.getElementById("todoTable").clickedRowIndex);
}