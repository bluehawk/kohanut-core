<div class="grid_12">
	
	<div class="box">
		<h1>Layouts</h1>
		
		<ul id="layoutlist" class="standardlist">
		<?php
		$zebra = false;
		if (count($layouts) > 0)
		{
			foreach($layouts as $item)
			{
			?>
				<li <?php if ($zebra) echo "class='z' " ?> title="Click to edit" >
					<div class='actions'>
						<?php
						echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'layouts','action'=>'edit','params'=>$item->id)),
							 html::image( Route::get('kohanut-media')->uri(array('file'=>'img/fam/layout_edit.png')) ) . 
							 "<br/><span>edit</span>",array('title'=>'Click to edit'));
						echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'layouts','action'=>'delete','params'=>$item->id)),
							 html::image( Route::get('kohanut-media')->uri(array('file'=>'img/fam/layout_delete.png')) ) . 
							 "<br/><span>delete</span>",array('title'=>'Click to delete'));
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
			echo "<li>No layouts found</li>";
		}
		?>
		</ul>
		<div class="clear"></div>
		
	</div>
	
</div>

<div class="grid_4">
	<div class="box">
		<h1>Help</h1>
		<p><?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'layouts','action'=>'new')),"Create a new Layout",array('class'=>'button')); ?></p>
		<p>Help goes here</p>
	</div>
</div>