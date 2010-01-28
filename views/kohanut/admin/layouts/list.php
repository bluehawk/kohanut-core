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
					<div class="actions">
						<a href="/admin/layouts/edit/<?php echo $item->id ?>"><img src="/kohanutres/img/fam/layout_edit.png" alt="edit" /><br/><span>edit</span></a>
						<a href="/admin/layouts/copy/<?php echo $item->id ?>" title="Click to copy"><img src="/kohanutres/img/fam/layout_go.png" alt="copy" /><br/><span>copy</span></a>
						<a href="/admin/layouts/delete/<?php echo $item->id ?>" title="Click to delete"><img src="/kohanutres/img/fam/layout_delete.png" alt="delete" /><br/><span>delete</span></a>
					</div>
					<a href="/admin/layouts/edit/<?php echo $item->id ?>" ><p>
						<?php echo $item->name ?>
						<small><?php echo $item->desc ?></small>
					</p></a>
				
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
		<p><a class="button curved-alt" href="/admin/layouts/new">Create a New Layout</a></p>
		<p>Help goes here</p>
	</div>
</div>