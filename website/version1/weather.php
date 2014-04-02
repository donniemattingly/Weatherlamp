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
     $alarmState = $_POST["alarmVal"];
     if ($alarmState == "on")
      $alarmState = 1;
     else
      $alarmState = 0;
     $alarmTime = mysql_real_escape_string($alarmTime);
     $alarmState = mysql_real_escape_string($alarmState);
     $query = "INSERT INTO alarms (alarmTime,state) VALUES ('$alarmTime','$alarmState')";
     mysql_query($query); 


   
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

  $query = "SELECT color FROM color";
  $result = mysql_query($query);
  $row = mysql_fetch_array($result);
  $theColorVal = $row['color'];
  $theColorVal = sprintf("%09s",$theColorVal);

  $_SESSION['color'] = $theColorVal;
  ?>




<html>
<head>
<link type = "text/css" rel = "stylesheet" href = "weather.css">
<link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400' rel='stylesheet' type='text/css'>
<script>
function changeBackground()
  {
    var colorStr;
    <?php echo $_SESSION['color'];?>
    
    var red = colorStr.substring(0,3);
    var green = colorStr.substring(3,6);
    var blue = colorStr.substring(6,9);
    red = parseInt(red);
    green = parseInt(green);
    blue = parseInt(blue);

    function componentToHex(c) {
        var hex = c.toString(16);
        return hex.length == 1 ? "0" + hex : hex;
    }

    function rgbToHex(r, g, b) {
        return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
    }    
    var hexVal = rgbToHex(red,green,blue);
    // document.body.style.background = (red,green,blue);
    document.body.style.background = hexVal;
  }
</script>

<BODY onload="changeBackground();">
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