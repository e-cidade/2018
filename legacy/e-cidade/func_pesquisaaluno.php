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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$oDaoAluno              = new cl_aluno();
$oDaoProgressaoParcial  = new cl_progressaoparcialaluno();
$oDaoAluno->rotulo->label();

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body class='body-default'>
  <form name="form2" method="post" class="container">
    <fieldset>
      <legend>Filtros</legend>
      <table class="form-container">
        <tr>
          <td class='bold'>Código:</td>
          <td>
            <?php
              db_input("ed47_i_codigo",11,$Ied47_i_codigo,true,"text",4,"","chave_ed47_i_codigo");
            ?>
          </td>
        </tr>
        <tr>
          <td class='bold'>Nome:</td>
          <td>
            <?php
              db_input("ed47_v_nome",11,$Ied47_v_nome,true,"text",4,"","chave_ed47_v_nome");
            ?>
          </td>
        </tr>
      </table>
    </fieldset>

    <input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar">
    <input name="limpar"    type="button" id="limpar" value="Limpar" onClick="js_limpar();">
    <input name="Fechar"    type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_pesquisaaluno.hide();">

  </form>

  <div class="subcontainer">
    <?php

      $sCampos = "distinct ed47_i_codigo, ed47_v_nome";
      $aWhere  = array();

      /**
       * Filtros realizados no formulário
       */
      if ( !empty($chave_ed47_i_codigo) ) {
        $aWhere[] = "ed47_i_codigo = {$chave_ed47_i_codigo}";
      }

      if ( !empty($chave_ed47_v_nome) )  {
        $aWhere[] = "ed47_v_nome ilike '$chave_ed47_v_nome%' ";
      }

      $sWhere = implode(" and ", $aWhere);
      $sOrdem = "ed47_v_nome";

      if ( !isset($pesquisa_chave) ) {

        $aRepassa                         = array();
        $aRepassa["chave_ed47_i_codigo"]  = !empty($chave_ed47_i_codigo) ? $chave_ed47_i_codigo : '';
        $aRepassa["chave_ed47_v_nome"]    = !empty($chave_ed47_v_nome) ? $chave_ed47_v_nome : '';

        $sSql = $oDaoProgressaoParcial->sql_query_aluno_em_progressao( null, $sCampos, $sOrdem, $sWhere );

        db_lovrot( $sSql, 15, "()", "", $funcao_js, "", "NoMe", $aRepassa, false);
      } else if ( !empty($pesquisa_chave) ) {

        $sWhere              = "ed47_i_codigo = {$pesquisa_chave}";
        $sSqlAlunoProgressao = $oDaoProgressaoParcial->sql_query_aluno_em_progressao( null, $sCampos, null, $sWhere );
        $rsAlunoProgressao   = $oDaoProgressaoParcial->sql_record( $sSqlAlunoProgressao );

        $sRertorno = "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
        if( $oDaoProgressaoParcial->numrows != 0 ) {

          db_fieldsmemory( $rsAlunoProgressao, 0 );
          $sRertorno = "<script>" . $funcao_js . "($ed47_i_codigo,'$ed47_v_nome');</script>";
        }
        echo $sRertorno;
      } else {
        echo "<script>".$funcao_js."('','');</script>";
      }
    ?>
  </div>
</body>
<script type="text/javascript">

  $("chave_ed47_i_codigo").addClassName("field-size2");
  $("chave_ed47_v_nome").addClassName("field-size7");

  function js_limpar() {

    $("chave_ed47_i_codigo").value = "";
    $("chave_ed47_v_nome").value   = "";
  }

</script>
</html>