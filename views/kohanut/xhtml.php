<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php echo (isset($title) ? $title : "Page Title"); ?></title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<?php if (isset($metadesc) && $metadesc != ""): ?>
	<meta name="description" content="<?php echo $metadesc ?>" />
<?php endif; ?>
<?php if (isset($metakw) && $metakw != ""): ?>
	<meta name="keywords" content="<?php echo $metakw ?>" />
<?php endif; ?>
	<meta name="generator" content="Kohanut" />
<!-- Begin Kohanut includes -->
<?php echo Kohanut::stylesheet_render(); ?>
<?php echo Kohanut::javascript_render(); ?>
<?php echo Kohanut::meta_render(); ?>
<!-- End Kohanut includes -->

</head>
<body>
<!-- Begin Page Layout Code -->
<?php echo $layoutcode ?>

<!-- End Page Layout Code -->
<?php //echo View::factory('profiler/stats'); ?>
</body>
</html>
