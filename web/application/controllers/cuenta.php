<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class cuenta extends CI_Controller {

	public function index()
	{
		
	}
	
	public function crear($id = "")
	{
		//verificacion de session
		if(!$this->session->userdata('isLoggedIn'))
		{
			redirect('user');
		}
	
		$url = "http://63.247.95.44/martin/funciones.php?funcion=crear_usuario_cuenta";
		$url = $url . "&". $this->session->userdata('idSession');
		$id = "&id=".$id;
		$comision_tipo = "&comision_tipo=".$this->input->post('comision_tipo');
		$comision = "&comision=".$this->input->post('comision');
		$nombre = "&nombre=".$this->input->post('nombre');
	
		$url = $url .$id .$comision_tipo .$comision .$nombre;
	
		$response = $this->fn_curl($url);
			
		if($response['respuesta']<="-1")
		{
			//hubo un error en la operacion
			$this->show_create($response['resultado']);
		}
		else
		{
			//operacion exitosa
			//$this->show_create($response['resultado']);
			redirect('user');
		}
	}
	
	public function habilitar($id = "", $cuenta = "")
	{
		//verificacion de session
		if(!$this->session->userdata('isLoggedIn'))
		{
			redirect('user');
		}
	
		$url = "http://63.247.95.44/martin/funciones.php?funcion=habilitar_cuenta";
		$url = $url . "&". $this->session->userdata('idSession');
		$cuenta = "&cuenta=".$cuenta;
		$url = $url .$cuenta;
	
		$response = $this->fn_curl($url);
			
		if($response['respuesta']<="-1")
		{
			//hubo un error en la operacion
			$this->show_create($response['resultado']);
		}
		else
		{
			//operacion exitosa
			//$this->show_create($response['resultado']);
			redirect('user');
		}
	}
	
	public function deshabilitar($id = "", $cuenta = "")
	{
		//verificacion de session
		if(!$this->session->userdata('isLoggedIn'))
		{
			redirect('user');
		}
	
		$url = "http://63.247.95.44/martin/funciones.php?funcion=deshabilitar_cuenta";
		$url = $url . "&". $this->session->userdata('idSession');
		$cuenta = "&cuenta=".$cuenta;
		$url = $url .$cuenta;
	
		$response = $this->fn_curl($url);
			
		if($response['respuesta']<="-1")
		{
			//hubo un error en la operacion
			$this->show_create($response['resultado']);
		}
		else
		{
			//operacion exitosa
			//$this->show_create($response['resultado']);
			redirect('user');
		}
	}
	
	private function show_create($error = FALSE)
	{
		$res['titulo'] = "Crear Cuenta";
		$res['tipoUser'] = $this->session->userdata('tipo');
		$res['login'] = $this->session->userdata('isLoggedIn');
		if(!$error)
			$error = "";
	
		$res['error'] = $error;
		$this->load->view('header',$res);
		$this->load->view('cuenta/crear',$res);
		$this->load->view('footer',$res);
	}
	
	private function set_key($result)
	{
		$data = array('idSession'=>$result);
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
		$response = json_decode($response,true);
		curl_close($ch);
		if($response && $this->session->userdata('isLoggedIn'))
		{
			$this->set_key($response['llave']);
			//echo $response['llave'];
		}
		
		return $response;
	}
}