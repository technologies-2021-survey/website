<?php $this->load->view('Power/navigation'); ?>
<?php 
$textss = "";
if($this->session->selection == "doctor") {
    $textss = "doctor";
} else if($this->session->selection == "receptionist") {
    $textss = "receptionist";
} else if($this->session->selection == "administrator") {
    $textss = "administrator";
} 
?>
	<div class="panel panel-primary" style="border-radius: 0px; border: 0px;">
        <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
			Inquiries
		</div>
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<td>#</td>
							<td>ID</td>
							<td>Title</td>
							<td>Body</td>
							<td>Name</td>
							<td>Email Address</td>
							<td>Phone Number</td>
							<td>Date & Time</td>
							<td></td>
						</tr>
					</thead>
					<tbody>
						<?php
							$i = 1;
							foreach($inquiries as $per_inquiry):
							?>
							<tr>
								<td><?php echo $i++;?> </td>
								<td>
									<?=htmlspecialchars($per_inquiry->Inquiries_ID); ?>
								</td>
								<td>
									<?=htmlspecialchars($per_inquiry->Inquiries_title); ?>
								</td>
								<td>
									<?=htmlspecialchars($per_inquiry->Inquiries_body); ?>
								</td>
								<td>
									<?=htmlspecialchars($per_inquiry->Inquiries_Name); ?>
								</td>
								<td>
									<?=htmlspecialchars($per_inquiry->Inquiries_EmailAddress); ?>
								</td>
								<td>
									<?=htmlspecialchars($per_inquiry->Inquiries_PhoneNumber); ?>
								</td>
								<td>
									<?=
										date('M d, Y h:iA', $per_inquiry->Inquiries_DateTime);
										//  you must put to application/config/config.php after define function:
										// date_default_timezone_set('Asia/Manila');
									?>
								</td>
								<td>
									<a href="<?=base_url()?>index.php/<?php echo $textss;?>/view/<?=$per_inquiry->Inquiries_ID; ?>" class="btn btn-info">View</a>
									<a href="<?=base_url()?>index.php/<?php echo $textss;?>/delete/<?=$per_inquiry->Inquiries_ID; ?>" class="btn btn-danger">Delete</a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
