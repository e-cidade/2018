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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));

define('MENSAGENS_SAU4_MEDICOS_RPC', 'saude.ambulatorial.sau4_medicos_RPC.');

$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
$oRetorno->erro    = false;
$iDepartamento     = db_getsession("DB_coddepto");

try {

  switch($oParam->exec) {

    /**
     * Busca as unidades e CBOs que o(s) médico(s) trabalha(m) 
     */
  	case 'getUnidadeCBOMedico':
  	  
  	  $aWhere = array();
  	  if (!empty($oParam->iMedico)) {
  	    $aWhere[] = " codigo_medico = {$oParam->iMedico}";
        $aWhere[] = " (situacao = 'A' OR situacao IS NULL)";
  	  }
  	  
  	  $sWhere = implode(" and ", $aWhere);
  	  
  	  $oDaoMedico = new cl_medicos();
  	  $sSql       = $oDaoMedico->sql_query_unidade_cbo_medico("nome", $sWhere);
  	  
  	  $rsMedicos = db_query($sSql);
  	  if (!$rsMedicos) {
  	  	throw new DBException("Erro ao buscar médicos.\n" . pg_last_error());
  	  }
  	  $oRetorno->aDados = db_utils::getCollectionByRecord($rsMedicos, false, false, true);
    break;

    /**
     * Busca as especialidades de um profissional. Pode receber os seguintes parâmetros:
     * - lValidaUnidade: se setado, busca somente especialidades do profissional vinculadas ao departamento logado
     * - lSomenteAtiva: se setado, busca somente vínculos ativos
     */
    case 'especialidadeMedico':

      if(empty($oParam->iProfissional)) {
        throw new ParameterException(_M(MENSAGENS_SAU4_MEDICOS_RPC . 'profissional_nao_informado'));
      }

      $oDaoEspecialidade       = new cl_especmedico();
      $sCamposEspecialidade    = "distinct rh70_sequencial, rh70_descr";
      $sOrdenacaoEspecialidade = "rh70_descr";
      $sWhereEspecialidade     = "sd04_i_unidade = {$iDepartamento}";

      if(isset($oParam->lValidaUnidade)) {
        $sWhereEspecialidade .= " AND sd04_i_medico = {$oParam->iProfissional}";
      }

      if(isset($oParam->lSomenteAtiva)) {
        $sWhereEspecialidade .= " AND sd27_c_situacao = 'A'";
      }

      $sSqlEspecialidade = $oDaoEspecialidade->sql_query_especmedico(
        null,
        $sCamposEspecialidade,
        $sOrdenacaoEspecialidade,
        $sWhereEspecialidade
      );

      $rsEspecialidade = db_query($sSqlEspecialidade);

      if(!$rsEspecialidade) {
        throw new DBException(_M(MENSAGENS_SAU4_MEDICOS_RPC . 'erro_buscar_especialidades'));
      }

      $iTotalEspecialidades = pg_num_rows($rsEspecialidade);

      if($iTotalEspecialidades == 0) {
        throw new BusinessException(_M(MENSAGENS_SAU4_MEDICOS_RPC . 'nenhuma_especialidade_encontrada'));
      }

      $oRetorno->aEspecialidades = db_utils::makeCollectionFromRecord($rsEspecialidade, function($oRetorno) {

        $oEspecialidade                 = new stdClass();
        $oEspecialidade->iEspecialidade = $oRetorno->rh70_sequencial;
        $oEspecialidade->sEspecialidade = DBString::urlencode_all($oRetorno->rh70_descr);

        return $oEspecialidade;
      });

      break;
  }
} catch (Exception $oErro) {

  db_fim_transacao(true);

  $oRetorno->status  = 2;
  $oRetorno->erro    = true;
  $oRetorno->message = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);