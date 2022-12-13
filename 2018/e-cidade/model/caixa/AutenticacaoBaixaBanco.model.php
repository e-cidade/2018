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
 * Classe para autenticar Baixas de banco
 * @author Iuri Guntchnigg iuri@dbseller.com.br
 * @package caixa
 * @version $Revision: 1.28 $
 */
class AutenticacaoBaixaBanco {

  /**
   * Planilha de Arrecadacao
   * @var integer
   */
  private $iCodigoBaixaBanco;

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
   * Ano da autenticacao
   * @var integer
   */
  private $iAnoUsu;

  /**
   *
   * @param integer $iCodigoClassificacao Codigo da Classificacao
   * @throws ParameterException
   */
  public function __construct ($iCodigoClassificacao) {

    $this->iCodigoBaixaBanco = $iCodigoClassificacao;
    $this->dtAutenticacao    = date("Y-m-d", db_getsession("DB_datausu"));
    $this->iCodigoUsuario    = db_getsession("DB_id_usuario");
    $this->sIpTerminal       = db_getsession("DB_ip");
    $this->iAnoUsu           = db_getsession("DB_anousu");
  }

  /**
   * Autentica as receitas inclusas em uma planilha
   * @throws BusinessException
   */
  public function autenticar() {

    if (!db_utils::inTransaction()) {
      throw new BusinessException("Sem transação ativa com o banco de dados.");
    }

    $iInstituicao      = db_getsession("DB_instit");
    $dtAutenticacao    = $this->dtAutenticacao;
    $sSqlAutenticacao  = " select fc_autenclass({$this->iCodigoBaixaBanco}, '{$dtAutenticacao}', ";
    $sSqlAutenticacao .= "                     '{$dtAutenticacao}', {$this->iAnoUsu},";
    $sSqlAutenticacao .= "                     '{$this->sIpTerminal}', {$iInstituicao}) as fc_autenticabaixa";

    $rsAutenticacao = db_query($sSqlAutenticacao);
    if (!$rsAutenticacao) {
    	$sMsgBanco = substr(pg_last_error(), 0, strpos(pg_last_error(),"CONTEXT") );
      throw new BusinessException("Não foi possível autenticar a baixa de banco. ".$sMsgBanco );
    }

    $sRetornoAutenticacao = db_utils::fieldsMemory($rsAutenticacao, 0)->fc_autenticabaixa;
    if (substr($sRetornoAutenticacao, 0, 1) != '1') {

      $sMsgErro  = "Erro ao Autenticar.\n";
      $sMsgErro .= $sRetornoAutenticacao;
      throw new BusinessException($sMsgErro);
    }

    if (USE_PCASP) {

      $lReceitaOrcamentaria = $this->executarLancamentoContabeis(false);
      $lPrestacaoContas     = $this->processaLancamentoPrestacaoContas();
      if ($lReceitaOrcamentaria) {

        /**
         * Arrecada a receita no valor do desconto,
         */
        $this->executarLancamentoContabeis(true, true);

        /**
         * lancamento do desconto
         */
        $this->executarLancamentoContabeis(true);
      }

      $lReceitaExtra = $this->processaArrecadacaoReceitaExtraOrcamentaria();
      $lReceitaExtraPrestacaoConta = $this->processaArrecadacaoReceitaExtraOrcamentariaPrestacaoContas();

      if (!$lReceitaOrcamentaria && !$lReceitaExtra && !$lPrestacaoContas && !$lReceitaExtraPrestacaoConta) {
        throw new BusinessException("Não foram localizadas receitas para arrecadação.");
      }
    }
    return $sRetornoAutenticacao;
  }

