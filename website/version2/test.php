<html>
<h3>Weather</h3>
  <?php
  $json_string = file_get_contents("http://api.wunderground.com/api/20942639d54119db/geolookup/conditions/q/NC/Chapel_Hill.json");
  $parsed_json = json_decode($json_string);
  $location = $parsed_json->{'location'}->{'city'};
  $temp_f = $parsed_json->{'current_observation'}->{'temp_f'};
  $weather = $parsed_json->{'current_observation'}->{'weather'};
  echo "Current temperature in ${location} is: ${temp_f}\n";
  echo "Weather is ${weather}" ;
?>
</html>