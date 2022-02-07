<?php $this->load->view('Power/navigation'); ?>
    <div class="panel panel-primary" style="border-radius: 0px; border: 0px;">
        <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
            Appointment Requests
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-3">
                    <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="form-control" style="height: 59px; margin-bottom: -10px;">
                        <option value="" selected="">Appointment Requests</option>
                        <option value="./appointment">Appointment</option>
                    </select>
                    <label>&nbsp;</label>
                </div>
                <div class="col-sm-3">
                    <?php
                    $array = array('','','','','');
                    if(isset($_GET['filter'])) {
                        if($_GET['filter'] == "") {
                            $array[0] = 'selected';
                        } else if($_GET['filter'] == "pending") {
                            $array[1] = 'selected';
                        } else if($_GET['filter'] == "approved") {
                            $array[2] = 'selected';
                        } else if($_GET['filter'] == "finished") {
                            $array[3] = 'selected';
                        } else {
                            header("Location: ?error");
                        }
                        /* else if($_GET['filter'] == "cancelled") {
                            $array[4] = 'selected';
                        }*/
                    }
                    ?>
                    <label>Filter</label>
                    <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="form-control" style="margin-bottom: 10px;">
                        <option value="?filter=<?php echo (htmlspecialchars(($_GET['date'] != "")) ? "&date=".htmlspecialchars($_GET['date']) : ""); ?><?php echo ((htmlspecialchars($_GET['doctor_id']) != "") ? "&doctor_id=".htmlspecialchars($_GET['doctor_id']) : ""); ?>" <?php echo $array[0]; ?>>Filter: All</option>
                        <option value="?filter=approved<?php echo (htmlspecialchars(($_GET['date']) != "") ? "&date=".htmlspecialchars($_GET['date']) : ""); ?><?php echo (htmlspecialchars(($_GET['doctor_id']) != "") ? "&doctor_id=".htmlspecialchars($_GET['doctor_id']) : ""); ?>" <?php echo $array[2]; ?>>Filter: Approved</option>
                        <option value="?filter=finished<?php echo (htmlspecialchars(($_GET['date']) != "") ? "&date=".htmlspecialchars($_GET['date']) : ""); ?><?php echo (htmlspecialchars(($_GET['doctor_id']) != "") ? "&doctor_id=".htmlspecialchars($_GET['doctor_id']) : ""); ?>" <?php echo $array[3]; ?>>Filter: Finished</option>
                        <!--<option value="?filter=cancelled" <?php echo $array[4]; ?>>Filter: Cancelled</option>-->
                    </select>
                </div>
                <?php
                if($level != "doctor") {
                ?>
                <div class="col-sm-3">
                    <label>Doctor</label>
                    <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="form-control" style="margin-bottom: 10px;">

                        <?php
                        $qds2 = $this->db->query("SELECT * FROM doctors_tbl");
                            if($qds2->num_rows() > 0) {
                            echo '<option value="?doctor_id='.((htmlspecialchars($_GET['filter']) != "") ? "&filter=".htmlspecialchars($_GET['filter']) : "").''.((htmlspecialchars($_GET['date']) != "") ? "&date=".htmlspecialchars($_GET['date']) : "").'" '.($row['doctor_id'] == htmlspecialchars($_GET['doctor_id']) ? "selected" : "").'>All</option>';
                            foreach($qds2->result_array() as $row) {
                                echo '<option value="?doctor_id='.htmlspecialchars($row['doctor_id']).''.((htmlspecialchars($_GET['filter']) != "") ? "&filter=".htmlspecialchars($_GET['filter']) : "").''.((htmlspecialchars($_GET['date']) != "") ? "&date=".htmlspecialchars($_GET['date']) : "").'" '.($row['doctor_id'] == htmlspecialchars($_GET['doctor_id']) ? "selected" : "").'>'.htmlspecialchars($row['doctor_name']).'</option>';
                            }
                        } else {
                            echo '<option>No data</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php
                }
                ?>
                <div class="col-sm-3">
                    <label>Date</label>
                    <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="form-control" style="margin-bottom: 10px;">

                    <?php
                    $qds = $this->db->query("SELECT * FROM appointments GROUP BY date_text");
                        if($qds->num_rows() > 0) {
                        echo '<option value="?date='.((htmlspecialchars($_GET['filter']) != "") ? "&filter=".htmlspecialchars($_GET['filter']) : "").''.((htmlspecialchars($_GET['doctor_id']) != "") ? "&doctor_id=".htmlspecialchars($_GET['doctor_id']) : "").'" '.($row['date_text'] == htmlspecialchars($_GET['date']) ? "selected" : "").'>All</option>';
                        foreach($qds->result_array() as $row) {
                            echo '<option value="?date='.$row['date_text'].''.((htmlspecialchars($_GET['filter']) != "") ? "&filter=".htmlspecialchars($_GET['filter']) : "").''.((htmlspecialchars($_GET['doctor_id']) != "") ? "&doctor_id=".htmlspecialchars($_GET['doctor_id']) : "").'" '.($row['date_text'] == htmlspecialchars($_GET['date']) ? "selected" : "").'>'.$row['date_text'].'</option>';
                        }
                    } else {
                        echo '<option>No data</option>';
                    }
                    ?>
                    </select>
                </div>
            </div>
            <div class="search">
                <?php
                    $textss = "";
                    if($this->session->selection == "doctor") {
                        $textss = "doctor";
                    } else if($this->session->selection == "receptionist") {
                        $textss = "receptionist";
                    } else if($this->session->selection == "administrator") {
                        $textss = "administrator";
                    } 
                    foreach($appointment_requests as $row) {
                        echo '<div class="appointment-requests-'.$row['appointment_id'].'">';
                            echo '<div class="appointment-heading" style="padding: 10px;box-shadow: 0 0 2px #000 inset;">';
                                //echo $row['parent_name'];
			    echo htmlspecialchars($row['parent_name'])." / <b>".htmlspecialchars($row['patient_name'])."</b> (".$row['appointment_datetime'].")";
                                echo '<div class="pull-right">';
                                if($row['appointment_status'] == "Finished") {
                                    echo '<label class="label label-primary">';
                                }
                                if($row['appointment_status'] == "Refund") {
                                    echo '<label class="label label-danger">';
                                }
                                if($row['appointment_status'] == "Pending") {
                                    echo '<label class="label label-warning">';
                                }
                                if($row['appointment_status'] == "Approved") {
                                    echo '<label class="label label-success">';
                                }
                                /*
                                if($row['appointment_status'] == "Cancelled") {
                                    echo '<label class="label label-success">';
                                }*/
                                        echo htmlspecialchars($row['appointment_status']);
                                    echo '</label>';
                                echo '</div>';
                                echo '<div style="clear:both;"></div>';
                            echo '</div>';
                            echo '<div class="appointment-body" style="background: rgb(244 244 244);display: none;word-break:break-all;height: 600px;overflow-y: scroll; padding: 20px;">';
                                echo '<b>Parent Name:</b><br/>';
                                echo htmlspecialchars($row['parent_name']);
                                echo '<br/><br/>';
                
                            echo '<label>Patient Name:</label><br/>';
                            echo htmlspecialchars($row['patient_name']);
                            echo '<br/><br/>';

                            echo '<b>Appointment Date:</b><br/>';
                            echo $row['appointment_datetime'];
                            //echo date('M d Y h:iA', $row['appointment_datetime']);
                            echo '<br/><br/>';

                            echo '<b>Appointment Date End:</b><br/>';
                            echo $row['appointment_datetime_end'];
                            //echo date('M d Y h:iA', $row['appointment_datetime_end']);
                            echo '<br/><br/>';

                            echo '<b>Description:</b><br/>';
                            echo htmlspecialchars($row['appointment_description']);
                            echo '<br/><br/>';

                            echo '<b>Money:</b> P'.htmlspecialchars($row['money']);
                            echo '<br/><br/>';
                                

                            echo '<b>Reference Number:</b> '.htmlspecialchars($row['reference_number']);
                            echo '<br/><br/>';
                            
                            /*
                            if($row['appointment_status'] == "Finished") {
                                echo '<br/><br/>';
                                echo '<b>Approved by:</b> '.htmlspecialchars($row['staff_name']);
                            }*/


                            if($row['appointment_status'] == "Finished") {
                                
                            } else {
                                echo '<hr/>';
                                
                                if($row['appointment_status'] == "Pending") {

                                    echo '<div class="row">';
                                        echo '<div class="col-md-4">';
                                            echo '<a href="'.base_url().''.$textss.'/approveAppointmentRequest/'.$row['appointment_id'].'"><button id="donePrescription" data-id="'.$row['appointment_id'].'" class="btn btn-success btn-block">Approve Appointment Request</button></a>';
                                        echo '</div>';
                                        /*
                                        echo '<div class="col-md-4">';
                                            echo '<a href="'.base_url().''.$textss.'/cancelAppointmentRequest/'.$row['appointment_id'].'"><button class="btn btn-success btn-block">Cancel Appointment Request</button></a>';
                                        echo '</div>';*/
                                    echo '</div>';
                                } else if($row['appointment_status'] != "Refund") {
                                    if($row['interview_id'] == "0") {
                                        echo '<label>Interview:</label><br/>';
                                    
                                        if(isset($_POST['submitDoctorInterview'])) {
                                            $doctor_id = $_POST['doctor_id'];

                                            $displays = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$doctor_id."'");
                                            if($displays->num_rows() > 0) {
                                                // exist
                                                $this->db->query("UPDATE `appointments` SET `interview_id` = '".$doctor_id."' WHERE appointment_id = '".$row['appointment_id']."'"); // delete all
                                                redirect(base_url() . $textss .'/appointment_requests?success=Successfully!');
                                            } else {
                                                redirect(base_url() . $textss .'/appointment_requests?error=Error! There\'s something wrong!');
                                            }
                                        
                                        }
                                        echo '<form action="" method="POST" style="margin-bottom: 20px;">';
                                            echo '<div class="row">';
                                                echo '<div class="col-md-6">';
                                                    echo '<select name="doctor_id" class="form-control" style="width:20%;">';
                                                        $displays2 = $this->db->query("SELECT * FROM `doctors_tbl`");
                                                        if($displays2->num_rows() > 0) {
                                                            //exist
                                                            foreach($displays2->result_array() as $display_row2) {
                                                                echo '<option value="'.$display_row2['doctor_id'].'" selected="">'.htmlspecialchars($display_row2['doctor_name']).'</option>';
                                                            }
                                                        } else {
                                                            // not exist
                                                            echo '<option>No doctor found.</option>';
                                                        }
                                                    echo '</select>';
                                                    echo '<br/>';
                                                    echo '<button class="btn btn-primary" name="submitDoctorInterview" style="margin-top:20px;">Submit Doctor Interview</button>';
                                                echo '</div>';
                                            echo '</div>';
                                        echo '</form>';
                                    } else {
                                        
                                        
                                        if(isset($_POST['submitCommonIllness'])) {
                                            $id = $_POST['id'];
                                            $asdasd2 = $this->db->query("SELECT * FROM appointments WHERE appointment_id = '".$id."'");
                                            if($asdasd2->num_rows() > 0 ) {
                                            } else {
                                                redirect($textss."/appointment_requests?error=There\'s something wrong.");
                                            }
                                                
                                            $this->db->query("DELETE FROM patients_illness_tbl WHERE `type` = 'appointments' AND id = '".$id."'"); // delete all
                                            $common_illness = $_POST['common_illness'];
            
            
                                            $countError =0;
                                            foreach($common_illness as $rowzxc1) {
                                                $asdasd = $this->db->query("SELECT * FROM terms_tbl WHERE terms_id = '".$rowzxc1."'");
                                                if($asdasd->num_rows() > 0 ) {
                                                    // exist
                                                    //$display = $asdasd->result_array();
                                                    //echo $display[0]['terms_title'];
                                                    //echo '<br/>';
                                                } else {
                                                    $countError++;
                                                }
                                            }
            
                                            if($countError != 0) {
                                                redirect($textss."/appointment_requests?error=There\'s something wrong.");
                                            }
                                            foreach($common_illness as $rowzxc) {
                                                $asdasddd = $this->db->query("SELECT * FROM terms_tbl WHERE terms_id = '".$rowzxc."'")->result_array();
                                                $this->db->query("INSERT INTO `patients_illness_tbl` (`type`, `id`, `terms_id`,`terms_title`,`timestamp`) VALUES('appointments', '".$id."', '".$rowzxc."', '".$asdasddd[0]['terms_title']."','".time()."')");
                                                
                                            }
                                            redirect(base_url() . $textss .'/appointment_requests?success=Successfully!');
                                        }
                                        echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
                                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />';
                                        echo '<label>Common Illness:</label><br/>';
                                        echo '<form action="" method="POST" style="margin-bottom: 20px;">';
                                            echo '<div class="row">';
                                                echo '<div class="col-md-6">';
                                                echo '<input type="hidden" name="id" value="'.$row['appointment_id'].'">';
                                                echo '<select id="common_illness" name="common_illness[]" multiple class="form-control common_illness">';
                                                        $displays = $this->db->query("SELECT * FROM `terms_tbl`");
                                                        foreach($displays->result_array() as $display_row) {
                                                            $ifSelected = $this->db->query("SELECT * FROM `patients_illness_tbl` WHERE `type` = 'appointments' AND `id` = '".$row['appointment_id']."' AND `terms_id` = '".$display_row['terms_id']."'");

                                                            if($ifSelected->num_rows() > 0) {
                                                                //exist
                                                                echo '<option value="'.$display_row['terms_id'].'" selected="">'.$display_row['terms_title'].'</option>';
                                                            } else {
                                                                // not exist
                                                                echo '<option value="'.$display_row['terms_id'].'">'.$display_row['terms_title'].'</option>';
                                                            }
                                                            
                                                        }
                                                    echo '</select>';
                                                    echo '<button class="btn btn-primary" name="submitCommonIllness" style="margin-left:20px;">Submit Common Illness</button>';
                                                echo '</div>';
                                            echo '</div>';
                                        echo '</form>';
                                        echo"<script>
                                            $(document).ready(function() {
                                                $('.common_illness').multiselect({
                                                nonSelectedText: 'Select Common Illness',
                                                enableFiltering: true,
                                                enableCaseInsensitiveFiltering: true,
                                                buttonWidth:'400px'
                                                });
                                            });
                                        </script>";

                                        echo '<br/><br/>';

                                        echo '<div class="row">';
                                        /*
                                            echo '<div class="col-md-4">';
                                                echo '<a href="'.base_url().''.$textss.'/cancelAppointmentRequest/'.$row['appointment_id'].'"><button class="btn btn-success btn-block">Cancel Appointment Request</button></a>';
                                            echo '</div>';*/
                                            echo '<div class="col-md-4">';
                                                echo '<a href="'.base_url().''.$textss.'/doneAppointmentRequest/'.$row['appointment_id'].'"><button id="donePrescription" data-id="'.$row['appointment_id'].'" class="btn btn-success btn-block">Done Appointment Request</button></a>';
                                            echo '</div>';
                                        echo '</div>';
                                    }
                                    
                                }
                            }
                            echo '</div>';
                        echo '</div>';
                    }

                    echo $links;
                ?>
                <script type="text/javascript">
                    $(document).ready(function() {

                        $('.appointment-heading').click(function() {
                            $(this).siblings(".appointment-body").slideToggle();
                            console.log(0);    
                        });

                        $('.pot').click(function() {
                            $('#viewModal').modal('show');
                            var image = $(this).attr("src");
                            $("#POT").attr("src", image);
                        });
                    });
                </script>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewModalLabel">Proof of Transaction</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <img src="#" id="POT" style="width:100%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
