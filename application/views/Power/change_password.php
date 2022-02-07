<?php $this->load->view('Power/navigation'); ?>
    <div class="panel panel-primary" style="border-radius: 0px; border: 0px;">
        <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
            Change Password
        </div>
        <div class="panel-body">
            <form id="changepasswords">
                <div class="form-group">
                    <label>
                        Old Password
                    </label>
                    <input type="password" class="form-control" id="oldpassword" required="">
                </div>
                <div class="form-group">
                    <label>
                        New Password
                    </label>
                    <input type="password" class="form-control" id="newpassword" required="">
                </div>
                <div class="form-group">
                    <label>
                        Re-type New Password
                    </label>
                    <input type="password" class="form-control" id="retypenewpassword" required="">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Change Password" name="submit" >
                </div>
            </form>
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
            <script type="text/javascript">
            $(document).ready(function() {
                
                $('#changepasswords').submit(function(e) {
                    e.preventDefault();
                    var oldpassword = $('#oldpassword').val();
                    var newpassword = $('#newpassword').val();
                    var retypenewpassword = $('#retypenewpassword').val();

                    $.ajax({
                        type: 'post',
                        url: "<?php echo base_url().$textss; ?>/change_password2",
                        data:{oldpassword:oldpassword, newpassword:newpassword, retypenewpassword:retypenewpassword},
                        success:function(response) {
                            if(response != "success") {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops....',
                                    html: response
                                })
                            } else {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Successfully',
                                    text: 'Your password has been changed successfully.'
                                })
                            }
                        }
                    });
                });
            });
            </script>
        </div>
    </div>