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
			
			<h1><?php echo __('Install Kohanut') ?></h1>
			
			<p><?php echo __('You should have your database settings put into :config or this will fail.',array(':config'=>'<strong>application/config/database.php</strong>')) ?></p>
			
			<?php include Kohana::find_file('views', 'kohanut/errors') ?>
			
			<?php echo form::open(NULL, array('id' => 'login','method'=>'post')) ?>
			
				<p>
					<label><?php echo __('Set your admin password:') ?></label>
					<?php echo form::password('password') ?>
				</p>
				<p>
					<label><?php echo __('Repeat password:') ?></label>
					<?php echo form::password('repeat') ?>
				</p>
				<p>
					<?php echo form::submit('submit',__('Install'),array('class'=>'submit')) ?>
				</p>

			</form>
			
		</div>
			
    </div>
    
</body>
</html>
