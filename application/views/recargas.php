
<?php 

echo form_open('recarga/recargas');

$mobile = array('id' => 'mobile_id','name' => 'mobileNumber','maxlength' => '8', 'type'=>'text');
$operator = 'operator';
$amount = array('id' => 'amount_id','name' => 'amount','maxlength' => '4', 'type'=>'text');
$input = array('id' => 'input_id','name' => 'input');
?>

<dl><dt>
	<?php echo form_label("N&uacute;mero de Celular"); ?>
</dt>
<dd>
	<?php echo form_input($mobile); ?>
	<span id="mobno_err_span" style="display: none;" class="error-red fright">Por favor ingrese un n&uacute;mero valido.</span>
</dd>
</dl>
<dl><dt>
	<?php echo form_label("Operadora"); ?>
</dt>
<dd>
<div>
	<?php echo form_dropdown($operator,$operadora)?>
</div>
	<span id="valid_oprt" style="display: none;" class="error-red fright">Por favor selecciona un operador</span>
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
echo form_submit($input,'Procesar Recarga');
echo form_close();
?>

