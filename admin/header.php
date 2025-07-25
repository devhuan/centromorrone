<?php
$filename =  dirname(dirname(__FILE__)) . '/config.php';
$file = file_exists($filename);
if ($file) {
   if (!filesize($filename) > 0) {
      header('location:../ct_install.php');
   } else {
      include(dirname(dirname(__FILE__)) . "/objects/class_connection.php");
      $cvars = new cleanto_myvariable();
      $host = trim($cvars->hostnames);
      $un = trim($cvars->username);
      $ps = trim($cvars->passwords);
      $db = trim($cvars->database);
      $con = new cleanto_db();
      $conn = $con->connect();
      if (($conn->connect_errno == '0' && ($host == '' || $db == '')) || $conn->connect_errno != '0') {
         header('Location: ../config_index.php');
      }
   }
} else {
   echo "Config file does not exist";
}
ob_start();
session_start();
include(dirname(dirname(__FILE__)) . '/header.php');

if (!isset($_SESSION['ct_adminid']) && !isset($_SESSION['ct_login_user_id'])) {    ?>
   <script>
      var loginObj = {
         'site_url': '<?php echo SITE_URL; ?>'
      };
      var login_url = loginObj.site_url;
      window.location = login_url + "admin/";
   </script>
<?php  }
include(dirname(dirname(__FILE__)) . '/class_configure.php');
include(dirname(dirname(__FILE__)) . "/objects/class_dashboard.php");
include(dirname(dirname(__FILE__)) . "/objects/class_setting.php");
include(dirname(dirname(__FILE__)) . "/objects/class_general.php");
include(dirname(dirname(__FILE__)) . "/objects/class_off_days.php");
include(dirname(dirname(__FILE__)) . "/objects/class_version_update.php");
include(dirname(dirname(__FILE__)) . "/objects/class_gc_hook.php");

$cvars = new cleanto_myvariable();
$host = trim($cvars->hostnames);
$un = trim($cvars->username);
$ps = trim($cvars->passwords);
$db = trim($cvars->database);
$con = new cleanto_db();
$conn =
   $con->connect();

if (($conn->connect_errno == '0' && ($host == '' || $db == '')) || $conn->connect_errno != '0') {
   header('Location: ' . BASE_URL . '/config_index.php');
   exit(0);
}
$objdashboard = new cleanto_dashboard();
$objdashboard->conn = $conn;
$general = new cleanto_general();
$general->conn = $conn;
$setting = new cleanto_setting();
$setting->conn = $conn;
$setting->readAll();
$getdateformat = $setting->get_option('ct_date_picker_date_format');
$gettimeformat = $setting->get_option('ct_time_format');
$offday = new cleanto_provider_off_day();
$offday->conn = $conn;
$symbol_position = $setting->get_option('ct_currency_symbol_position');
$decimal = $setting->get_option('ct_price_format_decimal_places');
$objcheckversion = new cleanto_version_update();
$objcheckversion->conn = $conn;
$gc_hook = new cleanto_gcHook();
$gc_hook->conn = $conn;
$current = $setting->get_option('ct_version');

if ($current == "") {
   $objcheckversion->insert_option("ct_version", "1.1");
}

if ($current < 1.1) {
   $setting->set_option("ct_version", "1.1");
   $objcheckversion->update1_1();
}
if ($current < 1.2) {
   $setting->set_option("ct_version", "1.2");
   $objcheckversion->update1_2();
}
if ($current < 1.3) {
   $setting->set_option("ct_version", "1.3");
   $objcheckversion->update1_3();
}
if ($current < 1.4) {
   $setting->set_option("ct_version", "1.4");
   $objcheckversion->update1_4();
}
if ($current < 1.5) {
   $setting->set_option("ct_version", "1.5");
   $objcheckversion->update1_5();
}
if ($current < 1.6) {
   $setting->set_option("ct_version", "1.6");
   $objcheckversion->update1_6();
}
if ($current < 2.0) {
   $setting->set_option("ct_version", "2.0");
   $objcheckversion->update2_0();
}
if ($current < 2.1) {
   $setting->set_option("ct_version", "2.1");
}
if ($current < 2.2) {
   $setting->set_option("ct_version", "2.2");
   $objcheckversion->update2_2();
}
if ($current < 2.3) {
   $setting->set_option("ct_version", "2.3");
   $objcheckversion->update2_3();
}
if ($current < 2.4) {
   $setting->set_option("ct_version", "2.4");
   $objcheckversion->update2_4();
}
if ($current < 2.5) {
   $setting->set_option("ct_version", "2.5");
   $objcheckversion->update2_5();
}
if ($current < 2.6) {
   $setting->set_option("ct_version", "2.6");
   $objcheckversion->update2_6();
}
if ($current < 2.7) {
   $setting->set_option("ct_version", "2.7");
   $objcheckversion->update2_7();
}
if ($current < 2.8) {
   $setting->set_option("ct_version", "2.8");
   $objcheckversion->update2_8();
}
if ($current < 3.0) {
   $setting->set_option("ct_version", "3.0");
   $objcheckversion->update3_0();
}
if ($current < 3.1) {
   $setting->set_option("ct_version", "3.1");
}
if ($current < 3.2) {
   $setting->set_option("ct_version", "3.2");
   $objcheckversion->update3_2();
}
if ($current < 3.3) {
   $setting->set_option("ct_version", "3.3");
   $objcheckversion->update3_3();
}
if ($current < 4.0) {
   $setting->set_option("ct_version", "4.0");
   $objcheckversion->update4_0();
}
if ($current < 4.1) {
   $setting->set_option("ct_version", "4.1");
   $objcheckversion->update4_1();
}
if ($current < 4.2) {
   $setting->set_option("ct_version", "4.2");
   $objcheckversion->update4_2();
}
if ($current < 4.3) {
   $setting->set_option("ct_version", "4.3");
   $objcheckversion->update4_3();
}
if ($current < 4.4) {
   $setting->set_option("ct_version", "4.4");
   $objcheckversion->update4_4();
}
if ($current < 5.0) {
   $setting->set_option("ct_version", "5.0");
   $objcheckversion->update5_0();
}
if ($current < 5.1) {
   $setting->set_option("ct_version", "5.1");
}
if ($current < 5.2) {
   $setting->set_option("ct_version", "5.2");
   $objcheckversion->update5_2();
}
if ($current < 5.3) {
   $setting->set_option("ct_version", "5.3");
   $objcheckversion->update5_3();
}
if ($current < 6.0) {
   $setting->set_option("ct_version", "6.0");
   $objcheckversion->update6_0();
}
if ($current < 6.1) {
   $setting->set_option("ct_version", "6.1");
}
if ($current < 6.2) {
   $setting->set_option("ct_version", "6.2");
   $objcheckversion->update6_2();
}
if ($current < 6.3) {
   $setting->set_option("ct_version", "6.3");
   $objcheckversion->update6_3();
}
if ($current < 6.4) {
   $setting->set_option("ct_version", "6.4");
   $objcheckversion->update6_4();
}
if ($current < 6.5) {
   $setting->set_option("ct_version", "6.5");
   $objcheckversion->update6_5();
}
if ($current < 7.0) {
   $setting->set_option("ct_version", "7.0");
   $objcheckversion->update7_0();
}
if ($current < 7.1) {
   $setting->set_option("ct_version", "7.1");
}
if ($current < 7.2) {
   $setting->set_option("ct_version", "7.2");
}
if ($current < 7.3) {
   $setting->set_option("ct_version", "7.3");
   $objcheckversion->update7_3();
}
if ($current < 7.4) {
   $setting->set_option("ct_version", "7.4");
   $objcheckversion->update7_4();
}
if ($current < 7.5) {
   $setting->set_option("ct_version", "7.5");
   $objcheckversion->update7_5();
}
if ($current < 7.6) {
   $setting->set_option("ct_version", "7.6");
   $objcheckversion->update7_6();
}
if ($current < 7.7) {
   $setting->set_option("ct_version", "7.7");
   $objcheckversion->update7_7();
}
if ($current < 7.8) {
   $setting->set_option("ct_version", "7.8");
}
if ($current < 7.9) {
   $setting->set_option("ct_version", "7.9");
}
if ($current < 8.0) {
   $setting->set_option("ct_version", "8.0");
}
if ($current < 8.1) {
   $setting->set_option("ct_version", "8.1");
}
$lang = $setting->get_option("ct_language");
$label_language_values = array();
$language_label_arr = $setting->get_all_labelsbyid($lang);

