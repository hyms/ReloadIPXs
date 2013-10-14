<?php
class user_cuenta_model extends CI_Model {

	var $userId		= "";
	var $cuentaId	= "";
	var $fecha		= "";

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
}