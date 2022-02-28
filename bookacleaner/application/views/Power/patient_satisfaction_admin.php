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

$array = array();
$selectionz = "";
$textz = "";
$timestamp = "";
if(isset($_GET['v'])) {
    $v = $_GET['v'];
    if($v == "1week") {
        date_default_timezone_set("Asia/Manila");
        $timestamp = strtotime(date("M d, Y 00:00:00", time()));
        $textz = 'Weekly';
        $selectionz = strtotime("-7 day", $timestamp); // 7 days
        $array[0] = " selected";
    } else if($v == "1month") {
        date_default_timezone_set("Asia/Manila");
        $timestamp = strtotime(date("M d, Y 00:00:00", time()));
        $textz = 'Monthly';
        $selectionz = strtotime("-1 month", $timestamp); // 1 month
        $array[1] = " selected";
    } else if($v == "1year") {
        date_default_timezone_set("Asia/Manila");
        $timestamp = strtotime(date("M d, Y 00:00:00", time()));
        $textz = 'Yearly';
        $selectionz = strtotime("-1 year", $timestamp); // 1year
        $array[2] = " selected";
    }
} else {
    $textz = 'Weekly';
    $timestamp = strtotime(date("M d, Y 00:00:00", time()));
    $selectionz = strtotime("-7 day", $timestamp); // 7 days
    $array[0] = " selected";
}

$counts = 0;
$overallCounts = 0;

if(is_numeric($_GET['doctor_id']) == 1) {
    $axcasdasd = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".htmlspecialchars($_GET['doctor_id'])."'");
    if($axcasdasd->num_rows() > 0) {
        // existing
    } else {
        redirect($textss."/patient_satisfaction?error=There\'s something wrong");
    }
} else if($_GET['doctor_id'] != "") {
    redirect($textss."/patient_satisfaction?error=There\'s something wrong");
}

$q = $this->db->query("SELECT * FROM `consultations` WHERE `date_consultation_sub` >= '".$selectionz."' AND interview_id = '".$_GET['doctor_id']."' AND consultation_status = 'Finished'");
foreach($q->result_array() as $row) {
    $counts++;
}
$q2 = $this->db->query("SELECT * FROM `appointments` WHERE `appointment_timestamp_sub` >= '".$selectionz."' AND interview_id = '".$_GET['doctor_id']."' AND appointment_status = 'Finished'");
foreach($q2->result_array() as $row2) {
    $counts++;
}
$q3 = $this->db->query("SELECT * FROM `consultations`")->num_rows();
$q4 = $this->db->query("SELECT * FROM `appointments`")->num_rows();

$overallCounts = $q3+$q4;
$responseRate = 0;
if($counts != 0 && $overallCounts != 0) {
    $responseRate = $counts / $overallCounts * 100;
}

$minusRate = 100 - $responseRate;

/*
echo 'Counts: '.$counts.'<br/>';
echo $selectionz.'<br/>';
echo $timestamp.'<br/>';
*/
?>
<div class="row">
    <div class="col-md-4">
        <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="form-control" style="margin-bottom: 10px;">
            <option value="">Select...</option>
            <?php
                $asd = $this->db->query("SELECT * FROM doctors_tbl");
                if($asd->num_rows() > 0) {
                    foreach($asd->result_array() as $rows) {
                        $vasasdsd = htmlspecialchars($_GET['doctor_id']) == $rows['doctor_id'] ? " selected": "";
                        echo '<option value="?doctor_id='.$rows['doctor_id'].'" '.$vasasdsd.'>'.htmlspecialchars($rows['doctor_name']).'</option>';
                    }

                } else {
                    echo '';
                }

            ?>
        </select>
    </div>
    <?php
        if(isset($_GET['doctor_id'])) {
    ?>
    <div class="col-md-4">
        
        <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="form-control" style="margin-bottom: 10px;">
            <option value="?doctor_id=<?php echo htmlspecialchars($_GET['doctor_id']); ?>&v=1week"<?php echo $array[0];?>>Last week</option>
            <option value="?doctor_id=<?php echo htmlspecialchars($_GET['doctor_id']); ?>&v=1month"<?php echo $array[1];?>>Last 1 month</option>
            <option value="?doctor_id=<?php echo htmlspecialchars($_GET['doctor_id']); ?>&v=1year"<?php echo $array[2];?>>Last 1 year</option>
        </select>
    </div>
    <?php
        }
    ?>