  /**
   * Executa oslancamentos contabeis necessarios para a baixa de banco
   * @param unknown $iCodigoAutenticacao
   * @param string  $lEstorno
   * @throws BusinessException
   */
  protected function executarLancamentoContabeis($lDesconto=false, $lArrecadaDesconto = false) {

    $aWhereReceitas   = array();
    $aWhereReceitas[] = "k12_codcla = {$this->iCodigoBaixaBanco}";
    $aWhereReceitas[] = "orcreceita.o70_anousu = {$this->iAnoUsu}";

    $oDaoDisrec  = db_utils::getDao("disrec");
    $sNomeMetodo = 'sql_query_receitas_autenticadas_desconto';
    if ( ! $lDesconto) {
      $sNomeMetodo  = 'sql_query_receitas_autenticadas';
    }

    $sWhereReceitas = implode(" and ", $aWhereReceitas);

    $sSqlDisrec = $oDaoDisrec->$sNomeMetodo (null,
                                             " distinct taborc.k02_codrec,
                                                        taborc.k02_codigo,
    		                                                corrente.k12_id,
  	                                                    corrente.k12_data,
  	                                                    corrente.k12_autent,
                                                        corrente.k12_conta,
                                                        k02_codrec,
  	                                                    cornump.k12_valor as total_receita",
                                              null,
                                              "{$sWhereReceitas}");

    $rsReceitasBaixaBanco = db_query($sSqlDisrec);

    if (!$rsReceitasBaixaBanco) {
      throw new BusinessException("Não foi possível localizar as receitas a serem arrecadadas.");
    }

    $iReceitas = pg_num_rows($rsReceitasBaixaBanco);

    /**
     * caso não encontre nenhuma receita, retorna false
     * para que seja realizado posterior tratamento,  no caso de não haver receitas para arrecadar
    **/
    if ($iReceitas <= 0) {
      return false;
    }


    for ($rec = 0; $rec < $iReceitas; $rec ++) {

      $oDadoSqlGeral = db_utils::fieldsMemory($rsReceitasBaixaBanco, $rec);
      $oReceitaContabil = new ReceitaContabil($oDadoSqlGeral->k02_codrec);
      if ($lArrecadaDesconto) {

        $oReceitaDeducao              = new ReceitaContabil($oDadoSqlGeral->k02_codrec,$this->iAnoUsu);
        $sEstruturalContaDeducao      = substr($oReceitaDeducao->getContaOrcamento()->getEstrutural(), 1, 14);
        $sEstruturalContaArrecadacao  = "4{$sEstruturalContaDeducao}";
        $oContaPlano                  = ContaOrcamento::getContaPorEstrutural($sEstruturalContaArrecadacao, $this->iAnoUsu);
        $oReceitaContabil             = $oContaPlano->getReceitaContabil();
        $lDesconto                    = false;
      }
      $oReceitaContabil->processaLancamentosReceita(abs($oDadoSqlGeral->total_receita),
                                                    $oDadoSqlGeral->k12_id,
                                                    $oDadoSqlGeral->k12_data,
                                                    $oDadoSqlGeral->k12_autent,
                                                    false,
                                                    $oDadoSqlGeral->k12_conta,
                                                    null,
                                                    null,
                                                    null,
                                                    null,
                                                    $lDesconto
                                                    );
    }
    return true;
  }


  /**
   * Executa o lançamento das receitas originadas de empenhos de prestação de contas.
   */
  private function processaLancamentoPrestacaoContas() {

    $sCampos  = "  k02_codrec,";
    $sCampos .= "  corrente.k12_id";
    $sCampos .= ", corrente.k12_data";
    $sCampos .= ", corrente.k12_autent";
    $sCampos .= ", corrente.k12_conta";
    $sCampos .= ", empprestarecibo.e170_numpre";
    $sCampos .= ", empprestarecibo.e170_numpar";
    $sCampos .= ", vlrrec";

    $sWhere   = "    k12_codcla = {$this->iCodigoBaixaBanco}  ";
    $sWhere  .= "and orcreceita.o70_anousu = {$this->iAnoUsu} ";

    $oDaoDisrec = db_utils::getDao("disrec");
    $sSqlDisrec = $oDaoDisrec->sql_query_prestacao_conta(null, $sCampos, null, $sWhere);
    $rsReceitasBaixaBanco = db_query($sSqlDisrec);

    if (!$rsReceitasBaixaBanco) {
      throw new BusinessException("Não foi possível localizar as receitas a serem arrecadadas.");
    }

    $iTotalReceitas = pg_num_rows($rsReceitasBaixaBanco);

    /**
     * caso não encontre nenhuma receita, retorna false
     * para que seja realizado posterior tratamento,  no caso de não haver receitas para arrecadar
    **/
    if ($iTotalReceitas <= 0) {
      return false;
    }

    for ($iRowReceita = 0; $iRowReceita < $iTotalReceitas; $iRowReceita ++) {

      $oDadoSqlGeral    = db_utils::fieldsMemory($rsReceitasBaixaBanco, $iRowReceita);
      $oReceitaContabil = new ReceitaContabil($oDadoSqlGeral->k02_codrec);
      $oReceitaContabil->processaLancamentosReceita(abs($oDadoSqlGeral->vlrrec),
                                                    $oDadoSqlGeral->k12_id,
                                                    $oDadoSqlGeral->k12_data,
                                                    $oDadoSqlGeral->k12_autent,
                                                    false,
                                                    $oDadoSqlGeral->k12_conta,
                                                    null,
                                                    null,
                                                    $oDadoSqlGeral->e170_numpre,
                                                    $oDadoSqlGeral->e170_numpar);
    }
    return true;
  }


