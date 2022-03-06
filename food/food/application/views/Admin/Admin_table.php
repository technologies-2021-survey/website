<div class="col-lg-6 col-md-6">
    <div class="box-container"> 
        <div class="box-body">
            <h3>
                Table(s)
                <div class="pull-right">
                    <button class="btn btn-success">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        &nbsp;Add Table
                    </button>
                </div>
            </h3>
            
            <div class="table-list" style="margin-bottom: 10px;">
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
                Dine In(s)
            </h3>
            
            <div class="dine-in-list" style="margin-bottom: 10px;">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
    var id = 1;
    var id2 = 1;
    function checkTables(ids, type = "") {
        $.ajax({
            url: "<?php echo base_url(); ?>admin/getTables/"+ids,
            type: "GET",
            success: function(data){
                data = JSON.parse(data);
                if(data.length != "") {
                    notif.play();
                    if(type == "add") {
                        id++;
                        getTables(id);
                    } else if(type == "minus") {
                        id--;
                        getTables(id);
                    }
                } else {
                    errorRow();
                }
                
            }
        });
    }
    function getTables(ids) {
        $.ajax({
            url: "<?php echo base_url(); ?>admin/getTables/"+id,
            type: "GET",
            success: function(data){
                data = JSON.parse(data);
                $('.table-list').html("");
                if(data.length != "") {
                    for(var i = 0; i < data.length; i++) {
                        addRow(i, data[i]);
                    }
                }
            }
        });
    }

    function checkDineIn(ids, type = "") {
        $.ajax({
            url: "<?php echo base_url(); ?>admin/getDineIn/"+ids,
            type: "GET",
            success: function(data){
                data = JSON.parse(data);
                if(data.length != "") {
                    notif.play();
                    if(type == "add") {
                        id2++;
                        getDineIn(id2);
                    } else if(type == "minus") {
                        id2--;
                        getDineIn(id2);
                    }
                } else {
                    errorRow();
                }
                
            }
        });
    }
    function getDineIn(ids) {
        $.ajax({
            url: "<?php echo base_url(); ?>admin/getDineIn/"+id2,
            type: "GET",
            success: function(data){
                data = JSON.parse(data);
                $('.dine-in-list').html("");
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
            x = x + '<span>'+data.table_name+'</span>';
            if(data.status == "Available") {
                x = x + '<label class="label label-success">'+data.status+'</label>';
            } else {
                x = x + '<label class="label label-danger">'+data.status+'</label>';
            }
        x = x + '</div>';
        $('.table-list').append(x);
        
        var durations = i * 500;
        $('.row-'+data.id).hide().css({ opacity: 0, marginLeft: "200px"});
        $('.row-'+data.id).show(durations).animate({ opacity: 1, marginLeft: "0px"}, { duration: 'normal', easing: 'easeOutBack'});
    }

    function addRow2(i, data) {
        var x = '<div class="accounts-row row2-'+data.id+'">';
            x = x + '<span>'+data.table_id+'</span>';
            if(data.status == "Waiting") {
                x = x + '<label class="label label-danger">'+data.status+'</label>';
            } else if(data.status == "Eating") {
                x = x + '<label class="label label-danger">'+data.status+'</label>';
            } else if(data.status == "Done") {
                x = x + '<label class="label label-success">'+data.status+'</label>';
            }
            x = x + '<label class="label label-primary"><i class="fa fa-clock" style="margin-right: 5px;"></i>'+moment.unix(data.time).utc().fromNow()+'</label>';
        x = x + '</div>';
        $('.dine-in-list').append(x);
        
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

    getTables(id);
    getDineIn(id2);

    $(document).ready(function() {
        $('#prev').click(function() {
            if(id > 1) {
                var s = id - 1;
                checkTables(s, 'minus');
            } else {
                errorRow();
            }
        });
        $('#next').click(function() {
            var s = id + 1;
            checkTables(s, 'add');
        });

        $('#prev2').click(function() {
            if(id2 > 1) {
                var s = id2 - 1;
                checkDineIn(s2, 'minus');
            } else {
                errorRow();
            }
        });
        $('#next2').click(function() {
            var s = id2 + 1;
            checkDineIn(s, 'add');
        });
    });
</script>