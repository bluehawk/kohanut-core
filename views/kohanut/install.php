<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Install Kohanut</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    
    <link rel="stylesheet" type="text/css" href="/kohanutres/reset.css" />
	<link rel="stylesheet" type="text/css" href="/kohanutres/kohanut.css" />
	
	<script type="text/javascript" src="/kohanutres/jquery/jquery-1.3.2.min.js" ></script>
	<script type="text/javascript" src="/kohanutres/jquery/jquery.treeview.js"></script>
	<script type="text/javascript" src="/kohanutres/jquery/jquery.cookie.js"></script>
    
    <style type="text/css">

        .logincontainer {
            width:500px;
            margin:120px auto 0;
            border:1px solid #888;
            -moz-border-radius: 4px; -webkit-border-radius: 4px;
            background:white;
        }
        
        .logincontainer .top {
            background:#005014 url('/kohanutres/img/header-bg.gif') top left repeat-x scroll;
            text-align:center;
        }
        
        .logincontainer .content {
            padding:10px;
        }

    </style>
	
</head>
<body>

    <div class="logincontainer">
        
        <div class="top">
            <a href="http://kohanut.com"><img id="headerlogo" alt="Powered by Kohanut" src="/kohanutres/img/logo.png" /></a>
        </div>
        
        <div class="content">
            
<h1>Install Kohanut</h1>

<p>You should have your database settings put into application/config/database.php or this will fail :)</p>

 <?php include Kohana::find_file('views', 'kohanut/admin/errors') ?>

<?php echo form::open(NULL, array('id' => 'login','method'=>'post')) ?>

<label>Set your admin password:</label><br/>
<?php echo form::password('password') ?><br/>
<label>Repeat password:</label><br/>
<?php echo form::password('repeat') ?><br/>
<?php echo form::button(NULL, 'Install', array('type' => 'submit')) ?>

  
<?php echo form::close() ?>
			
			
            
        </div>
        
    </div>
    
</body>
</html>
