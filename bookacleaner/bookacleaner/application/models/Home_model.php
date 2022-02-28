<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {
    public function status($status = "", $message = "") {
		$array = array(
			'status' => $status,
			'message' => $message
		);
		return json_encode($array);
	}
    public function insertBook($array) {
        $this->db->insert('bookings', $array);
    }
    public function insertBookServiceRequired($array) {
        $this->db->insert('bookings_service_required', $array);
    }
    public function getBookId($uniq_id) {
        $q = $this->db->query("SELECT * FROM `bookings` WHERE `unique_id` = '".$uniq_id."'"); 
		foreach($q->result() as $row) { 
            return $row->id;
		}
    }
}
?>