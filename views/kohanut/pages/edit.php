<div class="grid_12">
	
	<div class="box">
		
		<h1><?php echo __('Editing Page:') ?> <?php echo $page->name ?></h1>
		
		<?php include Kohana::find_file('views', 'kohanut/errors') ?>
		
		<?php if ($page->islink): ?>
			
			<p class="notice"><?php echo __('This is an external link, meaning it is not actually a page managed by this system, but rather it links to a page somewhere else.  To change it to a page that you can control here, uncheck "External Link" below.') ?></p>
			
		<?php else: ?>
		
			<h2><?php echo __('Edit Page Content') ?></h2>
			<p><?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'pages','action'=>'edit','params'=>$page->id)),__('Click to edit this page&rsquo;s content'),array('class'=>'button')) ?></p>
			
		<?php endif; ?>
		
		
		<form method="post">
			
			<p>
				<label><?php echo __('Navigation Name') ?><small><?php echo __('This is the name that shows up in the navigation.') ?></small></label>
				<?php echo $page->input('name'); ?>
			</p>
			<p>
				<label><?php echo __('URL') ?><small><?php echo __('This is the "link" to the page, or whats in the address bar.') ?></small></label>
				<?php echo $page->input('url',array('id'=>'kohanut_url')); ?>
			</p>
			
			<p id="kohanut_url_note"><?php echo __('If you are seeing this text, you might have javascript disabled.') ?></p>
			
			<p>
				<label><?php echo __('External Link') ?><small><?php echo __('Checking this will mean you can\'t edit this page here, it simply links to the URL above.') ?></small></label>
				<?php echo $page->input('islink',array('class'=>'check','id'=>'kohanut_islink')) ?>
			</p>
			<p>
				<label><?php echo __('Show in Navigation') ?><small><?php echo __('Check this to have this page show in the navigation menus.') ?></small></label>
				<?php echo $page->input('shownav',array('class'=>'check')) ?>
			</p>
			<p>
				<label><?php echo __('Show in Site Map') ?><small><?php echo __('Check this to have this page show in the site map.') ?></small></label>
				<?php echo $page->input('showmap',array('class'=>'check')) ?>
			</p>
			
		<?php if ( ! $page->islink): ?>
		<hr/>
			<h1><?php echo __('Page Meta Data') ?></h1>
			
			<p>
				<label><?php echo __('Title') ?><small><?php echo __('This is what shows up at the top of the window or tab.') ?></small></label>
				<?php echo $page->input('title'); ?>
			</p>
			<p>
				<label><?php echo __('Meta Keywords') ?><small><?php echo __('Keywords are used by search engines to find and rank your page.') ?></small></label>
				<?php echo $page->input('metakw',array('style'=>'height:60px')); ?>
			</p>
			<p>
				<label><?php echo __('Meta Description') ?><small><?php echo __('This is used by search engines to summarize your page for visitors.') ?></small></label>
				<?php echo $page->input('metadesc',array('style'=>'height:60px')); ?>
			</p>
			<p>
				<label><?php echo __('Layout') ?><small><?php echo __('Which layout this page should use.') ?></small></label>
				<?php echo $page->input('layout'); ?>
			</p>
		<?php endif; ?>
		
			<p>
				<?php echo Form::submit('submit',__('Save Changes'),array('class'=>'submit')) ?>
				<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'pages')),__('cancel')); ?>
			</p>
			
		</form>
	
		<div class="clear"></div>
		
	</div>
	
</div>

<div class="grid_4">
	
	<div class="box">
		<h1><?php echo __('Help') ?></h1>
		
		<p>I need to write the help for this page.</p>
		
	</div>
	
</div>

<script type="text/javascript">
$(document).ready(function() {
	
	$('#kohanut_url').keyup(function() {
		
		url = this.value;
		
		// if the link doesn't contain '://' add the site url to it
		if (url.indexOf('://') == -1)
		{
			url = '<span style="color:#666"><?php echo url::site(FALSE,TRUE) ?></span><strong>' + url + '</strong>';
		}
		
		if ($('#kohanut_islink').is(':checked'))
		{
			string = "<?php echo __('This will link to:')?> <strong>" + url + "</strong>";
		}
		else
		{
			string = "<?php echo __('This page will have the URL:')?> " + url;
		}
		
		$('#kohanut_url_note').html(string);
	});
	
	$('#kohanut_islink').click(function(){ $('#kohanut_url').keyup() });
	
	// Call the function when the page loads
	$('#kohanut_url').keyup()
	
});

</script>
