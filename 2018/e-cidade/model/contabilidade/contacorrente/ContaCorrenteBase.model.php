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
 * model base para contacorrente
 * @author  rafael.lopes rafael.lopes@dbseller.com.br
 * @name    ContaCorrenteBase
 * @package contabilidade
 */
abstract class ContaCorrenteBase {

  /**
   * objeto instituição
   * @var object
   */
  protected $oInstituicao;

  /**
   * objeto plano de contas
   * @var object
   */
  protected $oContaPlano;

  /**
   * data de Lancamento
   * @var string
   */
  protected $dDataLancamento;

  /**
   * Código do lançamento (conlancamval)
   * @var integer
   */
  protected $iCodigoLancamento;

  /**
   * Código reduzido da conta no plano de contas PCASP
   * @var integer
   */
  protected $iCodigoReduzido;

  /**
   * Lançamento auxiliar do lançamento contábil
   * @var ILancamentoAuxiliar - Objeto que implemente a interface de Lançamento Auxiliar
   */
  protected $oLancamentoAuxiliar;

  /**
   * Constante para operação de Crédito
   */
  const OPERACAO_CREDITO = "C";

  /**
   * Constante para operação de Débito
   */
  const OPERACAO_DEBITO = "D";

  /**
   * Tipo de Lançamento
   * C - Crédito
   * D - Débito
   * @var string
   */
  protected $sTipoLancamento;

  /**
   * Valor do lançamento
   * @var float
   */
  protected $nValorLancamento;

  /**
   * Data que o lançamento foi realizado
   * @var string
   */
  protected $dtLancamento;

  /**
   * Conta Corrente
   * @var ContaCorrente
   */
  protected $oContaCorrente;

  /**
   * @var ContaCorrenteDetalhe
   */
  protected $oContaCorrenteDetalhe;

  /**
   * @var DocumentoEventoContabil
   */
  protected $oDocumentoEventoContabil;

  /**
   * Seta as propriedades padrão para a execução do conta corrente
   * @param integer $iCodigoLancamento
   * @param integer $iCodigoReduzido
   * @param ILancamentoAuxiliar|LancamentoAuxiliarArrecadacaoReceita|LancamentoAuxiliarArrecadacaoReceitaExtraOrcamentaria|LancamentoAuxiliarContaCorrente|LancamentoAuxiliarEmpenho $oLancamentoAuxiliar
   * @throws BusinessException
   */
  public function __construct($iCodigoLancamento, $iCodigoReduzido, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    $this->iCodigoLancamento     = $iCodigoLancamento;
    $this->iCodigoReduzido       = $iCodigoReduzido;
    $this->oLancamentoAuxiliar   = $oLancamentoAuxiliar;
    $this->oContaCorrenteDetalhe = $oLancamentoAuxiliar->getContaCorrenteDetalhe();

    if ($this->oContaCorrenteDetalhe) {
      $this->oContaCorrenteDetalhe = clone $this->oContaCorrenteDetalhe;
    }

    $iInstituicaoSessao        = db_getsession('DB_instit');
    $iAnoSessao                = db_getsession('DB_anousu');
    $this->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo($iInstituicaoSessao));
    $this->setContaPlano(ContaPlanoPCASPRepository::getContaByCodigo(null,
                                                                     $iAnoSessao,
                                                                     $iCodigoReduzido,
                                                                     $iInstituicaoSessao
                                                                    )
                        );

