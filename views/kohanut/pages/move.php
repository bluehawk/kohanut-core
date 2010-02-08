<div class="grid_12">
	
	<div class="box">
		<h1>Move Page</h1>
		
		<?php echo Form::open(); ?>
			<p><label>Move "<?php echo $page->name ?>" to </label>
				<?php
				echo Form::select('action',array(
					'before'=>'before',
					'after'=>'after',
					'first'=>'first child of',
					'last'=>'last child of',
				));
				echo Form::select('target',$page->select_list('id','name','&nbsp;&nbsp;&nbsp;'));
				?>
			</p>
			
			<p>
				<?php echo Form::submit('submit','Move Page',array('class'=>'submit')); ?>
				<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'pages')),'cancel'); ?>
			</p>
			
			<div class="clear"></div>
		</form>
		
	</div>
	
</div>

<div class="grid_4">
	
	<div class="box">
		<h1>Help</h1>
		<div class="content">
			
			<p>To move this page to a new location, use the drop downs to choose the new location for the page.</p>
			
			<p><strong>Note:</strong> This will move the page, and all of its children to the new location.</p>
			
			<p>Example, If you selected &quot;before&quot; and &quot;Products&quot; the page would be moved to before Products.</p>
			
		</div>
	</div>
	
</div>
