<?php
include(dirname(__FILE__).'/header.php');
include(dirname(__FILE__).'/user_session_check.php');
$setting = new cleanto_setting();
$setting->conn = $conn;
$gettimeformat=$setting->get_option('ct_time_format');
/*CHECK FOR VC AND PARKING STATUS*/
$global_vc_status = $setting->get_option('ct_vc_status');
$global_p_status = $setting->get_option('ct_p_status');
/*CHECK FOR VC AND PARKING STATUS END*/
?>
<div id="ct-calendar-all">
	<div class="ct-legends-panel-body">
		<div class="ct-legends-main">
			<div class="ct-legends-inner">
				<ul class="list-inline nm">
					<li><h4><?php  echo $label_language_values['legends'];?>:</h4></li>			<?php
					if($gc_hook->gc_purchase_status() == 'exist')
						{?>
							<li><i class="fa fa-google txt-primary"></i>Google Event</li>
							<?php }	?>
							<li><i class="fa fa-thumbs-o-up txt-completed"></i>
								<?php echo $label_language_values['completed'];?>
							</li>
							<li><i class="fa fa-check txt-success"></i>
								<?php echo $label_language_values['confirmed'];?>
							</li>
							<li><i class="fa fa-pencil-square-o txt-info"></i>
								<?php echo $label_language_values['rescheduled'];?>
							</li>
							<li><i class="fa fa-ban txt-danger"></i>
								<?php echo $label_language_values['rejected'];?>
							</li>
							<li><i class="fa fa-times txt-primary"></i>
								<?php echo $label_language_values['cancelled_by_client'];?>
							</li>
							<li><i class="fa fa-info-circle txt-warning"></i>
								<?php echo $label_language_values['pending'];?>
						    </li>
						</ul>
					</div>
				</div>
			</div>
			<div id="calendar" class="ct-booking-calendar"></div>

			<!--    DONT DELETE THIS THIS IS FOR USE-->
			<div id="booking-details-calendar" class="modal fade booking-details-calendar" tabindex="-1" role="dialog" aria-hidden="true">
			<!-- modal pop up start -->
			<div class="vertical-alignment-helper">
				<div class="modal-dialog modal-md vertical-align-center">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" id="info_modal_close" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title"><?php  echo $label_language_values['booking_details'];?>
							</h4>
						</div>
						<div class="modal-body">
							<ul class="list-unstyled ct-cal-booking-details bkng-detl">
							    <li style="width: 100%;">
    								<label style="width: 30%; margin-right: 0;">
    									<?php  echo $label_language_values['booking_status'];?>
    								</label>
								    <div class="ct-booking-status"></div>
							    </li>
							    <li class="booking_date_set">
									<label><?php  echo $label_language_values['booking_date'];?>
							    	</label>
							    	<i class="fa fa-calendar pull-left mt-2"></i>
							    	<span class="starttime pull-left"></span> &nbsp;<i class="fa fa-clock-o ml-10 mt-2 pull-left"></i><span class="start_time"></span>
							    </li>
							    <?php/*<li>
							    	<label><?php  echo $label_language_values['service'];?>
							    	</label>
							    	<span class="service-html span-scroll"></span>
							    </li>
							    <li>
							    	<label><?php  echo $label_language_values['methods'];?>
							    	</label>
							    	<span class="method-html span-scroll"></span>
							    </li>
							    <li>
							    	<label><?php  echo $label_language_values['units'];?></label>
							    	<span class="units-html span-scroll"></span>
							    </li>*/?>
							    <li>
							    	<label><?/*php  echo $label_language_values['addons'];*/?>Esame</label>
							    	<span class="addons-html span-scroll"></span>
							    </li>
							    <li>
							    	<label><?php  echo $label_language_values['price'];?></label>
							    	<span class="price span-scroll"></span>
							    </li>
							    <li class="li_of_duration <?php   if ($setting->get_option('ct_show_time_duration') == 'N') {echo "force_hidden";} ?>">							<label><?php  echo $label_language_values['duration'];?></label>
							    	<span class="duration span-scroll"></span>
							    </li>
							    <li><h6 class="ct-customer-details-hr">
							    	<?/*php  echo $label_language_values['customer'];*/?>Cliente</h6>
							    </li>
							    <li>
							    	<label><?php  echo $label_language_values['name'];?></label>
							    	<span class="client_name span-scroll"></span>
							    </li>
							    <li>
							    	<label><?/*php  echo $label_language_values['email']." ".$label_language_values['address'];*/?>Email</label>
							    	<span class="client_email span-scroll"></span>
							    </li>
							    <li>
							    	<label><?php  echo $label_language_values['phone'];?></label>
							    	<span class="client_phone span-scroll"></span>
							    </li>						<li>
								<label><?php  echo $label_language_values['company_address'];?>
								</label><!--<a href="javascript:void(0)" id="address_on_map1" class="address_on_map1" data-toggle="modal1" lat="" lng="" target="_blank">--><span class="client_address span-scroll"></span><!--</a>-->

							    </li>
							    <li>
							    	<label><?php  echo $label_language_values['payment'];?>
							    	</label>
							    	<span class="client_payment span-scroll"></span>
							    </li>
							    <?php    if($global_vc_status == 'Y'){	?>
							    	<li class="pop_vc_status">
							    		<label><?php  echo $label_language_values['vaccum_cleaner'];?></label>
							    		<span class="client_vc_status span-scroll"></span>
							    	</li>
							    <?php    }	?>
							    <?php    if($global_p_status == 'Y'){?>
							    <li class="pop_p_status">
							    	<label><?php  echo $label_language_values['parking'];?>
							    	</label>
							    	<span class="client_parking span-scroll"></span>
							    </li>
							    <?php    } ?>
							    <li class="li_of_notes">
							    	<label><?php  echo $label_language_values['notes'];?></label><span class="notes span-scroll"></span>
							    </li>
							    <li class="li_of_reason">
							    	<label><?php echo $label_language_values['reason'];?></label>
							    	<span class="reason span-scroll"></span>
							    </li>
							    <?php  if($setting->get_option("ct_company_willwe_getin_status") == "Y") { ?>
							    <li>
							    	<label><?php  echo $label_language_values['contact_status'];?></label>
							    	<span class="contact_status span-scroll"></span>
							    </li>
							    <?php    } ?>
							    <?php/*<hr>
							    <li>
							    	<label class="assign-app-staff"><?php  echo $label_language_values['assign_appointment_to_staff'];?></label>
							    	<span class="staff_list span-scroll-staff"></span>
							    </li>*/?>
							</ul>
						</div>
						<div class="modal-footer">
							<div class="col-xs-12 np ct-footer-popup-btn text-center">
                <div class="fln-mrat-dib">
								<span class="col-xs-4 np ct-w-32 mycompleteclass">
									<a id="ct-complete-appointment" class="btn btn-link ct-small-btn confirm_book ct-complete-appointment-cal" data-id="" title="<?php  echo $label_language_values['complete_appointment'];?>"><i class="fa fa-thumbs-up fa-2x"></i><br /><?php  echo $label_language_values['complete'];?></a>
								</span>
								<span class="col-xs-4 np ct-w-32 myconfirmclass">
                  <a id="ct-confirm-appointment" class="btn btn-link
									ct-small-btn confirm_book ct-confirm-appointment-cal" data-id
									="" title="<?php  echo $label_language_values['confirm_appointment'];?>"><i class="fa fa-check fa-2x"></i><br /><?php  echo $label_language_values['confirm'];?></a>
								</span>
								<span class="col-xs-4 np ct-w-32 myconfirmclass myconfirmclass-edit">
									<a id="ct-reschedual-appointment" class="btn btn-link ct-small-btn rescedual_book ct-reschedual-appointment-cal" data-id="" title="<?php    echo $label_language_values['rescheduled'];?>" ><i class="fa fa-pencil-square-o fa-2x"></i><br /><?/*php    echo $label_language_values['rescheduled'];*/?>Riprogramma</a>
								</span>
		<span class="col-xs-4 np ct-w-32 myrejectclass">
			<a id="ct-reject-appointment-cal-popup" data-id="" class="btn btn-link ct-small-btn book_rejct" data-bkid="" rel="popover" data-placement='top' title="<?php  echo $label_language_values['reject_reason'];?>?"><i class="fa fa-thumbs-o-down fa-2x"></i><br /><?php  echo $label_language_values['reject'];?></a>

			<div id="popover-reject-appointment-cal-popup" class="reject_book" style="display: none;">
				<div class="arrow"></div>
				<table class="form-horizontal" cellspacing="0">
				<tbody>
					<tr>
						<td><textarea class="form-control reject_rea_appt" id="reason_reject" name="" placeholder="<?php  echo $label_language_values['appointment_reject_reason'];?>" required="required" ></textarea>
						</td>
					</tr>
					<tr>
						<td><button id="reject_appt" data-gc_event="" data-pid="" data-gc_staff_event="" value="Delete" class="btn btn-danger btn-sm reject_bookings" data-id="" type="submit"><?php  echo $label_language_values['reject'];?></button>
							<button id="ct-close-reject-appointment-cal-popup" class="btn btn-default btn-sm" href="javascript:void(0)"><?php  echo $label_language_values['cancel'];?></button>
						</td>
					</tr>
				</tbody>
			    </table>

		    </div>
		    <!-- end pop up -->
	    </span>
	    <span class="col-xs-4 np ct-w-32">
	    	<a id="ct-delete-appointment-cal-popup" class="ct-delete-appointment-cal-popup pull-left btn btn-link ct-small-btn book_cancel" data-id="" data-bkid="" rel="popover" data-placement='top' title="<?php  echo $label_language_values['delete_this_appointment'];?>?"><i class="fa fa-trash-o fa-2x"></i><br /> <?php    echo $label_language_values['delete'];?></a>
	    </span>
		  <span class="col-xs-4 np ct-w-32 del_all_recurrence">
	    	<a id="ct-delete-appointment-cal-popup1" class="ct-delete-appointment-cal-popup pull-left btn btn-link ct-small-btn book_cancel" data-id1="" data-bkid="" rel="popover" data-placement='top' title="<?php  echo $label_language_values['delete_all_reccurring'];;?>?"><i class="fa fa-trash-o fa-2x"></i><br /> <span><?php    echo $label_language_values['delete_all_reccurring'];;?></span> </a>
	    </span>
	    <div id="popover-delete-appointment-cal-popup" class="popup_display_cancel" style="display: none;">
	    	<div class="arrow"></div>
	    	<table class="form-horizontal" cellspacing="0">
	    		<tbody>
	    			<tr>
	    				<td>
	    					<button id="delete_appt" value="Delete" data-id="" data-gc_event="" data-pid="" data-gc_staff_event="" class="btn btn-danger btn-sm delete_bookings delete_bookings_dash" type="submit"><?php  echo $label_language_values['delete'];?></button>
	    					<button id="ct-close-del-appointment-cal-popup" class="btn btn-default btn-sm" href="javascript:void(0)"><?php  echo $label_language_values['cancel'];?></button>
	    				</td>
	    			</tr>
	    		</tbody>
	    	</table>
	    </div>
		<div id="popover-delete-appointment-cal-popup1" class="popup_display_cancel" style="display: none;">
	    	<div class="arrow"></div>
	    	<table class="form-horizontal" cellspacing="0">
	    		<tbody>
	    			<tr>
						<td>
	    					<button id="delete_appt1" value="Delete" data-id1="" data-gc_event1="" data-pid1="" data-gc_staff_event1="" class="btn btn-danger btn-sm delete_bookingss delete_bookings_dash" type="submit"><?php  echo $label_language_values['delete'];?></button>
	    					<button id="ct-close-del-appointment-cal-popup1" class="btn btn-default btn-sm" href="javascript:void(0)"><?php  echo $label_language_values['cancel'];?></button>
	    				</td>
	    			</tr>
	    		</tbody>
	    	</table>
	    </div>
	    <!-- end pop up -->

	    </div>
	</div>
   </div>
  </div>
 </div>
 </div>
