
// These two lines are required to initialize Express in Cloud Code.
var express = require('express');
var app = express();

// Global app configuration section
app.set('views', 'cloud/views');  // Specify the folder to find templates
app.set('view engine', 'ejs');    // Set the template engine
app.use(express.bodyParser());    // Middleware for reading request body

// This is an example of hooking up a request handler with a specific request
// path and HTTP verb using the Express routing API.
app.get('/gonzalo', function(req, res) {
  res.render('gonzalo', { salida: 'felicidades huraa comienzas a diseñar la interfaz web! y a realizar tu primera interfaz' });
});

app.get('/formulario', function(req, res){
	res.render('formulario',{salida:"INGRESE LOS DATOS QUE SE LE PIDEN ..."});
});

app.get('/saludo', function(req, res){
	res.render('saludo',{salida:"ESTE ES UN SALUDO EN PHP ..."});
});


app.post('/formulario_out', function(req, res) {	
		var o_autorizacion=req.body.nro_autorizacion;
		var o_factura=req.body.nro_factura;
		var o_nit=req.body.nit;
		var o_fecha=req.body.fecha_transaccion;
		var o_monto=req.body.monto_transaccion;
		var o_llave=req.body.llave_dosificacion;
	res.render('formulario_out', {salida:"SALIDA",autorizacion:o_autorizacion,factura:o_factura,nit:o_nit,fecha:o_fecha,monto:o_monto,llave:o_llave});
});

/*
*ESTA ES LA FUNCION PRINCIPAL DESPUES DEL LOGEO
*/
//añadiendo mi primer metodo post
app.post('/principal', function(req, res) {
	Parse.User.logIn(req.body.usuario, req.body.password, {
		success: function(user) {
			//res.render('logins', {salida: "hola amigo  "+ user.id});
			//añadiendo la funcion completa del login
			var query=new Parse.Query("User");
			query.equalTo("objectId",user.id);
			query.find().then(function(results){
					var name=results[0].get("username");
					var estado=results[0].get("estado");
					var activo=results[0].get("activo");
					var iden=results[0].get("objectId");
					//var balance=results[0].get("balance");
					//salida de prueba
					
					//variables a usar en la tabla de logins
					if(estado==1){
						/*
						*introduciendo por defecto el dispositivo de la web o buscar funcion para buscar capturar la IP del equipo
						*/
						var dispositivo="web";
						/*
						*fin introduciendo por defecto el dispositivo de la web
						*/
						var RegLogin = Parse.Object.extend("Login");
						var regLogin = new RegLogin();
						//cargando informacion en la clase Login
						regLogin.save({
							//estos son los campos que se llenan para el registro de los logins de los usuarios o clientes
							code_login: user.id,
							senderapp_login: dispositivo,
							status_login: true
						});
						//creandoo variables para la comunicacion
						var RegComunicacion=Parse.Object.extend("comunicacion");
						var regComunicacion=new RegComunicacion();
						regComunicacion.save({
							//campos a llenar el la comunicacion
							nro_cel_operador_com: name,
							senderapp_com: dispositivo
						},{
						success: function(regComunicacion)
						{
							/*
							*procedimiento para designar el tipo de mensaje 
							*a responder al dispositivo en funcion de su balance
							*/
							var queryCuentas=new Parse.Query("Cuentas");
							queryCuentas.equalTo("usuario",name);
							queryCuentas.find().then(function(results){
									var balance_cuentas=results[0].get("balance");
									//
									var code=0;
									if(balance_cuentas==0){
										code=4;
									}else{
										if(balance_cuentas<=50){
											code=3;
										}else{
											if(balance_cuentas<=100){
												code=2;
											}else{
												if(balance_cuentas<=150){
													code=1;
												}else{
													if(balance_cuentas>150){
														code=0;
													}
												}
											}
										}
									}
									/*
									*Realizando una consulta a la clase "Menasaje"
									*para despachar un mesaje segun su codigo
									*/
									var queryMensajes=new Parse.Query("Mensajes");
									queryMensajes.equalTo("codigo",code);
									queryMensajes.find().then(function(results){
											//var queryCuentas
											var sMensaje=results[0].get("mensaje");
											var sCode=results[0].get("codigo");
											var respuesta={"code":sCode,"Balances":balance_cuentas,"mensaje":sMensaje,"key":regComunicacion.id};
											//response.success(respuesta);
											//res.render('logins', {codigo:sCode,balance:balance_cuentas,texto:sMensaje,key:regComunicacion.id,operador:name});
											res.render('principal', {salida:"estas dentro",usuario:req.body.usuario,password:req.body.password});

									});	
							});
						},
						error: function(user, error) {
						//falla del logeo
							res.render('mensajes', {salida:"ERROR NO SE PUDO REALIZAR LA OPERACION"});
							//response.error("login incorrecto");
						}
						});
					}
					else{
						//response.success("su cuenta no esta activa!");
						//res.render('gonzalo', {salida:"error de logueo"});
						res.render('mensajes', {salida:"SU CUENTA NO SE ENCUENTRA ACTIVA COMUNIQUESE CON SU PROBEEDOR PARA DAR SOLUCION AL PROBLEMA"});
					}
			});
		},
		error: function(user, error) {
		//falla del logeo
			res.render('mensajes', {salida:"DATOS NO VALIDOS !!!"});
			//response.error("login incorrecto");
		}
	});
});

