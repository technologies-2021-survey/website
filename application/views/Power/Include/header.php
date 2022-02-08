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
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?=$title;?></title>
		<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">	
		
		<link rel="icon" type="image/png" href="<?php echo base_url() . "assets/img/whealth.png";?>"/>
		
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,800,700,400italic,600italic,700italic,800italic,300italic" rel="stylesheet" type="text/css">
		
		
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
		<!-- Bootstrap 3.3.7 -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>bower_components/font-awesome/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>bower_components/Ionicons/css/ionicons.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/AdminLTE.min.css">
		<!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/skins/skin-blue.min.css">
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="<?php echo base_url(); ?>bower_components/html5shiv/dist/html5shiv.js"></script>
			<script src="<?php echo base_url(); ?>bower_components/respond/dest/respond.min.js"></script>
		<![endif]-->
		<!-- jQuery 3 -->
		<script src="<?php echo base_url(); ?>bower_components/jquery/dist/jquery.min.js"></script>
		<!-- Bootstrap 3.3.7 -->
		<script src="<?php echo base_url(); ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<!-- FastClick -->
		<script src="<?php echo base_url(); ?>bower_components/fastclick/lib/fastclick.js"></script>
		<!-- AdminLTE App -->
		<script src="<?php echo base_url(); ?>dist/js/adminlte.min.js"></script>
		
		<style type="text/css">/* ===== Scrollbar CSS ===== */ /* Firefox */ * { scrollbar-width: auto; scrollbar-color: #3c8dbc #d6d6d6; } /* Chrome, Edge, and Safari */ *::-webkit-scrollbar { width: 11px; } *::-webkit-scrollbar-track { background: #d6d6d6; } *::-webkit-scrollbar-thumb { background-color: #3c8dbc; border-radius: 10px; border: 2px none #ffffff; }.navbar {background-color: #1982c4; border-radius: 0;}.nav>li>a:focus, .nav>li>a:hover{background-color:#1d97e4;}.modal-body{padding-bottom: 15px;}.navbar-default .btn-link, .navbar-default .navbar-brand, .navbar-default .navbar-link,.navbar-default .navbar-nav>li>a, .navbar-default .navbar-text{color:#fff;}</style>
		<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.1.1/howler.min.js"></script>
		<?php 
		if($this->session->selection == "doctor" || $this->session->selection == "receptionist") { 	

			if($this->session->selection == "doctor") {
				$heheheheh = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$this->session->id."'")->result_array();
				if($heheheheh[0]['doctor_sms_code'] == "@@@@@@@@SUCCESS@@@@@@@@") {
				?>
				<audio src="https://peekabook.tech/assets/mp3/mixkit-dry-pop-up-notification-alert-2356.wav" id="my_audio"></audio>
				<script type="text/javascript">
					var timer = 5000;
					var id = 0;
					var category = "";
					$(document).ready(function() {
						
						setInterval(function(){
							$.ajax({
								url: '<?php echo base_url(); ?>doctor/realtimeNotification',
								type: 'GET',
								dataType: "json",
								cache: true,
								success: function(data) {
									data.reverse();

									if (data.length === 0) { console.log("Array is empty!"); return false;}
									if(data[0].id != "" || data[0].category != "") {
										console.log("Current ID: "+id+"\nCurrent Category: "+category+"\nFuture ID: "+data[0].id+"\nFuture Category: "+data[0].category);
										if(id == data[0].id) {
										} else {
											
										
											//getAudioContext().resume();
											const Toast = Swal.mixin({
												toast: true,
												position: 'bottom-start',
												showConfirmButton: false,
												timerProgressBar: false,
												didOpen: (toast) => {
													var promise = document.getElementById('my_audio').play();

													if (promise !== undefined) {
													promise.then(_ => {
														// Autoplay started!
													}).catch(error => {
														// Autoplay was prevented.
														// Show a "Play" button so that user can start playback.
													});
													}
													toast.addEventListener('mouseenter', Swal.stopTimer)
													toast.addEventListener('mouseleave', Swal.resumeTimer)
												}
											})

											Toast.fire({
												icon: 'success',
												title: 'Datetime: '+data[0].datetime+'\nDatetime end: '+data[0].datetime_end+'\nCategory: '+ucfirsth(data[0].category)
											})
										}
										id = data[0].id;
										category = data[0].category;
									}
									
								}
							})
						}, timer);
					});
					function ucfirsth(string) {
						return string.charAt(0).toUpperCase() + string.slice(1);
					}
				</script>
				<?php 
				}
			}

			if($this->session->selection == "receptionist") {
				$heheheheh = $this->db->query("SELECT * FROM `receptionists_tbl` WHERE `receptionist_id` = '".$this->session->id."'")->result_array();
				if($heheheheh[0]['receptionist_sms_code'] == "@@@@@@@@SUCCESS@@@@@@@@") {
				?>
				<audio src="https://peekabook.tech/assets/mp3/mixkit-dry-pop-up-notification-alert-2356.wav" id="my_audio"></audio>
				<script type="text/javascript">
					var timer = 5000;
					var id = 0;
					var category = "";
					$(document).ready(function() {
						
						setInterval(function(){
							$.ajax({
								url: '<?php echo base_url(); ?>doctor/realtimeNotification',
								type: 'GET',
								dataType: "json",
								cache: true,
								success: function(data) {
									data.reverse();

									if (data.length === 0) { console.log("Array is empty!"); return false;}
									if(data[0].id != "" || data[0].category != "") {
										console.log("Current ID: "+id+"\nCurrent Category: "+category+"\nFuture ID: "+data[0].id+"\nFuture Category: "+data[0].category);
										if(id == data[0].id) {
										} else {
											
										
											//getAudioContext().resume();
											const Toast = Swal.mixin({
												toast: true,
												position: 'bottom-start',
												showConfirmButton: false,
												timerProgressBar: false,
												didOpen: (toast) => {
													var promise = document.getElementById('my_audio').play();

													if (promise !== undefined) {
													promise.then(_ => {
														// Autoplay started!
													}).catch(error => {
														// Autoplay was prevented.
														// Show a "Play" button so that user can start playback.
													});
													}
													toast.addEventListener('mouseenter', Swal.stopTimer)
													toast.addEventListener('mouseleave', Swal.resumeTimer)
												}
											})

											Toast.fire({
												icon: 'success',
												title: 'Datetime: '+data[0].datetime+'\nDatetime end: '+data[0].datetime_end+'\nCategory: '+ucfirsth(data[0].category)
											})
										}
										id = data[0].id;
										category = data[0].category;
									}
									
								}
							})
						}, timer);
					});
					function ucfirsth(string) {
						return string.charAt(0).toUpperCase() + string.slice(1);
					}
				</script>
				<?php
				}
			}
		}
		?>
	</head>

	<body class="skin-blue sidebar-mini">
		<div class="wrapper">