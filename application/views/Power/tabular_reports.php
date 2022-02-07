<?php $this->load->view('Power/navigation');
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
        Tabular Reports
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-3">
                <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="form-control" class="form-control" style="height: 59px;">
                    <option value="?type=" <?php if($_GET['type'] == "") {echo'selected';}?>>Online Consultations</option>
                    <option value="?type=appointments" <?php if($_GET['type'] == "appointments") {echo'selected';}?>>Appointments</option>
                    <option value="?type=number_of_patients" <?php if($_GET['type'] == "number_of_patients") {echo'selected';}?>>Number of Patients</option>
                    <option value="?type=patient_satisfactions" <?php if($_GET['type'] == "patient_satisfactions") {echo'selected';}?>>Patient Satisfactions</option>
                    <option value="?type=immunizations" <?php if($_GET['type'] == "immunizations") {echo'selected';}?>>Immunization Records</option>
                </select>
                
            </div>
            <div class="pull-right" style="margin-bottom: 10px;">
                <?php
                    $xxt = base_url().$textss.'//getTabularReport//'.$per_page.'//'.$page.'//'.(($_GET['type'] != "") ? $_GET['type'] : "");
                    echo '<a href="#" class="btn btn-success" onclick="window.open(\''.$xxt.'\')" style="margin-right:10px;">Download PDF</a>';
                ?>
            </div>
            <div style="clear:both;"></div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover ">
                <?php
                if($_GET['type'] == "") {
                    echo '<thead>';
                        echo '<tr>';
                            echo '<th>Parent Name</th>';
                            echo '<th>Patient Name</th>';
                            echo '<th>Date Start</th>';
                            echo '<th>Date End</th>';
                            echo '<th>Status</th>';
                        echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                        foreach($tabular_reports as $row) {
                            echo '<tr>';
                                echo '<td>'.$row['parent_name'].'</td>';
                                echo '<td>'.$row['patient_name'].'</td>';
                                echo '<td>'.$row['datetime'].'</td>';
                                echo '<td>'.$row['datetime_end'].'</td>';
                                echo '<td>'.$row['status'].'</td>';
                            echo '</tr>';
                        }
                    echo '</tbody>';
                } else if($_GET['type'] == "appointments") {
                    echo '<thead>';
                        echo '<tr>';
                            echo '<th>Parent Name</th>';
                            echo '<th>Patient Name</th>';
                            echo '<th>Date Start</th>';
                            echo '<th>Date End</th>';
                            echo '<th>Status</th>';
                        echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                        foreach($tabular_reports as $row) {
                            echo '<tr>';
                                echo '<td>'.$row['parent_name'].'</td>';
                                echo '<td>'.$row['patient_name'].'</td>';
                                echo '<td>'.$row['datetime'].'</td>';
                                echo '<td>'.$row['datetime_end'].'</td>';
                                echo '<td>'.$row['status'].'</td>';
                            echo '</tr>';
                        }
                    echo '</tbody>';
                } else if($_GET['type'] == "number_of_patients") {
                    echo '<thead>';
                        echo '<tr>';
                            echo '<th>Patient Name</th>';
                            echo '<th>Services</th>';
                        echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                        foreach($tabular_reports as $row) {
                            echo '<tr>';
                                echo '<td>'.$row['patient_name'].'</td>';
                                echo '<td>'.$row['services'].'</td>';
                            echo '</tr>';
                        }
                    echo '</tbody>';
                } else if($_GET['type'] == "patient_satisfactions") {
                    echo '<thead>';
                        echo '<tr>';
                            echo '<th>Question</th>';
                            echo '<th>Answer</th>';
                            echo '<th>Percentage</th>';
                        echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                        foreach($tabular_reports as $row) {
                            echo '<tr>';
                                echo '<td>'.$row['question'].'</td>';
                                echo '<td>'.$row['answer'].'</td>';
                                if($row['percentage'] != "") {
                                    echo '<td>'.$row['percentage'].'%</td>';
                                } else {
                                    echo '<td><b>-</b></td>';
                                }
                            echo '</tr>';
                        }
                    echo '</tbody>';
                } else if($_GET['type'] == "immunizations") {
                    echo '<thead>';
                        echo '<tr>';
                            echo '<th>Patient Name</th>';
                            echo '<th>Date</th>';
                            echo '<th>Vaccine</th>';
                            echo '<th>Route</th>';
                            echo '<th>Parent Name</th>';
                        echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                        foreach($tabular_reports as $row) {
                            echo '<tr>';
                                echo '<td>'.$row['patient_name'].'</td>';
                                echo '<td>'.$row['date'].'</td>';
                                echo '<td>'.$row['vaccine_name'].'</td>';
                                echo '<td>'.$row['route'].'</td>';
                                echo '<td>'.$row['parent_name'].'</td>';
                            echo '</tr>';
                        }
                    echo '</tbody>';
                }
                ?>
            </table>
        </div>
        <?php
            echo $links;
        ?>
    </div>
</div>