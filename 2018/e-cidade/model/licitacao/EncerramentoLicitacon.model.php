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
 * Class EncerramentoLicitacon
 * Classse responsável por controlar o encerramento das licitações para os arquivos do LicitaCon.
 */
class EncerramentoLicitacon {

  /**
   * @var DBDate
   */
  private $oDataAtual;

  /**
   * @var Instituicao
   */
  private $oInstituicao;

  public function __construct(DBDate $oDataAtual, Instituicao $oInstituicao) {

    $this->oDataAtual   = $oDataAtual;
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Realiza o encerramento do LicitaCon para todas as licitações que possuem envento de encerramento até a data
   * informata e que ainda não tenham encerramento para o LicitaCon.
   * @param DBDate $oDataEncerramento
   *
   * @throws BusinessException
   * @throws DBException
   */
  public function encerrar(DBDate $oDataEncerramento) {

    if (is_null($this->oInstituicao)) {
      throw new BusinessException("A instituição deve ser informada para o encerramento das Licitações do Licitacon.");
    }

    if (is_null($this->oDataAtual)) {
      throw new BusinessException("A data atual deve ser informada para o encerramento das Licitações do Licitacon.");
    }

    if (is_null($oDataEncerramento)) {
      throw new BusinessException("A data de encerramento deve ser informada para o encerramento das Licitações do Licitacon.");
    }

    if ($oDataEncerramento->getTimeStamp() > $this->oDataAtual->getTimeStamp()) {
      throw new BusinessException("A Data de Geração deve ser menor ou igual a data atual.");
    }

    $iInstituicao      = $this->oInstituicao->getCodigo();
    $sDataEncerramento = $oDataEncerramento->getDate(DBDate::DATA_EN);
    $sEncerramentos    = implode(", ", EventoLicitacao::getEventosEncerramento());

    $sCamposLicitacoesEncerrar = " distinct l20_codigo ";
    $sWhereLicitacoesEncerrar  = " l18_sequencial is null and l46_liclicitatipoevento in ({$sEncerramentos}) ";
    $sWhereLicitacoesEncerrar .= " and l46_dataevento <= '{$sDataEncerramento}' and l20_instit = {$iInstituicao} ";

    $oDaoLicitacoesEncerrar  = new cl_liclicita();
    $oDaoLicitacaoEncerrando = new cl_liclicitaencerramentolicitacon();
    $sSqlLicitacoesEncerrar  = $oDaoLicitacoesEncerrar->sql_query_encerramento($sCamposLicitacoesEncerrar, $sWhereLicitacoesEncerrar);
    $rsLicitacoesEncerrar    = db_query($sSqlLicitacoesEncerrar);
    
    if ($rsLicitacoesEncerrar === false) {
      throw new DBException("Houve um erro ao buscar as licitações para o encerramento do LicitaCon.");
    }

    $iTotalLicitacoes = pg_num_rows($rsLicitacoesEncerrar);

    if(empty($iTotalLicitacoes)) {
      throw new BusinessException("Não há licitações a confirmar o envio.");
    }
    
    for ($iLicitacaoAtual = 0; $iLicitacaoAtual < $iTotalLicitacoes; $iLicitacaoAtual++) {

      $oLicitacaoAtual = db_utils::fieldsMemory($rsLicitacoesEncerrar, $iLicitacaoAtual);

      $oDaoLicitacaoEncerrando->l18_sequencial = null;
      $oDaoLicitacaoEncerrando->l18_liclicita  = $oLicitacaoAtual->l20_codigo;
      $oDaoLicitacaoEncerrando->l18_data       = $sDataEncerramento;
      if (!$oDaoLicitacaoEncerrando->incluir(null)) {
        throw new DBException("Houve um erro ao encerrar a Licitação.");
      }
    }

    $this->encerrarContratos($oDataEncerramento);
  }

  /**
   * @param DBDate $oDataEncerrameto
   * @return bool
   * @throws DBException
   */
  public function encerrarContratos(DBDate $oDataEncerrameto) {

    $oDaoAcordo  = new cl_acordo;
    $sDataEncerramento = $oDataEncerrameto->getDate(DBDate::DATA_EN);
    $iInstituicao      = $this->oInstituicao->getCodigo();

    $sCampos = "distinct ac16_sequencial";
    $sWhere  = implode(" and ", array(
      "ac55_tipoevento = " . AcordoEvento::TIPO_EVENTO_ENCERRAMENTO_CONTRATO,
      "ac58_acordo is null",
      "ac16_instit = {$iInstituicao}",
      "ac55_data <= '{$sDataEncerramento}'"
    ));

    $sSqlAcordoEncerrados = $oDaoAcordo->sql_query_encerramento($sCampos, $sWhere);
    $rsAcordoEncerados = db_query($sSqlAcordoEncerrados);

    if (!$rsAcordoEncerados) {
      throw new DBException("Houve um error ao buscar os Acordos para o encerramento do LicitaCon.");
    }

    $iTotalAcordos = pg_num_rows($rsAcordoEncerados);
    for ($iIndice = 0; $iIndice < $iTotalAcordos; $iIndice++) {

      $oAcordo = db_utils::fieldsMemory($rsAcordoEncerados, $iIndice);

      $oDaoAcordoEncerramento = new cl_acordoencerramentolicitacon;
      $oDaoAcordoEncerramento->ac58_sequencial = null;
      $oDaoAcordoEncerramento->ac58_acordo     = $oAcordo->ac16_sequencial;
      $oDaoAcordoEncerramento->ac58_data       = $sDataEncerramento;

      if (!$oDaoAcordoEncerramento->incluir(null)) {
        throw new DBException("Houve um erro ao encerrar o Acordo.");
      }
    }

    return true;
  }
}