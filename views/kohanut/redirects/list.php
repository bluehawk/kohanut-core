 <div class="grid_12">
	
	<div class="box">
		<h1>Redirects</h1>
			
			
		<ul class="standardlist">
			
		<?php
		$zebra = false;
		if (count($redirects) > 0)
		{
			foreach($redirects as $item)
			{
			?>
				<li <?php if ($zebra) echo "class='z' " ?> title="Click to edit" >
					<div class='actions'>
						<?php
						echo html::anchor($item->url,
							 html::image( Route::get('kohanut-media')->uri(array('file'=>'img/fam/link_go.png')) ) . 
							 "<br/><span>test</span>",array('title'=>'Click to test'));
						echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'redirects','action'=>'edit','params'=>$item->id)),
							 html::image( Route::get('kohanut-media')->uri(array('file'=>'img/fam/link_edit.png')) ) . 
							 "<br/><span>edit</span>",array('title'=>'Click to edit'));
						echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'redirects','action'=>'delete','params'=>$item->id)),
							 html::image( Route::get('kohanut-media')->uri(array('file'=>'img/fam/link_delete.png')) ) . 
							 "<br/><span>delete</span>",array('title'=>'Click to delete'));
						?>
					</div>
					<?php
					echo
					html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'redirects','action'=>'edit','params'=>$item->id)),
						"<p>" . $item->url .
						html::image(Route::get('kohanut-media')->uri(array('file'=>'img/fam/bullet_go.png')),array('alt'=>'redirects to')) .
						$item->newurl .
						"<small>" .
							(($item->type == "301")?'permanent (301)':'').
							(($item->type == "302")?'temporary (302)':'').
						"</small>" .
						"</p>"
					);
					?>
				</li>
				
			<?php
				$zebra = !$zebra;
			}
		}
		else
		{
			echo "<li><p>No redirects</p></li>";
		}
		?>
		</ul>
			
		</div>
			
	</div>
	
<div class="grid_4">
	<div class="box">
		<h1>Help</h1>
		
		<p><?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'redirects','action'=>'new')),"Create a new Redirect",array('class'=>'button')); ?></p>
		
		<h3>What are redirects?</h3>
		<p>You should add a redirect if you move a page or a site, so links on other sites do not break, and search engine rankings are preserved.</p>
		<p>When a user types in the outdated link, or clicks on an outdated link, they will be taken to the new link.</p>
		<p>Redirect type should be permanent (301) in most cases, as this helps to preserve search engine rankings better. Leave it as permanent unless you know what you are doing.</p> 
		   
	</div>
</div>
