<div id="side">
	
	<div class="box">
		<h2>Help</h2>
		<div class="content">
			
			<p>To move this page to a new location, use the drop downs to choose the new location for the page.</p>
			
			<p><strong>Note:</strong> This will move the page, and all of its children to the new location.</p>
			
			<p>Example, If you selected &quot;before&quot; and &quot;Products&quot; the page would be moved to before Products.</p>
			
		</div>
	</div>
	
</div>

<div id="main">
	
	<div class="box">
		<h2>Move Page</h2>
		<div class="content">
			
			<form action="" method="post" class="standardform" name="formo"> 
				<div><input type="hidden" name="__formo" value="formo" id="__formo" /></div> 
				<p>Move "<?php echo $page->name ?>" to <?php echo $form->action ?> <?php echo $form->position ?></p>
				<?php echo $form->submit ?><a class="cancel" href="/admin/pages">cancel</a>
				<br/>
				<div class="clear"></div>
			</form>
			
		</div>
	</div>
	
</div>