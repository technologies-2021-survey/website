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
        var x = '<div class="cleaners-row row-'+data.parseInt(id)+'">';
            x = x + data.cleaners_name;
            x = x + '<br>';
            x = x + data.cleaners_contact;
        x = x + '</div>';
        $('.cleaners-list').append(x);
        
        var durations = data.id * 1000;
        $('.row-'+data.id).hide().css({ opacity: 0, marginLeft: "200px"});
        $('.row-'+data.id).show().animate({ opacity: 1, marginLeft: "0px"}, { duration: durations, easing: 'easeOutBack'});
        console.log(durations)
    }

    getCleaners(id);
</script>