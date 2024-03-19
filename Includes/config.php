<?php
    $host='myebillsvr.mysql.database.azure.com
';
# MySQL Host
    $mysql_user="myadmin";# MySql Username
    $mysql_pwd="Server@1"; # MySql Password
    $dbms="ebillsystem"; # Database

    $con = mysqli_connect($host,$mysql_user,$mysql_pwd,$dbms);
    if (!$con) die('Could not connect: ' . mysql_error());
    mysqli_select_db($con,$dbms) or die("cannot select DB" . mysql_error());
?>
