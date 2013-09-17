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
			$res['result'] = "Operacion realizada exitosamente";
			$res['login'] = $this->session->userdata('isLoggedIn');
			//operacion exitosa
			$this->load->view('header',$res);
			$this->load->view('result',$res);
			$this->load->view('footer',$res);
		}
	}
	
	public function login()
	{
		
		if(!$this->input->post())
		{
			//si no se realizo el envio de formulario
			redirect('user');
		}
		
		//login usuario
		$url = "http://63.247.95.44/martin/funciones.php?funcion=auth_login";
		
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
	
	public function logout()
	{
		if($this->session->userdata('isLoggedIn'))
		{
			$this->session->sess_destroy();
		}
		redirect('user');
	}
	
	public function crear()
	{
		//verificacion de session
		if(!$this->session->userdata('isLoggedIn'))
		{
			redirect('user');
		}
		
		if(!$this->input->post())
		{
			//si no se realizo el envio de formulario
			$this->show_create();
		}
		else 
		{
			$url = "http://63.247.95.44/martin/funciones.php?funcion=crear_usuario&";
			$url = $url . $this->session->userdata('idSession');
			$docIdentidad = "&docIdentidad=".$this->input->post('docIdentidad');
			$nombre = "&nombre=".$this->input->post('nombre');
			$apellido = "&apellido=".$this->input->post('apellido');
			$tipo = "&tipo=".$this->input->post('tipo');
			
			$url = $url . $docIdentidad . $nombre . $apellido . $tipo;
			
			$response = json_decode($this->fn_curl($url),true);
			
			if($response[0]<="-1")
			{
				//hubo un error en la operacion
				$this->show_create($response[1]);
			}
			else
			{
				//operacion exitosa
				//$this->show_create($response[1]);
				redirect('user');
			}
		}
	}
	
	public function crear_login($id = "")
	{
		//verificacion de session
		if(!$this->session->userdata('isLoggedIn'))
		{
			redirect('user');
		}
		if(!$this->input->post())
		{
			$this->show_create_login();
		}
		else
		{
			$url = "http://63.247.95.44/martin/funciones.php?funcion=crear_usuario_login";
			$url = $url . "&". $this->session->userdata('idSession');
			$id = "&id=".$id;
			$username = "&username=".$this->input->post('username');
			$password = "&password=".$this->input->post('password');
			$url = $url .$id .$username .$password;
			
			$response = json_decode($this->fn_curl($url),true);
				
			if($response[0]<="-1")
			{
				//hubo un error en la operacion
				$this->show_create_login($response[1]);
			}
			else
			{
				//operacion exitosa
				//$this->show_create($response[1]);
				redirect('user');
			}
		}
	} 
	
	public function crear_cuenta($id = "")
	{
		//verificacion de session
		if(!$this->session->userdata('isLoggedIn'))
		{
			redirect('user');
		}
		
		$url = "http://63.247.95.44/martin/funciones.php?funcion=crear_usuario_cuenta";
		$url = $url . "&". $this->session->userdata('idSession');
		$id = "&id=".$id;
		$comision_tipo = "&comision_tipo=".$this->input->post('');
		$comision = "&comision=".$this->input->post('');
		$nombre = "&nombre=".$this->input->post('');
		
		$url = $url .$id .$comision_tipo .$comision .$nombre;
		
		$response = json_decode($this->fn_curl($url),true);
			
		if($response[0]<="-1")
		{
			//hubo un error en la operacion
			$this->show_create($response[1]);
		}
		else
		{
			//operacion exitosa
			//$this->show_create($response[1]);
			redirect('user');
		}
	}
	
	public function habilitar_usuario($id = "")
	{
		//verificacion de session
		if(!$this->session->userdata('isLoggedIn'))
		{
			redirect('user');
		}
		$url = "http://63.247.95.44/martin/funciones.php?funcion=crear_usuario_cuenta";
		$url = $url . "&". $this->session->userdata('idSession');
		$id = "&id=".$id;
		$url = $url .$id;
		
		$response = json_decode($this->fn_curl($url),true);
			
		if($response[0]<="-1")
		{
			//hubo un error en la operacion
			$this->show_create($response[1]);
		}
		else
		{
			//operacion exitosa
			//$this->show_create($response[1]);
			redirect('user');
		}
	}
	
	public function deshabilitar_usuario($id = "")
	{
		//verificacion de session
		if(!$this->session->userdata('isLoggedIn'))
		{
			redirect('user');
		}
		$url = "http://63.247.95.44/martin/funciones.php?funcion=crear_usuario_cuenta";
		$url = $url . "&". $this->session->userdata('idSession');
		$id = "&id=".$id;
		$url = $url .$id;
		
		$response = json_decode($this->fn_curl($url),true);
			
		if($response[0]<="-1")
		{
			//hubo un error en la operacion
			$this->show_create($response[1]);
		}
		else
		{
			//operacion exitosa
			//$this->show_create($response[1]);
			redirect('user');
		}
	}
	
	public function habilitar_cuenta($id = "", $cuenta = "")
	{
		//verificacion de session
		if(!$this->session->userdata('isLoggedIn'))
		{
			redirect('user');
		}
		
		$url = "http://63.247.95.44/martin/funciones.php?funcion=crear_usuario_cuenta";
		$url = $url . "&". $this->session->userdata('idSession');
		$cuenta = "&cuenta=".$cuenta;
		$url = $url .$cuenta;
		
		$response = json_decode($this->fn_curl($url),true);
			
		if($response[0]<="-1")
		{
			//hubo un error en la operacion
			$this->show_create($response[1]);
		}
		else
		{
			//operacion exitosa
			//$this->show_create($response[1]);
			redirect('user');
		}
	}
	
	public function deshabilitar_cuenta($id = "", $cuenta = "")
	{
		//verificacion de session
		if(!$this->session->userdata('isLoggedIn'))
		{
			redirect('user');
		}
		
		$url = "http://63.247.95.44/martin/funciones.php?funcion=crear_usuario_cuenta";
		$url = $url . "&". $this->session->userdata('idSession');
		$cuenta = "&cuenta=".$cuenta;
		$url = $url .$cuenta;
		
		$response = json_decode($this->fn_curl($url),true);
			
		if($response[0]<="-1")
		{
			//hubo un error en la operacion
			$this->show_create($response[1]);
		}
		else
		{
			//operacion exitosa
			//$this->show_create($response[1]);
			redirect('user');
		}
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
	
	private function show_create_login($error = FALSE)
	{
		$res['titulo'] = "Crear Login";
		$res['login'] = $this->session->userdata('isLoggedIn');
		if(!$error)
			$error = "";
	
		$res['error'] = $error;
		$this->load->view('header',$res);
		$this->load->view('usuario/crear_login',$res);
		$this->load->view('footer',$res);
	}
	
	private function show_create($error = FALSE)
	{
		$res['titulo'] = "Crear Usuario";
		$res['login'] = $this->session->userdata('isLoggedIn');
		$res['tipo'] = $this->listar_tipo();
		
		if(!$error)
			$error = "";
		
		$res['error'] = $error;
		$this->load->view('header',$res);
		$this->load->view('usuario/crear',$res);
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
	
	private function listar_tipo()
	{
		$url = "http://63.247.95.44/martin/funciones.php?funcion=listar_usuarios_tipos&";
		$url = $url.$this->session->userdata('idSession');
		
		$response = $this->fn_curl($url);
		
		return json_decode($response,true);
	}
}
/* End of file users.php */
/* Location: ./application/controllers/users.php */