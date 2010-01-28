<div class="grid_12">
	
	<div class="box">
		<h1>Users</h1>
		
		<ul id="userlist" class="standardlist">
		<?php
		$zebra = false;
		if (count($users) > 0)
		{
			foreach($users as $user)
			{
			?>
				<li <?php if ($zebra) echo "class='z' " ?> title="Click to edit" >
					<div class="actions">
						<a href="/admin/users/edit/<?php echo $user->id ?>"><img src="/kohanutres/img/fam/user_edit.png" alt="edit" /><br/><span>edit</span></a>
						<a href="/admin/users/delete/<?php echo $user->id ?>" title="Click to delete"><img src="/kohanutres/img/fam/user_delete.png" alt="delete" /><br/><span>delete</span></a>
					</div>
					<a href="/admin/users/edit/<?php echo $user->id ?>" ><p>
						<?php echo $user->username ?>
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
		
	</div>
	
</div>

<div class="grid_4">
	<div class="box">
		
		<h1>Help</h1>
		<p><a href="/admin/users/new/" class="button">Create a new User</a></p>
			
	</div>
</div>
