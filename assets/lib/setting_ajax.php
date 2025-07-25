<?php  

session_start();
include(dirname(dirname(dirname(__FILE__))).'/objects/class_connection.php');
include(dirname(dirname(dirname(__FILE__))).'/objects/class_setting.php');
include(dirname(dirname(dirname(__FILE__))) . "/objects/class_services.php");
include(dirname(dirname(dirname(__FILE__)))."/header.php");
include(dirname(dirname(dirname(__FILE__))).'/objects/class_frequently_discount.php');
include(dirname(dirname(dirname(__FILE__))).'/objects/class_users.php');
include(dirname(dirname(dirname(__FILE__)))."/objects/class_gc_hook.php");
include(dirname(dirname(dirname(__FILE__)))."/objects/class_payment_hook.php");
include(dirname(dirname(dirname(__FILE__))).'/objects/class.phpmailer.php');
include(dirname(dirname(dirname(__FILE__))).'/objects/class_front_first_step.php');
$database=new cleanto_db();
$setting=new cleanto_setting();
$conn=$database->connect();
$database->conn=$conn;
$setting->conn=$conn;
$objservice = new cleanto_services();
$objservice->conn = $conn;
$objuser = new cleanto_users();
$objuser->conn = $conn;
$objfrequently = new cleanto_frequently_discount();
$objfrequently->conn = $conn;
$gc_hook = new cleanto_gcHook();
$gc_hook->conn = $conn;
$payment_hook = new cleanto_paymentHook();
$payment_hook->conn = $conn;
$first_step=new cleanto_first_step();
$first_step->conn=$conn;
$lang = $setting->get_option("ct_language");
$label_language_values = array();
$language_label_arr = $setting->get_all_labelsbyid($lang);

if($setting->get_option('ct_smtp_authetication') == 'true'){
	$mail_SMTPAuth = '1';
	if($setting->get_option('ct_smtp_hostname') == "smtp.gmail.com"){
		$mail_SMTPAuth = 'Yes';
	}
	
}else{
	$mail_SMTPAuth = '0';
	if($setting->get_option('ct_smtp_hostname') == "smtp.gmail.com"){
		$mail_SMTPAuth = 'No';
	}
}

$mail = new cleanto_phpmailer();
$mail->Host = $setting->get_option('ct_smtp_hostname');
$mail->Username = $setting->get_option('ct_smtp_username');
$mail->Password = $setting->get_option('ct_smtp_password');
$mail->Port = $setting->get_option('ct_smtp_port');
$mail->SMTPSecure = $setting->get_option('ct_smtp_encryption');
$mail->SMTPAuth = $mail_SMTPAuth;
$mail->CharSet = "UTF-8";

$payment_hook->payment_extenstions_exist();
$purchase_check = $payment_hook->payment_purchase_status();
if ($language_label_arr[1] != "" || $language_label_arr[3] != "" || $language_label_arr[4] != "" || $language_label_arr[5] != "")
{
	$default_language_arr = $setting->get_all_labelsbyid("en");
	if($language_label_arr[1] != ''){
		$label_decode_front = base64_decode($language_label_arr[1]);
	}else{
		$label_decode_front = base64_decode($default_language_arr[1]);
	}
	if($language_label_arr[3] != ''){
		$label_decode_admin = base64_decode($language_label_arr[3]);
	}else{
		$label_decode_admin = base64_decode($default_language_arr[3]);
	}
	if($language_label_arr[4] != ''){
		$label_decode_error = base64_decode($language_label_arr[4]);
	}else{
		$label_decode_error = base64_decode($default_language_arr[4]);
	}
	if($language_label_arr[5] != ''){
		$label_decode_extra = base64_decode($language_label_arr[5]);
	}else{
		$label_decode_extra = base64_decode($default_language_arr[5]);
	}
	
	$label_decode_front_unserial = unserialize($label_decode_front);
	$label_decode_admin_unserial = unserialize($label_decode_admin);
	$label_decode_error_unserial = unserialize($label_decode_error);
	$label_decode_extra_unserial = unserialize($label_decode_extra);
    
	$label_language_arr = array_merge($label_decode_front_unserial,$label_decode_admin_unserial,$label_decode_error_unserial,$label_decode_extra_unserial);
	
	foreach($label_language_arr as $key => $value){
		$label_language_values[$key] = urldecode($value);
	}
}
else
{
    $default_language_arr = $setting->get_all_labelsbyid("en");
	
	$label_decode_front = base64_decode($default_language_arr[1]);
	$label_decode_admin = base64_decode($default_language_arr[3]);
	$label_decode_error = base64_decode($default_language_arr[4]);
	$label_decode_extra = base64_decode($default_language_arr[5]);
		
	
	$label_decode_front_unserial = unserialize($label_decode_front);
	$label_decode_admin_unserial = unserialize($label_decode_admin);
	$label_decode_error_unserial = unserialize($label_decode_error);
	$label_decode_extra_unserial = unserialize($label_decode_extra);
    
	$label_language_arr = array_merge($label_decode_front_unserial,$label_decode_admin_unserial,$label_decode_error_unserial,$label_decode_extra_unserial);
	
	foreach($label_language_arr as $key => $value){
		$label_language_values[$key] = urldecode($value);
	}
}
if(isset($_POST['action']) && $_POST['action']=='add_specail_offer'){

 $setting->special_text=$_POST['special_text'];
 $setting->coupon_type=$_POST['coupon_type'];
 $setting->coupon_value=$_POST['coupon_value'];
 $setting->coupon_date=$_POST['coupon_date'];
 $result=$setting->insert_special_offer();
}

if(isset($_POST['action']) && $_POST['action']=='add_referral_setting'){

 $setting->option_name="ct_referral_status";
 $setting->option_value=$_POST['refer_1'];
 $result=$setting->update_special_offer();

 $setting->option_name="ct_referral_type";
 $setting->option_value=$_POST['percent_ref_flatfree'];
 $result=$setting->update_special_offer();

 $setting->option_name="ct_referral_value";
 $setting->option_value=$_POST['referral_value'];
 $result=$setting->update_special_offer();

 $setting->option_name="ct_refs_type";
 $setting->option_value=$_POST['percent_refs_flatfree'];
 $result=$setting->update_special_offer();
 
 $setting->option_name="ct_refs_value";
 $setting->option_value=$_POST['refs_value'];
 $result=$setting->update_special_offer();
}

