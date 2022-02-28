<?php $this->load->view('Power/navigation'); ?>
    <div class="panel panel-primary" style="border-radius: 0px; border: 0px;">
        <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
            Access Privilege
        </div>
        <div class="panel-body">
            <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" class="form-control" class="form-control" style="height: 59px; margin-bottom: -10px;">
                <option value="" selected="">Choose Position</option>
                <option value="?selections=doctor">Doctor</option>
                <option value="?selections=administrator">Administrator</option>
                <option value="?selections=receptionist">Receptionist</option>
            </select>
            <div class="search">

                <?php 
                    foreach($accessprivilege as $data) {
                        $x = "";
                        if($data['selection'] == 1) {
                            //doctor
                            $x .= "<b>".$data['doctor_name']."</b>";
                            $x .= '<a href="'.base_url().'/administrator/addAccessPrivilege/'.$data['doctor_id'].'/'.$data['selection'].'" class="btn btn-info pull-right">Access Privilege</a>';
                            $x .= "<span style='display:block;'>Doctor</span>";
                        } else if($data['selection'] == 2) {
                            // admin
                            $x .= "<b>".$data['admin_name']."</b>";
                            $x .= '<a href="'.base_url().'/administrator/addAccessPrivilege/'.$data['admin_id'].'/'.$data['selection'].'" class="btn btn-info pull-right">Access Privilege</a>';
                            $x .= "<span style='display:block;'>Administrator</span>";
                        } else if($data['selection'] == 3) {
                            // receptionist
                            $x .= "<b>".$data['receptionist_name']."</b>";
                            $x .= '<a href="'.base_url().'/administrator/addAccessPrivilege/'.$data['receptionist_id'].'/'.$data['selection'].'" class="btn btn-info pull-right">Access Privilege</a>';
                            $x .= "<span style='display:block;'>Receptionist</span>";
                        }
                        echo '<div class="well" style="margin-bottom: 10px;">';
                            echo $x;
                            echo '<div style="clear:both;"></div>';
                        echo '</div>';
                    }
                    
                    echo $links;
                ?>
            </div>
        </div>
    </div>