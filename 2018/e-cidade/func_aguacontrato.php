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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("dbforms/db_funcoes.php"));

$oGET        = db_utils::postMemory($_GET);
$oPOST       = db_utils::postMemory($_POST);
$iChave      = isset($oGET->pesquisa_chave) ? $oGET->pesquisa_chave : null;
$iSequencial = isset($oPOST->chave_x54_sequencial) ? $oPOST->chave_x54_sequencial : null;
$iCgm        = isset($oGET->filtro_cgm) ? $oGET->filtro_cgm : null;

$oAguaContrato = new cl_aguacontrato;
$oAguaContrato->rotulo->label("x54_sequencial");
$oAguaContrato->rotulo->label("x54_sequencial");
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
            <label for="chave_x54_sequencial">
            <?php echo $Lx54_sequencial ?>
            </label>
          </td>
          <td><? db_input("x54_sequencial", 10, $Ix54_sequencial, true, "text", 4, "", "chave_x54_sequencial"); ?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_aguacontrato.hide();">
  </form>
  <?php

  $aWhere = array();

  if ($iCgm) {

    $aWhere[] = "
      (
        -- CGM responsável por contrato de economia
        (case when x54_condominio is false then
          (x54_cgm = {$iCgm})
        else
          false
        end)
        
        -- CGM é uma economia de contrato de condomínio (e responsável pagamento é Economia)
        or exists (
          select
            true
          from
            aguacontratoeconomia
          where
            x38_aguacontrato = x54_sequencial
            and x54_condominio is true
            and x54_responsavelpagamento = 1
            and x38_cgm = {$iCgm}
        )

        -- CGM responsável por contrato de condomínio (e responsável pagamento é Condomínio)
        or (x54_responsavelpagamento = 2 and x54_cgm = {$iCgm})
      )";
  }

  if ($iSequencial || $iChave) {

    $iCodigo = $iSequencial ? $iSequencial : $iChave;
    $aWhere[] = "x54_sequencial = {$iCodigo}";
  }

  if (!isset($campos)) {
    require_once (modification("funcoes/db_func_aguacontrato.php"));
  }

  $sOrder = "x54_sequencial";
  $sWhere = implode(' and ', $aWhere);
  $sSql   = $oAguaContrato->sql_query(null, $campos, $sOrder, $sWhere);

  if (!$iChave) {

    $aRepassa = array();
    if ($iSequencial) {

      $aRepassa = array(
        "chave_x54_sequencial" => $iSequencial,
      );
    }
    ?>
    <div class="container">
      <fieldset>
        <legend>Resultado da Pesquisa</legend>
        <?php db_lovrot($sSql, 15, "()", "", $funcao_js, "", "NoMe", $aRepassa, $lAutomatico = false); ?>
      </fieldset>
    </div>
  <?php
  } else {

    $rsResultado = db_query($sSql);
    if (pg_num_rows($rsResultado) > 0) {

      $oResultado = pg_fetch_object($rsResultado, 0);
      echo "<script>" . $funcao_js . "({$oResultado->x54_sequencial}, '{$oResultado->z01_nome}', false);</script>";
    } else {
      echo "<script>" . $funcao_js . "('Chave(" . $iChave . ") não encontrada', true);</script>";
    }
  }
  ?>
</body>
</html>
