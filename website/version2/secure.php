<!DOCTYPE HTML>
<html>
<head>
<link style = "text/css" rel = "stylesheet" href = "alarm.css"/>
<link href='http://fonts.googleapis.com/css?family=Ubuntu:300' rel='stylesheet' type='text/css'>
<title> Login </title>
</head>
<body>
<?php
$user = $_POST['user'];
$pass = $_POST['pass'];

if($user == "admin"
&& $pass == "admin")
{
        include("alarm.html");
}
else
{
    if(isset($_POST))
    {?>

            <form method="POST" action="secure.php">
            User <input type="text" name="user"></input><br/>
            Pass <input type="password" name="pass"></input><br/>
            <input type="submit" name="submit" value="Go"></input>
            </form>
    <?}
}
?>

</body>
</html>