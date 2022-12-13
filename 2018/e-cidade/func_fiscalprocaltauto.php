<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
include("classes/db_fiscalproc_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clfiscalproc = new cl_fiscalproc;
$clfiscalproc->rotulo->label("y29_codtipo");
$clfiscalproc->rotulo->label("y29_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Ty29_codtipo?>">
              <?=$Ly29_codtipo?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("y29_codtipo",20,$Iy29_codtipo,true,"text",4,"","chave_y29_codtipo");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Ty29_descr?>">
              <?=$Ly29_descr?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("y29_descr",50,$Iy29_descr,true,"text",4,"","chave_y29_descr");
	       ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?

      $w=" y29_coddepto = ".db_getsession("DB_coddepto")." and y29_tipoproced='A' and y29_tipofisc=$tipofisc ";

      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_fiscalprocalt.php")==true){
             include("funcoes/db_func_fiscalprocalt.php");
           }else{
           $campos = "y29_codtipo,y29_descr,y29_descr_obs,y45_valor,y45_vlrfixo,y45_percentual";
           }
        }
        if(isset($chave_y29_codtipo) && (trim($chave_y29_codtipo)!="") ){
	         $sql = $clfiscalproc->sql_query_rec($chave_y29_codtipo,$campos,"y29_codtipo","y29_codtipo=$chave_y29_codtipo and $w");
        }else if(isset($chave_y29_descr) && (trim($chave_y29_descr)!="") ){
	         $sql = $clfiscalproc->sql_query_rec("",$campos,"y29_descr"," y29_descr like '$chave_y29_descr%' and $w");
        }else{
           $sql = $clfiscalproc->sql_query_rec("",$campos,"y29_codtipo","$w");
        }

        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
	        $result = $clfiscalproc->sql_record($clfiscalproc->sql_query_rec($pesquisa_chave,"*","","y29_codtipo=$pesquisa_chave and $w"));
          if($clfiscalproc->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$y29_descr',false,'$y45_vlrfixo','$y45_valor', '$y45_percentual');</script>";
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