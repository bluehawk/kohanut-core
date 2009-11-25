<div id="main">
	
	<div class="box">
		<h2>Delete Page</h2>
		<div class="content">
			
			<?php
			
			echo "<p>Are you sure you want to delete the page &quot;$page->name&quot;? <span style='color:red;font-weight:bold'>This is not reversible!<span></p>";
			
			if ($page->has_children()) {
				echo "<p style='color:red;font-weight:bold;'>This page has children. Deleting it will delete all children too. Are you really sure you want to do this?</p>";
			}
			
			echo Form::open();
			
			echo Form::submit('submit','Yes, delete it.');
			
			echo Form::close();
			
			?>
			<div class="clear"></div>
			
			
		</div>
	</div>
	
</div>