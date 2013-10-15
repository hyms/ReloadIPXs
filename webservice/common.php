<?php

ini_set('session.use_cookies', 0);
ini_set('session.use_only_cookies', 0);
ini_set('session.use_trans_sid', 0);
session_start();

//operaciones db
function db_connect()
{
	pg_connect("host=localhost dbname=recarga user=postgres password=") or die('Could not connect: ' . pg_last_error());
}


//listas
function listar_usuarios_tipos()
{
	verificar_session();

	$query = 'SELECT id,nombre FROM usuarios_tipos';
	$result = pg_query($query) or die('Query failed: ' . pg_last_error());
	$resultArray = array();
	
	while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$resultArray[] = $row;
	}
	
	pg_free_result($result);
	respuesta_json("0", $resultArray);
}

function listar_distribuidores()
{
    verificar_session();

    $query = 'select u.id,u.estado,d.nombre,d.apellido,d."docIdentidad" from usuarios u, usuarios_datos d where u.id=d.id and u.estado and u.tipo=1';
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    $resultArray = array();
    
    while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        $resultArray[] = $row;
    }
    
    pg_free_result($result);
    respuesta_json("0", $resultArray);
}

function listar_revendedores()
{
    verificar_session();

    $query = 'select u.id,u.estado,d.nombre,d.apellido,d."docIdentidad" from usuarios u, usuarios_datos d where u.id=d.id and u.estado and u.tipo=2';
	if($_SESSION['tipo']==1)
		$query .= " and u.id in (select revendedor from revendedores_por_distribuidor where distribuidor=".$_SESSION['id'].")";
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    $resultArray = array();
    
    while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        $resultArray[] = $row;
    }
    
    pg_free_result($result);
    respuesta_json("0", $resultArray);
}

function reporte()
{
	verificar_session();
	
	$query = 'select u.id,u.estado,d.nombre,d.apellido,d."docIdentidad" from usuarios u, usuarios_datos d where u.id=d.id and u.estado and u.tipo='.$_SESSION['tipo'];
	
	$result = pg_query($query) or die('Query failed: ' . pg_last_error());
	$resultArray = array();
	
	while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$resultArray[] = $row;
	}
	
	pg_free_result($result);
	respuesta_json("0", $resultArray);
}

//transacciones
function recargar()
{
	verificar_session();

	$id = verificar_id();
	if(!usuario_habilitado($id))
	{
		respuesta_json("-4", "el id=$id no esta hablitado");
	}

	$monto = verificar_monto();

	//cuenta
	$nro_cuentas = usuarios_cuentas_cantidad($id);
	if($nro_cuentas == 0)
	{
		respuesta_json("-1", "el id=$id aun no tiene cuentas");
	}
	else if($nro_cuentas == 1)
	{
		$result = do_select("SELECT id FROM usuarios_cuentas WHERE usuario=$id", "id=$id no existe en usuarios_cuenta", false);
		$row=pg_fetch_row($result);
		$cuenta = $row[0];
		pg_free_result($result);
	}
	else
	{
		if(!isset($_REQUEST['cuenta']))
		{
			respuesta_json("-4", "debe especificar el numero de cuenta");
		}
		$cuenta = trim($_REQUEST['cuenta']);
		if(!($cuenta>0))
		{
			respuesta_json("-1", "Cuenta invalida");
		}
	}

	if(!cuenta_habilitada($cuenta))
	{
		respuesta_json("-4", "la cuenta=$cuenta no esta hablitada");
	}

	$query = "UPDATE usuarios_cuentas SET saldo=saldo+$monto WHERE id=$cuenta";
	$result=pg_query($query);
	if(!$result)
	{
		respuesta_json("-3", "Error en el SQL $query");
	}

	respuesta_json("0", "Se ha acreditado $monto a la cuenta $cuenta");
}

