<div class="grid_16">
	<div class="box">
		<h1>Adding <?php echo ucfirst($element->type) ?></h1>
		
		<?php include Kohana::find_file('views', 'kohanut/admin/errors') ?>
		
		<form method="post">
			
			<p>
				<label for="which">Select a <?php echo ucfirst($element->type) ?></label>
				<select name="which" id="which">
			<?php foreach ($element->load(NULL,FALSE) as $item): ?>
			
				<option value="<?php echo $item->id?>"><?php echo $item->name ?></option>
			
			<?php endforeach ?>
				</select>
			</p>
			<p>
				<?php echo Form::submit('submit','Add',array('class'=>'submit')) ?>
				<a>cancel</a>
			</p>
			
		</form>
		
		</div>
	</div>

</div>