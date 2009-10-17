<div id="side">
	
	<div class="box">
		<h2>Help<h2>
		<div class="content">
			
			<h3>Name</h3>
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
			
			<?php echo $form; ?>
			
			<div class="clear"></div>
			
		</div>
	</div>
	
</div>
