<?php
require_once '../util/Banco.php';
class EmpresaService {
	private $banco;
	function __construct() {
		$this->banco = new BancoDados ();
		try {
			$this->banco->connect ();
		} catch ( Exception $e ) {
			echo "Falha na Conexão com Base de Dados" . $e->getMessage ();
		}
	}
	
	public function getOperadoraEmpresa($cod_empresa, $agencia, $dg_agencia, $conta_corrente, $dg_conta_corrente){
		$sql = "SELECT operadora_empresa.* FROM operadora_empresa
				WHERE operadora_empresa.fk_empresa = $cod_empresa AND operadora_empresa.numero_agencia = $agencia AND operadora_empresa.digito_agencia = $dg_agencia AND 
				operadora_empresa.numero_conta = $conta_corrente AND operadora_empresa.digito_conta = $dg_conta_corrente";
// 		echo "\n$sql\n";
		$consulta = $this->banco->getConexaoBanco ()->query ( $sql );
		$lstOE = array ();
		while ( $linha = $consulta->fetch_array ( MYSQLI_ASSOC ) ) {
			array_push ( $lstOE, $linha );
		}
// 		$total = $consulta->num_rows;
		$consulta->close ();
		return $lstOE;
// 		return $total;
	}
}
	