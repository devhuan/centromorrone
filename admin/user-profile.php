<?php  
include(dirname(__FILE__).'/header.php');
include(dirname(__FILE__).'/admin_session_check.php');
include(dirname(dirname(__FILE__)) . "/objects/class_userdetails.php");
$con = new cleanto_db();
$conn = $con->connect();
$objuserdetails = new cleanto_userdetails();
$objuserdetails->conn = $conn;
?>    

<div id="cta-user-profile">        
	<div class="panel-body">            
		<div class="tab-content">               
		 <form novalidate="novalidate" id="user_info_form">                    
		 	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">                        
		 		<?php                         
		 		/* SET SESSION VALUE HERE IN HARD CODED VALUE OF USERid FROM 1 TO SESSION id */                        
		 		$objuserdetails->id = $_SESSION['ct_login_user_id'];                       
		 	    $userinfo = $objuserdetails->readone();                        
		 	    ?>                    
		 	</div>                    
		 	<div class="col-lg-8 col-md-8 col-xs-12 np">                        
		 		<h4 class="header4"><?php echo $label_language_values['personal_information'];?>
		 		</h4>                        
		 		<div class="form-group col-md-6 col-sm-6 col-xs-12">                            
		 			<label for="firstname"><?php echo $label_language_values['first_name'];?>
		 			</label>                            
		 			<input class="form-control" name="userfirstname" id="userfirstname" value="<?php  echo $userinfo[3];?>" type="text">                        
		 		</div>                        
		 		<div class="form-group col-md-6 col-sm-6 col-xs-12">                            
		 			<label for="lastname"><?php echo $label_language_values['last_name'];?>
		 			</label>                            
		 			<input class="form-control" name="userlastname" id="userlastname" value="<?php  echo $userinfo[4];?>" type="text">                        
		 		</div>                        
		 		<div class="form-group col-md-6 col-sm-6 col-xs-12">                            
		 			<label for="inputEmail"><?php echo $label_language_values['email']." ".$label_language_values['address'];?></label><span class="form-control"><?php  echo $userinfo[1];?></span>
		 		</div>                        
				<div class="form-group col-md-6 col-sm-6 col-xs-12">                            
					<label for="admin-phone-number"><?php echo $label_language_values['phone'];?></label>                            
					<input type="tel" class="form-control phone_number" name="userphone" id="userphone" value="<?php  echo $userinfo[5];?>" onkeyup="if (/\D/g.test(this.value)) this.value  =is.value.replace(/\D/g,'')" />                        
				</div>
				<div class="form-group col-md-6 col-sm-6 col-xs-12">                            
					<label for="admin-address"><?php echo $label_language_values['address'];?></label>             
					<input class="form-control" id="useraddress" name="useraddress" value="<?php  echo $userinfo[7];?>" />                        
				</div>                        
				<div class="form-group col-md-6 col-sm-6 col-xs-12">                            
					<label for="city"><?php echo $label_language_values['city'];?></label>       
					<input class="form-control value_city" id="usercity" name="usercity" placeholder="<?php echo $label_language_values['city'];?>" value="<?php  echo $userinfo[8];?>" type="text">                        
				</div>                        
				<div class="form-group col-md-6 col-sm-6 col-xs-12">                            
					<label for="state"><?php echo $label_language_values['state'];?></label>     
					<input class="form-control value_state" id="userstate" name="userstate" placeholder="<?php echo $label_language_values['state'];?>" value="<?php  echo $userinfo[9];?>" type="text">                        
				</div>						
				<?php  if($setting->get_option('ct_user_zip_code') == 'Y')
				{?>                        
					<div class="form-group col-md-6 col-sm-6 col-xs-12">                       
						<label for="zip"><?php echo $label_language_values['zip'];?></label>     
						<input class="form-control value_zip" id="userzip" name="userzip" placeholder="<?php echo $label_language_values['zip'];?>" value="<?php  echo $userinfo[6];?>" type="text">                        
					</div>						
				<?php  } ?>                        
				<div class="form-group col-md-12 col-sm-12 col-xs-12  mb-0">            
					<a href="javascript:void(0)" id="btn-change-pass" class="btn btn-link pl-0"><?php echo $label_language_values['change_password'];?></a>
				</div>                        
				<div class="ct-change-password hide-div">                            
					<div class="form-group col-md-12 col-sm-12 col-xs-12 mb-0">              
						<label for="useroldpass"><?php echo $label_language_values['old_password'];?></label>                                
						<input name="userdboldpass" value="<?php echo $userinfo[2];?>" class="form-control" id="userdboldpass" type="hidden">                       
						<input name="useroldpass" class="form-control u_op" id="useroldpass" type="password">                  
						<label id="msg_oldps" class="old_pass_msg"></label>                           
					</div>                            
					<div class="form-group col-md-12 col-sm-12 col-xs-12">                      
						<label for="usernewpasswrd"><?php echo $label_language_values['new_password'];?></label>                               
					    <input name="usernewpasswrd" class="form-control" id="usernewpasswrd" type="password">                            
					</div>                           
					<div class="form-group col-md-12 col-sm-12 col-xs-12 mb-0">
						<label for="userrenewpasswrd"><?php echo $label_language_values['retype_new_password'];?></label>                                
						<input name="userrenewpasswrd" class="form-control u_rp" id="userrenewpasswrd" type="password">                                
						<label id="msg_retype" class="retype_pass_msg"></label>                            
					</div>                        
				</div>                        
				<div class="form-group cb col-md-12 col-sm-12 col-xs-12 mb-0 mt-10">                            
					<!--   SET SESSION ID IN ID--> 

				<a href="javascript:void(0)" data-zip="<?php echo $setting->get_option('ct_user_zip_code');?>" data-id="<?php echo $_SESSION['ct_login_user_id']; ?>" class="btn btn-success ct-btn-width mybtnuserprofile_save"><?php echo $label_language_values['save'];?></a>                        
			</div>                    
		</div>                    
	  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12"></div>                
	</form>           
   </div>        
  </div>   
</div>
<?php include(dirname(__FILE__).'/footer.php');?>


