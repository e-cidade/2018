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
 * Class ProcessamentoContaCorrente
 */
class ProcessamentoContaCorrente {

  /**
   * @var string
   */
  const MENSAGENS = 'financeiro.contabilidade.ProcessamentoContaCorrente.';

  /**
   * @var DBDate
   */
  private $oDataInicial;

  /**
   * @var DBDate
   */
  private $oDataFinal;

  /**
   * @var DocumentoEventoContabil
   */
  private $oDocumentoEventoContabil;

  /**
   * @var ContaCorrente
   */
  private $oContaCorrente;

  /**
   * @var Instituicao
   */
  private $oInstituicao;

  /**
   * Total de registros processados.
   * @var integer
   */
  private $iTotalRegistrosProcessados = 0;

  /**
   * Método que busca os lançamentos contábeis para reprocessar as contas crédito e débito do lançamento
   * @return bool
   * @throws BusinessException
   * @throws DBException
   */
  public function processarContas() {

    $this->validar();

    $sCampos = "distinct conlancamval.*";
    $sWhere  = implode(' and ', array(
      "c71_coddoc = {$this->oDocumentoEventoContabil->getCodigo()}",
      "c18_contacorrente = {$this->oContaCorrente->getCodigo()}",
      "c60_anousu = {$this->oDataInicial->getAno()}",
      "c61_anousu = {$this->oDataInicial->getAno()}",
      "c70_data between '{$this->oDataInicial->getDate()}' and '{$this->oDataFinal->getDate()}'",
      "c61_instit = {$this->oInstituicao->getCodigo()}"
    ));

    $oDaoLancamentoContabil = new cl_conlancam();
    $sSqlBuscaPartidas = $oDaoLancamentoContabil->sql_query_conta_corrente($sCampos, 'c69_sequen', $sWhere);
    $rsBuscaPartidas   = db_query($sSqlBuscaPartidas);
    if (!$rsBuscaPartidas) {
      throw new DBException(_M( self::MENSAGENS . 'erro_busca_lancamentos'));
    }

    $iTotalRegistros = pg_num_rows($rsBuscaPartidas);
    if ($iTotalRegistros == 0) {
      throw new BusinessException(_M( self::MENSAGENS . 'registros_nao_encontrados'));
    }

    for ($iRow = 0; $iRow < $iTotalRegistros; $iRow++) {

      $oStdDados = db_utils::fieldsMemory($rsBuscaPartidas, $iRow);

      /**
       * Excluimos o vinculo com o detalhamento da conta corrente, caso existe
       */
      self::excluirVinculoPartida($oStdDados->c69_sequen);
      $oLancamentoAuxiliarContaCorrente = new LancamentoAuxiliarContaCorrente($oStdDados->c69_codlan, $oStdDados->c69_sequen);
      $oContaCredito                    = new DisponibilidadeFinanceira($oStdDados->c69_sequen, $oStdDados->c69_credito, $oLancamentoAuxiliarContaCorrente);
      $oContaDebito                     = new DisponibilidadeFinanceira($oStdDados->c69_sequen, $oStdDados->c69_debito, $oLancamentoAuxiliarContaCorrente);

      if ($oContaCredito !== false) {
        $oContaCredito->salvar($oStdDados->c69_data);
      }

      if ($oContaDebito !== false) {
        $oContaDebito->salvar($oStdDados->c69_data);
      }
    }
    $this->iTotalRegistrosProcessados = $iTotalRegistros;


    self::excluirDetalhamentosSemLancamentos();
    return true;
  }

  /**
   * @param integer $iCodigoPartida
   * @return bool
   * @throws BusinessException
   */
  private static function excluirVinculoPartida($iCodigoPartida) {

    $oDaoExcluirVinculo = new cl_contacorrentedetalheconlancamval();
    $oDaoExcluirVinculo->excluir(null, "c28_conlancamval = {$iCodigoPartida}");
    if ($oDaoExcluirVinculo->erro_status == "0") {
      throw new BusinessException(_M(self::MENSAGENS . 'erro_exclusao_vinculo'));
    }
    return true;
  }

