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

session_start();
require_once("libs/db_conecta.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once ("libs/JSON.php");
require_once("classes/db_ouvidoriaatendimento_classe.php");

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno           = new stdClass();
$oRetorno->dados    = array();
$oRetorno->status   = 1;

try{
  
  switch ($oParam->exec) {
  
    case 'buscaDados':
      
      if (isset($oParam->lRetornoAutomatico) && empty($oParam->lRetornoAutomatico)) {
      
        $oDaoOuvidoriaAtendimento = db_utils::getDao('ouvidoriaatendimento');
      	$sCampos  = " DISTINCT ov01_sequencial,                           "; 
        $sCampos .= " fc_numeroouvidoria(ov01_sequencial) as ov01_numero, ";
        $sCampos .= " p51_descr,                                          "; 
        $sCampos .= " ov01_requerente,                                    "; 
        $sCampos .= " descrdepto,                                         ";
        $sCampos .= " ov01_dataatend,                                     ";
        $sCampos .= " ov01_dataatend,                                     ";
        $sCampos .= " case when                                           ";
        $sCampos .= "   ov02_cnpjcpf is null                              ";
        $sCampos .= "     then z01_cgccpf                                 ";
        $sCampos .= "   else ov02_cnpjcpf end as cpf_cnpj,                ";
        $sCampos .= " ov09_protprocesso,                                  ";
        $sCampos .= " p58_numero,                                         ";
        $sCampos .= " p58_ano                                             ";

        $aWhere = array();
        
        if ($oParam->iCPF != "") {
          
          $oParam->iCPF = removeFormatacao($oParam->iCPF);
          $aWhere[] = "(ov02_cnpjcpf = '{$oParam->iCPF}' or z01_cgccpf = '{$oParam->iCPF}')";
        }
        
        if ($oParam->iCNPJ != "") {
          
          $oParam->iCNPJ = removeFormatacao($oParam->iCNPJ);
          $aWhere[] = "(ov02_cnpjcpf = '{$oParam->iCNPJ}' or z01_cgccpf = '{$oParam->iCNPJ}')";
        }
        
        if ($oParam->iProcesso != "") {
          $aWhere[] = "p58_codproc = {$oParam->iProcesso}";
        }
        
        if ($oParam->iAtendimento != "") {
          
          list($iNumeroAtendimento, $iAnoAtendimento) = explode("/", $oParam->iAtendimento); 
          $aWhere[] = "ov01_numero = {$iNumeroAtendimento} and ov01_anousu = {$iAnoAtendimento}";
        }
        
        $sWhere                  = implode(" and ",$aWhere);
        $sSqlBuscaAtendimentos   = $oDaoOuvidoriaAtendimento->sql_query_titular(null, $sCampos,null, $sWhere);
        $rsBuscaAtendimentos     = $oDaoOuvidoriaAtendimento->sql_record($sSqlBuscaAtendimentos);
    
        if ($oDaoOuvidoriaAtendimento->numrows == 0) {
          throw new Exception("Não encontrado atendimentos para os filtros selecionados.");
        }
  
        $aResultados                     = array();
        for($iAtendimento = 0; $iAtendimento < $oDaoOuvidoriaAtendimento->numrows; $iAtendimento++){
          
          $oOuvidoriaAtendimento           = db_utils::fieldsMemory($rsBuscaAtendimentos,$iAtendimento);
          $oAtendimento                    = new stdClass();
          $oAtendimento->iSeqAtendimento   = $oOuvidoriaAtendimento->ov01_sequencial;
          $oAtendimento->iAtendimento      = $oOuvidoriaAtendimento->ov01_numero;     
          $oAtendimento->sDescricao        = urlencode($oOuvidoriaAtendimento->p51_descr);       
          $oAtendimento->sRequerente       = urlencode($oOuvidoriaAtendimento->ov01_requerente); 
          $oAtendimento->sDepartamento     = urlencode($oOuvidoriaAtendimento->descrdepto);      
          $oAtendimento->dtDataAtendimento = $oOuvidoriaAtendimento->ov01_dataatend;
          $oAtendimento->iProtocolo        = $oOuvidoriaAtendimento->ov09_protprocesso;
          $oAtendimento->iCpfCnpj          = $oOuvidoriaAtendimento->cpf_cnpj;
          
          $aResultados[]                   = $oAtendimento;
        }
        
        $_SESSION["aResultadosConsultaOuvidoria"] = $aResultados;
      }
      
      $oRetorno->status                = 1;
      $oRetorno->aResultados           = $_SESSION["aResultadosConsultaOuvidoria"];
      
    break;
  } 
}catch (Exception $eErro) {
		  
		  $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
		  $oRetorno->status  = 2;
}

function removeFormatacao($sValor) {
  
  $sValor = str_replace(".", "", $sValor);
  $sValor = str_replace("-", "", $sValor);
  $sValor = str_replace("/", "", $sValor);
  
  return $sValor;
}

echo $oJson->encode($oRetorno);
?>