if ($language_label_arr[1] != "" || $language_label_arr[3] != "" || $language_label_arr[4] != "" || $language_label_arr[5] != "" || $language_label_arr[6] != "") {
   $default_language_arr = $setting->get_all_labelsbyid("en");

   if ($language_label_arr[1] != '') {
      $label_decode_front = base64_decode($language_label_arr[1]);
   } else {
      $label_decode_front = base64_decode($default_language_arr[1]);
   }

   if ($language_label_arr[3] != '') {
      $label_decode_admin = base64_decode($language_label_arr[3]);
   } else {
      $label_decode_admin = base64_decode($default_language_arr[3]);
   }

   if ($language_label_arr[4] != '') {
      $label_decode_error = base64_decode($language_label_arr[4]);
   } else {
      $label_decode_error = base64_decode($default_language_arr[4]);
   }

   if ($language_label_arr[5] != '') {
      $label_decode_extra = base64_decode($language_label_arr[5]);
   } else {
      $label_decode_extra = base64_decode($default_language_arr[5]);
   }

   if ($language_label_arr[6] != '') {
      $label_decode_front_form_errors = base64_decode($language_label_arr[6]);
   } else {
      $label_decode_front_form_errors = base64_decode($default_language_arr[6]);
   }

   $label_decode_front_unserial = unserialize($label_decode_front);
   $label_decode_admin_unserial = unserialize($label_decode_admin);
   $label_decode_error_unserial = unserialize($label_decode_error);
   $label_decode_extra_unserial = unserialize($label_decode_extra);
   $label_decode_front_form_errors_unserial = unserialize(
      $label_decode_front_form_errors
   );

   $label_language_arr = array_merge(
      $label_decode_front_unserial,
      $label_decode_admin_unserial,
      $label_decode_error_unserial,
      $label_decode_extra_unserial,
      $label_decode_front_form_errors_unserial
   );

   foreach ($label_language_arr as $key => $value) {
      $label_language_values[$key] = urldecode($value);
   }
} else {
   $default_language_arr = $setting->get_all_labelsbyid("en");
   $label_decode_front = base64_decode($default_language_arr[1]);
   $label_decode_admin = base64_decode($default_language_arr[3]);
   $label_decode_error = base64_decode($default_language_arr[4]);
   $label_decode_extra = base64_decode($default_language_arr[5]);

   $label_decode_front_form_errors = base64_decode($default_language_arr[6]);
   $label_decode_front_unserial = unserialize($label_decode_front);
   $label_decode_admin_unserial = unserialize($label_decode_admin);
   $label_decode_error_unserial = unserialize($label_decode_error);
   $label_decode_extra_unserial = unserialize($label_decode_extra);
   $label_decode_front_form_errors_unserial = unserialize(
      $label_decode_front_form_errors
   );
   $label_language_arr = array_merge(
      $label_decode_front_unserial,
      $label_decode_admin_unserial,
      $label_decode_error_unserial,
      $label_decode_extra_unserial,
      $label_decode_front_form_errors_unserial
   );

   foreach ($label_language_arr as $key => $value) {
      $label_language_values[$key] = urldecode($value);
   }
} ?>
<!Doctype html>

