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


/**
 * classe para controlar a depreciação dos bens
 *
 * @author   Raphael Lopes <rafael.lopes@dbseller.com.br>
 * @package  patrimonio
 * @version  1.0 $
 */
class BemDepreciacao {

  private $iAno;
  private $iMes;
  private $iInstituicao;
  private $nValorAtual;

  /**
   * @var float
   */
  private $nValorResidual;

  private $aDepreciacaoSintetica = array();


  /**
   * construtor da classe
   * @param integer $iAno
   * @param integer $iMes
   * @param integer $iInstituicao
   */
  function __construct($iAno = null, $iMes = null, $iInstituicao = null){

    $this->setAno($iAno);
    $this->setMes($iMes);
    $this->setInstituicao($iInstituicao);

  }

  /**
   * Seta o ano do processamento
   * @param integer $iAno
   */
  public function setAno($iAno) {
    $this->iAno = $iAno;
  }

  /**
   * Retorna o Ano do Processamento
   * @return integer
   */
  public function getAno() {
    return $this->iAno;
  }

  /**
   * Seta o mes do processamento
   * @param integer $iMes
   */
  public function setMes($iMes) {
    $this->iMes = $iMes;
  }

  /**
   * Retorna o Mes do Processamento
   * @return integer
   */
  public function getMes() {
    return $this->iMes;
  }

  /**
   * Seta instituicao do processamento
   * @param integer $iInstituicao
   */
  public function setInstituicao($iInstituicao) {
    $this->iInstituicao = $iInstituicao;
  }

  /**
   * Retorna instituicao do Processamento
   * @return integer
   */
  public function getInstituicao() {
    return $this->iInstituicao;
  }


  /**
   * Retorna o valor atual do Bem
   * @return number
   */
  public function getValorAtual() {
    return $this->nValorAtual;
  }

  /**
   * Seta o valor atual do Bem
   * @param number
   */
  public function setValorAtual($nValorAtual) {
    $this->nValorAtual = $nValorAtual;
  }



  /**
   * Seta array depreciação sintetica
   * @param array $DepreciacaoSintetica
   */
  public function setDepreciacaoSintetica($aDepreciacaoSintetica) {
    $this->aDepreciacaoSintetica = $aDepreciacaoSintetica;
  }

  /**
   * Retorna array DepreciacaoSintetica
   * @return array
   */
  public function getDepreciacaoSintetica() {
    return $this->aDepreciacaoSintetica;
  }

  /**
   * @return float
   */
  public function getValorResidual() {
    return $this->nValorResidual;
  }

  /**
   * @param float $nValorResidual
   */
  public function setValorResidual($nValorResidual) {
    $this->nValorResidual = $nValorResidual;
  }

  /**
   * Funcao que retorna a data de implantacao da depreciacao, caso tenha sido implantada
   * @return  date
   * @return  null
   */
  public static function retornaDataImplantacaoDepreciacao($iInstituicao = null) {

    if ($iInstituicao == null) {
      $iInstituicao = db_getsession("DB_instit");
    }

    $oDaoCfpatriinstituicao     = db_utils::getDao("cfpatriinstituicao");
    $sWhereImplantancao         = "t59_instituicao = {$iInstituicao}";
    $sSqlImplantacaoDepreciacao = $oDaoCfpatriinstituicao->sql_query_file(null,"*",null,$sWhereImplantancao);
    $rsImplantacaoDepreciacao   = $oDaoCfpatriinstituicao->sql_record($sSqlImplantacaoDepreciacao);

    if ($oDaoCfpatriinstituicao->numrows == 0) {
      return null;
    }

    $oImplantacao = db_utils::fieldsMemory($rsImplantacaoDepreciacao, 0);
    return $oImplantacao ->t59_dataimplanatacaodepreciacao;
  }



