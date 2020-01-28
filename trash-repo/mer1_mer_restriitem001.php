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
include("classes/db_mer_restriitem_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clmer_restriitem = new cl_mer_restriitem;
$db_opcao         = 1;
$db_botao         = true;
$db_opcao2        = 1;
if (isset($opcao)) {
	
  $campos  = " mer_restriitem.*, ";
  $campos .= " mer_alimento.me35_c_nomealimento,alimento.me35_c_nomealimento as me35_c_nomealimento2, ";
  $campos .= " aluno.ed47_v_nome ";
  $result1 = $clmer_restriitem->sql_record($clmer_restriitem->sql_query("",
                                                                        $campos,
                                                                        "",
                                                                        "me25_i_codigo = $me25_i_codigo"
                                                                       )
                                          );
  if ($clmer_restriitem->numrows>0){
    db_fieldsmemory($result1,0);
  }
  if( $opcao == "alterar"){
    $db_opcao = 2;
  } else{
  	
    if ( $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
    	
      $db_opcao  = 3;
      $db_opcao2 = 3;
      
    } else{
    	
      if (isset($alterar)){
      	
        $db_opcao  = 2;
        $db_opcao2 = 3;
        
      }
    }
  }
}
if (isset($incluir)) {
	
  $db_opcao2 = 3;
  db_inicio_transacao();
  $clmer_restriitem->incluir($me25_i_codigo);
  db_fim_transacao();
  
}
if (isset($alterar)) {
	
  $db_opcao = 2;
  db_inicio_transacao();
  $clmer_restriitem->alterar($me25_i_codigo);
  db_fim_transacao();
  
}
if (isset($excluir)) {
	
  $db_opcao = 3;
  db_inicio_transacao();
  $clmer_restriitem->excluir($me25_i_codigo);
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
   <fieldset style="width:95%"><legend><b>Itens com Restri��o</b></legend>
    <?include("forms/db_frmmer_restriitem.php");?>
   </fieldset>
  </td>
 </tr>
</table>
</center>
</body>
</html>
<script>
js_tabulacaoforms("form1","me25_i_alimento",true,1,"me25_i_alimento",true);
</script>
<?
if (isset($incluir) || isset($alterar) || isset($excluir)) {
	
  if ($clmer_restriitem->erro_status=="0") {
  	
    $clmer_restriitem->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($clmer_restriitem->erro_campo!="") {
    	
      echo "<script> document.form1.".$clmer_restriitem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmer_restriitem->erro_campo.".focus();</script>";
      
    }
  } else {
  	
    $clmer_restriitem->erro(true,false);
    db_redireciona("mer1_mer_restriitem001.php?me25_i_restricao=$me25_i_restricao&me24_i_aluno=$me24_i_aluno&ed47_v_nome=$ed47_v_nome");
    
 }
}
?>