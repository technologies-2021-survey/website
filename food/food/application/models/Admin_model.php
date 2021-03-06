<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {
    public function login($username, $password) {
        $this->db->where('username', $username);
        $query = $this->db->get('accounts');

        if($query->num_rows() > 0) {
            // success
            foreach($query->result() as $row) { // row of this data
                if($row->password == md5($password)) {
                    $this->session->set_userdata('id', $row->id); // setup session
                    return 'Success';
                } else {
                    return 'Error! Wrong password!';
                }
            }
        } else {
            // error
            return 'Error! That user is not exist!';
        }
    }

    public function status($status = "", $message = "") {
		$array = array(
			'status' => $status,
			'message' => $message
		);
		return json_encode($array);
	}

    public function session() {
        if($this->session->userdata('id')) {
			return 1;
		} else {
            return 0;
        }
    }
    public function user($type = "") {
        $this->db->where('id', $this->session->userdata('id'));
        $query = $this->db->get('accounts');
        foreach($query->result() as $row) {
            if($type == "") {
                return $row->id;
            } else if($type == "username") {
                return $row->username;
            }
        }
    }
    public function updateDineIn($id, $data) {
        $this->db->where('id', $id);
		$this->db->update('tables_dine_in', $data);
    }
    public function updateDineIn2($id, $data) {
        $this->db->where('table_id', $id);
        $this->db->where('status', 'Eating');
		$this->db->update('tables_dine_in', $data);
    }
    public function updateTable($id, $data) {
        $this->db->where('id', $id);
		$this->db->update('tables', $data);
    }
    public function deleteMenu($id) {
        $this->db->query("DELETE FROM `menu` WHERE `id` = '".$id."'");
    }
    public function addMenu($data) {
		$this->db->insert('menu', $data);
        return 'Success';
    }
    public function checkUsername($username, $table) {
        $this->db->where('username', $username);
        $result = $this->db->get($table);

        if($result->num_rows() > 0){
            /*
            * the email already exists
            */
            return false;
        }else{
            return true;
        }
    }
    public function addAccount($data) {
		$this->db->insert('accounts', $data);
        return 'Success';
    }
}
?>