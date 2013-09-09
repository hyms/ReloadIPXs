
// Use Parse.Cloud.define to define as many cloud functions as you want.
// For example:

require('cloud/login.js');
require('cloud/recarga.js');
require('cloud/comprobante.js');
require('cloud/app.js');
require('cloud/registro.js');


Parse.Cloud.define("contador", function(request, response) {
	var queryCuentas=new Parse.Query("comprobante");
	queryCuentas.equalTo("estado_comprobante",false);
	queryCuentas.count({
		success:function(number){
			
			//var contador
			//response.success("verificar que el contador funcione ::: "+number+" :::");
			var queryCuentas=new Parse.Query("comprobante");
			queryCuentas.select("username");
			queryCuentas.count({
				success:function(gonzalo){
					
					//var contador
					response.success("verificar que el contador funcione ::: "+gonzalo+" :::"+number);
					
				}
			});
			
		}
	});
});


