<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");  

require_once ("dbforms/db_funcoes.php");

require_once ("classes/db_rhpesdoc_classe.php");
require_once ("classes/db_cgm_classe.php");
require_once ("classes/db_rhpessoal_classe.php");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

try {
  
  switch ($oParam->exec) {
    
    case "salvar":
      
      $oDaoCGM       = new cl_cgm();
      $oDaoRhPesDoc  = new cl_rhpesdoc();
      $oRhPessoal    = new cl_rhpessoal();
      $iMatricula    = $oParam->iMatricula;
      
      $sSqlCgm       = $oRhPessoal->sql_query_file($iMatricula, "rh01_numcgm", null, null);
      $rsCgm         = $oRhPessoal->sql_record($sSqlCgm);
      $aNumCgm       = db_utils::getColectionByRecord($rsCgm);
      $iNumCgm       = $aNumCgm[0]->rh01_numcgm;
      
      $iTitulo       = $oParam->iTitulo;
      $iZona         = $oParam->iZona;
      $iSecao        = $oParam->iSecao; 
      $iCertificado  = $oParam->iCertificado;
      $iCategoria    = $oParam->iCategoria;
      $iCtps         = $oParam->iCtps;
      $iSerie        = $oParam->iSerie;
      $iDigito       = $oParam->iDigito;
      $sUfCtps       = $oParam->sUfCtps;
      $iPis          = $oParam->iPis;
      $iCnh          = $oParam->iCnh;
      $sCategoriaCnh = $oParam->sCategoriaCnh;
      $dValidadeCnh  = $oParam->dValidadeCnh;
      $iCpf          = $oParam->iCpf;
      $iAno          = db_getsession("DB_anousu");

      $oDaoRhPesDoc->rh16_regist    = $iMatricula;
      $oDaoRhPesDoc->rh16_titele    = $iTitulo;
      $oDaoRhPesDoc->rh16_zonael    = $iZona;
      $oDaoRhPesDoc->rh16_secaoe    = $iSecao;
      $oDaoRhPesDoc->rh16_reserv    = $iCertificado;
      $oDaoRhPesDoc->rh16_catres    = $iCategoria;
      $oDaoRhPesDoc->rh16_ctps_n    = $iCtps;
      $oDaoRhPesDoc->rh16_ctps_s    = $iSerie;
      $oDaoRhPesDoc->rh16_ctps_d    = $iDigito;
      $oDaoRhPesDoc->rh16_ctps_uf   = $sUfCtps;
      $oDaoRhPesDoc->rh16_pis       = $iPis;
      $oDaoRhPesDoc->rh16_carth_n   = $iCnh;
      $oDaoRhPesDoc->r16_carth_cat  = $sCategoriaCnh;
      $oDaoRhPesDoc->rh16_carth_val = $dValidadeCnh;
      
      if($iCpf == "00000000000" && db_permissaomenu($iAno, 604, 3775) == "false" ){
      	
      	  throw new Exception("Usuário sem permissão para cadastrar CPF zerado.");
      }
      
      $sSqlCpfRepetido = $oDaoCGM->sql_query(null, "z01_cgccpf", null, "z01_cgccpf = '{$iCpf}' and z01_numcgm <> {$iNumCgm}");
      $rsCpfRepetido   = $oDaoCGM->sql_record($sSqlCpfRepetido);
      if ($oDaoCGM->numrows > 0) {
      	
      	throw new Exception("CPF já cadastrado no sistema.");
      }
      
      
      $oDaoCGM->z01_numcgm = $iNumCgm;
      $oDaoCGM->z01_cgccpf = $iCpf;
      
      db_inicio_transacao();
      $oDaoRhPesDoc->alterar($oDaoRhPesDoc->rh16_regist);
      if($oDaoRhPesDoc->erro_status == 0){
      	throw new Exception($oDaoRhPesDoc->erro_msg);
      }
      
      $oDaoCGM->alterar($oDaoCGM->z01_numcgm);
      if($oDaoCGM->erro_status == 0){
        throw new Exception($oDaoCGM->erro_msg);
      }      
      $oRetorno->sMessage = "Alteração efetuada com sucesso.";
    break;
    
    
    default:
      throw new ErrorException("Nenhuma Opção Definida");
    break;
  }
  
  db_fim_transacao(false);
  $oRetorno->sMessage = urlencode($oRetorno->sMessage);
  echo $oJson->encode($oRetorno);
  
} catch (Exception $eErro){
	
  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);
}