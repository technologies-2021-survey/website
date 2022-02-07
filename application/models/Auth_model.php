<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {
    var $client_service = "whealth-client";
    var $auth_key = "peekabookWhealthApi!@";

    public function check_auth() {
        $client_service = $this->input->get_request_header("Client-Service", TRUE);
        $auth_key = $this->input->get_request_header("Auth-Key", TRUE);

        if($client_service == $this->client_service && $auth_key == $this->auth_key) {
            return true;
        } else {
            return json_output(
                401,
                array(
                    'status' => 401,
                    'message' => 'Unauthorized.'
                )
            );
        }
    }
    public function itexmo($number, $message){
        $ch = curl_init();
        $itexmo = array('1' => $number, '2' => $message, '3' => 'ST-MICHA378601_38DKZ', 'passwd' => ')2hcz6prbn');
        curl_setopt($ch, CURLOPT_URL,"https://www.itexmo.com/php_api/api.php");
        curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_POSTFIELDS, 
                  http_build_query($itexmo));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return curl_exec ($ch);
        curl_close ($ch);
    }
    public function login($email, $selection) {
        $q = "";
        $role = 0;
        if($selection == "doctor") { $role = 1;$q = $this->db->select('doctor_phonenumber as phonenumber, doctor_sms_code_mobile, doctor_id, profile_picture')->from("doctors_tbl")->where(array('doctor_emailaddress'=> $email))->get()->row(); }
        else if($selection == "administrator") { $role = 2; $q = $this->db->select('admin_phonenumber as phonenumber, admin_sms_code_mobile, admin_id, profile_picture')->from("admins_tbl")->where(array('admin_emailaddress'=> $email))->get()->row(); }
        else if($selection == "receptionist") { $role = 3; $q = $this->db->select('receptionist_phonenumber as phonenumber, receptionist_sms_code_mobile, receptionist_id, profile_picture')->from("receptionists_tbl")->where(array('receptionist_emailaddress' => $email))->get()->row(); }
        else if($selection == "parent") { $role = 4; $q = $this->db->select('parent_phonenumber as phonenumber, parent_sms_code_mobile, parent_id, profile_picture, verified')->from("parents_tbl")->where(array('parent_emailaddress' => $email))->get()->row(); }

        if($q == "") {
            return array(
                'status' => 204,
                'message' => "Email not found."
            );
        } else {            

            $data_id = "";
            if($role == 1) { $data_id = $q->doctor_id; }
            else if($role == 2) { $data_id = $q->admin_id; }
            else if($role == 3) { $data_id = $q->receptionist_id; }
            else if($role == 4) { $data_id = $q->parent_id;  }

            $otps = mt_rand(000000,999999);
            $copyOtp = $otps;
            $this->auth_model->itexmo($q->phonenumber, $copyOtp . ' is your OTP. Please enter this to confirm your login.');
            if($role == 1) { 
                $this->db->set('doctor_sms_code_mobile', $copyOtp);
                $this->db->set('active', '1');
                $this->db->set('login_timeout', time()+36000); // 10 hours
                $this->db->where('doctor_id', $data_id);
                $this->db->update('doctors_tbl');
            } else if($role == 2) { 
                $this->db->set('admin_sms_code_mobile', $copyOtp);
                $this->db->set('active', '1');
                $this->db->set('login_timeout', time()+36000); // 10 hours
                $this->db->where('admin_id', $data_id);
                $this->db->update('admins_tbl');
            } else if($role == 3) { 
                $this->db->set('receptionist_sms_code_mobile', $copyOtp);
                $this->db->set('active', '1');
                $this->db->set('login_timeout', time()+36000); // 10 hours
                $this->db->where('receptionist_id', $data_id);
                $this->db->update('receptionists_tbl');
            } else if($role == 4) { 
                if($q->verified == 1) {
                    $this->db->set('parent_sms_code_mobile', $copyOtp);
                    $this->db->set('active', '1');
                    $this->db->set('login_timeout', time()+36000); // 10 hours
                    $this->db->where('parent_id', $data_id);
                    $this->db->update('parents_tbl');
                }
            }
            if($role == 4) { 
                if($q->verified == 1) {
                    return array(
                        'status' => 200,
                        'message' => 'Successfully login!',
                        'id' => $data_id,
                        'selection' => $role,
                        'name' => $this->auth_model->getName($data_id, $role),
                        'otp' => $copyOtp,
                        'phonenumber' => $q->phonenumber,
                        'profile_picture' => (base64_encode($q->profile_picture) != "") ? base64_encode($q->profile_picture) : 'aHR0cHM6Ly9wZWVrYWJvb2sudGVjaC9hc3NldHMvaW1nL3doZWFsdGgucG5n'
                    );
                } else if($q->verified == 2) {
                    return array(
                        'status' => 201,
                        'message' => 'This account is deactivated. You want to activate?',
                        'id' => $data_id,
                        'selection' => $role,
                        'name' => $this->auth_model->getName($data_id, $role),
                        //'otp' => $copyOtp,
                        //'profile_picture' => (base64_encode($q->profile_picture) != "") ? base64_encode($q->profile_picture) : 'aHR0cHM6Ly9wZWVrYWJvb2sudGVjaC9hc3NldHMvaW1nL3doZWFsdGgucG5n'
                    );
                } else if($q->verified == 0) {
                    return array(
                        'status' => 202,
                        'message' => 'This account has not approved yet by the administrator',
                        'id' => $data_id,
                        'selection' => $role,
                        'name' => $this->auth_model->getName($data_id, $role),
                        //'otp' => $copyOtp,
                        //'profile_picture' => (base64_encode($q->profile_picture) != "") ? base64_encode($q->profile_picture) : 'aHR0cHM6Ly9wZWVrYWJvb2sudGVjaC9hc3NldHMvaW1nL3doZWFsdGgucG5n'
                    );
                }
            } else {
                return array(
                    'status' => 200,
                    'message' => 'Successfully login!',
                    'id' => $data_id,
                    'selection' => $role,
                    'name' => $this->auth_model->getName($data_id, $role),
                    'otp' => $copyOtp,
                    'profile_picture' => (base64_encode($q->profile_picture) != "") ? base64_encode($q->profile_picture) : 'aHR0cHM6Ly9wZWVrYWJvb2sudGVjaC9hc3NldHMvaW1nL3doZWFsdGgucG5n'
                );
            }
        }
    } // done, login

    public function resend($id, $selection) {
        $q = "";
        $role = 0;
        if($selection == 1) { $role = 1;$q = $this->db->select('doctor_phonenumber as phonenumber, doctor_sms_code_mobile, doctor_id, profile_picture')->from("doctors_tbl")->where(array('doctor_id'=> $id))->get()->row(); }
        else if($selection == 2) { $role = 2; $q = $this->db->select('admin_phonenumber as phonenumber, admin_sms_code_mobile, admin_id, profile_picture')->from("admins_tbl")->where(array('admin_id'=> $id))->get()->row(); }
        else if($selection == 3) { $role = 3; $q = $this->db->select('receptionist_phonenumber as phonenumber, receptionist_sms_code_mobile, receptionist_id, profile_picture')->from("receptionists_tbl")->where(array('receptionist_id' => $id))->get()->row(); }
        else if($selection == 4) { $role = 4; $q = $this->db->select('parent_phonenumber as phonenumber, parent_sms_code_mobile, parent_id, profile_picture')->from("parents_tbl")->where(array('parent_id' => $id, 'verified' => 1))->get()->row(); }

        if($q == "") {
            return array(
                'status' => 204,
                'message' => "Email not found or your account has not approved yet by admins."
            );
        } else {            

            $data_id = "";
            if($role == 1) { $data_id = $q->doctor_id; }
            else if($role == 2) { $data_id = $q->admin_id; }
            else if($role == 3) { $data_id = $q->receptionist_id; }
            else if($role == 4) { $data_id = $q->parent_id;  }

            $otps = mt_rand(000000,999999);
            $copyOtp = $otps;
            $this->auth_model->itexmo($q->phonenumber, $copyOtp . ' is your OTP. Please enter this to confirm your login.');
            if($role == 1) { 
                $this->db->set('doctor_sms_code_mobile', $copyOtp);
                $this->db->set('active', '1');
                $this->db->set('login_timeout', time()+36000); // 10 hours
                $this->db->where('doctor_id', $data_id);
                $this->db->update('doctors_tbl');
            } else if($role == 2) { 
                $this->db->set('admin_sms_code_mobile', $copyOtp);
                $this->db->set('active', '1');
                $this->db->set('login_timeout', time()+36000); // 10 hours
                $this->db->where('admin_id', $data_id);
                $this->db->update('admins_tbl');
            } else if($role == 3) { 
                $this->db->set('receptionist_sms_code_mobile', $copyOtp);
                $this->db->set('active', '1');
                $this->db->set('login_timeout', time()+36000); // 10 hours
                $this->db->where('receptionist_id', $data_id);
                $this->db->update('receptionists_tbl');
            } else if($role == 4) { 
                $this->db->set('parent_sms_code_mobile', $copyOtp);
                $this->db->set('active', '1');
                $this->db->set('login_timeout', time()+36000); // 10 hours
                $this->db->where('parent_id', $data_id);
                $this->db->update('parents_tbl');
            }
            $asd = '';
            return array(
                'status' => 200,
                'message' => 'Successfully login!',
                'id' => $data_id,
                'selection' => $role,
                'name' => $this->auth_model->getName($data_id, $role),
                'otp' => $copyOtp,
                'profile_picture' => (base64_encode($q->profile_picture) != "") ? base64_encode($q->profile_picture) : 'aHR0cHM6Ly9wZWVrYWJvb2sudGVjaC9hc3NldHMvaW1nL3doZWFsdGgucG5n'
            );
        }
    } // done, login

    public function registration($first_name, $middle_name, $last_name, $email, $phone_number, $address) {
        $qasd = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_emailaddress` = '".$email."'");
        if($qasd->num_rows() > 0) {
            // exist
            return array(
                'status' => 404,
                'message' => 'Error, email existing!'
            );
        }

        $qasd2 = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_phonenumber` = '".$phone_number."'");
        if($qasd2->num_rows() > 0) {
            // exist
            return array(
                'status' => 404,
                'message' => 'Error, phone number existing!'
            );
        }

        $array = array(
            "parent_name" => ucfirst($first_name) . ' ' . ucfirst($middle_name) . ' ' . ucfirst($last_name),
            "parent_emailaddress" => $email,
            "parent_phonenumber" => $phone_number,
            "parent_address" => $address
        );

        $this->db->insert('parents_tbl', $array);

        return array(
            'status' => 200,
            'message' => 'Successfully register!'
        );
    }
    public function check_otp($id, $selection, $code) {
        if($selection == 1) { 
            $getCode = $this->db->query("SELECT * FROM doctors_tbl WHERE doctor_id = '".$id."' AND doctor_sms_code_mobile = '".$code."'");
            if($getCode->num_rows() > 0) {
                // exist
                $this->db->set('doctor_sms_code_mobile', "@@@@@@@@SUCCESS@@@@@@@@");
                $this->db->set('active', '1');
                $this->db->set('login_timeout', time()+36000); // 10 hours
                $this->db->where('doctor_id', $id);
                $this->db->update('doctors_tbl');

                return array(
                    'status' => 200,
                    'message' => 'Successfully login!',
                );
            } else {
                // not exists
                return array(
                    'status' => 204,
                    'message' => 'Invalid code!',
                );
            }
        } else if($selection == 2) { 
            $getCode = $this->db->query("SELECT * FROM admins_tbl WHERE admin_id = '".$id."' AND admin_sms_code_mobile = '".$code."'");
            if($getCode->num_rows() > 0) {
                $this->db->set('admin_sms_code_mobile', "@@@@@@@@SUCCESS@@@@@@@@");
                $this->db->set('active', '1');
                $this->db->set('login_timeout', time()+36000); // 10 hours
                $this->db->where('admin_id', $id);
                $this->db->update('admins_tbl');

                return array(
                    'status' => 200,
                    'message' => 'Successfully login!',
                );
            } else {
                return array(
                    'status' => 204,
                    'message' => 'Invalid code!',
                );
            }
        } else if($selection == 3) { 
            $getCode = $this->db->query("SELECT * FROM receptionists_tbl WHERE receptionist_id = '".$id."' AND receptionist_sms_code_mobile = '".$code."'");
            if($getCode->num_rows() > 0) {
                $this->db->set('receptionist_sms_code_mobile', "@@@@@@@@SUCCESS@@@@@@@@");
                $this->db->set('active', '1');
                $this->db->set('login_timeout', time()+36000); // 10 hours
                $this->db->where('receptionist_id', $id);
                $this->db->update('receptionists_tbl');

                return array(
                    'status' => 200,
                    'message' => 'Successfully login!',
                );
            } else {
                return array(
                    'status' => 204,
                    'message' => 'Invalid code!',
                );
            }
        } else if($selection == 4) { 
            $getCode = $this->db->query("SELECT * FROM parents_tbl WHERE parent_id = '".$id."' AND parent_sms_code_mobile = '".$code."'");
            if($getCode->num_rows() > 0) {
                $this->db->set('parent_sms_code_mobile', "@@@@@@@@SUCCESS@@@@@@@@");
                $this->db->set('active', '1');
                $this->db->set('login_timeout', time()+36000); // 10 hours
                $this->db->where('parent_id', $id);
                $this->db->update('parents_tbl');

                return array(
                    'status' => 200,
                    'message' => 'Successfully login!',
                );
            } else {
                return array(
                    'status' => 204,
                    'message' => 'Invalid code!',
                );
            }
        } else {
            return array(
                'status' => 204,
                'message' => 'Invalid code!',
            );
        }
    } 
    public function getName($id, $selection) {
        if($selection == 1) {
            $q = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$id."'"); 
            foreach($q->result() as $row) { 
                return $row->doctor_name;
            }
        } else if($selection == 2) {
            $q = $this->db->query("SELECT * FROM `admins_tbl` WHERE `admin_id` = '".$id."'"); 
            foreach($q->result() as $row) { 
                return $row->admin_name;
            }
        } else if($selection == 3) {
            $q = $this->db->query("SELECT * FROM `receptionists_tbl` WHERE `receptionist_id` = '".$id."'"); 
            foreach($q->result() as $row) { 
                return $row->receptionist_name;
            }
        } else if($selection == 4) {
            $q = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$id."'"); 
            foreach($q->result() as $row) { 
                return $row->parent_name;
            }
        } else if($selection == 5) {
            $q = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$id."'"); 
            foreach($q->result() as $row) { 
                return $row->patient_name;
            }
        }
        
    }
    public function milestone($id = 0, $parent_id) {
        $record_per_page = 5;
        $page = "";
        if(isset($id)) {
            $page = $id;
        } else {
            $page = 1;
        }

        $start_from = ($page-1) * $record_per_page;
        
        $q = $this->db->query("SELECT * FROM `milestone_tbl` WHERE `parent_id` = '".$parent_id."' ORDER BY `milestone_id` DESC LIMIT $start_from, $record_per_page");		

        $arr = array();

        if($q->num_rows() > 0) {
            // existing
            // success
            foreach($q->result_array() as $row) { 

                $arr[] = array(
                    'status' => 200,
                    'milestone_id' => $row['milestone_id'],
                    'milestone_caption' => $row['milestone_caption'],
                    'milestone_image' => $row['milestone_image'],
                    'milestone_datetime' => $row['milestone_datetime'],
                    'parent_id' => $row['parent_id']
                );
            }
            return $arr;
        } else {
            return array(
                'status' => 204,
                'message' => "Milestones not found."
            );
        }
    }
    public function addAppointment($interview_id, $appointment_parent_id, $appointment_patient_id, $appointment_timestamp, $appointment_timestamp_end, $appointment_datetime, $appointment_datetime_end, $money, $appointment_description) {
        
        if(strlen($interview_id) == 0) {
            return array(
                'status' => 204,
                'message' => "Doctor not found"
            );
        } else if(strlen($appointment_patient_id) == 0) {
            return array(
                'status' => 204,
                'message' => "Appointment Patient ID not found"
            );
        } else if(strlen($appointment_parent_id) == 0) {
            return array(
                'status' => 204,
                'message' => "Appointment Parent ID not found"
            );
        } else if(strlen($appointment_timestamp) == 0) {
            return array(
                'status' => 204,
                'message' => "Appointment Timestamp not found"
            );
        } else if(strlen($appointment_timestamp_end) == 0) {
            return array(
                'status' => 204,
                'message' => "Appointment Timestamp End not found"
            );
        } else if(strlen($appointment_description) == 0) {
            return array(
                'status' => 204,
                'message' => "Appointment Description End not found"
            );
        }  else if(strlen($appointment_datetime) == 0) {
            return array(
                'status' => 204,
                'message' => "Appointment Date Time not found"
            );
        } else if(strlen($appointment_datetime_end) == 0) {
            return array(
                'status' => 204,
                'message' => "Appointment Date Time End not found"
            );
        } else if(strlen($money) == 0) {
            return array(
                'status' => 204,
                'message' => "Money not found"
            );
        } else {
            $count = 0;
            /*
            $checkAppointment = $this->db->query("SELECT * FROM `appointments` WHERE `appointment_datetime` = '".$appointment_datetime."'");
            if($checkAppointment->num_rows() > 0) {
                // exist
                $count += 1;
            }
            $checkConsultation = $this->db->query("SELECT * FROM `consultations` WHERE `date_consultation_datetime` = '".$appointment_datetime."'");
            if($checkConsultation->num_rows() > 0) {
                // exist
                $count += 1;
            }*/

            if($count == 0) {
                $references = strtoupper(substr(md5(rand(0,100000)+rand(100000,999999)), 0, 12));
                $final_references = $references;

                $array = array(
                    'interview_id' => $interview_id,
                    'appointment_parent_id' => $appointment_parent_id,
                    'appointment_patient_id' => $appointment_patient_id,
                    'appointment_timestamp' => $appointment_timestamp,
                    'appointment_timestamp_end' => $appointment_timestamp_end,
                    'date_text' => date('m/d/Y', $appointment_timestamp),
                    'appointment_timestamp_sub' => $appointment_timestamp,
                    'appointment_timestamp_sub_end' => $appointment_timestamp_end,
                    'appointment_description' => $appointment_description,
                    'appointment_datetime' => $appointment_datetime,
                    'appointment_datetime_end' => $appointment_datetime_end,
                    'money' => $money,
                    'reference_number' => $final_references,
                    'appointment_status' => 'Approved'
                );
                $this->db->insert('appointments', $array);
            
                //parent
                $p = $this->db->where('parent_id', $appointment_parent_id)->get("parents_tbl")->result_array();
                $p2 = $this->db->where('patient_id', $appointment_patient_id)->get("patients_tbl")->result_array();
                //doctor
                $dc = $this->db->where('doctor_id', $interview_id)->get("doctors_tbl")->result_array();
                
                
                //$message = "Your consultation request for ".$p2[0]['patient_name']." has been approved!\n\nDate start: ".$date_consultation_datetime."\nDate end: ".$date_consultation_datetime_end+"\nTransaction #: ".$final_references;
                //$message2 = "Your consultation request for ".$p2[0]['patient_name']." has been approved!\n\nDate start: ".$date_consultation_datetime."\nDate end: ".$date_consultation_datetime_end;
                
                $message = "Hello, ".$p[0]['parent_name'];
                $message .= "!\n\nYour appointment request for ".$p2[0]['patient_name'];
                $message .= " has been approved!\n\n";
                $message .= "Date start: ".$appointment_datetime;
                $message .= "\nDate end: ".$appointment_datetime_end;
                $message .= "\nTransaction ID: ".$final_references;

                $message2 = "Hello, ".$dc[0]['doctor_name'];
                $message2 .= "!\n\nYou have a scheduled appointment for ".$p2[0]['patient_name'];
                $message2 .= "\n\n";
                $message2 .= "Date start: ".$appointment_datetime;
                $message2 .= "\nDate end: ".$appointment_datetime_end;
                $this->auth_model->itexmo($p[0]['parent_phonenumber'], $message);
		        $this->auth_model->itexmo($dc[0]['doctor_phonenumber'], $message2);

                return array(
                    'status' => 200,
                    'message' => "Successfully!"
                );
            } else {
                return array(
                    'status' => 204,
                    'message' => "This slot has been reserved."
                );
            }
        }
    }
    public function consultations($id = 0, $parent_id) {
        $record_per_page = 10;
        $page = "";
        if(isset($id)) {
            $page = $id;
        } else {
            $page = 1;
        }

        $start_from = ($page-1) * $record_per_page;
        
        $qasd = $this->db->query("SELECT * FROM `consultations` WHERE `consultation_parent_id` = '".$parent_id."' ORDER BY `consultation_id` DESC LIMIT $start_from, $record_per_page");		

        $arr = array();

        if($qasd->num_rows() > 0) {
            // existing
            // success
            //return $parent_id;
            
            foreach($qasd->result_array() as $row) { 
                $getParent = $this->db->query("SELECT * FROM parents_tbl WHERE parent_id = '".$row['consultation_parent_id']."'")->result_array();
                $getPatient = $this->db->query("SELECT * FROM patients_tbl WHERE patient_id = '".$row['consultation_patient_id']."'")->result_array();


                $name = "";
                if($row['consultation_approve_selection'] == 1) {
                    $getApproveName = $this->db->query("SELECT * FROM doctors_tbl WHERE doctor_id = '".$row['consultation_approve_by']."'")->result_array();
                    $name = $getApproveName[0]['doctor_name'];
                } else if($row['consultation_approve_selection'] == 2) {
                    $getApproveName = $this->db->query("SELECT * FROM admins_tbl WHERE admin_id = '".$row['consultation_approve_by']."'")->result_array();
                    $name = $getApproveName[0]['admin_name'];
                } else if($row['consultation_approve_selection'] == 3) {
                    $getApproveName = $this->db->query("SELECT * FROM receptionists_tbl WHERE receptionist_id = '".$row['consultation_approve_by']."'")->result_array();
                    $name = $getApproveName[0]['receptionist_name'];
                }

                $arr[] = array(
                    'status' => 200,
                    'send_it_to_parent' => $row['send_it_to_parent'],
                    'consultation_id'                   => $row['consultation_id'],
                    'consultation_parent_id'            => $row['consultation_parent_id'],
                    'consultation_parent_name'          => $getParent[0]['parent_name'],
                    'consultation_patient_id'           => $row['consultation_patient_id'],
                    'consultation_patient_name'         => $getPatient[0]['patient_name'],
                    'date_consultation'                 => $row['date_consultation'],
                    'date_consultation_end'             => $row['date_consultation_end'],
                    'date_consultation_datetime'        => $row['date_consultation_datetime'],
                    'date_consultation_datetime_end'    => $row['date_consultation_datetime_end'],
                    'reason'                            => $row['reason'],
                    'consultation_prescription'         => $row['consultation_prescription'],
                    'googlelink'                        => $row['googlelink'],
                    'consultation_status'               => $row['consultation_status'],
                    'money'                             => $row['money'],
                    'survey_done'                       => $row['survey_done'],
                    'consultation_by'                   => $name,
                    'reference_number'                  => $row['reference_number'],
                    'approve_by' => $row['consultation_approve_by'],
                    'approve_selection' => $row['consultation_approve_selection'],
                    'healthHistory'        => $row['healthHistory'],
                    'anyMedication'        => $row['anyMedication'],
                    'anyAllergies'         => $row['anyAllergies'],
                    'pdf'                  => md5($row['date_consultation_datetime'] . ' ' . $row['date_consultation_datetime_end'])
                );
            }
            return $arr;
        } else {
            return array(
                'status' => 204,
                'message' => "Consultations not found."
            );
        }
    }   
    public function appointments($id = 0, $parent_id) {
        $record_per_page = 10;
        $page = "";
        if(isset($id)) {
            $page = $id;
        } else {
            $page = 1;
        }

        $start_from = ($page-1) * $record_per_page;
        
        $q = $this->db->query("SELECT * FROM `appointments` WHERE `appointment_parent_id` = '".$parent_id."' ORDER BY `appointment_id` DESC LIMIT $start_from, $record_per_page");		

        $arr = array();

        if($q->num_rows() > 0) {
            // existing
            // success
            foreach($q->result_array() as $row) { 

                $arr[] = array(
                    'status' => 200,
                    'appointment_id' => $row['appointment_id'],
                    'appointment_parent_id' => $row['appointment_parent_id'],
                    'appointment_timestamp' => $row['appointment_timestamp'],
                    'appointment_timestamp_end' => $row['appointment_timestamp_end'],
                    'appointment_description' => $row['appointment_description'],
                    'appointment_datetime' => $row['appointment_datetime'],
                    'appointment_datetime_end' => $row['appointment_datetime_end'],
                    'money' => $row['money'],
                    'survey_done' => $row['survey_done'],
                    'appointment_status' => $row['appointment_status'],
                    'reference_number' => $row['reference_number'],
                    'approve_by' => $row['appointment_approve_by'],
                    'approve_selection' => $row['appointment_approve_selection']
                );
            }
            return $arr;
        } else {
            return array(
                'status' => 204,
                'message' => "Appointments not found."
            );
        }
    }
    public function addConsultation($interview_id, $consultation_parent_id, $consultation_patient_id, $date_consultation, $date_consultation_end,$date_consultation_datetime, $date_consultation_datetime_end, $reason, $money, $choice, $healthHistory, $anyMedication, $anyAllergies) {
        if(strlen($interview_id) == 0) {
            return array(
                'status' => 204,
                'message' => "Doctor not found"
            );
        } else if(strlen($consultation_parent_id) == 0) {
            return array(
                'status' => 204,
                'message' => "Consultation Parent ID not found"
            );
        } else if(strlen($consultation_patient_id) == 0) {
            return array(
                'status' => 204,
                'message' => "Consultation Patient ID not found"
            );
        } else if(strlen($date_consultation) == 0) {
            return array(
                'status' => 204,
                'message' => "Date not found"
            );
        } else if(strlen($date_consultation_end) == 0) {
            return array(
                'status' => 204,
                'message' => "Date end not found"
            );
        } else if(strlen($date_consultation_datetime) == 0) {
            return array(
                'status' => 204,
                'message' => "Date string not found"
            );
        } else if(strlen($date_consultation_datetime_end) == 0) {
            return array(
                'status' => 204,
                'message' => "Date end string ID not found"
            );
        } else if(strlen($reason) == 0) {
            return array(
                'status' => 204,
                'message' => "Reason not found"
            );
        } else if(strlen($money) == 0) {
            return array(
                'status' => 204,
                'message' => "Money not found"
            );
        } else if(strlen($healthHistory) == 0) {
            return array(
                'status' => 204,
                'message' => "Health History not found"
            );
        } else if(strlen($anyMedication) == 0) {
            return array(
                'status' => 204,
                'message' => "Any Medication not found"
            );
        } else if(strlen($anyAllergies) == 0) {
            return array(
                'status' => 204,
                'message' => "Any Allergies not found"
            );
        } else {
            $count = 0;

            if($count == 0) {
                $references = strtoupper(substr(md5(rand(0,100000)+rand(100000,999999)), 0, 12));
                $final_references = $references;
                
                $array = array(
                    'interview_id' => $interview_id,
                    'consultation_parent_id' => $consultation_parent_id,
                    'consultation_patient_id' => $consultation_patient_id,
                    'date_consultation' => $date_consultation,
                    'date_consultation_end' => $date_consultation_end,
                    'date_text' => date('m/d/Y', $date_consultation),
                    'date_consultation_sub' => $date_consultation,
                    'date_consultation_sub_end' => $date_consultation_end,
                    'date_consultation_datetime' => $date_consultation_datetime,
                    'date_consultation_datetime_end' => $date_consultation_datetime_end,
                    'reason' => $reason,
                    'money' => $money,
                    'choice' => $choice,
                    'healthHistory' => $healthHistory,
                    'anyMedication' => $anyMedication,
                    'anyAllergies' => $anyAllergies,
                    'consultation_status' => 'Approved',
                    'reference_number' => $final_references
                );
                $this->db->insert('consultations', $array);
                
                //parent
                $p = $this->db->where('parent_id', $consultation_parent_id)->get("parents_tbl")->result_array();
                $p2 = $this->db->where('patient_id', $consultation_patient_id)->get("patients_tbl")->result_array();
                //doctor
                $dc = $this->db->where('doctor_id', $interview_id)->get("doctors_tbl")->result_array();
                
                
                //$message = "Your consultation request for ".$p2[0]['patient_name']." has been approved!\n\nDate start: ".$date_consultation_datetime."\nDate end: ".$date_consultation_datetime_end+"\nTransaction #: ".$final_references;
                //$message2 = "Your consultation request for ".$p2[0]['patient_name']." has been approved!\n\nDate start: ".$date_consultation_datetime."\nDate end: ".$date_consultation_datetime_end;
                
                $message = "Hello, ".$p[0]['parent_name'];
                $message .= "!\n\nYour consultation request for ".$p2[0]['patient_name'];
                $message .= " has been approved!\n\n";
                $message .= "Date start: ".$date_consultation_datetime;
                $message .= "\nDate end: ".$date_consultation_datetime_end;
                $message .= "\nTransaction ID: ".$final_references;

                $message2 = "Hello, ".$dc[0]['doctor_name'];
                $message2 .= "!\n\nYou have a scheduled online consultation for ".$p2[0]['patient_name'];
                $message2 .= "\n\n";
                $message2 .= "Date start: ".$date_consultation_datetime;
                $message2 .= "\nDate end: ".$date_consultation_datetime_end;
                $this->auth_model->itexmo($p[0]['parent_phonenumber'], $message);
		        $this->auth_model->itexmo($dc[0]['doctor_phonenumber'], $message2);
                
                return array(
                    'status' => 200,
                    'message' => "Successfully!",
                    'transaction_id' => $final_references
                    //'patient' => $p2[0]['patient_name'],
                    //'parent' => $p[0]['parent_name'],
                    //'parent phone number' => $p[0]['parent_phonenumber'],
                    //'doctor' => $dc[0]['doctor_name'],
                    //'doctor phone number' => $dc[0]['doctor_phonenumber'],
                );
            } else {
                return array(
                    'status' => 204,
                    'message' => "This slot has been reserved."
                );
            }
        }
    }

    public function deleteMilestone($milestone_id, $parent_id){
        if(strlen($milestone_id) == 0 && strlen($parent_id) == 0) {
            return array(
                'status' => 204,
                'message' => "Milestone ID and Parent ID not found."
            );
        } else {
            $this->db->where('milestone_id', $milestone_id);
            $this->db->where('parent_id', $parent_id);
            $this->db->delete('milestone_tbl');
            return array(
                'status' => 200,
                'message' => "Successfully!"
            );
        }
    }

    public function postMilestone($milestone_image, $milestone_caption, $parent_id) {
        if(strlen($milestone_image) == 0 && strlen($milestone_caption) == 0 && strlen($parent_id) == 0) {
            return array(
                'status' => 204,
                'message' => "Milestone Image, Milestone Caption and Parent ID not found."
            );
        } else if(strlen($milestone_image) == 0 && strlen($milestone_caption) == 0) {
            return array(
                'status' => 204,
                'message' => "Milestone Image and Milestone Caption and Parent ID not found."
            );
        } else {
            $array = array(
                'milestone_image' => (strlen($milestone_image == 0)) ? "" : $milestone_image,
                'milestone_caption' => (strlen($milestone_caption == 0)) ? "" : $milestone_caption,
                'parent_id' => $parent_id,
                'milestone_datetime' => time()
            );
            $this->db->insert('milestone_tbl', $array);
            return array(
                'status' => 200,
                'message' => "Successfully!"
            );
        }
    }

    public function getAvailable($doctor_id) {
        date_default_timezone_set("Asia/Manila");

        $array = array('','','','','','','');
    
        $count = 0;
    
        // get in database
        $getDoctorQuery2 = $this->db->query("SELECT * FROM `doctor_schedule` WHERE `doctor_id` = '".$doctor_id."' AND `doctor_status` = 'Active' GROUP BY `doctor_schedule_day`");
        $s = 0;
        foreach($getDoctorQuery2->result_array() as $getDoctorRow2) {
            $array[$s] = array($getDoctorRow2['doctor_schedule_day']);
            $count++;
            $s++;
        }
    
        if($count == 0) {
            return array(
                    "status" => 204,
                    "message"=>"No results found."
                );
        } else {
            // counts all array
            $countsHehe = 0;
            $countsAllArray = 0;
            for($sx = 0; $sx < 7; $sx++) {
                if(!empty($array[$countsHehe][0])) {
                    $countsAllArray++;
                }
                $countsHehe++;
            }
            $time = time();
            $getTime = $time;
            $dates = "";
            $arrays = array();
            for($i = 0; $i < 7; $i++) { // 1week
                $dates = date("M d, Y h:iA", strtotime(date("M d, Y", $getTime) . " 09:00AM"));
                for($s = 0; $s < $countsAllArray; $s++) { // per day
                    
                    for($xi = 0; $xi < 19; $xi++) {
                        $exist = 0;
                        $checkDoctorSchedQuery = $this->db->query("SELECT * FROM `doctor_schedule` WHERE `doctor_schedule_day` = '".date("l", strtotime($dates))."' and `doctor_schedule_start_time` = '".date("h:iA", strtotime($dates))."' and `doctor_schedule_end_time` = '".date("h:iA", strtotime($dates.' + 30 minutes'))."' AND `doctor_id` = '".$doctor_id."'  AND `doctor_status` = 'Active' ");
                        if($checkDoctorSchedQuery->num_rows() > 0) {
                            $getAppointmentCheckQuery = $this->db->query("SELECT * FROM `appointments` WHERE `appointment_datetime` = '".date("m/d/Y H:i:00", strtotime($dates))."' AND `appointment_datetime_end` = '".date("m/d/Y H:i:00", strtotime($dates . " + 30 minutes"))."' AND `interview_id` = '".$doctor_id."'");
                            $getConsultationCheckQuery = $this->db->query("SELECT * FROM `consultations` WHERE `date_consultation_datetime` = '".date("m/d/Y H:i:00", strtotime($dates))."' AND `date_consultation_datetime_end` = '".date("m/d/Y H:i:00", strtotime($dates . " + 30 minutes"))."' AND `interview_id` = '".$doctor_id."'");
                            if($getAppointmentCheckQuery->num_rows() > 0) {
                                $exist += 1;
                            }
                            if($getConsultationCheckQuery->num_rows() > 0) {
                                $exist += 1;
                            }
                            if($exist == 0) {
                                if(array_search( (string) strtotime($dates), array_column($arrays, 'timestamp')) == "") {
                                    $arrays[] = array(
                                        'time' => "".date("m/d/Y h:iA", strtotime($dates))." to ".date("h:iA", strtotime($dates . " + 30 minutes")),
                                        'timestamp' => strtotime($dates),
                                        'timestamp_end' => strtotime($dates . " + 30 minutes"),
                                        'datetime' => date("m/d/Y h:iA", strtotime($dates)),
                                        'datetime_end' => date("m/d/Y h:iA", strtotime($dates . " + 30 minutes"))
                                    );
                                }
                            }
                        }
                        $dates = date("M d, Y h:iA", strtotime($dates . " + 30 minutes")); 
                    }
                }
                $dates = date("M d, Y", strtotime($dates . ' + 1 day'));
                $getTime = strtotime('+ 1 day', $getTime);
                
            }
    
            return $arrays;
        }
    }

    public function getAvailableConsultation() {
        date_default_timezone_set("Asia/Manila");

        $start_time = 9;
        $count = 0;
        
        $data = array();
        for($i = 0; $i < 6; $i++) {
            $timestamp_number = 0;
            $timestamp_number_end = 0;

            $timestamp = 0;
            $timestamp_end = 0;
            if($count >= 1) {
                $start_time2 = strtotime(''.$start_time.':00'); // time start

                $timestamp = date("m/d/Y h:i:sA", strtotime('+30 minutes', $start_time2));
                $timestamp_number = strtotime('+30 minutes', $start_time2);

                $timestamp_end = date("m/d/Y h:i:sA", strtotime('+60 minutes', $start_time2));
                $timestamp_number_end = strtotime('+60 minutes', $start_time2);

                $count = 0;
                $start_time+=1;
            } else {
                $start_time2 = strtotime(''.$start_time.':00'); // time start
                $timestamp = date("m/d/Y h:i:sA", strtotime('+0 minutes', $start_time2));
                $timestamp_number = strtotime('+0 minutes', $start_time2);
                $timestamp_end = date("m/d/Y h:i:sA", strtotime('+30 minutes', $start_time2));
                $timestamp_number_end = strtotime('+30 minutes', $start_time2);
                $count += 1;
            }

            $q = $this->db->query("SELECT * FROM `consultations` WHERE date_consultation = $timestamp_number AND date_consultation_end = $timestamp_number_end")->num_rows();

            if($q > 0) {
                // exist
            } else {
                $data[] = array(
                    'time' => $timestamp . ' to ' . $timestamp_end,
                    'timestamp' => $timestamp_number,
                    'timestamp_end' => $timestamp_number_end,
                    'datetime' => date("m/d/Y h:i:sA", $timestamp_number),
                    'datetime_end' => date("m/d/Y h:i:sA", $timestamp_number_end)
                );
            }
        }

        $start_time2 = 9;
        $count2 = 0;
        for($s = 0; $s < 6; $s++) {
            $timestamp_number = 0;
            $timestamp_number_end = 0;

            $timestamp = 0;
            $timestamp_end = 0;

            $dateRightNow = date("Y-m-d", time());
            $getDateNow = date("Y-m-d", strtotime((string) $dateRightNow. ' + 1 days'));
            if($count2 >= 1) {
                $start_time22 = strtotime($getDateNow . ' '.$start_time2.':00'); // time start

                $timestamp = date("m/d/Y h:i:sA", strtotime('+30 minutes', $start_time22));
                $timestamp_number = strtotime('+30 minutes', $start_time22);

                $timestamp_end = date("m/d/Y h:i:sA", strtotime('+60 minutes', $start_time22));
                $timestamp_number_end = strtotime('+60 minutes', $start_time22);

                $count2 = 0;
                $start_time2+=1;
            } else {
                $start_time22 = strtotime($getDateNow . ' '.$start_time2.':00'); // time start
                $timestamp = date("m/d/Y h:i:sA", strtotime('+0 minutes', $start_time22));
                $timestamp_number = strtotime('+0 minutes', $start_time22);
                $timestamp_end = date("m/d/Y h:i:sA", strtotime('+30 minutes', $start_time22));
                $timestamp_number_end = strtotime('+30 minutes', $start_time22);
                $count2 += 1;
            }

            $q = $this->db->query("SELECT * FROM `consultations` WHERE date_consultation = $timestamp_number AND date_consultation_end = $timestamp_number_end")->num_rows();

            if($q > 0) {
                // exist
            } else {
                $data[] = array(
                    'time' => $timestamp . ' to ' . $timestamp_end,
                    'timestamp' => $timestamp_number,
                    'timestamp_end' => $timestamp_number_end,
                    'datetime' => date("m/d/Y h:i:sA", $timestamp_number),
                    'datetime_end' => date("m/d/Y h:i:sA", $timestamp_number_end)
                );
            }
        }

        $start_time3 = 9;
        $count3 = 0;
        for($h = 0; $h < 6; $h++) {
            $timestamp_number = 0;
            $timestamp_number_end = 0;

            $timestamp = 0;
            $timestamp_end = 0;

            $dateRightNow = date("Y-m-d", time());
            $getDateNow = date("Y-m-d", strtotime((string) $dateRightNow. ' + 2 days'));
            if($count3 >= 1) {
                $start_time33 = strtotime($getDateNow . ' '.$start_time3.':00'); // time start

                $timestamp = date("m/d/Y h:i:sA", strtotime('+30 minutes', $start_time33));
                $timestamp_number = strtotime('+30 minutes', $start_time33);

                $timestamp_end = date("m/d/Y h:i:sA", strtotime('+60 minutes', $start_time33));
                $timestamp_number_end = strtotime('+60 minutes', $start_time33);

                $count3 = 0;
                $start_time3+=1;
            } else {
                $start_time33 = strtotime($getDateNow . ' '.$start_time3.':00'); // time start
                $timestamp = date("m/d/Y h:i:sA", strtotime('+0 minutes', $start_time33));
                $timestamp_number = strtotime('+0 minutes', $start_time33);
                $timestamp_end = date("m/d/Y h:i:sA", strtotime('+30 minutes', $start_time33));
                $timestamp_number_end = strtotime('+30 minutes', $start_time33);
                $count3 += 1;
            }

            $q = $this->db->query("SELECT * FROM `consultations` WHERE date_consultation = $timestamp_number AND date_consultation_end = $timestamp_number_end")->num_rows();

            if($q > 0) {
                // exist
            } else {
                $data[] = array(
                    'time' => $timestamp . ' to ' . $timestamp_end,
                    'timestamp' => $timestamp_number,
                    'timestamp_end' => $timestamp_number_end,
                    'datetime' => date("m/d/Y h:i:sA", $timestamp_number),
                    'datetime_end' => date("m/d/Y h:i:sA", $timestamp_number_end)
                );
            }
        }

        $start_time4 = 9;
        $count4 = 0;
        for($sr = 0; $sr < 6; $sr++) {
            $timestamp_number = 0;
            $timestamp_number_end = 0;

            $timestamp = 0;
            $timestamp_end = 0;

            $dateRightNow = date("Y-m-d", time());
            $getDateNow = date("Y-m-d", strtotime((string) $dateRightNow. ' + 3 days'));
            if($count4 >= 1) {
                $start_time44 = strtotime($getDateNow . ' '.$start_time4.':00'); // time start

                $timestamp = date("m/d/Y h:i:sA", strtotime('+30 minutes', $start_time44));
                $timestamp_number = strtotime('+30 minutes', $start_time44);

                $timestamp_end = date("m/d/Y h:i:sA", strtotime('+60 minutes', $start_time44));
                $timestamp_number_end = strtotime('+60 minutes', $start_time44);

                $count4 = 0;
                $start_time4+=1;
            } else {
                $start_time44 = strtotime($getDateNow . ' '.$start_time4.':00'); // time start
                $timestamp = date("m/d/Y h:i:sA", strtotime('+0 minutes', $start_time44));
                $timestamp_number = strtotime('+0 minutes', $start_time44);
                $timestamp_end = date("m/d/Y h:i:sA", strtotime('+30 minutes', $start_time44));
                $timestamp_number_end = strtotime('+30 minutes', $start_time44);
                $count4 += 1;
            }

            $q = $this->db->query("SELECT * FROM `consultations` WHERE date_consultation = $timestamp_number AND date_consultation_end = $timestamp_number_end")->num_rows();

            if($q > 0) {
                // exist
            } else {
                $data[] = array(
                    'time' => $timestamp . ' to ' . $timestamp_end,
                    'timestamp' => $timestamp_number,
                    'timestamp_end' => $timestamp_number_end,
                    'datetime' => date("m/d/Y h:i:sA", $timestamp_number),
                    'datetime_end' => date("m/d/Y h:i:sA", $timestamp_number_end)
                );
            }
        }

        $start_time5 = 9;
        $count5 = 0;
        for($ssd = 0; $ssd < 6; $ssd++) {
            $timestamp_number = 0;
            $timestamp_number_end = 0;

            $timestamp = 0;
            $timestamp_end = 0;

            $dateRightNow = date("Y-m-d", time());
            $getDateNow = date("Y-m-d", strtotime((string) $dateRightNow. ' + 4 days'));
            if($count5 >= 1) {
                $start_time55 = strtotime($getDateNow . ' '.$start_time5.':00'); // time start

                $timestamp = date("m/d/Y h:i:sA", strtotime('+30 minutes', $start_time55));
                $timestamp_number = strtotime('+30 minutes', $start_time55);

                $timestamp_end = date("m/d/Y h:i:sA", strtotime('+60 minutes', $start_time55));
                $timestamp_number_end = strtotime('+60 minutes', $start_time55);

                $count5 = 0;
                $start_time5+=1;
            } else {
                $start_time55 = strtotime($getDateNow . ' '.$start_time5.':00'); // time start
                $timestamp = date("m/d/Y h:i:sA", strtotime('+0 minutes', $start_time55));
                $timestamp_number = strtotime('+0 minutes', $start_time55);
                $timestamp_end = date("m/d/Y h:i:sA", strtotime('+30 minutes', $start_time55));
                $timestamp_number_end = strtotime('+30 minutes', $start_time55);
                $count5 += 1;
            }

            $q = $this->db->query("SELECT * FROM `consultations` WHERE date_consultation = $timestamp_number AND date_consultation_end = $timestamp_number_end")->num_rows();

            if($q > 0) {
                // exist
            } else {
                $data[] = array(
                    'time' => $timestamp . ' to ' . $timestamp_end,
                    'timestamp' => $timestamp_number,
                    'timestamp_end' => $timestamp_number_end,
                    'datetime' => date("m/d/Y h:i:sA", $timestamp_number),
                    'datetime_end' => date("m/d/Y h:i:sA", $timestamp_number_end)
                );
            }
        }

        $start_time6 = 9;
        $count6 = 0;
        for($sds = 0; $sds < 6; $sds++) {
            $timestamp_number = 0;
            $timestamp_number_end = 0;

            $timestamp = 0;
            $timestamp_end = 0;

            $dateRightNow = date("Y-m-d", time());
            $getDateNow = date("Y-m-d", strtotime((string) $dateRightNow. ' + 5 days'));
            if($count6 >= 1) {
                $start_time66 = strtotime($getDateNow . ' '.$start_time6.':00'); // time start

                $timestamp = date("m/d/Y h:i:sA", strtotime('+30 minutes', $start_time66));
                $timestamp_number = strtotime('+30 minutes', $start_time66);

                $timestamp_end = date("m/d/Y h:i:sA", strtotime('+60 minutes', $start_time66));
                $timestamp_number_end = strtotime('+60 minutes', $start_time66);

                $count6 = 0;
                $start_time6+=1;
            } else {
                $start_time66 = strtotime($getDateNow . ' '.$start_time6.':00'); // time start
                $timestamp = date("m/d/Y h:i:sA", strtotime('+0 minutes', $start_time66));
                $timestamp_number = strtotime('+0 minutes', $start_time66);
                $timestamp_end = date("m/d/Y h:i:sA", strtotime('+30 minutes', $start_time66));
                $timestamp_number_end = strtotime('+30 minutes', $start_time66);
                $count6 += 1;
            }

            $q = $this->db->query("SELECT * FROM `consultations` WHERE date_consultation = $timestamp_number AND date_consultation_end = $timestamp_number_end")->num_rows();

            if($q > 0) {
                // exist
            } else {
                $data[] = array(
                    'time' => $timestamp . ' to ' . $timestamp_end,
                    'timestamp' => $timestamp_number,
                    'timestamp_end' => $timestamp_number_end,
                    'datetime' => date("m/d/Y h:i:sA", $timestamp_number),
                    'datetime_end' => date("m/d/Y h:i:sA", $timestamp_number_end)
                
                );
            }
        }

        $start_time7 = 9;
        $count7 = 0;
        for($ssdsd = 0; $ssdsd < 6; $ssdsd++) {
            $timestamp_number = 0;
            $timestamp_number_end = 0;

            $timestamp = 0;
            $timestamp_end = 0;

            $dateRightNow = date("Y-m-d", time());
            $getDateNow = date("Y-m-d", strtotime((string) $dateRightNow. ' + 6 days'));
            if($count7 >= 1) {
                $start_time77 = strtotime($getDateNow . ' '.$start_time7.':00'); // time start

                $timestamp = date("m/d/Y h:i:sA", strtotime('+30 minutes', $start_time77));
                $timestamp_number = strtotime('+30 minutes', $start_time77);

                $timestamp_end = date("m/d/Y h:i:sA", strtotime('+60 minutes', $start_time77));
                $timestamp_number_end = strtotime('+60 minutes', $start_time77);

                $count7 = 0;
                $start_time7+=1;
            } else {
                $start_time77 = strtotime($getDateNow . ' '.$start_time7.':00'); // time start
                $timestamp = date("m/d/Y h:i:sA", strtotime('+0 minutes', $start_time77));
                $timestamp_number = strtotime('+0 minutes', $start_time77);
                $timestamp_end = date("m/d/Y h:i:sA", strtotime('+30 minutes', $start_time77));
                $timestamp_number_end = strtotime('+30 minutes', $start_time77);
                $count7 += 1;
            }

            $q = $this->db->query("SELECT * FROM `consultations` WHERE date_consultation = $timestamp_number AND date_consultation_end = $timestamp_number_end")->num_rows();

            if($q > 0) {
                // exist
            } else {
                $data[] = array(
                    'time' => $timestamp . ' to ' . $timestamp_end,
                    'timestamp' => $timestamp_number,
                    'timestamp_end' => $timestamp_number_end,
                    'datetime' => date("m/d/Y h:i:sA", $timestamp_number),
                    'datetime_end' => date("m/d/Y h:i:sA", $timestamp_number_end)
                );
            }
        }
        return $data;
    }
    
    public function updateConsultation($data, $id) {
        $this->db->where('consultation_id', $id);
		$this->db->update('consultations', $data);
        return "Finished!";
    }

    public function updateAppointment($data, $id) {
        $this->db->where('appointment_id', $id);
		$this->db->update('appointments', $data);
        return "Finished!";
    }

    public function patientSatisfaction($type, $parent_id) {
        if($type == "appointments") {
            $q = $this->db->query("SELECT * FROM appointments_tbl WHERE appointment_parent_id = '".$parent_id."' AND survey_done ='' AND appointment_status = 'Finished'");
            $array = array();
            foreach($q->result_array() as $row) {
                $array[] = array(
                    'appointment_id' => $row['appointment_id']
                );
            }
            return $array;
        } else if($type == "consultations") {
            $q = $this->db->query("SELECT * FROM consultations WHERE consultation_parent_id = '".$parent_id."' AND survey_done ='' AND consultation_status = 'Finished'");
            $array = array();
            foreach($q->result_array() as $row) {
                $array[] = array(
                    'consultation_id' => $row['consultation_id']
                );
            }
            return $array;
        } else {
            return "Error";
        }
    }

    public function postPatientSatisfaction($answerOne, $answerTwo, $answerThree, $interview_id, $type, $id) {
        if($type == "appointments") {
            $array = array(
                'type' => 'appointments',
                'id' => $id,
                'answer_one' => $answerOne,
                'answer_two' => $answerTwo,
                'answer_three' => $answerThree,
                'interview_id' => $interview_id,
                'timestamp' => time()
            );
            $this->db->insert('surveys_tbl', $array);

            $this->db->query("UPDATE appointments SET survey_done = 1 WHERE appointment_id = '".$id."'");
            return "Done!";
        } else if($type == "consultations") {
            $array = array(
                'type' => 'consultations',
                'id' => $id,
                'answer_one' => $answerOne,
                'answer_two' => $answerTwo,
                'answer_three' => $answerThree,
                'interview_id' => $interview_id,
                'timestamp' => time()
            );
            $this->db->insert('surveys_tbl', $array);

            $this->db->query("UPDATE consultations SET survey_done = 1 WHERE consultation_id = '".$id."'");
            return "Done!";
        } else {
            return "Error";
        }
    }

    public function allTransactions($parent_id, $id = 0) {
        $record_per_page = 10;
        $page = "";
        if(isset($id)) {
            $page = $id;
        } else {
            $page = 1;
        }

        $start_from = ($page-1) * $record_per_page;
        $num_rowsss = 0;

        $q = $this->db->query("SELECT * FROM appointments WHERE appointment_parent_id = '".$parent_id."' ORDER BY `appointment_id` DESC LIMIT $start_from, $record_per_page");
		$array = array();
        $count = 1;

        if($q->num_rows() > 0) {
            foreach($q->result_array() as $row) {
                $array[] = array(
                    'overallId'            => $count,
                    'category'             => 'appointments',
                    'id'                   => $row['appointment_id'],
                    'parent_id'            => $row['appointment_parent_id'],
                    'patient_id'           => '',
                    'patient_name'         => '',
                    'timestamp'            => $row['appointment_timestamp'],
                    'timestamp_end'        => $row['appointment_timestamp_end'],
                    'prescription'         => '',
                    'description'          => $row['appointment_description'],
                    'datetime'             => $row['appointment_datetime'],
                    'datetime_end'         => $row['appointment_datetime_end'],
                    'reason'               => '', //
                    'approve_by'           => '', //
                    'approve_selection'    => '', //
                    'googlelink'           => '', //
                    'money'                => $row['money'],
                    'survey_done'          => $row['survey_done'],
                    'status'               => $row['appointment_status'],
                    'reference_number'     => $row['reference_number']
                );
                $count++;
            }
        } else {
            $num_rowsss++;
        }

        $q2 = $this->db->query("SELECT * FROM consultations WHERE consultation_parent_id = '".$parent_id."'  ORDER BY `consultation_id` DESC LIMIT $start_from, $record_per_page");

        if($q2->num_rows() > 0) {
            foreach($q2->result_array() as $row2) {
                $getPatient = $this->db->query("SELECT * FROM `patients_tbl` WHERE patient_id = '".$row2['consultation_patient_id']."'")->result_array();

                $array[] = array(
                    'overallId'            => $count,
                    'category'             => 'consultations', //
                    'id'                   => $row2['consultation_id'], //
                    'parent_id'            => $row2['consultation_parent_id'], //
                    'patient_id'           => $row2['consultation_patient_id'], //
                    'patient_name'         => $getPatient[0]['patient_name'], //
                    'timestamp'            => $row2['date_consultation'], //
                    'timestamp_end'        => $row2['date_consultation_end'], //
                    'prescription'         => $row2['consultation_prescription'], //
                    'description'          => '',
                    'datetime'             => $row2['date_consultation_datetime'], //
                    'datetime_end'         => $row2['date_consultation_datetime_end'], //
                    'reason'               => $row2['reason'], //
                    'approve_by'           => $row2['consultation_approve_by'], //
                    'approve_selection'    => $row2['consultation_approve_selection'], //
                    'googlelink'           => $row2['googlelink'], //
                    'money'                => $row2['money'], //
                    'survey_done'          => $row2['survey_done'], //
                    'status'               => $row2['consultation_status'], //
                    'reference_number'     => $row2['reference_number'],  
                    'healthHistory'        => $row2['healthHistory'],
                    'anyMedication'        => $row2['anyMedication'],
                    'anyAllergies'         => $row2['anyAllergies']
                );
                $count++;
            }
        } else {
            $num_rowsss++;
        }

        if($num_rowsss == 2) {
            return array(
                'status' => 204,
                'message' => "Transactions not found."
            );
        }

        return $array;
        
        
    }

    public function displayPatients($parent_id) {
        $q = $this->db->query("SELECT * FROM `patients_tbl` WHERE parent_id = '".$parent_id."'");
        if($q->num_rows() > 0) {
            //exist
            $array = array();
            foreach($q->result_array() as $row) {
                $array[] = array(
                    'patient_id' => $row['patient_id'],
                    'patient_name' => $row['patient_name'],
                    'patient_birthdate' => $row['patient_birthdate'],
                    'patient_gender' => $row['patient_gender']
                );
            }
            return $array;
        } else {
            return 'Error';
        }
        
    }
    public function displayDoctors() {
        $q = $this->db->query("SELECT * FROM `doctors_tbl`");
        if($q->num_rows() > 0) {
            //exist
            $array = array();
            foreach($q->result_array() as $row) {
                $array[] = array(
                    'doctor_id' => $row['doctor_id'],
                    'doctor_name' => $row['doctor_name']
                );
            }
            return $array;
        } else {
            return 'Error';
        }
        
    }
    public function displayDoctorsAvailable() {
        $q = $this->db->query("SELECT * FROM `doctors_tbl`");
        if($q->num_rows() > 0) {
            //exist
            $array = array();
            foreach($q->result_array() as $row) {
                $checkSched = $this->db->query("SELECT * FROM `doctor_schedule` WHERE `doctor_id` = '".$row['doctor_id']."'");
                if($checkSched->num_rows() > 0) {
                    $array[] = array(
                        'doctor_id' => $row['doctor_id'],
                        'doctor_name' => $row['doctor_name']
                    );
                }
            }
            return $array;
        } else {
            return 'Error';
        }
        
    }
    public function displayAllPatients() {
        $q = $this->db->query("SELECT * FROM `patients_tbl`");
        if($q->num_rows() > 0) {
            //exist
            $array = array();
            $array[] = array(
                'patient_id' => "",
                'patient_name' => "All"
            );
            foreach($q->result_array() as $row) {
                $array[] = array(
                    'patient_id' => $row['patient_id'],
                    'patient_name' => $row['patient_name']
                );
            }
            return $array;
        } else {
            return 'Error';
        }
        
    }

    public function announcements($id = 0) {
        $record_per_page = 10;
        $page = "";
        if(isset($id)) {
            $page = $id;
        } else {
            $page = 1;
        }

        $start_from = ($page-1) * $record_per_page;
        
        $q = $this->db->query("SELECT * FROM `announcement_tbl` ORDER BY `announcement_tbl_id` DESC LIMIT $start_from, $record_per_page");		

        $arr = array();

        if($q->num_rows() > 0) {
            // existing
            // success
            foreach($q->result_array() as $row) { 

                $arr[] = array(
                    'status' => 200,
                    'announcement_tbl_id' => $row['announcement_tbl_id'],
                    'announcement_tbl_date' => $row['announcement_tbl_date'],
                    'announcement_tbl_title' => $row['announcement_tbl_title'],
                    'announcement_tbl_content' => $row['announcement_tbl_content'],
                    'announcement_tbl_image' => ($row['announcement_tbl_image'] != "") ? "data:image/png;base64,".base64_encode($row['announcement_tbl_image']) : ""
                );
            }
            return $arr;
        } else {
            return array(
                'status' => 204,
                'message' => "Announcements not found."
            );
        }
    }
    public function health_tips($id = 0) {
        $record_per_page = 10;
        $page = "";
        if(isset($id)) {
            $page = $id;
        } else {
            $page = 1;
        }

        $start_from = ($page-1) * $record_per_page;
        
        $q = $this->db->query("SELECT * FROM `health_tips_tbl` ORDER BY `health_tips_id` DESC LIMIT $start_from, $record_per_page");		

        $arr = array();

        if($q->num_rows() == 0) {
            return array(
                'status' => 204,
                'message' => "Health Tips not found."
            );
        } else {
            // existing
            // success
            foreach($q->result_array() as $row) { 

                $arr[] = array(
                    'status' => 200,
                    'health_tips_id' => $row['health_tips_id'],
                    'health_tips_title' => $row['health_tips_title'],
                    'health_tips_link' => $row['health_tips_link']
                );
            }
            return $arr;
        }
    }
    public function babyRecords($id = 0, $patient_name, $date) {
        $record_per_page = 10;
        $page = "";
        if(isset($id)) {
            $page = $id;
        } else {
            $page = 1;
        }

        $start_from = ($page-1) * $record_per_page;
        $chck = 0;
        if($patient_name != "") {
            $chck += 1;
        }
        if($date != "") {
            $chck += 1;
        }
        $q = $this->db->query("SELECT * FROM `patients_history_tbl` ".($chck != 0 ? "WHERE " : "")." ".($patient_name != "" ? "`patient_name` LIKE '%".$patient_name."%'" : "")." ".($chck == 2 ? "AND " : "")." ".($date != "" ? "`date_text` = '".$date."'" : "")." ORDER BY `patients_history_id` DESC LIMIT $start_from, $record_per_page");		

        $arr = array();

        if($q->num_rows() > 0) {
            // existing
            // success
            foreach($q->result_array() as $row) { 
                $asd = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$row['patient_id']."'")->result_array();
                $arr[] = array(
                    'status' => 200,
                    'patients_history_id' => $row['patients_history_id'],
                    'patient_id' => $row['patient_id'],
                    'patient_name' => $asd[0]['patient_name'],
                    'date' => date("M d, Y", $row['patient_datetime']),
                    'patient_datetime' => $row['patient_datetime'],
                    'chiefComplaint' => $row['chiefComplaint'],
                    'medicalHistory' => $row['medicalHistory'],
                    
                    'pastMedicalHistory' => $row['pastMedicalHistory'],
                    'familyHistory' => $row['familyHistory'],
                    'birthHistory' => $row['birthHistory'],
                    'feedingHistory' => $row['feedingHistory'],
                    'immunization' => $row['immunization'],

                    'earAndBodyPiercing' => $row['earAndBodyPiercing'],
                    'circumcision' => $row['circumcision'],
                    'developmentalHistory' => $row['developmentalHistory'],
                    'bp' => $row['bp'],
                    'cr' => $row['cr'],

                    'rr' => $row['rr'],
                    'temp' => $row['temp'],
                    'O2Sat' => $row['O2Sat'],
                    'weight' => $row['weight'],
                    'Ht' => $row['Ht'],
                    
                    'hc' => $row['hc'],
                    'cc' => $row['cc'],
                    'ac' => $row['ac'],
                    'height' => $row['height'],
                    'skin' => $row['skin'],

                    'heent' => $row['heent'],
                    'thorax' => $row['thorax'],
                    'abdomen' => $row['abdomen'],
                    'genitourinarySystem' => $row['genitourinarySystem'],
                    'rectalExamination' => $row['rectalExamination'],

                    'extremities' => $row['extremities'],
                    'assessment' => $row['assessment'],
                    'lmp' => $row['lmp'],
                    'obstretrics' => $row['obstretrics'],
                    'Investigate' => $row['Investigate'],

                    'therapy' => $row['therapy']
                );
            }
            return $arr;
        } else {
            return array(
                'status' => 204,
                'message' => "Baby record not found."
            );
        }
    }
    public function displayDateBabyRecords() {

        $q = $this->db->query("SELECT * FROM `patients_history_tbl` GROUP BY `date_text` DESC");		

        $arr = array();

        $arr[] = array(
            'timestamp' => '',
            'date' => 'All'
        );
        foreach($q->result_array() as $row) {
            $arr[] = array(
                'timestamp' => $row['patient_datetime'],
                'date' => $row['date_text']
            );
        }
        return $arr;
    }
    public function immunizationRecords($id = 0, $patient_id, $vaccine = "", $date = "") {
        $record_per_page = 10;
        $page = "";
        if(isset($id)) {
            $page = $id;
        } else {
            $page = 1;
        }

        $start_from = ($page-1) * $record_per_page;

        $q = $this->db->query("SELECT * FROM `immunization_record` WHERE `patient_id` = '".$patient_id."' ".($vaccine != "" ? "AND `vaccine_id` = '".$vaccine."'" : "")." ".($date != "" ? "AND `date` = '".$date."'" : "")." ORDER BY `immunization_record_id` DESC LIMIT $start_from, $record_per_page");		

        $arr = array();

        if($q->num_rows() >= 1) {
            // existing
            // success
            foreach($q->result_array() as $row) {
                $asdasd = $this->db->query("SELECT * FROM `vaccine_terms_tbl` WHERE `vaccine_terms_id` = '".$row['vaccine_id']."'")->result_array();
                $arr[] = array(
                    'status' => 200,
                    'immunization_record_id' => $row['immunization_record_id'],
                    'patient_id' => $row['patient_id'],
                    'parent_id' => $row['parent_id'],
                    'date' => $row['date'],
                    'vaccine' => $asdasd[0]['vaccine_terms_title'],
                    'route' => $row['route'],
                    'age' => $row['age'],
                    'weight' => $row['weight'],
                    'length' => $row['length'],
                    'bmi' => $row['bmi'],
                    'head_circumference' => $row['head_circumference'],
                    'doctors_instruction' => $row['doctors_instruction'],
                    'comeback_on' => $row['comeback_on'],
                    'comeback_for' => $row['comeback_for'],
                    'timestamp' => $row['timestamp']
                );
                
            }
            return $arr;
        } else {
            return array(
                'status' => 204,
                'message' => "Immunization Record not found."
            );
        }
    }

    public function staffAllTransactions() {
		date_default_timezone_set("Asia/Manila");

        $num_rowsss = 0;
		$timestamp = strtotime(date("M d, Y 00:00:00", time()));
        $q = $this->db->query("SELECT * FROM appointments WHERE `appointment_timestamp` >= '".$timestamp."' AND appointment_status = 'Approved' ORDER BY `appointment_id`");
		$array = array();
        $count = 1;

        if($q->num_rows() > 0) {
            foreach($q->result_array() as $row) {
				$getParent = $this->db->query("SELECT * FROM `parents_tbl` WHERE parent_id = '".$row['appointment_parent_id']."'")->result_array();
                $getPatient = $this->db->query("SELECT * FROM `patients_tbl` WHERE patient_id = '".$row['appointment_patient_id']."'")->result_array();

                $array[] = array(
                    'overallId'            => $count,
                    'category'             => 'appointments',
                    'id'                   => $row['appointment_id'],
                    'parent_id'            => $row['appointment_parent_id'],
                    'parent_name'         => $getParent[0]['parent_name'],
                    'patient_id'           => $row['appointment_patient_id'],
                    'patient_name'         => $getPatient[0]['patient_name'],
                    'timestamp'            => $row['appointment_timestamp'],
                    'timestamp_end'        => $row['appointment_timestamp_end'],
                    'prescription'         => '',
                    'description'          => $row['appointment_description'],
                    'datetime'             => $row['appointment_datetime'],
                    'datetime_end'         => $row['appointment_datetime_end'],
                    'reason'               => '', //
                    'approve_by'           => '', //
                    'approve_selection'    => '', //
                    'googlelink'           => '', //
                    'money'                => $row['money'],
                    'survey_done'          => $row['survey_done'],
                    'status'               => $row['appointment_status'],
                    'reference_number'     => $row['reference_number']
                );
                $count++;
            }
        }

        $q2 = $this->db->query("SELECT * FROM consultations  WHERE `date_consultation` >= '".$timestamp."' AND consultation_status = 'Approved'ORDER BY `consultation_id`");

        if($q2->num_rows() > 0) {
            foreach($q2->result_array() as $row2) {
                $getParent = $this->db->query("SELECT * FROM `parents_tbl` WHERE parent_id = '".$row2['consultation_parent_id']."'")->result_array();
                $getPatient = $this->db->query("SELECT * FROM `patients_tbl` WHERE patient_id = '".$row2['consultation_patient_id']."'")->result_array();

                $array[] = array(
                    'overallId'            => $count,
                    'category'             => 'consultations', //
                    'id'                   => $row2['consultation_id'], //
                    'parent_id'            => $row2['consultation_parent_id'], //
                    'parent_name'         => $getParent[0]['parent_name'], //
                    'patient_id'           => $row2['consultation_patient_id'], //
                    'patient_name'         => $getPatient[0]['patient_name'], //
                    'timestamp'            => $row2['date_consultation'], //
                    'timestamp_end'        => $row2['date_consultation_end'], //
                    'prescription'         => $row2['consultation_prescription'], //
                    'description'          => '',
                    'datetime'             => $row2['date_consultation_datetime'], //
                    'datetime_end'         => $row2['date_consultation_datetime_end'], //
                    'reason'               => $row2['reason'], //
                    'approve_by'           => $row2['consultation_approve_by'], //
                    'approve_selection'    => $row2['consultation_approve_selection'], //
                    'googlelink'           => $row2['googlelink'], //
                    'money'                => $row2['money'], //
                    'survey_done'          => $row2['survey_done'], //
                    'status'               => $row2['consultation_status'], //
                    'reference_number'     => $row2['reference_number'],
                    'healthHistory'        => $row2['healthHistory'],
                    'anyMedication'        => $row2['anyMedication'],
                    'anyAllergies'         => $row2['anyAllergies']
                );
                $count++;
            }
        }

        function sortTime($a, $b) {
			$a = $a['timestamp'];
			$b = $b['timestamp'];
			if ($a == $b)
			  return 0;
			return ($a < $b) ? -1 : 1;
		}

		usort($array, "sortTime");
		return $array;

		/*
        if($num_rowsss == 0) {
			
        } else {
			
            return array(
                'status' => 204,
                'message' => "Transactions not found."
            );
        }*/
    }

    public function transaction_appointment($id = "", $parent_id = "", $filter = "", $dates = "", $doctor_id = "") {
        $record_per_page = 5;
        $page = "";
        if(isset($id)) {
            $page = $id;
        } else {
            $page = 1;
        }

        $start_from = ($page-1) * $record_per_page;

        $q = "";
        if($parent_id == "") {
            $filter123 = "";
            $dates123 = "";
            if($filter == "") {
                if($dates == "") {
                    $filter123 = "";
                    $dates123 = "";
                } else if($dates != "") {
                    $filter123 = "";
                    $dates123 = "WHERE `date_text` = '".$dates."'";
                }
            } else if($filter != "") {
                if($dates == "") {
                    $filter123 = "WHERE `appointment_status` = '".$filter."'";
                    $dates123 = "";
                } else if($dates != "") {
                    $filter123 = "WHERE `appointment_status` = '".$filter."'";
                    $dates123 = "AND `date_text` = '".$dates."'";
                }
            }
            $q = $this->db->query("SELECT * FROM `appointments` ".$filter123." ".$dates123." ORDER BY `appointment_id` DESC LIMIT $start_from, $record_per_page");

        } else {
            $filter123 = "";
            $dates123 = "";
            $parents123 = "";
            if($filter == "") {
                if($dates == "") {
                    $parents123 = "WHERE `appointment_parent_id` = '".$parent_id."'";
                    $filter123 = "";
                    $dates123 = "";
                    
                } else if($dates != "") {
                    $parents123 = "WHERE `appointment_parent_id` = '".$parent_id."'";
                    $filter123 = "";
                    $dates123 = "AND `date_text` = '".$dates."'";
                }
            } else {
                if($dates == "") {
                    $parents123 = "WHERE `appointment_parent_id` = '".$parent_id."'";
                    $filter123 = "AND `appointment_status` = '".$filter."'";
                    $dates123 = "";
                } else if($dates != "") {
                    $parents123 = "WHERE `appointment_parent_id` = '".$parent_id."'";
                    $filter123 = "AND `appointment_status` = '".$filter."'";
                    $dates123 = "AND `date_text` = '".$dates."'";
                }
            }
            $q = $this->db->query("SELECT * FROM `appointments` ".$parents123." ".$filter123." ".$dates123." ORDER BY `appointment_id` DESC LIMIT $start_from, $record_per_page");
        }
        $arr = array();

        $count = 1;

        if($q->num_rows() > 0) {
            // existing
            // success
            $forDoctor = array();
            foreach($q->result_array() as $row) { 
                $getParent = $this->db->query("SELECT * FROM `parents_tbl` WHERE parent_id = '".$row['appointment_parent_id']."'")->result_array();
                $getPatient = $this->db->query("SELECT * FROM `patients_tbl` WHERE patient_id = '".$row['appointment_patient_id']."'")->result_array();
                $patients_illness = $this->db->query("SELECT * FROM `patients_illness_tbl` WHERE `type` = 'appointments' AND `id` = '".$row['appointment_id']."'");
                $illness="";
                if($patients_illness->num_rows() > 0) {
                    foreach($patients_illness->result_array() as $rews) {
                        $illness .= $rews['terms_title'].", ";
                    }
                }
                if($doctor_id == "") {
                    $arr[] = array(
                        'status' => '200',
                        'overallId'            => $count,
                        'category'             => 'appointments',
                        'id'                   => $row['appointment_id'],
                        'parent_id'            => $row['appointment_parent_id'],
                        'parent_name'         => $getParent[0]['parent_name'],
                        'patient_id'           => $row['appointment_patient_id'],
                        'patient_name'         => $getPatient[0]['patient_name'],
                        'timestamp'            => $row['appointment_timestamp'],
                        'timestamp_end'        => $row['appointment_timestamp_end'],
                        'prescription'         => '',
                        'description'          => $row['appointment_description'],
                        'date_text'            => $row['date_text'],
                        'datetime'             => $row['appointment_datetime'],
                        'datetime_end'         => $row['appointment_datetime_end'],
                        'timestamp_sub'            => $row['appointment_timestamp_end'], //
                        'timestamp_sub_end'        => $row['appointment_timestamp_sub_end'], //
                        'reason'               => '', //
                        'approve_by'           => $row['appointment_approve_by'], //
                        'approve_selection'    => $row['appointment_approve_selection'], //
                        'interview_id'    => $row['interview_id'], //
                        'googlelink'           => '', //
                        'money'                => $row['money'],
                        'survey_done'          => $row['survey_done'],
                        'status'               => $row['appointment_status'],
                        'reference_number'     => $row['reference_number'],
                        'interview_id'         => $row['interview_id'],
                        'patient_illness'     => $illness
                    );
                    
                } else if($doctor_id == $row['interview_id']) {
                    $forDoctor[] = array(
                        'status' => '200',
                        'overallId'            => $count,
                        'category'             => 'appointments',
                        'id'                   => $row['appointment_id'],
                        'parent_id'            => $row['appointment_parent_id'],
                        'parent_name'         => $getParent[0]['parent_name'],
                        'patient_id'           => $row['appointment_patient_id'],
                        'patient_name'         => $getPatient[0]['patient_name'],
                        'timestamp'            => $row['appointment_timestamp'],
                        'timestamp_end'        => $row['appointment_timestamp_end'],
                        'prescription'         => '',
                        'description'          => $row['appointment_description'],
                        'date_text'            => $row['date_text'],
                        'datetime'             => $row['appointment_datetime'],
                        'datetime_end'         => $row['appointment_datetime_end'],
                        'timestamp_sub'            => $row['appointment_timestamp_end'], //
                        'timestamp_sub_end'        => $row['appointment_timestamp_sub_end'], //
                        'reason'               => '', //
                        'approve_by'           => $row['appointment_approve_by'], //
                        'approve_selection'    => $row['appointment_approve_selection'], //
                        'interview_id'    => $row['interview_id'], //
                        'googlelink'           => '', //
                        'money'                => $row['money'],
                        'survey_done'          => $row['survey_done'],
                        'status'               => $row['appointment_status'],
                        'reference_number'     => $row['reference_number'],
                        'interview_id'         => $row['interview_id'],
                        'patient_illness'     => $illness
                    );
                }
                $count++;
            }
            if($doctor_id != "") {
                if(count($forDoctor) == 0) {
                    return array(
                        'status' => 204,
                        'message' => "Transaction Appointment not found."
                    );
                } else {
                    return $forDoctor;
                }
            } else {
                return $arr;
            }
        } else {
            return array(
                'status' => 204,
                'message' => "Transaction Appointment not found."
            );
        }
    }
    public function transaction_consultation($id = "", $parent_id = "", $filter = "", $dates = "", $doctor_id = "") {
        $record_per_page = 5;
        $page = "";

        $q = "";

        if(isset($id)) {
            $page = $id;
        } else {
            $page = 1;
        }

        $start_from = ($page-1) * $record_per_page;
        if($parent_id == "") {
            $filter123 = "";
            $dates123 = "";
            if($filter == "") {
                if($dates == "") {
                    $filter123 = "";
                    $dates123 = "";
                } else if($dates != "") {
                    $filter123 = "";
                    $dates123 = "WHERE `date_text` = '".$dates."'";
                }
            } else if($filter != "") {
                if($dates == "") {
                    $filter123 = "WHERE `consultation_status` = '".$filter."'";
                    $dates123 = "";
                } else if($dates != "") {
                    $filter123 = "WHERE `consultation_status` = '".$filter."'";
                    $dates123 = "AND `date_text` = '".$dates."'";
                }
            }
            $q = $this->db->query("SELECT * FROM `consultations` ".$filter123." ".$dates123." ORDER BY `consultation_id` DESC LIMIT $start_from, $record_per_page");
        } else {
            $parents123 = "";
            $dates123 = "";
            $filter123 = "";

            if($filter == "") {
                if($dates == "") {
                    $parents123 = "WHERE `consultation_parent_id` = '".$parent_id."'";	
                    $filter123 = "";
                    $dates123 = "";
                } else if($dates != "") {
                    $parents123 = "WHERE `consultation_parent_id` = '".$parent_id."'";
                    $filter123 = "";
                    $dates123 = "AND `date_text` = '".$dates."'";
                }
            } else if($filter != "") {
                if($dates == "") {
                    $parents123 = "WHERE consultation_parent_id = '".$parent_id."'";
                    $filter123 = "AND consultation_status = '".$filter."'";
                    $dates123 = "";
                } else if($dates != "") {
                    $parents123 = "WHERE consultation_parent_id = '".$parent_id."'";
                    $filter123 = "AND consultation_status = '".$filter."'";
                    $dates123 = "AND `date_text` = '".$dates."'";	
                }
            }
            $q = $this->db->query("SELECT * FROM `consultations` ".$parents123." ".$filter123." ".$dates123." ORDER BY `consultation_id` DESC LIMIT $start_from, $record_per_page");	
        }
        $arr = array();

        $count = 1;

        if($q->num_rows() > 0) {
            // existing
            // success
            $forDoctor = array();
            foreach($q->result_array() as $row2) { 
                $getParent = $this->db->query("SELECT * FROM `parents_tbl` WHERE parent_id = '".$row2['consultation_parent_id']."'")->result_array();
                $getPatient = $this->db->query("SELECT * FROM `patients_tbl` WHERE patient_id = '".$row2['consultation_patient_id']."'")->result_array();
                $patients_illness = $this->db->query("SELECT * FROM `patients_illness_tbl` WHERE `type` = 'consultations' AND `id` = '".$row2['appointment_id']."'");
                $illness="";
                if($patients_illness->num_rows() > 0) {
                    foreach($patients_illness->result_array() as $rews) {
                        $illness .= $rews['terms_title'].", ";
                    }
                }
                if($doctor_id == "") {
                    $arr[] = array(
                        'status' => '200',
                        'send_it_to_parent' => $row2['send_it_to_parent'],
                        'overallId'            => $count,
                        'category'             => 'consultations', //
                        'id'                   => $row2['consultation_id'], //
                        'parent_id'            => $row2['consultation_parent_id'], //
                        'parent_name'         => $getParent[0]['parent_name'], //
                        'patient_id'           => $row2['consultation_patient_id'], //
                        'patient_name'         => $getPatient[0]['patient_name'], //
                        'timestamp'            => $row2['date_consultation'], //
                        'timestamp_end'        => $row2['date_consultation_end'], //
                        'timestamp_sub'            => $row2['date_consultation_sub'], //
                        'timestamp_sub_end'        => $row2['date_consultation_sub_end'], //
                        'prescription'         => $row2['consultation_prescription'], //
                        'description'          => '',
                        'date_text'            => $row2['date_text'],
                        'datetime'             => $row2['date_consultation_datetime'], //
                        'datetime_end'         => $row2['date_consultation_datetime_end'], //
                        'reason'               => $row2['reason'], //
                        'approve_by'           => $row2['consultation_approve_by'], //
                        'approve_selection'    => $row2['consultation_approve_selection'], //
                        'interview_id'    => $row2['interview_id'], //
                        'googlelink'           => $row2['googlelink'], //
                        'money'                => $row2['money'], //
                        'survey_done'          => $row2['survey_done'], //
                        'status'               => $row2['consultation_status'], //
                        'reference_number'     => $row2['reference_number'],
                        'interview_id'         => $row2['interview_id'],
                        'patient_illness'      => $illness,
                        'healthHistory'        => $row2['healthHistory'],
                        'anyMedication'        => $row2['anyMedication'],
                        'anyAllergies'         => $row2['anyAllergies'],
                        'pdf'                  => ($row2['send_it_to_parent'] != "") ? md5($row2['date_consultation_datetime'] . ' ' . $row2['date_consultation_datetime_end']) : ""
                    );
                } else if($doctor_id == $row2['interview_id']) {
                    $forDoctor[] = array(
                        'status' => '200',
                        'send_it_to_parent' => $row2['send_it_to_parent'],
                        'overallId'            => $count,
                        'category'             => 'consultations', //
                        'id'                   => $row2['consultation_id'], //
                        'parent_id'            => $row2['consultation_parent_id'], //
                        'parent_name'         => $getParent[0]['parent_name'], //
                        'patient_id'           => $row2['consultation_patient_id'], //
                        'patient_name'         => $getPatient[0]['patient_name'], //
                        'timestamp'            => $row2['date_consultation'], //
                        'timestamp_end'        => $row2['date_consultation_end'], //
                        'timestamp_sub'            => $row2['date_consultation_sub'], //
                        'timestamp_sub_end'        => $row2['date_consultation_sub_end'], //
                        'prescription'         => $row2['consultation_prescription'], //
                        'description'          => '',
                        'date_text'            => $row2['date_text'],
                        'datetime'             => $row2['date_consultation_datetime'], //
                        'datetime_end'         => $row2['date_consultation_datetime_end'], //
                        'reason'               => $row2['reason'], //
                        'approve_by'           => $row2['consultation_approve_by'], //
                        'approve_selection'    => $row2['consultation_approve_selection'], //
                        'interview_id'    => $row2['interview_id'], //
                        'googlelink'           => $row2['googlelink'], //
                        'money'                => $row2['money'], //
                        'survey_done'          => $row2['survey_done'], //
                        'status'               => $row2['consultation_status'], //
                        'reference_number'     => $row2['reference_number'],
                        'interview_id'         => $row2['interview_id'],
                        'patient_illness'      => $illness,
                        'healthHistory'        => $row2['healthHistory'],
                        'anyMedication'        => $row2['anyMedication'],
                        'anyAllergies'         => $row2['anyAllergies'],
                        'pdf'                  => ($row2['send_it_to_parent'] != "") ? md5($row2['date_consultation_datetime'] . ' ' . $row2['date_consultation_datetime_end']) : ""
                    );
                }
                $count++;
            }
            if($doctor_id != "") {
                if(count($forDoctor) == 0) {
                    return array(
                        'status' => 204,
                        'message' => "Transaction Appointment not found."
                    );
                } else {
                    return $forDoctor;
                }
            } else {
                return $arr;
            }
        } else {
            return array(
                'status' => 204,
                'message' => "Transaction Consultation not found."
            );
        }
    }
    public function displayDateType($type = "", $parent_id = "") {
        if($type == "appointment") {
            $array = array();
            $q = $this->db->query("SELECT * FROM `appointments` WHERE `appointment_parent_id` = '".$parent_id."' GROUP BY date_text");
            if($q->num_rows() > 0) {
                foreach($q->result_array() as $row) {
                    $array[] = array(
                        'date' => $row['date_text'],
                        'interview_id' => $row['interview_id']
                    );
                }
            } else {
                // not exist
                return array(
                    'status' => 204,
                    'message' => "Display Date Type not found."
                );
            }

            return $array;
            
        } else if($type == "consultation") {
            $array = array();
            $q = $this->db->query("SELECT * FROM `consultations` WHERE `consultation_parent_id` = '".$parent_id."' GROUP BY date_text");
            if($q->num_rows() > 0) {
                foreach($q->result_array() as $row) {
                    $array[] = array(
                        'date' => $row['date_text'],
                        'interview_id' => $row['interview_id']
                    );
                }
            } else {
                // not exist
                return array(
                    'status' => 204,
                    'message' => "Display Date Type not found."
                );
            }
            return $array;
        } else {
            // not exist
            return array(
                'status' => 204,
                'message' => "Display Date Type not found."
            );
        }
    }

    public function terms($type = "", $id = "") {
        
        // exist
        $q = $this->db->query("SELECT * FROM terms_tbl");
        $array = array();
        if($q->num_rows() > 0) {
            //exist
            foreach($q->result_array() as $row) {
                $type2 = "";
                $booleans = "";
                $patient_illness = $this->db->query("SELECT * FROM `patients_illness_tbl` WHERE `type` = '".$type."' AND `id` = '".$id."' AND `terms_id` = '".$row['terms_id']."'");
                if($patient_illness->num_rows() > 0) {
                    // exists
                    $booleans = true;
                } else {
                    // not exists
                    $booleans = false;
                }
                $array[] = array(
                    'type' => $type,
                    'id'      => $id,
                    'terms_id' => $row['terms_id'],
                    'terms_title' => $row['terms_title'],
                    'isChecked' => $booleans
                );
            }
            return $array;
            
        } else {
            // not exist
            return array(
                'status' => 204,
                'message' => "Terms not found."
            );
        }
    }

    public function getDate($type = "", $dates = "", $parent_id = "", $filter = "") {
        if($type == "consultation") {
            $q = "";
            $num = 0;

            $parents123 = "";
            $filter123 = "";
            $dates123 = "";

            if($parent_id == "") {
                if($filter == "") {
                    if($dates == "") {
                        $num = 1;
                        $parents123 = "";
                        $filter123 = "";
                        $dates123 = "";
                    } else if($dates != "") {
                        $num = 2;
                        $parents123 = "";
                        $filter123 = "";
                        $dates123 = "WHERE `date_text` = '".$dates."'";
                    }
                } else {
                    if($dates == "") {
                        $num = 3;
                        $parents123 = "";
                        $filter123 = "WHERE `consultation_status` = '".$filter."'";
                        $dates123 = "";
                    } else if($dates != "") {
                        $num = 4;
                        $parents123 = "";
                        $filter123 = "WHERE `consultation_status` = '".$filter."'";
                        $dates123 = "AND `date_text` = '".$dates."'";
                    }
                    
                }
            } else {
                
                if($filter == "") {
                    if($dates == "") {
                        $num = 5;
                        $parents123 = "WHERE `consultation_parent_id` = '".$parent_id."'";
                        $filter123 = "";
                        $dates123 = "";
                    } else if($dates != "") {
                        $num = 6;
                        $parents123 = "WHERE `consultation_parent_id` = '".$parent_id."'";
                        $filter123 = "";
                        $dates123 = "AND `date_text` = '".$dates."'";
                    }
                } else {
                    if($dates == "") {
                        $num = 7;
                        $parents123 = "WHERE `consultation_parent_id` = '".$parent_id."'";
                        $filter123 = "AND `consultation_status` = '".$filter."'";
                        $dates123 = "";
                    } else if($dates != "") {
                        $num = 8;
                        $parents123 = "WHERE `consultation_parent_id` = '".$parent_id."'";
                        $filter123 = "AND `consultation_status` = '".$filter."'";
                        $dates123 = "AND `date_text` = '".$dates."'";
                    }
                }
            }
            $q = $this->db->query("SELECT `date_text` FROM `consultations` ".$parents123." ".$filter123." ".$dates123." GROUP BY `date_text`");
            $array = array();
            $array[] = array('date_text' => 'All');
            if($q->num_rows() > 0) {
                foreach($q->result_array() as $row) {
                    $array[] = array(
                        'date_text' => $row['date_text']
                    );
                }
            } else {
                return array(
                    'status' => 204,
                    'message' => "Get Date not found. " . $num
                );
            }

            return $array;
        } else if($type == "appointment") {
            $parents123 = "";
            $filter123 = "";
            $dates123 = "";
            $q = "";
            if($parent_id == "") {
                
                if($filter == "") {
                    if($dates == "") {
                        $parents123 = "";
                        $filter123 = "";
                        $dates123 = "";
                    } else if($dates != "") {
                        $parents123 = "";
                        $filter123 = "";
                        $dates123 = "WHERE `date_text` = '".$dates."'";
                    }
                } else {
                    if($dates == "") {
                        $parents123 = "";
                        $filter123 = "WHERE `appointment_status` = '".$filter."'";
                        $dates123 = "";
                    } else if($dates != "") {
                        $parents123 = "";
                        $filter123 = "WHERE `appointment_status` = '".$filter."'";
                        $dates123 = "AND `date_text` = '".$dates."'";
                    }
                }
                
            } else {
                $parents123 = "";
                $filter123 = "";
                $dates123 = "";
                if($filter == "") {
                    if($dates == "") {
                        $parents123 = "WHERE `appointment_parent_id` = '".$parent_id."'";
                        $filter123 = "";
                        $dates123 = "";
                    } else if($dates != "") {
                        $parents123 = "WHERE `appointment_parent_id` = '".$parent_id."'";
                        $filter123 = "";
                        $dates123 = "AND `date_text` = '".$dates."'";
                    }
                } else {
                    if($dates == "") {
                        $parents123 = "WHERE `appointment_parent_id` = '".$parent_id."'";
                        $filter123 = "AND `appointment_status` = '".$filter."'";
                        $dates123 = "";
                    } else if($dates != "") {
                        $parents123 = "WHERE `appointment_parent_id` = '".$parent_id."'";
                        $filter123 = "AND `appointment_status` = '".$filter."'";
                        $dates123 = "AND `date_text` = '".$dates."'";
                    }
                }
            }
            $q = $this->db->query("SELECT `date_text` FROM `appointments` ".$parents123." ".$filter123." ".$dates123."  GROUP BY `date_text`");
            $array = array();
            $array[] = array('date_text' => 'All');
            if($q->num_rows() > 0) {
                foreach($q->result_array() as $row) {
                    $array[] = array(
                        'date_text' => $row['date_text']
                    );
                }
            } else {
                return array(
                    'status' => 204,
                    'message' => "Get Date not found."
                );
            }

            return $array;
        } else {
            return array(
                'status' => 204,
                'message' => "Get Date not found."
            );
        }
    }
    public function getSchedule($id = "", $type = "", $parent_id = "", $filter = "") {
        $record_per_page = 5;
        $page = "";
        if(isset($id)) {
            $page = $id;
        } else {
            $page = 1;
        }

        $start_from = ($page-1) * $record_per_page;

        if($type == "consultation") {
            if($parent_id == "") {
                if($filter == "") {
                    $q = $this->db->query("SELECT `date_text` FROM `consultations` GROUP BY `date_text` ORDER BY `consultation_id` DESC LIMIT $start_from, $record_per_page");
                } else {
                    $q = $this->db->query("SELECT `date_text` FROM `consultations` WHERE `consultation_status` = '".$filter."' GROUP BY `date_text` DESC LIMIT $start_from, $record_per_page");
                }
            } else {
                if($filter == "") {
                    $q = $this->db->query("SELECT `date_text` FROM `consultations` WHERE `consultation_parent_id` = '".$parent_id."' GROUP BY `date_text` DESC LIMIT $start_from, $record_per_page");
                } else {
                    $q = $this->db->query("SELECT `date_text` FROM `consultations` WHERE `consultation_status` = '".$filter."' AND `consultation_parent_id` = '".$parent_id."' GROUP BY `date_text` DESC LIMIT $start_from, $record_per_page");
                }
                
            }

            $array = array();
            if($q->num_rows() > 0) {
                foreach($q->result_array() as $row) {
                    $array[] = array(
                        'date_text' => $row['date_text']
                    );
                }
            } else {
                return array(
                    'status' => 204,
                    'message' => "Get Date not found."
                );
            }

            return $array;
        } else if($type == "appointment") {
            if($parent_id == "") {
                if($filter == "") {
                    $q = $this->db->query("SELECT `date_text` FROM `appointments` GROUP BY `date_text`");
                } else {
                    $q = $this->db->query("SELECT `date_text` FROM `appointments` WHERE `appointment_status` = '".$filter."' GROUP BY `date_text`");
                }
            } else {
                if($filter == "") {
                    $q = $this->db->query("SELECT `date_text` FROM `appointments` WHERE `appointment_parent_id` = '".$parent_id."' GROUP BY `date_text`");
                } else {
                    $q = $this->db->query("SELECT `date_text` FROM `appointments` WHERE `appointment_parent_id` = '".$parent_id."' AND `appointment_status` = '".$filter."' GROUP BY `date_text`");
                }
            }

            $array = array();
            if($q->num_rows() > 0) {
                foreach($q->result_array() as $row) {
                    $array[] = array(
                        'date_text' => $row['date_text']
                    );
                }
            } else {
                return array(
                    'status' => 204,
                    'message' => "Get Date not found."
                );
            }

            return $array;
        } else {
            return array(
                'status' => 204,
                'message' => "Get Date not found."
            );
        }
    }
    public function deleteIllness($type = "", $id = "") {
        $this->db->query("DELETE FROM patients_illness_tbl WHERE `type` = '".$type."' AND `id` = '".$id."'");
    }

    public function addIllness($type = "", $id = "", $terms_id = "") {
        if($type == "" && $id == "" && $terms_id == "") {
            return array(
                'status' => 204,
                'message' => "Add Illness not found."
            );
        } else {
            $q = $this->db->query("SELECT * FROM `terms_tbl` WHERE `terms_id` = '".$terms_id."'")->result_array();
            $array = array(
                "type" => $type,
                "id" => $id,
                "terms_id" => $terms_id,
                "terms_title" => $q[0]['terms_title'],
                "timestamp" => time()
            );

            $this->db->insert('patients_illness_tbl', $array);
        }
    }
    public function updateGoogleLink($id = "", $googlelink = "") {
        if($id == "" && $googlelink == "") {
            return array(
                'status' => 204,
                'message' => "Update Google Link not found."
            );
        } else {
            $q = $this->db->query("SELECT * FROM consultations WHERE consultation_id = '".$id."'");
            if($q->num_rows() > 0) {
                // exist
                
                $this->db->query("UPDATE `consultations` SET `googlelink` = '".$googlelink."' WHERE `consultation_id` = '".$id."'");

                $qs = $this->db->query("SELECT * FROM `consultations` WHERE `consultation_id` = '".$id."'")->result_array();
                $p = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$qs[0]['consultation_parent_id']."'")->result_array();
                $p2 = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$qs[0]['consultation_patient_id']."'")->result_array();
                $message = "For patient name: ".$p2[0]['patient_name']."\n\nHere the meeting:\n".$qs[0]['googlelink']."\n\nDate start: ".$qs[0]['date_consultation_datetime']."\nDate end: ".$qs[0]['date_consultation_datetime_end']."\n\nSee you there!";
                $this->auth_model->itexmo($p[0]['parent_phonenumber'], $message);

                return array(
                    'status' => 200,
                    'message' => $message
                );
            } else {
                return array(
                    'status' => 204,
                    'message' => "Update Google Link not found."
                );
            }
        }
    }
    public function approveThis($type = "", $id = "", $doctor_id = "") {
        if($type == "" && $id == "") {
            return array(
                'status' => 204,
                'message' => "Type and ID not found."
            );
        } else {
            if($type == "appointments") {
                $this->db->query("UPDATE `appointments` SET `appointment_status` = 'Approved', appointment_approve_by = '".$doctor_id."', appointment_approve_selection = '1' WHERE `appointment_id` = '".$id."'");

                $q = $this->db->query("SELECT * FROM `appointments` WHERE `appointment_id` = '".$id."'")->result_array();
                $p = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$q[0]['appointment_parent_id']."'")->result_array();
                $p2 = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$q[0]['appointment_patient_id']."'")->result_array();
                //$message = "Your appointment request has been approved!\n\nDate start: ".$q[0]['appointment_datetime']."\nDate end: ".$q[0]['appointment_datetime_end']."\n\nWe will message you via SMS as soon as the link is ready for your appointment";
                $message = "Your appointment request for ".$p2[0]['patient_name']." has been approved!\n\nDate start: ".$q[0]['appointment_datetime']."\nDate end: ".$q[0]['appointment_datetime_end'];
		        $this->auth_model->itexmo($p[0]['parent_phonenumber'], $message);
                return array(
                    'status' => 200,
                    'message' => "Done"
                );
            } else if($type == "consultations") {
                $this->db->query("UPDATE `consultations` SET `consultation_status` = 'Approved', consultation_approve_by = '".$doctor_id."', consultation_approve_selection = '1' WHERE `consultation_id` = '".$id."'");

                $q = $this->db->query("SELECT * FROM `consultations` WHERE `consultation_id` = '".$id."'")->result_array();
                $p = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$q[0]['consultation_parent_id']."'")->result_array();
                $p2 = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$q[0]['consultation_patient_id']."'")->result_array();
                $message = "Your consultation request for ".$p2[0]['patient_name']." has been approved!\n\nDate start: ".$q[0]['date_consultation_datetime']."\nDate end: ".$q[0]['date_consultation_datetime_end']."\n\nWe will message you via SMS as soon as the link is ready for your consultation";
                $this->auth_model->itexmo($p[0]['parent_phonenumber'], $message);
                return array(
                    'status' => 200,
                    'message' => "Done"
                );
            }
            
        }
    }
    public function cancelThis($type = "", $id = "") {
        if($type == "" && $id == "") {
            return array(
                'status' => 204,
                'message' => "Type and ID not found."
            );
        } else {
            if($type == "appointments") {
                $this->db->query("UPDATE `appointments` SET `appointment_status` = 'Cancelled', `appointment_timestamp` = '0',  WHERE `appointment_id` = '".$id."'");

                $q = $this->db->query("SELECT * FROM `appointments` WHERE `appointment_id` = '".$id."'")->result_array();
                $p = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$q[0]['appointment_parent_id']."'")->result_array();
                $p2 = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$q[0]['appointment_patient_id']."'")->result_array();
                //$message = "Your appointment request has been approved!\n\nDate start: ".$q[0]['appointment_datetime']."\nDate end: ".$q[0]['appointment_datetime_end']."\n\nWe will message you via SMS as soon as the link is ready for your appointment";
                $message = "Your appointment request for ".$p2[0]['patient_name']." has been cancelled!";
		        $this->auth_model->itexmo($p[0]['parent_phonenumber'], $message);
                return array(
                    'status' => 200,
                    'message' => "Done"
                );


            } else if($type == "consultations") {
                $this->db->query("UPDATE `consultations` SET `consultation_status` = 'Cancelled',  `date_consultation` = '0', `date_consultation_end` = '0'  WHERE `consultation_id` = '".$id."'");

                $q = $this->db->query("SELECT * FROM `consultations` WHERE `consultation_id` = '".$id."'")->result_array();
                $p = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$q[0]['consultation_parent_id']."'")->result_array();
                $p2 = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$q[0]['consultation_patient_id']."'")->result_array();
                $message = "Your consultation request for ".$p2[0]['patient_name']." has been cancelled!";
                $this->auth_model->itexmo($p[0]['parent_phonenumber'], $message);
                return array(
                    'status' => 200,
                    'message' => "Done"
                );

            }
            
        }
    }
    public function doneThis($type = "", $id = "") {
        if($type == "" || $id == "") {
            return array(
                'status' => 204,
                'message' => "Type and ID not found.\nId: " . $id . "\nType: ".$type 
            );
        } else {
            if($type == "appointments") {
                $this->db->query("UPDATE `appointments` SET `appointment_status` = 'Finished', appointment_timestamp = '0', appointment_timestamp_end = 0 WHERE `appointment_id` = '".$id."'");

                $q = $this->db->query("SELECT * FROM `appointments` WHERE `appointment_id` = '".$id."'")->result_array();
                $p = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$q[0]['appointment_parent_id']."'")->result_array();
                $p2 = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$q[0]['appointment_patient_id']."'")->result_array();
                $message = "Your appointment request for ".$p2[0]['patient_name']." has been finished!";
		        $this->auth_model->itexmo($p[0]['parent_phonenumber'], $message);
                

                return array(
                    'status' => 200,
                );

            } else if($type == "consultations") {
                $this->db->query("UPDATE `consultations` SET `consultation_status` = 'Finished', date_consultation = '0', date_consultation_end = '0' WHERE `consultation_id` = '".$id."'");

                $q = $this->db->query("SELECT * FROM `consultations` WHERE `consultation_id` = '".$id."'")->result_array();
                $p = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$q[0]['consultation_parent_id']."'")->result_array();
                $p2 = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$q[0]['consultation_patient_id']."'")->result_array();
                $message = "Your consultation request for ".$p2[0]['patient_name']." has been finished!";
                $this->auth_model->itexmo($p[0]['parent_phonenumber'], $message);


                return array(
                    'status' => 200,
                    'message' => "Done"
                );

            }
            
        }
    }
    public function chooseDoctor($id = "", $doctor_id = "", $type = "") {
        if($id == "" && $doctor_id == "") {
            return array(
                'status' => 204,
                'message' => "Choose Doctor not found."
            );
        } else {
            if($type == "consultations") {
                $q = $this->db->query("SELECT * FROM consultations WHERE consultation_id = '".$id."'");
                if($q->num_rows() > 0) {
                    $this->db->query("UPDATE `consultations` SET `interview_id` = '".$doctor_id."' WHERE `consultation_id` = '".$id."'");
                    return array(
                        'status' => 200,
                        'message' => "Successfully!"
                    );
                } else {
                    return array(
                        'status' => 204,
                        'message' => "Error! There\'s something wrong!"
                    );
                }
            } else if($type == "appointments") {
                $q = $this->db->query("SELECT * FROM appointments WHERE appointment_id = '".$id."'");
                if($q->num_rows() > 0) {
                    $this->db->query("UPDATE `appointments` SET `interview_id` = '".$doctor_id."' WHERE `appointment_id` = '".$id."'");
                    return array(
                        'status' => 200,
                        'message' => "Successfully!"
                    );
                } else {
                    return array(
                        'status' => 204,
                        'message' => "Error! There\'s something wrong!"
                    );
                }
            } else {
                return array(
                    'status' => 204,
                    'message' => "Error! There\'s something wrong!"
                );
            }
            /*
            $q = $this->db->query("SELECT * FROM consultations WHERE consultation_id = '".$id."'");
            if($q->num_rows() > 0) {
                // exist
                
                $this->db->query("UPDATE `consultations` SET `googlelink` = '".$googlelink."' WHERE `consultation_id` = '".$id."'");

                $qs = $this->db->query("SELECT * FROM `consultations` WHERE `consultation_id` = '".$id."'")->result_array();
                $p = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$qs[0]['consultation_parent_id']."'")->result_array();
                $p2 = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$qs[0]['consultation_patient_id']."'")->result_array();
                $message = "For patient name: ".$p2[0]['patient_name']."\nHere's the google link: ".$qs[0]['googlelink']."\n\nDate start: ".$qs[0]['date_consultation_datetime']."\nDate end: ".$qs[0]['date_consultation_datetime_end']."\n\nSee you there!";
                $this->auth_model->itexmo($p[0]['parent_phonenumber'], $message);

                return array(
                    'status' => 200,
                    'message' => $message
                );
            } else {
                return array(
                    'status' => 204,
                    'message' => "Update Google Link not found."
                );
            }*/
        }
    }

    public function displayUsers($txt = "", $id = "", $selection = "") {
        $array = array();
			
		if($txt == "") {
			$q = $this->db->query("SELECT * FROM `doctors_tbl`");
			foreach($q->result_array() as $row1) {
                if($row1['doctor_id'] == $id && 1 == $selection) {
                } else {
                    $check = $this->db->query("SELECT * FROM `chats_info_tbl` WHERE `chats_id` = '".$id."' AND `chats_selection` = '".$selection."' AND `id_to` = '".$row1['doctor_id']."' AND `selection_to` = '1'");
                    if($check->num_rows() > 0) {
                        // existing
                        $getCheck = $check->result_array();
                        $check2 = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE `chats_info_code` = '".$getCheck[0]['code']."' ORDER BY `chats_history_id` DESC LIMIT 1");
                        if($check2->num_rows() > 0) {
                            // existing
                            $getCheck2 = $check2->result_array();

                            $array[] = array(
                                'time' => $getCheck2[0]['time'],
                                'msg' => $getCheck2[0]['chats_message'],
                                'id' => $row1['doctor_id'],
                                'selection' => '1',
                                'name' => $row1['doctor_name'],
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row1['profile_picture'])
                            );
                        } else {
                            // not exist
                            $array[] = array(
                                'time' => "",
                                'msg' => "",
                                'id' => $row1['doctor_id'],
                                'selection' => '1',
                                'name' => $row1['doctor_name'],
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row1['profile_picture'])
                            );
                        }
                    } else {
                        $array[] = array(
                            'time' => "",
                            'msg' => "",
                            'id' => $row1['doctor_id'],
                            'selection' => '1',
                            'name' => $row1['doctor_name'],
                            'code' => "",
                            'profile_picture' => base64_encode($row1['profile_picture'])
                        );
                    }
                }
			}

			$q2 = $this->db->query("SELECT * FROM `admins_tbl`");
			foreach($q2->result_array() as $row2) {
                if($row2['admin_id'] == $id && 2 == $selection) {
                } else {
                    $check = $this->db->query("SELECT * FROM `chats_info_tbl` WHERE `chats_id` = '".$id."' AND `chats_selection` = '".$selection."' AND `id_to` = '".$row2['admin_id']."' AND `selection_to` = '2'");
                    if($check->num_rows() > 0) {
                        // existing
                        $getCheck = $check->result_array();
                        $check2 = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE `chats_info_code` = '".$getCheck[0]['code']."' ORDER BY `chats_history_id` DESC LIMIT 1");
                        if($check2->num_rows() > 0) {
                            // existing
                            $getCheck2 = $check2->result_array();

                            $array[] = array(
                                'time' => $getCheck2[0]['time'],
                                'msg' => $getCheck2[0]['chats_message'],
                                'id' => $row2['admin_id'],
                                'selection' => '2',
                                'name' => $row2['admin_name'],
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row2['profile_picture'])
                            );
                        } else {
                            // not exist
                            $array[] = array(
                                'time' => "",
                                'msg' => "",
                                'id' => $row2['admin_id'],
                                'selection' => '2',
                                'name' => $row2['admin_name'],
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row2['profile_picture'])
                            );
                        }
                    } else {
                        $array[] = array(
                            'time' => "",
                            'msg' => "",
                            'id' => $row2['admin_id'],
                            'selection' => '2',
                            'name' => $row2['admin_name'],
                            'code' => "",
                            'profile_picture' => base64_encode($row2['profile_picture'])
                        );
                    }
                }
			}

			$q3 = $this->db->query("SELECT * FROM `receptionists_tbl`");
			foreach($q3->result_array() as $row3) {
                if($row3['receptionist_id'] == $id && 3 == $selection) {
                } else {
                    $check = $this->db->query("SELECT * FROM `chats_info_tbl` WHERE `chats_id` = '".$id."' AND `chats_selection` = '".$selection."' AND `id_to` = '".$row3['receptionist_id']."' AND `selection_to` = '3'");
                    if($check->num_rows() > 0) {
                        // existing
                        $getCheck = $check->result_array();
                        $check2 = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE `chats_info_code` = '".$getCheck[0]['code']."' ORDER BY `chats_history_id` DESC LIMIT 1");
                        if($check2->num_rows() > 0) {
                            // existing
                            $getCheck2 = $check2->result_array();

                            $array[] = array(
                                'time' => $getCheck2[0]['time'],
                                'msg' => $getCheck2[0]['chats_message'],
                                'id' => $row3['receptionist_id'],
                                'selection' => '3',
                                'name' => $row3['receptionist_name'],
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row3['profile_picture'])
                            );
                        } else {
                            // not exist
                            $array[] = array(
                                'time' => "",
                                'msg' => "",
                                'id' => $row3['receptionist_id'],
                                'selection' => '3',
                                'name' => $row3['receptionist_name'],
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row3['profile_picture'])
                            );
                        }
                    } else {
                        $array[] = array(
                            'time' => "",
                            'msg' => "",
                            'id' => $row3['receptionist_id'],
                            'selection' => '3',
                            'name' => $row3['receptionist_name'],
                            'code' => "",
                            'profile_picture' => base64_encode($row3['profile_picture'])
                        );
                    }
                }
			}
            
            if($selection != 4) {
			    $q4 = $this->db->query("SELECT * FROM `parents_tbl`");
                foreach($q4->result_array() as $row4) {
                    if($row4['parent_id'] == $id && 4 == $selection) {
                    } else {
                        $check = $this->db->query("SELECT * FROM `chats_info_tbl` WHERE `chats_id` = '".$id."' AND `chats_selection` = '".$selection."' AND `id_to` = '".$row4['parent_id']."' AND `selection_to` = '4'");
                        if($check->num_rows() > 0) {
                            // existing
                            $getCheck = $check->result_array();
                            $check2 = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE `chats_info_code` = '".$getCheck[0]['code']."' ORDER BY `chats_history_id` DESC LIMIT 1");
                            if($check2->num_rows() > 0) {
                                // existing
                                $getCheck2 = $check2->result_array();

                                $array[] = array(
                                    'time' => $getCheck2[0]['time'],
                                    'msg' => $getCheck2[0]['chats_message'],
                                    'id' => $row4['parent_id'],
                                    'selection' => '4',
                                    'name' => $row4['parent_name'],
                                    'code' => $getCheck[0]['code'],
                                    'profile_picture' => base64_encode($row4['profile_picture'])
                                );
                            } else {
                                // not exist
                                $array[] = array(
                                    'time' => "",
                                    'msg' => "",
                                    'id' => $row4['parent_id'],
                                    'selection' => '4',
                                    'name' => $row4['parent_name'],
                                    'code' => $getCheck[0]['code'],
                                    'profile_picture' => base64_encode($row4['profile_picture'])
                                );
                            }
                        } else {
                            $array[] = array(
                                'time' => "",
                                'msg' => "",
                                'id' => $row4['parent_id'],
                                'selection' => '4',
                                'name' => $row4['parent_name'],
                                'code' => "",
                                'profile_picture' => base64_encode($row4['profile_picture'])
                            );
                        }
                    }
                }
            }
		} else {
			
			$q1 = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_name` LIKE '".$txt."%'");
			foreach($q1->result_array() as $row1) {
                if($row1['doctor_id'] == $id && 1 == $selection) {
                } else {
                    $check = $this->db->query("SELECT * FROM `chats_info_tbl` WHERE `chats_id` = '".$id."' AND `chats_selection` = '".$selection."' AND `id_to` = '".$row1['doctor_id']."' AND `selection_to` = '1'");
                    if($check->num_rows() > 0) {
                        // existing
                        $getCheck = $check->result_array();
                        $check2 = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE `chats_info_code` = '".$getCheck[0]['code']."' ORDER BY `chats_history_id` DESC LIMIT 1");
                        if($check2->num_rows() > 0) {
                            // existing
                            $getCheck2 = $check2->result_array();

                            $array[] = array(
                                'time' => $getCheck2[0]['time'],
                                'msg' => $getCheck2[0]['chats_message'],
                                'id' => $row1['doctor_id'],
                                'selection' => '1',
                                'name' => $row1['doctor_name'],
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row1['profile_picture'])
                            );
                        } else {
                            // not exist
                            $array[] = array(
                                'time' => "",
                                'msg' => "",
                                'id' => $row1['doctor_id'],
                                'selection' => '1',
                                'name' => $row1['doctor_name'],
                                'last_chat' => "",
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row1['profile_picture'])
                            );
                        }
                    } else {
                        $array[] = array(
                            'time' => "",
                            'msg' => "",
                            'id' => $row1['doctor_id'],
                            'selection' => '1',
                            'name' => $row1['doctor_name'],
                            'code' => "",
                            'profile_picture' => base64_encode($row1['profile_picture'])
                        );
                    }
                }
			}

			$q2 = $this->db->query("SELECT * FROM `admins_tbl` WHERE `admin_name` LIKE '".$txt."%'");
			foreach($q2->result_array() as $row2) {
                if($row2['admin_id'] == $id && 2 == $selection) {
                } else {
                    $check = $this->db->query("SELECT * FROM `chats_info_tbl` WHERE `chats_id` = '".$id."' AND `chats_selection` = '".$selection."' AND `id_to` = '".$row2['admin_id']."' AND `selection_to` = '2'");
                    if($check->num_rows() > 0) {
                        // existing
                        $getCheck = $check->result_array();
                        $check2 = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE `chats_info_code` = '".$getCheck[0]['code']."' ORDER BY `chats_history_id` DESC LIMIT 1");
                        if($check2->num_rows() > 0) {
                            // existing
                            $getCheck2 = $check2->result_array();

                            $array[] = array(
                                'time' => $getCheck2[0]['time'],
                                'msg' => $getCheck2[0]['chats_message'],
                                'id' => $row2['admin_id'],
                                'selection' => '2',
                                'name' => $row2['admin_name'],
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row2['profile_picture'])
                            );
                        } else {
                            // not exist
                            $array[] = array(
                                'time' => "",
                                'msg' => "",
                                'id' => $row2['admin_id'],
                                'selection' => '2',
                                'name' => $row2['admin_name'],
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row2['profile_picture'])
                            );
                        }
                    } else {
                        $array[] = array(
                            'time' => "",
                            'msg' => "",
                            'id' => $row2['admin_id'],
                            'selection' => '2',
                            'name' => $row2['admin_name'],
                            'code' => "",
                            'profile_picture' => base64_encode($row2['profile_picture'])
                        );
                    }
                }
			}

			$q3 = $this->db->query("SELECT * FROM `receptionists_tbl` WHERE `receptionist_name` LIKE '".$txt."%'");
			foreach($q3->result_array() as $row3) {
                if($row3['receptionist_id'] == $id && 3 == $selection) {
                } else {
                    $check = $this->db->query("SELECT * FROM `chats_info_tbl` WHERE `chats_id` = '".$id."' AND `chats_selection` = '".$selection."' AND `id_to` = '".$row3['receptionist_id']."' AND `selection_to` = '3'");
                    if($check->num_rows() > 0) {
                        // existing
                        $getCheck = $check->result_array();
                        $check2 = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE `chats_info_code` = '".$getCheck[0]['code']."' ORDER BY `chats_history_id` DESC LIMIT 1");
                        if($check2->num_rows() > 0) {
                            // existing
                            $getCheck2 = $check2->result_array();

                            $array[] = array(
                                'time' => $getCheck2[0]['time'],
                                'msg' => $getCheck2[0]['chats_message'],
                                'id' => $row3['receptionist_id'],
                                'selection' => '3',
                                'name' => $row3['receptionist_name'],
                                
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row3['profile_picture'])
                            );
                        } else {
                            // not exist
                            $array[] = array(
                                'time' => "",
                                'msg' => "",
                                'id' => $row3['receptionist_id'],
                                'selection' => '3',
                                'name' => $row3['receptionist_name'],
                                'code' => $getCheck[0]['code'],
                                'profile_picture' => base64_encode($row3['profile_picture'])
                            );
                        }
                    } else {
                        $array[] = array(
                            'time' => "",
                            'msg' => "",
                            'id' => $row3['receptionist_id'],
                            'selection' => '3',
                            'name' => $row3['receptionist_name'],
                            'code' => "",
                            'profile_picture' => base64_encode($row3['profile_picture'])
                        );
                    }
                }
			}
            if($selection != 4) {
                $q4 = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_name` LIKE '".$txt."%'");
                foreach($q4->result_array() as $row4) {
                    if($row4['receptionist_id'] == $id && 4 == $selection) {
                    } else {
                        $check = $this->db->query("SELECT * FROM `chats_info_tbl` WHERE `chats_id` = '".$id."' AND `chats_selection` = '".$selection."' AND `id_to` = '".$row4['parent_id']."' AND `selection_to` = '4'");
                        if($check->num_rows() > 0) {
                            // existing
                            $getCheck = $check->result_array();
                            $check2 = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE `chats_info_code` = '".$getCheck[0]['code']."' ORDER BY `chats_history_id` DESC LIMIT 1");
                            if($check2->num_rows() > 0) {
                                // existing
                                $getCheck2 = $check2->result_array();

                                $array[] = array(
                                    'time' => $getCheck2[0]['time'],
                                    'msg' => $getCheck2[0]['chats_message'],
                                    'id' => $row4['parent_id'],
                                    'selection' => '4',
                                    'name' => $row4['parent_name'],
                                    'code' => $getCheck[0]['code'],
                                    'profile_picture' => base64_encode($row4['profile_picture'])
                                );
                            } else {
                                // not exist
                                $array[] = array(
                                    'time' => "",
                                    'msg' => "",
                                    'id' => $row4['parent_id'],
                                    'selection' => '4',
                                    'name' => $row4['parent_name'],
                                    'code' => $getCheck[0]['code'],
                                    'profile_picture' => base64_encode($row4['profile_picture'])
                                );
                            }
                        } else {
                            $array[] = array(
                                'time' => "",
                                'msg' => "",
                                'id' => $row4['parent_id'],
                                'selection' => '4',
                                'name' => $row4['parent_name'],
                                'last_chat' => "",
                                'code' => "",
                                'profile_picture' => base64_encode($row4['profile_picture'])
                            );
                        }
                    }
                }
            }
		}

		return $array;
	}

    public function addChat($id, $selection, $id_to, $selection_to) {
        $this->load->model('power_model', null, true); // auto-connect model

        if($id == "" || $selection == "" || $id_to == "" || $selection_to == "") {
            return array(
                'status' => 204,
                'message' => "Empty blanks!"
            );
        }
        if($id == $id_to && $selection == $selection_to) {
            return array(
                'status' => 204,
                'message' => "You can\'t talk to your self."
            );
        }

		if($this->power_model->checkAccount2($id_to, $selection_to) == 1) {
			// check if this account exist
			// this is exist
		} else {
			return array(
                'status' => 204,
                'message' => "This user doesn't exist."
            );
		}

		$resultg = $this->power_model->checkMsgs2($id, $selection, $id_to, $selection_to);
		if($resultg == 1) {
			// exist
			return array(
                'status' => 204,
                'message' => "This sender and recipient are already exist."
            );
		} else {
			$generate = mt_rand(00000000, 99999999);
			$encrypts = md5($generate);

			// not exist
			$array = array(
				'chats_id' => $id,
				'chats_selection' => $selection,
				'id_to' => $id_to,
				'selection_to' => $selection_to,
				'code' => $encrypts
			);

			$array2 = array(
				'chats_id' => $id_to,
				'chats_selection' => $selection_to,
				'id_to' => $id,
				'selection_to' => $selection,
				'code' => $encrypts
			);
            $this->db->insert('chats_info_tbl', $array);
		    $this->db->insert('chats_info_tbl', $array2);
            return array(
                'status' => 200,
                'message' => "Successfully!"
            );
        }
	}
    public function getChats($code, $id, $account_id, $account_selection) {
        $this->load->model('power_model', null, true); // auto-connect model
		$q = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE `chats_info_code` = '".$code."' AND `chats_next_id` > '".$id."' ORDER BY `chats_history_id` DESC LIMIT 10");		
		$arr = array();
		// success
		foreach($q->result_array() as $row) { 
			//1-doctor
			//2-admin
			//3-receptionist
			//4-parent 
			$getInfo = "";
			if($row['chats_account_type'] == 1) {
				$getts = $this->power_model->getUserInfoDoctor($row['chats_id']);
				foreach($getts as $getData) {
					$getInfo = array(
						'full_name' => $getData['doctor_name'],
						'active' => $getData['active'],
						'profile_picture' => base64_encode($getData['profile_picture'])
					);
				} 
			} else if($row['chats_account_type'] == 2) {
				$getts = $this->power_model->getUserInfoAdmin($row['chats_id']);
				foreach($getts as $getData) {
					$getInfo = array(
						'full_name' => $getData['admin_name'],
						'active' => $getData['active'],
						'profile_picture' => base64_encode($getData['profile_picture'])
					);
				} 
			} else if($row['chats_account_type'] == 3) {
				$getts = $this->power_model->getUserInfoReceptionist($row['chats_id']);
				foreach($getts as $getData) {
					$getInfo = array(
						'full_name' => $getData['receptionist_name'],
						'active' => $getData['active'],
						'profile_picture' => base64_encode($getData['profile_picture'])
					);
				} 
			} else if($row['chats_account_type'] == 4) {
				$getts = $this->power_model->getUserInfoParent($row['chats_id']);
				foreach($getts as $getData) {
					$getInfo = array(
						'full_name' => $getData['parent_name'],
						'active' => $getData['active'],
						'profile_picture' => base64_encode($getData['profile_picture'])
					);
				} 
			}
			
			$text = "";
			if($row['chats_account_type'] == 1) { $text = "Doctor"; } 
			else if($row['chats_account_type'] == 2) { $text = "Administrator"; }
			else if($row['chats_account_type'] == 3) { $text = "Receptionist"; }
			else if($row['chats_account_type'] == 4) { $text = "Parent"; }

			$arr[] = array(
				'chats_next_id' => $row['chats_next_id'],
				'chats_history_id' => $row['chats_history_id'],
				'chats_info_code' => $row['chats_info_code'],

				'name' => $getInfo['full_name'],
				'active' => $getInfo['active'],
				'profile_picture' => $getInfo['profile_picture'],
				
				'chats_account_type_text' => $text,
				'chats_id' => $row['chats_id'],

				'msg' => $row['chats_message'],
				'time' => date('m-d-Y h:i:sA', $row['time']),
				'timestamp' => $row['time'],
                'type' => ($account_id == $row['chats_id'] && $account_selection == $row['chats_account_type'] ? "" : "other")
			);
		}
		return $arr;
	}
    public function getPreviouslyChats($code, $id, $account_id, $account_selection) {
        $this->load->model('power_model', null, true); // auto-connect model
        $q = $this->db->query("SELECT * FROM `chats_history_tbl` WHERE `chats_info_code` = '".$code."' AND `chats_next_id` < '".$id."' ORDER BY `chats_history_id` DESC LIMIT 10");		
        $arr = array();
        // success

        foreach($q->result_array() as $row) { 
            //1-doctor
            //2-admin
            //3-receptionist
            //4-parent 
            $getInfo = "";
            if($row['chats_account_type'] == 1) {
                $getts = $this->power_model->getUserInfoDoctor($row['chats_id']);
                foreach($getts as $getData) {
                    $getInfo = array(
                        'full_name' => $getData['doctor_name'],
                        'active' => $getData['active'],
                        'profile_picture' => base64_encode($getData['profile_picture'])
                    );
                } 
            } else if($row['chats_account_type'] == 2) {
                $getts = $this->power_model->getUserInfoAdmin($row['chats_id']);
                foreach($getts as $getData) {
                    $getInfo = array(
                        'full_name' => $getData['admin_name'],
                        'active' => $getData['active'],
                        'profile_picture' => base64_encode($getData['profile_picture'])
                    );
                } 
            } else if($row['chats_account_type'] == 3) {
                $getts = $this->power_model->getUserInfoReceptionist($row['chats_id']);
                foreach($getts as $getData) {
                    $getInfo = array(
                        'full_name' => $getData['receptionist_name'],
                        'active' => $getData['active'],
                        'profile_picture' => base64_encode($getData['profile_picture'])
                    );
                } 
            } else if($row['chats_account_type'] == 4) {
                $getts = $this->power_model->getUserInfoParent($row['chats_id']);
                foreach($getts as $getData) {
                    $getInfo = array(
                        'full_name' => $getData['parent_name'],
                        'active' => $getData['active'],
                        'profile_picture' => base64_encode($getData['profile_picture'])
                    );
                } 
            }
            
            $text = "";
            if($row['chats_account_type'] == 1) { $text = "Doctor"; } 
            else if($row['chats_account_type'] == 2) { $text = "Administrator"; }
            else if($row['chats_account_type'] == 3) { $text = "Receptionist"; }
            else if($row['chats_account_type'] == 4) { $text = "Parent"; }

            $arr[] = array(
                'chats_next_id' => $row['chats_next_id'],
                'chats_history_id' => $row['chats_history_id'],
                'chats_info_code' => $row['chats_info_code'],

                'name' => $getInfo['full_name'],
                'active' => $getInfo['active'],
                'profile_picture' => $getInfo['profile_picture'],
                
                'chats_account_type_text' => $text,
                'chats_id' => $row['chats_id'],

                'msg' => $row['chats_message'],
                'time' => date('m-d-Y h:i:sA', $row['time']),
                'timestamp' => $row['time'],
                'type' => ($account_id == $row['chats_id'] && $account_selection == $row['chats_account_type'] ? "" : "other")
            );
        }
        return $arr;
    }
    public function send($code, $id, $selection, $message) {
        if($code == "" || $id == "" || $selection == "" || $message == "") {
            return array(
                'status' => 204,
                'message' => "Error! Empty input fields!"
            );
        } else {
            $this->load->model('power_model', null, true); // auto-connect model
            if($this->power_model->checkChatCode($id, $selection, $code) == 1) {
                echo 'noexists';
            } else {
                $checkS = $this->db->query("SELECT * FROM chats_history_tbl WHERE chats_info_code = '".$code."' ORDER BY chats_history_id DESC")->result_array();
                $querys = array(
                    'chats_next_id' => $checkS[0]['chats_next_id'] + 1,
                    'chats_info_code' => $code,
                    'chats_account_type' => $selection,
                    'chats_id'  => $id,
                    'chats_message' => $message,
                    'time' => time()
                );
                $this->db->insert('chats_history_tbl', $querys);
                return array(
                    'status' => 200,
                    'message' => "Successfully!"
                );
                
            }
        }
    }

    public function vaccineList() {
        $vaccines = $this->db->query("SELECT * FROM vaccine_terms_tbl ORDER BY vaccine_terms_id");
        if($vaccines->num_rows() > 0) {
            // exist
            $array = array();
            $array[] = array(
                'vaccine_id' => "",
                'vaccine_title' => "All"
            );
            foreach($vaccines->result_array() as $row) {
                $array[] = array(
                    'vaccine_id' => $row['vaccine_terms_id'],
                    'vaccine_title' => $row['vaccine_terms_title']
                );
            }
            return $array;
        } else {
            // not exist
            return array(
                'status' => 204,
                'message' => "Error! Vaccine List not found!"
            );
        }
    }

    public function getDateImmunization() {
        $vaccines = $this->db->query("SELECT `date` FROM immunization_record GROUP BY date");
        if($vaccines->num_rows() > 0) {
            // exist
            $array = array();
            $array[] = array(
                'date' => "All"
            );
            foreach($vaccines->result_array() as $row) {
                $array[] = array(
                    'date' => $row['date']
                );
            }
            return $array;
        } else {
            // not exist
            return array(
                'status' => 204,
                'message' => "Error! Immunization Record Date not found!"
            );
        }
    }
    public function esc($val){
        $new = mysqli_real_escape_string($this->db->conn_id, htmlspecialchars($val));
        return $new;
    }
    public function medical_certificates(
        $parent_id,
        $patient_id,
        $date_of_consultation,
        $date_text,
        $money,
        $purpose,
        $interview_id
    ) {
        $this->load->model('power_model', null, true); // auto-connect model

        if($parent_id == "" ||
          $patient_id == "" ||
          $date_of_consultation == "" ||
          $money == "" ||
          $purpose == "") {
            return array(
                'status' => 204,
                'message' => "Empty blanks!"
            );
        } else {
            $array = array(
                'parent_id' => $parent_id,
                'patient_id' => $patient_id,
                'date_of_consultation' => $date_of_consultation,
                'date_text' => $date_text,
                'money' => $money,
                'reference_number' => strtoupper(substr(md5(rand(0,100000)+rand(100000,999999)), 0, 12)),
                'purpose' => $purpose,
                'timestamp' => time(),
                'status' => 'Pending',
                'interview_id' => $interview_id
            );
            $this->db->insert('medical_certificates', $array);
            return array(
                'status' => 200,
                'message' => "Successfully!"
            );
        }
	}
    public function list_medical_certificates(
        $parent_id = "",
        $patient_id = "",
        $id = "",
        $doctor_id = "" 
    ) {
        $record_per_page = 10;
        $page = "";
        if(isset($id)) {
            $page = $id;
        } else {
            $page = 1;
        }

        $start_from = ($page-1) * $record_per_page;

        $array = array();
        if($patient_id != "") {
            $this->db->where("patient_id", $patient_id);
        }
        if($parent_id != "") {
            $this->db->where("parent_id", $parent_id);
        }
        if($doctor_id != "") {
            $this->db->where("interview_id", $doctor_id);
        }
        $this->db->limit($record_per_page, $start_from); 

        $q = $this->db->get('medical_certificates');
        if($q->num_rows() > 0) {
            foreach($q->result_array() as $row) {
                $getParent = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$row['parent_id']."'")->result_array();
                $getPatient = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$row['patient_id']."'")->result_array();
                $array[] = array(
                    'medical_certificates_id' => $row['medical_certificates_id'],
                    'parent_id' => $row['parent_id'],
                    'parent_name' => $getParent[0]['parent_name'],
                    'date_of_consultation' => $row['date_of_consultation'],
                    'date_text' => $row['date_text'],
                    'patient_id' => $row['patient_id'],
                    'patient_name' => $getPatient[0]['patient_name'], 

                    'money' => $row['money'],
                    'reference_number' => $row['reference_number'],
                    'purpose' => $row['purpose'],
                    'diagnosis' => $row['diagnosis'],

                    'treatment_and_recommendation' => $row['treatment_and_recommendation'],
                    'approve_by' => $row['approve_by'],
                    'approve_selection' => $row['approve_selection'],
                    'interview_id' => $row['interview_id'],
                    'purpose_doctor' => $row['purpose_doctor'],

                    'timestamp' => $row['timestamp'],
                    'send_it_to_parent' => $row['send_it_to_parent'],
                    'reference_number' => $row['reference_number'],
                    'status' => $row['status'],
                    'pdf' => ($row['send_it_to_parent'] != "") ? md5($row['date_of_consultation'] . ' ' . $row['date_text'] . ' ' . $row['reference_number'] . ' ' . $row['timestamp']) : ""
                );
            }
            return $array;
        } else {
            return array(
                'status' => 204,
                'message' => "Empty blanks!"
            );
        }
	}
    public function addLaboratoryResults($parent_id, $patient_id, $date, $type_of_laboratory) {
        if($date == "" && $type_of_laboratory) {
            return array(
                'status' => 204,
                'message' => "Empty blanks!"
            );
        } else {
            $array = array(
                'parent_id' => $parent_id,
                'patient_id' => $patient_id,
                'date' => $date,
                'type_of_laboratory' => $type_of_laboratory,
                'timestamp' => time()
            );
            $this->db->insert('laboratory_results', $array);
            return array(
                'status' => 200,
                'message' => "Successfully!"
            );
        }
    }
    public function laboratoryResults(
        $parent_id,
        $id
    ) {
        $record_per_page = 10;
        $page = "";
        if(isset($id)) {
            $page = $id;
        } else {
            $page = 1;
        }

        $start_from = ($page-1) * $record_per_page;

        $array = array();
        if($parent_id != "") {
            $this->db->where("parent_id", $parent_id);
        }
        $this->db->limit($record_per_page, $start_from); 

        $q = $this->db->get('laboratory_results');
        if($q->num_rows() > 0) {
            foreach($q->result_array() as $row) {
                $getParent = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$row['parent_id']."'")->result_array();
                $getPatient = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$row['patient_id']."'")->result_array();
                $array[] = array(
                    'laboratory_results_id' => $row['laboratory_results_id'],
                    'parent_id' => $getParent[0]['parent_id'],
                    'parent_name' => $getParent[0]['parent_name'],
                    'patient_id' => $getPatient[0]['patient_id'],
                    'patient_name' => $getPatient[0]['patient_name'],
                    'date' => $row['date'],
                    'type_of_laboratory' => $row['type_of_laboratory'],
                    'file' => $row['file'],
                    'timestamp' => $row['timestamp'],
                    'pdf' => ($row['file']!="") ? md5($row['parent_id'] . ' ' . $row['parent_name'] . ' ' . $row['patient_id'] . ' ' . $row['patient_name'] . ' ' . $row['date'] . ' ' . $row['timestamp']) : ""
                );
            }
            return $array;
        } else {
            return array(
                'status' => 204,
                'message' => "Empty blanks!"
            );
        }
	}
}

?>