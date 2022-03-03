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
            url: "<?php echo base_url(); ?>admin/getCleaners/"+id,
            type: "GET",
            success: function(data){
                data = JSON.parse(data);
                $('.cleaners-list').html("");
                if(data.length != "") {
                    for(var i = 0; i < data.length * 1000; i++) {
                        addRow(data[i]);
                    }
                    return 1;
                } else {
                    var x = '<div class="cleaners-row row-0">';
                        x = x + '<span style="text-align:center;">No results found.</span>';
                    x = x + '</div>';
                    $('.cleaners-list').append(x);
                    
                    var durations = 1 * 700;
                    $('.row-0').hide().css({ opacity: 0, marginLeft: "200px"});
                    $('.row-0').show(durations).animate({ opacity: 1, marginLeft: "0px"}, { duration: 'normal', easing: 'easeOutBack'});

                    notif.play();
                    Toast.fire({
                        icon: 'error',
                        title: 'Error! There\'s no data'
                    });
                    return 0;
                }
                
            }
        });
    }
    function addRow(data) {
        var x = '<div class="cleaners-row row-'+data.id+'">';
            x = x + '<span>'+data.cleaners_name+'</span>';
            x = x + '<span>'+data.cleaners_contact+'</span>';
        x = x + '</div>';
        $('.cleaners-list').append(x);
        
        var durations = id * 700;
        $('.row-'+data.id).hide().css({ opacity: 0, marginLeft: "200px"});
        $('.row-'+data.id).show(durations).animate({ opacity: 1, marginLeft: "0px"}, { duration: 'normal', easing: 'easeOutBack'});
    }

    getCleaners(id);

    $(document).ready(function() {
        $('#prev').click(function() {
            
            if(getCleaners(id-1) == 1) {
                if(id > 1) {
                    id--;
                }
            } else {
                id++;
            }
        });
        $('#next').click(function() {
            if(getCleaners(id+1) == 1) {
                id++;
            } else {
                id--;
            }
        });
    });
</script>