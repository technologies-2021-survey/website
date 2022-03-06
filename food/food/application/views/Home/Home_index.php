<?php $this->load->view('Home/Home_navigation'); ?>
		<section class="header">
			<div class="header2">
				<div class="container">
					<div class="row">
						<div class="col-lg-12">
							<img src="https://peekabook.tech/food/food/assets/img/logo.png" style="height: 12vh; margin: 0 auto; display: block; margin-top: 90px; margin-bottom: 28px;">
							<p>
								CAFÉ LIDIA formally opened in September 8, 2009 after its soft opening on August 17, 2009. Operations were cut short because of Typhoon “Ondoy” which came in Sept 26, 2009. We reopened our doors on October 15, 2009 and since then we’ve never looked back. We called the restaurant “LIDIA” after the owner’s name which means Woman of Business. Having been in the shoe manufacturing business for years, the transition was not easy. We experienced the same birthing pains of one who is starting in the business. But with hard work, and perseverance, things got better after three months of operation. We did not spend a penny on any marketing scheme. We became known by word of mouth from our first customers – friends, relatives and school mates. Today, we are proud to have grown into one if not the most well-loved café – restaurant in Marikina. Families, organizations, “barkadas” come to enjoy the
							</p>

						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="book" id="book">
			<div class="container">
				<h3>Contact Info</h3>
				<div class="row">
					<div style="background: #2b137e;margin: 0 15px;box-shadow: 0 0 10px #281276;">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="padding: 0;margin: 0;">
							
							<iframe width="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=64%20F.%20Calderon%20St.,%20Calumpang%20Marikina%20City,%20Philippines&amp;t=&amp;z=19&amp;ie=UTF8&amp;iwloc=&amp;output=embed"></iframe>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="col-md-4">
								<div class="contact-item" style="padding-top: 20%;">
									<p style="color: white; font-size: 23px;">
										<span><i class="fas fa-map-marker-alt" style="padding-right: 1%;"></i></span>
										64 F. Calderon St., Calumpang Marikina City, Philippines
									</p>
								</div>
								<div class="contact-item">
									<p style="color: white;">
										<span><i class="fa fa-phone"></i> Phone</span> (02) 8647 7606
									</p>
								</div>
								<div class="contact-item">
									<p style="color: white;">
										<span><i class="fa fa-phone"></i> Phone</span> (02) 8647 2542
									</p>
								</div>
								<div class="contact-item">
									<p style="color: white;">
										<span><i class="fa fa-phone"></i> Phone</span> 0916 338 7201
									</p>
								</div>
								<div class="contact-item">
									<p style="color: white;">
										<span><i class="far fa-envelope"></i> Email</span> cafe_lidia@yahoo.com.ph
									</p>
								</div>
							</div>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
		</section>

		<section class="footer">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<i class="fa fa-copyright" aria-hidden="true"></i>
						A-Team / Page rendered in {elapsed_time} seconds
					</div>
				</div>
			</div>
		</section>

		<div class="modal fade" id="bookNow" tabindex="-1" role="dialog" aria-labelledby="bookNowLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<h4 style="text-align: center; margin-top: 22px;">Book a service with us today!</h4>
					<hr class="colorgraph" />
					<form id="book">
						<div class="modal-body">
							
								<div class="rows">
									<div class="col-xs-6 col-sm-6 col-md-6">
										<div class="form-group">
											<input type="text" name="first_name" id="first_name" class="form-control input-lg" tabindex="1" autocomplete="off" required="">
											<label class="floatingText">First Name</label>
										</div>
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6">
										<div class="form-group">
											<input type="text" name="last_name" id="last_name" class="form-control input-lg" tabindex="2" autocomplete="off" required="">
											<label class="floatingText">Last Name</label>
										</div>
									</div>
								</div>
								<div class="rows">
									<div class="col-lg-12">
										<div class="form-group">
											<input type="email" name="email" id="email" class="form-control input-lg" tabindex="3" autocomplete="off" required="">
											<label class="floatingText">E-mail Address</label>
										</div>
									</div> 
								</div>
								<div class="rows">
									<div class="col-xs-6 col-sm-6 col-md-6">
										<div class="form-group">
											<input type="text" name="mobile_number" id="mobile_number" class="form-control input-lg" pattern="^(09|\+639)\d{9}$" tabindex="4" autocomplete="off" required="">
											<label class="floatingText">Mobile Number</label>
										</div> 
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6">
										<div class="form-group">
											<input type="date" name="preferred_date" id="preferred_date" class="form-control input-lg" tabindex="5" autocomplete="off" required="">
											<label class="floatingText">Preferred Date</label>
										</div>
									</div>
								</div>
								<div class="rows">
									<div class="col-lg-12">
										<div class="form-group">
											<input type="text" name="address" id="address" class="form-control input-lg" tabindex="6" autocomplete="off" required="">
											<label class="floatingText">Address</label>
										</div>
									</div>
								</div>
								<div class="rows">
									<div class="col-lg-12">
										<div class="form-group">
											<select name="cleaning" class="form-control input-lg" tabindex="7" autocomplete="off" required="">
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
											<input type="text" name="sqm" id="sqm" class="form-control input-lg" tabindex="8" autocomplete="off" required="">
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
											<textarea type="text" name="comments_or_notes" id="comments_or_notes" class="form-control input-lg" tabindex="8" autocomplete="off" required=""></textarea>
											<label class="floatingText">Comments/Notes</label>
										</div>
									</div>
								</div>
								<div style="clear:both;"></div>
							
						</div>
						<hr class="colorgraph" style="margin-bottom: -1px;" />
						<div class="modal-footer" style="border: 0;">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button name="submit" class="btn btn-success">
								<i class="fa fa-paper-plane" aria-hidden="true" style="margin-right: 5px;"></i>
								Submit Booking
							</button>
						</div>
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
				var notif = new Audio('https://peekabook.tech/bookacleaner/bookacleaner/assets/mp3/mixkit-dry-pop-up-notification-alert-2356.wav');
				$('form#book').submit(function(e) {
					e.preventDefault();
					var first_name = $('input[name=first_name]').val();
					var last_name = $('input[name=last_name]').val();
					var email = $('input[name=email]').val();
					var mobile_number = $('input[name=mobile_number]').val();
					var preferred_date = $('input[name=preferred_date]').val();
					var address = $('input[name=address]').val();
					var cleaning = $('select[name=cleaning]').val();
					var sqm = $('input[name=sqm]').val();
					var service_required = [];
					var comments_or_notes = $('textarea[name=comments_or_notes]').val();

					$("input[name='serviceRequired']:checked").each(function(){
						service_required.push(this.value);
					});
					$.ajax({
						url: "<?php echo base_url(); ?>home/book",
						type: "POST",
						data: {
							first_name: first_name,
							last_name: last_name,				
							email: email,				
							mobile_number: mobile_number,				
							preferred_date: preferred_date,				
							address: address,				
							cleaning: cleaning,				
							sqm: sqm,				
							service_required: service_required,				
							comments_or_notes: comments_or_notes		
						},
						success: function(data){
							console.log(data);
							var data = JSON.parse(data);
							if(data.status == 200) {
								notif.play();
								$("#back-to-top").trigger("click");
								$("form").trigger("reset");
								Swal.fire({
									icon: 'success',
									title: data.message
								})
								
							} else {
								notif.play();
								Swal.fire({
									icon: 'error',
									title: data.message
								})
							}
						}
					});
				});
			});
		</script>