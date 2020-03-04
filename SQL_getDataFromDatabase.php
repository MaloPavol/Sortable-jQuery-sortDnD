<?php
/*************************************
This demo uses Chrome Logger developed 
by Craig Campbell.
See infos and how to install under: 
https://craig.is/writing/chrome-logger
**************************************/
include '../_chromephp-master/ChromePhp.php';
$newSort = explode(",", $_GET["data"]); 

class fruit{
    public $fruitName;
    public $fruitRank;
    public $fruitId;
}

//DATABASE
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "fruits";
// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    ChromePhp::log("Connection failed:"  . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

// test if db already created
$fruitDatabaseExists = false;
$availableDatabases = mysqli_query($conn,"SHOW DATABASES"); 
    
while ($row = mysqli_fetch_array($availableDatabases)) { 
    if ($row["Database"]=="fruits") {
        $fruitDatabaseExists = true;
    }
}

//if the database does not exist yet
if (!$fruitDatabaseExists) {
    
    // Create database
    $sql = "CREATE DATABASE $dbname";
    if ($conn->query($sql) === TRUE) {   
        ChromePhP::log("Database created successfully");
    } else {
        ChromePhP::log("Error creating database: " . $conn->error);
    }

//TODO: Reduce unnecessary reconnecting.
$conn->close();
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    ChromePhP::log("Error creating database: " . $conn->error);
    die("Connection failed: " . $conn->connect_error);
}

    //create table
    $sql = "CREATE TABLE  MyFruits (
        fruitId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        fruitName VARCHAR(30) NOT NULL,
        fruitRank INT(2) NOT NULL,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
    
    if ($conn->query($sql) === TRUE) {
        ChromePhP::log("Table MyFruits created successfully");
    } else {
        ChromePhP::log("Error creating table: " . $conn->error);
    }

    // sql enter data
    $sql = "INSERT INTO MyFruits (fruitName, fruitRank) 
            VALUES ('apple', 1), ('banana', 2), ('strawberry', 3), ('orange', 4), ('pear', 5)";
    if ($conn->query($sql) === TRUE) {
        ChromePhP::log("New record created successfully");
    } else {
        ChromePhP::log("Error: " . $sql . $conn->error);
    }
    $conn->close();
}

//when the database exists...
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    ChromePhp::log("Connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT fruitName, fruitRank, fruitId FROM MyFruits";
$result = $conn->query($sql);
$list = array();
if ($result->num_rows > 0) {
    $list = $result->fetch_all();
    $result->free_result();
    $fruitArray = array();
    foreach($list as $value){
        $fruit = new fruit();
        $fruit->fruitName = $value[0];
        $fruit->fruitRank = $value[1];
        $fruit->fruitId = $value[2];
        array_push($fruitArray, $fruit);

    } 
     
    echo json_encode($fruitArray);
    
} else {
    ChromePhP::log("Loading data from database failed.");
}
$conn->close();

?>