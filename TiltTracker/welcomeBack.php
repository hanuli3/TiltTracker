<!-- Hans Li and David Xue -->
<?php session_start(); require_once "config.php"; include("riot-methods.php")?>
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

  <body onload="setTimeMessage()">
    <header>
      <nav class="navbar navbar-expand-md bg-dark navbar-dark">
        <a class="navbar-brand" href="home.php">Tilt Tracker</a>    
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
          <span class="navbar-toggler-icon"></span  >
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
      <h1 class = "bodyMid">Welcome Back <?php if(isset($_COOKIE["summoner"])) {echo $_COOKIE["summoner"];} else {header("location: logout.php");}?> !</h1>

      <p id="timeMessage"style="text-align:center;">It's been 6 hours since you last played a game. How are you feeling now?</p>
    
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="text-align:center;">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Previous Tilt Level: <?php $sql = "SELECT tilt FROM users WHERE summoner='$summoner_name' "; $result = $link->query($sql); echo $result->fetch_assoc()["tilt"];?> </label>
                <input style="width:270px; margin: 0 auto;" type="number" min ="0" max="100" class="form-control" name="settilt" placeholder = "Please Enter a Number 0 to 100">
            </div>    
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Set Tilt Level">
            </div>
      </form>

    <script type="text/javascript">
    //Sets the tilt score. Checks for blank or incorrect input. If correct, goes to the main page.
      var setTilt = function(){
        var xhr = new XMLHttpRequest();
        var url = "welcome-back.php";
        xhr.open("POST", url, true );

        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.setRequestHeader("Content-Length", params.length); // POST request MUST have a Content-Length header (as per HTTP/1.1)

    httpc.onreadystatechange = function() { //Call a function when the state changes.
        if(httpc.readyState == 4 && httpc.status == 200) { // complete and no errors
            alert(httpc.responseText); // some processing here, or whatever you want to do with the response
        }
    };
    httpc.send(params);


        if (document.getElementById("tiltdesc").value === ''){
          document.getElementById("tiltdesc").focus();
          document.getElementById("tiltdesc-note").innerHTML = "Please enter tilt score";
        }
        else if(document.getElementById("tiltdesc").value >100 ||document.getElementById("tiltdesc").value < 1){
          document.getElementById("tiltdesc").focus();
          document.getElementById("tiltdesc-note").innerHTML = "Please enter a score 1 to 100";
        }
        else{
          window.location.href = "template.html";
          document.getElementById(tiltScore).innerHTML = "80";
        }
      }

      //Sets the timeMessage
      function setTimeMessage(){
        document.getElementById(timeMessage).innerHTML = "It's been 6 hours since you last played a game. How are you feeling now?";
      }
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
  
  </body>         
</html>
