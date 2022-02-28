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
            View Immunization Record / <?php echo $patient_name;?>
        </div>
        <div class="panel-body">
            <?php
                echo '<a href="'.base_url().$textss.'//addImmunizationRecord/'.$patient_id.'" class="btn btn-success">Add Immunization Record</a>';
            ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Vaccine</th>
                            <th>Route</th>
                            <th>Age</th>
                            <th>Weight</th>
                            <th>Length</th>
                            <th>BMI</th>
                            <th>Doctor's Instruction</th>
                            <th>Head Circumference</th>
                            <th>Comeback On</th>
                            <th>For</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach($viewImmunizationRecords as $row) {
                            echo '<tr class="viewImmunizationRecords-'.$row['immunization_record_id'].'">';
                                echo '<td>'.$i++.'</td>';
                                echo '<td>'.$row['date'].'</td>';
                                $getData = $this->db->query("SELECT * FROM `vaccine_terms_tbl` WHERE `vaccine_terms_id` = '".$row['vaccine_id']."'")->result_array();
                                echo '<td>'.htmlspecialchars($getData[0]['vaccine_terms_title']).'</td>';
                                echo '<td>'.htmlspecialchars($row['route']).'</td>';
                                echo '<td>'.htmlspecialchars($row['age']).'</td>';
                                echo '<td>'.htmlspecialchars($row['weight']).'</td>';
                                echo '<td>'.htmlspecialchars($row['length']).'</td>';
                                echo '<td>'.htmlspecialchars($row['bmi']).'</td>';
                                echo '<td>'.htmlspecialchars($row['doctors_instruction']).'</td>';
                                echo '<td>'.htmlspecialchars($row['head_circumference']).'</td>';
                                echo '<td>'.htmlspecialchars($row['comeback_on']).'</td>';
                                echo '<td>'.htmlspecialchars($row['comeback_for']).'</td>';
                                echo '<td>';
                                    echo '<a href="'.base_url().$textss.'//editImmunizationRecord/'.$row['immunization_record_id'].'" class="btn btn-warning" style="margin-right:10px;">Edit</a>';
                                    echo '<a href="'.base_url().$textss.'//deleteImmunizationRecord/'.$row['immunization_record_id'].'" class="btn btn-danger">Delete</a>';
                                echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php echo $links; ?>
        </div>
    </div>