</div>
<?php
    if(isset($_GET['doctor_id'])) {
?>
<div class="row">
    <div class="col-md-4">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
        <script type="text/javascript">
        $(document).ready(function() {
            function createChart(chartId, chartData) {
                const ctx = document.getElementById(chartId);
                const myChart = new Chart(ctx, {
                    type: chartData.type,
                    data: chartData.data,
                    options: chartData.options,
                });
            };
            const myChartData = {
                type: 'doughnut',
                data: {
                    labels: ["Response", "Not response"],
                    datasets: [{
                        label: "Response Rate",
                        data: [<?php if($responseRate != "") {echo $responseRate;} else{echo '0';}?>, <?php if($minusRate != "") {echo $minusRate;} else {echo'0'; } ?>],
                        backgroundColor: [
                            'rgba(255, 153, 102, 1)',
                            'rgba(80, 47, 30, 1)',
                        ],
                        borderColor: [
                            'rgba(255, 153, 102, 1)',
                            'rgba(80, 47, 30, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                }
            };
            createChart('myChart', myChartData);


            

            
        });
        </script>
        <div class="panel panel-default" style="box-shadow: 0 0 1px #000;">
            <div class="panel-body">
                <h4>Response Rate (<?php echo $textz; ?>)</h4>
                <div style="height: 154px">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default" style="box-shadow: 0 0 1px #000;">
            <div class="panel-body">
                <h4>Overall Questions (<?php echo $textz; ?>)</h4>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr class="success">
                            <td></td>
                            <td>Top Answer</td>
                            <td>Percentage</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="height: 50px;">
                            <td>How would you rate this service? </td>
                            <?php
                                $texts = "";
                                
                                $hehe = $this->db->query("SELECT answer_one, COUNT(*) as NUM FROM surveys_tbl WHERE `timestamp` >= '".$selectionz."' AND interview_id = '".$_GET['doctor_id']."' GROUP BY `answer_one`")->result_array();
                                $hehe2 = $this->db->query("SELECT answer_two, COUNT(*) as NUM FROM surveys_tbl WHERE `timestamp` >= '".$selectionz."' AND interview_id = '".$_GET['doctor_id']."' GROUP BY `answer_two`")->result_array();

                                $haha = $this->db->query("SELECT * FROM surveys_tbl WHERE `timestamp` >= '".$selectionz."' AND interview_id = '".$_GET['doctor_id']."'")->num_rows();
                                $haha2 = $this->db->query("SELECT * FROM surveys_tbl WHERE `timestamp` >= '".$selectionz."' AND interview_id = '".$_GET['doctor_id']."'")->num_rows();
                                
                                $counts = $this->db->query("SELECT COUNT(*) as NUM FROM surveys_tbl WHERE `timestamp` >= '".$selectionz."' AND interview_id = '".$_GET['doctor_id']."' ORDER BY NUM DESC")->result_array();
                            ?>
                            <td>
                                <?php if($hehe[0]['answer_one'] != "") {echo $hehe[0]['answer_one'];} else {echo '0';}?>
                            </td>
                            <td>
                                <?php if($hehe[0]['NUM'] != "") { echo ($hehe[0]['NUM']/$haha)*100; } else {echo'0';}?>%
                            </td>
                        </tr>
                        <tr style="height: 50px;">
                            <td>Would you recommend this app to others?</td>
                            <td>
                                <?php if($hehe2[0]['answer_two'] != "") {echo $hehe2[0]['answer_two'];}else{echo'0';} ?>
                            </td>
                            <td>
                                <?php if($hehe2[0]['NUM'] != "") {echo ($hehe2[0]['NUM']/$haha2)*100;} else {echo'0';} ?>%
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
    <div class="col-md-4">
        <div class="panel panel-default" style="box-shadow: 0 0 1px #000;">
            <div class="panel-body">
                <h4>Feedback</h4>
                <?php
                    $asd = $this->db->query("SELECT * FROM surveys_tbl WHERE `timestamp` >= $selectionz AND interview_id = '".$_GET['doctor_id']."' ORDER BY survey_id DESC LIMIT 4");
                    if($asd->num_rows() > 0) {
                        foreach($asd->result_array() as $row) {
                            if($row['answer_three'] != "" || $row['answer_three'] != "null") {
                                echo '<div class="panel panel-default" style="box-shadow: 0 0 1px #000;margin-bottom: 0px;">';
                                    echo '<div class="panel-body">';
                                        echo htmlspecialchars($row['answer_three']);
                                    echo '</div>';
                                echo '</div>';
                            }
                        }
                    } else {
                        echo '';
                    }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
    }
?>
