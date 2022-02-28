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
}
?>