</div>
<!-- end details of booking -->

<!-- file upload preview -->

<div class="ct-new-customer-image-popup-view">
	<div id="ct-image-upload-popup" class="modal fade" tabindex="-1" role="dialog">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog modal-md vertical-align-center">
				<div class="modal-content">
					<div class="modal-header">
						<div class="col-md-12 col-xs-12">
							<button type="submit" class="btn btn-success"><?php  echo $label_language_values['crop_and_save'];?></button>
							<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?php  echo $label_language_values['cancel'];?>
							</button>
						</div>
					</div>
					<div class="modal-body">
						<img id="ct-preview-img" />
					</div>
					<div class="modal-footer">
						<div class="col-md-12 np">
							<div class="col-md-4 col-xs-12">
								<label class="pull-left"><?php  echo $label_language_values['file_size'];?></label>
								<input type="text" class="form-control" id="filesize" name="filesize" />
							</div>
							<div class="col-md-4 col-xs-12">										<label class="pull-left">H</label>
							    <input type="text" class="form-control" id="h" name="h" />
							</div>
							<div class="col-md-4 col-xs-12">										<label class="pull-left">W</label>
								<input type="text" class="form-control" id="w" name="w" />
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!--reshedual appo-->

<div class="modal fade" id="myModal_reschedual" role="dialog"></div>
<div id="add-new-booking" class="modal fade ct-manual-booking-modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" id="info_modal_close" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
				<h4 class="modal-title"><?php  echo $label_language_values['Add_Manual_booking'];?></h4>
			</div>
			<div class="modal-body">
				<?php   include_once(dirname(dirname(__FILE__)).'/manual_booking.php');	?>
			</div>
			<div class="modal-footer cb">
				<button type="button" class="btn btn-warning" data-dismiss="modal"><?php  echo $label_language_values['cancel'];?></button>
			</div>
		</div>
	</div>
</div>
</div>

<?php  include(dirname(__FILE__).'/footer.php');?>
<script>
	var ajax_url = '<?php  echo AJAX_URL;?>';
	var base_url = '<?php  echo BASE_URL;?>';
	/*var calObj={'ajax_url':'<?php  echo AJAX_URL;?>'};*/
	var times={'time_format_values':'<?php  echo $gettimeformat;?>'};
	var site_ur = {'site_url':'<?php  echo SITE_URL;?>'};
</script>
