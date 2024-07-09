<?php

require_once("../config.php");

header('Content-Type: application/json; charset=utf-8');

// Connect to the database
$mysqli = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName); 


$result = $mysqli->query("SELECT timestamp, temperature_inhouse, humidity_inhouse, temperature_outdoor, pressure_outdoor,(SELECT timestamp FROM measurement_results ORDER BY timestamp LIMIT 1) AS measurementsStarted FROM measurement_results ORDER BY id DESC LIMIT 1");

if ($result->num_rows > 0) {

  while($row = $result->fetch_assoc()) {
	echo (json_encode(array("timestamp" => "" . $row["timestamp"] ."", "temperatureInhouse" => "" . $row["temperature_inhouse"] ."", "humidityInhouse" => "" . $row["humidity_inhouse"] ."", "temperatureOutdoor" => "" . $row["temperature_outdoor"] ."", "pressureOutdoor" => "" . $row["pressure_outdoor"] ."", "firstMeasurement" => "" . $row["measurementsStarted"] ."")));
  }
}

?>