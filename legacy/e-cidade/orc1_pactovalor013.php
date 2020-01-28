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
include("libs/db_utils.php");
include("libs/db_usuariosonline.php");
include("classes/db_pactovalor_classe.php");
include("classes/db_pactovalorsaldo_classe.php");
include("classes/db_pactovalormov_classe.php");
include("classes/db_pactovalormovempempitem_classe.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clpactovalor              = new cl_pactovalor();
$clpactovalorsaldo         = new cl_pactovalorsaldo();
$clpactovalormov           = new cl_pactovalormov();
$clpactovalormovempempitem = new cl_pactovalormovempempitem();

$db_botao = false;
$db_opcao = 33;

if(isset($excluir)){
	
	$lSqlErro = false;
	$sMsgErro = "";
	
  db_inicio_transacao();
  
  $db_opcao = 3;
  $rsConsultaItemEmp = $clpactovalormovempempitem->sql_record($clpactovalormovempempitem->sql_query(null,"*",null," o88_pactovalor = {$o87_sequencial} "));
  $iLinhasItemEmp    = $clpactovalormovempempitem->numrows; 
  
  if ( $iLinhasItemEmp > 0 ) {
  	
  	for ( $iInd=0; $iInd < $iLinhasItemEmp; $iInd++) {
  		
  		$oItem = db_utils::fieldsMemory($rsConsultaItemEmp,$iInd);
  		
	  	$clpactovalormovempempitem->excluir($oItem->o105_sequencial);
	  	
	  	if ( $clpactovalormovempempitem->erro_status == 0 ) {
	  		$lSqlErro = true;
	  	}
	  	
  	  $sMsgErro = $clpactovalormovempempitem->erro_msg;
  	  
  	}
  	
  	
  	if ( !$lSqlErro ) {
  		
	    $clpactovalormov->excluir(null," o88_pactovalor = {$o87_sequencial} ");
	    
	    if ( $clpactovalormov->erro_status == 0 ) {
	      $lSqlErro = true;
	    }
	    
	    $sMsgErro = $clpactovalormov->erro_msg;  		
  		
  	}
  	
  	
  }

  if ( !$lSqlErro ) {
    
    $clpactovalorsaldo->excluir(null," o103_pactovalor = $o87_sequencial");
    
    if ( $clpactovalorsaldo->erro_status == 0 ) {
      $lSqlErro = true;
    }
      
    $sMsgErro = $clpactovalorsaldo->erro_msg;    
    
  }  
  
  if ( !$lSqlErro ) {
  	
    $clpactovalor->excluir($o87_sequencial);
    
    if ( $clpactovalor->erro_status == 0 ) {
      $lSqlErro = true;
    }
      
    $sMsgErro = $clpactovalor->erro_msg;    
    
  }
  
  db_fim_transacao($lSqlErro);
  
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clpactovalor->sql_record($clpactovalor->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
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
<table align="center" style="padding-top:25px;" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td> 
    <center>
			<?
			  include("forms/db_frmpactovalor.php");
			?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($excluir)){
  if($lSqlErro){
    db_msgbox($sMsgErro);
  }else{
    $clpactovalor->erro(true,true);
  }
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>