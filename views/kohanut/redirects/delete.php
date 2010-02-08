<div class="grid_16">
	
	<div class="box">
		<h1>Delete Redirect</h1>
		
		<p style="color:red;font-weight:bold;">Are you sure you want to delete the redirect from &quot;<?php echo $redirect->url ?>&quot; to &quot;<?php echo $redirect->newurl ?>&quot;? This cannot be undone.</p>
			
		<?php include Kohana::find_file('views', 'kohanut/errors') ?>
		
		<?php echo Form::open() ?>
			<p>
				<input type="submit" name="submit" value="Yes, delete it." class="submit" />
				<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'redirects')),'cancel'); ?>
			</p>
		</form>
		
	</div>
	
</div>