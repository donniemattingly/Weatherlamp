
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
	echo "<br";

	return $returnVal;


}
$color =  $_POST["color"];

echo $color;
echo "<br>";

$outline = "access_token=4e5a333571329fd3741c72a2f2aec5cf3b183258&params=";

$temp = floatval($color);
$RGB = convertTemp($temp);

$final = $outline . $RGB;

echo $final;


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"https://api.spark.io/v1/devices/50ff6d065067545629500587/setColor");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$final);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_exec ($ch);
curl_close ($ch);
?>