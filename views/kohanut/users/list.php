<div class="grid_12">
	
	<div class="box">
		<h1>Users</h1>
		
		<ul id="userlist" class="standardlist">
		<?php
		$zebra = false;
		if (count($users) > 0)
		{
			foreach($users as $item)
			{
			?>
				<li <?php if ($zebra) echo "class='z' " ?> title="Click to edit" >
					<div class='actions'>
						<?php
						echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'users','action'=>'edit','params'=>$item->id)),
							 html::image( Route::get('kohanut-media')->uri(array('file'=>'img/fam/user_edit.png')) ) . 
							 "<br/><span>edit</span>",array('title'=>'Click to edit'));
						echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'users','action'=>'delete','params'=>$item->id)),
							 html::image( Route::get('kohanut-media')->uri(array('file'=>'img/fam/user_delete.png')) ) . 
							 "<br/><span>delete</span>",array('title'=>'Click to delete'));
						?>
					</div>
					<?php
					echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'users','action'=>'edit','params'=>$item->id)),
					                  "<p>" . $item->username . "</p>");
					?>
				</li>
				
			<?php
				$zebra = !$zebra;
			}
			
		}
		else
		{
			echo "<li>No layouts found</li>";
		}
		?>
		</ul>
		
	</div>
	
</div>

<div class="grid_4">
	<div class="box">
		
		<h1>Help</h1>
		<p><?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'users','action'=>'new')),"Create a new User",array('class'=>'button')); ?></p>
		
	</div>
</div>
