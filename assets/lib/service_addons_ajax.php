<?php include (dirname(dirname(dirname(__FILE__))) . "/objects/class_connection.php");
include (dirname(dirname(dirname(__FILE__))) . "/objects/class_services_addon.php");
include (dirname(dirname(dirname(__FILE__))) . "/objects/class_services.php");
include (dirname(dirname(dirname(__FILE__))) . "/objects/class_services_addon_rates.php");
include (dirname(dirname(dirname(__FILE__))) . '/objects/class_general.php');
include (dirname(dirname(dirname(__FILE__))) . '/objects/class_setting.php');
include (dirname(dirname(dirname(__FILE__))) . "/header.php");
$con = new cleanto_db();
$conn = $con->connect();
$objservice_addon = new cleanto_services_addon();
$objservice_addon_rate = new cleanto_services_addon_rates();
$objservice_addon->conn = $conn;
$objservice_addon_rate->conn = $conn;
$objservice = new cleanto_services();
$objservice->conn = $conn;
$general = new cleanto_general();
$general->conn = $conn;
$settings = new cleanto_setting();
$settings->conn = $conn;
$symbol_position = $settings->get_option('ct_currency_symbol_position');
$decimal = $settings->get_option('ct_price_format_decimal_places');
$lang = $settings->get_option("ct_language");
$label_language_values = array();
$language_label_arr = $settings->get_all_labelsbyid($lang);
if ($language_label_arr[1] != "" || $language_label_arr[3] != "" || $language_label_arr[4] != "" || $language_label_arr[5] != "")
{
    $default_language_arr = $settings->get_all_labelsbyid("en");
    if ($language_label_arr[1] != '')
    {
        $label_decode_front = base64_decode($language_label_arr[1]);
    }
    else
    {
        $label_decode_front = base64_decode($default_language_arr[1]);
    }
    if ($language_label_arr[3] != '')
    {
        $label_decode_admin = base64_decode($language_label_arr[3]);
    }
    else
    {
        $label_decode_admin = base64_decode($default_language_arr[3]);
    }
    if ($language_label_arr[4] != '')
    {
        $label_decode_error = base64_decode($language_label_arr[4]);
    }
    else
    {
        $label_decode_error = base64_decode($default_language_arr[4]);
    }
    if ($language_label_arr[5] != '')
    {
        $label_decode_extra = base64_decode($language_label_arr[5]);
    }
    else
    {
        $label_decode_extra = base64_decode($default_language_arr[5]);
    }
    $label_decode_front_unserial = unserialize($label_decode_front);
    $label_decode_admin_unserial = unserialize($label_decode_admin);
    $label_decode_error_unserial = unserialize($label_decode_error);
    $label_decode_extra_unserial = unserialize($label_decode_extra);
    $label_language_arr = array_merge($label_decode_front_unserial, $label_decode_admin_unserial, $label_decode_error_unserial, $label_decode_extra_unserial);
    foreach ($label_language_arr as $key => $value)
    {
        $label_language_values[$key] = urldecode($value);
    }
}
else
{
    $default_language_arr = $settings->get_all_labelsbyid("en");
    $label_decode_front = base64_decode($default_language_arr[1]);
    $label_decode_admin = base64_decode($default_language_arr[3]);
    $label_decode_error = base64_decode($default_language_arr[4]);
    $label_decode_extra = base64_decode($default_language_arr[5]);
    $label_decode_front_unserial = unserialize($label_decode_front);
    $label_decode_admin_unserial = unserialize($label_decode_admin);
    $label_decode_error_unserial = unserialize($label_decode_error);
    $label_decode_extra_unserial = unserialize($label_decode_extra);
    $label_language_arr = array_merge($label_decode_front_unserial, $label_decode_admin_unserial, $label_decode_error_unserial, $label_decode_extra_unserial);
    foreach ($label_language_arr as $key => $value)
    {
        $label_language_values[$key] = urldecode($value);
    }
}
if (isset($_POST['getservice_addons']))
{
    $objservice_addon->service_id = $_POST['service_id'];
    $res = '';
    if (isset($_GET['service']) && $_GET['service'] && isset($_GET['id']) && $_GET['id']) {
      $res = $objservice_addon->getdataby_id($_GET['id']);
    } else if (isset($_GET['service'])) {
      $service = strtolower($_GET['service']);
       if ($service == 'all' || $service == 'tutti') {
         $res = $objservice_addon->getdataby_serviceid();
       }
    }
    $i = 1; /* ?>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap-toggle.min.css" type="text/css" media="all">
    <script src="<?php echo BASE_URL; ?>/assets/js/bootstrap-toggle.min.js" type="text/javascript" ></script>        <?php  */
    while ($arrs = mysqli_fetch_array($res))
    {
        $i++; ?>
<li class="panel panel-default ct-clean-services-panel cta-addon-icons mysortlistaddons" id="addons_service_<?php echo $arrs['id']; ?>" data-id="<?php echo $arrs['id']; ?>" data-position="<?php echo $arrs['position']; ?>">
   <div class="panel-heading">
      <h4 class="panel-title">
         <div class="cta-col7">
            <div class="pull-left">
              <i class="fa fa-th-list"></i></span>
            </div>
            <span class="ct-clean-service-title-name" id="addons_service_name<?php echo $arrs['id']; ?>" title="<?php echo $label_language_values['extra_service_title']; ?>"><?php echo $arrs['addon_service_name']; ?></span>
         </div>
         <div class="pull-right cta-col5">
            <div class="cta-col2">
              <span class="ct-service-addon-price" title="<?php echo $label_language_values['basic_price']; ?>"><span class="price-currency"></span>
              <?php echo $general->ct_price_format($arrs['base_price'], $symbol_position, $decimal); ?></span>
            </div>
            <div class="cta-col3">
              <label for="sevice-endis-<?php echo $i; ?>">
        <input class='myservices_addons_status' data-toggle="toggle" data-size="small" type='checkbox' data-id="<?php echo $arrs['id']; ?>" <?php if ($arrs['status'] == 'E')
        {
            echo "checked='checked'";
        }
        else
        {
            echo "";
        } ?> name="myservices_addons_status" id="sevice-endis-<?php echo $i; ?>" data-on="<?php echo $label_language_values['enable']; ?>" data-off="<?php echo $label_language_values['disable']; ?>" data-onstyle='success' data-offstyle='danger' />
      </label>
    </div>
            <div class="pull-right">
               <div class="cta-col1">
                  <?php $t = $objservice_addon->addons_isin_use($arrs['id']);
        if ($t > 0)
        { ?>
          <a data-toggle="popover" class="delete-clean-service-btn pull-right btn-circle btn-danger btn-sm" rel="popover" data-placement='top' title="<?php echo $label_language_values['addon_is_booked']; ?>"> <i class="fa fa-ban"></i></a>
           <?php
        }
        else
        { ?>
          <a id="ct-delete-service-addon<?php echo $arrs['id']; ?>" data-toggle="popover"class="delete-clean-service-btn pull-right btn-circle btn-danger btn-sm" rel="popover" data-placement='left' title="<?php echo $label_language_values['delete_this_addon_service']; ?>"> <i class="fa fa-trash" title="<?php echo $label_language_values['delete_service']; ?>"></i></a>
                  <div id="popover-delete-service" style="display: none;">
                     <div class="arrow"></div>
                     <table class="form-horizontal" cellspacing="0">
                        <tbody>
                           <tr>
                              <td>
                                <button data-serviceaddonid="<?php echo $arrs['id']; ?>" value="Delete" class="btn btn-danger btn-sm service-addons-delete-button" data-imagename="<?php echo $arrs['image']; ?>" type="submit"><?php echo $label_language_values['yes']; ?>
                               </button>
                              <button id="ct-close-popover-delete-service-addon" class="btn btn-default btn-sm" href="javascript:void(0)" data-serviceaddonid="<?php echo $arrs['id']; ?>"><?php echo $label_language_values['cancel']; ?>
                            </button>
                          </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
                  <?php
        } ?>
               </div>
               <div class="ct-show-hide pull-right">
                  <input type="checkbox" name="ct-show-hide" class="ct-show-hide-checkbox" id="sp<?php echo $arrs['id']; ?>"><!--Added Serivce Id-->                                <label class="ct-show-hide-label" for="sp<?php echo $arrs['id']; ?>"></label>
               </div>
            </div>
         </div>
      </h4>
   </div>
   <div id="details_sp<?php echo $arrs['id']; ?>" class="serviceaddon_detail panel-collapse collapse">
      <div class="panel-body">
         <div class="ct-service-collapse-div col-sm-12 col-md-6 col-lg-6 col-xs-12">
            <form id="myformedt_addons_<?php echo $arrs['id']; ?>" method="post" type="" class="slide-toggle">
               <table class="ct-create-service-table">
                  <tbody>
                     <tr>
                        <td><label><?php echo $label_language_values['addon_title']; ?></label></td>
                        <td>
                           <div class="col-xs-12 np"><input type="text" class="form-control txtedtaddon_title<?php echo $arrs['id']; ?>" id="myedtaddon_title<?php echo $arrs['id']; ?>" name = "myedtaddon_titlename<?php echo $arrs['id']; ?>" value="<?php echo $arrs['addon_service_name']; ?>"/>                                        </div>
                        </td>
                     </tr>
                     <tr>
                        <td><label> Testo Preparazioni (usare tag html)</label></td>
                        <td>
                           <div class="col-xs-12 np"><textarea id="myedtaddon_title_desc" class="form-control myedtaddon_titlenamedesc<?php echo $arrs['id']; ?>" ><?php echo $arrs['addon_service_description']; ?></textarea>                    </div>
                        </td>
                     </tr>
                     <tr>
                        <td><label> Tags Esami</label></td>
                        <td>
                           <div class="col-xs-12 np"><textarea id="myedtaddon_tags" class="form-control myedtaddon_tags<?php echo $arrs['id']; ?>" ><?php echo $arrs['tags']; ?></textarea></div>
                        </td>
                     </tr>
                     <tr>
                     <td><label><?php echo $label_language_values['duration']; ?></label></td>
                     <?php $duration = $arrs['aduration'];
        $intval = intval($duration / 60);
        $modulas = fmod($duration, 60); ?>
                     <td>
                        <div class="form-inline">
                           <div class="input-group">                                   <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>                                   <input placeholder="00" size="2" maxlength="2" type="text" class="form-control txtedtaddon_hours<?php echo $arrs['id']; ?>" id="addon_edit_hours<?php echo $arrs['id']; ?>" name = "myedtaddon_durationhours<?php echo $arrs['id']; ?>" value="<?php echo $intval; ?>" />                                                                       <span class="input-group-addon"><?php echo $label_language_values['hours']; ?></span>                                </div>
                           <div class="input-group cta_mt_10">                                  <input placeholder="05" size="2" maxlength="2" type="text" class="form-control txtedtaddon_mints<?php echo $arrs['id']; ?>" id="addon_edit_mints<?php echo $arrs['id']; ?>" name = "myedtaddon_durationmints<?php echo $arrs['id']; ?>" value="<?php echo $modulas; ?>"/>                                   <span class="input-group-addon"><?php echo $label_language_values['minutes']; ?></span>                                 </div>
                        </div>
                     </td>
                     </tr>
                     <tr>
                        <td><label><?php echo $label_language_values['service_image']; ?></label></td>
                        <td>
                           <div class="ct-clean-service-addons-image-uploader">
                              <?php if ($arrs['image'] == '')
        {
            $imagepath = "../assets/images/default_service.png";
        }
        else
        {
            $imagepath = "../assets/images/services/" . $arrs['image'];
        } ?>                                            <img data-imagename="" id="pcaol<?php echo $arrs['id']; ?>addonimage" src="<?php echo $imagepath; ?>" class="ct-clean-service-addons-image br-100" height="100" width="100">                                 <?php if ($arrs['image'] == '')
        { ?>                                 <label for="ct-upload-imagepcaol<?php echo $arrs['id']; ?>" class="ct-clean-service-addons-img-icon-label ser_addons<?php echo $arrs['id']; ?>">                                                <i class="ct-camera-icon-common br-100 fa fa-camera"></i>                                                <i class="pull-left fa fa-plus-circle fa-2x"></i>                                            </label>                               <?php
        } ?>                                            <input data-us="pcaol<?php echo $arrs['id']; ?>" data-id="<?php echo $arrs['id']; ?>" class="hide ct-upload-images" type="file" name="" id="ct-upload-imagepcaol<?php echo $arrs['id']; ?>" />                                 <label for="ct-upload-imagepcaol<?php echo $arrs['id']; ?>" class="ct-clean-service-addons-img-icon-label addon_ser_cam cam_btn_addon<?php echo $arrs['id']; ?>">                                                <i class="ct-camera-icon-common br-100 fa fa-camera"></i>                                                <i class="pull-left fa fa-plus-circle fa-2x"></i>                                            </label>                                <?php if ($arrs['image'] !== '')
        { ?>
          <a data-pcaolid="<?php echo $arrs['id']; ?>" id="ct-remove-service-addons-imagepcaol<?php echo $arrs['id']; ?>" class="pull-left br-100 btn-danger bt-remove-service-addons-img btn-xs addons_del_btn addons_del_icon<?php echo $arrs['id']; ?>" rel="popover" data-placement='left' title="<?php echo $label_language_values['remove_image']; ?>"> <i class="fa fa-trash" title="<?php echo $label_language_values['remove_service_image']; ?>"></i></a>                                            <?php
        } ?>
        <a data-pcaolid="<?php echo $arrs['id']; ?>" id="ct-remove-service-addons-imagepcaol<?php echo $arrs['id']; ?>" class="pull-left br-100 btn-danger bt-remove-service-addons-img btn-xs new_addons_del del_btn_addon<?php echo $arrs['id']; ?>" rel="popover" data-placement='left' title="<?php echo $label_language_values['remove_image']; ?>"> <i class="fa fa-trash" title="<?php echo $label_language_values['remove_service_image']; ?>"></i></a>
                              <div id="popover-ct-remove-service-addons-imagepcaol<?php echo $arrs['id']; ?>" style="display: none;">
                                 <div class="arrow"></div>
                                 <table class="form-horizontal" cellspacing="0">
                                    <tbody>
                                       <tr>
                                          <td>
                                            <a href="javascript:void(0)" id="" value="Delete" class="btn btn-danger btn-sm delete_image_addons" data-pcaolid="<?php echo $arrs['id']; ?>" type="submit"><?php echo $label_language_values['yes']; ?></a>
                                            <a href="javascript:void(0)" id="ct-close-popover-service-addon-image" class="btn btn-default btn-sm" href="javascript:void(0)" data-pcaolid="<?php echo $arrs['id']; ?>"><?php echo $label_language_values['cancel']; ?></a>                                                        </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <label class="error_image" ></label>                                                            <span class="cta-addon-img-icon"><?php echo $label_language_values['or']; ?></span>
                           <div class="cta-addons-imagelist-dropdown fl">
                              <div class="cta-addons-selection-main">
                                 <div class="cta-addon-is update_id" data-id="<?php echo $arrs['id']; ?>" title="<?php echo $label_language_values['choose_your_addon_image']; ?>">
                                    <?php if ($arrs['predefine_image'] != "")
        { ?>
                                    <div class="cta-addons-list" id="addonid_<?php echo $arrs['id']; ?>" data-name="<?php echo $arrs['predefine_image']; ?>" data-p_i_name="<?php echo $arrs['predefine_image_title']; ?>">
                                       <img class="cta-addons-image" src='../assets/images/addons-images/<?php echo $arrs['predefine_image']; ?>' title="<?php echo $label_language_values['addon_image']; ?>" />
                                       <h3 class="cta-addons-name"><?php echo $arrs['predefine_image_title']; ?></h3>
                                    </div>
                                    <?php
        }
        else
        { ?>
                                    <div class="cta-addons-list" id="addonid_<?php echo $arrs['id']; ?>" data-name="" data-p_i_name="">
                                       <i class="cta-addons-image icon-puzzle icons"></i>
                                       <h3 class="cta-addons-name"><?php echo $label_language_values['select_addon_image']; ?></h3>
                                    </div>
                                    <?php
        } ?>
                                 </div>
                                 <div class="cta-addons-dropdown display_update_<?php echo $arrs['id']; ?>" >
                                    <div class="cta-addons-list select_addons" data-id="<?php echo $arrs['id']; ?>" data-name="ct-icon-fridge.png" data-p_i_name="<?php echo $label_language_values['inside_fridge']; ?>">
                                       <img class="cta-addons-image" src="../assets/images/addons-images/ct-icon-fridge.png" title="<?php echo $label_language_values['addon_image']; ?>" />
                                       <h3 class="cta-addons-name"><?php echo $label_language_values['inside_fridge']; ?></h3>
                                    </div>
                                    <div class="cta-addons-list select_addons"  data-id="<?php echo $arrs['id']; ?>" data-name="ct-icon-oven.png" data-p_i_name="<?php echo $label_language_values['inside_oven']; ?>">
                                       <img class="cta-addons-image" src="../assets/images/addons-images/ct-icon-oven.png" title="<?php echo $label_language_values['addon_image']; ?>" />
                                       <h3 class="cta-addons-name"><?php echo $label_language_values['inside_oven']; ?></h3>
                                    </div>
                                    <div class="cta-addons-list select_addons" data-id="<?php echo $arrs['id']; ?>" data-name="ct-icon-inside-window.png" data-p_i_name="<?php echo $label_language_values['inside_windows']; ?>">
                                       <img class="cta-addons-image" src="../assets/images/addons-images/ct-icon-inside-window.png" title="<?php echo $label_language_values['addon_image']; ?>" />
                                       <h3 class="cta-addons-name"><?php echo $label_language_values['inside_windows']; ?></h3>
                                    </div>
                                    <div class="cta-addons-list select_addons" data-id="<?php echo $arrs['id']; ?>" data-name="ct-icon-carpet.png" data-p_i_name="<?php echo $label_language_values['carpet_cleaning']; ?>">
                                       <img class="cta-addons-image" src="../assets/images/addons-images/ct-icon-carpet.png" title="<?php echo $label_language_values['addon_image']; ?>" />
                                       <h3 class="cta-addons-name"><?php echo $label_language_values['carpet_cleaning']; ?></h3>
                                    </div>
                                    <div class="cta-addons-list select_addons" data-id="<?php echo $arrs['id']; ?>" data-name="ct-icon-green-cleaning.png" data-p_i_name="<?php echo $label_language_values['green_cleaning']; ?>">
                                       <img class="cta-addons-image" src="../assets/images/addons-images/ct-icon-green-cleaning.png" title="<?php echo $label_language_values['addon_image']; ?>" />
                                       <h3 class="cta-addons-name"><?php echo $label_language_values['green_cleaning']; ?></h3>
                                    </div>
                                    <div class="cta-addons-list select_addons" data-id="<?php echo $arrs['id']; ?>" data-name="ct-icon-pets.png" data-p_i_name="<?php echo $label_language_values['pets_care']; ?>">
                                       <img class="cta-addons-image" src="../assets/images/addons-images/ct-icon-pets.png" title="<?php echo $label_language_values['addon_image']; ?>" />
                                       <h3 class="cta-addons-name"><?php echo $label_language_values['pets_care']; ?></h3>
                                    </div>
                                    <div class="cta-addons-list select_addons" data-id="<?php echo $arrs['id']; ?>" data-name="ct-icon-tiles.png" data-p_i_name="<?php echo $label_language_values['tiles_cleaning']; ?>">
                                       <img class="cta-addons-image" src="../assets/images/addons-images/ct-icon-tiles.png" title="<?php echo $label_language_values['addon_image']; ?>" />
                                       <h3 class="cta-addons-name"><?php echo $label_language_values['tiles_cleaning']; ?></h3>
                                    </div>
                                    <div class="cta-addons-list select_addons" data-id="<?php echo $arrs['id']; ?>" data-name="ct-icon-wall-cleaning.png" data-p_i_name="<?php echo $label_language_values['wall_cleaning']; ?>">
                                       <img class="cta-addons-image" src="../assets/images/addons-images/ct-icon-wall-cleaning.png" title="<?php echo $label_language_values['addon_image']; ?>" />
                                       <h3 class="cta-addons-name"><?php echo $label_language_values['wall_cleaning']; ?></h3>
                                    </div>
                                    <div class="cta-addons-list select_addons" data-id="<?php echo $arrs['id']; ?>" data-name="ct-icon-laundry.png" data-p_i_name="<?php echo $label_language_values['laundry']; ?>">
                                       <img class="cta-addons-image" src="../assets/images/addons-images/ct-icon-laundry.png" title="<?php echo $label_language_values['addon_image']; ?>" />
                                       <h3 class="cta-addons-name"><?php echo $label_language_values['laundry']; ?></h3>
                                    </div>
                                    <div class="cta-addons-list select_addons" data-id="<?php echo $arrs['id']; ?>" data-name="ct-icon-basement.png" data-p_i_name="<?php echo $label_language_values['basement_cleaning']; ?>">
                                       <img class="cta-addons-image" src="../assets/images/addons-images/ct-icon-basement.png" title="<?php echo $label_language_values['addon_image']; ?>" />
                                       <h3 class="cta-addons-name"><?php echo $label_language_values['basement_cleaning']; ?></h3>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div id="ct-image-upload-popuppcaol<?php echo $arrs['id']; ?>" class="ct-image-upload-popup modal fade" tabindex="-1" role="dialog">
                              <div class="vertical-alignment-helper">
                                 <div class="modal-dialog modal-md vertical-align-center">
                                    <div class="modal-content">
                                       <div class="modal-header">
                                          <div class="col-md-12 col-xs-12">                                                                <a data-us="pcaol<?php echo $arrs['id']; ?>" class="btn btn-success ct_upload_img2" data-imageinputid="ct-upload-imagepcaol<?php echo $arrs['id']; ?>" data-id="<?php echo $arrs['id']; ?>" ><?php echo $label_language_values['crop_and_save']; ?></a>                                                                <button type="button" class="btn btn-default hidemodal" data-dismiss="modal" aria-hidden="true"><?php echo $label_language_values['cancel']; ?></button>                                                            </div>
                                       </div>
                                       <div class="modal-body">                                                            <img id="ct-preview-imgpcaol<?php echo $arrs['id']; ?>" />                                                        </div>
                                       <div class="modal-footer">
                                          <div class="col-md-12 np">
                                             <div class="col-md-4 col-xs-12">                                                                    <label class="pull-left"><?php echo $label_language_values['file_size']; ?></label> <input type="text" class="form-control" id="pcaolfilesize<?php echo $arrs['id']; ?>" name="filesize" />                                                                </div>
                                             <div class="col-md-4 col-xs-12">                                                                    <label class="pull-left">H</label> <input type="text" class="form-control" id="pcaol<?php echo $arrs['id']; ?>h" name="h" />                                                                </div>
                                             <div class="col-md-4 col-xs-12">                                                                    <label class="pull-left">W</label> <input type="text" class="form-control" id="pcaol<?php echo $arrs['id']; ?>w" name="w" />                                                                </div>
                                             <!-- hidden crop params -->                                                                <input type="hidden" id="pcaol<?php echo $arrs['id']; ?>x1" name="x1" />                                                                <input type="hidden" id="pcaol<?php echo $arrs['id']; ?>y1" name="y1" />                                                                <input type="hidden" id="pcaol<?php echo $arrs['id']; ?>x2" name="x2" />                                                                <input type="hidden" id="pcaol<?php echo $arrs['id']; ?>y2" name="y2" />                                                                <input type="hidden" id="pcaol<?php echo $arrs['id']; ?>id" name="id" value="<?php echo $arrs['id']; ?>" />                                                                <input id="pcaolctimage<?php echo $arrs['id']; ?>" type="hidden" name="ctimage" />                                                                <input type="hidden" id="lastrecordid" value="addon_<?php echo $arrs['id']; ?>">                                                                <input type="hidden" id="pcaol<?php echo $arrs['id']; ?>ctimagename" class="pcaolimg" name="ctimagename" value="<?php echo $arrs['image']; ?>" />                                                                <input type="hidden" id="pcaol<?php echo $arrs['id']; ?>newname" value="addon_" />
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </td>
                     </tr>
                     <tr>
                        <td><label><?php echo $label_language_values['basic_price']; ?></label></td>
                        <td>
                           <div class="col-xs-12 np">
                              <div class="input-group">                                                <span class="input-group-addon"><?php echo $settings->get_option('ct_currency_symbol'); ?></span>                                                <input type="text" id="myedtaddon_baseprice<?php echo $arrs['id']; ?>" name = "myedtaddon_baseprice<?php echo $arrs['id']; ?>" class="form-control txtedtaddon_baseprice<?php echo $arrs['id']; ?>" placeholder="US Dollar" value="<?php echo $arrs['base_price']; ?>">                                            </div>
                              <label generated="true" class="error"></label>
                           </div>
                        </td>
                     </tr>
                     <tr>
                        <td><label><?php echo $label_language_values['max_qty']; ?></label></td>
                        <td>
                           <div class="col-xs-12 np">                               <input type="text" class="form-control txtedtaddon_maxqty<?php echo $arrs['id']; ?>" id="myedtaddon_maxqty<?php echo $arrs['id']; ?>" name = "myedtaddon_maxqty<?php echo $arrs['id']; ?>" value="<?php echo $arrs['maxqty']; ?>"/>                                        </div>
                        </td>
                     </tr>
                     <tr>
                        <td><label ><?php echo $label_language_values['multiple_qty']; ?></label></td>
                        <td>                                        <label >                                 <input class='txtedtaddon_multipleqty' data-toggle="toggle" data-size="small" type='checkbox' data-id="<?php echo $arrs['id']; ?>" <?php if ($arrs['multipleqty'] == 'Y')
        {
            echo "checked";
        }
        else
        {
            echo "";
        } ?> id="addon-multi-qty<?php echo $i; ?>" data-on="<?php echo $label_language_values['o_n']; ?>" data-off="<?php echo $label_language_values['off']; ?>" data-onstyle="primary" data-offstyle="default" />                             </label>                                    </td>
                     </tr>
                     <tr>
                        <td></td>
                        <td><a data-id="<?php echo $arrs['id']; ?>" class="btn btn-success ct-btn-width  btneditaddon_service com_set"><?php echo $label_language_values['update']; ?>           </a>
						<button type="reset" class="btn btn-default ct-btn-width ml-30"><?php echo $label_language_values['reset']; ?></button>
						</td>
                     </tr>
                  </tbody>
               </table>
            </form>
         </div>
         <div class="ct-service-collapse-div col-sm-12 col-md-6 col-lg-6 col-xs-12 manage-addon-price-container<?php echo $arrs['id']; ?>" style="display:<?php if ($settings->get_option('ct_calculation_policy') == "M")
        {
            echo "none";
        }
        else
        {
            echo "block";
        } ?>">
            <div class="addon-price-rules-container mt-20 ">
               <div class="manage-unit-price-main col-sm-12 col-md-12 col-lg-12 col-xs-12">
                  <h4><?php echo $label_language_values['service_addons_price_rules']; ?></h4>
                  <ul>
                     <li class="form-group">
                        <label class="col-sm-2 col-xs-12 np"><?php echo $label_language_values['base_price']; ?></label>
                        <div class="col-xs-12 col-sm-2">                                                <input class="form-control" placeholder="1" value="1" id="" type="text" readonly="readonly" /></div>
                        <div class="price-rules-select">
                           <select class="form-control" id="">
                              <option selected="" readonly value="=">= </option>
                           </select>
                        </div>
                        <div class="col-xs-12 col-sm-3">                                                <input class="pull-left form-control" readonly value="<?php echo $arrs['base_price']; ?>" placeholder="<?php echo $label_language_values['price']; ?>" type="text" />                                            </div>
                     </li>
                  </ul>
                  <ul class="myaddonspricebyqty<?php echo $arrs['id']; ?>">
                     <?php $objservice_addon_rate->addon_service_id = $arrs['id'];
        $idss = $arrs['id'];
        $result = $objservice_addon_rate->readall();
        while ($r = mysqli_fetch_array($result))
        { ?>
                     <li class="form-group myaddon-qty_price_row<?php echo $r['id']; ?>">
                        <form id="myedtform_addonunits<?php echo $r['id']; ?>">
                           <label class="col-sm-2 col-xs-12"><?php echo $label_language_values['Quantity']; ?></label>
                           <div class="col-xs-12 col-sm-2">                                                        <input id="myedtqty_addon<?php echo $r['id']; ?>" name="txtedtqtyaddons_<?php echo $r['id']; ?>" class="form-control myloadedqty_addons<?php echo $r['id']; ?>" placeholder="1" value="<?php echo $r['unit']; ?>" type="text"/>                                                    </div>
                           <div class="price-rules-select">
                              <select                                                            class="form-control myloadedrules_addons<?php echo $r['id']; ?>">
                                 <option <?php if ($r['rules'] == 'E')
            { ?>selected<?php
            } ?>                                                                    value="E">=                                                            </option>
                                 <option <?php if ($r['rules'] == 'G')
            { ?>selected<?php
            } ?>                                                                    value="G"> &gt; </option>
                              </select>
                           </div>
                           <div class="col-xs-12 col-sm-3">                                                        <input name="myedtpriceaddon" id="myedtprice_addon<?php echo $r['id']; ?>" class="pull-left form-control myloadedprice_addons<?php echo $r['id']; ?>" value="<?php echo $r['rate']; ?>" placeholder="<?php echo $label_language_values['price']; ?>" type="text"/>                                                    </div>
                           <a data-id="<?php echo $r['id']; ?>"                                                       class="btn btn-circle btn-success  pull-left update-addon-rule myloadedbtnsave_addons"><i class="fa fa-thumbs-up"></i></a>                                                    <a href="javascript:void(0);" data-id="<?php echo $r['id']; ?>"                                                       class="btn btn-circle btn-danger pull-left delete-addon-rule myloadedbtndelete_addons"><i class="fa fa-trash"></i></a>
                        </form>
                     </li>
                     <?php
        } ?>
                     <li class="form-group">
                        <form id="mynewaddedform_addonunits<?php echo $arrs['id']; ?>">
                           <label class="col-sm-2 col-xs-12" ><?php echo $label_language_values['Quantity']; ?></label>
                           <div class="col-xs-12 col-sm-2">                                                    <input name="mynewssqtyaddon<?php echo $arrs['id']; ?>"                                                           id="mynewaddedqty_addon<?php echo $arrs['id']; ?>"                                                           class="form-control mynewqty_addons<?php echo $arrs['id']; ?>"                                                           placeholder="1"                                                           value="" type="text"/>                                                </div>
                           <div class="price-rules-select">
                              <select                                                        class="form-control mynewrules_addons<?php echo $arrs['id']; ?>">
                                 <option selected value="E">=</option>
                                 <option value="G"> &gt; </option>
                              </select>
                           </div>
                           <div class="col-xs-12 col-sm-3">                                                    <input name="mynewsspriceaddon<?php echo $arrs['id']; ?>"                                                           id="mynewaddedprice_addon<?php echo $arrs['id']; ?>"                                                           class="pull-left form-control mynewprice_addons<?php echo $arrs['id']; ?>"                                                           value=""                                                           placeholder="<?php echo $label_language_values['price']; ?>" type="text"/>                                                </div>
                           <a href="javascript:void(0);" data-id="<?php echo $arrs['id']; ?>" data-inspector="0" class="btn btn-circle btn-success add-addon-price-rule form-group new-manage-price-list mybtnaddnewqty_addon"><?php echo $label_language_values['add_new']; ?></a>
                        </form>
                     </li>
                  </ul>
               </div>
            </div>
            <!-- end manage unit price container -->
         </div>
      </div>
   </div>
</li>
<?php
    } ?>
<li class="new-addon-scroll">
   <!-- add new clean service pop up -->
   <div class="panel panel-default ct-clean-services-panel ct-add-new-service cta-add-new-addon">
      <div class="panel-heading">
         <h4 class="panel-title">
            <div class="cta-col8">
               <div class="pull-left">                            <i class="fa fa-th-list"></i>                        </div>
               <span class="ct-service-title-name"></span>
            </div>
            <div class="pull-right cta-col4">
               <div class="pull-right">
                  <div class="ct-show-hide pull-right">
                     <input type="checkbox" name="ct-show-hide" checked="checked" class="ct-show-hide-checkbox" id="sp0" ><!--Added Serivce Id-->                                <label class="ct-show-hide-label" for="sp0"></label>
                  </div>
               </div>
            </div>
         </h4>
      </div>
      <div id="" class="panel-collapse collapse in detail_sp0">
         <div class="panel-body">
            <div class="ct-service-collapse-div col-sm-12 col-md-6 col-lg-6 col-xs-12">
               <form id="mynewformfor_insertaddons" method="post" type="" class="slide-toggle">
                  <table class="ct-create-service-table">
                     <tbody>
                        <tr>
                           <td><label for="ct-service-title"><?php echo $label_language_values['addon_title']; ?></label></td>
                           <td>
                              <div class="col-sm-6 col-xs-12 np">                               <input type="text" placeholder="<?php echo $label_language_values['addon_title']; ?>" class="form-control txtaddon_title" id="mynewtitlefor_addons" name="newssssqty_addon"/></div>
                           </td>
                        </tr>
                        <tr>
                           <td><label for="ct-service-hours"><?php echo $label_language_values['duration']; ?></label></td>
                           <td>
                              <div class="form-inline dis_flex">
                                 <div class="input-group w-50">                                 <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>                                <input placeholder="00" size="2" maxlength="2" type="text" placeholder="Duration hours" class="form-control txtaddon_hours" id="addon_add_hours" name="addon_add_hours" />                                                                <span class="input-group-addon"><?php echo $label_language_values['hours']; ?></span>                             </div>
                                 <div class="input-group w-50">                                 <input placeholder="05" size="2" maxlength="2" type="text" placeholder="Duration mintues" class="form-control txtaddon_mints" id="addon_add_mints" name="addon_add_mints" />                                <span class="input-group-addon"><?php echo $label_language_values['minutes']; ?></span>                              </div>
                              </div>
                           </td>
                        </tr>
                        <tr>
                           <td><label for="ct-service-desc"><?php echo $label_language_values['service_image']; ?></label></td>
                           <td>
                              <div class="ct-clean-service-addons-image-uploader">
                                 <img id="pcaoaddonimage" src="../assets/images/default_service.png" class="ct-clean-service-addons-image br-100" height="100" width="100">                                            <label for="ct-upload-imagepcao" class="ct-clean-service-addons-img-icon-label">                                                <i class="ct-camera-icon-common br-100 fa fa-camera"></i>                                                <i class="pull-left fa fa-plus-circle fa-2x"></i>                                            </label>
                                 <input data-us="pcao" class="hide ct-upload-images" type="file" name="" id="ct-upload-imagepcao" />
                                 <a id="ct-remove-service-addons-imagepcao" class="pull-left br-100 btn-danger bt-remove-service-addons-img btn-xs hide" rel="popover" data-placement='left' title="<?php echo $label_language_values['remove_image']; ?>"> <i class="fa fa-trash" title="<?php echo $label_language_values['remove_image']; ?>"></i></a>
                                 <div id="popover-ct-remove-service-addons-image" style="display: none;">
                                    <div class="arrow"></div>
                                    <table class="form-horizontal" cellspacing="0">
                                       <tbody>
                                          <tr>
                                             <td>                                                            <a href="javascript:void(0)" id="" value="Delete" class="btn btn-danger btn-sm" type="submit"><?php echo $label_language_values['yes']; ?></a>                                                            <a href="javascript:void(0)" id="ct-close-popover-service-image" class="btn btn-default btn-sm" href="javascript:void(0)"><?php echo $label_language_values['cancel']; ?></a>                                                        </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                 </div>
                                 <!-- end pop up -->
                              </div>
                              <label class="error_image" ></label>                                                            <span class="cta-addon-img-icon"><?php echo $label_language_values['or']; ?></span>
                              <div class="cta-addons-imagelist-dropdown fl">
                                 <div class="cta-addons-selection-main">
                                    <div class="cta-addon-is insert_id" title="<?php echo $label_language_values['choose_your_addon_image']; ?>">
                                       <div class="cta-addons-list" id="cta_selected_addon" data-name="" data-p_i_name="">
                                          <i class="cta-addons-image icon-puzzle icons"></i>
                                          <h3 class="cta-addons-name"><?php echo $label_language_values['select_addon_image']; ?></h3>
                                       </div>
                                    </div>
                                    <div class="cta-addons-dropdown display_insert">
                                       <div class="cta-addons-list select_addons_insert" data-name="ct-icon-fridge.png" data-p_i_name="<?php echo $label_language_values['inside_fridge']; ?>">
                                          <img class="cta-addons-image" src="../assets/images/addons-images/ct-icon-fridge.png" title="<?php echo $label_language_values['addon_image']; ?>" />
                                          <h3 class="cta-addons-name"><?php echo $label_language_values['inside_fridge']; ?></h3>
                                       </div>
                                       <div class="cta-addons-list select_addons_insert" data-name="ct-icon-oven.png" data-p_i_name="<?php echo $label_language_values['inside_oven']; ?>">
                                          <img class="cta-addons-image" src="../assets/images/addons-images/ct-icon-oven.png" title="<?php echo $label_language_values['addon_image']; ?>" />
                                          <h3 class="cta-addons-name"><?php echo $label_language_values['inside_oven']; ?></h3>
                                       </div>
                                       <div class="cta-addons-list select_addons_insert" data-name="ct-icon-inside-window.png" data-p_i_name="<?php echo $label_language_values['inside_windows']; ?>">
                                          <img class="cta-addons-image" src="../assets/images/addons-images/ct-icon-inside-window.png" title="<?php echo $label_language_values['addon_image']; ?>" />
                                          <h3 class="cta-addons-name"><?php echo $label_language_values['inside_windows']; ?></h3>
                                       </div>
                                       <div class="cta-addons-list select_addons_insert" data-name="ct-icon-carpet.png" data-p_i_name="<?php echo $label_language_values['carpet_cleaning']; ?>">
                                          <img class="cta-addons-image" src="../assets/images/addons-images/ct-icon-carpet.png" title="<?php echo $label_language_values['addon_image']; ?>" />
                                          <h3 class="cta-addons-name"><?php echo $label_language_values['carpet_cleaning']; ?></h3>
                                       </div>
                                       <div class="cta-addons-list select_addons_insert" data-name="ct-icon-green-cleaning.png" data-p_i_name="<?php echo $label_language_values['green_cleaning']; ?>">
                                          <img class="cta-addons-image" src="../assets/images/addons-images/ct-icon-green-cleaning.png" title="<?php echo $label_language_values['addon_image']; ?>" />
                                          <h3 class="cta-addons-name"><?php echo $label_language_values['green_cleaning']; ?></h3>
                                       </div>
                                       <div class="cta-addons-list select_addons_insert" data-name="ct-icon-pets.png" data-p_i_name="<?php echo $label_language_values['pets_care']; ?>">
                                          <img class="cta-addons-image" src="../assets/images/addons-images/ct-icon-pets.png" title="<?php echo $label_language_values['addon_image']; ?>" />
                                          <h3 class="cta-addons-name"><?php echo $label_language_values['pets_care']; ?></h3>
                                       </div>
                                       <div class="cta-addons-list select_addons_insert" data-name="ct-icon-tiles.png" data-p_i_name="<?php echo $label_language_values['tiles_cleaning']; ?>">
                                          <img class="cta-addons-image" src="../assets/images/addons-images/ct-icon-tiles.png" title="<?php echo $label_language_values['addon_image']; ?>" />
                                          <h3 class="cta-addons-name"><?php echo $label_language_values['tiles_cleaning']; ?></h3>
                                       </div>
                                       <div class="cta-addons-list select_addons_insert" data-name="ct-icon-wall-cleaning.png" data-p_i_name="<?php echo $label_language_values['wall_cleaning']; ?>">
                                          <img class="cta-addons-image" src="../assets/images/addons-images/ct-icon-wall-cleaning.png" title="<?php echo $label_language_values['addon_image']; ?>" />
                                          <h3 class="cta-addons-name"><?php echo $label_language_values['wall_cleaning']; ?></h3>
                                       </div>
                                       <div class="cta-addons-list select_addons_insert" data-name="ct-icon-laundry.png" data-p_i_name="<?php echo $label_language_values['laundry']; ?>">
                                          <img class="cta-addons-image" src="../assets/images/addons-images/ct-icon-laundry.png" title="<?php echo $label_language_values['addon_image']; ?>" />
                                          <h3 class="cta-addons-name"><?php echo $label_language_values['laundry']; ?></h3>
                                       </div>
                                       <div class="cta-addons-list select_addons_insert" data-name="ct-icon-basement.png" data-p_i_name="<?php echo $label_language_values['basement_cleaning']; ?>">
                                          <img class="cta-addons-image" src="../assets/images/addons-images/ct-icon-basement.png" title="<?php echo $label_language_values['addon_image']; ?>" />
                                          <h3 class="cta-addons-name"><?php echo $label_language_values['basement_cleaning']; ?></h3>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div id="ct-image-upload-popuppcao" class="ct-image-upload-popup modal fade" tabindex="-1" role="dialog">
                                 <div class="vertical-alignment-helper">
                                    <div class="modal-dialog modal-md vertical-align-center">
                                       <div class="modal-content">
                                          <div class="modal-header">
                                             <div class="col-md-12 col-xs-12">                                                                <a data-us="pcao" class="btn btn-success ct_upload_img2" data-imageinputid="ct-upload-imagepcao"><?php echo $label_language_values['crop_and_save']; ?></a>                                                                <button type="button" class="btn btn-default hidemodal" data-dismiss="modal" aria-hidden="true"><?php echo $label_language_values['cancel']; ?></button>                                                            </div>
                                          </div>
                                          <div class="modal-body">                                                            <img id="ct-preview-imgpcao" />                                                        </div>
                                          <div class="modal-footer">
                                             <div class="col-md-12 np">
                                                <div class="col-md-4 col-xs-12">                                                                    <label class="pull-left"><?php echo $label_language_values['file_size']; ?></label> <input type="text" class="form-control" id="pcaofilesize" name="filesize" />                                                                </div>
                                                <div class="col-md-4 col-xs-12">                                                                    <label class="pull-left">H</label> <input type="text" class="form-control" id="pcaoh" name="h" />                                                                </div>
                                                <div class="col-md-4 col-xs-12">                                                                    <label class="pull-left">W</label> <input type="text" class="form-control" id="pcaow" name="w" />                                                                </div>
                                                <!-- hidden crop params -->                                                                <input type="hidden" id="pcaox1" name="x1" />                                                                <input type="hidden" id="pcaoy1" name="y1" />                                                                <input type="hidden" id="pcaox2" name="x2" />                                                                <input type="hidden" id="pcaoy2" name="y2" />                                                                <input type="hidden" id="pcaoid" name="id" value="<?php echo ($objservice_addon->getlast_record_insert() + 1); ?>" />                                                                <input id="pcaoctimage" type="hidden" name="ctimage" />                                                                <input type="hidden" id="lastrecordid" value="addon_<?php echo ($objservice_addon->getlast_record_insert() + 1); ?>">                                                                <input type="hidden" id="pcaoctimagename" class="pcaoimg" name="ctimagename" value="" />                                                                <input type="hidden" id="pcaonewname" value="addon_" />
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </td>
                        </tr>
                        <tr>
                           <td><label for="ct-service-price"><?php echo $label_language_values['basic_price']; ?></label></td>
                           <td>
                              <div class="col-sm-4 col-xs-12 np">
                                 <div class="input-group">                                                <span class="input-group-addon"><?php echo $settings->get_option('ct_currency_symbol'); ?></span>                                                <input type="text" class="form-control txtaddon_baseprice" placeholder="US Dollar" id="mynewbasepricefor_addons" name="newssssprice_addon">                                            </div>
                                 <label for="mynewbasepricefor_addons" generated="true" class="error"></label>
                              </div>
                           </td>
                        </tr>
                        <tr>
                           <td><label for="ct-service-title"><?php echo $label_language_values['max_qty']; ?></label></td>
                           <td>
                              <div class="col-sm-4 col-xs-12 np">                               <input type="text" placeholder="<?php echo $label_language_values['max_qty']; ?>" class="form-control txtaddon_maxqty" id="mynewbasemaxqtyfor_addons" name="newssssmaxqty_addon"/></div>
                           </td>
                        </tr>
                        <tr>
                           <td><label for="phone-number"><?php echo $label_language_values['multiple_qty']; ?></label></td>
                           <td>                                        <label for="addon-multi-qty0">                               <input class='txtaddon_multiple' data-toggle="toggle" data-size="small" type='checkbox' id="addon-multi-qty0" data-on="<?php echo $label_language_values['o_n']; ?>" data-off="<?php echo $label_language_values['off']; ?>" data-onstyle='primary' data-offstyle='default' />                              </label>                                    </td>
                        </tr>
                        <tr>
                           <td></td>
                           <td>                             <a name="asdad" class="btn btn-success ct-btn-width  btnaddon_save"                                   type="submit"><?php echo $label_language_values['save']; ?>                            </a>                             <button type="reset" id="reset_service_addons" class="btn btn-default ct-btn-width ml-30"><?php echo $label_language_values['reset']; ?></button>                          </td>
                        </tr>
                     </tbody>
                  </table>
            </div>
            </form>
         </div>
      </div>
   </div>
</li>
<?php /* echo mysqli_num_rows($res); */
}
else if (isset($_POST['deleteid']))
{
    chmod(dirname(dirname(dirname(__FILE__))) . "/assets/images/services", 0777);
    $objservice_addon->id = $_POST['deleteid'];
    unlink(dirname(dirname(dirname(__FILE__))) . "/assets/images/services/" . $_POST['imagename']); /* DELETE ALL ADDONS SUB RECORDS */
    $addons_rates = $objservice->get_exist_addons_rate_by_addonid($_POST['deleteid']);
    while ($addons_rates_arr = mysqli_fetch_array($addons_rates))
    { /* DELETE ADDONS RATE*/
        $objservice->delete_addons_rate($addons_rates_arr['id']);
    } /* DELETE ADDONS*/
    $objservice->delete_addons_of_service($_POST['deleteid']);
    $objservice_addon->delete_services_addon();
}
elseif (isset($_POST['changestatus']))
{
    $objservice_addon->id = $_POST['id'];
    $objservice_addon->status = $_POST['changestatus'];
    $objservice_addon->changestatus();
    if ($objservice_addon)
    {
        if ($_POST['changestatus'] == 'E')
        {
            echo "Addon's Enable";
        }
        else
        {
            echo "Addon's Disable";
        }
    }
}
elseif (isset($_POST['operationinsert']))
{
    chmod(dirname(dirname(dirname(__FILE__))) . "/assets/images/services", 0777);
    $objservice_addon->service_id = $_POST['service_id'];
    $objservice_addon->addon_service_name = filter_var($_POST['addon_service_name'], FILTER_SANITIZE_STRING);
    $hours = $_POST['addon_hours'];
    $mintues = $_POST['addon_mints'];
    if ($_POST['addon_hours'] > 0 && $_POST['addon_hours'] != '')
    {
        $objservice_addon->duration = ($hours * 60) + $mintues;
    }
    else
    {
        $objservice_addon->duration = $hours + $mintues;
    }
    $t = $objservice_addon->check_same_title();
    $cnt = mysqli_num_rows($t);
    
    if ($cnt == 0)
    {
        $objservice_addon->service_id = $_POST['service_id'];
        $objservice_addon->addon_service_name = filter_var(mysqli_real_escape_string($conn, ucwords($_POST['addon_service_name'])) , FILTER_SANITIZE_STRING);
        $objservice_addon->base_price = $_POST['base_price'];
        $objservice_addon->maxqty = $_POST['maxqty'];
        $objservice_addon->image = $_POST['image'];
        $objservice_addon->multipleqty = $_POST['multipleqty'];
        $objservice_addon->status = $_POST['status'];
        $objservice_addon->tags = $_POST['tags'] ? $_POST['tags'] : $_POST['addon_service_name'];
        $objservice_addon->predefine_image = $_POST['predefineimage'];
        $objservice_addon->predefine_image_title = $_POST['predefineimage_title'];
        $objservice_addon->add_services_addon(); /* REMOVE UNSED IMAGES FROM FOLDER*/
        $used_images = $objservice->get_used_images();
        $imgarr = array();
        while ($img = mysqli_fetch_array($used_images))
        {
            $filtername = preg_replace('/\\.[^.\\s]{3,4}$/', '', $img[0]);
            array_push($imgarr, $filtername);
        }
        array_push($imgarr, "default");
        array_push($imgarr, "default_service");
        array_push($imgarr, "default_service1");
        $dir = dirname(dirname(dirname(__FILE__))) . "/assets/images/services/";
        $cnt = 1;
        if ($dh = opendir($dir))
        {
            while (($file = readdir($dh)) !== false)
            {
                if ($cnt > 2)
                {
                    $filtername = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
                    if (in_array($filtername, $imgarr))
                    {
                    }
                    elseif ($file == '..')
                    {
                        continue;
                    }
                    else
                    {
                        unlink(dirname(dirname(dirname(__FILE__))) . "/assets/images/services/" . $file);
                    }
                }
                $cnt++;
            }
            closedir($dh);
        }
    }
    else
    {
        echo "1";
    }
}
elseif (isset($_POST['operationedit']))
{
    chmod(dirname(dirname(dirname(__FILE__))) . "/assets/images/services", 0777);
    $objservice_addon->id = $_POST['id'];
    $objservice_addon->addon_service_name = filter_var(mysqli_real_escape_string($conn, ucwords($_POST['addon_service_name'])) , FILTER_SANITIZE_STRING);
    $objservice_addon->addon_service_description = $_POST['addon_service_description'];
    $hours = $_POST['addon_hours'];
    $mintues = $_POST['addon_mints'];
    if ($_POST['addon_hours'] > 0 && $_POST['addon_hours'] != '')
    {
        $objservice_addon->duration = ($hours * 60) + $mintues;
    }
    else
    {
        $objservice_addon->duration = $hours + $mintues;
    }
    $objservice_addon->base_price = $_POST['base_price'];
    $objservice_addon->maxqty = $_POST['maxqty'];
    $objservice_addon->image = $_POST['image'];
    $objservice_addon->tags = $_POST['tags'];
    $objservice_addon->predefine_image = $_POST['predefineimage'];
    $objservice_addon->predefine_image_title = $_POST['predefineimage_title'];
    $objservice_addon->update_services_addon(); /* REMOVE UNSED IMAGES FROM FOLDER */
    $used_images = $objservice->get_used_images();
    $imgarr = array();
    while ($img = mysqli_fetch_array($used_images))
    {
        $filtername = preg_replace('/\\.[^.\\s]{3,4}$/', '', $img[0]);
        array_push($imgarr, $filtername);
    }
    array_push($imgarr, "default");
    array_push($imgarr, "default_service");
    array_push($imgarr, "default_service1");
    print_r($imgarr);
    $dir = dirname(dirname(dirname(__FILE__))) . "/assets/images/services/";
    $cnt = 1;
    if ($dh = opendir($dir))
    {
        while (($file = readdir($dh)) !== false)
        {
            if ($cnt > 2)
            {
                $filtername = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
                if (in_array($filtername, $imgarr))
                {
                }
                else
                {
                    unlink(dirname(dirname(dirname(__FILE__))) . "/assets/images/services/" . $file);
                }
            }
            $cnt++;
        }
        closedir($dh);
    }
}
elseif (isset($_POST['operationedit_multipleqty']))
{
    $objservice_addon->id = $_POST['id'];
    $objservice_addon->multipleqty = $_POST['multipleqty'];
    $objservice_addon->changemultiple_qty();
}
elseif (isset($_POST['operationdelete_addonprice']))
{
    $objservice_addon_rate->id = $_POST['id'];
    $objservice_addon_rate->delete_addon_service_rate();
}
elseif (isset($_POST['operation_getallqtyprice']))
{
    $objservice_addon_rate->addon_service_id = $_POST['addon_service_id'];
    $idss = $_POST['addon_service_id'];
    $result = $objservice_addon_rate->readall();
    while ($r = mysqli_fetch_array($result))
    { ?>
<li class="form-group myaddon-qty_price_row<?php echo $r['id']; ?>">
   <form id="myedtform_addonunits<?php echo $r['id']; ?>">
      <label class="col-sm-2 col-xs-12" for="addon_qty_6"><?php echo $label_language_values['qty']; ?>.</label>
      <div class="col-xs-12 col-sm-2">                    <input id="myedtqty_addon<?php echo $r['id']; ?>"                           name="txtedtqtyaddons_<?php echo $r['id']; ?>"                           class="form-control myloadedqty_addons<?php echo $r['id']; ?>"                           placeholder="1"                           value="<?php echo $r['unit']; ?>" type="text"/>                </div>
      <div class="price-rules-select">
         <select                        class="form-control myloadedrules_addons<?php echo $r['id']; ?>">
            <option <?php if ($r['rules'] == 'E')
        { ?>selected<?php
        } ?>                                value="E">=                        </option>
            <option <?php if ($r['rules'] == 'G')
        { ?>selected<?php
        } ?>                                value="G"> &gt; </option>
         </select>
      </div>
      <div class="col-xs-12 col-sm-3">                    <input name="myedtpriceaddon"                           id="myedtprice_addon<?php echo $r['id']; ?>"                           class="pull-left form-control myloadedprice_addons<?php echo $r['id']; ?>"                           value="<?php echo $r['rate']; ?>" placeholder="<?php echo $label_language_values['price']; ?>"                           type="text"/>                </div>
      <a data-id="<?php echo $r['id']; ?>"                   class="btn btn-circle btn-success  pull-left update-addon-rule myloadedbtnsave_addons"><i                        class="fa fa-thumbs-up"></i></a>                <a href="javascript:void(0);" data-id="<?php echo $r['id']; ?>"                   class="btn btn-circle btn-danger pull-left delete-addon-rule myloadedbtndelete_addons"><i                        class="fa fa-trash"></i></a>
   </form>
</li>
<?php
    } ?>
<li class="form-group">
   <form id="mynewaddedform_addonunits<?php echo $_POST['addon_service_id']; ?>">
      <label class="col-sm-2 col-xs-12" for="addon_qty_6"><?php echo $label_language_values['qty']; ?>.</label>
      <div class="col-xs-12 col-sm-2">          <input name="mynewssqtyaddon<?php echo $_POST['addon_service_id']; ?>"                 id="mynewaddedqty_addon<?php echo $_POST['addon_service_id']; ?>"                class="form-control mynewqty_addons<?php echo $_POST['addon_service_id']; ?>"                placeholder="1"                  value="" type="text"/>        </div>
      <div class="price-rules-select">
         <select              class="form-control mynewrules_addons<?php echo $_POST['addon_service_id']; ?>">
            <option selected value="E">=</option>
            <option value="G"> &gt; </option>
         </select>
      </div>
      <div class="col-xs-12 col-sm-3">          <input name="mynewsspriceaddon<?php echo $_POST['addon_service_id']; ?>"                  id="mynewaddedprice_addon<?php echo $_POST['addon_service_id']; ?>"                 class="pull-left form-control mynewprice_addons<?php echo $_POST['addon_service_id']; ?>"                value=""                placeholder="<?php echo $label_language_values['price']; ?>" type="text"/>       </div>
      <a href="javascript:void(0);"                   data-id="<?php echo $_POST['addon_service_id']; ?>"                     data-inspector="0"                     class="btn btn-circle btn-success add-addon-price-rule form-group new-manage-price-list mybtnaddnewqty_addon"><?php echo $label_language_values['add_new']; ?></a>
   </form>
</li>
</ul><?php
}
elseif (isset($_POST['operation_updateqtyprice_addon']))
{
    $objservice_addon_rate->id = $_POST['editid'];
    $objservice_addon_rate->unit = $_POST['qty'];
    $objservice_addon_rate->rules = $_POST['rules'];
    $objservice_addon_rate->rate = $_POST['price'];
    $objservice_addon_rate->update_addonprice();
}
elseif (isset($_POST['operation_insertqtyprice_addon']))
{
    $objservice_addon_rate->unit = $_POST['qty'];
    $objservice_addon_rate->rules = $_POST['rules'];
    $objservice_addon_rate->rate = $_POST['price'];
    $objservice_addon_rate->addon_service_id = $_POST['addon_id'];
    $objservice_addon_rate->insert_addonprice();
} /*Delete Service Addons Image*/
if (isset($_POST['action']) && $_POST['action'] == 'delete_image_addons')
{
    $objservice_addon->id = $_POST['serviceaddons_id'];
    $objservice_addon->image = "";
    $del_image = $objservice_addon->update_image(); /* if($del_image){        unlink(dirname(dirname(__FILE__))."/images/business/".$_SESSION['b_id']."/".$_POST['image_name']); } */
}
if (isset($_POST['pos']) && isset($_POST['ids']))
{
    echo "yes in "; /* echo count((array)$_POST['ids']); */
    for ($i = 0;$i < count((array)$_POST['ids']);$i++)
    {
        $objservice_addon->position = $_POST['pos'][$i];
        $objservice_addon->id = $_POST['ids'][$i];
        $objservice_addon->updateposition();
    }
} ?>
