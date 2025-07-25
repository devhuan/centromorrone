<?php

include (dirname(__FILE__) . '/header.php');
include (dirname(__FILE__) . '/admin_session_check.php');
include (dirname(dirname(__FILE__)) . "/objects/class_userdetails.php");
include (dirname(dirname(__FILE__)) . "/objects/class_booking.php");
include (dirname(dirname(__FILE__)) . '/objects/class_front_first_step.php');
include (dirname(dirname(__FILE__)) . "/objects/class_rating_review.php");
include (dirname(dirname(__FILE__)) . "/objects/class_frequently_discount.php");
include (dirname(dirname(__FILE__)) . "/objects/class_order_client_info.php");

if (!isset($_SESSION['ct_login_user_id']))
{
    header('Location:' . SITE_URL . "admin/");
}
$con = new cleanto_db();
$conn = $con->connect();
$objuserdetails = new cleanto_userdetails();
$objuserdetails->conn = $conn;
$booking = new cleanto_booking();
$booking->conn = $conn;
$setting = new cleanto_setting();
$setting->conn = $conn;
$general = new cleanto_general();
$general->conn = $conn;
$first_step = new cleanto_first_step();
$first_step->conn = $conn;
$rating_review = new cleanto_rating_review();
$rating_review->conn = $conn;
$frequently_discount = new cleanto_frequently_discount();
$frequently_discount->conn = $conn;
$objocinfo = new cleanto_order_client_info();
$objocinfo->conn = $conn;
$symbol_position = $setting->get_option('ct_currency_symbol_position');
$decimal = $setting->get_option('ct_price_format_decimal_places');
$getdateformat = $setting->get_option('ct_date_picker_date_format');
$time_format = $setting->get_option('ct_time_format');
$date_format = $setting->get_option('ct_date_picker_date_format');
$getmaximumbooking = $setting->get_option('ct_max_advance_booking_time');
$t_zone_value = $setting->get_option('ct_timezone');
$server_timezone = date_default_timezone_get();
if (isset($t_zone_value) && $t_zone_value != '')
{
    $offset = $first_step->get_timezone_offset($server_timezone, $t_zone_value);
    $timezonediff = $offset / 3600;
}
else
{
    $timezonediff = 0;
}
if (is_numeric(strpos($timezonediff, '-')))
{
    $timediffmis = str_replace('-', '', $timezonediff) * 60;
    $currDateTime_withTZ = strtotime("-" . $timediffmis . " minutes", strtotime(date('Y-m-d H:i:s')));
}
else
{
    $timediffmis = str_replace('+', '', $timezonediff) * 60;
    $currDateTime_withTZ = strtotime("+" . $timediffmis . " minutes", strtotime(date('Y-m-d H:i:s')));
} ?>

	<div id="cta-user-appointments">    
		<div class="panel-body">        
			<div class="tab-content">            
				<h4 class="header4"><?php echo $label_language_values['my_appointments']; ?>	
					<a href="<?php echo SITE_URL; ?>" class="btn btn-success pull-right" target="_BLANK"><?php echo $label_language_values['book_appointment']; ?></a>
					</h4>            
					<form>                
						<div class="table-responsive">                    
							<table id="user-profile-booking-table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"  width="100%">   
								<thead>                        
									<tr>													
										<th><?php echo $label_language_values['order']; ?>#</th>													
										<th><?php echo $label_language_values['order_date']; ?></th>													
										<th><?php echo $label_language_values['order_time']; ?></th>													
										<th><?php echo $label_language_values['show_all_bookings']; ?></th>	
										<th><?php echo $label_language_values['actions']; ?></th>                        
									</tr>                        
								</thead>                        
								<tbody class="my-app-tb">                        
							<?php
