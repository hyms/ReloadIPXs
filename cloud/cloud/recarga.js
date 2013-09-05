
Parse.Cloud.define("recarga", function(request, response) {

	var querycomunicacion=new Parse.Query("comunicacion");
	querycomunicacion.get(request.params.key,{
		success: function(comunica){
			var cel_operador=comunica.get("nro_cel_operador_com");
			var operador=request.params.usuario;
			var id_comunicacion=comunica.id;
			var numero_final=request.params.numero;
			var senderapp=comunica.get("senderapp_com");
			
			if(cel_operador==operador){
				var queryuser=new Parse.Query("Cuentas");
				queryuser.equalTo("usuario",cel_operador);
				queryuser.find({
					success:function(results){
						var inMonto=request.params.carga;
						var saldo=results[0].get("balance");
						if(saldo>=inMonto){
						/*
							var resto=saldo-inMonto;
							results[0].set("balance",resto);
							results[0].save();
						*/	
						
							/*
							*
							*Registrando las transacciones que se realizan
							*
							*/
							var RegTransaccion = Parse.Object.extend("transaccion");
							var regtransaccion = new RegTransaccion();
							
							regtransaccion.save({
								//capos a llenar para registrar la transaccion
								estado_trans:false,
								key_transaccion:id_comunicacion,
								monto:inMonto,
								nro_cel_final:numero_final,
								nro_cel_operador:cel_operador,
								senderapp_trans:senderapp
							},{
								success:function(transaccion){
									
									/*
									*
									*Registrando las los comprobantes a generar
									*
									*/
									var RegComprobante = Parse.Object.extend("comprobante");
									var regComprobante = new RegComprobante();
							
									regComprobante.save({
									//capos a llenar para registrar la transaccion
										estado_comprobante:false,
										texto_comprobante:"texto del comprobante",
										transaccion_id_comp:transaccion.id,
									},{
										success:function(comprobante){
											//response.success("exito "+inMonto);
											var detalle_txt={"cliente":"senior","nit":"78839124","literal":"v. litelar 00/100"};
											var comprobante_txt="datos para la facturacion de la transaccion";
											var respuesta={"comprobante_id":comprobante.id,"fecha":comprobante.createdAt,"cliente":"senior","nit":"78839124"};
											response.success(respuesta);
										}
									});
									/*
									*fin registro de comprobantes
									*/
								}
							});
							/*
							*fin registro de transacciones
							*/
						}else{
							if(saldo<inMonto){
								response.success("no tiene suficiente saldo para realizar la transaccion");
							}
						}
						
					}
				});
			}else{
				response.success("no puede realizar la transaccion");
			}
		}
	});
});

