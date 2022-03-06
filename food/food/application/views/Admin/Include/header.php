<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $title; ?></title>
		<meta name="description" content="BookACleaner">
		<meta name="keywords" content="BookACleaner">
		<meta name="author" content="Harvey Arboleda">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		
		<link rel="icon" type="image/png" href="https://peekabook.tech/assets/img/whealth.png">
		
		<link rel="stylesheet" href="<?=base_url('assets/css/bootstrap.min.css');?>" media="screen" />
		<link rel="stylesheet" href="<?=base_url('assets/css/admin.css');?>" media="screen" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/AdminLTE.min.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/skins/skin-purple.min.css">
		
		<link rel="stylesheet" href="//db.onlinewebfonts.com/c/0f56c874ba695191e7f4cab237447698?family=Futura" >
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Catamaran:wght@100;200;300;400;500;600;700;800;900&display=swap">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
		
        <script type="text/javascript" src="<?=base_url('assets/js/jquery-1.10.2.min.js');?>"></script>
        <script type="text/javascript" src="<?=base_url('assets/js/jquery.easing.1.3.js');?>"></script>
		<script type="text/javascript" src="<?=base_url('assets/js/bootstrap.min.js');?>"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>dist/js/adminlte.min.js"></script>
		<script type="text/javascript" src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<script type="text/javascript" src="https://unpkg.com/scrollreveal"></script>
 		<script type="text/javascript">
			const Toast = Swal.mixin({
				toast: true,
				position: 'top-end',
				showConfirmButton: false,
				timer: 3000,
				timerProgressBar: true,
				didOpen: (toast) => {
					toast.addEventListener('mouseenter', Swal.stopTimer)
					toast.addEventListener('mouseleave', Swal.resumeTimer)
				}
			})
			var notif = new Audio('https://peekabook.tech/bookacleaner/bookacleaner/assets/mp3/mixkit-dry-pop-up-notification-alert-2356.wav');
		</script>
    </head>
    <body class="skin-purple sidebar-mini">
		<div class="wrapper">
			<header class="main-header">
				<a href="<?php echo base_url(); ?>admin" class="logo">
					<span class="logo-mini"><b>B</b></span>
					<span class="logo-lg"><b>BookACleaner</b></span>
				</a>
				<nav class="navbar navbar-static-top">
				<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>

				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
					<li class="dropdown user user-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<img src="https://peekabook.tech/assets/img/whealth.png" class="user-image" alt="User Image">
							<span class="hidden-xs"><?=$username;?></span>
						</a>
						<ul class="dropdown-menu">
							<li class="user-header">
								<img src="https://peekabook.tech/assets/img/whealth.png" class="img-circle" alt="User Image">

								<p>
									<?=$username;?>
								</p>
							</li>
							<li class="user-footer">
								<div class="pull-right">
									<a href="<?=base_url('admin/logout');?>" class="btn btn-default btn-flat">Sign out</a>
								</div>
							</li>
						</ul>
					</li>
					</ul>
				</div>
				</nav>
			</header>
			<aside class="main-sidebar">
				<section class="sidebar">
					<div class="user-panel">
						<div class="pull-left image">
							<img src="https://peekabook.tech/assets/img/whealth.png" class="img-circle" alt="User Image">
						</div>
						<div class="pull-left info">
							<p style="margin-bottom: 2px!important;"><?=ucfirst($username);?></p>
							<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
						</div>
					</div>
					<!-- sidebar menu: : style can be found in sidebar.less -->
				<ul class="sidebar-menu tree" data-widget="tree">
					<li class="header">MAIN NAVIGATION</li>
					<li>
						<a href="<?php echo base_url();?>admin/main">
							<i class="fa fa-dashboard"></i> <span>Dashboard</span>
						</a>
					</li>
					<li class="treeview">
						<a href="#">
							<i class="fa fa-pie-chart"></i>
							<span>Menu</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
						<li><a href="<?=base_url('admin/accounts');?>">Account's List</a></li>
						<li><a href="<?=base_url('admin/dashboard');?>">Cleaner(s) / Booking(s)</a></li>
						</ul>
					</li>
					</ul>
				</section>
			</aside>
			<div class="content-wrapper">
				<section class="content">