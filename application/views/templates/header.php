<!DOCTYPE html>
<html>
<head>
	<title>Attendance</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
  	<!-- <link href="<?php echo base_url(); ?>assets/MDB-Free_4.8.7/css/mdb.min.css" rel="stylesheet"> -->
  	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/mystyle.css">
  	<script src="<?php echo base_url(); ?>assets/jqueryfile/jquery-3.3.1.min.js"></script>
  	<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body id="edge" style="background-color: #f5f5dc;">
	<header>
		<nav class="navbar navbar-inverse navbar-fixed-top" style="border: none; border-radius: 0%; border-bottom: 1px solid white;">
  <div class="container-fluid">
    <div class="navbar-header">
    	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
    		<span class="glyphicon glyphicon-menu-hamburger" style="color: rgb(100,200,200);"></span>
    	</button>
      <a class="navbar-brand" style="font-size: 30px; font-family: 'Kranky'; font-weight: bold; color: rgb(200,150,50); margin-left: 2vh;" href="#">At-Log</a>
      <h4 class="navbar-text"><?= $title ?> What are the constructs of the lang you used??</h4>
    </div>
   <div class="collapse navbar-collapse" id="myNavbar" style="border: none;">
    <ul class="nav navbar-nav navbar-right">
      <li>
        <a id="partition" href="<?php echo base_url(); ?>" title="Home"><strong id="shadow"><span class="glyphicon glyphicon-home"></span></strong></a>
      </li>
      <li>
        <a id="partition" href="<?php echo base_url(); ?>users/timetable" title="Timetable"><strong id="shadow"><span class="glyphicon glyphicon-check"></span></strong></a>
      </li>
      <li class="dropdown">
        <a id="partition" class="dropdown-toggle" data-toggle="dropdown" href="#" title="User"><strong id="shadow"><span class="glyphicon glyphicon-user"></span></strong></a>
        <div class="dropdown-menu" style="width: 250px;">
          <ul class="form" style="border: none; padding: 10px 20px;">
        	<?php if ($this->session->userdata('lecturer_in')): ?>
        		<p><b>Logged in as <?php echo $this->session->userdata('lecturer_name'); ?></b></p>
        		<p><a id="shadow" class="changePwd" href="<?php echo base_url(); ?>users/changePwd" data="<?php echo $this->session->userdata('lecturer_id'); ?>">Change password</a></p>
        		<p><a id="shadow" href="<?php echo base_url(); ?>users/logout">Logout</a></p>
        		<?php elseif ($this->session->userdata('log_in')): ?>
        			Logged in as Admin<br>
        		<a id="shadow" href="<?php echo base_url(); ?>users/logout">Logout</a>
        		<?php else: ?>
      <?php echo form_open('users/logLecturerIn', array("id" => "login")); ?>
      	<div id="loginmessage"></div>
        <div id="loginerr" class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input type="text" id="uname" class="form-control" name="uname" placeholder="Username/Course code" autofocus>
        </div><br>
        <div id="loginerr" class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
            <input type="password" id="password" class="form-control" name="password" placeholder="Password">
            <span class="input-group-addon" style="cursor: pointer;"><i class="glyphicon glyphicon-eye-close"></i></span>
        </div><br>
        <input type="hidden" id="hiddenLogin" name="btnClicked">
        <button type="submit" name="logAdminIn" id="login" class="btn btn-success">Admin</button>
        <button type="submit" name="logLecturerIn" id="login" class="btn btn-success">Lecturer</button>
      <?php echo form_close(); ?>
        	<?php endif; ?>
    </ul>
        </div>
      </li>
      <li class="dropdown">
        <a id="partition" class="dropdown-toggle" data-toggle="dropdown" href="#" title="Options"><strong id="shadow""><span class="glyphicon glyphicon-list"></span></strong></a>
          <ul class="dropdown-menu" style="">
      <li><a id="shadow" href="#" id="guide" data-toggle="modal" data-target="#myGuide">User Guidelines</a></li>
      <?php if ($this->session->userdata('lecturer_in')): ?>
      <?php if ($this->session->userdata('otp_set')): ?>
      <li><a id="shadow" class="end" href="<?php echo base_url(); ?>attendance/endAttendance">End attendance</a></li>
      	<?php else: ?>
      <li><a id="shadow" class="start" href="<?php echo base_url(); ?>attendance/startAttendance">Start attendance</a></li>
      <?php endif; ?>
      <li><a id="shadow" href="<?php echo base_url(); ?>users/dashboard" title="Info, Students present in last class, etc...">Dashboard</a></li>
      <?php endif; ?>
      <?php if ($this->session->userdata('log_in')): ?>
      <li><a id="shadow" href="<?php echo base_url(); ?>users/students">Students</a></li>
      <li><a id="shadow" href="<?php echo base_url(); ?>users/addcourses">Upload courses</a></li>
      <li><a id="shadow" href="<?php echo base_url(); ?>users/register">User registration</a></li>
      <li><a id="restart" style="color: red;" href="<?php echo base_url(); ?>users/restart">Restart</a></li>
      <?php endif; ?>
      <li><a id="shadow" href="<?php echo base_url(); ?>about">About</a></li>
    </ul>
      </li>
    </ul>
</div>
  </div>
</nav>
	</header>
<!-- User Guidelines modal -->
	<div class="modal fade" id="myGuide" role="dialog">
  <div class="modal-dialog" style="z-index: 9999 !important;">

    <div class="modal-content">
    	<div class="modal-header">
      	<button style="color: red;" type="button" class="close" data-dismiss="modal">&times;</button>
    	</div>
      <div class="modal-body">
      <h3>User Guidlines</h3>
      <ul class="nav nav-tabs nav-tabs-justified">
	    <li class="active"><a data-toggle="tab" href="#lecturer">Lecturer</a></li>
	    <li><a data-toggle="tab" href="#admin">Admin</a></li>
	  </ul>
	  <div class="well tab-content" style="box-shadow: 1px 1px 1px grey;">
	  	<div id="lecturer" class="tab-pane fade in active">
	      <p>1. Never forget to end attendance or logout.</p>
	      <p>2. If the system logs you out, log back in and end attendance else the course will display as ongoing in the timetable.</p>
	  	</div>
	  	<div id="admin" class="tab-pane fade">
	      <p>1. Never forget to logout!</p>
	    </div>
	  </div>
      </div>
    </div>                
  </div>              
</div>
	<div class="container bodycon" style="margin-top: 10vh;">
		<!--flash messages-->