<?php
/* 	Descrição do Arquivo Formato CNAB
*	Remessa:
*	Registro 0 - Header Label
*	Registro 1 - Transação
*	Registro 2 - Mensagem (opcional)
*	Registro 3 - Rateio de Crédito (opcional)
*	Registro 7 - Pagador Avalista (opcional)
*	Registro 9 - Trailler
*/
	class BradescoCNAB400{
		//HEADER LABEL EM CAIXA ALTA
		private $idRegistro;				//001 a 001		numérico
		private $idArquivoRemessa;			//002 a 002 	numérico
		private $literalRemessa;			//003 a 009		alfa
		private $codServico;				//010 a 011		numérico
		private $literalServico;			//012 a 026		alfa
		private $codEmpresa;				//027 a 046		numérico
		private $nomeEmpresa;				//047 a 076		alfa
		private $numBradescoCompensacao;	//077 a 079		numérico
		private $nomeBanco;					//080 a 094		alfa
		private $dataGravacaoArquivo;		//095 a 100		numerico DDMMAA
											//101 a 108		EM BRANCO
		private $idSistema;					//109 a 110		alfa		
		private $numSequencialRemessa;		//111 a 117		numérico
											//118 a 394		EM BRANCO
		private $numSequencialRegistro;		//395 a 400		numérico
														
		//FIM HEADER
		//REGISTRO DE TRANSAÇÃO TIPO 3
		private $idRegistroT;				//001 a 001		numérico (fixo "3")
		private $idEmpresa;					//002 a 017		alfa
		private $idTituloBanco;				//018 a 029		alfa
		private $codCalcRateio;				//030 a 030		numérico
		private $tipoValorInformado;		//031 a 031		numérico
											//032 a 043		alfa(BRANCOS)
		private $codBancoCredito1Benefic;	//044 a 046		numérico (fixo "237")
		private $codAgenciaCredito1Benefic;	//047 a 051		numérico 
		private $digitAgencCredito1Benefic;	//052 a 052		alfa 
		private $numeroContaCorrente1Benef;	//053 a 064		numérico 
		private $digitCCCredito1Benefic;	//065 a 065		alfa
		private $valorRateio1Beneficiario;	//066 a 080		numérico
		private $nome1Beneficiario;			//081 a 120		alfa
											//121 a 151		alfa(BRANCOS)
		private $idParcela;					//152 a 157		alfa
		private $float1Beneficiario;		//158 a 160		numérico
		//REPETE POR BENEFICIARIO
		private $numSequenciaRegistroT;		//395 a 400 	numérico
		//FIM REGISTRO DE TRANSAÇÃO TIPO 1
	}
	
	