    /**
     * Buscamos os valores da tabela conlancamval para sabermos se é um lançamento a crédito ou a débito
     */
    $oDaoConLancamVal    = db_utils::getDao("conlancamval");
    $sCamposBusca        = "c69_sequen, c69_credito, c69_debito, c69_data, c69_valor";
    $sSqlBuscaLancamento = $oDaoConLancamVal->sql_query_file($this->iCodigoLancamento, $sCamposBusca, null, null);
    $rsBuscaLancamento   = $oDaoConLancamVal->sql_record($sSqlBuscaLancamento);
    if ($oDaoConLancamVal->numrows == 0) {
      throw new BusinessException("Lançamento {$this->iCodigoLancamento} não encontrado.");
    }
    $oStdLancamento         = db_utils::fieldsMemory($rsBuscaLancamento, 0);
    $this->sTipoLancamento  = ($oStdLancamento->c69_debito == $this->iCodigoReduzido) ? self::OPERACAO_DEBITO :
                                                                                        self::OPERACAO_CREDITO;
    $this->nValorLancamento = $oStdLancamento->c69_valor;
    return true;
  }

  /**
   * @return DocumentoEventoContabil
   * @throws Exception
   */
  protected function getDocumentoEventoContabil() {

    if (empty($this->oDocumentoEventoContabil)) {

      $oDaoDocumento = new cl_conlancamval();
      $sSqlBuscaDocumento = $oDaoDocumento->sql_query_conta_documento("distinct c71_coddoc", "c69_sequen = {$this->iCodigoLancamento} limit 1");
      $rsBuscaDocumento   = db_query($sSqlBuscaDocumento);
      if (!$rsBuscaDocumento) {
        throw new Exception("Ocorreu um erro ao buscar o documento do lançamento.");
      }
      $this->oDocumentoEventoContabil = DocumentoEventoContabilRepository::getPorCodigo(db_utils::fieldsMemory($rsBuscaDocumento, 0)->c71_coddoc);
    }
    return $this->oDocumentoEventoContabil;
  }

  /**
   * Atualiza o saldo da conta corrente na tabela contacorrentesaldo
   * @param integer $iContaCorrenteSequencial - Sequencial na tabela contacorrentedetalhe
   * @return string Retornamos uma string com os valores D (débito) ou C (crédito)
   */
  protected function atualizarSaldo($iContaCorrenteSequencial, $dtLancamento = null) {

    $sTipoLancamento = $this->sTipoLancamento;

    /**
		 * Podemos estar reprocessando um lançamento, aí não podemos alterar a sua data.
     */
    if ($dtLancamento === null) {
      $dtLancamento = date("Y-m-d", db_getsession("DB_datausu"));
    }

    list($iAno, $iMes, $iDia) = explode("-", $dtLancamento);

    $oDaoContaCorrenteSaldo = db_utils::getDao("contacorrentesaldo");

    $sWhere  = "     c29_contacorrentedetalhe = {$iContaCorrenteSequencial}";
    $sWhere .= " and c29_anousu = {$iAno}";
    $sWhere .= " and c29_mesusu = {$iMes}";

    $sSqlBuscaSaldo = $oDaoContaCorrenteSaldo->sql_query_file(null, "*", null, $sWhere);
    $rsBuscaSaldo   = $oDaoContaCorrenteSaldo->sql_record($sSqlBuscaSaldo);

    /**
     * Caso já haver registros na contacorrentesaldo, só atualizamos os campos necessários
     */
    if ($oDaoContaCorrenteSaldo->numrows > 0) {

      $oStdSaldo                              = db_utils::fieldsMemory($rsBuscaSaldo, 0);
      $oDaoContaCorrenteSaldo->c29_sequencial = $oStdSaldo->c29_sequencial;

      /**
       * Se for tipo crédito atualizamos incrementamops no campo crédito o valor do lançamento
       * E deixamos o campo débito com o mesmo valor
       */
      if ($sTipoLancamento == self::OPERACAO_CREDITO) {

        $oDaoContaCorrenteSaldo->c29_credito = $oStdSaldo->c29_credito + $this->nValorLancamento;
      } else {
        /**
         * E se for tipo débito fazemos o contrário.
         * O campo crédito fica com o mesmo valor
         * No campo débito incrementamos o valor do lançamento
         */
        $oDaoContaCorrenteSaldo->c29_debito = $oStdSaldo->c29_debito + $this->nValorLancamento;
      }

      $oDaoContaCorrenteSaldo->alterar($oStdSaldo->c29_sequencial);

    } else {

      /**
       * Se não incluímos um registro novo com os valores.
       */
      if ($sTipoLancamento == self::OPERACAO_CREDITO) {

        /**
         * Se for tipo crédito inserimos o registro com o valor do lançamento na coluna c29_crédito
         * E o campo débito é setado para 0
         */
        $oDaoContaCorrenteSaldo->c29_credito = $this->nValorLancamento;
        $oDaoContaCorrenteSaldo->c29_debito  = "0";
      } else {

        /**
         * Se não fazemos o contrário
         * O campo crédito é setado para 0
         * E o campo débito vai com o valor do lançamento na coluna c23_debito
         */
        $oDaoContaCorrenteSaldo->c29_credito = "0";
        $oDaoContaCorrenteSaldo->c29_debito  = $this->nValorLancamento;
      }

      $oDaoContaCorrenteSaldo->c29_contacorrentedetalhe = $iContaCorrenteSequencial;
      $oDaoContaCorrenteSaldo->c29_anousu               = $iAno;
      $oDaoContaCorrenteSaldo->c29_mesusu               = $iMes;
      $oDaoContaCorrenteSaldo->c29_sequencial           = null;
      $oDaoContaCorrenteSaldo->incluir($oDaoContaCorrenteSaldo->c29_sequencial);
    }

    if ($oDaoContaCorrenteSaldo->erro_status == "0") {
      throw new BusinessException("Não foi possível salvar o saldo da conta corrente.");
    }

    /**
     * Retornamos o tipo do lançamento, para poder vincular da tabela contacorrentedetalhe com a
     * contacorrentedetalheconlancamval e deixamos o campo c28_tipo com D (débito) ou C (crédito)
     */
    return $sTipoLancamento;
  }

  /**
   * metodo responsavel pelo vinculo
   * conlancamval a tabela contacorrentedetalhe
   * na tabela contacorrentedetalheconlancamval
   * @param integer $iContaCorrenteDetalhe
   * @param string  $sTipoLancamento - Define se o lançamento foi a crédito ou a débito
   */
  protected function vincularLancamentos($iContaCorrenteDetalhe, $sTipoLancamento) {

    if(!db_utils::inTransaction()) {
      throw new DBException("ERRO [1] - Não foi encontrada transação com o banco de dados. Procedimento abortado.");
    }

    $oDaoContaCorrenteDetalheConlancamval = db_utils::getDao("contacorrentedetalheconlancamval");

    $oDaoContaCorrenteDetalheConlancamval->c28_contacorrentedetalhe = $iContaCorrenteDetalhe;
    $oDaoContaCorrenteDetalheConlancamval->c28_conlancamval         = $this->iCodigoLancamento;
    $oDaoContaCorrenteDetalheConlancamval->c28_tipo                 = $sTipoLancamento;
    $oDaoContaCorrenteDetalheConlancamval->incluir(null);
    if ($oDaoContaCorrenteDetalheConlancamval->erro_status == 0) {

      $sMsgErro  = "ERRO [2] - Não foi possível vincular o lançamento {$this->iCodigoLancamento} com a ";
      $sMsgErro .= "conta corrente {$iContaCorrenteDetalhe}.\n{$oDaoContaCorrenteDetalheConlancamval->erro_msg}";
      throw new DBException($sMsgErro);
    }
  }

  /**
   * Método estático que atualiza o saldo da conta corrente quando estamos em um "reprocessamento" de lançamento
   *
   * Para o funcionamento correto, é preciso passar um objeto do tipo stdClass com as
   * propriedades da tabela conlancamval
   * @param stdClass $oStdConLancamVal
   * @return boolean
   *
   * @todo - refatorar para receber um objeto do tipo LancamentoContabilPartida
   *
   */
  public static function atualizarSaldoContaCorrenteReprocessamento($oStdConLancamVal) {

    $oDaoContaCorrenteSaldo   = db_utils::getDao("contacorrentesaldo");
    list($iAno, $iMes, $iDia) = explode("-", $oStdConLancamVal->c69_data);
    $sCampos                  = "c29_sequencial, c28_tipo, c29_debito, c29_credito";
    $sWhere                   = "     c69_sequen = {$oStdConLancamVal->c69_sequen} ";
    $sWhere                  .= " and c29_mesusu = {$iMes} ";
    $sWhere                  .= " and c29_anousu = {$iAno} ";
    $sSqlBuscaSaldo           = $oDaoContaCorrenteSaldo->sql_query_buscasaldo(null, $sCampos, null, $sWhere);
    $rsBuscaSaldo             = $oDaoContaCorrenteSaldo->sql_record($sSqlBuscaSaldo);

    /**
     * Se encontramos registros nas tabelas referentes a conta corrente
     * devemos atualizar os seus saldos
     * Caso não encontre simplesmente não faz nada.
     */
    if ($oDaoContaCorrenteSaldo->numrows > 0) {

      for ($iReduzido = 0; $iReduzido < $oDaoContaCorrenteSaldo->numrows; $iReduzido++) {

        $oStdBuscaSaldo                 = db_utils::fieldsMemory($rsBuscaSaldo, $iReduzido);
        $oDaoContaCorrenteSaldoAuxiliar = db_utils::getDao("contacorrentesaldo");

        /**
				 * Verifica qual a operação do lançamento (crédito ou débito)
				 */
        if ($oStdBuscaSaldo->c28_tipo == self::OPERACAO_CREDITO) {
          $oDaoContaCorrenteSaldoAuxiliar->c29_credito = $oStdBuscaSaldo->c29_credito - $oStdConLancamVal->c69_valor;
        } else {
          $oDaoContaCorrenteSaldoAuxiliar->c29_debito = $oStdBuscaSaldo->c29_debito - $oStdConLancamVal->c69_valor;
        }

        $oDaoContaCorrenteSaldoAuxiliar->c29_sequencial = $oStdBuscaSaldo->c29_sequencial;
        $oDaoContaCorrenteSaldoAuxiliar->alterar($oStdBuscaSaldo->c29_sequencial);
        if ($oDaoContaCorrenteSaldoAuxiliar->erro_status == "0") {
          throw new BusinessException("Erro ao atualizar saldo da Conta Corrente. Contate o suporte.");
        }

        /**
         * Após atualizarmos o saldo da conta corrente, excluímos o vínculo entre o detalhamento da conta corrente
         * e os valores que nela constavam
         */
        $oDaoConLancamValContaCorrente = db_utils::getDao("contacorrentedetalheconlancamval");
        $oDaoConLancamValContaCorrente->excluir(null, "c28_conlancamval = {$oStdConLancamVal->c69_sequen}");
        if ($oDaoConLancamValContaCorrente->erro_status == "0") {
          throw new BusinessException("Não foi possível excluir o vínculo entre a conta corrente e o lancamento contábil.");
        }
      }
    }
    return true;
  }

  /**
   * Retorna o código do lançamento (conlancamval)
   * @return integer
   */
  public function getCodigoLancamento() {
    return $this->iCodigoLancamento;
  }

  /**
   * Retorna o código reduzido da conta no plano de contas PCASP
   * @return integer
   */
  public function getCodigoReduzido() {
    return $this->iCodigoReduzido;
  }

  /**
   * Retorna o objeto da instituição
   * @return object
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  }

  /**
   * Recebe o objeto instituição
   * @param $oInstituicao
   */
  public function setInstituicao(Instituicao $oEntidade) {
    $this->oInstituicao = $oEntidade;
  }

  /**
   * Retorna o objeto do plano de contas
   * @return ContaPlanoPCASP
   */
  public function getContaPlano() {
    return $this->oContaPlano;
  }

  /**
   * Recebe o objeto plano de conta
   * @param $oContaPlano
   */
  public function setContaPlano(ContaPlanoPCASP $oContaPlano) {
    $this->oContaPlano = $oContaPlano;
  }


  /**
   * Retorna data do DataLancamento
   * @return date
   */
  public function getDataLancamento() {
    return $this->dDataLancamento;
  }

  /**
   * Recebe a data do DataLancamento
   * @param date $dDataLancamento
   */
  public function setDataLancamento($dDataLancamento) {
    $this->dDataLancamento = $dDataLancamento;
  }

  /**
   * Retorna o objeto Lançamento Auxiliar
   * @return ILancamentoAuxiliar
   */
  public function getLancamentoAuxiliar() {
    return $this->oLancamentoAuxiliar;
  }

  /**
   * Seta a conta corrente
   * @param ContaCorrente $oContaCorrente
   */
  public function setContaCorrente(ContaCorrente $oContaCorrente) {
    $this->oContaCorrente = $oContaCorrente;
  }

  /**
   * Retorna a conta corrente
   * @return ContaCorrente
   */
  public function getContaCorrente() {
    return $this->oContaCorrente;
  }

  /**
   * metodo para reprocessar saldo das conta corrente
   * @param iCodigoContaCorrenteDetalhe integer
   * @param dtLancamento date
   */
  public function reprocessarSaldo($iCodigoContaCorrenteDetalhe, $dtLancamento){
  	return $this->atualizarSaldo($iCodigoContaCorrenteDetalhe, $dtLancamento);
  }
}