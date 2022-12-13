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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oDaoRecHumano = new cl_rechumano();
$oDaoRotulo    = new rotulocampo();
$oDaoRotulo->label( "ed20_i_codigo" );
$oDaoRotulo->label( "z01_numcgm" );
$oDaoRotulo->label( "z01_nome" );
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC>
  <div class="container">
    <form class="form-container" name="form" method="post">
      <fieldset>
        <legend>Filtros</legend>
        <table>
          <tr>
            <td>
              <label class="bold">
                Recurso Humano:
              </label>
            </td>
            <td>
              <?php
                db_input( 'ed20_i_codigo', 10, $Ied20_i_codigo, true, "text", 1, "", "chave_ed20_i_codigo" );
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label class="bold">
                CGM:
              </label>
            </td>
            <td>
              <?php
                db_input( 'z01_numcgm', 10, $Iz01_numcgm, true, "text", 1, "", "chave_z01_numcgm" );
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label class="bold">
                Nome:
              </label>
            </td>
            <td>
              <?php
                db_input( 'z01_nome', 40, $Iz01_nome, true, "text", 1, "", "chave_z01_nome" );
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input id="btnPesquisar" name="btnPesquisar" type="submit" value="Pesquisar" />
      <input id="btnFechar"    name="btnFechar"    type="button" value="Fechar" onClick="parent.db_iframe_diretor.hide();">
    </form>
  </div>
  <div class="container">
    <?
    $iEscola = db_getsession("DB_coddepto");

    $sCampos = "rechumano.ed20_i_codigo,
                  case
                       when ed20_i_tiposervidor = 1
                       then cgmrh.z01_nome
                       else cgmcgm.z01_nome
                   end as z01_nome,
                  atividaderh.ed01_c_exigeato,
                  case
                       when ed20_i_tiposervidor = 1
                       then rechumanopessoal.ed284_i_rhpessoal
                       else rechumanocgm.ed285_i_cgm
                   end as dl_identificacao,
                  case
                       when ed20_i_tiposervidor = 1
                       then cgmrh.z01_cgccpf
                       else cgmcgm.z01_cgccpf
                   end as dl_cpf,
                  (select ativrh.ed01_c_descr
                     from rechumanoativ as ativ
                          inner join atividaderh as ativrh on ativrh.ed01_i_codigo = ativ.ed22_i_atividade
                    where ativ.ed22_i_rechumanoescola = ed75_i_codigo
                      and ed01_i_funcaoadmin = 2
                    order by ed01_c_regencia desc
                    limit 1) as dl_atividade";

    if( !isset( $pesquisa_chave ) ) {

      $sWhere   = "ed75_i_escola = {$iEscola} AND ed01_i_funcaoadmin = 2";

      if ( isset( $chave_ed20_i_codigo ) && !empty( $chave_ed20_i_codigo ) ) {
        $sWhere .= " AND rechumano.ed20_i_codigo = {$chave_ed20_i_codigo}";
      }

      if ( isset( $chave_z01_numcgm ) && !empty( $chave_z01_numcgm ) ) {
        $sWhere .= " AND z01_numcgm = {$chave_z01_numcgm}";
      }

      if ( isset( $chave_z01_nome ) && !empty( $chave_z01_nome ) ) {
        $sWhere .= " AND (cgmrh.z01_nome ilike '$chave_z01_nome%' OR cgmcgm.z01_nome ilike '$chave_z01_nome%')";
      }

      $sSql     = $oDaoRecHumano->sql_query_escola( "", "distinct {$sCampos}", "z01_nome", $sWhere );
      $aRepassa = array();

      db_lovrot( @$sSql, 15, "()", "", $funcao_js, "", "NoMe", $aRepassa );
    } else {

      if( $pesquisa_chave != null && $pesquisa_chave != "" ) {

        $sWhere      = "ed20_i_codigo = {$pesquisa_chave} AND ed75_i_escola = {$iEscola}";
        $sSql        = $oDaoRecHumano->sql_query_escola( "", $campos, "z01_nome", $sWhere );
        $rsRecHumano = $oDaoRecHumano->sql_record( $sSql );
        if( $oDaoRecHumano->numrows != 0 ) {

          db_fieldsmemory( $rsRecHumano, 0 );
          echo "<script>".$funcao_js."('{$ed20_i_codigo}', '{$z01_nome}', '{$dl_identificacao}', '{$dl_cpf}', '{$dl_atividade}', false);</script>";
        } else {
          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
        }
      } else {
        echo "<script>".$funcao_js."('',false);</script>";
      }
    }
    ?>
  </div>
</body>
</html>
<script>
  js_tabulacaoforms( "form", "chave_ed20_i_codigo", true, 1, "chave_ed20_i_codigo", true );
</script>