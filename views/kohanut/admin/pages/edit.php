<div id="side">
	
	<div class="box">
		<h2>Help</h2>
		<div class="content">
			
			<p>blah</p>
			
		</div>
	</div>
	
</div>

<div id="main">
	
	<div class="box">
		<h2><img class="headericon" src="/kohanutres/img/fam/page_edit.png" alt="Editing" />Editing Page: <?php echo $page->name ?></h2>
		<div class="content">
			
			<?php include Kohana::find_file('views', 'kohanut/admin/errors') ?>
			
			<ul class="standardform">
				<form method="post">
					
					<li>
						<label>Navigation Name</label>
						<?php echo $page->input('name'); ?>
					</li>
					<li>
						<label>URL</label>
						<?php echo $page->input('url'); ?>
					</li>
					<li>
						<label>External Link?</label>
						<?php echo $page->input('islink',array('class'=>'check')) ?>
					</li>
					<li>
						<label>Show in Navigation?</label>
						<?php echo $page->input('shownav',array('class'=>'check')) ?>
					</li>
					<li>
						<label>Show in Site Map?</label>
						<?php echo $page->input('showmap',array('class'=>'check')) ?>
					</li>
					
					<li>
						<label>Title</label>
						<?php echo $page->input('title'); ?>
					</li>
					<li>
						<label>Meta keywords</label>
						<?php echo $page->input('metakw'); ?>
					</li>
					<li>
						<label>Meta description</label>
						<?php echo $page->input('metadesc'); ?>
					</li>
					<li>
						<label>Layout</label>
						<?php echo $page->input('layout'); ?>
					</li>
					
					<?php echo Form::submit('submit','Save changes',array('class'=>'submit')) ?>
					
				</form>
			</ul>
			
			<div class="clear"></div>
			
		</div>
	</div>
	
</div>

<script type="text/javascript">
$(document).ready(function(){
	
	
	$("#type_link").click( function(){
		$("#pageinfo").animate({height:"hide"},"slow");
	});
	
	$("#type_page").click( function(){
		$("#pageinfo").animate({height:"show"},"slow");
	});
});
</script>

