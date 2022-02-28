<?php $this->load->view('Power/navigation'); ?>

    <div class="panel panel-primary" style="border-radius: 0px; border: 0px;">
        <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
            <?php echo $parent_name; ?> | Add Child
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
            <style>td{border: 1px solid #9f9f9f!important;}</style>
            <form action="<?php echo base_url() . $textss; ?>/addChild/<?php echo $id; ?>" method="POST" enctype="multipart/form-data">

                <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="patient_name" class="form-control">
                </div>

                <div class="form-group">
                <label for="patient_birthdate">Date of Birth</label>
                <input type="date" id="patient_birthdate" name="patient_birthdate" class="form-control">
                </div>

                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="patient_gender" name="patient_gender" class="form-control">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>

                <br/>
                <div class="pull-right">
                    <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                </div>
                <div style="clear:both;"></div>
            </form>
        </div>
    </div>