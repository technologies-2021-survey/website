<div class="col-lg-6 col-md-6">
    <div class="box-container">
        <div class="box-body">
            <h3>Account's List</h3>
            <div class="accounts-list" style="margin-bottom: 10px;">
            </div>
            <div class="pull-left">
                <button class="btn btn-primary" id="prev">Prev</button>
            </div>
            <div class="pull-right">
                <button class="btn btn-primary" id="next">Next</button>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var id = 1;
    function checkAccounts(ids, type = "") {
        $.ajax({
            url: "<?php echo base_url(); ?>admin/getAccounts/"+ids,
            type: "GET",
            success: function(data){
                data = JSON.parse(data);
                if(data.length != "") {
                    console.log("wow")
                    return 0;
                } else {
                    return 1;
                }
                
            }
        });
    }
    function getAccounts(ids, type = "") {
        $.ajax({
            url: "<?php echo base_url(); ?>admin/getAccounts/"+ids,
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
            if(checkAccounts(id-1) == 0) {
                id--;
                getAccounts(id);
            } else {
                errorRow();
            }
        });
        $('#next').click(function() {
            if(checkAccounts(id+1) == 0) {
                id++;
                getAccounts(id);
            } else {
                errorRow();
            }
        });
    });
</script>