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
            
            <div class="table-list" style="clear: both;margin-bottom: 10px;"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var id = 1;
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
    function addRow(i, data) {
        var x = '<div class="accounts-row row-'+data.id+'">';
            x = x + '<span>'+data.table_name+'</span>';
        x = x + '</div>';
        $('.table-list').append(x);
        
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

    getTables(id);

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
    });
</script>