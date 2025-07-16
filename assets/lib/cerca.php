<?php

session_start();
include(dirname(dirname(dirname(__FILE__))) . '/header.php');
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_connection.php');
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_users.php');
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_order_client_info.php');
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_setting.php');
include(dirname(dirname(dirname(__FILE__))) . "/objects/class_services.php");
include(dirname(dirname(dirname(__FILE__))) . "/objects/class_services_addon.php");
include(dirname(dirname(dirname(__FILE__))) . "/objects/class_services_addon_rates.php");
include(dirname(dirname(dirname(__FILE__))) . "/objects/class_services_methods.php");
include(dirname(dirname(dirname(__FILE__))) . "/objects/class_service_methods_design.php");
include(dirname(dirname(dirname(__FILE__))) . "/objects/class_services_methods_units.php");
include(dirname(dirname(dirname(__FILE__))) . "/objects/class_design_settings.php");
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_general.php');
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_front_first_step.php');
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_adminprofile.php');
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_frequently_discount.php');
include(dirname(dirname(dirname(__FILE__))) . '/objects/class_rating_review.php');

$database = new cleanto_db();
$conn = $database->connect();
$database->conn = $conn;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$objservice_addon = new cleanto_services_addon();
$objservice_addon_rate = new cleanto_services_addon_rates();
$objservice_addon->conn = $conn;
$objservice_addon_rate->conn = $conn;
$objservice_addon->service_id = 4;
$term = $_POST['term'];
$frontend = isset($_POST['frontend']) ? $_POST['frontend'] : '';
$id = $_POST['id'];
if ($term != "") {
    $res = $objservice_addon->getdataby_serviceid_autocomplete($term, $frontend);
    $ret = array();
    foreach ($res as $value) {
        array_push($ret, $value);
    };
    echo json_encode($ret);
    exit;
}
if ($id > 0) {
    $res = $objservice_addon->getdataby_id($id);
    foreach ($res as $value) {
        echo json_encode($value);
    };
    exit;
}
