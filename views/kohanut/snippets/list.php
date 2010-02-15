<div class="grid_12">
	
	<div class="box">
		<h1><?php echo __('Snippets') ?></h1>
		
		<ul class="standardlist">
		<?php
		$zebra = false;
		if (count($snippets) > 0)
		{
			foreach($snippets as $item)
			{
			?>
				<li <?php echo text::alternate('class="z"','') ?> title="<?php echo __('Click to edit') ?>" >
					<div class='actions'>
						<?php
						echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'snippets','action'=>'edit','params'=>$item->id)),
							 '<div class="fam-note-edit"></div><span>'.__('edit').'</span>',array('title'=>__('Click to edit')));
						echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'snippets','action'=>'delete','params'=>$item->id)),
							 '<div class="fam-note-delete"></div><span>'.__('delete').'</span>',array('title'=>__('Click to delete')));
						?>
					</div>
					<?php
					echo
					html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'snippets','action'=>'edit','params'=>$item->id)),
								 '<p>' . $item->name . '</p>');
					?>
				</li>
				
			<?php
			}
			
		}
		else
		{
			echo '<li>'.__('No Snippets found.'). '</li>';
		}
		?>
		</ul>
		<div class="clear"></div>
		
	</div>
	
</div>

<div class="grid_4">
	<div class="box">
		<h1><?php echo __('Help') ?></h1>
		<p><?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'snippets','action'=>'new')),__('Create a New Snippet'),array('class'=>'button')); ?></p>
		<p>Help goes here</p>
	</div>
</div>