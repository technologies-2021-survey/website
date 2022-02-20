<div class="container">
    <div class="row">
        <h4>Admin Dashboard</h4>
        <div class="col-md-4 col-md-offset-4">
            <hr class="colorgraph" style="margin-top:25%;margin-bottom:0;padding:0;"/>
            <div class="login-box">
                <form>
                    <div class="rows">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <input type="text" name="text" id="username" class="form-control input-lg" tabindex="1">
                                <label class="floatingText">Username</label>
                            </div>
                        </div> 
                    </div>
                    <div class="rows">
                        <div class="col-lg-12">
                            <div class="form-group" style="margin-bottom: 0;">
                                <input type="password" name="password" id="password" class="form-control input-lg" tabindex="2">
                                <label class="floatingText">Password</label>
                            </div>
                        </div> 
                    </div>
                    <div style="clear:both;"></div>
                </form>
            </div>
            <hr class="colorgraph" style="margin:0;padding:0;"/>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.floatingText').click(function() {
            if($(this).prev()[0].tagName == "INPUT" || $(this).prev()[0].tagName == "TEXTAREA") {
                $(this).siblings().select();
            } else {
                // can't open <select> :(
            }
            
        });
    });
</script>
