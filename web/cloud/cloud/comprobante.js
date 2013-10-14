
Parse.Cloud.define("comprobante", function(request, response) {

	var queryComprobante=new Parse.Query("comprobante");
	queryComprobante.get(request.params.id_comprobante,{
		success: function(comprobante){
			var id_transaccion=comprobante.get("transaccion_id_comp");
			var inEstado=request.params.estado;
			if(inEstado==1){
				//
				comprobante.set("estado_comprobante",true);
				comprobante.save();
				//inicio del error
				var queryTransaccion=new Parse.Query("transaccion");
				queryTransaccion.get(id_transaccion,{
					success: function(transaccion){
						transaccion.set("estado_trans",true);
						transaccion.save();
						//response.success("datos de salida ff  "+transaccion.id);
						var usuario=transaccion.get("nro_cel_operador");
						var senderapp=transaccion.get("senderapp_trans");
						var inMonto=transaccion.get("monto");
						/*
						*registrando un nuevo registrode la comunicacion o cola para la siguiente transaccion
						*/
						var RegComunicacion=Parse.Object.extend("comunicacion");
						var regComunicacion=new RegComunicacion();
						regComunicacion.save({
							//campos a llenar el la comunicacion
							nro_cel_operador_com: usuario,
							senderapp_com: senderapp
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
											response.success(respuesta);
										}
									});	
									//
								}
							});
						
							
							
						//response.success("parametros no validos gonzalo"+usuario);
						}
						});
			
						//fin
					}
				});
				//hasta aqui el error
				
			}else{
				if(inEstado==0){
					response.success("por falso "+id_transaccion);
				}
				else{
					response.success("parametros no validos");
				}
			}
		}
	});
});

