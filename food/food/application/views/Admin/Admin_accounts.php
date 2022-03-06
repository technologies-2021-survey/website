<div class="col-lg-6 col-md-6">
    <div class="box-container">
        <div class="box-body">
            <h3>Account's List</h3>
            <div class="accounts-list" style="margin-bottom: 10px;">
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
            <h3>Customer's List</h3>
            <div class="customers-list" style="margin-bottom: 10px;">
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
    function checkAccounts(ids, type = "") {
        $.ajax({
            url: "<?php echo base_url(); ?>admin/getAccounts/"+ids,
            type: "GET",
            success: function(data){
                data = JSON.parse(data);
                if(data.length != "") {
                    notif.play();
                    if(type == "add") {
                        id++;
                        getAccounts(id);
                    } else if(type == "minus") {
                        id--;
                        getAccounts(id);
                    }
                } else {
                    errorRow();
                }
                
            }
        });
    }
    function getAccounts(ids) {
        $.ajax({
            url: "<?php echo base_url(); ?>admin/getAccounts/"+id,
            type: "GET",
            success: function(data){
                data = JSON.parse(data);
                $('.accounts-list').html("");
                if(data.length != "") {
                    for(var i = 0; i < data.length; i++) {
                        addRow(i, data[i]);
                    }
                }
            }
        });
    }

    function checkCustomers(ids, type = "") {
        $.ajax({
            url: "<?php echo base_url(); ?>admin/getCustomers/"+ids,
            type: "GET",
            success: function(data){
                data = JSON.parse(data);
                if(data.length != "") {
                    notif.play();
                    if(type == "add") {
                        id2++;
                        getCustomers(id2);
                    } else if(type == "minus") {
                        id2--;
                        getCustomers(id2);
                    }
                } else {
                    errorRow();
                }
                
            }
        });
    }
    function getCustomers(ids) {
        $.ajax({
            url: "<?php echo base_url(); ?>admin/getCustomers/"+id2,
            type: "GET",
            success: function(data){
                data = JSON.parse(data);
                $('.customers-list').html("");
                if(data.length != "") {
                    for(var i = 0; i < data.length; i++) {
                        addRow2(i, data[i]);
                    }
                }
            }
        });
    }
    function addRow(i, data) {
        var x = '<div class="accounts-row row-'+data.id+'">';
            x = x + '<span>'+data.full_name+'</span>';
            x = x + '<span>Administrator</span>';
        x = x + '</div>';
        $('.accounts-list').append(x);
        
        var durations = i * 500;
        $('.row-'+data.id).hide().css({ opacity: 0, marginLeft: "200px"});
        $('.row-'+data.id).show(durations).animate({ opacity: 1, marginLeft: "0px"}, { duration: 'normal', easing: 'easeOutBack'});
    }
    function addRow2(i, data) {
        var x = '<div class="accounts-row row2-'+data.id+'">';
            x = x + '<span>'+data.full_name+'</span>';
        x = x + '</div>';
        $('.customers-list').append(x);
        
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

    getAccounts(id);

    $(document).ready(function() {
        $('#prev').click(function() {
            if(id > 1) {
                var s = id - 1;
                checkAccounts(s, 'minus');
            } else {
                errorRow();
            }
        });
        $('#next').click(function() {
            var s = id + 1;
            checkAccounts(s, 'add');
        });

        $('#prev2').click(function() {
            if(id > 1) {
                var s = id - 1;
                checkCustomers(s, 'minus');
            } else {
                errorRow();
            }
        });
        $('#next2').click(function() {
            var s = id + 1;
            checkCustomers(s, 'add');
        });
    });
</script>