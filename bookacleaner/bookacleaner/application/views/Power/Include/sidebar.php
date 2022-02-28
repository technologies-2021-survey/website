<?php
// addAccessPrivilege.php
// Power_model.php > setAccessPrivilege()
$textss = "";
$textssss = "";
if($this->session->selection == "doctor") {
    $textss = "doctor";
    $textssss = 1;
} else if($this->session->selection == "receptionist") {
    $textss = "receptionist";
    $textssss = 3;
} else if($this->session->selection == "administrator") {
    $textss = "administrator";
    $textssss = 2;
} 

$textsz = "";
$namesz = "";
$profile_picturesz = "";
if($this->session->selection == "doctor") {
    $textsz = "doctor";
    $getName = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$this->session->id."'")->result_array();
    $namesz = $getName[0]['doctor_name'];
    $profile_picturesz = ($getName[0]['profile_picture'] != "") ? "data:image/png;base64,".base64_encode($getName[0]['profile_picture']) : "https://peekabook.tech/assets/img/whealth.png";
} else if($this->session->selection == "receptionist") {
    $textsz = "receptionist";
    $getName = $this->db->query("SELECT * FROM `receptionists_tbl` WHERE `receptionist_id` = '".$this->session->id."'")->result_array();
    $namesz = $getName[0]['receptionist_name'];
    $profile_picturesz = ($getName[0]['profile_picture'] != "") ? "data:image/png;base64,".base64_encode($getName[0]['profile_picture']) : "https://peekabook.tech/assets/img/whealth.png";
} else if($this->session->selection == "administrator") {
    $textsz = "administrator";
    $getName = $this->db->query("SELECT * FROM `admins_tbl` WHERE `admin_id` = '".$this->session->id."'")->result_array();
    $namesz = $getName[0]['admin_name'];
    $profile_picturesz = ($getName[0]['profile_picture'] != "") ? "data:image/png;base64,".base64_encode($getName[0]['profile_picture']) : "https://peekabook.tech/assets/img/whealth.png";
}
?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo $profile_picturesz; ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p style="margin-bottom: 2px!important;"><?php echo strlen($namesz) > 15 ? substr($namesz,0,15)."..." : $namesz; ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu tree" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li>
          <a href="<?php echo base_url(); echo $textsz; ?>">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>Menu</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          <?php
            $resultAccess = $this->power_model->setAccessPrivilege($this->session->id, $textssss);
            $resultAccessArray = (array) $resultAccess;
    
            $access = array(0,0,0,0,0, 0,0,0,0,0, 0,0,0,0,0, 0,0,0,0,0, 0,0,0,0,0, 0,0,0,0,0, 0,0,0,0,0, 0,0,0,0,0, 0,0,0,0,0,0);
            foreach($resultAccess as $data) {
                $number = $data['category'];
                if($data['category']) {$access[$number] = $data['category']; }
            }
            ?>
            <?php if($access[1] == 0) { echo '<li><a href="'.base_url('/'.$textss.'/inquiries_list').'">Inquiries List(s)</a></li>'; } ?>
            <?php if($access[3] == 0) { echo '<li><a href="'.base_url('/'.$textss.'/appointment').'">Appointment(s)</a></li>'; } ?>
            <?php if($access[4] == 0) { echo '<li><a href="'.base_url('/'.$textss.'/consultation').'">Online Consultation(s)</a></li>'; } ?>
            <?php if($access[5] == 0) { echo '<li><a href="'.base_url('/'.$textss.'/transaction_appointment').'">Transaction (Appointments)</a></li>'; } ?>
            <?php if($access[5] == 0) { echo '<li><a href="'.base_url('/'.$textss.'/transaction_consultation').'">Transaction (Online Consultations)</a></li>'; } ?>
            <?php if($access[5] == 0) { echo '<li><a href="'.base_url('/'.$textss.'/transaction_medical_certificate').'">Transaction (Medical Certificates)</a></li>'; } ?>
            <?php if($access[6] == 0) { echo '<li><a href="'.base_url('/'.$textss.'/immunizationrecord').'">Immunization</a></li>'; } ?>
            <?php if($access[10] == 0) { echo '<li><a href="'.base_url('/'.$textss.'/parents').'">Parent List(s)</a></li>'; } ?>
            <?php if($access[16] == 0) { echo '<li><a href="'.base_url('/'.$textss.'/patients').'">Patient List(s)</a></li>'; } ?>
            <?php if($access[18] == 0) {echo '<li><a href="'.base_url('/'.$textss.'/announcements').'">Announcements List(s)</a></li>'; } ?>
            <?php 
            if($this->session->selection == "administrator" || $this->session->selection == "doctor") {
            ?>
            
              <?php if($access[22] == 0) {echo '<li><a href="'.base_url('/'.$textss.'/patient_satisfaction').'">Patient Satisfactions</a></li>'; } ?>
            <?php
            }
            ?>
            <?php if($access[23] == 0) {echo '<li><a href="'.base_url('/'.$textss.'/terms').'">Pre-defined Terms</a></li>'; } ?>
            <?php echo '<li><a href="'.base_url('/'.$textss.'/clinic_analytics').'">Clinic Analytics</a></li>'; ?>
            <?php
                if($this->session->selection == "administrator") {
            ?>
            <?php if($access[17] == 0) {echo '<li><a href="'.base_url('/'.$textss.'/accessprivilege').'">Access Privilege</a></li>'; } ?>
            <?php if($access[29] == 0) {echo '<li><a href="'.base_url('/'.$textss.'/accounts').'">Account Lists</a></li>'; } ?>

            <?php
                }
            ?>
            <?php if($access[28] == 0) {echo '<li><a href="'.base_url('/'.$textss.'/health_tips').'">Health Tips</a></li>'; } ?>
            <?php if($access[30] == 0) {echo '<li><a href="'.base_url('/'.$textss.'/medical_certificate').'">Medical Certificate</a></li>'; } ?>
            <?php if($access[31] == 0) {echo '<li><a href="'.base_url('/'.$textss.'/laboratory_results').'">Laboratory Results</a></li>'; } ?>
            <?php if($access[32] == 0) {echo '<li><a href="'.base_url('/'.$textss.'/tabular_reports').'">Tabular Reports</a></li>'; } ?>
            <?php echo '<li><a href="'.base_url('/'.$textss.'/doctor_schedule').'">Doctor Schedule</a></li>'; ?>
          </ul>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
</aside>