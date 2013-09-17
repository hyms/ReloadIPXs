<?php
echo form_open('cuenta/crear');

$comision_tipo = array('id' => 'comision_tipo_id','name' => 'comision_tipo','maxlength' => '15', 'type'=>'text');
$comision = array('id' => 'comision_id','name' => 'comision','maxlength' => '20', 'type'=>'text');
$nombre = array('id' => 'nombre_id','name' => 'nombre','maxlength' => '20', 'type'=>'text');
$submit = array('id' => 'submit_id','name' => 'submit');

//$error = array('id' => 'user_id','name' => 'username','maxlength' => '8', 'type'=>'text');?>

<dl><dt>
	<?php echo form_label("Tipo de Comisión"); ?>
</dt>
<dd>
	<?php echo form_input($comision_tipo); ?>
</dd></dl>

<dl><dt>
	<?php echo form_label("Comision"); ?>
</dt>
<dd>
	<?php echo form_input($comision); ?>
</dd></dl>

<dl><dt>
	<?php echo form_label("Nombre"); ?>
</dt>
<dd>
	<?php echo form_input($nombre); ?>
</dd></dl>


<?php echo form_submit($submit,'Registrar'); ?>
<p><?php echo form_label($error); ?></p>
<?php echo form_close(); ?>