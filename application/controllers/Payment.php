<?php 
    defined('BASEPATH') OR exit('No direct script access allowed'); 
    date_default_timezone_set("Asia/Manila");

    class Payment extends CI_controller{

        public function __construct(){
            parent::__construct();
            $this->load->helper('url');
            $this->load->model('payment_model',null,true);
        }

        public function payment(){
            $data = array (
                'title' => "Home",
                'items' => $this->payment_model->get_paylist()
            );
            $this->load->view('Home/Include/header', $data);
            $this->load->view('transaction');
        }

        public function view_payment(){
            $id = $this->uri->segment(3);
            $data = array(
                'title' => "Patient Transaction",
                'items' => $this->payment_model->get_payuser('payments_tbl', 'payments_id', $id)
            );
            $this->load->view('Home/Include/header', $data);
            $this->load->view('view_payment');
        }

        public function update_payment(){
            $id = $this->uri->segment(3);
            $data = [
                'payments_service'   =>  $this->input->post('service',true),
                'payments_status'   =>  $this->input->post('status',true),
                'payments_paid' =>  $this->input->post('paid',true)
            ];
            $this->payment_model->update_paystatus($data,$id);
            $this->index();
        }

        public function delete_payment(){
            $id = $this->uri->segment(3);
            $this->payment_model->delete_payuser($id);
            $this->index();
        }
    }
?>