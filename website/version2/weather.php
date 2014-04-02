<?php session_start(); ?>
<!DOCTYPE HTML>
<?php
  mysql_connect("localhost","donniema_testusr","test123") or die(mysql_error());
  mysql_select_db("donniema_weatherlamp") or die(mysql_error());
  

  if ($_POST["alarm"] == null)
    $a = 9;
  else
     $query = "DELETE FROM alarms";
     mysql_query($query);
     $alarmTime = $_POST["alarm"];
     $alarmState = 1;
     $alarmTime = mysql_real_escape_string($alarmTime);
     $alarmState = mysql_real_escape_string($alarmState);
     $query = "INSERT INTO alarms (alarmTime,state) VALUES ('$alarmTime','$alarmState')";
     mysql_query($query); 

  if ($_POST["alarmVal"] == null)
  {
    $a = 3;
  }
  else{
    $alarmState = $_POST["alarmVal"];
    if ($alarmState == "on")
      $alarmState = 255;
    else
      $alarmState = 0;
    $outline = "access_token=4e5a333571329fd3741c72a2f2aec5cf3b183258&params=";
    $alarmState = sprintf("%03d",$alarmState);
    $final = $outline . $alarmState;
    echo $final;
      // this is the cURL code for transmitting the brightness to the core
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,"https://api.spark.io/v1/devices/50ff6d065067545629500587/setBrite");
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS,$final);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $result = curl_exec ($ch);
      curl_close ($ch);
  }

   
$query = "SELECT weather FROM theWeather";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
$theWeather = $row['weather'];
$query = "SELECT temp FROM theWeather";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
$temp = $row['temp'];

  $_SESSION['temp_f'] = $temp;
  $_SESSION['weather'] = $theWeather;

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
  $_SESSION['alarmTime'] = $time;
  $_SESSION['state'] = $strState;
  ?>




<html>
<head>
<link type = "text/css" rel = "stylesheet" href = "weather.css">
<link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400' rel='stylesheet' type='text/css'>
</head>
<body>
<h1>
Weatherlamp Dashboard
</h1>
<div class = information>
  <p class="conditions">
      Temperature is : 
      <?php echo $_SESSION['temp_f'];?> 
      <br>
      Weather is : 
      <?php echo $_SESSION['weather'];?>
  </p>
  <p class="alarms">
      Alarm set for: <?php echo $_SESSION['alarmTime'];?>
      <br>
      Alarm is currently: <?php echo $_SESSION['state'];?>
  </p>
<a href = "/secure.php">Settings</a>
</div>
</body>
</html>