if (isset($_SESSION['ct_login_user_id']))
{
    $id = $_SESSION['ct_login_user_id'];
    $objuserdetails->id = $id;
    $details = $objuserdetails->get_user_details();
   		 while ($dd = mysqli_fetch_array($details)) { ?>                            
	    	<tr>                                
				<td><?php echo $dd['order_id']; ?></td>                                
				<?php if ($time_format == 12) { ?> 
                <td><?php echo str_replace($english_date_array, $selected_lang_label, date($getdateformat, strtotime($dd['booking_date_time']))); ?></td>
                 <?php }  else  { ?>                                    
                <td><?php echo str_replace($english_date_array, $selected_lang_label, date($getdateformat, strtotime($dd['booking_date_time']))); ?></td>         
            	<?php  } ?> <?php if ($time_format == 12) { ?>
            	<td><?php echo str_replace($english_date_array, $selected_lang_label, date(" h:i A", strtotime($dd['booking_date_time']))); ?></td>                    
            	<?php  }  else  { ?>                                    
            	<td><?php echo date(" H:i", strtotime($dd['booking_date_time'])); ?></td>       
            	<?php  } ?>                                
            	<td><a href="#user-booking-details<?php echo $dd['order_id']; ?>" data-toggle="modal" data-target="#user-booking-details<?php echo $dd['order_id']; ?>" class="ct-my-booking-user btn btn-info myappointment_popup"><i class="fa fa-eye"></i><?php echo $label_language_values['my_appointments']; ?></a> 
            	</td>                                
            	<td><?php if ($dd["recurrence_status"] == "Y" && strtotime($dd['booking_date_time']) >= $currDateTime_withTZ)
		        {
		            $frequently_discount->id = $dd['frequently_discount'];
		            $frequently_discount_detail = $frequently_discount->readone();
		            $objocinfo->order_id = $dd['order_id'];
		            $oc_detail = $objocinfo->readone_order_client();
		            $objocinfo->recurring_id = $oc_detail["recurring_id"];
		            $count_rec = $objocinfo->count_recurring_id();
		            $count_rec_status = $objocinfo->get_one_rec_status();

            if ($count_rec > 0 && mysqli_num_rows($count_rec_status) == 0)
            { ?>							
            	<button type="button" data-toggle="popover_sent_req" rel="popover" data-placement="left" title="<?php echo $label_language_values['cancel_recurrence']; ?>" class="btn btn-success badges_show" data-order_id="<?php echo $dd['order_id']; ?>"><i class="fa fa-times" aria-hidden="true"></i>							
            	<span class="badge br-10 hide_badges badge_<?php echo $dd['order_id']; ?>"><?php echo $oc_detail["recurring_id"]; ?>&nbsp;/&nbsp;<?php echo $frequently_discount_detail['discount_typename']; ?></span>					
            	</button>							
            	<div id="popover-request-recurrence" style="display: none;">						<div class="arrow"></div>								
            	<table class="form-horizontal" cellspacing="0">									
            		<tbody>									
            			<tr>										
            				<td>											
            					<a data-recurring_id="<?php echo $oc_detail["recurring_id"]; ?>" class="btn btn-danger btn-sm recurring_request" ><?php echo $label_language_values['yes']; ?></a>											
            					<button type="button" id="ct-close-popover-request-recurrence" class="btn btn-default btn-sm" href="javascript:void(0)"><?php echo $label_language_values['cancel']; ?></button>										
            				</td>									
            			</tr>									
            		</tbody>								
            	</table>							
            	</div><?php
            }
        } ?>            
        <a target="_blank" href="<?php echo BASE_URL; ?>/assets/lib/download_invoice_client.php?iid=<?php echo $dd['order_id']; ?>" class="btn btn-primary"><i class="fa fa-download"></i><?/*php echo $label_language_values['download_invoice']; */?>Download Ricevuta
        </a>									
        <?php $rating_review->order_id = $dd['order_id'];
        $rating = $rating_review->select_one();
        $bt = date("Y-m-d H:i:s", strtotime($dd['booking_date_time']));
        $booking_status = $dd['booking_status'];
        if ($dd['staff_ids'] != '' && $rating == 0 && $booking_status == "CO")
        { ?>																		
        	<?php/*<button type="button" class="btn btn-info" data-toggle="modal" data-id="<?php echo $dd['order_id']; ?>"  data-target="#rating_model<?php echo $dd['order_id']; ?>"><?php echo $label_language_values['rating_and_review']; ?></button>*/?>
        	<div class="modal fade" id="rating_model<?php echo $dd['order_id']; ?>" role="dialog">	<div class="modal-dialog">		
        		<div class="modal-content">			
        			<div class="modal-header">				
        				<button type="button" class="close" data-dismiss="modal">&times;</button>
        				<h4 class="modal-title"><?php echo $label_language_values['rating_and_review']; ?></h4>			
        				</div>			
        				<div class="modal-body">				
        					<input id="ratings<?php echo $dd['order_id']; ?>" name="ratings<?php echo $dd['order_id']; ?>" class="rating" data-min="0" data-max="5" data-step="0.1" value="0" /><br />				
        					<label class="control-label"><?php echo $label_language_values['review']; ?></label>				
        					<textarea class="form-control custom_textarea_feedback" id="review_note<?php echo $dd['order_id']; ?>" name="review_note<?php echo $dd['order_id']; ?>"></textarea><br />				
        					<button type="button" data-staff_id="<?php echo $dd['staff_ids']; ?>" data-id="<?php echo $dd['order_id']; ?>" id="rating_review_submit" class="btn btn-success"><?php echo $label_language_values['submit']; ?></button>			
        					</div>			
        					<div class="modal-footer">				
        						<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $label_language_values['close']; ?></button>	
        					</div>		
        				</div>	
        			</div>
        		</div>						
        	<?php  } ?>                                
        	</td>                            
        	</tr> <?php } ?>                        
        </tbody>                    
      </table>                
    </div>                

    <?php $details = $objuserdetails->get_user_details();
    while ($dd = mysqli_fetch_array($details)) { ?>                    
    	<div id="user-booking-details<?php echo $dd['order_id']; ?>" class="user-booking-details modal fade" tabindex="-1" role="dialog" aria-hidden="true">                        
    		<div class="modal-dialog modal-lg">							
    			<div class="modal-content">								
    				<div class="modal-header">									
    					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>									
    					<h4 class="modal-title"><?php echo $label_language_values['my_appointments']; ?></h4>								
    					</div>								
    					<div class="modal-body">									
    						<div class="table-responsive">										
    							<table id="user-all-bookings-details" class="table table-striped table-bordered responsive nowrap" cellspacing="0" width="100%">											
    							<thead>											
    								<tr >												
    									<th><?php echo $label_language_values['order']; ?>#</th>
    									<?/*<th><?php echo $label_language_values['service']; ?>Servizio</th>*/?>
    									<th style="width: 240px;"><?php echo $label_language_values['booking_date_and_time']; ?>
    									</th>												
    									<th style="width: 230px;"><?php echo $label_language_values['more_details']; ?></th>												
    									<th><?php echo $label_language_values['status']; ?></th>												
    									<?/*<th><?php echo $label_language_values['actions']; ?></th>*/?>										
    								</tr>											
    							</thead>											
    						<tbody>											
    							<tr>												
    								<td><?php echo $dd['order_id']; ?></td>												
    								<?/*php<td><?php echo $dd['title']; ?></td>	*/?>											
    								<?php if ($time_format == 12) { ?>							
    								<td><?php echo str_replace($english_date_array, $selected_lang_label, date($getdateformat . " h:i A", strtotime($dd['booking_date_time']))); ?></td>		
    								<?php  }  else  { ?>										
    								<td><?php echo str_replace($english_date_array, $selected_lang_label, date($getdateformat . " H:i", strtotime($dd['booking_date_time']))); ?></td>			
	    							<?php  } ?>												
	    							<td><?php /* methods */
							        $units = "None";
							        $methodname = "None";
							        $hh = $booking->get_methods_ofbookings($dd['order_id']);
							        $hh = $booking->get_methods_ofbookings($dd['order_id']);
							        $count_methods = mysqli_num_rows($hh);
							        $hh1 = $booking->get_methods_ofbookings($dd['order_id']);
							        if ($count_methods > 0)
							        {
							            while ($jj = mysqli_fetch_array($hh1))
							            {
							                if ($units == "None")
							                {
							                    $units = $jj['units_title'] . "-" . $jj['qtys'];
							                }
							                else
							                {
							                    $units = $units . "," . $jj['units_title'] . "-" . $jj['qtys'];
							                }
							                $methodname = $jj['method_title'];
							            }
							        }
							        $addons = "None";
							        $hh = $booking->get_addons_ofbookings($dd['order_id']);
							        while ($jj = mysqli_fetch_array($hh))
							        {
							            if ($addons == "None")
							            {
							                $addons = $jj['addon_service_name'] . "-" . $jj['addons_service_qty'];
							            }
							            else
							            {
							                $addons = $addons . "," . $jj['addon_service_name'] . "-" . $jj['addons_service_qty'];
							            }
							        } ?>													
							        <?/*php<b><?php echo $label_language_values['methods']; ?></b> - <?php echo $methodname; ?>													
							        <br>													
							        <b><?php echo $label_language_values['units']; ?></b> - <?php echo $units; ?>													
							        <br>*/?>										
							        <b><?php echo $label_language_values['add_ons']; ?></b> - <?php echo $addons; ?>												
							        </td>
		<td class="txt-success"><?php if ($dd['booking_status'] == 'A')
        {
            $booking_stats = $label_language_values['active'];
        }
        elseif ($dd['booking_status'] == 'C')
        {
            $booking_stats = '<i class="fa fa-check txt-success">' . $label_language_values['confirm'] . '</i>';
        }
        elseif ($dd['booking_status'] == 'R')
        {
            $booking_stats = '<i class="fa fa-ban txt-danger">' . $label_language_values['reject'] . '</i><br><b class="txt-danger">Reason : ' . $dd['reject_reason'] . '</b>';
        }
        elseif ($dd['booking_status'] == 'RS')
        {
            $booking_stats = '<i class="fa fa-pencil-square-o txt-info">' . $label_language_values["rescheduled"] . '</i>';
        }
        elseif ($dd['booking_status'] == 'CC')
        {
            $booking_stats = '<i class="fa fa-times txt-primary">' . $label_language_values['cancel_by_client'] . '</i>';
        }
        elseif ($dd['booking_status'] == 'CS')
        {
            $booking_stats = '<i class="fa fa-times-circle-o txt-info">' . $label_language_values['cancelled_by_service_provider'] . '</i>';
        }
        elseif ($dd['booking_status'] == 'CO')
        {
            $booking_stats = '<i class="fa fa-thumbs-o-up txt-success">' . $label_language_values['completed'] . '</i>';
        }
        else
        {
            $dd['booking_status'] == 'MN';
            $booking_stats = '<i class="fa fa-thumbs-o-down txt-danger">' . $label_language_values['mark_as_no_show'] . '</i>';
        } ?><?php echo $booking_stats; ?>												
        </td>												
        <?/*php<td><?php $t_zone_value = $setting->get_option('ct_timezone');
        $server_timezone = date_default_timezone_get();
        if (isset($t_zone_value) && $t_zone_value != '')
        {
            $offset = $first_step->get_timezone_offset($server_timezone, $t_zone_value);
            $timezonediff = $offset / 3600;
        }
        else
        {
            $timezonediff = 0;
        }
        if (is_numeric(strpos($timezonediff, '-')))
        {
            $timediffmis = str_replace('-', '', $timezonediff) * 60;
            $currDateTime_withTZ = strtotime("-" . $timediffmis . " minutes", strtotime(date('Y-m-d H:i:s')));
        }
        else
        {
            $timediffmis = str_replace('+', '', $timezonediff) * 60;
            $currDateTime_withTZ = strtotime("+" . $timediffmis . " minutes", strtotime(date('Y-m-d H:i:s')));
        }
		$current_times = date('Y-m-d H:i:s', $currDateTime_withTZ); 
        $td = date('Y-m-d H:i:s', strtotime($current_times));
        if ($bt < $td )
        { ?><a class="btn btn-danger"  rel="popover"  ><i class="fa fa-check"></i><?php echo $label_language_values['completed']; ?></a><?php
        }
        else
        {
            if ($dd['booking_status'] == 'A' || $dd['booking_status'] == 'C' || $dd['booking_status'] == 'RS')
            {
                $booking_start_datetime = strtotime(date('Y-m-d H:i:s', strtotime($dd['booking_date_time'])));
                $reschedule_buffer_time = $setting->get_option('ct_reshedule_buffer_time');
                $cancellation_buffer_time = $setting->get_option('ct_cancellation_buffer_time');
                $t_zone_value = $setting->get_option('ct_timezone');
                $server_timezone = date_default_timezone_get();
                if (isset($t_zone_value) && $t_zone_value != '')
                {
                    $offset = $first_step->get_timezone_offset($server_timezone, $t_zone_value);
                    $timezonediff = $offset / 3600;
                }
                else
                {
                    $timezonediff = 0;
                }
                if (is_numeric(strpos($timezonediff, '-')))
                {
                    $timediffmis = str_replace('-', '', $timezonediff) * 60;
                    $currDateTime_withTZ = strtotime("-" . $timediffmis . " minutes", strtotime(date('Y-m-d H:i:s')));
                }
                else
                {
                    $timediffmis = str_replace('+', '', $timezonediff) * 60;
                    $currDateTime_withTZ = strtotime("+" . $timediffmis . " minutes", strtotime(date('Y-m-d H:i:s')));
                }
                $current_times = date('Y-m-d H:i:s', $currDateTime_withTZ);
                $current_time = strtotime($current_times);
                $remain_times = $booking_start_datetime - $current_time;
                $time_in_min = round($remain_times / 60);
                if ($time_in_min > $reschedule_buffer_time)
                { ?>
                <a id="ct-reschedual-appointment" class="btn btn-info ct-small-btn rescedual_book ct-reschedual-appointment-cal" data-id="<?php echo $dd['order_id'];?>" data-dismiss="modal" title="Rescheduled"><i class="fa fa-repeat"></i><?php echo $label_language_values['reschedule']; ?></a>																
                <?php
                }
                else
                {
                    if ($booking_start_datetime > $current_time)
                    { ?>																	
                    	<a href="javascript:void(0)" class="btn btn-success"><i class="fa fa-repeat"></i><?php echo $label_language_values['cannot_reschedule_now']; ?></a>																<?php
                    }
                    else
                    {
                        echo '';
                    }
                } ?>															
                <?php if ($time_in_min > $cancellation_buffer_time)
                { ?>																
                	<a id="ct-user-cancel-appointment<?php echo $dd['order_id'] ?>" data-id="<?php echo $dd['order_id']; ?>" class="btn btn-danger cancel_appointment"  rel="popover" data-placement='left' title="<?php echo $label_language_values['booking_cancel_reason']; ?>?"><i class="fa fa-ban" style="margin-right: 5px;"></i><?php echo $label_language_values['cancel']; ?></a>		
                	<?php
                }
                else
                {
                    if ($booking_start_datetime > $current_time)
                    { ?>																	
                    	<a class="btn btn-danger" href="javascript:void(0)"><i class="fa fa-ban" style="margin-right: 5px;"></i><?php echo $label_language_values['cannot_cancel_now']; ?></a>											
                    	<?php
                    }
                    else
                    {
                        echo '';
                    }
                } ?>															
                <div id="popover-user-cancel-appointment<?php echo $dd['order_id'] ?>" style="display: none;">																<div class="arrow"></div>																<table class="form-horizontal" cellspacing="0">																	<tbody>																	<tr>																		<td>																			<textarea class="form-control" id="reason_cancel<?php echo $dd['order_id'] ?>" name="" placeholder="<?php echo $label_language_values['booking_cancel_reason']; ?>" required="required" >
                </textarea>
            </td>																	
                </tr>																	
                <tr>																		
                	<td><a data-id="<?php echo $dd['order_id'] ?>" data-gc_event="<?php echo $dd['gc_event_id']; ?>" data-gc_staff_event="<?php echo $dd['gc_staff_event_id']; ?>" data-pid="<?php echo $dd['staff_ids']; ?>" value="Delete" class="btn btn-danger btn-sm mybtncancel_booking_user_details"><?php echo $label_language_values['yes']; ?></a>																			
                		<a id="ct-close-user-cancel-appointment" class="btn btn-default btn-sm" href="javascript:void(0)"><?php echo $label_language_values['cancel']; ?></a>																	
                		</td>																	
                </tr>			

                </tbody>																
            </table>															
            </div>
            <!-- end pop up -->														
            <?php
            }
            else
            {
                echo '';
            }
        } ?>	
        </td>*/?>										
        </tr>											
        </tbody>										
        </table>									
        </div>								
        </div>							
        </div>						
        </div>                    
        </div>                
    <?php } } ?>                
