//funcion login que verifica el estado de usuario
Parse.Cloud.define("login", function(request, response) {
	Parse.User.logIn(request.params.usuario, request.params.password, {
		success: function(user) {
			var query=new Parse.Query("User");
			query.equalTo("objectId",user.id);
			query.find({
				success:function(results){
				
					//variables de la clase
					var estado	= results[0].get("estado"); 	//estado 0 no activo,1 activo,2 sin credito
					var dispositivo = request.params.dispositivo;
					
					var msg = "Usuario no activo";
					
					//verificando usuario activo
					if(estado==1){
						msg = "Usuario Activo";
					}
					if(estado==2){
						msg = "Usuario sin credito";
					}
					
					response.success(msg);
				}
			});
		},
		error: function(user, error) {
			// falla en el login.
			response.error("login incorrecto");
		}
	});
});