function transferir()
{
	verificar_session();

	$id = $_SESSION['id']; //verificar_id();
	$monto = verificar_monto();

	//tipo usuario
	$tipo = $_SESSION['tipo']; //leer_usuario_tipo($id);
	if(!($tipo==1 or $tipo==2))
	{
		respuesta_json("-4", "el id=$id es del tipo=$tipo y no puede realizar transferencias");
	}

	//cuenta
	$nro_cuentas = usuarios_cuentas_cantidad($id);
	if($nro_cuentas == 0)
	{
		respuesta_json("-1", "el id=$id aun no tiene cuentas");
	}
	else if($nro_cuentas == 1)
	{
		$result = do_select("SELECT id FROM usuarios_cuentas WHERE usuario=$id", "id=$id no existe en usuarios_cuenta", false);
		$row=pg_fetch_row($result);
		$cuenta = $row[0];
		pg_free_result($result);
	}
	else
	{
		if(!isset($_REQUEST['cuenta']))
		{
			respuesta_json("-4", "debe especificar el numero de cuenta");
		}
		$cuenta = trim($_REQUEST['cuenta']);
		if(!($cuenta>0))
		{
			respuesta_json("-1", "Cuenta invalida");
		}
	}

	if(!cuenta_habilitada($cuenta))
	{
		respuesta_json("-4", "la cuenta=$cuenta no esta hablitada");
	}

	//saldo
	$saldo = leer_saldo($cuenta);
	if($saldo < $monto)
	{
		respuesta_json("-1", "La cuenta=$cuenta tiene solo $saldo y no llega a $monto");
	}

	//comision
	$comision = leer_comision($cuenta, $monto);

	//destino
	$destino = verificar_destino();
	if(es_usuario_final($destino))
	{
		$tipo_destino = 3;
		$operadora = leer_operadora($destino);
	}
	else
	{
		if(!usuario_habilitado($destino))
		{
			respuesta_json("-4", "el destino=$destino no esta hablitado");
		}
		$tipo_destino = leer_usuario_tipo($destino);
		if($tipo_destino == 1)
		{
			respuesta_json("-4", "el id=$destino es del tipo=$tipo_destino y no puede recibir transferencias");
		}
	}

	//destino cuenta
	if($tipo_destino == 3) //si es usuario final
	{
		$query = "UPDATE usuarios_cuentas SET saldo=saldo-$monto, ganancia=ganancia+$comision WHERE id=$cuenta";
		$result=pg_query($query);
		if(!$result)
		{
			respuesta_json("-3", "Error en el SQL $query");
		}

		respuesta_json("0", "La transferencia de la cuenta $cuenta a la cuenta $destino_cuenta de la operadora $operadora por $monto con $comision de comision fue exitosa");
	}
	else
	{
		$nro_cuentas = usuarios_cuentas_cantidad($destino);
		if($nro_cuentas == 0)
		{
			respuesta_json("-1", "el destino=$destino aun no tiene cuentas");
		}
		else if($nro_cuentas == 1)
		{
			$result = do_select("SELECT id FROM usuarios_cuentas WHERE usuario=$destino", "destino=$destino no existe en usuarios_cuenta", false);
			$row=pg_fetch_row($result);
			$destino_cuenta = $row[0];
			pg_free_result($result);
		}
		else
		{
			if(!isset($_REQUEST['destino_cuenta']))
			{
				respuesta_json("-4", "debe especificar el numero de cuenta de destino");
			}
			$destino_cuenta = trim($_REQUEST['destino_cuenta']);
			if(!($destino_cuenta>0))
			{
				respuesta_json("-1", "Cuenta de destino invalida");
			}
		}

		if(!cuenta_habilitada($destino_cuenta))
		{
			respuesta_json("-4", "la cuenta destino=$destino_cuenta no esta hablitada");
		}

		if($id == $destino)
			$comision = 0; //Transferencia entre cuentas del mismo usuario

		//acreditacion usuario local (distribuidor o revendedor)
		$query = "UPDATE usuarios_cuentas SET saldo=saldo+($monto-$comision) WHERE id=$destino_cuenta";
		$result=pg_query($query);
		if(!$result)
		{
			respuesta_json("-3", "Error en el SQL $query");
		}
		$query = "UPDATE usuarios_cuentas SET saldo=saldo-$monto, ganancia=ganancia+$comision WHERE id=$cuenta";
		$result=pg_query($query);
		if(!$result)
		{
			respuesta_json("-3", "Error en el SQL $query");
		}

		respuesta_json("0", "La transferencia de la cuenta $cuenta a la cuenta $destino_cuenta por $monto con $comision de comision fue exitosa");
	}
}


