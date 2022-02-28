<div class="col-lg-4">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://rawgit.com/tempusdominus/bootstrap-3/master/build/css/tempusdominus-bootstrap-3.css" crossorigin="anonymous">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://rawgit.com/tempusdominus/bootstrap-3/master/build/js/tempusdominus-bootstrap-3.js" crossorigin="anonymous"></script>
    <style>.fc-row .fc-bg {z-index: 1;background: #fff;}</style>
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
            events:"",
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
<div class="col-lg-8">

</div>