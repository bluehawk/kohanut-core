<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php echo (isset(Kohanut::$page->title) ? Kohanut::$page->title : "Page Title"); ?></title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<?php if (isset(Kohanut::$page->metadesc) && Kohanut::$page->metadesc != ""): ?>
	<meta name="description" content="<?php echo Kohanut::$page->metadesc ?>" />
<?php endif; ?>
<?php if (isset(Kohanut::$page->metakw) && Kohanut::$page->metakw != ""): ?>
	<meta name="keywords" content="<?php echo Kohanut::$page->metakw ?>" />
<?php endif; ?>
<?php //	<meta name="generator" content="Kohanut" /> ?>
<!-- Begin Kohanut includes -->
<?php echo Kohanut::style_render(); ?>
<?php echo Kohanut::script_render(); ?>
<?php echo Kohanut::meta_render(); ?>
<!-- End Kohanut includes -->

</head>
<body<?php if (Kohanut::$adminmode) echo " id='kohanut_admin'"; ?>>
<?php if (Kohanut::$adminmode): ?>
<!-- Admin mode header -->
<div id="kohanut_header">
	<p>
		<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'pages')),'&laquo; ' . __('Back')) ?> | 
		<?php echo __('You are editing :page',array(':page'=>'<strong>'.Kohanut::$page->name.'</strong>')) ?> |
		<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'pages','action'=>'meta','params'=>Kohanut::$page->id)),__('Edit meta data')) ?>
	</p>
</div>
<!-- End Admin mode header -->
<?php endif; ?>
<!-- Begin Page Layout Code -->

<?php echo $layoutcode ?>
<!-- End Page Layout Code -->
<div id="kohana-profiler">
<?php //echo View::factory('profiler/stats') ?>
</div>
</body>
</html>