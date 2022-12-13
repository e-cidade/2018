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
include("classes/db_mer_cardapioitem_classe.php");
include("classes/db_mer_modpreparo_classe.php");
include("classes/db_mer_subitem_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clmer_cardapioitem = new cl_mer_cardapioitem;
$clmer_subitem            = new cl_mer_subitem;
$clmer_modpreparo         = new cl_mer_modpreparo;
$db_opcao           = 1;
$db_botao           = true;
if (isset($incluir)) {
    
  db_inicio_transacao();
  $clmer_cardapioitem->incluir($me07_i_codigo);
  db_fim_transacao();
  
}

if (isset($alterar)) {
    
  $db_opcao = 2;
  db_inicio_transacao();
  $clmer_cardapioitem->alterar($me07_i_codigo);
  db_fim_transacao();
  
}
if (isset($excluir)) {
    
  $db_opcao = 3;
  $erro     = false;
  $msg_erro = "";
  $hoje     = date("Y-m-d",db_getsession("DB_datausu"));
  $result   = $clmer_subitem->sql_record(
                                          $clmer_subitem->sql_query("",
                                                                    "me29_i_codigo",
                                                                    "",
                                                                    "me29_i_refeicao = $me07_i_cardapio 
                                                                     AND me29_i_alimentoorig = $me07_i_alimento 
                                                                     AND '$hoje' between me29_d_inicio AND me29_d_fim"
                                                                  )
                                        );
  if ($clmer_subitem->numrows>0) {
    
    $msg_erro                       .= "Ítem não pode ser excluído, " ;
    $msg_erro                       .= " pois tem registro de Substituição de Ítens ainda vigente ";
    $msg_erro                       .= " para este nesta refeição!";
    $clmer_cardapioitem->erro_msg    = $msg_erro;
    $clmer_cardapioitem->erro_status = "0";
    $erro                            = true;
    
  }
  $result = $clmer_modpreparo->sql_record(
                                          $clmer_modpreparo->sql_query("",
                                                                       "me05_i_codigo",
                                                                       "",
                                                                       "me05_i_cardapio = $me07_i_cardapio 
                                                                        AND me05_i_alimento = $me07_i_alimento"
                                                                      )
                                         );
  if ($clmer_modpreparo->numrows>0) {
    
    $msg_erro                       .= "\\n\\nÍtem não pode ser excluído, pois tem registro de Modo de Preparo";
    $msg_erro                       .= " para este nesta refeição!";
    $clmer_cardapioitem->erro_msg    = $msg_erro;
    $clmer_cardapioitem->erro_status = "0";
    $erro = true;
    
  }
  if ($erro==false) {
    
    db_inicio_transacao();
    $clmer_cardapioitem->excluir($me07_i_codigo);
    db_fim_transacao();
    
  }  
}
if ($naopode==1) {
    
  $db_botao = false;
  $opcao_frame = 4;
  
} else {
  $opcao_frame = 1;
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
   <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <br>
    <center>
     <fieldset style="width:95%"><legend><b>Inclusão de Itens da Refeição</b></legend>
	  <?include("forms/db_frmmer_cardapioitem.php");?>
	 </fieldset>
    </center>
   </td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1", "me07_i_alimento", true, 1, "me07_i_alimento", true);
</script>
<?
if (isset($incluir) || isset($alterar) || isset($excluir)) {

  if ($clmer_cardapioitem->erro_status == "0") {

    $clmer_cardapioitem->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clmer_cardapioitem->erro_campo != "") {

      echo "<script> document.form1.".$clmer_cardapioitem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmer_cardapioitem->erro_campo.".focus();</script>";
    }
    
  } else {
  	
    $clmer_cardapioitem->erro(true,false);
    db_redireciona("mer1_mer_cardapioitem001.php?me07_i_cardapio=$me07_i_cardapio&me01_c_nome=$me01_c_nome&naopode=$naopode");
     
  }
}
if (isset($cancelar)) {
  db_redireciona("mer1_mer_cardapioitem001.php?me07_i_cardapio=$me07_i_cardapio&me01_c_nome=$me01_c_nome&naopode=$naopode");	
}
?>