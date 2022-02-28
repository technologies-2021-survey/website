	<div class="container" style="padding-top:13%;">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<?php if($success) { ?>
				<div class="alert alert-success">
					<?php echo $success; ?>
				</div>
				<br/>
				<center>Please return to the <a href="<?=base_url().'login';?>">login</a>.</center>
				<?php } else { ?>
				<?php if(validation_errors() || $error) { ?>
				<div class="alert alert-danger">
					<?php echo validation_errors(); ?>
					<?php echo $error; ?>
				</div>
				<?php } ?>
				<div class="panel panel-primary">
					<div class="panel-heading">
						Change Password
					</div>
					<div class="panel-body">
						<?php
							echo form_open('home/forgot/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5));
						?>
							<div class="form-group">
								<label for="password">Password</label>
								<input type="password" id="password" name="password" placeholder="Password" class="form-control" required="">
							</div>
							<div class="form-group">
								<label for="repassword">Re-type Password</label>
								<input type="password" id="repassword" name="repassword" placeholder="Re-type Password" class="form-control" required="">
							</div>
							<button class="btn btn-primary btn-block" type="submit" name="submit">Send</button>
						</form>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>