<?php $this->load->view('Power/navigation'); ?>

    <div class="panel panel-primary" style="border-radius: 0px; border: 0px;">
        <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
            Add Access Privilege / <?php echo $name; ?>
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
            $count = 1;
            $arraysz = array(
                'View Inquiry', // 1 @
                'Delete Inquiry', // 2
                'View Appointment', // 3 @
                'View Consultation', // 4 @
                'View Transaction', // 5 @
                'View Immunization Record', // 6 @
                'Add Immunization Record', // 7
                'Edit Immunization Record', // 8
                'Delete Immunization Record', // 9
                'View Parent List', // 10 @
                'Add Parent', // 11
                'View Child List', // 12 
                'Add Child', // 13
                'Edit Child', // 14
                'Delete Child', // 15
                'Patient List', // 16 @
                'Access Privilege', // 17
                'View Announcement', // 18
                'Add Announcement', // 19
                'Delete Announcement', // 20
                'Edit Announcement', // 21
                'Patient Satisfaction', //22
                'Management',//23
                'View Pediatric Chart', //24
                'Add Pediatric Chart', //25
                'Edit Pediatric Chart', //26
                'Delete Pediatric Chart', //27
                'Health Tips', // 28
                'Account List', // 29
                'Medical Certificate', // 30
                'Laboratory Result', // 31
                'Tabular Reports' //32
            );
            ?>
            <style type="text/css">select.form-control { width: 15%; }</style>
            <?php //echo var_dump($user); ?>
            <form action="<?php echo base_url() . $textss; ?>/addAccessPrivilege/<?php echo $idd; ?>/<?php echo $selectionn;?>" method="POST">
                <?php
                for($i=0;$i<33;$i++){
                    if($arraysz[$i] != "") {
                        echo '<div class="form-group">';
                            echo '<label>'.($i+1).'. '.$arraysz[$i].'</label>';
                            echo '<select class="form-control" required=""  name="add[]">';
                                if($this->power_model->getAccessPrivilegeRow($idd, $selectionn, ($i+1)) == 1) {
                                    // exist, off
                                    echo '<option value="OFF" selected>OFF</option>';
                                    echo '<option value="ON">ON</option>';
                                } else {
                                    // not exist, on
                                    echo '<option value="ON" selected>ON</option>';
                                    echo '<option value="OFF">OFF</option>';
                                }
                                
                            echo '</select>';
                        echo '</div>';
                    }
                }
                ?>
                <div class="pull-right">
                    <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                </div>
                <div style="clear:both;"></div>
            </form>
        </div>
    </div>