/*
*funcion de logeo
*/
app.post('/logins', function(req, res) {
	var usuario_i=req.body.usuario;
	var password_i=req.body.password;
	Parse.User.logIn(usuario_i, password_i, {
		success: function(user) {
			//res.render('logins', {salida: "hola amigo  "+ user.id});
			//añadiendo la funcion completa del login
			var query=new Parse.Query("User");
			query.equalTo("objectId",user.id);
			query.find().then(function(results){
					var name=results[0].get("username");
					var estado=results[0].get("estado");
					var activo=results[0].get("activo");
					var iden=results[0].get("objectId");
					//var balance=results[0].get("balance");
					//salida de prueba
					
					//variables a usar en la tabla de logins
					if(estado==1){
						/*
						*introduciendo por defecto el dispositivo de la web
						*/
						var dispositivo="web";
						/*
						*fin introduciendo por defecto el dispositivo de la web
						*/
						var RegLogin = Parse.Object.extend("Login");
						var regLogin = new RegLogin();
						//cargando informacion en la clase Login
						regLogin.save({
							//estos son los campos que se llenan para el registro de los logins de los usuarios o clientes
							code_login: user.id,
							senderapp_login: dispositivo,
							status_login: true
						});
						//creandoo variables para la comunicacion
						var RegComunicacion=Parse.Object.extend("comunicacion");
						var regComunicacion=new RegComunicacion();
						regComunicacion.save({
							//campos a llenar el la comunicacion
							nro_cel_operador_com: name,
							senderapp_com: dispositivo
						},{
						success: function(regComunicacion)
						{
							/*
							*procedimiento para designar el tipo de mensaje 
							*a responder al dispositivo en funcion de su balance
							*/
							var queryCuentas=new Parse.Query("Cuentas");
							queryCuentas.equalTo("usuario",name);
							queryCuentas.find().then(function(results){
									var balance_cuentas=results[0].get("balance");
									//
									var code=0;
									if(balance_cuentas==0){
										code=4;
									}else{
										if(balance_cuentas<=50){
											code=3;
										}else{
											if(balance_cuentas<=100){
												code=2;
											}else{
												if(balance_cuentas<=150){
													code=1;
												}else{
													if(balance_cuentas>150){
														code=0;
													}
												}
											}
										}
									}
									/*
									*Realizando una consulta a la clase "Menasaje"
									*para despachar un mesaje segun su codigo
									*/
									var queryMensajes=new Parse.Query("Mensajes");
									queryMensajes.equalTo("codigo",code);
									queryMensajes.find().then(function(results){
											//var queryCuentas
											var sMensaje=results[0].get("mensaje");
											var sCode=results[0].get("codigo");
											var respuesta={"code":sCode,"Balances":balance_cuentas,"mensaje":sMensaje,"key":regComunicacion.id};
											//response.success(respuesta);
											res.render('logins', {codigo:sCode,balance:balance_cuentas,texto:sMensaje,key:regComunicacion.id,operador:name,usuario:usuario_i,password:password_i});
											//res.render('principal', {salida:"estas dentro",usuario:req.body.usuario,password:req.body.password});

									});	
							});
						},
						error: function(user, error) {
							res.render('mensajes', {salida:"NO SE HA PODIDO EFECTUAR SU TRANSACCION !!!"});
						}
						});
					}
					else{
						//response.success("su cuenta no esta activa!");
						res.render('mensajes', {salida:"SU CUENTA NO SE ENCUENTRA ACTIVA COMUNIQUESE CON SU EMPRESA PARA SOLUCIONAR EL PROBLEMA !!!"});
					}
			});
		},
		error: function(user, error) {
			res.render('mensajes', {salida:"NO SE HA PODIDO EFECTUAR SU TRANSACCION !!!"});
		}
	});
});