//operaciones con usuarios 
function transferir_revendedor()
{
	verificar_session();

	//revendedor
    if(!isset($_REQUEST['revendedor']))
    {
        respuesta_json("-4", "debe especificar el revendedor");
    }
    
    $revendedor = trim($_REQUEST['revendedor']);
    if(!($revendedor>0))
    {
        respuesta_json("-1", "revendedor invalido");
    }
	
    do_select("SELECT id FROM usuarios where id=$revendedor and tipo=2", "el revendedor $revendedor no existe", false);

    //distribuidor
    if(!isset($_REQUEST['distribuidor']))
    {
        respuesta_json("-4", "debe especificar el distribuidor");
    }
    
    $distribuidor = trim($_REQUEST['distribuidor']);
    if(!($distribuidor>0))
    {
        respuesta_json("-1", "distribuidor invalido");
    }
	
    do_select("SELECT id FROM usuarios where id=$distribuidor and tipo=1", "el distribuidor $distribuidor no existe", false);

	$query = "DELETE FROM revendedores_por_distribuidor WHERE revendedor=$revendedor";
	pg_query($query);

	$query = "INSERT INTO revendedores_por_distribuidor (revendedor, dstribuidor) VALUES ($revendedor, $distribuidor)";
	pg_query($query);

	respuesta_json("0", "El revendedor $revendedor ahora pertenece al distribuidor $distribuidor");
}

function habilitar_usuario()
{
	verificar_session();

	$id = verificar_id();
	if(usuario_habilitado($id))
	{
		respuesta_json("-1", "El usuario $id ya esta habilitado");
	}

	$query = "UPDATE usuarios SET estado=true WHERE id=$id";
    $result=pg_query($query);
    
    if(!$result)
    {
        respuesta_json("-3", "Error en el SQL $query");
    }
	respuesta_json("0", "El usuario $id ha sido habilitado");
}

function deshabilitar_usuario()
{
    verificar_session();

    $id = verificar_id();
    if(!usuario_habilitado($id))
    {
        respuesta_json("-1", "El usuario $id ya esta deshabilitado");
    }

    $query = "UPDATE usuarios SET estado=false WHERE id=$id";
    $result=pg_query($query);
    if(!$result)
    {
        respuesta_json("-3", "Error en el SQL $query");
    }
    respuesta_json("0", "El usuario $id ha sido deshabilitado");
}

function habilitar_cuenta()
{
    verificar_session();
    
    if(!isset($_REQUEST['cuenta']))
    {
        respuesta_json("-4", "debe especificar el numero de cuenta");
    }
    $cuenta = trim($_REQUEST['cuenta']);
    if(!($cuenta>0))
    {
        respuesta_json("-1", "Cuenta invalida");
    }
	if(cuenta_habilitada($cuenta))
	{
		respuesta_json("-4", "La cuenta $cuenta ya esta habilitada");
	}

    $query = "UPDATE usuarios_cuentas SET estado=true WHERE id=$cuenta";
    $result=pg_query($query);
    if(!$result)
    {
        respuesta_json("-3", "Error en el SQL $query");
    }
    respuesta_json("0", "La cuenta $id ha sido habilitado");
}

function deshabilitar_cuenta()
{
    verificar_session();
    
    if(!isset($_REQUEST['cuenta']))
    {
        respuesta_json("-4", "debe especificar el numero de cuenta");
    }
    $cuenta = trim($_REQUEST['cuenta']);
    if(!($cuenta>0))
    {
        respuesta_json("-1", "Cuenta invalida");
    }
    if(!cuenta_habilitada($cuenta))
    {
        respuesta_json("-4", "La cuenta $cuenta ya esta deshabilitada");
    }

    $query = "UPDATE usuarios_cuentas SET estado=false WHERE id=$cuenta";
    $result=pg_query($query);
    if(!$result)
    {
        respuesta_json("-3", "Error en el SQL $query");
    }
    respuesta_json("0", "La cuenta $id ha sido deshabilitado");
}

