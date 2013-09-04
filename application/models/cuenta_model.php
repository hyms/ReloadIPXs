<?php
class cuenta_model extends CI_Model {

	var $saldo		= "";
	var $fecha_reg 	= "";
	var $estado 	= "";
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
}