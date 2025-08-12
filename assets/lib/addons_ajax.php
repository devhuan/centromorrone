<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once(dirname(dirname(__FILE__)) . '/phpoffice/vendor/autoload.php');
include (dirname(dirname(dirname(__FILE__))) . "/objects/class_connection.php");
include (dirname(dirname(dirname(__FILE__))) . "/objects/class_services_addon.php");
include (dirname(dirname(dirname(__FILE__))) . "/header.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$con = new cleanto_db();
$conn = $con->connect();
$objservice_addon = new cleanto_services_addon();
$objservice_addon->conn = $conn;
if (isset($_POST['action']) && $_POST['action'] == 'export') {
   $objservice_addon->service_id = $_POST['id'];
   $res = $objservice_addon->getaddonby_serviceid();
   
   $spreadsheet = new Spreadsheet();
   $sheet = $spreadsheet->getActiveSheet();
   $sheet->setCellValue('A1', 'Id');
   $sheet->setCellValue('B1', 'Service Id');
   $sheet->setCellValue('C1', 'Addon Service Name');
   $sheet->setCellValue('D1', 'Base Price');
   $sheet->setCellValue('E1', 'Maxqty');
   $sheet->setCellValue('F1', 'Multipleqty');
   $sheet->setCellValue('G1', 'Pay Locally Status');
   $sheet->setCellValue('H1', 'Paypal Express Checkout Status');
   $sheet->setCellValue('I1', 'Stripe Payment Form Status');
   $sheet->setCellValue('J1', 'Attachment');
   $sheet->setCellValue('K1', 'Mandatory');
   $sheet->setCellValue('L1', 'Booking Latency');
   $sheet->setCellValue('M1', 'Status');
   $sheet->setCellValue('N1', 'Position');
   $sheet->setCellValue('O1', 'Addon Service Description');
   $sheet->setCellValue('P1', 'Aduration');
   $sheet->setCellValue('Q1', 'Tags');
   $sheet->setCellValue('R1', 'Block Date');
   $sheet->setCellValue('S1', 'Block Date To');
   $sheet->setCellValue('T1', 'Block Time From');
   $sheet->setCellValue('U1', 'Block Time To');
   
   $i = 2;
   while ($arrs = mysqli_fetch_array($res)){
      $sheet->setCellValue('A'.$i, $arrs['id']);
      $sheet->setCellValue('B'.$i, $arrs['service_id']);
      $sheet->setCellValue('C'.$i, $arrs['addon_service_name']);
      $sheet->setCellValue('D'.$i, $arrs['base_price']);
      $sheet->setCellValue('E'.$i, $arrs['maxqty']);
      $sheet->setCellValue('F'.$i, $arrs['multipleqty']);
      $sheet->setCellValue('G'.$i, $arrs['pay_locally_status']);
      $sheet->setCellValue('H'.$i, $arrs['paypal_express_checkout_status']);
      $sheet->setCellValue('I'.$i, $arrs['stripe_payment_form_status']);
      $sheet->setCellValue('J'.$i, $arrs['attachment']);
      $sheet->setCellValue('K'.$i, $arrs['mandatory']);
      $sheet->setCellValue('L'.$i, $arrs['booking_latency']);
      $sheet->setCellValue('M'.$i, $arrs['status']);
      $sheet->setCellValue('N'.$i, $arrs['position']);
      $sheet->setCellValue('O'.$i, $arrs['addon_service_description']);
      $sheet->setCellValue('P'.$i, $arrs['aduration']);
      $sheet->setCellValue('Q'.$i, $arrs['tags']);
      $sheet->setCellValue('R'.$i, $arrs['block_date']);
      $sheet->setCellValue('S'.$i, $arrs['block_date_to']);
      $sheet->setCellValue('T'.$i, $arrs['block_time_from']);
      $sheet->setCellValue('U'.$i, $arrs['block_time_to']);
      $i++;
   }

   $filename = 'excel_addon_' . time() . '_' . rand(1000,9999) . '.xlsx';
   $filePath = dirname(dirname(__FILE__)) . '/tmp/' . $filename;

   $writer = new Xlsx($spreadsheet);
   $writer->save($filePath);

   $baseUrl = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}/assets";
   $downloadUrl = $baseUrl . '/tmp/' . $filename;
   echo json_encode([
      'success' => true,
      'download_url' => $downloadUrl
   ]);
   exit;
}

