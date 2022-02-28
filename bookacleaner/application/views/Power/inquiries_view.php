<?php $this->load->view('Power/navigation'); ?>

	<div class="panel panel-primary" style="border-radius: 0px; border: 0px;">
        <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
			View Inquiry 
		</div>
		<div class="panel-body">
			<?php foreach($inquiries_view as $view): ?>
			<div class ="card border-info mb-3">
				<div class="card-header">
					<b> Inquiry from <?php echo ucwords($view->Inquiries_Name)?> </b>
				</div>
				<div class="card-body">
					<h2><?php echo ucwords(htmlspecialchars($view->Inquiries_title));?></h2>
					<h6><?php echo date('M d, Y h:iA', htmlspecialchars($view->Inquiries_DateTime));?></h6>
					<h6><?php echo htmlspecialchars($view->Inquiries_EmailAddress)." / ".htmlspecialchars($view->Inquiries_PhoneNumber); ?></h6>
					<hr>
					<h3><?php echo htmlspecialchars($view->Inquiries_body);?></h3>
				</div>
			</div>
			
			
			<?php endforeach;?>
			
			
			
			
			
			
			
			
			
<!-- 			<div class="table-responsive">
				<table class="table table-primary">
					<thead>
						<tr class="info">
							<td>#</td>
							<td>Title</td>
							<td>Body</td>
							<td>Name</td>
							<td>Email Address</td>
							<td>Phone Number</td>
							<td>Date & Time</td>
						</tr>
					</thead>
					<tbody>
						<?php
// 						foreach($inquiries_view as $view){
// 							echo '<tr>';
// 								echo '<td>'; echo $view->Inquiries_ID; echo '</td>';
// 								echo '<td>'; echo $view->Inquiries_title; echo '</td>';
// 								echo '<td>'; echo $view->Inquiries_body; echo '</td>';
// 								echo '<td>'; echo $view->Inquiries_Name; echo '</td>';
// 								echo '<td>'; echo $view->Inquiries_EmailAddress; echo '</td>';
// 								echo '<td>'; echo $view->Inquiries_PhoneNumber; echo '</td>';
// 								echo '<td>'; echo date('M d, Y h:iA', $per_inquiry->Inquiries_DateTime); echo '</td>';
// 							echo '</tr>';
// 						}
						?>
					</tbody>
				</table>
			</div> -->
			
			
		</div>
	</div>
