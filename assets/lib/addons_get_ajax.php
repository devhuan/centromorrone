<?php 
include (dirname(dirname(dirname(__FILE__))) . "/objects/class_connection.php");
include (dirname(dirname(dirname(__FILE__))) . "/objects/class_services_addon.php");
include (dirname(dirname(dirname(__FILE__))) . "/header.php");

$con = new cleanto_db();
$conn = $con->connect();
$objservice_addon = new cleanto_services_addon();
$objservice_addon->conn = $conn;
if (isset($_POST['addon_id']) && $_POST['addon_id']) {
   $json_array = mysqli_fetch_assoc($objservice_addon->getdataby_id($_POST['addon_id']));
   echo json_encode($json_array);
}

