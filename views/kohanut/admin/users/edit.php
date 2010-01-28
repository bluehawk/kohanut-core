<div class="grid_12">
	
	<div class="box">
		<h1>Edit User</h1>
		
		<?php include Kohana::find_file('views', 'kohanut/admin/errors') ?>
			
			<?php echo Form::open() ?>
			
			<ul>
			<?php foreach ($user->inputs() as $label => $input): ?>
				<p>
					<label><?php echo $label ?></label>
					<?php echo $input ?>
				</p>
			<?php endforeach ?>
			
			<p>
				<input type="submit" name="submit" value="Save Changes" class="submit" />
				<a href="/admin/users/">cancel</a>
			</p>
			
			</form>
	</div>
	
</div>

<div class="grid_4">
	<div class="box">
		<h1>Help</h1>
		<p>Help goes here</p>
	</div>
</div>