<?
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

require_once("libs/db_stdlib.php");
require_once('libs/db_utils.php');
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/verticalTab.widget.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_protprocesso_classe.php");
require_once("classes/db_ouvidoriaatendimentolocal_classe.php");
require_once("classes/db_arqproc_classe.php");
require_once("classes/db_ouvidoriaatendimento_classe.php");

$oGet = db_utils::postMemory($_GET);

$clProtProcesso     = new cl_protprocesso();
$oDaoOuvAtendLocal  = new cl_ouvidoriaatendimentolocal();
$oDaoOuvidoriaAtend = new cl_ouvidoriaatendimento();
$lPermissaoImpressao = db_permissaomenu(db_getsession("DB_anousu"),398, 4754);

/**
 * Busca os dados do processo 
 */
$sCamposAtendimentoLocal  = "ov01_sequencial,";
$sCamposAtendimentoLocal .= "p51_codigo ||' - '|| p51_descr   as tipo_processo,";
$sCamposAtendimentoLocal .= "ov01_numero ||' / '|| ov01_anousu as numero_atendimento,";
$sCamposAtendimentoLocal .= "ov01_solicitacao,";
$sCamposAtendimentoLocal .= "ov01_executado,";
$sCamposAtendimentoLocal .= "nome as nome_ouvidor,";
$sCamposAtendimentoLocal .= "ov01_dataatend as data_atendimento,";
$sCamposAtendimentoLocal .= "ov01_horaatend as hora_atendimento,";
$sCamposAtendimentoLocal .= "ov01_requerente as nome_requerente,";
$sCamposAtendimentoLocal .= "ov25_sequencial ||' - '|| ov25_descricao as local,";
$sCamposAtendimentoLocal .= "ov01_depart ||' - '|| descrdepto as departamento_inicial,";
$sCamposAtendimentoLocal .= "(select procarquiv.p67_dtarq ";
$sCamposAtendimentoLocal .= "   from arqproc ";
$sCamposAtendimentoLocal .= "        inner join procarquiv on p68_codarquiv = p67_codarquiv ";
$sCamposAtendimentoLocal .= " where  p68_codproc = p58_codproc) as data_arquivamento, ";
$sCamposAtendimentoLocal .= "p58_codproc as numero_processo";

/**
 * Se nao for passado codigo atendimento por get pesquisa pelo codigo do processo
 * - consultas >> atendimento : pesquisa por atendimento
 * - consultas >> ouvidoria   : pesquisa pelo codigo do processo
 */	 
if ( !empty($oGet->iAtendimento) ) {
	$sWhereAtendimentoLocal  = "ouvidoriaatendimento.ov01_sequencial = {$oGet->iAtendimento}";
} else {
	$sWhereAtendimentoLocal  = "p58_codproc = {$oGet->iCodProcesso}";
}  

$sSqlOuvAtend      = $oDaoOuvidoriaAtend->sql_query_atendimento_processo(null, $sCamposAtendimentoLocal, null, $sWhereAtendimentoLocal);
$rsOuviAtendimento = $oDaoOuvidoriaAtend->sql_record($sSqlOuvAtend);
$oAtendimento      = db_utils::fieldsMemory($rsOuviAtendimento, 0);

/**
 * Se nao for passado codigo atendimento por get pega o atendimento encontrado no sql dos dados do processo  
 */
