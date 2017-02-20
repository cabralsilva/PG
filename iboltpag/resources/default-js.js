function imprimirBoleto(elem){
	window.open("../controllers/boletoController.php?idT="+elem.getAttribute('data-idt')+"","janelaBloq", "width=800, height=650, top=0, left=0, scrollbars=no, status=no, resizable=no, directories=no, location=no, menubar=no, titlebar=no, toolbar=no");
}