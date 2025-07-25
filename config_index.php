<?php    


ob_start();
$filename =  './config.php';
$file = file_exists($filename);
if($file){
	if(filesize($filename) > 0){
	}
	else{
		header('location:ct_install.php');
	}
}
session_start();
include(dirname(__FILE__) . '/config.php');
include(dirname(__FILE__) . '/class_configure.php');

include(dirname(__FILE__) . "/header.php");
$cvars = new cleanto_myvariable();
$host = trim($cvars->hostnames);
$un = trim($cvars->username);
// $ps = base64_decode(trim($cvars->passwords)); 
$ps = trim($cvars->passwords); 
$db = trim($cvars->database);
$con = new mysqli($host, $un, $ps, $db);
if(isset($_POST['btnaddadmin'])){
    $configs = new cleanto_configure();
    $configs->conn = $con;
	$configs->email = $_POST['txtemail'];
	$configs->password = $_POST['txtpassword'];
    $configs->q26();
	$returned_inserted_id = $configs->q23();
    $insertedadminid = $returned_inserted_id;
    $_SESSION['ct_adminid'] = $insertedadminid;
    $_SESSION['ct_useremail'] = $_POST['txtemail'];
    /* header("Location:./admin/"); */
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cleanto Login</title>
    <link rel="stylesheet" type="text/css" href="<?php  echo BASE_URL; ?>/assets/css/login-style.css" />
    <link rel="stylesheet" type="text/css" href="<?php  echo BASE_URL; ?>/assets/css/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php  echo BASE_URL; ?>/assets/css/bootstrap/bootstrap-theme.min.css" />
    <!-- **Google - Fonts** -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" type="text/css" href="<?php  echo BASE_URL; ?>/assets/css/font-awesome/css/font-awesome.css" />
    <script type="text/javascript" src="<?php  echo BASE_URL; ?>/assets/js/jquery-2.1.4.min.js"></script>
	<script type="text/javascript" src="<?php  echo BASE_URL; ?>/assets/js/bootstrap.min.js"></script>
    <script src="<?php  echo BASE_URL; ?>/assets/js/jquery.validate.min.js"></script>
	<script>
	jQuery(document).ready(function(){
		jQuery("#configform").validate({
    
        rules: {
            txtemail: "required",
            txtpassword: "required",
        },
        
        messages: {
            txtemail: "Please enter email",
            txtpassword: "Please enter password",
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });
	});
	</script>
	<style>
	.error{
		color: #F00;
		font-size: 12px !important;
		width: 100% !important;
		float: left !important;
		font-weight: normal !important;
		display:block !important;
		position: relative !important;
	}
	body{
		font-family: 'Open Sans', sans-serif;
		font-weight: 300;
		background-size: 100% 100% !important;
		font-size: 15px;
		color: #333;
		-webkit-font-smoothing: antialiased;	
	}
	</style>
</head>
<body>
<?php   
if($con->connect_errno=='0' && ($host=='' || $db=='')) {
	echo '<div style="margin:35px 0px 0px 30px;font-family: Open Sans, sans-serif;font-size: 18px;">Error: Database connection not established. Please configure database connection variables in config file <br/><br/>File Path:<strong>'.$cur_dir.'/config.php</strong> <br/>Please mention Hostname, Username, Password and Database Name.</div>';
}
elseif ($con->connect_errno!='0') {
	$cur_dir = basename(__DIR__);
	
	echo '<div style="margin:35px 0px 0px 30px;font-family: Open Sans, sans-serif;font-size: 18px;">Error: <strong>'.mysqli_connect_error().'</strong></div>';
	}
else
{
    $query = "select * from `ct_admin_info`";
    $info = $con->query($query);
    if(@mysqli_num_rows($info) != 0){
        ?>
		<script>
			window.location = '<?php  echo SITE_URL;?>';
		</script>
		<?php    
    }
    else
    {	
        
	session_destroy();	
	$configs = new cleanto_configure();
    $configs->conn = $con;
	$configs->q1(); $configs->q2();$configs->q3(); $configs->q4();
    $configs->q5(); $configs->q6();$configs->q7(); $configs->q8();
    $configs->q9(); $configs->q10();$configs->q11(); $configs->q12();
    $configs->q13(); $configs->q14();$configs->q15(); $configs->q16();
    $configs->q17(); $configs->q18(); $configs->q19(); $configs->q20();
    $configs->q21(); $configs->q22(); $configs->q24();$configs->q25();$configs->q27();
	
        ?>
        <div id="ct-login">
            <section class="main">
                <div class="vertical-alignment-helper">
                    <div class="vertical-align-center">
                        <div class="ct-main-login visible animated fadeInUp">
                            <div class="form-container">
                                <div class="tab-content">
                                    <form id="configform" name="" method="POST">
                                        <h1 class="log-in">Configure admin login credentials</h1>
                                        <div class="form-group fl">
                                            <div for="userEmail"><i class="icon-envelope-alt"></i>Email</div>
                                            <input type="email" id="userEmail" name="txtemail"  onkeydown="if (event.keyCode == 13) document.getElementById('mybtnlog').click()">
                                        </div>
                                        <div class="form-group fl">
                                            <div for="userPassword"><i class="icon-lock"></i>Password</div>
                                            <input type="password" id="userPassword" name="txtpassword"  minlength="8" class="showpassword" onkeydown="if (event.keyCode == 13) document.getElementById('mybtnlog').click()">
                                        </div>
                                        <div class="clearfix">
                                            <input name="btnaddadmin" type="submit" class="btn ct-login-btn btn-lg col-xs-12" value="Save" >
                                        </div>
                                    </form>
                                </div>​​
                            </div>​​
                        </div>​​<!-- login end here -->
                        <!-- forget password -->
                    </div>
                </div>
            </section>
        </div>
    <?php   
    }
}
?>
</body>
</html>