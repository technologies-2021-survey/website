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
            Patient List
        </div>
        <div class="panel-body">

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Patient Name</th>
                            <th>Gender</th>
                            <th>Birthdate</th>
                            <th>Parent Name</th>
                            <?php
                            if($this->session->selection != "doctor") {
                                echo '<th>Resident Doctor</th>';
                            }
                            ?>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($patients as $row) {
                            echo '<tr class="patients-'.$row['parent_id'].'">';
                                echo '<td>'.htmlspecialchars($row['patient_id']).'</td>';
                                echo '<td>'.htmlspecialchars($row['patient_name']).'</td>';
                                echo '<td>'.htmlspecialchars($row['patient_gender']).'</td>';


                                $birthDate = htmlspecialchars($row['patient_birthdate']);
                                $from = new DateTime($birthDate);
                                $to   = new DateTime('today');
                                $age = $from->diff($to)->y;

                                echo '<td>'.$row['patient_birthdate'].'<br/>(Age: '.htmlspecialchars($age).')</td>';
                                echo '<td>'.$row['parent_name'].'</td>';
                                if($this->session->selection != "doctor") {
                                    echo '<td>'.$row['doctor_name'].'</td>';
                                }
                                echo '<td><a href="'.base_url().$textss.'//addImmunizationRecord/'.$row['patient_id'].'" class="btn btn-success" style="margin-bottom:10px;">Add Immunization Record</a><br/><a href="'.base_url().$textss.'//viewImmunizationRecord/'.$row['patient_id'].'" class="btn btn-warning">View Immunization Record</a></td>';
                                echo '<td><a href="'.base_url().$textss.'//addPediatricChart/'.$row['patient_id'].'" class="btn btn-info" style="margin-bottom:10px;">Add Pediatric Chart</a><br/><a href="'.base_url().$textss.'//viewPediatricChart/'.$row['patient_id'].'" class="btn btn-warning">View Pediatric Chart</a></td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php echo $links; ?>
        </div>
    </div>