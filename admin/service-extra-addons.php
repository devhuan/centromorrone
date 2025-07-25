<?php  

include(dirname(__FILE__).'/header.php');
include(dirname(dirname(__FILE__))  . "/objects/class_services_addon.php");
include(dirname(__FILE__).'/user_session_check.php');
$con = new cleanto_db();
$conn = $con->connect();
$objaddonservice = new cleanto_services_addon();
$objaddonservice->conn = $conn;
$setting = new cleanto_setting();
$setting->conn = $conn;
$getaddon_default_design=$setting->get_option('ct_addons_default_design');
?>
<script>
    function goBack() {
        window.history.back();
    }
</script>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap-toggle.min.css" type="text/css" media="all">
<script src="<?php echo BASE_URL; ?>/assets/js/bootstrap-toggle.min.js" type="text/javascript" ></script>
<div id="cta-clean-services-panel" class="panel tab-content">
    <div class="panel-body">
        <div class="ct-clean-service-details tab-content col-md-12 col-sm-12 col-xs-12">
            <ul class="breadcrumb">
                <li><a style="cursor:pointer"  onclick="goBack()" class="myextraservice_addon"></a></li>
                <li><a href="#" class="mkyextraservice_addontitle"><?/*php echo $label_language_values['price_calculation_method'];*/?>Gestione Esami</a></li>
            </ul>
            <!-- right side common menu for service -->
            <div class="ct-clean-service-top-header">
                <!--<span class="ct-clean-service-service-name pull-left myextraservice_addontitle">Gestione Esami</span>-->
                <span class="">Gestione Esami</span>
                <div class="pull-search" style="display: inline-block;margin-left: 15px;">
                    <form action="/admin/service-extra-addons.php" method="get">
                        <input id="birds-search" type="search" name="service" data-id="<?= (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : '' ?>" value="<?= (isset($_GET['service']) && $_GET['service']) ? $_GET['service'] : '' ?>" placeholder="   Cerca prestazione o esame da effettuare..." style="font-family:Arial, FontAwesome" class="ui-autocomplete-input" autocomplete="off">
                    </form>
                </div>
                <div class="pull-right">
                    <table>
                        <tbody>
                        <tr>
                            <td>
                                <a href="#service-front-view" class="btn btn-info mybtnfrontdesignaddons" data-toggle="modal"><?php echo $label_language_values['front_view_options'];?></a>
                                <!-- Modal HTML -->
                                <div id="service-front-view" class="modal fade">
                                    <div class="modal-dialog modal-sm modal-md ">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title"><?php echo $label_language_values['addon_service_front_view'];?></h4>
                                                <div class="ct-alert-msg-show-main mainheader_message_addon_design">
                                                    <div class="ct-all-alert-messags alert alert-success mainheader_message_inneraddon_design">
                                                        <!-- <a href="#" class="close" data-dismiss="alert">&times;</a> -->
                                                        <strong><?php echo $label_language_values['success'];?></strong> <span id="ct_sucess_message_addon_design"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <div class="ct-custom-radio">
                                                    <ul class="ct-radio-list">
                                                        <?php 
                                                        ?>
                                                        <li class="fln w100">
                                                            <input <?php  if($getaddon_default_design == 1){ echo "checked";}?> type="radio" id="front-service-box-view" class="cta-radio design_radio_btn_addons"   name="front-service-view-radio" value="1" />
                                                            <label for="front-service-box-view"><span></span><?php echo $label_language_values['service_add_ons_front_block_view'];?></label>
                                                            <img src="<?php echo SITE_URL;?>assets/images/democheck/service-addons-design-1.jpg" style="height: 100%;width: 80%;">
                                                        </li>
                                                        <li class="fln w100">
                                                            <input <?php  if($getaddon_default_design == 2){ echo "checked";}?> type="radio" id="front-service-dropdown-view" class="cta-radio design_radio_btn_addons" name="front-service-view-radio" value="2" />
                                                            <label for="front-service-dropdown-view"><span></span><?php echo $label_language_values['service_add_ons_front_increase_decrease_view'];?></label>
                                                            <img src="<?php echo SITE_URL;?>assets/images/democheck/service-addons-design-2.jpg" style="height: 100%;width: 80%;">
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="modal-footer cb">
                                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $label_language_values['close'];?></button>
                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <button id="ct-add-new-service" class="btn btn-success" value="add new service"><i class="fa fa-plus"></i><?php echo $label_language_values['create_addon_service'];?></button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="hr"></div>
            <div class="tab-pane active"><!-- services list -->
                <div class="tab-content ct-clean-services-right-details">
                    <div class="tab-pane active col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div id="accordion" class="panel-group">
                            <ul class="nav nav-tab nav-stacked myservice_addon_loader my-sortable-addons-services" id="sortable-addons-services"> <!-- sortable-services -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- file upload preview -->
        <div id="ct-image-upload-popup" class="ct-image-upload-popup modal fade" tabindex="-1" role="dialog">
            <div class="vertical-alignment-helper">
                <div class="modal-dialog modal-md vertical-align-center">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="col-md-12 col-xs-12">
                                <button type="submit" class="btn btn-success"><?php echo $label_language_values['crop_and_save'];?></button>
                                <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?php echo $label_language_values['cancel'];?></button>
                            </div>
                        </div>
                        <div class="modal-body">
                            <img id="ct-preview-img" />
                        </div>
                        <div class="modal-footer">
                            <div class="col-md-12 np">
                                <div class="col-md-4 col-xs-12">
                                    <label class="pull-left"><?php echo $label_language_values['file_size'];?></label> <input type="text" class="form-control" id="filesize" name="filesize" />
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <label class="pull-left">H</label> <input type="text" class="form-control" id="h" name="h" />
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <label class="pull-left">W</label> <input type="text" class="form-control" id="w" name="w" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
include(dirname(__FILE__).'/footer.php');
?>
<script type="text/javascript">
    var ajax_url = '<?php echo AJAX_URL;?>';
    var ajaxObj = {'ajax_url':'<?php echo AJAX_URL;?>'};
    var servObj={'site_url':'<?php echo SITE_URL.'assets/images/business/';?>'};
    var imgObj={'img_url':'<?php echo SITE_URL.'assets/images/';?>'};
</script>