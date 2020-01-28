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

// @todo refatorar todo a classe, separar os objetos
class AutenticacaoArrecadacao extends Autenticacao {

  private $iNumpre;
  private $iNumpar;
  private $iContaDebito;
  private $sCodigoAutenticacao;
  private $dtSessao;
  private $iIpTerminal;
  private $iAnoUsu;
  private $iCodigoInstituicao;
  private $iCodigoGrupoArrecadacao;
  private $iCodigoRecurso;
  private $sCaracteristicaPeculiar = null;
  private $iSequencialEmpenho = null;

  public function __construct($iNumpre = null, $iNumpar = null, $iContaDebito=null,
                             $iCodigoGrupoArrecadacao = null, $dDataAutenticacao = null,
                             $sCaracteristicaPeculiar = null, $iSequencialEmpenho = null) {

    if (empty($iNumpre)) {
      throw new ParameterException("Numpre não informado !");
    }
    if (empty($iContaDebito)) {
      throw new ParameterException("Conta débito não informado !");
    }

    $this->iNumpre                 = $iNumpre;
    $this->iNumpar                 = $iNumpar;
    $this->iContaDebito            = $iContaDebito;
    $this->iCodigoGrupoArrecadacao = $iCodigoGrupoArrecadacao;
    $this->sCaracteristicaPeculiar = $sCaracteristicaPeculiar;
    $this->iSequencialEmpenho      = $iSequencialEmpenho;

    if (!empty($dDataAutenticacao)) {
      $this->dtSessao = $dDataAutenticacao;
    } else {
      $this->dtSessao = date("Y-m-d", db_getsession("DB_datausu"));
    }
    $this->iIpTerminal        = db_getsession("DB_ip");
    $this->iAnoUsu            = db_getsession("DB_anousu");
    $this->iCodigoInstituicao = db_getsession("DB_instit");

  }

  /**
   * Método que autentica uma arrecadação de receita
   * @throws BusinessException
   * @return void
   */
  public function autenticar() {

    $iCodigoGrupoArrecadacao = 0;
    if (isset($this->iCodigoGrupoArrecadacao) && $this->iCodigoGrupoArrecadacao != null) {
      $iCodigoGrupoArrecadacao = $this->iCodigoGrupoArrecadacao;
    }

    $sSqlAutenticacao = "select *
                           from fc_autentica({$this->iNumpre},
                                             {$this->iNumpar},
                                             '{$this->dtSessao}',
                                             '{$this->dtSessao}',
                                              {$this->iAnoUsu},
                                              {$this->iContaDebito},
                                             '{$this->iIpTerminal}',
                                              {$this->iCodigoInstituicao},
                                              {$iCodigoGrupoArrecadacao}) as fc_autentica";

    $rsAutenticacao = db_query($sSqlAutenticacao);
    if (!$rsAutenticacao ) {
      throw new BusinessException("Erro ao autenticar receita : ".pg_last_error());
    }

    $oDadosAutenticacao = db_utils::fieldsMemory($rsAutenticacao, 0);
    if ($oDadosAutenticacao->erro == 't') {
      throw new BusinessException("Erro ao Autenticar: ".$oDadosAutenticacao->mensagem." - ".pg_last_error());
    }
    $this->sCodigoAutenticacao = $oDadosAutenticacao->autenticacao;

    $lReceitaContabil = $this->efetuarLancamentos($oDadosAutenticacao->data,
                                                  $oDadosAutenticacao->id,
                                                  $oDadosAutenticacao->codautent,
                                                  $this->iContaDebito,
                                                  false);

    if ($lReceitaContabil) {

      $this->efetuarLancamentos($oDadosAutenticacao->data,
                                $oDadosAutenticacao->id,
                                $oDadosAutenticacao->codautent,
                                $this->iContaDebito,
                                false, true, true);

      $this->efetuarLancamentos($oDadosAutenticacao->data,
                                $oDadosAutenticacao->id,
                                $oDadosAutenticacao->codautent,
                                $this->iContaDebito,
                                false, true);
    }

    $lReceitaExtra = $this->efetuarLancamentosReceitaExtra(false,
                                          $oDadosAutenticacao->data,
                                          $oDadosAutenticacao->codautent,
                                          $oDadosAutenticacao->id);

    if (!$lReceitaContabil && !$lReceitaExtra) {
      throw new BusinessException("Não encontradas receitas para arrecadação");
    }
  }