  /**
   * metodo responsavel por anular a depreciação
   * alimenta o objeto LancamentoAuxiliarDepreciacao
   * e passa por parâmetro para o executarLancamentoCOntabil()
   */
  public function executarLancamentosDepreciacao($sObservacao , $iDocumento, $lEstorno) {

    $sEstorno = ($lEstorno?"true":"false");

    $iDocumentoExecutar       = $iDocumento;
    $dtImplantacaoDepreciacao = self::retornaDataImplantacaoDepreciacao();

    $sWhereDataImplantacao    = "";
    if ($dtImplantacaoDepreciacao != null) {
      $sWhereDataImplantacao  = "t78_data > {$dtImplantacaoDepreciacao}";
    }

    $oDaoBensDepreciacaoLancamento = db_utils::getDao("bensdepreciacaolancamento");
    $sWhere                        = "t78_instit = {$this->iInstituicao} and t78_mes = {$this->iMes} and t78_ano = {$this->iAno}";
    $sSqlBensDepreciacaoLancamento = $oDaoBensDepreciacaoLancamento->sql_query_file(null, "*", null, $sWhere);
    $rsBensDepreciacaoLancamento   = $oDaoBensDepreciacaoLancamento->sql_record($sSqlBensDepreciacaoLancamento);

    if ($oDaoBensDepreciacaoLancamento->erro_status == "0" && $lEstorno == true) {
      throw new BusinessException("Não existe lançamento de depreciação para o ano e mes selecionado !");
    }

    $oDaoBensDepreciacaoLancamento->t78_usuario   =	db_getsession("DB_id_usuario");
    $oDaoBensDepreciacaoLancamento->t78_instit    =	db_getsession("DB_instit");
    $oDaoBensDepreciacaoLancamento->t78_mes       = $this->iMes;
    $oDaoBensDepreciacaoLancamento->t78_ano       = $this->iAno;
    $oDaoBensDepreciacaoLancamento->t78_data      = date('Y-m-d', db_getsession("DB_datausu"));
    $oDaoBensDepreciacaoLancamento->t78_estornado = $sEstorno;

    if ($oDaoBensDepreciacaoLancamento->numrows > 0) {

      $oBensDepreciacaoLancamento 									  = db_utils::fieldsMemory($rsBensDepreciacaoLancamento, 0);
      $oDaoBensDepreciacaoLancamento->t78_sequencial	= $oBensDepreciacaoLancamento->t78_sequencial;
      $oDaoBensDepreciacaoLancamento->alterar($oBensDepreciacaoLancamento->t78_sequencial);
    } else {
      $oDaoBensDepreciacaoLancamento->incluir(null);
    }

    if ($oDaoBensDepreciacaoLancamento->erro_status == "0") {
      throw new BusinessException("Erro técnico: Não foi possível estornar o lançamento da depreciação!");
    }

    $oEventoContabil  = new EventoContabil($iDocumentoExecutar, $this->iAno);
    $aLancamentos     = $oEventoContabil->getEventoContabilLancamento();
    $iCodigoHistorico = $aLancamentos[0]->getHistorico();
    unset($aLancamentos);
    unset($oEventoContabil);

    $aDadosSintetico = $this->getDadosSintetico();

    /**
     * Para cada agrupamento realizar um lançamento contabil
     */
    $iAnoSessao = db_getsession("DB_anousu");
    foreach ($aDadosSintetico as $oStdSintetico) {

      $oPlanoConta          = ContaPlanoPCASPRepository::getContaByCodigo($oStdSintetico->iPlanoConta, $iAnoSessao);
      $iCodigoContaReduzida = $oPlanoConta->getReduzido();

      if (empty($iCodigoContaReduzida)) {
        throw new BusinessException("Nenhuma conta reduzida cadastrada para a conta {$oStdSintetico->iPlanoConta} - {$oPlanoConta->getDescricao()}.");
      }
      unset($oPlanoConta);
      unset($aContasReduzidas);

      $oLancamentoAuxiliarDepreciacao = new LancamentoAuxiliarDepreciacao();
      $oLancamentoAuxiliarDepreciacao->setObservacaoHistorico($sObservacao);
      $oLancamentoAuxiliarDepreciacao->setValorTotal($oStdSintetico->nValorDepreciacao);
      $oLancamentoAuxiliarDepreciacao->setHistorico($iCodigoHistorico);
      $oLancamentoAuxiliarDepreciacao->setCodigoConta($iCodigoContaReduzida);
      $oLancamentoAuxiliarDepreciacao->setBensDepreciacaoLancamento($oDaoBensDepreciacaoLancamento->t78_sequencial);
      $oLancamentoAuxiliarDepreciacao->setEstorno($lEstorno);
      $this->executarLancamentoContabil($oLancamentoAuxiliarDepreciacao, $iDocumentoExecutar);
    }

    return true;
  }

