function gerarRemessa(baseProjeto) {
	
	var dataRemessa = document.getElementById('diaRemessa').value;
	var operadoras = "";
	$("#selecaoOperadoraRemessa option:selected").each(function(ind, elem) {
		operadoras += elem.value;
	});
//	console.log(operadoras);
	$
			.ajax({
				async : true,
				type : "POST",
				url : baseProjeto + "/controllers/homeController.php",
				data : {
					servico : "gerarRemessaDia",
					dataRemessa : dataRemessa,
					banco : operadoras
				},
				success : function(e) {
					if (e !== 'null') {
						console.log(e);

						var obj = JSON.parse(e);
						// console.log(obj);
						switch (obj[0]) {
							case 0:
								break;
							case 1:
								var link = document.createElement('a');
		        				link.href = baseProjeto + "/controllers/"+obj[2]+"/"+obj[1];
		        				link.download = obj[1];
		        				document.body.appendChild(link);
		        				link.click();
								break;
							case 2:
								alert("Selecione boletos somente de um banco!");
								break;
							case 3:
								alert("Um arquivo remessa já foi gerado nesta data. O mesmo arquivo será gerado novamente!");
								var link = document.createElement('a');
								link.href = baseProjeto + "/controllers/"+obj[2]+"/"+obj[1];
								link.download = obj[1];
								document.body.appendChild(link);
								link.click();
								break;
							default:
								break;
						}
					} else
						alert("Nenhum boleto encontrado nesta data");
				},
				error : function(error) {
					// console.log(eval(error));
				}
			});
}

function imprimirBoleto(elem){
	window.open("../controllers/boletoController.php?idT="+elem.getAttribute('data-idt')+"","janelaBloq", "width=800, height=650, top=0, left=0, scrollbars=no, status=no, resizable=no, directories=no, location=no, menubar=no, titlebar=no, toolbar=no");
}