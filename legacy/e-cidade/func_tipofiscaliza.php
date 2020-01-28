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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_tipofiscaliza_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$cltipofiscaliza = new cl_tipofiscaliza;
$cltipofiscaliza->rotulo->label("y27_codtipo");
$cltipofiscaliza->rotulo->label("y27_descr");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body>
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Ty27_codtipo?>">
              <?=$Ly27_codtipo?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("y27_codtipo",8,$Iy27_codtipo,true,"text",4,"","chave_y27_codtipo");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Ty27_descr?>">
              <?=$Ly27_descr?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("y27_descr",20,$Iy27_descr,true,"text",4,"","chave_y27_descr");
		       ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_tipofiscaliza.hide();">
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
           if(file_exists("funcoes/db_func_tipofiscaliza.php")==true){
             include("funcoes/db_func_tipofiscaliza.php");
           }else{
           $campos = "tipofiscaliza.*";
           }
        }
        if(isset($chave_y27_codtipo) && (trim($chave_y27_codtipo)!="") ){
	         $sql = $cltipofiscaliza->sql_query($chave_y27_codtipo,$campos,"y27_codtipo","y27_codtipo=$chave_y27_codtipo and y27_instit = ".db_getsession('DB_instit') );
        }else if(isset($chave_y27_descr) && (trim($chave_y27_descr)!="") ){
	         $sql = $cltipofiscaliza->sql_query("",$campos,"y27_codtipo"," y27_descr like '$chave_y27_descr%' and y27_instit = ".db_getsession('DB_instit') );
        }else{
           $sql = $cltipofiscaliza->sql_query("",$campos,"y27_codtipo","y27_instit = ".db_getsession('DB_instit') );
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{

        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cltipofiscaliza->sql_record($cltipofiscaliza->sql_query($pesquisa_chave,"*",null,"y27_codtipo=$pesquisa_chave  and y27_instit = ".db_getsession('DB_instit') ));
          if($cltipofiscaliza->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$y27_descr',false);</script>";
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