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
 * Classe para autenticar planilha de arrecadação
 * @author matheus.felini
 * @package caixa
 * @version $Revision: 1.31 $
 */
class AutenticacaoPlanilha {

  /**
   * Planilha de Arrecadacao
   * @var PlanilhaArrecadacao
   */
  private $oPlanilha;

  /**
   * Data da autenticacao
   * @var integer
   */
  private $dtAutenticacao;

  /**
   * IP do terminal em que foi autenticado a planilha
   * @var string
   */
  private $sIpTerminal;

  /**
   * Codigo do usuario que esta autenticando a planilha
   * @var integer
   */
  private $iCodigoUsuario;

  /**
   * Constrói o objeto para executar a autenticação da planilha passada pelo parâmetro ao construtor
   * @param  PlanilhaArrecadacao $oPlanilha
   * @throws ParameterException
   */
  public function __construct (PlanilhaArrecadacao $oPlanilha = null) {

    if (!$oPlanilha instanceof PlanilhaArrecadacao) {
      throw new ParameterException("Não é um objeto do tipo PlanilhaArrecadacao.");
    }
    $this->oPlanilha      = $oPlanilha;
    $this->dtAutenticacao = date("Y-m-d", db_getsession("DB_datausu"));
    $this->iCodigoUsuario = db_getsession("DB_id_usuario");
    $this->sIpTerminal    = db_getsession("DB_ip");
  }

  /**
   * Autentica as receitas inclusas em uma planilha
   * @throws BusinessException
   */
  public function autenticar() {

    if (!db_utils::inTransaction()) {
      throw new BusinessException("Sem transação ativa com o banco de dados.");
    }

    $iCodigoPlanilha   = $this->oPlanilha->getCodigo();
    $sSqlAutenticacao  = " select fc_autenticaplanilha({$iCodigoPlanilha}, '{$this->dtAutenticacao}', ";
    $sSqlAutenticacao .=                             "'{$this->sIpTerminal}', $this->iCodigoUsuario, true)";

    $rsAutenticacao    = db_query($sSqlAutenticacao);
    if (!$rsAutenticacao) {
      throw new BusinessException("Não foi possível autenticar a planilha.\nPossível causa: Conciliação da conta bancária pode estar fechada. Verifique.");
    }

  	$sRetornoAutenticacao = db_utils::fieldsMemory($rsAutenticacao, 0)->fc_autenticaplanilha;
  	if (substr($sRetornoAutenticacao, 0, 1) != '1') {

  		$sMsgErro  = "Erro ao Autenticar.\n";
  		$sMsgErro .= $sRetornoAutenticacao;
  		throw new BusinessException($sMsgErro);
  	}

  	$aAutenticacoes = explode(",", substr_replace($sRetornoAutenticacao, '', 0, 1));

  	foreach ($aAutenticacoes as $iCodigoAutenticacao) {

  	  $oDadosAutenticacao  = self::getDadosAutenticacao(null);
  	  $lReceita           = $this->executarLancamentoContabeis($iCodigoAutenticacao, false, $oDadosAutenticacao);
  	  $lReceitaExtra      = $this->executarLancamentosReceitaExtraOrcamentaria($iCodigoAutenticacao, false, $oDadosAutenticacao);

  	  if (!$lReceita && !$lReceitaExtra) {
  	    throw new BusinessException("Não encontradas receitas para serem arrecadadas");
  	  }

  	}

  	return true;
  }

  /**
   * Estorna a autenticação da planilha
   * @throws BusinessException
   * @return boolean true
   */
  public function estornar() {

    if (!db_utils::inTransaction()) {
    	throw new BusinessException("Sem transação ativa com o banco de dados.");
    }

    $sIpAutenticadora = db_getsession("DB_ip");
    $iIdUsuario       = db_getsession("DB_id_usuario");
    $dtEstorno        = date('Y-m-d',db_getsession("DB_datausu"));

    $sSql      = "select fc_estornoplanilha({$this->oPlanilha->getCodigo()}, '{$dtEstorno}', '{$sIpAutenticadora}', {$iIdUsuario}, true)";
    $rsEstorno = db_query($sSql);

    if (!$rsEstorno) {
      throw new BusinessException("Não foi possível estornar a autenticação da planilha.\nPossível causa: Conciliação da conta bancária pode estar fechada. Verifique.");
    }

  	$sRetornoEstorno = db_utils::fieldsMemory($rsEstorno, 0)->fc_estornoplanilha;
  	if (substr($sRetornoEstorno, 0, 1) != '1') {

  		$sMsgErro  = "Erro ao Autenticar.\n";
  		$sMsgErro .= $sRetornoEstorno;
  		throw new BusinessException($sMsgErro);
  	}

  	$aAutenticacoes = explode(",", substr_replace($sRetornoEstorno, '', 0, 1));

  	if ($this->oPlanilha->existeLancamentoContabil()) {

    	foreach ($aAutenticacoes as $iCodigoAutenticacao) {

    	  $oDadoAutenticacao  = self::getDadosAutenticacao(null);
    		$lReceita           = $this->executarLancamentoContabeis($iCodigoAutenticacao, true, $oDadoAutenticacao );
    		$lReceitaExtra      = $this->executarLancamentosReceitaExtraOrcamentaria($iCodigoAutenticacao, true, $oDadoAutenticacao );

    		if (!$lReceita && !$lReceitaExtra) {
    		  throw new BusinessException("Não encontradas receitas para serem arrecadadas");
    		}
    	}
  	}
    return true;
  }


