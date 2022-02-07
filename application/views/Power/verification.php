<?php
$textss = "";
if($this->session->selection == "doctor") {
    $textss = "doctor";
} else if($this->session->selection == "administrator") {
    $textss = "administrator";
} else if($this->session->selection == "receptionist") {
    $textss = "receptionist";
} 
?>
<div class="container" style="padding-top: 15%;padding-bottom: 15%;">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <?php
            if($error != "") {
            ?>
			<div class="alert alert-danger">
			<?php
				echo $error;
			?>
			</div>
			<?php
            }
			?>
            <a href="<?php echo base_url(); ?>/home/resend" class="btn btn-info pull-left" style="font-size: 12px;">Resend code</a>
            <a href="<?php echo base_url(); ?>/<?php echo $textss; ?>/logout" class="btn btn-danger pull-right" style="font-size: 12px;">Log out</a>
            <div style="clear:both;"></div>
            <div class="panel panel-primary" style="margin-top: 10px;">
                <div class="panel-body" style="padding-top: 50px;padding-bottom: 50px;">
                    <h4 style="text-align:center;">Please enter your one time password to verify your account</h4>
                    <?php
					echo form_open($textss.'/verification');
					?>
                        <input type="text" name="otp" class="form-control" />
                        <input type="submit" name="submit" class="btn btn-primary" style="margin: 0 auto;display:block;margin-top: 25px;" value="Validate">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>html{height:100%!important;}body{height:inherit!important;}</style>