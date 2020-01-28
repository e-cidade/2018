<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">

  <div class="container">

    <form name="form1" method="post" action="" style="margin: 0">
    <fieldset>
      <legend>Tipo de Isenção</legend>

      <table>
        <tr>
          <td nowrap title="<?= @$Tx29_codisencaotipo ?>">
             <label class="bold" for="x29_codisencaotipo">Código:</label>
          </td>
          <td>
            <?php db_input('x29_codisencaotipo', 5, $Ix29_codisencaotipo, true, 'text', 3, "") ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?= @$Tx29_descr ?>">
             <label class="bold" for="x29_descr">Descrição:</label>
          </td>
          <td>
            <?php db_input('x29_descr', 40, $Ix29_descr, true, 'text', $db_opcao, "") ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?= @$Tx29_tipo ?>">
             <label class="bold" for="x29_tipo">Tipo:</label>
          </td>
          <td>
            <?php
              $aOpcoes = array(
                AguaTipoIsencao::TIPO_NORMAL   => 'Normal',
                AguaTipoIsencao::TIPO_IMUNE    => 'Imune',
                AguaTipoIsencao::TIPO_DESCONTO => 'Desconto',
                AguaTipoIsencao::TIPO_IDADE    => 'Idade/Aposentados',
              );
              db_select('x29_tipo', $aOpcoes, true, $db_opcao, "");
            ?>
          </td>
        </tr>
        </table>

    </fieldset>

    <?php
    $sNameAcao     = ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"));
    $sValueAcao    = ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"));
    $sDisabledAcao = ($db_botao == false ? "disabled" : "");
    ?>

    <input name="<?php echo $sNameAcao ?>" type="submit" id="db_opcao" value="<?php echo $sValueAcao ?>" <?php echo $sDisabledAcao ?> >
    <?php if ($db_opcao != 1) : ?>
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    <?php endif ?>
    </form>
  </div>

  <script type="text/javascript">

    function js_pesquisa() {

      js_OpenJanelaIframe(
        'CurrentWindow.corpo',
        'db_iframe_aguaisencaotipo',
        'func_aguaisencaotipo.php?funcao_js=parent.js_preenchepesquisa|x29_codisencaotipo',
        'Pesquisa', true
      );
    }

    function js_preenchepesquisa(chave) {

      db_iframe_aguaisencaotipo.hide();
      <?php
        if ($db_opcao != 1) {
          echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
        }
      ?>
    }
  </script>

  <?php db_menu() ?>
</body>
</html>
