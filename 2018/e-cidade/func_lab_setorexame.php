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

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$cllab_setorexame = new cl_lab_setorexame;
$cllab_setorexame->rotulo->label("la09_i_codigo");
$cllab_setorexame->rotulo->label("la08_c_descr");
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body bgcolor=#CCCCCC>
  <div class="container">
    <form name="form2" method="post" action="" class="form-container">
      <fieldset>
        <legend>Filtros</legend>
        <table width="35%" border="0" align="center" cellspacing="0">
          <tr>
            <td nowrap title="<?=$Tla09_i_codigo?>">
              <?=$Lla09_i_codigo?>
            </td>
            <td nowrap>
              <?php
              db_input( "la09_i_codigo", 10, $Ila09_i_codigo, true, "text", 4, "", "chave_la09_i_codigo" );
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap>
              <b>Descrição</b>
            </td>
            <td nowrap>
              <?php
              db_input( "la08_c_descr", 50, @$Ila08_c_descr, true, "text", 4, "", "chave_la08_c_descr" );
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar"    type="reset"  id="limpar"     value="Limpar" >
      <input name="Fechar"    type="button" id="fechar"     value="Fechar" onClick="parent.db_iframe_lab_setorexame.hide();">
    </form>
  </div>
  <div class="container">
    <table>
      <tr>
        <td>
          <?php

          $aWhere = array();
          if( isset( $la02_i_codigo ) ) {
            $aWhere[] = " la02_i_codigo = {$la02_i_codigo} ";
          }

          if( isset( $la24_i_codigo ) ) {
            $aWhere[] = " la09_i_labsetor = {$la24_i_codigo} " ;
          }

          if( isset( $la23_i_codigo ) ) {
            $aWhere[] = " la23_i_codigo = {$la23_i_codigo} " ;
          }

          if( !isset( $pesquisa_chave ) ) {

            $campos  = "lab_setorexame.la09_i_codigo, lab_setor.la23_i_codigo, lab_setor.la23_c_descr";
            $campos .= ", lab_exame.la08_i_codigo, lab_exame.la08_c_descr";

            if( isset( $chave_la09_i_codigo ) && ( trim( $chave_la09_i_codigo ) != "" ) ) {
              $aWhere[] = " la08_i_codigo = {$chave_la09_i_codigo} ";
            }
            if( isset( $chave_la08_c_descr ) && ( trim( $chave_la08_c_descr ) != "" ) ) {
              $aWhere[] = "la08_c_descr like '".mb_strtoupper($chave_la08_c_descr)."%'";
            }

            $sWhere = implode(" and ", $aWhere);
            $sSql   = $cllab_setorexame->sql_query( "", $campos, "la23_c_descr, la08_c_descr", $sWhere);

            $repassa = array();
            if( isset( $chave_la09_i_codigo ) ) {
              $repassa = array( "chave_la09_i_codigo" => $chave_la09_i_codigo, "chave_la09_i_codigo" => $chave_la09_i_codigo );
            }

            db_lovrot( $sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa );
          } else {

            if( $pesquisa_chave != null && $pesquisa_chave != "" ) {


              $aWhere[] = " la08_i_codigo = {$pesquisa_chave} ";
              $sWhere   = implode(" and ", $aWhere);
              $sSql     = $cllab_setorexame->sql_query( null, '*', null, $sWhere );
              $result   = $cllab_setorexame->sql_record( $sSql );

              if( $cllab_setorexame->numrows != 0 ) {

                db_fieldsmemory( $result, 0 );
                echo "<script>".$funcao_js."('$la08_c_descr',false,$la09_i_codigo);</script>";
              } else {
                echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true,'');</script>";
              }
            } else {
              echo "<script>".$funcao_js."('',false,'');</script>";
            }
          }
          ?>
       </td>
     </tr>
  </table>
  </div>
</body>
</html>
<script>
$('chave_la09_i_codigo').className = 'field-size2';
$('chave_la08_c_descr').className  = 'field-size7';
js_tabulacaoforms("form2","chave_la09_i_codigo",true,1,"chave_la09_i_codigo",true);
</script>