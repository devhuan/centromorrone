<?php     
include(dirname(dirname(dirname(__FILE__)))."/objects/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/header.php");
include(dirname(dirname(dirname(__FILE__)))."/objects/class_setting.php");
include(dirname(dirname(dirname(__FILE__)))."/objects/class_services_addon.php");
include(dirname(dirname(dirname(__FILE__)))."/objects/class_look_day.php");
$con = new cleanto_db();
$conn = $con->connect();
$addonlookday = new cleanto_addonlookday();
$addonlookday->conn = $conn;
$objservice_addon = new cleanto_services_addon();
$objservice_addon->conn = $conn;
$objservice_addon->service_id = 4;
$addon_det = [];
$setting = new cleanto_setting();
$setting->conn = $conn;
$getdateformat = $setting->get_option('ct_date_picker_date_format');
if(isset($_POST['add_html']))
{
    $addon_id = $_POST['addon_id'];
    $weekday = $_POST['weekday'];
    $res = $addonlookday->getdataby_id($addon_id, $weekday);
    foreach ($res as $value) {
        $addon_det = $value;
    };
    ?>
    <input type="hidden" id="weekday" name="weekday" value="<?= $weekday ?>">
    <div class="col-xs-12">
        <div class="form-group">
            <label class="cta-col2 ct-w-50"><?php echo 'Data'; ?>:</label>
            <div class="cta-col6 ct-w-50">
                <?php 
                $block_date = (isset($addon_det['block_date']) && $addon_det['block_date']) ? date("Y-m-d",strtotime($addon_det['block_date'])) : '';
                ?>
                <input class="exp_cp_date_lock form-control" id="expiry_lock_date" value="<?php echo $block_date;?>" data-date-format="yyyy/mm/dd" data-provide="datepicker" />
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="form-group">
            <label class="cta-col2 ct-w-50"><?php echo 'Orario'; ?>:</label>
            <div class="cta-col10 ct-w-50">
                <?php 
                $block_time_from = (isset($addon_det['block_time_from']) && $addon_det['block_time_from']) ? date('H:i:s',strtotime($addon_det['block_time_from'])) : '';
                $block_time_to = (isset($addon_det['block_time_to']) && $addon_det['block_time_to']) ? date('H:i:s',strtotime($addon_det['block_time_to'])) : '';
                ?>
                <div>
                    <select class="selectpicker selectpickerstart" id="start_lock_time">
                    <?php 
                    $min = 0;
                    while ($min < 1440) {
                        if ($min == 1440) {
                            $timeValue = date('G:i', mktime(0, $min - 1, 0, 1, 1, 2015));
                        } else {
                            $timeValue = date('G:i', mktime(0, $min, 0, 1, 1, 2015));
                        }
                        $timetoprint = date('G:i', mktime(0, $min, 0, 1, 1, 2014)); ?>
                        <option <?php  if ($block_time_from == date("H:i:s", strtotime($timeValue))) {
                            echo "selected";
                        } elseif("10:00:00" == date("H:i:s", strtotime($timeValue))){ echo "selected";}?>
                            value="<?php echo date("H:i:s", strtotime($timeValue)); ?>">
                            <?php 
                                echo date("H:i", strtotime($timetoprint));
                            ?>
                        </option>
                        <?php 
                        $min = $min + 60;
                    }
                    ?>
                    </select>
                    <div class="ct-staff-hours-to"> <?php  echo 'to';?> </div>
                    <select class="selectpicker selectpickerend" id="end_lock_time">
                    <?php 
                    $min = 0;
                    while ($min < 1440) {
                        if ($min == 1440) {
                            $timeValue = date('G:i', mktime(0, $min - 1, 0, 1, 1, 2015));
                        } else {
                            $timeValue = date('G:i', mktime(0, $min, 0, 1, 1, 2015));
                        }
                        $timetoprint = date('G:i', mktime(0, $min, 0, 1, 1, 2014)); ?>
                        <option <?php  if ($block_time_to == date("H:i:s", strtotime($timeValue))) {
                            echo "selected";
                        } elseif("20:00:00" == date("H:i:s", strtotime($timeValue))){ echo "selected";}?>
                            value="<?php echo date("H:i:s", strtotime($timeValue)); ?>">
                            <?php 
                                echo date("H:i", strtotime($timetoprint));
                            ?>
                        </option>
                        <?php 
                        $min = $min + 60;
                    }
                    ?>
                    </select>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="clearfix"></div>
<?php } elseif (isset($_POST['add_html_addon'])){ 
    $addon_id = $_POST['addon_id'];
    $res = $objservice_addon->getdataby_id($addon_id);
    foreach ($res as $value) {
        $addon_det = $value;
    };
    ?>
    <input type="hidden" id="weekday" name="weekday" value="all">
    <div class="col-xs-12">
        <div class="form-group">
            <label class="cta-col2 ct-w-50"><?php echo 'Data From'; ?>:</label>
            <div class="cta-col6 ct-w-50">
                <?php 
                $block_date = (isset($addon_det['block_date']) && $addon_det['block_date']) ? date("Y-m-d",strtotime($addon_det['block_date'])) : '';
                ?>
                <input class="exp_cp_date_lock form-control" id="expiry_lock_date" value="<?php echo $block_date;?>" data-date-format="yyyy/mm/dd" data-provide="datepicker" />
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="form-group">
            <label class="cta-col2 ct-w-50"><?php echo 'Data To'; ?>:</label>
            <div class="cta-col6 ct-w-50">
                <?php 
                $block_date_to = (isset($addon_det['block_date_to']) && $addon_det['block_date_to']) ? date("Y-m-d",strtotime($addon_det['block_date_to'])) : '';
                ?>
                <input class="exp_cp_date_lock form-control" id="expiry_lock_date_to" value="<?php echo $block_date_to;?>" data-date-format="yyyy/mm/dd" data-provide="datepicker" />
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="form-group">
            <label class="cta-col2 ct-w-50"><?php echo 'Orario'; ?>:</label>
            <div class="cta-col10 ct-w-50">
                <?php 
                $block_time_from = (isset($addon_det['block_time_from']) && $addon_det['block_time_from']) ? date('H:i:s',strtotime($addon_det['block_time_from'])) : '';
                $block_time_to = (isset($addon_det['block_time_to']) && $addon_det['block_time_to']) ? date('H:i:s',strtotime($addon_det['block_time_to'])) : '';
                ?>
                <div>
                    <select class="selectpicker selectpickerstart" id="start_lock_time">
                    <?php 
                    $min = 0;
                    while ($min < 1440) {
                        if ($min == 1440) {
                            $timeValue = date('G:i', mktime(0, $min - 1, 0, 1, 1, 2015));
                        } else {
                            $timeValue = date('G:i', mktime(0, $min, 0, 1, 1, 2015));
                        }
                        $timetoprint = date('G:i', mktime(0, $min, 0, 1, 1, 2014)); ?>
                        <option <?php  if ($block_time_from == date("H:i:s", strtotime($timeValue))) {
                            echo "selected";
                        } elseif("10:00:00" == date("H:i:s", strtotime($timeValue))){ echo "selected";}?>
                            value="<?php echo date("H:i:s", strtotime($timeValue)); ?>">
                            <?php 
                                echo date("H:i", strtotime($timetoprint));
                            ?>
                        </option>
                        <?php 
                        $min = $min + 60;
                    }
                    ?>
                    </select>
                    <div class="ct-staff-hours-to"> <?php  echo 'to';?> </div>
                    <select class="selectpicker selectpickerend" id="end_lock_time">
                    <?php 
                    $min = 0;
                    while ($min < 1440) {
                        if ($min == 1440) {
                            $timeValue = date('G:i', mktime(0, $min - 1, 0, 1, 1, 2015));
                        } else {
                            $timeValue = date('G:i', mktime(0, $min, 0, 1, 1, 2015));
                        }
                        $timetoprint = date('G:i', mktime(0, $min, 0, 1, 1, 2014)); ?>
                        <option <?php  if ($block_time_to == date("H:i:s", strtotime($timeValue))) {
                            echo "selected";
                        } elseif("20:00:00" == date("H:i:s", strtotime($timeValue))){ echo "selected";}?>
                            value="<?php echo date("H:i:s", strtotime($timeValue)); ?>">
                            <?php 
                                echo date("H:i", strtotime($timetoprint));
                            ?>
                        </option>
                        <?php 
                        $min = $min + 60;
                    }
                    ?>
                    </select>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="clearfix"></div>
<?php } elseif (isset($_POST['newdatelock'])){ ?>
    <?php
        $addon_id = $_POST['addon_id'];
        if (!$addon_id) {
            return;
        }
        $expiry_lock_date = $_POST['expiry_lock_date'];
        $start_lock_time = $_POST['start_lock_time'];
        $end_lock_time = $_POST['end_lock_time'];
        $weekday = $_POST['weekday'];
        if ($weekday == 'all') {
            $expiry_lock_date_to = $_POST['expiry_lock_date_to'];
            if (!$expiry_lock_date_to) {
                $expiry_lock_date_to = $_POST['expiry_lock_date'];
            }
            $res = $objservice_addon->getdataby_id($addon_id);
            foreach ($res as $value) {
                $addon_det = $value;
            };
            $objservice_addon->block_date = $expiry_lock_date;
            $objservice_addon->block_date_to = $expiry_lock_date_to;
            $objservice_addon->block_time_from = $start_lock_time;
            $objservice_addon->block_time_to = $end_lock_time;
            $objservice_addon->id = $addon_id;
            $objservice_addon->update_services_addon_time_lock();
        } else {
            $res = $addonlookday->getdataby_id($addon_id, $weekday);
            foreach ($res as $value) {
                $addon_det = $value;
            };
            $addonlookday->addon_id = $addon_id;
            $addonlookday->weekday = $weekday;
            $addonlookday->block_date = $expiry_lock_date;
            $addonlookday->block_time_from = $start_lock_time;
            $addonlookday->block_time_to = $end_lock_time;
            if (isset($addon_det['id']) && $addon_det['id']) {
                $addonlookday->id = $addon_det['id'];
                $addonlookday->update_starttime();
            } else {
                $addonlookday->insert_addonlookday();
            }
        }?>
        <?php if ($addon_id) { ?>
        <div class="date_set">
            <div class="date">
                <i class="fa fa-calendar mt-2"></i>
                <span class="starttime"><?= str_replace($english_date_array,$selected_lang_label,date($getdateformat, strtotime($expiry_lock_date))); ?></span> <?php if ($weekday == 'all' && strtotime($expiry_lock_date) < strtotime($expiry_lock_date_to)) { ?>to <span class="starttime"><?= str_replace($english_date_array,$selected_lang_label,date($getdateformat, strtotime($expiry_lock_date_to))); ?></span> <?php } ?>
            </div>
            <div class="time">
                <i class="fa fa-clock-o mt-2"></i><span class="start_time"><?= str_replace($english_date_array,$selected_lang_label,date("h:i A",strtotime($start_lock_time))) ?></span> to <span class="end_time"><?= str_replace($english_date_array,$selected_lang_label,date("h:i A",strtotime($end_lock_time))) ?></span>
            </div>
        </div>
        <?php } ?>
<?php } elseif (isset($_POST['removedatelock'])){ ?>
    <?php
        $addon_id = $_POST['addon_id'];
        $weekday = $_POST['weekday'];
        if ($weekday == 'all') {
            $res = $objservice_addon->getdataby_id($addon_id);
            foreach ($res as $value) {
                $addon_det = $value;
            };
            $objservice_addon->block_date = '';
            $objservice_addon->block_date_to = '';
            $objservice_addon->block_time_from = '';
            $objservice_addon->block_time_to = '';
            $objservice_addon->id = $addon_id;
            $objservice_addon->update_services_addon_time_lock();
        } else {
            $res = $addonlookday->getdataby_id($addon_id, $weekday);
            foreach ($res as $value) {
                $addon_det = $value;
            };
            if (count($res) > 0) {
                $addonlookday->id = $addon_det['id'];
                $addonlookday->delete_addonlookday();
            }
        }
        
    ?>
<?php } ?>