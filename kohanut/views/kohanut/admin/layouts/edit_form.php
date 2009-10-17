<? echo $open ?>

<p><label for='name'>Name:</label><?=$name?><span class="<?= $name->error_msg_class?>"><?=$name->error?><span></p>

<p><label for='desc'>Description (optional):</label><?=$desc?><span class="<?= $desc->error_msg_class?>"><?=$desc->error?><span></p>

<p><label for='code'>Layout Code:</label></p>

<div class="clear"></div>

<p><?php echo $code ?></p>

<div class="clear"></div>

<?= $submit ?>

<a class='cancel' href='/admin/layouts'>cancel</a>
        
 
<? echo $close ?>