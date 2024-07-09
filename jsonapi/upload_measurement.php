<?php

require_once("../config.php");

header('Content-Type: application/json; charset=utf-8');

// Connect to the database
$mysqli = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName); 

// http://localhost/project_greenhouse/upload_measurement.php?timestamp=3&temperature_inhouse=3&humidity_inhouse=3&temperature_outdoor=5&humidity_outdoor=41

$jpayl = json_decode(file_get_contents("php://input"));

if (!isset($jpayl->secretKey) || !isset($jpayl->timestamp) || !isset($jpayl->temperature_inhouse) || !isset($jpayl->humidity_inhouse) || !isset($jpayl->temperature_outdoor) || !isset($jpayl->pressure_outdoor))
{
	echo (json_encode(array("status" => "fail", "error" => "1", "description" => "Missing required parameter")));
	return;
}


if ($jpayl->secretKey != $secretKey)
{
	echo $jpayl->secretKey;
	echo (json_encode(array("status" => "fail", "error" => "2", "description" => "Wrong secretKey")));
	return;
}
$ts = $jpayl->timestamp;
$tih = $jpayl->temperature_inhouse;
$hih = $jpayl->humidity_inhouse;
$tod = $jpayl->temperature_outdoor;
$pod = $jpayl->pressure_outdoor;



$sqlq = $mysqli->prepare("INSERT INTO measurement_results (timestamp, temperature_inhouse, humidity_inhouse, temperature_outdoor, pressure_outdoor) VALUES (?, ?, ?, ?, ?);");
$sqlq->bind_param("ididi", $ts, $tih, $hih, $tod, $pod);
$sqlq->execute();

echo (json_encode(array("status" => "ok")));



?>