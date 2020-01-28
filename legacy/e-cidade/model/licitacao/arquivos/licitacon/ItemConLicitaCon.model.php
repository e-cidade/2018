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
 * Class ItemConLicitaCon
 */
class ItemConLicitaCon extends ArquivoLicitaCon {

  const CODIGO_LAYOUT = 246;
  const NOME_ARQUIVO  = "ITEM_CON";

  /**
   * @type stdClass[]
   */
  private $aItens = array();

  /**
   * Campos utilizados nas querys dos métodos
   * @type array
   */
  private static $aCampos = array(
    "distinct (select min(coalesce(case when l20_tipojulg in(1,2) then 1 else lcll.l04_codigo end, 1)) from liclicitemlote as lcll inner join liclicitem as lcl on lcll.l04_liclicitem = lcl.l21_codigo where lcl.l21_codliclicita = liclicitem.l21_codliclicita and lcll.l04_descricao = liclicitemlote.l04_descricao) as nr_lote",
    "l21_codliclicita",
    "ac16_numero",
    "ac16_anousu",
    "l04_liclicitem",
    "l04_descricao",
    "ac16_tipoinstrumento",
    "l21_ordem",
    "ac20_quantidade",
    "ac20_valorunitario",
    "ac20_valortotal",
    "l20_numero",
    "l20_anousu",
    "l44_sigla",
    "l20_codigo",
    "pc23_bdi",
    "pc23_encargossociais"
  );

  /**
   * ItemConLicitaCon constructor.
   *
   * @param CabecalhoLicitaCon $oCabecalho
   */
  public function __construct(CabecalhoLicitaCon $oCabecalho) {

    parent::__construct($oCabecalho);
    $this->sNomeArquivo  = self::NOME_ARQUIVO;
    $this->iCodigoLayout = self::CODIGO_LAYOUT;
  }

  /**
   * @return array
   */
  public function getDados() {

    $this->aItens = array();
    $this->carregarItensOrigemLicitacao();
    $this->carregarItensOrigemManual();
    $this->carregarItensOrigemProcessoDeCompras();
    return $this->aItens;
  }

  /**
   * Carrega os itens do acordo com origem Licitação
   * @return bool
   * @throws DBException
   * @todo verificar a necessidade do where ac16_dataassinatura >= '2016-05-02'
   */
  public function carregarItensOrigemLicitacao() {

    $sWhere = implode(" and ", array(
      'ac16_origem = ' . Acordo::ORIGEM_LICITACAO,
      "ac26_acordoposicaotipo = " . AcordoPosicao::TIPO_INCLUSAO,
      "ac16_dataassinatura >= '2016-05-02'",
      "(ac58_acordo is null or ac58_data >= '{$this->oCabecalho->getDataGeracao()->getDate()}')",
      "pc24_pontuacao = 1",
      "ac16_instit = {$this->oCabecalho->getInstituicao()->getCodigo()}",
    ));

    $rsItens = LoteConLicitaCon::getItens(implode(',', self::$aCampos), $sWhere);
    if (!$rsItens) {
      throw new DBException("Não foi possível encontrar os itens para o arquivo " . self::NOME_ARQUIVO);
    }

    $iTotalItens = pg_num_rows($rsItens);
    for ($iIndice = 0; $iIndice < $iTotalItens; $iIndice++) {

      $oItem = db_utils::fieldsMemory($rsItens, $iIndice);
      $this->adicionarItem(self::criarObjetoImpressao($oItem));
    }
    return true;
  }

  /**
   * Carrega os itens do acordo com origem Manual
   * @return bool
   * @throws DBException
   * @todo verificar a necessidade do where ac16_dataassinatura >= '2016-05-02'
   */
  public function carregarItensOrigemManual() {

    $sWhere = implode(" and ", array(
      'ac16_origem = ' . Acordo::ORIGEM_MANUAL,
      "ac26_acordoposicaotipo = " . AcordoPosicao::TIPO_INCLUSAO,
      "ac16_dataassinatura >= '2016-05-02'",
      "(ac58_acordo is null or ac58_data >= '{$this->oCabecalho->getDataGeracao()->getDate()}')",
      "pc24_pontuacao = 1",
      "ac16_instit = {$this->oCabecalho->getInstituicao()->getCodigo()}",
    ));

    $rsItens = LoteConLicitaCon::getItens(implode(',', self::$aCampos), $sWhere);
    if (!$rsItens) {
      throw new DBException("Não foi possível encontrar os itens para o arquivo " . self::NOME_ARQUIVO);
    }

    $iTotalItens = pg_num_rows($rsItens);

    for ($iIndice = 0; $iIndice < $iTotalItens; $iIndice++) {

      $oItem = db_utils::fieldsMemory($rsItens, $iIndice);
      $this->adicionarItem(self::criarObjetoImpressao($oItem));
    }
    return true;
  }

