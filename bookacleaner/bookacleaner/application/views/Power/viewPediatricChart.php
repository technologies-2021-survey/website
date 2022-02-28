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
            View Pediatric Chart / <?php echo $patient_name;?>
        </div>
        <div class="panel-body">
            <?php
                echo '<a href="'.base_url().$textss.'//addPediatricChart/'.$patient_id.'" class="btn btn-success">Add Pediatric Chart</a>';
            ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($viewPediatricCharts as $row) {
                            echo '<tr class="viewPediatricCharts-'.$row['patients_history_id'].'">';
                                echo '<td>'.date("M d Y h:i:sA", $row['patient_datetime']).'</td>';
                                echo '<td>';
                                    echo '<a href="'.base_url().$textss.'//editPediatricChart/'.$row['patients_history_id'].'" class="btn btn-warning" style="margin-right:10px;">Edit</a>';
                                    echo '<a href="'.base_url().$textss.'//viewPediatricChartData/'.$row['patients_history_id'].'" class="btn btn-info" style="margin-right:10px;">View</a>';
                                    echo '<a href="'.base_url().$textss.'//deletePediatricChart/'.$row['patients_history_id'].'" class="btn btn-danger">Delete</a>';
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