<?php $this->load->view('Power/navigation'); ?>
<?php
$textss = "";
if($this->session->selection == "doctor") {
    $textss = "doctor";
} else if($this->session->selection == "receptionist") {
    $textss = "receptionist";
} else if($this->session->selection == "administrator") {
    $textss = "administrator";
} 
?>
    <div class="panel panel-primary" style="border-radius: 0px; border: 0px;">
        <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
            Medical Certificate
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-4">
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
                        <option value="?filter=pending<?php echo (htmlspecialchars(($_GET['date']) != "") ? "&date=".htmlspecialchars($_GET['date']) : ""); ?><?php echo (htmlspecialchars(($_GET['doctor_id']) != "") ? "&doctor_id=".htmlspecialchars($_GET['doctor_id']) : ""); ?>" <?php echo $array[1]; ?>>Filter: Pending</option>
                        <option value="?filter=approved<?php echo (htmlspecialchars(($_GET['date']) != "") ? "&date=".htmlspecialchars($_GET['date']) : ""); ?><?php echo (htmlspecialchars(($_GET['doctor_id']) != "") ? "&doctor_id=".htmlspecialchars($_GET['doctor_id']) : ""); ?>" <?php echo $array[2]; ?>>Filter: Approved</option>
                        <option value="?filter=finished<?php echo (htmlspecialchars(($_GET['date']) != "") ? "&date=".htmlspecialchars($_GET['date']) : ""); ?><?php echo (htmlspecialchars(($_GET['doctor_id']) != "") ? "&doctor_id=".htmlspecialchars($_GET['doctor_id']) : ""); ?>" <?php echo $array[3]; ?>>Filter: Finished</option>
                        <!--<option value="?filter=cancelled" <?php echo $array[4]; ?>>Filter: Cancelled</option>-->
                    </select>
                </div>
                <?php
                    if($this->session->selection != "doctor") {
                ?>
                <div class="col-sm-4">
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
                <div class="col-sm-4">
                    <label>Date</label>
                    <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="form-control" style="margin-bottom: 10px;">

                    <?php
                    $qds = $this->db->query("SELECT * FROM medical_certificates GROUP BY date_text");
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
                    foreach($medical_certificates as $row) {
                        echo '<div class="medical-certificates-'.$row['medical_certificates_id'].'">';
                            echo '<div class="medical-certificates-heading" style="padding: 10px;box-shadow: 0 0 2px #000 inset;">';
                                //echo $row['parent_name'];
			                    echo htmlspecialchars($row['parent_name'])." / <b>".htmlspecialchars($row['patient_name'])."</b> (".date("M d, Y h:iA",$row['timestamp']).")";
                                echo '<div class="pull-right">';
                                if($row['status'] == "Finished") {
                                    if($row['send_it_to_parent'] == "") {
                                        if($row['interview_id'] != "") {
                                            echo '<label class="label label-warning">';
                                        }
                                    } else {
                                        echo '<label class="label label-primary">';
                                    }
                                }
                                if($row['status'] == "Refund") {
                                    echo '<label class="label label-danger">';
                                }
                                if($row['status'] == "Pending") {
                                    echo '<label class="label label-warning">';
                                }
                                if($row['status'] == "Approved") {
                                    echo '<label class="label label-success">';
                                }
                                        if($row['status'] == "Finished") {
                                            if($row['send_it_to_parent'] == "") {
                                                if($row['interview_id'] != "") {
                                                    $getDoctor = $this->db->query("SELECT * FROM `doctors_tbl` WHERE doctor_id = '".$row['interview_id']."'")->result_array();
                                                    echo htmlspecialchars('Finished / '.$getDoctor[0]['doctor_name'] . '\'s signature');
                                                }
                                            } else {
                                                echo htmlspecialchars($row['status']);
                                            }
                                        } else {
                                            echo htmlspecialchars($row['status']);
                                        }
                                    echo '</label>';
                                echo '</div>';
                                echo '<div style="clear:both;"></div>';
                            echo '</div>';
                            echo '<div class="medical-certificates-body" style="background: rgb(244 244 244);display: none;word-break:break-all;height: 600px;overflow-y: scroll; padding: 20px;">';
                                echo '<b>Parent Name:</b><br/>';
                                echo htmlspecialchars($row['parent_name']);
                                echo '<br/><br/>';
                
                            echo '<label>Patient Name:</label><br/>';
                            echo htmlspecialchars($row['patient_name']);
                            echo '<br/><br/>';

                            echo '<b>Date of Consultation:</b><br/>';
                            echo htmlspecialchars($row['date_text']);
                            echo '<br/><br/>';

                            echo '<b>Money:</b> P'.htmlspecialchars($row['money']);
                            echo '<br/><br/>';
                                

                            echo '<b>Reference Number:</b> '.htmlspecialchars($row['reference_number']);
                            echo '<br/><br/>';

                            
                            if($row['status'] == "Finished") {
                                echo '<br/><br/>';
                                echo '<b>Approved by:</b> '.htmlspecialchars($row['staff_name']);
                            }


                            if($row['status'] == "Finished") {
                                // invoke upload_file function and pass your input as a parameter
                                $encoded_string = !empty($_POST['base64_file']) ? $_POST['base64_file'] : 'V2ViZWFzeXN0ZXAgOik=';
                                   

                                if(isset($_POST['submitUpload'])) {
                                    $file = $_POST['file'];
                                    
                                    $filename = $_FILES['file']['name'];
                                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                                    $allowed = array('pdf');
                                    if( ! in_array( $ext, $allowed ) ) {
                                        redirect($textss."/medical_certificate?error=PDF only!");
                                    }

                                    $_imagePost = file_get_contents($_FILES['file']['tmp_name']);

                                    $id = htmlspecialchars($_POST['id']);
                                    $resultsz = $this->db->query("SELECT * FROM `medical_certificates` WHERE `medical_certificates_id` = '".$id."'");

                                    if($resultsz->num_rows() > 0) {
                                        //$resultfff = $this->power_model->upload_file($encoded_string);
                                        //if($resultfff != false) {

                                            $this->db->query("UPDATE `medical_certificates` SET `send_it_to_parent` = '".base64_encode($_imagePost)."' WHERE medical_certificates_id = '".$id."'");
                                            redirect($textss."/medical_certificate?success=Successfully uploaded!");
                                        //} else {
                                        //    redirect($textss."/medical_certificate?error=Error! There\'s something error!");
                                        //}
                                    } else {
                                        redirect($textss."/medical_certificate?error=Error! There\'s something error!");
                                    }
                                }
                                echo '<hr/>';
                                echo '<div class="row">';
                                    echo '<div class="col-md-6">';
                                        echo 'Download the PDF:<br/>';
                                        $string = md5($row['date_of_consultation'] . ' ' . $row['date_text'] . ' ' . $row['reference_number'] . ' ' . $row['timestamp']);
                                        echo '<a href="#" onclick="window.open(\''.base_url().'index.php/home/generate_to_pdf2/'.$string.'\')" class="btn btn-success" target="_blank">Download</a>';
                                        echo '</div>';
                                    echo '<div class="col-md-6">';
                                            echo '<form action="" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="id" value="'.$row['medical_certificates_id'].'">
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
                                            echo '<iframe width="100%" height="100%" name="plugin" src="data:application/pdf;base64,'.$row['send_it_to_parent'].'" style="height: 500px;"></iframe>';
                                        echo '</div>';
                                    echo '</div>';*/
                                }
                            } else {
                                echo '<hr/>';
                                
                                if($row['status'] == "Pending") {

                                    echo '<div class="row">';
                                        echo '<div class="col-md-4">';
                                            echo '<a href="'.base_url().''.$textss.'/approveMedicalCertificate/'.$row['medical_certificates_id'].'"><button id="donePrescription" data-id="'.$row['medical_certificates_id'].'" class="btn btn-success btn-block">Approve Medical Certificate Request</button></a>';
                                        echo '</div>';
                                        /*
                                        echo '<div class="col-md-4">';
                                            echo '<a href="'.base_url().''.$textss.'/cancelMedicalCertificate/'.$row['medical_certificates_id'].'"><button class="btn btn-success btn-block">Cancel Medical Certificate Request</button></a>';
                                        echo '</div>';*/
                                    echo '</div>';
                                } else if($row['status'] != "Refund") {
                                    if($row['interview_id'] == "0") {
                                        echo '<label>Interview:</label><br/>';
                                    
                                        if(isset($_POST['submitDoctorInterview'])) {
                                            $doctor_id = $_POST['doctor_id'];

                                            $displays = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$doctor_id."'");
                                            if($displays->num_rows() > 0) {
                                                // exist
                                                $this->db->query("UPDATE `medical_certificates` SET `interview_id` = '".$doctor_id."' WHERE medical_certificates_id = '".$row['medical_certificates_id']."'"); // delete all
                                                redirect(base_url() . $textss .'/medical_certificate?success=Successfully!');
                                            } else {
                                                redirect(base_url() . $textss .'/medical_certificate?error=Error! There\'s something wrong!');
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
                                                    echo '<button class="btn btn-primary" name="submitDoctorInterview" style="margin-top:20px;">Submit Doctor Interview</button>';
                                                echo '</div>';
                                            echo '</div>';
                                        echo '</form>';
                                    } else {
                                        if(isset($_POST['submit'])) {
                                            $medical_certificates_id = $_POST['id'];
                                            $q = $this->db->query("SELECT * FROM `medical_certificates` WHERE `medical_certificates_id` = '".$medical_certificates_id."'");
                                            if($q->num_rows() > 0) {
                                                // exist
                                                $diagnosis = htmlspecialchars($_POST['diagnosis']);
                                                $treatment_and_recommendation = htmlspecialchars($_POST['treatment_and_recommendation']);
                                                $purpose_doctor = htmlspecialchars($_POST['purpose_doctor']);
                                                $array = array(
                                                    'diagnosis' => $diagnosis,
                                                    'treatment_and_recommendation' => $treatment_and_recommendation,
                                                    'purpose_doctor' => $purpose_doctor
                                                );

                                                $this->db->where('medical_certificates_id', $medical_certificates_id);
		                                        $this->db->update('medical_certificates', $array);

                                                redirect($textss.'/medical_certificate?success=Successfully added!');
                                            } else {
                                                // not exist
                                                redirect($textss.'/medical_certificate?error=There\'s something wrong!');
                                            }
                                        }
                                        echo '<div style="background: #fff; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">';
                                            echo '<h4>Add Medical Certificate</h4>';
                                            echo '<form action="" method="POST">';
                                                echo '<input type="hidden" name="id" value="'.$row['medical_certificates_id'].'">';
                                                echo '<label>Diagnosis</label>';
                                                echo '<textarea class="form-control" name="diagnosis" required="" style="margin-bottom: 10px;" placeholder="Diagnosis">'.$row['diagnosis'].'</textarea>';
                                                echo '<label>Treatment/Recommendation</label>';
                                                echo '<textarea class="form-control" name="treatment_and_recommendation" required="" style="margin-bottom: 10px;" placeholder="Treatment/Recommendation">'.$row['treatment_and_recommendation'].'</textarea>';
                                                echo '<label>Purpose</label>';
                                                echo '<textarea class="form-control" name="purpose_doctor" required="" style="margin-bottom: 10px;" placeholder="Purpose">'.$row['purpose_doctor'].'</textarea>';
                                                echo '<div class="pull-right">';
                                                    echo '<button class="btn btn-success" name="submit">Add Certificate</button>';
                                                echo '</div>';
                                                echo '<div class="pull-left">';
                                                    $string = md5($row['date_of_consultation'] . ' ' . $row['date_text'] . ' ' . $row['reference_number'] . ' ' . $row['timestamp']);
                                                    echo '<a href="#" onclick="window.open(\''.base_url().'index.php/home/generate_to_pdf2/'.$string.'\')" class="btn btn-success" target="_blank">Download and View PDF</a>';
                                                echo '</div>';
                                                echo '<div style="clear:both;"></div>';
                                            echo '</form>';
                                        echo '</div>';
                                        echo '<hr/>';
                                        echo '<div class="row">';
                                            echo '<div class="col-md-4">';
                                                echo '<a href="'.base_url().''.$textss.'/doneMedicalCertificate/'.$row['medical_certificates_id'].'"><button id="donePrescription" data-id="'.$row['medical_certificates_id'].'" class="btn btn-success btn-block">Done Medical Certificate Request</button></a>';
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

                        $('.medical-certificates-heading').click(function() {
                            $(this).siblings(".medical-certificates-body").slideToggle();
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