if(isset($_POST['action']) && $_POST['action']=='change_language_status'){
	$setting->lang=$_POST['lang'];
	$setting->language_status=$_POST['language_status'];
	$status_change = $result=$setting->language_label_status(); 
	if($status_change){
		echo "ok";
	}else{
		echo "not_ok";
	}
}
if(isset($_POST['action']) && $_POST['action']=='update_company_setting'){
		$exist_lang = $_POST['sel_language'];
		$lang = $setting->get_lang($exist_lang);
		if($lang == 0 )
		{
			$eng = "en";
			$lang_en = $setting->get_lang_en($eng);
			
			$setting->label_data = $lang_en['label_data'];
			$setting->language = $_POST['sel_language'];
			$setting->admin_labels = $lang_en['admin_labels'];
			$setting->error_labels = $lang_en['error_labels'];
			$setting->extra_labels = $lang_en['extra_labels'];
			$setting->front_error_labels = $lang_en['front_error_labels'];
			$setting->language_status = $lang_en['language_status'];
			$setting->app_labels = $lang_en['app_labels'];
			$result=$setting->enter_lang();
		}
    $labels_option=array(
        'ct_company_name'=>ucwords($_POST['company_name']),
        'ct_company_email'=>$_POST['company_email'],
        'ct_company_address'=>$_POST['company_address'],
        'ct_company_city'=>ucwords($_POST['company_city']),
        'ct_company_state'=>ucwords($_POST['company_state']),
        'ct_company_country_code'=>$_POST['company_country_code'],
        'ct_company_zip_code'=>ucwords($_POST['company_zipcode']),
        'ct_company_country'=>ucwords($_POST['company_country']),
        'ct_company_logo'=>$_POST['company_logo'],
        'ct_company_phone'=>$_POST['company_phone'],
		/* 'ct_company_header_address'=>$_POST['company_header_address'], */
		/* 'ct_company_service_desc_status'=>$_POST['company_service_desc_status'], */
		/* 'ct_company_willwe_getin_status'=>$_POST['company_willwe_getin_status'], */
		/* 'ct_company_logo_display'=>$_POST['company_logo_display'], */
        'ct_timezone'=>$_POST['time_zone'],
		'ct_language'=>$_POST['sel_language']
    );
    foreach($labels_option as $option_key=>$option_value){
        $add3=$setting->set_option($option_key,$option_value);
    }
   
	
    chmod(dirname(dirname(dirname(__FILE__)))."/assets/images/services", 0777);
    $used_images = $objservice->get_used_images();
    $used_staff_images = $objservice->get_used_staff_images();
    $imgarr = array();
    while($img  = mysqli_fetch_array($used_images)){
        $filtername = preg_replace('/\\.[^.\\s]{3,4}$/', '', $img[0]);
        array_push($imgarr,$filtername);
    }
    while($img  = mysqli_fetch_array($used_staff_images)){
        $filtername = preg_replace('/\\.[^.\\s]{3,4}$/', '', $img[0]);
        array_push($imgarr,$filtername);
    }
    array_push($imgarr,"default");
    array_push($imgarr,"default_service");
    array_push($imgarr,"default_service1");
    $dir = dirname(dirname(dirname(__FILE__)))."/assets/images/services/";
    $cnt = 1;
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if($cnt > 2){
                $filtername = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
                if (in_array($filtername, $imgarr)) {
                }elseif($file=='..'){
					continue;
				}					
                else{
                    unlink(dirname(dirname(dirname(__FILE__)))."/assets/images/services/".$file);
                }
            }
            $cnt++;
        }
        closedir($dh);
    }
	ob_clean();ob_start();
	if($add3){
		echo "updated";
    }else{
        echo "Record Not Added";
    }
	
}
if(isset($_POST['action']) && $_POST['action']=='update_quickbooks_setting'){
  $labels_option=array(
    'ct_quickbooks_client_ID'=>$_POST['ID'],
    'ct_quickbooks_client_secret'=>$_POST['secret'],
    'ct_quickbooks_status'=>$_POST['status'],
	'ct_qb_account'=>$_POST['account']
  );

  foreach($labels_option as $option_key=>$option_value){
      $add3=$setting->set_option($option_key,$option_value);
  }

  if($add3){
    echo "updated";
  }else{
    echo "Record Not Added";
  }
}
if(isset($_POST['action']) && $_POST['action']=='update_xero_setting'){
  $labels_option=array(
    'ct_xero_client_ID'=>$_POST['ID'],
    'ct_xero_client_secret'=>$_POST['secret'],
    'ct_xero_status'=>$_POST['status']
  );

  foreach($labels_option as $option_key=>$option_value){
      $add3=$setting->set_option($option_key,$option_value);
  }

  if($add3){
    echo "updated";
  }else{
    echo "Record Not Added";
  }
}
if(isset($_POST['ct_google_analytics_code'])){
	if(isset($_FILES)){
		if($_FILES['ct_seo_og_image']['name'] != ''){
			$img = time().'.'.pathinfo($_FILES['ct_seo_og_image']['name'], PATHINFO_EXTENSION);
			$img_type3=array('jpg','jpeg','png','gif');
			$destination3="../images/og_tag_img/".$img;
			$og_image_type=pathinfo($destination3,PATHINFO_EXTENSION);
			if(in_array($og_image_type,$img_type3)){
				move_uploaded_file($_FILES['ct_seo_og_image']['tmp_name'],$destination3);
				$labels_option=array(
					'ct_google_analytics_code'=>$_POST['ct_google_analytics_code'],
					'ct_page_title'=>ucwords($_POST['ct_page_meta_tag']),
					'ct_seo_og_title'=>$_POST['ct_seo_og_title'],
					'ct_seo_og_type'=>$_POST['ct_seo_og_type'],
					'ct_seo_og_url'=>$_POST['ct_seo_og_url'],
					'ct_seo_og_image'=>$img,
					'ct_seo_meta_description'=>$_POST['ct_seo_meta_description']
				);
				foreach($labels_option as $option_key=>$option_value){
					$add3=$setting->set_option($option_key,$option_value);
				}
			}else{
				echo "Invalid Image Type";
				die;
				
			}
		}else{
			$labels_option=array(
				'ct_google_analytics_code'=>$_POST['ct_google_analytics_code'],
				'ct_page_title'=>ucwords($_POST['ct_page_meta_tag']),
				'ct_seo_og_title'=>$_POST['ct_seo_og_title'],
				'ct_seo_og_type'=>$_POST['ct_seo_og_type'],
				'ct_seo_og_url'=>$_POST['ct_seo_og_url'],
				'ct_seo_meta_description'=>$_POST['ct_seo_meta_description']
			);
			foreach($labels_option as $option_key=>$option_value){
				$add3=$setting->set_option($option_key,$option_value);
			}
		}
	}else{
		$labels_option=array(
			'ct_google_analytics_code'=>$_POST['ct_google_analytics_code'],
			'ct_page_title'=>ucwords($_POST['ct_page_meta_tag']),
			'ct_seo_og_title'=>$_POST['ct_seo_og_title'],
			'ct_seo_og_type'=>$_POST['ct_seo_og_type'],
			'ct_seo_og_url'=>$_POST['ct_seo_og_url'],
			'ct_seo_meta_description'=>$_POST['ct_seo_meta_description']
		);
		foreach($labels_option as $option_key=>$option_value){
			$add3=$setting->set_option($option_key,$option_value);
		}
	}
}
/*Update Company logo*/
elseif(isset($_POST['action']) && $_POST['action']=='delete_logo'){
    $update_logo=array('ct_company_logo'=>"");
    foreach($update_logo as $option_key=>$option_value){
        $logo=$setting->set_option($option_key,$option_value);
    }
}
/* Below code is use for save value of General settings */
elseif(isset($_POST['action']) && $_POST['action']=='update_general_setting'){
    switch($_POST['currency']) {
        case 'ALL': $currency_symbol = 'Lek'; break;
        case 'AFN': $currency_symbol = '؋'; break;
        case 'ARS': $currency_symbol = '$'; break;
        case 'AWG': $currency_symbol = 'ƒ'; break;
        case 'AUD': $currency_symbol = '$'; break;
        case 'AZN': $currency_symbol = 'ман'; break;
        case 'AED': $currency_symbol = 'د.إ'; break;
        case 'ANG': $currency_symbol = 'NAƒ'; break;
        case 'BSD': $currency_symbol = '$'; break;
        case 'BBD': $currency_symbol = '$'; break;
        case 'BYR': $currency_symbol = 'p.'; break;
        case 'BZD': $currency_symbol = 'BZ$'; break;
        case 'BMD': $currency_symbol = '$'; break;
        case 'BOB': $currency_symbol = '$b'; break;
        case 'BAM': $currency_symbol = 'KM'; break;
        case 'BWP': $currency_symbol = 'P'; break;
        case 'BGN': $currency_symbol = 'лв'; break;
        case 'BRL': $currency_symbol = 'R$'; break;
        case 'BND': $currency_symbol = '$'; break;
        case 'BDT': $currency_symbol = 'Tk'; break;
        case 'BIF': $currency_symbol = 'FBu'; break;
        case 'KHR': $currency_symbol = '៛'; break;
        case 'CAD': $currency_symbol = '$'; break;
        case 'KYD': $currency_symbol = '$'; break;
        case 'CLP': $currency_symbol = '$'; break;
        case 'CNY': $currency_symbol = '¥'; break;
        case 'CYN': $currency_symbol = '¥'; break;
        case 'COP': $currency_symbol = '$'; break;
        case 'CRC': $currency_symbol = '₡'; break;
        case 'HRK': $currency_symbol = 'kn'; break;
        case 'CUP': $currency_symbol = '₱'; break;
        case 'CZK': $currency_symbol = 'Kč'; break;
        case 'CVE': $currency_symbol = 'Esc'; break;
        case 'CHF': $currency_symbol = 'CHF'; break;
        case 'DKK': $currency_symbol = 'kr'; break;
        case 'DOP': $currency_symbol = 'RD$'; break;
        case 'DJF': $currency_symbol = 'Fdj'; break;
        case 'DZD': $currency_symbol = 'دج'; break;
        case 'XCD': $currency_symbol = '$'; break;
        case 'EGP': $currency_symbol = '£'; break;
        case 'SVC': $currency_symbol = '$'; break;
        case 'EEK': $currency_symbol = 'kr'; break;
        case 'EUR': $currency_symbol = '€'; break;
        case 'ETB': $currency_symbol = 'Br'; break;
        case 'FKP': $currency_symbol = '£'; break;
        case 'FJD': $currency_symbol = '$'; break;
        case 'GHC': $currency_symbol = '¢'; break;
        case 'GIP': $currency_symbol = '£'; break;
        case 'GTQ': $currency_symbol = 'Q'; break;
        case 'GGP': $currency_symbol = '£'; break;
        case 'GYD': $currency_symbol = '$'; break;
        case 'GMD': $currency_symbol = 'D'; break;
        case 'GNF': $currency_symbol = 'FG'; break;
        case 'HNL': $currency_symbol = 'L'; break;
        case 'HKD': $currency_symbol = '$'; break;
        case 'HUF': $currency_symbol = 'Ft'; break;
        case 'HRK': $currency_symbol = 'kn'; break;
        case 'HTG': $currency_symbol = 'G'; break;
        case 'ISK': $currency_symbol = 'kr'; break;
        case 'INR': $currency_symbol = 'Rs.'; break;
        case 'IDR': $currency_symbol = 'Rp'; break;
        case 'IRR': $currency_symbol = '﷼'; break;
        case 'IMP': $currency_symbol = '£'; break;
        case 'ILS': $currency_symbol = '₪'; break;
        case 'JMD': $currency_symbol = 'J$'; break;
        case 'JPY': $currency_symbol = '¥'; break;
        case 'JEP': $currency_symbol = '£'; break;
        case 'KZT': $currency_symbol = 'лв'; break;
        case 'KPW': $currency_symbol = '₩'; break;
        case 'KRW': $currency_symbol = '₩'; break;
        case 'KGS': $currency_symbol = 'лв'; break;
        case 'KES': $currency_symbol = 'KSh'; break;
        case 'KMF': $currency_symbol = 'KMF'; break;
        case 'LAK': $currency_symbol = '₭'; break;
        case 'LVL': $currency_symbol = 'Ls'; break;
        case 'LBP': $currency_symbol = '£'; break;
        case 'LRD': $currency_symbol = '$'; break;
        case 'LTL': $currency_symbol = 'Lt'; break;
        case 'MKD': $currency_symbol = 'ден'; break;
        case 'MYR': $currency_symbol = 'RM'; break;
        case 'MUR': $currency_symbol = '₨'; break;
        case 'MXN': $currency_symbol = '$'; break;
        case 'MNT': $currency_symbol = '₮'; break;
        case 'MZN': $currency_symbol = 'MT'; break;
        case 'MDL': $currency_symbol = 'MDL'; break;
        case 'MOP': $currency_symbol = '$'; break;
        case 'MRO': $currency_symbol = 'UM'; break;
        case 'MVR': $currency_symbol = 'Rf'; break;
        case 'MWK': $currency_symbol = 'MK'; break;
        case 'MAD': $currency_symbol = 'د.م.'; break;
        case 'NAD': $currency_symbol = '$'; break;
        case 'NPR': $currency_symbol = '₨'; break;
        case 'ANG': $currency_symbol = 'ƒ'; break;
        case 'NZD': $currency_symbol = '$'; break;
        case 'NIO': $currency_symbol = 'C$'; break;
        case 'NGN': $currency_symbol = '₦'; break;
        case 'NOK': $currency_symbol = 'kr'; break;
        case 'OMR': $currency_symbol = '﷼'; break;
        case 'PKR': $currency_symbol = '₨'; break;
        case 'PAB': $currency_symbol = 'B/.'; break;
        case 'PYG': $currency_symbol = 'Gs'; break;
        case 'PEN': $currency_symbol = 'S/.'; break;
        case 'PHP': $currency_symbol = '₱'; break;
        case 'PLN': $currency_symbol = 'zł'; break;
        case 'PGK': $currency_symbol = 'K'; break;
        case 'QAR': $currency_symbol = '﷼'; break;
        case 'RON': $currency_symbol = 'lei'; break;
        case 'RUB': $currency_symbol = 'руб'; break;
        case 'SHP': $currency_symbol = '£'; break;
        case 'SAR': $currency_symbol = '﷼'; break;
        case 'RSD': $currency_symbol = 'Дин.'; break;
        case 'SCR': $currency_symbol = '₨'; break;
        case 'SGD': $currency_symbol = '$'; break;
        case 'SBD': $currency_symbol = '$'; break;
        case 'SOS': $currency_symbol = 'S'; break;
        case 'ZAR': $currency_symbol = 'R'; break;
        case 'LKR': $currency_symbol = '₨'; break;
        case 'SEK': $currency_symbol = 'kr'; break;
        case 'CHF': $currency_symbol = 'CHF'; break;
        case 'SRD': $currency_symbol = '$'; break;
        case 'SYP': $currency_symbol = '£'; break;
        case 'SLL': $currency_symbol = 'Le'; break;
        case 'STD': $currency_symbol = 'Db'; break;
        case 'TWD': $currency_symbol = 'NT'; break;
        case 'THB': $currency_symbol = '฿'; break;
        case 'TTD': $currency_symbol = 'TTD'; break;
        case 'TRY': $currency_symbol = '₤'; break;
        case 'TVD': $currency_symbol = '$'; break;
        case 'TOP': $currency_symbol = 'T$'; break;
        case 'TZS': $currency_symbol = 'x'; break;
        case 'UAH': $currency_symbol = '₴'; break;
        case 'GBP': $currency_symbol = '£'; break;
        case 'USD': $currency_symbol = '$'; break;
        case 'UYU': $currency_symbol = '$U'; break;
        case 'UZS': $currency_symbol = 'лв'; break;
        case 'UGX': $currency_symbol = 'USh'; break;
        case 'VEF': $currency_symbol = 'Bs'; break;
        case 'VND': $currency_symbol = '₫'; break;
        case 'VUV': $currency_symbol = 'Vt'; break;
        case 'WST': $currency_symbol = 'WS$'; break;
        case 'XAF': $currency_symbol = 'BEAC'; break;
        case 'XOF': $currency_symbol = 'BCEAO'; break;
        case 'XPF': $currency_symbol = 'F'; break;
        case 'YER': $currency_symbol = '﷼'; break;
        case 'ZWD': $currency_symbol = 'Z$'; break;
        case 'ZAR': $currency_symbol = 'R'; break;
        case 'KZ': $currency_symbol = 'AOA'; break;
        default: $currency_symbol = '$'; break;
    }
    $postalcode = preg_split('/\r\n|[\r\n]/', $_POST['ct_postal_code']);
    $converted_postalcode = implode(',',$postalcode);
    $ct_option=array(
		'ct_calculation_policy'=>$_POST['ct_calculation_policy'],
        'ct_time_interval'=>$_POST['time_interval'],
        'ct_allow_privacy_policy'=>$_POST['ct_allow_privacy_policy'],
        'ct_privacy_policy_link'=>urldecode($_POST['ct_privacy_policy_link']),
        'ct_addons_default_design'=>$_POST['ct_addons_default_design'],
        'ct_method_default_design'=>$_POST['ct_method_default_design'],
        'ct_service_default_design'=>$_POST['ct_service_default_design'],
        'ct_cart_scrollable'=>$_POST['ct_cart_scrollable'],
        'ct_terms_condition_link'=>urldecode($_POST['ct_terms_condition_link']),
        'ct_front_desc'=>urldecode($_POST['ct_front_desc']),
        'ct_min_advance_booking_time'=>$_POST['min_advanced_booking'],
        'ct_max_advance_booking_time'=>$_POST['max_advanced_booking'],
        'ct_booking_padding_time'=>$_POST['booking_padding_time'],
        'ct_service_padding_time_before'=>$_POST['service_padding_time_before'],
        'ct_service_padding_time_after'=>$_POST['service_padding_time_after'],
        'ct_cancellation_buffer_time'=>$_POST['cancelled_buffer_time'],
        'ct_reshedule_buffer_time'=>$_POST['reshedule_buffer_time'],
        'ct_currency'=>$_POST['currency'],
        'ct_currency_symbol_position'=>$_POST['currency_symbol_position'],
        'ct_service_design'=>$_POST['ct_service_design'],
        'ct_price_format_decimal_places'=>$_POST['price_format_decimal_places'],
        'ct_tax_vat_status'=>$_POST['tax_vat_1'],
        'ct_tax_vat_type'=>$_POST['percent_flatfree'],
        'ct_tax_vat_value'=>$_POST['tax_vat_value'],
        'ct_postalcode_status'=>$_POST['postal_code_1'],
        'ct_partial_deposit_status'=>$_POST['status_partial'],
		'ct_cancelation_policy_status'=>$_POST['cancel_policy_status'],
		'ct_cancel_policy_header'=>$_POST['cancel_policy_header'],
		'ct_cancel_policy_textarea'=>$_POST['cancel_policy_textarea'],
		'ct_partial_type'=>$_POST['partial_percent_flatfree'],
        'ct_partial_deposit_amount'=>$_POST['partial_deposit_amount'],
        'ct_partial_deposit_message'=>$_POST['partial_deposit_message'],
        'ct_thankyou_page_url'=>urldecode($_POST['thanks_url']),
        'ct_allow_multiple_booking_for_same_timeslot_status'=>$_POST['allow_multiple_booking_for_same_timeslot'],
        'ct_appointment_auto_confirm_status'=>$_POST['appointment_auto_confirmation'],
        'ct_star_show_on_front'=>$_POST['star_show_on_frontend'],
        'ct_allow_day_closing_time_overlap_booking'=>$_POST['allow_time_overlap_booking'],
        'ct_allow_terms_and_conditions'=>$_POST['allow_terms_and_condition'],
        'ct_allow_front_desc'=>$_POST['ct_allow_front_desc'],
        'ct_currency_symbol'=>$currency_symbol,
		/*'ct_subheaders'=>$_POST['ct_subheaders'],
		'ct_vc_status'=>$_POST['ct_vc_status'],
		'ct_p_status'=>$_POST['ct_p_status'],*/
		'ct_user_zip_code'=>$_POST['ct_user_zip_code'],
		'ct_booking_page_design'=>$_POST['ct_booking_page_design'],
		'ct_google_api_key'=>urldecode($_POST['google_api_key']),
		'ct_minimum_booking_price'=>urldecode($_POST['minimum_booking_price']),
        'ct_additional_slot_time'=>$_POST['ct_additional_slot_time'],
        'ct_staff_registration'=>$_POST['staff_regist'],
		'ct_staff_zipcode'=>$_POST['staff_zipcode'],
    );

    foreach($ct_option as $option_key=>$option_value){
        $add3=$setting->set_option($option_key,$option_value);
    }
	$setting->set_option_postal($converted_postalcode);
    if($add3){
        /* 
			$lng=$setting->get_option($_SESSION['b_id'],'ct_languages');
			$lng=$_POST['languages'];
			setcookie('bt-language',$lng, time() + (86400 * 30), "/"); 
		*/
        echo "updated";
    }
}

