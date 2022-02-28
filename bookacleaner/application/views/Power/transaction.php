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
            <?php echo $title; ?>
        </div>
        <div class="panel-body">
            <div class="pull-right" style="margin-bottom: 10px;">
                <?php
                    if($title == "Transaction (Appointment)") {
                        
                        echo '<a href="#" class="btn btn-success" onclick="window.open(\''.base_url().$textss.'//getTransactionAppointmentPDF//'.$pdf_per_page.'//'.$pdf_page.'\')">Download PDF</a>';
                    } else {
                        echo '<a href="#" class="btn btn-success" onclick="window.open(\''.base_url().$textss.'//getTransactionConsultationPDF//'.$pdf_per_page.'//'.$pdf_page.'\')">Download PDF</a>';
                    }
                ?>
            </div>
            <div style="clear:both;"></div>
            <?php
            
                foreach($transactions as $row) {
                    echo '<div class="transaction-'.$row['overallId'].'">';
                            echo '<div class="transaction-heading" style="padding: 10px;box-shadow: 0 0 2px #000 inset;border-radius:10px;cursor:pointer;">';
//                                 echo $row['parent_name'];
//                                 echo ' / ';
//                                 echo ucfirst($row['category']);
				echo htmlspecialchars($row['parent_name'])." / ".ucfirst(htmlspecialchars($row['category']))." | <b> Ref no: </b>(<i>".htmlspecialchars($row['reference_number'])."</i>)";
				
                                echo '<div class="pull-right">';
                                    echo '<label class="label label-info">';
                                        echo '<b>P'.htmlspecialchars($row['money']).'</b>';
                                    echo '</label>';
                                echo '</div>';
                                echo '<div style="clear:both;"></div>';
                            echo '</div>';
                            echo '<div class="transaction-body"  style="background: rgb(244 244 244);display: none;word-break:break-all;height: 600px;overflow-y: scroll; padding: 20px;">';
                            
                                echo '<b>Parent Name:</b><br/>';
                                echo htmlspecialchars($row['parent_name']);
                                echo '<br/><br/>';
                                echo '<b>Patient Name:</b><br/>';
                                echo htmlspecialchars($row['patient_name']);
                                echo '<br/><br/>';
                                echo '<b>Date Start:</b><br/>';
                                echo htmlspecialchars($row['datetime']);
                                echo '<br/><br/>';
                                echo '<b>Date End:</b><br/>';
                                echo htmlspecialchars($row['datetime_end']);
                                echo '<br/><br/>';
                                echo '<b>Reference Number:</b><br/>';
                                echo htmlspecialchars($row['reference_number']);
                                echo '<br/><br/>';
                                echo '<b>Amount:</b><br/>P';
                                echo htmlspecialchars($row['money']);
                                echo '<br/><br/>';
                                if(htmlspecialchars($row['category']) == "appointments") {
                                    if(htmlspecialchars($row['description']) != "") {
                                        echo '<b>Description:</b><br/>';
                                        echo htmlspecialchars($row['description']);
                                        echo '<br/><br/>';
                                    }
                                } else if(htmlspecialchars($row['category']) == "consultations") {
                                    if(htmlspecialchars($row['prescription']) != "") {
                                        echo '<b>Prescriptions:</b><br/>';
                                        echo htmlspecialchars($row['prescription']);
                                        echo '<br/><br/>';
                                    }
                                    if(htmlspecialchars($row['reason']) != "") {
                                        echo '<b>Reason:</b><br/>';
                                        echo htmlspecialchars($row['reason']);
                                        echo '<br/><br/>';
                                    }
                                } 
                                echo '<b>Status:</b><br/>';
                                echo htmlspecialchars($row['status']);
                            echo '</div>';
                    echo '</div>';
                }
            ?>
            <?php echo $links; ?>
            <script type="text/javascript">
            $(document).ready(function() {

                $('.transaction-heading').click(function() {
                    if ($(this).siblings(".transaction-body").css('display') == 'none') {
                        $(".transaction-body").fadeOut();
                        $(this).siblings(".transaction-body").slideToggle();
                        console.log(0);
                    } else {
                        $(this).siblings(".transaction-body").slideToggle();
                    }
                });
                $('.pot').click(function() {
                    $('#viewModal').modal('show');
                    var image = $(this).attr("src");
                    $("#POT").attr("src", image);
                });
            });
            </script>
            <!-- Modal -->
            <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewModalLabel">Proof of Transaction</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <img src="#" id="POT"class="img-responsive"  style="width:100%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
