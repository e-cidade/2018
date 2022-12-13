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

//MODULO: educação
require_once (modification("libs/db_stdlibwebseller.php"));
require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("classes/db_formaavaliacao_classe.php"));

$lAcessadoEscola = isModuloEscola();

db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clformaavaliacao = new cl_formaavaliacao;
$clformaavaliacao->rotulo->label("ed37_i_codigo");
$clformaavaliacao->rotulo->label("ed37_c_descr");

$aWhere = array();
if ( $lAcessadoEscola ) {
  $aWhere[] = " ed37_i_escola = ".db_getsession("DB_coddepto");
} else {
  $aWhere[] = " ed37_i_escola is null ";
}

if ( !empty($forma) ) {
  $aWhere[] = " ed37_c_tipo = '$forma' ";
}

?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

  <div class="container">
    <form name="form2" method="post" action="" >
      <fieldset>
        <legend>Filtros</legend>
        <table>
          <tr>
            <td title="<?=$Ted37_i_codigo?>">
              <label for="chave_ed37_i_codigo"><?=$Led37_i_codigo?></label>
            </td>
            <td>
              <?php db_input("ed37_i_codigo",10,$Ied37_i_codigo,true,"text",4,"","chave_ed37_i_codigo");?>
            </td>
          </tr>
          <tr>
            <td title="<?=$Ted37_c_descr?>">
              <label for="chave_ed37_c_descr"><?=$Led37_c_descr?></label>
            </td>
            <td >
              <?php db_input("ed37_c_descr",30,$Ied37_c_descr,true,"text",4,"","chave_ed37_c_descr"); ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" />
      <input name="limpar" type="reset" id="limpar" value="Limpar" />
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_formaavaliacao.hide();" />
    </form>
  </div>

<?php

  $campos = "formaavaliacao.ed37_i_codigo,
             trim(formaavaliacao.ed37_c_descr) as ed37_c_descr,
             formaavaliacao.ed37_c_tipo,
             formaavaliacao.ed37_i_menorvalor,
             formaavaliacao.ed37_i_maiorvalor,
             formaavaliacao.ed37_i_variacao,
             formaavaliacao.ed37_c_minimoaprov";

  $group = "ed37_i_codigo, ed37_c_descr, ed37_c_tipo, ed37_i_menorvalor, ed37_i_maiorvalor, ed37_i_variacao, ed37_c_minimoaprov";

  if (!isset($pesquisa_chave) && !isset($pesquisa_chave2)) {

    if(isset($chave_ed37_i_codigo) && (trim($chave_ed37_i_codigo)!="") ) {
      $aWhere[] = " ed37_i_codigo = {$chave_ed37_i_codigo} ";
    }else if(isset($chave_ed37_c_descr) && (trim($chave_ed37_c_descr)!="") ){
      $aWhere[] = " ed37_c_descr like '{$chave_ed37_c_descr}%' ";
    }

    $sWhere = implode(" and ", $aWhere);
    $sql    = $clformaavaliacao->sql_formaavaliacao("", $campos, "ed37_c_descr", "{$sWhere} group by $group");

    $repassa = array();
    if(isset($chave_ed37_c_descr)){
      $repassa = array("chave_ed37_i_codigo"=>$chave_ed37_i_codigo,"chave_ed37_c_descr"=>$chave_ed37_c_descr);
    }

    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';
      db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
    echo '  </fieldset>';
    echo '</div>';
  }

  if (isset($pesquisa_chave) && $pesquisa_chave!="") {

    $aWhere[] = " ed37_i_codigo = {$pesquisa_chave}";
    $sWhere   = implode(" and ", $aWhere);
    $result   = $clformaavaliacao->sql_record($clformaavaliacao->sql_formaavaliacao("", $campos, "", $sWhere));
    if ( $clformaavaliacao->numrows != 0) {

      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$ed37_c_descr','$ed37_c_minimoaprov',false);</script>";
    }else{
      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado','',true);</script>";
    }
  }
  if (isset($pesquisa_chave2) && $pesquisa_chave2 != "") {

    $aWhere[] = " ed37_i_codigo = {$pesquisa_chave2}";
    $sWhere   = implode(" and ", $aWhere);
    $result   = $clformaavaliacao->sql_record($clformaavaliacao->sql_formaavaliacao("", $campos, "", $sWhere));
    if ( $clformaavaliacao->numrows != 0) {

      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$ed37_c_descr','$ed37_c_minimoaprov',false);</script>";
    }else{
      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave2.") não Encontrado','',true);</script>";
    }
  }
?>
</body>
</html>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
