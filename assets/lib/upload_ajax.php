<?php
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
include_once(dirname(dirname(dirname(__FILE__))).'/header.php');
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
	$file = $_FILES['image'];
	$fileName = basename($file['name']);
	$fileTmp = $file['tmp_name'];
	$fileSize = $file['size'];
	$fileType = mime_content_type($fileTmp);
	$newFileName = pathinfo($fileName, PATHINFO_FILENAME) . time() . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
	$uploadDir = dirname(dirname(dirname(__FILE__)))."/assets/images/exame/";
    $destination = $uploadDir . $newFileName;
	move_uploaded_file($fileTmp, $destination);
	echo $newFileName;
}
?>