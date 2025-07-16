<?php

class cleanto_addonlookday {

    public $id;
    public $block_time_to;
    public $block_time_from;
    public $block_date;
    public $weekday;
    public $addon_id;
    public $table_name = "ct_addon_look_day";
    public $conn;

    /* insert new off break */

    public function insert_addonlookday() {
        $query = "insert into `" . $this->table_name . "` (`id`,`addon_id`,`weekday`,`block_date`,`block_time_from`,`block_time_to`) values(NULL,'" . $this->addon_id . "','" . $this->weekday . "','" . $this->block_date . "','" . $this->block_time_from . "','" . $this->block_time_to . "')";
        $result = mysqli_query($this->conn, $query);
        $value = mysqli_insert_id($this->conn);
        return $value;
    }

    /* get one record of the last inserted id */

    public function getlastidrecord($id) {
        $query = "select * from `" . $this->table_name . "` where `id`=" . $id;
        $result = mysqli_query($this->conn, $query);
        $value = mysqli_fetch_row($result);
        return $value;
    }

    public function delete_addonlookday() {
        $query = "delete from `" . $this->table_name . "` where `id`='" . $this->id . "' ";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    public function update_starttime() {
        $query = "update `" . $this->table_name . "` set `block_date`='" . $this->block_date . "', `block_time_from`='" . $this->block_time_from . "', `block_time_to`='" . $this->block_time_to . "' where `id`='" . $this->id . "'";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    public function getdataby_id($addon_id, $weekday) {
        $query = "select * from `" . $this->table_name . "` where `addon_id` = '" . $addon_id . "' and `weekday` = '" . $weekday . "'";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    public function getdataby_selectdate($addon_id, $weekday) {
        $weekday = strtolower($weekday);
        switch($weekday) {
            case 'monday':
                $weekday = 1;
                break;
            case 'tuesday':
                $weekday = 2;
                break;
            case 'wednesday':
                $weekday = 3;
                break;
            case 'thursday':
                $weekday = 4;
                break;
            case 'friday':
                $weekday = 5;
                break;
            case 'saturday':
                $weekday = 6;
                break;
            default:
                $weekday = 7;
                break;
        }
        $query = "select * from `" . $this->table_name . "` where `addon_id` = '" . $addon_id . "' and `weekday` = '" . $weekday . "'";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }
}

?>