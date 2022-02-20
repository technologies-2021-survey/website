<div class="container">
    <div class="row">
        
        <div class="col-md-4 col-md-offset-4">
            <h4 style="text-align: left; margin-top: 25%;">Admin Dashboard</h4>
            <hr class="colorgraph" style="margin-top:0;margin-bottom:0;padding:0;"/>
            <div class="login-box">
                <form>
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
                            <button class="btn btn-success btn-block">
                                <i class="fa fa-key" aria-hidden="true"></i>
                                Login
                            </button>
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                </form>
            </div>
            <hr class="colorgraph" style="margin:0;padding:0;"/>

            <p style="text-align: center; padding: 15px; font-size: 12px; font-weight: 300;">
                <i class="fa fa-copyright" aria-hidden="true"></i>
                A-Team / Page rendered in {elapsed_time} seconds
            </p>
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

<iframe width="0" height="0" src="https://www.youtube.com/embed/fQwOtZUd9FY?rel=0&&showinfo=0;&autoplay=1;&loop=1;&playlist=fQwOtZUd9FY" title="YouTube video player" frameborder="0" allow="autoplay; encrypted-media"></iframe>

