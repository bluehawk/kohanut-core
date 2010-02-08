<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Install Kohanut</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
	
	<?php echo html::style( Route::get('kohanut-media')->uri(array('file'=>'css/960.css'))      , array('media'=>'screen','charset'=>'utf-8') ) . "\n"; ?>
	<?php echo html::style( Route::get('kohanut-media')->uri(array('file'=>'css/template.css')) , array('media'=>'screen','charset'=>'utf-8') ) . "\n"; ?>
	<?php echo html::style( Route::get('kohanut-media')->uri(array('file'=>'css/color.css'))    , array('media'=>'screen','charset'=>'utf-8') ) . "\n"; ?>
	<?php echo html::style( Route::get('kohanut-media')->uri(array('file'=>'css/kohanut.css'))  , array('media'=>'screen','charset'=>'utf-8') ) . "\n"; ?>

</head>
<body>

	<div class="grid_6" style="width:350px;margin:100px auto;display:block;float:none;" >
		<div class="box">
			
			<h1>Install Kohanut</h1>
			
			<p>You should have your database settings put into application/config/database.php or this will fail :)</p>
			
			<?php include Kohana::find_file('views', 'kohanut/admin/errors') ?>
			
			<?php echo form::open(NULL, array('id' => 'login','method'=>'post')) ?>
			
				<p>
					<label>Set your admin password:</label>
					<?php echo form::password('password') ?>
				</p>
				<p>
					<label>Repeat password:</label>
					<?php echo form::password('repeat') ?>
				</p>
				<p>
					<input type="submit" name="submit" value="Install" class="submit" />
				</p>

			</form>
			
		</div>
			
    </div>
    
</body>
</html>
