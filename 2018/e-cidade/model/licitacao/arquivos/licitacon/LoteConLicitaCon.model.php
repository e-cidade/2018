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

class LoteConLicitaCon extends ArquivoLicitaCon {

  const CODIGO_LAYOUT = 247;
  const NOME_ARQUIVO  = "LOTE_CON";

  public function __construct(CabecalhoLicitaCon $oCabecalho) {

    parent::__construct($oCabecalho);
    $this->sNomeArquivo  = self::NOME_ARQUIVO;
    $this->iCodigoLayout = self::CODIGO_LAYOUT;
  }

  /**
   *
   * @param  string $sCampos
   * @param  string $sWhere
   * @param  string $sGroupBy
   * @param  string $sOrderBy
   * @throws DBException
   * @return resource
   */
  public static function getItens($sCampos, $sWhere = null, $sGroupBy = null, $sOrderBy = 'l20_codigo') {

    $aSql = array(
      "select {$sCampos}",
      'from acordo',
      'inner join acordoposicao        on ac26_acordo              = ac16_sequencial',
      'inner join acordoitem           on ac20_acordoposicao       = ac26_sequencial',
      'inner join acordoliclicitem     on ac24_acordoitem          = ac20_sequencial',
      'inner join liclicitem           on ac24_liclicitem          = l21_codigo',
      'inner join liclicitemlote       on l04_liclicitem           = l21_codigo',
      'inner join pcorcamitemlic       on pc26_liclicitem          = l21_codigo',
      'inner join pcorcamitem          on pc22_orcamitem           = pc26_orcamitem',
      'inner join pcorcamval           on pc23_orcamitem           = pc22_orcamitem',
      'left  join pcorcamjulg          on pc24_orcamitem           = pc23_orcamitem',
      '                               and pc24_orcamforne          = pc23_orcamforne',
      'inner join liclicita            on l21_codliclicita         = l20_codigo',
      'inner join cflicita             on l20_codtipocom           = l03_codigo',
      'inner join pctipocompratribunal on l03_pctipocompratribunal = l44_sequencial',
      'left join acordoencerramentolicitacon on ac58_acordo       = ac16_sequencial'
    );

    if ($sWhere) {
      $aSql[] = "where {$sWhere}";
    }

    if ($sGroupBy) {
      $aSql[] = "group by {$sGroupBy}";
    }

    if($sOrderBy) {
      $aSql[] = "order by {$sOrderBy}";
    }

    $rsDados = db_query(implode("\n", $aSql));
    if (!$rsDados) {
      throw new DBException('Não foi possível buscar os itens');
    }

    return $rsDados;
  }

  /**
   *
   * @param  string $sCampos
   * @param  string $sWhere
   * @param  string $sGroupBy
   * @param  string $sOrderBy
   * @throws DBException
   * @return resource
   */
  private function getItensProcessoCompras($sCampos, $sWhere = null, $sGroupBy = null, $sOrderBy = null) {

    $aSql = array(
      "select {$sCampos}",
      "from acordo",
      "inner join acordoposicao                  on ac26_acordo                          = ac16_sequencial",
      "inner join acordoitem                     on ac20_acordoposicao                   = ac26_sequencial",
      "inner join acordopcprocitem               on ac23_acordoitem                      = ac20_sequencial",
      "inner join pcprocitem                     on ac23_pcprocitem                      = pc81_codprocitem",
      "inner join solicitem                      on pc81_solicitem                       = solicitem.pc11_codigo",
      "inner join solicitemvinculo               on pc55_solicitemfilho                  = pc81_solicitem",
      "inner join solicitem solicitemlicitacao   on pc55_solicitempai                    = solicitemlicitacao.pc11_codigo",
      "inner join pcprocitem pcprocitemlicitacao on solicitemlicitacao.pc11_codigo       = pcprocitemlicitacao.pc81_solicitem",
      "inner join liclicitem                     on pcprocitemlicitacao.pc81_codprocitem = l21_codpcprocitem",
      "inner join liclicitemlote                 on l04_liclicitem                       = l21_codigo",
      "inner join liclicita                      on l21_codliclicita                     = l20_codigo",
      "inner join cflicita                       on l20_codtipocom                       = l03_codigo",
      "inner join pctipocompratribunal           on l03_pctipocompratribunal             = l44_sequencial",
      "left join acordoencerramentolicitacon     on ac58_acordo                          = ac16_sequencial",
    );

    if ($sWhere) {
      $aSql[] = "where {$sWhere}";
    }

    if ($sGroupBy) {
      $aSql[] = "group by {$sGroupBy}";
    }

    if($sOrderBy) {
      $aSql[] = "order by {$sOrderBy}";
    }

    $rsDados = db_query(implode("\n", $aSql));
    if (!$rsDados) {
      throw new DBException('Não foi possível buscar os itens');
    }

    return $rsDados;
  }

