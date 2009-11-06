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
					Logged in as <?php echo $user; ?> | 
					<a href="/">Visit Site</a> | <a href="/admin/user/logout">Logout</a>
				</p>
			</div>
		</div>
	</div>
	<div id="mainnav">
		<ul>
	
			<div style="float:right;">
				<li class="seperator"></li>
				
				
				<li class="dropdown <?php if ($controller == "settings") echo 'current'; ?>">
					<a href="/admin/settings"><img src="/kohanutres/img/fam/cog.png" />Settings</a>
					<ul>
						<li>
							<a href="/admin/settings"><img src="/kohanutres/img/fam/cog.png" />Site Settings</a>
						</li>
						<li>
							<a href="/admin/layouts"><img src="/kohanutres/img/fam/layout.png" />Layouts</a>
						</li>
						<li>
							<a href="/admin/redirects"><img src="/kohanutres/img/fam/link.png" />Redirects</a>
						</li>
						<li>
							<a href="/admin/plugins"><img src="/kohanutres/img/fam/plugin.png" />Plugins</a>
						</li>
						<li>
							<a href="/admin/users"><img src="/kohanutres/img/fam/user.png" />Users</a>
						</li>
					</ul>
				</li>
			</div>
	
	
			<li<?php if ($controller == "pages") echo ' class="current"'; ?>>
				<a href="/admin/pages"><img src="/kohanutres/img/fam/page.png" />Pages</a>
			</li>
			
			<li class="seperator"></li>
			
			<!-- Plugins go here -->
			
			<li<?php if ($controller == "snippets") echo ' class="current"'; ?>>
				<a href="/admin/snippets"><img src="/kohanutres/img/fam/note.png" />Snippets</a>
			</li>
			
			<!-- End Plugins -->
		
		</ul>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
		
	<div id="container">
		
		<?php echo $body ?>
		
	</div>
	
</body>
</html>
