<?php

mysql_connect("localhost","donniema_testusr","test123") or die(mysql_error());
mysql_select_db("donniema_weatherlamp") or die(mysql_error());
$outline = "access_token=MyAccessToken&params=";

//Is the lamp on or off?
$query = "SELECT state FROM alarms";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
$state = $row['state'];

//At what time is the alarm?
$query = "SELECT alarmTime FROM alarms";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
$alarmTime = $row['alarmTime'];

//This block deals with the alarm feature of the clock
//Storing alarms in a SQL database and querying them to lauch an alarm


//make sure we're not getting someone up on GMT
date_default_timezone_set('America/New_York');

//all these echo statements are pretty inconsquential, just diagnostics 
echo "current time is: ";
$curTime = date("H:i:s");
echo $curTime;
echo "<br>";
echo "alarm is at: $alarmTime<br>";
echo $state;
echo "<br>";

if($curTime > $alarmTime){
	echo "it is past the alarm";
}
elseif($curTime < $alarmTime){
	echo "it is before the alarm";
}

//converting the pretty dates into UNIX timestamps
$unixCur = strtotime($curTime);
//the minus 1800 here starts the wake up process 30 min before the set time
//this is so that when the alarm time is reached the lamp is fully lit
$unixAlarm = strtotime($alarmTime)-1800;
$unixDiff = $unixCur - $unixAlarm;
$unixDiff = $unixDiff % 86400;
echo "<br>Unix TimeStamps <br>current time: ";
echo $unixCur;
echo "<br> alarm time: ";
echo $unixAlarm;
echo "<br>difference: ";
echo $unixDiff;
echo"<br>";


function setColor($unixDiff)
{
	echo "setting color";
	echo $unixDiff;
	echo "<br>";
	$outline = "access_token=MyAccessToken&params=";
	$query = "SELECT color FROM color";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	$theColorVal = $row['color'];
	$theColorVal = sprintf("%09s",$theColorVal);
	$colors = str_split($theColorVal,3);
	$red = (int) $colors[0];
	$green = (int) $colors[1];
	$blue = (int) $colors[2];

	//want to make sure it's pas time for the alarm and not too far after the alarm
	if(($unixDiff > 0)&&($unixDiff < 2000)){
		// getting iterations from server
		echo "gettting iterations <br>";
		$query = "SELECT iteration FROM alarms";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);

		//Turns the Lamp on 
		$iteration = $row['iteration'];
		echo "the iteration: ";
		echo $iteration;
		// 30 intervals because the cron job for this script runs every minute so 30 iterations
		// should have it at full brightness by the alarm time
		if($iteration < 30){
			// this $brite variable is the brightness we'll send to the core
			$brite = $iteration + 1;
			$brite = (int) ((pow($brite,2)/pow(30,2))*254)+1;
			echo $theMax;
			echo "<br>";
			$scaleFactor = $brite / 255;
			echo $scaleFactor;
			echo "<br>";
			$red =(int)$red * $scaleFactor;
			$blue =(int)$blue * $scaleFactor;
			$green =(int)$green * $scaleFactor;

			echo "<br>";
			echo $brite; 
			echo "<br>";


			$redStr = strval($red);
			$redStr = sprintf("%03d", $redStr);
			$greenStr = strval($green);
			$greenStr = sprintf("%03d", $greenStr);
			$blueStr = strval($blue);
			$blueStr = sprintf("%03d", $blueStr);

			$theColorVal = $redStr . $greenStr . $blueStr;
			echo "adjusted color val";
			echo $theColorVal;
			$iteration = $iteration + 1;
			$query = "UPDATE alarms SET iteration = $iteration WHERE state = 1";
			mysql_query($query);
			
		}
	}
	if($iteration == 30 && $unixDiff > 2000){
		$query = "UPDATE alarms SET iteration = 0 WHERE state = 1";
		mysql_query($query);
		
	}
	

	$final = $outline . $theColorVal;
	echo "<br>";
	echo $final;

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,"https://api.spark.io/v1/devices/50ff6d065067545629500587/setColor");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$final);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec ($ch);
	curl_close ($ch);
}


// This block deals with updating the color
if(($unixAlarm % 86400) == 14400){
	echo "alarm at midnight effectively turns off alarm";
}
elseif(($unixDiff > 0)&&($unixDiff < 2000)){
	echo "changing state <br>";
	$query = "UPDATE alarms SET state = '1'";
	mysql_query($query);
	$state = 1;
	setColor($unixDiff);
}
elseif($state == 0)
{
	echo "turning off <br>";
	$final = $outline;
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,100);
	curl_setopt($ch, CURLOPT_URL,"https://api.spark.io/v1/devices/50ff6d065067545629500587/powerOff");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$final);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec ($ch);
	curl_close ($ch);
	$query = "UPDATE alarms SET state = '2'";
	mysql_query($query);
}
elseif($state == 2){
	//
	echo "Should keep device from turning off once off"
}
else
{
	setColor($unixDiff);
}



?>