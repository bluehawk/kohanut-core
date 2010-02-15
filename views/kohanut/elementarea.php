<?php

echo "\n<!-- Element Area  $id ($name) -->\n";

if (Kohanut::$adminmode) {
?>

<p class='kohanut_area_title'><?php echo __('Element Area #:num - :name',array(':num'=>$id,':name'=>$name)) ?></p>
<div class='kohanut_area'>
	
<?php
}

echo $content;

if (Kohanut::$adminmode)
{
?>
<div class="kohanut_element_ctl">
	<p class="title"><span class="fam-add inline-sprite"></span><?php echo __('Add New Element') ?></p>
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
	<?php echo form::submit('add',__('Add Element'),array('class'=>'submit')); ?>
	</form>
	<div style="clear:left;"></div>
</div>

</div>
<?php
}
echo "\n<!-- End Content Area $id ($name) -->\n";
