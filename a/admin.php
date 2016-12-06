<html>
<head>
<title>Города v.1 [release]</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style.css" />
</head>
<body>
<center>

<?php

include '../conf.php';
include '../functions.php';

if($_POST['addcity'] == true){
    addcity();
}

?>

<form action="admin.php" method="post">
<p><input type="button" onclick="location.href='../index.php';" value="На главную" /> <br />
<input type="submit" value="Добавить/обновить города" name="addcity" /></p>
</form>


</center>
</body>
</html>