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


/**
 * Cadastro de ordem de compras por empenho
 * @package compras
 * @author Iuri Guntchnigg Revisão$Author: dbiuri $
 * @version $Revision: 1.20 $
*/
//echo ($HTTP_SERVER_VARS['QUERY_STRING']);exit;
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_libdocumento.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_matparam_classe.php");
require_once ("classes/db_pcparam_classe.php");
require_once ("classes/db_empempitem_classe.php");
require_once ("classes/db_cgm_classe.php");
require_once ("classes/db_matordem_classe.php");
require_once ("classes/db_matordemitem_classe.php");
require_once ("classes/db_empempenho_classe.php");
require_once ("classes/db_matordemmail_classe.php");
require_once ("classes/db_empparametro_classe.php");
require_once ("classes/db_empempenholiberado_classe.php");
require_once ('std/DBNumber.php');

$clmatparam			      = new cl_matparam;
$clpcparam			      = new cl_pcparam;
$clmatordem			      = new cl_matordem;
$clmatordemitem       = new cl_matordemitem;
$clempempenho		      = new cl_empempenho;
$clempempitem		      = new cl_empempitem;
$clempparametro       = new cl_empparametro;
$clempempenholiberado = new cl_empempenholiberado;
$clcgm					      = new cl_cgm;
$clmatordemmail       = new cl_matordemmail;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$oGet      = db_utils::Postmemory($_GET);
$oPost     = db_utils::Postmemory($_POST);

$dbopcao   = 1;
$sDisable  = '';
$lBloquear = false;
$result    = $clempparametro->sql_record($clempparametro->sql_query(db_getsession("DB_anousu")));

if($result != false && $clempparametro->numrows > 0){
  $oParam = db_utils::fieldsMemory($result,0);
}

/*
 * Desabilita a pesquisa caso os parametros tiver como nao
 */


if ($oParam->e30_liberaempenho == 't') {
	
	if (isset($oGet->e60_numemp) && !empty($oGet->e60_numemp)) {
		$sCampos = "empempenholiberado.*";
    $sWhere  = "e22_numemp = {$oGet->e60_numemp} ";
    $sWhere .= "and exists(select 1 from empempenholiberado where e22_numemp= e60_numemp)";
    $sWhere .= "and exists(select 1 from desdobramentosliberadosordemcompra where pc33_codele = e64_codele ";
    $sWhere .= "                                                              and pc33_anousu = ".db_getsession("DB_anousu").")";
     
    $sSqlEmpenhosLiberados  = $clempempenholiberado->sql_query(null,$sCampos,null,$sWhere);
    $rsSqlEmpenhosLiberados = $clempempenholiberado->sql_record($sSqlEmpenhosLiberados);

    if ($clempempenholiberado->numrows == 0) {
      $dbopcao   = 3;
      $sDisable  = "disabled";
      $lBloquear = true;
    }
	}
}

