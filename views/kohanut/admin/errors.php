<?php if ( ! empty($errors)): ?>
<ul class="error">
<?php foreach ($errors as $field => $error): ?>
 <li rel="<?php echo $field ?>"><?php echo ucfirst($error) ?></li>
<?php endforeach ?>
</ul>
<?php endif ?>

<?php if ( ! empty($success) AND $success != false): ?>
<div class="success">
	<?php echo $success ?>	
</div>
<?php endif ?>