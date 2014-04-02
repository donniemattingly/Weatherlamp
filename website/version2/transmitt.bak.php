<?php

mysql_connect("localhost","donniema_testusr","test123") or die(mysql_error());
mysql_select_db("donniema_weatherlamp") or die(mysql_error());

$query = "SELECT color FROM color";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
$theColorVal = $row['color'];
$theColorVal = sprintf("%09s",$theColorVal);

$outline = "access_token=4e5a333571329fd3741c72a2f2aec5cf3b183258&params=";
$final = $outline . $theColorVal;

echo $final;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"https://api.spark.io/v1/devices/50ff6d065067545629500587/setColor");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$final);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec ($ch);
curl_close ($ch);

// echo $result;




?>