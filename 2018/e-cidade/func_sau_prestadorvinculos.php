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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory( $_POST );
parse_str( $_SERVER["QUERY_STRING"] );

$clsau_prestadorvinculos = new cl_sau_prestadorvinculos;
$clsau_prestadorvinculos->rotulo->label();

$oRotulo = new rotulocampo();
$oRotulo->label( "sd63_c_procedimento" );
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
          <td><label><?=$Ls111_i_codigo?></label></td>
          <td><? db_input("s111_i_codigo",10,$Is111_i_codigo,true,"text",4,"","chave_s111_i_codigo"); ?></td>
        </tr>
        <tr>
          <td width="4%" align="right" nowrap title="<?=$Ts111_procedimento?>">
            <?=$Ls111_procedimento?>
          </td>
          <td width="96%" align="left" nowrap>
            <?
         db_input("sd63_c_procedimento",10,$Isd63_c_procedimento,true,"text",4,"","chave_sd63_c_procedimento");
         ?>
          </td>
        </tr>
        <tr>
          <td width="4%" align="right" nowrap title="<?=$Ts111_i_prestador?>">
            <?=$Ls111_i_prestador?>
          </td>
          <td width="96%" align="left" nowrap>
            <?
         db_input("s111_i_prestador",10,$Is111_i_prestador,true,"text",4,"","chave_s111_i_prestador");
         ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_sau_prestadorvinculos.hide();">
  </form>
  <?php
  $where    = "";
  $aWhere   = array();
  $aWhere[] = "s111_c_situacao = 'A'";


  if( isset( $chave_sd63_c_procedimento ) && ( ( int ) $chave_sd63_c_procedimento != 0 ) ) {
    $aWhere[] = "sd63_c_procedimento ilike '{$chave_sd63_c_procedimento}%'";
  }

  if( isset( $chave_s111_i_prestador ) && ( ( int ) $chave_s111_i_prestador != 0 ) ) {
    $aWhere[] = "s111_i_prestador = {$chave_s111_i_prestador}";
  }

  if( !isset( $pesquisa_chave ) ) {

    if( isset( $campos ) == false ) {

      if( file_exists( "funcoes/db_func_sau_prestadorvinculos.php" ) == true ) {
        include(modification("funcoes/db_func_sau_prestadorvinculos.php"));
      } else {
        $campos = "sau_prestadorvinculos.*";
      }
    }

    if( isset( $lProcedimentosAgendamento ) ) {
      $campos = "s111_i_codigo, sd63_i_codigo, sd63_c_procedimento, sd63_c_nome, sd63_i_mescomp, sd63_i_anocomp";
    }

    if( isset( $chave_s111_i_codigo ) && ( trim( $chave_s111_i_codigo ) != "" ) ) {
      $aWhere[] = "s111_i_codigo = {$chave_s111_i_codigo}";
    }

    $sWhere = implode( " AND ", $aWhere );
    $sql    = $clsau_prestadorvinculos->sql_query( "", $campos, "s111_i_codigo", $sWhere );

    $repassa = array();
    if( isset( $chave_s111_i_codigo ) ) {
      $repassa = array( "chave_s111_i_codigo" => $chave_s111_i_codigo );
    }

    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';
      db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
    echo '  </fieldset>';
    echo '</div>';
  } else {

    if( $pesquisa_chave != null && $pesquisa_chave != "" ) {

      $sWhere  = "s111_i_codigo = {$pesquisa_chave}";
      if( isset( $lProcedimentosAgendamento ) ) {
        $sWhere  = "sd63_c_procedimento = '{$pesquisa_chave}'";
      }

      $aWhere[] = $sWhere;

      if ( isset( $chave_s111_i_prestador ) && ( ( int ) $chave_s111_i_prestador != 0 ) ) {
        $aWhere[]  = "s111_i_prestador = '{$chave_s111_i_prestador}'";
      }

      $sWhere = implode( " AND ", $aWhere );

      $sSql   = $clsau_prestadorvinculos->sql_query( null, "*", null, $sWhere );
      $result = $clsau_prestadorvinculos->sql_record( $sSql );

      if( $clsau_prestadorvinculos->numrows != 0 ) {

        db_fieldsmemory( $result, 0 );
        if( isset( $lProcedimentosAgendamento ) ) {
          echo "<script>".$funcao_js."({$s111_i_codigo},false, {$sd63_i_codigo}, '{$sd63_c_nome}');</script>";
        }
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
js_tabulacaoforms("form2","chave_s111_i_codigo",true,1,"chave_s111_i_codigo",true);
</script>

<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
