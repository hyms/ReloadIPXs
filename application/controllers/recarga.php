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
		$response = json_decode($this->fn_curl($url),true);
		
		if($response[0] <= -1)
		{
			$this->show_recarga($response[1]);
		}
		else 
		{
			$res['titulo'] = "Recargas";
			$res['login'] = $this->session->userdata('isLoggedIn');
			$res['result'] = $response;
				
			$this->load->view('header',$res);
			$this->load->view('result',$res);
			$this->load->view('footer',$res);
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
		$response = json_decode($this->fn_curl($url),true);
		
		if($response[0] <= -1)
		{
			$this->show_recarga($response[1]);
		}
		else
		{
			$res['titulo'] = "Recargas";
			$res['login'] = $this->session->userdata('isLoggedIn');
			$res['result'] = $response;
		
			$this->load->view('header',$res);
			$this->load->view('result',$res);
			$this->load->view('footer',$res);
		}
	}
	
	private function show_recarga($error = FALSE)
	{
		$res['titulo'] = "Recargas";
		$res['operadora'] = array("Entel","Viva","Tigo");
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
		$res['login'] = $this->session->userdata('isLoggedIn');
		$res['error'] = $error;
	
		$this->load->helper('form');
		$this->load->view('header',$res);
		$this->load->view('recarga/transferir',$res);
		$this->load->view('footer',$res);
	}
	
	private function fn_curl($url)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_COOKIEJAR,"cookie.txt"); // sesi�n
		curl_setopt($ch, CURLOPT_COOKIEFILE,"cookie.txt"); // sesi�n
		curl_setopt($ch, CURLOPT_COOKIESESSION, "cookie.txt"); // sesi�n
		$response = curl_exec($ch);
		curl_close($ch);
			
		return $response;
	}
	
}