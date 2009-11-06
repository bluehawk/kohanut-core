<div class="container_12">
    <div class="grid_8">
        
        <div class="ui-widget">
            <h2 class="ui-widget-header ui-corner-top">
                <img src='/kohanutres/img/fam/link.png' alt="link" class='headericon' />Redirects
            </h2>
            <div class="ui-padding ui-widget-content ui-corner-bottom">
                
               <ul id="redirectlist" class="standardlist">
                     <li class="addli">
                        <a id="showaddform"><p><img src="/kohanutres/img/fam/link_add.png"/>Add a Redirect</p></a>
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
                            <a href="<?php echo $item->url ?>" title="Click to test" ><img src="/kohanutres/img/fam/link_go.png" alt="test" /><br/><span>test</span></a>
                            <a href="/admin/redirects/edit/<?php echo $item->id ?>"><img src="/kohanutres/img/fam/link_edit.png" alt="edit" /><br/><span>edit</span></a>
                            <a href="/admin/redirects/delete/<?php echo $item->id ?>" onclick="javascript:return confirm('Really delete that redirect?')" title="Click to delete"><img src="/kohanutres/img/fam/link_delete.png" alt="delete" /><br/><span>delete</span></a>
                        </div>
                        <a href="/admin/redirects/edit/<?php echo $item->id ?>" ><p>
                            <?php echo $item->url ?><img src="/kohanutres/img/fam/bullet_go.png" alt="redirects to" class="redirecticon" /><?php echo $item->newurl ?>
                            <span class="redirecttype">
                            <?php
                            if ($item->type == "301")
                                echo "permanent (301)";
                            else if ($item->type == "302")
                                echo "temporary (302)";
                            else
                                echo "(error)";
                            ?>
                            </span>
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
            <h2 class="ui-widget-header ui-corner-top">What are Redirects For?</h2>
            <div class="ui-padding ui-widget-content ui-corner-bottom">
                
                <p>You should add a redirect if you move a page or a site, so links on other sites do not break, and search engine rankings are preserved.</p>
                <p>When a user types in the outdated link, or clicks on an outdated link, they will be taken to the new link.</p>
                <p>Redirect type should be permanent (301) in most cases, as this helps to preserve search engine rankings better. Leave it as permanent unless you know what you are doing.</p>
                
            </div>
        </div>
    </div>
    <div class='clear'></div>
</div>
