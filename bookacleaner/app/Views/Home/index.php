<?php
    namespace App\Controllers;
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>{elapsed_time} - BookACleaner</title>
		<meta name="description" content="BookACleaner">
		<meta name="keywords" content="BookACleaner">
		<meta name="author" content="Harvey Arboleda">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		
		<link rel="icon" type="image/png" href="<?php echo base_url() . "public/assets/img/whealth.png";?>">
		
		<link rel="stylesheet" href="<?=base_url('public/assets/css/bootstrap.min.css');?>" media="screen" />
		<link rel="stylesheet" href="<?=base_url('public/assets/css/style.css');?>" media="screen" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:400,700" media="screen" />
        <script type="text/javascript" src="<?=base_url('public/assets/js/jquery-1.10.2.min.js');?>"></script>
		<script type="text/javascript" src="<?=base_url('public/assets/js/bootstrap.min.js');?>"></script>
		<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        
    </head>
    <body>
        <?php echo view('Home/navigation.php'); ?>
		<?php echo view('Home/content.php'); ?>
    </body>
</html>
