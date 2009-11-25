<div id="side">
	
	<div class="box">
		<h2>Help</h2>
		<div class="content">
			
			<h3>Navigation Name</h3>
			<p>This is what will appear in the navigation.</p>
			
			<h3>URL</h3>
			<p>This is the location of the page, or what is in the address bar when you are at that page.</p>
			
			<h3>Type</h3>
			<p><strong>Page</strong> - Creates a page in the system that can be managed from here.</p>
			<p><strong>External Link</strong> - Use this to link to a page or another site that is not managed from here.</p>
			
			<h3>Location</h3>
			<p>Where the page will be in the list of children.</p>
			
			<h3>Layout</h3>
			<p>Which layout to use.</p>
			
		</div>
	</div>
	
</div>

<div id="main">
	
	<div class="box">
		<h2><img class="headericon" src="/kohanutres/img/fam/page_add.png" alt="add"/>Add Page</h2>
		<div class="content">
			
			<p>Add a subpage to <?php echo $parent->name ?> (url: <?php echo $parent->url?>) </p>
			
			<?php include Kohana::find_file('views', 'kohanut/admin/errors') ?>
			
			<ul class="standardform">
				<form method="post">
				<li>
					<label>Navigation Name</label>
					<?php echo $newpage->input('name') ?>
				</li>
				
				<li>
					<label>URL</label>
					<?php echo $newpage->input('url') ?>
				</li>
				
				<li>
					<label for="islink">External Link?</label>
					<?php echo $newpage->input('islink',array('class'=>'check')) ?>
				</li>
				
				<li>
					<label for="islink">Show in Navigation?</label>
					<?php echo $newpage->input('shownav',array('class'=>'check')) ?>
				</li>
				
				<li>
					<label for="islink">Show in Site Map?</label>
					<?php echo $newpage->input('showmap',array('class'=>'check')) ?>
				</li>
				
				<li>
					<label>Location</label>
					<select name="location">
						<option value="first">First Child</option>
						<?php foreach( $parent->children() as $child): ?>
							<option value="<?php echo $child->id ?>">After <?php echo $child->name ?></option>
						<?php endforeach; ?>
						<option selected="selected" value="last">Last Child</option>
					</select>
				</li>
				
				<li>
					<label>Layout</label>
					<?php echo $newpage->input('layout') ?>
				</li>
				<?php echo Form::submit('submit','Add page',array('class'=>'submit')) ?>
				</form>
			</ul>
			
			
			
			<div class="clear"></div>
			
		</div>
	</div>
	
</div>
