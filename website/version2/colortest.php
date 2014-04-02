<?php
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"https://api.spark.io/v1/devices/50ff6d065067545629500587/setColor");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
"access_token=4e5a333571329fd3741c72a2f2aec5cf3b183258&params=255000255");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_exec ($ch);
curl_close ($ch);
?>