if ( empty($oGet->iAtendimento) ) {
	$iAtendimento = $oAtendimento->ov01_sequencial;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<? 
  db_app::load('strings.js');
  db_app::load('scripts.js');
  db_app::load('prototype.js');
  db_app::load('estilos.css');
  db_app::load('grid.style.css');
  db_app::load('tab.style.css');
?>
<style>
.valores {background-color:#FFFFFF}
.acoesExecutadas {background-color:#FFFFFF; min-height: 167px;}
</style>
</head>
<body bgcolor="#CCCCCC">
<table width="100%" border="0">
	<tr>
	  <td style="width: 40%;" valign="top">
    	<fieldset>
       <legend>
         <b>Dados do Atendimento</b>
       </legend>
         <table border="0" width="100%">
           <tr>
             <td align="left">
             	 <b>Atendimento:</b>
             </td>
             <td class="valores" id ='atendimento'>
               <?=$oAtendimento->numero_atendimento;?>
             </td> 
             <td align="right">
               <b>Processo:</b>
             </td>
             <td class="valores" id ='processo'>
               <?=$oAtendimento->numero_processo;?>
             </td>
           </tr>
           <tr>
             <td>
               <b>Data do Processo:</b>
             </td>
             <td class="valores">
               <?=db_formatar($oAtendimento->data_atendimento,'d');?>
             </td>
             <td align="right">
               <b>Hora Inclusão:</b>
             </td>
             <td  class="valores">
               <?=$oAtendimento->hora_atendimento;?>
             </td>             
           </tr>
           <tr>
             <td><b>Ouvidor:</b></td>
             <td class="valores" colspan="3">
               <?=$oAtendimento->nome_ouvidor;?>
             </td>
           </tr>
           <tr>
             <td>
               <b>Departamento Inicial:</b>
             </td>
             <td colspan="3" class="valores">
               <?=$oAtendimento->departamento_inicial;?>
             </td>
           </tr>            
           <tr>
             <td>
               <b>Tipo de Processo:</b>
             </td>
             <td colspan="3" class="valores">
               <?=$oAtendimento->tipo_processo;?>
             </td>
           </tr>
           <tr>
             <td>
               <b>Requerente:</b>
             </td>
             <td colspan="3" class="valores">
               <?=utf8_decode($oAtendimento->nome_requerente);?>
             </td>
           </tr>                       
           <tr>
             <td>
               <b>Local:</b>
             </td>
             <td colspan="3" class="valores">
               <?=$oAtendimento->local;?>
             </td>
           </tr>             
           <tr>
             <td>
               <b>Data de arquivamento:</b>
             </td>
             <td colspan="3" class="valores">
               <?=db_formatar($oAtendimento->data_arquivamento, 'd');?>
             </td>
           </tr>           
         </table>   
      </fieldset>
	  </td>
	  <td style="width: 30%;">
	    <fieldset>
	      <legend><b>Solicitação</b></legend>
	      <div class="acoesExecutadas">
	        <?=$oAtendimento->ov01_solicitacao;?>
	      </div>
	    </fieldset>
	  </td>
	  <td style="width: 30%;">
	    <fieldset>
	      <legend><b>Executado</b></legend>
	      <div class="acoesExecutadas">
	        <?=$oAtendimento->ov01_executado;?>
	      </div>
	    </fieldset>
	  </td>
	</tr>
</table>
 <fieldset>
  <legend>
    <b>Detalhamento</b>
  </legend>
	<?
		$oTabDetalhes = new verticalTab("detalhesProcesso",300);
		
		/*
		 * Somente vai habilitar as abas se existir processo vinculado ao atendimento
		 */
		$lHabilitaAba = false;
		$iNumeroProcesso = 0;
		
		if ($oAtendimento->numero_processo != "") {
		  $lHabilitaAba = true;
		  $iNumeroProcesso = $oAtendimento->numero_processo;
		}
		
  	$sQuery       = "?iCodProcesso={$iNumeroProcesso}";
		$sParametros  = "&iProcesso={$oAtendimento->numero_processo}&iAtendimento={$iAtendimento}";
		$sURL         = "ouv1_consultas.php?{$sParametros}";
		
		$oTabDetalhes->add("atendVic" , "Atendimentos Vinculados" ,"func_detalheatendimentoouvidoria.php{$sQuery}",         $lHabilitaAba);
		$oTabDetalhes->add("despachos", "Despachos"               ,"func_detalhedespachosouvidoria.php{$sQuery}",           $lHabilitaAba);
		$oTabDetalhes->add("retornos" , "Retornos Efetuados"      ,"func_detalheretornosouvidoria.php{$sQuery}",            $lHabilitaAba);
		$oTabDetalhes->add("atendReq" , "Informações Requerente"  ,"func_informacoesrequerente.php{$sQuery}{$sParametros}", $lHabilitaAba);
		$oTabDetalhes->add("impressao", "Imprimir Consulta",$sURL, $lHabilitaAba);
		$oTabDetalhes->show();
	?>
</fieldset>
</form>
</body>
</html>
<script>
  
  function js_imprimir(iAtendimento) {

    var sUrl  = 'ouv1_reldetalhesprocesso002.php?iAtendimento='+iAtendimento;
        sUrl += '&iProcesso='+<?php echo $oAtendimento->numero_processo; ?>;
    js_OpenJanelaIframe('top.corpo','db_iframe_detalhesProc',sUrl,'Imprimir',false);
  }
  
</script>