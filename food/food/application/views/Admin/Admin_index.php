<div class="container">
    <div class="row">
        
        <div class="col-md-4 col-md-offset-4">
            <h4 style="text-align: left; margin-top: 25%;">Admin Dashboard</h4>
            <hr class="colorgraph" style="margin-top:0;margin-bottom:0;padding:0;"/>
            <div class="login-box">
                <form id="login">
                    <div class="rows">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <input type="text" name="username" id="username" class="form-control input-lg" tabindex="1" autocomplete="off" required="">
                                <label class="floatingText">Username</label>
                            </div>
                        </div> 
                    </div>
                    <div class="rows">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <input type="password" name="password" id="password" class="form-control input-lg" tabindex="2" autocomplete="off" required="">
                                <label class="floatingText">Password</label>
                            </div>
                            <button class="btn btn-success btn-block" name="submit">
                                <i class="fa fa-key" aria-hidden="true" style="margin-right:5px;"></i>
                                Login
                            </button>
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                </form>
            </div>
            <hr class="colorgraph" style="margin:0;padding:0;"/>
            <p style="text-align: center; padding: 15px; font-size: 12px; font-weight: 300;">
                <i class="fa fa-copyright" aria-hidden="true" style="margin-right: 5px;"></i>
                TechLance / Page rendered in {elapsed_time} seconds
            </p>
        </div>
    </div>
</div>
<script type="text/javascript">
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
    function redirect() {
        window.location.replace("<?php echo base_url();?>admin/main");
    }  
    $(document).ready(function() {
        $('.floatingText').click(function() {
            if($(this).prev()[0].tagName == "INPUT" || $(this).prev()[0].tagName == "TEXTAREA") {
                $(this).siblings().select();
            } else {
                // can't open <select> :(
            }
            
        });
        var notif = new Audio('https://peekabook.tech/food/food/assets/mp3/mixkit-dry-pop-up-notification-alert-2356.wav');
        $('#login').submit(function(e) {
            e.preventDefault();
            var username = $('input[name=username]').val();
            var password = $('input[name=password]').val();
            $.ajax({
				url: "<?php echo base_url(); ?>admin/login",
				type: "POST",
				data: {
					username: username,
					password: password						
				},
				success: function(data){
                    var data = JSON.parse(data);
                    if(data.status == 200) {
                        notif.play();
                        Toast.fire({
                            icon: 'success',
                            title: "Successfully, you will be redirected to user page in 3 sec. You are not able to get back to this page by clicking the browser back button."
                        })
                        setTimeout("redirect()", 3000);
                    } else {
                        notif.play();
                        Toast.fire({
                            icon: 'error',
                            title: data.message
                        })
                    }
				}
			});
        });
    });
</script>