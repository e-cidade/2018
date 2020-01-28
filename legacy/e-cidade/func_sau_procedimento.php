<?
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
require_once(modification("classes/db_sau_procedimento_ext_classe.php"));

db_postmemory( $_POST );
parse_str( $_SERVER["QUERY_STRING"] );

$clsau_procedimento = new cl_sau_procedimento_ext;
$clsau_procedimento->rotulo->label("sd63_i_codigo");
$clsau_procedimento->rotulo->label("sd63_c_nome");
$clsau_procedimento->rotulo->label("sd63_c_procedimento");

$oDaoPrestadorHorarios = new cl_sau_prestadorhorarios();

/**
 * Busca o último mês e ano que foi atualizada a tabela de procedimentos
 */
$oDaoSauAtualiza = new cl_sau_atualiza();
$sSqlSauAtualiza = $oDaoSauAtualiza->sql_query_file( null, 's100_i_mescomp, s100_i_anocomp', "s100_i_codigo desc limit 1" );
$rsSauAtualiza   = db_query( $sSqlSauAtualiza );

if( $rsSauAtualiza && pg_num_rows( $rsSauAtualiza ) > 0 ) {
  $oDadosMesAno = db_utils::fieldsMemory( $rsSauAtualiza, 0 );
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
          <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="Código">
              <label for="codigo"><?=$Lsd63_i_codigo?></label>
            </td>
            <td width="96%" align="left" nowrap>
              <input title="Código" name="chave_sd63_i_codigo" id="codigo" value="" size="5"
              maxlength="10" onblur="js_ValidaMaiusculo(this,'f',event);" oninput="js_ValidaCampos(this,1,'Código','t','f',event);"
              onkeydown="return js_controla_tecla_enter(this,event);" autocomplete="on" tabindex="1" type="text">
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="Código do procedimento">
              <label for="procedimento"><?=$Lsd63_c_procedimento?></label>
            </td>
            <td width="96%" align="left" nowrap>
              <input title="Código do procedimento" name="chave_sd63_c_procedimento" id="procedimento" value="" size="10"
              maxlength="10" onblur="js_ValidaMaiusculo(this,'f',event);" oninput="js_ValidaCampos(this,3,'Procedimento','t','f',event);"
              onkeydown="return js_controla_tecla_enter(this,event);" autocomplete="off" tabindex="2" type="text">
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="Nome do procedimento">
              <label for="nome"><?=$Lsd63_c_nome?></label>
            </td>
            <td width="96%" align="left" nowrap>
              <input title="Nome do procedimento" name="chave_sd63_c_nome" id="nome" value="" size="60"
              maxlength="250" style="text-transform:uppercase;" onblur="js_ValidaMaiusculo(this,'t',event);" oninput="js_ValidaCampos(this,0,'Nome','t','t',event);"
              onkeydown="return js_controla_tecla_enter(this,event);" autocomplete="off" tabindex="3" type="text">
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="button" id="limpar" value="Limpar" onClick="js_limpar();">
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_sau_procedimento.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?php
      $aWhere      = array();
      $sWhere      = '';
      $sOrdernacao = "sd63_i_anocomp desc, sd63_i_mescomp desc, sd63_c_nome ";

      if( isset( $chave_sd04_i_cbo ) ) {

        $sWhere   = "(    sd96_i_codigo = {$chave_sd04_i_cbo}";
        $sWhere  .= "  or (select count(*) from proccbo where sd96_i_procedimento = sd63_i_codigo) = 0 )";
        $aWhere[] = $sWhere;
      }

      if( !isset( $pesquisa_chave ) ) {

        if( isset( $campos ) == false ) {

          if( file_exists( "funcoes/db_func_sau_procedimento.php" ) == true ) {
            include(modification("funcoes/db_func_sau_procedimento.php"));
          } else {
            $campos = "sau_procedimento.*";
          }
        }

        if (isset($chave_sd62_c_formaorganizacao) && $chave_sd62_c_formaorganizacao != '') {

          $sWhere    = " sd63_c_procedimento like '";
          $sWhere   .= $chave_sd60_c_grupo.$chave_sd61_c_subgrupo.$chave_sd62_c_formaorganizacao;
          $sWhere   .= "%' ";
          $aWhere[]  = $sWhere;
        }

        if (isset($chave_sd61_c_subgrupo) && $chave_sd61_c_subgrupo != '') {

          $sWhere    = " sd63_c_procedimento like '";
          $sWhere   .= $chave_sd60_c_grupo.$chave_sd61_c_subgrupo;
          $sWhere   .= "%' ";
          $aWhere[]  = $sWhere;
        }

        if (isset($chave_sd60_c_grupo) && $chave_sd60_c_grupo != '') {

          $sWhere    = " sd63_c_procedimento like '";
          $sWhere   .= $chave_sd60_c_grupo;
          $sWhere   .= "%' ";
          $aWhere[]  = $sWhere;
        }

        if( isset( $lProcedimentosAgendamento ) ) {

          $campos   = "distinct sd63_i_codigo, sd63_c_procedimento, sd63_c_nome, sd63_i_mescomp, sd63_i_anocomp";
          $aWhere[] = "sd63_c_procedimento ilike '02%'";
        }

        if ( isset ($lVinculaProcedimentos) && isset( $oDadosMesAno )) {

          $aWhere[] = " sd63_i_mescomp = {$oDadosMesAno->s100_i_mescomp}";
          $aWhere[] = " sd63_i_anocomp = {$oDadosMesAno->s100_i_anocomp}";
        }

        $sOrdernacao = "sd63_i_anocomp desc, sd63_i_mescomp desc, sd63_c_nome ";
        if( isset( $chave_sd63_i_codigo ) && ( trim( $chave_sd63_i_codigo ) != "" ) ) {
          $aWhere[] = "sd63_i_codigo = {$chave_sd63_i_codigo}";
        }

        if( isset( $chave_sd63_c_procedimento ) && ( trim( $chave_sd63_c_procedimento ) != "" ) ) {
          $aWhere[] = "sd63_c_procedimento ilike '{$chave_sd63_c_procedimento}%'";
        }

        if( isset( $chave_sd63_c_nome ) && ( trim( $chave_sd63_c_nome ) != "" ) ) {
          $aWhere[] = " sd63_c_nome ilike '{$chave_sd63_c_nome}%'";
        }

        if (isset($chave_sd60_c_grupo) && $chave_sd60_c_grupo != '') {
          $aWhere[] = " sd63_c_nome ilike '{$chave_sd63_c_nome}%'";
        }

        $sWhere = implode( ' AND ', $aWhere );

        if ( isset($lProcedimentosVinculadosPrestadora) ) {

          $sWhere .= " AND s111_c_situacao like 'A'";
          $sql     = $oDaoPrestadorHorarios->sql_query("", $campos, $sOrdernacao, $sWhere);
        } else {
          $sql = $clsau_procedimento->sql_query_ext( "", $campos, $sOrdernacao, $sWhere );
        }

        if (isset($nao_mostra)) {

          $sSep    = '';
          $aFuncao = explode('|', $funcao_js);
          $rs      =  $clsau_procedimento->sql_record($sql);

          if ($clsau_procedimento->numrows == 0) {
	          die('<script>'.$aFuncao[0]."(true,'Chave(".$chave_sd63_c_procedimento.") não Encontrado');</script>");
          } else {

            db_fieldsmemory($rs, 0);
            $sFuncao = $aFuncao[0].'(';

            for ($iCont = 1; $iCont < count($aFuncao); $iCont++) {

              $sFuncao .= $sSep.'"'.eval('return @$'.$aFuncao[$iCont].';').'"';
              $sSep     = ', ';
            }

            $sFuncao  = substr($sFuncao, 0, strlen($sFuncao));
            $sFuncao .= ');';
            die("<script>".$sFuncao.'</script>');
          }
        }

        $repassa = array();
        if( isset( $chave_sd63_c_nome ) ) {
          $repassa = array( "chave_sd63_i_codigo" => $chave_sd63_i_codigo, "chave_sd63_c_nome" => $chave_sd63_c_nome );
        }

        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);

      } else {

        if( $pesquisa_chave != null && $pesquisa_chave != "" ) {

          if( isset( $lProcedimentosAgendamento ) ) {

            $campos   = "sd63_i_codigo, sd63_c_procedimento, sd63_c_nome, sd63_i_mescomp, sd63_i_anocomp";
            $aWhere[] = "sd63_c_procedimento = '{$pesquisa_chave}'";
          }

          if ( isset ($lVinculaProcedimentos) && isset( $oDadosMesAno )) {

            $aWhere[] = " sd63_i_mescomp = {$oDadosMesAno->s100_i_mescomp}";
            $aWhere[] = " sd63_i_anocomp = {$oDadosMesAno->s100_i_anocomp}";
          }

          $sWhere = implode( ' AND ', $aWhere );
          $sSql   = $clsau_procedimento->sql_query_ext( "", $campos, $sOrdernacao, $sWhere );
          $result = $clsau_procedimento->sql_record( $sSql );

          if( $clsau_procedimento->numrows != 0 ) {

            db_fieldsmemory( $result, 0 );
            echo "<script>".$funcao_js."('$sd63_c_nome',false,$sd63_i_codigo);</script>";

            if (isset( $lCotaMensal )) {
              echo "<script>".$funcao_js."({$sd63_i_codigo},false, '{$sd63_c_nome}');</script>";
            }

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
</body>
</html>
<script>
function js_limpar() {

  document.form2.chave_sd63_i_codigo.value       = "";
  document.form2.chave_sd63_c_procedimento.value = "";
  document.form2.chave_sd63_c_nome.value         = "";
}

js_tabulacaoforms("form2","chave_sd63_c_nome",true,1,"chave_sd63_c_nome",true);
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
