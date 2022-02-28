    <?php $this->load->view('Power/navigation'); ?>

    <div class="panel panel-primary" style="border-radius: 0px; border: 0px;">
        <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
                Add Parent
        </div>
        <div class="panel-body">
            <?php 
            $textss = "";
            if($this->session->selection == "doctor") {
                $textss = "doctor";
            } else if($this->session->selection == "receptionist") {
                $textss = "receptionist";
            } else if($this->session->selection == "administrator") {
                $textss = "administrator";
            } 
            ?>
            <form action="<?php echo base_url() .'index.php/' . $textss; ?>/addParents" method="POST">
                <div class="form-group">
                    <label>Parent Name</label>
                    <input type="text" name="parent_name" class="form-control" required="">
                </div>
                <div class="form-group">
                    <label>Parent Email</label>
                    <input type="email" name="parent_emailaddress" class="form-control" required="">
                </div>
                <div class="form-group">
                    <label>Parent Phone Number</label>
                    <input type="text" name="parent_phonenumber" class="form-control" required="">
                </div>
                <div class="form-group">
                    <label>Parent Address</label>
                    <input type="text" name="parent_address" class="form-control" required="">
                </div>
                <div class="pull-right">
                    <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                </div>
                <div style="clear:both;"></div>
            </form>
        </div>
    </div>