<?php $results = (array) $announcements; ?>
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
            <?php echo $title; ?> | Edit Announcements
        </div>
        <div class="panel-body">
            <form action="<?php echo base_url().$textss; ?>/editAnnouncements/<?php echo $this->uri->segment(3); ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Title <span style="color:red;">*</span></label>
                    <input type="text" name="announcement_tbl_title" class="form-control" value="<?php echo $title; ?>">
                </div>
                <div class="form-group">
                    <label>Content <span style="color:red;">*</span></label>
                    <textarea name="announcement_tbl_content" class="form-control" style="height: 200px;"><?php echo htmlentities($content); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Image</label>
                    <input type="file" name="announcement_tbl_image" class="form-control">
                    <p style="font-size:11px;font-style:italic;">Note: If you have already uploaded an image, then you do not want to change the current image, do not put an image in the input field.</p>
                    <label style="font-size: 10px;font-style:italic;">Max width: 2000px / Max height: 2000px</label>
                </div>
                <div class="pull-right">
                    <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                </div>
                <div style="clear:both;"></div>
            </form>
        </div>
    </div>