function crear_usuario_login()
{
	verificar_session();

	$id = verificar_id();
    if(!usuario_habilitado($id))
        respuesta_json("-4", "el id=$id no esta hablitado");

	if(!(isset($_REQUEST['username']) and isset($_REQUEST['password']) or isset($_REQUEST['msisdn']) and isset($_REQUEST['PIN'])))
		respuesta_json("-1", "debe definir username o msisdn");

	$username = 'NULL';
	if(isset($_REQUEST['username']))
	{
		$username = trim($_REQUEST['username']);
    	if(strlen($username)>8)
    	{
        	respuesta_json("-1", "username muy largo. Maximo 20 caracteres");
    	}
        $result = do_select("SELECT id FROM usuarios_login WHERE username='$username'", "username ya existe", true);
		pg_free_result($result);
	}

	$password = 'NULL';
	if(isset($_REQUEST['password']))
	{
		$password = trim($_REQUEST['password']);
    	if(strlen($password)>8)
    	{
        	respuesta_json("-1", "password muy largo. Maximo 8 caracteres");
    	}
	}

	$msisdn = 'NULL';
	if(isset($_REQUEST['msisdn']))
	{
		$msisdn = trim($_REQUEST['msisdn']);
		if(strlen($msisdn)>8)
		{
			respuesta_json("-1", "msisdn muy largo. Maximo 8 digitos");
		}
        $result = do_select("SELECT id FROM usuarios_login WHERE msisdn='$username'", "msisdn ya existe", true);
		pg_free_result($result);
	}

	$PIN = 'NULL';
	if(isset($_REQUEST['PIN']))
	{
		$PIN = trim($_REQUEST['PIN']);
    	if(strlen($PIN)!=5)
    	{
        	respuesta_json("-1", "PIN debe tener 5 digitos");
    	}
	}

    $query = "INSERT INTO usuarios_login (id,msisdn,\"PIN\",username,password) VALUES ";
    $query.= "($id,'$msisdn','$PIN','$username','$password')";
    $result=pg_query($query);
    if(!$result)
    {
        respuesta_json("-3", "Error en el SQL $query");
    }

	respuesta_json("-1", "Clave creada exitodamente para usuario $id");
}

function crear_usuario_cuenta()
{
	verificar_session();

    $id = verificar_id();
    if(!usuario_habilitado($id))
    {
        respuesta_json("-4", "el id=$id no esta hablitado");
    }

	//Comision Tipo
    if(!isset($_REQUEST['comision_tipo']))
    {
        respuesta_json("-1", "No se recibio comision_tipo");
    }
    $comision_tipo = trim($_REQUEST['comision_tipo']);

    $result = do_select("SELECT * FROM comisiones_tipos WHERE id=$comision_tipo", "comision tipo id=$comision_tipo no existe", false);
    pg_free_result($result);

    //Comision
    if(!isset($_REQUEST['comision']))
    {
        respuesta_json("-1", "No se recibio comision");
    }
    $comision = trim($_REQUEST['comision']);
	if(!($comision>0))
	{
		respuesta_json("-1", "Comision invalida");
	}

	$saldo=0;
	if(isset($_REQUEST['saldo']))
	{
		$saldo =  trim($_REQUEST['saldo']);
		if(!($saldo >= 0))
		{
			respuesta_json("-1", "Saldo invalido");
		}
	}

	$nombre = 'NULL';
	if(usuarios_cuentas_cantidad($id) > 0)
	{
		if(!isset($_REQUEST['nombre']))
		{
			respuesta_json("-2", "Se requiere un nombre para la cuenta");
		}
		$nombre = trim($_REQUEST['nombre']);
		if(strlen($nombre) == 0)
		{
			respuesta_json("-2", "El campo nombre es requerido y esta vacio");
		}
	}

    $query = "INSERT INTO usuarios_cuentas (usuario,saldo,comision_tipo,comision,nombre) VALUES ";
	$query.= "($id,$saldo,$comision_tipo,$comision,'$nombre') RETURNING id";
    $result=pg_query($query);
    if(!$result)
    {
        respuesta_json("-3", "Error en el SQL $query");
    }

    $row=pg_fetch_row($result);
    $cuenta = $row[0];

	respuesta_json("0", "Cuenta $cuenta del usuario $id creada exitosamente");
}