  /**
   * metodo responsavel por processar o lancamento
   *
   * @param string sObservacao
   */
  public function processarLancamentos($sObservacao) {

  	$iDocumentoExecutar = 604;

    return $this->executarLancamentosDepreciacao($sObservacao , $iDocumentoExecutar, false);
  }
  /**
   * metodo responsavel por estornar o lancamento
   *
   * @param string sObservacao
   */
  public function estornarLancamentos($sObservacao) {

    $iDocumentoExecutar = 605;

    return $this->executarLancamentosDepreciacao($sObservacao , $iDocumentoExecutar, true);
  }


  /**
   * Metodo criado para executar lancamento contabil
   * Recebe como parametro un $oLancamentoAuxiliarMovimentacaoEstoque
   * Executar o lancamento cfe exemplo da Classe Transferencia.model., e codigo do tipo do documento.
   * @param object  $oLancamentoAuxiliarMovimentacaoEstoque
   * @param integer $iCodigoDocumento
   * @return bool
   */
  protected function executarLancamentoContabil($oLancamentoAuxiliarMovimentacaoEstoque , $iCodigoDocumento) {

  	$oDocumentoContabil       = SingletonRegraDocumentoContabil::getDocumento($iCodigoDocumento);
  	$iCodigoDocumentoExecutar = $oDocumentoContabil->getCodigoDocumento();
  	$oEventoContabil          = new EventoContabil($iCodigoDocumentoExecutar, db_getsession("DB_anousu"));
  	$oEventoContabil->executaLancamento($oLancamentoAuxiliarMovimentacaoEstoque);
  	return true;
  }

