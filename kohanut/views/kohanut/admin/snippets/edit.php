<div class="container_12">
    <div class="grid_8">
        
		<div class="ui-widget">
            <h2 class="ui-widget-header ui-corner-top">
				<img class="headericon" src="/kohanutres/img/fam/note_edit.png" alt="edit" />Edit Snippet
			</h2>
            <div class="ui-padding ui-widget-content ui-corner-bottom">
            
                <?php echo $form ?>
                <div class="clear"></div>
                <br/>
                <a onclick="javascript:return confirm('Really Delete it?')" href="/admin/snippets/delete/<?php echo $id ?>"><img src="/kohanutres/img/fam/note_delete.png" style="float:left;padding-right:5px;" alt="delete" /> Delete this snippet </a>
            
            </div>
        </div>
    </div>
    
    <div class="grid_4">
        <div class="ui-widget">
            <h2 class="ui-widget-header ui-corner-top">What are Snippets?</h2>
            <div class="ui-padding ui-widget-content ui-corner-bottom">
            
                <p>Small pieces of code that you can use throughout the site.</p>
            
            </div>
        </div>
    </div>
	<div class='clear' ></div>
</div>
