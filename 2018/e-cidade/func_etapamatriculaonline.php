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
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory( $_POST );
parse_str    ( $_SERVER["QUERY_STRING"] );

$oGet = db_utils::postMemory( $_GET );

$oDaoVagas = new cl_vagas();
$oDaoSerie = new cl_serie;
$oDaoSerie->rotulo->label( "ed11_i_codigo" );
$oDaoSerie->rotulo->label( "ed11_c_descr" );

define( 'MENSAGEM_FUNC_ETAPAMATRICULA', 'educacao.matriculaonline.func_etapamatriculaonline.' );
define('INSCRITOS',      1);
define('DESIGNADOS',     2);
define('NAO_DESIGNADOS', 3);

if ( !isset($oGet->iFase) || empty($oGet->iFase) ) {

  db_msgbox( _M( MENSAGEM_FUNC_ETAPAMATRICULA .'informe_fase' ) );
  echo "<script>parent.db_iframe_etapa_matricula_online.hide();</script>";
  return false; 
}

if ( !isset($oGet->iTipoConsulta) || empty($oGet->iTipoConsulta) ) {

  db_msgbox( _M( MENSAGEM_FUNC_ETAPAMATRICULA .'erro_buscar_etapas' ) );
  echo "<script>parent.db_iframe_etapa_matricula_online.hide();</script>";
  return false; 
}
?>

<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link type="text/css" rel="stylesheet" href="estilos.css">
  <link type="text/css" rel="stylesheet" href="estilos/DBFormularios.css">
  <script type="text/javascript" src="scripts/scripts.js"></script>
  <script type="text/javascript" src="scripts/prototype.js"></script>
  <script type="text/javascript" src="scripts/strings.js"></script>
  </head>
<body>
  <form name="form2" method="post" action="" class="container">
    <fieldset>
      <legend>Filtros:</legend>
      <table class="subtable">
        <tr>
          <td nowrap title="<?=$Ted11_i_codigo?>">
            <?=$Led11_i_codigo?>
          </td>
          <td nowrap>
            <?php
              db_input( "ed11_i_codigo", 10, $Ied11_i_codigo, true, "text", 4, "", "chave_ed11_i_codigo");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Ted11_c_descr?>">
            <?=$Led11_c_descr?>
          </td>
          <td nowrap>
            <?php
              db_input( "ed11_c_descr", 30, $Ied11_c_descr, true, "text", 4, "", "chave_ed11_c_descr");
            ?> 
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar"    type="reset"  id="limpar"     value="Limpar" >
    <input name="Fechar"    type="button" id="fechar"     value="Fechar" onClick="parent.db_iframe_etapa_matricula_online.hide();">
  </form>
  <?php
      
  $aWhere   = array();
  $aWhere[] = "mo10_fase = {$oGet->iFase} ";
  $sCampos  = "distinct ed11_i_codigo, ed10_c_descr, ed11_c_descr, ed10_i_codigo as db_ed10_i_codigo";
  $sOrder   = "ed10_i_codigo, ed11_i_codigo";

  if ( isset($oGet->iEscola) && !empty($oGet->iEscola)  ) {
    $aWhere[] = " mo10_escola = {$oGet->iEscola} ";
  }

  if ( in_array( $oGet->iTipoConsulta, array( DESIGNADOS, NAO_DESIGNADOS) ) ) {
    $aWhere[] = " mo04_processada is true ";
  }

  if ( $oGet->iTipoConsulta == INSCRITOS ) {
    $aWhere[] = " mo04_processada is false ";
  }

  if( !isset( $pesquisa_chave ) ) {

    if( isset( $chave_ed11_i_codigo ) && ( !empty( $chave_ed11_i_codigo ) ) ) {
      $aWhere[] = " ed11_i_codigo = {$chave_ed11_i_codigo} ";
    }

    if( isset( $chave_ed11_c_descr ) && ( !empty( $chave_ed11_c_descr ) ) ) {
      $aWhere[] = " ed11_c_descr like '{$chave_ed11_c_descr}%' ";
    }

    $sWhereVagas = implode(' and ', $aWhere);
    $sSqlVagas   = $oDaoVagas->sql_query_escola_serie_ensino( "", $sCampos, $sOrder, $sWhereVagas );

    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';

    $repassa = array();
    $rsVagas = db_lovrot($sSqlVagas, 15, "()", "", $funcao_js, "", "NoMe", $repassa);

    echo '  </fieldset>';
    echo '</div>';

    if ( !$rsVagas ) {

      db_msgbox( _M( MENSAGEM_FUNC_ETAPAMATRICULA .'erro_buscar_etapas' ) );
      echo "<script>parent.db_iframe_etapa_matricula_online.hide();</script>";
      return false;
    }
  } else {

    if( !empty($pesquisa_chave) ) {

      $aWhere[]       = " ed11_i_codigo = {$pesquisa_chave} ";
      $sWhereVagas    = implode(' and ', $aWhere);
      $sSqlVagas      = $oDaoVagas->sql_query_escola_serie_ensino( "", $sCampos, $sOrder, $sWhereVagas );
      $rsVagas        = db_query( $sSqlVagas );

      if ( !$rsVagas ) {

        db_msgbox( _M( MENSAGEM_FUNC_ETAPAMATRICULA .'erro_buscar_etapa_solicitada' ) );
        return false;
      }

      if( pg_num_rows($rsVagas) != 0 ) {

        $oDadosVagas = db_utils::fieldsMemory( $rsVagas, 0 );
        echo "<script>".$funcao_js."('{$oDadosVagas->ed11_c_descr}',false);</script>";
      } else {
        echo "<script>".$funcao_js."('Chave({$pesquisa_chave}) não Encontrado',true);</script>";
      }
    } else {
      echo "<script>".$funcao_js."('',false);</script>";
    }
  }
  ?>
</body>
</html>
<script>

$('limpar').onclick = function() {
    
  $('chave_ed11_i_codigo').value = '';
  $('chave_ed11_c_descr').value   = '';
  $('pesquisar2').click();
}

</script>