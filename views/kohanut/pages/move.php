<div class="grid_12">
	
	<div class="box">
		<h1><?php echo __('Move Page') ?></h1>
		
		<?php echo Form::open(); ?>
			<p><label><?php echo __('Move ":page" to',array(':page'=>$page->name)) ?></label>
				<?php
				echo Form::select('action',array(
					'before'=>__('before'),
					'after'=>__('after'),
					'first'=>__('first child of'),
					'last'=>__('last child of'),
				));
				echo Form::select('target',$page->select_list('id','name','&nbsp;&nbsp;&nbsp;'));
				?>
			</p>
			
			<p>
				<?php echo Form::submit('submit',__('Move Page'),array('class'=>'submit')); ?>
				<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'pages')),__('cancel')); ?>
			</p>
			
			<div class="clear"></div>
		</form>
		
	</div>
	
</div>

<div class="grid_4">
	
	<div class="box">
		<h1><?php echo __('Help') ?></h1>
		<div class="content">
			
			<p><?php echo __('To move this page to a new location, use the drop downs to choose the new location for the page.<br/><br/>This will move the page, and all of its children to the new location.<br/><br/>Example: If you selected "before" and "Products" the page would be moved to before Products.') ?></p>
			
		</div>
	</div>
	
</div>
