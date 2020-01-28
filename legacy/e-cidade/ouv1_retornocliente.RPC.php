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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("std/db_stdClass.php");

$oPost    = db_utils::postMemory($_POST);
$oJson    = new services_json();
$lErro    = false;
$sMsgErro = '';

require_once('classes/db_protprocesso_classe.php');
$clProtProcesso = new cl_protprocesso();

require_once('classes/db_ouvidoriaatendimento_classe.php');
$clOuvidoriaAtendimento = new cl_ouvidoriaatendimento();

require_once('model/processoOuvidoria.model.php');
$oProcessoOuvidoria = new processoOuvidoria();

require_once('classes/db_ouvidoriaatendimentoretorno_classe.php');
$clOuvidoriaAtendimentoRetorno = new cl_ouvidoriaatendimentoretorno();


if ( $oPost->sMethod == 'consultaProcessos') {
 
	
	$aListaProcesso  = array(); 

	$iDepto   = db_getsession('DB_coddepto');
	$iUsuario = db_getsession('DB_id_usuario');
  $iIntit   = db_getsession('DB_instit');
  
  $sWhereProcesso  = "     p51_tipoprocgrupo = 2                                                                                  ";
  $sWhereProcesso .= " and p58_instit      = {$iIntit}                                                                            ";
  $sWhereProcesso .= " and p61_coddepto    = {$iDepto}                                                                            ";
  $sWhereProcesso .= " and not exists( select *                                                                                   ";
  $sWhereProcesso .= "                   from proctransferproc                                                                    ";                                    
  $sWhereProcesso .= "                        inner join proctransfer on proctransfer.p62_codtran = proctransferproc.p63_codtran  ";
  $sWhereProcesso .= "                        left  join proctransand on proctransand.p64_codtran = proctransferproc.p63_codtran  ";
  $sWhereProcesso .= "                  where p63_codproc  = p58_codproc                                                          ";
  $sWhereProcesso .= "                    and p64_codtran is null limit 1 )                                                       "; 
  $sWhereProcesso .= " and p68_codarquiv is null                                                                                  ";
  
  
  if ( trim($oPost->iTipoRetorno) != 0 ) {
  	if ( $oPost->iTipoRetorno == 5 ) {
      $sWhereProcesso .= " and not exists ( select * 
                                             from processoouvidoria 
                                                  inner join ouvidoriaatendimentotiporetorno on ov17_ouvidoriaatendimento = ov09_ouvidoriaatendimento
                                            where ov09_protprocesso = protprocesso.p58_codproc ) ";  		 
  	} else {
	  	$sWhereProcesso .= " and exists ( select * 
	  	                                    from processoouvidoria 
	  	                                         inner join ouvidoriaatendimentotiporetorno on ov17_ouvidoriaatendimento = ov09_ouvidoriaatendimento
	  	                                   where ov09_protprocesso = protprocesso.p58_codproc 
	  	                                     and ov17_tiporetorno  = {$oPost->iTipoRetorno}) ";
  	}
  }
  
  if ( trim($oPost->iProcIni) != '' ) {
    $sWhereProcesso .= " and p58_codproc >= {$oPost->iProcIni}  ";
  }
  if ( trim($oPost->iProcFin) != '' ) {
    $sWhereProcesso .= " and p58_codproc <= {$oPost->iProcFin}  ";
  } 
  if ( trim($oPost->dtDataIni) != '' ) {
    $sWhereProcesso .= " and p58_dtproc >= '".implode('-',array_reverse(explode('/',$oPost->dtDataIni)))."'";
  }
  if ( trim($oPost->dtDataFin) != '' ) {
    $sWhereProcesso .= " and p58_dtproc <= '".implode('-',array_reverse(explode('/',$oPost->dtDataFin)))."'";
  }  
  if ( trim($oPost->iProcTipo) != '' ) {
    $sWhereProcesso .= " and p58_codigo = {$oPost->iProcTipo}  ";
  }      
  
  if (trim($oPost->iNumeroAtendimento) != "") {
    
    if (!empty($oPost->iAnoAtendimento)) {
      $iAnoAtendimento = $oPost->iAnoAtendimento;
      $iNumeroAtendimento = $oPost->iNumeroAtendimento;
    } else {
      list($iNumeroAtendimento, $iAnoAtendimento) = explode("/", $oPost->iNumeroAtendimento);
    }

    $sWhereProcesso .= " and ov01_numero = {$iNumeroAtendimento} and ov01_anousu = {$iAnoAtendimento}";
  }
  
  $sSqlProcesso = $clProtProcesso->sql_query_transand(null,"*","p58_codproc",$sWhereProcesso);
  
  $rsProcessos  = $clProtProcesso->sql_record($sSqlProcesso);

  if ( $clProtProcesso->numrows > 0 ) {
    $aListaProcesso = db_utils::getCollectionByRecord($rsProcessos,false,false,true);
  } else {
    $sMsgErro = 'Nenhum registro encontrado!';
    $lErro    = true;
  }	
	
  $aRetorno = array("lErro"          =>$lErro,
	                  "sMsg"           =>$sMsgErro,
                    "aListaProcessos"=>$aListaProcesso);

  echo $oJson->encode($aRetorno);
  
} else if ( $oPost->sMethod == 'consultaAtendimentos') {
 
  
  $aListaAtendimentos  = array(); 
  $sCamposAtendimento  = "  distinct ov01_sequencial,";
  $sCamposAtendimento .= " fc_numeroouvidoria(ov01_sequencial) as ov01_numero, ";
  $sCamposAtendimento .= " ov01_anousu,     ";
  $sCamposAtendimento .= " ov01_requerente, ";
  $sCamposAtendimento .= " case ";
  $sCamposAtendimento .= "   when ( select ov20_sequencial";
  $sCamposAtendimento .= "            from ouvidoriaatendimentoretorno";
  $sCamposAtendimento .= "           where ov20_ouvidoriaatendimento = ov01_sequencial";
  $sCamposAtendimento .= "           limit 1 ) is null then 'Sem Retorno'"; 
  $sCamposAtendimento .= "   else 'Com Retorno'"; 
  $sCamposAtendimento .= " end as situacao,  ";
  $sCamposAtendimento .= " case ";
  $sCamposAtendimento .= "   when ( select ov20_sequencial";
  $sCamposAtendimento .= "            from ouvidoriaatendimentoretorno";
  $sCamposAtendimento .= "           where ov20_ouvidoriaatendimento = ov01_sequencial";
  $sCamposAtendimento .= "             and ov20_confirma is true";
  $sCamposAtendimento .= "           limit 1 ) is not null then 'Sim'"; 
  $sCamposAtendimento .= "   else 'Não'"; 
  $sCamposAtendimento .= " end as confirmacao,";  
  $sCamposAtendimento .= " case ";
  $sCamposAtendimento .= "   when ( select ov17_sequencial";
  $sCamposAtendimento .= "            from ouvidoriaatendimentotiporetorno";
  $sCamposAtendimento .= "           where ov17_ouvidoriaatendimento = ov01_sequencial";
  $sCamposAtendimento .= "           limit 1 ) is not null then 'true'"; 
  $sCamposAtendimento .= "   else 'false' ";
  $sCamposAtendimento .= " end as tiporetorno ";  
  $sWhereAtendimento   = "  ov09_protprocesso = {$oPost->iCodProcesso}";
  $sSqlAtendimento     = $clOuvidoriaAtendimento->sql_query_proc(null,$sCamposAtendimento,null,$sWhereAtendimento);

  $rsAtendimento       = $clOuvidoriaAtendimento->sql_record($sSqlAtendimento);
   
  if ( $clOuvidoriaAtendimento->numrows > 0 ) {
    $aListaAtendimentos = db_utils::getCollectionByRecord($rsAtendimento,false,false,true);
  } else {
    $sMsgErro = 'Nenhum registro encontrado!';
    $lErro    = true;
  }
  
  $aRetorno = array("lErro"             =>$lErro,
                    "sMsg"              =>$sMsgErro,
                    "aListaAtendimentos"=>$aListaAtendimentos);

  echo $oJson->encode($aRetorno);
  
} else if ( $oPost->sMethod == 'incluirRetorno') {

	db_inicio_transacao();

	$clOuvidoriaAtendimentoRetorno->ov20_ouvidoriaatendimento = $oPost->iCodAtendimento;
	$clOuvidoriaAtendimentoRetorno->ov20_dataretorno          = date('Y-m-d',db_getsession('DB_datausu'));
	$clOuvidoriaAtendimentoRetorno->ov20_horaretorno          = db_hora();
	$clOuvidoriaAtendimentoRetorno->ov20_informa              = utf8_decode($oPost->sInformacao);
	$clOuvidoriaAtendimentoRetorno->ov20_resposta             = utf8_decode($oPost->sResposta);
	$clOuvidoriaAtendimentoRetorno->ov20_tiporetorno          = $oPost->iTipoRetorno;
	$clOuvidoriaAtendimentoRetorno->ov20_confirma             = ($oPost->sConfirma=='s'?'true':'false');
	$clOuvidoriaAtendimentoRetorno->incluir(null);

	if ( $clOuvidoriaAtendimentoRetorno->erro_status == 0 ) {
		$lErro    = true;
		$sMsgErro = $clOuvidoriaAtendimentoRetorno->erro_msg;
	}
	
	db_fim_transacao($lErro);
	
	if ( !$lErro ) {
		$sMsgErro = 'Retorno incluído com sucesso!';
	}
	
  $aRetorno = array("lErro"             =>$lErro,
                    "sMsg"              =>urlencode($sMsgErro));

  echo $oJson->encode($aRetorno);
  
} else if ( $oPost->sMethod == 'arquivarProcesso') {

	
	if ( isset($oPost->sMotivo) && trim($oPost->sMotivo) != '' ) {
		$sMotivo = $oPost->sMotivo;
	} else {
		$sMotivo = ' ';
	}
	
  db_inicio_transacao();

  try {
  	$oProcessoOuvidoria->arquivarProcesso($oPost->iCodProcesso,$sMotivo);
  } catch (Exception $eException) {
    $lErro    = true;
    $sMsgErro = $eException->getMessage();  	
  }
  
  db_fim_transacao($lErro);
  
  if ( !$lErro ) {
    $sMsgErro = 'Processo arquivado com sucesso!';
  }
  
  $aRetorno = array("lErro"             =>$lErro,
                    "sMsg"              =>urlencode($sMsgErro));
  
  echo $oJson->encode($aRetorno);
  
}

  
?>