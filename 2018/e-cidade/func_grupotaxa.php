<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_grupotaxa_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clgrupotaxa = new cl_grupotaxa;
$clgrupotaxa->rotulo->label("ar37_sequencial");
$clgrupotaxa->rotulo->label("ar37_descricao");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body class='body-default'>
<div class="container">

  <form name="form2" method="post" action="" >
    <table width="35%" border="0" align="center" cellspacing="0">
      <tr>
          <td width="4%" align="right" nowrap title="<?=$Tar37_sequencial?>">
            <?=$Lar37_sequencial?>
          </td>
          <td width="96%" align="left" nowrap>
          <?php
		        db_input("ar37_sequencial",10,$Iar37_sequencial,true,"text",4,"","chave_ar37_sequencial");
		      ?>
        </td>
      </tr>
      <tr>
        <td width="4%" align="right" nowrap title="<?=$Tar37_descricao?>">
          <?=$Lar37_descricao?>
        </td>
        <td width="96%" align="left" nowrap>
          <?php
		        db_input("ar37_descricao",50,$Iar37_descricao,true,"text",4,"","chave_ar37_descricao");
		      ?>
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center">
          <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
          <input name="limpar" type="reset" id="limpar" value="Limpar" >
          <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_grupotaxa.hide();">
        </td>
      </tr>
    </table>
  </form>

  <?php

    if (!isset($pesquisa_chave)) {

      $campos = " ar37_sequencial, ar38_descricao,  ar37_descricao " ;
      $aWhere = array();

      if(isset($chave_ar37_sequencial) && (trim($chave_ar37_sequencial)!="") ) {
        $aWhere[] = " ar37_sequencial = $chave_ar37_sequencial ";
      }
      if(isset($chave_ar37_descricao) && (trim($chave_ar37_descricao)!="") ){
        $aWhere[] = " ar37_descricao like '$chave_ar37_descricao%'";
      }

      $sWhere = implode(" and ", $aWhere);
      $sSql   = $clgrupotaxa->sql_query("",$campos,"ar37_sequencial", $sWhere);

      $repassa = array();
      if(isset($chave_ar37_descricao)){
        $repassa = array("chave_ar37_sequencial"=>$chave_ar37_sequencial,"chave_ar37_descricao"=>$chave_ar37_descricao);
      }
      db_lovrot($sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
    } else {

      if ($pesquisa_chave!=null && $pesquisa_chave!="") {

        $result = $clgrupotaxa->sql_record($clgrupotaxa->sql_query($pesquisa_chave));
        if ($clgrupotaxa->numrows != 0) {

          db_fieldsmemory($result,0);
          echo "<script>".$funcao_js."('$ar37_descricao',false);</script>";
        } else {
         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
        }
      } else {
       echo "<script>".$funcao_js."('',false);</script>";
      }
    }
    ?>
</div>
</body>
</html>

<script>
js_tabulacaoforms("form2","chave_ar37_descricao",true,1,"chave_ar37_descricao",true);
</script>