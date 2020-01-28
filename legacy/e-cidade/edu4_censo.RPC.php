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
require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_app.utils.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("libs/JSON.php"));

require_once(modification("model/dbLayoutReader.model.php"));
require_once(modification("model/dbLayoutLinha.model.php"));

use ECidade\Educacao\Escola\Censo\SituacaoAluno\Exportacao;
use ECidade\Educacao\Escola\Censo\SituacaoAluno\Importacao;
use \ECidade\Educacao\Escola\Censo\Censo;

$oJson              = new services_json();
$oParam             = JSON::create()->parse(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = '';

$iEscola   = db_getsession("DB_coddepto");
$sFonteMsg = 'educacao.escola.edu4_censoRPC.';

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    /**
     * Busca a data base do censo e as datas do inicio e fim do calendário para o ano que esta gerando o censo na escola
     */
    case "buscaDataBaseCenso":

      if ( empty($oParam->iAno) ) {
        $oParam->iAno = date("Y");
      }

      $oCenso = new Censo($oParam->iAno);
      $oData  = $oCenso->getDataCenso();

      $oRetorno->dataCenso = $oData->getDate(DBDate::DATA_PTBR);
      $oRetorno->iAno      = $oParam->iAno;

      /**
       * Busca dos dados do calendário da escola
       */
      $oDaoCalendario = new cl_calendarioescola;
      $sWhere         = "ed52_i_ano = {$oParam->iAno} AND ed38_i_escola = {$iEscola}";
      $sCampos        = "min(ed52_d_inicio), max(ed52_d_fim)";
      $sSqlCalendario = $oDaoCalendario->sql_query("", $sCampos, null, $sWhere);
      $rsCalendario   = db_query($sSqlCalendario);

      if ( !$rsCalendario ) {
        throw new Exception( _M( $sFonteMsg . 'erro_buscar_calendario') );
      }

      $oDatasCalendario = db_utils::fieldsMemory($rsCalendario, 0);
      if ( empty($oDatasCalendario->min) || empty($oDatasCalendario->max) ) {

        $oMsgErro       = new stdClass();
        $oMsgErro->iAno = $oParam->iAno;
        throw new Exception( _M( $sFonteMsg . 'sem_calendario', $oMsgErro) );
      }
      $oRetorno->inicioCalendario = db_formatar($oDatasCalendario->min, 'd');
      $oRetorno->fimCalendario    = db_formatar($oDatasCalendario->max, 'd');

      break;

    case 'exportarSituacaoAluno' :

      if ( empty($oParam->iAno) ) {
        throw new Exception( _M( $sFonteMsg . 'ano_nao_informado') );
      }

      $oCenso      = new Censo($oParam->iAno);
      $oExportacao = new Exportacao( $oCenso, new Escola($iEscola));

      $oRetorno->sArquivoLog    = '';
      $oRetorno->lInconsistente = false;
      if ( !$oExportacao->gerarArquivo() ){

        $oRetorno->sArquivoLog = $oExportacao->getNomeAquivoLog();
        $oRetorno->lInconsistente = true;
      }
      $oRetorno->sArquivoCenso = $oExportacao->getNomeArquivo();

      break;

    case 'importarSituacaoAluno':

      if ( empty($oParam->iAno) ) {
        throw new Exception( _M( $sFonteMsg . 'ano_nao_informado') );
      }

      if ( empty($oParam->sPath) ) {
        throw new Exception( _M($sFonteMsg . 'arquivo_nao_encontrado') );
      }

      $oCenso      = new Censo($oParam->iAno);
      $oExportacao = new Importacao( $oCenso, new Escola($iEscola), $oParam->sPath);
      $oExportacao->importar($oParam->sPath);

      $oRetorno->lInconsistente = $oExportacao->temInconsitencia();
      $oRetorno->sArquivoLog    = $oExportacao->getNomeAquivoLog();
      $oRetorno->iAno           = $oParam->iAno;

      $oRetorno->sMessage = _M($sFonteMsg . 'situacao_importada');
      if ($oRetorno->lInconsistente) {
        $oRetorno->sMessage = _M($sFonteMsg . 'situacao_importada_parcialmente');
      }


      break;
  }

  db_fim_transacao(false);


} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = $eErro->getMessage();
}
$oRetorno->erro = $oRetorno->iStatus == 2;
echo JSON::create()->stringify($oRetorno);