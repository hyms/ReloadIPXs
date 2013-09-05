Parse.Cloud.define("login", function(request, response) {
	Parse.User.logIn(request.params.usuario, request.params.password, {
		success: function(user) {
			var query=new Parse.Query("User");
			query.equalTo("objectId",user.id);
			query.find({
				success:function(results){
				
					var name=results[0].get("username");
					var estado=results[0].get("estado");
					var activo=results[0].get("activo");
					var iden=results[0].get("objectId");
					//var balance=results[0].get("balance");
					//salida de prueba
					
					//variables a usar en la tabla de logins
					if(estado==1){
						var dispositivo=request.params.dispositivo;
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
						/*regcomunicacion.set("nro_cel_operador_com",name);
						regcomunicacion.set("senderapp_com",dispositivo);
						regcomunicacion.save(null, {
							success: function(regcomunicacion) {
							//responce('nuevo objeto creado con el id: '+usuario.objectid);
								response.success("persona  "+regcomunicacion.id);
							}	*/	
								//hasta aqui
						regComunicacion.save({
							//campos a llenar el la comunicacion
							nro_cel_operador_com: name,
							senderapp_com: dispositivo
						},{
						success: function(regComunicacion)
						{
							/*
							*
							*procedimiento para designar el tipo de mensaje 
							*a responder al dispositivo en funcion de su balance
							*
							*/
							//var queryCuenta=new Parse.Query("Cuentas");
							//queryCuentas.equalTo("usuario",name);
							var queryCuentas=new Parse.Query("Cuentas");
							queryCuentas.equalTo("usuario",name);
							queryCuentas.find({
								success:function(results){
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
											function suma(var1,var2){
												var respuesta=var1+var2;
											return respuesta;
											}
											var res=suma(4,2);
											var sMensaje=results[0].get("mensaje");
											var sCode=results[0].get("codigo");
											var respuesta={"key":regComunicacion.id,"balance":balance_cuentas,"code":sCode,"mensaje":sMensaje,"sumainterna":res};
											response.success(respuesta);
										}
									});	
									//
								}
							});
							
						
							//
							/*var apellido="sinka";
							var respuesta={"Balance":balance,"key":regComunicacion.id};
							response.success(respuesta);*/
							//response.success("la salidaa "+name+".."+estado+"..."+regComunicacion.id);
						}
						});
						//response.success("la salidaa "+name+".."+estado+"..."+regComunicacion.id);
							//fin hasta aqui
						//});
					}
					else{
						//response.success("su cuenta no esta activa!");
					}
					//var query2=new Parse.Query()
					//var Comunica=Parse.Object.extend("comunicacion");
					//var comunicacion=new Comunica();
					//response.success("la salidaa "+name+".."+estado+"...");
				}
			});
		},
		error: function(user, error) {
			// falla en el login.
			response.error("login incorrecto");
		}
	});
});