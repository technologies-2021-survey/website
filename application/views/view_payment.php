<?php 
    $item = $items[0];
?>
<h1>View Payment</h1>
<div class = "container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-auto me-auto"><b>Patient Transaction</b></div>
                <div class="col-auto">
                    <a href = "<?=$this->config->base_url()?>doctor/payment" class = "btn btn-secondary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
                        <path d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1z"/>
                        </svg> Transaction List
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class = "container">

                <form action="<?=$this->config->base_url()?>doctor/update_payment/<?=$item->payments_id?>" method="POST">

                <?php foreach ($items as $item): ?>
                    <!-- 1 -->
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Transaction ID:</label>
                        <div class="col-sm-5">                                
                        <input type="text" readonly class="form-control-plaintext" value="<?= $item->payments_id;?>">
                        </div>
                    </div>           
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Payment Date:</label>
                        <div class="col-sm-5">                                
                        <input type="text" readonly class="form-control-plaintext" value="<?php echo  date("M d, Y H:i A", strtotime($item->payments_date)) ?>">
                        </div>
                    </div>
                    <!-- 2 -->
                    <hr>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Scheduled Date:</label>
                        <div class="col-sm-5">                                
                        <input type="text" readonly class="form-control-plaintext" value="<?php echo  date("M d, Y H:i A", strtotime($item->payments_scheddate)) ?>">
                        </div>
                    </div>
                    <!-- 3 -->           
                    <hr>             
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Patient Name:</label>
                        <div class="col-sm-5">                                
                        <input type="text" readonly class="form-control-plaintext" value="<?php echo ucwords($item->payments_name)?>">
                        </div>
                    </div>  
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Mode:</label>
                        <div class="col-sm-4">                                
                        <input type="text" readonly class="form-control" id="mymode2" name="mode" value="<?php echo ucwords($item->payments_mode)?>" onchange="changeMode(this.value)">
                        </div>

                        <label class="col-sm-2 col-form-label">Change of Mode:</label>
                        <div class="col-sm-4">  
                            <select class="form-select" id="myMode" onchange="changeMode()">
                                <option value="---">---</option>
                                <option value="Appointment">Appointment</option>
                                <option value="Online Consultation">Online Consultation</option>
                            </select>
                            <script>
                            function changeMode(cs){
                                var cs = document.getElementById("myMode").value;
                                document.getElementById("mymode2").value= cs;
                            }
                        </script>                                    
                        </div>                                
                    </div>  

                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Service:</label>
                        <div class="col-sm-4">                                
                        <input type="text" readonly class="form-control" id="myservice2" name="service" value="<?php echo ucwords($item->payments_service)?>" onchange="changeService(this.value)">
                        </div>

                        <label class="col-sm-2 col-form-label">Change of Service:</label>
                        <div class="col-sm-4">  
                            <select class="form-select" id="myService" onchange="changeService()">
                                <option value="---">---</option>
                                <option value="Check-up">Check-up</option>
                                <option value="Vaccination">Vaccination</option>
                                <option value="Counselling">Counselling</option>
                                <option value="Other">Other</option>
                            </select>
                            <script>
                            function changeService(cs){
                                var cs = document.getElementById("myService").value;
                                document.getElementById("myservice2").value= cs;
                            }
                        </script>                                    
                        </div>                                
                    </div> 
                    <!-- 4-->
                    <hr>
                    <div class="mb-3 row">
                        <label class="col-sm-1 col-form-label">Payable:</label>
                        <div class="col-sm-2">                                
                        <input type="text" readonly class="form-control" id = "payable" value="<?= $item->payments_payable?>" onchange="adjustPaid()">
                        </div>

                        
                    </div>  

                    <div class="mb-3 row">
                        <label class="col-sm-1 col-form-label">Paid:</label>
                        <div class="col-sm-2">                                
                        <input type="text" readonly class="form-control" id="paid2" name="paid" value="<?= $item->payments_paid?>">
                        </div>

                        <label class="col-sm-2 col-form-label">Adjust Paid:</label>
                        <div class="col-sm-2">                                
                        <input type="text" class="form-control" id ="paid"  onchange="adjustPaid()">
                        </div>

                        <script>
                            function adjustPaid(ap){
                                var ap = document.getElementById("paid").value; //paid
                                var pf = document.getElementById("payable").value; //payable fee

                                document.getElementById("paid2").value = ap;

                                var bal = pf - ap; //balance
                                document.getElementById("balance2").value = bal;
                                
                            }
                        </script>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-1 col-form-label">Balance:</label>
                        <div class="col-sm-2">                 
                                <?php
                                    $a = $item->payments_payable;
                                    $b = $item->payments_paid;
                                    $balance = $a-$b;                                    
                                ?>               
                            <input type="text" readonly class="form-control" id ="balance2" value="<?=$balance?>"onchange="adjustPaid()">
                        </div>
                    </div>

                    <!-- 5janew.css-->
                    <hr>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Proof of Payment:</label>
                        <div class="col-sm-5">                                
                        <input type="text" readonly class="form-control-plaintext" value="<?= $item->payments_proof;?>">
                        </div>
                    </div> 

                    <!-- 6 -->
                    <hr>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Payment Status:</label>
                        <div class="col-sm-2">                                
                        <input type="text" readonly class="form-control" id="mystatus2" name="status" value="<?= $item->payments_status;?>" onchange="changeStatus(this.value)">
                        </div>
                    </div> 
                    <div class = "mb-3 row">
                    <label class="col-sm-3 col-form-label">Change Payment Status:</label>
                        <div class="col-sm-2">  
                            <select class="form-select" id="myStatus" onchange="changeStatus()">
                                <option value="---">---</option>
                                <option value="Pending">Pending</option>
                                <option value="Accepted">Accepted</option>
                                <option value="Denied">Denied</option>
                            </select>
                            <script>
                            function changeStatus(x){
                                var x = document.getElementById("myStatus").value;
                                document.getElementById("mystatus2").value= x;
                            }
                        </script>                                    
                        </div>
                    </div>
                    <hr>
                    <div class="d-grid gap-2 col-6 mx-auto">
                        <button class="btn btn-success" type="submit" onClick="return confirm('Confirm Changes')">Update Status</button>
                    </div>
                <?php endforeach;?>

                </form>

            </div>
        </div>
    </div>          
</div> 