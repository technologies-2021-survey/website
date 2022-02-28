<?php

$countConsultations = 0;
$countAppointments = 0;
$selectionz = "";
$array = array('','','','');
if(isset($_GET['v'])) {
    $v = $_GET['v'];
    if($v == "1week") {
        date_default_timezone_set("Asia/Manila");
        $timestamp = strtotime(date("M d, Y 00:00:00", time()));
        $array[0] = ' selected';
        $selectionz = strtotime("-7 day", $timestamp); // 7 days
    } else if($v == "2weeks") {
        date_default_timezone_set("Asia/Manila");
        $timestamp = strtotime(date("M d, Y 00:00:00", time()));
        $array[1] = ' selected';
        $selectionz = strtotime("-14 day", $timestamp); // 2 weeks
    } else if($v == "1month") {
        date_default_timezone_set("Asia/Manila");
        $timestamp = strtotime(date("M d, Y 00:00:00", time()));
        $array[2] = ' selected';
        $selectionz = strtotime("-1 month", $timestamp); // 1 month
    } else if($v == "1year") {
        date_default_timezone_set("Asia/Manila");
        $timestamp = strtotime(date("M d, Y 00:00:00", time()));
        $array[3] = ' selected';
        $selectionz = strtotime("-1 year", $timestamp); // 1year
    }
} else {
    $array[0] = ' selected';
    $selectionz = strtotime("-7 day", $timestamp); // 7 days
}
date_default_timezone_set("Asia/Manila");
$timestamp = strtotime(date("M d, Y 00:00:00", time()));

if($this->session->selection != "doctor") {
    $q = $this->db->query("SELECT * FROM `consultations` WHERE (`date_consultation_sub` >= $selectionz OR `date_consultation_sub` <= $timestamp)");
    foreach($q->result_array() as $row) {
        $countConsultations++;
    }

    $q2 = $this->db->query("SELECT * FROM `appointments` WHERE (`appointment_timestamp_sub` >= $selectionz OR `appointment_timestamp_sub` <= $timestamp)");
    foreach($q2->result_array() as $row) {
        $countAppointments++;
    }

    $sum = $countAppointments + $countConsultations;

    $timestamp2 = strtotime(date("M d, Y 00:00:00", time()));
    $selection3 = strtotime("+1day -1minute", $timestamp2); // 1 month

    $approved = 0;
    $finished = 0;

    $q3 = $this->db->query("SELECT * FROM `consultations` WHERE (`date_consultation_sub` >= $timestamp2 OR date_consultation_sub <= $selection3) AND consultation_status = 'Approved'");
    foreach($q3->result_array() as $row) {
        $approved++;
    }

    $q4 = $this->db->query("SELECT * FROM `appointments` WHERE (`appointment_timestamp_sub` >= $timestamp2 OR appointment_timestamp_sub <= $selection3) AND appointment_status = 'Approved'");
    foreach($q4->result_array() as $row) {
        $approved++;
    }

    $q7 = $this->db->query("SELECT * FROM `consultations` WHERE (`date_consultation_sub` >= $timestamp2 OR date_consultation_sub <= $selection3) AND consultation_status = 'Finished'");
    foreach($q7->result_array() as $row) {
        $finished++;
    }

    $q8 = $this->db->query("SELECT * FROM `appointments` WHERE (`appointment_timestamp_sub` >= $timestamp2 OR appointment_timestamp_sub <= $selection3) AND appointment_status = 'Finished'");
    foreach($q8->result_array() as $row) {
        $finished++;
    }
} else {
    $q = $this->db->query("SELECT * FROM `consultations` WHERE (`date_consultation_sub` >= $selectionz OR `date_consultation_sub` <= $timestamp) AND `interview_id` = '".$this->session->id."'");
    foreach($q->result_array() as $row) {
        $countConsultations++;
    }

    $q2 = $this->db->query("SELECT * FROM `appointments` WHERE (`appointment_timestamp_sub` >= $selectionz OR `appointment_timestamp_sub` <= $timestamp) AND `interview_id` = '".$this->session->id."'");
    foreach($q2->result_array() as $row) {
        $countAppointments++;
    }

    $sum = $countAppointments + $countConsultations;

    $timestamp2 = strtotime(date("M d, Y 00:00:00", time()));
    $selection3 = strtotime("+1day -1minute", $timestamp2); // 1 month

    $approved = 0;
    $finished = 0;

    $q3 = $this->db->query("SELECT * FROM `consultations` WHERE (`date_consultation_sub` >= $timestamp2 OR date_consultation_sub <= $selection3) AND consultation_status = 'Approved'  AND `interview_id` = '".$this->session->id."'");
    foreach($q3->result_array() as $row) {
        $approved++;
    }

    $q4 = $this->db->query("SELECT * FROM `appointments` WHERE (`appointment_timestamp_sub` >= $timestamp2 OR appointment_timestamp_sub <= $selection3) AND appointment_status = 'Approved'  AND `interview_id` = '".$this->session->id."' ");
    foreach($q4->result_array() as $row) {
        $approved++;
    }

    $q7 = $this->db->query("SELECT * FROM `consultations` WHERE (`date_consultation_sub` >= $timestamp2 OR date_consultation_sub <= $selection3) AND consultation_status = 'Finished'  AND `interview_id` = '".$this->session->id."'");
    foreach($q7->result_array() as $row) {
        $finished++;
    }

    $q8 = $this->db->query("SELECT * FROM `appointments` WHERE (`appointment_timestamp_sub` >= $timestamp2 OR appointment_timestamp_sub <= $selection3) AND appointment_status = 'Finished'  AND `interview_id` = '".$this->session->id."'");
    foreach($q8->result_array() as $row) {
        $finished++;
    }
}

