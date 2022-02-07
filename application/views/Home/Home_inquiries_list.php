<?php $this->load->view('Home/Home_navigation'); ?>

		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<h2>Inquiries</h2>
					<table class="table table-striped table-hover">
						<tbody>
							<?php
								foreach($inquiries as $per_inquiry):
								?>
								<tr>
									<td>
										<?=$per_inquiry->Inquiries_ID; ?>
									</td>
									<td>
										<?=$per_inquiry->Inquiries_title; ?>
									</td>
									<td>
										<?=$per_inquiry->Inquiries_body; ?>
									</td>
									<td>
										<?=$per_inquiry->Inquiries_Name; ?>
									</td>
									<td>
										<?=$per_inquiry->Inquiries_EmailAddress; ?>
									</td>
									<td>
										<?=$per_inquiry->Inquiries_PhoneNumber; ?>
									</td>
									<td>
										<?=
											date('M d, Y h:iA', $per_inquiry->Inquiries_DateTime);
											//  you must put to application/config/config.php after define function:
											// date_default_timezone_set('Asia/Manila');
										?>
									</td>
									<td>
										<a href="<?=base_url()?>view/<?=$per_inquiry->Inquiries_ID; ?>" class="btn btn-success">View</a>
										<a href="<?=base_url()?>delete/<?=$per_inquiry->Inquiries_ID; ?>" class="btn btn-danger">Delete</a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>