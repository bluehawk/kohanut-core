<div class="grid_16">
	
	<div class="box">
		<h1>Delete User</h1>
		
		<p style="color:red;font-weight:bold;">Are you sure you want to delete the user &quot;<?php echo  $user->username ?>&quot;? This cannot be undone.</p>
			
		<?php include Kohana::find_file('views', 'kohanut/admin/errors') ?>
		
		<?php echo Form::open() ?>
			<p>
				<input type="submit" name="submit" value="Yes, delete." class="submit" />
				<a href="/admin/users/">cancel</a>
			</p>
		</form>
		
	</div>
	
</div>