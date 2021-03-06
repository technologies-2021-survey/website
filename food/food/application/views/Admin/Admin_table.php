<div class="col-lg-6 col-md-6">
    <div class="box-container"> 
        <div class="box-body">
            <h3>
                Table(s)
                <div class="pull-right">
                    <button class="btn btn-success" style="margin-right: 10px;" onclick="refresh1()">
                        <i class="fa fa-refresh" aria-hidden="true"></i>
                        &nbsp;Refresh
                    </button>
                    <button class="btn btn-success">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        &nbsp;Add Table
                    </button>
                </div>
            </h3>
            
            <div class="table-list" style="clear:both;margin-bottom: 10px;">
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
    <div class="box-container"> 
        <div class="box-body">
            <h3>
                List Order(s)
                <div class="pull-right">
                    <button class="btn btn-success" style="margin-right: 10px;" onclick="refresh3()">
                        <i class="fa fa-refresh" aria-hidden="true"></i>
                        &nbsp;Refresh
                    </button>
                </div>
            </h3>
            
            <div class="list-orders-list" style="clear:both;margin-bottom: 10px;">
            </div>
            <div class="pull-left">
                <button class="btn btn-primary" id="prev3"><i class="fa fa-caret-left" aria-hidden="true"></i>&nbsp;Prev</button>
            </div>
            <div class="pull-right">
                <button class="btn btn-primary" id="next3">Next&nbsp;<i class="fa fa-caret-right" aria-hidden="true"></i></button>
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
                <div class="pull-right">
                    <button class="btn btn-success" onclick="refresh2()" style="margin-right: 5px;">
                        <i class="fa fa-refresh" aria-hidden="true"></i>
                        &nbsp;Refresh
                    </button>
                    <button class="btn btn-success" onclick="addSampleDineIn()">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        &nbsp;Add Sample Dine In
                    </button>
                </div>
            </h3>
            
            <div class="dine-in-list" style="clear:both;margin-bottom: 10px;">
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


