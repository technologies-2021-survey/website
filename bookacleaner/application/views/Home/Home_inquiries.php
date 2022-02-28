<?php $this->load->view('Home/Home_navigation'); ?>
		<section class="header">
			<div class="container">
				<div class="row">
					<div style="position:relative;">
					<h2 class="heading">Inquiries</h2>
				</div>
			</div>
		</section>
		<div class="container">
			<div class="row">
				<div class="col-md-7">
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d965.2545587207794!2d121.0006312292216!3d14.598036799362745!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c9e56ff38f8d%3A0xe2c7824b6ca462d0!2s1519%20Nagtahan%20St%2C%20Santa%20Mesa%2C%20Manila%2C%201008%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1623460187850!5m2!1sen!2sph" width="100%" height="315" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
				</div>
				<div class="col-md-5">
					<form action="<?=$this->config->base_url()?>insert" method="POST">
						<div class="form-group">
							<label for="name">Name</label>
							<input type="text" id="name" class="form-control" name="name" placeholder="Name" autocomplete="off" required="">
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