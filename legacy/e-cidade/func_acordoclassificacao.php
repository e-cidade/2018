<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_acordoclassificacao_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clacordoclassificacao = new cl_acordoclassificacao;
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
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_acordoclassificacao.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?php

        if(!isset($pesquisa_chave)) {

          if(isset($campos)==false){

            if(file_exists("funcoes/db_func_acordoclassificacao.php")==true){
              include("funcoes/db_func_acordoclassificacao.php");
            }else{
              $campos = "acordoclassificacao.*";
            }
          }

          $sql = $clacordoclassificacao->sql_query();
          $repassa = array();
          db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);

        } else {

          if ($pesquisa_chave != null && $pesquisa_chave != "") {

            $result = $clacordoclassificacao->sql_record($clacordoclassificacao->sql_query($pesquisa_chave));

            if ($clacordoclassificacao->numrows!=0) {

              db_fieldsmemory($result, 0);
              echo "<script>".$funcao_js."('$ac46_descricao', false);</script>";

            } else {
              echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado', true);</script>";
            }

          } else {
            echo "<script>".$funcao_js."('', false);</script>";
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
