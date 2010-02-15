 <div class="grid_12">
	
	<div class="box">
		<h1><?php echo __('Redirects') ?></h1>
			
			
		<ul class="standardlist">
			
		<?php
		$zebra = false;
		if (count($redirects) > 0)
		{
			foreach($redirects as $item)
			{
			?>
				<li <?php echo text::alternate('class="z"','') ?> title="<?php echo __('Click to edit') ?>" >
					<div class='actions'>
						<?php
						echo html::anchor($item->url,
							 '<div class="fam-link-go"></div><span>'.__('test').'</span>',array('title'=>__('Click to test')));
						echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'redirects','action'=>'edit','params'=>$item->id)),
							 '<div class="fam-link-edit"></div><span>'.__('edit').'</span>',array('title'=>__('Click to edit')));
						echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'redirects','action'=>'delete','params'=>$item->id)),
							 '<div class="fam-link-delete"></div><span>'.__('delete').'</span>',array('title'=>__('Click to delete')));
						?>
					</div>
					<?php
					echo
					html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'redirects','action'=>'edit','params'=>$item->id)),
						"<p>" . $item->url .
						html::image(Route::get('kohanut-media')->uri(array('file'=>'img/bullet_go.png')),array('alt'=>'redirects to')) .
						$item->newurl .
						"<small>" .
							(($item->type == "301")?__('permanent').' (301)':'').
							(($item->type == "302")?__('temporary').' (302)':'').
						"</small>" .
						"</p>"
					);
					?>
				</li>
				
			<?php
			}
		}
		else
		{
			echo '<li><p>'.__('No redirects found').'</p></li>';
		}
		?>
		</ul>
			
		</div>
			
	</div>
	
<div class="grid_4">
	<div class="box">
		<h1><?php echo __('Help') ?></h1>
		
		<p><?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'redirects','action'=>'new')),__('Create a New Redirect'),array('class'=>'button')); ?></p>
		
		<h3><?php echo __('What are redirects?') ?></h3>
		<p><?php echo __('You should add a redirect if you move a page or a site, so links on other sites do not break, and search engine rankings are preserved.<br/><br/>When a user types in the outdated link, or clicks on an outdated link, they will be taken to the new link.<br/><br/>Redirect type should be permanent (301) in most cases, as this helps to preserve search engine rankings better. Leave it as permanent unless you know what you are doing.') ?></p> 
		   
	</div>
</div>
