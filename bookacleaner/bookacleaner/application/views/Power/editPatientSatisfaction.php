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
            Edit Patient Satisfaction
        </div>
        <div class="panel-body">
            <form action="<?php echo base_url().$textss; ?>/editPatientSatisfaction/<?php echo $this->uri->segment(3); ?>" method="POST">
                <div class="form-group">
                    <label>Question <span style="color:red;">*</span></label>
                    <input type="text" name="surveyresults_tbl_ans" class="form-control" value="<?php echo $ans; ?>">
                </div>
                <div class="pull-right">
                    <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                </div>
                <div style="clear:both;"></div>
            </form>
        </div>
    </div>
