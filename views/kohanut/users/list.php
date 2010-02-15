<div class="grid_12">
	
	<div class="box">
		<h1><?php echo __('Users') ?></h1>
		
		<ul id="userlist" class="standardlist">
		<?php
		$zebra = false;
		if (count($users) > 0)
		{
			foreach($users as $item)
			{
			?>
				<li <?php echo text::alternate('class="z"','') ?> title="<?php echo __('Click to edit') ?>" >
					<div class='actions'>
						<?php
						echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'users','action'=>'edit','params'=>$item->id)),
							 '<div class="fam-user-edit"></div><span>'.__('edit').'</span>',array('title'=>__('Click to edit')));
						echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'users','action'=>'delete','params'=>$item->id)),
							 '<div class="fam-user-delete"></div><span>'.__('delete').'</span>',array('title'=>__('Click to delete')));
						?>
					</div>
					<?php
					echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'users','action'=>'edit','params'=>$item->id)),
					                  "<p>" . $item->username . "</p>");
					?>
				</li>
				
			<?php
			}
			
		}
		else
		{
			echo '<li>'.__('No layouts found').'</li>';
		}
		?>
		</ul>
		
	</div>
	
</div>

<div class="grid_4">
	<div class="box">
		
		<h1><?php echo __('Help') ?></h1>
		<p><?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'users','action'=>'new')),__('Create a New User'),array('class'=>'button')); ?></p>
		
	</div>
</div>
