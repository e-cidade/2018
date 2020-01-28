<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2015 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

/**
 * Classe para controle de assentamentos de RRA
 * 
 * @package Pessoal
 * Class AssentamentoRRA
 */
class AssentamentoRRA extends Assentamento {
	
	/**
	 * Código da natureza do assentamento
	 *@var integer 
	 */
	const CODIGO_NATUREZA = 3;

	/**
	 * Valor total devido no RRA
	 * @var float
	 */
	private $nValorTotalDevido = 0;

	/**
	 * Número de meses devido
	 * @var integer
	 */
	private $iNumeroDeMeses = null;

	/**
	 * Valor pago em encargos judiciais.
	 * @var float
	 */
	private $nEncargosJudiciais = 0;

	/**
	 * AssentamentoRRA constructor.
	 * Instância um assentamento de RRA
	 *
	 * @param int|null $iCodigo Código do assentamento
	 * @throws BusinessException
	 * @throws DBException
	 */
	function __construct($iCodigo = null) {
		
		if (empty($iCodigo)) {
			return;
		}
		
		parent::__construct($iCodigo);
		
		$oDaoAssentamentoRRA = new cl_assentamentorra();
		$sSqlRRA             = $oDaoAssentamentoRRA->sql_query_file($iCodigo, "*", null, "");
		$rsRAA               = db_query($sSqlRRA);
		if (!$rsRAA) {
			throw new DBException("Erro ao consultar dados do RRA {$iCodigo}.");
		}
		
	  if (pg_num_rows($rsRAA) != 1) {
			throw new BusinessException("Assentamento de RRA {$iCodigo} não encontrada no sistema.");
		}
		
		$oDadosRRA = db_utils::fieldsMemory($rsRAA, 0);
		/*
		 * @TODO verificar com o nery se o campo já foi implementado
		 */
		$this->setValorDosEncargosJudiciais(0);
		$this->setValorTotalDevido($oDadosRRA->h83_valor);
		$this->setNumeroDeMeses($oDadosRRA->h83_meses);
		$this->setValorDosEncargosJudiciais($oDadosRRA->h83_encargos);
	}
	
	/**
	 * Retorna o total devido do RRA
	 * @return float
	 */
	public function getValorTotalDevido() {
		return $this->nValorTotalDevido;
	}
	
	/**
	 * Define o valor Devido do RRA
	 * @param float $nValorTotalDevido
	 */
	public function setValorTotalDevido($nValorTotalDevido) {
		$this->nValorTotalDevido = $nValorTotalDevido;
	}
	
	/**
	 * Retorna o numero de meses do RRA
	 * @return int
	 */
	public function getNumeroDeMeses() {
		return $this->iNumeroDeMeses;
	}
	
	/**
	 * Define o número de meses do RRA
	 * @param int $iNumeroDeMeses
	 */
	public function setNumeroDeMeses($iNumeroDeMeses) {
		$this->iNumeroDeMeses = $iNumeroDeMeses;
	}
	
	/**
	 * Retorna o valar pago de encargos judiciais
	 * @return float
	 */
	public function getValorDosEncargosJudiciais() {
		return $this->nEncargosJudiciais;
	}
	
	
	/**
	 * Define o vlaor dos encargos judiciais. 
	 * Os encargos judiciais de um RRA servem como dedução da base para IR devido.
	 * @param float $nEncargosJudiciais
	 */
	public function setValorDosEncargosJudiciais($nEncargosJudiciais) {
		
		$this->nEncargosJudiciais = $nEncargosJudiciais;
	}
	
	/**
	 * Persiste os dados do assentamento no banco de dados
	 * Para a inclusão dos dados, o valor total devido e o numero de meses deve ser informado
	 * @throws DBException
	 */
	public function persist($lSomenteRRA = false) {
		
		$this->validarDadosObrigatorios();
		
		if (!db_utils::inTransaction()) {
			throw new DBException("Transação com o banco de dados não encontrada.");
		}
		
		if($lSomenteRRA === false) {
  	  $mRetornoPersistencia = parent::persist(); 
		  if (!$mRetornoPersistencia instanceof Assentamento) {
		    throw new BusinessException($mRetornoPersistencia);	
		  }
		}

		$nValorEncargos = $this->getValorDosEncargosJudiciais();
		if (empty($nValorEncargos)) {
			$nValorEncargos = 0;
		}
		$oDaoAssentamentoRRA               = new cl_assentamentorra();
		$oDaoAssentamentoRRA->h83_meses    = $this->getNumeroDeMeses();
		$oDaoAssentamentoRRA->h83_valor    = $this->getValorTotalDevido();
		$oDaoAssentamentoRRA->h83_encargos = "{$nValorEncargos}";

		$sSqlVerificaExistenciaAssentamentoRRA = $oDaoAssentamentoRRA->sql_query_file($this->getCodigo(), "*", null, "");
		$rsVerificaExistenciaAssentamentoRRA   = db_query($sSqlVerificaExistenciaAssentamentoRRA);

		if(!$rsVerificaExistenciaAssentamentoRRA) {
			throw new DBException("Ocorreu um erro ao consultar a base de dados");
		}

		$iCodigoAssentamento   = null;
		if(pg_num_rows($rsVerificaExistenciaAssentamentoRRA) == 1) {
			$iCodigoAssentamento = $this->getCodigo();
		}
		
		if (empty($iCodigoAssentamento)) {
			
			$oDaoAssentamentoRRA->incluir($this->getCodigo());

		} else {

			$oDaoAssentamentoRRA->h83_assenta = $iCodigoAssentamento;
			$oDaoAssentamentoRRA->alterar($iCodigoAssentamento);
		}

		$this->setCodigo($oDaoAssentamentoRRA->h83_assenta);
		
		if ($oDaoAssentamentoRRA->erro_status == 0) {
			throw new BusinessException("Erro ao persistir os dados do RRA.\nErro:{$oDaoAssentamentoRRA->erro_sql}");
		}
	}
	
	/**
	 * Realiza a validação dos dados obrigatórios para a persistencia 
	 * @throws BusinessException
	 */
	private function validarDadosObrigatorios() {
		
		if (empty($this->iNumeroDeMeses)) {
			throw new BusinessException("Número de meses deve ser informado.");
		}
		
		if (empty($this->nValorTotalDevido)) {
			throw new BusinessException("Valor total devido deve ser informado.");
		}
	}
	
	/**
	 * Converte os dados da classe emm um objeto json
	 * @return stdClass
	 */
	public function toJSON() {
		
		$oDados = json_decode(parent::toJSON());
		if (empty($oDados)) {
		  $oDados = new \stdClass();
    }
		$oDados->natureza                       = "RRA";
		$oDados->valor_total_devido             = $this->getValorTotalDevido();
		$oDados->numero_meses                   = $this->getNumeroDeMeses();
		$oDados->encargos_judiciais             = $this->getValorDosEncargosJudiciais();
		return json_encode($oDados);
	}

	/**
	 * Exclui os dados de RRA de um assentamento ou um assentamento de RRA completamente
	 */
	public function excluir($lSomenteRRA = false) {

		$oDaoAssentamentoRRA = new cl_assentamentorra();
		$oDaoAssentamentoRRA->excluir($this->getCodigo());

		if ($oDaoAssentamentoRRA->erro_status == 0) {
			throw new BusinessException("Erro ao exluir os dados do RRA.\nErro:{$oDaoAssentamentoRRA->erro_sql}");
		}

		if($lSomenteRRA === false) {
  	  AssentamentoRepository::excluir($this);
		}
	}
}
