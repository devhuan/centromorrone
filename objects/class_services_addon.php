<?php

class cleanto_services_addon {

    public $id;
    public $service_id;
    public $addon_service_name;
    public $duration;
    public $base_price;
    public $maxqty;
    public $image;
    public $multipleqty;
    public $status;
    public $position;
    public $predefine_image;
    public $predefine_image_title;
    public $addon_service_description;
    public $units_id;
    public $tags;
    public $table_name = "ct_services_addon";
    public $table_name1 = "ct_booking_addons";
    public $conn;

    /* Function for Add service */

    public function add_services_addon() {
        $val=mysqli_real_escape_string($this->conn,$this->addon_service_description);
        $query = "insert into `" . $this->table_name . "` (`id`,`service_id`,`addon_service_name`,`base_price`,`maxqty`,`image`,`multipleqty`,`status`,`position`,`predefine_image`,`predefine_image_title`,`aduration`,`addon_service_description`,`tags`) values(NULL,'" . $this->service_id . "','" . $this->addon_service_name . "','" . $this->base_price . "','" . $this->maxqty . "','" . $this->image . "','" . $this->multipleqty . "','" . $this->status . "','0','" . $this->predefine_image . "','" . $this->predefine_image_title . "','" . $this->duration . "','" . $val . "','" . $this->tags . "')";
        $result = mysqli_query($this->conn, $query);
        $value = mysqli_insert_id($this->conn);
        return $value;
    }

    /* Function for Update service-Not Used in this */

