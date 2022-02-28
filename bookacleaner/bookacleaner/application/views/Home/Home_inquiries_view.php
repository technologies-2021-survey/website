<?php $this->load->view('Home/Home_navigation'); ?>

		<div class="container">
			<div class="row">
				<div class="col-lg-12">
				    <table class="table table-primary">
					<?php
					foreach($inquiries_view as $view){
					    echo '<tr>';
    						echo '<td>'; echo $view->Inquiries_ID; echo '</td>';
    						echo '<td>'; echo $view->Inquiries_title; echo '</td>';
    						echo '<td>'; echo $view->Inquiries_body; echo '</td>';
    						echo '<td>'; echo $view->Inquiries_Name; echo '</td>';
    						echo '<td>'; echo $view->Inquiries_EmailAddress; echo '</td>';
    						echo '<td>'; echo $view->Inquiries_PhoneName; echo '</td>';
    						echo '<td>'; echo $view->Inquiries_DateTime; echo '</td>';
						echo '</tr>';
				    }
				    ?>
				    </table>
				</div>
			</div>
		</div>