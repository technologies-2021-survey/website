<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Payment_model extends CI_Model{
        
        public function get_paylist(){
            $q = $this->db->get("payments_tbl");
            return $q->result();
        }

        public function get_payuser($tableName, $colFilter, $param){
            $q = $this->db->get_where($tableName, [$colFilter => $param]);
            return $q -> result();
        }

        public function update_paystatus($status, $id){
            $this->db->update('payments_tbl',$status,['payments_id' => $id]);
        }

        public function delete_payuser($id){
            $this->db->delete('payments_tbl',['payments_id' => $id]);
        }

    }

?>