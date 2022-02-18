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

		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Catamaran:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

        <script type="text/javascript" src="<?=base_url('public/assets/js/jquery-1.10.2.min.js');?>"></script>
		<script type="text/javascript" src="<?=base_url('public/assets/js/bootstrap.min.js');?>"></script>
		<script type="text/javascript" src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<script type="text/javascript" src="https://unpkg.com/scrollreveal"></script>
    </head>
    <body>
        <?php echo view('Home/navigation.php'); ?>
		<?php echo view('Home/content.php'); ?>
		<a id="back-to-top"></a>
		<script>
			var btn = $('#back-to-top');

			$(window).scroll(function() {
				if ($(window).scrollTop() > 300) {
					btn.addClass('show');
				} else {
					btn.removeClass('show');
				}
			});

			btn.on('click', function(e) {
				e.preventDefault();
				$('html, body').animate({scrollTop:0}, '300');
			});

			window.sr = ScrollReveal();
			sr.reveal('.container', {duration: 1000,origin: 'bottom'});
		</script>
    </body>
</html>
