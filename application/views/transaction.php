<h1>Payment</h1>
<div class = "container-fluid">
    <div class="card">
        <div class="card-header">
            <b>List of Transaction</b>
        </div>
        <div class="card-body">
        <div class = "table-responsive">
        <table class="table table-condensed table-hover align-middle">
                <thead>
                    <th>#</th>
                    <th>Transaction No.</th>
                    <th>Date of Payment</th>
                    <th>Name</th>
                    <th>Mode</th>
                    <th>Service</th>
                    <th>Scheduled Date</th>
                    <th>Payable Fee</th>
                    <th>Paid</th>
                    <th>Balance</th>
                    <th>Status</th>
                </thead>
                <tbody>
                    <?php $i = 1;?>
                    <?php foreach ($items as $item):?>
                        <tr>
                            <td><?php echo $i++ ?></td>
                            <td><?= $item->payments_id;?></td>
                            <td><?php echo  date("M d, Y", strtotime($item->payments_date)) ?></td>
                            <td><?php echo ucwords($item->payments_name)?></td>
                            <td><?php echo ucwords($item->payments_mode)?></td>
                            <td><?php echo ucwords($item->payments_service)?></td>
                            <td><?php echo  date("M d, Y", strtotime($item->payments_scheddate)) ?></td>
                            <td><?php echo number_format($item->payments_payable,2)?></td>
                            <td><?php echo number_format($item->payments_paid,2)?></td>
                            <td><?php
                                    $a = $item->payments_payable;
                                    $b = $item->payments_paid;
                                    echo number_format($a-$b,2)                                    
                                ?>
                            </td>
                            <td><b><?= $item->payments_status;?></b></td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href= "<?= $this->config->base_url()?>doctor/view_payment/<?=$item->payments_id;?>" class="btn btn-outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                        </svg>
                                    </a>
                                    <!-- <a href="<?= $this->config->base_url()?>payment/delete_payment/<?=$item->payments_id;?>" onClick="return confirm('Are you sure you want to delete?')" class="btn btn-outline-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                        </svg>
                                    </a> -->
                                </div>                                       
                            </td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
        </div>
    </div>          
</div>