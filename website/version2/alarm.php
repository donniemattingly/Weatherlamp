<html>
<body>
<?php
  mysql_connect("localhost","donniema_testusr","test123") or die(mysql_error());
  mysql_select_db("donniema_weatherlamp") or die(mysql_error());
  

  if ($_POST["alarm"] == null)
    echo "No Alarm";
  else
     $query = "DELETE FROM alarms";
     mysql_query($query);
     $alarmTime = $_POST["alarm"];
     $alarmState = $_POST["alarmVal"];
     if ($alarmState == "on")
      $alarmState = 1;
     else
      $alarmState = 0;
     $alarmTime = mysql_real_escape_string($alarmTime);
     $alarmState = mysql_real_escape_string($alarmState);
     $query = "INSERT INTO alarms (alarmTime,state) VALUES ('$alarmTime','$alarmState')";
     mysql_query($query); 


   
  $url = "http://api.wunderground.com/api/20942639d54119db/geolookup/conditions/q/NC/Chapel_Hill.json";
  $json_string = file_get_contents($url);
  $parsed_json = json_decode($json_string);
  $location = $parsed_json->{'location'}->{'city'};
  $temp_f = $parsed_json->{'current_observation'}->{'temp_f'};
  $weather = $parsed_json->{'current_observation'}->{'weather'};
  echo "Current temperature in ${location} is: ${temp_f}<br>";
  echo "Weather is ${weather}<br>" ;

  $File = "testfile.txt";
  $Handle = fopen($File,'w');
  $Data = $temp_f;
  fwrite($Handle,$Data);
  $Data = $weather;
  fwrite($Handle,$Data);
  fclose($Handle);

  $query = "SELECT alarmTime FROM alarms";
  $result = mysql_query($query);
  $row = mysql_fetch_array($result);
  $time = $row['alarmTime'];
  
  $query = "SELECT state FROM alarms";
  $result = mysql_query($query);
  $row = mysql_fetch_array($result);
  $state = $row['state'];

  if ($state == 0)
    $strState = "off";
  else
    $strState = "on";

  echo " Alarm is set for: ${time}<br>";
  echo " Alarm is ${strState}<br>";
  ?>



<a href = "/secure.php">Settings</a>
</body>
</html>