<?php
echo form_open('user/crear');

$docIdentidad = array('id' => 'CI_id','name' => 'docIdentidad','maxlength' => '15', 'type'=>'text');
$nombre = array('id' => 'name_id','name' => 'nombre','maxlength' => '20', 'type'=>'text');
$apellido = array('id' => 'ap_id','name' => 'apellido','maxlength' => '20', 'type'=>'text');
$tipod = array('id' => 'type_id','name' => 'tipo');
$submit = array('id' => 'submit_id','name' => 'submit');

//$error = array('id' => 'user_id','name' => 'username','maxlength' => '8', 'type'=>'text');?>

<dl><dt>
	<?php echo form_label("CI/NIT"); ?>
</dt>
<dd>
	<?php echo form_input($docIdentidad); ?>
</dd></dl>

<dl><dt>
	<?php echo form_label("Nombre"); ?>
</dt>
<dd>
	<?php echo form_input($nombre); ?>
</dd></dl>

<dl><dt>
	<?php echo form_label("Apellido"); ?>
</dt>
<dd>
	<?php echo form_input($apellido); ?>
</dd></dl>

<dl><dt>
	<?php echo form_label("Tipo"); ?>
</dt>
<dd>
	<select name="tipo">
	<?php foreach ($tipo as $item): ?>
		<option value='<?php print($item['id'])?>'><?php print($item['nombre']);?></option>
	<?php endforeach; ?>
	</select>
</dd>
</dl>

<?php echo form_submit($submit,'Registrar'); ?>
<p><?php echo form_label($error); ?></p>
<?php echo form_close(); ?>