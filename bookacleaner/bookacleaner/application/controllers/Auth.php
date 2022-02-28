<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Manila");

class Auth extends CI_Controller {
    public function index() {
        json_output(
            400,
            array(
                "status" => 400,
                "message" => 'Bad Request'
            )
        );
    }
    public function login() {
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $email = $params['email'];
                $selection = $params['selection'];

                $response = $this->auth_model->login($email, $selection);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function registration() {
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $first_name = $params['first_name'];
                $middle_name = $params['middle_name'];
                $last_name = $params['last_name'];
                $email = $params['email'];
                $phone_number = $params['phone_number'];
                $address = $params['address'];

                $response = $this->auth_model->registration($first_name, $middle_name, $last_name, $email, $phone_number, $address);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function check_otp() {
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $id = $params['id'];
                $selection = $params['selection'];
                $code = $params['code'];

                $response = $this->auth_model->check_otp($id, $selection, $code);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function milestone() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $id = $params['id'] == "" ? 0 : $params['id'];
                $parent_id = $params['parent_id'];
                $response = $this->auth_model->milestone($id, $parent_id);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function doneThis() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;


                $type = $params['type'];
                $id   = $params['id'];
                $response = $this->auth_model->doneThis($type,$id);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function consultations() {
        header('Content-Type: application/json');

        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $id = $params['id'] == "" ? 0 : $params['id'];
                $parent_id = $params['parent_id'];
                $response = $this->auth_model->consultations($id, $parent_id);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function appointments() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $id = $params['id'] == "" ? 0 : $params['id'];
                $parent_id = $params['parent_id'];
                $response = $this->auth_model->appointments($id, $parent_id);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function addAppointment() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {

            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $interview_id = $params['interview_id'];
                $appointment_parent_id = $params['appointment_parent_id'];
                $appointment_patient_id = $params['appointment_patient_id'];
                $appointment_timestamp = $params['appointment_timestamp'];
                $appointment_timestamp_end = $params['appointment_timestamp_end'];
                $appointment_datetime = $params['appointment_datetime'];
                $appointment_datetime_end = $params['appointment_datetime_end'];
                $money = $params['money'];
                $appointment_description = $params['appointment_description'];
                $response = $this->auth_model->addAppointment($interview_id, $appointment_parent_id, $appointment_patient_id, $appointment_timestamp, $appointment_timestamp_end, $appointment_datetime, $appointment_datetime_end, $money, $appointment_description);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function addConsultation() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $interview_id = $params['interview_id'];
                $consultation_parent_id = $params['consultation_parent_id'];
                $consultation_patient_id = $params['consultation_patient_id'];
                $date_consultation = $params['date_consultation'];
                $date_consultation_end = $params['date_consultation_end'];
                $date_consultation_datetime = $params['date_consultation_datetime'];
                $date_consultation_datetime_end = $params['date_consultation_datetime_end'];
                $reason = $params['reason'];
                $money = $params['money'];
                $choice = $params['choice'];
                $healthHistory = $params['healthHistory'];
                $anyMedication = $params['anyMedication'];
                $anyAllergies = $params['anyAllergies'];
                $response = $this->auth_model->addConsultation($interview_id, $consultation_parent_id,$consultation_patient_id,$date_consultation,$date_consultation_end,$date_consultation_datetime,$date_consultation_datetime_end,$reason,$money,$choice,$healthHistory, $anyMedication, $anyAllergies);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function postMilestone() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $milestone_image = $params['milestone_image'];
                $milestone_caption = $params['milestone_caption'];
                $parent_id = $params['parent_id'];
                $response = $this->auth_model->postMilestone($milestone_image, $milestone_caption, $parent_id);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function deleteMilestone() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $milestone_id = $params['milestone_id'];
                $parent_id = $params['parent_id'];
                $response = $this->auth_model->deleteMilestone($milestone_id, $parent_id);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function getAvailable() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $doctor_id = $params['doctor_id'];
                $response = $this->auth_model->getAvailable($doctor_id);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function refund() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $consultation_id = $params['consultation_id'];
                $array = array(
                    'consultation_status' => 'Refund',
                    'date_consultation' => '0',
                    'date_consultation_end' => '0'
                );
                $response = $this->auth_model->updateConsultation($array, $consultation_id);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function deleteAppointment() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $appointment_id = $params['appointment_id'];
                $array = array(
                    'appointment_status' => 'Cancelled',
                    'appointment_timestamp' => '0',
                    'appointment_timestamp_end' => '0'
                );
                $response = $this->auth_model->updateAppointment($array, $appointment_id);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function patientSatisfaction() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $type = $params['type'];
                $parent_id = $params['parent_id'];
                $response = $this->auth_model->patientSatisfaction($type, $parent_id);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function postPatientSatisfaction() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $answerOne = $params['answerOne'];
                $answerTwo = $params['answerTwo'];
                $answerThree = $params['answerThree'];
                $interview_id = $params['interview_id'];
                $type = $params['type'];
                $id = $params['id'];
                $response = $this->auth_model->postPatientSatisfaction($answerOne, $answerTwo, $answerThree, $interview_id, $type, $id);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function allTransactions() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $parent_id = $params['parent_id'];
                $id = $params['id'];
                $response = $this->auth_model->allTransactions($parent_id, $id);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function displayPatients() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $parent_id = $params['parent_id'];
                $response = $this->auth_model->displayPatients($parent_id);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function announcements() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $id = $params['id'] == "" ? 0 : $params['id'];
                $response = $this->auth_model->announcements($id);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function health_tips() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $id = $params['id'] == "" ? 0 : $params['id'];
                $response = $this->auth_model->health_tips($id);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function babyRecords() {
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $id = $params['id'];
                $patient_name = $params['patient_name'];
                $date = $params['date'];

                $response = $this->auth_model->babyRecords($id, $patient_name, $date);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function immunizationRecords() {
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $id = $params['id'];
                $vaccine = $params['vaccine'];
                $date = $params['date'];
                $patient_id = $params['patient_id'];

                $response = $this->auth_model->immunizationRecords($id, $patient_id, $vaccine, $date);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }

    public function staffAllTransactions() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $response = $this->auth_model->staffAllTransactions();
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }

    public function resend() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $id = $params['id'];
                $selection = $params['selection'];
                $response = $this->auth_model->resend($id, $selection);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }

    public function displayDateType() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $type = $params['type'];
                $parent_id = $params['parent_id'];
                $response = $this->auth_model->displayDateType($type, $parent_id);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }

    public function transaction_appointment() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $id        = ($params['id'] == "")        ? "" : $params['id'];
                $parent_id = ($params['parent_id'] == "") ? "" : $params['parent_id'];
                $filter    = ($params['filter'] == "")    ? "" : $params['filter'];
                $dates     = ($params['dates'] == "")     ? "" : $params['dates'];
                $doctor_id     = ($params['doctor_id'] == "")     ? "" : $params['doctor_id'];
                $response = $this->auth_model->transaction_appointment($id, $parent_id, $filter, $dates, $doctor_id);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function transaction_consultation() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $id        = ($params['id'] == "")        ? "" : $params['id'];
                $parent_id = ($params['parent_id'] == "") ? "" : $params['parent_id'];
                $filter    = ($params['filter'] == "")    ? "" : $params['filter'];
                $dates     = ($params['dates'] == "")     ? "" : $params['dates'];
                $doctor_id     = ($params['doctor_id'] == "")     ? "" : $params['doctor_id'];
                
                $response = $this->auth_model->transaction_consultation($id, $parent_id, $filter, $dates, $doctor_id);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function getDate() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                
                $type      = ($params['type']      == "") ? "" : $params['type'];
                $dates     = ($params['dates']     == "") ? "" : $params['dates'];
                $parent_id = ($params['parent_id'] == "") ? "" : $params['parent_id'];
                $filter    = ($params['filter']    == "") ? "" : $params['filter'];
                $response = $this->auth_model->getDate($type, $dates, $parent_id, $filter);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function terms() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                
                $type      = ($params['type']      == "") ? "" : $params['type'];
                $id        = ($params['id']        == "") ? "" : $params['id'];
                $response = $this->auth_model->terms($type, $id);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function addIllness() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                
                $type      = ($params['type']      == "") ? "" : $params['type'];
                $id        = ($params['id']        == "") ? "" : $params['id'];
                $terms_id  = ($params['terms_id']  == "") ? "" : $params['terms_id'];
                
                $response = $this->auth_model->addIllness($type, $id, $terms_id);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function deleteIllness() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                
                $type      = ($params['type']      == "") ? "" : $params['type'];
                $id        = ($params['id']        == "") ? "" : $params['id'];
                
                $response = $this->auth_model->deleteIllness($type, $id);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function updateGoogleLink() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                
                $id        = ($params['id']        == "") ? "" : $params['id'];
                $googlelink      = ($params['googlelink']      == "") ? "" : $params['googlelink'];

                $response = $this->auth_model->updateGoogleLink($id, $googlelink);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function approveThis() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;


                $type = $params['type'];
                $id   = $params['id'];
                $doctor_id   = $params['doctor_id'];
                $response = $this->auth_model->approveThis($type, $id, $doctor_id);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function cancelThis() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;


                $type = $params['type'];
                $id   = $params['id'];
                $response = $this->auth_model->approveThis($type, $id);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    
    public function displayDoctors() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $response = $this->auth_model->displayDoctors();
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function displayDoctorsAvailable() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $response = $this->auth_model->displayDoctorsAvailable();
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function displayAllPatients() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $response = $this->auth_model->displayAllPatients();
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function chooseDoctor() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                
                $id        = ($params['id']        == "") ? "" : $params['id'];
                $doctor_id      = ($params['doctor_id']      == "") ? "" : $params['doctor_id'];
                $type      = ($params['type']      == "") ? "" : $params['type'];

                $response = $this->auth_model->chooseDoctor($id, $doctor_id, $type);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function displayUsers() {
        header('Content-Type: application/json');
        
        $this->load->model('auth_model', null, true);

        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $search      = $params['search'];
                $id        = ($params['id']        == "") ? "" : $params['id'];
                $selection      = ($params['selection']      == "") ? "" : $params['selection'];

                $response = $this->auth_model->displayUsers($search, $id, $selection);
                $array = array();

                foreach ($response as $data) {
                    if($id == $data['id'] && $selection == $data['selection']) {

                    } else {
                        $array[] = array(
                            'count' => $data['count'],
                            'time' => $data['time'],
                            'msg' => $data['msg'],
                            'id' => (int) $data['id'],
                            'selection' => (int) $data['selection'],
                            'name' => $data['name'],
                            'last_chat' => $data['last_chat'],
                            'code' => $data['code'],
                            'profile_picture' => $data['profile_picture']
                        );
                    }
                }
                function sortTime($a, $b) {
                    $a = $a['time'];
                    $b = $b['time'];
                    if ($a == $b)
                      return 0;
                    return ($a < $b) ? 1 : -1;
                }
        
                usort($array, "sortTime");
                //
                echo json_encode($array);
                json_output($response['status'], $response);
            }
        }
    }
    public function addChat() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $id = $params['id'];
                $selection = $params['selection'];
                $id_to = $params['id_to'];
                $selection_to = $params['selection_to'];
                $response = $this->auth_model->addChat($id, $selection, $id_to, $selection_to);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function getChats() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $id = $params['id'];
                $code = $params['code'];
                $account_id = $params['account_id'];
                $account_selection = $params['account_selection'];
                $response = $this->auth_model->getChats($code, $id, $account_id, $account_selection);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function getPreviouslyChats() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $id = $params['id'];
                $code = $params['code'];
                $account_id = $params['account_id'];
                $account_selection = $params['account_selection'];
                $response = $this->auth_model->getPreviouslyChats($code, $id, $account_id, $account_selection);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function send() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;//$code, $id, $selection, $message
                $code = $params['code'];
                $id = $params['id'];
                $selection = $params['selection'];
                $message = $params['message'];
                $response = $this->auth_model->send($code, $id, $selection, $message);
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function vaccineList() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $response = $this->auth_model->vaccineList();
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function getDateImmunization() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $response = $this->auth_model->getDateImmunization();
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function displayDateBabyRecords() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $response = $this->auth_model->displayDateBabyRecords();
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function medical_certificates() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $parent_id = $params['parent_id'];
                $patient_id = $params['patient_id'];
                $date_of_consultation = $params['date_of_consultation'];
                $money = $params['money'];
                $purpose = $params['purpose'];
                $interview_id = $params['interview_id'];

                $response = $this->auth_model->medical_certificates(
                    $parent_id,
                    $patient_id,
                    strtotime($date_of_consultation),
                    date('m/d/Y', strtotime($date_of_consultation)),
                    $money,
                    $purpose,
                    $interview_id
                );
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function list_medical_certificates() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $parent_id = $params['parent_id'];
                $patient_id = $params['patient_id'];
                $id = $params['id'];
                $doctor_id = $params['doctor_id'];

                $response = $this->auth_model->list_medical_certificates(
                    $parent_id,
                    $patient_id,
                    $id,
                    $doctor_id
                );
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function addLaboratoryResults() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $parent_id = $params['parent_id'];
                $patient_id = $params['patient_id'];
                $date = $params['date'];
                $type_of_laboratory = $params['type_of_laboratory'];
                $response = $this->auth_model->addLaboratoryResults(
                    $parent_id,
                    $patient_id,
                    $date,
                    $type_of_laboratory
                );
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function laboratoryResults() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $parent_id = $params['parent_id'];
                $id = $params['id'];
                $response = $this->auth_model->laboratoryResults(
                    $parent_id,
                    $id
                );
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function deactivateParent() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $email = $params['email'];
                //update
                $this->db->query("UPDATE `parents_tbl` SET `verified` = 2 WHERE `parent_emailaddress` = '".$email."'");
                //select
                $search = $this->db->query("SELECT * FROM `parents_tbl`  WHERE `parent_emailaddress` = '".$email."'")->result_array();
                $this->auth_model->itexmo($search[0]['parent_phonenumber'], 'Your account has been deactivated.');
                $response = array(
                    'status' => 200,
                    'message' => 'Successfully deactivated.'
                );
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function activateParent() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $email = $params['email'];
                //update
                $this->db->query("UPDATE `parents_tbl` SET `verified` = 1 WHERE `parent_emailaddress` = '".$email."'");
                //select
                $search = $this->db->query("SELECT * FROM `parents_tbl`  WHERE `parent_emailaddress` = '".$email."'")->result_array();
                $this->auth_model->itexmo($search[0]['parent_phonenumber'], 'Your account has been activated.');
                $response = array(
                    'status' => 200,
                    'message' => 'Successfully activated.'
                );
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function sendCodeParentChange() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                $otps = mt_rand(000000,999999);
                $copyOtp = $otps;

                $email = $params['email'];
                //update
                $this->db->query("UPDATE `parents_tbl` SET `parent_sms_code_change` = '".$copyOtp."' WHERE `parent_emailaddress` = '".$email."'");
                //select
                $search = $this->db->query("SELECT * FROM `parents_tbl`  WHERE `parent_emailaddress` = '".$email."'")->result_array();
                $message = "Change Phone Number:\nCode: ".$copyOtp;
                $this->auth_model->itexmo($search[0]['parent_phonenumber'], $message);
                $response = array(
                    'status' => 200,
                    'message' => 'Successfully sent.'
                );
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
    public function checkCodeParentChange() {
        header('Content-Type: application/json');
        $this->load->model('auth_model', null, true);
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != "POST") {
            json_output(
                400,
                array(
                    "status" => 400,
                    "message" => 'Bad Request'
                )
            );
        } else {
            $checkAuth = $this->auth_model->check_auth();
            if($checkAuth == true) {
                $params = $_REQUEST;
                //9668429327
                $new_phonenumber = $params['new_phonenumber'];
                $email = $params['email'];
                $code = $params['code'];
                //select
                $search = $this->db->query("SELECT * FROM `parents_tbl`  WHERE `parent_emailaddress` = '".$email."'")->result_array();
                $response = array();
                if($search[0]['parent_sms_code_change'] == $code) {
                    $this->db->query("UPDATE `parents_tbl` SET `parent_phonenumber` = '".$new_phonenumber."', `parent_sms_code_change` = '@@@@@@@@SUCCESS@@@@@@@@' WHERE `parent_emailaddress` = '".$email."'");
                    $message = "Your phone number has been updated.";
                    $this->auth_model->itexmo($search[0]['parent_phonenumber'], $message);
                    $response = array(
                        'status' => 200,
                        'message' => 'Successfully changed but you must login again.'
                    );
                } else {
                    $response = array(
                        'status' => 201,
                        'message' => 'The code doesn\'t same.'
                    );
                }
                echo json_encode($response);
                json_output($response['status'], $response);
            }
        }
    }
}