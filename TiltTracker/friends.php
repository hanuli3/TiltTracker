<!-- Hans Li and David Xue -->
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
  <body>
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
  
    <form name="mainform" >
      <div class="form-group">
        <label for="taskdesc">Summoner Name</label>
        <input style="width:30%;" type="text" id="taskdesc" class="form-control" name="desc" />
        <span class="error" id="taskdesc-note"></span>
      </div>        
      <input type="button" class="btn btn-light" id="add" value="Add Friend" onclick="addRow()"/> 
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
        <!-- JS will dynamically create add new row upon form submission -->      
      </table> 
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
  </body>
</html>
          
<?php
session_start();
if(!isset($_SESSION["loggedin"])){
  header("location: login.php");
  exit;
}
?>       