<?php

require_once("../config.php");

header('Content-Type: application/json; charset=utf-8');


$timespan = 0;
$timespanOnLetters = "year";

if (isset($_GET["timespan"]))
{

if (intval($_GET["timespan"]) < 0 || intval($_GET["timespan"]) > 3)
{
	http_response_code(401);
	return;
}

	$timespan = intval($_GET["timespan"]);
}



// Connect to the database
$mysqli = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName); 


	$timestampNow = time();
	$timestampDesired = 0;
	
	switch ($timespan) {
    case 0:
        // year
		
		$timespanOnLetters = "Vuoden";
		
		$timestampDesired = time() - 31536000;
		
        break;
    case 1:
	
		// month
		
		$timespanOnLetters = "Kuukauden";
		
        $timestampDesired = time() - 2628000;
		
        break;
    case 2:
	
		// week
       
	   $timespanOnLetters = "Viikon";
	   
	   $timestampDesired = time() - 604800;
	   
        break;
		
	case 3:
	
		// day
		
		$timespanOnLetters = "Päivän";
		
		$timestampDesired = time() - 86400;
		
		break;
	}
		


	$stmt = $mysqli->prepare("SELECT cast(AVG(temperature_inhouse) as decimal(6, 1)) AS temperature_inhouse_average, cast(AVG(humidity_inhouse) as decimal(6, 0)) AS humidity_inhouse_average, cast(AVG(temperature_outdoor) as decimal(6, 1)) AS temperature_outdoor_average, cast(AVG(pressure_outdoor) as decimal(6, 0)) AS pressure_outdoor_average FROM measurement_results WHERE timestamp > ? AND timestamp < ? LIMIT 1");
	$stmt->bind_param('ii', $timestampDesired, $timestampNow);

	$stmt->execute();
	//$stmt->store_result();
	$result = $stmt->get_result();
	$data = $result->fetch_all(MYSQLI_ASSOC);
	
$measurements = array();

if ($data) {
	
 foreach($data as $row) {
	$measurementJson = array("timestampNow" => "" . time() ."","timespanPretty" => "" . $timespanOnLetters ."", "temperatureInhouseAverage" => "" . $row["temperature_inhouse_average"] ."", "humidityInhouseAverage" => "" . $row["humidity_inhouse_average"] ."", "temperatureOutdoorAverage" => "" . $row["temperature_outdoor_average"] ."", "pressureOutdoorAverage" => "" . $row["pressure_outdoor_average"] ."");
  }
}
echo json_encode($measurementJson);

?>