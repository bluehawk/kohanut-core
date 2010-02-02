<div class="grid_12">
	
	<div class="box">
		
		<h1>Adding New Page</h1>
		
		<p>Adding a sub page to &quot;<?php echo $parent->name ?>&quot;</p>
		
		<?php include Kohana::find_file('views', 'kohanut/admin/errors') ?>
		
		<form method="post">
			<p>
				<label>Location<small>Where in the list of siblings this page will appear</small></label>
				<select name="location">
					<?php foreach ( $parent->children() as $child): ?>
					<option value="<?php echo $child->id ?>">After <?php echo $child->name ?></option>
					<?php endforeach; ?>
					<option value="last" selected="selected">Last Child</option>
				</select>
			</p>
			<p>
				<label>Navigation Name<small>This is the name that shows up in the navigation</small></label>
				<?php echo $page->input('name'); ?>
			</p>
			<p>
				<label>URL<small>This is the "link" to the page, or whats in the address bar.</small></label>
				<?php echo $page->input('url'); ?>
			</p>
			<p>
				<label>External Link<small>Checking this will mean you can't edit this page here, it simply links to the URL above</small></label>
				<?php echo $page->input('islink',array('class'=>'check')) ?>
			</p>
			<p>
				<label>Show in Navigation<small>Check this to have this page show in the main, and other navigation menus</small></label>
				<?php echo $page->input('shownav',array('class'=>'check')) ?>
			</p>
			<p>
				<label>Show in Site Map?<small>Check this to have this page show in the site map</small></label>
				<?php echo $page->input('showmap',array('class'=>'check')) ?>
			</p>
			
			<p>
				<label>Layout<small>Which layout this page should use (Not needed for external Links)</small></label>
				<?php echo $page->input('layout'); ?>
			</p>
		
			<p>
				<?php echo Form::submit('submit','Create Page',array('class'=>'submit')) ?>
				<a href="/admin/pages">cancel</a>
			</p>
			
		</form>
	
		<div class="clear"></div>
		
	</div>
	
</div>

<div class="grid_4">
	
	<div class="box">
		<h1>Help</h1>
		
		<p>I need to write the help for this page.</p>
		
	</div>
	
</div>
