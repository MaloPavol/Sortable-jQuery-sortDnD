<?php
/*************************************
This demo uses Chrome Logger developed 
by Craig Campbell.
See infos and how to install under: 
https://craig.is/writing/chrome-logger
**************************************/
include '../_chromephp-master/ChromePhp.php';
$postDataString = $_POST["data"];

$fruits = json_decode($postDataString);

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "fruits";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    ChromePhp::log("Connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

$values = "";

foreach($fruits as $value){
    $values .= "('".$value->fruitId."','".$value->fruitName."','".$value->fruitRank."'), ";

}
    $values = rtrim($values,", ");
    $sql = "INSERT INTO MyFruits (fruitId,fruitName,fruitRank) VALUES $values ON DUPLICATE KEY UPDATE fruitName=VALUES(fruitName),fruitRank=VALUES(fruitRank)";
    
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
        ChromePhp::log("(PHP logger) server sent to SQL-database:\r\n" . $sql);

    } else {
        echo "Error updating record: " . $conn->error;
        ChromePhp::log("Error updating record: " . $conn->error);
}

$conn->close();

?>