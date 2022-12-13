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
include("classes/db_mer_desperdicio_classe.php");
include("classes/db_mer_cardapio_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clmer_desperdicio = new cl_mer_desperdicio;
$clmer_cardapio = new cl_mer_cardapio;
$clmer_desperdicio->rotulo->label("me22_i_codigo");
$clmer_cardapio->rotulo->label("me01_c_nome");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td height="63" align="center" valign="top">
   <table width="35%" border="0" align="center" cellspacing="0">
    <form name="form2" method="post" action="" >
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Tme22_i_codigo?>">
      <?=$Lme22_i_codigo?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("me22_i_codigo",4,$Ime22_i_codigo,true,"text",4,"","chave_me22_i_codigo");?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Tme01_c_nome?>">
      <?=$Lme01_c_nome?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("me01_c_nome",20,$Ime01_c_nome,true,"text",4,"","chave_me01_c_nome");?>
     </td>
    </tr>
    <tr>
     <td colspan="2" align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_mer_desperdicio.hide();">
     </td>
    </tr>
    </form>
   </table>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <?
   $escola = db_getsession("DB_coddepto");
   if (!isset($pesquisa_chave)) {
   	
    if (isset($campos)==false) {
    	
     if (file_exists("funcoes/db_func_mer_desperdicio.php")==true) {
      include("funcoes/db_func_mer_desperdicio.php");
     } else {
       $campos = "mer_desperdicio.*";
     }     
    }
    if (isset($chave_me22_i_codigo) && (trim($chave_me22_i_codigo)!="")) {
      $sql = $clmer_desperdicio->sql_query("",
                                           $campos,
                                           "me12_d_data desc,me03_i_orden",
                                           "ed18_i_codigo = $escola and me22_i_codigo = $chave_me22_i_codigo"
                                          );
    } else if (isset($chave_me01_c_nome) && (trim($chave_me01_c_nome)!="")) {
      $sql = $clmer_desperdicio->sql_query("",
                                           $campos,
                                           "me12_d_data desc,me03_i_orden",
                                           " ed18_i_codigo = $escola AND me01_c_nome like '$chave_me01_c_nome%' "
                                          );
    } else {
      $sql = $clmer_desperdicio->sql_query("",$campos,"me12_d_data desc,me03_i_orden"," ed18_i_codigo = $escola");
    }
    $repassa = array();
    if (isset($chave_me22_i_codigo)) {
      $repassa = array("chave_me22_i_codigo"=>$chave_me22_i_codigo,"chave_me01_c_nome"=>$chave_me01_c_nome);
    }
    db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
    
   } else {
   	
    if ($pesquisa_chave!=null && $pesquisa_chave!="") {
    	
     $result = $clmer_desperdicio->sql_record($clmer_desperdicio->sql_query("",
                                                                            "*",
                                                                            "",
                                                                            "ed18_i_codigo = $escola and me22_i_codigo = $pesquisa_chave"
                                                                           ));
     if ($clmer_desperdicio->numrows!=0) {
     	
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$me22_i_codigo',false);</script>";
      
     } else {
      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
     }     
    } else {
     echo "<script>".$funcao_js."('',false);</script>";
    }    
   }
   ?>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form2","chave_me22_i_codigo",true,1,"chave_me22_i_codigo",true);
</script>