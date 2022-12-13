<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_matrequi_classe.php");
$oGet = db_utils::postMemory($_GET);
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clmatrequi = new cl_matrequi;
$clmatrequi->rotulo->label("m40_codigo");
$clmatrequi->rotulo->label("m40_codigo");
$sWhere = '';

if (!empty($oGet->departamento)){
$sWhere = "m40_depto =".db_getsession('DB_coddepto');
}

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
            <td width="4%" align="right" nowrap title="<?=$Tm40_codigo?>">
              <?=$Lm40_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("m40_codigo",10,$Im40_codigo,true,"text",4,"","chave_m40_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_matrequi.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_matrequi.php")==true){
             include("funcoes/db_func_matrequi.php");
           }else{
           $campos = "matrequi.*";
           }
        }
        if (isset($chave_m40_codigo) && (trim($chave_m40_codigo)!="") ) {

          if (!empty($sWhere)) {
            $sWhere .= " and ";
          }
          $sWhere .= " m40_codigo = {$chave_m40_codigo} ";
	        $sql = $clmatrequi->sql_query(null, $campos, "m40_codigo", $sWhere);
        } else {
          $sql = $clmatrequi->sql_query("",$campos,"m40_codigo", $sWhere);
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!="") {
          if (!empty($sWhere)) {
            $sWhere .= " and ";
          }
          $sWhere .=  " matrequi.m40_codigo = ".$pesquisa_chave;
          $result = $clmatrequi->sql_record($clmatrequi->sql_query($pesquisa_chave, '*', null, $sWhere));
          if($clmatrequi->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$m40_codigo',false);</script>";
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
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>