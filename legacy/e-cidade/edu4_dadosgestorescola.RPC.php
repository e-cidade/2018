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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("std/db_stdClass.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("model/webservices/ControleAcessoAluno.model.php");

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";
$iCodigoEscola     = db_getsession("DB_coddepto");

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));

switch ($oParam->exec) {

  case 'getGestorEscolaCenso':

    $oDaoGestorEscolaCenso = new cl_escolagestorcenso();

    $sCampos  = " case when cgmrh.z01_nome is not null";
    $sCampos .= "        then cgmrh.z01_nome";
    $sCampos .= "        else cgmcgm.z01_nome";
    $sCampos .= " end as z01_nome,";
    $sCampos .= " ed20_i_codigo,";
    $sCampos .= " case when cgmrh.z01_cgccpf is not null";
    $sCampos .= "        then cgmrh.z01_cgccpf";
    $sCampos .= "        else cgmcgm.z01_cgccpf";
    $sCampos .= " end as z01_cgccpf,";
    $sCampos .= " ed325_email, ed325_rechumano";

    $sSqlGestorEscola   = $oDaoGestorEscolaCenso->sql_query_dados_gestor(null,
                                                                         $sCampos,
                                                                         "z01_nome",
                                                                         "ed325_escola = {$iCodigoEscola}"
                                                                        );

    $rsDadosGestorCenso = $oDaoGestorEscolaCenso->sql_record($sSqlGestorEscola);

    if ($oDaoGestorEscolaCenso->numrows > 0) {

      $oRetorno->iStatus            = 1;
      $oRetorno->sMensagem          = "";
      $oRetorno->oGestorEscolaCenso = db_utils::fieldsMemory($rsDadosGestorCenso, 0, false, false, true);
    } else {
      $oRetorno->iStatus   = 2;
    }

    break;

  case 'salvarGestorEscola':

      try {

        db_inicio_transacao();

        $oDaoGestorEscolaCenso = new cl_escolagestorcenso();

        $oDaoGestorEscolaCenso->excluir(null, "ed325_escola = {$iCodigoEscola}");

        $oDaoGestorEscolaCenso->ed325_escola    = $iCodigoEscola;
        $oDaoGestorEscolaCenso->ed325_rechumano = $oParam->iRecHumano;
        $oDaoGestorEscolaCenso->ed325_email     = db_stdClass::normalizeStringJsonEscapeString($oParam->sEmail);
        $oDaoGestorEscolaCenso->incluir(null);

        db_fim_transacao(false);

        $oRetorno->iStatus = 1;


      } catch (BusinessException $eErro) {

        db_fim_transacao(true);
        $oRetorno->iStatus  = 2;
        $oRetorno->sMessage = urlencode($eErro->getMessage());
      }
      break;
}

echo $oJson->encode($oRetorno);