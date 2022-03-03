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
    function getCleaners(ids, type = "") {
        if(type == "minus") {
            ids -= 1;
        } else if(type == "add") {
            ids += 1;
        }
        $.ajax({
            url: "<?php echo base_url(); ?>admin/getCleaners/"+ids,
            type: "GET",
            success: function(data){
                data = JSON.parse(data);
                $('.cleaners-list').html("");
                if(data.length != "") {
                    notif.play();
                    for(var i = 0; i < data.length; i++) {
                        addRow(i, data[i]);
                    }
                } else {
                    notif.play();
                    errorRow();
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
        x = x + '</div>';
        $('.cleaners-list').append(x);
        
        var durations = i * 500;
        $('.row-'+data.id).hide().css({ opacity: 0, marginLeft: "200px"});
        $('.row-'+data.id).show(durations).animate({ opacity: 1, marginLeft: "0px"}, { duration: 'normal', easing: 'easeOutBack'});
    }

    function errorRow() {
        var x = '<div class="cleaners-row row-0">';
            x = x + '<span style="text-align:center;">No results found.</span>';
        x = x + '</div>';
        $('.cleaners-list').append(x);
        
        var durations = 1 * 700;
        $('.row-0').hide().css({ opacity: 0, marginLeft: "200px"});
        $('.row-0').show(durations).animate({ opacity: 1, marginLeft: "0px"}, { duration: 'normal', easing: 'easeOutBack'});

        Toast.fire({
            icon: 'error',
            title: 'Error! There\'s no data'
        });
    }

    getCleaners(id);

    $(document).ready(function() {
        $('#prev').click(function() {
            if(id <= 1) {
                Toast.fire({
                    icon: 'error',
                    title: 'Error! There\'s no data'
                });
            } else {
                getCleaners(id, 'minus');
            }
        });
        $('#next').click(function() {
            getCleaners(id, 'add');
        });
    });
</script>