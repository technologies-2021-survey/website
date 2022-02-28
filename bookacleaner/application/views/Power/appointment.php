<?php $this->load->view('Power/navigation'); ?>
        
        <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>

        <!-- Tempus Dominus JavaScript -->
        <script src="https://rawgit.com/tempusdominus/bootstrap-3/master/build/js/tempusdominus-bootstrap-3.js" crossorigin="anonymous"></script>

        <!-- Tempus Dominus Styles -->
        <link href="https://rawgit.com/tempusdominus/bootstrap-3/master/build/css/tempusdominus-bootstrap-3.css" rel="stylesheet" crossorigin="anonymous">
        
        <style>.fc-row .fc-bg {z-index: 1;background: #fff;}</style>
        <div class="panel panel-primary" style="border-radius: 0px; border: 0px;">
            <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
                Appointment
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-3">
                        <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="form-control" style="margin-bottom: 10px;">
                            <option value="" selected="">Appointments</option>
                            <option value="./appointment_requests">Appointment Requests</option>
                        </select>
                    </div>
                </div>
                
                <div id="calendar"></div>

                <style>.fc-row .fc-bg {z-index: 1;background: #fff;}</style>
                
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
                <script type="text/javascript">
                    $(document).ready(function(){
                        var calendar = $('#calendar').fullCalendar({
                            editable:true,
                            header:{
                                left:'prev,next today',
                                center:'title',
                                right:'month,agendaWeek,agendaDay'
                            },
                            events:"<?php echo base_url() .'index.php/'. $textss; ?>/getAppointment",
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
