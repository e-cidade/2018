<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require_once modification("interfaces/ILancamentoAuxiliar.interface.php");
require_once modification("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php");

/**
 * Classe que implementa os mйtodos necessarios para generalizar um lanзamento auxiliar
 * Utilizada no reprocessamento de lanзamentos contбbeis
 * @author Bruno Silva
 * @version $Revision: 1.20 $
 */
class LancamentoAuxiliarContaCorrente extends LancamentoAuxiliarBase implements ILancamentoAuxiliar{

	/**
	 * Sequencial da conta corrente (c19_contacorrente - contacorrentedetalhe)
	 * @var integer $iContaCorrente
	 */
	private $iContaCorrente;

	/**
	 * Cуdigo da receita
	 * @var integer $iTipoReceita
	 */
	private $iTipoReceita;

	/**
	 * Cуdigo da instituiзгo (db_config)
	 * @var integer $iInstituicao
	 */
	private $iInstituicao;

	/**
	 * Caracterнstica Peculiar
	 * @var String $sCaracteristicaPeculiar
	 */
	private $sCaracteristicaPeculiar;

	/**
	 * Cуdigo da conta bancбria
	 * @var integer $iContaBancaria
	 */
	private $iContaBancaria;

	/**
	 * Cуdigo reduzido da conta bancбria
	 * @var integer $iReduzidoContaBancaria
	 */
	private $iReduzidoContaBancaria;


	/**
	 * Cуdigo do CGM
	 * @var integer $iCgm
	 */
	private $iCgm;

	/**
	 * Cуdigo do recurso
	 * @var integer $iCodigoRecurso
	 */
	private $iCodigoRecurso;

	/**
	 * Ano da Unidade
	 * @var integer $iUnidadeAnousu
	 */
	private $iUnidadeAnousu;

	/**
	 * Unidade do уrgгo
	 * @var integer $iUnidadeDoOrgao
	 */
	private $iUnidadeDoOrgao;

	/**
	 * Cуdigo da unidade
	 * @var integer $iUnidade
	 */
	private $iUnidade;

	/**
   * Ano do уrgгo
   * @var integer $iOrgaoAnousu
	 */
	private $iOrgaoAnousu;

	/**
	 * Cуdigo do уrgгo
	 * @var integer $iOrgao
	 */
	private $iOrgao;

  /**
   * @var integer
   */
  private $iCodigoReceita;

	/**
	 * Cуdigo do histуrico
	 * @var integer $iCodigoHistorico
	 */
	private $iCodigoHistorico;
	/**
	 * Valor total
	 * @var number $nValorTotal
	 */
	private $nValorTotal;

	/**
	 * Cуdigo sequencial do Acordo
   * @var integer
	 */
	private $iAcordo;

  /**
   * @var DocumentoEventoContabil
   */
  private $oDocumentoEventoContabil;

