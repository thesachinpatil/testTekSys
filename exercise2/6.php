<?php

$servername = "localhost";
$username = "root";
$password = "";
$db = 'ciscotest';

$conn = mysqli_connect($servername,$username,$password,$db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_query($conn, "CREATE VIEW RouterInfo AS SELECT * FROM routers");

$query = "insert into routers (sap_id, loopback, host_name, mac_address, type, created_at) values ";
$values = '';

for($i = 1; $i <= 10; $i++) {
    $randIP = mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255);
    $chars1 = '0123456789abcdefghijklmnopqrstuvwxyz';
    $randSapId = substr(str_shuffle($chars1), 0, 18);
    $chars2 = 'abcdefghijklmnopqrstuvwxyz';
    $randHostName = substr(str_shuffle($chars2), 0, 10).'.com';
    $randMac = implode(':', str_split(str_pad(base_convert(mt_rand(0, 0xffffff), 10, 16) . base_convert(mt_rand(0, 0xffffff), 10, 16), 12), 2));
    $types = ['AG1', 'CSS'];
    $randType = $types[rand ( 0 , count($types) -1)];
    $ipExists = mysqli_query($conn, "select loopback from routers where loopback = '$randIP'");
    if(mysqli_num_rows($ipExists) > 0) {
        $i--;
        return;
    }
    $sapIdExists = mysqli_query($conn, "select sap_id from routers where sap_id = '$randSapId'");
    if(mysqli_num_rows($sapIdExists) > 0) {
        $i--;
        return;
    }
    $hostNameExists = mysqli_query($conn, "select host_name from routers where host_name = '$randHostName'");
    if(mysqli_num_rows($hostNameExists) > 0) {
        $i--;
        return;
    }
    $macAddExists = mysqli_query($conn, "select mac_address from routers where host_name = '$randMac'");
    if(mysqli_num_rows($macAddExists) > 0) {
        $i--;
        return;
    }
    
    $values .= "('$randSapId', '$randIP', '$randHostName', '$randMac', '$randType', '".date("Y-m-d H:i:s")."')";
}
$values = substr($values, 0, strlen($values) - 1);
mysqli_query($conn, $query);