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
    <div class="panel panel-primary" style="border-radius: 0px; border: 0px;">
        <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
            Announcements List
            <div class="pull-right">
                <a href="<?php echo base_url().$textss; ?>/addAnnouncements" class="btn btn-primary" style="font-size: 14px;">Add Announcement</a>
            </div>
        </div>
        <div class="panel-body">
    
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date/Time</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Image</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($announcements as $row) {
                            echo '<tr class="patients-'.htmlspecialchars($row['announcement_tbl_id']).'">';
                                echo '<td>'.htmlspecialchars($row['announcement_tbl_id']).'</td>';
                                echo '<td>'.date("m-d-Y h:i:sa", $row['announcement_tbl_date']).'</td>';
                                echo '<td>'.htmlspecialchars($row['announcement_tbl_title']).'</td>';
                                echo '<td><pre style="width: 500px;overflow: auto;">'.htmlspecialchars($row['announcement_tbl_content']).'</pre></td>';
                                if($row['announcement_tbl_image'] != "") {
                                    echo '<td><img src="data:image/png;base64,'.$row['announcement_tbl_image'].'" class="img-thumbnail" style="width: 100px;"/></td>';
                                } else {
                                    echo '<td>No image uploaded.</td>';
                                }
                                echo '<td>';
                                    echo '<a href="'.base_url().$textss.'//editAnnouncements/'.htmlspecialchars($row['announcement_tbl_id']).'" class="btn btn-warning" style="margin:10px;">Edit Announcement</a>';
                                    //echo '<a href="'.base_url().$textss.'//viewAnnouncements/'.$row['announcement_tbl_id'].'" class="btn btn-warning" style="margin-right:10px;">View Announcements</a>';
                                    echo '<a href="'.base_url().$textss.'//deleteAnnouncements/'.htmlspecialchars($row['announcement_tbl_id']).'" class="btn btn-danger" style="margin:10px;">Delete Announcement</a>';
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
