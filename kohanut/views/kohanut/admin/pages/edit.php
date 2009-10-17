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
		<h2><img class="headericon" src="/kohanutres/img/fam/page_edit.png" alt="Editing" />Editing Page: <?php echo Kohanut::$page->name ?></h2>
		<div class="content">
			
			
			<div id="edittabs">
				<ul>
					<li><a href="#meta">Page Info</a></li>
					<li><a href="#content">Contents</a></li>
				</ul>
				<div id="meta">
					<?php echo $metaform ?>
				</div>
				<div id="content">
					<?php echo $editcontents ?>
				</div>
			</div>
			
			
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