  /**
   * metodo estatico para retornar competencias disponiveis
   * bensdepreciacaolancamento
   * @param integer $iInstituicao
   * @return object stdClass
   */
  public static function getCompetenciaDisponivel($iInstituicao, $lEstorno = false) {

  	$oDaoBensDepreciacaoLancamento = db_utils::getDao("bensdepreciacaolancamento");

  	$sCampos  = "t78_mes, ";
  	$sCampos .= "t78_ano, ";
  	$sCampos .= "t78_data ";

  	$sEstornado = $lEstorno ? 'true' : 'false';


  	/**
  	 * Verifica data de implantação, para que filtre somente as depreciações posteriores a implantação
  	 * Não deverá ser possibilitado ao usuário, lançar e estornar depreciações de implantação
  	 */
  	$dtImplantacao     = self::retornaDataImplantacaoDepreciacao();
  	$sWhereImplantacao = "";

  	if ($dtImplantacao == null) {
  	  throw new Exception ("Não configurado parâmetro de implantação");
  	}

	  $aDataImplantacao  = explode("-",$dtImplantacao);
	  $sMesImplantacao   = $aDataImplantacao[1];
	  $sAnoImplantacao   = $aDataImplantacao[0];
	  $sWhereImplantacao = " AND ( t78_ano > {$sAnoImplantacao} OR ( t78_ano = {$sAnoImplantacao} AND t78_mes >= {$sMesImplantacao} ))";

  	// $sWhere            = "t78_instit = {$iInstituicao} and t78_estornado = {$sEstornado}";
  	$sWhere            = "t78_instit = {$iInstituicao} and t78_estornado is false ";
  	$sWhere            = $sWhere.$sWhereImplantacao;
  	$sSql              = $oDaoBensDepreciacaoLancamento->sql_query_file(null, $sCampos, "t78_ano desc, t78_mes desc  limit 1", $sWhere);

  	$rsBensDepreciacaoLancamento = $oDaoBensDepreciacaoLancamento->sql_record($sSql);

  	if (!$rsBensDepreciacaoLancamento && $lEstorno) {
  	  throw new Exception ("Não possui lançamentos para estorno.");
  	}

  	$oDadosCompetencia 	= new stdClass();

  	/**
  	 * Em caso de não haver lançamentos posteriores a implantação da depreciação
  	 * deve retornar a data da implantação
  	 */
  	if ($oDaoBensDepreciacaoLancamento->numrows == 0) {

  	  $oDadosCompetencia->iMes         = $sMesImplantacao;
  	  $oDadosCompetencia->iAno         = $sAnoImplantacao;
  	  $oDadosCompetencia->dtData       = db_getsession("DB_datausu");
  	  return $oDadosCompetencia;
  	}

  	$oDadosBensDepreciacaoLancamento = db_utils::fieldsMemory($rsBensDepreciacaoLancamento, 0);

  	$oDadosCompetencia->iMes         = $oDadosBensDepreciacaoLancamento->t78_mes;
  	$oDadosCompetencia->iAno         = $oDadosBensDepreciacaoLancamento->t78_ano;
  	$oDadosCompetencia->dtData       = $oDadosBensDepreciacaoLancamento->t78_data;

  	if ($lEstorno) {
  	  return $oDadosCompetencia;
  	}


  	if ( $oDadosCompetencia->iMes == 12 ) {

  		$oDadosCompetencia->iMes = 1;
  		$oDadosCompetencia->iAno++;
  	} else {

  	  $oDadosCompetencia->iMes++;
  	}

  	return $oDadosCompetencia;
  }


  /**
   * Percorre os bens da planilha e agrupa os dados por classificacao
   *
   * @access public
   * @return array
   */
  public function getDadosSintetico() {

		$oDaoBensHistoricoCalculo = new cl_benshistoricocalculo();
		$sWhereDadosPlanilha      = "t57_ano = {$this->iAno} and t57_mes = {$this->iMes} and t57_instituicao = {$this->iInstituicao} ";
    $sWhereDadosPlanilha     .= " and  t57_processado is true and t57_ativo is true and t57_tipocalculo = 1";
		$sSqlDadosPlanilha        = $oDaoBensHistoricoCalculo->sql_query(null, 't57_sequencial', null, $sWhereDadosPlanilha);

		$rsDadosPlanilha          = $oDaoBensHistoricoCalculo->sql_record($sSqlDadosPlanilha);
		$iCalculos                = $oDaoBensHistoricoCalculo->numrows;

		/**
		 * Nao encontrou calculos para o periodo e insituicao
		 */
		if ($iCalculos == 0) {
			throw new Exception('Nenhuma depreciação encontrada para o periodo');
		}

		/**
		 * Percorre o historico e pega instancia PlanilhaCalculo
		 */
		for ( $iIndice = 0; $iIndice < $iCalculos; $iIndice++ ) {

			$oCalculo  = db_utils::fieldsMemory($rsDadosPlanilha, $iIndice);

			$oPlanilha = new PlanilhaCalculo($oCalculo->t57_sequencial);
			$aCalculos = $oPlanilha->getCalculos();

			/**
			 * Percorre os calculos da planilha e agrupa por classificacao
			 */
			foreach( $aCalculos as $oCalculo ) {

				$oBem = $oCalculo->getBem();

				/**
				 * Continua se tipo de depreciacao for 6 - REAVALIACAO
				 */
				if ( $oBem->getTipoDepreciacao()->getCodigo() == 6 ) {
					continue;
				}

				/**
				 * Codigo da classificacao
				 */
				$iClassificacao = $oBem->getClassificacao()->getCodigo();
				$iCodigoConta   = $oBem->getClassificacao()->getCodigoContaDepreciacao();

        if ($iCodigoConta == null) {
          throw new Exception (" Existe Classificação de Bem sem associação no plano de contas Classificação:{$iClassificacao}");
        }

        /**
         * Conta PCASP
         */
        $oContaPlanoPCASP = ContaPlanoPCASPRepository::getContaByCodigo($iCodigoConta, $this->iAno);
        $sDescricaoConta  = $oContaPlanoPCASP->getDescricao();

				/**
				 * Agrupa os dados da planilha por classificacao
				 */
				if ( isset($this->aDepreciacaoSintetica[$iCodigoConta]) ) {
					$this->aDepreciacaoSintetica[$iCodigoConta]->nValorDepreciacao += $oCalculo->getValorCalculado();
				} else {

					$oDepreciacaoSintetica                     = new StdClass();
					$oDepreciacaoSintetica->iClassificacao     = $iClassificacao;
					$oDepreciacaoSintetica->iPlanoConta        = $iCodigoConta;
					$oDepreciacaoSintetica->sDescricaoConta    = $sDescricaoConta;
					$oDepreciacaoSintetica->nValorDepreciacao  = $oCalculo->getValorCalculado();
					$this->aDepreciacaoSintetica[$iCodigoConta] = $oDepreciacaoSintetica;
				}
			}
		}

		return $this->aDepreciacaoSintetica;
  }


