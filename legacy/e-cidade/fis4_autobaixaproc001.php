<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("classes/db_autotipo_classe.php");
require_once("classes/db_autotipobaixaproc_classe.php");
require_once("classes/db_autotipobaixaprocproc_classe.php");
require_once("classes/db_autotipobaixa_classe.php");
require_once("classes/db_autonumpre_classe.php");
require_once("classes/db_arrecad_classe.php");
require_once("classes/db_auto_classe.php");
require_once("classes/db_parfiscal_classe.php");
require_once("dbforms/db_funcoes.php");

$clautotipo              = new cl_autotipo;
$clautotipobaixaproc     = new cl_autotipobaixaproc;
$clautotipobaixaprocproc = new cl_autotipobaixaprocproc;
$clautotipobaixa         = new cl_autotipobaixa;
$clautonumpre            = new cl_autonumpre;
$clarrecad               = new cl_arrecad;
$clparfiscal             = new cl_parfiscal;
$clauto                  = new cl_auto;

db_postmemory($HTTP_POST_VARS);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clautotipobaixaprocproc->rotulo->label();

$db_opcao           = 1;
$db_botao           = true;
$lProcProtBaixaAuto = false;

$iInstit  = db_getsession('DB_instit');

$sSqlParFiscal  = $clparfiscal->sql_query($iInstit,"parfiscal.y32_procprotbaixaauto",null,"");
$rsSqlParFiscal = $clparfiscal->sql_record($sSqlParFiscal);
if ($clparfiscal->numrows > 0) {
	
	$oParFiscal = db_utils::fieldsMemory($rsSqlParFiscal,0);
	if (isset($oParFiscal->y32_procprotbaixaauto) && $oParFiscal->y32_procprotbaixaauto == 1) {
		$lProcProtBaixaAuto = true;
	}
}

if (isset($baixar)) {
	
  
  db_inicio_transacao();
  
  $sqlerro      = false;
  $dtbaixa      = $q07_databx_ano.'-'.$q07_databx_mes.'-'.$q07_databx_dia;
  $data         = date('Y-m-d',db_getsession('DB_datausu'));
  $usu          = db_getsession('DB_id_usuario');
  
  $sWhereAutoTipo  = "y87_dtbaixa   = '{$dtbaixa}'    and y87_data    = '{$data}' and y87_usuario = {$usu} and ";
  $sWhereAutoTipo .= "y59_codauto = {$y50_codauto}                                                             ";
  
  if (isset($y114_processo) && !empty($y114_processo)) {
    $sWhereAutoTipo .= " and autotipobaixaprocproc.y114_processo = {$y114_processo} ";  	
  }
  
  $sSqlAutoTipo    = $clautotipo->sql_query_baixa(null,"*",null,$sWhereAutoTipo);
  $result_baixa    = $clautotipo->sql_record($sSqlAutoTipo);
  
  if ($clautotipo->numrows == 0) {
  	
    $clautotipobaixaproc->y87_dtbaixa  = $dtbaixa;
    $clautotipobaixaproc->y87_data     = date('Y-m-d',db_getsession('DB_datausu'));
    $clautotipobaixaproc->y87_hora     = db_hora();
    $clautotipobaixaproc->y87_usuario  = db_getsession('DB_id_usuario');
    $clautotipobaixaproc->incluir(null);
    $erro = $clautotipobaixaproc->erro_msg;
    if ($clautotipobaixaproc->erro_status == 0) {
       $sqlerro=true;
    }
    
    $codigo = $clautotipobaixaproc->y87_baixaproc;
  } else {
  	
    db_fieldsmemory($result_baixa,0);
    $codigo = $y87_baixaproc;
    $erro   = 'Inclusão efetuada com Sucesso!!';
  }
  
  if ($sqlerro == false) {
  	
	  if (isset($lProcProtBaixaAuto) && $lProcProtBaixaAuto == true || isset($y114_processo) && !empty($y114_processo)) {
	    
	    $clautotipobaixaprocproc->y114_baixaproc = $clautotipobaixaproc->y87_baixaproc;
	    $clautotipobaixaprocproc->y114_processo  = $y114_processo;
	    $clautotipobaixaprocproc->incluir(null);
	    if ($clautotipobaixaprocproc->erro_status == 0) {
	    	
	       $sqlerro = true;
	       $erro    = $clautotipobaixaprocproc->erro_msg;
	    }
	  }
  }
  
  if ($sqlerro == false) {
  	
    $cods = split('#',$chaves);
    for($x = 0; $x < count($cods); $x++) {
    	
      if ($sqlerro == false) {
      	
	      $clautotipobaixa->y86_codbaixaproc = $codigo;
	      $clautotipobaixa->incluir($cods[$x]);
	      if ($clautotipobaixa->erro_status == 0) {
	      	
	        $sqlerro = true;
	        $erro    = $clautotipobaixa->erro_msg;
	      }
      }
    }
    
    $sWhereAutoNumpre = "y17_codauto = {$y50_codauto}";
    $result_numpre    = $clautonumpre->sql_record($clautonumpre->sql_query_file(null,"*",null,$sWhereAutoNumpre));
    if ($clautonumpre->numrows > 0) {
    	
    	$numrows_numpre = $clautonumpre->numrows;
    	for($w = 0; $w < $numrows; $w++) {
    		
    		db_fieldsmemory($result_numpre,$w);
    		$clarrecad->excluir(null,"k00_numpre = {$y17_numpre}");
    		if ($clarrecad->erro_status == 0) {
    			
    			$sqlerro=true;
    			$erro=$clarrecad->erro_msg;
    			break;
    		}
    	}
    	
    	if ($sqlerro == false) {
    		
    		$clautonumpre->excluir(null,"y17_codauto = {$y50_codauto}");
    		if ($clautonumpre->erro_status == 0) {
    			
	  			$sqlerro=true;
	  			$erro=$clautonumpre->erro_msg;
			  }
    	}
      
    	$sWhereAutoTipo  = "y59_codauto = {$y50_codauto} and y86_codautotipo is null";
    	$sSqlAutoTipo    = $clautotipo->sql_query_baixa(null,"*",null,$sWhereAutoTipo);
    	$result_baixadas = $clautotipo->sql_record($sSqlAutoTipo);
	    if ($clautotipo->numrows > 0) {
	    	
    	  $result_calc = $clauto->sql_calculo($y50_codauto);
        db_fieldsmemory($result_calc,0);
        $info = $fc_autodeinfracao;	
    	}
    }
    
    db_fim_transacao($sqlerro);
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  >
<table align="center" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
			<?
			  include("forms/db_frmautobaixaproc.php");
			?>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<?
if (isset($baixar)) {
	
  if ($sqlerro == true) { 
  	
    db_msgbox($erro);
    if ($clautotipobaixaproc->erro_campo != "") {
    	
      echo "<script> document.form1.".$clautotipobaixaproc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clautotipobaixaproc->erro_campo.".focus();</script>";
    } 
  } else {
  	 
    if (isset($erro) && !empty($erro)) {
      db_msgbox($erro); 
    }
  }
}
?>
</html>