  /**
   * Método responsável pelo estorno de uma autenticação de receita
   * @throws BusinessException
   * @return void
   */
  public function estornar() {

    $iCodigoGrupoArrecadacao = 0;
    if (isset($this->iCodigoGrupoArrecadacao) && $this->iCodigoGrupoArrecadacao != null) {
      $iCodigoGrupoArrecadacao = $this->iCodigoGrupoArrecadacao;
    }

    $sSqlAutenticacao = "select *
                           from fc_autenesto({$this->iNumpre},
                                             {$this->iNumpar},
                                            '{$this->dtSessao}',
                                            '{$this->dtSessao}',
                                             {$this->iAnoUsu},
                                             {$this->iContaDebito},
                                            '{$this->iIpTerminal}',
                                             {$this->iCodigoInstituicao},
                                             {$iCodigoGrupoArrecadacao}) as fc_autenesto";

    $rsAutenticacao = db_query($sSqlAutenticacao);
    if (!$rsAutenticacao ) {
      throw new BusinessException("Erro ao estornar : ".pg_last_error());
    }

    $oDadosAutenticacao = db_utils::fieldsMemory($rsAutenticacao, 0);
    if ($oDadosAutenticacao->erro == 't') {
      throw new BusinessException("Erro ao estornar: ".$oDadosAutenticacao->mensagem." - ".pg_last_error());
    }

    $this->sCodigoAutenticacao = $oDadosAutenticacao->autenticacao;

    /**
     * Lancamento de receita normal
     */
    $lReceitaContabil = $this->efetuarLancamentos($oDadosAutenticacao->data,
                              $oDadosAutenticacao->id,
                              $oDadosAutenticacao->codautent,
                              $this->iContaDebito,
                              true);
    /**
     * Lancamento de desconto
     */

    if ($lReceitaContabil) {

      $this->efetuarLancamentos($oDadosAutenticacao->data,
                                $oDadosAutenticacao->id,
                                $oDadosAutenticacao->codautent,
                                $this->iContaDebito,
                                true, true, true);

      $this->efetuarLancamentos($oDadosAutenticacao->data,
                              $oDadosAutenticacao->id,
                              $oDadosAutenticacao->codautent,
                              $this->iContaDebito,
                              true, true);
    }

    $lReceitaExtra = $this->efetuarLancamentosReceitaExtra(true,
                                          $oDadosAutenticacao->data,
                                          $oDadosAutenticacao->codautent,
                                          $oDadosAutenticacao->id);

    if (!$lReceitaContabil && !$lReceitaExtra) {
      throw new BusinessException("Não encontradas receitas para arrecadação");
    }
  }

