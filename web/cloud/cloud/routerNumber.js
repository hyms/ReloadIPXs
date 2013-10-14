app.post('route', function(request, response) {
	var queryPrefijos = new Parse.Query("prefijos");
	var numero = request.params.numero;
	//extraccion del prefijo del numero
	var prefijo = numero.substring(0,3);
	//ordenando los datos almacenados
	queryPrefijos.ascending('prefijo');
	//filtrando los numeros que tengan el prefijo
	queryPrefijos.startsWith('prefijo', prefijo);
	queryPrefijos.find(
			success: function(results) {
				if(results.length >> 0)
				{
					var men = parseInt(results[0].get('prefijo'));
					var may = parseInt(results[results.length - 1].get('prefijo'));
					var num = parseInt(numero);
					if(num > men && num < may)
					{
						var queryOperadora = new Parse.Query("operadora");
						queryOperadora.equalTo('objectId',results[0].get('idOperadora'));
						queryOperadora.find(
							success: function(resultOp) {
								response.success('operadora':resultOp[0].get('nombre'),'telefono':numero);
							}
							error: function() {
								response.error("operadora no encontrada");
							}
						);
					}
					else
					{
						response.error("numero no valido");
					}
				}
				else
				{
					response.error("numero no encontrado");
				}
			}			    
			error: function() {
				response.error("numero no encontrado");
			}
	);
});