	/**
	 * Construtor da classe
	 * A classe й contruнda a partir de um dos parвmetros passados para o construtor
	 * 1) a partir de um cуdigo de lanзamento:
	 * 	- buscando todos atributos necessбrios para o preenchimento do objeto
	 * 2) a partir do cуdigo do detalhamento do lanзamento
	 * 	- busca na tabela mapeada pela classe, e reconstroi o objeto
	 *
	 * @param int $iCodigoLancamento
	 * @param int $iCodigoLancamentoValor Cуdigo da conlancamval. Parвmetro temporбrio.
	 * @throws Exception
	 */
	public function __construct($iCodigoLancamento, $iCodigoLancamentoValor = null) {

		$this->iContaCorrente    = null;
		$this->iInstituicao      = db_getsession("DB_instit");
		$this->iCodigoLancamento = $iCodigoLancamento;

    $oDaoDocumento      = new cl_conlancamdoc();
    $sSqlBuscaDocumento = $oDaoDocumento->sql_query_file($iCodigoLancamento);
    $rsBuscaDocumento   = $oDaoDocumento->sql_record($sSqlBuscaDocumento);
    if ($oDaoDocumento->erro_status == "0") {
      throw new Exception("Nгo foi possнvel buscar o documento do lanзamento com cуdigo {$iCodigoLancamento}.");
    }
    $this->oDocumentoEventoContabil = DocumentoEventoContabilRepository::getPorCodigo(db_utils::fieldsMemory($rsBuscaDocumento, 0)->c71_coddoc);

    $this->oContaCorrenteDetalhe = new ContaCorrenteDetalhe();

		/**
		 * propriedades setadas durante reconstruзгo do lanзamento
		 */
		$this->iContaBancaria         = null;
		$this->iReduzidoContaBancaria = null;


		/**
		 *	Busca dados referente ao cgm, empenho e caracteristica peculiar
		 */
		$oDAOConlancam	= db_utils::getDao("conlancam");
		$sWhere					= "c70_codlan = {$this->iCodigoLancamento}";
		$sSQLConlancam	= $oDAOConlancam->sql_query_empenho_cgm(null, "*", null, $sWhere);
		$rsConlancam		= $oDAOConlancam->sql_record($sSQLConlancam);

		if ($oDAOConlancam->numrows > 0) {

			$oConlancam							       = db_utils::fieldsMemory($rsConlancam, 0);
			$this->iCgm 					         = $oConlancam->c76_numcgm;
			$this->iNumeroEmpenho          = $oConlancam->e60_numemp;
			$this->sCaracteristicaPeculiar = $oConlancam->c08_concarpeculiar;


      if (!empty($this->iCgm)) {

        $oCgm = CgmFactory::getInstanceByCgm($this->iCgm);
        $this->oContaCorrenteDetalhe->setCredor($oCgm);
      }

      if (!empty($this->iNumeroEmpenho)) {

        $oEmpenho = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorNumero($this->iNumeroEmpenho);
        $this->oContaCorrenteDetalhe->setEmpenho($oEmpenho);
        $oRecurso  = $oEmpenho->getDotacao()->getDadosRecurso();
        $this->oContaCorrenteDetalhe->setRecurso($oRecurso);
        $this->oContaCorrenteDetalhe->setDotacao($oEmpenho->getDotacao());
      }
			/**
			 * Caso nгo vir caracterнstica peculiar, pegar a mesma do registro do empenho
			 */
			if (empty($this->sCaracteristicaPeculiar)) {
				$this->sCaracteristicaPeculiar = $oConlancam->e60_concarpeculiar;
			}
			unset($oConlancam);
		}


    $oDaoConlancamRec = new  cl_conlancamrec();
    $sSqlDadosReceita = $oDaoConlancamRec->sql_query_dados_receita($iCodigoLancamento);
    $rsDadosReceita   = $oDaoConlancamRec->sql_record($sSqlDadosReceita);
    if ($oDaoConlancamRec->numrows > 0) {

      $oDadosReceita = db_Utils::fieldsMemory($rsDadosReceita, 0);
      $this->oContaCorrenteDetalhe->setRecurso(RecursoRepository::getRecursoPorCodigo($oDadosReceita->o70_codigo));
      $this->oContaCorrenteDetalhe->setEstrutural($oDadosReceita->o57_fonte);
      $this->iCodigoReceita = $oDadosReceita->o70_codrec;
      $this->sCaracteristicaPeculiar = $oDadosReceita->o70_concarpeculiar;
      unset($oDadosReceita);
    }

    /**
     * Verificamos a conta bancaria do lanзamento,
     */
    $oDaoConlancamPag     = new cl_conlancampag();
    $sSqlDadosLancamento  = $oDaoConlancamPag->sql_query_file($iCodigoLancamento);
    $rsDadosContaPagadora = $oDaoConlancamPag->sql_record($sSqlDadosLancamento);
    if ($rsDadosContaPagadora && $oDaoConlancamPag->numrows > 0) {

      $oDadosContaPagadora = db_utils::fieldsMemory($rsDadosContaPagadora, 0);
      $oContaPlano         = ContaPlanoPCASPRepository::getContaPorReduzido($oDadosContaPagadora->c82_reduz,
                                                                            $oDadosContaPagadora->c82_anousu
                                                                           );
      if (!empty($oContaPlano) && $oContaPlano->getContaBancaria() != '') {
        $this->oContaCorrenteDetalhe->setContaBancaria($oContaPlano->getContaBancaria());
      }

      $this->oContaCorrenteDetalhe->setRecurso(RecursoRepository::getRecursoPorCodigo($oContaPlano->getRecurso()));
    }
    /**
		 *  Busca dados referente a unidade e orgгo
		 */
		$oDAOConlancamdot	= new cl_conlancamdot();
		$sSQLConlancamdot	= $oDAOConlancamdot->sql_query(null, "orcdotacao.*, o41_anousu,o41_orgao,o41_unidade,o40_anousu,o40_orgao", null, $sWhere);
		$rsConlancamdot		= $oDAOConlancamdot->sql_record($sSQLConlancamdot);

		if ($oDAOConlancamdot->numrows > 0) {

			$oConlancamdotorcunidade = db_utils::fieldsMemory($rsConlancamdot, 0);
      $oDotacao                = DotacaoRepository::getDotacaoPorCodigoAno($oConlancamdotorcunidade->o58_coddot, $oConlancamdotorcunidade->o58_anousu);
      $this->oContaCorrenteDetalhe->setDotacao($oDotacao);

			$this->iUnidadeAnousu    = $oConlancamdotorcunidade->o41_anousu;
			$this->iUnidadeDoOrgao   = $oConlancamdotorcunidade->o41_orgao;
			$this->iUnidade          = $oConlancamdotorcunidade->o41_unidade;
			$this->iOrgaoAnousu      = $oConlancamdotorcunidade->o40_anousu;
			$this->iOrgao            = $oConlancamdotorcunidade->o40_orgao;

		}

		$oDAOConlancamgrupocorrente	= new cl_conlancamcorgrupocorrente();
		$sWhere											= "c70_codlan = {$this->iCodigoLancamento}";
		$sSQLConlancamgrupocorrente	= $oDAOConlancamgrupocorrente->sql_query_recurso(null, "k00_recurso", null, $sWhere);
		$rsConlancamgrupocorrente   = $oDAOConlancamgrupocorrente->sql_record($sSQLConlancamgrupocorrente);

		$this->iCodigoRecurso		= null;
		if($oDAOConlancamgrupocorrente->numrows > 0) {

			$this->iCodigoRecurso = db_utils::fieldsMemory($rsConlancamgrupocorrente, 0)->k00_recurso;
      $oRecurso             = RecursoRepository::getRecursoPorCodigo($this->iCodigoRecurso);
      $this->oContaCorrenteDetalhe->setRecurso($oRecurso);
		}

		/**
		 * Se for empenho, buscar o nъmero do acordo (contrato)
		 */
		if (!empty($this->iNumeroEmpenho)) {

			$oDAOEmpempenhocontrato	= new cl_empempenhocontrato();
			$sWhere									= "e100_numemp = {$this->iNumeroEmpenho}";
			$sSQLEmpempenhocontrato	= $oDAOEmpempenhocontrato->sql_query_file(null, "e100_acordo", null, $sWhere);
			$rsEmpempenhocontrato   = $oDAOEmpempenhocontrato->sql_record($sSQLEmpempenhocontrato);

			if($oDAOEmpempenhocontrato->numrows > 0) {

				$this->iAcordo = db_utils::fieldsMemory($rsEmpempenhocontrato, 0)->e100_acordo;
        $this->oContaCorrenteDetalhe->setAcordo(AcordoRepository::getByCodigo($this->iAcordo));
			}
		}

		$oDaoPlanilhaReceita = new cl_placaixarec();
    $sSqlBuscaRecurso    = $oDaoPlanilhaReceita->sql_query_planilha_autenticada('k81_codigo', null, "c70_codlan = {$this->iCodigoLancamento}");
    $rsBuscaRecursoPlanilha = db_query($sSqlBuscaRecurso);
    if (!$rsBuscaRecursoPlanilha) {
      throw new Exception("Ocorreu um erro ao buscar a planilha do lanзamento {$this->iCodigoLancamento}.");
    }

    if (pg_num_rows($rsBuscaRecursoPlanilha) > 0) {
      $iCodigoRecurso = db_utils::fieldsMemory($rsBuscaRecursoPlanilha, 0)->k81_codigo;
      $this->oContaCorrenteDetalhe->setRecurso(RecursoRepository::getRecursoPorCodigo($iCodigoRecurso));
    }

    $sWhere   = "c70_codlan = {$this->iCodigoLancamento} and k131_tipo = 2";
    $oDaoSlip    = new cl_slip();
    $sSqlBuscaCP = $oDaoSlip->sql_query_slip_autenticado('k131_concarpeculiar', null, $sWhere);
    $rsBuscaCP   = db_query($sSqlBuscaCP);
    if (!$rsBuscaCP) {
      throw new Exception("Ocorreu um erro ao buscar a Caracteristica Peculiar do lanзamento {$this->iCodigoLancamento}.");
    }
    if (pg_num_rows($rsBuscaCP) == 1) {
      $this->sCaracteristicaPeculiar = db_utils::fieldsMemory($rsBuscaCP, 0)->k131_concarpeculiar;
    }

    $aDocumentosContasCredito = array(120, 140, 151, 161, 163);
    $aDocumentosContasDebito  = array(130, 141, 150, 160, 162);
    $iCodigoDocumento = $this->oDocumentoEventoContabil->getCodigo();

    if (!in_array($iCodigoDocumento, $aDocumentosContasCredito) && !in_array($iCodigoDocumento, $aDocumentosContasDebito)) {
      return;
    }

    if (empty($iCodigoLancamentoValor)) {
      throw new ParameterException("Cуdigo do valor do lanзamento deve ser informado para lanзamentos de slips.");
    }

    $sCamposConplanoreduz  = "distinct c61_codigo as recurso";
    $aWhereConplanoreduz   = array();
    $aWhereConplanoreduz[] = "c69_sequen = {$iCodigoLancamentoValor}";
    $aWhereConplanoreduz[] = "c69_anousu = c61_anousu";

    $oDaoConplanoreduz = new cl_conplanoreduz();
    if (in_array($this->oDocumentoEventoContabil->getCodigo(), $aDocumentosContasCredito)) {
      $aWhereConplanoreduz[] = "c61_reduz = c69_credito";
    }

    if (in_array($this->oDocumentoEventoContabil->getCodigo(), $aDocumentosContasDebito)) {
      $aWhereConplanoreduz[] = "c61_reduz = c69_debito";
    }
    $sWhereConplanoreduz = implode(" and ", $aWhereConplanoreduz);

    $sSqlConPlanoreduz = $oDaoConplanoreduz->sql_query_razao(null, null, $sCamposConplanoreduz, null, $sWhereConplanoreduz);
    $rsConplanoreduz   = db_query($sSqlConPlanoreduz);

    if (!$rsConplanoreduz) {
      throw new DBException("Houve um erro ao buscar o recurso da conta para o lanзamento {$iCodigoDocumento}.");
    }

    if (pg_num_rows($rsConplanoreduz) != 1) {
      throw new BusinessException("Nгo foi encontrado o recurso da conta referente ao lanзamento {$iCodigoDocumento}.");
    }

    $iCodigoRecurso = db_utils::fieldsMemory($rsConplanoreduz, 0)->recurso;

    $this->oContaCorrenteDetalhe->setRecurso(RecursoRepository::getRecursoPorCodigo($iCodigoRecurso));
  }


