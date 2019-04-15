<!-- Hans Li and David Xue -->
<?php
session_start();
if(!isset($_SESSION["loggedin"])){
  header("location: login.php");
}
if(!isset($_COOKIE["summoner"])){
  echo '<script language="javascript">';
  echo 'alert("Cookie has expired, Please login again.")';
  echo '</script>';
  header("location:login.php");
}
require_once "config.php"; 
include("riot-methods.php");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">  
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="styles/main.css">

    <title>Tilt Tracker</title>
        
  </head>
  <script src="myScripts.js"></script>
  <body onload="showfriends()">
    <header>

      <nav class="navbar navbar-expand-md bg-dark navbar-dark">
        <a class="navbar-brand" href="home.php">Tilt Tracker</a>    
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="collapsibleNavbar">   
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="about.php">About</a>
            </li>                        
            <li class="nav-item"> 
              <a class="nav-link" href="friends.php">Friends</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">Logout</a>
            </li>           
          </ul>
        </div>  
      </nav>
    </header>

  <div class="container">
    <h1>My Friends</h1>
  
    <form name="mainform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" >
      <div class="form-group">
        <label for="taskdesc">Summoner Name</label>
        <input style="width:30%;" type="text" id="taskdesc" class="form-control" name="friend" />
        <span class="error" id="taskdesc-note"></span>
      </div>        
      <input type="submit" class="btn btn-light" id="add" value="Add Friend" onclick="showfriends()"/> 
    </form>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get"">
            <div class="form-group">
                <input hidden name="refreshfriends" value = "true">
            </div>    
            <div class="form-group">
                <input type="submit" class="btn btn-primary">
            </div>
    </form> 
    <br/>
      
    <div id="todo">
      <table id="todoTable" class="table" >
        <thead>   <!-- set table headers -->
          <tr>
            <th>Friends</th>
            <th>Tilt Level</th>
            <th>Status</th>
            <th>Remove Friend</th>
          </tr> 
        </thead>      
        <?php 
    $result = getFriendsArray($_COOKIE["summoner"]);
    if (sizeof($result) > 0){
      foreach ($result as $friend){
        if($friend != ""){
          $ftilt = getTilt($friend);
          echo "<tr><td>" . $friend. "</td><td>" . $ftilt . "</td><td> Tilted! </td><td> <input type=button value=' X ' onClick='delRow()'></td></tr>";
        }
      }
      echo "</table>";
    }
  else{
    echo "no friends :(";
  }
  ?>      
    </div>
  </div>



  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
  <script>
  function checkInput(){
  var desc = document.getElementById("taskdesc").value;
   if (desc === '')    // check if appropriate data are entered
     {
     	document.getElementById("taskdesc").focus();
     	document.getElementById("taskdesc-note").innerHTML = "Please enter summoner name";
     } 
  }

//Removes friend from friend list and gives a warning before removing
function delRow()
{
  // since deletion action is unrecoverable, add hesitation to minimize/avoid user error 
  if (confirm("Press OK to delete. This action is unrecoverable.") == true)   
  	document.getElementById("todoTable").deleteRow(document.getElementById("todoTable").clickedRowIndex);
}

  </script>
  </body>
</html>  