if (isset($oPost->incluir)){
  
  db_inicio_transacao();
  $sqlerro     = false;
  $valor_total = 0;
  for ($i = 0; $i < count($oPost->itensOrdem); $i++) {
    
    $sValor       = "valor{$oPost->itensOrdem[$i]}";
    $valor_total += DBNumber::round($oPost->$sValor, 2);
  }
  $m51_data="$oPost->m51_data_ano-$oPost->m51_data_mes-$oPost->m51_data_dia";
  $clmatordem->m51_data       = $m51_data; 
  $clmatordem->m51_depto      = @$oPost->coddepto;
  $clmatordem->m51_numcgm     = $oPost->e60_numcgm;
  $clmatordem->m51_obs        = $oPost->m51_obs;
  
  $nValorTotal                = DBNumber::round($valor_total,2);
  
  $clmatordem->m51_valortotal = "{$nValorTotal}";
  
  
  $clmatordem->m51_tipo       = 1;
  $clmatordem->incluir(null);
  if($clmatordem->erro_status==0){
    $sqlerro=true;
  }
  
  $erro_msg = $clmatordem->erro_msg;
  $codigo   = $clmatordem->m51_codordem;
  if ($sqlerro == false){
    
  	if (isset($oPost->manda_mail) && $oPost->manda_mail != "") {
  	  
  		$clmatordemmail->m55_codordem = $codigo;
	  	$clmatordemmail->m55_email    = $oPost->z01_email;
	  	$clmatordemmail->incluir(null);
	 	  if ($clmatordemmail->erro_status==0) {
	 	    
			  $sqlerro=true;
			  $erro_msg = $clmatordemmail->erro_msg;
        
		  }
    }
  }
  
  /*
   * incluimos os itens na matordem item 
   * a propriedade itensOrdem do post, e os itens que o usuario marcou .
   * logo devemos procurar pela quantidade e valor correspondente do item;
   */
  for ($i=0; $i < count($oPost->itensOrdem); $i++) {
   
    $sSqlEmp    = "select e62_numemp, e62_sequen from empempitem where e62_sequencial = {$oPost->itensOrdem[$i]}";
    $rsEmpItem  = $clempempitem->sql_record($sSqlEmp);
    if ($clempempitem->numrows == 1) {
      $oEmpItem = db_utils::fieldsMemory($rsEmpItem, 0);
    } else {
     
      $sqlerro  = true;
      $erro_msg = "Item {$oPost->itensOrdem[$i]} não encontrado no Empenho.Operacao cancelada.";
      break;   
    }
    if (!$sqlerro) { 

      /**
          validação para nao incluir ordem de compra com data de inclusao inverior a emissao do empenho
       */
      $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oEmpItem->e62_numemp);
      
      $dEmissaoEmpenho = strtotime($oEmpenhoFinanceiro->getDataEmissao());
      $dInclusaoOrdem  = strtotime($m51_data);
      
      if ($dInclusaoOrdem < $dEmissaoEmpenho) {
        
        $sqlerro  = true;
        $erro_msg = "Data da entrada da ordem é menor que a data do empenho";
      }
      
      $sValor         = "valor{$oPost->itensOrdem[$i]}";
      $sQuantidade    = "quantidade{$oPost->itensOrdem[$i]}";
      $nValorUnitario = "vlrunitario{$oPost->itensOrdem[$i]}";
      $clmatordemitem->m52_codordem = $codigo;
      $clmatordemitem->m52_numemp   = $oEmpItem->e62_numemp;
      $clmatordemitem->m52_sequen   = $oEmpItem->e62_sequen;
      $clmatordemitem->m52_quant    = $oPost->$sQuantidade;
      $nValor   = DBNumber::round($oPost->$sValor,2);
      $clmatordemitem->m52_valor    = "{$nValor}";
      $clmatordemitem->m52_vlruni   = "".round($oPost->$nValorUnitario, $oParam->e30_numdec)."";      
      $clmatordemitem->incluir(null);
      if ($clmatordemitem->erro_status == 0) {
     
        $sqlerro=true;
        break;
       
      }
    }
  }
  //$sqlerro = true;
  db_fim_transacao($sqlerro);
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/numbers.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/notaliquidacao.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<table border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td  align="left" valign="top" bgcolor="#CCCCCC">
    <?
    include("forms/db_frmmatordemNota.php");
    ?>
    </td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<?
if (isset($incluir)){
  if($sqlerro == true){ 
    db_msgbox($erro_msg);
    if($clmatordem->erro_campo!=""){
      echo "<script> document.form1.".$clmatordem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmatordem->erro_campo.".focus();</script>";
    } 
   }else{ 
   db_msgbox($erro_msg);
     	echo "
         <script>
           if(confirm('Deseja imprimir a ordem de compra?')){
             jan = window.open('emp2_ordemcompra002.php?cods=$codigo','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
             jan.moveTo(0,0);
	      } 
         </script>
";
  if (isset($manda_mail)&&$manda_mail!="") {
    
  	$headers  = "Content-Type:text/html;";  	  	
		$objteste = new libdocumento(1750);
		$corpo    = $objteste->emiteDocHTML();
  	$mail     = mail($z01_email,"Ordem de Compra Nº $codigo",$corpo,$headers);
  	if ($mail){
  		db_msgbox("E-mail enviado com sucesso!!");  		
  	}else{
  		db_msgbox("Erro ao enviar e-mail!!E-mail não foi enviado!!");
  	}
		
  }
  echo "<script>
	   location.href='emp4_ordemCompra001.php';
         </script>
   "; 
  }
}
if (isset($lBloquear) && $lBloquear == true) {
  db_msgbox("Empenho ({$oGet->e60_numemp}) não liberado para a ordem de compra!");
  db_redireciona('emp4_ordemCompra001.php');
}
?>
</body>
</html>