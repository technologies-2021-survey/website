<?php
    date_default_timezone_set("Asia/Manila");
    error_reporting(0);
    $host = "localhost";
    $user = "u964751242_whealth";
    $dbname = "u964751242_whealth";
    $pass = "6!xl+msLx";
    $conn = mysqli_connect($host, $user, $pass, $dbname);

    if($_GET['error'] != "") {
        echo $_GET['error'];
        echo '<br/>';
    }
?>
<script src="https://code.jquery.com/jquery-1.12.4.min.js" ></script>
<script type="text/javascript">
// select start time
$(document).ready(function() {
    $('select[name=doctor_schedule_start_time]').change(function() {
        //console.log($('select[name=doctor_schedule_start_time]')[0].selectedIndex);
        $('select[name=doctor_schedule_end_time]').prop('selectedIndex', $('select[name=doctor_schedule_start_time]')[0].selectedIndex);

    });
});
</script>
<?php
    if(isset($_POST['submit'])) {
        $doctor_id                  = $_POST['doctor_id'];
        $doctor_schedule_day        = $_POST['doctor_schedule_day'];
        $doctor_schedule_start_time = $_POST['doctor_schedule_start_time'];
        $doctor_status              = $_POST['doctor_status'];

        echo "hehe".$_POST['doctor_id'];
        echo '<br/>';
        $submitQuery = mysqli_query($conn, "SELECT * FROM `doctor_schedule` WHERE `doctor_schedule_day` = '".$doctor_schedule_day."' AND `doctor_schedule_start_time` = '".$doctor_schedule_start_time."'");

        if(mysqli_num_rows($submitQuery) > 0) {
            // exist
            // error
            header("Location: ?error=Oops, that schedule is already exist!");
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
            } else if($doctor_schedule_start_time == "12:00PM") {
            } else if($doctor_schedule_start_time == "12:30PM") {
            } else if($doctor_schedule_start_time == "01:00PM") {
            } else if($doctor_schedule_start_time == "01:30PM") {
            } else if($doctor_schedule_start_time == "02:00PM") {
            } else if($doctor_schedule_start_time == "02:30PM") {
            } else if($doctor_schedule_start_time == "03:00PM") {
            } else if($doctor_schedule_start_time == "03:30PM") {
            } else if($doctor_schedule_start_time == "04:00PM") {
            } else if($doctor_schedule_start_time == "04:30PM") {
            } else if($doctor_schedule_start_time == "05:00PM") {
            } else if($doctor_schedule_start_time == "05:30PM") {
            } else {
                header("Location: ?error=Oops, you\'re trying to sql injection? hmm. (2)");
                die();
            }
            
            if( $doctor_status == "Active" ) {
            } else if( $doctor_status == "Inactive" ) {
            } else {
                header("Location: ?error=Oops, you\'re trying to sql injection? hmm. (3)");
                die();
            }

            $checkDoctorQuery = mysqli_query($conn, "SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$doctor_id."'");
            if(mysqli_num_rows($checkDoctorQuery) > 0) {
                // exist
                mysqli_query($conn, "INSERT INTO `doctor_schedule` 
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
                header("Location: ?success");
            } else {
                // not exist
                header("Location: ?error=Oops, that doctor id doesn\'t exist!");
                die();
            }
        }
    }
?>
<fieldset>
    <legend>Add Schedule</legend>
    <form action="" method="POST">
        <label>Doctor Name</label><br/>
        <select name="doctor_id" class="form-control">
            <?php
                $getDoctorQuery = mysqli_query($conn, "SELECT * FROM `doctors_tbl`");
                while($getDoctorRow = mysqli_fetch_array($getDoctorQuery)) {
                    echo '<option value="'.$getDoctorRow['doctor_id'].'">'.$getDoctorRow['doctor_name'].'</option>';
                }
            ?>
        </select><br/><br/>

        <label>Day</label><br/>
        <select name="doctor_schedule_day" class="form-control">
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
            <option value="Saturday">Saturday</option>
            <option value="Sunday">Sunday</option>
        </select><br/><br/>

        <label>Start Time</label><br/>
        <select name="doctor_schedule_start_time" class="form-control">
            <?php
                $date = "9:00AM";
                for($i = 0; $i < 18; $i++) {
                    echo '<option value="'.date("h:iA", strtotime($date)).'">'. date("h:iA", strtotime($date)).'</option>';
                    $date = date("h:iA", strtotime($date . " +30 mins")); 
                }
            ?>
        </select><br/><br/>

        <label>End Time</label><br/>
        <select name="doctor_schedule_end_time" class="form-control">
            <?php
                $date = "8:30AM";
                for($i = 0; $i < 18; $i++) {
                    $date = date("h:iA", strtotime($date . " +30 mins")); 
                    echo '<option value="'.date("h:iA", strtotime($date . " + 30 mins")).'">'. date("h:iA", strtotime($date . " + 30 mins")).'</option>';
                }
            ?>
        </select><br/><br/>

        <label>Status</label><br/>
        <select name="doctor_status" class="form-control">
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
        </select><br/><br/>

        <input type="submit" name="submit">
    </form>
</fieldset>
<fieldset> 
    <legend>Schedule(s) List</legend>
    <table border="1"> 
        <thead>
            <tr>
                <td>
                    Doctor #
                </td>
                <td>
                    Day 
                </td>
                <td>
                    Start Time
                </td>
                <td> 
                    Start End
                </td>
                <td>
                    Status
                </td>
            </tr>
        </thead> 
        <tbody>
            <?php
                $count = 0;

                // Monday
                $checkMondayQuery = mysqli_query($conn, "SELECT * FROM `doctor_schedule` WHERE `doctor_schedule_day` = 'Monday' ORDER BY STR_TO_DATE(`doctor_schedule_start_time`,'%h:%i%p') ASC");
                if(mysqli_num_rows($checkMondayQuery) > 0) {
                    // exist
                    while($checkMondayRow = mysqli_fetch_array($checkMondayQuery)) {
                        echo '<tr>';
                            echo '<td>';
                                $getDoctorQuery = mysqli_query($conn, "SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$checkMondayRow['doctor_id']."'");
                                $getDoctorRow = mysqli_fetch_array($getDoctorQuery);
                                echo $getDoctorRow['doctor_name'];
                            echo '</td>';
                            echo '<td>';
                                echo 'Monday';
                            echo '</td>';
                            echo '<td>';
                                echo $checkMondayRow['doctor_schedule_start_time'];
                            echo '</td>';
                            echo '<td>';
                                echo $checkMondayRow['doctor_schedule_end_time'];
                            echo '</td>';
                            echo '<td>';
                                echo $checkMondayRow['doctor_status'];
                            echo '</td>';
                        echo '</tr>';
                        $count += 1;
                    }
                }

                // Tuesday
                $checkTuesdayQuery = mysqli_query($conn, "SELECT * FROM `doctor_schedule` WHERE `doctor_schedule_day` = 'Tuesday' ORDER BY STR_TO_DATE(`doctor_schedule_start_time`,'%h:%i%p') ASC");
                if(mysqli_num_rows($checkTuesdayQuery) > 0) {
                    // exist
                    while($checkTuesdayRow = mysqli_fetch_array($checkTuesdayQuery)) {
                        echo '<tr>';
                            echo '<td>';
                                $getDoctorQuery = mysqli_query($conn, "SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$checkTuesdayRow['doctor_id']."'");
                                $getDoctorRow = mysqli_fetch_array($getDoctorQuery);
                                echo $getDoctorRow['doctor_name'];
                            echo '</td>';
                            echo '<td>';
                                echo 'Tuesday';
                            echo '</td>';
                            echo '<td>';
                                echo $checkTuesdayRow['doctor_schedule_start_time'];
                            echo '</td>';
                            echo '<td>';
                                echo $checkTuesdayRow['doctor_schedule_end_time'];
                            echo '</td>';
                            echo '<td>';
                                echo $checkTuesdayRow['doctor_status'];
                            echo '</td>';
                        echo '</tr>';
                        $count += 1;
                    }
                }

                // Wednesday
                $checkWednesdayQuery = mysqli_query($conn, "SELECT * FROM `doctor_schedule` WHERE `doctor_schedule_day` = 'Wednesday' ORDER BY STR_TO_DATE(`doctor_schedule_start_time`,'%h:%i%p') ASC");
                if(mysqli_num_rows($checkWednesdayQuery) > 0) {
                    // exist
                    while($checkWednesdayRow = mysqli_fetch_array($checkWednesdayQuery)) {
                        echo '<tr>';
                            echo '<td>';
                                $getDoctorQuery = mysqli_query($conn, "SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$checkWednesdayRow['doctor_id']."'");
                                $getDoctorRow = mysqli_fetch_array($getDoctorQuery);
                                echo $getDoctorRow['doctor_name'];
                            echo '</td>';
                            echo '<td>';
                                echo 'Wednesday';
                            echo '</td>';
                            echo '<td>';
                                echo $checkWednesdayRow['doctor_schedule_start_time'];
                            echo '</td>';
                            echo '<td>';
                                echo $checkWednesdayRow['doctor_schedule_end_time'];
                            echo '</td>';
                            echo '<td>';
                                echo $checkWednesdayRow['doctor_status'];
                            echo '</td>';
                        echo '</tr>';
                        $count += 1;
                    }
                }

                // Thursday
                $checkThursdayQuery = mysqli_query($conn, "SELECT * FROM `doctor_schedule` WHERE `doctor_schedule_day` = 'Thursday' ORDER BY STR_TO_DATE(`doctor_schedule_start_time`,'%h:%i%p') ASC");
                if(mysqli_num_rows($checkThursdayQuery) > 0) {
                    // exist
                    while($checkThursdayRow = mysqli_fetch_array($checkThursdayQuery)) {
                        echo '<tr>';
                            echo '<td>';
                                $getDoctorQuery = mysqli_query($conn, "SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$checkThursdayRow['doctor_id']."'");
                                $getDoctorRow = mysqli_fetch_array($getDoctorQuery);
                                echo $getDoctorRow['doctor_name'];
                            echo '</td>';
                            echo '<td>';
                                echo 'Thursday';
                            echo '</td>';
                            echo '<td>';
                                echo $checkThursdayRow['doctor_schedule_start_time'];
                            echo '</td>';
                            echo '<td>';
                                echo $checkThursdayRow['doctor_schedule_end_time'];
                            echo '</td>';
                            echo '<td>';
                                echo $checkThursdayRow['doctor_status'];
                            echo '</td>';
                        echo '</tr>';
                        $count += 1;
                    }
                }

                // Friday
                $checkFridayQuery = mysqli_query($conn, "SELECT * FROM `doctor_schedule` WHERE `doctor_schedule_day` = 'Friday' ORDER BY STR_TO_DATE(`doctor_schedule_start_time`,'%h:%i%p') ASC");
                if(mysqli_num_rows($checkFridayQuery) > 0) {
                    // exist
                    while($checkFridayRow = mysqli_fetch_array($checkFridayQuery)) {
                        echo '<tr>';
                            echo '<td>';
                                $getDoctorQuery = mysqli_query($conn, "SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$checkFridayRow['doctor_id']."'");
                                $getDoctorRow = mysqli_fetch_array($getDoctorQuery);
                                echo $getDoctorRow['doctor_name'];
                            echo '</td>';
                            echo '<td>';
                                echo 'Friday';
                            echo '</td>';
                            echo '<td>';
                                echo $checkFridayRow['doctor_schedule_start_time'];
                            echo '</td>';
                            echo '<td>';
                                echo $checkFridayRow['doctor_schedule_end_time'];
                            echo '</td>';
                            echo '<td>';
                                echo $checkFridayRow['doctor_status'];
                            echo '</td>';
                        echo '</tr>';
                        $count += 1;
                    }
                }

                // Saturday
                $checkSaturdayQuery = mysqli_query($conn, "SELECT * FROM `doctor_schedule` WHERE `doctor_schedule_day` = 'Saturday' ORDER BY STR_TO_DATE(`doctor_schedule_start_time`,'%h:%i%p') ASC");
                if(mysqli_num_rows($checkSaturdayQuery) > 0) {
                    // exist
                    while($checkSaturdayRow = mysqli_fetch_array($checkSaturdayQuery)) {
                        echo '<tr>';
                            echo '<td>';
                                $getDoctorQuery = mysqli_query($conn, "SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$checkSaturdayRow['doctor_id']."'");
                                $getDoctorRow = mysqli_fetch_array($getDoctorQuery);
                                echo $getDoctorRow['doctor_name'];
                            echo '</td>';
                            echo '<td>';
                                echo 'Saturday';
                            echo '</td>';
                            echo '<td>';
                                echo $checkSaturdayRow['doctor_schedule_start_time'];
                            echo '</td>';
                            echo '<td>';
                                echo $checkSaturdayRow['doctor_schedule_end_time'];
                            echo '</td>';
                            echo '<td>';
                                echo $checkSaturdayRow['doctor_status'];
                            echo '</td>';
                        echo '</tr>';
                        $count += 1;
                    }
                }

                // Sunday
                $checkSundayQuery = mysqli_query($conn, "SELECT * FROM `doctor_schedule` WHERE `doctor_schedule_day` = 'Sunday' ORDER BY STR_TO_DATE(`doctor_schedule_start_time`,'%h:%i%p') ASC");
                if(mysqli_num_rows($checkSundayQuery) > 0) {
                    // exist
                    while($checkSundayRow = mysqli_fetch_array($checkSundayQuery)) {
                        echo '<tr>';
                            echo '<td>';
                                $getDoctorQuery = mysqli_query($conn, "SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$checkSundayRow['doctor_id']."'");
                                $getDoctorRow = mysqli_fetch_array($getDoctorQuery);
                                echo $getDoctorRow['doctor_name'];
                            echo '</td>';
                            echo '<td>';
                                echo 'Sunday';
                            echo '</td>';
                            echo '<td>';
                                echo $checkSundayRow['doctor_schedule_start_time'];
                            echo '</td>';
                            echo '<td>';
                                echo $checkSundayRow['doctor_schedule_end_time'];
                            echo '</td>';
                            echo '<td>';
                                echo $checkSundayRow['doctor_status'];
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
</fieldset>
<fieldset>
    <legend>Legend</legend>
    Active means ready to getting some appointments/consultation<br/>
    Inactive means break or something else.
</fieldset>
<hr/>
<?php
if(isset($_GET['doctor_id'])) {
    // array day of doctor
    $array = array('','','','','','','');
    
    $count = 0;

    // get in database
    $getDoctorQuery2 = mysqli_query($conn, "SELECT * FROM `doctor_schedule` WHERE `doctor_id` = '".$_GET['doctor_id']."' GROUP BY `doctor_schedule_day`");
    $s = 0;
    while($getDoctorRow2 = mysqli_fetch_array($getDoctorQuery2)) {
        $array[$s] = array($getDoctorRow2['doctor_schedule_day']);
        $count++;
        $s++;
    }

    if($count == 0) {
        echo '<div style="border:1px solid #000;">No results found</div>';
    } else {
        // counts all array
        $countsHehe = 0;
        $countsAllArray = 0;
        for($sx = 0; $sx < 7; $sx++) {
            if(!empty($array[$countsHehe][0])) {
                $countsAllArray++;
            }
            $countsHehe++;
        }
        $time = time();
        $getTime = $time;
        $dates = "";
        for($i = 0; $i < 7; $i++) { // 1week
            $dates = date("M d, Y h:iA", strtotime(date("M d, Y", $getTime) . " 09:00AM"));
            for($s = 0; $s < $countsAllArray; $s++) { // per day
                for($xi = 0; $xi < 19; $xi++) {
                    $checkDoctorSchedQuery = mysqli_query($conn, "SELECT * FROM `doctor_schedule` WHERE `doctor_schedule_day` = '".date("l", strtotime($dates))."' and `doctor_schedule_start_time` = '".date("h:iA", strtotime($dates))."' AND `doctor_id` = '".$_GET['doctor_id']."'");
                    if(mysqli_num_rows($checkDoctorSchedQuery) > 0) {
                        $checkDoctorSchedRow = mysqli_fetch_array($checkDoctorSchedQuery);
                        echo "<b>".date("M d, Y h:iA", strtotime($dates))."</b>";
                        
                        echo " - <b>".date("h:iA", strtotime($dates))."</b>";
                        echo " / ".date("l", strtotime($dates))."</b>";

                        $getDoctorDataSchedQuery = mysqli_query($conn, "SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$checkDoctorSchedRow['doctor_id']."'");
                        $getDoctorDataSchedRow = mysqli_fetch_array($getDoctorDataSchedQuery);
                        echo " / ".$getDoctorDataSchedRow['doctor_name'];
                        

                        $getAppointmentCheckQuery = mysqli_query($conn, "SELECT * FROM `appointments` WHERE `appointment_datetime` = '".date("m/d/Y H:i:00", strtotime($dates))."'");
                        if(mysqli_num_rows($getAppointmentCheckQuery) > 0) {
                            echo " / Reserved in Appointment";
                        }

                        $getConsultationCheckQuery = mysqli_query($conn, "SELECT * FROM `consultations` WHERE `date_consultation_datetime` = '".date("m/d/Y H:i:00", strtotime($dates))."'");
                        if(mysqli_num_rows($getConsultationCheckQuery) > 0) {
                            echo " / Reserved in consultation";
                        }

                        echo '<br/>';
                    }
                    $dates = date("M d, Y h:iA", strtotime($dates . " +30 mins")); 
                    /*else {
                        echo "<b>".date("M d, Y h:iA", strtotime($dates))."</b>";
                        $dates = date("M d, Y h:iA", strtotime($dates . " +30 mins"));
                        echo " - <b>".date("h:iA", strtotime($dates))."</b>";
                        echo " / ".date("l", strtotime($dates))."</b>";
                        echo '<br/>';
                    }*/

                    /*if(date("l",strtotime($dates)) == $array[$s][0]) {
                    if($checking == 0) {
                        echo '<b>'.$dates . ': '.date('l', strtotime($dates . " 09:00AM")).'</b>';
                    } else {
                        echo '<b>'.$dates . ': '.date('l', strtotime($dates)).'</b>';
                    }
                    echo '<br/>';
                    for($ix = 0; $ix < 18; $ix++) { // hours
                        echo '<i style="margin-left:10px;">';
                        echo date("M d, Y h:iA", strtotime($dates));
                        echo '</i><br/>';
                        $dates = date("M d, Y h:iA", strtotime($dates . " +30 mins")); 
                    }
                    $checking++;
                    }*/
                }
            }
            $dates = date("M d, Y", strtotime($dates . ' +1day'));
            $getTime = strtotime('+1 day', $getTime);
            
        }
    }
} else {
?>
    <fieldset>
        <legend>Check Schedule of Doctor</legend>
        <form action="" method="GET">
            <select name="doctor_id" class="form-control">
                <?php
                    $getDoctorQuery = mysqli_query($conn, "SELECT * FROM `doctors_tbl`");
                    while($getDoctorRow = mysqli_fetch_array($getDoctorQuery)) {
                        echo '<option value="'.$getDoctorRow['doctor_id'].'">'.$getDoctorRow['doctor_name'].'</option>';
                    }
                ?>
            </select>
            <input type="submit" name="add" value="submit"/>
        </form>
    </fieldset>
<?php
}
?>