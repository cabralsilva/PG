<?php
	/* Arquivo: RemessaController
	 * Descrição: Gerar arquivo remessa de boletos bancários 
	 * 
	 * */

class Remessa{
	
	private $banco;
	
	function __construct() {
		
	}
	private function  gerarEspacosBrancos($qtd){
		$espacos = "";
		for ($i = 0; $i < $qtd; $i++) $espacos .= " ";
		return $espacos;
	}
	private function  gerarEspacosZeros($qtd){
		$zeros = "";
		for ($i = 0; $i < $qtd; $i++) $zeros .= "0";
		return $zeros;
	}
	
	private function removePontosVirgulas($number, $sizeField){
		$number = str_replace(",", "", $number);
		$number = str_replace(".", "", $number);
		$number = str_pad($number, $sizeField, '0', STR_PAD_LEFT);
		return $number;
	}
	
	private function zerosEsquerda($number, $sizeField){
		$number = str_pad($number, $sizeField, '0', STR_PAD_LEFT);
		return $number;
	}
	private function brancosDireita($texto, $sizeField){
		$texto = str_pad($texto, $sizeField, ' ', STR_PAD_RIGHT);
		return $texto;
	}
	
	private function calculoDigitoNossoNumero($carteira, $nossoNumero){
		$numero = $carteira.$nossoNumero;
		if (strlen($numero) == 13){
			$multiplicador = 2;
			$soma = 0;
			$multiplicacao = 0;
			for($i = 12; $i >= 0; $i--){
				$multiplicacao = (substr($numero, $i, 1)) * $multiplicador;
				$multiplicador++;
				if ($multiplicador > 7) $multiplicador = 2;
				$soma += $multiplicacao;
			}
			$resto = $soma % 11;
			switch ($resto){
				case 0:
					return '0';
					break;
				case 1:
					return 'P';
					break;
				default:
					return 11 - $resto;
			}
		}else return 'E';
	}
	public function gerarRemessaBradesco240(){
		// Abre ou cria o arquivo CB2711AB.REM
		// "a" representa que o arquivo é aberto para ser escrito
		$fp = fopen("CB2711AB.REM", "a");
		
		$headerFile = /*CONTROLE*/
			"237" //CÓD. BANCO G001- TAMANHO 3 / 1-3 NUM TABELA OPERADORAS->CODIGO_BANCO
			. "0000" //LOTE SERVICO G002 - TAMANHO 4 / 4-7 NUM DEFAULT '0000'
			. "0" //TIPO REGISTRO G003 - TAMANHO 1 - / 8-8 NUM DEFAULT '0'
			/*FIM CONTROLE*/
			. $this->gerarEspacosBrancos(9) //USO FEBRABAN - TAMANHO 9 / 9-17
			
			. "1" //TIPO INSCRIÇÃO DA EMPRESA G005 - TAMANHO 1 / 18-18  TABELA EMPRESA->TIPO_INSCRICAO_EMPRESA
			. "12345678901234" //NÚMERO INSCRIÇÃO EMPRESA G006 - TAMANHO 14 / 19-32 TABELA EMPRESA->CNPJ
			. "12345678901234567890"  //NÚMERO CONVÊNIO NO BANCO G007 - TAMANHO 20 / 33-52
			. "12345" //AGENCIA MANTENEDORA DA CONTA G008 - TAMANHO 5 / 53-57
			. "1" //DIGITO VERIFICADOR DA AGENCIA G009 - TAMANHO 1 / 58-58
			. "123456789012" //NÚMERO CONTA CORRENTE G010 - TAMANHO 12 / 59-70
			. "1" //DIGITO VERIFICADOR CONTA CORRENTE G011 - TAMANHO 1 / 71-71
			. "1" //DIGITO VERIFICADOR AGENCIA/CONTA G012 - TAMANHO 1 / 72-72
			. "NOME EMPRESA                  " //NOME EMPRESA G013 - TAMANHO 30 / 73-102
			. "NOME BANCO                    " //NOME BANCO G014 - TAMANHO 30 / 103-132
			. $this->gerarEspacosBrancos(10) //USO FEBRABAN G004 - TAMANHO 10 / 133-142 BRANCOS
			
			. "1" //CÓD. REMESSA/RETORNO G015 - TAMANHO 1 / 143-143
			. "01012016" //DATA GERACAO ARQUIVO G016 - TAMANHO 8 / 144-151
			. "235959" //HORA GERAÇÃO ARQUIVO G017 - TAMANHO 6 / 152-157
			. "000001" //NÚMERO SEQUENCIAL ARQUIVO G018 - TAMANHO 6 / 158-163
			. "101"	//NÚMERO DA VERSÃO DO ARQUIVO G019 - TAMANHO 3 / 164-166
			. "12345" //DENSIDADE DA GRAVAÇÃO DO ARQUIVO G020 - TAMANHO 5 / 167-171
			
			. "ABCDEFGHIJKLMNOPQRST" //PARA USO DO BANCO G021 - TAMANHO 20 / 172-191
			. "ABCDEFGHIJKLMNOPQRST" //PARA USO DA EMPRESA G022 - TAMANHO 20 / 192-211
			. $this->gerarEspacosBrancos(29) //USO FEBRABAN G004 - TAMANHO 29 / 212-240 BRANCOS
			;
		$headerPart = "237" //CÓD. BANCO - TAMANHO 3 / 1-3 NUM
			. "0000" //LOTE SERVICO - TAMANHO 4 / 4-7 NUM
			. "0" //TIPO REGISTRO - TAMANHO 1 - / 8-8 NUM
			. "A" //TIPO OPERAÇÃO - TAMANHO 1 / 9-9 ALFA
			. "12" //TIPO SERVIÇO - TAMANHO 2 / 10-11 NUM
			. $this->gerarEspacosBrancos(2) //USO FEBRABAN - TAMANHO 2 / 12-13 BRANCOS
			. "060" //VERSÃO LAYOUT DO LOTE  - TAMANHO 3 / 14-16 NUM
			. $this->gerarEspacosBrancos(1) //USO FEBRABAN - TAMANHO 1 / 17-17 ALFA
			
			. "1" //TIPO INSCRIÇÃO DA EMPRESA - TAMANHO 1 / 18-18
			. "123456789012345" //NÚMERO INSCRIÇÃO EMPRESA - TAMANHO 15 / 19-33
			. "12345678901234567890"  //NÚMERO CONVÊNIO NO BANCO - TAMANHO 20 / 34-53
			. "12345" //AGENCIA MANTENEDORA DA CONTA - TAMANHO 5 / 54-58
			. "1" //DIGITO VERIFICADOR DA AGENCIA - TAMANHO 1 / 59-59
			. "123456789012" //NÚMERO CONTA CORRENTE - TAMANHO 12 / 60-71
			. "1" //DIGITO VERIFICADOR CONTA CORRENTE - TAMANHO 1 / 72-72
			. "1" //DIGITO VERIFICADOR AGENCIA/CONTA - TAMANHO 1 / 73-73
			. "NOME EMPRESA                  " //NOME EMPRESA - TAMANHO 30 / 74-103
			
			. "ABCDEFGHIJKLMNOPQRSTUVXWYZABCDEFGHIJKLMN" //MENSAGEM1 - TAMANHO 40 / 104-143 ALFA
			. "ABCDEFGHIJKLMNOPQRSTUVXWYZABCDEFGHIJKLMN" //MENSAGEM2 - TAMANHO 40 / 144-183 ALFA
			. "12345678" //NUMERO REMESSA/RETORNO - TAMANHO 8 / 184-191 NUM
			. "12346578" //DATA GRAVAÇÃO REMESSA/RETRONO - TAMANHO 8 - 192-199 NUM
			. "12345678" //DATA DO CRÉDITO - TAMANHO 8 - 200-207 NUM
			. $this->gerarEspacosBrancos(33) //USO FEBRABAN - TAMANHO 33 / 208-240
			;
		$segP = "237" //CÓD. BANCO - TAMANHO 3 / 1-3 NUM
			. "0000" //LOTE SERVICO - TAMANHO 4 / 4-7 NUM
			. "3" //TIPO REGISTRO - TAMANHO 1 - / 8-8 NUM
			. "12345" //NÚMERO SEQUENCIAL DO REGISTRO NO LOTE - TAMANHO 5 / 9-13 NUM
			. "P" //SEGMENTO DO REGISTRO DETALHE - TAMANHO 1 / 14-14 ALFA
			. $this->gerarEspacosBrancos(1) //USO FEBRABAN - TAMANHO 1 / 15-15 ALFA BRANCOS
			. "12" //COD. MOVIMENTO REMESSA - TAMANHO 2 / 16-17 NUM
			. "12345" //AGENCIA MANTEN. CONTA - TAMANHO 5 / 18-22 NUM
			. "A" //DIGITO VERIFICADOR AGENCIA - TAMANHO 1 / 23-23 ALFA
			. "123456789012" //NUMERO CONTA CORRENTE - TAMANHO 12 / 24-35 NUM
			. "A" //DIGITO VERIFICADOR C/C - TAMANHO 1 / 36-36 ALFA
			. "A" //DIGITO VERIFICADOR AGENCIA/CONTA - TAMANHO 1 / 37-37 ALFA
			. "123" //IDENTIFICAÇÃO DO PRODUTO - TAMANHO 3 / 38-40 NUM
			. "00000" //ZEROS - TAMANHO 5 / 41-45 NUM
			. "12345678901" //NOSSO NÚMERO - TAMANHO 11 / 46-56 NUM
			. "1" //DIGITO NOSSO NÚMERO - TAMANHO 1 / 57-57 NUM
			. "1" //CODIGO CARTEIRA - TAMANHO 1 / 58-58 NUM
			. "1" //FORMA CADASTRO TITULO BANCO - TAMANHO 1 / 59-59 NUM
			. "A" //TIPO DE DOCUMENTO - TAMANHO 1 / 60-60 ALFA
			. "1" //IDENTIFICAÇÃO EMISSÃO BOLETO - TAMANHO 1 / 61-61 NUM
			. "A" //IDENTIFICAÇÃO DA DISTRIBUIÇÃO - TAMANHO 1 / 62-62 ALFA
			. "ABCDEFGHIJKLMNO" //NUMERO DO DOCUMENTO COBRANÇA - TAMANHO 15 / 63-77 ALFA
			. "12345678" //DATA VENCIMENTO TITULO - TAMANHO 8 / 78-85 NUM
			. "123456789012345" //VALOR NOMINAL DO TITULO - TAMANHO 13 + 2 / 86-100 NUM
			. "12345" //AGENCIA ENCARRG. COBRANÇA - TAMANHO 5 / 101-105 NUM
			. "A" //DIGITO VERIFICADOR AGENCIA - TAMANHO 1 / 106-106 ALFA
			. "12" //ESPÉCIE TITULO - TAMANHO 2 / 106-107 NUM
			. "A" //IDENTIFICAÇÃO DE TITULO ACEITO/NÃO ACEITO - TAMANHO 1 / 109-109 ALFA
			. "12346578" //DATA EMISSÃO DO TITULO - TAMANHO 8 / 110-117 NUM
			/*JUROS*/
			. "1" //CÓDIGO JUROS DE MORA - TAMANHO 1 / 118-118 NUM
			. "12345678" //DATA JUROS DE MORA - TAMANHO 8 / 119-126 NUM
			. "123456789012345" //JUROS MORA/DIA - TAMANHO 13 + 2 / 127-141 NUM
			/*FIM JUROS*/
			/*DESCONTO 1*/
			. "1" //CÓDIGO DO DESCONTO1 - TAMANHO 1 / 142-142 NUM
			. "12345678" //DATA DESCONTO2 - TAMANHO 8 / 143-150 NUM
			. "123456789012345" //VALOR/PERCENTUAL A SER CONCEDIDO DESCONTO1 - TAMANHO 13 + 2 / 151-165 NUM
			/*FIM DESCONTO 1*/
			. "123456789012345" //VALOR IOF A SER RECOLHIDO - TAMANHO 13 + 2 / 166-180 NUM
			. "123456789012345" //VALOR DO ABATIMENTO - TAMANHO 13 + 2 / 181-195 NUM
			. "ABCDEFGHIJKLMNOPQRSTUVXWY" //IDENTIF. DO TÍTULO NA EMPRESA - TAMANHO 25 / 196-220 ALFA
			. "1" //CÓDIGO PARA PROTESTO - TAMANHO 1 / 221-221 NUM
			. "12" //NÚMERO DE DIAS PARA PROTESTO - TAMANHO 2 / 222-223 NUM
			. "1" //CÓDIGO PARA BAIXA/DEVOLUÇÃO - TAMANHO 1 / 224-224 NUM
			. "ABC" //NÚMERO DE DIAS PARA BAIXA/DEVOLUÇÃO - TAMANHO 3 / 225-227 ALFA
			. "12" //CÓDIGO DA MOEDA - TAMANHO 2 / 228-229 NUM
			. "1234567890" //NÚMERO CONTRATO DA OPERAÇÃO DE CRÉDITO - TAMANHO 10 / 230-239 NUM
			. $this->gerarEspacosBrancos(1) //USO DA FEBRABAN - TAMANHO 1 / 240-240 ALFA BRANCOS
			
			;
		$segQ = /*CONTROLE*/
			"237" //CÓD. BANCO - TAMANHO 3 / 1-3 NUM
			. "0000" //LOTE SERVICO - TAMANHO 4 / 4-7 NUM
			. "3" //TIPO REGISTRO - TAMANHO 1 - / 8-8 NUM
			/*FIM CONTROLE*/
			
			/*SERVIÇO*/
			. "12345" //NÚMERO SEQUENCIAL DO REGISTRO NO LOTE - TAMANHO 5 / 9-13 NUM
			. "Q" //CÓDIGO SEGMENTO DO REGISTRO DETALHE - TAMANHO 1 / 14-14 ALFA
			. $this->gerarEspacosBrancos(1) //USO FEBRABAN - TAMANHO 1 / 15-15 ALFA BRANCOS
			. "12" //COD. MOVIMENTO REMESSA - TAMANHO 2 / 16-17 NUM
			/*FIM SERVIÇO*/
			
			/*DADOS PAGADOR*/
			. "1" //TIPO DE INSCRIÇÃO - TAMANHO 1 / 18-18 NUM
			. "123465789012345" //NÚMERO INSCRIÇÃO - TAMANHO 15 / 19-33 NUM
			. "ABCDEFGHIJKLMNOPQRSTUVXWYZABCDEFGHIJKLMN" //NOME PAGADOR - TAMANHO 40 / 34-73 ALFA
			. "ABCDEFGHIJKLMNOPQRSTUVXWYZABCDEFGHIJKLMN" //ENDEREÇO PAGADOR - TAMANHO 40 / 74-113 ALFA
			. "ABCDEFGHIJKLMNO" //BAIRRO PAGADOR - TAMANHO 15 / 114-128 ALFA
			. "12345" //CEP PAGADOR - TAMANHO 5 / 129-133 NUM
			. "123" //SUFIXO CEP PAGADOR - TAMANHO 3 / 134-136 NUM
			. "ABCDEFGHIJKLMNO" //CIDADE PAGADOR - TAMANHO 15 / 137-151 ALFA
			. "AB" //UF PAGADOR - TAMANHO 2 / 152-153 ALFA
			/*FIM DADOS PAGADOR*/
			
			/*SACADOR*/
			. "1" //TIPO DE INSCRIÇÃO - TAMANHO 1 / 154-154 NUM
			. "123465789012345" //NÚMERO INSCRIÇÃO - TAMANHO 15 / 155-169 NUM
			. "ABCDEFGHIJKLMNOPQRSTUVXWYZABCDEFGHIJKLMN" //NOME SACADOR - TAMANHO 40 / 170-209 ALFA
			/*FIM SACADOR*/
			
			/*BANCO CORRESPONDENTE*/
			. "123" //CÓDIGO BANCO CORRESP. NA COMPENSAÇÃO - TAMANHO 3 / 210-212 NUM
			. "ABCDEFGHIJKLMNOPQRST" //NOSSO NÚMERO BANCO CORRESP. - TAMANHO 20 / 213-232 ALFA
			/*FIM BANCO CORRESPONDENTE*/
				
			. $this->gerarEspacosBrancos(8) //USO FEBRABAN - TAMANHO 8 / 233-240 ALFA BRANCOS
			;
		
		$trailerPart = /*CONTROLE*/
			"237" //CÓD. BANCO - TAMANHO 3 / 1-3 NUM
			. "0000" //LOTE SERVICO - TAMANHO 4 / 4-7 NUM
			. "5" //TIPO REGISTRO - TAMANHO 1 - / 8-8 NUM
			/*FIM CONTROLE*/
			
			. $this->gerarEspacosBrancos(9) //USO FEBRABAN - TAMANHO 9 / 9-17 ALFA BRANCOS
			. "123456" //QUANTIDADE REGISTROS NO LOTE - TAMANHO 6 / 18-23 NUM
			
			/*TOTALIZAÇÃO COBRANÇA SIMPLES*/
			. "123456" //QUANTIDADE TITULOS EM COBRANÇA - TAMANHO 6 / 24-29 NUM
			. "12345678901234567" //VALOR TOTAL DOS TÍTULO EM CARTEIRAS - TAMANHO 15 + 2 / 30-46 NUM
			/*FIMTOTALIZAÇÃO COBRANÇA SIMPLES*/
			
			/*TOTALIZAÇÃO COBRANÇA VINCULADA*/
			. "123456" //QUANTIDADE TITULOS EM COBRANÇA - TAMANHO 6 / 47-52 NUM
			. "12345678901234567" //VALOR TOTAL DOS TÍTULO EM CARTEIRAS - TAMANHO 15 + 2 / 53-69 NUM
			/*FIMTOTALIZAÇÃO COBRANÇA SIMPLES*/
			
			/*TOTALIZAÇÃO COBRANÇA CAUCIONADA*/
			. "123456" //QUANTIDADE TITULOS EM COBRANÇA - TAMANHO 6 / 70-75 NUM
			. "12345678901234567" //VALOR TOTAL DOS TÍTULO EM CARTEIRAS - TAMANHO 15 + 2 / 76-92 NUM
			/*FIMTOTALIZAÇÃO COBRANÇA SIMPLES*/
			
			/*TOTALIZAÇÃO COBRANÇA DESCONTADA*/
			. "123456" //QUANTIDADE TITULOS EM COBRANÇA - TAMANHO 6 / 93-98 NUM
			. "12345678901234567" //VALOR TOTAL DOS TÍTULO EM CARTEIRAS - TAMANHO 15 + 2 / 99-115 NUM
			/*FIMTOTALIZAÇÃO COBRANÇA SIMPLES*/
			
			. "ABCDEFGH" //NÚMERO AVISO DE LANÇAMENTOS - TAMANHO 8 / 116-123 ALFA
			. $this->gerarEspacosBrancos(117) //USO FEBRABAN - TAMANHO 117 / 124-240 ALFA BRANCOS
			;
		$trailerFile = "237" //CÓD. BANCO - TAMANHO 3 / 1-3 NUM
			. "9999" //LOTE SERVICO - TAMANHO 4 / 4-7 NUM DEFAULT '9999'
			. "9" //TIPO REGISTRO - TAMANHO 1 - / 8-8 NUM DEFAULT '9'
			. "         " //USO FEBRABAN - TAMANHO 9 / 9-17 ALFA
			
			. "123456" //QUANTIDADE DE LOTES DO ARQUIVO - TAMANHO 6 / 18-23 NUM
			. "123456" //QUANTIDADE DE REGISTROS DO ARQUIVO - TAMANHO 6 / 24-29 NUM
			. "123456" //QUANTIDADE CONTAS PARA CONCILIAR - TAMANHO 6 / 30-35 NUM
			. $this->gerarEspacosBrancos(205) // USO FEBRABAN - TAMANHO 205 / 36-240 ALFA
			;
		
		$escreve = fwrite($fp, $headerFile.$headerPart.$segP.$segQ.$trailerPart.$trailerFile);
		
		// Fecha o arquivo
		fclose($fp);
		
	}
	
