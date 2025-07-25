<?php  
include(dirname(__FILE__).'/header.php');
include(dirname(__FILE__).'/user_session_check.php');
include(dirname(dirname(__FILE__)) . "/objects/class_adminprofile.php");
$con = new cleanto_db();$conn = $con->connect();
$objadminprofile = new cleanto_adminprofile();
$objadminprofile->conn = $conn;?>    
<script type="text/javascript">        
	var ajax_url = '<?php echo AJAX_URL;?>';        
	var base_url = '<?php echo BASE_URL;?>';        
	var profile_site_url = {'prof_site_url':'<?php echo SITE_URL;?>'};    
</script>    
<script>        
 jQuery(document).on('change','input[name="second-widget-loading"]', function(){            
 	if(jQuery(this).val()=='on_btn_click'){                
 		jQuery('#button-click-content').show( "slide", {direction: "up" }, 1000 );            
 	}else{                
 		jQuery('#button-click-content').hide( "slide", {direction: "up" }, 500  );            
 	}   
 });    

  /* admin profile */        
jQuery(document).ready(function () {            
	jQuery("#btn-change-pass").click(function () {                
	jQuery(".ct-change-password").show( "blind", {direction: "vertical"}, 1000 );                
	jQuery("#btn-change-pass").hide();            
});        
});   

/* phone number */        
jQuery(document).bind('ready ajaxComplete',function() {            
	jQuery(".phone_number").intlTelInput({                
	autoPlaceholder: false,                
	utilsScript: "../assets/js/utils.js"            
	});        
});    

</script><?php $userembeddcode ='<div id="cleanto-booking" class="direct-load"></div><script src="'.SITE_URL.'" id="1" type="text/javascript" ></script>';?>    
<div id="cta-profile" class="panel tab-content">        
	<div class="ct-admin-staff ct-left-menu col-md-3 col-sm-3 col-xs-12 col-lg-3">            
		<ul class="nav nav-tab nav-stacked">                
			<li class="active" style="display:none;"><a data-toggle="tab" href="#personal-info-tab"><i class="fa fa-user fa-2x"></i><br /><?php echo $label_language_values['personal_information'];?></a>
			</li>            
		</ul>        
	</div>        
	<div class="panel-body">            
		<div class="ct-admin-profile-details tab-content col-md-9 col-md-offset-3 col-sm-9 col-lg-9 col-xs-12 ">                
			<!-- right side common menu for service -->                
			<div id="personal-info-tab" class="col-lg-10 col-md-10 col-sm-10 col-xs-12 tab-pane fade active in">                    
				<?php                     
				$objadminprofile->id = $_SESSION['ct_adminid'];                    
				$admininfo = $objadminprofile->readone();					
				?>                    
				<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 main-info-usr">      
					<h4 class="header4 text-center"><?php echo $label_language_values['personal_information'];?></h4>                        
					<form novalidate="novalidate" id="admin_info_form">  
						<div class="form-group">                                
							<label for="fullname"><?php echo $label_language_values['full_name'];?></label>                                
							<input class="form-control" name="fullnamess" id="adminfullname" value="<?php  echo $admininfo[3];?>" type="text">                           
						 </div>                           
						 <div class="form-group">                               
						  <label for="inputEmail"><?php echo $label_language_values['email'];?></label>                                
						  <input class="form-control admin_inputEmail" name="fullemail" id="inputEmail" value="<?php  echo $admininfo[2];?>" type="text">                            
						 </div>                            
						 <div class="form-group">                                
						 	<input class="form-control admin_inputEmail_old" name="fullemailold" id="inputEmailold" value="<?php  echo $admininfo[2];?>" type="hidden">                            
						 </div>                            
						 <div class="form-group">                                
						 	<label for="admin-phone-number"><?php echo $label_language_values['phone'];?></label>                                
						 	<input type="tel"  class="form-control" name="adminphoness" id="adminphone" value="<?php  echo $admininfo[4];?>" />                            
						 </div>                            
						 <div class="form-group">                                
						 	<label for="admin-address"><?php echo $label_language_values['admin_profile_address'];?></label>                                <textarea class="form-control" id="adminaddress" name="adminaddressss" cols="6"><?php  echo $admininfo[5];?>
						 	</textarea>                            
						 </div>                            
						 <div class="form-group fl w100">                                
						 	<div class="cta-col6 ct-w-50 mb-6">  
						 	<label for="city"><?php echo $label_language_values['city'];?></label>                                    
						 	<input class="form-control value_city" id="admincity" name="cityss" placeholder="<?php echo $label_language_values['city'];?>" value="<?php  echo $admininfo[6];?>" type="text">                                
						 </div>                                
						 <div class="cta-col6 ct-w-50 mb-6 float-right">                                    
						 	<label for="state"><?php echo $label_language_values['state'];?></label>                                    
						 	<input class="form-control value_state" id="adminstate" name="state" placeholder="<?php echo $label_language_values['state'];?>" value="<?php  echo $admininfo[7];?>" type="text">                                
						 </div>                            
						</div>                            
						<div class="form-group fl w100">                                
							<div class="cta-col6 ct-w-50 mb-6">  
							<label for="zip"><?php echo $label_language_values['zip'];?></label>                                    
							<input class="form-control value_zip" id="adminzip" name="zipss" placeholder="<?php echo $label_language_values['zip'];?>" value="<?php  echo $admininfo[8];?>" type="text">                                
						</div>                                
						<div class="cta-col6 ct-w-50 mb-6 float-right">                                    
							<label for="country"><?php echo $label_language_values['country'];?></label>                                    
							<input class="form-control value_country" id="admincountry" name="countryss" placeholder="<?php echo $label_language_values['country'];?>" value="<?php  echo $admininfo[9];?>" type="text">                                
						</div>                            
					</div>                            
					<div class="form-group">                                
						<a href="javascript:void(0)" id="btn-change-pass" class="btn btn-link"><?php echo $label_language_values['change_password'];?></a>                            
					</div>                            
					<div class="ct-change-password hide-div">                                
						<div class="form-group cb">                                    
							<label for="oldpass"><?php echo $label_language_values['old_password'];?></label>                                    
							<input name="dboldpass" value="<?php echo $admininfo[1];?>" class="form-control" id="dboldpass" type="hidden">                                    
							<input name="oldpass" class="form-control u_op" id="oldpass" type="password" value="<?php echo $admininfo[1];?>">                                    
							<label id="msg_oldps" class="old_pass_msg" style="display: none;"></label>                                
						</div>                                
						<div class="form-group">                                    
							<label for="newpass"><?php echo $label_language_values['new_password'];?></label>                                    
							<input name="newpasswrd" class="form-control" id="newpass" type="password">                                
						</div>                                
						<div class="form-group">                                    
							<label for="retypenewpass"><?php echo $label_language_values['retype_new_password'];?></label>                                    
							<input name="renewpasswrd" class="form-control u_rp" id="retypenewpass" type="password">                                    
							<label id="msg_retype" class="retype_pass_msg"></label>                                
						</div>                            
					</div>                            
					<div class="form-group cb prof-suc-btn">                                
						<a href="javascript:void(0)" data-id="<?php echo $_SESSION['ct_adminid'];?>" id="" class="btn btn-success prf-btn ct-btn-width mybtnadminprofile_save"><?php echo $label_language_values['save'];?></a>                            
					</div>                        
				</form>                    
			</div>                
		</div> 
		<!-- end personal infomation -->            
	</div>        
</div>    
</div><?php include(dirname(__FILE__).'/footer.php');?>