<?
/*
  CLASE PARA LA GENERACION DE CODIGO DE CONTROL VER. 7.0
  Enero 2008
  AMERINST
*/


class facturacion{

  function facturacion(){
  
  }
  
  function getInvierteCadena($cadena){
     $j = strlen($cadena)-1;
     $cadenaInv ="";
     for($i=0;strlen($cadena)>$i;$i++){
        $cadenaInv = $cadenaInv.$cadena[$j];	
	$j--;
     }
     
     return $cadenaInv;
  
  }
  
  function getVerhoeff($cifra){
      
    $mul = array(
	  array(0,1,2,3,4,5,6,7,8,9),
	  array(1,2,3,4,0,6,7,8,9,5),
          array(2,3,4,0,1,7,8,9,5,6),
	  array(3,4,0,1,2,8,9,5,6,7),
	  array(4,0,1,2,3,9,5,6,7,8),
	  array(5,9,8,7,6,0,4,3,2,1),
	  array(6,5,9,8,7,1,0,4,3,2),
	  array(7,6,5,9,8,2,1,0,4,3),
	  array(8,7,6,5,9,3,2,1,0,4),
	  array(9,8,7,6,5,4,3,2,1,0),
			  );
   $per = array(array(0,1,2,3,4,5,6,7,8,9),
  	  array(1,5,7,6,2,8,3,0,9,4),
	  array(5,8,0,3,7,9,6,1,4,2),
	  array(8,9,1,6,0,4,3,5,2,7),
	  array(9,4,5,3,1,2,6,8,7,0),
	  array(4,2,8,6,5,7,3,9,0,1),
	  array(2,7,9,3,8,0,6,4,1,5),
	  array(7,0,4,6,9,1,3,2,5,8), );				  
	  
    $inv = array(0,4,3,2,1,5,6,7,8,9);	  
    
    $check = 0;
    
    $cadena = $cifra;
    
    $numeroInvertido = $this->getInvierteCadena($cadena);
    
    for($i=0;strlen($numeroInvertido)>$i;$i++){
      
      $check = $mul[$check][$per[($i+1)%8][$numeroInvertido[$i]]];
     
    }
    			  
    return $inv[$check]; 
  }
  
  
  function rellenaCero($valor){
     if(strlen($valor)<2){
       $valor = "0".$valor;
     
     }
     
     return $valor;
  
  }
  
  function cifrarMensajeRC4($mensaje,$key){
     $state = array();
     
     $x=0; $y=0; $index1=0; $index2=0;	
     $mensajeCifrado = "";
     
     for($i=0;256>$i;$i++){
        $state[$i] = $i;  
     }
  
     for($i=0; 256>$i;$i++){
        $index2 = (ord($key[$index1]) + $state[$i] + $index2)%256;
	
		$aux = $state[$i];
	    $state[$i] = $state[$index2];
		$state[$index2] = $aux;
		$index1 = ($index1 + 1)%strlen($key);
     }
     
     for($i=0;strlen($mensaje)>$i;$i++){
       $x = ($x+1)%256;
       $y = ($state[$x]+$y)%256;
       $aux = $state[$x];
       $state[$x] = $state[$y];
       $state[$y] = $aux;	
       $NMen = ord($mensaje[$i]) ^ $state[($state[$x]+$state[$y])%256];
       $mensajeCifrado = $mensajeCifrado."-".$this->rellenaCero(dechex($NMen)); 
     }
     
     return strtoupper(substr($mensajeCifrado,1,strlen($mensajeCifrado)-1));
  }
  
  function obtenerBase64($numero){
     $diccionario = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz+/";
     $cociente = 1;
     
     $palabra = "";

     while($cociente > 0){
       $cociente = (int) ($numero / 64);
       $resto    = $numero%64;
       //echo $diccionario[$resto]."<br>";
       //echo $cociente."----->".$resto."<br>";
       $palabra = $diccionario[$resto].$palabra;
       $numero = $cociente;
     }
     
     return $palabra;
  }
  
