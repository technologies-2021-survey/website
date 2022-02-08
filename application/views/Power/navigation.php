<?php
    $userInfo = "";
    foreach($data as $row) {
        $userInfo = $row;
    }
	$texts = "";
	$names = "";
	if($this->session->selection == "doctor") {
		$texts = "doctor";
		$names = $userInfo->doctor_name;
	} else if($this->session->selection == "receptionist") {
		$texts = "receptionist";
		$names = $userInfo->receptionist_name;
	} else if($this->session->selection == "administrator") {
		$texts = "administrator";
		$names = $userInfo->admin_name;
	}
	
	$profile_picturesz = "";
	if($this->session->selection == "doctor") {
		$getName = $this->db->query("SELECT * FROM `doctors_tbl` WHERE `doctor_id` = '".$this->session->id."'")->result_array();
		$profile_picturesz = ($getName[0]['profile_picture'] != "") ? "data:image/png;base64,".base64_encode($getName[0]['profile_picture']) : "https://peekabook.tech/assets/img/whealth.png";
	} else if($this->session->selection == "receptionist") {
		$getName = $this->db->query("SELECT * FROM `receptionists_tbl` WHERE `receptionist_id` = '".$this->session->id."'")->result_array();
		$profile_picturesz = ($getName[0]['profile_picture'] != "") ? "data:image/png;base64,".base64_encode($getName[0]['profile_picture']) : "https://peekabook.tech/assets/img/whealth.png";
	} else if($this->session->selection == "administrator") {
		$getName = $this->db->query("SELECT * FROM `admins_tbl` WHERE `admin_id` = '".$this->session->id."'")->result_array();
		$profile_picturesz = ($getName[0]['profile_picture'] != "") ? "data:image/png;base64,".base64_encode($getName[0]['profile_picture']) : "https://peekabook.tech/assets/img/whealth.png";
	}
?>
		<header class="main-header">
			<!-- Logo -->
			<a href="<?php echo base_url(); ?>login" class="logo">
			<!-- mini logo for sidebar mini 50x50 pixels -->
			<span class="logo-mini"><b style="color:#ff9800;">W</b>H</span>
			<!-- logo for regular state and mobile devices -->
			<span class="logo-lg"><b style="color:#ff9800;">W</b>Health</span>
			</a>
			<!-- Header Navbar: style can be found in header.less -->
			<nav class="navbar navbar-static-top">
			<!-- Sidebar toggle button-->
			<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>

			<div class="navbar-custom-menu">
				<ul class="nav navbar-nav">
				<!-- Messages: style can be found in dropdown.less-->
				<li class="dropdown messages-menu">
					<a href="<?=base_url('/'.$texts.'/messages');?>">
						<i class="fa fa-envelope-o"></i>
					</a>
				</li>
				<!-- User Account: style can be found in dropdown.less -->
				<li class="dropdown user user-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<img src="<?php echo $profile_picturesz; ?>" class="user-image" alt="User Image">
					<span class="hidden-xs"><?php echo $names; ?></span>
					</a>
					<ul class="dropdown-menu">
					<!-- User image -->
					<li class="user-header">
						<img src="<?php echo $profile_picturesz; ?>" class="img-circle" alt="User Image">

						<p>
						<?php echo $names; ?>
						<!--<small>Member since Nov. 2012</small>-->
						</p>
					</li>
					<!-- Menu Body -->
					<li class="user-body">
						<div class="row">
						<div class="col-xs-12 text-center">
							<a href="<?=base_url('/'.$texts.'/change_profile_picture');?>">Change Profile Picture</a>
						</div>
						</div>
						<!-- /.row -->
					</li>
					<!-- Menu Footer-->
					<li class="user-footer">
						<div class="pull-right">
						<a href="<?=base_url('/'.$texts.'/logout');?>" class="btn btn-default btn-flat">Sign out</a>
						</div>
					</li>
					</ul>
				</li>
				</ul>
			</div>
			</nav>
		</header>
		<?php $this->load->view('Power/Include/sidebar'); ?>
		<div class="content-wrapper">
			<!--
			<section class="content-header">
				<h1>
					General UI
					<small>Preview of UI elements</small>
				</h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li><a href="#">UI</a></li>
					<li class="active">General</li>
				</ol>
			</section>
					-->
			<section class="content">