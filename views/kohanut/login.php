<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title><?php echo (isset($title) ? __("Admin") . " - " . $title : __("Admin")); ?></title>

	<?php echo html::style( Route::get('kohanut-media')->uri(array('file'=>'css/960.css'))      , array('media'=>'screen','charset'=>'utf-8') ) . "\n"; ?>
	<?php echo html::style( Route::get('kohanut-media')->uri(array('file'=>'css/template.css')) , array('media'=>'screen','charset'=>'utf-8') ) . "\n"; ?>
	<?php echo html::style( Route::get('kohanut-media')->uri(array('file'=>'css/color.css'))    , array('media'=>'screen','charset'=>'utf-8') ) . "\n"; ?>
	<?php echo html::style( Route::get('kohanut-media')->uri(array('file'=>'css/kohanut.css'))  , array('media'=>'screen','charset'=>'utf-8') ) . "\n"; ?>

	<?php echo html::script(Route::get('kohanut-media')->uri(array('file'=>'jquery/jquery-1.3.2.min.js')) ). "\n"; ?>
	<?php echo html::script(Route::get('kohanut-media')->uri(array('file'=>'jquery/jquery.treeview.js')) ). "\n"; ?>
	<?php echo html::script(Route::get('kohanut-media')->uri(array('file'=>'jquery/jquery.cookie.js')) ). "\n"; ?>
	
</head>
<body>

	
	<div class="grid_6" style="width:350px;margin:100px auto;display:block;float:none;" >
		<div class="box">
  
			<h1><?php echo __('Login') ?></h1>
			
			<?php include Kohana::find_file('views', 'kohanut/errors') ?>
			<?php echo form::open(NULL, array('id' => 'login')) ?>
			
			 <p><label><?php echo __('Username:') ?></label> <?php echo form::input('username', $user->username) ?></p>
			 <p><label><?php echo __('Password:') ?></label> <?php echo form::password('password') ?></p>
			
			<p>
				<?php echo form::submit('submit',__('Login'),array('class'=>'submit')) ?>
			</p>
			
			<?php echo form::close() ?>
		</div>
	</div>
	
	
</body>
</html>