<head>
   <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <link rel="shortcut icon" type="image/png" href="<?php echo BASE_URL; ?>/assets/images/backgrounds/<?php echo $setting->get_option('ct_favicon_image'); ?>" />
   <title><?php echo $setting->get_option("ct_page_title"); ?> |
      <?php
      if (strpos($_SERVER['SCRIPT_NAME'], 'my-appointments.php') != false) {
         echo 'My Appointments';
      } elseif (strpos($_SERVER['SCRIPT_NAME'], 'user-profile.php') != false) {
         echo 'Profile';
      } else {
         echo "Admin";
      }
      ?></title>
   <meta name="description" content="" />
   <meta name="author" content="" />
   <!-- Manual Booking CSS Files Start -->
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/ct-main.css" type="text/css" media="all" />
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/ct-common.css" type="text/css" media="all" />
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/tooltipster.bundle.min.css" type="text/css" media="all" />
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/tooltipster-sideTip-shadow.min.css" type="text/css" media="all" />

   <?php if (in_array($lang, array('ary', 'ar', 'azb', 'fa_IR', 'haz'))) { ?>
      <!-- Front RTL style -->
      <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/cta-front-rtl.css" type="text/css" media="all" />
   <?php   } ?>
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/jquery_editor/jquery-te-1.4.0.css" type="text/css" media="all" />
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/ct-responsive.css" type="text/css" media="all" />
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/ct-manual-booking.css" type="text/css" media="all" />
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/ct-reset.min.css" type="text/css" media="all" />
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/jquery-ui.theme.min.css" type="text/css" media="all" />
   <style>
      .error {
         color: red;
      }
   </style>
   <style>
      #ct .not-scroll-custom {
         margin-top: 0 !important;
      }
   </style>
   <!-- Manual Booking CSS Files End -->
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/cta-reset.min.css" type="text/css" media="all">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/cta-admin-style.css?ver=<?php echo time(); ?>" type="text/css" media="all">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/cta-admin-common.css" type="text/css" media="all">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/cta-admin-responsive.css" type="text/css" media="all">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap/bootstrap.min.css" type="text/css" media="all">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/daterangepicker.css" type="text/css" media="all">
   <?php if (in_array($lang, array('ary', 'ar', 'azb', 'fa_IR', 'haz'))) { ?>
      <!-- admin rtl css -->
      <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap/bootstrap-rtl.min.css" type="text/css" media="all">
      <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/cta-admin-rtl.css" type="text/css" media="all">
   <?php   } ?>
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/fullcalendar.css" type="text/css" media="all">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/jquery.Jcrop.min.css" type="text/css" media="all">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/intlTelInput.css" type="text/css" media="all">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap/bootstrap-theme.min.css" type="text/css" media="all">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap-toggle.min.css" type="text/css" media="all">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap-select.min.css" type="text/css" media="all">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/jquery.minicolors.css" type="text/css" media="all">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/jquery.dataTables.min.css" type="text/css" media="all">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/responsive.dataTables.min.css" type="text/css" media="all">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/dataTables.bootstrap.min.css" type="text/css" media="all">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/buttons.dataTables.min.css" type="text/css" media="all">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/jquery-ui.min.css" type="text/css" media="all">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/star_rating.min.css" type="text/css" media="all">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/font-awesome/css/font-awesome.min.css" type="text/css" media="all">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/line-icons/simple-line-icons.css" type="text/css" media="all">

   <!-- ** Google Fonts **  -->
   <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet">
   <!-- ** Jquery ** -->
   <script src="<?php echo BASE_URL; ?>/assets/js/jquery-2.1.4.min.js" type="text/javascript"></script>
   <script src="<?php echo BASE_URL; ?>/assets/js/bootstrap-multiselect.js" type="text/javascript"></script>
   <script src="<?php echo BASE_URL; ?>/assets/js/jquery-ui.min.js" type="text/javascript"></script>
   <script src="<?php echo BASE_URL; ?>/assets/js/moment.min.js" type="text/javascript"></script>
   <script src="<?php echo BASE_URL; ?>/assets/js/jquery.Jcrop.min.js" type="text/javascript"></script>
   <script src="<?php echo BASE_URL; ?>/assets/js/jquery.color.js" type="text/javascript"></script>
   <script src="<?php echo BASE_URL; ?>/assets/js/fullcalendar.min.js" type="text/javascript"></script>
   <script src="<?php echo BASE_URL; ?>/assets/js/lang-all.js" type="text/javascript"></script>
   <script src="<?php echo BASE_URL; ?>/assets/js/intlTelInput.js" type="text/javascript"></script>
   <script src="<?php echo BASE_URL; ?>/assets/js/jquery.nicescroll.min.js" type="text/javascript">
   </script>
   <script src="<?php echo BASE_URL; ?>/assets/js/bootstrap.min.js" type="text/javascript"></script>

   <?php if (strpos($_SERVER['SCRIPT_NAME'], 'service-extra-addons.php') == false && strpos($_SERVER['SCRIPT_NAME'], 'service-manage-unit-price.php') == false && strpos($_SERVER['SCRIPT_NAME'], 'service-manage-calculation-methods.php') == false) { ?>
      <script src="<?php echo BASE_URL; ?>/assets/js/bootstrap-toggle.min.js" type="text/javascript">
      </script> <?php   } ?>
   <script src="<?php echo BASE_URL; ?>/assets/js/vue.min.js" type="text/javascript"></script>
   <script src="<?php echo BASE_URL; ?>/assets/js/bootstrap-select.min.js" type="text/javascript">
   </script>
   <script src="<?php echo BASE_URL; ?>/assets/js/daterangepicker.js" type="text/javascript">
   </script>
   <script src="<?php echo BASE_URL; ?>/assets/js/Chart.js" type="text/javascript"></script>
   <script src="<?php echo BASE_URL; ?>/assets/js/jquery.minicolors.min.js" type="text/javascript"></script>
   <!-- data tables all js inlcude pdf,csv, and excel -->
   <script src="<?php echo BASE_URL; ?>/assets/js/datatable/jquery.dataTables.min.js" type="text/javascript"></script>
   <script src="<?php echo BASE_URL; ?>/assets/js/datatable/dataTables.responsive.min.js" type="text/javascript"></script>
   <script src="<?php echo BASE_URL; ?>/assets/js/datatable/dataTables.bootstrap.min.js" type="text/javascript"></script>
   <script src="<?php echo BASE_URL; ?>/assets/js/datatable/dataTables.buttons.min.js" type="text/javascript"></script>
   <script src="<?php echo BASE_URL; ?>/assets/js/datatable/jszip.min.js" type="text/javascript"></script>
   <script src="<?php echo BASE_URL; ?>/assets/js/datatable/pdfmake.min.js" type="text/javascript"></script>
   <script src="<?php echo BASE_URL; ?>/assets/js/datatable/vfs_fonts.js" type="text/javascript"></script>
   <script src="<?php echo BASE_URL; ?>/assets/js/datatable/buttons.html5.min.js" type="text/javascript"></script>
   <!--    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
   <!--[if lt IE 9]>    <script src="js/html5shiv.js"></script>    <script src="js/respond.min.js"></script>    <![endif]-->
   <script src="<?php echo BASE_URL; ?>/assets/js/star_rating_min.js" type="text/javascript">
   </script>
   <script src="<?php echo BASE_URL; ?>/assets/js/jquery.validate.min.js"></script>

   <?php include(dirname(dirname(__FILE__)) . "/objects/class_payment_hook.php");
   $payment_hook = new cleanto_paymentHook();
   $payment_hook->conn = $conn;
   $payment_hook->payment_extenstions_exist();
   $purchase_check = $payment_hook->payment_purchase_status();
   include(dirname(dirname(__FILE__)) . "/extension/ct-common-extension-js.php");

   // include(dirname(__FILE__) . "/setting.php");  
   ?>
   <script src="<?php echo BASE_URL; ?>/assets/js/ct-common-admin-jquery.js?<?php echo time(); ?>" type="text/javascript"></script>
   <script type='text/javascript'>
  window.smartlook||(function(d) {
    var o=smartlook=function(){ o.api.push(arguments)},h=d.getElementsByTagName('head')[0];
    var c=d.createElement('script');o.api=new Array();c.async=true;c.type='text/javascript';
    c.charset='utf-8';c.src='https://web-sdk.smartlook.com/recorder.js';h.appendChild(c);
    })(document);
    smartlook('init', 'b7aab4c3ce65c4ea51dfdceb54d2719119370446', { region: 'eu' });
