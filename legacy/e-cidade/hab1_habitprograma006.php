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
require_once("classes/db_habitprogramalistacompraitem_classe.php");
require_once("classes/db_habitprogramaconcedente_classe.php");

$clHabitPrograma            = new cl_habitprograma();
$clHabitProgramaLote        = new cl_habitprogramalote();
$clHabitProgramaConcedente  = new cl_habitprogramaconcedente();
$clHabitProgramaListaCompra = new cl_habitprogramalistacompra();
$clHabitProgramaListaCompraItem = new cl_habitprogramalistacompraitem();

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$db_opcao = 33;
$db_botao = false;
$lSqlErro = false;

if(isset($oPost->excluir)){
  
  db_inicio_transacao();
  
  
  $sSqlListaCompra = $clHabitProgramaListaCompra->sql_query_file(null,"*",null,"ht17_habitprograma = {$oPost->ht01_sequencial}");
  $rsListaCompra   = $clHabitProgramaListaCompra->sql_record($sSqlListaCompra);
  $iNumRowsLista   = $clHabitProgramaListaCompra->numrows;
  
  if ( $iNumRowsLista > 0 ) {
  	for ($iInd=0; $iInd < $iNumRowsLista; $iInd++ ) {
  		
  		$oListaCompra = db_utils::fieldsMemory($rsListaCompra,$iInd);
  		
  		$clHabitProgramaListaCompraItem->excluir(null," ht18_habitprogramalistacompra = {$oListaCompra->ht17_sequencial}");
  		
  		if ($clHabitProgramaListaCompraItem->erro_status == 0) {
  			$lSqlErro = true;
  			$sMsgErro = $clHabitProgramaListaCompraItem->erro_msg;
  			break;
  		}
  	}
  	
  	if (!$lSqlErro) {
  		$clHabitProgramaListaCompra->excluir(null," ht17_habitprograma = {$oPost->ht01_sequencial}");
  	}
  	
  }
  
  if (!$lSqlErro) {
  	
	  $clHabitProgramaConcedente->excluir(null," ht19_habitprograma = {$oPost->ht01_sequencial}");
	
	  if ($clHabitProgramaConcedente->erro_status == '0') {
	    $lSqlErro = true;
	    $sMsgErro = $clHabitProgramaConcedente->erro_msg;
	  }
  }

  if (!$lSqlErro) {
  	
	  $clHabitProgramaLote->excluir(null," ht05_habitprograma = {$oPost->ht01_sequencial}");
	   
	  if ($clHabitProgramaLote->erro_status == 0) {
	    $lSqlErro = true;
	    $sMsgErro = $clHabitProgramaLote->erro_msg;
	  }  
  }
  
  if (!$lSqlErro) {
  	
	  $clHabitPrograma->ht01_sequencial = $oPost->ht01_sequencial;
	  $clHabitPrograma->excluir($oPost->ht01_sequencial);
	  
	  if( $clHabitPrograma->erro_status == 0 ){
	    $lSqlErro = true;
	  } 
	
	  $sMsgErro = $clHabitPrograma->erro_msg; 
  }
  
  db_fim_transacao($lSqlErro);
  
  $db_opcao = 3;
  $db_botao = true;
   
} else if(isset($oGet->chavepesquisa)) {
  $db_opcao = 3;
  $db_botao = true;
  $result = $clHabitPrograma->sql_record($clHabitPrograma->sql_query($oGet->chavepesquisa)); 
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
if (isset($oPost->excluir)) {
  
	if($lSqlErro){
		
    db_msgbox($sMsgErro);
    
    if($clHabitPrograma->erro_campo!=""){
      echo "<script> document.form1.".$clHabitPrograma->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clHabitPrograma->erro_campo.".focus();</script>";
    }
  } else {
   db_msgbox($sMsgErro);
   echo "<script>
				   function js_db_tranca(){
				     parent.location.href='hab1_habitprograma003.php';
				   }
				   js_db_tranca();
			   </script>";
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
//    $sHtml .= "   parent.document.formaba.habitprogramalote.disabled=false;";
//    $sHtml .= "   top.corpo.iframe_habitprogramalote.location.href='hab1_habitprogramalote001.php?ht05_habitprograma=".@$ht01_sequencial."';";
//  } else {
//    $sHtml .= "   parent.document.formaba.habitprogramalote.disabled=true;";
//  }
     
  $sHtml .= "   } ";
  $sHtml .= "   js_db_libera();";
  $sHtml .= "</script> ";
    
  echo $sHtml;
  
}

if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>