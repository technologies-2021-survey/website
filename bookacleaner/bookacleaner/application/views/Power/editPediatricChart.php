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
            Edit Pediatric Chart / <?php echo $patient_name; ?>
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
            <form action="<?php echo base_url(). $textss; ?>/editPediatricChart/<?php echo $this->uri->segment(3); ?>" method="POST">
                <div class="form-group">
                    <label for="chiefComplaint">Chief complaint:</label>
                    <textarea id="chiefComplaint" name="chiefComplaint" class="form-control" required="" style="height: 50px;"><?=($datas[0]['chiefComplaint'] != "") ? $datas[0]['chiefComplaint'] : ""; ?></textarea>
                </div>

                <div class="form-group">
                <label for="medicalHistory">Medical History:</label>
                <textarea id="medicalHistory" name="medicalHistory" class="form-control" required="" style="height: 100px;"><?=($datas[0]['medicalHistory'] != "") ? $datas[0]['medicalHistory'] : ""; ?></textarea>
                </div>
                <div class="form-group">
                <label for="pastMedicalHistory">Past Medical History:</label>
                <textarea id="pastMedicalHistory" name="pastMedicalHistory" class="form-control" required="" style="height: 100px;"><?=($datas[0]['pastMedicalHistory'] != "") ? $datas[0]['pastMedicalHistory'] : ""; ?></textarea>
                </div>

                <div class="form-group">
                <label for="familyHistory">Family History:</label>
                <textarea id="familyHistory" name="familyHistory" class="form-control" required="" style="height: 100px;"><?=($datas[0]['familyHistory'] != "") ? $datas[0]['familyHistory'] : ""; ?></textarea>
                </div>
                <hr/>
                <h3>Personal and Social History</h3>
                <div class="form-group">
                <label for="birthHistory">A. Birth History</label>
                <textarea id="birthHistory" name="birthHistory" class="form-control" required="" style="height: 100px;"><?=($datas[0]['birthHistory'] != "") ? $datas[0]['birthHistory'] : ""; ?></textarea>
                </div>


                <div class="form-group">
                <label for="feedingHistory">B. Feeding History</label>
                <textarea id="feedingHistory" name="feedingHistory" class="form-control" required="" style="height: 100px;"><?=($datas[0]['feedingHistory'] != "") ? $datas[0]['feedingHistory'] : ""; ?></textarea>
                </div>


                <div class="form-group">
                <label for="immunization">C. Immunization</label>
                <textarea id="immunization" name="immunization" class="form-control" required="" style="height: 100px;"><?=($datas[0]['immunization'] != "") ? $datas[0]['immunization'] : ""; ?></textarea>
                </div>

                <div class="form-group">
                <label for="earAndBodyPiercing">D. Ear and body piercing</label>
                <textarea id="earAndBodyPiercing" name="earAndBodyPiercing" class="form-control" required="" style="height: 100px;"><?=($datas[0]['earAndBodyPiercing'] != "") ? $datas[0]['earAndBodyPiercing'] : ""; ?></textarea>
                </div>

                <div class="form-group">
                <label for="circumcision">E. Circumcision</label>
                <textarea id="circumcision" name="circumcision" class="form-control" required="" style="height: 100px;"><?=($datas[0]['circumcision'] != "") ? $datas[0]['circumcision'] : ""; ?></textarea>
                </div>

                <div class="form-group">
                <label for="developmentalHistory">F. Developmental History</label>
                <textarea id="developmentalHistory" name="developmentalHistory" class="form-control" required="" style="height: 100px;"><?=($datas[0]['developmentalHistory'] != "") ? $datas[0]['developmentalHistory'] : ""; ?></textarea>
                </div>
                <hr/>
                <h3>Pediatric Chart</h3>
                <div class="row">
                    <div class="col-xs-4">
                    <div class="form-group">
                        <label for="bp">BP: </label>
                        <input type="text" id="bp" name="bp" class="form-control" value="<?=($datas[0]['bp'] != "") ? $datas[0]['bp'] : ""; ?>">
                    </div>
                    </div>
                    <div class="col-xs-4">
                    <div class="form-group">
                        <label for="cr">CR: </label>
                        <input type="text" id="cr" name="cr" class="form-control" value="<?=($datas[0]['cr'] != "") ? $datas[0]['cr'] : ""; ?>">
                    </div>
                    </div>
                    <div class="col-xs-4">
                    <div class="form-group">
                        <label for="rr">RR: </label>
                        <input type="text" id="rr" name="rr" class="form-control" value="<?=($datas[0]['rr'] != "") ? $datas[0]['rr'] : ""; ?>">
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                    <div class="form-group">
                        <label for="temp">Temp: </label>
                        <input type="text" id="temp" name="temp" class="form-control" value="<?=($datas[0]['temp'] != "") ? $datas[0]['temp'] : ""; ?>">
                    </div>
                    </div>
                    <div class="col-xs-4">
                    <div class="form-group">
                        <label for="O2Sat">O2 sat: </label>
                        <input type="text" id="O2Sat" name="O2Sat" class="form-control" value="<?=($datas[0]['O2Sat'] != "") ? $datas[0]['O2Sat'] : ""; ?>">
                    </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-4">
                    <div class="form-group">
                        <label for="weight">Weight: </label>
                        <input type="text" id="weight" name="weight" class="form-control" value="<?=($datas[0]['weight'] != "") ? $datas[0]['weight'] : ""; ?>">
                    </div>
                    </div>
                    <div class="col-xs-4">
                    <div class="form-group">
                        <label for="O2Sat">Ht: </label>
                        <input type="text" id="ht" name="Ht" class="form-control" value="<?=($datas[0]['Ht'] != "") ? $datas[0]['Ht'] : ""; ?>">
                    </div>
                    </div>
                </div>
                <br/>
                <h4>If less than 3 years old</h4>
                <div class="row">
                    <div class="col-xs-3">
                    <div class="form-group">
                        <label for="hc">HC: </label>
                        <input type="text" id="hc" name="hc" class="form-control" value="<?=($datas[0]['hc'] != "") ? $datas[0]['hc'] : ""; ?>">
                    </div>
                    </div>
                    <div class="col-xs-3">
                    <div class="form-group">
                        <label for="cc">CC: </label>
                        <input type="text" id="cc" name="cc" class="form-control" value="<?=($datas[0]['cc'] != "") ? $datas[0]['cc'] : ""; ?>">
                    </div>
                    </div>
                    <div class="col-xs-3">
                    <div class="form-group">
                        <label for="ac">AC: </label>
                        <input type="text" id="ac" name="ac" class="form-control" value="<?=($datas[0]['ac'] != "") ? $datas[0]['ac'] : ""; ?>">
                    </div>
                    </div>
                    <div class="col-xs-3">
                    <div class="form-group">
                        <label for="height">Height: </label>
                        <input type="text" id="height" name="height" class="form-control" value="<?=($datas[0]['height'] != "") ? $datas[0]['height'] : ""; ?>">
                    </div>
                    </div>
                </div>
                <br/>
                <h4>General survey:</h4>
                <div class="form-group">
                    <label for="skin">Skin: </label>
                    <input type="text" id="skin" name="skin" class="form-control" value="<?=($datas[0]['skin'] != "") ? $datas[0]['skin'] : ""; ?>">
                </div>

                <div class="form-group">
                    <label for="heent">HEENT: </label>
                    <input type="text" id="heent" name="heent" class="form-control" value="<?=($datas[0]['heent'] != "") ? $datas[0]['heent'] : ""; ?>">
                </div>

                <div class="form-group">
                    <label for="thorax">Thorax: </label>
                    <input type="text" id="thorax" name="thorax" class="form-control" value="<?=($datas[0]['thorax'] != "") ? $datas[0]['thorax'] : ""; ?>">
                </div>

                <div class="form-group">
                    <label for="abdomen">Abdomen: </label>
                    <input type="text" id="abdomen" name="abdomen" class="form-control" value="<?=($datas[0]['abdomen'] != "") ? $datas[0]['abdomen'] : ""; ?>">
                </div>

                <div class="form-group">
                    <label for="genitourinarySystem">Genitourinary system: </label>
                    <input type="text" id="genitourinarySystem" name="genitourinarySystem" class="form-control" value="<?=($datas[0]['genitourinarySystem'] != "") ? $datas[0]['genitourinarySystem'] : ""; ?>">
                </div>

                <div class="form-group">
                    <label for="rectalExamination">Rectal examination: </label>
                    <input type="text" id="rectalExamination" name="rectalExamination" class="form-control" value="<?=($datas[0]['rectalExamination'] != "") ? $datas[0]['rectalExamination'] : ""; ?>">
                </div>

                <div class="form-group">
                    <label for="extremities">Extremities: </label>
                    <input type="text" id="extremities" name="extremities" class="form-control" value="<?=($datas[0]['extremities'] != "") ? $datas[0]['extremities'] : ""; ?>">
                </div>
                <br/>
                <div class="form-group">
                    <label for="assessment">Assessment: </label>
                    <textarea id="assessment" name="assessment" class="form-control" required="" style="height: 100px;"><?=($datas[0]['assessment'] != "") ? $datas[0]['assessment'] : ""; ?></textarea>
                </div>
                <br/>
                <h4>OB and Gynecologic History:</h4>
                <div class="form-group">
                    <label for="lmp">LMP: </label>
                    <textarea id="lmp" name="lmp" class="form-control" required="" style="height: 100px;"><?=($datas[0]['lmp'] != "") ? $datas[0]['lmp'] : ""; ?></textarea>
                </div>
                <br/>
                <div class="form-group">
                    <label for="obstretrics">Obstretrics: </label>
                    <textarea id="obstretrics" name="obstretrics" class="form-control" required="" style="height: 100px;"><?=($datas[0]['obstretrics'] != "") ? $datas[0]['obstretrics'] : ""; ?></textarea>
                </div>
                <hr/>
                <h4>Plan</h4>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="Investigate">Investigate: </label>
                            <textarea id="Investigate" name="Investigate" class="form-control" required="" style="height: 100px;"><?=($datas[0]['Investigate'] != "") ? $datas[0]['Investigate'] : ""; ?></textarea>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="therapy">Therapy: </label>
                            <textarea id="therapy" name="therapy" class="form-control" required="" style="height: 100px;"><?=($datas[0]['therapy'] != "") ? $datas[0]['therapy'] : ""; ?></textarea>
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
