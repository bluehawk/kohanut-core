<?php

echo "\n<!-- Element Area  $id ($name) -->\n";

if (Kohanut::$adminmode) {
?>

<p class='kohanut_area_title'>Element Area #<?php echo $id ?> - <?php echo $name ?></p>
<div class='kohanut_area'>
	
<?php
}

echo $content;

if (Kohanut::$adminmode)
{
?>
<div class="kohanut_element_ctl">
	<p class="title"><?php echo html::image(Route::get('kohanut-media')->uri(array('file'=>'img/fam/add.png'))); ?> Add New Element</p>
	<?php echo form::open() ?>
	<?php echo form::hidden('area',$id); ?>
	<select name="type" style="float:left;margin-right:5px;">
		<?php
		$elements = Sprig::factory('kohanut_elementtype')->load(NULL,FALSE);
		foreach ($elements as $e)
		{
			echo "<option value='{$e->id}'>" . ucfirst($e->name) . "</option>";
		}
		?>
	</select>
	<?php echo form::submit('add','Add Element',array('class'=>'submit')); ?>
	</form>
	<div style="clear:left;"></div>
</div>

</div>
<?php
}
echo "\n<!-- End Content Area $id ($name) -->\n";