function crear_usuario()
{
	verificar_session();

	// Documento de Identidad
	if(!isset($_REQUEST['docIdentidad']))
	{
		respuesta_json("-1", "No se recibio docIdentidad");
	}
	$docIdentidad = trim($_REQUEST['docIdentidad']);
	if(!strlen($docIdentidad))
	{
		respuesta_json("-2", "docIndentidad esta vacio");
	}

	//TODO: agregar validaciones de formato de CI o NIT

	$result = do_select("SELECT * FROM usuarios_datos WHERE \"docIdentidad\"='$docIdentidad'", "docIdentidad=$docIdentidad ya existe", true);
	pg_free_result($result);

	//Nombre
    if(!isset($_REQUEST['nombre']))
    {
        respuesta_json("-1", "No se recibio nombre");
    }
    $nombre = trim($_REQUEST['nombre']);
    if(!strlen($nombre))
    {
        respuesta_json("-2", "nombre esta vacio");
    }

    //TODO: agregar validaciones de formato de nombre

    /*$query = "SELECT * FROM usuarios_datos WHERE nombre='$nombre'";
    $result=pg_query($query);
    if(!$result)
    {
        respuesta_json("-3", "Error en el SQL $query");
    }
    if (pg_num_rows($result))
    {
        respuesta_json("-4", "nombre=$nombre ya existe");
    }
    pg_free_result($result); */

	//apellido
    if(!isset($_REQUEST['apellido']))
    {
        respuesta_json("-1", "No se recibio apellido");
    }
    $apellido = trim($_REQUEST['apellido']);
    if(!strlen($apellido))
    {
        respuesta_json("-2", "apellido esta vacio");
    }

    //TODO: agregar validaciones de formato de apellido

    /* $query = "SELECT * FROM usuarios_datos WHERE apellido='$apellido'";
    $result=pg_query($query);
    if(!$result)
    {
        respuesta_json("-3", "Error en el SQL $query");
    }
    if (pg_num_rows($result))
    {
        respuesta_json("-4", "apellido=$apellido ya existe");
    }
    pg_free_result($result); */

    // Ciudad

	$ciudad='NULL';
	if(isset($_REQUEST['ciudad']))
	{
    	$ciudad = trim($_REQUEST['ciudad']);
    	$result = do_select("SELECT * FROM ciudades WHERE id='$ciudad'", "ciudad=$ciudad no existe", false);
    	pg_free_result($result);
	}

	// Tipo (Distribuidor, Revendedor o Usuario Final)
	if(isset($_SESSION['id']) and isset($_SESSION['tipo']))
	{
		$tipo = 2; //Revendedor
	}
	else
	{
    	if(!isset($_REQUEST['tipo']))
        	respuesta_json("-1", "No se recibio tipo");
		$tipo = trim($_REQUEST['tipo']);
    	if(!strlen($tipo))
        	respuesta_json("-2", "tipo esta vacio");
	}

	$result = do_select("SELECT * FROM usuarios_tipos WHERE id='$tipo'", "tipo=$tipo no existe", false);
    pg_free_result($result);

	$fechaNac = 'NULL';
	if(isset($_REQUEST['fechaNac']))
	{
		$fechaNac = trim($_REQUEST['fechaNac']); //dd-mm-aaaa
	}
	$segundoNombre = trim($_REQUEST['segundoNombre']);
	$segundoApellido = trim($_REQUEST['segundoApellido']);
	$direccion = trim($_REQUEST['direccion']);

	$query = "INSERT INTO usuarios (tipo) VALUES ($tipo) RETURNING id";
	$result=pg_query($query);
    if(!$result)
    {
        respuesta_json("-3", "Error en el SQL $query");
    }

	$row=pg_fetch_row($result);
	$id = $row[0];

	$query = "INSERT INTO usuarios_datos (id,nombre,\"segundoNombre\",apellido,\"segundoApellido\",direccion,ciudad,\"fechaNac\",\"docIdentidad\") ";
	$query.= "VALUES ($id,'$nombre','$segundoNombre','$apellido','$segundoApellido','$direccion',$ciudad,$fechaNac,'$docIdentidad')";
    $result=pg_query($query);
    if(!$result)
    {
		$query = "DELETE FROM usuarios WHERE id=$id";
		pg_query($query);
		respuesta_json("-3", "Error en el SQL $query");
    }

	if($_SESSION['tipo'] == 1 and $tipo == 2) //Distribuidor que crea un Proveedor
	{
		$query = "INSERT INTO revendedores_por_distribuidor (revendedor, distribuidor) VALUES ('$id', '".$_SESSION['id']."')";
		pg_query($query);
	}

	respuesta_json("0", "Usuario ID=$id creado exitosamente");
}