  /**
   * Executa os lançamentos contabeis de uma arrecadação de receita
   * @param string $dtAutenticacao
   * @param string $iId
   * @param string $iAutent
   * @param integer $iCodigoContaDebito
   * @param boolean $lEstorno
   * @throws BusinessException
   */
  public function efetuarLancamentos($dtAutenticacao="", $iId="", $iAutent="",
                                     $iCodigoContaDebito, $lEstorno, $lDesconto=false, $lArrecadaDesconto = false) {

    if (USE_PCASP) {

      $clorcreceita         = db_utils::getDao('orcreceita');
      $clconlancam          = db_utils::getDao('conlancam');
      $clconplanoreduz      = db_utils::getDao('conplanoreduz');
      $oDaoCorgrupoCorrente = db_utils::getDao('corgrupocorrente');

      list($c70_data_ano, $c70_data_mes, $c70_data_dia) = explode("-", $dtAutenticacao);

      $aContasSemVinculo = self::getReceitasSemVinculoPcasp($iId, $dtAutenticacao, $iAutent);
      if (count($aContasSemVinculo) > 0 ) {

        $sContasSemVinculo = implode("\\n", $aContasSemVinculo);
        throw new BusinessException("Encontrado contas de receita sem vínculo com PCASP.\\n\\n{$sContasSemVinculo}");
      }

      $sql                          = self::getSqlAutenticacoes($iId, $dtAutenticacao, $iAutent, $lEstorno, $lDesconto);
      $resultorcamentaria           = db_query($sql);
      $iTotalLinhasReceitaOrcamento = pg_num_rows($resultorcamentaria);

      if ($iTotalLinhasReceitaOrcamento == 0) {

        return false;
      }


      for ($rec = 0; $rec < $iTotalLinhasReceitaOrcamento; $rec ++) {

        $oDadoSqlGeral = db_utils::fieldsMemory($resultorcamentaria, $rec);

        if (!empty ($oDadoSqlGeral->cgm_estornado) || !empty ($oDadoSqlGeral->cgm_pago)) {
          $iCodigoCgm = $oDadoSqlGeral->cgm_pago!=""?$oDadoSqlGeral->cgm_pago:$oDadoSqlGeral->cgm_estornado;
        }

        if (empty ($iCodigoCgm) && !empty ($oDadoSqlGeral->cgm_recibo_avulso)) {
          $iCodigoCgm = $oDadoSqlGeral->cgm_recibo_avulso;
        }

        $oDadoSqlGeral->arrecada = abs($oDadoSqlGeral->arrecada);
        if ($lEstorno) {
          $oDadoSqlGeral->arrecada = abs($oDadoSqlGeral->estorna);
        }

        $oReceitaContabil = ReceitaContabilRepository::getReceitaByCodigo($oDadoSqlGeral->k02_codrec, $this->iAnoUsu);
        if ($lArrecadaDesconto) {

          $oReceitaDeducao             = ReceitaContabilRepository::getReceitaByCodigo($oDadoSqlGeral->k02_codrec,
                                                                                       $this->iAnoUsu);
          $sEstruturalContaDeducao     = substr($oReceitaDeducao->getContaOrcamento()->getEstrutural(), 1, 14);
          $sEstruturalContaArrecadacao = "4{$sEstruturalContaDeducao}";
          $oContaPlano                 = ContaOrcamento::getContaPorEstrutural($sEstruturalContaArrecadacao,
                                                                               $this->iAnoUsu
                                                                              );
          $oReceitaContabil            = $oContaPlano->getReceitaContabil();
          $lDesconto                   = false;
        }

        // @todo - revisar questao da receita (codrec) para mais de uma instituicao
        // @todo - arrumar nome para este metodo
        $oReceitaContabil->processaLancamentosReceita($oDadoSqlGeral->arrecada,
                                                      $iId,
                                                      $dtAutenticacao,
                                                      $iAutent, $lEstorno, $this->iContaDebito,
                                                      $oDadoSqlGeral->k12_histcor,
                                                      null,
                                                      $oDadoSqlGeral->k12_numpre,
                                                      $oDadoSqlGeral->k12_numpar,
                                                      $lDesconto,
                                                      $this->getCodigoRecurso(),
                                                      $this->sCaracteristicaPeculiar,
                                                      $iCodigoCgm
                                                     );

      } // final for
    }
    return true;
  }

  /**
   * Retorna a string autenticada pelas funções de autenticação
   * @return string
   */
  public function getCodigoAutenticacao() {
    return $this->sCodigoAutenticacao;
  }


  public static function getSqlAutenticacoes($iId = null, $dtAutenticacao = null, $iAutent = null,
                                             $lEstorno = false, $lDesconto = false) {

    $sMetodoUtilizar = "sql_query_arrecadacao_receita";
    if ($lDesconto) {
      $sMetodoUtilizar = "sql_query_arrecadacao_desconto";
    }
    $oDaoCorrente     = db_utils::getDao('corrente');
    $sSqlAutenticacao = $oDaoCorrente->$sMetodoUtilizar($iId,
                                                        $dtAutenticacao,
                                                        $iAutent,
                                                        "xxx.*, orcreceita.o70_codigo"
                                                       );
    return $sSqlAutenticacao;
  }

