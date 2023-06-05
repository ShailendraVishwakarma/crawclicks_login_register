
<?php
$servername = "localhost";
$username = "root";
$password = "";
$database="crowlclick";


// Create connection
$con =mysqli_connect($servername, $username, $password,$database);

// Check connection
if($con){
  echo"";
}
else{
  echo"DB not Connected";
}
?>