  /**
   * Retorna uma instância de BemDepreciacao com a ultima depreciação
   * ou a depreciação do mês e ano da data repassada
   * @param  Bem $oBem
   * @param  DBDate $oDate
   * @return BemDepreciacao $oBemDepreciacao
   */
  public static function getInstance(Bem $oBem, DBDate $oDate = null) {

    $iCodigoInstituicao    = $oBem->getInstituicao();
    $iCodigoBem            = $oBem->getCodigoBem();
    //$sWhereDataReavaliacao = " 1 = 1 ";

    $sWhereDataReavaliacao   = "     t58_bens        = {$iCodigoBem}         ";
    $sWhereDataReavaliacao  .= " and t57_instituicao = {$iCodigoInstituicao} ";
    $sWhereDataReavaliacao  .= " and t57_processado  = true                  ";
    $sWhereDataReavaliacao  .= " and t57_ativo       = true                  ";


    /**
     * Filtra data, caso ela seja passada como parâmentro
     */
    if (!empty($oDate)) {
      $sWhereDataReavaliacao .= " and t57_mes = {$oDate->getMes()} ";
      $sWhereDataReavaliacao .= " and t57_ano = {$oDate->getAno()} ";
    }

    $oDaoBemDepreciacao = db_utils::getDao("benshistoricocalculobem");
    $sOrder             = " t57_ano desc, t57_mes desc limit 1 ";
    $sSql               = $oDaoBemDepreciacao->sql_query_calculo(null, "*", $sOrder, $sWhereDataReavaliacao);
    $rsResultadoQuery   = $oDaoBemDepreciacao->sql_record($sSql);
    $oBemDepreciacao    = null;

    if ($oDaoBemDepreciacao->numrows == 1) {

      $oStdDadosDepreciacao = db_utils::fieldsMemory($rsResultadoQuery, 0);
      $oBemDepreciacao = new BemDepreciacao();
      $oBemDepreciacao->setMes( $oStdDadosDepreciacao->t57_mes );
      $oBemDepreciacao->setAno( $oStdDadosDepreciacao->t57_ano );
      $oBemDepreciacao->setInstituicao( $oStdDadosDepreciacao->t57_instituicao );
      $oBemDepreciacao->setValorAtual( $oStdDadosDepreciacao->t58_valoratual );
      $oBemDepreciacao->setValorResidual($oStdDadosDepreciacao->t58_valorresidual );
    }

    return $oBemDepreciacao;
  }
}