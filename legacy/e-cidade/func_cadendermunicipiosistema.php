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

db_postmemory( $_POST );
parse_str( $_SERVER["QUERY_STRING"] );

$clcadendermunicipiosistema = new cl_cadendermunicipiosistema;
$clcadendermunicipiosistema->rotulo->label("db125_sequencial");
$clcadendermunicipiosistema->rotulo->label("db125_sequencial");

$oDaoRotulo = new rotulocampo();
$oDaoRotulo->label( 'db72_descricao' );
$oDaoRotulo->label( 'db71_sigla' );
$oDaoRotulo->label( 'db125_codigosistema' );
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body class="body-default">
  <div class="container">
    <form name="form2" method="post" action="" class="form-container" >
      <fieldset>
        <legend>Filtros</legend>
        <table>
          <tr>
            <td nowrap title="<?=$Tdb125_sequencial?>">
              <?=$Ldb125_sequencial?>
            </td>
            <td nowrap>
              <?php
              db_input( "db125_sequencial", 10, $Idb125_sequencial, true, "text", 4, "", "chave_db125_sequencial" );
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=$Tdb71_sigla?>">
              <?=$Ldb71_sigla?>
            </td>
            <td nowrap>
              <?php
              db_input( "db71_sigla", 10, $Idb71_sigla, true, "text", 4, "", "chave_db71_sigla" );
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap>
              <label class="bold">Código IBGE:</label>
            </td>
            <td nowrap>
              <?php
              db_input( "db125_codigosistema", 10, $Idb125_codigosistema, true, "text", 4, "", "chave_db125_codigosistema" );
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=$Tdb72_descricao?>">
              <?=$Ldb72_descricao?>
            </td>
            <td nowrap>
              <?php
              db_input( "db72_descricao", 10, $Idb72_descricao, true, "text", 4, "", "chave_db72_descricao" );
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar"    type="button" id="limpar"     value="Limpar" >
      <input name="Fechar"    type="button" id="fechar"     value="Fechar" onClick="parent.db_iframe_cadendermunicipiosistema.hide();">
    </form>
    <table>
      <tr>
        <td>
          <?php
          $sCampos = "db125_sequencial, db72_descricao, db71_sigla, db125_codigosistema";
          $aWhere  = array();
          $sWhere  = '';

          if( isset( $iTipoSistema ) && !empty( $iTipoSistema ) ) {
            $aWhere[] = "db124_sequencial = {$iTipoSistema}";
          }

          if( !isset( $pesquisa_chave ) ) {

            if( isset( $chave_db125_sequencial ) && ( trim( $chave_db125_sequencial ) != "" ) ) {
              $aWhere[] = "db125_sequencial = {$chave_db125_sequencial}";
            }

            if( isset( $chave_db72_descricao ) && ( trim( $chave_db72_descricao ) != "" ) ) {
              $aWhere[] = "db72_descricao ilike '{$chave_db72_descricao}%'";
            }

            if( isset( $chave_db71_sigla ) && ( trim( $chave_db71_sigla ) != "" ) ) {
              $aWhere[] = "db71_sigla ilike '{$chave_db71_sigla}%'";
            }

            if( isset( $chave_db125_codigosistema ) && ( trim( $chave_db125_codigosistema ) != "" ) ) {
              $aWhere[] = "db125_codigosistema ilike '{$chave_db125_codigosistema}%'";
            }

            $repassa = array();
            if( isset( $chave_db125_sequencial ) ) {

              $repassa = array(
                "chave_db125_sequencial"    => $chave_db125_sequencial,
                "chave_db72_descricao"      => $chave_db72_descricao,
                "chave_db71_sigla"          => $chave_db71_sigla,
                "chave_db125_codigosistema" => $chave_db125_codigosistema
              );
            }

            $sWhere = implode( " and ", $aWhere );
            $sSql   = $clcadendermunicipiosistema->sql_query( "", $sCampos, "db125_sequencial", $sWhere );
            db_lovrot( $sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa );
          } else {

            if( $pesquisa_chave != null && $pesquisa_chave != "" ) {

              $aWhere[] = "db125_sequencial = {$pesquisa_chave}";
              $sWhere   = implode( " and ", $aWhere );
              $sSql     = $clcadendermunicipiosistema->sql_query( null, $sCampos, null, $sWhere );
              $result   = $clcadendermunicipiosistema->sql_record( $sSql );

              if( $clcadendermunicipiosistema->numrows != 0 ) {

                db_fieldsmemory( $result, 0 );
                echo "<script>".$funcao_js."('{$db125_sequencial}',false, '{$db72_descricao}', '{$db125_codigosistema}', '{$db71_sigla}');</script>";
              } else {
                echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
              }
            } else {
              echo "<script>".$funcao_js."('',false);</script>";
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
js_tabulacaoforms( "form2", "chave_db125_sequencial", true, 1, "chave_db125_sequencial", true );

$('chave_db125_sequencial').className    = 'field-size2';
$('chave_db71_sigla').className          = 'field-size2';
$('chave_db125_codigosistema').className = 'field-size2';
$('chave_db72_descricao').className      = 'field-size7';

$('limpar').onclick = function() {

  $('chave_db125_sequencial').value    = '';
  $('chave_db71_sigla').value          = '';
  $('chave_db125_codigosistema').value = '';
  $('chave_db72_descricao').value      = '';
}
</script>