function usuarios_cuentas_cantidad($id)
{
	$query = "SELECT count(*) FROM usuarios_cuentas WHERE usuario=$id";
    $result=pg_query($query);
    if(!$result)
    {
        respuesta_json("-3", "Error en el SQL $query");
    }

	$row=pg_fetch_row($result);
	$cantidad = $row[0];

	return $cantidad;	
}

//validadores
function verificar_id()
{
    if(!isset($_REQUEST['id']))
    {
        respuesta_json("-1", "No se recibio id");
    }
    $id = trim($_REQUEST['id']);
    if(!($id>0))
    {
        respuesta_json("-2", "id no valido");
    }

	return $id;
}

function verificar_destino()
{
    if(!isset($_REQUEST['destino']))
    {
        respuesta_json("-1", "No se recibio destino");
    }
    $destino = trim($_REQUEST['destino']);
    if(!($destino>0))
    {
        respuesta_json("-2", "destino no valido");
    }

    return $destino;
}

function leer_saldo($cuenta)
{
	$result = do_select("SELECT saldo FROM usuarios_cuentas WHERE id=$cuenta", "cuenta=$cuenta no existe", false);
    $row=pg_fetch_row($result);
    $saldo = $row[0];
	pg_free_result($result);

	return $saldo;
}

function leer_comision($cuenta,$monto)
{
	$result = do_select("SELECT comision_tipo, comision FROM usuarios_cuentas WHERE id=$cuenta", "cuenta=$cuenta no existe", false);
    $row=pg_fetch_array($result);
    $comision_tipo = $row['comision_tipo'];
	$comision = $row['comision'];
	pg_free_result($result);

	if($comision_tipo == 1) //Fijo
	{
		return $comision;
	}
	else if($comision_tipo == 2) //Porcentaje
	{
		return $monto * $comision;
	}
	else
	{
		respuesta_json("-4", "tipo comision = $comision_tipo desconocido");
	}
}

function leer_usuario_tipo($id)
{
    $result = do_select("SELECT tipo FROM usuarios WHERE id=$id", "id=$id no existe", false);
    $row=pg_fetch_row($result);
    $tipo = $row[0];
	pg_free_result($result);

	return $tipo;
}

function es_usuario_final($id)
{
    $query="SELECT estado FROM usuarios WHERE id=$id";
    $result=pg_query($query);
    if(!$result)
    {
        respuesta_json("-3", "Error en el SQL $query");
    }
    if (pg_num_rows($result) == 0)
		if(strlen($id) == 8)
			return true;

    return false;
}

function leer_operadora($id)
{
	$query = "select id from operadoras_prefijos where substring('$id' from 1 for char_length(prefijo))=prefijo";
    $result=pg_query($query);
    if(!$result)
    {
        respuesta_json("-3", "Error en el SQL $query");
    }
    if (pg_num_rows($result) == 0)
    {
        respuesta_json("-4", "no se encuenta operadora para $id");
    }
    $row=pg_fetch_array($result);
    $id = $row['id'];
	
	return $id;
}

function usuario_habilitado($id)
{
	$query="SELECT estado FROM usuarios WHERE id=$id";
    $result=pg_query($query);
    if(!$result)
    {
        respuesta_json("-3", "Error en el SQL $query");
    }
    if (pg_num_rows($result) == 0)
    {
        respuesta_json("-4", "cuenta=$cuenta no existe");
    }
    $row=pg_fetch_array($result);
    $estado = $row['estado'];

	return $estado=='t';
}

