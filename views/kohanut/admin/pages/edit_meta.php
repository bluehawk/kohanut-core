<? echo $open ?>

<p><label for='type'>Type:</label><?php echo $type ?></p>

<p><label for='name'>Name:</label><?=$name?><span class="<?= $name->error_msg_class?>"><?=$name->error?><span></p>

<p><label for='url'>URL:</label><?=$url?><span class="<?= $url->error_msg_class?>"><?=$url->error?><span></p>

<div id="pageinfo">
	<h3>Page Meta Info</h3>
	<p><label for='title'>Title:</label><?=$title?><span class="<?= $title->error_msg_class?>"><?=$title->error?><span></p>
	<p><label for='metakw'>Meta Keywords:</label><?=$metakw?><span class="<?= $metakw->error_msg_class?>"><?=$metakw->error?><span></p>
	<p><label for='metadesc'>Meta Description:</label><?=$metadesc?><span class="<?= $metadesc->error_msg_class?>"><?=$metadesc->error?><span></p>
	<p><label for='shownav'>Show in nav?</label><?=$shownav?><span class="<?= $shownav->error_msg_class?>"><?=$shownav->error?><span></p>
	<p><label for='showmap'>Show in sitemap?</label><?=$showmap?><span class="<?= $showmap->error_msg_class?>"><?=$showmap->error?><span></p>
	<p><label for='layout'>Layout:</label><?=$layout?><span class="<?= $layout->error_msg_class?>"><?=$layout->error?><span></p>
</div>

	
<?= $submit ?>
<div class="clear"></div>
<? echo $close ?>