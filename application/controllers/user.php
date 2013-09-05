<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user extends CI_Controller {
	
	public function index()
	{
		$this->load->library('parse/parseRestClient');
		$this->load->library('parse/parseUser');
		
		//$res['resul'] = $this->parseUser->signup();
		$res['resul'] = $this->parseuser->username ='ipxserver';
		$res['resul'] = $this->parseuser->password ='4rc4ng3l';
		$res['resul'] = $this->parseuser->login();
		$this->load->view('Base');
	}
}
/* End of file users.php */
/* Location: ./application/controllers/users.php */