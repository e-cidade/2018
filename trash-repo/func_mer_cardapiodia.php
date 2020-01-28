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
include("classes/db_mer_cardapiodiaescola_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clmer_cardapiodiaescola = new cl_mer_cardapiodiaescola;
$clrotulo = new rotulocampo;
$clrotulo->label("me01_c_nome");
$clrotulo->label("me12_d_data");

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
     <td width="4%" align="right" nowrap title="<?=$Tme01_c_nome?>">
      <?=$Lme01_c_nome?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("me01_c_nome",50,$Ime01_c_nome,true,"text",4,"","chave_me01_c_nome");?>
     </td>
    </tr>
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Tme12_d_data?>">
      <?=$Lme12_d_data?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_inputdata('me12_d_data',@$me12_d_data_dia,@$me12_d_data_mes,@$me12_d_data_ano,true,'text',4,"")?>
     </td>
    </tr>
    <tr>
     <td colspan="2" align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_mer_cardapiodia.hide();">
     </td>
    </tr>
    </form>
   </table>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <?
   $escola     = db_getsession("DB_coddepto");
   $dataatual  = date("Y-m-d",db_getsession("DB_datausu"));
   $horaatual  = date("H:i");
   $restricao = "  (me12_d_data < '$dataatual' ";
   $restricao .= " OR (me12_d_data = '$dataatual' AND me03_c_fim < '$horaatual') ";
   $restricao .= " ) AND ed18_i_codigo = $escola";
   $restricao .= " AND not exists (select * from mer_desperdicio where me22_i_cardapiodiaescola = me37_i_codigo)";
   if (!isset($pesquisa_chave)) {
   	
    if (isset($campos)==false) {
    	
     if (file_exists("funcoes/db_func_mer_cardapiodia.php")==true) {
       include("funcoes/db_func_mer_cardapiodia.php");
     } else {
       $campos = "mer_cardapiodia.*";
     }
     
    }
    if (isset($chave_me01_c_nome) && (trim($chave_me01_c_nome)!="")) {
      $sql = $clmer_cardapiodiaescola->sql_query("",
                                           $campos,
                                           "me12_d_data DESC,me03_i_orden",
                                           " $restricao AND me01_c_nome like '$chave_me01_c_nome%' "
                                          );
    } else if (isset($me12_d_data) && (trim($me12_d_data)!="")) {
      $data_pesquisa = substr($me12_d_data,6,4)."-".substr($me12_d_data,3,2)."-".substr($me12_d_data,0,2);
      $sql = $clmer_cardapiodiaescola->sql_query("",
                                           $campos,
                                           "me12_d_data DESC,me03_i_orden",
                                           "$restricao AND me12_d_data = '$data_pesquisa'"
                                          );
    } else {
      $sql = $clmer_cardapiodiaescola->sql_query("",$campos,"me12_d_data DESC,me03_i_orden",$restricao);
    }
    $repassa = array();
    if (isset($chave_me01_c_nome)) {
     $repassa = array("chave_me01_c_nome"=>$chave_me01_c_nome,"me12_d_data"=>$me12_d_data);
    }
    db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
    
   } else {
   	
    if ($pesquisa_chave!=null && $pesquisa_chave!="") {
    	
     $result = $clmer_cardapiodia->sql_record($clmer_cardapiodia->sql_query("",
                                                                            "*",
                                                                            "",
                                                                            "$restricao 
                                                                             AND me12_i_codigo = $pesquisa_chave"
                                                                           ));
     if ($clmer_cardapiodia->numrows!=0) {
     	
       db_fieldsmemory($result,0);
       echo "<script>".$funcao_js."('$me12_i_codigo',false);</script>";
       
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
js_tabulacaoforms("form2","chave_me01_c_nome",true,1,"chave_me01_c_nome",true);
</script>