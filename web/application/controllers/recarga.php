<?php
class recarga extends CI_Controller {

	public function index()
	{
		$this->show_recarga();
	}
	
	public function recargas()
	{
		//si no paso en LogIn
		if(!$this->session->userdata('isLoggedIn'))
		{
			redirect('user');
		}
		
		$url = "http://63.247.95.44/martin/funciones.php?funcion=transferir&".$this->session->userdata('idSession');
		$iduser = "&id=".$this->session->userdata('id');
		$destino = "&destino=".$this->input->post('mobileNumber');
		$monto = "&monto=". $this->input->post('amount');
		$cuenta = "";//&cuenta=9";
		$destino_cuenta = "";//&cuenta=9"
		$url = $url .$iduser .$destino .$monto .$cuenta .$destino_cuenta;
		//echo $url;
		$response = $this->fn_curl($url);
		
		if($response['respuesta'] <= -1)
		{
			$this->show_recarga($response['resultado']);
		}
		else
		{
			$this->show_resultado($response);
		}
	}
	
	public function transferir()
	{
		//si no paso en LogIn
		if(!$this->session->userdata('isLoggedIn'))
		{
			redirect('user');
		}
		
		$url = "http://63.247.95.44/martin/funciones.php?funcion=recargar&".$this->session->userdata('idSession');
		$iduser = "&id=".$this->session->userdata('id');
		$monto = "&monto=". $this->input->post('amount');
		$cuenta = "";//&cuenta=9";
		$url = $url .$iduser .$monto .$cuenta;
		//echo $url;
		$response = $this->fn_curl($url);
		
		if($response['respuesta'] <= -1)
		{
			$this->show_recarga($response['resultado']);
		}
		else
		{
			$this->show_resultado($response);
		}
	}
	
	private function show_recarga($error = FALSE)
	{
		$res['titulo'] = "Recargas";
		$res['operadora'] = array("Entel","Viva","Tigo");
		$res['tipoUser'] = $this->session->userdata('tipo');
		$res['login'] = $this->session->userdata('isLoggedIn');
		$res['error'] = $error;
		
		$this->load->helper('form');
		$this->load->view('header',$res);
		$this->load->view('recarga/recargas',$res);
		$this->load->view('footer',$res);
	}
	
	private function show_transferir($error = FALSE)
	{
		$res['titulo'] = "Transferencias";
		$res['tipoUser'] = $this->session->userdata('tipo');
		$res['login'] = $this->session->userdata('isLoggedIn');
		$res['error'] = $error;
	
		$this->load->helper('form');
		$this->load->view('header',$res);
		$this->load->view('recarga/transferir',$res);
		$this->load->view('footer',$res);
	}
	
	private function show_resultado($resultado = FALSE)
	{
		$res['titulo'] = "Recargas";
		$res['login'] = $this->session->userdata('isLoggedIn');
		$res['result'] = $resultado;
		
		$this->load->view('header',$res);
		$this->load->view('result',$res);
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