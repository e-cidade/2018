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

$oGET        = db_utils::postMemory($_GET);
$oPOST       = db_utils::postMemory($_POST);
$iCodigo     = isset($oPOST->chave_i05_codigo) ? $oPOST->chave_i05_codigo : null;
$iSequencial = !empty($oPOST->chave_i05_sequencial) ? $oPOST->chave_i05_sequencial : null;
$sDescricao  = !empty($oPOST->chave_i05_descricao) ? $oPOST->chave_i05_descricao : null;
$iNivel  = !empty($oPOST->chave_i05_nivel) ? $oPOST->chave_i05_nivel : null;

$oRotulo = new rotulocampo();
$oRotulo->label("i05_codigo");
$oRotulo->label("i05_descricao");
$oRotulo->label("i05_nivel");
$oRotulo->label("i05_sequencial");

$oDaoInfracaoTransito = new \cl_infracaotransito();
?>

<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body>
  <form name="form2" method="post" action="" class="container">
    <fieldset>
      <legend>Filtros</legend>
      <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
          <tr>
            <td>
              <label class="bold" for="chave_i05_sequencial">Sequencial:</label>
            </td>
            <td>
              <?php
                db_input('i05_sequencial',10,$Ii05_sequencial,true,'text',4, "", "chave_i05_sequencial");
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label class="bold" for="chave_i05_codigo">Código da Infração:</label>
            </td>
            <td>
              <?php
                db_input('i05_codigo',10,$Ii05_codigo,true,'text',4, "onkeyup= 'js_ValidaCampos(this, 1, \"Código da infração\", 0, 1);'", "chave_i05_codigo", "", "", '5');
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label class="bold" for="chave_i05_descricao">Descrição:</label>
            </td>
            <td>
              <?php
                db_input('i05_codigo',50,$Ii05_codigo,true,'text',4, "", "chave_i05_descricao");
              ?>
            </td>
          </tr>
          <tr>
              <td>
                  <label id="sLabelNivel" class="bold" for="chave_i05_nivel">Nível: </label>
              </td>
              <td >
                <?php
                  $aTipos = array('0'=>'Selecione', '1' => 'Nível 1', '2' => 'Nível 2', '3' => 'Nível 3', '4' => 'Nível 4');
                  db_select("chave_i05_nivel", $aTipos, false, 1);
                ?>
              </td>
            </tr>
        </table>
    </fieldset>
    <input type="submit" name="pesquisar" id="pesquisar2" value="Pesquisar">
    <input type="button" name="limpar"    id="limpar"     value="Limpar" onclick="limparCampos();">
    <input type="button" name="fechar"    id="fechar"     value="Fechar" onClick="parent.db_iframe_infracaotransito.hide();">
  </form>

  <?php
    $sOrdem = 'i05_codigo';
    $aWhere = array();;

    if ($iCodigo) {
      $aWhere[] = "i05_codigo = '{$iCodigo}'";
    }

    if ($iSequencial) {
      $aWhere[] = "i05_sequencial = '{$iSequencial}'";
    }

    if ($sDescricao) {
      $aWhere[] = "i05_descricao ilike  '%{$sDescricao}%'";
    }

    if ($iNivel) {
      $aWhere[] = "i05_nivel = '{$iNivel}'";
    }

    $sSql = $oDaoInfracaoTransito->sql_query(null, '*', $sOrdem, implode(' and ', $aWhere));

  ?>

  <div class="container">
    <fieldset>
      <legend>Resultado da Pesquisa</legend>
      <?php db_lovrot($sSql, 15, "()", "", $funcao_js, "", "NoMe"); ?>
    </fieldset>
  </div>
  <script>

    function limparCampos() {

      document.getElementById('chave_i05_sequencial').value = "" ;
      document.getElementById('chave_i05_codigo').value     = "" ;
      document.getElementById('chave_i05_descricao').value  = "" ;
      document.getElementById('chave_i05_nivel').value      = "0";
    }

  </script>
</body>
</html>