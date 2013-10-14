
<?php 

echo form_open('recarga/transferir');

$cuenta = array('id' => 'cuenta_id','name' => 'cuenta','maxlength' => '8', 'type'=>'text');
$amount = array('id' => 'amount_id','name' => 'amount','maxlength' => '4', 'type'=>'text');
$submit = array('id' => 'submit_id','name' => 'submit');
?>

<dl><dt>
	<?php echo form_label("Cuenta"); ?>
</dt>
<dd>
	<?php echo form_input($cuenta); ?>
	<span id="mobno_err_span" style="display: none;" class="error-red fright">Por favor ingrese un n&uacute;mero valido.</span>
</dd>
</dl>

<dl><dt>
<?php echo form_label("Monto"); ?>
</dt>
<dd>
	<?php echo form_input($amount); ?>
	<span id="amount_err_span" style="display: none;" class="error-redlt fright">Por favor ingrese un monto valido.</span>
</dd>
<dd id="messageSpan" style="display: none;">
	<span id="amount_notvalid_span" style="font-size: 12px; display: none;">Lo siento pero no puede realizar la transacci&oacute;n.</span>
</dd>
</dl>
<?php 
echo form_submit($submit,'Procesar Recarga');
echo form_close();
?>
<dd>
	<span id="amount_err_span" class="error-redlt fright"><?php echo $error ?></span>
</dd>
