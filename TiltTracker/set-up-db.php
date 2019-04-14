<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" />
  
  <title>State handling in PHP</title>    
</head>
<body>
  
  <div class="container">
    <h1>PHP and MySQL database</h1>
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="get">
      <input type="submit" name="btnaction" value="create" class="btn btn-light" />
      <input type="submit" name="btnaction" value="insert" class="btn btn-light" />   
      <input type="submit" name="btnaction" value="select" class="btn btn-light" />
      <input type="submit" name="btnaction" value="update" class="btn btn-light" />
      <input type="submit" name="btnaction" value="delete" class="btn btn-light" />
      <input type="submit" name="btnaction" value="drop" class="btn btn-light" />            
    </form>

<?php 
if (isset($_GET['btnaction']))
{	
   try 
   { 	
      switch ($_GET['btnaction']) 
      {
         case 'create': createTable(); break;
         case 'insert': insertData();  break;
         case 'select': selectData();  break;
         case 'update': updateData();  break;
         case 'delete': deleteData();  break;
         case 'drop':   dropTable();   break;      
      }
   }
   catch (Exception $e)       // handle any type of exception
   {
      $error_message = $e->getMessage();
      echo "<p>Error message: $error_message </p>";
   }   
}
?>



<?php
// require('connect-db.php');

// require: if a required file is not found, reqire() produces a fatal error, the rest of the script won't run
// include: if a required file is not found, include() throws a warning, the rest of the script will run
?>


<?php  
/*************************/
/** get data **/
function selectData()
{
   require('connect-db.php');

   // To prepare a SQL statement, use the prepare() method of the PDO object
   //    syntax:   prepare(sql_statement)

   // To execute a SQL statement, use the bindValue() method of the PDO statement object
   // to bind the specified value to the specified param in the prepared statement 
   //    syntax:   bindValue(param, value)
   // then use the execute() method to execute the prepared statement

   // Excute a SQL statement that doesn't have params
   $query = "SELECT * FROM users";
   $statement = $db->prepare($query); 
   $statement->execute();

   // fetchAll() returns an array for all of the rows in the result set
   $results = $statement->fetchAll();

   // closes the cursor and frees the connection to the server so other SQL statements may be issued 
   $statement->closecursor();

   foreach ($results as $result)
   {	
      echo $result['username'] . ":" . $result['tilt_score'] . "<br/>";
   }


   // Execute a SQL statement that has a param, use a colon followed by a param name
   $someid = "id1";
   $query = "SELECT * FROM users WHERE username = :someid";
   $statement = $db->prepare($query);
   $statement->bindValue(':someid', $someid);
   $statement->execute();

   // fetchAll() returns an array for all of the rows in the result set
   $results = $statement->fetchAll();

   // closes the cursor and frees the connection to the server so other SQL statements may be issued
   $statement->closecursor();

   foreach ($results as $result)
   {
      echo "select a row where username=id1 --->" . $result['username'] . ":" . $result['tilt_score'] . "<br/>";
   }

// a SELECT statement returns a result set in the PDOStatement object 
}
?>

<?php 
/*************************/
/** create table **/
function createTable()
{
   require('connect-db.php');
   $query = CREATE TABLE users (
      id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
      username VARCHAR(50) NOT NULL UNIQUE,
      password VARCHAR(255) NOT NULL,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      summoner VARCHAR(50),
   );


    echo "create";
   $statement = $db->prepare($query);
   $statement->execute();   
   $statement->closeCursor();

   $query = CREATE TABLE summoners (
      summoner VARCHAR(50) NOT NULL UNIQUE,
      tilt INT,
      lastgametime BIGINT
   );
   
   $statement = $db->prepare($query);
   $statement->execute();   
   $statement->closeCursor();
   echo " created";
}
?>


<?php 
/*************************/
/** drop table **/
function dropTable()
{
   require('connect-db.php');

//    $query = "DROP TABLE `web4640`.`courses`";
   $query = "DROP TABLE users";

   $statement = $db->prepare($query);
   $statement->execute();   
   $statement->closeCursor();
}
?>

<?php 
/*************************/
/** insert data **/
function insertData()
{
   require('connect-db.php');
   
   $users_id = "Haniism";
   $users_score = "100";
   
   $query = "INSERT INTO users (username, tilt_score) VALUES (:users_id, :users_score)";
   $statement = $db->prepare($query);
   $statement->bindValue(':users_id', $users_id);
   $statement->bindValue(':users_score', $users_score);
   $statement->execute();
   $statement->closeCursor();
}
?>


<?php
/*************************/
/** update data **/
function updateData()
{
   require('connect-db.php');
   
   $users_id = "id1";
   $users_score = "90";
    
   $query = "UPDATE users SET tilt_score=:users_score WHERE username=:users_id";
   $statement = $db->prepare($query);
   $statement->bindValue(':users_id', $users_id);
   $statement->bindValue(':users_score', $users_score);
   $statement->execute();
   $statement->closeCursor();   
}
?>

<?php
/*************************/
/** delete data **/
function deleteData()
{
   require('connect-db.php');
	
   $users_id = "newid_fr";
	
   $query = "DELETE FROM users WHERE username=:id";
   $statement = $db->prepare($query);
   $statement->bindValue(':id', $users_id);
   $statement->execute();
   $statement->closeCursor();
}
?>


  </div>
</body>
</html>
