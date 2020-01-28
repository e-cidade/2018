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
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_mer_infnutricional_classe.php");
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clmer_infnutricional     = new cl_mer_infnutricional;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$db_opcao                 = 1;
$db_botao                 = true;
$db_botao1                = false;
if (isset($opcao)) {
	
  $sCampos  = " matuniditem.m61_descr as m61_descr1, ";
  $sCampos .= " matunidnutri.m61_descr as m61_descr, ";
  $sCampos .= " mer_infnutricional.*, ";
  $sCampos .= " mer_nutriente.me09_c_descr,me08_i_nutriente ";  
  $result1  = $clmer_infnutricional->sql_record(
                                                $clmer_infnutricional->sql_query("",
                                                                                 $sCampos,
                                                                                 "",
                                                                                 "me08_i_codigo = $me08_i_codigo"
                                                                                )
                                              );
  if ($clmer_infnutricional->numrows>0) {
    db_fieldsmemory($result1,0);
  }
  if ( $opcao == "alterar") {
    $sCampos = " me08_f_quant,";
    $sCampos .= " me09_c_descr,me08_i_nutriente ";  
    $result1  = $clmer_infnutricional->sql_record(
                                                  $clmer_infnutricional->sql_query("",
                                                                                   $sCampos,
                                                                                   "",
                                                                                   "me08_i_codigo = $me08_i_codigo"
                                                                                  )
                                                );
    $db_opcao  = 2;
    $db_botao1 = true;
  } else {
  	
    if ( $opcao  ==  "excluir" || isset($db_opcao) && $db_opcao == 3) {
    	
      $db_opcao  = 3;
      $db_botao1 = true;
    } else {
    	
      if (isset($alterar)) {      	
        $db_opcao  = 2;
        $db_botao1 = true;
        
      }
    }
  }
}

if (isset($incluir)) {
	
  $db_opcao = 1;
  db_inicio_transacao();
  $clmer_infnutricional->incluir($me08_i_codigo);
  db_fim_transacao();
  
}

if (isset($alterar)) {
	
  $db_opcao = 2;
  db_inicio_transacao();
  $clmer_infnutricional->alterar($me08_i_codigo);
  db_fim_transacao();
  
}

if (isset($excluir)) {
	
  $db_opcao = 3;
  db_inicio_transacao();
  $clmer_infnutricional->excluir($me08_i_codigo);
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
   <fieldset style="width:95%"><legend><b>Inclusão de Informação Nutricional</b></legend>
    <?
    include("forms/db_frmmer_infnutricional.php");
    ?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1", "me08_i_codmater", true, 1, "me08_i_codmater", true);
</script>
<?
if (isset($incluir) || isset($alterar) || isset($excluir)) {
	
 if ($clmer_infnutricional->erro_status == "0") {
 	
  $clmer_infnutricional->erro(true,false);
  $db_botao = true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  
  if ($clmer_infnutricional->erro_campo != "") {
  	
    echo "<script> document.form1.".$clmer_infnutricional->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clmer_infnutricional->erro_campo.".focus();</script>";
    
  }
 } else {
 	
   $clmer_infnutricional->erro(true,false);
   db_redireciona("mer1_mer_infnutricional001.php?me08_i_alimento=$me08_i_alimento
                   &me35_c_nomealimento=$me35_c_nomealimento"
                 );
   
 }
}
?>