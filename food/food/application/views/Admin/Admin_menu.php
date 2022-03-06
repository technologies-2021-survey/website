<div class="col-lg-6 col-md-6">
    <div class="box-container"> 
        <div class="box-body">
            <h3>
                Menu(s)
                <div class="pull-right">
                    <button class="btn btn-success">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        &nbsp;Add Menu
                    </button>
                </div>
            </h3>
            <div class="menu-list" style="clear:both;margin-bottom: 10px;">
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
<script type="text/javascript">
    var id = 1;
    function checkMenu(ids, type = "") {
        $.ajax({
            url: "<?php echo base_url(); ?>admin/getMenu/"+ids,
            type: "GET",
            success: function(data){
                data = JSON.parse(data);
                if(data.length != "") {
                    notif.play();
                    if(type == "add") {
                        id++;
                        getMenu(id);
                    } else if(type == "minus") {
                        id--;
                        getMenu(id);
                    }
                } else {
                    errorRow();
                }
                
            }
        });
    }
    
    function getMenu(ids) {
        $.ajax({
            url: "<?php echo base_url(); ?>admin/getMenu/"+id,
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
    function errorRow() {
        notif.play();
        Toast.fire({
            icon: 'error',
            title: 'Error! There\'s no data'
        });
    }

    getMenu(id);

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