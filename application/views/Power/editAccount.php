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
$error = "";
?>
    <?php
    if($type == "doctor" || $type == "receptionist" || $type == "administrator" && $id != "") {
        if(isset($_POST['submit'])) {
            $name = htmlspecialchars($_POST['name']);
            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);
            $emailaddress = htmlspecialchars($_POST['emailaddress']);
            $phonenumber = htmlspecialchars($_POST['phonenumber']);
             
            if($type == "administrator") {
                
                $checkUsername = $this->db->query("SELECT * FROM admins_tbl WHERE `admin_username` = '".$username."'");
                if($checkUsername->num_rows() > 0) {
                    $checkUsernameData = $checkUsername->result_array();

                    if($checkUsernameData[0]['admin_id'] != $id) {
                        // exist
                        $error .= "<div>That username already exist!</div>";
                    }
                }

                $checkEmail = $this->db->query("SELECT * FROM admins_tbl WHERE `admin_emailaddress` = '".$emailaddress."'");
                if($checkEmail->num_rows() > 0) {
                    $checkEmailData = $checkEmail->result_array();

                    if($checkEmailData[0]['admin_id'] != $id) {
                        // exist
                        $error .= "<div>That email already exist!</div>";
                    }
                }
                if($error == "") {
                    $array = array(
                        "admin_name" => $name,
                        "admin_username" => $username,
                        "admin_emailaddress" => $emailaddress,
                        "admin_phonenumber" => $phonenumber,
                    );

                    if($password != "") {
                        $array["admin_password"] = md5($password);
                    }
                    
                    $this->db->where('admin_id', $id);
                    $this->db->update('admins_tbl', $array);
                    redirect("administrator/accounts?success=Successfully!");
                }
            } else if($type == "doctor") {
                $ptr = htmlspecialchars($_POST['ptr']);
                $license_no = htmlspecialchars($_POST['license_no']);

                $checkUsername = $this->db->query("SELECT * FROM doctors_tbl WHERE `doctor_username` = '".$username."'");
                if($checkUsername->num_rows() > 0) {
                    $checkUsernameData = $checkUsername->result_array();

                    if($checkUsernameData[0]['doctor_id'] != $id) {
                        // exist
                        $error .= "<div>That username already exist!</div>";
                    }
                }

                $checkEmail = $this->db->query("SELECT * FROM doctors_tbl WHERE `doctor_emailaddress` = '".$emailaddress."'");
                if($checkEmail->num_rows() > 0) {
                    $checkEmailData = $checkEmail->result_array();

                    if($checkEmailData[0]['doctor_id'] != $id) {
                        // exist
                        $error .= "<div>That email already exist!</div>";
                    }
                
                }
                if($error == "") {
                    $array = array(
                        "doctor_name" => $name,
                        "doctor_username" => $username,
                        "doctor_emailaddress" => $emailaddress,
                        "doctor_phonenumber" => $phonenumber,
                        "ptr" => $ptr,
                        "license_no" => $license_no
                    );
                    if($password != "") {
                        $array["doctor_password"] = md5($password);
                    }
                    $this->db->where('doctor_id', $id);
                    $this->db->update('doctors_tbl', $array);
                    redirect("administrator/accounts?success=Successfully!");
                }
            } else if($type == "receptionist") {
                $checkUsername = $this->db->query("SELECT * FROM receptionists_tbl WHERE `receptionist_username` = '".$username."'");
                if($checkUsername->num_rows() > 0) {
                    $checkUsernameData = $checkUsername->result_array();

                    if($checkUsernameData[0]['receptionist_id'] != $id) {
                        // exist
                        $error .= "<div>That username already exist!</div>";
                    }
                }

                $checkEmail = $this->db->query("SELECT * FROM receptionists_tbl WHERE `receptionist_emailaddress` = '".$emailaddress."'");
                if($checkEmail->num_rows() > 0) {
                    // exist
                    $checkEmailData = $checkEmail->result_array();

                    if($checkEmailData[0]['receptionist_id'] != $id) {
                        // exist
                        $error .= "<div>That email already exist!</div>";
                    }
                }

                if($error == "") {
                    $array = array(
                        "receptionist_name" => $name,
                        "receptionist_username" => $username,
                        "receptionist_emailaddress" => $emailaddress,
                        "receptionist_phonenumber" => $phonenumber,
                    );
                    if($password != "") {
                        $array["receptionist_password"] = md5($password);
                    }
                    $this->db->where('receptionist_id', $id);
                    $this->db->update('receptionists_tbl', $array);
                    redirect("administrator/accounts?success=Successfully!");
                }
            }
        }

        $name = "";
        $username = "";
        $emailaddress = "";
        $phonenumber = "";
        $ptr = "";
        $license_no = "";
        if($type == "doctor") {
            $getData = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$id."'")->result_array();
            $name = $getData[0]['doctor_name'];
            $username = $getData[0]['doctor_username'];
            $emailaddress = $getData[0]['doctor_emailaddress'];
            $phonenumber = $getData[0]['doctor_phonenumber'];
            $ptr = $getData[0]['ptr'];
            $license_no = $getData[0]['license_no'];
        } else if($type == "receptionist") {
            $getData = $this->db->query("SELECT * FROM `receptionists_tbl` WHERE `receptionist_id` = '".$id."'")->result_array();
            $name = $getData[0]['receptionist_name'];
            $username = $getData[0]['receptionist_username'];
            $emailaddress = $getData[0]['receptionist_emailaddress'];
            $phonenumber = $getData[0]['receptionist_phonenumber'];
        } else if($type == "administrator") {
            $getData = $this->db->query("SELECT * FROM `admins_tbl` WHERE `admin_id` = '".$id."'")->result_array();
            $name = $getData[0]['admin_name'];
            $username = $getData[0]['admin_username'];
            $emailaddress = $getData[0]['admin_emailaddress'];
            $phonenumber = $getData[0]['admin_phonenumber'];
        }
    } else {
        redirect("administrator/accounts?error=Error! There\'s something wrong!");
    }
    ?>
    <?php
    if($error!="") {
        echo '<div class="alert alert-danger">';
            echo $error;
        echo '</div>';
    }
    ?>
    <div class="panel panel-primary" style="border-radius: 0px; border: 0px;">
        <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
            Edit <?php echo ucfirst($type); ?> Account
        </div>
        <div class="panel-body">
            <form action="<?php echo base_url() . $textss; ?>/editAccount/<?php echo $type; ?>/<?php echo $id; ?>" method="POST" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Name" value="<?php echo $name; ?>" autocomplete="off" required="">
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Username" value="<?php echo $username; ?>" autocomplete="off" required="">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" autocomplete="off">
                    <label style="font-size: 12px;font-style:italic;font-weight: normal;color:red;">Note: The password field will only be saved if it is not empty.</label>
                </div>
                <div class="form-group">
                    <label for="emailaddress">E-mail Address</label>
                    <input type="email" id="emailaddress" name="emailaddress" class="form-control" placeholder="E-mail Address (xxxxxx@gmail.com)" value="<?php echo $emailaddress; ?>" autocomplete="off" required="">
                </div>

                <div class="form-group">
                    <label for="phonenumber">Phone Number</label>
                    <input type="phonenumber" id="phonenumber" name="phonenumber" class="form-control" placeholder="Phone Number (09123456789)" value="<?php echo $phonenumber; ?>" pattern="^(09|\+639)\d{9}$" autocomplete="off" required="">
                </div>
                <?php
                if($type == "doctor") {
                ?>
                <div class="form-group">
                    <label for="ptr">PTR</label>
                    <input type="text" id="ptr" name="ptr" class="form-control" placeholder="PTR" value="<?php echo $ptr; ?>" autocomplete="off" required="">
                </div>

                <div class="form-group">
                    <label for="license_no">License No.</label>
                    <input type="text" id="license_no" name="license_no" class="form-control" placeholder="License No." value="<?php echo $license_no; ?>" autocomplete="off" required="">
                </div>

                <?php  
                }
                ?>
                <br>
                <div class="pull-right">
                    <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                </div>
                <div style="clear:both;"></div>
            </form>
        </div>
    </div>
