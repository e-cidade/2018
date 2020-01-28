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

use \ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Geral as Regra;
use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Contrato as RegraContrato;

class DocumentoConLicitaCon extends ArquivoLicitaCon {

  const CODIGO_LAYOUT = 250;
  const NOME_ARQUIVO  = "DOCUMENTO_CON";

  public function __construct(CabecalhoLicitaCon $oCabecalho) {

    parent::__construct($oCabecalho, new Regra($oCabecalho->getDataGeracao()));
    $this->sNomeArquivo  = self::NOME_ARQUIVO;
    $this->iCodigoLayout = self::CODIGO_LAYOUT;
  }

  /**
   * @return bool|resource
   * @throws DBException
   */
  public function getDocumentos() {

    $sCampos = " distinct " . implode(', ', array(
      'ac16_sequencial      as sequencial',
      'ac16_numero          as nr_contrato',
      'ac16_anousu          as ano_contrato',
      'ac16_tipoinstrumento as tp_instrumento',
      'ac55_sequencial      as sq_evento',
      'ac57_tipodocumento   as cd_tipo_documento',
      'ac40_nomearquivo     as nome_arquivo',
      'ac40_arquivo         as codigo_arquivo',
      "ac55_tipoevento      as evento"
    ));

    $sDataAtual = $this->oCabecalho->getDataGeracao()->getDate(DBDate::DATA_EN);
    $sWhere   = " (ac58_acordo is null or ac58_data >= '{$sDataAtual}') and ac16_instit = {$this->oCabecalho->getInstituicao()->getCodigo()} and (ac55_tipoevento <> 12 or (ac55_tipoevento = 12 and ac56_acordoevento is not null))";
    $sOrderBy = "sequencial asc";
    $oDaoAcordoDocumento = new cl_acordodocumento();
    $sSqlDocumentos      = $oDaoAcordoDocumento->sql_query_documentos_eventos($sCampos, $sWhere, $sOrderBy);
    $rsDocumentos        = db_query($sSqlDocumentos);

    if (!$rsDocumentos) {
      throw new DBException("Não foi possível encontrar os documentos para a geração do arquivo ". self::NOME_ARQUIVO);
    }

    return $rsDocumentos;
  }

  /**
   * @return array
   */
  public function getDados() {

    $rsDocumentos      = $this->getDocumentos();
    $iTotalDocumentos  = pg_num_rows($rsDocumentos);
    $aTiposDocumento   = LicitaConTipoDocumentoAcordo::getSiglas();
    $aTiposInstrumento = LicitaConTipoInstrumentoAcordo::getSiglas();

    $aDocumentos = array();
    for ($iIndice = 0; $iIndice < $iTotalDocumentos; $iIndice++) {

      $oDocumento    = db_utils::fieldsMemory($rsDocumentos, $iIndice);
			$oRegraContrato = new RegraContrato($this->oCabecalho->getDataGeracao());
      $oLicitacao    = $oRegraContrato->getDadosDaLicitacaoDoContrato($oDocumento->sequencial);
      $oStdDocumento = new stdClass;

      $sCaminhoArquivo = $this->sNomeArquivo . "\\" . preg_replace("/((?![\w\\.!@#$%*()_+= ,<>?\/^~-]).)/", "", $oDocumento->nome_arquivo);
			$sCaminhoArquivo = File::cutName($sCaminhoArquivo, $this->oRegra->getTamanhoNomeArquivo());

      $oStdDocumento->NR_LICITACAO           = $oLicitacao->numero;
      $oStdDocumento->ANO_LICITACAO          = $oLicitacao->ano;
      $oStdDocumento->CD_TIPO_MODALIDADE     = $oLicitacao->tipo;
      $oStdDocumento->NR_CONTRATO            = $oDocumento->nr_contrato;
      $oStdDocumento->ANO_CONTRATO           = $oDocumento->ano_contrato;
      $oStdDocumento->TP_INSTRUMENTO         = $aTiposInstrumento[$oDocumento->tp_instrumento];
      $oStdDocumento->SQ_EVENTO              = $oDocumento->evento == TipoEventoAcordo::EVENTO_DOCUMENTOS ? '' : $oDocumento->sq_evento;
      $oStdDocumento->CD_TIPO_DOCUMENTO      = $aTiposDocumento[$oDocumento->cd_tipo_documento];
      $oStdDocumento->NOME_ARQUIVO_DOCUMENTO = $sCaminhoArquivo;

      $aDocumentos[] = $oStdDocumento;
      $this->aAnexos[$oDocumento->codigo_arquivo] = $sCaminhoArquivo;
    }

    return $aDocumentos;
  }

}