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
    function getCleaners(id) {
        $.ajax({
            url: "<?php echo base_url(); ?>admin/getCleaners",
            type: "GET",
            data: {
                id: id,					
            },
            success: function(data){
                data = JSON.parse(data);
                if(data.length != "") {
                    $('.cleaners-list').html("");
                    for(var i = 0; i < data.length * 1000; i++) {
                        addRow(data[i]);
                    }
                    return true;
                } else {
                    notif.play();
                    Toast.fire({
                        icon: 'error',
                        title: 'Error! There\'s no data'
                    });
                    return false;
                }
            }
        });
    }
    function addRow(data) {
        var id = data.id;

        var x = '<div class="cleaners-row row-'+id+'">';
            x = x + '<span>'+data.cleaners_name+'</span>';
            x = x + '<span>'+data.cleaners_contact+'</span>';
        x = x + '</div>';
        $('.cleaners-list').append(x);
        
        var durations = id * 700;
        $('.row-'+id).hide().css({ opacity: 0, marginLeft: "200px"});
        $('.row-'+id).show(durations).animate({ opacity: 1, marginLeft: "0px"}, { duration: 'normal', easing: 'easeOutBack'});
        console.log(durations)
    }

    getCleaners(id);

    $(document).ready(function() {
        $('#prev').click(function() {
            getCleaners(id-1);
            id--;
        });
        $('#next').click(function() {
            getCleaners(id+1);
            id++;
        });
    });
</script>