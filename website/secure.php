<!DOCTYPE HTML>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
<link style = "text/css" rel = "stylesheet" href = "alarm.css"/>
<link href='http://fonts.googleapis.com/css?family=Ubuntu:300' rel='stylesheet' type='text/css'>
<title> Login </title>
</head>
<body>
<?php
$pass = $_POST['pass'];

if($pass == "the password")
{
        include("../alarm.html");
}
else
{
    if(isset($_POST))
    {?>

            <form method="POST" action="secure.php">
            Pass <input type="password" name="pass"></input><br/>
            <input type="submit" name="submit" value="Go"></input>
            </form>
    <?}
}
?>

</body>
</html>