  public function executarLancamentoContabeis($iCodigoAutenticacao, $lEstorno=false, $oDadoAutenticacao) {


    $oDaoCorrente           = db_utils::getDao('corrente');
    $sSqlBuscaDadosCorrente = $oDaoCorrente->sql_query_arrecadacao_receita($oDadoAutenticacao->k12_id,
                                                                           $oDadoAutenticacao->k12_data,
                                                                           $iCodigoAutenticacao,
                                                                           "xxx.*, orcreceita.o70_codigo");


    $rsBuscaDadosPlanilha   = db_query($sSqlBuscaDadosCorrente);

    if ( !$rsBuscaDadosPlanilha) {
      $sMsgErro = "Não é possível buscar os dados da autenticação para executar os lançamentos contábeis.";
      throw new DBException($sMsgErro);
    }

    $iTotalReceitas = pg_num_rows($rsBuscaDadosPlanilha);

    if ($iTotalReceitas == 0) {
      return false;
    }

    for ($iRowReceita = 0; $iRowReceita < $iTotalReceitas; $iRowReceita++) {

    	$oDadoSqlGeral = db_utils::fieldsMemory($rsBuscaDadosPlanilha, $iRowReceita);
    	$aReceitas     = array();

      $iAno              = db_getsession('DB_anousu');
    	$oReceitaContabil  = ReceitaContabilRepository::getReceitaByCodigo($oDadoSqlGeral->k02_codrec, $iAno);
      $iCodigoEstrutural = substr($oReceitaContabil->getContaOrcamento()->getEstrutural(), 0, 1);

      /**
       *
       *
       * @todo
       * Precisamos urgentemente refatorar este model, principalmente este trecho. O mesmo foi criado para atender
       * uma solicitação urgente do PO (Product Owner)
       *
       * Descrição do Problema:
       *  - Quando o usuário cria uma planilha lançando valores negativos na receita, o programa deve entender que
       *  se trata de um estorno, portanto devemos corrigir as variáveis com o valor necessário e setar a flag
       *  estorno = true.
       *
       *  - Quando o usuário acessar a rotina de ESTORNO dessa planilha com valores negativos devemos entender que não
       *  se trata de um estorno e sim de uma arrecadação.
       *
       *  Básicamente, a lógica se INVERTE quando se trata de receita com valores negativos. O mesmo acontece quando se
       *  trata de uma receita de dedução. Ou seja, começa com o estrutural ilike '9%'
       *
       *
       */
    	if ($lEstorno) {

    		if ($iCodigoEstrutural == 9) {


    		  $oDadoSqlGeral->arrecada = abs($oDadoSqlGeral->arrecada);
    		  $lEstorno = false;
    		} else if ($oDadoSqlGeral->arrecada > 0 && $oDadoSqlGeral->estorna == 0 && $lEstorno) {

    		  $oDadoSqlGeral->arrecada = abs($oDadoSqlGeral->arrecada);
    		  $lEstorno = false;

    		} else {

    		  $oDadoSqlGeral->arrecada = abs($oDadoSqlGeral->estorna);
    		  $lEstorno = true;
    		}
    	}

    	if ($iCodigoEstrutural == 9 &&
    	    $oDadoSqlGeral->arrecada == 0 && !$lEstorno ) {
    	    $oDadoSqlGeral->arrecada = abs($oDadoSqlGeral->estorna);
    	    $lEstorno = true;
    	}

    	/**
    	 * Quando a planilha é criada com valor negativo, devemos entender que ele está estornando algum lançamento, portanto
    	 * setamos a variável estorno = true para que a receita saiba o que fazer de lançamento para estorno
    	 */
    	if ($oDadoSqlGeral->arrecada == 0 && $oDadoSqlGeral->estorna < 0 && !$lEstorno) {

    	  $oDadoSqlGeral->arrecada = abs($oDadoSqlGeral->estorna);
    	  $lEstorno = true;
    	}

    	// @todo - revisar questao da receita (codrec) para mais de uma instituicao
    	// @todo - arrumar nome para este metodo
    	$oReceitaContabil->processaLancamentosReceita($oDadoSqlGeral->arrecada,
    	                                              $oDadoAutenticacao->k12_id,
    	                                              $oDadoAutenticacao->k12_data,
    	                                              $oDadoSqlGeral->k12_autent,
    	                                              $lEstorno,
    	                                              $oDadoSqlGeral->k12_conta,
    	                                              $oDadoSqlGeral->k12_histcor,
    	                                              $this->oPlanilha->getCodigo());
    }
    return true;
  }

