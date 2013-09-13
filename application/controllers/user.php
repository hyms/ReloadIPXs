<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user extends CI_Controller {
	
	public function index()
	{
		if(!$this->session->userdata('isLoggedIn'))
		{
			$this->show_login();
		}
		else
		{
			$res['titulo'] = "Login";
			$res['result'] = "Ya estas dentro del sistema";
			$res['login'] = $this->session->userdata('isLoggedIn');
			//operacion exitosa
			$this->load->view('header',$res);
			$this->load->view('result',$res);
			$this->load->view('footer',$res);
		}
	}
	
	public function login()
	{
		
		$url = "http://63.247.95.44/martin/funciones.php?funcion=auth_login";
		if(!$this->input->post())
		{
			//si no se realizo el envio de formulario
			redirect('user');
		}
		else 
		{
			//login usuario
			$username = "&username=".$this->input->post('username');
			$password = "&password=".$this->input->post('password');
			$url = $url .$username.$password;
			$response = $this->fn_curl($url);
			$response = json_decode($response,true);
			
			if($response[0]<="-1")
			{
				//si no es usuario se ve si es cliente
				$telefono = "&msisdn=".$this->input->post('username');
				$pin = "&PIN=".$this->input->post('password');
				$url = $url .$username.$password;
				$response = $this->fn_curl($url);
				$response = json_decode($response,true);
			}
			
			if($response[0]<="-1")
			{
				//hubo un error en la operacion
				$this->show_login($response[1]);
			}
			else
			{
				//operacion exitosa
				$this->set_session($response[1]);
				redirect('user');
			}
		
		}
	}
	
	public function logout()
	{
		if($this->session->userdata('isLoggedIn'))
		{
			$this->session->sess_destroy();
		}
		redirect('user');
	}
	
	private function show_login($error = FALSE)
	{
		$res['titulo'] = "Login";
		$res['login'] = $this->session->userdata('isLoggedIn');
		if(!$error)
			$error = "";
		
		$res['error'] = $error;
		$this->load->view('header',$res);
		$this->load->view('usuario/login',$res);
		$this->load->view('footer',$res);
	}
	
	private function set_session($result)
	{
		$data = array('id'=>$result[0],'idSession'=>$result[1],'isLoggedIn'=>TRUE);
		$this->session->set_userdata($data);
	}
	
	private function fn_curl($url)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_COOKIEJAR,"cookie.txt"); // sesión
		curl_setopt($ch, CURLOPT_COOKIEFILE,"cookie.txt"); // sesión
		curl_setopt($ch, CURLOPT_COOKIESESSION, "cookie.txt"); // sesión
		$response = curl_exec($ch);
		curl_close($ch);
			
		return $response;
	}
	
}
/* End of file users.php */
/* Location: ./application/controllers/users.php */