<?php $this->load->view('Power/navigation'); ?>
<?php 
if($error != "") {
    echo '<div class="alert alert-danger">'.$error.'</div>';
}
if($success != "") {
    echo '<div class="alert alert-success">'.$success.'</div>';
}
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
            Change Profile Picture
        </div>
        <div class="panel-body">
            <form method="POST" action="<?php echo base_url();?>index.php/<?php echo $textss; ?>/change_profile_picture" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="file" class="form-control" name="image" required="">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Change Profile Picture" name="submit" >
                </div>
            </form>
        </div>
    </div>