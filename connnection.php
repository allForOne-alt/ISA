<?php 
$serverName = "localhost";
$userName = "root";
$password = "";
$api_key = "API_KEY";
$conn = mysqli_connect($serverName, $userName, $password);

if($conn) {
    // echo "Connection was successful<br>";
}
else {
    // echo "Failed to connect".mysqli_connect_error();
}

$createDatabase = "CREATE DATABASE IF NOT EXISTS prototype2";
if(mysqli_query($conn, $createDatabase)) {
    // echo "Database already exists if not created"; 
}
else {
    // echo "Failed to create database";
}

mysqli_select_db($conn, 'prototype2');

$createTable = "CREATE TABLE IF NOT EXISTS weather (
    city VARCHAR(100) NOT NULL,
    humidity FLOAT NOT NULL,
    wind FLOAT NOT NULL,
    pressure FLOAT NOT NULL,
    temperature FLOAT NOT NULL,
    wind_direction FLOAT NOT NULL,
    weather_description VARCHAR(255) NOT NULL,
    weather_less2hr DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $createTable)) {
    // echo "Table Created or already exists";
}
else {
    // echo "Failed to create table:;
}

if(isset($_GET['q'])) {
    $cityName = $_GET['q'];   
}
else {
    $cityName = "Janakpur";
}

$selectAllData = "SELECT * FROM weather WHERE city = '$cityName'
                AND weather_less2hr >= DATE_SUB(NOW(), INTERVAL 2 HOUR)
                ORDER BY weather_less2hr DESC LIMIT 1";

$result = mysqli_query($conn, $selectAllData);
if($result === false) {
    echo json_encode(["error" => "Query failed :".mysqli_error($conn)]);
    mysqli_close($conn);
    exit();
}


if(mysqli_num_rows($result)==0) {
    $url = "https://api.openweathermap.org/data/2.5/weather?q={$cityName}&appid={$api_key}&units=metric";
    $response = file_get_contents($url);

    if($response === false ) {
        echo json_encode(["erro" => "Unable to fetch from openWeather". mysqli_error($conn)]); 
        mysqli_close($conn);
        exit();
    }

    $data = json_decode($response, true);
    
    $humidity = $data['main']['humidity'];
    $wind = $data['wind']['speed'];
    $pressure = $data['main']['pressure'];
    $temperature = $data['main']['temp'];
    $wind_direction = $data['wind']['deg'];
    $weather_description = $data['weather'][0]['description'];
    $city_name = $data['name'];

    $insertData = "INSERT INTO weather (city, humidity, wind, pressure, temperature, wind_direction, weather_description, weather_less2hr) 
                   VALUES ('$city_name', '$humidity', '$wind', '$pressure', '$temperature', '$wind_direction', '$weather_description', NOW())";
    
    if(!mysqli_query($conn, $insertData)) {
        echo json_encode(["error" => "Failed to insert data: " . mysqli_error($conn)]);
        exit();
    }
    
    //newly inserted data 
    $result = mysqli_query($conn, $selectAllData);
    if($result === false) {
        echo json_encode(["error" => "Failed to insert : ". mysqli_error($conn)]);
        mysqli_close($conn);
        exit();;
    }
}

//fetch data from ->->"database"<-<- 
$row = mysqli_fetch_assoc($result);

if(!$row) {
    echo json_encode(["error" => "No data found for city: " . $cityName]);
    exit();
}

$json_data = json_encode($row);
header('Content-Type: application/json');
echo $json_data;

mysqli_close($conn);

?>
