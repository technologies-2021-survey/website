<?php $this->load->view('Home/Home_navigation'); ?>
        <a id="back-to-top"></a>
        <section id="header">
            <h2 class="bottom-left">Welcome to Pediatrics of WHealth!</h2>
        </section>
        <section id="services">
    		<div class="container" style="margin-top: 10px;">
    			<div class="row">
    			    <h2 class="services-title">Our Services</h2>
    				<div class="col-md-3">
    				    <div class="services">
    				        <i class="fas fa-baby"></i>
    				        <h4>Checkup</h4>
    				        
    				    </div>
    				</div>
    				<div class="col-md-3">
    				    <div class="services">
    				        <i class="fas fa-mobile-alt"></i>
    				        <h4>Online Consultation</h4>
    				       
    				    </div>
    				</div>
    				<div class="col-md-3">
    				    <div class="services">
    				        <i class="fas fa-syringe"></i>
    				        <h4>Immunization</h4>
    				        
    				    </div>
    				</div>
    				<div class="col-md-3">
    				    <div class="services">
    				        <i class="fas fa-comments"></i>
    				        <h4>Counselling</h4>
    				        
    				    </div>
    				</div>
    			</div>
    			
    		</div>
    	</section>
    	<section id="aboutus">
    	    <div class="container">
    	        <div class="row">
    	            <div class="col-md-6">
    	                <img src="<?php echo base_url() . "assets/img/pedia.jpg";?>" class="img-responsive"/>
    	            </div>
    	            <div class="col-md-6">
    	                <h2 style="font-size: 35px; font-weight: bold;">Why PeekABook?</h2>
    	                <p style="font-size: 22px;text-align:justify;word-break:break-word;">
				PeekABook is a child health record management and appointment system where all data will be digitzed, made portable, 
				an stored in a single comprehensive system. The developers partnered with the Pediatrics Department of Whealth Medical Clinic and Diagnostic Center
			    	to deliver the best services possible for their patients.</p>
    	            </div>
    	        </div>
    	    </div>
    	</section>
    	<section id="downloadOurApp">
    	    <div class="container">
    	        <div class="row">
    	            <div class="col-xs-4">
    	                <img src="<?php echo base_url() . "assets/img/ourapp.jpg";?>" class="img-responsive"/>
    	            </div>
    	            <div class="col-xs-8">
    	                <h2>Our app!</h2>
    	                <p style="font-size: 19px;text-align:justify;word-break:break-word;">To connect directly with our patients,PeekABook has a mobile application.
			Through this application, user can manage the health records of their child.
			PeekABook also offers appointment and online consultation for hassle-free scheduling.
			Users can be notified when their child's next immunization regimen is due. Also, users can easily ask inquiries using the messaging feature.</p>
    	            </div>
    	        </div>
    	    </div>
    	</section>
		<section id="inquiries">
		    <div class="container">
    			<div class="row">
    				<div class="col-md-7">
    					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d965.2545587207794!2d121.0006312292216!3d14.598036799362745!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c9e56ff38f8d%3A0xe2c7824b6ca462d0!2s1519%20Nagtahan%20St%2C%20Santa%20Mesa%2C%20Manila%2C%201008%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1623460187850!5m2!1sen!2sph" width="100%" height="315" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    				</div>
    				<div class="col-md-5">
    					<form id="insertInquiry">
    						<div class="form-group">
    							<label for="fullname">Full Name</label>
    							<input type="text" id="fullname" class="form-control" name="fullname" placeholder="Full Name" autocomplete="off" required="">
    						</div>
    						<div class="row">
    							<div class="col-sm-6">
    								<div class="form-group">
    									<label for="email">E-mail Address</label>
    									<input type="email" id="email" class="form-control" name="email" placeholder="E-mail Address (xxxxxx@gmail.com)" autocomplete="off" required="">
    								</div>
    							</div>
    							<div class="col-sm-6">
    								<div class="form-group">
    									<label for="phoneNumber">Phone Number</label>
    									<input type="text" id="phoneNumber" class="form-control" name="phoneNumber" placeholder="Phone Number (09123456789)" pattern="^(09|\+639)\d{9}$" autocomplete="off" required="">
    								</div>
    							</div>
    						</div>
    						<div class="form-group">
    							<label for="title">Title</label>
    							<input type="text" id="title" class="form-control" name="title" placeholder="Title" autocomplete="off" required="">
    						</div>
    						<div class="form-group">
    							<label for="message">Message</label>
    							<textarea type="text" id="message" class="form-control" name="message" placeholder="Message" autocomplete="off" required=""></textarea>
    						</div>
    						<div class="form-group">
    							<button type="submit" class="btn btn-success" name="submit"><i class="far fa-paper-plane" style="margin-right: 10px;"></i> Submit</button>
    						</div>
    					</form>
    				</div>
    			</div>
    		</div>
		</section>
		<section id="footer">
		    Copyright &copy; WHealth 2021. All rights reserved.
		</section>
		<style type="text/css">#back-to-top { display: inline-block; background-color: #FF9800; width: 50px; height: 50px; text-align: center; border-radius: 4px; position: fixed; bottom: 30px; right: 30px; transition: background-color .3s, opacity .5s, visibility .5s; opacity: 0; visibility: hidden; z-index: 1000; } #back-to-top::after { content: "\f077"; font-family: FontAwesome; font-weight: normal; font-style: normal; font-size: 2em; line-height: 50px; color: #fff; } #back-to-top:hover { cursor: pointer; background-color: #333; } #back-to-top:active { background-color: #555; } #back-to-top.show { opacity: 1; visibility: visible; }section#downloadOurApp{background:#fafafa;padding-bottom: 50px; padding-top: 50px;}section#services{padding-top:50px;padding-bottom:50px;}section#aboutus { padding-top: 50px; padding-bottom: 50px; background: #2c3e50; color: #FFF;}.bottom-left { position: absolute; bottom: 10%; left: 10%; color: #FFF; font-weight: bold; }section#header{position:relative;height: 60vh; background: url(<?php echo base_url();?>assets/img/unsplash.com-photos-gUFQybn_CVg.jpg) no-repeat center; background-size: cover;}h2.services-title{text-align: center; margin-bottom: 50px;}div.services{margin-top: 40px;margin-bottom: 40px;text-align:center;}.services i { font-size: 5vh; margin-bottom: 40px; }section#footer{background: #3b5773; color: white; padding: 10px; text-align: center;}section#inquiries{padding-top: 25px; padding-bottom: 25px; background: #2c3e50; color: #FFF;}</style>
		<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<script type="text/javascript">
		var btn = $('#back-to-top');

        $(window).scroll(function() {
          if ($(window).scrollTop() > 300) {
            btn.addClass('show');
          } else {
            btn.removeClass('show');
          }
        });
        
        btn.on('click', function(e) {
          e.preventDefault();
          $('html, body').animate({scrollTop:0}, '300');
        });
        $(document).ready(function() {
            $( "form#insertInquiry" ).submit(function(e)
            {
                e.preventDefault();
                var fullname = $("#fullname").val();
                var email = $("#email").val();
                var phoneNumber= $("#phoneNumber").val();
                var title= $("#title").val();
                var message= $("#message").val();
    
                $.ajax({
					type:"post",
					url: "https://peekabook.tech/index.php/home/insert",
					data:{fullname:fullname,email:email,phoneNumber:phoneNumber, title: title, message: message},
					success:function(data) {
						if(data != "") {
							Swal.fire({
							title: 'Ooops!',
							html: data,
							icon: 'error'
							});
						} else {
							$("#fullname").val("");
							$("#email").val("");
							$("#phoneNumber").val("");
							$("#title").val("");
							$("#message").val("");
							
							Swal.fire(
							'Good job!',
							'Successfully!',
							'success'
							);
						}
					}
                });
            });
        });
        </script>
        <?php
        if($this->input->get('success', true)) {
            echo "<script>Swal.fire( 'Good job!', 'Successfully!', 'success' )</script>";
        }
        ?>
