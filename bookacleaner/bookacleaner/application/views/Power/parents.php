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
    <div style="clear:both;"></div>

    <div class="panel panel-primary" style="border-radius: 0px; border: 0px;">
        <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
            Parent List
            <div class="pull-right">
                <a href="<?php echo base_url().$textss; ?>/addParents" class="btn btn-primary" style="font-size:14px;">Add Parent</a>
            </div>
            <div style="clear:both;"></div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">

                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Parent Name</th>
							
<!-- 			test -->
			<th> Email </th>
							
                            <th>Phone Number</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(isset($_GET['disableParent'])){
                            if(is_numeric($_GET['disableParent']) == 1) {
                                // success

                                $q = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$_GET['disableParent']."'");
                                if($q->num_rows() > 0) {
                                    // success
                                    $array = array("verified"=>0);
                                    $this->db->where("parent_id" , $_GET['disableParent']);
                                    $this->db->update("parents_tbl", $array);
                                    redirect($textss."/parents?success=Successfully disabled!");
                                } else {
                                    // error
                                    redirect($textss."/parents?error=That parent account doesn\'t exist!");
                                }
                            } else {
                                //error
                                redirect($textss."/parents?error=There\'s something wrong!");
                            }
                        }

                        if(isset($_GET['approveParent'])){
                            if(is_numeric($_GET['approveParent']) == 1) {
                                // success

                                $q = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$_GET['approveParent']."'");
                                if($q->num_rows() > 0) {
                                    // success
                                    $array = array("verified"=>1);
                                    $this->db->where("parent_id" , $_GET['approveParent']);
                                    $this->db->update("parents_tbl", $array);
                                    redirect($textss."/parents?success=Successfully activated!");
                                } else {
                                    // error
                                    redirect($textss."/parents?error=That parent account doesn\'t exist!");
                                }
                            } else {
                                //error
                                redirect($textss."/parents?error=There\'s something wrong!");
                            }
                        }

                        if(isset($_GET['deactParent'])){
                            if(is_numeric($_GET['deactParent']) == 1) {
                                // success

                                $q = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$_GET['deactParent']."'");
                                if($q->num_rows() > 0) {
                                    // success
                                    $array = array("verified"=>2);
                                    $this->db->where("parent_id" , $_GET['deactParent']);
                                    $this->db->update("parents_tbl", $array);
                                    redirect($textss."/parents?success=Successfully deactivated!");
                                } else {
                                    // error
                                    redirect($textss."/parents?error=That parent account doesn\'t exist!");
                                }
                            } else {
                                //error
                                redirect($textss."/parents?error=There\'s something wrong!");
                            }
                        }

                        if(isset($_GET['reactParent'])){
                            if(is_numeric($_GET['reactParent']) == 1) {
                                // success

                                $q = $this->db->query("SELECT * FROM `parents_tbl` WHERE `parent_id` = '".$_GET['reactParent']."'");
                                if($q->num_rows() > 0) {
                                    // success
                                    $array = array("verified"=>1);
                                    $this->db->where("parent_id" , $_GET['reactParent']);
                                    $this->db->update("parents_tbl", $array);
                                    redirect($textss."/parents?success=Successfully reactivated!");
                                } else {
                                    // error
                                    redirect($textss."/parents?error=That parent account doesn\'t exist!");
                                }
                            } else {
                                //error
                                redirect($textss."/parents?error=There\'s something wrong!");
                            }
                        }
                        
                        foreach($parents as $row) {
                            echo '<tr class="parents-'.$row['parent_id'].'">';
                                echo '<td>'.htmlspecialchars($row['parent_id']).'</td>';
                                echo '<td>'.htmlspecialchars($row['parent_name']).'</td>';
							
// 							test
							echo '<td>'.htmlspecialchars($row['parent_emailaddress']).'</td>';
							
                                echo '<td>'.htmlspecialchars($row['parent_phonenumber']).'</td>';
                                if($row['verified'] == 1) {
                                    echo '<td>Approved</td>';
                                } else if($row['verified'] == 0) {
                                    echo '<td>Disabled</td>';
                                } else if($row['verified'] == 2) {
                                    echo '<td>Deactivated</td>';
                                }
                                echo '<td>';
                                
                                
                                echo '<a href="'.base_url().$textss.'//editParent/'.$row['parent_id'].'" class="btn btn-primary" style="margin-right:10px;">Edit Parent</a>';
                                echo '<a href="'.base_url().$textss.'//addChild/'.$row['parent_id'].'" class="btn btn-primary" style="margin-right:10px;">Add Child</a>';
                                echo '<a href="'.base_url().$textss.'//listChild/'.$row['parent_id'].'" class="btn btn-info" style="margin-right:10px;">Child List</a>';

                                if($row['verified'] == 1) {
                                    echo '<a href="?disableParent='.$row['parent_id'].'" class="btn btn-success" style="margin-right:10px;">Disable Account</a>';
                                } else if($row['verified'] == 0) {
                                    echo '<a href="?approveParent='.$row['parent_id'].'" class="btn btn-danger" style="margin-right:10px;">Approve Account</a>';
                                }

                                if($row['verified'] == 1) {
                                    echo '<a href="?deactParent='.$row['parent_id'].'" class="btn btn-danger">Deactivate Account</a>';
                                } else if($row['verified'] == 2) {
                                    echo '<a href="?reactParent='.$row['parent_id'].'" class="btn btn-success">Activate Account</a>';
                                }
                                echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
                
            </div>

            <?php echo $links; ?>
        </div>
    </div>
