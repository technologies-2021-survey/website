<?php $this->load->view('Power/navigation'); ?>
<?php
    if($level == "doctor") {
?>
<div class="panel panel-primary" style="border-radius: 0px; border: 0px;">
    
    <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
        Add Schedule
    </div>
    <div class="panel-body">
        <script type="text/javascript">
        // select start time
        $(document).ready(function() {
            $('select[name=doctor_schedule_start_time]').change(function() {
                //console.log($('select[name=doctor_schedule_start_time]')[0].selectedIndex);
                $('select[name=doctor_schedule_end_time]').prop('selectedIndex', $('select[name=doctor_schedule_start_time]')[0].selectedIndex);

            });
            $('select[name=doctor_schedule_end_time]').change(function() {
                //console.log($('select[name=doctor_schedule_start_time]')[0].selectedIndex);
                $('select[name=doctor_schedule_start_time]').prop('selectedIndex', $('select[name=doctor_schedule_end_time]')[0].selectedIndex);

            });
        });
        </script>
        <?php
            if(isset($_POST['submit'])) {
                $doctor_id                  = $this->session->id;
                $doctor_schedule_day        = $_POST['doctor_schedule_day'];
                $doctor_schedule_start_time = $_POST['doctor_schedule_start_time'];
                $doctor_status              = $_POST['doctor_status'];

                echo "hehe".$_POST['doctor_id'];
                echo '<br/>';
                $submitQuery = $this->db->query("SELECT * FROM `doctor_schedule` WHERE `doctor_schedule_day` = '".$doctor_schedule_day."' AND `doctor_schedule_start_time` = '".$doctor_schedule_start_time."' AND `doctor_id` = '".$this->session->id."'");

                if($submitQuery->num_rows() > 0) {
                    // exist
                    // error
                    header("Location: ?error=Oops, you have already that schedule!");
                } else {
                    // not exist
                    // success
                    if( $doctor_schedule_day == "Monday") {
                    } else if($doctor_schedule_day == "Tuesday") { 
                    } else if($doctor_schedule_day == "Wednesday") { 
                    } else if($doctor_schedule_day == "Thursday") { 
                    } else if($doctor_schedule_day == "Friday") { 
                    } else if($doctor_schedule_day == "Saturday") { 
                    } else if($doctor_schedule_day == "Sunday") {
                    } else {
                        //error
                        header("Location: ?error=Oops, you\'re trying to sql injection? hmm. (1)");
                        die();
                    }

                    if( $doctor_schedule_start_time == "09:00AM") {
                    } else if($doctor_schedule_start_time == "09:30AM") {
                    } else if($doctor_schedule_start_time == "10:00AM") {
                    } else if($doctor_schedule_start_time == "10:30AM") {
                    } else if($doctor_schedule_start_time == "11:00AM") {
                    } else if($doctor_schedule_start_time == "11:30AM") {
                    }
                    
                    if( $doctor_status == "Active" ) {
                    } else if( $doctor_status == "Inactive" ) {
                    } else {
                        header("Location: ?error=Oops, you\'re trying to sql injection? hmm. (3)");
                        die();
                    }

                    $checkDoctorQuery = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$doctor_id."'");
                    if($checkDoctorQuery->num_rows() > 0) {
                        // exist
                        $this->db->query("INSERT INTO `doctor_schedule` 
                        (
                            `doctor_id`,
                            `doctor_schedule_day`,
                            `doctor_schedule_start_time`,
                            `doctor_schedule_end_time`,
                            `doctor_status`
                        )
                        VALUES
                        (
                            '$doctor_id',
                            '$doctor_schedule_day',
                            '$doctor_schedule_start_time',
                            '".date("h:iA", strtotime($doctor_schedule_start_time . " +30 minutes"))."',
                            '$doctor_status'
                        )
                        ");
                        header("Location: ?success=Successfully!");
                    } else {
                        // not exist
                        header("Location: ?error=Oops, that doctor id doesn\'t exist!");
                        die();
                    }
                }
            }
        ?>
        <form action="" method="POST">
            <label>Day</label><br/>
            <select name="doctor_schedule_day" class="form-control" style="width: 30%;">
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option>
            </select><br/><br/>

            <label>Start Time</label><br/>
            <select name="doctor_schedule_start_time" class="form-control" style="width: 30%;">
                <?php
                    $date = "9:00AM";
                    for($i = 0; $i < 6; $i++) {
                        echo '<option value="'.date("h:iA", strtotime($date)).'">'. date("h:iA", strtotime($date)).'</option>';
                        $date = date("h:iA", strtotime($date . " +30 mins")); 
                    }
                ?>
            </select><br/><br/>

            <label>End Time</label><br/>
            <select name="doctor_schedule_end_time" class="form-control" style="width: 30%;">
                <?php
                    $date = "8:30AM";
                    for($i = 0; $i < 6; $i++) {
                        $date = date("h:iA", strtotime($date . " +30 mins")); 
                        echo '<option value="'.date("h:iA", strtotime($date . " + 30 mins")).'">'. date("h:iA", strtotime($date . " + 30 mins")).'</option>';
                    }
                ?>
            </select><br/><br/>

            <label>Status</label><br/>
            <select name="doctor_status" class="form-control" style="width: 30%;">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select><br/><br/>

            <input type="submit" name="submit" class="btn btn-success">
        </form>
    </div>
</div>
<?php
    }