?>
<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>

<!-- Tempus Dominus JavaScript -->
<script src="https://rawgit.com/tempusdominus/bootstrap-3/master/build/js/tempusdominus-bootstrap-3.js" crossorigin="anonymous"></script>

<!-- Tempus Dominus Styles -->
<link href="https://rawgit.com/tempusdominus/bootstrap-3/master/build/css/tempusdominus-bootstrap-3.css" rel="stylesheet" crossorigin="anonymous">

<style>.fc-row .fc-bg {z-index: 1;background: #fff;}</style>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.1/Chart.min.js"></script>
<script type="text/javascript">
 
window.onload = function () {
    // Global Options:
    Chart.defaults.global.defaultFontColor = 'black';
    Chart.defaults.global.defaultFontSize = 16; 
    
    var canvas2 = document.getElementById("barChart2");
    var ctx2 = canvas2.getContext('2d');

	const data2 = {
        labels: ["Appointments", "Consultations"],
        datasets:[
            {
                fill: true,
                backgroundColor: [
                    '#4f81bc',
                    '#c0504e'
                ],
                data:  [
                    <?php if($countAppointments == null || $countAppointments == "") {echo "0";} else {echo $countAppointments;} ?>,
                    <?php if($countConsultations == null || $countConsultations == "") {echo "0";} else {echo $countConsultations;} ?>
                ]
            }
        ]
    }

    var canvas = document.getElementById("barChart");
    var ctx = canvas.getContext('2d');
    canvas.height = 140;

    var data = {
        labels: ["Approved", "Finished"],
        datasets: [
            {
                fill: true,
                backgroundColor: [
                    '#4f81bc',
                    '#c0504e',
                    '#cbc044'],
                data: [<?php echo $approved;?>, <?php echo $finished; ?>],
                borderColor: ['#4f81bc','#c0504e','#cbc044'],
                borderWidth: [2,2,2]
            }
        ]
    };

    // Notice the rotation from the documentation.

    var options = {
        title: {
            display: false,
        },
        responsive: true,
        maintainAspectRatio: false,
        animation: {
    duration: 500,
    easing: "easeOutQuart",
    onComplete: function () {
      var ctx = this.chart.ctx;
      ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontFamily, 'normal', Chart.defaults.global.defaultFontFamily);
      ctx.textAlign = 'center';
      ctx.textBaseline = 'bottom';

      this.data.datasets.forEach(function (dataset) {

        for (var i = 0; i < dataset.data.length; i++) {
          var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model,
              total = dataset._meta[Object.keys(dataset._meta)[0]].total,
              mid_radius = model.innerRadius + (model.outerRadius - model.innerRadius)/2,
              start_angle = model.startAngle,
              end_angle = model.endAngle,
              mid_angle = start_angle + (end_angle - start_angle)/2;

          var x = mid_radius * Math.cos(mid_angle);
          var y = mid_radius * Math.sin(mid_angle);

          ctx.fillStyle = '#fff';
          if (i == 3){ // Darker text color for lighter background
            ctx.fillStyle = '#444';
          }
          var percent = String(Math.round(dataset.data[i]/total*100)) + "%";      
          //Don't Display If Legend is hide or value is 0
          if(dataset.data[i] != 0 && dataset._meta[0].data[i].hidden != true) {
            ctx.fillText(dataset.data[i], model.x + x, model.y + y);
            // Display percent in another line, line break doesn't work for fillText
            ctx.fillText(percent, model.x + x, model.y + y + 15);
          }
        }
      });               
    }
  }

    };

    var options2 = {
        title: {
            display: true,
        },
        responsive: true,
        maintainAspectRatio: false,
        legend: { 
            display: false
        },
        scales: {
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
                ctx = chartInstance.ctx;

                ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                ctx.textAlign = 'center';
                ctx.textBaseline = 'bottom';
                

                this.data.datasets.forEach(function(dataset, i) {
                    var meta = chartInstance.controller.getDatasetMeta(i);
                    meta.data.forEach(function(bar, index) {
                        ctx.fillStyle = "#ffffff";
                        var data = dataset.data[index];
                        ctx.fillText(data, bar._model.x, bar._model.y+20);
                    });
                });
            }
        },
    };


    // Chart declaration:
    var myBarChart = new Chart(ctx, {
        type: 'pie',
        data: data,
        options: options
    });

    var myBarChart2 = new Chart(canvas2, {
        type: 'bar',
        data: data2,
        options: options2
    });
}
</script>
<div class="row">
    <div class="col-sm-6">
        <div class="panel panel-default" style="box-shadow: 0 0 1px #000;">
            <div class="panel-body">
                <h4>Schedule Status</h4>
                <div style="height: 502px;">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="panel panel-default" style="box-shadow: 0 0 1px #000;">
            <div class="panel-body">
                <h4>Number of Patients: <b><?php echo $sum; ?></b></h4>
                <div class="pull-right">
                    <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="form-control" style="margin-bottom: 10px;">
                        <option value="?v=1week"<?php echo $array[0];?>>Last week</option>
                        <option value="?v=2weeks"<?php echo $array[1];?>>Last 2 weeks</option>
                        <option value="?v=1month"<?php echo $array[2];?>>Last 1 month</option>
                        <option value="?v=1year"<?php echo $array[3];?>>Last 1 year</option>
                    </select>
                </div>
                <div style="clear:both;"></div>
                <div style="height: 458px;">
                    <canvas id="barChart2"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default" style="box-shadow: 0 0 1px #000;">
            <div class="panel-body">
                <?php
                $arrayThis = array();
                foreach($nextPatients as $row) {
                    $parent = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$row['parent_id']."'")->result_array();
                    $patient = $this->db->query("SELECT * FROM `patients_tbl` WHERE `patient_id` = '".$row['patient_id']."'")->result_array();
                    $arrayThis[] = array(
                        'id' => $row['overallId'],
                        'title' => $parent[0]['parent_name'] . " / " . $patient[0]['patient_name'] . " \n " . ucfirst($row['category']),
                        'start' => $row['datetime'],
                        'end' => $row['datetime_end'],
                        'parent_id' => $row['parent_id'],
                        'timestamp' => $row['timestamp'],
                        'timestamp_end' => $row['timestamp_end'],
                        'status' => $row['timestamp'],
                        'color' => ($row['category'] == "appointments") ? "#4caf50" : "#2196f3"
                    );
                }
                ?>
                <h4>Calendar</h4>
                <div id="calendar"></div>
                <script type="text/javascript">
                    $(document).ready(function(){
                        var calendar = $('#calendar').fullCalendar({
                            editable:true,
                            header:{
                                left:'prev,next today',
                                center:'title',
                                right:'month,agendaWeek,agendaDay'
                            },
                            events:<?php echo json_encode($arrayThis,JSON_UNESCAPED_SLASHES);?>,
                            selectable:true,
                            selectHelper:true,
                            
                            editable:true,
                        });
                    });
                    $(function () {
                        $('#dateTime').datetimepicker({
                            useSeconds: false,
                            stepping: 30,
                            disabledHours: [0, 1, 2, 3, 4, 5, 6, 7, 8],
                            enabledHours: [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24]
                        });
                        $('input#getTime').on('input', function(){
                            var datetime = $('#getTime').val();
                            var d = new Date(datetime);
                            d.setMinutes ( d.getMinutes() + 30 );
                            $('#getTime2').val(getDateTimeFromTimestamp(d));
                        });
                    });
                    function getDateTimeFromTimestamp(unixTimeStamp) {
                        var date = new Date(unixTimeStamp + 30 * 60000);
                        var hours = date.getHours();
                        var minutes = date.getMinutes();
                        var ampm = hours >= 12 ? 'PM' : 'AM';
                        hours = hours % 12;
                        hours = hours ? hours : 12; // the hour '0' should be '12'
                        minutes = minutes < 10 ? '0'+minutes : minutes;
                    
                        return ('0' + (date.getMonth() + 1)).slice(-2) + '/' +  ('0' + date.getDate()).slice(-2) + '/' + date.getFullYear() + ' ' + ('0' + hours).slice(-2) + ':' + ('0' + date.getMinutes()).slice(-2) + ampm;
                    }
                </script>
            </div>
        </div>
    </div>
</div>