  public static function getReceitasSemVinculoPcasp($iId="", $sData="", $iAutent="") {

    $oDaoCorNump     = db_utils::getDao('cornump');
    $sCamposCorNump  = "distinct c60_codcon, ";
    $sCamposCorNump .= "         c60_estrut, ";
    $sCamposCorNump .= "         c60_descr   ";

    $sWhereCorNump  = "    cornump.k12_id     = {$iId}     ";
    $sWhereCorNump .= "and cornump.k12_data   = '{$sData}' ";
    $sWhereCorNump .= "and cornump.k12_autent = {$iAutent} ";
    $sWhereCorNump .= "and conplanoconplanoorcamento.c72_conplanoorcamento is null ";
    $sWhereCorNump .= "and taborc.k02_anousu   = ".db_getsession('DB_anousu');

    $sSqlBuscaReceitas     = $oDaoCorNump->sql_query_plano_conta($iId,
                                                                 $sData,
                                                                 $iAutent,
                                                                 null,
                                                                 null,
                                                                 null,
                                                                 $sCamposCorNump,
                                                                 null,
                                                                 $sWhereCorNump
                                                                );
    $rsVerificaContasPcasp = $oDaoCorNump->sql_record($sSqlBuscaReceitas);
    $iLinhasContaPcasp     = $oDaoCorNump->numrows;

    $aContasSemVinculo = array();
    if ($iLinhasContaPcasp > 0) {

      for ($iRowContaPcasp = 0; $iRowContaPcasp < $iLinhasContaPcasp; $iRowContaPcasp++) {

        $oDadoConta          = db_utils::fieldsMemory($rsVerificaContasPcasp, $iRowContaPcasp);
        $aContasSemVinculo[] = "{$oDadoConta->c60_codcon} - {$oDadoConta->c60_estrut} - {$oDadoConta->c60_descr}";
      }
    }
    return $aContasSemVinculo;
  }


  /**
   * Seta o código do recurso
   * @param integer $iCodigoRecurso
   */
  public function setCodigoRecurso($iCodigoRecurso) {
    $this->iCodigoRecurso = $iCodigoRecurso;
  }

  /**
   * Retorna o código do recurso
   * @return integer
   */
  public function getCodigoRecurso() {
    return $this->iCodigoRecurso;
  }

