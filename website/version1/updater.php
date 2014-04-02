
<?php
function convertTemp($temp)
{
	$adjTemp = $temp*7.68;
	$adjTemp = $adjTemp + 75;
	$adjTemp = $adjTemp % 768;

	echo $adjTemp;
	echo "<br>";

	$red = 0;
	$green = 0;
	$blue = 0;

	if ($adjTemp <= 255)
	{
		$red = 255 - $adjTemp;
		$blue = $adjTemp;
	}
	elseif ($adjTemp <= 511)
	{
		$blue = 255 - ($adjTemp - 256);
		$green = $adjTemp - 256;

	}

	else
	{
		$green = 255 - ($adjTemp - 512);
		$red = $adjTemp - 512;
	}
	$theMax = max($red, $green, $blue);
	echo $theMax;
	echo "<br>";
	$scaleFactor = 255 / $theMax;
	echo $scaleFactor;
	echo "<br>";
	$red =(int)$red * $scaleFactor;
	$blue =(int)$blue * $scaleFactor;
	$green =(int)$green * $scaleFactor;

	echo $red,' ',$green,' ', $blue;
	echo "<br>";

	$redStr = strval($red);
	$redStr = sprintf("%03d", $redStr);
	$greenStr = strval($green);
	$greenStr = sprintf("%03d", $greenStr);
	$blueStr = strval($blue);
	$blueStr = sprintf("%03d", $blueStr);

	$returnVal = $redStr . $greenStr . $blueStr;
	echo $returnVal; 
	echo "<br>";

	return $returnVal;


}


  mysql_connect("localhost","donniema_testusr","test123") or die(mysql_error());
  mysql_select_db("donniema_weatherlamp") or die(mysql_error());

$url = "http://api.wunderground.com/api/20942639d54119db/geolookup/conditions/q/NC/Canton.json";
  $json_string = file_get_contents($url);
  $parsed_json = json_decode($json_string);
  $location = $parsed_json->{'location'}->{'city'};
  $temp_f = $parsed_json->{'current_observation'}->{'temp_f'};
  $weather = $parsed_json->{'current_observation'}->{'weather'};
  $_SESSION['temp_f'] = $temp_f;
  $_SESSION['weather'] = $weather;

  echo $temp_f;
  echo "<br>";
  echo $weather;
  echo "<br>";

$colorVal = convertTemp($temp_f);
  // $colorVal = "001002003";

$query = "DELETE FROM theWeather";
mysql_query($query);
$query = "INSERT INTO theWeather (temp,weather) VALUES ('$temp_f','$weather')";
mysql_query($query); 

echo "complete <br>";

$query = "SELECT weather FROM theWeather";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
$theWeather = $row['weather'];

echo $theWeather;
echo "<br>";
$colorVal = mysql_escape_string($colorVal);
$query = "UPDATE color SET color = $colorVal WHERE override = '0'";
mysql_query($query);

$query = "SELECT color FROM color";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
$theColorVal = $row['color'];
$theColorVal = sprintf("%09s",$theColorVal);

echo $theColorVal;

  ?>