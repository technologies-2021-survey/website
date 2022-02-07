<?php $result = (array) $immunizations; ?>
<?php $this->load->view('Power/navigation'); ?>
<?php
$textss = "";
if($this->session->selection == "doctor") {
    $textss = "doctor";
    $count = 0;
    $sql = 'SELECT
    *
FROM
immunization_record WHERE immunization_record_id = '.$this->uri->segment(3).' AND `interview_id` = '.$this->session->id;

$s = $this->db->query($sql);
if($s->num_rows() > 0) {
    // exist
    $count++;
}

if($count == 0) {
    redirect("doctor/patients?error=There\'s something wrong");
    exit();
}
} else if($this->session->selection == "receptionist") {
    $textss = "receptionist";
} else if($this->session->selection == "administrator") {
    $textss = "administrator";
} 
?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
    <div class="panel panel-primary" style="border-radius: 0px; border: 0px;">
        <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
            Edit Immunization Record
        </div>
        <div class="panel-body">
            <form action="<?php echo base_url().$textss; ?>/editImmunizationRecord/<?php echo $this->uri->segment(3); ?>" method="POST">
                <div class="form-group">
                    <label>Patient Name</label>
                    <input type="text" name="patient_name" class="form-control" value="<?php echo $patient_name; ?>" disabled="">
                </div>
                <div class="form-group">
                    <label>Parent Name</label>
                    <input type="text" name="parent_name" class="form-control" value="<?php echo $parent_name; ?>" disabled="">
                </div>
                <div class="form-group">
                    <label>Date of Visit</label>
                    <input type="date" name="dates" class="form-control" value="<?php echo $result[0]['date']; ?>">
                </div>
				
				<!--new1-->
				<hr>
				
				<div class ="row">
					<div class = "col-xs-4">
						<div class = "form-group">
							<label>Age</label>
							<input type = "text" name="age" class ="form-control" value="<?php echo $result[0]['age']; ?>">
						</div>
					</div>
					<div class = "col-xs-4">
						<div class = "form-group">
							<label>Weight (kg)</label>
							<input type = "text" name="weight" class ="form-control" value="<?php echo $result[0]['weight']; ?>">
						</div>
					</div>
					<div class = "col-xs-4">
						<div class = "form-group">
							<label>Length (cm)</label>
							<input type = "text" name="length" class ="form-control" value="<?php echo $result[0]['length']; ?>">
						</div>
					</div>            
				</div>
				
				<div class = "row">
					<div class = "col-xs-6">
						<div class = "form-group">
							<label>BMI (kg/m²)</label>
							<input type = "text" name="bmi" class ="form-control" value="<?php echo $result[0]['bmi']; ?>">
						</div>
					</div>
					<div class = "col-xs-6">
						<div class = "form-group">
							<label>Head Circumference</label>
							<input type = "text" name="head_circumference" class ="form-control" value="<?php echo $result[0]['head_circumference']; ?>">
						</div>
					</div>
				</div>
				
				<hr>
				
				<!--new1-->
				
                <div class="form-group">
                    <label>Vaccine</label><br/>
                    <select name="vaccine" class="form-control" required="">
                        <?php
                        $q = $this->db->query("SELECT * FROM vaccine_terms_tbl");
                        foreach($q->result_array() as $row) {
                            $asdsd = $this->db->query("SELECT * FROM `immunization_record` WHERE `immunization_record_id` = '".$asd."'")->result_array();
                            if($vaccine_id == $asdsd[0]['immunization_record_id']) {
                                echo '<option value="'.$row['vaccine_terms_id'].'" selected="">'.$row['vaccine_terms_title'].'</option>';
                            } else {
                                echo '<option value="'.$row['vaccine_terms_id'].'">'.$row['vaccine_terms_title'].'</option>';
                            }
                            
                        }
                        ?>
                    </select>
                </div>
                <script>
                $(document).ready(function(){
                    $('select[name=vaccine]').multiselect({
                        nonSelectedText: 'Select Vaccine',
                        enableFiltering: true,
                        enableCaseInsensitiveFiltering: true,
                        buttonWidth:'400px',
                    });
                });
                </script>
                <div class="form-group">
                    <label>Route</label>
                    <input type="text" name="route" class="form-control" value="<?php echo $result[0]['route']; ?>">
                </div>
				
				<!--new2-->
				<div class = "form-group">
					<label>Doctor's Instruction</label>
					<textarea class ="form-control" name ="doctors_instruction" rows = "3" required =""><?php echo $result[0]['doctors_instruction']; ?></textarea>
				</div>

				<hr>
				
				<div class = "row">
					<div class = "col-xs-6">
						<div class = "form-group">
							<label>Please come back on: </label>
							<input type = "date" name="comeback_on" class ="form-control" value="<?php echo $result[0]['comeback_on']; ?>">
						</div>
					</div>
					<div class = "col-xs-6">
						<div class = "form-group">
							<label>for</label>
							<input type = "text" name="comeback_for" class ="form-control"  value="<?php echo $result[0]['comeback_for']; ?>">
						</div>
					</div>
				</div>
				<!--new2-->
				
                <div class="pull-right">
                    <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                </div>
                <div style="clear:both;"></div>
            </form>
        </div>
    </div>
                
