<?php 
echo form_open('user/crear_login');

$username = array('id' => 'user_id','name' => 'username','maxlength' => '9', 'type'=>'text');
$password = array('id' => 'pass_id','name' => 'password','maxlength' => '9', 'type'=>'password');
$submit = array('id' => 'submit_id','name' => 'submit');

?>

<dl><dt>
	<?php echo form_label("Usuario"); ?>
</dt>
<dd>
	<?php echo form_input($username); ?>
</dd></dl>

<dl><dt>
	<?php echo form_label("Password"); ?>
</dt>
<dd>
	<?php echo form_input($username); ?>
</dd></dl>

<?php echo form_submit($submit,'Ingresar'); ?>
<p><?php echo form_label($error); ?></p>
<?php echo form_close(); ?>