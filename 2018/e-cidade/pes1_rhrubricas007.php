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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("classes/db_bases_classe.php");
require_once modification("classes/db_basesr_classe.php");
require_once modification("dbforms/db_funcoes.php");

db_postmemory($_POST);

$clbases = new cl_bases;
$clbasesr = new cl_basesr;
$db_opcao = 1;
$db_botao = true;
if(isset($cadastrar)){

  $sqlerro=false;

  db_inicio_transacao();

  $anousu = db_anofolha();
  $mesusu = db_mesfolha();

  $clbasesr->excluir($anousu,$mesusu,$r09_base,null,db_getsession("DB_instit"));

  if($clbasesr->erro_status==0){
    $sqlerro=true;
    $erro_msg = $clbasesr->erro_msg;
  }

  if($sqlerro == false){
    while (list($k,$v) = each($r09_rubric)){

      $clbasesr->incluir($anousu,$mesusu,$r09_base,$v,db_getsession("DB_instit"));

      if($clbasesr->erro_status == 0){

        $erro_msg = $clbasesr->erro_msg;
        $sqlerro=true;
      }
    }
  }
  db_fim_transacao($sqlerro);

}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToggleList.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBPesquisa.plugin.js"></script>

    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>


      .grid-resize {
        display: none;
      }

    </style>

    <style>
    .DBToggleListBox .gridcontainer  div table tbody tr td:nth-child(1) {
      width: 45px !important;
    }
    .DBToggleListBox .gridcontainer  div table tbody tr td:nth-child(2) {
      width: 215px;
    }
    .DBToggleListBox .gridcontainer  div table tbody tr td:nth-child(3) {
      width: 75px !important;
    }

    .DBToggleListBox > div {
      height: 550px;
    }

    .DBToggleListBox .body-container {
      height: 550px !important;
    }
    </style>
  </head>

  <body>
    <form name="form1" method="post" class="container" onsubmit="return makeSelection()">
      <fieldset>
        <legend>
          Rubricas lançadas para a base <?=$r09_base?>
        </legend>
        <div id="grids"></div>
      </fieldset>
      <input type='submit' name='cadastrar' value='Processar' />
    </form>
  </body>
</html>
<?php

$sql  ="SELECT r09_base,                                               ";
$sql .="       r09_rubric,                                             ";
$sql .="       rh27_rubric,                                            ";
$sql .="       rh27_pd,                                                ";
$sql .="       rh27_descr                                              ";
$sql .="  FROM rhrubricas                                              ";
$sql .="			  LEFT JOIN basesr ON rh27_rubric = r09_rubric           ";
$sql .="                        and r09_base    = '{$_GET["r09_base"]}'";
$sql .="						            and r09_anousu  = " . db_anofolha();
$sql .="							          and r09_mesusu  = " . db_mesfolha();
$sql .="							          and r09_instit  = " . db_getsession("DB_instit");
$sql .="       WHERE  rh27_instit = ".db_getsession("DB_instit");
$sql .=" order by rh27_rubric                                          ";
$rs   = db_query($sql);

if (!$rs) {
  db_msgbox("Erro ao buscar as rubricas da base({$_GET["r09_base"]})");
  exit;
}



$aSelecionados = array();
$aSelecionar   = array();

$aCollection = db_utils::makeCollectionFromRecord($rs, function($oDados) {

  switch ($oDados->rh27_pd) {
    case 1:
      $sTipoRubrica = "Provento";
    break;
    case 2:
      $sTipoRubrica = "Desconto";
    break;
    case 3:
      $sTipoRubrica = "Base";
    break;
  }

  return (object)array(
    "rubrica"     => $oDados->rh27_rubric,
    "descricao"   => urlencode($oDados->rh27_descr),
    "tipo"        => $sTipoRubrica,
    "selecionado" => !empty($oDados->r09_base)
  );

});


$sDados        = json_encode($aCollection);
if(isset($cadastrar)){

  if($sqlerro == true){
  	db_msgbox($erro_msg);
  }
}
?>
<script>

var aDados        = JSON.parse('<?= $sDados; ?>');

var oLinha;

var oToggle = new DBToggleList([
  {sId: 'rubrica',   sLabel: 'Rubrica'},
  {sId: 'descricao', sLabel: 'Descrição'},
  {sId: 'tipo',      sLabel: 'Tipo Rubrica'}]);
oToggle.closeOrderButtons();
oToggle.show($('grids'));
oToggle.oGridSelect.setPesquisa(1);
oToggle.oGridSelected.setPesquisa(1);
oToggle.clearAll(true);

for (var iRubrica = 0; iRubrica < aDados.length; iRubrica++) {

  var oDados = aDados[iRubrica];
  var oLinha = {
    'rubrica' : oDados.rubrica,
    'descricao' : oDados.descricao.urlDecode(),
    'tipo' : oDados.tipo
  };

  if (oDados.selecionado) {
    oToggle.addSelected(oLinha)
  } else {
    oToggle.addSelect(oLinha);
  }

}

oToggle.renderRows();

document.querySelectorAll("div.gridcontainer")[0].style.width = "380px";
document.querySelectorAll("div.gridcontainer")[1].style.width = "380px";

/**
 *  Mantem a compatibilidade com a rotina anterior
 */
makeSelection = function() {

  if ($('input_selecionados')) {
    $('input_selecionados').remove();
  }

  var oSelecionados = document.createElement("div");
  oSelecionados.id = "input_selecionados";
  document.forms[0].appendChild(oSelecionados);

  var aSelecionados = oToggle.getSelected();

  for (var oDados of aSelecionados) {
    var oInput = document.createElement("input");
    oInput.type = "hidden";
    oInput.name = 'r09_rubric[]';
    oInput.value= oDados.rubrica;
    oSelecionados.appendChild(oInput);
  }

};

makeSelection();

</script>