<!--     <?php if (isset($_SESSION['ct_login_user_id']))
	{
    $details = $objuserdetails->get_user_details();
    while ($dd = mysqli_fetch_array($details))
    { 

    	?>                        
    	<div id="update-user-booking-details<?php echo $dd['order_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">                            
    		<div class="vertical-alignment-helper">                                
    			<div class="modal-dialog modal-md vertical-align-center">                                    
    				<div class="modal-content">                                        
    					<div class="modal-header">                                            
    					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>                                            
    					<h4 class="modal-title"><?php echo $label_language_values['appointment_details']; ?></h4>                                        
    				</div>                                        
    				<div class="modal-body">                                            
    					<div class="tab-content">                                                
    						<div class="tab-pane fade in active">                                                    
    				<table>                                                        
    					<tbody>                                                        
    						<tr>                                                            
    							<td><label for="ct-service-duration"><?php echo $label_language_values['amount']; ?></label></td>                                                            
    							<td>                                                                
    								<div class="cta-col6 ct-w-50 ">                                                                    
    									<div class="form-control booking_total_payment" readonly="readonly">                                                                    
    									</div>                                                                
    								</div>                     
    							</td>                 
    							</tr>                 
    							<tr>                                                            
								<td><label for="ct-service-duration"><?php echo $label_language_values['date_and_time']; ?></label>
								</td>            
								<td>                                                                
								<div class="cta-col6 ct-w-50"><?php $dates = date("Y-m-d", strtotime($dd['booking_date_time']));
						        $slot_timess = date('H:i', strtotime($dd['booking_date_time']));
						        $get_staff_id = $booking->get_staff_ids_from_bookings($dd['order_id']);
						        if ($get_staff_id == "")
						        {
						            $staff_id = 1;
						        }
						        else
						        {
						            $staff_id = $get_staff_id;
						        } ?>                                                        
						        <input class="exp_cp_date form-control" id="expiry_date<?php echo $dd['order_id']; ?>" data-staffid="<?php echo $staff_id; ?>" value=	"<?php echo $dates; ?>" data-date-format="yyyy/mm/dd" data-provide="datepicker" />                                                                                                                
						         </div>                                                       
						         <div class="cta-col6 ct-w-50 float-right mytime_slots_booking">                                                                    
		<?php $t_zone_value = $setting->get_option('ct_timezone');
        $server_timezone = date_default_timezone_get();
        if (isset($t_zone_value) && $t_zone_value != '')
        {
            $offset = $first_step->get_timezone_offset($server_timezone, $t_zone_value);
            $timezonediff = $offset / 3600;
        }
        else
        {
            $timezonediff = 0;
        }
        if (is_numeric(strpos($timezonediff, '-')))
        {
            $timediffmis = str_replace('-', '', $timezonediff) * 60;
            $currDateTime_withTZ = strtotime("-" . $timediffmis . " minutes", strtotime(date('Y-m-d H:i:s')));
        }
        else
        {
            $timediffmis = str_replace('+', '', $timezonediff) * 60;
            $currDateTime_withTZ = strtotime("+" . $timediffmis . " minutes", strtotime(date('Y-m-d H:i:s')));
        }
        $select_time = date('Y-m-d', strtotime($dates));
        $start_date = date($select_time, $currDateTime_withTZ);
        $time_interval = $setting->get_option('ct_time_interval');
        $time_slots_schedule_type = $setting->get_option('ct_time_slots_schedule_type');
        $advance_bookingtime = $setting->get_option('ct_min_advance_booking_time');
        $ct_service_padding_time_before = $setting->get_option('ct_service_padding_time_before');
        $ct_service_padding_time_after = $setting->get_option('ct_service_padding_time_after');
        $booking_padding_time = $setting->get_option('ct_booking_padding_time');
        $time_schedule = $first_step->get_day_time_slot_by_provider_id($time_slots_schedule_type, $start_date, $time_interval, $advance_bookingtime, $ct_service_padding_time_before, $ct_service_padding_time_after, $timezonediff, $booking_padding_time, $staff_id);
        $allbreak_counter = 0;
        $allofftime_counter = 0;
        $slot_counter = 0; ?>                                                                    
		<select class="selectpicker mydatepicker_appointment   form-control" id="myuser_reschedule_time" data-size="10" style="" >                                                                        
		<?php if ($time_schedule['off_day'] != true && isset($time_schedule['slots']) && sizeof((array)$time_schedule['slots']) > 0 && $allbreak_counter != sizeof((array)$time_schedule['slots']) && $allofftime_counter != sizeof((array)$time_schedule['slots']))
        {
            foreach ($time_schedule['slots'] as $slot)
            {
                $ifbreak = 'N'; /* Need to check if the appointment slot come under break time. */
                foreach ($time_schedule['breaks'] as $daybreak)
                {
                    if (strtotime($slot) >= strtotime($daybreak['break_start']) && strtotime($slot) < strtotime($daybreak['break_end']))
                    {
                        $ifbreak = 'Y';
                    }
                } /* if yes its break time then we will not show the time for booking  */
                if ($ifbreak == 'Y')
                {
                    $allbreak_counter++;
                    continue;
                }
                $ifofftime = 'N';
                foreach ($time_schedule['offtimes'] as $offtime)
                {
                    if (strtotime($dates . ' ' . $slot) >= strtotime($offtime['offtime_start']) && strtotime($dates . ' ' . $slot) < strtotime($offtime['offtime_end']))
                    {
                        $ifofftime = 'Y';
                    }
                } /* if yes its offtime time then we will not show the time for booking  */
                if ($ifofftime == 'Y')
                {
                    $allofftime_counter++;
                    continue;
                }
                $complete_time_slot = mktime(date('H', strtotime($slot)) , date('i', strtotime($slot)) , date('s', strtotime($slot)) , date('n', strtotime($time_schedule['date'])) , date('j', strtotime($time_schedule['date'])) , date('Y', strtotime($time_schedule['date'])));
                if ($setting->get_option('ct_hide_faded_already_booked_time_slots') == 'on' && in_array($complete_time_slot, $time_schedule['booked']))
                {
                    continue;
                }
                if (in_array($complete_time_slot, $time_schedule['booked']) && ($setting->get_option('ct_allow_multiple_booking_for_same_timeslot_status') != 'Y'))
                { ?>                                                                                    <?php if ($setting->get_option('ct_hide_faded_already_booked_time_slots') == "on")
                    { ?>                                                                            
                    	<option value="<?php echo date("H:i", strtotime($slot)); ?>" <?php if (date("H:i", strtotime($slot)) == $slot_timess)
                        {
                            echo "selected";
                        } ?> class="time-slot br-2 ct-booked" >                                                                                            
                        <?php if ($setting->get_option('ct_time_format') == 24)
                        {
                            echo date("H:i", strtotime($slot));
                        }
                        else
                        {
                            echo str_replace($english_date_array, $selected_lang_label, date("h:i A", strtotime($slot)));
                        } ?>                                                                                       
                         </option> <?php } ?> 
              <?php
                }
                else
                {
                    if ($setting->get_option('ct_time_format') == 24)
                    {
                        $slot_time = date("H:i", strtotime($slot));
                    }
                    else
                    {
                        $slot_time = str_replace($english_date_array, $selected_lang_label, date("h:i A", strtotime($slot)));
                    } ?>                                                                                    <option value="<?php echo date("H:i", strtotime($slot)); ?>" <?php if (date("H:i", strtotime($slot)) == $slot_timess)
                    {
                        echo "selected";
                    } ?> class="time-slot br-2 <?php if (in_array($complete_time_slot, $time_schedule['booked']))
                    {
                        echo ' ct-booked';
                    }
                    else
                    {
                        echo ' time_slotss';
                    } ?>" <?php if (in_array($complete_time_slot, $time_schedule['booked']))
                    {
                        echo '';
                    }
                    else
                    {
                        echo 'data-slot_date_to_display="' . date($date_format, strtotime($dates)) . '" data-slot_date="' . $dates . '" data-slot_time="' . $slot_time . '"';
                    } ?>><?php if ($setting->get_option('ct_time_format') == 24)
                    {
                        echo date("H:i", strtotime($slot));
                    }
                    else
                    {
                        echo str_replace($english_date_array, $selected_lang_label, date("h:i A", strtotime($slot)));
                    } ?></option>                                                                                <?php
                }
                $slot_counter++;
            }
            if ($allbreak_counter == sizeof((array)$time_schedule['slots']) && sizeof((array)$time_schedule['slots']) != 0)
            { ?>                                                                                
            	<option  class="time-slot"><?php echo "Sorry Not Available "; ?></option>                                                                            
            	<?php
            }
        }
        else
        { ?>                                                                            
        	<option class="time-slot"><?php echo "Sorry Not Available"; ?></option>                                                                        
        	<?php
        } ?>                                                                    
        </select>                                                                
        </div>                                                            
        </td>                                                       
        </tr>                                                        
        <?php $userinfo = $objuserdetails->get_user_notes($dd['order_id']);
        $temppp = unserialize(base64_decode($userinfo[0]));
        $tem = str_replace('\\', '', $temppp);
        $finalnotes = $tem['notes']; ?>                                                        
        <tr>                                                            
        	<td><?php echo $label_language_values['notes']; ?></td>                                                            
        	<td><textarea class="form-control my_user_notes_reschedule<?php echo $dd['order_id']; ?>"><?php echo $finalnotes; ?></textarea></td>                                                        
        	</tr>                                                        
        	</tbody>                                                    
        	</table>                                                
        	</div>                                            
        	</div>                                        
        	</div>                                        
        	<div class="modal-footer">                                            
        		<div class="cta-col12 ct-footer-popup-btn" style="width: 0%;padding: 5px;">                                                
        			<div class="cta-col6">													<button type="button" data-order="<?php echo $dd['order_id']; ?>" class="btn btn-info my_user_btn_for_reschedule" data-gc_event="<?php echo $dd['gc_event_id']; ?>" data-gc_staff_event="<?php echo $dd['gc_staff_event_id']; ?>" data-pid="<?php echo $dd['staff_ids']; ?>"><?php echo $label_language_values['update_appointment']; ?></button>                                                
        				</div>                                            
        			</div>                                        
        		</div>                                    
        	</div>                                
        </div>                            
      </div>                        
   </div> <?php } } ?>  -->       
   </div>        
 </form>    
 </div>
</div>

<?php if ($gc_hook->gc_purchase_status() == 'exist')
{
    if ($setting->get_option('ct_gc_status_configure') == 'Y' && $setting->get_option('ct_gc_status') == 'Y')
    { ?>
    	<input type="hidden" id="extension_js" value="true" />	
	<?php } else  { ?>		
		<input type="hidden" id="extension_js" value="false" />        
    <?php } }
include (dirname(__FILE__) . '/footer.php'); ?>
