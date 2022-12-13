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
require_once(modification("classes/db_passagemdestino_classe.php"));

db_postmemory( $_POST );
parse_str( $_SERVER["QUERY_STRING"] );

$clpassagemdestino = new cl_passagemdestino;
$clpassagemdestino->rotulo->label("tf37_sequencial");
$clpassagemdestino->rotulo->label("tf37_destino");
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
          <td>
            <label><?=$Ltf37_sequencial?></label>
          </td>
          <td>
            <?php
            db_input( "tf37_sequencial", 10, $Itf37_sequencial, true, "text", 4, "", "chave_tf37_sequencial" );
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <label><?=$Ltf37_destino?></label>
          </td>
          <td>
            <?php
            db_input( "tf37_destino", 10, $Itf37_destino, true, "text", 4, "", "chave_tf37_destino" );
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_passagemdestino.hide();">
  </form>
      <?php
      if( !isset( $pesquisa_chave ) ) {

        if( isset( $campos ) == false ) {

          if( file_exists( "funcoes/db_func_passagemdestino.php" ) == true ) {
            include(modification("funcoes/db_func_passagemdestino.php"));
          } else {
            $campos = "passagemdestino.*";
          }
        }

        if( isset( $chave_tf37_sequencial ) && ( trim( $chave_tf37_sequencial ) != "" ) ) {
	        $sql = $clpassagemdestino->sql_query( $chave_tf37_sequencial, $campos, "tf37_sequencial" );
        } else if( isset( $chave_tf37_destino ) && ( trim( $chave_tf37_destino ) != "" ) ) {
	        $sql = $clpassagemdestino->sql_query( "", $campos, "tf37_destino", " tf37_destino like '$chave_tf37_destino%' " );
        } else {
          $sql = $clpassagemdestino->sql_query( "", $campos, "tf37_sequencial", "" );
        }

        $repassa = array();
        if( isset( $chave_tf37_destino ) ) {
          $repassa = array(
                            "chave_tf37_sequencial" => $chave_tf37_sequencial,
                            "chave_tf37_destino"    => $chave_tf37_destino
                          );
        }

        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      } else {

        if( $pesquisa_chave != null && $pesquisa_chave != "" ) {

          $result = $clpassagemdestino->sql_record($clpassagemdestino->sql_query($pesquisa_chave));

          if( $clpassagemdestino->numrows != 0 ) {

            db_fieldsmemory( $result, 0 );
            echo "<script>".$funcao_js."('$tf37_destino',false);</script>";
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
js_tabulacaoforms("form2","chave_tf37_destino",true,1,"chave_tf37_destino",true);
</script>