app.post('/preregistro', function(req, res) {
	var usuario=req.body.usuario_m;
	var password=req.body.password_m;
	res.render('preregistro', {salida:"preregistro activo",usuario_m:usuario,password_m:password});
});

app.post('/post_recarga', function(req, res){
	/*
	*valores para mantener la session
	*/
	var usuario_i=req.body.usuario;
	var password_i=req.body.password;
	
	/*
	*datos para la comprobacion de la transaccion
	*/
	var id_comprobante=req.body.idcomprobante;
	var inEstado=req.body.estado;
	var dispositivo_comprobante=req.body.dispositivo;
	
	/*
	*Efectuando las operaciones de la post recarga
	*/
	//var queryComprobante=new Parse.Query("comprobante");
	//funcion del api rest
	var queryComprobante=new Parse.Query("comprobante");
	queryComprobante.get(id_comprobante,{
		success: function(comprobante){
			var id_transaccion=comprobante.get("transaccion_id_comp");
			//var inEstado=request.params.estado;
			if(inEstado==1){
				//
				comprobante.set("estado_comprobante",true);
				comprobante.save();

				var queryTransaccion=new Parse.Query("transaccion");
				queryTransaccion.get(id_transaccion,{
					success: function(transaccion){
						transaccion.set("estado_trans",true);
						transaccion.save();
						//response.success("datos de salida ff  "+transaccion.id);
						var usuario=transaccion.get("nro_cel_operador");
						//var senderapp=transaccion.get("senderapp_trans");
						var inMonto=transaccion.get("monto");
						/*
						*registrando un nuevo registrode la comunicacion o cola para la siguiente transaccion
						*/
						var RegComunicacion=Parse.Object.extend("comunicacion");
						var regComunicacion=new RegComunicacion();
						regComunicacion.save({
							//campos a llenar el la comunicacion
							nro_cel_operador_com: usuario,
							senderapp_com: dispositivo_comprobante
						},{
						success: function(regComunicacion)
						{
							/*
							*
							*procedimiento para designar el tipo de mensaje 
							*a responder al dispositivo en funcion de su balance
							*
							*/
							var queryCuentas=new Parse.Query("Cuentas");
							queryCuentas.equalTo("usuario",usuario);
							queryCuentas.find({
								success:function(results){
									var saldo=results[0].get("balance");
									var resto=saldo-inMonto;
									results[0].set("balance",resto);
									results[0].save();
									
									var balance_cuentas=results[0].get("balance");

									var code=0;
									if(balance_cuentas==0){
										code=4;
									}else{
										if(balance_cuentas<=50){
											code=3;
										}else{
											if(balance_cuentas<=100){
												code=2;
											}else{
												if(balance_cuentas<=150){
													code=1;
												}else{
													if(balance_cuentas>150){
														code=0;
													}
												}
											}
										}
									}
									/*
									*
									*Realizando una consulta a la clase "Menasaje"
									*para despachar un mesaje segun su codigo
									*
									*/
									var queryMensajes=new Parse.Query("Mensajes");
									queryMensajes.equalTo("codigo",code);
									queryMensajes.find({
										success:function(results){
											//var queryCuentas
											var sMensaje=results[0].get("mensaje");
											var sCode=results[0].get("codigo");
											var respuesta={"key":regComunicacion.id,"balance":balance_cuentas,"code":sCode,"mensaje":sMensaje};
											//response.success(respuesta);
											res.render('post_recarga', {usuario:usuario_i,password:password_i});
										},
										error: function(user, error) {
											res.render('mensajes', {salida:"NO SE HA PODIDO EFECTUAR SU TRANSACCION !!!"});
										}
										
									});	
									//
								},
								error: function(user, error) {
									res.render('mensajes', {salida:"NO SE HA PODIDO EFECTUAR SU TRANSACCION !!!"});
								}
								
							});
						
							
							
						//response.success("parametros no validos gonzalo"+usuario);
						},
						error: function(user, error) {
							res.render('mensajes', {salida:"NO SE HA PODIDO EFECTUAR SU TRANSACCION !!!"});
						}
						
						});
			
						//fin
					},
					error: function(user, error) {
						res.render('mensajes', {salida:"NO SE HA PODIDO EFECTUAR SU TRANSACCION !!!"});
					}
						
				});
				//hasta aqui el error
				
			}else{
				if(inEstado==0){
					res.render('mensajes', {salida:"NO SE HA PODIDO EFECTUAR SU TRANSACCION !!!"});
				}
			}
		},
		error: function(user, error) {
			res.render('mensajes', {salida:"NO SE HA PODIDO EFECTUAR SU TRANSACCION !!!"});
		}
	});
	//res.render('felicitacion', {mensaje:"LA TRANSACCION SE HA REALIZADO EXITOSAMENTE!!!",usuario:usuario_i,password:});
});