  /**
   * Executa os lançamentos contábeis para receitas extras orçamentárias
   *
   * @param integer $iCodigoAutenticacao
   * @param string  $lEstorno
   * @return bool
   * @throws BusinessException
   */
  public function executarLancamentosReceitaExtraOrcamentaria($iCodigoAutenticacao, $lEstorno = false, $oDadoAutenticacao) {

    $sCamposExtra  = "corrente.k12_autent, corrente.k12_data, corrente.k12_id, k12_conta,";
    $sCamposExtra .= "tabrec.k02_codigo,";
    $sCamposExtra .= "k02_reduz,";
    $sCamposExtra .= "k81_concarpeculiar,";
    $sCamposExtra .= "k81_codigo,";
    $sCamposExtra .= "k81_numcgm,";
    $sCamposExtra .= "k12_histcor,";
    $sCamposExtra .= "corrente.k12_id,";
    $sCamposExtra .= "corrente.k12_autent,";
    $sCamposExtra .= "corrente.k12_estorn,";
    $sCamposExtra .= "case when";
    $sCamposExtra .= "  corrente.k12_estorn = 'f'";
    $sCamposExtra .= "    then cornump.k12_valor";
    $sCamposExtra .= "  else case when";
    $sCamposExtra .= "         corrente.k12_estorn = 't'";
    $sCamposExtra .= "           then cornump.k12_valor*-1";
    $sCamposExtra .= "       end ";
    $sCamposExtra .= "end as valor_arrecadar ";

    $sWhereExtra  = "     corrente.k12_instit = ".db_getsession("DB_instit");
    $sWhereExtra .= " and corrente.k12_data   = '{$oDadoAutenticacao->k12_data}'";
    $sWhereExtra .= " and corrente.k12_autent = $iCodigoAutenticacao";
    $sWhereExtra .= " and corrente.k12_id     = {$oDadoAutenticacao->k12_id}";

    $oDaoCorrente          = db_utils::getDao("corrente");
    $sSqlBuscaReceitaExtra = $oDaoCorrente->sql_query_autenticacao_receita_extra_planilha(null,
                                                                                          null,
                                                                                          null,
                                                                                          $sCamposExtra,
                                                                                          null,
                                                                                          $sWhereExtra);

    $rsBuscaReceitaExtra = db_query($sSqlBuscaReceitaExtra);
    if (!$rsBuscaReceitaExtra) {
      throw new BusinessException("Erro Técnico: Não foi possível localizar as receitas extras-orçamentárias para arrecadar.");
    }

    $iTotalReceitasExtras = pg_num_rows($rsBuscaReceitaExtra);
    if ($iTotalReceitasExtras == 0) {
      return false;
    }

    $iAnoSessao         = db_getsession("DB_anousu");
    $dtDataAutenticacao = $oDadoAutenticacao->k12_data;
    for ($iRowAutenticacao = 0; $iRowAutenticacao < $iTotalReceitasExtras; $iRowAutenticacao++) {

      $oDadoAutenticacao = db_utils::fieldsMemory($rsBuscaReceitaExtra, $iRowAutenticacao);

      /**
       * Decidimos o tipo de documento aqui pois a receita pode ter sido lançada na planilha com valor negativo
       */
      $iCodigoDocumento = 160;
      $lEstorno         = false;
      if ($oDadoAutenticacao->k12_estorn == "t") {

        $lEstorno         = true;
        $iCodigoDocumento = 162;
      }


      $sObservacaoHistorico = "Planilha de Receita Extra-Orçamentária";
      if ($oDadoAutenticacao->k12_histcor != "") {
        $sObservacaoHistorico = $oDadoAutenticacao->k12_histcor;
      }

      $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
      $oContaCorrenteDetalhe->setCredor(CgmFactory::getInstanceByCgm($oDadoAutenticacao->k81_numcgm));
      $oContaCorrenteDetalhe->setRecurso(new Recurso($oDadoAutenticacao->k81_codigo));

      $oLancamentoAuxiliar = new LancamentoAuxiliarArrecadacaoReceitaExtraOrcamentaria();
      $oLancamentoAuxiliar->setObservacaoHistorico($sObservacaoHistorico);
      $oLancamentoAuxiliar->setValorTotal(abs($oDadoAutenticacao->valor_arrecadar));
      $oLancamentoAuxiliar->setHistorico(9500);
      $oLancamentoAuxiliar->setContaCredito($oDadoAutenticacao->k02_reduz);
      $oLancamentoAuxiliar->setContaDebito($oDadoAutenticacao->k12_conta);
      $oLancamentoAuxiliar->setEstorno($lEstorno);
      $oLancamentoAuxiliar->setCaracteristicaPeculiar($oDadoAutenticacao->k81_concarpeculiar);
      $oLancamentoAuxiliar->setAutenticacao($oDadoAutenticacao->k12_id);
      $oLancamentoAuxiliar->setDataAutenticacao($oDadoAutenticacao->k12_data);
      $oLancamentoAuxiliar->setAutenticadora($iCodigoAutenticacao);
      $oLancamentoAuxiliar->setContaCorrenteDetalhe($oContaCorrenteDetalhe);

      $oEventoContabil = new EventoContabil($iCodigoDocumento, $iAnoSessao);
      $oEventoContabil->executaLancamento($oLancamentoAuxiliar, $dtDataAutenticacao);
    }
    return true;
  }


