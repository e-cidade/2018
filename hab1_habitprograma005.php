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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

require_once("libs/db_utils.php");
require_once("classes/db_habitprograma_classe.php");
require_once("classes/db_habitprogramalote_classe.php");
require_once("classes/db_habitprogramalistacompra_classe.php");
require_once("classes/db_habitprogramaconcedente_classe.php");

$clHabitPrograma            = new cl_habitprograma();
$clHabitProgramaLote        = new cl_habitprogramalote();
$clHabitProgramaConcedente  = new cl_habitprogramaconcedente();
$clHabitProgramaListaCompra = new cl_habitprogramalistacompra();

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$db_opcao = 22;
$db_botao = false;
$lSqlErro = false;

if(isset($oPost->alterar)){
  
  db_inicio_transacao();
  
  if ($oPost->ht01_controleqtd == 2) {
  	$ht01_qtdbenef = $oPost->ht01_qtdbenef;
  } else {
  	$ht01_qtdbenef = '';
  }
  
  $clHabitPrograma->ht01_sequencial                = $oPost->ht01_sequencial;
  $clHabitPrograma->ht01_descrcontrato             = $oPost->ht01_descrcontrato;
  $clHabitPrograma->ht01_descricao                 = $oPost->ht01_descricao;
  $clHabitPrograma->ht01_diapadraopagamento        = $oPost->ht01_diapadraopagamento;
  $clHabitPrograma->ht01_habitgrupoprograma        = $oPost->ht01_habitgrupoprograma;
  $clHabitPrograma->ht01_lei                       = $oPost->ht01_lei;
  $clHabitPrograma->ht01_obs                       = $oPost->ht01_obs;
  $clHabitPrograma->ht01_controleqtd               = $oPost->ht01_controleqtd;
  $clHabitPrograma->ht01_controlemultpartcandidato = $oPost->ht01_controlemultpartcandidato;
  $clHabitPrograma->ht01_qtdparcpagamento          = $oPost->ht01_qtdparcpagamento;
  $clHabitPrograma->ht01_receitapadraopagamento    = $oPost->ht01_receitapadraopagamento;
  $clHabitPrograma->ht01_exigeassconcedente        = $oPost->ht01_exigeassconcedente;
  $clHabitPrograma->ht01_exigevalcpf               = $oPost->ht01_exigevalcpf;
  $clHabitPrograma->ht01_workflow                  = $oPost->ht01_workflow;
  $clHabitPrograma->ht01_validadeini               = implode("-",array_reverse(explode("/",$oPost->ht01_validadeini)));
  $clHabitPrograma->ht01_validadefim               = implode("-",array_reverse(explode("/",$oPost->ht01_validadefim)));
  $clHabitPrograma->ht01_qtdbenef                  = $ht01_qtdbenef;

  $clHabitPrograma->alterar($oPost->ht01_sequencial);
  
  if( $clHabitPrograma->erro_status == 0 ){
    $lSqlErro = true;
  } 

  $sMsgErro = $clHabitPrograma->erro_msg; 
  
  $clHabitProgramaConcedente->excluir(null," ht19_habitprograma = {$clHabitPrograma->ht01_sequencial}");

  if ($clHabitProgramaConcedente->erro_status == '0') {
    $lSqlErro = true;
    $sMsgErro = $clHabitProgramaConcedente->erro_msg;
  }
  
 	if (trim($oPost->ht19_numcgm) != '' && !$lSqlErro) {
  		
 		$clHabitProgramaConcedente->ht19_habitprograma = $clHabitPrograma->ht01_sequencial;
 		$clHabitProgramaConcedente->ht19_numcgm        = $oPost->ht19_numcgm;
 		$clHabitProgramaConcedente->incluir(null);

 		if ($clHabitProgramaConcedente->erro_status == '0') {
 			$lSqlErro = true;
 		}
 		
 		$sMsgErro = $clHabitProgramaConcedente->erro_msg;
 	}
  		
  if ( !in_array($oPost->ht01_habitgrupoprograma,array(1,2,3))) {
  	
  	$clHabitProgramaLote->excluir(null," ht05_habitprograma = {$clHabitPrograma->ht01_sequencial}");
  	
  	if ($clHabitProgramaLote->erro_status == 0) {
  		$lSqlErro = true;
  		$sMsgErro = $clHabitProgramaLote->erro_msg;
  	}
  }
  
  db_fim_transacao($lSqlErro);
  
  $db_opcao = 2;
  $db_botao = true;
   
} else if(isset($oGet->chavepesquisa)) {
  $db_opcao = 2;
  $db_botao = true;
  $result   = $clHabitPrograma->sql_record($clHabitPrograma->sql_query($oGet->chavepesquisa)); 
  db_fieldsmemory($result,0);
  
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table align="center">
  <tr> 
    <td> 
			<?
			  include("forms/db_frmhabitprograma.php");
			?>
	  </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($oPost->alterar)){
  
	if ($lSqlErro) {
    
		db_msgbox($sMsgErro);
    
    if ($clHabitPrograma->erro_campo != "") {
    	
      echo "<script> document.form1.".$clHabitPrograma->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clHabitPrograma->erro_campo.".focus();</script>";
    }
  } else {
  	
    db_msgbox($sMsgErro);
    
    $sHtml  = " <script> ";
    $sHtml .= "   function js_db_libera(){";
    
    $sHtml .= "     parent.document.formaba.habitprogramalistacompra.disabled=false;";
    $sHtml .= "     top.corpo.iframe_habitprogramalistacompra.location.href='hab1_habitprogramalistacompra001.php?ht17_habitprograma=".@$clHabitPrograma->ht01_sequencial."';";
    $sHtml .= "     parent.mo_camada('habitprogramalistacompra');";
    
//    if ( in_array( $clHabitPrograma->ht01_habitgrupoprograma,array(1,2,3)) ) {
//      $sHtml .= "   parent.document.formaba.habitprogramalote.disabled=false;";
//      $sHtml .= "   top.corpo.iframe_habitprogramalote.location.href='hab1_habitprogramalote001.php?ht05_habitprograma=".@$clHabitPrograma->ht01_sequencial."';";
//    } else {
//      $sHtml .= "   parent.document.formaba.habitprogramalote.disabled=true;";
//    }
         
    $sHtml .= "   } ";
    $sHtml .= "   js_db_libera();";
    $sHtml .= "</script> ";
    
    echo $sHtml;
    
  }
  
}

