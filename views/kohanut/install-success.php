<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title><?php echo __('Install Kohanut') ?></title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
	
	<?php echo html::style( Route::get('kohanut-media')->uri(array('file'=>'css/960.css'))      , array('media'=>'screen','charset'=>'utf-8') ) . "\n"; ?>
	<?php echo html::style( Route::get('kohanut-media')->uri(array('file'=>'css/template.css')) , array('media'=>'screen','charset'=>'utf-8') ) . "\n"; ?>
	<?php echo html::style( Route::get('kohanut-media')->uri(array('file'=>'css/color.css'))    , array('media'=>'screen','charset'=>'utf-8') ) . "\n"; ?>
	<?php echo html::style( Route::get('kohanut-media')->uri(array('file'=>'css/kohanut.css'))  , array('media'=>'screen','charset'=>'utf-8') ) . "\n"; ?>
	
</head>
<body>

	<div class="grid_6" style="width:350px;margin:100px auto;display:block;float:none;" >
		<div class="box">
			
			<h1><?php echo __('Success') ?></h1>

			<p><?php echo __('Installed successfully! Be sure to delete or rename the folder :installer',array('installer'=>'<strong>modules/kohanut/classes/kohanut/install</strong>')) ?></p>
			
			<p><?php echo html::anchor(Route::get('kohanut-login')->uri(array('action'=>'login')),__('Login'),array('class'=>'button')) ?></p>

		</div>
			
    </div>
    
</body>
</html>