	/**
	 * Retorna o Cуdigo do caracteristica peculiar
	 * @return integer
	 */
	public function getCaracteristicaPeculiar() {
		return $this->sCaracteristicaPeculiar;
	}

	/**
	 * Retorna o Nъmero do Empenho
	 * @return integer
	 */
	public function getNumeroEmpenho() {
		return $this->iNumeroEmpenho;
	}

	/**
	 * Retorna o Cуdigo do Recurso
	 * @return integer
	 */
	public function getCodigoRecurso() {
		return $this->iCodigoRecurso;
	}

	/**
	 * Retorna o Cуdigo do Acordo
	 * @return integer
	 */
	public function getAcordo() {
		return $this->iAcordo;
	}

	/**
	 * Retorna o Cуdigo do Favorecido
	 * @return integer
	 */
	public function getFavorecido() {
		return $this->iCgm;
	}


	/**
	 * Seta o valor total do lancamento
	 * @see ILancamentoAuxiliar::setValorTotal()
	 */
	public function setValorTotal($nValorTotal) {
		$this->nValorTotal = $nValorTotal;
	}

	/**
	 * Retorna o valor total
	 * @see ILancamentoAuxiliar::getValorTotal()
	 */
	public function getValorTotal() {
		return $this->nValorTotal;
	}