  /**
   * Executa os lançamentos de receita extra-orçamentária
   * @param string $lEstorno
   * @throws BusinessException
   * @return boolean
   */
  public function efetuarLancamentosReceitaExtra($lEstorno = false, $dtAutenticacao, $iAutenticacao, $iId) {

    /**
     * Em caso do cliente não utilizar PCASP retornamos true
     */
    if (!USE_PCASP) {
      return true;
    }


    $iCodigoDocumento = 160;
    $sNegacao         = "not";
    if ($lEstorno) {

      $iCodigoDocumento = 162;
      $sNegacao         = "";
    }

    $sCamposExtra  = " k12_conta,";
    $sCamposExtra .= " tabrec.k02_codigo,";
    $sCamposExtra .= " k02_reduz,";
    $sCamposExtra .= " case when k130_concarpeculiar is null then '000' else k130_concarpeculiar end k130_concarpeculiar, ";
    $sCamposExtra .= " k12_histcor,";
    $sCamposExtra .= " k00_numcgm as cgm,";
    $sCamposExtra .= " c61_codigo,";
    $sCamposExtra .= " corrente.k12_id,";
    $sCamposExtra .= " corrente.k12_autent,";
    $sCamposExtra .= " empprestarecibo.e170_numpre,";
    $sCamposExtra .= " empprestarecibo.e170_numpar,";
    $sCamposExtra .= " emppresta.e45_numemp,";

    $sCamposExtra .= " case when";
    $sCamposExtra .= "  corrente.k12_estorn = 'f'";
    $sCamposExtra .= "    then cornump.k12_valor";
    $sCamposExtra .= "  else case when";
    $sCamposExtra .= "         corrente.k12_estorn = 't'";
    $sCamposExtra .= "           then cornump.k12_valor*-1";
    $sCamposExtra .= "       end ";
    $sCamposExtra .= " end as valor_arrecadar ";

    $sWhereExtra  = "     corrente.k12_instit = ".db_getsession("DB_instit");
    $sWhereExtra .= " and corrente.k12_data   = '{$dtAutenticacao}'";
    $sWhereExtra .= " and corrente.k12_autent = {$iAutenticacao}";
    $sWhereExtra .= " and corrente.k12_id     = {$iId}";
//    $sWhereExtra .= " and corhist.k12_id is {$sNegacao} null";

    $oDaoCorrente          = new cl_corrente;
    $sSqlBuscaReceitaExtra = $oDaoCorrente->sql_query_autenticacao_receita_extra(null,
                                                                                 null,
                                                                                 null,
                                                                                 $sCamposExtra,
                                                                                 null,
                                                                                 $sWhereExtra
                                                                                );

    $rsBuscaReceitaExtra = db_query($sSqlBuscaReceitaExtra);
    if (!$rsBuscaReceitaExtra) {

      $sErroMensagem = "Erro Técnico: Não foi possível localizar as receitas extras-orçamentárias para arrecadar.";
      throw new BusinessException($sErroMensagem);
    }

    $iTotalReceitasExtras = pg_num_rows($rsBuscaReceitaExtra);
    if ($iTotalReceitasExtras == 0) {
      return false;
    }

    $iAnoSessao = db_getsession("DB_anousu");
    for ($iRowAutenticacao = 0; $iRowAutenticacao < $iTotalReceitasExtras; $iRowAutenticacao++) {


      $oDadoAutenticacao    = db_utils::fieldsMemory($rsBuscaReceitaExtra, $iRowAutenticacao);
      $sObservacaoHistorico = "Arrecadação de Receita Extra-Orçamentária";
      if ($oDadoAutenticacao->k12_histcor != "") {
        $sObservacaoHistorico = $oDadoAutenticacao->k12_histcor;
      }

      $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
      if (!empty($this->iSequencialEmpenho)) {

        $oEmpenhoFinanceiro = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorNumero($this->iSequencialEmpenho);
        $oContaCorrenteDetalhe->setCredor($oEmpenhoFinanceiro->getFornecedor());
        $oContaCorrenteDetalhe->setEmpenho($oEmpenhoFinanceiro);
        $oContaCorrenteDetalhe->setRecurso($oEmpenhoFinanceiro->getDotacao()->getDadosRecurso());
      } else if (!empty($oDadoAutenticacao->cgm)) {

        $oContaCorrenteDetalhe->setCredor(CgmFactory::getInstanceByCgm($oDadoAutenticacao->cgm));
        $oContaCorrenteDetalhe->setRecurso(new Recurso($oDadoAutenticacao->c61_codigo));

      }

      $oLancamentoAuxiliar = new LancamentoAuxiliarArrecadacaoReceitaExtraOrcamentaria();
      $oLancamentoAuxiliar->setObservacaoHistorico($sObservacaoHistorico);
      $oLancamentoAuxiliar->setValorTotal(abs($oDadoAutenticacao->valor_arrecadar));
      $oLancamentoAuxiliar->setHistorico(9500);
      $oLancamentoAuxiliar->setContaCredito($oDadoAutenticacao->k02_reduz);
      $oLancamentoAuxiliar->setContaDebito($oDadoAutenticacao->k12_conta);
      $oLancamentoAuxiliar->setEstorno($lEstorno);
      $oLancamentoAuxiliar->setCaracteristicaPeculiar($oDadoAutenticacao->k130_concarpeculiar);
      $oLancamentoAuxiliar->setAutenticacao($iId);
      $oLancamentoAuxiliar->setDataAutenticacao($dtAutenticacao);
      $oLancamentoAuxiliar->setAutenticadora($iAutenticacao);
      $oLancamentoAuxiliar->setContaCorrenteDetalhe($oContaCorrenteDetalhe);

      if (!empty($oDadoAutenticacao->e170_numpre) && !empty($oDadoAutenticacao->e170_numpar)) {

        $oLancamentoAuxiliar->setNumeroEmpenho($oDadoAutenticacao->e45_numemp);
        $iCodigoDocumento = 416;
        if ($lEstorno) {
          $iCodigoDocumento = 417;
        }
      }

      $oEventoContabil = EventoContabilRepository::getEventoContabilByCodigo($iCodigoDocumento, $iAnoSessao);
      $oEventoContabil->executaLancamento($oLancamentoAuxiliar, $dtAutenticacao);

    }
    return true;
  }
}

