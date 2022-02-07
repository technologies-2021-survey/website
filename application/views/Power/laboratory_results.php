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
            Laboratory Result(s)
        </div>
        <div class="panel-body">
            <div class="search">
                <?php
                    $textss = "";
                    if($this->session->selection == "doctor") {
                        $textss = "doctor";
                    } else if($this->session->selection == "receptionist") {
                        $textss = "receptionist";
                    } else if($this->session->selection == "administrator") {
                        $textss = "administrator";
                    } 
                    foreach($laboratory_results as $row) {
                        echo '<div class="laboratory-results-'.$row['laboratory_results_id'].'">';
                            echo '<div class="laboratory-results-heading" style="padding: 10px;box-shadow: 0 0 2px #000 inset;">';
                                //echo $row['parent_name'];
			                    echo htmlspecialchars($row['parent_name'])." / <b>".htmlspecialchars($row['patient_name'])."</b> (".date("M d, Y h:iA",$row['timestamp']).")";  
                                if(empty($row['file'])) {
                                    echo '<div class="pull-right">';
                                        echo '<label class="label label-warning">Please upload this PDF</label>';
                                    echo '</div>';
                                }
                                echo '<div style="clear:both;"></div>';
                            echo '</div>';
                            echo '<div class="laboratory-results-body" style="background: rgb(244 244 244);display: none;word-break:break-all;height: 600px;overflow-y: scroll; padding: 20px;">';
                                echo '<b>Parent Name:</b><br/>';
                                echo htmlspecialchars($row['parent_name']);
                                echo '<br/><br/>';
                
                            echo '<label>Patient Name:</label><br/>';
                            echo htmlspecialchars($row['patient_name']);
                            echo '<br/><br/>';

                            echo '<b>Date:</b><br/>';
                            echo htmlspecialchars($row['date']);
                            echo '<br/><br/>';

                            echo '<b>Type of Laboratory:</b> P'.htmlspecialchars($row['type_of_laboratory']);
                            echo '<br/><br/>';

                             // invoke upload_file function and pass your input as a parameter
                             $encoded_string = !empty($_POST['base64_file']) ? $_POST['base64_file'] : 'V2ViZWFzeXN0ZXAgOik=';
                                   

                             if(isset($_POST['submitUpload'])) {
                                 $file = $_POST['file'];
                                 
                                 $filename = $_FILES['file']['name'];
                                 $ext = pathinfo($filename, PATHINFO_EXTENSION);
                                 $allowed = array('pdf');
                                 if( ! in_array( $ext, $allowed ) ) {
                                     redirect($textss."/laboratory_results?error=PDF only!");
                                 }

                                 $_imagePost = file_get_contents($_FILES['file']['tmp_name']);

                                 $id = htmlspecialchars($_POST['id']);
                                 $resultsz = $this->db->query("SELECT * FROM `laboratory_results` WHERE `laboratory_results_id` = '".$id."'");

                                 if($resultsz->num_rows() > 0) {
                                         $this->db->query("UPDATE `laboratory_results` SET `file` = '".base64_encode($_imagePost)."' WHERE laboratory_results_id = '".$id."'");
                                         redirect($textss."/laboratory_results?success=Successfully uploaded!");
                                 } else {
                                     redirect($textss."/laboratory_results?error=Error! There\'s something error!");
                                 }
                             }
                             echo '<hr/>';
                             echo '<div class="row">';
                                 echo '<div class="col-md-6">';
                                         echo '<form action="" method="post" enctype="multipart/form-data">
                                         <input type="hidden" name="id" value="'.$row['laboratory_results_id'].'">
                                         Please upload here the pdf:<br/>
                                         <input type="file" name="file" class="form-control" style="margin-bottom:10px;">
                                         <input type="submit" name="submitUpload" value="Upload" class="btn btn-success">
                                     </form>';
                                 echo '</div>';
                             echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    }

                    echo $links;
                ?>
                <script type="text/javascript">
                    $(document).ready(function() {
                        
                        $('.laboratory-results-heading').click(function() {
                            if ($(this).siblings(".laboratory-results-body").css('display') == 'none') {
                                $(".laboratory-results-body").fadeOut();
                                $(this).siblings(".laboratory-results-body").slideToggle();
                                console.log(0);
                            } else {
                                $(this).siblings(".laboratory-results-body").slideToggle();
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
