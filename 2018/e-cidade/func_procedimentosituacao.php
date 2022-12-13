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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$iEscola = db_getsession('DB_coddepto');
$aWhere  = array();

// só filtra escola quando acessado do módulo escola
if ( db_getsession('DB_modulo') == 1100747 ) {
  $aWhere[] = "ed86_i_escola = {$iEscola} ";
} else {
  $aWhere[] = "ed86_i_codigo is null ";
}

$oDaoProcedimento = new cl_procedimento;
$oDaoProcedimento->rotulo->label("ed40_i_codigo");
$oDaoProcedimento->rotulo->label("ed40_c_descr");

$sCampos  = "ed40_i_codigo, trim(ed40_c_descr)::varchar as ed40_c_descr, ed40_desativado as db_ed40_desativado, ";
$sCampos .= " case when ed40_desativado is true then 'Desativado' else 'Ativo' end::varchar as dl_Situacao ";

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body class='body-default'>
  <div class='container'>
  <form name="form2" method="post" action="" >
    <fieldset>
      <legend>Filtros</legend>
      <table>
        <tr>
          <td title="<?=$Ted40_i_codigo?>">
            <label for="chave_ed40_i_codigo"><?=$Led40_i_codigo?></label>
          </td>
          <td >
            <?php db_input("ed40_i_codigo",10,$Ied40_i_codigo,true,"text",4,"","chave_ed40_i_codigo");?>
          </td>
        </tr>
        <tr>
          <td title="<?=$Ted40_c_descr?>">
             <label for="chave_ed40_c_descr"><?=$Led40_c_descr?></label>
          </td>
          <td >
            <?php db_input("ed40_c_descr",30,$Ied40_c_descr,true,"text",4,"","chave_ed40_c_descr");?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" />
    <input name="limpar" type="reset" id="limpar" value="Limpar" />
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_procedimentosituacao.hide();" />
  </form>

  </div>

  <div class="subcontainer">
  <?php

    if ( empty($pesquisa_chave) ) {

      if(isset($chave_ed40_i_codigo) && (trim($chave_ed40_i_codigo)!="") ) {
        $aWhere[] = " ed40_i_codigo = {$chave_ed40_i_codigo} ";
      }
      if(isset($chave_ed40_c_descr) && (trim($chave_ed40_c_descr)!="") ) {
        $aWhere[] = " ed40_c_descr ilike '{$chave_ed40_c_descr}%' ";
      }

      $sWhere = implode(' and ', $aWhere);
      $sSql   = $oDaoProcedimento->sql_query_origem_procedimento(null, $sCampos, "2", $sWhere);

      $repassa = array();
      if(isset($chave_ed40_c_descr)){
        $repassa = array("chave_ed40_i_codigo"=>$chave_ed40_i_codigo,"chave_ed40_c_descr"=>$chave_ed40_c_descr);
      }

      echo '<div class="container">';
      echo '  <fieldset>';
      echo '    <legend>Resultado da Pesquisa</legend>';
        db_lovrot($sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
      echo '  </fieldset>';
      echo '</div>';
    } else if ( !empty($pesquisa_chave) ) {

      $aWhere[] = " ed40_i_codigo = {$pesquisa_chave} ";
      $sWhere   = implode(' and ', $aWhere);
      $sSql     = $oDaoProcedimento->sql_query_origem_procedimento(null, $sCampos, "2", $sWhere);
      $rs       = db_query($sSql);
      if ( $rs && pg_num_rows($rs) > 0) {

        db_fieldsmemory($rs, 0);
        echo "<script>".$funcao_js."('$ed40_c_descr',false, '$db_ed40_desativado');</script>";
      } else {
        echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
      }

    } else {
      echo "<script>".$funcao_js."('', false);</script>";
    }

  ?>
  </div>
</body>
</html>