</script>
   <?php echo "<style>     
   
         #cta #cta-main-navigation .navbar-inverse{      
            background:" . $setting->get_option('ct_primary_color_admin') . " !important;  }  
         #cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a, #cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,   #cta #cta-top-nav .navbar .nav > .active > a:focus{      
            background-color: " . $setting->get_option('ct_secondary_color_admin') . " ;      
            color: " . $setting->get_option('ct_text_color_admin') . "  ;   }    
         
      #cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a:hover,   #cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {   
          background-color: " . $setting->get_option('ct_secondary_color_admin') . " ;      
          color: " . $setting->get_option('ct_text_color_admin') . "  ;   }  
      #cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a,   #cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a{     
          color: " . $setting->get_option('ct_text_color_admin') . "  ;   }  
      #cta .noti_color{    
          color: " . $setting->get_option('ct_text_color_admin') . " !important ;  }  
      #cta a#ct-notifications i.icon-bell.cta-new-booking{     
          color: " . $setting->get_option('ct_secondary_color_admin') . " !important ;   }     
      #cta a.ct-tooltip-link{    
          color: " . $setting->get_option('ct_primary_color_admin') . " !important ;  }  
      .navbar-inverse .navbar-nav>.open>a, .navbar-inverse .navbar-nav>.open>a:focus,  .navbar-inverse .navbar-nav>.open>a:hover{      
         background-color: " . $setting->get_option('ct_secondary_color_admin') . " !important  ;   }  
      #cta #cta-staff-panel .ct-staff-right-details .member-offdays .ct-custom-checkbox  ul.ct-checkbox-list label span,   #cta .ct-custom-radio ul.ct-radio-list label span{    border-color: " . $setting->get_option('ct_primary_color_admin') . " !important;  }  
      #cta #cta-staff-panel .ct-staff-right-details .member-offdays .ct-custom-checkbox   ul.ct-checkbox-list input[type='checkbox']:checked + label span{     
         border-color: " . $setting->get_option('ct_secondary_color_admin') . " !important;      
         background-color: " . $setting->get_option('ct_secondary_color_admin') . " !important;  }  
      #cta .ct-custom-radio ul.ct-radio-list input[type='radio']:checked + label span{    
         border-color: " . $setting->get_option('ct_secondary_color_admin') . " !important;   }  
      #cta .fc-toolbar {      
         background-color: " . $setting->get_option('ct_primary_color_admin') . " !important; }  
      #cta .ct-notification-main .notification-header{      
         color: " . $setting->get_option('ct_text_color_admin') . " !important;      
         background-color: " . $setting->get_option('ct_secondary_color_admin') . " !important;  }     
      #cta .fc-toolbar {      
         border-top: 1px solid " . $setting->get_option('ct_primary_color_admin') . " !important;      
         border-left: 1px solid " . $setting->get_option('ct_primary_color_admin') . " !important;     
         border-right: 1px solid " . $setting->get_option('ct_primary_color_admin') . " !important; }  
      #cta .fc button,  #cta .ct-notification-main .notification-header #ct-close-notifications{      color: " . $setting->get_option('ct_text_color_admin') . " !important ;  }  
      #cta .ct-notification-main .notification-header #ct-close-notifications:hover{      background-color: " . $setting->get_option('ct_primary_color_admin') . " !important; }  
      #cta .fc button:hover{     
         color: " . $setting->get_option('ct_secondary_color_admin') . " !important ;   } 

      /* iPads (portrait and landscape) ----------- */   
      @media only screen and (min-width : 768px) and (max-width : 1024px) {      
      #cta #cta-main-navigation .navbar-header,    #cta #cta-main-navigation .navbar .nav.cta-nav-tab > 
         li > a:hover,      
      #cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {       
         color: " . $setting->get_option('ct_secondary_color_admin') . "  ;    }        }  

      /* iPads (landscape) ----------- */ 
      @media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : landscape) {    
      #cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a:hover,      
      #cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {       
         background-color: " . $setting->get_option('ct_secondary_color_admin') . " ;         
         color: " . $setting->get_option('ct_text_color_admin') . "  ;      }     }  

      /* iPads (portrait) ----------- */  
      @media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) {     
      #cta #cta-top-nav .navbar-header,      
      #cta #cta-main-navigation .navbar-header,    
      #cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,    
      #cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,      
      #cta #cta-top-nav .navbar .nav > .active > a:focus,      
      #cta #cta-top-nav .navbar-nav > li > a:hover,     
      #cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a:hover,      
      #cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {       
         color: " . $setting->get_option('ct_secondary_color_admin') . "  ;    }     
      #cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,    
      #cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,      
      #cta #cta-top-nav .navbar .nav > .active > a:focus{         
         background: unset !important;    }  }     

      /********** iPad 3 **********/   
      @media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : landscape) and (-webkit-min-device-pixel-ratio : 2) {     
      #cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a:hover,      
      #cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {       
         background-color: " . $setting->get_option('ct_secondary_color_admin') . " ;         
         color: " . $setting->get_option('ct_text_color_admin') . "  ;      }  }  

      @media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) and (-webkit-min-device-pixel-ratio : 2) {         
      #cta #cta-top-nav .navbar-header,      
      #cta #cta-main-navigation .navbar-header,    
      #cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,    
      #cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,      
      #cta #cta-top-nav .navbar .nav > .active > a:focus,      
      #cta #cta-top-nav .navbar-nav > li > a:hover,      
      #cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a:hover,      
      #cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {       
         color: " . $setting->get_option('ct_secondary_color_admin') . "  ;    }     
      #cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,    
      #cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,      
      #cta #cta-top-nav .navbar .nav > .active > a:focus{         
         background: unset !important;    }  } 

       /* Smartphones (landscape) ----------- */ 
       @media only screen and (max-width: 767px) {     
       #cta #cta-top-nav .navbar-header, #cta #cta-main-navigation .navbar-header, 
       #cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,    
       #cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,      
       #cta #cta-top-nav .navbar .nav > .active > a:focus,      
       #cta #cta-top-nav .navbar-nav > li > a:hover,      
       #cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a:hover,      
       #cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {       
         color: " . $setting->get_option('ct_secondary_color_admin') . "  ;    }     
         #cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,    
         #cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,      
         #cta #cta-top-nav .navbar .nav > .active > a:focus{         
         background: unset !important;    }        }     

      /* Smartphones (portrait and landscape) ----------- */   
      @media only screen and (min-width : 320px) and (max-width : 480px) {          
      #cta #cta-top-nav .navbar-header,      
      #cta #cta-main-navigation .navbar-header,    
      #cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,    
      #cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,      
      #cta #cta-top-nav .navbar .nav > .active > a:focus,      
      #cta #cta-top-nav .navbar-nav > li > a:hover,      
      #cta #cta-main-navigation .navbar .nav.cta-nav-tab > li > a:hover,      
      #cta #cta-main-navigation .navbar .nav.user-nav-bar > li > a:hover {       
         color: " . $setting->get_option('ct_secondary_color_admin') . "  ;    }     
      #cta #cta-main-navigation .navbar .nav.cta-nav-tab > .active > a,    
      #cta #cta-main-navigation .navbar .nav.user-nav-bar > .active > a,      
      #cta #cta-top-nav .navbar .nav > .active > a:focus{         
         background: unset !important;    }  }</style>";    ?>