<div class="modal fade" id="viewOrder" tabindex="-1" role="dialog" aria-labelledby="viewOrderLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewOrderLabel" style="display: inline-block;">View Order</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody class="view-order-body">

            </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
    var id = 1;
    var id2 = 1;
    var id3 = 0;
    function getTableDineInOrder(ids, type = "") {
        $.ajax({
            url: "<?php echo base_url(); ?>admin/getTableDineInOrder/"+ids,
            type: "GET",
            success: function(data){
                data = JSON.parse(data);
                if(data.length != "") {
                    $('.view-order-body').html("");
                    $('#viewOrderLabel').text("View Order #"+ids);

                    $('#viewOrder').modal('show');
                    
                    for(var i = 0; i < data[0].length; i++) {
                        var x = '<tr>';
                            x = x + '<td>'+(i+1)+'</td>';
                            x = x + '<td>'+data[0][i].food_name+'</td>';
                            x = x + '<td>P'+data[0][i].food_price+'</td>';
                            x = x + '<td>'+data[0][i].quantity+'</td>';
                            x = x + '<td>P'+data[0][i].row_total+'</td>';
                        x = x + '</tr>';
                        $('.view-order-body').append(x);
                    }
                    var y = '<tr>';
                        y = y + '<td colspan="5" style="text-align: right;">';
                            y = y + '<b>Overall Total:</b> P'+data[1].overall_total;
                        y = y + '</td>';
                    y = y + '</tr>';
                    $('.view-order-body').append(y);
                } else {
                    errorRow();
                }
                
            }
        });
    }

    function checkListOrders(ids, type = "") {
        $.ajax({
            url: "<?php echo base_url(); ?>admin/getListOrders/"+ids,
            type: "GET",
            success: function(data){
                data = JSON.parse(data);
                if(data.length != "") {
                    notif.play();
                    if(type == "add") {
                        id3++;
                        getListOrders(id3);
                    } else if(type == "minus") {
                        id3--;
                        getListOrders(id3);
                    }
                } else {
                    errorRow();
                }
                
            }
        });
    }
    
    function getListOrders(ids) {
        $.ajax({
            url: "<?php echo base_url(); ?>admin/getListOrders/"+id3,
            type: "GET",
            success: function(data){
                data = JSON.parse(data);
                $('.list-orders-list').html("");
                if(data.length != "") {
                    for(var i = 0; i < data.length; i++) {
                        addRow3(i, data[i]);
                    }
                } else {
                    errorRows('list-orders-list');
                }
            }
        });
    }

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
                } else {
                    errorRows('table-list');
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
                } else {
                    errorRows('dine-in-list');
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
                x = x + '<label class="label label-primary">'+data.status+'</label>';
            }
            if(data.status == "Eating") {
                x = x + '<div class="pull-right" style="margin-top: -20px;">';
                    x = x + '<button class="btn btn-success" style="margin-right: 10px;"  onclick="doneEating('+data.id+')">Done eating</button>';
                x = x + '</div>';
            }
        x = x + '</div>';
        $('.table-list').append(x);
        
        var durations = i * 500;
        $('.row-'+data.id).hide().css({ opacity: 0, marginLeft: "200px"});
        $('.row-'+data.id).show(durations).animate({ opacity: 1, marginLeft: "0px"}, { duration: 'normal', easing: 'easeOutBack'});
    }

    function addRow2(i, data) {
        var x = '<div class="accounts-row row2-'+data.id+'">';
            x = x + '<span>Queue #'+data.queue+' / Table '+data.table_id+'</span>';
            if(data.status == "Waiting") {
                x = x + '<label class="label label-danger">'+data.status+'</label>';
            } else if(data.status == "Eating") {
                x = x + '<label class="label label-danger">'+data.status+'</label>';
            }
            x = x + '<label class="label label-primary" style="margin-left: 5px;"><i class="fa fa-clock-o" style="margin-right: 5px;"></i>'+moment.unix(data.time).utc().fromNow()+'</label>';
            x = x + '<div class="pull-right" style="margin-top: -20px;">';
                x = x + '<button class="btn btn-primary" style="margin-right: 10px;" onclick="getTableDineInOrder('+data.id+')">View Order</button>';
                if(data.status == "Waiting") {
                    x = x + '<button class="btn btn-success" onclick="doneSitting('+data.id+','+data.table_id+')">Done serve</button>';
                }
            x = x + '</div>';
            x = x + '<div style="clear: both;"></div>';
        x = x + '</div>';
        $('.dine-in-list').append(x);
        
        var durations = i * 500;
        $('.row2-'+data.id).hide().css({ opacity: 0, marginLeft: "200px"});
        $('.row2-'+data.id).show(durations).animate({ opacity: 1, marginLeft: "0px"}, { duration: 'normal', easing: 'easeOutBack'});
    }
    function addRow3(i, data) {
        var x = '<div class="accounts-row row3-'+data.id+'">';
            x = x + '<span>Table '+data.table_id+'</span>';
            if(data.status == "Waiting") {
                x = x + '<label class="label label-danger">'+data.status+'</label>';
            } else if(data.status == "Eating") {
                x = x + '<label class="label label-primary">'+data.status+'</label>';
            } else if(data.status == "Done") {
                x = x + '<label class="label label-success">'+data.status+'</label>';
            }
            x = x + '<label class="label label-primary" style="margin-left: 5px;"><i class="fa fa-clock-o" style="margin-right: 5px;"></i>'+moment.unix(data.time).utc().fromNow()+'</label>';
            x = x + '<div class="pull-right" style="margin-top: -20px;">';
                x = x + '<button class="btn btn-primary" style="margin-right: 10px;" onclick="getTableDineInOrder('+data.id+')">View Order</button>';
                if(data.status == "Waiting") {
                    x = x + '<button class="btn btn-success" onclick="doneSitting('+data.id+', '+data.table_id+')">Sitting</button>';
                }
            x = x + '</div>';
            x = x + '<div style="clear: both;"></div>';
        x = x + '</div>';
        $('.list-orders-list').append(x);
        
        var durations = i * 500;
        $('.row3-'+data.id).hide().css({ opacity: 0, marginLeft: "200px"});
        $('.row3-'+data.id).show(durations).animate({ opacity: 1, marginLeft: "0px"}, { duration: 'normal', easing: 'easeOutBack'});
    }

    function errorRow() {
        notif.play();
        Toast.fire({
            icon: 'error',
            title: 'Error! There\'s no data'
        });
    }

    function errorRows(insert) {
        var x = '<div class="accounts-row hehe-1">';
            x = x + '<center>No results found</center>';
        x = x + '</div>';
        $('.'+insert).append(x);
        $('.hehe-1').hide().css({ opacity: 0, marginLeft: "200px"});
        $('.hehe-1').show('slow').animate({ opacity: 1, marginLeft: "0px"}, { duration: 'normal', easing: 'easeOutBack'});
    }

    getTables(id);
    getDineIn(id2);
    getListOrders(id3);

    function doneEating(id) {
        if (window.confirm("Are you sure?")) {
            $.ajax({
            url: "<?php echo base_url(); ?>admin/doneEating/"+id,
                type: "GET",
                success: function(data){
                    data = JSON.parse(data);
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
                        getTables(id);
                        getDineIn(id2);
                    }
                }
            });
        }
    }

    function doneSitting(id, table_id) {
        if (window.confirm("Are you sure?")) {
            $.ajax({
            url: "<?php echo base_url(); ?>admin/doneSitting/"+id+"/"+table_id,
                type: "GET",
                success: function(data){
                    data = JSON.parse(data);
                    if(data.status == 200) {
                        notif.play();
                        Toast.fire({
                            icon: 'success',
                            title: 'Successfully!'
                        });
                        getTables(id);
                        getDineIn(id2);
                        getListOrders(id3);
                        
                    } else {
                        notif.play();
                        Toast.fire({
                            icon: 'error',
                            title: data.message
                        });
                    }
                }
            });
        }
    }

    function addSampleDineIn() {
        $.ajax({
            url: "<?php echo base_url(); ?>admin/addSampleDineIn",
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
                    getTables(id);
                    getDineIn(id2);
                    getListOrders(id3);
                }
            }
        });
    }

    function refresh1() {
        notif.play();
        getTables(id)
    }
    function refresh2() {
        notif.play();
        getDineIn(id2)
    }
    function refresh3() {
        notif.play();
        getListOrders(id3)
    }

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
                checkDineIn(s, 'minus');
            } else {
                errorRow();
            }
        });
        $('#next2').click(function() {
            var s = id2 + 1;
            checkDineIn(s, 'add');
        });

        $('#prev3').click(function() {
            if(id3 > 1) {
                var s = id3 - 1;
                checkListOrders(s3, 'minus');
            } else {
                errorRow();
            }
        });
        $('#next3').click(function() {
            var s = id3 + 1;
            checkListOrders(s, 'add');
        });
    });
</script>