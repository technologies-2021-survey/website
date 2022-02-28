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

$m = date("m", $selectionz);
$d = date("d", $selectionz);
$y = date("y", $selectionz);
$h = date("h", $selectionz);
$i = date("i", $selectionz);
$a = date("s", $selectionz); 

$final = mktime($h, $i, $a, $m, $d, $y);
?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.1/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chart.piecelabel.js/0.14.1/Chart.PieceLabel.min.js"></script>

    <h4>Clinic Analytics</h4>
    <div class="row">
        <div class="col-md-4">
            <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="form-control" style="margin-bottom: 10px;">
                    <option value="?v=1week"<?php echo $array[0];?>>Last week</option>
                    <option value="?v=1month"<?php echo $array[1];?>>Last 1 month</option>
                    <option value="?v=1year"<?php echo $array[2];?>>Last 1 year</option>
                </select>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="panel panel-default" style="box-shadow: 0 0 1px #000;">
                <div class="panel-body" style="height: 280px;">
                    <h4>New Patients</h4>
                    <center style="font-size: 28px;font-weight:bold;text-align:center;padding-top:75px;">
                        <?php
                            $q = $this->db->query("SELECT * FROM `patients_tbl` WHERE `timestamp` >= '".$final."'");

                            echo $q->num_rows();
                        ?>
                    </center>
                </div>
                
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-default" style="box-shadow: 0 0 1px #000;">
                <div class="panel-body" style="height: 280px;">
                    <h4>Service Used</h4>
                    <center style="font-size: 28px;font-weight:bold;text-align:center;">
                        
                        <?php
                        $appointmentCounts = $this->db->query("SELECT * FROM `appointments` WHERE `appointment_timestamp_sub` >= '".$final."'")->num_rows();
                        $consultationCounts = $this->db->query("SELECT * FROM `consultations` WHERE `date_consultation_sub` >= '".$final."'")->num_rows();
                        $immunizationrecordCounts = $this->db->query("SELECT * FROM `immunization_record` WHERE `timestamp` >= '".$final."'")->num_rows();

                        // common illness
                        
                        $commonIllness = $this->db->query("SELECT terms_title, COUNT(*) as NUM FROM patients_illness_tbl WHERE `timestamp` >= '".$final."' GROUP BY `terms_title` ORDER BY NUM DESC LIMIT 3");
                        $array = array("","","");
                        $array2 = array("","","");


                        $sql_age = "SELECT CASE WHEN (DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(patient_birthdate, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(patient_birthdate, '00-%m-%d'))) <= 2 THEN '0-2' WHEN (DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(patient_birthdate, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(patient_birthdate, '00-%m-%d'))) <= 4 THEN '3-4' WHEN (DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(patient_birthdate, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(patient_birthdate, '00-%m-%d'))) <= 8 THEN '5-8' WHEN (DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(patient_birthdate, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(patient_birthdate, '00-%m-%d'))) <= 12 THEN '11-12' WHEN (DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(patient_birthdate, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(patient_birthdate, '00-%m-%d'))) <= 15 THEN '13-15' WHEN (DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(patient_birthdate, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(patient_birthdate, '00-%m-%d'))) <= 18 THEN '16-18' WHEN (DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(patient_birthdate, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(patient_birthdate, '00-%m-%d'))) <= 21 THEN '19-21' END AS age, COUNT(*) OVERALL FROM patients_tbl GROUP BY age";
                        // good but not enough!
                        //$sql_age = "SELECT COUNT(*) AS OVERALL, DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(patient_birthdate, '%Y') -( DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(patient_birthdate, '00-%m-%d')) AS age FROM patients_tbl GROUP BY age ORDER BY OVERALL DESC";

                        $array3 = array(); // overall
                        $array4 = array(); // text
                        $array5 = array(); // color
                        
                        $sx = 0;
                        $age = $this->db->query($sql_age);
                        foreach($age->result_array() as $row_age) {
                            $array3[] = $row_age['OVERALL'];                          
                            $array4[] = $row_age['age'];
                            $sx++;                   
                        }
                        function random_color_part() {
                            return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
                        }
                        
                        function random_color() {
                            return random_color_part() . random_color_part() . random_color_part();
                        }
                        
                        for($sdds = 0; $sdds < $sx; $sdds++) {
                            $array5[] = "#".random_color();
                        }
                    
                        $i = 0;
                        $s = 0;
                        foreach($commonIllness->result_array() as $rowzxczcz) {
                            if($rowzxczcz['terms_title'] != "") {
                                $array[$i] = htmlspecialchars($rowzxczcz['terms_title']);
                                $array2[$i] = htmlspecialchars($rowzxczcz['NUM']);
                            } else {
                                $s++;
                            }
                            $i++;
                        }
                        ?>
                        <script>
                            
                            window.onload = function () {

                                var canvas = document.getElementById("barChart").getContext('2d');
                                var canvas2 = document.getElementById("barChart2").getContext('2d');
                                var canvas3 = document.getElementById("barChart3").getContext('2d');

                                var data = {
                                    labels: ["Appointments", "Consultations", "Immunizations"],
                                    datasets: [
                                        {
                                            fill: false,
                                            backgroundColor: [
                                                '#4f81bc',
                                                '#c0504e',
                                                '#cbc044'],
                                            data: [<?php if($appointmentCounts != "") { echo $appointmentCounts; } else { echo '0'; } ?>,
                                                        <?php if($consultationCounts != "") { echo $consultationCounts; } else { echo '0'; } ?>,
                                                        <?php if($immunizationrecordCounts != "") { echo $immunizationrecordCounts; } else { echo '0'; } ?>],
                                            borderColor: ['#4f81bc','#c0504e','#cbc044'],
                                            borderWidth: [2,2,2]
                                        }
                                    ]
                                };

                                var data2 = {
                                    labels: [<?php if($s ==3){echo'Empty';}?><?php if($array[0] != "") {echo '"'.$array[0].'"';} ?><?php if($array[1] != "") {echo ',"'.$array[1].'"';} ?><?php if($array[2] != "") {echo ',"'.$array[2].'"';} ?>],
                                    datasets: [
                                        { 
                                            fill: false,
                                            backgroundColor: [<?php if($s ==3){echo'#000';}?><?php if($array[0] != "") { echo "'#4f81bc'"; } ?><?php if($array[1] != "") { echo ",'#c0504e'"; } ?><?php if($array[2] != "") { echo ",'#cbc044'"; } ?>],
                                            data: [<?php if($s ==3){echo'0';}?><?php if($array2[0] != "") {echo $array2[0];} ?><?php if($array2[1] != "") {echo ','.$array2[1];} ?><?php if($array2[2] != "") {echo ','.$array2[2];} ?>],
                                            borderColor: [<?php if($s ==3){echo'#000';}?><?php if($array[0] != "") { echo "'#4f81bc'"; } ?><?php if($array[1] != "") { echo ",'#c0504e'"; } ?><?php if($array[2] != "") { echo ",'#cbc044'"; } ?>],
                                            borderWidth: [<?php if($s ==3){echo'#000';}?><?php if($array[0] != "") { echo "2"; } ?><?php if($array[1] != "") { echo ",2"; } ?><?php if($array[2] != "") { echo ",2"; } ?>]
                                        }
                                    ]
                                };

                                var data3 = {
                                    labels: [<?php if($sx != 0) {for($sss = 0; $sss < $sx; $sss++) {if($sss==0) {echo '"'.$array4[$sss].'"';} else {echo ',"'.$array4[$sss].'"';}}} else {echo 'Empty';}?>],
                                    datasets: [
                                        { 
                                            axis: 'y',
                                            fill: false,
                                            backgroundColor: [<?php if($sx != 0) { for($sss2 = 0; $sss2 < $sx; $sss2++) { if($sss2==0) { echo '"'.$array5[$sss2].'"'; } else { echo ',"'.$array5[$sss2].'"'; } } } else { echo '#000000'; } ?>],
                                            data: [ <?php if($sx != 0) { for($sss3 = 0; $sss3 < $sx; $sss3++) { if($sss3==0) { echo $array3[$sss3]; } else { echo ','.$array3[$sss3]; } } } else { echo '0'; } ?> ],
                                            borderColor: [<?php if($sx != 0) { for($sss4 = 0; $sss4 < $sx; $sss4++) { if($sss4==0) { echo '"'.$array5[$sss4].'"'; } else { echo ',"'.$array5[$sss4].'"'; } } } else { echo '#000000'; } ?> ],
                                            borderWidth: [ <?php if($sx != 0) { for($sss5 = 0; $sss5 < $sx; $sss5++) {if($sss5==0) {echo '2';} else { echo ',2'; } } } else { echo '2'; } ?>]
                                        }
                                    ]
                                };

                                // Notice the rotation from the documentation.

                                var options = {
                                    title: {
                                        display: false,
                                    },
                                    rotation: -0.7 * Math.PI,
                                    maintainAspectRatio: false,
                                    pieceLabel: {
                                        mode: 'value',
                                        fontColor: '#fff',
                                    }
                                };

                                var options2 = {
                                    title: {
                                            display: false,
                                        },
                                    rotation: -0.7 * Math.PI,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    },
                                    legend: {
                                        display: false
                                    },
                                    scales:{
                                        yAxes: [{
                                            ticks: {
                                                beginAtZero:true
                                            }
                                        }]
                                    },
                                    "animation": {
                                        "duration": 1,
                                        "onComplete": function() {
                                            var chartInstance = this.chart,
                                            canvas2 = chartInstance.ctx;

                                            canvas2.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                            canvas2.textAlign = 'center';
                                            canvas2.textBaseline = 'bottom';
                                            

                                            this.data.datasets.forEach(function(dataset, i) {
                                                var meta = chartInstance.controller.getDatasetMeta(i);
                                                meta.data.forEach(function(bar, index) {
                                                    var data = dataset.data[index];
                                                    canvas2.fillStyle = "#ffffff";
                                                    canvas2.fillText(data, bar._model.x, bar._model.y+20);
                                                });
                                            });
                                        }
                                    },
                                };

                                var options3 = {
                                    indexAxis: 'y',
                                    title: {
                                            display: false,
                                        },
                                    rotation: -0.7 * Math.PI,
                                    maintainAspectRatio: false,
                                    legend: {
                                        display: false
                                    },
                                    scales:{
                                        xAxes: [{
                                            ticks: {
                                                beginAtZero:true
                                            }
                                        }]
                                    },
                                    "animation": {
                                        "duration": 1,
                                        "onComplete": function() {
                                            var chartInstance = this.chart,
                                            canvas3 = chartInstance.ctx;

                                            canvas3.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                                            canvas3.textAlign = 'center';
                                            canvas3.textBaseline = 'bottom';
                                            

                                            this.data.datasets.forEach(function(dataset, i) {
                                                var meta = chartInstance.controller.getDatasetMeta(i);
                                                meta.data.forEach(function(bar, index) {
                                                    var data = dataset.data[index];
                                                    canvas3.fillStyle = "#ffffff";
                                                    canvas3.fillText(data, bar._model.x-8, bar._model.y+8);
                                                });
                                            });
                                        }
                                    },
                                    
                                };


                                // Chart declaration:
                                var myBarChart = new Chart(canvas, {
                                    type: 'doughnut',
                                    data: data,
                                    options: options
                                });

                                var myBarChart2 = new Chart(canvas2, {
                                    type: 'bar',
                                    data: data2,
                                    options: options2
                                });

                                var myBarChart3 = new Chart(canvas3, {
                                    type: 'horizontalBar',
                                    data: data3,
                                    options: options3
                                });

                            }
                        </script>
                        <div style="height: 200px;">
                            <canvas id="barChart"></canvas>
                        </div>
                    </center>
                </div>
                
            </div>
        </div>
        
        <div class="col-sm-4">
            <div class="panel panel-default" style="box-shadow: 0 0 1px #000;">
                <div class="panel-body" style="height: 280px;">
                    <h4>Patient Satisfactions</h4>
                    <center style="font-size: 28px;font-weight:bold;text-align:center;padding-top:75px;">
                        <?php
                            $q = $this->db->query("SELECT * FROM `surveys_tbl` WHERE `timestamp` >= '".$final."'");

                            echo $q->num_rows();
                        ?>
                    </center>
                </div>
                
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="panel panel-default" style="box-shadow: 0 0 1px #000;">
                <div class="panel-body" style="height: 280px;">
                <h4>Age Range</h4>
                    <center style="font-size: 28px;font-weight:bold;text-align:center;">
                        <div style="height: 200px;">
                            <canvas id="barChart3"></canvas>
                        </div>
                    </center>
                </div>
                
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-default" style="box-shadow: 0 0 1px #000;">
                <div class="panel-body">
                    <h4>Top 3 Common Illness</h4>
                    <center style="font-size: 28px;font-weight:bold;text-align:center;">
                        <div style="height: 210px;">
                            <canvas id="barChart2"></canvas>
                        </div>
                    </center>
                </div>
                
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-default" style="box-shadow: 0 0 1px #000;">
                <div class="panel-body" style="height: 280px;">
                    <h4>List of Common Illness</h4>
                    <style>.table-wrapper { max-height: 207px; overflow: auto; }</style>
                    <div class="table-wrapper">
                        <table class="table table-striped table-hover ">
                            <thead>
                                <tr class="info">
                                    <td>
                                       Common Illness
                                    </td>
                                    <td>
                                        #
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $commonIllness3 = $this->db->query("SELECT terms_title, COUNT(*) as NUM FROM patients_illness_tbl WHERE `timestamp` >= '".$final."' GROUP BY `terms_title` ORDER BY NUM DESC");
                                    foreach($commonIllness3->result_array() as $row4) {
                                        echo '<tr>';
                                            echo '<td>'.htmlspecialchars($row4['terms_title']).'</td>';
                                            echo '<td>'.htmlspecialchars($row4['NUM']).'</td>';
                                        echo '</tr>';
                                    }
                                ?>
                            </tbody>
                            
                        </table>
                    </div>
                </div>
                
            </div>
        </div>
    </div>