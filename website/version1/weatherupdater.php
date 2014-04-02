
<?php
$url = "http://api.wunderground.com/api/20942639d54119db/geolookup/conditions/q/NC/Chapel_Hill.json";
  $json_string = file_get_contents($url);
  $parsed_json = json_decode($json_string);
  $location = $parsed_json->{'location'}->{'city'};
  $temp_f = $parsed_json->{'current_observation'}->{'temp_f'};
  $weather = $parsed_json->{'current_observation'}->{'weather'};
  $_SESSION['temp_f'] = $temp_f;
  $_SESSION['weather'] = $weather;

$query = "DELETE FROM theWeather";
mysql_query($query);
// $temp_f = mysql_real_escape_string($temp_f);
$weather = mysql_real_escape_string($weather);
$query = "INSERT INTO theWeather (temp,condition) VALUES ('$temp_f','$weather')";
mysql_query($query); 

echo "complete";

  ?>