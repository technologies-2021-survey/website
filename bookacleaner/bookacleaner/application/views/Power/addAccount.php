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
    if($type == "doctor" || $type == "receptionist" || $type == "administrator") {
        if(isset($_POST['submit'])) {
            $name = htmlspecialchars($_POST['name']);
            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);
            $emailaddress = htmlspecialchars($_POST['emailaddress']);
            $phonenumber = htmlspecialchars($_POST['phonenumber']);
            
            if($type == "administrator") {
                
                $checkUsername = $this->db->query("SELECT * FROM admins_tbl WHERE `admin_username` = '".$username."'");
                if($checkUsername->num_rows() > 0) {
                    // exist
                    $error .= "<div>That username already exist!</div>";
                }

                $checkEmail = $this->db->query("SELECT * FROM admins_tbl WHERE `admin_emailaddress` = '".$emailaddress."'");
                if($checkEmail->num_rows() > 0) {
                    // exist
                    $error .= "<div>That email address already exist!</div>";
                }
                if($error == "") {
                    $array = array(
                        "admin_name" => $name,
                        "admin_username" => $username,
                        "admin_password" => md5($password),
                        "admin_emailaddress" => $emailaddress,
                        "admin_phonenumber" => $phonenumber,
                    );
                    $this->db->insert('admins_tbl', $array);
                    redirect("administrator/accounts?success=Successfully!");
                }
            } else if($type == "doctor") {
                $ptr = htmlspecialchars($_POST['ptr']);
                $license_no = htmlspecialchars($_POST['license_no']);

                $checkUsername = $this->db->query("SELECT * FROM doctors_tbl WHERE `doctor_username` = '".$username."'");
                if($checkUsername->num_rows() > 0) {
                    // exist
                    $error .= "<div>That username already exist!</div>";
                }

                $checkEmail = $this->db->query("SELECT * FROM doctors_tbl WHERE `doctor_emailaddress` = '".$emailaddress."'");
                if($checkEmail->num_rows() > 0) {
                    // exist
                    $error .= "<div>That email address already exist!</div>";
                }
                if($error == "") {
                    $array = array(
                        "doctor_name" => $name,
                        "doctor_username" => $username,
                        "doctor_password" => md5($password),
                        "doctor_emailaddress" => $emailaddress,
                        "doctor_phonenumber" => $phonenumber,
                        "ptr" => $ptr,
                        "license_no" => $license_no
                    );
                    $this->db->insert('doctors_tbl', $array);
                    redirect("administrator/accounts?success=Successfully!");
                }
            } else if($type == "receptionist") {
                $checkUsername = $this->db->query("SELECT * FROM receptionists_tbl WHERE `receptionist_username` = '".$username."'");
                if($checkUsername->num_rows() > 0) {
                    // exist
                    $error .= "<div>That username already exist!</div>";
                }

                $checkEmail = $this->db->query("SELECT * FROM receptionists_tbl WHERE `receptionist_emailaddress` = '".$emailaddress."'");
                if($checkEmail->num_rows() > 0) {
                    // exist
                    $error .= "<div>That email address already exist!</div>";
                }

                if($error == "") {
                    $array = array(
                        "receptionist_name" => $name,
                        "receptionist_username" => $username,
                        "receptionist_password" => md5($password),
                        "receptionist_emailaddress" => $emailaddress,
                        "receptionist_phonenumber" => $phonenumber,
                    );
                    $this->db->insert('receptionists_tbl', $array);
                    redirect("administrator/accounts?success=Successfully!");
                }
            }
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
            Add Account for <?php echo ucfirst($type); ?>
        </div>
        <div class="panel-body">
            <form action="<?php echo base_url() . $textss; ?>/addAccount/<?php echo $type; ?>" method="POST" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Name" autocomplete="off" required="">
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Username" autocomplete="off" required="">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" autocomplete="off" required="">
                </div>
                <div class="form-group">
                    <label for="emailaddress">E-mail Address</label>
                    <input type="email" id="emailaddress" name="emailaddress" class="form-control" placeholder="E-mail Address (xxxxxx@gmail.com)" autocomplete="off" required="">
                </div>

                <div class="form-group">
                    <label for="phonenumber">Phone Number</label>
                    <input type="phonenumber" id="phonenumber" name="phonenumber" class="form-control" placeholder="Phone Number (09123456789)" pattern="^(09|\+639)\d{9}$" autocomplete="off" required="">
                </div>
                <?php
                if($type == "doctor") {
                ?>
                <div class="form-group">
                    <label for="ptr">PTR</label>
                    <input type="text" id="ptr" name="ptr" class="form-control" placeholder="PTR" autocomplete="off" required="">
                </div>

                <div class="form-group">
                    <label for="license_no">License No.</label>
                    <input type="text" id="license_no" name="license_no" class="form-control" placeholder="License No." autocomplete="off" required="">
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