    public function update_services_addon() {
        $val=mysqli_real_escape_string($this->conn,$this->addon_service_description);
        $query = "update `" . $this->table_name . "` set `addon_service_name`='" . $this->addon_service_name . "',`tags`='" . $this->tags . "',`base_price`='" . $this->base_price . "', `maxqty`='" . $this->maxqty . "', `image`='" . $this->image . "', `predefine_image`='" . $this->predefine_image . "', `predefine_image_title` ='" . $this->predefine_image_title . "',aduration='" . $this->duration . "',`addon_service_description`='" . $val . "' where `id`='" . $this->id . "'";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    public function update_services_addon_time_lock() {
        if (!$this->block_date && !$this->block_date_to) {
            $query = "update `" . $this->table_name . "` set `block_date`=NULL,`block_date_to`=NULL,`block_time_from`=NULL,`block_time_to`=NULL where `id`='" . $this->id . "'";
        } else {
            $query = "update `" . $this->table_name . "` set `block_date`='" . $this->block_date . "',`block_date_to`='" . $this->block_date_to . "',`block_time_from`='" . $this->block_time_from . "',`block_time_to`='" . $this->block_time_to . "' where `id`='" . $this->id . "'";
        }        
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    /* Function for Delete service */

    public function delete_services_addon() {
        $query = "delete from `" . $this->table_name . "` where `id`='" . $this->id . "' ";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    /* Function to change the status of the table */
    /* send the alternate status id to update ex send e for enalbe and d for disable */

    public function changestatus() {
        $query = "update `" . $this->table_name . "` set `status`='" . $this->status . "' where `id`='" . $this->id . "'";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    /* send the alternate status id to update ex send e for enalbe and d for disable */

    public function changemultiple_qty() {
        $query = "update `" . $this->table_name . "` set `multipleqty`='" . $this->multipleqty . "' where `id`='" . $this->id . "'";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    /* Function for Read Only one data matched with Id */

    public function readone() {
        $query = "select * from `" . $this->table_name . "` where `id`='" . $this->id . "'";
        $result = mysqli_query($this->conn, $query);
        $value = mysqli_fetch_array($result);
        return $value;
    }

    public function readone_export() {
        $query = "select * from `" . $this->table_name . "` where `id`='" . $this->id . "'";
        $result = mysqli_query($this->conn, $query);
        $value = mysqli_fetch_row($result);
        return $value;
    }

    /* function to get the design assigned to addons */

    public function get_setting_design_addons($title) {
        $query = "select `design` from `ct_setting_design` where `title`='" . $title . "'";
        $result = mysqli_query($this->conn, $query);
        $value = mysqli_fetch_row($result);
        return $value = isset($value[0]) ? $value[0] : '';
    }

    /* Function for Read All data from table by service id */

    public function getdataby_serviceid() {
        $query = "select * from `" . $this->table_name . "` where `service_id` = " . $this->service_id . " ORDER BY `position`";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }
    
    /* Function for Read All data from table by service id and have term in tag then output from autocomplete*/

    public function getdataby_serviceid_autocomplete($term, $frontend = '') {
        if ($frontend) {
            if ($frontend == '13') {
                $query = "select id as id, addon_service_name as label, addon_service_name as value from `" . $this->table_name . "` where `service_id` = " . $this->service_id . " and `status` = 'E'"
                . " and addon_service_name = '$term' ORDER BY `position`";
            } else {
                $query = "select id as id, addon_service_name as label, addon_service_name as value from `" . $this->table_name . "` where `service_id` = " . $this->service_id . " and `status` = 'E'"
                . " and tags like '%$term%' ORDER BY `position`";
            }
        } else {
            $query = "select id as id, addon_service_name as label, addon_service_name as value from `" . $this->table_name . "` where `service_id` = " . $this->service_id . " "
                . " and tags like '%$term%' ORDER BY `position`";
        }
        $result = mysqli_query($this->conn, $query);
        return $result;
    }
    
    /* Function for Read All data from row by id */

    public function getdataby_id($id) {
        $query = "select * from `" . $this->table_name . "` where `id` = $id";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    public function getdataby_name($name) {
        $query = "select * from `" . $this->table_name . "` where `addon_service_name` = '".$name."'";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    /* function to update the position of the services */

    public function updateposition() {
        $query = "update `" . $this->table_name . "` set `position`='" . $this->position . "' where `id`='" . $this->id . "' ";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    /* get last inserted record */

    public function getlast_record_insert() {
        $query = "select MAX(`id`) from `" . $this->table_name . "`";
        $result = mysqli_query($this->conn, $query);
        $value = mysqli_fetch_row($result);

        return $value = isset($value[0]) ? $value[0] : '';
    }

    public function count_addon_serviceid() {
        $query = "select count(`id`) as `count_addon` from `" . $this->table_name . "` where `service_id` = " . $this->service_id;
        $result = mysqli_query($this->conn, $query);
        $val = mysqli_fetch_row($result);
        return $val;
    }

    /* changed by and working properly (use to display data in popup of export page ) */

    public function display_booking_addons($order_id) {
        $query = "select * from `ct_booking_addons` where `order_id`='" . $order_id . "'";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    public function readall_from_service() {
        $query = "select * from `" . $this->table_name . "` where `service_id`='" . $this->service_id . "' and `status`='E' and `maxqty` > 0 order by `position`";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    /* Update Image in services Addons */

    public function update_image() {
        $query = "update `" . $this->table_name . "` set `image`='" . $this->image . "' where `id`='" . $this->id . "'";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    /* Check for the bookings of the services */

    public function addons_isin_use($id) {
        $query = "select * from `ct_booking_addons` where `ct_booking_addons`.`addons_service_id` = $id  LIMIT 1";
        $result = mysqli_query($this->conn, $query);
        $value = mysqli_fetch_row($result);
        return $value = isset($value[0]) ? $value[0] : '';
    }

    /* check for the entry of the same title */

    public function check_same_title() {
        $query = "select * from `" . $this->table_name . "` where `service_id` = '" . $this->service_id . "' and `addon_service_name`='" . ucwords($this->addon_service_name) . "'";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    /* Addon's Details with Order id */

    public function addon_readall() {
        $query = "select * from `" . $this->table_name1 . "` where `order_id`='" . $this->order_id . "'";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    /* Function for Read Only one data matched with Id */

    public function readone_single() {
        $query = "select * from `" . $this->table_name . "` where `id`='" . $this->id . "'";
        $result = mysqli_query($this->conn, $query);
        $value = mysqli_fetch_row($result);
        return $value;
    }

    public function get_duration_value() {
        $query = "SELECT `aduration` FROM `" . $this->table_name . "` WHERE `id`='" . $this->units_id . "'";
        $result = mysqli_query($this->conn, $query);
        $value = mysqli_fetch_assoc($result);
        /* return $value['aduration']; */
        if (isset($value['aduration'])) {
            return $value['aduration'];
        } else {
            return "";
        }
    }

}

?>