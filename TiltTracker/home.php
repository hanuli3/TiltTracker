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

    <script src="myScripts.js"></script>
    <title>Tilt Tracker</title>
        
  </head>

  <body onload="updateMessage(<?php $sql = "SELECT tilt FROM summoners WHERE summoner='$summoner_name'"; $result = $link->query($sql); echo $result->fetch_assoc()["tilt"];?>)">
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

    <h1 class = "bodyMid"><?php echo $_SESSION["summoner"]?></h1>

    <h2 class = "bodyMid">Tilt Level:</h2>

    <p id = "tiltScore"><?php $summoner_name = $_COOKIE['summoner']; $sql = "SELECT tilt FROM summoners WHERE summoner='$summoner_name' "; $result = $link->query($sql); echo $result->fetch_assoc()["tilt"];?></p>

    <p id = "message"></p>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" style="text-align:center;">
            <div class="form-group">
                <input hidden name="refresh" value = "true">
            </div>    
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Refresh">
            </div>
      </form>
    <br>
    <script type="text/javascript">
      //updateMessage(70);
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
  </body>
</html>



            