/* Google Calendar Start */

if($gc_hook->gc_purchase_status() == 'exist'){
	echo $gc_hook->gc_settings_save_ajax_hook();
	echo $gc_hook->gc_setting_configure_ajax_hook();
	echo $gc_hook->gc_setting_disconnect_ajax_hook();
	echo $gc_hook->gc_staff_settings_save_ajax_hook();
	echo $gc_hook->gc_staff_setting_configure_ajax_hook();
	echo $gc_hook->gc_staff_setting_disconnect_ajax_hook();
}

/* Google Calendar End */

/* Below code is use for save value of payment settings */
if(isset($_POST['action']) && $_POST['action']=='payment_setting'){
    $payment_option=array(
        'ct_all_payment_gateway_status'=>$_POST['payemnt_gateway_all'],
        'ct_pay_locally_status'=>$_POST['payemnt_locally'],
        'ct_paypal_express_checkout_status'=>$_POST['payemnt_paypal'],
        'ct_paypal_api_username'=>$_POST['username'],
        'ct_paypal_api_password'=>$_POST['password'],
        'ct_paypal_api_signature'=>$_POST['signature'],
        'ct_paypal_guest_payment_status'=>$_POST['payemnt_guest'],
        'ct_paypal_test_mode_status'=>$_POST['test_mode'],
        'ct_stripe_payment_form_status'=>$_POST['stripe_payment'],
        'ct_stripe_secretkey'=>$_POST['secretkey'],
        'ct_stripe_publishablekey'=>$_POST['publishablekey'],
		'ct_authorizenet_status'=>$_POST['authorize_net_status'],
		'ct_authorizenet_API_login_ID'=>$_POST['autorize_login_ID'],
		'ct_authorizenet_transaction_key'=>$_POST['authorize_transaction_key'],
		'ct_authorize_sandbox_mode'=>$_POST['authorize_test_mode'],
		'ct_2checkout_sandbox_mode'=>$_POST['twocheckout_testmode'],
		'ct_2checkout_status'=>$_POST['twocheckout_payment'],
		'ct_2checkout_privatekey'=>$_POST['twocheckout_privatekey'],
		'ct_2checkout_publishkey'=>$_POST['twocheckout_publishkey'],
		'ct_2checkout_sellerid'=>$_POST['twocheckout_sellerid'],
		/*new add*/
		'ct_bank_name'=>$_POST['bank_name'],
		'ct_account_name'=>$_POST['account_name'],
		'ct_account_number'=>$_POST['account_number'],
		'ct_branch_code'=>$_POST['branch_code'],
		'ct_ifsc_code'=>$_POST['ifsc_code'],
		'ct_bank_description'=>$_POST['bank_description'],
        'ct_bank_transfer_status'=>$_POST['bank_status'],
    );
    foreach($payment_option as $option_key=>$option_value){
        $add3=$setting->set_option($option_key,$option_value);
    }
    if($add3){
        echo "updated";
    }
}
/* Below code is use for save value of E-mail notification */
if(isset($_POST['action']) && $_POST['action']=='email_setting'){
    $email_option=array(
        'ct_admin_email_notification_status'=>$_POST['admin_email'],
        'ct_staff_email_notification_status'=>$_POST['staff_email'],
        'ct_client_email_notification_status'=>$_POST['client_email'],
        'ct_email_sender_name'=>addslashes($_POST['sender_name']),
        'ct_email_sender_address'=>$_POST['sender_email'],
        'ct_admin_optional_email'=>$_POST['admin_optional_email'],
        'ct_email_appointment_reminder_buffer'=>$_POST['appointment_reminder'],
		'ct_smtp_hostname'=>$_POST['hostname'],
        'ct_smtp_username'=>$_POST['username'],
        'ct_smtp_password'=>$_POST['password'],
        'ct_smtp_port'=>$_POST['port'],
		'ct_smtp_encryption'=>$_POST['encryptiontype'],
		'ct_smtp_authetication'=>$_POST['autheticationtype'],
    );
    foreach($email_option as $option_key=>$option_value){
        $add3=$setting->set_option($option_key,$option_value);
    }
    if($add3){
        echo "updated";
    }
}
/* Below code is use for save value of SMS Notification settings */
if(isset($_POST['action']) && $_POST['action']=='sms_reminder'){
    $sms_notification=array(
        'ct_sms_service_status'=>$_POST['status_sms_service'],
        'ct_sms_twilio_account_SID'=>$_POST['account_sid'],
        'ct_sms_twilio_auth_token'=>$_POST['auth_token'],
        'ct_sms_twilio_sender_number'=>$_POST['sender_number'],
        'ct_sms_twilio_send_sms_to_client_status'=>$_POST['status_sms_to_client'],
        'ct_sms_twilio_send_sms_to_admin_status'=>$_POST['status_sms_to_admin'],
        'ct_sms_twilio_send_sms_to_staff_status'=>$_POST['status_sms_to_staff'],
        'ct_sms_twilio_admin_phone_number'=>$_POST['admin_phone'],
        /*PLIVO SETTINGS*/
        'ct_sms_plivo_account_SID'=>$_POST['account_sid_p'],
        'ct_sms_plivo_auth_token'=>$_POST['auth_token_p'],
        'ct_sms_plivo_sender_number'=>$_POST['sender_number_p'],
        'ct_sms_plivo_send_sms_to_client_status'=>$_POST['status_sms_to_client_p'],
        'ct_sms_plivo_send_sms_to_admin_status'=>$_POST['status_sms_to_admin_p'],
        'ct_sms_plivo_send_sms_to_staff_status'=>$_POST['status_sms_to_staff_p'],
        'ct_sms_plivo_admin_phone_number'=>$_POST['admin_phone_p'],
        'ct_sms_plivo_status'=>$_POST['sms_plivo_status'],
        'ct_sms_twilio_status'=>$_POST['sms_twilio_status'],
		/* Nexmo Settings */
		'ct_sms_nexmo_status'=>$_POST['sms_nexmo_status'],
        'ct_nexmo_api_key'=>$_POST['sms_nexmo_api_key'],
        'ct_nexmo_api_secret'=>$_POST['sms_nexmo_api_secret'],
        'ct_nexmo_from'=>$_POST['sms_nexmo_from'],
        'ct_nexmo_status'=>$_POST['sms_nexmo_statuss'],
        'ct_sms_nexmo_send_sms_to_client_status'=>$_POST['sms_nexmo_statu_send_client'],
        'ct_sms_nexmo_send_sms_to_admin_status'=>$_POST['sms_nexmo_statu_send_admin'],
        'ct_sms_nexmo_send_sms_to_staff_status'=>$_POST['sms_nexmo_statu_send_staff'],
        'ct_sms_nexmo_admin_phone_number'=>$_POST['sms_nexmo_admin_phone'],
		/* textlocal settings */
		'ct_sms_textlocal_account_username'=>$_POST['sms_textlocal_username'],
		'ct_sms_textlocal_account_hash_id'=>$_POST['sms_textlocal_hashid'],
		'ct_sms_textlocal_send_sms_to_client_status'=>$_POST['sms_textlocal_status_send_client'],
		'ct_sms_textlocal_send_sms_to_admin_status'=>$_POST['sms_textlocal_status_send_admin'],
		'ct_sms_textlocal_send_sms_to_staff_status'=>$_POST['sms_textlocal_status_send_staff'],
		'ct_sms_textlocal_status'=>$_POST['sms_textlocal_status'],
		'ct_sms_textlocal_admin_phone'=>$_POST['textlocal_admin_phone'],
		/* messagebird settings */
		'ct_sms_messagebird_account_apikey'=>$_POST['sms_messagebird_apikey'],
		'ct_sms_messagebird_send_sms_to_client_status'=>$_POST['sms_messagebird_status_send_client'],
		'ct_sms_messagebird_send_sms_to_admin_status'=>$_POST['sms_messagebird_status_send_admin'],
		'ct_sms_messagebird_send_sms_to_staff_status'=>$_POST['sms_messagebird_status_send_staff'],
		'ct_sms_messagebird_status'=>$_POST['sms_messagebird_status'],
		'ct_sms_messagebird_admin_phone'=>$_POST['messagebird_admin_phone']
    );
    foreach($sms_notification as $option_key=>$option_value){
        $add3=$setting->set_option($option_key,$option_value);
    }
    if($add3){
        echo "updated";
    }
}
/* recurrence booking */
if(isset($_POST['ct_recurrence_booking']) && $_POST['ct_recurrence_booking'] == '1') {
	$ct_recurrence_booking_status = $_POST['ct_recurrence_booking_status'];
	$recurrence = array(
		'ct_recurrence_booking_status'=>$ct_recurrence_booking_status,
	);
	foreach($recurrence as $option_key=>$option_value){
		$recurrence_settings=$setting->set_option($option_key,$option_value);
	}
}
/* frequently setings */
if(isset($_POST['freqdis'])){
    $id = $_POST['id'];
    $status = $_POST['changestatus'];
    $objfrequently->id = $id;
    $objfrequently->status  = $status;
    $objfrequently->update_discount_status();
}elseif(isset($_POST['addrecurrence'])){
	$t_zone_value = $setting->get_option('ct_timezone');
	$server_timezone = date_default_timezone_get();
	if(isset($t_zone_value) && $t_zone_value!=''){
		$offset= $first_step->get_timezone_offset($server_timezone,$t_zone_value);
		$timezonediff = $offset/3600;  
	}else{
		$timezonediff =0;
	}
	
	if(is_numeric(strpos($timezonediff,'-'))){
		$timediffmis = str_replace('-','',$timezonediff)*60;
		$currDateTime_withTZ= strtotime("-".$timediffmis." minutes",strtotime(date('Y-m-d H:i:s')));
	}else{
		$timediffmis = str_replace('+','',$timezonediff)*60;
		$currDateTime_withTZ = strtotime("+".$timediffmis." minutes",strtotime(date('Y-m-d H:i:s')));
	}
	$end_3_month_strtotime = strtotime("+3 months",$currDateTime_withTZ);
	$cust_datediff = $end_3_month_strtotime - $currDateTime_withTZ;
	$total_days = abs(floor($cust_datediff / (60 * 60 * 24)))+1;
	$booking_count = 0;
	$days = $_POST["days"];
	if($days == 0){
		$days = 1;
	}
	
	for($j=0;$j<$total_days;$j+=$days) {
		$booking_count++;
	}
	
	$objfrequently->discount_typename = ucwords($_POST['name']);
	$objfrequently->label = ucwords($_POST['label']);
	$objfrequently->days = $days;
	$objfrequently->d_type = $_POST['types'];
	$objfrequently->rates = $_POST['values'];
	$objfrequently->booking_count = $booking_count;
	$objfrequently->stripe_plan_id = "";
	
	if($setting->get_option('ct_stripe_payment_form_status') == "on" && $setting->get_option('ct_stripe_create_plan') == "Y"){
		include(dirname(dirname(dirname(__FILE__))).'/assets/stripe/stripe.php');
		$secret_key = $setting->get_option('ct_stripe_secretkey');
		try{
			\Stripe\Stripe::setApiKey($secret_key);
			$objproduct = new \Stripe\Product;
			$one_product_create = $objproduct::Create(array(
				"name" => $objfrequently->discount_typename,
				"type" => "service",
				"statement_descriptor" => $objfrequently->discount_typename." For ".$days." Days"
			));
			$objfrequently->stripe_plan_id = $one_product_create->id;
		}	catch (Exception $e) {
			$error = $e->getMessage();
	  }
	}
	$result = $objfrequently->add_discount_freq();
	
	if($result){
		echo "1";
	}else{
		echo "0";
	}
}elseif(isset($_POST['updaterecurrence_once'])){
	$objfrequently->id = $_POST['id'];
	$objfrequently->discount_typename = ucwords($_POST['name']);
	$objfrequently->label = ucwords($_POST['label']);
	$result = $objfrequently->update_discount_freq_once();
	
	if($result){
		echo "1";
	}else{
		echo "0";
	}
}elseif(isset($_POST['updaterecurrence'])){
	$t_zone_value = $setting->get_option('ct_timezone');
	$server_timezone = date_default_timezone_get();
	if(isset($t_zone_value) && $t_zone_value!=''){
		$offset= $first_step->get_timezone_offset($server_timezone,$t_zone_value);
		$timezonediff = $offset/3600;  
	}else{
		$timezonediff =0;
	}
	
	if(is_numeric(strpos($timezonediff,'-'))){
		$timediffmis = str_replace('-','',$timezonediff)*60;
		$currDateTime_withTZ= strtotime("-".$timediffmis." minutes",strtotime(date('Y-m-d H:i:s')));
	}else{
		$timediffmis = str_replace('+','',$timezonediff)*60;
		$currDateTime_withTZ = strtotime("+".$timediffmis." minutes",strtotime(date('Y-m-d H:i:s')));
	}
	$end_3_month_strtotime = strtotime("+3 months",$currDateTime_withTZ);
	$cust_datediff = $end_3_month_strtotime - $currDateTime_withTZ;
	$total_days = abs(floor($cust_datediff / (60 * 60 * 24)))+1;
	$booking_count = 0;
	$days = $_POST["days"];
	if($days == 0){
		$days = 1;
	}
	
	for($j=0;$j<$total_days;$j+=$days) {
		$booking_count++;
	}
	
	$objfrequently->id = $_POST['id'];
	$objfrequently->discount_typename = ucwords($_POST['name']);
	$objfrequently->label = ucwords($_POST['label']);
	$objfrequently->days = $days;
	$objfrequently->d_type = $_POST['types'];
	$objfrequently->rates = $_POST['values'];
	$objfrequently->booking_count = $booking_count;
	$result = $objfrequently->update_discount_freq();
	
	if($setting->get_option('ct_stripe_payment_form_status') == "on" && $setting->get_option('ct_stripe_create_plan') == "Y"){
		include(dirname(dirname(dirname(__FILE__))).'/assets/stripe/stripe.php');
		$secret_key = $setting->get_option('ct_stripe_secretkey');
		try{
			\Stripe\Stripe::setApiKey($secret_key);
			$one_rec_detail = $objfrequently->readone();
			$objproduct = new \Stripe\Product;
			if($one_rec_detail["stripe_plan_id"] == ""){
				$one_product_create = $objproduct::Create(array(
					"name" => $objfrequently->discount_typename,
					"type" => "service",
					"statement_descriptor" => $objfrequently->discount_typename." For ".$days." Days"
				));
				
				$objfrequently->id = $_POST['id'];
				$objfrequently->stripe_plan_id = $one_product_create->id;
				$objfrequently->update_discount_freq_stripe_id();
			}else{
				$one_product_info_update = $objproduct::Update($one_rec_detail["stripe_plan_id"],array(
					"statement_descriptor" => $objfrequently->discount_typename." For ".$days." Days"
				));
			}
		}	catch (Exception $e) {
			$error = $e->getMessage();
	  }
	}
	
	if($result){
		echo "1";
	}else{
		echo "0";
	}
}elseif(isset($_POST['deleterecurrence'])){
	$objfrequently->id = $_POST['id'];
	if($setting->get_option('ct_stripe_payment_form_status') == "on" && $setting->get_option('ct_stripe_create_plan') == "Y"){
		include(dirname(dirname(dirname(__FILE__))).'/assets/stripe/stripe.php');
		$secret_key = $setting->get_option('ct_stripe_secretkey');
		try{
			\Stripe\Stripe::setApiKey($secret_key);
			$one_rec_detail = $objfrequently->readone();
			$objproduct = new \Stripe\Product;
			if($one_rec_detail["stripe_plan_id"] != ""){
				$one_product_info_retrive = $objproduct::Retrieve($one_rec_detail["stripe_plan_id"]);
				$one_product_info_retrive->delete();
			}
		}	catch (Exception $e) {
			$error = $e->getMessage();
		}
	}
	$result = $objfrequently->delete_discount_freq();
	if($result){
		echo "1";
	}else{
		echo "0";
	}
}elseif(isset($_POST['assigndesign'])){
    $design_id = $_POST['designid'];
    $option = $_POST['divname'];
    $setting->set_option($option,$design_id);
}
/* Language settings */
elseif(isset($_POST['change_language'])){
	$update_labels = $_POST['update_labels'];
	$id = $_POST['id'];
	foreach ($_POST['labels_front_error'] as $key => $value) {
		$language_front_error[$key] = $value;
	}
	$language_label_arr = $setting->get_all_labelsbyid_from_id($id);
	
	$language_front_arr = $language_label_arr[1];
	$language_admin_arr = $language_label_arr[3];
	$language_error_arr = $language_label_arr[4];
	$language_extra_arr = $language_label_arr[5];
	$language_front_error_arr = base64_encode(serialize($language_front_error));
	
	$setting->update_labels_languages($language_front_arr, $language_admin_arr, $language_error_arr, $language_extra_arr, $language_front_error_arr, $id);		
}
elseif(isset($_POST['get_all_labels'])){
	$lang = $_POST['oflang'];
	$langarr = $setting->get_all_labelsbyid($lang);
	
	if (isset($langarr[1]) != "" || isset($langarr[3]) != "" || isset($langarr[4]) != "" || isset($langarr[5]) != "" || isset($langarr[6]) != "" || isset($langarr[8]) != ""){
		$default_language_arr = $setting->get_all_labelsbyid("en");
		
		if($langarr[1] != ''){
			$label_decode_front = base64_decode($langarr[1]);
		}else{
			$label_decode_front = base64_decode($default_language_arr[1]);
		}
		if($langarr[3] != ''){
			$label_decode_admin = base64_decode($langarr[3]);
		}else{
			$label_decode_admin = base64_decode($default_language_arr[3]);
		}
		if($langarr[4] != ''){
			$label_decode_error = base64_decode($langarr[4]);
		}else{
			$label_decode_error = base64_decode($default_language_arr[4]);
		}
		if($langarr[5] != ''){
			$label_decode_extra = base64_decode($langarr[5]);
		}else{
			$label_decode_extra = base64_decode($default_language_arr[5]);
		}
		if($langarr[6] != ''){
			$label_decode_front_error = base64_decode($langarr[6]);
		}else{
			$label_decode_front_error = base64_decode($default_language_arr[6]);
		}
		if($langarr[8] != ''){
			$label_decode_app = base64_decode($langarr[8]);
		}else{
			$label_decode_app = base64_decode($default_language_arr[8]);
		}
		$label_decode_front_unserial = unserialize($label_decode_front);
		$label_decode_admin_unserial = unserialize($label_decode_admin);
		$label_decode_error_unserial = unserialize($label_decode_error);
		$label_decode_extra_unserial = unserialize($label_decode_extra);
		$label_decode_front_error_unserial = unserialize($label_decode_front_error);
		$label_decode_app_unserial = unserialize($label_decode_app);
		?>
		<div class="language_status" data-id="<?php echo $lang; ?>">
			<input class="cta-toggle-checkbox2 language_status_change" data-id="<?php echo $lang; ?>" data-toggle="toggle" data-size="small" type='checkbox' name="language_status" <?php if($langarr[7] == "Y"){echo 'checked';} ?> data-on="<?php echo $label_language_values['enable'];?>" data-off="<?php echo $label_language_values['disable'];?>" data-onstyle='success' data-offstyle='danger' />
		</div>
		
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#detail_spssfront"><?php echo $label_language_values['frontend_labels'];?></a></li>
			<li><a data-toggle="tab" href="#detail_spssadmin"><?php echo $label_language_values['admin_labels'];?></a></li>
			<li><a data-toggle="tab" href="#detail_spsserror"><?php echo $label_language_values['errors'];?></a></li>
			<li><a data-toggle="tab" href="#detail_spssextra"><?php echo $label_language_values['extra_labels'];?></a></li>
			<?php  if($langarr[6] == ''){ ?>
				<li><a data-toggle="tab" href="#detail_spsfront_error"><?php echo $label_language_values['front_error_labels'];?></a></li>
			<?php  } ?>
			<li><a data-toggle="tab" href="#detail_spssapp"><?php echo $label_language_values['app_labels'];?></a></li>
		</ul>
		<div class="tab-content">
			<div id="detail_spssfront" class="tab-pane fade in active">
				<form id="ct-frontend-labels-settings" method="post" type="" class="ct-labels-settings" >
					<input type="hidden" value="<?php echo $_POST['oflang']; ?>" name="ct_selected_lang_labels" />
					<div class="row cta-top-right">
						<span class="pull-right cta-setting-fix-btn" style="margin: 5px 40px !important;"> <input class="btn btn-success" type="submit" name="btn_submit_frontend_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
					</div>
					
					<table class="form-inline ct-common-table" >
						<?php  
						foreach ($label_decode_front_unserial as $key => $value) {
							$final_value = str_replace('_', ' ', $key);
							?>
							<tr>
							<td><label class="englabel_<?php  echo $key;?>"><?php echo $final_value;?></label></td>
							<td>
								<div class="form-group">
									<input type="text" size="50" value="<?php echo urldecode($value);?>" class="form-control langlabel_front" name="ctfrontlabelct<?php  echo $key;?>" />
								</div>
							</td>
							</tr>
						<?php  } ?>
						<tr>
							<td colspan="2">
								<span class="cta-setting-fix-btn"> <input class="btn btn-success" type="submit" name="btn_submit_frontend_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
							</td>
						</tr>
					</table>
				</form>
			</div>
			
			<div id="detail_spssadmin" class="tab-pane fade">
				<form id="ct-admin-labels-settings" method="post" type="" class="ct-labels-settings" >
					<input type="hidden" value="<?php echo $_POST['oflang']; ?>" name="ct_selected_lang_labels" />
					<div class="row cta-top-right">
						<span class="pull-right cta-setting-fix-btn" style="margin: 5px 40px !important;"> <input class="btn btn-success" type="submit" name="btn_submit_admin_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
					</div>
					
					<table class="form-inline ct-common-table" >
						<?php  
						foreach ($label_decode_admin_unserial as $key => $value) {
							$final_value = str_replace('_', ' ', $key);
							?>
							<tr>
							<td><label class="englabel_<?php  echo $key;?>"><?php echo $final_value;?></label></td>
							<td>
								<div class="form-group">
									<input type="text" size="50" value="<?php echo urldecode($value);?>" class="form-control langlabel_admin" name="ctadminlabelct<?php  echo $key;?>" />
								</div>
							</td>
							</tr>
						<?php  } ?>
						<tr>
							<td colspan="2">
								<span class="cta-setting-fix-btn"> <input class="btn btn-success" type="submit" name="btn_submit_admin_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
							</td>
						</tr>
					</table>
				</form>
			</div>
			
			<div id="detail_spsserror" class="tab-pane fade">
				<form id="ct-error-labels-settings" method="post" type="" class="ct-labels-settings" >
					<input type="hidden" value="<?php echo $_POST['oflang']; ?>" name="ct_selected_lang_labels" />
					<div class="row cta-top-right">
						<span class="pull-right cta-setting-fix-btn" style="margin: 5px 40px !important;"> <input class="btn btn-success" type="submit" name="btn_submit_error_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
					</div>
					
					<table class="form-inline ct-common-table" >
						<?php  
						foreach ($label_decode_error_unserial as $key => $value) {
							$final_value = str_replace('_', ' ', $key);
							?>
							<tr>
							<td><label class="englabel_<?php  echo $key;?>"><?php echo $final_value;?></label></td>
							<td>
								<div class="form-group">
									<input type="text" size="50" value="<?php echo urldecode($value);?>" class="form-control langlabel_error" name="cterrorlabelct<?php  echo $key;?>" />
								</div>
							</td>
							</tr>
						<?php  } ?>
						<tr>
							<td colspan="2">
								<span class="cta-setting-fix-btn"> <input class="btn btn-success" type="submit" name="btn_submit_error_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
							</td>
						</tr>
					</table>
				</form>
			</div>
			
			<div id="detail_spssextra" class="tab-pane fade">
				<form id="ct-extra-labels-settings" method="post" type="" class="ct-labels-settings" >
					<input type="hidden" value="<?php echo $_POST['oflang']; ?>" name="ct_selected_lang_labels" />
					<div class="row cta-top-right">
						<span class="pull-right cta-setting-fix-btn" style="margin: 5px 40px !important;"> <input class="btn btn-success" type="submit" name="btn_submit_extra_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
					</div>
					
					<table class="form-inline ct-common-table" >
						<?php  
						foreach ($label_decode_extra_unserial as $key => $value) {
							$final_value = str_replace('_', ' ', $key);
							?>
							<tr>
							<td><label class="englabel_<?php  echo $key;?>"><?php echo $final_value;?></label></td>
							<td>
								<div class="form-group">
									<input type="text" size="50" value="<?php echo urldecode($value);?>" class="form-control langlabel_extra" name="ctextralabelct<?php  echo $key;?>"/>
								</div>
							</td>
							</tr>
						<?php  } ?>
						<tr>
							<td colspan="2">
								<span class="cta-setting-fix-btn"> <input class="btn btn-success" type="submit" name="btn_submit_extra_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
							</td>
						</tr>
					</table>
				</form>
			</div>
			
			<?php  if($langarr[6] == ''){ ?>
				<div id="detail_spsfront_error" class="tab-pane fade">
					<form id="ct-ferror-labels-settings" method="post" type="" class="ct-labels-settings" >
						<input type="hidden" value="<?php echo $_POST['oflang']; ?>" name="ct_selected_lang_labels" />
						<div class="row cta-top-right">
							<span class="pull-right cta-setting-fix-btn" style="margin: 5px 40px !important;"> <input class="btn btn-success" type="submit" name="btn_submit_ferror_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
						</div>
						
						<table class="form-inline ct-common-table" >
							<?php  
							foreach ($label_decode_front_error_unserial as $key => $value) 
							{
								$final_value = str_replace('_', ' ', $key);
								?>
								<tr>
								<td><label class="englabel_<?php  echo $key;?>"><?php echo $final_value;?></label></td>
								<td>
									<div class="form-group">
										<input type="text" size="50" value="<?php echo urldecode($value);?>" class="form-control langlabel_extra" name="ctfr_errorlabelct<?php  echo $key;?>" />
									</div>
								</td>
								</tr>
							<?php  } ?>
							<tr>
								<td colspan="2">
									<span class="cta-setting-fix-btn"> <input class="btn btn-success" type="submit" name="btn_submit_ferror_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
								</td>
							</tr>
						</table>
					</form>
				</div>
			<?php  } ?>
			
			<div id="detail_spssapp" class="tab-pane fade">
				<form id="ct-extra-labels-settings" method="post" type="" class="ct-labels-settings" >
					<input type="hidden" value="<?php echo $_POST['oflang']; ?>" name="ct_selected_lang_labels" />
					<div class="row cta-top-right">
						<span class="pull-right cta-setting-fix-btn" style="margin: 5px 40px !important;"> <input class="btn btn-success" type="submit" name="btn_submit_app_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
					</div>
					
					<table class="form-inline ct-common-table" >
						<?php  
						foreach ($label_decode_app_unserial as $key => $value) {
							$final_value = str_replace('_', ' ', $key);
							?>
							<tr>
							<td><label class="englabel_<?php  echo $key;?>"><?php echo $final_value;?></label></td>
							<td>
								<div class="form-group">
									<input type="text" size="50" value="<?php echo urldecode($value);?>" class="form-control langlabel_extra" name="ctextralabelct<?php  echo $key;?>"/>
								</div>
							</td>
							</tr>
						<?php  } ?>
						<tr>
							<td colspan="2">
								<span class="cta-setting-fix-btn"> <input class="btn btn-success" type="submit" name="btn_submit_app_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
		<?php  
    }
	else
	{
		$default_language_arr = $setting->get_all_labelsbyid("en");
		
		$label_decode_front = base64_decode($default_language_arr[1]);
		$label_decode_admin = base64_decode($default_language_arr[3]);
		$label_decode_error = base64_decode($default_language_arr[4]);
		$label_decode_extra = base64_decode($default_language_arr[5]);
		$label_decode_front_error = base64_decode($default_language_arr[6]);
		$label_decode_app = base64_decode($default_language_arr[8]);
		
		$label_decode_front_unserial = unserialize($label_decode_front);
		$label_decode_admin_unserial = unserialize($label_decode_admin);
		$label_decode_error_unserial = unserialize($label_decode_error);
		$label_decode_extra_unserial = unserialize($label_decode_extra);
		$label_decode_front_error_unserial = unserialize($label_decode_front_error);
		$label_decode_app_unserial = unserialize($label_decode_app);
		?>
		<div class="language_status" data-id="<?php echo $lang; ?>">
			<input class="cta-toggle-checkbox2"  data-id="<?php echo $lang; ?>" data-toggle="toggle" data-size="small"  type='checkbox' name="language_status" data-on="<?php echo $label_language_values['enable'];?>" data-off="<?php echo $label_language_values['disable'];?>"  data-onstyle='success' data-offstyle='danger' />
		</div> 
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#detail_spssfront"><?php echo $label_language_values['frontend_labels'];?></a></li>
			<li><a data-toggle="tab" href="#detail_spssadmin"><?php echo $label_language_values['admin_labels'];?></a></li>
			<li><a data-toggle="tab" href="#detail_spsserror"><?php echo $label_language_values['errors'];?></a></li>
			<li><a data-toggle="tab" href="#detail_spssextra"><?php echo $label_language_values['extra_labels'];?></a></li>
			<li><a data-toggle="tab" href="#detail_spsfront_error"><?php echo $label_language_values['front_error_labels'];?></a></li>
			<li><a data-toggle="tab" href="#detail_spsapp"><?php echo $label_language_values['app_labels'];?></a></li>
		</ul>
		<div class="tab-content">
			<div id="detail_spssfront" class="tab-pane fade in active">
				<form id="ct-frontend-labels-settings" method="post" type="" class="ct-labels-settings" >
					<input type="hidden" value="<?php echo $_POST['oflang']; ?>" name="ct_selected_lang_labels" />
					<div class="row cta-top-right">
						<span class="pull-right cta-setting-fix-btn" style="margin: 5px 40px !important;"> <input class="btn btn-success" type="submit" name="btn_submit_frontend_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
					</div>
					
					<table class="form-inline ct-common-table" >
						<?php  
						foreach ($label_decode_front_unserial as $key => $value) {
							$final_value = str_replace('_', ' ', $key);
							?>
							<tr>
							<td><label class="englabel_<?php  echo $key;?>"><?php echo $final_value;?></label></td>
							<td>
								<div class="form-group">
									<input type="text" size="50" value="<?php echo urldecode($value);?>" class="form-control langlabel_front" name="ctfrontlabelct<?php  echo $key;?>" />
								</div>
							</td>
							</tr>
						<?php  } ?>
						<tr>
							<td colspan="2">
								<span class="cta-setting-fix-btn"> <input class="btn btn-success" type="submit" name="btn_submit_frontend_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
							</td>
						</tr>
					</table>
				</form>
			</div>
			
			<div id="detail_spssadmin" class="tab-pane fade">
				<form id="ct-admin-labels-settings" method="post" type="" class="ct-labels-settings" >
					<input type="hidden" value="<?php echo $_POST['oflang']; ?>" name="ct_selected_lang_labels" />
					<div class="row cta-top-right">
						<span class="pull-right cta-setting-fix-btn" style="margin: 5px 40px !important;"> <input class="btn btn-success" type="submit" name="btn_submit_admin_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
					</div>
					
					<table class="form-inline ct-common-table" >
						<?php  
						foreach ($label_decode_admin_unserial as $key => $value) {
							$final_value = str_replace('_', ' ', $key);
							?>
							<tr>
							<td><label class="englabel_<?php  echo $key;?>"><?php echo $final_value;?></label></td>
							<td>
								<div class="form-group">
									<input type="text" size="50" value="<?php echo urldecode($value);?>" class="form-control langlabel_admin" name="ctadminlabelct<?php  echo $key;?>" />
								</div>
							</td>
							</tr>
						<?php  } ?>
						<tr>
							<td colspan="2">
								<span class="cta-setting-fix-btn"> <input class="btn btn-success" type="submit" name="btn_submit_admin_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
							</td>
						</tr>
					</table>
				</form>
			</div>
			
			<div id="detail_spsserror" class="tab-pane fade">
				<form id="ct-error-labels-settings" method="post" type="" class="ct-labels-settings" >
					<input type="hidden" value="<?php echo $_POST['oflang']; ?>" name="ct_selected_lang_labels" />
					<div class="row cta-top-right">
						<span class="pull-right cta-setting-fix-btn" style="margin: 5px 40px !important;"> <input class="btn btn-success" type="submit" name="btn_submit_error_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
					</div>
					
					<table class="form-inline ct-common-table" >
						<?php  
						foreach ($label_decode_error_unserial as $key => $value) {
							$final_value = str_replace('_', ' ', $key);
							?>
							<tr>
							<td><label class="englabel_<?php  echo $key;?>"><?php echo $final_value;?></label></td>
							<td>
								<div class="form-group">
									<input type="text" size="50" value="<?php echo urldecode($value);?>" class="form-control langlabel_error" name="cterrorlabelct<?php  echo $key;?>" />
								</div>
							</td>
							</tr>
						<?php  } ?>
						<tr>
							<td colspan="2">
								<span class="cta-setting-fix-btn"> <input class="btn btn-success" type="submit" name="btn_submit_error_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
							</td>
						</tr>
					</table>
				</form>
			</div>
			
			<div id="detail_spssextra" class="tab-pane fade">
				<form id="ct-extra-labels-settings" method="post" type="" class="ct-labels-settings" >
					<input type="hidden" value="<?php echo $_POST['oflang']; ?>" name="ct_selected_lang_labels" />
					<div class="row cta-top-right">
						<span class="pull-right cta-setting-fix-btn" style="margin: 5px 40px !important;"> <input class="btn btn-success" type="submit" name="btn_submit_extra_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
					</div>
					
					<table class="form-inline ct-common-table" >
						<?php  
						foreach ($label_decode_extra_unserial as $key => $value) {
							$final_value = str_replace('_', ' ', $key);
							?>
							<tr>
							<td><label class="englabel_<?php  echo $key;?>"><?php echo $final_value;?></label></td>
							<td>
								<div class="form-group">
									<input type="text" size="50" value="<?php echo urldecode($value);?>" class="form-control langlabel_extra" name="ctextralabelct<?php  echo $key;?>"/>
								</div>
							</td>
							</tr>
						<?php  } ?>
						<tr>
							<td colspan="2">
								<span class="cta-setting-fix-btn"> <input class="btn btn-success" type="submit" name="btn_submit_extra_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
							</td>
						</tr>
					</table>
				</form>
			</div>
			
			<div id="detail_spsfront_error" class="tab-pane fade">
				<form id="ct-ferror-labels-settings" method="post" type="" class="ct-labels-settings" >
					<input type="hidden" value="<?php echo $_POST['oflang']; ?>" name="ct_selected_lang_labels" />
					<div class="row cta-top-right">
						<span class="pull-right cta-setting-fix-btn" style="margin: 5px 40px !important;"> <input class="btn btn-success" type="submit" name="btn_submit_ferror_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
					</div>
					
					<table class="form-inline ct-common-table" >
						<?php  
						foreach ($label_decode_front_error_unserial as $key => $value) 
						{
							$final_value = str_replace('_', ' ', $key);
							?>
							<tr>
							<td><label class="englabel_<?php  echo $key;?>"><?php echo $final_value;?></label></td>
							<td>
								<div class="form-group">
									<input type="text" size="50" value="<?php echo urldecode($value);?>" class="form-control langlabel_extra" name="ctfr_errorlabelct<?php  echo $key;?>" />
								</div>
							</td>
							</tr>
						<?php  } ?>
						<tr>
							<td colspan="2">
								<span class="cta-setting-fix-btn"> <input class="btn btn-success" type="submit" name="btn_submit_ferror_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
							</td>
						</tr>
					</table>
				</form>
			</div>
			
			<div id="detail_spsapp" class="tab-pane fade">
				<form id="ct-ferror-labels-settings" method="post" type="" class="ct-labels-settings" >
					<input type="hidden" value="<?php echo $_POST['oflang']; ?>" name="ct_selected_lang_labels" />
					<div class="row cta-top-right">
						<span class="pull-right cta-setting-fix-btn" style="margin: 5px 40px !important;"> <input class="btn btn-success" type="submit" name="btn_submit_app_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
					</div>
					
					<table class="form-inline ct-common-table" >
						<?php  
						foreach ($label_decode_app_unserial as $key => $value) 
						{
							$final_value = str_replace('_', ' ', $key);
							?>
							<tr>
							<td><label class="englabel_<?php  echo $key;?>"><?php echo $final_value;?></label></td>
							<td>
								<div class="form-group">
									<input type="text" size="50" value="<?php echo urldecode($value);?>" class="form-control langlabel_extra" name="ctfr_errorlabelct<?php  echo $key;?>" />
								</div>
							</td>
							</tr>
						<?php  } ?>
						<tr>
							<td colspan="2">
								<span class="cta-setting-fix-btn"> <input class="btn btn-success" type="submit" name="btn_submit_app_labels" value="<?php echo $label_language_values['save_labels_setting'];?>"></span>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
		<?php 
	}
}
elseif(isset($_POST['change_language'])){
	$update_labels = $_POST['update_labels'];
	$language_front_error = array();
	$alllang = $setting->get_all_labelsbyid($_POST['id']);
	while($all = mysqli_fetch_array($alllang))
	{
		$language_label_arr = $this->get_all_labelsbyid($all[2]);
		
		$label_decode_front = base64_decode($language_label_arr[1]);
		$label_decode_admin = base64_decode($language_label_arr[3]);
		$label_decode_error = base64_decode($language_label_arr[4]);
		$label_decode_extra = base64_decode($language_label_arr[5]);
		$label_decode_front_error_labels = base64_decode($language_label_arr[6]);

		$label_decode_front_unserial = unserialize($label_decode_front);
		$label_decode_admin_unserial = unserialize($label_decode_admin);
		$label_decode_error_unserial = unserialize($label_decode_error);
		$label_decode_extra_unserial = unserialize($label_decode_extra);
		$label_decode_front_error_labels_unserial = unserialize($label_decode_front_error_labels);
		
		/* UPDATE ALL CODE WITH NEW URLENCODE PATTERN */
		foreach($label_decode_front_unserial as $key => $value){
			$label_decode_front_unserial[$key] = urldecode($value);
		}
		foreach($label_decode_admin_unserial as $key => $value){
			$label_decode_admin_unserial[$key] = urldecode($value);
		}
		foreach($label_decode_error_unserial as $key => $value){
			$label_decode_error_unserial[$key] = urldecode($value);
		}
		foreach($label_decode_extra_unserial as $key => $value){
			$label_decode_extra_unserial[$key] = urldecode($value);
		}
		foreach($_POST['labels_front_error'] as $key => $value){
			$label_decode_front_error_labels_unserial[$key] = urldecode($value);
		}	
		$language_front_arr = base64_encode(serialize($label_decode_front_unserial));
		$language_admin_arr = base64_encode(serialize($label_decode_admin_unserial));
		$language_error_arr = base64_encode(serialize($label_decode_error_unserial));
		$language_extra_arr = base64_encode(serialize($label_decode_extra_unserial));
		$language_form_error_arr = base64_encode(serialize($label_decode_front_error_labels_unserial));

		$update_default_lang = "UPDATE `ct_languages` SET `label_data` = '".$language_front_arr."', `admin_labels` = '".$language_admin_arr."', `error_labels` = '".$language_error_arr."', `extra_labels` = '".$language_extra_arr."', `front_error_labels` = '".$language_form_error_arr."' WHERE `id` = '".$_POST['id']."'";
		mysqli_query($this->conn, $update_default_lang);
	}
	foreach ($_POST['labels_front_error'] as $key => $value) {
		$language_front[$key] = $value;
	}
	$language_front_arr = base64_encode(serialize($language_front));
	$language_admin_arr = base64_encode(serialize($language_admin));
	$language_error_arr = base64_encode(serialize($language_error));
	$language_extra_arr = base64_encode(serialize($language_extra));
	
	$setting->insert_labels_languages($language_front_arr, $language_admin_arr, $language_error_arr, $language_extra_arr, '', $update_labels);	
}
elseif(isset($_POST['manage_form_fields_setting'])){
	$notes = array($_POST['ct_bf_notes_1'],$_POST['ct_bf_notes_2'],$_POST['ct_bf_notes_3'],$_POST['ct_bf_notes_4']); 
	$final_notes = implode(",",$notes);
	$firstname = array($_POST['ct_bf_first_name_1'],$_POST['ct_bf_first_name_2'],$_POST['ct_bf_first_name_3'],$_POST['ct_bf_first_name_4']);
	$final_first_name = implode(",",$firstname);
	
	$lastname = array($_POST['ct_bf_last_name_1'],$_POST['ct_bf_last_name_2'],$_POST['ct_bf_last_name_3'],$_POST['ct_bf_last_name_4']);
	$final_last_name = implode(",",$lastname);
	
	$phone = array($_POST['ct_bf_phone_1'],$_POST['ct_bf_phone_2'],$_POST['ct_bf_phone_3'],$_POST['ct_bf_phone_4']);
	$final_phone = implode(",",$phone);
	
	$address = array($_POST['ct_bf_address_1'],$_POST['ct_bf_address_2'],$_POST['ct_bf_address_3'],$_POST['ct_bf_address_4']);
	$final_address = implode(",",$address);
	
	$zip = array($_POST['ct_bf_zip_1'],$_POST['ct_bf_zip_2'],$_POST['ct_bf_zip_3'],$_POST['ct_bf_zip_4']);
	$final_zip = implode(",",$zip);
	
	$city = array($_POST['ct_bf_city_1'],$_POST['ct_bf_city_2'],$_POST['ct_bf_city_3'],$_POST['ct_bf_city_4']);
	$final_city = implode(",",$city);
	
	$state = array($_POST['ct_bf_state_1'],$_POST['ct_bf_state_2'],$_POST['ct_bf_state_3'],$_POST['ct_bf_state_4']);
	$final_state = implode(",",$state);
	
	
	
	$prefered_password = array("on","Y",$_POST['preferred_password_min'],$_POST['preferred_password_max']);
	$final_pre_password = implode(",",$prefered_password);
	$final_lang_dd = $_POST['front_lang_dd'];
	
	$manage_from_fields=array(
    'ct_show_coupons_input_on_checkout'=>$_POST['coupon_checkout'],
    'ct_show_referral_input_on_checkout'=>$_POST['referral_checkout'],
		'ct_company_header_address'=>$_POST['company_header_address'],
		'ct_company_service_desc_status'=>$_POST['company_service_desc_status'],
		'ct_company_willwe_getin_status'=>$_POST['company_willwe_getin_status'],
		'ct_company_logo_display'=>$_POST['company_logo_display'],
		'ct_company_title_display'=>$_POST['company_title_display'],
		'ct_appointment_details_display'=>$_POST['appointment_details_display'],
		'ct_wallet_section'=>$_POST['wallet_section_display'],
		'ct_subheaders'=>$_POST['ct_subheaders'],
		'ct_vc_status'=>$_POST['ct_vc_status'],
		'ct_p_status'=>$_POST['ct_p_status'],
		'ct_bf_notes' => $final_notes,
		'ct_bf_first_name'=> $final_first_name,
		'ct_bf_last_name'=> $final_last_name,
		'ct_bf_phone'=> $final_phone,
		'ct_bf_address'=> $final_address,
		'ct_bf_zip_code'=> $final_zip,
		'ct_bf_city'=> $final_city,
		'ct_bf_state'=> $final_state,
		'ct_bf_password'=>$final_pre_password,
		'ct_front_language_selection_dropdown'=>$final_lang_dd
		);
	foreach($manage_from_fields as $option_key=>$option_value){
        $setting->set_option($option_key,$option_value);
    }
}
if(isset($_POST['action']) && $_POST['action']=='front_tooltips_setting'){
	 $tooltips_option=array(
        'ct_front_tool_tips_status'=>$_POST['status_front_tooltips'],
        'ct_front_tool_tips_my_bookings'=>$_POST['tooltips_my_booking'],
        'ct_front_tool_tips_postal_code'=>$_POST['tooltips_postal_code'],
        'ct_front_tool_tips_services'=>$_POST['tooltips_service'],
        'ct_front_tool_tips_addons_services'=>$_POST['tooltips_addons_service'],
        'ct_front_tool_tips_frequently_discount'=>$_POST['tooltips_frequently_discount'],
        'ct_front_tool_tips_time_slots'=>$_POST['tooltips_time_slots'],
        'ct_front_tool_tips_personal_details'=>$_POST['tooltips_personal_details'],
        'ct_front_tool_tips_promocode'=>$_POST['tooltips_promocode'],
        'ct_front_tool_payment_method'=>$_POST['tooltips_payment_method'],
    );
    foreach($tooltips_option as $option_key=>$option_value){
        $add_tips=$setting->set_option($option_key,$option_value);
    }
    if($add_tips){
        echo "updated";
    }else{
        echo "Record Not Added";
    }
}

