<?php
class trans_model extends CI_Model {

	var $monto			= "";
	var $detalle	 	= "";
	var $fecha 			= "";
	var $estado 		= "";
	var $tipo_trans		= "";
	var $id_operadora	= "";
	var $id_user		= "";
	var $id_cuenta		= "";
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	
}