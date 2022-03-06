<?php $this->load->view('Home/Home_navigation'); ?>
		<section class="header">
			<div class="header2">
				<div class="container">
					<div class="row">
						<div class="col-lg-12">
							<div class="logo"></div>
							<p>
								Hire a trusted and reliable professional cleaner for your home or office.
							</p>

						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="services" id="services">
			<div class="container">
				<h3>Services</h3>
				<div class="row two">
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="services-box2">
							<i class="fa fa-home" aria-hidden="true"></i>
							<div class="services-title2">
								Home Cleaning
							</div>
							<div class="services-body2">
								Our professional cleaner can tidy up homes of all shapes and sizes.
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="services-box2">
							<i class="fa fa-briefcase" aria-hidden="true"></i>
							<div class="services-title2">
								Office Cleaning
							</div>
							<div class="services-body2">
								Our professional cleaner can clean up your workplace and make it feel like home.
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="services-box2">
							<i class="fa fa-trash" aria-hidden="true"></i>
							<div class="services-title2">
								Cleaning Products
							</div>
							<div class="services-body2">
								We use all-natural products to clean, disinfect and sanitize.
							</div>
						</div>
					</div>
				</div>

				<div class="divider"></div>

				<div class="row two">
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						<div class="services-box">
							<div class="services-title">
								Deep Cleaning
							</div>
							<div class="services-desc">
								A total cleaning for your home or office. Full sweep, wipe down, sanitizing and disinfecting. A team of 2 professional cleaner at 5 hours per session (depending on the size of your home or office).
							</div>
						</div>
					</div>

					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						<div class="services-box">
							<div class="services-title">
								Disinfection Services
							</div>
							<div class="services-desc">
								Different cleaning and sanitation services to complement the deep cleaning for your home or office. Tell us cwhich cleaning and sanitation service is best for your needs.
							</div>
						</div>
					</div>

					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						<div class="services-box">
							<div class="services-title">
								Move In & Move Out Cleaning
							</div>
							<div class="services-desc">
								Moving in and out of your home or office can be a very daunting and stressful task. We can help lighten your work load.
							</div>
						</div>
					</div>

					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						<div class="services-box">
							<div class="services-title">
								Upholstery Cleaning
							</div>
							<div class="services-desc">
								It makes use of vacuum cleaning system with ecological pure water filter technology. We remove deep-seated dirt and allergens.
							</div>
						</div>
					</div>

					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						<div class="services-box">
							<div class="services-title">
								Steam Cleaning
							</div>
							<div class="services-desc">
								It makes use of Dry Steam cleaning machine. Picks up dust, removes stains, kills germs and bacteria, disinfects, sanitize all types of hard surfaces without the use of chemicals.
							</div>
						</div>
					</div>

					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						<div class="services-box">
							<div class="services-title">
								Aircon Cleaning
							</div>
							<div class="services-desc">
								Thoroughly clean all types and models of air conditioning systems.
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</section>

		<section class="book" id="book">
			<div class="container">
				<h3>Book a service or get a quote today</h3>
				<div class="row">
					<div style="background: #2b137e;margin: 0 15px;box-shadow: 0 0 10px #281276;">
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="padding: 0;margin: 0;">
							
							<img src="<?=base_url('./assets/img/book.png');?>" style="width: 100%; margin: 0 auto; display: block;">
						</div>
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
							
							<div class="timeline">
								<div class="timeline-block">
									<div class="timeline-title">
										1
									</div>
									<div class="timeline-body">
										Check out our available services.
									</div>
								</div>
								<div class="timeline-block">
									<div class="timeline-title">
										2
									</div>
									<div class="timeline-body">
									Fill out the form and click submit.
									</div>
								</div>
								<div class="timeline-block">
									<div class="timeline-title">
										3
									</div>
									<div class="timeline-body">
										Once submitted, kindly wait for a confirmation email from us. Cost and other details will be discussed before proceeding.
									</div>
								</div>
							</div>
							<div style="text-align:center;"> 
								<a href="#" class="book-btn" data-toggle="modal" data-target="#bookNow">
									Book a cleaner now!
								</a>
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