  /**
   * Carrega os itens do acordo com origem Processo de Compras
   * @throws DBException
   * @todo verificar a necessidade do where ac16_dataassinatura >= '2016-05-02'
   */
  private function carregarItensOrigemProcessoDeCompras() {

    $sWhere = implode(" and ", array(
      'ac16_origem = ' . Acordo::ORIGEM_PROCESSO_COMPRAS,
      "ac26_acordoposicaotipo = " . AcordoPosicao::TIPO_INCLUSAO,
      "ac16_dataassinatura >= '2016-05-02'",
      "(ac58_acordo is null or ac58_data >= '{$this->oCabecalho->getDataGeracao()->getDate()}')",
      "pc24_pontuacao = 1",
      "ac16_instit = {$this->oCabecalho->getInstituicao()->getCodigo()}",
    ));

    $oDaoItemProcesso = new cl_solicitemvinculo();
    $sSqlBuscaItens   = $oDaoItemProcesso->sql_query_item_licitacon(implode(', ', self::$aCampos), $sWhere);
    $rsBuscaItens     = db_query($sSqlBuscaItens);
    if (!$rsBuscaItens) {
      throw new DBException("Não foi possível carregar os itens do acordo incluso com origem Processo de Compras.");
    }

    $iTotalRegistros = pg_num_rows($rsBuscaItens);
    for ($iRow = 0; $iRow < $iTotalRegistros; $iRow++) {

      $oStdInformacao = db_utils::fieldsMemory($rsBuscaItens, $iRow);
      $this->adicionarItem(self::criarObjetoImpressao($oStdInformacao));
    }
  }

  /**
   * Cria um objeto stdClass para emissão do arquivo de acordo com as propriedades cadastradas no cadastro de relatório
   * @param stdClass $oStdRecordSet
   * @return stdClass
   */
  private static function criarObjetoImpressao(stdClass $oStdRecordSet) {

    $aTiposInstrumento = LicitaConTipoInstrumentoAcordo::getSiglas();
    $oLicitacaoDinamico = new LicitacaoAtributosDinamicos();
    $oLicitacaoDinamico->setCodigoLicitacao($oStdRecordSet->l20_codigo);
    $oStdItem = new stdClass;
    $oStdItem->NR_LICITACAO        = $oStdRecordSet->l20_numero;
    $oStdItem->ANO_LICITACAO       = $oStdRecordSet->l20_anousu;
    $oStdItem->CD_TIPO_MODALIDADE  = $oStdRecordSet->l44_sigla;
    $oStdItem->NR_CONTRATO         = $oStdRecordSet->ac16_numero;
    $oStdItem->ANO_CONTRATO        = $oStdRecordSet->ac16_anousu;
    $oStdItem->TP_INSTRUMENTO      = $aTiposInstrumento[$oStdRecordSet->ac16_tipoinstrumento];
    $oStdItem->NR_LOTE             = $oStdRecordSet->nr_lote;
    $oStdItem->NR_ITEM             = $oStdRecordSet->l21_ordem;
    $oStdItem->QT_ITENS            = number_format($oStdRecordSet->ac20_quantidade, 3, ",", '');
    $oStdItem->VL_ITEM             = number_format($oStdRecordSet->ac20_valorunitario, 3, ",", '');
    $oStdItem->VL_TOTAL_ITEM       = number_format($oStdRecordSet->ac20_valortotal, 2, ",", '');
    $oStdItem->PC_BDI              = null;
    $oStdItem->PC_ENCARGOS_SOCIAIS = null;

    if ($oLicitacaoDinamico->getAtributo("tipoobjeto") == 'OSE') {

      $oStdItem->PC_BDI              = number_format($oStdRecordSet->pc23_bdi, 2, ",", "");
      $oStdItem->PC_ENCARGOS_SOCIAIS = number_format($oStdRecordSet->pc23_encargossociais, 2, ",", "");
    }
    return $oStdItem;
  }

  /**
   * @param stdClass $oStdItem
   */
  private function adicionarItem(stdClass $oStdItem) {
    $this->aItens[] = $oStdItem;
  }
}
