<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title><?php echo (isset($title) ? "Admin - " . $title : "Admin"); ?></title>
	<link rel="stylesheet" type="text/css" href="/kohanutres/reset.css" />
	<link rel="stylesheet" href="/kohanutres/css/960.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="stylesheet" href="/kohanutres/css/template.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="stylesheet" href="/kohanutres/css/colour.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="stylesheet" href="/kohanutres/css/kohanut.css" type="text/css" media="screen" charset="utf-8" />
	
	<!--
	
	<link rel="stylesheet" type="text/css" href="/kohanutres/kohanut.css" />
	-->
	
	<script type="text/javascript" src="/kohanutres/jquery/jquery-1.3.2.min.js" ></script>
	<script type="text/javascript" src="/kohanutres/jquery/jquery.treeview.js"></script>
	<script type="text/javascript" src="/kohanutres/jquery/jquery.cookie.js"></script>

</head>
<body>

	<div id="head">
		<h1>Kohanut</h1>
	
		<p class="info">
			Logged in as <?php echo $user; ?> | 
			<a href="/">Visit Site</a> | <a href="/admin/user/logout">Logout</a>
		</p>
	</div>
	
	<ul id="navigation">
		<li><a href="/admin/pages/" <?php if ($controller == "pages") echo ' class="active"'; ?>>Pages</a></li>
		<li><a href="/admin/snippets" <?php if ($controller == "snippets") echo ' class="active"'; ?>>Snippets</a></li>
		<li><a href="/admin/users" <?php if ($controller == "users") echo ' class="active"'; ?>>Users</a></li>
		<li><a href="/admin/layouts" <?php if ($controller == "layouts") echo ' class="active"'; ?>>Layouts</a></li>
		<li><a href="/admin/redirects" <?php if ($controller == "redirects") echo ' class="active"'; ?>>Redirects</a></li>
	</ul>

	<div id="content" class="container_16 clearfix">
		<?php echo $body ?>
	</div>
	
	<?php // echo View::factory('profiler/stats'); ?>
	
</body>
</html>