function cuenta_habilitada($id)
{
    $query="SELECT estado FROM usuarios_cuentas WHERE id=$id";
    $result=pg_query($query);
    if(!$result)
    {
        respuesta_json("-3", "Error en el SQL $query");
    }
    if (pg_num_rows($result) == 0)
    {
        respuesta_json("-4", "cuenta=$cuenta no existe");
    }
    $row=pg_fetch_array($result);
    $estado = $row['estado'];

    return $estado=='t';
}

function verificar_monto()
{
    if(!isset($_REQUEST['monto']))
    {
        respuesta_json("-1", "no se recibio el monto");
    }
    $monto = trim($_REQUEST['monto']);
    if(!($monto>0))
    {
        respuesta_json("-1", "monto $monto invalido");
    }
	
    return $monto;
}

function auth_login()
{
	if(isset($_SESSION['id']))
	{
		echo json_encode(array("0", array($_SESSION['id'], SID)));
		exit;
	}

	if(isset($_REQUEST['username']))
	{
		if(!isset($_REQUEST['password']))
		{
			respuesta_json("-1", "no se recibio password");
		}
		$username = trim($_REQUEST['username']);
		$password = trim($_REQUEST['password']);
		$result = do_select("SELECT id FROM usuarios_login WHERE username='$username' AND password='$password'", "usuario o clave invalido", false);
    	$row=pg_fetch_array($result);
    	$id = $row['id'];
		pg_free_result($result);
	
		$tipo = leer_usuario_tipo($id);
		
		$_SESSION['id'] = $id;
		$_SESSION['tipo'] = $tipo;

		echo json_encode(array('respuesta' => "0", 'resultado' => array('id' => $_SESSION['id'], 'tipo' => $_SESSION['tipo'], 'llave' => SID)));
	}
	else if($_REQUEST['msisdn'])
	{
        if(!isset($_REQUEST['PIN']))
        {
            respuesta_json("-1", "no se recibio PIN");
        }
        $username = trim($_REQUEST['msisdn']);
        $password = trim($_REQUEST['PIN']);
        $result = do_select("SELECT id FROM usuarios_login WHERE msisdn='$username' AND PIN='$password'", "usuario o clave invalido", false);
        $row=pg_fetch_array($result);
        $id = $row['id'];
		pg_free_result($result);

		$tipo = leer_usuario_tipo($id);

		$_SESSION['id'] = $id;
		$_SESSION['tipo'] = $tipo;

        echo json_encode(array('respuesta' => "0", 'resultado' => array('id' => $_SESSION['id'], 'tipo' => $_SESSION['tipo'], 'llave' => SID)));
	}
	else
	{
		respuesta_json("-1", "no se recibio usuario");
	}
}

function verificar_session()
{
    if(!isset($_SESSION['id']))
        respuesta_json("-1", "se require usuario autorizado");
    if(!isset($_SESSION['tipo']))
        respuesta_json("-2", "no se puede determinar el tipo de usuario");

	$callers=debug_backtrace();
	$calling=$callers[1]['function'];

	$query = "select f.id from funciones f, funciones_tipos t where f.id=t.funcion and f.nombre='$calling' and tipo=".$_SESSION['tipo'];
	do_select($query, "no tiene autorizacion para esta funcion", false);
	
}

function respuesta_json($err, $texto)
{
	if(!isset($_SESSION['id']))
	{
		echo json_encode(array('respuesta' => $err, 'resultado' => $texto));
		exit;
	}

    $id = $_SESSION['id'];
    $tipo = $_SESSION['tipo'];

	session_regenerate_id(true);

    $_SESSION['id'] = $id;
    $_SESSION['tipo'] = $tipo;

	echo json_encode(array('respuesta' => $err, 'resultado' => $texto, 'llave' => SID));
	exit;
}

function do_select($query, $texto, $with_result)
{
    $result=pg_query($query);
    if(!$result)
        respuesta_json("-10", "Error en el SQL $query");

	if($with_result)
	{
	    if (pg_num_rows($result))
    	    respuesta_json("-11", $texto);
	}
	else
	{
		if (!pg_num_rows($result))
			respuesta_json("-11", $texto);
	}
	
	return $result;
}

?>
