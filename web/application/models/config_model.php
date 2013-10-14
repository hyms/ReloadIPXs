<?php
class config_model extends CI_Model {

	var $nombre			= "";
	var $NIT			= "";
	var $idFacturacion	= "";
	var $correo			= "";
	var $direccion		= "";
	var $ciudad			= "";
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
}