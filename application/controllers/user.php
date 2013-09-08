<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user extends CI_Controller {
	
	public function index()
	{
		$this->load->library('parse/parseRestClient');
		$parseUser = new parseUser;
		
		//$res['resul'] = $parseUser->signup('helier','master33');
		$parseUser->username = 'admin';
		$parseUser->password = 'master33';
		$res['resul'] = $parseUser->login();
		//print_r($res['resul']);
		$this->load->view('Base',$res);
	}
}
/* End of file users.php */
/* Location: ./application/controllers/users.php */