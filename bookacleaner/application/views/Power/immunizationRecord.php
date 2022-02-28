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
            Immunization Record
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <label>Vaccine</label>
                    <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="form-control" style="margin-bottom: 10px;">
                        <option value="<?php echo '?vaccine=&date='.(htmlspecialchars($_GET['date']) != "" ? htmlspecialchars($_GET['date']) : "").'&patient='.(htmlspecialchars($_GET['patient']) != "" ? htmlspecialchars($_GET['patient']) : ""); ?>">Please select</option>
                        <?php
                            $vaccines = $this->db->query("SELECT * FROM vaccine_terms_tbl ORDER BY vaccine_terms_id");
                            foreach($vaccines->result_array() as $row) {
                                if($_GET['vaccine'] == $row['vaccine_terms_id']) {
                                    echo '<option value="?vaccine='.$row['vaccine_terms_id'].'&date='.(htmlspecialchars($_GET['date']) != "" ? htmlspecialchars($_GET['date']) : "").'&patient='.(htmlspecialchars($_GET['patient']) != "" ? htmlspecialchars($_GET['patient']) : "").'" selected="">'.htmlspecialchars($row['vaccine_terms_title']).'</option>';
                                } else {
                                    echo '<option value="?vaccine='.$row['vaccine_terms_id'].'&date='.(htmlspecialchars($_GET['date']) != "" ? htmlspecialchars($_GET['date']) : "").'&patient='.(htmlspecialchars($_GET['patient']) != "" ? htmlspecialchars($_GET['patient']) : "").'">'.htmlspecialchars($row['vaccine_terms_title']).'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Patient</label>
                    <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="form-control" style="margin-bottom: 10px;">
                        <option value="<?php echo '?vaccine='.(htmlspecialchars($_GET['vaccine']) != "" ? htmlspecialchars($_GET['vaccine']) : "").'&date='.(htmlspecialchars($_GET['date']) != "" ? htmlspecialchars($_GET['date']) : "").'&patient='; ?>">Please select</option>
                        <?php
                            $patients = $this->db->query("SELECT * FROM patients_tbl ORDER BY patient_id");
                            foreach($patients->result_array() as $row2) {
                                if($_GET['patient'] == $row2['patient_id']) {
                                    echo '<option value="?patient='.$row2['patient_id'].'&date='.(htmlspecialchars($_GET['date']) != "" ? htmlspecialchars($_GET['date']) : "").'&vaccine='.(htmlspecialchars($_GET['vaccine']) != "" ? htmlspecialchars($_GET['vaccine']) : "").'" selected="">'.htmlspecialchars($row2['patient_name']).'</option>';
                                } else {
                                    echo '<option value="?patient='.$row2['patient_id'].'&date='.(htmlspecialchars($_GET['date']) != "" ? htmlspecialchars($_GET['date']) : "").'&vaccine='.(htmlspecialchars($_GET['vaccine']) != "" ? htmlspecialchars($_GET['vaccine']) : "").'">'.htmlspecialchars($row2['patient_name']).'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Date</label>
                    <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="form-control" style="margin-bottom: 10px;">
                        <option value="<?php echo '?vaccine='.($_GET['vaccine'] != "" ? $_GET['vaccine'] : "").'&date=&patient='.($_GET['patient'] != "" ? $_GET['patient'] : ""); ?>">Please select</option>
                        <?php
                            $dates = $this->db->query("SELECT * FROM `immunization_record` GROUP BY `date`");
                            foreach($dates->result_array() as $row3) {
                                if($_GET['date'] == $row3['date']) {
                                    echo '<option value="?patient='.(htmlspecialchars($_GET['patient']) != "" ? htmlspecialchars($_GET['patient']) : "").'&date='.$row3['date'].'&vaccine='.(htmlspecialchars($_GET['vaccine']) != "" ? htmlspecialchars($_GET['vaccine']) : "").'" selected="">'.htmlspecialchars($row3['date']).'</option>';
                                } else {
                                    echo '<option value="?patient='.(htmlspecialchars($_GET['patient']) != "" ? htmlspecialchars($_GET['patient']) : "").'&date='.$row3['date'].'&vaccine='.(htmlspecialchars($_GET['vaccine']) != "" ? htmlspecialchars($_GET['vaccine']) : "").'">'.htmlspecialchars($row3['date']).'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
            <?php
            $textzzz = base_url() . $textss.'/getImmunizationRecordPDF?patient='.(htmlspecialchars($_GET['patient']) != "" ? htmlspecialchars($_GET['patient']) : "").'&date='.(htmlspecialchars($_GET['date']) != "" ? htmlspecialchars($_GET['date']) : "").'&&vaccine='.(htmlspecialchars($_GET['vaccine']) != "" ? htmlspecialchars($_GET['vaccine']) : "");
            ?>
            <a href="#" onclick="window.open('<?php echo $textzzz; ?>')" class="btn btn-success pull-right" target="_blank">Download PDF</a>
            <div style="clear:both;"></div>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Patient Name</th>
                            <th>Date</th>
                            <th>Vaccine</th>
                            <th>Route</th>
                            <th>Parent Name</th>
                            <th>Doctor</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 0;
                        foreach($immunizationrecords as $row) {
                            
                            echo '<tr class="immunization_record-'.$row['immunization_record_id'].'">';
                                
				echo '<td>'.$row['immunization_record_id'].'</td>';
                                echo '<td>'.htmlspecialchars($row['patient_name']).'</td>';
                                echo '<td>'.htmlspecialchars($row['date']).'</td>';
                                $qs = $this->db->query("SELECT * FROM `vaccine_terms_tbl` WHERE `vaccine_terms_id` = '".$row['vaccine_id']."'")->result_array();
                                echo '<td>'.htmlspecialchars($qs[0]['vaccine_terms_title']).'</td>';
                                echo '<td>'.htmlspecialchars($row['route']).'</td>';
                                echo '<td>'.htmlspecialchars($row['parent_name']).'</td>';
                                $getDoctors = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$row['interview_id']."'")->result_array();
                                echo '<td>'.htmlspecialchars($getDoctors[0]['doctor_name']).'</td>';
                                echo '<td>';
                                    echo '<a href="'.base_url().''.$textss.'//viewImmunizationRecord/'.$row['patient_id'].'" class="btn btn-info" style="margin-right:10px;">View</a>';
                                    echo '<a href="'.base_url().''.$textss.'//editImmunizationRecord/'.$row['immunization_record_id'].'" class="btn btn-warning" style="margin-right:10px;">Edit</a>';
                                    echo '<a href="'.base_url().''.$textss.'//deleteImmunizationRecord/'.$row['immunization_record_id'].'" class="btn btn-danger">Delete</a>';
                                echo '</td>';
                            echo '</tr>';
                            $count++;
                        }
                        if($count == 0) {
                            echo '<tr>';
                                echo '<td colspan="7" style="text-align:center;font-weight:bold;">No results found.</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php
                echo $links;
            ?>
        </div>
    </div>
