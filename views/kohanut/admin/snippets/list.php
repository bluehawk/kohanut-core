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
					<div class="actions">
						<a href="/admin/snippets/edit/<?php echo $item->id ?>"><img src="/kohanutres/img/fam/note_edit.png" alt="edit" /><br/><span>edit</span></a>
						<!--<a href="/admin/snippets/copy/<?php echo $item->id ?>" title="Click to copy"><img src="/kohanutres/img/fam/note_go.png" alt="copy" /><br/><span>copy</span></a>-->
						<a href="/admin/snippets/delete/<?php echo $item->id ?>" title="Click to delete"><img src="/kohanutres/img/fam/note_delete.png" alt="delete" /><br/><span>delete</span></a>
					</div>
					<a href="/admin/snippets/edit/<?php echo $item->id ?>" ><p>
						<?php echo $item->name ?>
					</p></a>
				
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
		<p><a class="button" href="/admin/snippets/new">Create a New snippet</a></p>
		<p>Help goes here</p>
	</div>
</div>