<div class="col-lg-6 col-md-6">
    <div class="box-container">
        <div class="box-body">
            <h3>Cleaner's List</h3>
            <div class="cleaners-list" style="margin-bottom: 10px;">
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
                        x = x +'<button class="btn btn-danger fire" data-id="'+data.id+'">Fire Cleaner</button>';
                    x = x + '</div>';
                }
            } else {
                // former working
                x = x + '<div class="pull-right">';
                    x = x +'<button class="btn btn-success hire" data-id="'+data.id+'">Hire Cleaner</button>';
                x = x + '</div>';
            }
        x = x + '</div>';
        $('.cleaners-list').append(x);
        
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
    getCleaners(id);

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
        
    });
    $(window).on('ready', function () {
        $('.hire').click(function() {
            alert(0);
            var ids = $(this).attr("data-id");
            $.ajax({
            url: "<?php echo base_url(); ?>admin/hireCleaner/"+id,
                type: "GET",
                success: function(data){
                    notif.play();
                    Toast.fire({
                        icon: 'success',
                        title: 'Successfully!'
                    });
                    getCleaners(id)
                }
            });
        });
        $('.fire').click(function() {
            alert(1);
            var ids = $(this).attr("data-id");
            $.ajax({
            url: "<?php echo base_url(); ?>admin/fireCleaner/"+id,
                type: "GET",
                success: function(data){
                    notif.play();
                    Toast.fire({
                        icon: 'success',
                        title: 'Successfully!'
                    });
                    getCleaners(id)
                }
            });
        }); 
    });
</script>