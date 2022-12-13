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

require_once modification("model/contabilidade/arquivos/tce/AC/ArquivoBase.model.php");
require_once modification("model/contabilidade/arquivos/tce/AC/ArquivoConfiguracaoTCEAC.model.php");

class ArquivoLancamento extends ArquivoBase {

  /**
   * @param DBDate $oDataInicial
   * @param DBDate $oDataFinal
   */
  public function __construct(DBDate $oDataInicial, DBDate $oDataFinal) {

    parent::__construct($oDataInicial, $oDataFinal);
    $this->processarDados();
  }

  /**
   * @return void
   */
  private function processarDados() {

    $aLancamentos = $this->getLancamentos();

    if (empty($aLancamentos)) {
      throw new Exception("O Período informado não possui lançamentos contábeis.");
    }

    $this->gerarArquivo($aLancamentos);
  }

  /**
   * @return array
   */
  private function getDocumentos() {

    $oArquivoConfiguracao = ArquivoConfiguracaoTCEAC::getInstancia();
    $aDocumentos = array_keys($oArquivoConfiguracao->getEventosContabis());

    if (empty($aDocumentos)) {
      throw new Exception("Nenhum documento encontrado no arquivo de configuração.");
    }

    sort($aDocumentos);
    return array_unique($aDocumentos);
  }

  /**
   * @return StdClass[]
   */
  private function getLancamentos() {

    $aLancamentos = array();
    $oDaoLancamentos = new cl_conlancam();
    $sInstituicoes = implode(',', $this->aInstituicoes);
    $sDocumentos = implode(',', $this->getDocumentos());
    $sCampos = "c70_codlan, c70_data, c71_coddoc, c73_coddot, c72_complem, o58_unidade, o58_orgao, c02_instit,";
    $sCampos .= "codtrib, c115_conhistdocestorno  as estorno";
    $sOrdem = "c70_data";
    $sWhere = "c02_instit in($sInstituicoes) ";
    $sWhere .= "and c70_data between '{$this->oDataInicial->getDate()}' and '{$this->oDataFinal->getDate()}' ";
    $sWhere .= " and c71_coddoc in($sDocumentos)";
    $sSqlLancamentos = $oDaoLancamentos->sql_query_lancamentos_documento($sCampos, $sOrdem, $sWhere);
    $rsLancamentos = db_query($sSqlLancamentos);

    if (!$rsLancamentos) {
      throw new Exception("Erro ao buscar lançamentos.");
    }

    if (pg_num_rows($rsLancamentos) == 0) {
      return $aLancamentos;
    }

    for ($iLinha = 0; $iLinha < pg_num_rows($rsLancamentos); $iLinha++) {

      $oStdDadosLancamento = db_utils::fieldsMemory($rsLancamentos, $iLinha);
      $iClp = ArquivoConfiguracaoTCEAC::getInstancia()->getEventoPorCodigo($oStdDadosLancamento->c71_coddoc);
      $oData = new DBDate($oStdDadosLancamento->c70_data);
      $iUnidade = $oStdDadosLancamento->o58_unidade;
      $iOrgao = $oStdDadosLancamento->o58_orgao;

      /**
       * Lancamento sem dotacao, usa unidade da instituicao
       */
      if (empty($iUnidade)) {
        $iUnidade = (int) substr($oStdDadosLancamento->codtrib, 0, 2);
      }

      /**
       * Lancamento sem dotacao, usa orgao da instituicao
       */
      if (empty($iOrgao)) {
        $iOrgao = (int) substr($oStdDadosLancamento->codtrib, 2, 2);
      }

      $oStdLancamento = new stdClass();
      $oStdLancamento->numero    = $oStdDadosLancamento->c70_codlan;
      $oStdLancamento->data      = $oData->getDate(DBDate::DATA_PTBR);
      $oStdLancamento->tipo      = $oStdDadosLancamento->estorno ? 'ESTORNO' : 'ORDINARIO';
      $oStdLancamento->historico = $oStdDadosLancamento->c72_complem;
      $oStdLancamento->unidade   = '000001';
      $oStdLancamento->orgao     = '000304';
      $oStdLancamento->clp       = $iClp;

      $aLancamentos[] = $oStdLancamento;
    }

    return $aLancamentos;
  }

  /**
   * @param array $aLancamentos
   * @return void
   */
  private function gerarArquivo(Array $aLancamentos) {

    $oDocumento = new DOMDocument('1.0', 'utf-8');
    $oDocumento->formatOutput = true;
    $oDocumento->encoding = 'utf-8';

    $oLista = $oDocumento->createElement('lista');
    $oDocumento->appendChild($oLista);

    foreach($aLancamentos as $oStdLancamento) {

      $sHistorico = mb_convert_encoding(
        $oStdLancamento->historico,
        "UTF-8",
        mb_detect_encoding($oStdLancamento->historico, "UTF-8, ISO-8859-1, ISO-8859-15", true)
      );

      $oHistorico = $oDocumento->createElement('historico', $sHistorico);
//      $oHistoricoCDATA = $oDocumento->createCDATASection($sHistorico);
//      $oHistorico->appendChild($oHistorico);

      $oLancamento = $oDocumento->createElement('lancamento');
      $oLancamento->appendChild($oDocumento->createElement('numero', $oStdLancamento->numero));
      $oLancamento->appendChild($oDocumento->createElement('data', $oStdLancamento->data));
      $oLancamento->appendChild($oDocumento->createElement('tipoDeLancamento', $oStdLancamento->tipo));
      $oLancamento->appendChild($oHistorico);

      $oUnidadeOrcamentaria = $oDocumento->createElement('unidadeOrcamentaria');
      $oUnidadeOrcamentariaCodigo = $oDocumento->createElement('codigo', $oStdLancamento->unidade);
      $oUnidadeOrcamentariaOrgao = $oDocumento->createElement('orgao');
      $oUnidadeOrcamentariaOrgaoCodigo = $oDocumento->createElement('codigo', $oStdLancamento->orgao);

      $oUnidadeOrcamentariaOrgao->appendChild($oUnidadeOrcamentariaOrgaoCodigo);
      $oUnidadeOrcamentaria->appendChild($oUnidadeOrcamentariaCodigo);
      $oUnidadeOrcamentaria->appendChild($oUnidadeOrcamentariaOrgao);

      $oLancamento->appendChild($oUnidadeOrcamentaria);

      $oClp = $oDocumento->createElement('clp');
      $oClpCodigo = $oDocumento->createElement('codigo', $oStdLancamento->clp);
      $oClp->appendChild($oClpCodigo);

      $oLancamento->appendChild($oClp);
      $oLista->appendChild($oLancamento);
    }

    $this->validarXML($oDocumento, 'config/tce/AC/schema-lancamento.xsd');
    $this->sArquivo = $oDocumento->saveXML();
  }

  /**
   * @return string
   */
  public function getArquivo() {
    return $this->sArquivo;
  }

}
