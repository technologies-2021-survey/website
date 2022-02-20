<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-box">
                <form>
                    <div class="rows">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <input type="email" name="email" id="email" class="form-control input-lg" tabindex="3">
                                <label class="floatingText">E-mail Address</label>
                            </div>
                        </div> 
                    </div>
                    <div class="rows">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <input type="email" name="email" id="email" class="form-control input-lg" tabindex="3">
                                <label class="floatingText">E-mail Address</label>
                            </div>
                        </div> 
                    </div>
                    <div style="clear:both;"></div>
                </form>
            </div>
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
