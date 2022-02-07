<?php $results = (array) $childs; ?>
<?php $this->load->view('Power/navigation'); ?>
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
    <div class="panel panel-primary" style="border-radius: 0px; border: 0px;">
        <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
            <?php echo $parent_name; ?> | Edit Child
        </div>
        <div class="panel-body">
            <form action="<?php echo base_url().$textss; ?>/editChild/<?php echo $this->uri->segment(3); ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Patient Name</label>
                    <input type="text" name="patient_name" class="form-control" value="<?php echo $results[0]["patient_name"]; ?>">
                </div>
                <div class="form-group">
                    <label>Patient Gender</label>
                    <select name="patient_gender" class="form-control">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Patient Birthdate</label>
                    <input type="date" name="patient_birthdate" class="form-control" value="<?php echo $results[0]["patient_birthdate"]; ?>">
                </div>
                <div class="pull-right">
                    <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                </div>
                <div style="clear:both;"></div>
            </form>
        </div>
    </div>