	/**
	 * Seta o codigo do historico
	 * @see ILancamentoAuxiliar::setHistorico()
	 */
	public function setHistorico($iHistorico) {
		$this->iCodigoHistorico = $iHistorico;
	}

	/**
	 * Retorna o codigo do historico
	 * @see ILancamentoAuxiliar::getHistorico()
	 */
	public function getHistorico() {
		return $this->iCodigoHistorico;
	}

	/**
	 * Retorna o complemento do lancamento contabil
	 * @see ILancamentoAuxiliar::getObservacaoHistorico()
	 */
	public function getObservacaoHistorico() {
		return $this->sObservacao;
	}

	/**
	 * Seta o complemento do lancamento contabil
	 * @see ILancamentoAuxiliar::setObservacaoHistorico()
	 */
	public function setObservacaoHistorico($sObservacao) {
		$this->sObservacao = $sObservacao;
	}

  /**
   * @return int
   */
	public function getCodigoReceita() {
	  return $this->iCodigoReceita;
  }

	/**
	 * Executa o lanзamento Auxiliar
	 * Mйtodo necessбrio para implementar a interface ILancamentoAuxiliar
	 * @see ILancamentoAuxiliar::executaLancamentoAuxiliar()
	 */
	public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento) {
		return true;
	}

}

?>