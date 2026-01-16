<?php 
$serverName = "localhost";
$userName = "root";
$password = "";
$api_key = "8e67b2065f4c070146565abbdcbc8616";
$conn = mysqli_connect($serverName, $userName, $password);
if($conn) {
    echo "Connection was successful<br>";
}
else {
    echo "Failed to connect".mysqli_connect_error();
}
// $createDatabase = "CREATE DATABASE IF NOT EXISTS prototype2";
// if(mysqli_query($conn, $createDatabase)) {
//     echo "Database already exists if not created <br>";
// }
// else {
//     echo "Failed to create database<br>".mysqli_connect_error();
// }

mysqli_select_db($conn, 'prototype2');

// $createTable = "CREATE TABLE IF NOT EXISTS weather (
// /*
//   so basically the data from our table will be later converted to JSON format
//   and then that data will be sent to the weather_forecast_api
//  */
//     humidity FLOAT NOT NULL,
//     wind FLOAT NOT NULL,
//     pressure FLOAT NOT NULL
// );";

// if (mysqli_query($conn, $createTable)) {
//     echo "Table Created or already exists <br>";
// }
// else {
//     echo "Failed to create database <br>".mysqli_connect_error();
// }

if(isset($_GET['q'])) {
    /*
    so basically this checks if the user passed a city name, for eg : if the user typed
    jhapa then q = jhapa if not then q will default to kathmandu.
    */
    $cityName = $_GET['q'];   
    echo $cityName;
}
else {
    $cityName = "Kathmandu";
}

$selectAllData = "SELECT * FROM weather where city = '$cityName'";
$result = mysqli_query($conn, $selectAllData);
if(mysqli_num_rows($result)==0) {
    $url = "https://api.openweathermap.org/data/2.5/weather?q=${cityName}&appid=${api_key}&units=metric";
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    $humidity = $data['main']['humidity'];
    $wind = $data['wind']['speed'];
    $pressure = $data['main']['pressure'];

$insertData = "Insert into weather (humidity, wind , pressure) VALUES ('$humidity', '$wind','$pressure')";
    
    if(mysqli_query($conn, $insertData)) {
        echo "Data inserted <br>";
    }
    else {
        echo "Failed to insert data".mysqli_error($conn);
    }
}

?>