/*
*FUNCION DE RECARGAS
*Entradas : llave de la transaccion, numero de operador, numero final, monto de la carga
*Salidas  : identificacion del comprobante, contenido del comprobante, fecha del comprobante
*/
app.post('/recarga', function(req, res) {
	/*
	*datos para mantener sesion
	*/
	var usuario_i=req.body.usuario;
	var password_i=req.body.password;
	var querycomunicacion=new Parse.Query("comunicacion");
	querycomunicacion.get(req.body.key,{
		success: function(comunica){
			var cel_operador=comunica.get("nro_cel_operador_com");
			var operador=req.body.operador;
			var id_comunicacion=comunica.id;
			var numero_final=req.body.numero;
			var senderapp=comunica.get("senderapp_com");
			if(cel_operador==operador){
				var queryuser=new Parse.Query("Cuentas");
				queryuser.equalTo("usuario",cel_operador);
				queryuser.find().then(function(results){
						var inMonto=req.body.carga-0;
						var saldo=results[0].get("balance");
						if(saldo>=inMonto){
							/*
							var resto=saldo-inMonto;
							results[0].set("balance",resto);
							results[0].save();
							*/
							if(saldo!=0){
								/*
								*Registrando las transacciones que se realizan
								*/
								var RTransaccion=Parse.Object.extend("transaccion");
								var rTransaccion=new RTransaccion();

								rTransaccion.save({
									//campos a llenar el la comunicacion
									estado_trans:false,
									key_transaccion:id_comunicacion,
									monto:inMonto,
									nro_cel_final:numero_final,
									nro_cel_operador:cel_operador,
									senderapp_trans:senderapp
								},{
									success: function(transaccion)
									{			
										//verifiacr el ultimo numero del comprobante y aumentar le ++
										var queryComprobante=new Parse.Query("comprobante");
										queryComprobante.select("nro_comprobante");
										queryComprobante.find(). then (function (gonzalo){
											//success: function(number){
												var contador=0;
												for(var i=0;i<=gonzalo.length;i++){
													contador=contador+1;
												}
												/*
												*funcion que nos permite rellenar ceros al numero de facturas
												*/
												function rellenaCero(valor){
													if(valor.length<6){
														var cantidad=6-valor.length;
														for(var i=0;i<cantidad;i++){
															valor = "0"+valor;
														}
													}	 
													return valor;		  
												}
												var nrofactura=rellenaCero(""+contador);
												//var nrocomprobante=number[0].get("nro_comprobante");
												/*
												*Registrando las los comprobantes a generar
												*/
												var RegComprobante = Parse.Object.extend("comprobante");
												var regComprobante = new RegComprobante();
												
												regComprobante.save({
												//capos a llenar para registrar la transaccion
													estado_comprobante:false,
													texto_comprobante:"texto del comprobante",
													transaccion_id_comp:transaccion.id,
													nro_comprobante:""+nrofactura,
												},{
												//genrar el numero de comprobante de manera correlativa y añadir la funcion de la facturacion electronica
													success:function(comprobante){
														//response.success("exito "+inMonto);
														var fechaoriginal="esta es un de las fechas mas completas del mundo ok"; 
														var comprobante_txt="datos para la facturacion de la transaccion";
														var respuesta={"comprobante_id":comprobante.id,"detalle":comprobante_txt,"fecha":comprobante.createdAt};
														//response.success(respuesta);
														res.render('recarga', {id_comprobante:comprobante.id,monto_transaccion:inMonto,fecha:fechaoriginal,nro_factura:nrofactura,usuario:usuario_i,password:password_i});
													},
													error: function(user, error) {
														res.render('mensajes', {salida:"NO SE HA PODIDO EFECTUAR SU TRANSACCION !!!"});
													}
												});
												/*
												*fin registro de comprobantes
												*/
											//}
										});//fin de vericar el numeo de la factura
									},
									error: function(user, error) {
										res.render('mensajes', {salida:"NO SE HA PODIDO EFECTUAR SU TRANSACCION !!!"});
									}
								});
							}
							else{
							
								/*error: function(user, error) {
									//falla del logeo
									res.render('mensajes', {salida:"DATOS NO VALIDOS !!!"});
									//response.error("login incorrecto");
								}*/
								res.render('mensajes', {salida: "no cuenta con saldo suficiente para realizar la transaccion!!!"});
							}
						}else{
							if(saldo<inMonto){
								//response.success("no tiene suficiente saldo para realizar la transaccion");
								//res.render('recarga', {salida: "sin saldo suficiente "+req.body.carga});
								res.render('mensajes', {salida: "no cuenta con saldo suficiente para realizar la transaccion!!!"});
							}
						}
				});
				
			}else{
				//response.success("no puede realizar la transaccion");
				res.render('mensajes', {salida: "transaccion no valida!!!"});
			}
		},
		error: function(user, error) {
			//falla del logeo
			res.render('mensajes', {salida:"TRANSACCION NO VALIDA !!!"});
			//response.error("login incorrecto");
		}
	});
});

