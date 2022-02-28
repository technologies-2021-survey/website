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
    <h4>Account Lists</h4>
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-primary" style="border-radius:0px;border:0px;">
                <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
                    Administrator
                    <a href="<?php echo base_url().$textss; ?>/addAccount/administrator">
                        <button class="btn btn-success pull-right">
                            Add Administrator Account
                        </button>
                    </a>
                    <div style="clear:both;"></div>
                </div>
                <div class="panel-body">

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <td>Name</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $id = htmlspecialchars($_GET['id']) ? htmlspecialchars($_GET['id']) : 1;
                                $record_per_page = 5;
                                $page = "";
                                if(isset($id)) {
                                    $page = $id;
                                } else {
                                    $page = 1;
                                }
                        
                                $start_from = ($page-1) * $record_per_page;
                        
                                $q = $this->db->query("SELECT * FROM admins_tbl ORDER BY `admin_id` DESC LIMIT $start_from, $record_per_page");
                        
                                if($q->num_rows() > 0) {
                                    foreach($q->result_array() as $row) {
                                        echo '<tr>';
                                            echo '<td>'.$row['admin_id'].'</td>';
                                            echo '<td>'.$row['admin_name'].'</td>';
                                            echo '<td>';
                                                echo '<a href="'.base_url().'/'.$textss.'/editAccount/administrator/'.$row['admin_id'].'" class="btn btn-warning" style="margin-right: 10px;">Edit</a>';
                                                echo '<a href="'.base_url().'/'.$textss.'/deleteAccount/administrator/'.$row['admin_id'].'" class="btn btn-danger">Delete</a>';
                                            echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr>';
                                        echo '<td colspan="3" style="text-align:center;">No results found</td>';
                                    echo '</tr>';
                                }
                            ?>
                            </tbody>
                        </table>
                        <ul class="pager">
                            
                            <?php
                               echo '<li class="previous"><a href="'.base_url().$textss.'/accounts?'.((htmlspecialchars($_GET['id2']) != "" || htmlspecialchars($_GET['id2']) != "0") ? "&id2=".htmlspecialchars($_GET['id2']) : "").'&id='.($id-1).''.(htmlspecialchars($_GET['id3'] != "") ? "&id3=".htmlspecialchars($_GET['id3']) : "").'">&larr; Prev</a></li>';
                               echo '<li class="next"><a href="'.base_url().$textss.'/accounts?'.((htmlspecialchars($_GET['id2']) != "" || htmlspecialchars($_GET['id2']) != "0") ? "&id2=".htmlspecialchars($_GET['id2']) : "").'&id='.($id+1).''.(htmlspecialchars($_GET['id3'] != "") ? "&id3=".htmlspecialchars($_GET['id3']) : "").'">Next &rarr;</a></li>';
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary" style="border-radius: 0px; border: 0px;">
                <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
                    Doctor
                    <a href="<?php echo base_url().$textss; ?>/addAccount/doctor">
                        <button class="btn btn-success pull-right">
                            Add Doctor Account
                        </button>
                    </a>
                    <div style="clear:both;"></div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <td>Name</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $id2 = htmlspecialchars($_GET['id2']) ? htmlspecialchars($_GET['id2']) : 1;
                                $record_per_page2 = 5;
                                $page2 = "";
                                if(isset($id2)) {
                                    $page2 = $id2;
                                } else {
                                    $page2 = 1;
                                }
                        
                                $start_from2 = ($page2-1) * $record_per_page2;
                        
                                $q2 = $this->db->query("SELECT * FROM doctors_tbl ORDER BY `doctor_id` DESC LIMIT $start_from2, $record_per_page2");
                                if($q2->num_rows() > 0) {
                                    foreach($q2->result_array() as $row2) {
                                        echo '<tr>';
                                            echo '<td>'.$row2['doctor_id'].'</td>';
                                            echo '<td>'.$row2['doctor_name'].'</td>';
                                            echo '<td>';
                                                echo '<a href="'.base_url().'/'.$textss.'/editAccount/doctor/'.$row2['doctor_id'].'" class="btn btn-warning" style="margin-right: 10px;">Edit</a>';
                                                echo '<a href="'.base_url().'/'.$textss.'/deleteAccount/doctor/'.$row2['doctor_id'].'" class="btn btn-danger">Delete</a>';
                                            echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr>';
                                        echo '<td colspan="3" style="text-align:center;">No results found</td>';
                                    echo '</tr>';
                                }
                            ?>
                            </tbody>
                        </table>
                        <ul class="pager">
                            
                            <?php
                               echo '<li class="previous"><a href="'.base_url().$textss.'/accounts?'.(($_GET['id'] != "" || $_GET['id'] != "0") ? "&id=".$_GET['id'] : "").'&id2='.($id2-1).''.(($_GET['id3'] != "") ? "&id3=".$_GET['id3'] : "").'">&larr; Prev</a></li>';
                               echo '<li class="next"><a href="'.base_url().$textss.'/accounts?'.(($_GET['id'] != "" || $_GET['id'] != "0") ? "&id=".$_GET['id'] : "").'&id2='.($id2+1).''.(($_GET['id3'] != "") ? "&id3=".$_GET['id3'] : "").'">Next &rarr;</a></li>';
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary" style="border-radius:0px;border:0px;">
                <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
                    Receptionist
                    <a href="<?php echo base_url().$textss; ?>/addAccount/receptionist">
                        <button class="btn btn-success pull-right">
                            Add Receptionist Account
                        </button>
                    </a>
                    <div style="clear:both;"></div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <td>Name</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $id3 = htmlspecialchars($_GET['id3']) ? htmlspecialchars($_GET['id3']) : 1;
                                $record_per_page3 = 5;
                                $page3 = "";
                                if(isset($id3)) {
                                    $page3 = $id3;
                                } else {
                                    $page3 = 1;
                                }
                        
                                $start_from3 = ($page3-1) * $record_per_page3;
                                
                                $q3 = $this->db->query("SELECT * FROM receptionists_tbl ORDER BY `receptionist_id` DESC LIMIT $start_from3, $record_per_page3");
                        
                                if($q3->num_rows() > 0) {
                                    foreach($q3->result_array() as $row3) {
                                        echo '<tr>';
                                            echo '<td>'.$row3['receptionist_id'].'</td>';
                                            echo '<td>'.$row3['receptionist_name'].'</td>';
                                            echo '<td>';
                                                echo '<a href="'.base_url().'/'.$textss.'/editAccount/receptionist/'.$row3['receptionist_id'].'" class="btn btn-warning" style="margin-right: 10px;">Edit</a>';
                                                echo '<a href="'.base_url().'/'.$textss.'/deleteAccount/receptionist/'.$row3['receptionist_id'].'" class="btn btn-danger">Delete</a>';
                                            echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr>';
                                        echo '<td colspan="3" style="text-align:center;">No results found</td>';
                                    echo '</tr>';
                                }
                            ?>
                            </tbody>
                        </table>
                        <ul class="pager">
                            
                            <?php
                               echo '<li class="previous"><a href="'.base_url().$textss.'/accounts?'.(($_GET['id'] != "" || $_GET['id'] != "0") ? "&id=".$_GET['id'] : "").'&id3='.($id3-1).''.(($_GET['id2'] != "") ? "&id2=".$_GET['id2'] : "").'">&larr; Prev</a></li>';
                               echo '<li class="next"><a href="'.base_url().$textss.'/accounts?'.(($_GET['id'] != "" || $_GET['id'] != "0") ? "&id=".$_GET['id'] : "").'&id3='.($id3+1).''.(($_GET['id2'] != "") ? "&id2=".$_GET['id2'] : "").'">Next &rarr;</a></li>';
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>