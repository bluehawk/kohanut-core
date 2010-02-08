<div class="grid_16">
	
	<div class="box">
		<h1>Delete Snippet</h1>
		
		<p style="color:red;font-weight:bold;">Are you sure you want to delete the snippet &quot;<?php echo  $snippet->name ?>&quot;? This cannot be undone.</p>
			
		<?php include Kohana::find_file('views', 'kohanut/errors') ?>
		
		<?php echo Form::open() ?>
			<p>
				<input type="submit" name="submit" value="Yes, delete it." class="submit" />
				<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'snippets')),'cancel'); ?>
			</p>
		</form>
		
	</div>
	
</div>