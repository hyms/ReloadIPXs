<?php
class user_model extends CI_Model {
	
	var $nombre			= "";
	var $seg_nombre 	= "";
	var $ap_paterno 	= "";
	var $ap_materno 	= "";
	var $ci_nit 		= "";
	var $id_parent 		= "";
	var $tipo 			= "";
	var $fecha_inscrip 	= "";
	var $fecha_nac 		= "";
	var $estado			= "";
	var $direccion		= "";
	var $ciudad			= "";
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
}