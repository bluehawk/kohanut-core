<div class="grid_16">
	<div class="box">
		
		<h1><?php echo __('Change Language') ?></h1>

		<?php echo form::open(NULL, array('method' => 'get')) ?>
		<p>
			<?php echo form::select('lang', $translations, I18n::$lang) ?>
		</p>
		<p>
			<?php echo form::submit('submit',__('Change Language'),array('class'=>'submit')) ?>
		</p>
		</form>
	</div>
</div>