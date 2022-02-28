<?php $this->load->view('Power/navigation'); ?>
<?php
$textss = "";
if($this->session->selection == "doctor") {
    $textss = "doctor";
    $count = 0;
    $sql = 'SELECT
    p.patient_id,
    p.patient_name,
    p.patient_birthdate,
    p.patient_picture,
    p.parent_id
FROM
    patients_tbl as p
LEFT JOIN
    consultations tb1 ON 
    p.patient_id = tb1.consultation_patient_id

WHERE tb1.interview_id = '.$this->session->id.' AND 
tb1.consultation_patient_id = '.$this->uri->segment(3);

$s = $this->db->query($sql);
if($s->num_rows() > 0) {
    // exist
    $count++;
}

$sql2 = 'SELECT
p.patient_id,
p.patient_name,
p.patient_birthdate,
p.patient_picture,
p.parent_id
FROM
patients_tbl as p
LEFT JOIN
appointments tb1 ON 
p.patient_id = tb1.appointment_patient_id

WHERE tb1.interview_id = '.$this->session->id.' AND 
tb1.appointment_patient_id = '.$this->uri->segment(3);

$s2 = $this->db->query($sql2);
if($s2->num_rows() > 0) {
// exist
$count++;
}

if($count == 0) {
    redirect("doctor/patients?error=There\'s something wrong");
    exit();
}
} else if($this->session->selection == "receptionist") {
    $textss = "receptionist";
} else if($this->session->selection == "administrator") {
    $textss = "administrator";
} 
?>
    <div class="panel panel-primary" style="border-radius: 0px; border: 0px;">
        <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
            Add Pediatric Chart / <?php echo $patient_name; ?>
        </div>
        <div class="panel-body">
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
            <form action="<?php echo base_url(). $textss; ?>/addPediatricChart/<?php echo $this->uri->segment(3); ?>" method="POST">
                <div class="form-group">
                    <label for="chiefComplaint">Chief complaint:</label>
                    <textarea id="chiefComplaint" name="chiefComplaint" class="form-control"  style="height: 50px;"></textarea>
                </div>
            
                <div class="form-group">
                    <label for="medicalHistory">Medical History:</label>
                    <textarea id="medicalHistory" name="medicalHistory" class="form-control"  style="height: 100px;"></textarea>
                </div>
                    
                <div class = "row">
                    <div class = "col-xs-6">
                        <div class="form-group">
                        <label for="pastMedicalHistory">Past Medical History:</label>
                        <textarea id="pastMedicalHistory" name="pastMedicalHistory" class="form-control"  style="height: 100px;"></textarea>
                        </div>
                    </div>

                    <div class = "col-xs-6">
                        <div class="form-group">
                        <label for="familyHistory">Family History:</label>
                        <textarea id="familyHistory" name="familyHistory" class="form-control"  style="height: 100px;"></textarea>
                        </div>
                    </div>
                </div>
                <hr/>
                <h3>Personal and Social History</h3>
                <div class= "row">
                    <div class = "col-xs-6">
                        <div class="form-group">
                        <label for="birthHistory">A. Birth History</label>
                        <textarea id="birthHistory" name="birthHistory" class="form-control"  style="height: 100px;"></textarea>
                        </div>
                    </div>

                    <div class = "col-xs-6">
                        <div class="form-group">
                        <label for="feedingHistory">B. Feeding History</label>
                        <textarea id="feedingHistory" name="feedingHistory" class="form-control"  style="height: 100px;"></textarea>
                        </div>
                    </div>
                </div>

<!--                 <div class="row">
                    <div clas ="col-xs-6"> -->
						
                        <div class="form-group">
                        	<label for="immunization">C. Immunization</label>
                        	<textarea id="immunization" name="immunization" class="form-control" style="height: 100px;"></textarea>
                        </div>
						
<!--                     </div>

                    <div class="col-xs-6"> -->
						
                        <div class="form-group">
                       	 	<label for="earAndBodyPiercing">D. Ear and body piercing</label>
                        	<textarea id="earAndBodyPiercing" name="earAndBodyPiercing" class="form-control" style="height: 100px;"></textarea>
                        </div>
						
