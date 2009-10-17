<div class="container_12">
    <div class="grid_8">
        
        <div class="ui-widget">
            <h2 class="ui-widget-header ui-corner-top">
                <img src='/kohanutres/img/fam/note.png' alt="snippet" class='headericon' />Snippet Library
            </h2>
            <div class="ui-padding ui-widget-content ui-corner-bottom">
            
                <ul id="snippetlist" class="standardlist">
                     <li class="addli">
                        <a id="showaddform"><p><img src="/kohanutres/img/fam/note_add.png"/>Add a Snippet</p></a>
                        <div id="addform">
                            <?php echo $form ?>
                        </div>
                    </li>
                <?php
                $zebra = false;
                foreach($list as $item)
                {
                ?>
                    <li <?php if ($zebra) echo "class='z' " ?> title="Click to edit" >
                        <div class="actions">
                            <a href="/admin/snippets/edit/<?php echo $item->id ?>"><img src="/kohanutres/img/fam/note_edit.png" alt="edit" /><br/><span>edit</span></a>
                            <a href="/admin/snippets/delete/<?php echo $item->id ?>" onclick="javascript:return confirm('Really delete that snippet?')" title="Click to delete"><img src="/kohanutres/img/fam/note_delete.png" alt="delete" /><br/><span>delete</span></a>
                        </div>
                        <a href="/admin/snippets/edit/<?php echo $item->id ?>" ><p>
                            <?php echo $item->name ?>
                        </p></a>
                    
                    </li>
                    
                <?php
                    $zebra = !$zebra;
                }
                ?>
                </ul>
                <div class="clear"></div>
            
            </div>
        </div>
        
    </div>
    
    <script type="text/javascript">
    $(document).ready(function(){
        $("#showaddform").click( function(){
            $("#addform").animate({height:"toggle"},"fast");
        });
    
    });
    </script>
    
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