	/*
	* 	Remessa: Registro 0 - Header Label
	*	Registro 1 - Transação
	*	Registro 2 - Mensagem (opcional)
	*	Registro 3 - Rateio de Crédito (opcional)
	*	Registro 7 – Pagador Avalista (opcional)
	*	Registro 9 - Trailler
	*/
	
	public function gerarRemessaBradesco400(){
		// Abre ou cria o arquivo CB2711AB.REM
		// "a" representa que o arquivo é aberto para ser escrito
		$fp = fopen("CB2711AC.REM", "w");
		$valor = 256.25;
		$valor = $this->removePontosVirgulas($valor, 13);
		
		$carteira = 17;
		$percentual_multa = number_format(2.3, 2);
		$nosso_numero = $this->zerosEsquerda(125, 11);
		$valor_desconto_dia = $this->removePontosVirgulas(number_format(0.05, 2), 10);
		$resp_emitir_boleto = '2';
		$emitir_boleto_deb_aut = 'N';
		$rateio = $this->gerarEspacosBrancos(1);
		$emite_aviso_end_deb_aut = "2";
		$ocorrencia = $this->zerosEsquerda(1, 2);
		
		$data_vencimento = new DateTime();
		$data_vencimento->add(new DateInterval('P5D'));
		
		$dias_desconto = 2;
		$data_limite_desconto = clone $data_vencimento;
		$data_limite_desconto->sub(new DateInterval('P'.$dias_desconto.'D'));
		
		$especie_titulo = $this->zerosEsquerda(99, 2);
		$primeira_instrucao = $this->zerosEsquerda(9, 2);
		$segunda_instrucao = $this->zerosEsquerda(9, 2);
		$valor_mora = $this->removePontosVirgulas(number_format(2.12, 2), 13);
		
		$valor_desconto_boleto = $this->removePontosVirgulas(number_format(2.5, 2), 13);
		$valor_iof = $this->zerosEsquerda(0, 13);
		$valor_abatimento = $this->zerosEsquerda(0, 13);
		
		$tipo_inscricao_pagador = $this->zerosEsquerda(99, 2);
		$cpf_cnpj = $this->zerosEsquerda(0, 14);
		
		$cep_pagador = $this->zerosEsquerda(80330, 5);
		$sufixo_cep_pagador = $this->zerosEsquerda(310, 3);
		
		
		
		$reg0 = ""
			. "0" 								//IDENTIFICAÇÃO DO REGISTRO - TAMANHO 1 / 001-001 DEFAULT '0'
			. "1" 								//IDENTIFICAÇÃO DO ARQUIVO REMESSA - TAMANHO 1 / 002-002 DEFAULT '1'
			. "REMESSA" 						//LITERAL REMESSA - TAMANHO 7 - 003-009 ALFA DEFAULT 'REMESSA'
			. "01" 								//CODIGO DO SERVIÇO - TAMANAHO 2 / 010-011 NUM DEFAULT '01
			. $this->brancosDireita("COBRANCA", 15)	//LITERAL SERVIÇO - TAMANHO 15 / 012-026 ALFA DEFAULT 'COBRANCA'
			. $this->zerosEsquerda(211857391, 20)	//CODIGO EMPRESA - TAMANHO 20 / 027-046 NUM
			. $this->brancosDireita("IBOLT TECNOLOGIA EIRELI ME", 30)	//NOME EMPRESA - TAMANHO 30 / 047-076 ALFA
			. "237" 							//CÓDIGO DO BANCO NA COMPENSAÇÃO - TAMANHO 3 / 077-079 NUM
			. $this->brancosDireita("BRADESCO", 15)   //NOME DO BANCO NA COMPENSAÇÃO - TAMANHO 15 / 080-094 ALFA
			. date('dmy') 							//DATA GRAVAÇÃO DO ARQUIBO - TAMANHO 6 / 095-100 NUM
			. $this->gerarEspacosBrancos(8) 	//USO FEBRABAN - TAMANHO 8 / 101-108
			. "MX" 								//IDENTIFICAÇÃO DO SISTEMA - TAMANHO 2 / 109-110 ALFA DEFAULT 'MX'
			. $this->zerosEsquerda(1, 7)		//NÚMERO SEQUENCIAL REMESSA - TAMANHO 7 / 111-117 NUM
			. $this->gerarEspacosBrancos(277) 	//USO FEBRABAN - TAMANHO 277 / 118-394
			. "000001" 							//NÚMERO SEQUENCIAL DE UM REGISTRO - TAMANHO 6 / 395-400 DEFAULT '000001'
		;
		
		$reg1 = ""
			. "1" 							//IDENTIFICAÇÃO DO REGISTRO - TAMANHO 1 / 001-001 NUM DEFAULT '1'
			. $this->gerarEspacosZeros(19)
			/*. "12345" 						//AGENCIA DÉBITO PAGADOR (OPCIONAL) - TAMANHO 5 / 002-006 NUM
			. "A" 							//DIGITO AGENCIA DÉBITO PAGADOR (OPCIONAL) - TAMANHO 1 / 007-007 ALFA
			. "12345" 						//RAZAO C/C DÉBITO PAGADOR (OPCIONAL) - TAMANHO 5 / 008-012 NUM
			. "1234567" 					//NÚMERO C/C CORRENTE PAGADOR (OPCIONAL) - TAMANHO 7 / 013-019 NUM
			. "A" 							//DIGITO C/C PAGADOR (OPCIONAL) - TAMANHO 1 / 020-020 ALFA
			*/
			
					/*IDENTIFICAÇÃO EMPRESA BENEFICIÁRIA - TAMANHO 17 / 021-037 ALFA*/
			. "0" //ZERO DEFAUL '0'
			. $this->zerosEsquerda($carteira, 3)  	//CARTEIRA
			. $this->zerosEsquerda(1522, 5)	//AGENCIA SEM DIGITO
			. $this->zerosEsquerda(26347, 7)//CONTA CORRENTE
			. $this->zerosEsquerda(8, 1)	//DIGITO CONTA
					/*FIM IDENTIFICAÇÃO EMPRESA BENEFICIÁRIA - TAMANHO 17 / 021-037 ALFA*/
			
			. $this->zerosEsquerda(26, 25) 	//NÚM. CONTROLE PARTICIPANTE - TAMANHO 25 / 038-062 ALFA (será o código da tabela transação)
			. "000" 						//CÓDIGO DO BANCO A SER DEBITADO NA COMPENSAÇÃO - TAMANHO 3 / 063-065 NUM
			. "0" 							//CAMPO DE MULTA - TAMANHO 1 / 066-066 NUM (0->Sem multa; 2->Com multa)
			. $this->removePontosVirgulas($percentual_multa, 4) //PERCENTUAL DE MULTA - TAMANHO 4 / 067-070 NUM
			. $nosso_numero				//IDENTIFICAÇÃO DO TÍTULO NO BANCO - TAMANHO 11 / 071-081 NUM (NOSSO NÚMERO - CÓDIGO PEDIDO - VERIFICAR SE PODE SER CÓDIGO DO PEDIDOPAGAMENTO)
			. $this->calculoDigitoNossoNumero($carteira, $nosso_numero) 							//DIGITO AUTOCONFERENCIA NÚMERO BANCÁRIO - TAMANHO 1 / 082-082 ALFA
			. $valor_desconto_dia  				//DESCONTO BONIFICAÇÃO POR DIA - TAMANHO 11 / 083-092 NUM
			. $resp_emitir_boleto			//CONDIÇÃO PARA EMISSAO DO PAPEL BOLETO - TAMANHO 1 / 093-093 NUM
			. $emitir_boleto_deb_aut		//IDENTIFICAÇÃO EMITE BOLETO PARA DÉBITO - TAMANHO 1 / 094-094 ALFA
			. $this->gerarEspacosBrancos(10)//USO FEBRABAN - TAMANHO 10 / 095-104 BRANCOS
			. $rateio						//IDENTIFICAÇÃO RATEIO DE CRÉDITO (OPCIONAL) - TAMANHO 1 / 105-105 ALFA
			. $emite_aviso_end_deb_aut		//ENDEREÇ. AVISO DEB. AUTOM. EM C/C (OPCIONAL) - TAMANHO 1 / 106-106 NUM
			. $this->gerarEspacosBrancos(2)//USO FEBRABAN - TAMANHO 2 / 107-108 BRANCOS
			. $ocorrencia 							//IDENTIFICAÇÃO OCORRÊNCIA - TAMANHO 2 / 109-110 NUM
			
			. "ABCDEFGHIJ" 					//NÚMERO DO DOCUMENTO - TAMANHO 10 / 111-120 ALFA
			. $data_vencimento->format('dmy') //DATA VENCIMENTO TITULO - TAMANHO 6 / 121-126 NUM
			. $valor						//VALOR DO TÍTULO - TAMANHO 13 / 127-139 NUM
			. $this->gerarEspacosZeros(3) 	//BANCO ENCARREGADO COBRANÇA - TAMANHO 3 / 140-142 NUM ZEROS
			. $this->gerarEspacosZeros(5) 	//AGENCIA DEPOSITÁRIA - TAMANHO 5 / 143-147 NUM
			. $especie_titulo 							//ESPÉCIE DE TITULO - TAMANHO 2 / 148-149 NUM
			. "N" 							//IDENTIFICAÇÃO - TAMANHO 1 / 150-150 ALFA SEMPRE 'N'
			. date('dmy')					//DATA EMISSÃO TITULO - TAMANHO 6 / 151-156 NUM
			. $primeira_instrucao							//PRIMEIRA INSTRUÇÃO - TAMANHO 2 / 157-158 NUM
			. $segunda_instrucao 			//SEGUNDA INSTRUÇÃO - TAMANHO 2 / 159-160 NUM
			. $valor_mora					//VALOR ACRESC POR DIA DE ATRASO - TAMANHO 13 / 161-173 NUM
			. $data_limite_desconto->format('dmy') 		//DATA LIMITE PARA CONCESSÃO DESCONTO - TAMANHO 6 / 174-179 NUM
			. $valor_desconto_boleto		//VALOR DESCONTO - TAMANHO 13 / 180-192 NUM
			. $valor_iof					//VALOR IOF - TAMANHO 13 / 192-205 NUM
			. $valor_abatimento				//VALOR ABATIMENTO A SER CONCEDIDO/CANCELADO - TAMANHO 13 / 206-218 NUM
			. $tipo_inscricao_pagador		//IDENTIFICAÇÃO TIPO INSCRIÇÃO DO PAGADOR - TAMANHO 2 / 219-220 NUM
			. $cpf_cnpj						//NÚMERO INSCRIÇÃO DO PAGADOR - TAMANHO 14 / 221-234 NUM
			. $this->gerarEspacosBrancos(40)//NOME PAGADOR - TAMANHO 40 / 235-274 ALFA
			. $this->gerarEspacosBrancos(40)//ENDEREÇO COMPLETO - TAMANHO 40 / 275-314 ALFA
			
				//MENSAGEM IMPRESSA NO BOLETO
			. $this->gerarEspacosBrancos(12)//PRIMEIRA MENSAGEM - TAMANHO 12 / 315-326 ALFA
				//FIM MENSAGEM BOLETO
				
			. $cep_pagador					//CEP PAGADOR - TAMANHO 5 / 327-331 NUM
			. $sufixo_cep_pagador			//SUFIXO CEP - TAMANHO 3 / 332-334 NUM
			. $this->gerarEspacosBrancos(60)//SACADOR/AVALISTA OU SEGUNDA MENSAGEM - TAMANHO 60 / 335-394 ALFA
			. "000002"						//NÚMERO SEQUENCIAL DO REGISTRO - TAMANHO 6 / 395-400 NUM
		
		;
		$reg9 = ""
			. "9" 								//IDENTIFICAÇÃO DO REGISTRO - TAMANHO 1 / 001-001 NUM DEFAULT '9'		
			. $this->gerarEspacosBrancos(393)	//USO FEBRABAN - TAMANHO 393 / 002-394 BRANCOS
			. "000003"							//NÚMERO SEQUENCIAL DO REGISTRO - TAMANHO 6 / 395-400 NUM
		;
		$conteudo = $reg0."\r\n".$reg1."\r\n".$reg9;
		$escreve = fwrite($fp, $conteudo);
	
		// Fecha o arquivo
		fclose($fp);
	
	}
}