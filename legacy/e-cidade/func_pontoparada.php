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

$clpontoparada = new cl_pontoparada;
$clpontoparada->rotulo->label("tre04_sequencial");
$clpontoparada->rotulo->label("tre04_nome");
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
            <td>
              <label for="chave_tre04_sequencial"><?=$Ltre04_sequencial?></label>
            </td>
            <td>
              <?php
              db_input( "tre04_sequencial", 10, $Itre04_sequencial, true, "text", 4, "", "chave_tre04_sequencial" );
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label for="chave_tre04_nome">Nome do Ponto de Parada:</label>
            </td>
            <td>
              <?php
              db_input( "tre04_nome", 10, $Itre04_nome, true, "text", 4, "", "chave_tre04_nome" );
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="button" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_pontoparada.hide();">
    </form>
  </div>
  <div class="container">
    <table>
      <tr>
        <td>
          <?php
          $aWhere = array();
          $sQuery = "sql_query";

          if( !isset( $pesquisa_chave ) ) {

            if( isset( $campos ) == false ) {

              if( file_exists( "funcoes/db_func_pontoparada.php" ) == true ) {
                include(modification("funcoes/db_func_pontoparada.php"));
              } else {
                $campos = "pontoparada.*";
              }
            }

            if( isset( $lPontoParadaLinhaTransporteItinerario ) ) {

              $sQuery   = "sql_query_ponto_parada_linha_transporte";
              $campos   = "tre04_sequencial, tre04_nome, tre04_abreviatura, tre04_pontoreferencia, tre04_latitude";
              $campos  .= ", tre04_longitude, tre11_sequencial";
              $campos  .= ", case when tre04_tipo = 1 then 'Departamento' when tre04_tipo = 2 then 'Parada' else 'Escola de Procedência' end as tre04_tipo";
              $aWhere[] = "tre06_sequencial = {$iLinhaTransporte} and tre09_tipo = {$iItinerario}";
            }

            if( isset( $chave_tre04_sequencial ) && ( trim( $chave_tre04_sequencial ) != "" ) ) {
              $aWhere[] = "tre04_sequencial = {$chave_tre04_sequencial}";
            }

            if( isset( $chave_tre04_nome ) && ( trim( $chave_tre04_nome ) != "" ) ) {
              $aWhere[] = "tre04_nome like '{$chave_tre04_nome}%'";
            }

            $repassa = array();
            if( isset( $chave_tre04_sequencial ) ) {
              $repassa = array( "chave_tre04_sequencial" => $chave_tre04_sequencial, "tre11_sequencial" => $tre11_sequencial );
            }

            $sWhere = implode( ' AND ', $aWhere );
            $sSql   = $clpontoparada->{$sQuery}( null, $campos, "tre04_sequencial", $sWhere );

            db_lovrot( $sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa );
          } else {

            if( $pesquisa_chave != null && $pesquisa_chave != "" ) {

              $result = $clpontoparada->sql_record( $clpontoparada->sql_query( $pesquisa_chave ) );

              if( isset( $lPontoParadaLinhaTransporteItinerario ) ) {

                $sWhere  = "     tre04_sequencial = {$pesquisa_chave}";
                $sWhere .= " and tre06_sequencial = {$iLinhaTransporte}";
                $sWhere .= " and tre09_tipo       = {$iItinerario}";
                $sSql    = $clpontoparada->sql_query_ponto_parada_linha_transporte( null, "*", null, $sWhere );
                $result  = $clpontoparada->sql_record( $sSql );
              }

              if( $clpontoparada->numrows != 0 ) {

                db_fieldsmemory( $result, 0 );
                echo "<script>".$funcao_js."('$tre04_sequencial',false, '$tre04_nome', '$tre11_sequencial');</script>";
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
js_tabulacaoforms("form2","chave_tre04_sequencial",true,1,"chave_tre04_sequencial",true);

$('chave_tre04_sequencial').className = 'field-size2';
$('chave_tre04_nome').className       = 'field-size7';

$('limpar').onclick = function() {
  limpaCampos();
};

function limpaCampos() {

  $('chave_tre04_sequencial').value = '';
  $('chave_tre04_nome').value       = '';
  document.form2.submit();
}
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