  function generarCodigo($nroFactura,$nit,$fechaTransaccion,$montoTransaccion,$llave,$nroAutoriza){
    //echo "<br>";
    $s_nroFactura       = $nroFactura.$this->getVerhoeff($nroFactura."").$this->getVerhoeff($nroFactura.$this->getVerhoeff($nroFactura.""));
    //echo "<br>";
    $s_nit              = $nit.$this->getVerhoeff($nit."").$this->getVerhoeff($nit.$this->getVerhoeff($nit."")); 
    //"<br>";
    $s_fechaTransaccion = $fechaTransaccion.$this->getVerhoeff($fechaTransaccion."").$this->getVerhoeff($fechaTransaccion.$this->getVerhoeff($fechaTransaccion."")); 
    //echo "<br>";
    $s_montoTransaccion = $montoTransaccion.$this->getVerhoeff($montoTransaccion."").$this->getVerhoeff($montoTransaccion.$this->getVerhoeff($montoTransaccion."")); 
    //echo "<br>";
    
    $suma = $s_nroFactura + $s_nit + $s_fechaTransaccion + $s_montoTransaccion;
    //echo "<br>";
    $digitos5[0]  = $this->getVerhoeff($suma."");
    $digitos5[1]  = $this->getVerhoeff($suma.$digitos5[0]."");
    $digitos5[2]  = $this->getVerhoeff($suma.$digitos5[0].$digitos5[1]."");
    $digitos5[3]  = $this->getVerhoeff($suma.$digitos5[0].$digitos5[1].$digitos5[2]."");
    $digitos5[4]  = $this->getVerhoeff($suma.$digitos5[0].$digitos5[1].$digitos5[2].$digitos5[3]."");
    //echo "<br>";
	//echo "Verhoeff----->".$digitos5[0].$digitos5[1].$digitos5[2].$digitos5[3].$digitos5[4];
    $digitos51 = array();
    for($i=0;count($digitos5)>$i;$i++){
       //$aux = $digitos5[$i]+1;
       $digitos51[$i] = $digitos5[$i]+1;
    }    
    
    //$cadena = array();
    //echo "<br>";
	//echo "suma mas 1 de digitos51-->".$digitos51[0].$digitos51[1].$digitos51[2].$digitos51[3].$digitos51[4];
	//echo "<br>";
    $s_nroAutoriza      = $nroAutoriza.substr($llave,0,$digitos51[0]);
    //echo "<br>";
    $s_nroFactura       = $s_nroFactura.substr($llave,$digitos51[0],$digitos51[1]); 
    //echo "<br>"; 
    $s_nit              = $s_nit.substr($llave,$digitos51[0]+$digitos51[1],$digitos51[2]);
    //echo "<br>";
    $s_fechaTransaccion = $s_fechaTransaccion.substr($llave,$digitos51[0]+$digitos51[1]+$digitos51[2],$digitos51[3]);
    //echo "<br>";
    $s_montoTransaccion = $s_montoTransaccion.substr($llave,$digitos51[0]+$digitos51[1]+$digitos51[2]+$digitos51[3],$digitos51[4]);

    //echo "<br>";    
    $concatenada = $s_nroAutoriza.$s_nroFactura.$s_nit.$s_fechaTransaccion.$s_montoTransaccion;
    //echo "<br>";
    //$llave.$digitos5;
    //echo "<br>";
	$digitos5 = $digitos5[0].$digitos5[1].$digitos5[2].$digitos5[3].$digitos5[4];
    $cad = $this->cifrarMensajeRC4($concatenada."",$llave.$digitos5."");
	//echo "<br>";
    $cad = ereg_replace('-','',$cad);
	//echo "<br>";
    $index1=0; $index2=1; $index3=2; $index4=3; $index5=4; 
    $sum1=0;
    for($i=0;strlen($cad)>$i;$i++){
       if($index1==$i){
           $sum1 = $sum1 + ord($cad[$index1]);
	       $index1 = $index1 + 5 ;
       }
       
       if($index2==$i){
          $sum2 = $sum2 + ord($cad[$index2]);
	  $index2 = $index2 + 5 ;
       }
       
       if($index3==$i){
          $sum3 = $sum3 + ord($cad[$index3]);
	  $index3 = $index3 + 5 ;
       }
       
       if($index4==$i){
          $sum4 = $sum4 + ord($cad[$index4]);
	  $index4 = $index4 + 5 ;
       }
       
       if($index5==$i){
          $sum5 = $sum5 + ord($cad[$index5]);
	      $index5 = $index5 + 5 ;
       }
       
    }
    
    $st = $sum1 + $sum2 + $sum3 + $sum4 + $sum5;
    //echo "<br>";
    $sum1 = (int)(($st*$sum1)/$digitos51[0]); 
    //cho "<br>";
    $sum2 = (int)(($st*$sum2)/$digitos51[1]); 
    //echo "<br>";
    $sum3 = (int)(($st*$sum3)/$digitos51[2]); 
    //echo "<br>";
    $sum4 = (int)(($st*$sum4)/$digitos51[3]); 
    //echo "<br>";
    $sum5 = (int)(($st*$sum5)/$digitos51[4]); 
    
    $sumST = $sum1 + $sum2 + $sum3 + $sum4 + $sum5;    
    //echo "<br>";
    $base64 = $this->obtenerBase64($sumST."");
    //echo "<br>";
    $codigo = $this->cifrarMensajeRC4($base64,$llave.$digitos5);
    
    return $codigo;
  }

}

?>