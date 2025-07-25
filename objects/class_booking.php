<?php  

class cleanto_booking{
	public $booking_date_time;
	public $method_id;
	public $method_unit_id;
	public $method_unit_qty;
	public $method_unit_qty_rate;
	public $addons_service_id;
	public $addons_service_qty;
	public $addons_service_rate; 
	public $booking_id;
	public $location_id;
	public $order_id;
	public $client_id;
	public $provider_id;
	public $service_id;
	public $booking_price;
	public $booking_start_datetime;
	public $booking_end_datetime;
	public $booking_status;
	public $reject_reason;
	public $cancel_reason;
	public $reminder_status;
	public $lastmodify;
	public $read_status;
	public $user_id;
	public $startdate;
	public $enddate;
	public $order_date;
	public $start_date;
	public $staff_id;
	public $end_date;
	public $id;
	public $conn;
	public $offset;
	public $limit;
  public $friend_referral_code;
  public $random_string;
  public $coupon_limit;
  public $coupon_used;
  public $recurring_id;
	public $table_name="ct_bookings";
	public $tablename1="ct_services";
	public $tablename2="ct_order_client_info";
	public $tablename3="ct_users";
	public $tablename4="ct_payments";
	public $tablename5="ct_booking_addons";
	public $table_staff_status="ct_staff_status";
	
	/*
	* Function for add Booking
	*
	*/

