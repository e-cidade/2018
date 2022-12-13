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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

define('INSCRITOS',      1);
define('DESIGNADOS',     2);
define('NAO_DESIGNADOS', 3);

$oRotulo = new rotulocampo();
$oRotulo->label('ed18_i_codigo');
$oRotulo->label('ed18_c_nome');

$oDaoVagas = new cl_vagas();

$aWhere = array();
$aWhere[] = " mo10_fase = {$iFase} ";

if ( in_array( $iTipoConsulta, array( DESIGNADOS, NAO_DESIGNADOS) ) ) {
  $aWhere[] = " mo04_processada is true ";
}

if ( $iTipoConsulta == INSCRITOS ) {
  $aWhere[] = " mo04_processada is false ";
}

if ( !empty($iEtapa) ) {
  $aWhere[] = " mo10_serie = {$iEtapa}";
}

$sCampos = "distinct ed18_i_codigo, ed18_c_nome";

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

  <form name="form2" method="post" action="" class="container">
    <fieldset>
      <legend>Dados para Pesquisa</legend>
      <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
        <tr>
          <td width="4%" align="right" nowrap title="<?=$Ted18_i_codigo?>">
            <label for="chave_ed18_i_codigo">
              <?=$Led18_i_codigo?>
            </label>
          </td>
          <td width="96%" align="left" nowrap>
            <?php db_input("ed18_i_codigo", 10, $Ied18_i_codigo, true, "text", 1, "", "chave_ed18_i_codigo");?>
          </td>
        </tr>
        <tr>
          <td width="4%" align="right" nowrap title="<?=$Ted18_c_nome?>">
            <label for="chave_ed18_c_nome"> <?=$Led18_c_nome?></label>
          </td>
          <td width="96%" align="left" nowrap>
            <?php db_input("ed18_c_nome",40,$Ied18_c_nome,true,"text",4,"","chave_ed18_c_nome");?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_escola.hide();">
  </form>

  <?php

    if ( !isset($pesquisa_chave) ) {

      if(isset($chave_ed18_i_codigo) && (trim($chave_ed18_i_codigo)!="") ){
        $aWhere[] = " ed18_i_codigo = {$chave_ed18_i_codigo} ";
      }
      if(isset($chave_ed18_c_nome) && (trim($chave_ed18_c_nome)!="") ){
        $aWhere[] = " ed18_c_nome like '{$chave_ed18_c_nome}%' ";
      }
      $sWhere = implode(" and ", $aWhere);
      $sSql   = $oDaoVagas->sql_query_escola_serie_ensino("",$sCampos, null, $sWhere);

      $repassa = array();
      if(isset($chave_ed18_c_nome)){
        $repassa = array("chave_ed18_c_nome"=>$chave_ed18_c_nome,"chave_ed18_c_nome"=>$chave_ed18_c_nome);
      }
      echo '<div class="container">';
      echo '  <fieldset>';
      echo '    <legend>Resultado da Pesquisa</legend>';
        db_lovrot($sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
      echo '  </fieldset>';
      echo '</div>';
    } else {

      if( $pesquisa_chave != null && $pesquisa_chave != "" ) {

        $aWhere[] = " ed18_i_codigo = {$pesquisa_chave} ";
        $sWhere   = implode(" and ", $aWhere);

        $sSql   = $oDaoVagas->sql_query_escola_serie_ensino("", $sCampos, null, $sWhere);
        $result = $oDaoVagas->sql_record($sSql);

        if ($oDaoVagas->numrows != 0) {

          db_fieldsmemory($result,0);
          echo "<script>".$funcao_js."('$ed18_c_nome',false);</script>";

        }else{
         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
        }
      }else{

       echo "<script>".$funcao_js."('',false);</script>";
      }
    }
  ?>

</body>
</html>
<script>

$('limpar').onclick = function() {
    
  $('chave_ed18_i_codigo').value = '';
  $('chave_ed18_c_nome').value   = '';
  $('pesquisar2').click();
}

</script>