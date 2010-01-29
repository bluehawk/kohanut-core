<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title><?php echo (isset($title) ? "Admin - " . $title : "Admin"); ?></title>
	<link rel="stylesheet" href="/kohanutres/css/960.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="stylesheet" href="/kohanutres/css/template.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="stylesheet" href="/kohanutres/css/color.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="stylesheet" href="/kohanutres/css/kohanut.css" type="text/css" media="screen" charset="utf-8" />
	
	<!--
	
	<link rel="stylesheet" type="text/css" href="/kohanutres/kohanut.css" />
	-->
	
	<script type="text/javascript" src="/kohanutres/jquery/jquery-1.3.2.min.js" ></script>
	<script type="text/javascript" src="/kohanutres/jquery/jquery.treeview.js"></script>
	<script type="text/javascript" src="/kohanutres/jquery/jquery.cookie.js"></script>

</head>
<body>

	
	<div class="grid_6" style="width:350px;margin:100px auto;display:block;float:none;" >
		<div class="box">
  
			<h1><?php echo 'Login' ?></h1>
			
			<?php include Kohana::find_file('views', 'kohanut/admin/errors') ?>
			<?php echo form::open(NULL, array('id' => 'login')) ?>
			
			 <p><label><?php echo 'Username:' ?></label> <?php echo form::input('username', $user->username) ?></p>
			 <p><label><?php echo 'Password:' ?></label> <?php echo form::password('password') ?></p>
			
			<p>
				<input type="submit" name="submit" value="Login" class="submit" />
			</p>
			
			<?php echo form::close() ?>
		</div>
	</div>
	
	
</body>
</html>
