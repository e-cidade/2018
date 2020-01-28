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
include("classes/db_orcppa_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clorcppa = new cl_orcppa;
$clorcppa->rotulo->label("o23_codppa");
$clorcppa->rotulo->label("o23_anoexe");
$dbwhere = " o23_codleippa = $o23_codleippa  ";
if(isset($o23_orgao) && $o23_orgao != ''){
  $dbwhere .= " and o23_orgao = $o23_orgao ";   
}
if(isset($o23_unidade) && $o23_unidade != ''){
  $dbwhere .= " and o23_unidade = $o23_unidade ";   
}
if(isset($o23_funcao) && $o23_funcao != ''){
  $dbwhere .= " and o23_funcao = $o23_funcao ";   
}
if(isset($o23_subfuncao) && $o23_subfuncao != ''){
  $dbwhere .= " and o23_subfuncao = $o23_subfuncao ";   
}
if(isset($o23_programa) && $o23_programa != ''){
  $dbwhere .= " and o23_programa = $o23_programa ";   
}
if(isset($o23_acao) && $o23_acao != ''){
  $dbwhere .= " and o23_acao = $o23_acao ";   
}
//echo $dbwhere;

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
    <td align='center' valign='top' height='10%'>
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_orcppa.hide();">
     </td> 	      
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_orcppa.php")==true){
             include("funcoes/db_func_orcppa.php");
           }else{
           $campos = "orcppa.*";
           }
        }
        if(isset($chave_o23_codppa) && (trim($chave_o23_codppa)!="") ){
	         $sql = $clorcppa->sql_query_compl($chave_o23_codppa,$campos,"o23_codppa");
        }else if(isset($chave_o23_anoexe) && (trim($chave_o23_anoexe)!="") ){
	         $sql = $clorcppa->sql_query_compl("",$campos,"o23_anoexe"," o23_anoexe like '$chave_o23_anoexe%' ");
        }else if(isset($dbwhere)){
           $sql = $clorcppa->sql_query_compl("",$campos,"o23_codppa","$dbwhere");
        }else {
           $sql = $clorcppa->sql_query_compl("",$campos,"o23_codppa","");
        }
        db_lovrot($sql,15,"()","",$funcao_js,$campos_layer="",$NomeForm="NoMe",$variaveis_repassa=array(),$automatico=false);
     }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clorcppa->sql_record($clorcppa->sql_query_compl($pesquisa_chave));
          if($clorcppa->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$o23_anoexe',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>