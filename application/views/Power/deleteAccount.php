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
<?php
if($type == "doctor" || $type == "receptionist" || $type == "administrator" && $id != "") {
        
    if($type == "administrator") {
        $this->db->where('admin_id', $id);
        $this->db->delete('admins_tbl');
        redirect("administrator/accounts?success=Successfully!");
    } else if($type == "doctor") {
        $this->db->where('doctor_id', $id);
        $this->db->delete('doctors_tbl');
        redirect("administrator/accounts?success=Successfully!");
    } else if($type == "receptionist") {
        $this->db->where('receptionist_id', $id);
        $this->db->delete('receptionists_tbl');
        redirect("administrator/accounts?success=Successfully!");
    }
    
} else {
    redirect("administrator/accounts?error=Error! There\'s something wrong!");
}
?>