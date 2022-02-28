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
            Child List / <?php echo $parent_name;?>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Patient Name</th>
                            <th>Patient Gender</th>
                            <th>Patient Birthdate</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach($listChilds as $row) {
                            echo '<tr class="listChilds-'.$row['patient_id'].'">';
                                echo '<td>'.$i++.'</td>';
                                echo '<td>'.htmlspecialchars($row['patient_name']).'</td>';
                                echo '<td>'.htmlspecialchars($row['patient_gender']).'</td>';
                                echo '<td>'.htmlspecialchars($row['patient_birthdate']).'</td>';
                                echo '<td>';
                                    echo '<a href="'.base_url().''.$textss.'//editChild/'.$row['patient_id'].'" class="btn btn-warning" style="margin-right:10px;">Edit</a>';
                                    echo '<a href="'.base_url().''.$textss.'//deleteChild/'.$row['patient_id'].'" class="btn btn-danger" style="margin-right:10px;">Delete</a>';
                                    echo '<a href="'.base_url().''.$textss.'//viewImmunizationRecord/'.$row['patient_id'].'" class="btn btn-info" style="margin-right:10px;">View Immunization Record</a>';
                                    echo '<a href="'.base_url().''.$textss.'//viewPediatricChart/'.$row['patient_id'].'" class="btn btn-primary">View Pediatric Chart</a>';
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
