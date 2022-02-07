<?php $this->load->view('Power/navigation'); ?>
<?php
$textss = "";
if($this->session->selection == "doctor") {
    $textss = "doctor";
    $count = 0;
    $sql = 'SELECT
    p.patient_id,
    p.patient_name,
    p.patient_birthdate,
    p.patient_picture,
    p.parent_id
FROM
    patients_tbl as p
LEFT JOIN
    consultations tb1 ON 
    p.patient_id = tb1.consultation_patient_id

WHERE tb1.interview_id = '.$this->session->id.' AND 
tb1.consultation_patient_id = '.$this->uri->segment(3);

$s = $this->db->query($sql);
if($s->num_rows() > 0) {
    // exist
    $count++;
}

$sql2 = 'SELECT
p.patient_id,
p.patient_name,
p.patient_birthdate,
p.patient_picture,
p.parent_id
FROM
patients_tbl as p
LEFT JOIN
appointments tb1 ON 
p.patient_id = tb1.appointment_patient_id

WHERE tb1.interview_id = '.$this->session->id.' AND 
tb1.appointment_patient_id = '.$this->uri->segment(3);

$s2 = $this->db->query($sql2);
if($s2->num_rows() > 0) {
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
    <div class="panel panel-primary" style="border-radius: 0px; border: 0px;">
        <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
            Add Immunization Record
        </div>
        <div class="panel-body">
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
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
            <form action="<?php echo base_url(). $textss; ?>/addImmunizationRecord/<?php echo $this->uri->segment(3); ?>" method="POST">
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
                    <input type="date" name="dates" class="form-control" required="">
                </div>
				
				<!--new1-->
				<hr>
				
				<div class ="row">
					<div class = "col-xs-4">
						<div class = "form-group">
							<label>Age</label>
							<input type = "text" name="age" class ="form-control">
						</div>
					</div>
					<div class = "col-xs-4">
						<div class = "form-group">
							<label>Weight (kg)</label>
							<input type = "text" name="weight" class ="form-control">
						</div>
					</div>
					<div class = "col-xs-4">
						<div class = "form-group">
							<label>Length (cm)</label>
							<input type = "text" name="length" class ="form-control">
						</div>
					</div>            
				</div>
				
				<div class = "row">
					<div class = "col-xs-6">
						<div class = "form-group">
							<label>BMI (kg/m²)</label>
							<input type = "text" name="bmi" class ="form-control">
						</div>
					</div>
					<div class = "col-xs-6">
						<div class = "form-group">
							<label>Head Circumference</label>
							<input type = "text" name="head_circumference" class ="form-control">
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
                            echo '<option value="'.$row['vaccine_terms_id'].'">'.$row['vaccine_terms_title'].'</option>';
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
                    <input type="text" name="route" class="form-control" required="">
                </div>
				
				<!--new2-->
				<div class = "form-group">
					<label>Doctor's Instruction</label>
					<textarea class ="form-control" name ="doctors_instruction" rows = "3" required =""></textarea>
				</div>

				<hr>
				
				<div class = "row">
					<div class = "col-xs-6">
						<div class = "form-group">
							<label>Please come back on: </label>
							<input type = "date" name="comeback_on" class ="form-control">
						</div>
					</div>
					<div class = "col-xs-6">
						<div class = "form-group">
							<label>for</label>
							<input type = "text" name="comeback_for" class ="form-control">
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
