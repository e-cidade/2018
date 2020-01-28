<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once ("model/educacao/avaliacao/iElementoAvaliacao.interface.php");

db_app::import("educacao.*");
db_app::import("educacao.avaliacao.*");
db_app::import("exceptions.*");

$iEscola           = db_getsession("DB_coddepto");
$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

try {

  switch($oParam->exec) {

    /**
     * Pesquisa os dados dos alunos para cancelamento da troca de turma
     */
    case 'pesquisaEtapas':

      $aFiltros = array();

      if (isset($oParam->iEscola) && !empty($oParam->iEscola) && $oParam->iEscola != 0) {
        $aFiltros[] = " ed18_i_codigo in ({$oParam->iEscola}) ";
      }

      if (isset($oParam->iCalendario) && !empty($oParam->iCalendario)) {
        $aFiltros[] = " ed57_i_calendario in ({$oParam->iCalendario}) ";
      }

      if (isset($oParam->iAnoCalendario) && !empty($oParam->iAnoCalendario)) {
        $aFiltros[] = " ed52_i_ano in ({$oParam->iAnoCalendario}) ";
      }

      if (isset($oParam->iCurso) && !($oParam->iCurso)) {
        $aFiltros[] = " ed11_i_ensino= {$oParam->iCurso} ";
      }

      $sWhere    = implode(" and ", $aFiltros);
      $sCampos   = "distinct ed11_i_ensino,  ed11_i_codigo as codigo_etapa, ";
      $sCampos  .= "ed11_c_descr as etapa, ed10_c_abrev as ensino";
      $sOrdem    = "ed10_c_abrev, ed11_c_descr";

      $oDaoTurma  = new cl_turma();
      $sSqlEtapas = $oDaoTurma->sql_query_turma(null, $sCampos, $sOrdem, $sWhere);
      $rsEtapas   = $oDaoTurma->sql_record($sSqlEtapas);
      $iLnhas     = $oDaoTurma->numrows;

      if ($iLnhas == 0) {

        $sMsgErro  = "Nenhuma etapa encontrada.\n";
        $sMsgErro .= str_replace('\\n', "\n", $oDaoTurma->erro_sql);
        throw new Exception($sMsgErro, 1);
      }

      $oRetorno->dados = db_utils::getColectionByRecord($rsEtapas, false, false, true);

      break;
    
  }
} catch (ParameterException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
} catch (BusinessException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
} catch (DBException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);
?>