  /**
   * Método responsável por excluir os detalhamentos sem vínculo com nenhum lançamento contabil, evitando registros desnecessários
   * @throws DBException
   * @return boolean
   */
  private static function excluirDetalhamentosSemLancamentos() {

    $sCampos = "array_to_string(array_accum(c19_sequencial),',') as detalhes";
    $sWhere  = " not exists (select 1 from contacorrentedetalheconlancamval  where c19_sequencial = c28_contacorrentedetalhe) ";
    $sWhere .= " and not exists ( select 1 from contacorrentesaldo where c29_mesusu = 0 and c29_contacorrentedetalhe = c19_sequencial)";
    $oDaoContaCorrenteDetalhe = new cl_contacorrentedetalhe();
    $sSqlBuscaDetalhesSemLancamento = $oDaoContaCorrenteDetalhe->sql_query_file(null, $sCampos, null, $sWhere);
    $rsBuscaDetalheSemLancamento    = db_query($sSqlBuscaDetalhesSemLancamento);
    if (!$rsBuscaDetalheSemLancamento) {
      throw new DBException(_M(self::MENSAGENS . 'erro_detalhe_sem_lancamento'));
    }

    $sSequenciais = db_utils::fieldsMemory($rsBuscaDetalheSemLancamento, 0)->detalhes;

    if (empty($sSequenciais)) {
      return false;
    }

    $oDaoContaCorrenteSaldo = new cl_contacorrentesaldo();
    $oDaoContaCorrenteSaldo->excluir(null, "c29_contacorrentedetalhe in ({$sSequenciais})");
    if ($oDaoContaCorrenteSaldo->erro_status == "0") {
      throw new DBException(_M(self::MENSAGENS . 'erro_excluir_saldo_conta_corrente'));
    }

    $oDaoContaCorrenteDetalhe->excluir(null, "c19_sequencial in ({$sSequenciais})");
    if ($oDaoContaCorrenteDetalhe->erro_status == "0") {
      throw new DBException(_M(self::MENSAGENS . 'erro_excluir_detalhamento'));
    }
    return true;
  }

  /**
   * @throws ParameterException
   */
  private function validar() {

    if (empty($this->oContaCorrente)) {
      throw new ParameterException(_M(self::MENSAGENS . 'conta_corrente_obrigatorio'));
    }

    if (empty($this->oInstituicao)) {
      throw new ParameterException(_M(self::MENSAGENS . 'instituicao_obrigatorio'));
    }

    if (empty($this->oDataInicial)) {
      throw new ParameterException(_M(self::MENSAGENS . 'data_inicial_obrigatorio'));
    }

    if (empty($this->oDataFinal)) {
      throw new ParameterException(_M(self::MENSAGENS . 'data_final_obrigatorio'));
    }

    if (empty($this->oDocumentoEventoContabil)) {
      throw new ParameterException(_M(self::MENSAGENS . 'documento_obrigatorio'));
    }
  }

  /**
   * @return int
   */
  public function getTotalRegistrosProcessados() {
    return $this->iTotalRegistrosProcessados;
  }

  /**
   * @param DBDate $oDataInicial
   */
  public function setDataInicial(DBDate $oDataInicial) {
    $this->oDataInicial = $oDataInicial;
  }

  /**
   * @param DBDate $oDataFinal
   */
  public function setDataFinal(DBDate $oDataFinal) {
    $this->oDataFinal = $oDataFinal;
  }

  /**
   * @param DocumentoEventoContabil $oDocumentoEventoContabil
   */
  public function setDocumentoEventoContabil(DocumentoEventoContabil $oDocumentoEventoContabil) {
    $this->oDocumentoEventoContabil = $oDocumentoEventoContabil;
  }

  /**
   * @param ContaCorrente $oContaCorrente
   */
  public function setContaCorrente(ContaCorrente $oContaCorrente) {
    $this->oContaCorrente = $oContaCorrente;
  }

  /**
   * @param Instituicao $oInstituicao
   */
  public function setInstituicao(Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }
}