/*Update Login Image*/
if(isset($_POST['action']) && $_POST['action']=='delete_login_image'){
    $update_logo=array('ct_login_image'=>"");
    foreach($update_logo as $option_key=>$option_value){
        $logo=$setting->set_option($option_key,$option_value);
    }
}

/*Update Front Image*/
if(isset($_POST['action']) && $_POST['action']=='delete_front_imge'){
    $update_logo=array('ct_front_image'=>"");
    foreach($update_logo as $option_key=>$option_value){
        $logo=$setting->set_option($option_key,$option_value);
    }
}
/* Send Email Invoice */
if(isset($_POST['send_email_invoice']) && $_POST['send_email_invoice'] == '1') {
	$email = $_POST['email'];
	$name = $_POST['name'];
	$link = $_POST['link'];
	$company_email = $setting->get_option('ct_company_email');
	$company_name = $setting->get_option('ct_email_sender_name');
	$company_address = $setting->get_option('ct_company_address'); 
	if($setting->get_option('ct_client_email_notification_status') == 'Y'){
		$client_email_body = $link;
        if($setting->get_option('ct_smtp_hostname') != '' && $setting->get_option('ct_email_sender_name') != '' && $setting->get_option('ct_email_sender_address') != '' && $setting->get_option('ct_smtp_username') != '' && $setting->get_option('ct_smtp_password') != '' && $setting->get_option('ct_smtp_port') != ''){
			$mail->IsSMTP();
        }else{
            $mail->IsMail();
        }
        $mail->SMTPDebug  = 1;
        $mail->IsHTML(true);
        $mail->From = $company_email;
        $mail->FromName = $company_name;
        $mail->Sender = $email;
        $mail->AddAddress($email, $name);
        $mail->Subject = 'Invoice';
        $mail->Body = $client_email_body;
        $mail->send();
			$mail->ClearAllRecipients();
    }
}
/* Payment Start */
if(sizeof((array)$purchase_check)>0){
	foreach($purchase_check as $key=>$val){
		if($val == 'Y'){
			echo $payment_hook->payment_settings_save_ajax_hook($key);
		}
	}
}
/* Payment End */
if(isset($_POST['ct_create_plan']) && $_POST['ct_create_plan'] == '1') {
	if($setting->get_option('ct_stripe_payment_form_status') == "on" && $_POST['ct_stripe_create_plan'] == "Y"){
		include(dirname(dirname(dirname(__FILE__))).'/assets/stripe/stripe.php');
		$secret_key = $setting->get_option('ct_stripe_secretkey');
		$getalldis = $objfrequently->readall();
		$getalluser = $objuser->readall();
		try{
			\Stripe\Stripe::setApiKey($secret_key);
			$objproduct = new \Stripe\Product;
			while($getdata = @mysqli_fetch_assoc($getalldis)){
				if($getdata['id'] != "1" && $getdata['stripe_plan_id'] == ""){
					$one_product_create = $objproduct::Create(array(
						"name" => $getdata['discount_typename'],
						"type" => "service",
						"statement_descriptor" => $getdata['discount_typename']." For ".$getdata["days"]." Days"
					));
					
					$objfrequently->id = $getdata['id'];
					$objfrequently->stripe_plan_id = $one_product_create->id;
					$objfrequently->update_discount_freq_stripe_id();
				}
			}
			$objcustomer = new \Stripe\Customer;
			while($getdata_user = @mysqli_fetch_assoc($getalluser)){
				if($getdata_user['stripe_id'] == ""){
					$create_customer = $objcustomer::Create(array(
						"email"    => $getdata_user["user_email"],
						"description" => $getdata_user["user_email"]." This id name is ".$getdata_user["first_name"]." ".$getdata_user["last_name"]
					));
					$customer_id = $create_customer->id;
					$objuser->user_id = $getdata_user['id'];
					$objuser->stripe_id = $customer_id;
					$objuser->update_user_stripe_id();
				}
			}
		}	catch (Exception $e) {
		$error = $e->getMessage();
		/* echo "Message Is - ".$error; */
	  }
	}
	$create_plan = array(
		'ct_stripe_create_plan'=>$_POST['ct_stripe_create_plan'],
	);
	foreach($create_plan as $option_key=>$option_value){
		$setting->set_option($option_key,$option_value);
	}
}
?>