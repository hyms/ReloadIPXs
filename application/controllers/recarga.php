<?php
class recarga extends CI_Controller {

	public function index()
	{
		$res['titulo'] = "Recargas";
		$res['operadora'] = array("Entel","Viva","Tigo");
		
		$this->load->helper('form');
		$this->load->view('header',$res);
		$this->load->view('recargas',$res);
		$this->load->view('footer',$res);
	}
	
	public function recargas()
	{
		$this->load->helper('form');
		
		$url = "http://63.247.95.44/martin/funciones.php?funcion=recargar";
		$iduser = "id=6";
		$destino = "destino=7";
		$monto = "monto=". $this->input->post('amount');
		$cuenta = "cuenta=9";
		$destino_cuenta = "destino_cuenta=12";
		$url = $url ."&".$iduser."&".$destino."&".$monto."&".$cuenta."&".$destino_cuenta;
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch); 
		curl_close($ch);
		
		$res['titulo'] = "Recargas";
		$res['result'] = json_decode($response,true);
		 
		$this->load->view('header',$res);
		$this->load->view('recargado',$res);
		$this->load->view('footer',$res);
		
	}
}