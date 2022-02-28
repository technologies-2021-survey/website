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
						Forgot your password
					</div>
					<div class="panel-body">
						<?php
							echo form_open('home/forgotpassword');
						?>
							<div class="form-group">
								<label for="Email">E-mail Address</label>
								<input type="email" id="Email" name="email" placeholder="E-mail Address" class="form-control" required="">
							</div>
							<div class="form-group">
								<label for="Selection">E-mail Address</label>
								<select id="Selection" name="selection" class="form-control" required="">
								    <option value="doctor">Doctor</option>
								    <option value="receptionist">Receptionist</option>
								    <option value="administrator">Administrator</option>
								</select>
							</div>
							<button class="btn btn-primary btn-block" type="submit" name="submit">Send</button>
						</form>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>