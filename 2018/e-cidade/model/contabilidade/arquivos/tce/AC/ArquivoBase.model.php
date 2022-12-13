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

abstract class ArquivoBase {
  /**
   * @var string
   */
  protected $sArquivo;

  /**
   * @var DBDate
   */
  protected $oDataInicial;

  /**
   * @var DBDate
   */
  protected $oDataFinal;

  /**
   * @var array
   */
  protected $aInstituicoes;

  abstract protected function getArquivo();

  /**
   * @param DBDate $oDataInicial
   * @param DBDate $oDataFinal
   * @throws Exception
   */
  protected function __construct(DBDate $oDataInicial, DBDate $oDataFinal) {

    $this->oDataInicial = $oDataInicial;
    $this->oDataFinal   = $oDataFinal;

    $oDaoDBConfig     = new cl_db_config();
    $sSqlInstituicoes = $oDaoDBConfig->sql_query_file( null,
                                                       'codigo, codtrib',
                                                       'tribinst, codigo',
                                                       'tribinst = ' . db_getsession("DB_instit") );

    $rsInstituicoes = $oDaoDBConfig->sql_record($sSqlInstituicoes);

    if (!$rsInstituicoes || !pg_num_rows($rsInstituicoes)) {
      throw new Exception("Erro ao buscar instituições.");
    }

    $this->aInstituicoes = array();

    foreach( db_utils::getCollectionByRecord($rsInstituicoes) as $oInstituicao) {
      $this->aInstituicoes[] = $oInstituicao->codigo;
    }
  }

  /**
   * @param DOMDocument $oDocumento
   * @param string $sCaminhoSchema
   * @throws Exception
   */
  protected function validarXML(DOMDocument $oDocumento, $sCaminhoSchema) {

    libxml_use_internal_errors(true);
    $lDocumentoValido = $oDocumento->schemaValidate($sCaminhoSchema);

    $aErros = libxml_get_errors();
    $iTotalErros = count($aErros);
    $aMensagem = array();

    if ($lDocumentoValido || $iTotalErros == 0) {
      return true;
    }

    foreach ($aErros as $iIndice => $oErro) {
      $aMensagem[] = $iIndice+1 . ' - ' . $oErro->message;
    }

    libxml_clear_errors();
    throw new Exception("XML gerado possui $iTotalErros erro(s).\n" . implode("", $aMensagem));
  }

}
