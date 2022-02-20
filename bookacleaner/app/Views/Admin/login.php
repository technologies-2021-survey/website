<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <form>
                <div class="rows">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <input type="text" name="first_name" id="first_name" class="form-control input-lg" tabindex="1">
                            <label class="floatingText">First Name</label>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <input type="text" name="last_name" id="last_name" class="form-control input-lg" tabindex="2">
                            <label class="floatingText">Last Name</label>
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
                <div class="rows">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <input type="text" name="mobile_number" id="mobile_number" class="form-control input-lg" pattern="^(09|\+639)\d{9}$" tabindex="4">
                            <label class="floatingText">Mobile Number</label>
                        </div> 
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <input type="date" name="preferred_date" id="preferred_date" class="form-control input-lg" tabindex="5">
                            <label class="floatingText">Preferred Date</label>
                        </div>
                    </div>
                </div>
                <div class="rows">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <input type="text" name="address" id="address" class="form-control input-lg" tabindex="6">
                            <label class="floatingText">Address</label>
                        </div>
                    </div>
                </div>
                <div class="rows">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <select name="cleaningFor" class="form-control input-lg" tabindex="7">
                                <option selected="">Please select</option>
                                <option value="1">Appartment</option>
                                <option value="2">House</option>
                                <option value="3">Condo</option>
                                <option value="4">Office</option>
                                <option value="5">Others (Plesase specify in NOTES)</option>
                            </select>
                            <label class="floatingText">I need cleaning for</label>
                        </div>
                    </div>
                </div>
                <div class="rows">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <input type="text" name="sqm" id="sqm" class="form-control input-lg" tabindex="8">
                            <label class="floatingText">How big is the space that needs cleaning? (sqm.)</label>
                        </div>
                    </div>
                </div>
                <div class="rows">
                    <div class="col-lg-12">
                        <label>Service Required</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="serviceRequired" value="Deep Cleaning">
                                <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                Deep Cleaning
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="serviceRequired" value="Disinfection Service">
                                <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                Disinfection Service
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="serviceRequired" value="Move In & Move Out Cleaning">
                                <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                Move In & Move Out Cleaning
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="serviceRequired" value="Upholstery Cleaning">
                                <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                Upholstery Cleaning
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="serviceRequired" value="Steam Cleaning">
                                <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                Steam Cleaning
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="serviceRequired" value="Aircon Cleaning">
                                <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                Aircon Cleaning
                            </label>
                        </div>
                    </div>
                </div>
                <div class="rows">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <textarea type="text" name="sqm" id="sqm" class="form-control input-lg" tabindex="8"></textarea>
                            <label class="floatingText">Comments/Notes</label>
                        </div>
                    </div>
                </div>
                <div style="clear:both;"></div>
            </form>
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
