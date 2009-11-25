<div id="side">
	
	<div class="box">
		<h2>Help</h2>
		<div class="content">
			
			<p>To move this page to a new location, use the drop downs to choose the new location for the page.</p>
			
			<p><strong>Note:</strong> This will move the page, and all of its children to the new location.</p>
			
			<p>Example, If you selected &quot;before&quot; and &quot;Products&quot; the page would be moved to before Products.</p>
			
		</div>
	</div>
	
</div>

<div id="main">
	
	<div class="box">
		<h2>Move Page</h2>
		<div class="content">
			<?php echo Form::open(); ?>
				<p>Move "<?php echo $page->name ?>" to 
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
				
				<?php echo Form::submit('submit','Submit'); ?>
				
	
				<a class="cancel" href="/admin/pages">cancel</a>
				<br/>
				<div class="clear"></div>
			</form>
		</div>
	</div>

</div>