/*
*FUNCION PARA REDIRECCIONAR A LA PAGINA PRINCIPAL DE RECARGAS
*/

app.post('/felicitacion', function(req, res) {		
	//auxiliares para mantener session
	var user_manager=req.body.usuario_m;
	var pass_manager=req.body.password_m;
	//
	var in_nombre=req.body.nombre;
	var in_apellido=req.body.apellido;
	var in_ci=req.body.ci;
	var in_username=req.body.celular;
	var in_password=req.body.password;
	var in_estado=req.body.estado-0;
	var in_balance=req.body.balance-0;
	
	Parse.User.signUp(in_username, in_password,{
		nombre:in_nombre,
		apellidos:in_apellido,
		ci_usuario:in_ci,
		estado:in_estado
	},{
		success: function(user) {
			var RegCuentas=Parse.Object.extend("Cuentas");
			var regCuentas=new RegCuentas();
			regCuentas.save({
				usuario: in_username,
				balance: in_balance
				},{
				success: function(cuentas)
				{
					//armando la respuesta
					var s_nombre=user.get("nombre");
					var s_apellido=user.get("apellidos");
					var s_estado=user.get("estado");
					var s_balance=cuentas.get("balance");
					var s_ci=user.get("ci_usuario");
					var respuesta={"nombre":s_nombre,"apellido":s_apellido,"estado":s_estado,"balance":s_balance,"ci":s_ci};
					response.success(respuesta);

				},
				error: function(user, error) {
					res.render('mensajes', {salida:"NO SE HA PODIDO CREAR AL NUEVO OPERADOR!!!"});
				}
			});
			res.render('felicitacion', {salida:"SE HA CREADO EL REGISTRO EXITOSAMENTE!!!",usuario:user_manager,password:pass_manager});
		},
		error: function(user, error) {
			res.render('mensajes', {salida:"NO SE HA PODIDO CREAR AL NUEVO OPERADOR!!!"});
		}
		
	});
});
//liffestar

app.listen();


