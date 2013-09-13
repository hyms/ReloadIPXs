<?php

echo form_open('user/login');

$username = array('id' => 'user_id','name' => 'username','maxlength' => '8', 'type'=>'text');
$password = array('id' => 'pass_id','name' => 'password','maxlength' => '8', 'type'=>'password');
$submit = array('id' => 'submit_id','name' => 'submit');

//$error = array('id' => 'user_id','name' => 'username','maxlength' => '8', 'type'=>'text');?>

<dl><dt>
	<?php echo form_label("Usuario"); ?>
</dt>
<dd>
	<?php echo form_input($username); ?>
</dd>
</dl>

<dl><dt>
	<?php echo form_label("Password"); ?>
</dt>
<dd>
	<?php echo form_input($password); ?>
</dd>
</dl>
<?php echo form_submit($submit,'Ingresar'); ?>
<p><?php echo form_label($error); ?></p>
<?php echo form_close(); ?>