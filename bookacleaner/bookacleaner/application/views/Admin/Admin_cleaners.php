<div class="col-lg-6 col-md-6">
    <div class="box-container">
        <div class="box-body">
            <h3>Cleaner's List</h3>
            <div class="cleaners-list">
            </div>
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
                for(var i = 0; i < data.length * 1000; i++) {
                    addRow(data[i]);
                }
            }
        });
    }
    function addRow(data) {
        var id = data.id;

        var x = '<div class="cleaners-row row-'+id+'">';
            x = x + data.cleaners_name;
            x = x + '<br>';
            x = x + data.cleaners_contact;
        x = x + '</div>';
        $('.cleaners-list').append(x);
        
        var durations = id * 1000;
        $('.row-'+id).hide().css({ opacity: 0, marginLeft: "200px"});
        $('.row-'+id).show(durations).animate({ opacity: 1, marginLeft: "0px"}, { duration: 'slow', easing: 'easeOutBack'});
        console.log(durations)
    }

    getCleaners(id);
</script>