if (isset($oGet->chavepesquisa)) {
 
	$sHtml  = " <script> ";
  $sHtml .= "   function js_db_libera(){";

  $sHtml .= "     parent.document.formaba.habitprogramalistacompra.disabled=false;";
  $sHtml .= "     top.corpo.iframe_habitprogramalistacompra.location.href='hab1_habitprogramalistacompra001.php?ht17_habitprograma=".@$ht01_sequencial."';";
  
  if ( isset($oGet->liberaaba)) {
    $sHtml .= "   parent.mo_camada('habitprogramalistacompra');";
  }
  
//  if ( in_array( $ht01_habitgrupoprograma,array(1,2,3)) ) {
//	  $sHtml .= "   parent.document.formaba.habitprogramalote.disabled=false;";
//	  $sHtml .= "   top.corpo.iframe_habitprogramalote.location.href='hab1_habitprogramalote001.php?ht05_habitprograma=".@$ht01_sequencial."';";
//  } else {
//    $sHtml .= "   parent.document.formaba.habitprogramalote.disabled=true;";
//  }
     
  $sHtml .= "   } ";
  $sHtml .= "   js_db_libera();";
  $sHtml .= "</script> ";
    
  echo $sHtml;
  
}

if ($db_opcao==22||$db_opcao==33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>