<div class="grid_12">
	
	<div class="box">
		<h1>Snippets</h1>
		
		<ul class="standardlist">
		<?php
		$zebra = false;
		if (count($snippets) > 0)
		{
			foreach($snippets as $item)
			{
			?>
				<li <?php if ($zebra) echo "class='z' " ?> title="Click to edit" >
					<div class='actions'>
						<?php
						echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'snippets','action'=>'edit','params'=>$item->id)),
							 html::image( Route::get('kohanut-media')->uri(array('file'=>'img/fam/note_edit.png')) ) . 
							 "<br/><span>edit</span>",array('title'=>'Click to edit'));
						echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'snippets','action'=>'delete','params'=>$item->id)),
							 html::image( Route::get('kohanut-media')->uri(array('file'=>'img/fam/note_delete.png')) ) . 
							 "<br/><span>delete</span>",array('title'=>'Click to delete'));
						?>
					</div>
					<?php
					echo
					html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'snippets','action'=>'edit','params'=>$item->id)),
								 '<p>' . $item->name . '</p>');
					?>
				</li>
				
			<?php
				$zebra = !$zebra;
			}
			
		}
		else
		{
			echo "<li>No Snippets found</li>";
		}
		?>
		</ul>
		<div class="clear"></div>
		
	</div>
	
</div>

<div class="grid_4">
	<div class="box">
		<h1>Help</h1>
		<p><?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'snippets','action'=>'new')),"Create a new Snippet",array('class'=>'button')); ?></p>
		<p>Help goes here</p>
	</div>
</div>