</head>

<body>
   <div id="rtl-width-setter-enable" style="display:none;"><?php echo $label_language_values['enable']; ?></div>
   <div id="rtl-width-setter-disable" style="display:none;"><?php echo $label_language_values['disable']; ?></div>
   <div id="rtl-width-setter-on" style="display:none;"><?php echo $label_language_values['o_n']; ?></div>
   <div id="rtl-width-setter-off" style="display:none;"><?php echo $label_language_values['off']; ?>
   </div>
   <div class="ct-wrapper" id="cta">
      <!-- main wrapper -->
      <!-- loader -->

      <?php if ($setting->get_option("ct_loader") == 'css' && $setting->get_option("ct_custom_css_loader") != '') { ?>
         <div class="ct-loading-main" align="center">
            <?php echo $setting->get_option("ct_custom_css_loader"); ?>
         </div>

      <?php   } elseif ($setting->get_option("ct_loader") == 'gif' && $setting->get_option("ct_custom_gif_loader") != '') { ?>
         <div class="ct-loading-main" align="center">
            <img style="margin-top:18%;" src="<?php echo BASE_URL; ?>/assets/images/gif-loader/<?php echo $setting->get_option("ct_custom_gif_loader"); ?>"></img>
         </div>
      <?php   } else { ?>

         <div class="ct-loading-main">
            <div class="loader">Loading...</div>
         </div>
      <?php   } ?>
      <header class="ct-header">
         <?php if (isset($_SESSION['ct_adminid'])) {  ?>
            <div id="cta-top-nav" class="navbar-inner">
               <nav role="navigation" class="navbar navbar-inverse navbar-fixed-top">
                  <!-- Brand and toggle get grouped for better mobile display -->
                  <div class="container">
                     <div class="navbar-header">
                        <button type="button" data-target="#navbarCollapsetop" style="float:right" data-toggle="collapse" class="navbar-toggle">
                           <span class="sr-only">Toggle navigation</span>
                           <i class="fa fa-cog"></i>
                        </button>
                        <a href="<?php echo BASE_URL; ?>" class="navbar-brand"><?php echo $setting->get_option('ct_company_name'); ?></a>
                     </div>

                     <!-- Collection of nav links and other content for toggling -->
                     <div id="navbarCollapsetop" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                           <?/*php<li><a href="<?php echo BASE_URL; ?>/admin/cleanto-welcome.php">
                                 <span><?php echo $label_language_values['whats_new']; ?></span></a>
                           </li>*/?>
                           <?/*php $today_date = date("Y-m-d");
                           $first_showing_month_date = date("Y-m-05");
                           $second_showing_month_date = date("Y-m-15");
                           $third_showing_month_date = date("Y-m-20");
                           if ($today_date <= $first_showing_month_date) {
                              $version_updated_checker = $setting->get_contents('http://skymoonlabs.com/cleanto/versioncheck.php?' . time());
                              if ($version_updated_checker > $setting->get_option("ct_version")) {                     ?>
                                 <li><a href="#ct-update-version-modal" class="pulse-update" title="Cleanto Update Available" data-toggle="modal"><i class="fa fa-download"></i></a>
                                 </li>
                              <?php echo $setting->get_contents('http://skymoonlabs.com/cleanto/versiondetailpopup.php?' . time());
                              }
                           } elseif ($today_date >= $second_showing_month_date && $today_date <= $third_showing_month_date) { 
                              $version_updated_checker = $setting->get_contents('http://skymoonlabs.com/cleanto/versioncheck.php?' . time());
                              if ($version_updated_checker > $setting->get_option("ct_version")) {                     ?>
                                 <li><a href="#ct-update-version-modal" class="pulse-update" title="Cleanto Update Available" data-toggle="modal"><i class="fa fa-download"></i></a>
                                 </li>
                           <?php echo $setting->get_contents('http://skymoonlabs.com/cleanto/versiondetailpopup.php?' . time());
                              }
                           } */?>
                           <?/*php<li><a href="#ct-buy-support-modal" class="pulse-update" title="Cleanto Support" data-toggle="modal"><i class="fa fa-ticket"></i> Support</a></li>
                           <li><a href="<?php echo BASE_URL; ?>/admin/extensions.php" class="pulse-update" title="Cleanto Extensions" data-toggle="modal"><i class="fa fa-puzzle-piece"></i> Extensions</a></li>*/?>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                           <li> <a id="ct-notifications" href="javascript:void(0);">
                                 <?php
                                 $t = @mysqli_num_rows($objdashboard->getallbookingsunread_count()); ?>
                                 <i class="icon-bell <?php if ($t != 0) { ?> cta-new-booking <?php   } ?>"></i> <?php if ($t != 0) {  ?>

                                    <span class="total_notification noti_color" id="ct-notification-top">
                                       <?php
                                                                                                                     $t = mysqli_num_rows($objdashboard->getallbookingsunread_count());
                                                                                                                     if ($t != 0) {
                                                                                                                        echo mysqli_num_rows($objdashboard->getallbookingsunread_count());
                                                                                                                     } ?>
                                    </span>
                                 <?php  } else {  ?>
                                    <span class="total_notification noti_color" id="ct-notification-top"></span>
                                 <?php  }   ?>
                                 <i class="fa fa-angle-down"></i></a></li>
                           <li><a href="<?php echo SITE_URL . "admin/admin-profile.php" ?>"><i class="fa fa-user"></i><span><?php echo $label_language_values['profile']; ?></span></a></li>
                           <li><a id="logout" href="javascript:void(0)"><i class="fa fa-power-off"></i><span><?php echo $label_language_values['logout']; ?></span></a></li>
                        </ul>
                     </div>
                  </div>
               </nav>
            </div>
            <!-- top bar end here -->
            <!-- recent notifications listing -->
            <div class="ct-overlay-notification"></div>
            <div id="ct-notification-container">
               <div class="ct-notifications-inner">
                  <div class="ct-notification-main">
                     <div class="ct-notification-main">
                        <h4 class="notification-header"><?php echo $label_language_values['booking_notifications']; ?>
                           <a id="ct-close-notifications" class="pull-right" href="javascript:void(0);" title="<?php echo $label_language_values['close_notifications']; ?>"><i>×</i></a>
                        </h4>
                        <div class="ct-recent-booking-container">
                           <div class="ct-load-bar">
                              <div class="ct-bar"></div>
                              <div class="ct-bar"></div>
                              <div class="ct-bar"></div>
                           </div>
                           <ul class="ct-recent-booking-list myloadednotification"> </ul>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- end recent notifications -->
         <?php   } ?>

         <?php if (isset($_SESSION['ct_adminid'])) {    ?>
            <div id="cta-main-navigation" class="navbar-inner">
               <nav role="navigation" class="navbar navbar-inverse navbar-fixed-top cta-admin-nav">
                  <div class="container">
                     <!-- Brand and toggle get grouped for better mobile display -->
                     <div class="navbar-header">
                        <button type="button" style="float:right" data-target="#navbarCollapseMain" data-toggle="collapse" class="navbar-toggle">
                           <span class="sr-only">Toggle navigation</span>
                           <i class="fa fa-bars"></i></button>
                        <a href="javascript:void(0);" class="navbar-brand">Menu</a>
                     </div>
                     <!-- Collection of nav links and other content for toggling -->
                     <div id="navbarCollapseMain" class="collapse navbar-collapse">
                        <!--<li class="--> <?php   /* if(strpos($_SERVER['SCRIPT_NAME'],'index.php')!=false){ echo 'active';} */ ?>
                        <!--"><a href="--><?php /* echo BASE_URL; */ ?>
                        <!-- /index.php"><i class="fa fa-dashboard"></i>Dashboard</a></li> -->
                        <ul class="nav navbar-nav cta-nav-tab">
                           <li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'calendar.php') != false) {
                                          echo 'active';
                                       } ?>"><a href="<?php echo BASE_URL; ?>/admin/calendar.php"><i class="fa fa-calendar"></i><span><?/*php echo $label_language_values['appointments']; */?>Prenotazioni</span></a>
                           </li>
                           <li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'services.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'service-manage-calculation-methods.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'service-extra-addons.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'service-manage-unit-price.php') != false) {
                                          echo 'active';
                                       } ?>"><a href="<?php echo BASE_URL; ?>/admin/services.php">
                                 <i class="fa fa-tasks"></i><span><?/*php echo $label_language_values['services']; */?>Esami</span> </a>
                           </li>
                           <li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'schedule.php') != false) {
                                          echo 'active';
                                       } ?>"><a href="<?php echo BASE_URL; ?>/admin/schedule.php"><i class="fa fa-clock-o"></i><span><?/*php echo $label_language_values['schedule']; */?>Pianificazione Blocchi
                                 </span></a>
                           </li>
                            <?php if ($_SESSION['ct_adminid']==1) { ?>
                           <li class="<?/*php if (strpos($_SERVER['SCRIPT_NAME'], 'staff.php') != false) {
                                          echo 'active';
                                       } ?>"><a class="staff_link_clicked" href="<?php echo BASE_URL; ?>/admin/staff.php"><i class="fa fa-user-circle-o"></i><span> <?php echo $label_language_values['staff']; */?></span></a>
                           </li>
                           <?php } ?>
                           <li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'customers.php') != false) {
                                             echo 'active';
                                          } ?>"><a href="<?php echo BASE_URL; ?>/admin/customers.php"><i class="fa fa-users"></i><span><?/*php echo $label_language_values['customers']; */?>Clienti</span></a>
                           </li>
                           <?php if ($_SESSION['ct_adminid']==1) { ?>
                           <li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'crmn.php') != false) {
                                          echo 'active';
                                       }
                                       if (strpos($_SERVER['SCRIPT_NAME'], 'emlsms.php') != false) {
                                          echo 'active';
                                       } ?>"><a href="<?php echo BASE_URL; ?>/admin/crmn.php"><i class="fa fa-users"></i><span><?php echo  $label_language_values['crm']; ?></span></a>
                           </li>
                           <?php } ?>
                           <li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'payments.php') != false) {
                                          echo 'active';
                                       } ?>"><a href="<?php echo BASE_URL; ?>/admin/payments.php"><i class="fa fa-money"></i> <span><?php echo $label_language_values['payments']; ?>
                                 </span></a>
                           </li>
                           <?/*<?php if ($_SESSION['ct_adminid']==1) { ?>
                           <li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'settings.php') != false) {
                                          echo 'active';
                                       } ?>"><a href="<?php echo BASE_URL; ?>/admin/settings.php"><i class="fa fa-cog"></i><span><?php echo $label_language_values['settings']; ?></span></a>
                           </li>
                           <?php } ?>*/?>
                           <li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'export.php') != false) {
                                          echo 'active';
                                       } ?>"><a href="<?php echo BASE_URL; ?>/admin/export.php"><i class="fa fa-file-pdf-o"></i> <span><?php echo $label_language_values['export']; ?></span></a>
                           </li>
                           <?php/*<?php if ($_SESSION['ct_adminid']==1) { ?>
                           <li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'embedcode.php') != false) {
                                          echo 'active';
                                       } ?>"><a href="<?php echo BASE_URL; ?>/admin/embedcode.php"><i class="fa fa-square-o"></i> <span><?php echo $label_language_values['embed_code']; ?></span></a>
                           </li>
                            <?php } ?>*/?>
                           <?php if ($setting->get_option('ct_sample_data_status') == 'N') {   ?>
                              <li class=""><a href="javascript:void(0);" id="ct-sample-data">
                                    <i class="fa fa-download"></i><span><?php echo $label_language_values['add_sample_data']; ?></span></a>
                              </li>
                           <?php } elseif ($setting->get_option('ct_sample_data_status') == 'Y') { ?>
                              <?/*php<li class=""><a href="javascript:void(0);" id="ct-remove-sample-data">
                                    <i class="fa fa-eraser"></i><span><?php echo $label_language_values['remove_sample_data']; ?></span></a>
                              </li>*/?>
                           <?php }  ?>
                        </ul>
                     </div>
                  </div>
               </nav>
            </div>
            <!-- top bar end here -->
         <?php       } else {         ?>
            <!--    USER MENUS    -->
            <div id="cta-main-navigation" class="navbar-inner">
               <nav role="navigation" class="navbar navbar-inverse navbar-fixed-top">
                  <div class="container">
                     <!-- Brand and toggle get grouped for better mobile display -->
                     <div class="navbar-header">
                        <button type="button" data-target="#navbarCollapseMain" data-toggle="collapse" class="navbar-toggle">
                           <span class="sr-only">Toggle navigation</span>
                           <i class="fa fa-bars"></i>
                        </button>
                        <a href="javascript:void(0);" class="navbar-brand">Menu</a>
                     </div>
                     <!-- Collection of nav links and other content for toggling -->
                     <div id="navbarCollapseMain" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav user-nav-bar">
                           <li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'my-appointments.php') != false) {
                                          echo 'active';
                                       } ?>"><a href="<?php echo BASE_URL; ?>/admin/my-appointments.php"><i class="fa fa-calendar"></i><span><?php echo $label_language_values['my_appointments']; ?></span></a></li>
                           <li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'user-profile.php') != false) {
                                          echo 'active';
                                       } ?>"><a href="<?php echo BASE_URL; ?>/admin/user-profile.php"><i class="fa fa-user"></i><span><?php echo $label_language_values['profile']; ?></span></a></li>
                           <?php if ($setting->get_option('ct_wallet_section') == 'on') { ?>
                              <?php if ($setting->get_option('ct_referral_status') == 'Y') { ?>
                                 <li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'user_referral_code.php') != false) {
                                                echo 'active';
                                             } ?>"><a href="<?php echo BASE_URL; ?>/admin/user_referral_code.php"><i class="fa fa-usd"></i><span>Invite and earn</span>
                                    </a></li>
                              <?php } ?>

                              <li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 'wallet-history.php') != false) {
                                             echo 'active';
                                          } ?>">
                                 <a href="<?php echo BASE_URL; ?>/admin/wallet-history.php"><i class="fa fa-google-wallet"></i><span><?php echo $label_language_values['wallet_history']; ?></span></a>
                              </li>
                           <?php } ?>
                           <li><a id="logout" data-id="user" href="javascript:void(0)"><i class="fa fa-power-off"></i><span><?php echo $label_language_values['logout']; ?></span></a></li>
                        </ul>
                     </div>
                  </div>
               </nav>
            </div>
            <!-- top bar end here -->
         <?php    }  ?>
         <div id="booking-details-dashboard" class="modal fade booking-details-index-dashboard" tabindex="-1" role="dialog" aria-hidden="true"></div>
         <div id="GC-details-dashboard" class="modal fade GC-details-index-dashboard" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md">
               <div class="modal-content">
                  <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                     <h4 class="modal-title"><?php echo $label_language_values['reschedule']; ?></h4>
                  </div>
                  <div class="modal-body">
                     <div class="col-xs-12">
                        <div class="form-group">
                           <label class="cta-col2 ct-w-50"><?php echo $label_language_values['date_and_time']; ?>:</label>
                           <div class="cta-col4 ct-w-50">
                              <?php $staff_id = 1;
                              $today_date = date("Y-m-d"); ?>
                              <input class="exp_cp_date form-control" id="gc_date_check" data-staffid="<?php echo $staff_id; ?>" value="<?php echo $today_date; ?>" data-date-format="yyyy/mm/dd" data-provide="datepicker" />
                           </div>
                           <div class="cta-col6 ct-w-50 float-right mytime_slots_booking"> </div>
                           <div class="clearfix"></div>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <a href="javascript:void(0);" class="pull-left btn btn-info" id="edit_gc_reschedual" data-gc_event="" data-duration=""><?php echo $label_language_values['update_appointment']; ?></a>
                  </div>
               </div>
            </div>
         </div>
      </header>
      <?php include(dirname(__FILE__) . "/language_js_objects.php");   ?>
      <script type="text/javascript">
         var ajax_url = '<?php echo AJAX_URL; ?>';
         var base_url = '<?php echo BASE_URL; ?>';
         var times = {
            'time_format_values': "<?php echo $gettimeformat; ?>"
         };
         var language_new = {
            'selected_language': "<?php echo substr($lang, strpos($lang, chr(0)), strpos($lang, "chr(_)"));; ?>"
         };
         var titles = {
            'selected_today': "<?php echo $label_language_values['calendar_today']; ?>",
            'selected_month': "<?php echo $label_language_values['calendar_month']; ?>",
            'selected_week': "<?php echo $label_language_values['calendar_week']; ?>",
            'selected_day': "<?php echo $label_language_values['calendar_day']; ?>"
         };
         var site_ur = {
            'site_url': "<?php echo SITE_URL; ?>"
         };

         <?php $nacode = explode(',', $setting->get_option("ct_company_country_code"));
         $allowed = $setting->get_option("ct_phone_display_country_code");   ?>
         var ct_calendar_defaultView = '<?php if ($setting->get_option("ct_calendar_defaultView") != '') {
                                             echo $setting->get_option("ct_calendar_defaultView");
                                          } else {
                                             echo 'month';
                                          } ?>';
         var ct_calendar_firstDay = '<?php if ($setting->get_option("ct_calendar_firstDay") != '') {
                                          echo $setting->get_option("ct_calendar_firstDay");
                                       } else {
                                          echo '1';
                                       } ?>';
         var countrycodeObj = {
            'numbercode': '<?php echo $nacode[0]; ?>',
            'alphacode': '<?php echo $nacode[1]; ?>',
            'countrytitle': '<?php echo $nacode[2]; ?>',
            'allowed': '<?php echo $allowed; ?>'
         };
         var month = {
            'january': '<?php echo ucfirst(strtolower($label_language_values['january'])); ?>',
            'feb': '<?php echo ucfirst(strtolower($label_language_values['february'])); ?>',
            'mar': '<?php echo ucfirst(strtolower($label_language_values['march'])); ?>',
            'apr': '<?php echo ucfirst(strtolower($label_language_values['april'])); ?>',
            'may': '<?php echo ucfirst(strtolower($label_language_values['may'])); ?>',
            'jun': '<?php echo ucfirst(strtolower($label_language_values['june'])); ?>',
            'jul': '<?php echo ucfirst(strtolower($label_language_values['july'])); ?>',
            'aug': '<?php echo ucfirst(strtolower($label_language_values['august'])); ?>',
            'sep': '<?php echo ucfirst(strtolower($label_language_values['september'])); ?>',
            'oct': '<?php echo ucfirst(strtolower($label_language_values['october'])); ?>',
            'nov': '<?php echo ucfirst(strtolower($label_language_values['november'])); ?>',
            'dec': '<?php echo ucfirst(strtolower($label_language_values['december'])); ?>'
         };
         var days_date = {
            'sun': '<?php echo ucfirst($label_language_values['su']); ?>',
            'mon': '<?php echo ucfirst($label_language_values['mo']); ?>',
            'tue': '<?php echo ucfirst($label_language_values['tu']); ?>',
            'wed': '<?php echo ucfirst($label_language_values['we']); ?>',
            'thu': '<?php echo ucfirst($label_language_values['th']); ?>',
            'fri': '<?php echo ucfirst($label_language_values['fr']); ?>',
            'sat': '<?php echo ucfirst($label_language_values['sa']); ?>'
         };
      </script>

      <!-- all alerts, success messages -->
      <div class="ct-alert-msg-show-main mainheader_message">
         <div class="ct-all-alert-messags alert alert-success mainheader_message_inner">
            <!-- <a href="#" class="close" data-dismiss="alert">&times;</a> -->
            <strong><?php echo $label_language_values['success'] . " "; ?></strong>
            <span id="ct_sucess_message"> </span>
         </div>
      </div>
      <div class="ct-alert-msg-show-main mainheader_message_fail">
         <div class="ct-all-alert-messags alert alert-danger mainheader_message_inner_fail">
            <!-- <a href="#" class="close" data-dismiss="alert">&times;</a> -->
            <strong><?php echo $label_language_values['failed'] . " "; ?></strong>
            <span id="ct_sucess_message_fail"></span>
         </div>
      </div>
      <div id="ct-remove-sample-data-popup" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content" style="margin-top: 180px;">
               <div class="modal-body">
                  <h4><?php echo $label_language_values['remove_sample_data_message']; ?></h4>
               </div>
               <div class="modal-footer">
                  <button id="ct-remove-sample-data-ok" class="btn btn-success" data-dismiss="modal"><?php echo $label_language_values['ok_remove_sample_data']; ?></button>
                  <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $label_language_values['cancel']; ?></button>
               </div>
            </div>
         </div>
      </div>

      <div class="modal fade" id="myModal_reschedual" role="dialog"></div>
      <?php if ($gc_hook->gc_purchase_status() == 'exist') {
         if ($setting->get_option('ct_gc_status_configure') == 'Y' && $setting->get_option('ct_gc_status') == 'Y') { ?>
            <input type="hidden" id="extension_js" value="true" />
         <?php    } else {    ?>
            <input type="hidden" id="extension_js" value="false" />
      <?php  }
      }
      $english_date_array = array("January", "Jan", "February", "Feb", "March", "Mar", "April", "Apr", "May", "June", "Jun", "July", "Jul", "August", "Aug", "September", "Sep", "October", "Oct", "November", "Nov", "December", "Dec", "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "su", "mo", "tu", "we", "th", "fr", "sa", "AM", "PM");
      $selected_lang_label = array(ucfirst(strtolower($label_language_values['january'])), ucfirst(strtolower($label_language_values['jan'])), ucfirst(strtolower($label_language_values['february'])), ucfirst(strtolower($label_language_values['feb'])), ucfirst(strtolower($label_language_values['march'])), ucfirst(strtolower($label_language_values['mar'])), ucfirst(strtolower($label_language_values['april'])), ucfirst(strtolower($label_language_values['apr'])), ucfirst(strtolower($label_language_values['may'])), ucfirst(strtolower($label_language_values['june'])), ucfirst(strtolower($label_language_values['jun'])), ucfirst(strtolower($label_language_values['july'])), ucfirst(strtolower($label_language_values['jul'])), ucfirst(strtolower($label_language_values['august'])), ucfirst(strtolower($label_language_values['aug'])), ucfirst(strtolower($label_language_values['september'])), ucfirst(strtolower($label_language_values['sep'])), ucfirst(strtolower($label_language_values['october'])), ucfirst(strtolower($label_language_values['oct'])), ucfirst(strtolower($label_language_values['november'])), ucfirst(strtolower($label_language_values['nov'])), ucfirst(strtolower($label_language_values['december'])), ucfirst(strtolower($label_language_values['dec'])), ucfirst(strtolower($label_language_values['sun'])), ucfirst(strtolower($label_language_values['mon'])), ucfirst(strtolower($label_language_values['tue'])), ucfirst(strtolower($label_language_values['wed'])), ucfirst(strtolower($label_language_values['thu'])), ucfirst(strtolower($label_language_values['fri'])), ucfirst(strtolower($label_language_values['sat'])), ucfirst(strtolower($label_language_values['su'])), ucfirst(strtolower($label_language_values['mo'])), ucfirst(strtolower($label_language_values['tu'])), ucfirst(strtolower($label_language_values['we'])), ucfirst(strtolower($label_language_values['th'])), ucfirst(strtolower($label_language_values['fr'])), ucfirst(strtolower($label_language_values['sa'])), strtoupper($label_language_values['am']), strtoupper($label_language_values['pm']));
      ?>