  /**
   * Retorna os dados criados para a autenticacao atual
   * @throws BusinessException
   * @return stdClass - k12_data | k12_id | k12_autent
   */
  public static function getDadosAutenticacao($dtAutenticacao = null) {

    $dtDataBuscar = $dtAutenticacao;
    if (empty($dtAutenticacao)) {
      $dtDataBuscar = date("Y-m-d", db_getsession("DB_datausu"));
    }
    $oDaoCorrenteAutenticacao     = db_utils::getDao("corautent");
    $sCampoAutenticacao           = "max(k12_autent) as k12_autent, ";
    $sCampoAutenticacao          .= "k12_data, ";
    $sCampoAutenticacao          .= "k12_id ";
    $sWhereAutenticacao           = "k12_data = '{$dtDataBuscar}' ";
    $sWhereAutenticacao          .= " and k12_id   = (select k11_id ";
    $sWhereAutenticacao          .= "                   from cfautent where k11_ipterm = '".db_getsession("DB_ip")."'";
    $sWhereAutenticacao          .= "                    and k11_instit = ".db_getsession("DB_instit").")";
    $sWhereAutenticacao          .= "group by k12_data, k12_id ";
    $sSqlBuscaUltimaAutenticacao  = $oDaoCorrenteAutenticacao->sql_query_file(null, null, null, $sCampoAutenticacao, "k12_data", $sWhereAutenticacao);

    $rsBuscaUltimaAutenticacao    = $oDaoCorrenteAutenticacao->sql_record($sSqlBuscaUltimaAutenticacao);
    if ($oDaoCorrenteAutenticacao->erro_status == "0") {
      throw new BusinessException("Não foi possível validar a última autenticação executada.");
    }
    return db_utils::fieldsMemory($rsBuscaUltimaAutenticacao, 0);
  }


  /**
   * Alteramos o tipo de vínculo do SLIP caso as receitas da planilha estejam vinculadas a um SLIP
   * @throws BusinessException
   * @return boolean
   */
  private function verificaReceitasVinculadasEmSlip() {

    $oDaoPlaCaixaRecSlip = db_utils::getDao('placaixarecslip');
    $sWhereSlip          = "placaixa.k80_codpla = {$this->oPlanilha->getCodigo()}";
    $sSqlBuscaSlip       = $oDaoPlaCaixaRecSlip->sql_query_planilha_slip(null, "sliptipooperacaovinculo.*", null, $sWhereSlip);
    $rsBuscaSlip         = $oDaoPlaCaixaRecSlip->sql_record($sSqlBuscaSlip);

    if ($oDaoPlaCaixaRecSlip->numrows > 0) {

      for ($iRowSlip = 0; $iRowSlip < $oDaoPlaCaixaRecSlip->numrows; $iRowSlip++) {

        $oStdDadoSlip   = db_utils::fieldsMemory($rsBuscaSlip, $iRowSlip);
        $oTransferencia = new TransferenciaFactory($oStdDadoSlip->k153_slipoperacaotipo, $oStdDadoSlip->k153_slip);
        $oTransferencia->setTipoOperacao(11);
        $oTransferencia->alteraVinculoSlip();
        unset($oTransferencia);
      }
    }
    return true;
  }

}