  /**
   * @return array
   */
  public function getDados() {

    $sCampos = implode(', ', array(
      'distinct l20_codigo as codigo_licitacao',
      'l20_numero as nr_licitacao',
      'l20_anousu as ano_licitacao',
      'l44_sigla as cd_tipo_modalidade',
      'ac16_sequencial as codigo_contrato',
      'ac16_numero as nr_contrato',
      'ac16_anousu as ano_contrato',
      'ac16_tipoinstrumento as tp_instrumento',
      'l20_tipojulg as tipo_julgamento',
      '(select min(coalesce(case when l20_tipojulg in(1,2) then 1 else l04_codigo end, 1))) as nr_lote',
      '(case when l20_tipojulg in(1,2) then null else l04_descricao end) as ds_lote',
      'ac16_origem',
    ));
    $sCamposProcessoCompras = "{$sCampos}, ac16_valor as vl_lote";
    $sGroupBy = implode(', ', array(
      'l20_codigo',
      'l20_numero',
      'l20_anousu',
      'l20_tipojulg',
      'ds_lote',
      'l44_sigla',
      'ac16_sequencial',
      'ac16_numero',
      'ac16_anousu',
      'ac16_tipoinstrumento',
      'ac16_origem',
    ));
    $sDataAtual = $this->oCabecalho->getDataGeracao()->getDate();
    $aWherePadrao = array(
      "(ac58_acordo is null or ac58_data >= '{$sDataAtual}')",
      "ac16_instit = {$this->oCabecalho->getInstituicao()->getCodigo()}",
      "ac16_dataassinatura >= '2016-05-02'",
    );
    $aWhereItensLicitacao = array(
      'ac16_origem in (' . Acordo::ORIGEM_LICITACAO . ',' . Acordo::ORIGEM_MANUAL . ')'
    );
    $aWhereItensProcessoCompras = array(
      'ac16_origem = ' . Acordo::ORIGEM_PROCESSO_COMPRAS
    );
    $sWhereItensLicitacao = implode(' and ', array_merge($aWherePadrao, $aWhereItensLicitacao));
    $sWhereItensProcessCompras = implode(' and ', array_merge($aWherePadrao, $aWhereItensProcessoCompras));
    $sOrderBy = 'l20_codigo';

    $rsItensLicitacao = self::getItens($sCampos, $sWhereItensLicitacao, $sGroupBy, $sOrderBy);
    $rsItensProcessoCompras = $this->getItensProcessoCompras($sCamposProcessoCompras, $sWhereItensProcessCompras, $sGroupBy, $sOrderBy);

    $aItensLicitacao = $this->processarItens($rsItensLicitacao);
    $aItensProcessoCompras = $this->processarItens($rsItensProcessoCompras);
    $aLinhas = array_merge($aItensLicitacao, $aItensProcessoCompras);

    return $aLinhas;
  }

  /**
   *
   * @param  resource $rsItens
   * @return array
   */
  private function processarItens($rsItens) {

    $aTiposInstrumento = LicitaConTipoInstrumentoAcordo::getSiglas();

    $aLinhas = array();
    $iQuantidadeItens = pg_num_rows($rsItens);
    for ($iItem = 0; $iItem < $iQuantidadeItens; $iItem++) {

      $oStdItem = db_utils::fieldsMemory($rsItens, $iItem);

      $oLinha = new stdClass;
      $oLinha->NR_LICITACAO       = $oStdItem->nr_licitacao;
      $oLinha->ANO_LICITACAO      = $oStdItem->ano_licitacao;
      $oLinha->CD_TIPO_MODALIDADE = $oStdItem->cd_tipo_modalidade;
      $oLinha->NR_CONTRATO        = $oStdItem->nr_contrato;
      $oLinha->ANO_CONTRATO       = $oStdItem->ano_contrato;
      $oLinha->TP_INSTRUMENTO     = $aTiposInstrumento[$oStdItem->tp_instrumento];
      $oLinha->NR_LOTE            = $oStdItem->nr_lote;
      $oLinha->VL_LOTE            = 0;



      switch ($oStdItem->ac16_origem) {

        case $oStdItem->ac16_origem == Acordo::ORIGEM_LICITACAO:

          $oOrcamentoLicitacao = new OrcamentoLicitacao(new licitacao($oStdItem->codigo_licitacao));
          $oOrcamentoLicitacao->setCodigoAcordo($oStdItem->codigo_contrato);
          /**
           * Caso o julgamento seja por lote queremos o valor julgado do lote somente
           */
          if ($oStdItem->tipo_julgamento == licitacao::TIPO_JULGAMENTO_POR_LOTE) {
            $oOrcamentoLicitacao->setDescricaoLote($oStdItem->ds_lote);
          }
          $oLinha->VL_LOTE = $oOrcamentoLicitacao->getValorTotalHomologado();
          break;

        case Acordo::ORIGEM_MANUAL:

          if (!empty($oStdItem->codigo_contrato)) {
            $acordo = AcordoRepository::getByCodigo($oStdItem->codigo_contrato);
            $oLinha->VL_LOTE = $acordo->getValorContrato();
          }
          break;

        case Acordo::ORIGEM_PROCESSO_COMPRAS:
          $oLinha->VL_LOTE = $oStdItem->vl_lote;
          break;
      }
      $oLinha->VL_LOTE = number_format($oLinha->VL_LOTE, 2, ',', '');

      $aLinhas[] = $oLinha;
    }

    return $aLinhas;
  }

}
