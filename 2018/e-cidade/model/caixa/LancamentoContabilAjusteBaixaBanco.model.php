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
 * @version $Revision: 1.6 $
 */
class LancamentoContabilAjusteBaixaBanco {

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

    if (USE_PCASP) {


      $lReceitaOrcamentaria = $this->executarLancamentoContabeis(false);

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

      if (!$lReceitaOrcamentaria && !$lReceitaExtra) {
        throw new BusinessException("Não encontradas receitas para arrecadar");
      }
    }
    return true;
  }

  /**
   * Executa oslancamentos contabeis necessarios para a baixa de banco
   * @param unknown $iCodigoAutenticacao
   * @param string  $lEstorno
   * @throws BusinessException
   */
  protected function executarLancamentoContabeis($lDesconto=false, $lArrecadaDesconto = false) {

    $oDaoDisrec = db_utils::getDao("disrec");
    $sNomeMetodo = 'sql_query_receitas_autenticadas';
    if ($lDesconto) {
      $sNomeMetodo = 'sql_query_receitas_autenticadas_desconto';
    }

    $sSqlDisrec = $oDaoDisrec->$sNomeMetodo (null,
                                             "distinct corrente.k12_id,
  	                                                   corrente.k12_data,
  	                                                   corrente.k12_autent,
                                                       corrente.k12_conta,
                                                       taborc.k02_codigo,
                                                       k02_codrec,
  	                                                   cornump.k12_valor as total_receita",
                                              null,
                                              "k12_codcla = {$this->iCodigoBaixaBanco}
                                              and orcreceita.o70_anousu = {$this->iAnoUsu}"
                                            );



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



    echo "<pre>";

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
   * Executa os lançamentos contábeis de receitas extras-orçamentárias
   *
   */
  public function processaArrecadacaoReceitaExtraOrcamentaria() {

    $oDaoDisrec = db_utils::getDao("disrec");
    $sSqlBuscaReceita = $oDaoDisrec->sql_query_receita_extra(null,
                                                            "distinct corrente.k12_id,
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

        if ($oDadoAutenticacao->k12_histcor != "") {
          $sObservacaoHistorico = $oDadoAutenticacao->k12_histcor;
        }

        $oLancamentoAuxiliar = new LancamentoAuxiliarArrecadacaoReceitaExtraOrcamentaria();
        $oLancamentoAuxiliar->setObservacaoHistorico($sObservacaoHistorico);
        $oLancamentoAuxiliar->setValorTotal(abs($oDadoAutenticacao->total_receita));
        $oLancamentoAuxiliar->setHistorico(9500);
        $oLancamentoAuxiliar->setContaCredito($oDadoAutenticacao->k02_reduz);
        $oLancamentoAuxiliar->setContaDebito($oDadoAutenticacao->k12_conta);
        $oLancamentoAuxiliar->setCaracteristicaPeculiar("000");

        $oEventoContabil = new EventoContabil(160, $iAnoSessao);
        $oEventoContabil->executaLancamento($oLancamentoAuxiliar, $oDadoAutenticacao->k12_data);
      }

      return true;
    }
  return false;
  }
}
