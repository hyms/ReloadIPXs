//funcion para recargar`
//requiere idUser, monto y numero de celular

Parse.Cloud.define("recarga", function(request, response) {
	
	//cargando la clase userCuenta
	var queryCuentaUser = new Parse.Query("userCuenta");
	
	//buscando el idUser para asociar a cuenta 
	queryCuentaUser.equalTo('idUser',request.params.idUser);
	queryCuentaUser.find({
		success: function(userCuenta) {
			
			//cargando class cuenta
			var queryCuenta = new Parse.Query("cuenta");
			queryCuenta.equalTo('objectId',userCuenta['idCuenta']);
			queryCuenta.find({
				success: function(cuenta) {
					
					var numero = request.params.celular;
					var prefijo = numero.substring(0.3);
					
					//reduciendo el el monto del saldo
					var saldo = cuenta['saldo'];
					saldo = saldo - request.params.monto;
					
					var queryTransaccion = new Parse.Query("transaccion");
					
					queryTransaccion.save({
						estado		= 1; //estado 1 transaccion ralizada
						detalle		= 'transaccion';
						idCuenta	= userCuenta['idCuenta'];
						idUser		= request.params.idUser;
						monto		= request.params.monto;
					},{
						success: function(cuentas)
						{
							response.success('transaccion realizada');
						},
						error: function(user, error) {
							response.error("transaccion no realizada");
						}
					});
					
				},
				error: function() {
					response.error("Usuario sin cuenta");
			    }
			}
			response.success("");
		},
		error: function() {
			response.error("Usuario sin cuenta");
	    }
	}
});

