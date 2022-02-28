<?php $this->load->view('Home/Home_navigation'); ?>
<div class="container" style="padding-top:15%;">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">

			<?php if(validation_errors() || $this->input->get('error')): ?>
			<div class="alert alert-danger">
			<?php
				echo validation_errors();
				if($this->input->get('error') != "404") {
					echo "Your username is incorrect.";
				} else {
				    echo "Error! There's something wrong.";
				}
			?>
			</div>
			<?php endif ?>
			<div class="panel panel-primary" style="border: 0; box-shadow: 1px 1px 3px #a7a7a7;">
				<div class="panel-heading" style="font-size: 20px; text-align: center; padding-top: 20px; padding-bottom: 20px;">
					Sign In
				</div>
				<div class="panel-body">
					<?php
					echo form_open('login/validation');
					?>
						<div class="input-group" style="margin-bottom: 20px;">
							<span class="input-group-addon"><i class="fas fa-user"></i></span>
							<input type="text" class="form-control" name="user_name" required="" placeholder="Username">
						</div>
						
						<div class="input-group" style="margin-bottom: 20px;">
							<span class="input-group-addon"><i class="fas fa-key"></i></span>
							<input type="password" class="form-control" name="password" required="" placeholder="Password">
						</div>
						<div class="pull-right">
							<a href="<?php echo base_url();?>forgotpassword">Forgot your password</a>
						</div>
						<div style="clear:both;"></div>
						<div class="form-group" style="margin-bottom: 20px;">
							<label>Login as</label>
							<select class="form-control" name="selection">
								<option value="doctor">Doctor</option>
								<option value="receptionist">Receptionist</option>
								<option value="administrator">Administrator</option>
							</select>
						</div>
						<div class="form-group">
							<input type="submit" name="submit" class="btn btn-primary" value="Login" style="margin:0 auto; display:block;">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>