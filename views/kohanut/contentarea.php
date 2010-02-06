<?php

echo "\n<!-- Content Area  $id ($name) -->\n";

echo $content;

if (Kohanut::$adminmode)
{
?>
<div class="kohanut_element_ctl">
	<p class="title"><img src="/kohanutres/img/fam/add.png" />Add New Element</p>
	<?php echo form::open() ?>
	<?php echo form::hidden('area',$id); ?>
	<select name="type" style="float:left;margin-right:5px;">
		<?php
		$elements = Sprig::factory('elementtype')->load(NULL,FALSE);
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
<?php
}
echo "\n<!-- End Content Area $id ($name) -->\n";
