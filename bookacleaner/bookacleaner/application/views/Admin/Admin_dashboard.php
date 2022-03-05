<div class="col-lg-6 col-md-6">
    <div class="box-container">
        <div class="box-body">
            <h3>
                Cleaner(s)
                <div class="pull-right">
                    <button class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add Cleaner</button>
                </div>
            </h3>
            <div class="cleaners-list" style="clear: both; margin-bottom: 10px;">
            </div>
            <div class="pull-left">
                <button class="btn btn-primary" id="prev"><i class="fa fa-caret-left" aria-hidden="true"></i>&nbsp;Prev</button>
            </div>
            <div class="pull-right">
                <button class="btn btn-primary" id="next">Next&nbsp;<i class="fa fa-caret-right" aria-hidden="true"></i></button>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
</div>

<div class="col-lg-6 col-md-6">
    <div class="box-container">
        <div class="box-body">
            <h3>
                Booking(s)
            </h3>
            <div class="bookings-list" style="clear: both; margin-bottom: 10px;">
            </div>
            <div class="pull-left">
                <button class="btn btn-primary" id="prev2"><i class="fa fa-caret-left" aria-hidden="true"></i>&nbsp;Prev</button>
            </div>
            <div class="pull-right">
                <button class="btn btn-primary" id="next2">Next&nbsp;<i class="fa fa-caret-right" aria-hidden="true"></i></button>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var id = 1;
    var id2 = 1;
    var working;
    function checkCleaners(ids, type = "") {
        $.ajax({
            url: "<?php echo base_url(); ?>admin/getCleaners/"+ids,
            type: "GET",
            success: function(data){
                data = JSON.parse(data);
                if(data.length != 0) {
                    notif.play();
                    if(type == "add") {
                        id++;
                        getCleaners(id);
                    } else if(type == "minus") {
                        id--;
                        getCleaners(id);
                    }
                } else {
                    errorRow();
                }
                
            }
        });
    }
    function getCleaners(id) {
        $.ajax({
            url: "<?php echo base_url(); ?>admin/getCleaners/"+id,
            type: "GET",
            success: function(data){
                data = JSON.parse(data);
                $('.cleaners-list').html("");
                if(data.length != 0) {
                    for(var i = 0; i < data.length; i++) {
                        addRow(i, data[i]);
                    }
                }
            }
        });
    }
    function checkBookings(ids, type = "") {
        $.ajax({
            url: "<?php echo base_url(); ?>admin/getBookings/"+ids,
            type: "GET",
            success: function(data){
                data = JSON.parse(data);
                if(data.length != 0) {
                    notif.play();
                    if(type == "add") {
                        id2++;
                        getBookings(id2);
                    } else if(type == "minus") {
                        id2--;
                        getBookings(id2);
                    }
                } else {
                    errorRow();
                }
                
            }
        });
    }
    function getBookings(id) {
        $.ajax({
            url: "<?php echo base_url(); ?>admin/getBookings/"+id,
            type: "GET",
            success: function(data){
                data = JSON.parse(data);
                $('.bookings-list').html("");
                if(data.length != 0) {
                    for(var i = 0; i < data.length; i++) {
                        addRow2(i, data[i]);
                    }
                }
            }
        });
    }
    function addRow(i, data) {
        var x = '<div class="cleaners-row row-'+data.id+'">';
            x = x + '<span>'+data.cleaners_name+'</span>';
            x = x + '<span>'+data.cleaners_contact+'</span>';
            if(data.employee == 0) {
                if(data.available == 0) {
                    x = x + '<label class="label label-success">Off-duty</label>';
                } else {
                    x = x + '<label class="label label-danger">On-duty</label>';
                }
            } else {
                x = x + '<label class="label label-default">Former employee</label>';
            }
            
            if(data.employee == 0) {
                // working
                if(data.available == 0) {
                    // not working
                    x = x + '<div class="pull-right">';
                        x = x +'<button class="btn btn-danger" onclick="fire('+data.id+')"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;Fire Cleaner</button>';
                    x = x + '</div>';
                }
            } else {
                // former working
                x = x + '<div class="pull-right">';
                    x = x +'<button class="btn btn-success" onclick="hire('+data.id+')"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Hire Cleaner</button>';
                x = x + '</div>';
            }
        x = x + '</div>';
        $('.cleaners-list').append(x);
        
        var durations = i * 500;
        $('.row-'+data.id).hide().css({ opacity: 0, marginLeft: "200px"});
        $('.row-'+data.id).show(durations).animate({ opacity: 1, marginLeft: "0px"}, { duration: 'normal', easing: 'easeOutBack'});
    }
    function addRow2(i, data) {
        var x = '<div class="bookings-row row2-'+data.id+'">';
            x = x + '<div class="bookings-heading bookings-heading-'+data.id+'" onclick="bookClick('+data.id+')">';
                x = x + '<span>'+data.first_name+' '+data.last_name+'</span>';
                x = x + '<span><b>Preferred Date:</b> '+data.preferred_date+'</span>';
                if(data.status == "Pending") {
                    x = x +  '<label class="label label-default">Pending</label>';
                } else if(data.status == "Working") {
                    x = x +  '<label class="label label-warning" style="margin-right: 5px;">Working</label>';
                    x = x +  '<label class="label label-info">Cleaner: '+data.work_name+'</label>';
                } else if(data.status == "Completed") {
                    x = x +  '<label class="label label-success" style="margin-right: 5px;">Completed</label>';
                    x = x +  '<label class="label label-info">Cleaner: '+data.work_name+'</label>';
                } else if(data.status == "Cancelled") {
                    x = x +  '<label class="label label-danger">Cancelled</label>';
                }
                x = x + '<div class="button">';
                    x = x + '<i class="fa fa-caret-down"></i>';
                x = x + '</div>';
            x = x + '</div>';
            x = x + '<div class="bookings-body" style="display: none;">';
                x = x + '<b>E-mail:</b> '+data.email+' <br/>';
                x = x + '<b>Mobile Number:</b> '+data.mobile_number+' <br/>';
                x = x + '<b>E-mail:</b> '+data.email+' <br/>';
                x = x + '<b>Address:</b> '+data.address+' <br/>';
                x = x + '<b>Cleaning:</b> ';
                if(data.cleaning == 1) { x = x + 'Appartment'; }
                else if(data.cleaning == 2) { x = x + 'House'; }
                else if(data.cleaning == 3) { x = x + 'Condo'; }
                else if(data.cleaning == 4) { x = x + 'Office'; }
                else if(data.cleaning == 5) { x = x + 'Others (Plesase specify in NOTES)'; }
                x = x + '<br/>';
                x = x + '<b>How big is the space that needs cleaning? (sqm.):</b> '+data.sqm+' sqm.<br/>';
                x = x + '<b>Service Required:<br/>';
                for(var zz = 0; zz < data.service_required.length; zz++) {
                    if(data.service_required[zz] == 1) { x = x + 'Deep Cleaning<br/>'; } 
                    else if(data.service_required[zz] == 2) { x = x + 'Disinfection Service<br/>'; } 
                    else if(data.service_required[zz] == 3) { x = x + 'Move In & Move Out Cleaning<br/>'; } 
                    else if(data.service_required[zz] == 4) { x = x + 'Upholstery Cleaning<br/>'; } 
                    else if(data.service_required[zz] == 5) { x = x + 'Steam Cleaning<br/>'; } 
                    else if(data.service_required[zz] == 6) { x = x + 'Aircon Cleaning<br/>'; } 
                }
                x = x + '<br/>';
                x = x + '<b>Comments/Notes:</b> '+data.comments_or_notes+' <br/>';
            x = x + '</div>';
        x = x + '</div>';
        $('.bookings-list').append(x);
        
        var durations = i * 500;
        $('.row2-'+data.id).hide().css({ opacity: 0, marginLeft: "200px"});
        $('.row2-'+data.id).show(durations).animate({ opacity: 1, marginLeft: "0px"}, { duration: 'normal', easing: 'easeOutBack'});
    }

    function errorRow() {
        notif.play();
        Toast.fire({
            icon: 'error',
            title: 'Error! There\'s no data'
        });
    }

    getCleaners(id);
    getBookings(id2);
    
    function fire(ids) {
        $.ajax({
        url: "<?php echo base_url(); ?>admin/fireCleaner/"+ids,
            type: "GET",
            success: function(data){
                if(data.status == 203) {
                    Toast.fire({
                        icon: 'error',
                        title: data.message
                    });
                } else {
                    notif.play();
                    Toast.fire({
                        icon: 'success',
                        title: 'Successfully!'
                    });
                    getCleaners(id)
                }
            }
        });
    }
    function hire(ids) {
        $.ajax({
        url: "<?php echo base_url(); ?>admin/hireCleaner/"+ids,
            type: "GET",
            success: function(data){
                if(data.status == 203) {
                    Toast.fire({
                        icon: 'error',
                        title: data.message
                    });
                } else {
                    notif.play();
                    Toast.fire({
                        icon: 'success',
                        title: 'Successfully!'
                    });
                    getCleaners(id)
                }
            }
        });
    }
    function bookClick(id) {
        if($('.bookings-heading-'+id).siblings('.bookings-body').css('display') == 'none') {
            $('.bookings-heading-'+id).siblings('.bookings-body').hide().css({ opacity: 0, marginLeft: "200px"});
            $('.bookings-heading-'+id).siblings('.bookings-body').show('slow').animate({ opacity: 1, marginLeft: "0px"}, { duration: 'normal', easing: 'easeOutBack'});
            $('.bookings-heading-'+id+' i').attr("class", "fa fa-caret-up");
        } else {
            $('.bookings-heading-'+id).siblings('.bookings-body').show().css({ opacity: 1, marginLeft: "0px"});
            $('.bookings-heading-'+id).siblings('.bookings-body').hide('slow').animate({ opacity: 0, marginLeft: "200px"}, { duration: 'normal', easing: 'easeOutBack'});
            $('.bookings-heading-'+id+' i').attr("class", "fa fa-caret-down");
        }
    }

    $(document).ready(function() {
        $('#prev').click(function() {
            if(id > 1) {
                var s = id - 1;
                checkCleaners(s, 'minus');
            } else {
                errorRow();
            }
        });
        $('#next').click(function() {
            var s = id + 1;
            checkCleaners(s, 'add');
        });
        $('#prev2').click(function() {
            if(id2 > 1) {
                var s = id2 - 1;
                checkBookings(s, 'minus');
            } else {
                errorRow();
            }
        });
        $('#next2').click(function() {
            var s = id2 + 1;
            checkBookings(s, 'add');
        });
    });
</script>