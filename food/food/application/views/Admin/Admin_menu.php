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
                $('.menu-list').html("");
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

    function deleteMenu(id) {
        if (window.confirm("Are you sure?")) {
            $.ajax({
            url: "<?php echo base_url(); ?>admin/deleteMenu/"+id,
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
                        getMenu(id);
                    }
                }
            });
        }
    }

    function addRow(i, data) {
        var x = '<div class="accounts-row row-'+data.id+'">';
            x = x + '<span>'+data.food_name+'</span>';
            x = x + '<span>P'+data.food_price+'</span>';
            x = x + '<div class="pull-right" style="margin-top: -36px;">';
                x = x + '<button class="btn btn-danger" style="margin-right: 10px;" onclick="deleteMenu('+data.id+')">Delete</button>';
            x = x + '</div>';
            x = x + '<div style="clear: both;"></div>';
        x = x + '</div>';
        $('.menu-list').append(x);
        
        var durations = i * 500;
        $('.row-'+data.id).hide().css({ opacity: 0, marginLeft: "200px"});
        $('.row-'+data.id).show(durations).animate({ opacity: 1, marginLeft: "0px"}, { duration: 'normal', easing: 'easeOutBack'});
    }

    getMenu(id);

    $(document).ready(function() {
        $('#prev').click(function() {
            if(id > 1) {
                var s = id - 1;
                getMenu(s, 'minus');
            } else {
                errorRow();
            }
        });
        $('#next').click(function() {
            var s = id + 1;
            getMenu(s, 'add');
        });
    });
</script>