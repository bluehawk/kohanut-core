<div class="grid_16">
	<div class="box">
		<h1>Delete <?php echo ucfirst($element->type()) ?>?</h1>
		
		<?php include Kohana::find_file('views', 'kohanut/admin/errors') ?>
		
		
		<?php echo form::open() ?>

			<p>Are you sure you want to delete this element?</p>
			<?php if ($element->unique() == TRUE): ?>
			<p style="color:red">This will delete everything inside this element.</p>
			<?php else: ?>
			<p>This will not delete the actual element, just remove it from this page.</p>
			<?php endif; ?>

			<p>
				<?php echo Form::submit('submit','Delete',array('class'=>'submit')) ?>
				<a href="/admin/pages/edit/<?php echo $element->block->page->id ?>">cancel</a>
			</p>
			
		</form>
		
		<div class="clear"></div>
		
	</div>

</div>