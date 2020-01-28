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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);
db_postmemory($_POST);

$oDaoCensoEtapa = new cl_censoetapa();
$oDaoSerie      = new cl_serie();

$aFiltroTiposEnsino    = array();
$aFiltroTiposEnsino[1] = " ed131_regular      = 'S' ";
$aFiltroTiposEnsino[2] = " ed131_especial     = 'S' ";
$aFiltroTiposEnsino[3] = " ed131_eja          = 'S' ";
$aFiltroTiposEnsino[4] = " ed131_profissional = 'S' ";


/**
 * busca os dados do ensino:
 * modalidade e mediação didatico pedagógica...
 * só listar etapas do censo que atenden este filtro
 */
$sCamposEnsino = " ed10_i_tipoensino, ed10_mediacaodidaticopedagogica ";
$oDaoSerie  = new cl_serie();
$sSqlEnsino = $oDaoSerie->sql_query_curso($oGet->iSerie, $sCamposEnsino);
$rsEnsino   = db_query($sSqlEnsino);
if ( !$rsEnsino || pg_num_rows($rsEnsino) == 0) {

  echo '<br><b>Etapa não esta vínculada a nenhum ensino.</b>';
  exit();
}

/**
 * Define os filtros e campos retornados da etapas do censo
 */
$aWhereEtapas   = array();
$oDados         = db_utils::fieldsMemory($rsEnsino, 0);
$aWhereEtapas[] = " ed131_mediacaodidaticopedagogica = {$oDados->ed10_mediacaodidaticopedagogica} ";
$aWhereEtapas[] = $aFiltroTiposEnsino[$oDados->ed10_i_tipoensino];

$sCampos  = " distinct ed266_i_codigo, ed266_c_descr, ed131_ano ";
$sOrdem   = " ed131_ano desc, ed266_c_descr ";

$oRotulo = new rotulocampo;
$oRotulo->label('ed266_i_codigo');
$oRotulo->label('ed266_c_descr');
$oRotulo->label('ed131_ano');

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
          <td><label><?=$Led266_i_codigo?></label></td>
          <td><?php db_input("ed266_i_codigo", 10, $Ied266_i_codigo, true, "text", 4, "", "chave_ed266_i_codigo"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Led131_ano?></label></td>
          <td><?php db_input("ed131_ano", 10, $Ied131_ano, true, "text",4, "", "chave_ed131_ano");?></td>
        </tr>
        <tr>
          <td><label><?=$Led266_c_descr?></label></td>
          <td><?php db_input("ed266_c_descr", 30, $Ied266_c_descr, true, "text",4, "", "chave_ed266_c_descr");?></td>
        </tr>
      </table>
    </fieldset>

    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_seriecensoetapa.hide();">
  </form>
  <?php

  if ( !isset ($oGet->pesquisa_chave) ) {

    if( !empty( $chave_ed266_i_codigo ) && trim($chave_ed266_i_codigo) != ''  ) {

      $aWhereEtapas[] = " ed266_i_codigo = {$chave_ed266_i_codigo} ";
    } else if( !empty( $chave_ed266_c_descr ) && trim($chave_ed266_c_descr) != '' ){
      $aWhereEtapas[] = " ed266_c_descr ilike '{$chave_ed266_c_descr}%' ";
    } else if ( !empty($chave_ed131_ano) && trim($chave_ed131_ano ) != '') {
      $aWhereEtapas[] = " ed131_ano = $chave_ed131_ano";
    }

    $sWhere  = implode(" and ", $aWhereEtapas);
    $sSql    = $oDaoCensoEtapa->sql_query_mediacao(null, null, $sCampos, $sOrdem , $sWhere );
    $repassa = array();
    if(isset($chave_ed133_codigo)){
      $repassa = array("chave_ed266_i_codigo"=>$chave_ed266_i_codigo,"chave_ed266_c_descr"=>$chave_ed266_c_descr);
    }
    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';
      db_lovrot($sSql,15, "()", "", $funcao_js, "", "NoMe", $repassa);
    echo '  </fieldset>';
    echo '</div>';
  } else {

    if( !empty($oGet->pesquisa_chave) ) {

      $aWhereEtapas[] = " ed266_i_codigo = {$oGet->pesquisa_chave} ";

      $sWhere = implode(" and ", $aWhereEtapas);
      $sSql   = $oDaoCensoEtapa->sql_query_mediacao(null, null, $sCampos, $sOrdem , $sWhere );
      $rs     = db_query($sSql);
      if( pg_num_rows($rs) > 0) {

        db_fieldsmemory($rs,0);
        echo "<script>".$funcao_js."('$ed266_c_descr',false, '$ed131_ano', '$ed266_i_codigo');</script>";
      } else {
       echo "<script>".$funcao_js."('Chave(".$oGet->pesquisa_chave.") não Encontrado',true);</script>";
      }
    } else {
     echo "<script>".$funcao_js."('',false);</script>";
    }
  }
  ?>
</body>
</html>
