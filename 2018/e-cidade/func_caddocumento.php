<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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
require_once(modification("classes/db_caddocumento_classe.php"));

db_postmemory( $_POST );
parse_str( $_SERVER["QUERY_STRING"] );

$clcaddocumento = new cl_caddocumento;
$clcaddocumento->rotulo->label("db44_sequencial");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body class="body-default">
  <div class="container">
    <form name="form2" method="post" action="">
      <fieldset>
        <legend>Filtros:</legend>
        <table class="form-container">
          <tr>
            <td>
              <label for="db44_sequencial">
                <?=$Ldb44_sequencial?>
              </label>
            </td>
            <td>
              <?php
                db_input("db44_sequencial", 10, $Idb44_sequencial, true, "text", 4, "", "chave_db44_sequencial");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>

      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_caddocumento.hide();">

    </form>
  </div>

  <div class="container">
    <table>
      <tr>
        <td>
        <?php

        $aWhere     = array( "db44_sequencial < 3000000" );
        $sOrdenacao = "db44_sequencial";

        if( !isset( $pesquisa_chave ) ) {

          if( isset( $campos ) == false ) {

            if(file_exists("funcoes/db_func_caddocumento.php")==true) {
              include(modification("funcoes/db_func_caddocumento.php"));
            } else {
              $campos = "caddocumento.*";
            }
          }



          if( isset( $chave_db44_sequencial ) && ( trim( $chave_db44_sequencial ) != "") ) {
            $aWhere[] = "db44_sequencial = {$chave_db44_sequencial}";
          }

          $repassa = array();
          if( isset( $chave_db44_sequencial ) ) {
            $repassa = array( "chave_db44_sequencial" => $chave_db44_sequencial );
          }

          $sWhere = implode( ' AND ', $aWhere );
          $sSql   = $clcaddocumento->sql_query( "", $campos, $sOrdenacao, $sWhere );

          db_lovrot( $sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa );
        } else {

          if( $pesquisa_chave != null && $pesquisa_chave != "" ) {

            $result = $clcaddocumento->sql_record( $clcaddocumento->sql_query( $pesquisa_chave ) );

            if( $clcaddocumento->numrows != 0 ) {

              db_fieldsmemory( $result, 0 );
              echo "<script>".$funcao_js."('$db44_sequencial',false);</script>";
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
js_tabulacaoforms("form2","chave_db44_sequencial",true,1,"chave_db44_sequencial",true);
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
