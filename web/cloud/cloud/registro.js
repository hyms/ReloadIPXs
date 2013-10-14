
Parse.Cloud.define("registro", function(request, response) {

	var in_nombre		=	request.params.nombre;
	var in_apellido		=	request.params.apellido;
	var in_ci			=	request.params.ci;
	var in_username		=	request.params.celular;
	var in_password		=	request.params.password;
	var in_estado		=	request.params.estado-0;
	var in_balance		=	request.params.balance-0;
	
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
					response.error("registro no realizado");
				}
			});
		},
		error: function(user, error) {
			response.error("registro no realizado");
		}
	});
});
