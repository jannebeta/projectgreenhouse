<?php

require_once("../config.php");

header('Content-Type: application/json; charset=utf-8');

$amount = 10; // default amount
$from = -1;
$to = -1;
$order = "ntoo";

if (isset($_GET["amount"]))
{
	$amount = intval($_GET["amount"]);
}
if (isset($_GET["from"]) && isset($_GET["to"]))
{
	$from = $_GET["from"];
	$to = $_GET["to"];
}
if (isset($_GET["order"]))
{
	if ($_GET["order"] == "ntoo" || $_GET["order"] == "oton")
	{
		$order = $_GET["order"];
	}
}




// Connect to the database
$mysqli = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName); 

if ($from != -1 && $to != -1)
{

	$stmt = $mysqli->prepare("SELECT timestamp, temperature_inhouse, humidity_inhouse, temperature_outdoor, pressure_outdoor FROM measurement_results WHERE timestamp >= ? AND timestamp <= ? ORDER BY timestamp " . ($order == "ntoo" ? "DESC" : "") . " LIMIT ?");

	$stmt->bind_param('iii', $from, $to, $amount);
	

	
}
else if ($amount != 10)
{
	$stmt = $mysqli->prepare("SELECT timestamp, temperature_inhouse, humidity_inhouse, temperature_outdoor, pressure_outdoor FROM measurement_results ORDER BY timestamp " . ($order == "ntoo" ? "DESC" : "") . " LIMIT ?");
	$stmt->bind_param('i', $amount);
}
else
{
$stmt = $mysqli->prepare("SELECT timestamp, temperature_inhouse, humidity_inhouse, temperature_outdoor, pressure_outdoor FROM measurement_results ORDER BY timestamp " . ($order == "ntoo" ? "DESC" : "") . " LIMIT 10");
}

	$stmt->execute();
	
	
	$result = $stmt->get_result();
	$data = $result->fetch_all(MYSQLI_ASSOC);
	

	
$measurements = array();

if ($data) {
	
 foreach($data as $row) {
	 $pressureSealevel = round($row["pressure_outdoor"] / pow((1.0 - (90.1/44330.0)), 5.255), 0);
	 
	array_push($measurements, array("timestamp" => $row["timestamp"], "temperatureInhouse" => $row["temperature_inhouse"], "humidityInhouse" => $row["humidity_inhouse"], "temperatureOutdoor" =>  $row["temperature_outdoor"], "pressureOutdoor" => $row["pressure_outdoor"], "pressureOutdoor_sealevel" => $pressureSealevel));
  }
}

$measurementJson = array("timestampNow" => time(), "measurementCount" => count($data), "measurements" => $measurements);
echo json_encode($measurementJson);

?>