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
                for(var i = 0; i < data.length; i++) {
                    addRow(data[i]);
                }
            }
        });
    }
    function addRow(data) {
        var x = '<div class="cleaners-row row-'+data.id+'">';
            x = x + data.cleaners_name;
            x = x + '<br>';
            x = x + data.cleaners_contact;
        x = x + '</div>';
        $('.cleaners-list').append(x);
        $(".row-"+data.id).hide().insertAfter($(this).parent()).slideDown('slow');

		//$('.row-'+data.id).show().animate({ opacity: 1, marginLeft: "0px"}, { duration: 'slow', easing: 'easeOutBack'}).delay(5000);
    }

    getCleaners(id);
</script>