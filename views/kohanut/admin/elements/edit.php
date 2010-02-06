<div class="grid_16">
	<div class="box">
		<h1>Editing <?php echo ucfirst($element->type()) ?></h1>
		
			
		<?php include Kohana::find_file('views', 'kohanut/admin/errors') ?>
		
		
		<?php echo form::open(); ?>

			<?php foreach ($element->inputs() as $label => $input): ?>
			<p>
				<label><?php echo $label ?></label>
				<?php echo $input ?>
			</p>
			<?php endforeach ?>

			<p>
				<?php echo Form::submit('submit','Save Changes',array('class'=>'submit')) ?>
				<a href="/admin/pages/edit/<?php echo $page ?>">cancel</a>
			</p>
			
		</form>
		
		<div class="clear"></div>
		
	</div>
	
</div>
