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
include("classes/db_mer_desper_und_classe.php");
include("dbforms/db_funcoes.php");
$me12_d_data_dia  = substr($me12_d_data,8,2);
$me12_d_data_mes  = substr($me12_d_data,5,2);
$me12_d_data_ano  = substr($me12_d_data,0,4);
db_postmemory($HTTP_POST_VARS);
$clmer_desper_und = new cl_mer_desper_und;
$db_opcao         = 1;
$db_botao         = true;
if (isset($opcao)) {
	
  $result1 = $clmer_desper_und->sql_record($clmer_desper_und->sql_query("","*","","me23_i_codigo=".$me23_i_codigo));
  if ($clmer_desper_und->numrows>0) {
    db_fieldsmemory($result1,0);
  }
  if ( $opcao == "alterar") {
    $db_opcao = 2;
  } else {
  	
    if ($opcao=="excluir" || isset($db_opcao) && $db_opcao==3) {
      $db_opcao = 3;
    } else {
    	
      if (isset($alterar)) {
        $db_opcao = 2;
      }
    }
  }
}

if (isset($incluir)) {
	
  db_inicio_transacao();
  $clmer_desper_und->incluir($me23_i_codigo);
  db_fim_transacao();
  
}

if (isset($alterar)) {
	
  $db_opcao = 2;
  db_inicio_transacao();
  $clmer_desper_und->alterar($me23_i_codigo);
  db_fim_transacao();
  
}

if (isset($excluir)) {
	
  $db_opcao = 3;
  db_inicio_transacao();
  $clmer_desper_und->excluir($me23_i_codigo);
  db_fim_transacao();
  
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Unidades de Desperdício</b></legend>
    <?include("forms/db_frmmer_desper_und.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","me23_f_quant",true,1,"me23_f_quant",true);
</script>
<?
if (isset($incluir)||isset($alterar)||isset($excluir)){
	
  if ($clmer_desper_und->erro_status=="0"){
  	
    $clmer_desper_und->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clmer_desper_und->erro_campo!="") {
  	
      echo "<script> document.form1.".$clmer_desper_und->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmer_desper_und->erro_campo.".focus();</script>";
   
    }
  } else {
 	
    $clmer_desper_und->erro(true,false);
    $me12_d_data = substr($me12_d_data,6,4)."-".substr($me12_d_data,3,2)."-".substr($me12_d_data,0,2);
    db_redireciona("mer1_mer_desper_und001.php?me23_i_desperdicio=$me23_i_desperdicio&
                   me01_c_nome=$me01_c_nome&me12_d_data=$me12_d_data&me03_c_tipo=$me03_c_tipo"
                  );
                 
  }
}
?>