	public function add_booking(){
		$query="insert into `".$this->table_name."` (`id`,`order_id`,`client_id`,`order_date`,`booking_date_time`,`service_id`,`method_id`,`method_unit_id`,`method_unit_qty`,`method_unit_qty_rate`,`booking_status`,`reject_reason`,`reminder_status`,`lastmodify`,`read_status`,`staff_ids`,`gc_event_id`,`gc_staff_event_id`) values(NULL,'".$this->order_id."','".$this->client_id."','".$this->order_date."','".$this->booking_date_time."','".$this->service_id."','".$this->method_id."','".$this->method_unit_id."','".$this->method_unit_qty."','".$this->method_unit_qty_rate."','".$this->booking_status."','".$this->reject_reason."','0','".$this->lastmodify."','".$this->read_status."','".$this->staff_id."','','')";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_insert_id($this->conn);	
		return $value;
	}
	/**/
	public function add_addons_booking(){
		$query="insert into `".$this->tablename5."` (`id`,`order_id`,`service_id`,`addons_service_id`,`addons_service_qty`,`addons_service_rate`) values(NULL,'".$this->order_id."','".$this->service_id."','".$this->addons_service_id."','".$this->addons_service_qty."','".$this->addons_service_rate."')";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_insert_id($this->conn);	
		return $value;
	}
	/*
	* Function for Update Booking
	*
	*/
	public function update(){
		$query="update `".$this->table_name."` set `order_id`='".$this->order_id."',`business_id`='".$this->business_id."',`client_id`='".$this->client_id."',`service_id`='".$this->service_id."',`provider_id`='".$this->provider_id."',`booking_price`='".$this->booking_price."',`booking_datetime`='".$this->booking_datetime."',`booking_endtime`='".$this->booking_endtime."',`booking_status`='".$this->booking_status."',`reject_reason`='".$this->reject_reason."',`cancel_reason`='".$this->cancel_reason."',`reminder`='".$this->reminder."',`lastmodify`='".$this->lastmodify."' where `id`='".$this->booking_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	/*
	* Function for Read All Booking
	*
	*/
	public function readall(){
		$query="select * from `".$this->table_name."`";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	public function getallbookings($start_date = "",$end_date = ""){
		$date_check = "";
		if($start_date != "" && $end_date != ""){
			$sdate = $start_date." 00:00:00";
			$edate = $end_date." 23:59:59";
			$date_check = "and `b`.`booking_date_time` between '".$sdate."' and '".$edate."'";
		}
		$query = "SELECT DISTINCT`p`.`order_id`, `b`.`booking_status`, `b`.`client_id`, `b`.`booking_date_time`, `s`.`color`, `s`.`title`, `p`.`net_amount` FROM `ct_bookings` as `b`,`ct_services` as `s`,`ct_payments` as `p`
		WHERE
		`b`.`order_id` = `p`.`order_id` and
		`b`.`service_id` = `s`.`id` ".$date_check."  GROUP BY `p`.`order_id` ORDER BY `b`.`order_id` DESC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	/*
	* Function for Read One Booking
	*
	*/
	public function readone(){
		$query="select * from `".$this->table_name."` where `id`='".$this->booking_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_row($result);
		return $value;
	}
	public function readone_order_date_time(){
		$query="select `booking_date_time` from `".$this->table_name."` where `order_id`='".$this->booking_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_assoc($result);
		return $value["booking_date_time"];
	}
	public function get_staff_readone($staff_id){
		$query="select `staff_ids` from `".$this->table_name."` where `id`='".$staff_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_row($result);
		return $value;
  }
	/*Function to Get Last order id from booking table used in front end for add cart item in booking table*/
	public function last_booking_id(){
		$query="select max(`order_id`) from `".$this->table_name."`";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_row($result);
		return $value= isset($value[0])? $value[0] : '' ;
	}
	public function confirm_booking(){
		$query="update `".$this->table_name."` set `booking_status`='".$this->booking_status."' where `id`='".$this->booking_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	public function confirm_booking_api(){
		$query="update `".$this->table_name."` set `booking_status`='".$this->booking_status."',`lastmodify`='".$this->lastmodify."' where `order_id`='".$this->order_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	public function update_reject_status(){
		$query="update `".$this->table_name."` set `booking_status`='R',`read_status`='U',`lastmodify`='".$this->lastmodify."',`reject_reason`='".$this->reject_reason."' where `order_id`='".$this->order_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	/* Used in booking_ajax */
	public function count_order_id_bookings(){
		$query="select count(`order_id`) as `ordercount` from `".$this->table_name."` where `id`='".$this->order_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
	/* used in booking_ajax */
	public function delete_booking(){
		$query="delete from `".$this->table_name."` where `id`='".$this->booking_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	/* used for delete appointments in booking_ajax */
	public function delete_appointments(){
		$query="delete `ct_bookings`.*,`ct_payments`.*,`ct_order_client_info`.* from `ct_bookings` INNER JOIN `ct_payments`,`ct_order_client_info` where `ct_bookings`.`order_id`=`ct_payments`.`order_id` and `ct_bookings`.`order_id`=`ct_order_client_info`.`order_id` and `ct_bookings`.`order_id`='".$this->order_id."' ";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	/* thi smethod is used in export page to list all bookings */
	public function get_all_bookings(){
		$query = "select DISTINCT  `order_id`, `client_id`, `service_id`, `booking_status`, `order_date`, `booking_date_time` from `ct_bookings`  ORDER BY `order_id` ";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	public function get_all_bookingss(){
		$query = "select DISTINCT  `order_id`, `client_id`, `service_id`, `booking_status`, `order_date`, `booking_date_time` from `ct_bookings` GROUP BY `order_id` ORDER BY `order_id` ";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	public function get_booking_service_name($order_id){
		$query = "select DISTINCT `ct_bookings`.`order_id`, `ct_services`.`title` as `sname` from `ct_bookings`,`ct_services` where `ct_bookings`.`order_id` = '".$order_id."' and `ct_bookings`.`service_id` = `ct_services`.`id`";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	/* get all bookings details from the order_id */
  public function get_detailsby_order_id($orderid){
		$query = "select DISTINCT `b`.`booking_status`, `b`.`client_id`,`b`.`reject_reason`,`b`.`staff_ids`,`b`.`gc_event_id`,`b`.`gc_staff_event_id`,`b`.`booking_date_time`,`s`.`title` as `service_title`,`p`.`net_amount`,`sm`.`method_title`,`oci`.`client_name`,`oci`.`client_email`,`oci`.`client_personal_info`,`p`.`payment_method`,`p`.`frequently_discount`,`oci`.`client_phone`, `oci`.`recurring_id` from `ct_bookings` as `b`,`ct_services` as `s`,`ct_payments` as `p`,`ct_services_method` as `sm`,`ct_order_client_info` as `oci` where `b`.`service_id` = `s`.`id` and `b`.`order_id` = `p`.`order_id` and `b`.`method_id` = `sm`.`id` and `b`.`order_id` = '".$orderid."' and `b`.`order_id` = `oci`.`order_id` ";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
  }
  /* CODE FOR DISPLAY DETAIL IN POPUP */
  public function get_booking_details_appt($orderid){
    $query = "select DISTINCT `b`.`booking_status`,`b`.`booking_date_time`,`p`.`net_amount`,`oci`.`client_name`,`oci`.`client_email`,`oci`.`client_personal_info`,`p`.`payment_method`,`oci`.`client_phone`,`s`.`title` as `service_title`,`b`.`gc_event_id` ,`b`.`gc_staff_event_id` ,`b`.`staff_ids` ,`b`.`reject_reason` ,`oci`.`order_duration`,`oci`.`recurring_id` from `ct_bookings` as `b`,`ct_payments` as `p`,`ct_order_client_info` as `oci`,`ct_services` as `s` where `b`.`order_id` = `p`.`order_id` and `b`.`order_id` = '".$orderid."' and `b`.`order_id` = `oci`.`order_id` and `b`.`service_id` = `s`.`id` ";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
  }
	/* CODE FOR DISPLAY DETAIL IN POPUP API Function */
	public function get_booking_details_appt_api($orderid)    {
		$query = "select DISTINCT `b`.`booking_status`,`b`.`booking_date_time`,`p`.`net_amount`,`oci`.`client_name`,`oci`.`client_email`,`oci`.`client_personal_info`,`p`.`payment_method`,`oci`.`client_phone`,`s`.`title` as `service_title`,`b`.`gc_event_id` ,`b`.`gc_staff_event_id` ,`b`.`staff_ids` from `ct_bookings` as `b`,`ct_payments` as `p`,`ct_order_client_info` as `oci`,`ct_services` as `s`where `b`.`order_id` = `p`.`order_id`and `b`.`order_id` = '" . $orderid . "' and `b`.`order_id` = `oci`.`order_id` and `b`.`service_id` = `s`.`id` ";
		$result = mysqli_query($this->conn, $query);
		$value = mysqli_fetch_array($result);
		return $value;   
	}
	/* CODE FOR DISPLAY DETIAL IN POPUP  END */
	public function getdatabyorder_id($orderid){
			$query = "select * from `ct_bookings` where `order_id` = '".$orderid."'";
			$result=mysqli_query($this->conn,$query);
			return $result;
	}
	/* get all methods and units of the bookings */
	public function get_methods_ofbookings($orderid){
		$query = "select `b`.`method_unit_qty` as `qtys`,`sm`.*,`smu`.* from `ct_bookings` as `b`,`ct_services_method` as `sm`,`ct_service_methods_units` as `smu` where `b`.`method_id` = `sm`.`id` and `b`.`method_unit_id` = `smu`.`id` and `b`.`order_id` ='".$orderid."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	/* get all addons services of bookings */
	public function get_addons_ofbookings($orderid){
		$query = "select DISTINCT `ba`.*,`sa`.* from `ct_bookings` as `b`,`ct_booking_addons` as `ba`,`ct_services_addon` as `sa` where `b`.`order_id` = `ba`.`order_id` and `ba`.`addons_service_id` = `sa`.`id` and `b`.`order_id` = '".$orderid."' ";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	/*Use function for Invoice Purpose*/
	public function get_details_for_invoice_client(){
		$query="select `b`.`order_id` as `invoice_number`,`b`.`booking_date_time` as `start_time`,`b`.`order_date` as `invoice_date`,`b`.`service_id` as `sid`,`b`.`client_id` as `cid` from `ct_bookings` as `b`,`ct_payments` as `p`,`ct_order_client_info` as `oc` where `b`.`order_id`='".$this->order_id."' and `b`.`order_id`=`p`.`order_id` and `b`.`order_id`=`oc`.`order_id` ";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_row($result);
		return $value;
	}
	/* Get Client Info from user table */	
	public function get_client_info(){
		$query="select * from `".$this->tablename3."` where `id`='".$this->client_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_row($result);
		return $value;
	}
	/* Booking readall */
	public function readall_bookings(){
		$query="select * from `".$this->table_name."` where `order_id`='".$this->order_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	public function email_reminder(){
		$query="select `id`,`order_id`,`client_id`,`order_date`,`booking_date_time`,`service_id`,`method_id`,`method_unit_id`,`method_unit_qty`,`method_unit_qty_rate`,`booking_status`,`reject_reason`,`reminder_status`,`lastmodify`,`read_status`,`staff_ids`,`gc_event_id`,`gc_staff_event_id` from `".$this->table_name."` where (`reminder_status`='0' OR `reminder_status`='') and `booking_status`='C' ";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	public function update_reminder_booking($id){
		$query="update `".$this->table_name."` set `reminder_status`='1' where `id`='".$id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
  public function getalldetail_for_reminder($orderid){
		$query="select DISTINCT `s`.`title`,`b`.`booking_date_time`,`oci`.`client_name`,`oci`.`client_email` from `ct_bookings` as `b`,`ct_services` as `s`,`ct_order_client_info` as `oci` where `b`.`order_id` = '".$orderid."' and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `oci`.`order_id` ";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_row($result);
		return $value;
  }
	public function check_for_service_addons_availabilities($sid){
		$query="select count(`a`.`id`) as `count_of_addons` from `ct_services_addon` as `a` where `a`.`service_id` = '$sid'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['count_of_addons'];
  }
	public function check_for_service_units_availabilities($sid){
		$query="select count(`id`) as `count_of_method` from `ct_services_method` where `service_id` = '$sid'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['count_of_method'];
  }
	public function save_staff_to_booking($sid){
		$query="update `".$this->table_name."` set `staff_ids`='".$sid."' where `order_id`='".$this->order_id."'";
		$result=mysqli_query($this->conn,$query);
  }
	public function fetch_staff_of_booking(){
		$query = "SELECT DISTINCT `staff_ids` FROM `ct_bookings` where `order_id` = '".$this->order_id."' ";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value= isset($value[0])? $value[0] : '' ;
	}
	function getWeeks($date, $rollover){
		$cut = substr($date, 0, 8);
		$daylen = 86400;
		$timestamp = strtotime($date);
		$first = strtotime($cut . "00");
		$elapsed = ($timestamp - $first) / $daylen;
		$weeks = 1;
		for ($i = 1; $i <= $elapsed; $i++){
				$dayfind = $cut . (strlen($i) < 2 ? '0' . $i : $i);
				$daytimestamp = strtotime($dayfind);
				$day = strtolower(date("l", $daytimestamp));
				if($day == strtolower($rollover))  $weeks ++;
		}
		return $weeks;
	}
	function get_staff_detail_for_email($sid){
		$query="select * from `ct_admin_info` where `id` = '".$sid."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
	function get_staff_ids_from_bookings($oid){
		$query="select * from `ct_bookings` where `order_id` = '".$oid."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['staff_ids'];
	}
	function booked_staff_status(){
		$query = "select GROUP_CONCAT(`staff_ids`) as `sc` from `".$this->table_name."` where `booking_date_time` = '".$this->booking_date_time."' and `staff_ids` != ''";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value= isset($value[0])? $value[0] : '' ;
	}
	/* Update GC Event ID */
	function update_gc_event_id($last_id,$gc_event_id) {
		$update_gc_event_query = "update ".$this->table_name." set gc_event_id = '".$gc_event_id."' where order_id = '".$last_id."'";
		$res = mysqli_query($this->conn,$update_gc_event_query);
		return $res;
	}
	function update_gc_staffid_event_id($last_id,$gc_event_id) {
		$update_gc_event_query = "update ".$this->table_name." set gc_staff_event_id = '".$gc_event_id."' where order_id = '".$last_id."'";
		$res = mysqli_query($this->conn,$update_gc_event_query);
		return $res;
	}
 	public function readall_bookings_oid(){
		$query="select * from `".$this->table_name."` where `order_id`='".$this->order_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
	public function read_net_amt(){
		$query="select * from ct_payments where `order_id`='".$this->order_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
	public function check_for_booking_date_time($booking_date_time,$staff_id){
		$query="select * from ct_bookings where `booking_date_time`='".$booking_date_time."'";
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			return false;
		}else{
			if($staff_id != ''){
				$exploded_staffs = explode(',',$staff_id);
				$i=1;
				foreach($exploded_staffs as $staff){
					$qry="select * from ct_week_days_available where `provider_id`='".$staff."' limit 1";
					$res=mysqli_query($this->conn,$qry);
					if(sizeof((array)$exploded_staffs) == $i){
						if(mysqli_num_rows($res)>0){
							$val = mysqli_fetch_assoc($res);
							if($val['provider_schedule_type'] == 'monthly'){
								$date = date('Y-m-d', strtotime($booking_date_time));
								$date_day = date('l', strtotime($booking_date_time));
								$week_id = $this->getWeeks($date, $date_day);
								$weekday_id = date('N', strtotime($booking_date_time));
								
								$q="select * from ct_week_days_available where `weekday_id`='".$weekday_id."' and `week_id`='".$week_id."' and `off_day`='Y'";
								$r=mysqli_query($this->conn,$q);
								if(mysqli_num_rows($r)>0){
									return false;
								}else{
									return true;
								}
							}else{
								$date = date('Y-m-d', strtotime($booking_date_time));
								$week_id = '1';
								$weekday_id = date('N', strtotime($booking_date_time));
								
								$q="select * from ct_week_days_available where `weekday_id`='".$weekday_id."' and `week_id`='".$week_id."' and `off_day`='Y'";
								$r=mysqli_query($this->conn,$q);
								if(mysqli_num_rows($r)>0){
									return false;
								}else{
									return true;
								}
							}
						}else{
							$qq="select * from ct_week_days_available where `provider_id`='1' limit 1";
							$rr=mysqli_query($this->conn,$qq);
							if(mysqli_num_rows($rr)>0){
								$val = mysqli_fetch_assoc($rr);
								if($val['provider_schedule_type'] == 'monthly'){
									$date = date('Y-m-d', strtotime($booking_date_time));
									$date_day = date('l', strtotime($booking_date_time));
									$week_id = $this->getWeeks($date, $date_day);
									$weekday_id = date('N', strtotime($booking_date_time));
									
									$q="select * from ct_week_days_available where `weekday_id`='".$weekday_id."' and `week_id`='".$week_id."' and `off_day`='Y'";
									$r=mysqli_query($this->conn,$q);
									if(mysqli_num_rows($r)>0){
										return false;
									}else{
										return true;
									}
								}else{
									$date = date('Y-m-d', strtotime($booking_date_time));
									$week_id = '1';
									$weekday_id = date('N', strtotime($booking_date_time));
									
									$q="select * from ct_week_days_available where `weekday_id`='".$weekday_id."' and `week_id`='".$week_id."' and `off_day`='Y'";
									$r=mysqli_query($this->conn,$q);
									if(mysqli_num_rows($r)>0){
										return false;
									}else{
										return true;
									}
								}
							}else{
								return false;
							}
						}
					}elseif(mysqli_num_rows($res)>0){
						$val = mysqli_fetch_assoc($res);
						if($val['provider_schedule_type'] == 'monthly'){
							$date = date('Y-m-d', strtotime($booking_date_time));
							$date_day = date('l', strtotime($booking_date_time));
							$week_id = $this->getWeeks($date, $date_day);
							$weekday_id = date('N', strtotime($booking_date_time));
							
							$q="select * from ct_week_days_available where `weekday_id`='".$weekday_id."' and `week_id`='".$week_id."' and `off_day`='Y'";
							$r=mysqli_query($this->conn,$q);
							if(mysqli_num_rows($r)>0){
								return false;
							}else{
								return true;
							}
						}else{
							$date = date('Y-m-d', strtotime($booking_date_time));
							$week_id = '1';
							$weekday_id = date('N', strtotime($booking_date_time));
							
							$q="select * from ct_week_days_available where `weekday_id`='".$weekday_id."' and `week_id`='".$week_id."' and `off_day`='Y'";
							$r=mysqli_query($this->conn,$q);
							if(mysqli_num_rows($r)>0){
								return false;
							}else{
								return true;
							}
						}
					}else{
						$i++;
						continue;
					}
					$i++;
				}
			}else{
				$qq="select * from ct_week_days_available where `provider_id`='1' limit 1";
				$rr=mysqli_query($this->conn,$qq);
				if(mysqli_num_rows($rr)>0){
					$val = mysqli_fetch_assoc($rr);
					if($val['provider_schedule_type'] == 'monthly'){
						$date = date('Y-m-d', strtotime($booking_date_time));
						$date_day = date('l', strtotime($booking_date_time));
						$week_id = $this->getWeeks($date, $date_day);
						$weekday_id = date('N', strtotime($booking_date_time));
						
						$q="select * from ct_week_days_available where `weekday_id`='".$weekday_id."' and `week_id`='".$week_id."' and `off_day`='Y'";
						$r=mysqli_query($this->conn,$q);
						if(mysqli_num_rows($r)>0){
							return false;
						}else{
							return true;
						}
					}else{
						$date = date('Y-m-d', strtotime($booking_date_time));
						$week_id = '1';
						$weekday_id = date('N', strtotime($booking_date_time));
						
						$q="select * from ct_week_days_available where `weekday_id`='".$weekday_id."' and `week_id`='".$week_id."' and `off_day`='Y'";
						$r=mysqli_query($this->conn,$q);
						if(mysqli_num_rows($r)>0){
							return false;
						}else{
							return true;
						}
					}
				}else{
					return false;
				}
			}
		}
	}
	public function staff_status_select_staff_id(){
		$query="select `id` from `".$this->table_staff_status."` where `staff_id`='".$this->staff_id."' and  `order_id`='".$this->order_id."'";
		$result=mysqli_query($this->conn,$query);
		$value = mysqli_fetch_assoc($result);
		return $value['id'];
  }
  public function readone_bookings_details_by_order_id_s_id(){
		$query="select status from `".$this->table_staff_status."` where `order_id`='".$this->order_id."' and `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value = mysqli_fetch_array($result);
		return $value['status'];
	}
	public function readone_bookings_details_by_order_id(){
		$query="select * from `".$this->table_name."` where `order_id`='".$this->order_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
	public function update_staff_status(){
		$query="update `".$this->table_staff_status."` set `status`='".$this->status."' where  `order_id`='".$this->order_id."' and `id`='".$this->id."'";
		$query1="update `ct_bookings` set `booking_status`='".$this->status."' where  `order_id`='".$this->order_id."'";
		$result=mysqli_query($this->conn,$query);
		$result=mysqli_query($this->conn,$query1);
		return $result;
  }
	public function readone_bookings_sid_staff(){
		$query="select * from `".$this->table_staff_status."` where `id`='".$this->id."' and `status`='".$this->status."' order by order_id DESC limit 1";
	  $result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		/* $id=$value['order_id']; */
		return $value;
	}
	
	public function update_staff_id_bookings_details_by_order_id(){
		$query="update `".$this->table_name."` set `staff_ids`='".$this->staff_id."' where `order_id`='".$this->booking_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;	
	}
	public function staff_status_insert(){
		$query = "INSERT INTO `".$this->table_staff_status."`(`id`,`staff_id`,`order_id`,`status`) VALUES(null,'".$this->staff_id."','".$this->order_id."','A')";
		$result=mysqli_query($this->conn,$query);
		return mysqli_insert_id($this->conn);
	}
	public function staff_status_read_one_by_or_id(){
		$query="SELECT * FROM `".$this->table_staff_status."` WHERE `order_id`='".$this->order_id."' ORDER BY `id` DESC";
		$result = mysqli_query($this->conn,$query);
		return $result;
	}
	public function get_all_past_bookings(){
		$query = "SELECT DISTINCT `order_id` FROM `ct_bookings` WHERE `booking_date_time`<='".$this->booking_start_datetime."'  ORDER BY `order_id` ";
		$result=mysqli_query($this->conn,$query);
		return $result;
  }
	public function get_all_upcoming_bookings(){
	$query = "SELECT DISTINCT `order_id` FROM `ct_bookings` WHERE `booking_date_time`>='".$this->booking_start_datetime."'  ORDER BY `order_id` ";
	$result=mysqli_query($this->conn,$query);
	return $result;
	}
	/* API Function */
	public function get_all_past_bookings_api(){
		$query  = "SELECT DISTINCT `booking_date_time`,`order_id`,`client_id`,`staff_ids` FROM `ct_bookings` WHERE `booking_date_time`<'" . $this->booking_start_datetime . "'  ORDER BY `booking_date_time` DESC";
		$result = mysqli_query($this->conn, $query);
		return $result;
	}
	/* API Function */
    public function get_all_upcoming_bookings_api(){
	    $type = $this->type;
	    if($type == 'client'){
			$query  = "SELECT DISTINCT `order_id`,`client_id`,`staff_ids` FROM `ct_bookings` WHERE `booking_date_time`>='" . $this->booking_start_datetime . "' and `client_id` = '" . $this->user_id . "'";
			$result = mysqli_query($this->conn, $query);
			return $result;
	    }else{
			$query  = "SELECT DISTINCT `order_id`,`client_id`,`staff_ids` FROM `ct_bookings` WHERE `booking_date_time`>='" . $this->booking_start_datetime . "' and `staff_ids` = '" . $this->user_id . "'";
			$result = mysqli_query($this->conn, $query);
			return $result;   
	    }
	}
	public function complete_booking(){
		$query = "UPDATE `".$this->table_name."` SET `booking_status`='".$this->booking_status."',`lastmodify`='".$this->lastmodify."' WHERE `order_id`='".$this->order_id."'";
		$result = mysqli_query($this->conn,$query);
		return $result;
	}
	public function clone_booking_order_detail(){
		$query = "select * from `".$this->table_name."` where `order_id`='".$this->order_id."'";
		$result = mysqli_query($this->conn,$query);
		return $result;
	}
	public function clone_addon_order_detail(){
		$query = "select * from `".$this->tablename5."` where `order_id`='".$this->order_id."'";
		$result = mysqli_query($this->conn,$query);
		return $result;
	}
	public function clone_order_client_detail(){
		$query = "select * from `".$this->tablename2."` where `order_id`='".$this->order_id."'";
		$result = mysqli_query($this->conn,$query);
		return $result;
	}
	public function clone_payment_order_detail(){
		$query = "select * from `".$this->tablename4."` where `order_id`='".$this->order_id."'";
		$result = mysqli_query($this->conn,$query);
		return $result;
	}
	public function check_booking_by_gc_id($gc_event_id){
		$query = "select `order_id`,`booking_date_time`,`gc_event_id`,`gc_staff_event_id` from `".$this->table_name."` where `gc_event_id`='".$gc_event_id."'";
		$result = mysqli_query($this->conn,$query);
		return $result;
	}
	public function get_all_gc_from_db(){
		 $query = "select `recurring_id`,`order_id`,`staff_ids`,`gc_event_id`,`gc_staff_event_id` from `ct_bookings` INNER JOIN `ct_order_client_info` where `ct_bookings`.`order_id`=`ct_order_client_info`.`order_id`";
		$result = mysqli_query($this->conn,$query);
		return $result;
	}
	/* GET APPOINTMENTS for Admin API*/
	public function get_all_bookings_api(){
		$query="select DISTINCT `p`.`order_id`, `b`.`booking_date_time`, `b`.`booking_status`, `b`.`reject_reason`,`s`.`title`,`p`.`net_amount` as `total_payment`,`b`.`gc_event_id`,`b`.`gc_staff_event_id`,`b`.`staff_ids` from `ct_bookings` as `b`,`ct_payments` as `p`,`ct_services` as `s`,`ct_users` as `u` where `b`.`client_id` = `u`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `p`.`order_id`  order by `b`.`order_id` desc limit ".$this->limit." offset ".$this->offset;
		$result = mysqli_query($this->conn,$query);
		return $result;
	}
	
	public function get_booking_count_of_staff(){
		$query = "SELECT DISTINCT `staff_ids` FROM ".$this->table_name." ";
		$result = mysqli_query($this->conn,$query);
		$count_booking = 0;
		if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)){
				$staff_id_array = explode(",",$row["staff_ids"]);
				if(in_array($this->staff_id,$staff_id_array)){
					$count_booking++;
				}
			}
		}
		return $count_booking;
	}
	/*public function get_booking_time(){
		$query = "select `b`.`booking_date_time`,`oci`.`order_duration` from `ct_bookings` as `b`,`ct_order_client_info` as `oci` where `b`.`order_id` = `oci`.`order_id` GROUP BY `b`.`id`, `b`.`order_id`, `b`.`booking_date_time`,`oci`.`id`,`oci`.`order_id`";
		$result = mysqli_query($this->conn,$query);
		return $result;
	}*/
	public function get_booking_time($selected_date){
		/*echo "select `booking_date_time` from `ct_bookings` where `booking_date_time`='".$selected_date."'";*/
		$query = "select `booking_date_time` from `ct_bookings` where `booking_date_time`='".$selected_date."'";
		$result = mysqli_query($this->conn,$query);
		return $result;
  }

  public function add_referral_coupon(){
    $query2 = "SELECT id FROM ct_users WHERE referal_code='".$this->friend_referral_code."'";
    $result2 = mysqli_query($this->conn,$query2);
    $value = mysqli_fetch_array($result2);
    $friend_referral_id = $value['id'];

    
    $query="INSERT into `ct_referral_coupon` (`id`,`client_id`,`referral_coupon`,`friend_referral_id`,`coupon_limit`,`coupon_used`) values(NULL,'".$this->client_id."','".$this->random_string."','".$friend_referral_id."','".$this->coupon_limit."','".$this->coupon_used."')";
    $result=mysqli_query($this->conn,$query);
    $value=mysqli_insert_id($this->conn); 
    return $value;
  }
		public function get_sername_byser_id($ser_id){
		$query="select * from `ct_services` where id='".$ser_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_assoc($result);
		return $value;
	}
	public function get_units($orderid){
    $query = "SELECT 
			GROUP_CONCAT(distinct `smu`.`units_title` SEPARATOR '|') as `units_title`,
                GROUP_CONCAT(`b`.`method_unit_qty` SEPARATOR '|') as `meth_qty`
			FROM `ct_bookings` as `b`,`ct_services` as `s`,`ct_payments` as `p`,`ct_services_method` as `sm`,`ct_service_methods_units` as `smu`
                WHERE `b`.`service_id` = `s`.`id`
                and `b`.`order_id` = `p`.`order_id`
			and `b`.`method_unit_id` = `smu`.`id`
			and `b`.method_id=`sm`.`id`
                and `b`.`order_id` = '$orderid' GROUP BY `b`.`order_id`,`b`.`service_id`,`b`.`method_id`";
    $result=mysqli_query($this->conn,$query);
    return $result;
  }

  /* get all edit bookings smu details from the order_id */
  public function get_edit_smu_by_order_id($orderid){
    $query =  "select DISTINCT `b`.`service_id`,`b`.`method_id`,`b`.`method_unit_id`,`b`.`method_unit_qty`,`b`.`method_unit_qty_rate`,`b`.`booking_status`,`b`.`booking_date_time`,`b`.`staff_ids`,`smu`.`units_title`,`smu`.`uduration` from `ct_bookings` as `b`,`ct_services` as `s`,`ct_service_methods_units` as `smu` where `b`.`order_id` = '" . $orderid . "' and `b`.`service_id` = `s`.`id` and `b`.`method_unit_id` = `smu`.`id` ";
    $result=mysqli_query($this->conn,$query);
    return $result;
  }

  /* get all edit bookings sa details from the order_id */
  public function get_edit_sa_by_order_id($orderid){
    $query =  "select DISTINCT `ba`.`service_id`,`ba`.`addons_service_id`,`ba`.`addons_service_qty`,`ba`.`addons_service_rate`,`sa`.`addon_service_name`,`sa`.`aduration` from `ct_booking_addons` as `ba`,`ct_services_addon` as `sa` where `ba`.`order_id` = '" . $orderid . "' and `ba`.`service_id` = `sa`.`service_id` and `ba`.`addons_service_id` = `sa`.`id` ";
    $result=mysqli_query($this->conn,$query);
    return $result;
  }
	
  /* Count recurrence booking */
  public function count_recurrence(){
    $query="select * from `".$this->tablename2."` where `recurring_id`='".$this->recurring_id."'";
    $result=mysqli_query($this->conn,$query);
    $value=mysqli_num_rows($result);
    return $value;
  }
}
?>