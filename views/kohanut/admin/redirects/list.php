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
                    <div class="actions">
                        <a href="<?php echo $item->url ?>" title="Click to test" ><img src="/kohanutres/img/fam/link_go.png" alt="test" /><br/><span>test</span></a>
                        <a href="/admin/redirects/edit/<?php echo $item->id ?>"  title="Click to edit"><img src="/kohanutres/img/fam/link_edit.png" alt="edit" /><br/><span>edit</span></a>
                        <a href="/admin/redirects/delete/<?php echo $item->id ?>" title="Click to delete"><img src="/kohanutres/img/fam/link_delete.png" alt="delete" /><br/><span>delete</span></a>
                    </div>
                    <a href="/admin/redirects/edit/<?php echo $item->id ?>" ><p>
                        <?php echo $item->url ?><img src="/kohanutres/img/fam/bullet_go.png" alt="redirects to" class="redirecticon" /><?php echo $item->newurl ?>
                        <small>
                        <?php
                        if ($item->type == "301")
                            echo "permanent (301)";
                        else if ($item->type == "302")
                            echo "temporary (302)";
                        else
                            echo "(error)";
                        ?>
                        </small>
                    </p></a>
                
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
        
        <p><a href="/admin/redirects/new" class="button">Create a New Redirect</a></p>
        
        <h3>What are redirects?</h3>
        <p>You should add a redirect if you move a page or a site, so links on other sites do not break, and search engine rankings are preserved.</p>
        <p>When a user types in the outdated link, or clicks on an outdated link, they will be taken to the new link.</p>
        <p>Redirect type should be permanent (301) in most cases, as this helps to preserve search engine rankings better. Leave it as permanent unless you know what you are doing.</p> 
           
    </div>
</div>