?>
<div class="panel panel-primary" style="border-radius: 0px; border: 0px;">
    <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
        Doctor Schedule
    </div>
    <div class="panel-body">
        <table class="table table-striped table-hover "> 
            <thead>
                <tr>
                    <td>
                        Doctor Name
                    </td>
                    <td>
                        Day 
                    </td>
                    <td>
                        Time
                    </td>
                    <td>
                        Status
                    </td>
                    <td>
                        Action
                    </td>
                </tr>
            </thead> 
            <tbody>
                <?php
                    $count = 0;

                    // Monday
                    $checkMondayQuery = $this->db->query("SELECT * FROM `doctor_schedule` WHERE `doctor_schedule_day` = 'Monday' ORDER BY STR_TO_DATE(`doctor_schedule_start_time`,'%h:%i%p') ASC");
                    if($checkMondayQuery->num_rows() > 0) {
                        // exist
                        foreach($checkMondayQuery->result_array() as $checkMondayRow) {
                            echo '<tr>';
                                echo '<td>';
                                    $getDoctorQuery = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$checkMondayRow['doctor_id']."'");
                                    $getDoctorRow = $getDoctorQuery->result_array();
                                    echo $getDoctorRow[0]['doctor_name'];
                                echo '</td>';
                                echo '<td>';
                                    echo 'Monday';
                                echo '</td>';
                                echo '<td>';
                                    echo $checkMondayRow['doctor_schedule_start_time'];
                                    echo ' - ';
                                    echo $checkMondayRow['doctor_schedule_end_time'];
                                echo '</td>';
                                echo '<td>';
                                    if($checkMondayRow['doctor_status'] == "Active") {
                                        echo '<label class="label label-success">';
                                            echo $checkMondayRow['doctor_status'];
                                        echo '</label>';
                                    } else {
                                        echo '<label class="label label-danger">';
                                            echo $checkMondayRow['doctor_status'];
                                        echo '</label>';
                                    }
                                echo '</td>';
                                echo '<td>';
                                    if($level == "doctor") {
                                        if($checkMondayRow['doctor_id'] == $this->session->id) {
                                            if($checkMondayRow['doctor_status'] == "Active") {
                                                echo '<a href="?inactive='.$checkMondayRow['doctor_schedule_id'].'" class="btn btn-warning" onclick="return confirm(\'Are you sure you want to inactive this schedule?\');" style="margin-right:10px;">Inactive</a>';
                                            }
                                            if($checkMondayRow['doctor_status'] == "Inactive") {
                                                echo '<a href="?active='.$checkMondayRow['doctor_schedule_id'].'" class="btn btn-success" onclick="return confirm(\'Are you sure you want to active this schedule?\');" style="margin-right:10px;">Active</a>';
                                            }
                                            echo '<a href="?delete='.$checkMondayRow['doctor_schedule_id'].'" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this schedule?\');">Delete</a>';
                                        }
                                        
                                    }
                                echo '</td>';
                            echo '</tr>';
                            $count += 1;
                        }
                    }

                    // Tuesday
                    $checkTuesdayQuery = $this->db->query("SELECT * FROM `doctor_schedule` WHERE `doctor_schedule_day` = 'Tuesday' ORDER BY STR_TO_DATE(`doctor_schedule_start_time`,'%h:%i%p') ASC");
                    if($checkTuesdayQuery->num_rows() > 0) {
                        // exist
                        foreach($checkTuesdayQuery->result_array() as $checkTuesdayRow) {
                            echo '<tr>';
                                echo '<td>';
                                    $getDoctorQuery = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$checkTuesdayRow['doctor_id']."'");
                                    $getDoctorRow = $getDoctorQuery->result_array();
                                    echo $getDoctorRow[0]['doctor_name'];
                                echo '</td>';
                                echo '<td>';
                                    echo 'Tuesday';
                                echo '</td>';
                                echo '<td>';
                                    echo $checkTuesdayRow['doctor_schedule_start_time'];
                                    echo ' - ';
                                    echo $checkTuesdayRow['doctor_schedule_end_time'];
                                echo '</td>';
                                echo '<td>';
                                    if($checkTuesdayRow['doctor_status'] == "Active") {
                                        echo '<label class="label label-success">';
                                            echo $checkTuesdayRow['doctor_status'];
                                        echo '</label>';
                                    } else {
                                        echo '<label class="label label-danger">';
                                            echo $checkTuesdayRow['doctor_status'];
                                        echo '</label>';
                                    }
                                echo '</td>';
                                echo '<td>';
                                    if($level == "doctor") {
                                        if($checkTuesdayRow['doctor_id'] == $this->session->id) {
                                            if($checkTuesdayRow['doctor_status'] == "Active") {
                                                echo '<a href="?inactive='.$checkTuesdayRow['doctor_schedule_id'].'" class="btn btn-warning" onclick="return confirm(\'Are you sure you want to inactive this schedule?\');" style="margin-right:10px;">Inactive</a>';
                                            }
                                            if($checkTuesdayRow['doctor_status'] == "Inactive") {
                                                echo '<a href="?active='.$checkTuesdayRow['doctor_schedule_id'].'" class="btn btn-success" onclick="return confirm(\'Are you sure you want to active this schedule?\');" style="margin-right:10px;">Active</a>';
                                            }
                                            echo '<a href="?delete='.$checkTuesdayRow['doctor_schedule_id'].'" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this schedule?\');">Delete</a>';
                                        }
                                    }
                                echo '</td>';
                            echo '</tr>';
                            $count += 1;
                        }
                    }

                    // Wednesday
                    $checkWednesdayQuery = $this->db->query("SELECT * FROM `doctor_schedule` WHERE `doctor_schedule_day` = 'Wednesday' ORDER BY STR_TO_DATE(`doctor_schedule_start_time`,'%h:%i%p') ASC");
                    if($checkWednesdayQuery->num_rows() > 0) {
                        // exist
                        foreach($checkWednesdayQuery->result_array() as $checkWednesdayRow) {
                            echo '<tr>';
                                echo '<td>';
                                    $getDoctorQuery = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$checkWednesdayRow['doctor_id']."'");
                                    $getDoctorRow = $getDoctorQuery->result_array();
                                    echo $getDoctorRow[0]['doctor_name'];
                                echo '</td>';
                                echo '<td>';
                                    echo 'Wednesday';
                                echo '</td>';
                                echo '<td>';
                                    echo $checkWednesdayRow['doctor_schedule_start_time'];
                                    echo ' - ';
                                    echo $checkWednesdayRow['doctor_schedule_end_time'];
                                echo '</td>';
                                echo '<td>';
                                    if($checkWednesdayRow['doctor_status'] == "Active") {
                                        echo '<label class="label label-success">';
                                            echo $checkWednesdayRow['doctor_status'];
                                        echo '</label>';
                                    } else {
                                        echo '<label class="label label-danger">';
                                            echo $checkWednesdayRow['doctor_status'];
                                        echo '</label>';
                                    }
                                echo '</td>';
                                echo '<td>';
                                    if($level == "doctor") {
                                        if($checkWednesdayRow['doctor_id'] == $this->session->id) {
                                            if($checkWednesdayRow['doctor_status'] == "Active") {
                                                echo '<a href="?inactive='.$checkWednesdayRow['doctor_schedule_id'].'" class="btn btn-warning" onclick="return confirm(\'Are you sure you want to inactive this schedule?\');" style="margin-right:10px;">Inactive</a>';
                                            }
                                            if($checkWednesdayRow['doctor_status'] == "Inactive") {
                                                echo '<a href="?active='.$checkWednesdayRow['doctor_schedule_id'].'" class="btn btn-success" onclick="return confirm(\'Are you sure you want to active this schedule?\');" style="margin-right:10px;">Active</a>';
                                            }
                                            echo '<a href="?delete='.$checkWednesdayRow['doctor_schedule_id'].'" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this schedule?\');">Delete</a>';
                                        }
                                    }
                                echo '</td>';
                            echo '</tr>';
                            $count += 1;
                        }
                    }

                    // Thursday
                    $checkThursdayQuery = $this->db->query("SELECT * FROM `doctor_schedule` WHERE `doctor_schedule_day` = 'Thursday' ORDER BY STR_TO_DATE(`doctor_schedule_start_time`,'%h:%i%p') ASC");
                    if($checkThursdayQuery->num_rows() > 0) {
                        // exist
                        foreach($checkThursdayQuery->result_array() as $checkThursdayRow) {
                            echo '<tr>';
                                echo '<td>';
                                    $getDoctorQuery = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$checkThursdayRow['doctor_id']."'");
                                    $getDoctorRow = $getDoctorQuery->result_array();
                                    echo $getDoctorRow[0]['doctor_name'];
                                echo '</td>';
                                echo '<td>';
                                    echo 'Thursday';
                                echo '</td>';
                                echo '<td>';
                                    echo $checkThursdayRow['doctor_schedule_start_time'];
                                    echo ' - ';
                                    echo $checkThursdayRow['doctor_schedule_end_time'];
                                echo '</td>';
                                echo '<td>';
                                    if($checkThursdayRow['doctor_status'] == "Active") {
                                        echo '<label class="label label-success">';
                                            echo $checkThursdayRow['doctor_status'];
                                        echo '</label>';
                                    } else {
                                        echo '<label class="label label-danger">';
                                            echo $checkThursdayRow['doctor_status'];
                                        echo '</label>';
                                    }
                                echo '</td>';
                                echo '<td>';
                                    if($level == "doctor") {
                                        if($checkThursdayRow['doctor_id'] == $this->session->id) {
                                            if($checkThursdayRow['doctor_status'] == "Active") {
                                                echo '<a href="?inactive='.$checkThursdayRow['doctor_schedule_id'].'" class="btn btn-warning" onclick="return confirm(\'Are you sure you want to inactive this schedule?\');" style="margin-right:10px;">Inactive</a>';
                                            }
                                            if($checkThursdayRow['doctor_status'] == "Inactive") {
                                                echo '<a href="?active='.$checkThursdayRow['doctor_schedule_id'].'" class="btn btn-success" onclick="return confirm(\'Are you sure you want to active this schedule?\');" style="margin-right:10px;">Active</a>';
                                            }
                                            echo '<a href="?delete='.$checkThursdayRow['doctor_schedule_id'].'" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this schedule?\');">Delete</a>';
                                        }
                                    }
                                echo '</td>';
                            echo '</tr>';
                            $count += 1;
                        }
                    }

                    // Friday
                    $checkFridayQuery = $this->db->query("SELECT * FROM `doctor_schedule` WHERE `doctor_schedule_day` = 'Friday' ORDER BY STR_TO_DATE(`doctor_schedule_start_time`,'%h:%i%p') ASC");
                    if($checkFridayQuery->num_rows() > 0) {
                        // exist
                        foreach($checkFridayQuery->result_array() as $checkFridayRow) {
                            echo '<tr>';
                                echo '<td>';
                                    $getDoctorQuery = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$checkFridayRow['doctor_id']."'");
                                    $getDoctorRow = $getDoctorQuery->result_array();
                                    echo $getDoctorRow[0]['doctor_name'];
                                echo '</td>';
                                echo '<td>';
                                    echo 'Friday';
                                echo '</td>';
                                echo '<td>';
                                    echo $checkFridayRow['doctor_schedule_start_time'];
                                    echo ' - ';
                                    echo $checkFridayRow['doctor_schedule_end_time'];
                                echo '</td>';
                                echo '<td>';
                                    if($checkFridayRow['doctor_status'] == "Active") {
                                        echo '<label class="label label-success">';
                                            echo $checkFridayRow['doctor_status'];
                                        echo '</label>';
                                    } else {
                                        echo '<label class="label label-danger">';
                                            echo $checkFridayRow['doctor_status'];
                                        echo '</label>';
                                    }
                                echo '</td>';
                                echo '<td>';
                                    if($level == "doctor") {
                                        if($checkFridayRow['doctor_id'] == $this->session->id) {
                                            if($checkFridayRow['doctor_status'] == "Active") {
                                                echo '<a href="?inactive='.$checkFridayRow['doctor_schedule_id'].'" class="btn btn-warning" onclick="return confirm(\'Are you sure you want to inactive this schedule?\');" style="margin-right:10px;">Inactive</a>';
                                            }
                                            if($checkFridayRow['doctor_status'] == "Inactive") {
                                                echo '<a href="?active='.$checkFridayRow['doctor_schedule_id'].'" class="btn btn-success" onclick="return confirm(\'Are you sure you want to active this schedule?\');" style="margin-right:10px;">Active</a>';
                                            }
                                            echo '<a href="?delete='.$checkFridayRow['doctor_schedule_id'].'" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this schedule?\');">Delete</a>';
                                        }
                                    }
                                echo '</td>';
                            echo '</tr>';
                            $count += 1;
                        }
                    }

                    // Saturday
                    $checkSaturdayQuery = $this->db->query("SELECT * FROM `doctor_schedule` WHERE `doctor_schedule_day` = 'Saturday' ORDER BY STR_TO_DATE(`doctor_schedule_start_time`,'%h:%i%p') ASC");
                    if($checkSaturdayQuery->num_rows() > 0) {
                        // exist
                        foreach($checkSaturdayQuery->result_array() as $checkSaturdayRow) {
                            echo '<tr>';
                                echo '<td>';
                                    $getDoctorQuery = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$checkSaturdayRow['doctor_id']."'");
                                    $getDoctorRow = $getDoctorQuery->result_array();
                                    echo $getDoctorRow[0]['doctor_name'];
                                echo '</td>';
                                echo '<td>';
                                    echo 'Saturday';
                                echo '</td>';
                                echo '<td>';
                                    echo $checkSaturdayRow['doctor_schedule_start_time'];
                                    echo ' - ';
                                    echo $checkSaturdayRow['doctor_schedule_end_time'];
                                echo '</td>';
                                echo '<td>';
                                    if($checkSaturdayRow['doctor_status'] == "Active") {
                                        echo '<label class="label label-success">';
                                            echo $checkSaturdayRow['doctor_status'];
                                        echo '</label>';
                                    } else {
                                        echo '<label class="label label-danger">';
                                            echo $checkSaturdayRow['doctor_status'];
                                        echo '</label>';
                                    }
                                echo '</td>';
                                echo '<td>';
                                    if($level == "doctor") {
                                        if($checkSaturdayRow['doctor_id'] == $this->session->id) {
                                            if($checkSaturdayRow['doctor_status'] == "Active") {
                                                echo '<a href="?inactive='.$checkSaturdayRow['doctor_schedule_id'].'" class="btn btn-warning" onclick="return confirm(\'Are you sure you want to inactive this schedule?\');" style="margin-right:10px;">Inactive</a>';
                                            }
                                            if($checkSaturdayRow['doctor_status'] == "Inactive") {
                                                echo '<a href="?active='.$checkSaturdayRow['doctor_schedule_id'].'" class="btn btn-success" onclick="return confirm(\'Are you sure you want to active this schedule?\');" style="margin-right:10px;">Active</a>';
                                            }
                                            echo '<a href="?delete='.$checkSaturdayRow['doctor_schedule_id'].'" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this schedule?\');">Delete</a>';
                                        }
                                    }
                                echo '</td>';
                            echo '</tr>';
                            $count += 1;
                        }
                    }

                    // Sunday
                    $checkSundayQuery = $this->db->query("SELECT * FROM `doctor_schedule` WHERE `doctor_schedule_day` = 'Sunday' ORDER BY STR_TO_DATE(`doctor_schedule_start_time`,'%h:%i%p') ASC");
                    if($checkSundayQuery->num_rows() > 0) {
                        // exist
                        foreach($checkSundayQuery->result_array() as $checkSundayRow) {
                            echo '<tr>';
                                echo '<td>';
                                    $getDoctorQuery = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$checkSundayRow['doctor_id']."'");
                                    $getDoctorRow = $getDoctorQuery->result_array();
                                    echo $getDoctorRow[0]['doctor_name'];
                                echo '</td>';
                                echo '<td>';
                                    echo 'Sunday';
                                echo '</td>';
                                echo '<td>';
                                    echo $checkSundayRow['doctor_schedule_start_time'];
                                    echo ' - ';
                                    echo $checkSundayRow['doctor_schedule_end_time'];
                                echo '</td>';
                                echo '<td>';
                                    if($checkSundayRow['doctor_status'] == "Active") {
                                        echo '<label class="label label-success">';
                                            echo $checkSundayRow['doctor_status'];
                                        echo '</label>';
                                    } else {
                                        echo '<label class="label label-danger">';
                                            echo $checkSundayRow['doctor_status'];
                                        echo '</label>';
                                    }
                                echo '</td>';
                                echo '<td>';
                                    if($level == "doctor") {
                                        if($checkSundayRow['doctor_id'] == $this->session->id) {
                                            if($checkSundayRow['doctor_status'] == "Active") {
                                                echo '<a href="?inactive='.$checkSundayRow['doctor_schedule_id'].'" class="btn btn-warning" onclick="return confirm(\'Are you sure you want to inactive this schedule?\');" style="margin-right:10px;">Inactive</a>';
                                            }
                                            if($checkSundayRow['doctor_status'] == "Inactive") {
                                                echo '<a href="?active='.$checkSundayRow['doctor_schedule_id'].'" class="btn btn-success" onclick="return confirm(\'Are you sure you want to active this schedule?\');" style="margin-right:10px;">Active</a>';
                                            }
                                            echo '<a href="?delete='.$checkSundayRow['doctor_schedule_id'].'" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this schedule?\');">Delete</a>';
                                        }
                                    }
                                echo '</td>';
                            echo '</tr>';
                            $count += 1;
                        }
                    }

                    if($count == 0) {
                        echo '<tr>';
                            echo '<td colspan="5" style="text-align:center;">';
                                echo 'No results found.';
                            echo '</td>';
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>