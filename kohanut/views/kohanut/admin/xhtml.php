<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php echo (isset($title) ? "Admin - " . $title : "Admin"); ?></title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	
	<link rel="stylesheet" type="text/css" href="/kohanutres/reset.css" />
	<link rel="stylesheet" type="text/css" href="/kohanutres/kohanut.css" />
	
	<script type="text/javascript" src="/kohanutres/jquery/jquery-1.3.2.min.js" ></script>
	<script type="text/javascript" src="/kohanutres/jquery/jquery.treeview.js"></script>
	<script type="text/javascript" src="/kohanutres/jquery/jquery.cookie.js"></script>
	
</head>
<body>
	
	<div id="headerwrapper">
		<div id="header" >
			<img id="headerlogo" alt="Powered by Kohanut" src="/kohanutres/img/logo.png" />
			<div class="info">
				<p>
					Logged in as <?php echo Auth::instance()->get_user()->username; ?> | 
					<a href="/">Visit Site</a> | <a href="/admin/logout">Logout</a>
				</p>
			</div>
		</div>
	</div>
	<div id="mainnav">
		<ul>
	
			<div style="float:right;">
				<li class="seperator"></li>
				<li <?php echo (uri::segment(2)=="layouts") ? 'class="current"':''; ?> >
					<a href="/admin/layouts"><img src="/kohanutres/img/fam/layout.png" />Layouts</a>
				</li>
				<li <?php echo (uri::segment(2)=="redirects") ? 'class="current"':''; ?> >
					<a href="/admin/redirects"><img src="/kohanutres/img/fam/link.png" />Redirects</a>
				</li>
				<li <?php echo (uri::segment(2)=="plugins") ? 'class="current"':''; ?> >
					<a href="/admin/plugins"><img src="/kohanutres/img/fam/plugin.png" />Plugins</a>
				</li>
				<li <?php echo (uri::segment(2)=="users") ? 'class="current"':''; ?> >
					<a href="/admin/users"><img src="/kohanutres/img/fam/user.png" />Users</a>
				</li>
				
				<li <?php echo (uri::segment(2)=="settings") ? 'class="current"':''; ?> >
					<a href="/admin/settings"><img src="/kohanutres/img/fam/cog.png" />Settings</a>
				</li>
			</div>
	
	
			<li <?php echo (uri::segment(2)=="pages") ? 'class="current"':''; ?> >
				<a href="/admin/pages"><img src="/kohanutres/img/fam/page.png" />Pages</a>
			</li>
			
			<li class="seperator"></li>
			
			<li <?php echo (uri::segment(2)=="snippets") ? 'class="current"':''; ?> >
				<a href="/admin/snippets"><img src="/kohanutres/img/fam/note.png" />Snippets</a>
			</li>


		</ul>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
		
	<div id="container">
		
		<?php echo $body ?>
		
	</div>
	
</body>
</html>