  /**
   * Executa os lançamentos contábeis de receitas extras-orçamentárias
   *
   */
  public function processaArrecadacaoReceitaExtraOrcamentaria() {

    $oDaoDisrec = db_utils::getDao("disrec");
    $sSqlBuscaReceita = $oDaoDisrec->sql_query_receita_extra(null,
                                                            " distinct corrente.k12_id,
                                                      	               corrente.k12_data,
                                                      	               corrente.k12_autent,
                                                                       corrente.k12_conta,
                                                                       tabrec.k02_codigo,
                                                                       tabplan.k02_reduz,
                                                      	               cornump.k12_valor as total_receita",
                                                            null,
                                                            "    k12_codcla = {$this->iCodigoBaixaBanco}
                                                            and tabplan.k02_anousu = {$this->iAnoUsu}");
    $rsBuscaReceita = db_query($sSqlBuscaReceita);

    if (!$rsBuscaReceita) {
      throw new BusinessException("Ocorreu um erro ao buscar as receitas extras orçamentárias na baixa de banco.");
    }

    $iTotalReceitaExtra = pg_num_rows($rsBuscaReceita);

    if ($iTotalReceitaExtra > 0) {

      $iAnoSessao = db_getsession('DB_anousu');

      for ($iRowExtra = 0; $iRowExtra < $iTotalReceitaExtra; $iRowExtra++) {

        $oDadoAutenticacao    = db_utils::fieldsMemory($rsBuscaReceita, $iRowExtra);
        $sObservacaoHistorico = "Arrecadação de receita extra-orçamentária via baixa de banco.";

        $oLancamentoAuxiliar = new LancamentoAuxiliarArrecadacaoReceitaExtraOrcamentaria();
        $oLancamentoAuxiliar->setObservacaoHistorico($sObservacaoHistorico);
        $oLancamentoAuxiliar->setValorTotal(abs($oDadoAutenticacao->total_receita));
        $oLancamentoAuxiliar->setHistorico(9500);
        $oLancamentoAuxiliar->setContaCredito($oDadoAutenticacao->k02_reduz);
        $oLancamentoAuxiliar->setContaDebito($oDadoAutenticacao->k12_conta);
        $oLancamentoAuxiliar->setCaracteristicaPeculiar("000");

        $oLancamentoAuxiliar->setAutenticacao($oDadoAutenticacao->k12_id);
        $oLancamentoAuxiliar->setDataAutenticacao($oDadoAutenticacao->k12_data);
        $oLancamentoAuxiliar->setAutenticadora($oDadoAutenticacao->k12_autent);

        $oEventoContabil = new EventoContabil(160, $iAnoSessao);
        $oEventoContabil->executaLancamento($oLancamentoAuxiliar, $oDadoAutenticacao->k12_data);
      }
      return true;
    }
    return false;
  }

  public function processaArrecadacaoReceitaExtraOrcamentariaPrestacaoContas() {


    $oDaoDisrec = db_utils::getDao("disrec");
    $sSqlBuscaReceita = $oDaoDisrec->sql_query_receita_extra_prestacao_conta(null,
                                                            "corrente.k12_id,
                                                            corrente.k12_data,
                                                            corrente.k12_autent,
                                                            corrente.k12_conta,
                                                            tabrec.k02_codigo,
                                                            tabplan.k02_reduz,
                                                            vlrrec,
                                                            e45_numemp",
                                                            null,
                                                            "    k12_codcla = {$this->iCodigoBaixaBanco}
                                                            and tabplan.k02_anousu = {$this->iAnoUsu}");
    $rsBuscaReceita = db_query($sSqlBuscaReceita);

    if (!$rsBuscaReceita) {
      throw new BusinessException("Ocorreu um erro ao buscar as receitas extras orçamentárias na baixa de banco.");
    }

    $iTotalReceitaExtra = pg_num_rows($rsBuscaReceita);

    if ($iTotalReceitaExtra <= 0) {
      return false;
    }

     $iAnoSessao = db_getsession('DB_anousu');

     for ($iRowExtra = 0; $iRowExtra < $iTotalReceitaExtra; $iRowExtra++) {

       $oDadoAutenticacao    = db_utils::fieldsMemory($rsBuscaReceita, $iRowExtra);
       $sObservacaoHistorico = "Arrecadação de receita extra-orçamentária via baixa de banco.";

       $oLancamentoAuxiliar = new LancamentoAuxiliarArrecadacaoReceitaExtraOrcamentaria();
       $oLancamentoAuxiliar->setObservacaoHistorico($sObservacaoHistorico);
       $oLancamentoAuxiliar->setValorTotal(abs($oDadoAutenticacao->vlrrec));
       $oLancamentoAuxiliar->setContaCredito($oDadoAutenticacao->k02_reduz);
       $oLancamentoAuxiliar->setContaDebito($oDadoAutenticacao->k12_conta);
       $oLancamentoAuxiliar->setCaracteristicaPeculiar("000");
       $oLancamentoAuxiliar->setNumeroEmpenho($oDadoAutenticacao->e45_numemp);

       $oLancamentoAuxiliar->setAutenticacao($oDadoAutenticacao->k12_id);
       $oLancamentoAuxiliar->setDataAutenticacao($oDadoAutenticacao->k12_data);
       $oLancamentoAuxiliar->setAutenticadora($oDadoAutenticacao->k12_autent);

       $oEventoContabil = new EventoContabil(416, $iAnoSessao);
       $oEventoContabil->executaLancamento($oLancamentoAuxiliar, $oDadoAutenticacao->k12_data);
     }
     return true;
  }
}
