<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title><?php echo (isset($title) ? "Admin - " . $title : "Admin"); ?></title>

	<?php echo html::style( Route::get('kohanut-media')->uri(array('file'=>'css/960.css'))      , array('media'=>'screen','charset'=>'utf-8') ) . "\n"; ?>
	<?php echo html::style( Route::get('kohanut-media')->uri(array('file'=>'css/template.css')) , array('media'=>'screen','charset'=>'utf-8') ) . "\n"; ?>
	<?php echo html::style( Route::get('kohanut-media')->uri(array('file'=>'css/color.css'))    , array('media'=>'screen','charset'=>'utf-8') ) . "\n"; ?>
	<?php echo html::style( Route::get('kohanut-media')->uri(array('file'=>'css/kohanut.css'))  , array('media'=>'screen','charset'=>'utf-8') ) . "\n"; ?>

	<?php echo html::script(Route::get('kohanut-media')->uri(array('file'=>'jquery/jquery-1.3.2.min.js')) ). "\n"; ?>
	<?php echo html::script(Route::get('kohanut-media')->uri(array('file'=>'jquery/jquery.treeview.js')) ). "\n"; ?>
	<?php echo html::script(Route::get('kohanut-media')->uri(array('file'=>'jquery/jquery.cookie.js')) ). "\n"; ?>

</head>
<body>

	<div id="head">
		<?php echo html::image(Route::get('kohanut-media')->uri(array('file'=>'img/logo.png')),array('alt'=>'Kohanut Logo','class'=>'logo')); ?>
		<h1>Kohanut</h1>
	
		<p class="info">
			Logged in as <?php echo $user; ?> | 
			<a href="/">Visit Site</a> | <?php echo html::anchor( Route::get('kohanut-admin')->uri(array('controller'=>'user','action'=>'logout')) , "Logout" ) ?>
		</p>
	</div>
	
	<ul id="navigation">
		<li><?php echo html::anchor( Route::get('kohanut-admin')->uri(array('controller'=>'pages')) , "Pages" ) ?></li>
		<li><?php echo html::anchor( Route::get('kohanut-admin')->uri(array('controller'=>'snippets')) , "Snippets" ) ?></li>
		<li><?php echo html::anchor( Route::get('kohanut-admin')->uri(array('controller'=>'users')) , "Users" ) ?></li>
		<li><?php echo html::anchor( Route::get('kohanut-admin')->uri(array('controller'=>'layouts')) , "Layouts" ) ?></li>
		<li><?php echo html::anchor( Route::get('kohanut-admin')->uri(array('controller'=>'redirects')) , "Redirects" ) ?></li>
	</ul>

	<div id="content" class="container_16 clearfix">
		<?php echo $body ?>
	</div>
	
	<?php // echo View::factory('profiler/stats'); ?>
	
</body>
</html>
