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
    var id = 0
    $(document).ready(function() {
        function getCleaners(id) {
            $.ajax({
				url: "<?php echo base_url(); ?>admin/getCleaners",
				type: "POST",
				data: {
					id: id,					
				},
				success: function(data){
                    console.log(data)
                }
            });
        }


        getCleaners(0);
    });
</script>