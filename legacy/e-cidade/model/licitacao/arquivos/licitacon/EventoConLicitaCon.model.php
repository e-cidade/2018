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

use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Contrato as RegraContrato;

class EventoConLicitaCon extends ArquivoLicitaCon {

  const CODIGO_LAYOUT = 251;
  const NOME_ARQUIVO  = "EVENTO_CON";

  /**
   * Lista de Eventos dos Contratos
   * @var stdClass[]
   */
  private $aEventos = array();

  public function __construct(CabecalhoLicitaCon $oCabecalho) {

    parent::__construct($oCabecalho);
    $this->sNomeArquivo  = self::NOME_ARQUIVO;
    $this->iCodigoLayout = self::CODIGO_LAYOUT;
  }

  /**
   * Obtem dados do eventos dos acordos
   * @throws DBException
   * @return resource
   */
  private function getEventos() {

    $sDataAtual = $this->oCabecalho->getDataGeracao()->getDate();
    $oDaoAcordoEvento = new cl_acordoevento;
    $sCampos = " distinct " . implode(",", array(
      "acordoevento.*",
      "ac16_numero",
      "ac16_anousu",
      "ac16_sequencial",
      "ac16_tipoinstrumento",
      "ac26_numeroaditamento",
    ));
    $sWhere = implode(' and ', array(
      "(ac58_acordo is null or ac58_data >= '{$sDataAtual}')",
      "ac16_instit = {$this->oCabecalho->getInstituicao()->getCodigo()}",
      "(ac55_tipoevento <> 12 or (ac55_tipoevento = 12 and ac56_acordoevento is not null))",
      "exists (select 1 from acordoitem ai inner join acordoposicao ap on ap.ac26_sequencial = ai.ac20_acordoposicao where ap.ac26_acordo = acordo.ac16_sequencial) "
    ));
    $sOrder = 'ac16_sequencial asc, ac55_sequencial asc';
    $sSqlAcordoEvento = $oDaoAcordoEvento->sql_query(null, $sCampos, $sOrder, $sWhere);
    $rsAcordoEvento   = db_query($sSqlAcordoEvento);

    if (!$rsAcordoEvento) {
      throw new DBException("Erro ao buscar informações de evento dos acordos.");
    }

    return $rsAcordoEvento;
  }

  /**
   * Processar Eventos para o Formato do Layout
   */
  private function processarEventos() {

    $rsAcordoEventos = $this->getEventos();
    $iTotalEventos = pg_num_rows($rsAcordoEventos);
    $aTiposEventos = TipoEventoAcordo::getSiglas();
    $aTiposInstrumento = LicitaConTipoInstrumentoAcordo::getSiglas();

    for ($iIndice = 0; $iIndice < $iTotalEventos; $iIndice++) {

      $oEvento    = db_utils::fieldsMemory($rsAcordoEventos, $iIndice);
			$oRegraContrato = new RegraContrato($this->oCabecalho->getDataGeracao());
      $oLicitacao = $oRegraContrato->getDadosDaLicitacaoDoContrato($oEvento->ac16_sequencial);

      /**
       * Caso o evento seja um evento de lançamento de documentos
       */
      if ($aTiposEventos[$oEvento->ac55_tipoevento] == "NINF") {
        continue;
      }

      $oStdEvento = new stdClass;
      $oStdEvento->NR_LICITACAO          = $oLicitacao->numero;
      $oStdEvento->ANO_LICITACAO         = $oLicitacao->ano;
      $oStdEvento->CD_TIPO_MODALIDADE    = $oLicitacao->tipo;
      $oStdEvento->NR_CONTRATO           = $oEvento->ac16_numero;
      $oStdEvento->ANO_CONTRATO          = $oEvento->ac16_anousu;
      $oStdEvento->TP_INSTRUMENTO        = $aTiposInstrumento[$oEvento->ac16_tipoinstrumento];
      $oStdEvento->SQ_EVENTO             = $oEvento->ac55_sequencial;
      $oStdEvento->CD_TIPO_EVENTO        = $aTiposEventos[$oEvento->ac55_tipoevento];
      $oStdEvento->NR_PROCESSO           = null;
      $oStdEvento->DT_EVENTO             = null;
      $oStdEvento->ANO_PROCESSO          = null;
      $oStdEvento->TP_VEICULO_PUBLICACAO = null;
      $oStdEvento->DS_PUBLICACAO         = null;
      $oStdEvento->NR_PROCESSO           = null;
      $oStdEvento->ANO_PROCESSO          = null;
      $oStdEvento->NR_REGISTRO           = null;

      /**
       * Data do Evento
       */
      if (!empty($oEvento->ac55_data)) {

        $oDataEvento = new DBDate($oEvento->ac55_data);
        $oStdEvento->DT_EVENTO = $oDataEvento->getDate(DBDate::DATA_PTBR);
      }

      /**
       * Tipo de Veículo de Publicação e Descrição da Publicação
       */
      if ($aTiposEventos[$oEvento->ac55_tipoevento] == "PUC") {

        $oStdEvento->TP_VEICULO_PUBLICACAO = LicitaConTipoPublicacao::getSigla($oEvento->ac55_veiculocomunicacao);
        $oStdEvento->DS_PUBLICACAO         = str_replace('|', ' ', $oEvento->ac55_descricaopublicacao);
      }

      /**
       * Numero do Registro
       */
      if (in_array($aTiposEventos[$oEvento->ac55_tipoevento], array("TAD", "APO"))) {
        $oStdEvento->NR_REGISTRO = $oEvento->ac26_numeroaditamento;
      }

      /**
       * Número do Processo e Ano
       */
      if (in_array($aTiposEventos[$oEvento->ac55_tipoevento], array("SCD", "SCC"))) {

        $oStdEvento->NR_PROCESSO  = $oEvento->ac55_numeroprocesso;
        $oStdEvento->ANO_PROCESSO = $oEvento->ac55_anoprocesso;
      }

      $this->aEventos[] = $oStdEvento;
      unset($oStdEvento, $oEvento, $oLicitacao);
    }
  }

  /**
   * @return array
   */
  public function getDados() {

    $this->processarEventos();
    return $this->aEventos;
  }
}
