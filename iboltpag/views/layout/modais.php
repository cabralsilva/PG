<!-- Modal Remessa-->
<div class="modal fade" id="modalRemessa" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Arquivo remessa</h4>
			</div>
			<div class="modal-body">
				<p>Selecione o dia e o banco para gerar a remessa&hellip;</p>
				<form role="form">
					<div class="form-group col-md-4">
						<label for="diaRemessa">Data</label>
						<div class="input-daterange input-group" id="datepicker">
							<input type="text" class="input-sm2 form-control" id="diaRemessa" />
						</div>
					</div>
					<div class="form-group col-md-6">
						<label for="selecaoOperadoraRemessa">Banco</label> <select
							class="form-control selectpicker" id="selecaoOperadoraRemessa"
							title="Selecione..." data-width="100%">
								<?php foreach ($_REQUEST["lstOperadoras"] as $operadora){?>
									<option value="<?= $operadora["id_operadora"]?>"><?= $operadora["nome_operadora"]?></option>
								<?php }?>
							</select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				<button type="button" class="btn btn-primary"
					onclick="gerarRemessa('<?= BaseProjeto ?>')">Gerar remessa</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Retorno-->
<div class="modal fade" id="modalRetorno" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Arquivo retorno</h4>
			</div>
			<div class="modal-body">
				<p>Selecione o arquivo enviado pelo banco...</p>
				<form id="formulario" role="form" method="post" enctype="multipart/form-data">
					<div class="form-group">
<!-- 					    <label for="exampleInputFile">Arquivo de retorno</label> -->
<!-- 					    <input type="file" id="arquivoRetorno"> -->
<!-- 						<p class="help-block">Extensão .ret</p> -->
						
						<input type="text" name="servico" value="processarRetorno" class="hidden" />
						<label for="exampleInputFile">Arquivo de retorno</label>
					    <input name="arquivo" type="file" accept=".RET"/>
					    <p class="help-block">Extensão .ret</p>
					    <button>Enviar</button>
					</div>
				</form>
				
				
<!-- 				<form id="formulario" method="post" enctype="multipart/form-data"> -->
<!-- 				    <input type="text" name="servico" value="processarRetorno" class="hidden" /> -->
<!-- 				    <input name="arquivo" type="file" accept=".RET"/> -->
<!-- 				    <button>Enviar</button> -->
<!-- 				</form> -->
			</div>
<!-- 			<div class="modal-footer"> -->
<!-- 				<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button> -->
<!--				<button type="button" class="btn btn-primary" onclick="processarRetorno()">Processar</button> -->
<!-- 			</div> -->
		</div>
	</div>
</div>

<script type="text/javascript">
	$('#modalRemessa').on('show.bs.modal', function () {
	  	$('.modal-body').css('overflow-y', 'auto'); 
	  	$('#datepicker').focus();
	});
	$('#modalRemessa').on('hidden.bs.modal', function () {
		location.reload();
	});
	$('#modalRetorno').on('hidden.bs.modal', function () {
		location.reload();
	});
	$('.input-daterange').datepicker({
		endDate: '0d', 
	    format: "dd/mm/yyyy",
	    weekStart: 0,
	    language: "pt-BR",
	    multidate: false,
	    daysOfWeekHighlighted: "0",
	    todayHighlight: true,
	    autoclose: true
	});

	$("#formulario").submit(function () {
	    var formData = new FormData(this);

	    $.ajax({
	    	url : "../controllers/retornoController.php",
// 	        url: window.location.pathname,
	        type: 'POST',
	        data: formData,
	        success: function (data) {
	            console.log(data);
	        },
	        cache: false,
	        contentType: false,
	        processData: false,
	        xhr: function() {  // Custom XMLHttpRequest
	            var myXhr = $.ajaxSettings.xhr();
	            if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
	                myXhr.upload.addEventListener('progress', function () {
// 	                    console.log(".");
	                }, false);
	            }
	        	return myXhr;
	        }
	    });
	    return false;
	});
</script>