<!--                     </div>
                </div> -->

                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                        <label for="circumcision">E. Circumcision</label>
                        <textarea id="circumcision" name="circumcision" class="form-control" style="height: 100px;"></textarea>
                        </div>
                    </div>

                    <div class = "col-xs-6">
                        <div class="form-group">
                        <label for="developmentalHistory">F. Developmental History</label>
                        <textarea id="developmentalHistory" name="developmentalHistory" class="form-control"  style="height: 100px;"></textarea>
                        </div>
                    </div>
                </div>
                    
                    
                    <hr/>
                    <h3>Pediatric Chart</h3>
                    <div class="row">
                        <div class="col-xs-4">
                        <div class="form-group">
                            <label for="bp">BP: </label>
                            <input type="text" id="bp" name="bp" class="form-control">
                        </div>
                        </div>
                        <div class="col-xs-4">
                        <div class="form-group">
                            <label for="cr">CR: </label>
                            <input type="text" id="cr" name="cr" class="form-control">
                        </div>
                        </div>
                        <div class="col-xs-4">
                        <div class="form-group">
                            <label for="rr">RR: </label>
                            <input type="text" id="rr" name="rr" class="form-control">
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                        <div class="form-group">
                            <label for="temp">Temp: </label>
                            <input type="text" id="temp" name="temp" class="form-control">
                        </div>
                        </div>
                        <div class="col-xs-4">
                        <div class="form-group">
                            <label for="O2Sat">O2 sat: </label>
                            <input type="text" id="O2Sat" name="O2Sat" class="form-control">
                        </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-4">
                        <div class="form-group">
                            <label for="weight">Weight: </label>
                            <input type="text" id="weight" name="weight" class="form-control">
                        </div>
                        </div>
                        <div class="col-xs-4">
                        <div class="form-group">
                            <label for="O2Sat">Ht: </label>
                            <input type="text" id="Ht" name="Ht" class="form-control">
                        </div>
                        </div>
                    </div>
                    <br/>
                    <h4>If less than 3 years old</h4>
                    <div class="row">
                        <div class="col-xs-3">
                        <div class="form-group">
                            <label for="hc">HC: </label>
                            <input type="text" id="hc" name="hc" class="form-control">
                        </div>
                        </div>
                        <div class="col-xs-3">
                        <div class="form-group">
                            <label for="cc">CC: </label>
                            <input type="text" id="cc" name="cc" class="form-control">
                        </div>
                        </div>
                        <div class="col-xs-3">
                        <div class="form-group">
                            <label for="ac">AC: </label>
                            <input type="text" id="ac" name="ac" class="form-control">
                        </div>
                        </div>
                        <div class="col-xs-3">
                        <div class="form-group">
                            <label for="height">Height: </label>
                            <input type="text" id="height" name="height" class="form-control">
                        </div>
                        </div>
                    </div>
                    <br/>
                    <h4>General survey:</h4>
                    <div class="form-group">
                        <label for="skin">Skin: </label>
                        <input type="text" id="skin" name="skin" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="heent">HEENT: </label>
                        <input type="text" id="heent" name="heent" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="thorax">Thorax: </label>
                        <input type="text" id="thorax" name="thorax" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="abdomen">Abdomen: </label>
                        <input type="text" id="abdomen" name="abdomen" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="genitourinarySystem">Genitourinary system: </label>
                        <input type="text" id="genitourinarySystem" name="genitourinarySystem" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="rectalExamination">Rectal examination: </label>
                        <input type="text" id="rectalExamination" name="rectalExamination" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="extremities">Extremities: </label>
                        <input type="text" id="extremities" name="extremities" class="form-control">
                    </div>
                    <br/>
                    <div class="form-group">
                        <label for="assessment">Assessment: </label>
                        <textarea id="assessment" name="assessment" class="form-control"  style="height: 100px;"></textarea>
                    </div>
                    <br/>
                    <h4>OB and Gynecologic History:</h4>
                    <div class="form-group">
                        <label for="lmp">LMP: </label>
                        <textarea id="lmp" name="lmp" class="form-control"  style="height: 100px;"></textarea>
                    </div>
                    <br/>
                    <div class="form-group">
                        <label for="obstretrics">Obstretrics: </label>
                        <textarea id="obstretrics" name="obstretrics" class="form-control" style="height: 100px;"></textarea>
                    </div>
                    <hr/>
                    <h4>Plan</h4>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="Investigate">Investigate: </label>
                                <textarea id="Investigate" name="Investigate" class="form-control" style="height: 100px;"></textarea>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="therapy">Therapy: </label>
                                <textarea id="therapy" name="therapy" class="form-control"  style="height: 100px;"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="pull-right">
                        <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                    </div>
                    <div style="clear:both;"></div>
            </form>
        </div>
    </div>
