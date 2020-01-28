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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_cadenderruacep_classe.php"));

db_postmemory( $_POST );
parse_str( $_SERVER["QUERY_STRING"] );

$oDaoCadEnderRuaCep = new cl_cadenderruacep();

$oRotulo = new rotulocampo();
$oRotulo->label( "db72_descricao" );
$oRotulo->label( "db73_descricao" );
$oRotulo->label( "db86_cep" );
$oRotulo->label( "db74_descricao" );
$oRotulo->label( "db73_descricao" );
$oRotulo->label( "db71_sigla" );
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
    <form name="form2" method="post" action="" >
      <fieldset>
        <legend>Filtros</legend>
        <table class="form-container">
          <tr>
            <td title="<?=$Tdb86_cep?>">
              <?=$Ldb86_cep?>
            </td>
            <td nowrap>
              <?php
              db_input( "db86_cep", 10, $Idb86_cep, true, "text", 4, "", "chave_db86_cep" );
              ?>
            </td>
          </tr>
          <tr>
            <td title="<?=$Tdb72_descricao?>">
              <label>Município:</label>
            </td>
            <td nowrap>
              <?php
              db_input( "db72_descricao", 10, $Idb72_descricao, true, "text", 4, "", "chave_db72_descricao" );
              ?>
            </td>
          </tr>
          <tr>
            <td title="<?=$Tdb74_descricao?>">
              <label>Logradouro:</label>
            </td>
            <td nowrap>
              <?php
              db_input( "db74_descricao", 10, $Idb74_descricao, true, "text", 4, "", "chave_db74_descricao" );
              ?>
            </td>
          </tr>
          <tr>
            <td title="<?=$Tdb73_descricao?>">
              <label>Bairro:</label>
            </td>
            <td nowrap>
              <?php
              db_input( "db73_descricao", 10, $Idb73_descricao, true, "text", 4, "", "chave_db73_descricao" );
              ?>
            </td>
          </tr>
          <tr>
            <td title="<?=$Tdb71_sigla?>">
              <?=$Ldb71_sigla?>
            </td>
            <td nowrap>
              <?php
              db_input( "db71_sigla", 10, $Idb71_sigla, true, "text", 4, "", "chave_db71_sigla" );
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_cadendercompleta.hide();">
    </form>
  </div>
  <div class="container">
    <table>
      <tr>
        <td align="center" valign="top">
          <?php
          $sCampos    = "db74_descricao as DL_logradouro, db73_descricao as DL_bairro, db72_descricao as DL_municipio";
          $sCampos   .= ", db71_sigla, db86_cep, j88_descricao as DB_tiporua, db300_sequencial as DB_estado";
          $sCampos   .= ", db125_sequencial as DB_municipio";
          $sOrdenacao = "db74_descricao";
          $sQuery     = "sql_query_cep";
          $lPesquisou = false;
          $aWhere     = array();

          if( isset( $lSistemaExterno ) ) {

            $sQuery   = "sql_query_cep_sistema_externo";
            $aWhere[] = "db125_db_sistemaexterno = 4 "; // só municipios da base do IBGE
            $aWhere[] = "db300_db_sistemaexterno = 8 "; // só estados da base do DNE
          }

          if( !isset( $pesquisa_chave ) ) {

            if( isset( $chave_db86_cep ) && trim( $chave_db86_cep ) != '' ) {

              $lPesquisou = true;
              $aWhere[]   = "db86_cep like '{$chave_db86_cep}%'";
            }

            if( isset( $chave_db72_descricao ) && trim( $chave_db72_descricao ) != '' ) {

              $lPesquisou = true;
              $aWhere[]   = "db72_descricao ilike '%{$chave_db72_descricao}%'";
            }

            if( isset( $chave_db74_descricao ) && trim( $chave_db74_descricao ) != '' ) {

              $lPesquisou = true;
              $aWhere[]   = "db74_descricao ilike '%{$chave_db74_descricao}%'";
            }

            if( isset( $chave_db73_descricao ) && trim( $chave_db73_descricao ) != '' ) {

              $lPesquisou = true;
              $aWhere[]   = "db73_descricao ilike '%{$chave_db73_descricao}%'";
            }

            if( isset( $chave_db71_sigla ) && trim( $chave_db71_sigla ) != '' ) {

              $lPesquisou = true;
              $aWhere[]   = "db71_sigla = '{$chave_db71_sigla}'";
            }

            $sWhere = implode( " AND ", $aWhere );
            $sSql   = $oDaoCadEnderRuaCep->{$sQuery}( "", $sCampos, $sOrdenacao, $sWhere );

            $repassa = array();

            if( $lPesquisou ) {
              db_lovrot( $sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa );
            }
          } else {

            $sScript = "<script>".$funcao_js."('',false);</script>";

            if( $pesquisa_chave != null && $pesquisa_chave != "" ) {

              $sSqlCadEnderRuaCep = $oDaoCadEnderRuaCep->sql_query($pesquisa_chave);
              $rsCadEnderRuaCep   = db_query( $sSqlCadEnderRuaCep );

              if( $rsCadEnderRuaCep && pg_num_rows( $rsCadEnderRuaCep ) > 0 ) {

                db_fieldsmemory( $rsCadEnderRuaCep, 0 );
                $sScript =  "<script>".$funcao_js."('$db86_cep',false);</script>";
              } else {
               $sScript = "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
              }
            }

            if( isset( $lSistemaExterno ) ) {

              if( !empty( $sCep ) ) {
                $aWhere[] = "db86_cep = '{$sCep}'";
              }

              $sCamposRetorno = "(true, 'CEP( {$sCep} ) não Encontrado')";
              $sWhere         = implode( " AND ", $aWhere );

              $sSqlCadEnderRuaCep = $oDaoCadEnderRuaCep->{$sQuery}( null, $sCampos, $sOrdenacao, $sWhere );
              $rsCadEnderRuaCep   = db_query( $sSqlCadEnderRuaCep );

              if( $rsCadEnderRuaCep && pg_num_rows( $rsCadEnderRuaCep ) > 0 ) {

                db_fieldsmemory( $rsCadEnderRuaCep, 0 );

                $sCamposRetorno  = "(false, '{$db86_cep}', '{$dl_logradouro}', '{$dl_bairro}', '{$dl_municipio}'";
                $sCamposRetorno .= ", '{$db71_sigla}', '{$db_tiporua}', '{$db_estado}', '{$db_municipio}')";
              }

              $sScript =  "<script>{$funcao_js}{$sCamposRetorno};</script>";
            }

            echo $sScript;
          }
          ?>
         </td>
       </tr>
    </table>
  </div>
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
<script>
js_tabulacaoforms("form2","chave_db86_cep",true,1,"chave_db86_cep",true);
</script>
<script type="text/javascript">

$('chave_db86_cep').addClassName( 'field-size2' );
$('chave_db72_descricao').addClassName( 'field-size7' );
$('chave_db74_descricao').addClassName( 'field-size7' );
$('chave_db73_descricao').addClassName( 'field-size7' );
$('chave_db71_sigla').addClassName( 'field-size2' );

(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
