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
include("classes/db_veicmanutitem_classe.php");
include("classes/db_veicmanutitempcmater_classe.php");
include("classes/db_veicmanut_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clveicmanutitem = new cl_veicmanutitem;
$clveicmanutitempcmater = new cl_veicmanutitempcmater;
$clveicmanut = new cl_veicmanut;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
  /*
$clveicmanutitem->ve63_codigo = $ve63_codigo;
$clveicmanutitem->ve63_veicmanut = $ve63_veicmanut;
$clveicmanutitem->ve63_descr = $ve63_descr;
$clveicmanutitem->ve63_quant = $ve63_quant;
$clveicmanutitem->ve63_vlruni = $ve63_vlruni;
  */
}
if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clveicmanutitem->incluir($ve63_codigo);
    $erro_msg = $clveicmanutitem->erro_msg;
    if($clveicmanutitem->erro_status==0){
      $sqlerro=true;
    }
    if ($sqlerro==false){
    	if (isset($ve64_pcmater)&&$ve64_pcmater){
    		$clveicmanutitempcmater->ve64_veicmanutitem=$clveicmanutitem->ve63_codigo;
    		$clveicmanutitempcmater->incluir(null);    		
    		if($clveicmanutitempcmater->erro_status==0){
    			$erro_msg = $clveicmanutitempcmater->erro_msg;
      			$sqlerro=true;
    		}
    	}
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clveicmanutitem->alterar($ve63_codigo);
    $erro_msg = $clveicmanutitem->erro_msg;
    if($clveicmanutitem->erro_status==0){
      $sqlerro=true;
    }
    if ($sqlerro==false){
    	$result_mat=$clveicmanutitempcmater->sql_record($clveicmanutitempcmater->sql_query_file(null,"ve64_codigo",null,"ve64_veicmanutitem=$ve63_codigo"));    	
    	if (isset($ve64_pcmater)&&$ve64_pcmater){
    		if ($clveicmanutitempcmater->numrows>0){
    			db_fieldsmemory($result_mat,0);
    			$clveicmanutitempcmater->ve64_codigo=$ve64_codigo;
    			$clveicmanutitempcmater->alterar($ve64_codigo);    		
    			if($clveicmanutitempcmater->erro_status==0){
    				$erro_msg = $clveicmanutitempcmater->erro_msg;
      				$sqlerro=true;
    			}
    		}else{
    			$clveicmanutitempcmater->ve64_veicmanutitem=$clveicmanutitem->ve63_codigo;
    			$clveicmanutitempcmater->incluir(null);    		
    			if($clveicmanutitempcmater->erro_status==0){
    				$erro_msg = $clveicmanutitempcmater->erro_msg;
      				$sqlerro=true;
    			}
    		}
    	}else{
    		if ($clveicmanutitempcmater->numrows>0){
    			$clveicmanutitempcmater->excluir(null,"ve64_veicmanutitem=$ve63_codigo");    		
    			if($clveicmanutitempcmater->erro_status==0){
    				$erro_msg = $clveicmanutitempcmater->erro_msg;
      				$sqlerro=true;
    			}
    		}
    	}    	
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();    
    $result_mat=$clveicmanutitempcmater->sql_record($clveicmanutitempcmater->sql_query_file(null,"*",null,"ve64_veicmanutitem=$ve63_codigo"));
    if ($clveicmanutitempcmater->numrows>0){
     	$clveicmanutitempcmater->excluir(null,"ve64_veicmanutitem=$ve63_codigo");    		
    	if($clveicmanutitempcmater->erro_status==0){
    		$erro_msg = $clveicmanutitempcmater->erro_msg;
    		$sqlerro=true;
    	}
    }
    if ($sqlerro==false){
    	$clveicmanutitem->excluir($ve63_codigo);
    	$erro_msg = $clveicmanutitem->erro_msg;
    	if($clveicmanutitem->erro_status==0){
      		$sqlerro=true;
    	}
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $clveicmanutitem->sql_record($clveicmanutitem->sql_query_pcmater($ve63_codigo));
   if($result!=false && $clveicmanutitem->numrows>0){
     db_fieldsmemory($result,0);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmveicmanutitem.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($clveicmanutitem->erro_campo!=""){
        echo "<script> document.form1.".$clveicmanutitem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clveicmanutitem->erro_campo.".focus();</script>";
    }
}
?>