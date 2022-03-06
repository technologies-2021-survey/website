<div class="col-lg-6 col-md-6">
    <div class="box-container">
        <div class="box-body">
            <h3>
                Account's List
                <div class="pull-right">
                    <button class="btn btn-success" style="margin-right: 10px;" onclick="refresh1()">
                        <i class="fa fa-refresh" aria-hidden="true"></i>
                        &nbsp;Refresh
                    </button>
                    <button class="btn btn-success">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        &nbsp;Add Account
                    </button>
                </div>
            </h3>
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
            <h3>
                Customer's List
                <div class="pull-right">
                    <button class="btn btn-success" style="margin-right: 10px;" onclick="refresh1()">
                        <i class="fa fa-refresh" aria-hidden="true"></i>
                        &nbsp;Refresh
                    </button>
                </div>
            </h3>
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

<div class="modal fade" id="addAccount" tabindex="-1" role="dialog" aria-labelledby="addAccountLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addAccountLabel" style="display: inline-block;">Add Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="addAccounts">
            <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" name="username" required=""/>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" required=""/>
            </div>
            <div class="form-group">
                <label>Account Level</label>
                <select name="account_level" class="form-control">
                    <option value="0">Administrator</option>
                    <option value="1">Cashier</option>
                    <option value="2">Kitchen 1</option>
                    <option value="3">Kitchen 2</option>
                    <option value="4">Dispatcher</option>
                </select>
            </div>
            <button class="btn btn-success" id="add" name="submit">
                <i class="fa fa-plus" aria-hidden="true"></i>
                &nbsp;Add Account
            </button>
        </form>
      </div>
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
            if(data.account_level == 0) { x = x + '<span>Administrator</span>'; }
            else if(data.account_level == 1) { x = x + '<span>Cashier</span>'; }
            else if(data.account_level == 2) { x = x + '<span>Kitchen 1</span>'; }
            else if(data.account_level == 3) { x = x + '<span>Kitchen 2</span>'; }
            else if(data.account_level == 4) { x = x + '<span>Dispatcher</span>'; }
        x = x + '</div>';
        $('.accounts-list').append(x);
        
        var durations = i * 500;
        $('.row-'+data.id).hide().css({ opacity: 0, marginLeft: "200px"});
        $('.row-'+data.id).show(durations).animate({ opacity: 1, marginLeft: "0px"}, { duration: 'normal', easing: 'easeOutBack'});
    }
    function addRow2(i, data) {
        var x = '<div class="accounts-row row2-'+data.id+'">';
            x = x + '<span>'+data.full_name+'</span>';
            x = x + '<span>Customer</span>';
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

    function refresh1() {
        notif.play();
        getTables(id)
    }
    function refresh2() {
        notif.play();
        getDineIn(id2)
    }

    function addAccount() {
        notif.play();
        $('#addAccount').modal('show');
    }
    
    getAccounts(id);
    getCustomers(id2);

    

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