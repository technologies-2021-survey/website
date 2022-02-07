<?php $this->load->view('Power/navigation'); ?>
    <div class="panel panel-primary" style="border-radius: 0px; border: 0px;">
        <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
        Online Consultation Request
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-3">
                    <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="form-control" class="form-control" style="height: 59px; margin-bottom: -10px;">
                        <option value="" selected="">Online Consultation Requests</option>
                        <option value="./consultation">Online Consultation</option>
                    </select>
                    <label>&nbsp;</label>

                    <script type="text/javascript">
                        $(document).ready(function() {

                            $('.consultation-heading').click(function() {
                                if ($(this).siblings(".consultation-body").css('display') == 'none') {
                                    $(".consultation-body").fadeOut();
                                    $(this).siblings(".consultation-body").slideToggle();
                                    console.log(0);
                                } else {
                                    $(this).siblings(".consultation-body").slideToggle();
                                }  
                            });

                            $('.pot').click(function() {
                                $('#viewModal').modal('show');
                                var image = $(this).attr("src");
                                $("#POT").attr("src", image);
                            });

                            $('.addPrescription').on("click", function() {
                                var hid = $(this).attr("data-id");
                                $('input[name=prescription_id]').attr("value", hid);
                                $('#addPrescriptionModal').modal('show');
                            });
                        });
                    </script>
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
                        
                        /*else if($_GET['filter'] == "cancelled") {
                            $array[4] = 'selected';
                        }*/
                    }
                    ?>
                    <label>Filter</label>
                    <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="form-control" style="margin-bottom: 10px;">
                        <option value="?filter=" <?php echo $array[0]; ?>>Filter: All</option>
                        <option value="?filter=approved" <?php echo $array[2]; ?>>Filter: Approved</option>
                        <option value="?filter=finished" <?php echo $array[3]; ?>>Filter: Finished</option>
                        <!--<option value="?filter=cancel" <?php echo $array[4]; ?>>Filter: Cancelled</option>-->
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
                                echo '<option value="?doctor_id='.$row['doctor_id'].''.((htmlspecialchars($_GET['filter']) != "") ? "&filter=".htmlspecialchars($_GET['filter']) : "").''.((htmlspecialchars($_GET['date']) != "") ? "&date=".htmlspecialchars($_GET['date']) : "").'" '.($row['doctor_id'] == htmlspecialchars($_GET['doctor_id']) ? "selected" : "").'>'.htmlspecialchars($row['doctor_name']).'</option>';
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
                            echo '<option value="?date='.$row['date_text'].''.((htmlspecialchars($_GET['filter']) != "") ? "&filter=".htmlspecialchars($_GET['filter']) : "").''.((htmlspecialchars($_GET['doctor_id']) != "") ? "&doctor_id=".htmlspecialchars($_GET['doctor_id']) : "").'" '.($row['date_text'] == htmlspecialchars($_GET['date']) ? "selected" : "").'>'.htmlspecialchars($row['date_text']).'</option>';
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
                    foreach($consultations as $row) {
                        echo '<div class="consultation-'.$row['consultation_id'].'">';
                            echo '<div class="consultation-heading" style="padding: 10px;box-shadow: 0 0 2px #000 inset;">';
                                //echo $row['parent_name'];
						echo $row['parent_name']." / <b>".$row['patient_name']."</b> (".$row['date_consultation_datetime'].")";
                                echo '<div class="pull-right">';
                                    if($row['consultation_status'] == "Finished") {
                                        if($row['send_it_to_parent'] == "") {
                                            //no texist
                                            echo '<label class="label label-warning">';
                                        } else {
                                            echo '<label class="label label-primary">';
                                            
                                        }
                                    }
                                    if($row['consultation_status'] == "Refund") {
                                        echo '<label class="label label-danger">';
                                    }
                                    if($row['consultation_status'] == "Pending") {
                                        echo '<label class="label label-warning">';
                                    }
                                    if($row['consultation_status'] == "Approved") {
                                        echo '<label class="label label-success">';
                                    }
                                    /*
                                    if($row['consultation_status'] == "Cancelled") {
                                        echo '<label class="label label-default">';
                                    }*/
                                        if($row['consultation_status'] == "Finished") {
                                            if($row['send_it_to_parent'] == "") {
                                                if($row['interview_id'] != "") {
                                                    $getDoctor = $this->db->query("SELECT * FROM `doctors_tbl` WHERE doctor_id = '".$row['interview_id']."'")->result_array();
                                                    echo htmlspecialchars('Finished / '.$getDoctor[0]['doctor_name'] . '\'s signature');
                                                }
                                            } else {
                                                echo htmlspecialchars($row['consultation_status']);
                                            }
                                        } else {
                                            echo htmlspecialchars($row['consultation_status']);
                                        }
                                    echo '</label>';
                                echo '</div>';
                                echo '<div style="clear:both;"></div>';
                            echo '</div>';
                            echo '<div class="consultation-body" style="background: rgb(244 244 244);display: none;word-break:break-all;height: 600px;overflow-y: scroll; padding: 20px;">';
                                echo '<label>Parent Name:</label><br/>';
                                echo htmlspecialchars($row['parent_name']);
                                echo '<br/><br/>';

                                echo '<label>Patient Name:</label><br/>';
                                echo htmlspecialchars($row['patient_name']);
                                echo '<br/><br/>';

                                echo '<label>Date Consultation:</label><br/>';
                                echo $row['date_consultation_datetime'];
                                echo '<br/><br/>';

                                echo '<label>Date Consultation End:</label><br/>';
                                echo $row['date_consultation_datetime_end'];
                                echo '<br/><br/>';

                                echo '<label>Reason:</label><br/>';
                                echo htmlspecialchars($row['reason']);
                                echo '<br/><br/>';

                                echo '<label>Money:</label> P'.htmlspecialchars($row['money']);
                                echo '<br/><br/>';

                                echo '<label>Health History:</label> P'.htmlspecialchars($row['healthHistory']);
                                echo '<br/><br/>';

                                echo '<label>Did your child take any medication?:</label> P'.htmlspecialchars($row['anyMedication']);
                                echo '<br/><br/>';

                                echo '<label>Any Allergies:</label> P'.htmlspecialchars($row['anyAllergies']);
                                echo '<br/><br/>';
                                                            
                                if($row['consultation_prescription'] != "") {
                                    echo '<label>Prescriptions:</label><br/>';
                                    echo htmlspecialchars($row['consultation_prescription']);
                                }
                                if($row['consultation_status'] == "Finished") {
                                    
                                    // invoke upload_file function and pass your input as a parameter
                                    $encoded_string = !empty($_POST['base64_file']) ? $_POST['base64_file'] : 'V2ViZWFzeXN0ZXAgOik=';
                                   

                                    if(isset($_POST['submitUpload'])) {
                                        $file = $_POST['file'];
                                        
                                        $filename = $_FILES['file']['name'];
                                        $ext = pathinfo($filename, PATHINFO_EXTENSION);
                                        $allowed = array('pdf');
                                        if( ! in_array( $ext, $allowed ) ) {
                                            redirect($textss."/consultation_requests?error=PDF only!");
                                        }

                                        $_imagePost = file_get_contents($_FILES['file']['tmp_name']);

                                        $id = htmlspecialchars($_POST['id']);
                                        $resultsz = $this->db->query("SELECT * FROM `consultations` WHERE `consultation_id` = '".$id."'");

                                        if($resultsz->num_rows() > 0) {
                                            //$resultfff = $this->power_model->upload_file($encoded_string);
                                            //if($resultfff != false) {

                                                $this->db->query("UPDATE `consultations` SET `send_it_to_parent` = '".base64_encode($_imagePost)."' WHERE consultation_id = '".$id."'");
                                                redirect($textss."/consultation_requests?success=Successfully uploaded!");
                                            //} else {
                                            //    redirect($textss."/consultation_requests?error=Error! There\'s something error!");
                                            //}
                                        } else {
                                            redirect($textss."/consultation_requests?error=Error! There\'s something error!");
                                        }
                                    }
                                    echo '<hr/>';
                                    echo '<div class="row">';
                                        echo '<div class="col-md-6">';
                                            echo 'Download the PDF:<br/>';
                                            $string = $row['date_consultation_datetime'] . ' ' . $row['date_consultation_datetime_end'];
                                            echo '<a href="#" onclick="window.open(\''.base_url().'/home/generate_to_pdf/'.md5($string).'\')" class="btn btn-success" target="_blank">Download</a>';
                                            echo '</div>';
                                        echo '<div class="col-md-6">';
                                                echo '<form action="" method="post" enctype="multipart/form-data">
                                                <input type="hidden" name="id" value="'.$row['consultation_id'].'">
                                                Please upload here the pdf with signature:<br/>
                                                <input type="file" name="file" class="form-control" style="margin-bottom:10px;">
                                                <input type="submit" name="submitUpload" value="Upload" class="btn btn-success">
                                            </form>';
                                        echo '</div>';
                                    echo '</div>';
                                    if($row['send_it_to_parent'] != "") {
                                        /*
                                        echo '<div class="row">';
                                            echo '<div class="col-md-6">';
                                                echo 'Your upload pdf:<br/>';
                                                echo '<iframe width="100%" height="100%" name="plugin" src="" style="height: 500px;"></iframe>';
                                                echo '<a href="#" onclick="window.open(\'data:application/pdf;base64,'.$row['send_it_to_parent'].'\')" class="btn btn-success">View</a>';
                                                
                                            echo '</div>';
                                        echo '</div>';*/
                                    }
                                } else {
                                    echo '<hr/>';

                                    if($row['consultation_status'] == "Pending") {
                                        echo '<div class="row">';
                                            echo '<div class="col-md-4">';
                                                echo '<a href="'.base_url().''.$textss.'/approveConsultation/'.$row['consultation_id'].'"><button id="donePrescription" data-id="'.$row['consultation_id'].'" class="btn btn-success btn-block">Approve Consultation</button></a>';
                                            echo '</div>';
                                            /*
                                            echo '<div class="col-md-4">';
                                                echo '<a href="'.base_url().''.$textss.'/cancelConsultation/'.$row['consultation_id'].'"><button class="btn btn-success btn-block">Cancel Consultation</button></a>';
                                            echo '</div>';
                                            */
                                        echo '</div>';
                                    } else {
                                        if($row['interview_id'] == "0") {
                                            echo '<label>Interview:</label><br/>';
                                            
                                            if(isset($_POST['submitDoctorInterview'])) {
                                                $doctor_id = $_POST['doctor_id'];

                                                $displays = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$doctor_id."'");
                                                if($displays->num_rows() > 0) {
                                                    // exist
                                                    $this->db->query("UPDATE `consultations` SET `interview_id` = '".$doctor_id."' WHERE consultation_id = '".$row['consultation_id']."'"); // delete all
                                                    redirect(base_url() . $textss .'/consultation_requests?success=Successfully!');
                                                } else {
                                                    redirect(base_url() . $textss .'/consultation_requests?error=Error! There\'s something wrong!');
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
                                                                    echo '<option value="'.$display_row2['doctor_id'].'">'.htmlspecialchars($display_row2['doctor_name']).'</option>';
                                                                }
                                                            } else {
                                                                // not exist
                                                                echo '<option>No doctor found.</option>';
                                                            }
                                                        echo '</select>';
                                                        echo '<button class="btn btn-primary" name="submitDoctorInterview" style="margin-top:20px;">Submit Doctor Interview</button>';
                                                    echo '</div>';
                                                echo '</div>';
                                            echo '</form>';
                                        } else {
                                            // not exist
                                            
                                            if(isset($_POST['submitCommonIllness'])) {
                                                $id = $_POST['id'];
                                                $asdasd2 = $this->db->query("SELECT * FROM consultations WHERE consultation_id = '".$id."'");
                                                if($asdasd2->num_rows() > 0 ) {
                                                } else {
                                                    redirect($textss."/consultation_requests?error=There\'s something wrong.");
                                                }

                                                $this->db->query("DELETE FROM patients_illness_tbl WHERE `type` = 'consultations' AND id = '".$id."'"); // delete all
                                                $common_illness = $_POST['common_illness'];
                
                
                                                $countError =0;
                                                foreach($common_illness as $rowzxc1) {
                                                    $asdasd = $this->db->query("SELECT * FROM terms_tbl WHERE terms_id = '".$rowzxc1."'");
                                                    if($asdasd->num_rows() > 0 ) {
                                                        // exist
                                                    } else {
                                                        $countError++;
                                                    }
                                                }

                                                if($countError != 0) {
                                                    redirect($textss."/consultation_requests?error=There\'s something wrong.");
                                                }
                                                foreach($common_illness as $rowzxc) {
                                                    $asdasddd = $this->db->query("SELECT * FROM terms_tbl WHERE `terms_id` = '".$rowzxc."'")->result_array();
                                                    $this->db->query("INSERT INTO `patients_illness_tbl` (`type`, `id`, `terms_id`,`terms_title`, `timestamp`) VALUES('consultations', '".$id."', '".$rowzxc."', '".$asdasddd[0]['terms_title']."','".time()."')");
                                                }
                                                redirect(base_url() . $textss .'/consultation_requests?success=Successfully!');
                                            }
                                            echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
                                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />';
                                            echo '<label>Common Illness:</label><br/>';
                                            echo '<form action="" method="POST" style="margin-bottom: 20px;">';
                                            echo '<div class="row">';
                                                echo '<div class="col-md-6">';
                                                echo '<input type="hidden" name="id" value="'.$row['consultation_id'].'">';
                                                    echo '<select id="common_illness" name="common_illness[]" multiple class="form-control common_illness">';
                                                        $displays = $this->db->query("SELECT * FROM `terms_tbl`");
                                                        foreach($displays->result_array() as $display_row) {
                                                            $ifSelected = $this->db->query("SELECT * FROM `patients_illness_tbl` WHERE `type` = 'consultations' AND `id` = '".$row['consultation_id']."' AND `terms_id` = '".$display_row['terms_id']."'");

                                                            if($ifSelected->num_rows() > 0) {
                                                                //exist
                                                                echo '<option value="'.$display_row['terms_id'].'" selected="">'.htmlspecialchars($display_row['terms_title']).'</option>';
                                                            } else {
                                                                // not exist
                                                                echo '<option value="'.$display_row['terms_id'].'">'.htmlspecialchars($display_row['terms_title']).'</option>';
                                                            }
                                                            
                                                        }
                                                    echo '</select>';
                                                    echo '<br/>';
                                                    echo '<button class="btn btn-primary" name="submitCommonIllness" style="margin-top:20px;">Submit Common Illness</button>';
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
                                            echo '<form action="'.base_url().''.$textss.'/sendlink" method="POST" style="margin-bottom: 20px;">';
                                                echo '<input type="hidden" name="id" value="'.$row['consultation_id'].'">';
                                                echo '<div class="form-group">';
                                                    echo "<label>Choice of Meeting:</label> ".$row['choice'];
                                                    echo '<br/><label>Zoom or Google Link</label>';
                                                    echo '<div class="input-group">';
                                                        echo '<script type="text/javascript">
                                                        $(document).ready(function() {
                                                            var placeholder = "https://meet.google.com/......\n\nor\n\n<Doctor name> is inviting you to a scheduled Zoom Meeting.\n\nJoin Zoom Meeting\n<Zoom Link>\n\nMeeting ID: <Meeting ID>\nPasscode: <Passcode>";
                                                            $(\'textarea[name=googlelink]\').attr(\'placeholder\', placeholder);
                                                        });
                                                        </script>';
                                                        echo '<textarea name="googlelink" class="form-control" style="height: 170px;">'.$row['googlelink'].'</textarea>';
                                                        echo '<div class="input-group-btn">';
                                                            echo '<span class="input-group-btn">';
                                                                echo '<button class="btn btn-success" type="submit" name="submit">Send!</button>';
                                                            echo '</span>';
                                                        echo '</div>';
                                                    echo '</div>';
                                                echo '</div>';
                                            echo '</form>';

                                            echo '<div class="row">';
                                                echo '<div class="col-md-4">';
                                                    echo '<button data-id="'.$row['consultation_id'].'" class="addPrescription btn btn-success btn-block">Add Prescription</button>';
                                                echo '</div>';
                                                /*
                                                echo '<div class="col-md-4">';
                                                    echo '<a href="'.base_url().''.$textss.'/cancelConsultation/'.$row['consultation_id'].'"><button class="btn btn-success btn-block">Cancel Consultation</button></a>';
                                                echo '</div>';*/
                                                echo '<div class="col-md-4">';
                                                    echo '<a href="'.base_url().''.$textss.'/doneConsultation/'.$row['consultation_id'].'"><button id="donePrescription" data-id="'.$row['consultation_id'].'" class="btn btn-success btn-block">Done Consultation</button></a>';
                                                echo '</div>';
                                            echo '</div>';
                                        }
                                        
                                    }
                                }
                            echo '</div>';
                        echo '</div>';
                    }
                ?>
                
            </div>
            <?php
                echo $links;
            ?>
            <!-- Modal -->
            <div class="modal fade" id="viewModal" class="viewModals" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewModalLabel">Proof of Transaction</h5>
                            <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div style="clear:both;"></div>
                        </div>
                        <div class="modal-body">
                            <img src="#" id="POT" style="width:100%;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="addPrescriptionModal" class="addPrescriptionModals" tabindex="-1" role="dialog" aria-labelledby="addPrescriptionModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addPrescriptionModalLabel">Add Prescription</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="<?php echo base_url().$textss; ?>/addPrescription" method="POST">
                                <input type="hidden" name="prescription_id">
                                <div class="form-group">
                                    <label>Add Prescription</label>
                                    <textarea class="form-control" name="prescription" style="height: 200px;margin-bottom: 10px;"></textarea>
                                    <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
