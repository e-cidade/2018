<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBseller Servicos de Informatica
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

db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clsetorambulatorial = new cl_setorambulatorial;
$clsetorambulatorial->rotulo->label("sd91_codigo");
$clsetorambulatorial->rotulo->label("sd91_descricao");
?>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
  <link href='estilos.css' rel='stylesheet' type='text/css'>
  <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
</head>
<body>
  <form name="form2" method="post" action="" class="container">
    <fieldset>
      <legend>Dados para Pesquisa</legend>
      <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
        <tr>
          <td><label><?=$Lsd91_codigo?></label></td>
          <td><?php db_input("sd91_codigo",10,$Isd91_codigo,true,"text",4,"","chave_sd91_codigo"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lsd91_descricao?></label></td>
          <td><?php db_input("sd91_descricao",10,$Isd91_descricao,true,"text",4,"","chave_sd91_descricao");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_setorambulatorial.hide();">
  </form>
      <?php

      $aWhere   = array();
      $aWhere[] = " sd91_unidades = " . db_getsession('DB_coddepto');
      if (!isset($pesquisa_chave)) {

        if (isset($campos)==false) {

          if (file_exists("funcoes/db_func_setorambulatorial.php")==true) {
            include("funcoes/db_func_setorambulatorial.php");
          } else {
            $campos = "setorambulatorial.*";
          }
        }

        if (isset($chave_sd91_codigo) && (trim($chave_sd91_codigo)!="") ) {
          $aWhere[] = " sd91_codigo = {$chave_sd91_codigo} ";
        } else if(isset($chave_sd91_descricao) && (trim($chave_sd91_descricao)!="") ) {
          $aWhere[] = " sd91_descricao like '{$chave_sd91_descricao}%' ";
        }

        $sWhere  = implode(" and ", $aWhere);
        $sql     = $clsetorambulatorial->sql_query_file("", $campos, "sd91_codigo", $sWhere);
        $repassa = array();

        if (isset($chave_sd91_descricao)) {
          $repassa = array("chave_sd91_codigo"=>$chave_sd91_codigo,"chave_sd91_descricao"=>$chave_sd91_descricao);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
        echo '  </fieldset>';
        echo '</div>';
      } else {

        if ($pesquisa_chave!=null && $pesquisa_chave!="") {

          $aWhere[] = " sd91_codigo = {$pesquisa_chave} ";
          $sWhere   = implode(" and ", $aWhere);
          $result   = $clsetorambulatorial->sql_record($clsetorambulatorial->sql_query_file(null, "*", null, $sWhere));
          if ($clsetorambulatorial->numrows!=0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$sd91_descricao',false);</script>";
          } else {
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        } else {
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
</body>
</html>
<script>
js_tabulacaoforms("form2","chave_sd91_descricao",true,1,"chave_sd91_descricao",true);
</script>
