<div class="grid_12">
	
	<div class="box">
		<h1><?php echo __('Layouts') ?></h1>
		
		<ul id="layoutlist" class="standardlist">
		<?php
		$zebra = false;
		if (count($layouts) > 0)
		{
			foreach($layouts as $item)
			{
			?>
				<li <?php echo text::alternate('class="z"','')?> title="<?php echo __('Click to edit')?>" >
					<div class='actions'>
						<?php
						echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'layouts','action'=>'edit','params'=>$item->id)),
							 '<div class="fam-layout-edit"></div><span>'.__('edit').'</span>',array('title'=>__('Click to edit')));
						echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'layouts','action'=>'delete','params'=>$item->id)),
							 '<div class="fam-layout-delete"></div><span>'.__('delete').'</span>',array('title'=>__('Click to delete')));
						?>
					</div>
					<?php
					echo
					html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'layouts','action'=>'edit','params'=>$item->id)),
								 '<p>' . $item->name . '<small>' . $item->desc . '</small></p>');
					?>
				</li>
				
			<?php
				$zebra = !$zebra;
			}
			
		}
		else
		{
			echo '<li>'.__('No layouts found').'</li>';
		}
		?>
		</ul>
		<div class="clear"></div>
		
	</div>
	
</div>

<div class="grid_4">
	<div class="box">
		<h1><?php echo __('Help') ?></h1>
		<p><?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'layouts','action'=>'new')),__('Create a New Layout'),array('class'=>'button')); ?></p>
		<p>Help goes here</p>
	</div>
</div>