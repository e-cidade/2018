<?
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

require_once('libs/db_stdlib.php');
require_once('libs/db_utils.php');
require_once('libs/db_conecta.php');
require_once('libs/db_sessoes.php');
require_once('libs/JSON.php');
require_once('dbforms/db_funcoes.php');
require_once('model/encaminhamentos.model.php');

$oEncaminhamentos = new encaminhamento;

$oJson               = new services_json();
$oParam              = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMessage  = '';

if($oParam->exec == 'getCgsFaa') {
   
  $oTmp = $oEncaminhamentos->getCgsFaa($oParam->iFaa);

  if(empty($oTmp)) {
     
    $oRetorno->iStatus = 2;
    $oRetorno->iCgs    = '';
    $oRetorno->sNome   = urlencode('');
  } else {

    $oRetorno->iCgs  = $oTmp->sd24_i_numcgs;
    $oRetorno->sNome = urlencode( $oTmp->z01_v_nome );
  }
} else if($oParam->exec == 'getUnidadesMedico') {
  
  $oRetorno->oUnidades = $oEncaminhamentos->getUnidadesMedico($oParam->iMedico)->oUnidades;
  if(empty($oRetorno->oUnidades)) {
    $oRetorno->iStatus = 2;
  }
} else if($oParam->exec == 'getProcedimento') {
  
  $oRetorno->sProcedimento = $oParam->sProcedimento;
  $oTmp = $oEncaminhamentos->getProcedimento($oParam->sProcedimento,$oParam->iEspecialidade,
                                             isset($oParam->iUnidade) ? $oParam->iUnidade : 0);
  if(empty($oTmp)) {
     
    $oRetorno->iStatus = 2;
    $oRetorno->sDescrProcedimento = "Chave($oParam->sProcedimento) não Encontrado";
    $oRetorno->iCodProcedimento = '';
    $oRetorno->sProcedimento = '';
  } else {

    $oRetorno->sDescrProcedimento = urlencode($oTmp->sd63_c_nome);
    $oRetorno->iCodProcedimento = $oTmp->sd96_i_procedimento;
  }
} else if($oParam->exec == 'getEspecialidadeMedico' || $oParam->exec == 'getEspecialidade') {
  
  $oRetorno->sEspecialidade = $oParam->sEspecialidade;
  if($oParam->exec == 'getEspecialidade') {
    $oTmp = $oEncaminhamentos->getEspecialidade($oParam->sEspecialidade,false);
  } else {
    $oTmp = $oEncaminhamentos->getEspecialidade($oParam->sEspecialidade,true,$oParam->iCodMedico,$oParam->iCodUnidade);
  }
   
  if(empty($oTmp)) {
 
    $oRetorno->iStatus = 2;
    $oRetorno->sDescrEspecialidade = "Chave($oParam->sEspecialidade) não Encontrado";
    $oRetorno->iCodEspecialidade = '';
    $oRetorno->sEspecialidade = '';
  } else {

    $oRetorno->iCodEspecialidade = $oTmp->rh70_sequencial;
    $oRetorno->sDescrEspecialidade = urlencode($oTmp->rh70_descr);
  }
} else if($oParam->exec == 'getProcedimentosEncaminhamento') {
  
  $oRetorno->oProcedimentos = $oEncaminhamentos->getProcedimentosEncaminhamento($oParam->iEncaminhamento)->oProcedimentos;

  if(empty($oRetorno->oProcedimentos)) {
    $oRetorno->iStatus = 2;
  }
} else if($oParam->exec == 'alteraProcedimentosDoEncaminhamento') {
  
  db_inicio_transacao();
  $lSucesso = $oEncaminhamentos->alteraProcedimentosEncaminhamento($oParam->iEncaminhamento,$oParam->aProcedimentos);

  if(!$lSucesso) {
    
    $oRetorno->iStatus = 2;
    db_fim_transacao(true);

  } else {
    db_inicio_transacao();
  }
}

echo $oJson->encode($oRetorno);