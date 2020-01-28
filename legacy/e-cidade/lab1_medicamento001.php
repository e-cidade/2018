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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oDaoMedicamento = new cl_medicamentoslaboratorio();
$oDaoMedicamento->rotulo->label();
?>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
</head>
<body class='body-default'>
  <div class='container'>
    <form name ='form1'>
      <fieldset>
        <legend>Medicamento</legend>
        <table class="form-container">
          <tr>
            <td><label for="la43_abreviatura"> <?=$Lla43_abreviatura?></label></td>
            <td>
              <?php
                db_input('la43_abreviatura', 5, $Ila43_abreviatura, true, 'text', 1);
              ?>
            </td>
          </tr>
          <tr>
            <td><label for="la43_nome"><?=$Lla43_nome?></label></td>
            <td>
              <?php
                db_input('la43_sequencial', 10, $Ila43_sequencial, true, 'hidden', 3);
                db_input('la43_nome',       30, $Ila43_nome,       true, 'text',   1);
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" value="Salvar" name='salvar' id='btnSalvar' />
      <input type="button" value="Limpar" name='limpar' id='btnLimpar'  onclick="limparForm();" />
    </form>
  </div>
  <div class="subcontainer">
    <fieldset>
      <legend>Medicamentos Cadastrados</legend>
      <div id='ctnGrid' style="width: 600px" ></div>
    </fieldset>
  </div>
  <?php
    db_menu();
  ?>
</body>

<script type="text/javascript">

var MSG_LAB1_MEDICAMENTO = 'saude.laboratorio.lab1_medicamento001.';

// Medicamentos inclusos no sistema
var aMedicamentos  = [];

var sRPC           = 'lab4_medicamentoslaboratorio.RPC.php';
var oGrid          = new DBGrid('Medicamentos');
var aHeadersGrid   = ['Abreviatura', 'Nome', 'Ação'];
var aCellWidthGrid = ['15%', '75%', '10%'];
var aCellAlign     = ['center', 'left', 'center'];

oGrid.setCellWidth(aCellWidthGrid);
oGrid.setCellAlign(aCellAlign);
oGrid.setHeader(aHeadersGrid);
oGrid.setHeight(130);
oGrid.show($('ctnGrid'));


(function(){
  buscarMedicamentos();
})();


function buscarMedicamentos() {

  var oAjaxRequest = new AjaxRequest(sRPC, {exec: 'buscar'}, retornoMedicamentosCadastrados);
  oAjaxRequest.setMessage( _M(MSG_LAB1_MEDICAMENTO + 'buscando_medicamentos' ) );
  oAjaxRequest.execute();
}

function retornoMedicamentosCadastrados(oRetorno, lErro) {

  if (lErro) {

    alert(oRetorno.sMessage.urlDecode());
    return;
  }

  oGrid.clearAll(true);

  if ( oRetorno.aMedicamentos.length > 0 ) {

    aMedicamentos = oRetorno.aMedicamentos;
    oRetorno.aMedicamentos.each( function (oMedicamento) {

      var oBtnAlterar = createButton('A', oMedicamento.iCodigo, oMedicamento.lEditavel);
      var oBtnExcluir = createButton('E', oMedicamento.iCodigo, oMedicamento.lEditavel);

      var sBotoes = oBtnAlterar.outerHTML + '&nbsp;' + oBtnExcluir.outerHTML;

      var aLinha = [];
      aLinha.push(oMedicamento.sAbreviatura.urlDecode());
      aLinha.push(oMedicamento.sMedicamento.urlDecode());
      aLinha.push(sBotoes);
      oGrid.addRow(aLinha);

    });

    oGrid.renderRows();
  }
}

function createButton(sValue, iCodigoMedicamento, lHabilitado) {

  var oBtn   = document.createElement('input');
  oBtn.type  = 'button';
  oBtn.value = sValue;
  oBtn.name  = sValue +'_' + iCodigoMedicamento;
  oBtn.id    = 'btn' + sValue +'_' + iCodigoMedicamento;
  oBtn.setAttribute('codigo', iCodigoMedicamento);

  if ( !lHabilitado ) {
    oBtn.setAttribute('disabled', 'disabled');
  }

  if ( sValue == 'A' ) {
    oBtn.setAttribute('onclick', 'carregarDadosMedicamento(this)');
  } else {
    oBtn.setAttribute('onclick', 'excluirMedicamento(this)');
  }
  return oBtn;

}


function carregarDadosMedicamento(oElement) {

  var iCodigo = oElement.getAttribute('codigo') ;
  aMedicamentos.each( function (oMedicamento) {

    if (iCodigo == oMedicamento.iCodigo) {

      $('la43_sequencial').value  = oMedicamento.iCodigo;
      $('la43_nome').value        = oMedicamento.sMedicamento.urlDecode();
      $('la43_abreviatura').value = oMedicamento.sAbreviatura.urlDecode();
      throw $break;
    }
  });

}

function excluirMedicamento(oElement) {

  if ( !confirm(_M(MSG_LAB1_MEDICAMENTO + 'confirma_exclusao' )) ) {
    return;
  }

  var iCodigo      = oElement.getAttribute('codigo') ;
  var oAjaxRequest = new AjaxRequest(sRPC, {exec: 'excluir', iCodigo: iCodigo}, recarregarDados);
  oAjaxRequest.setMessage( _M(MSG_LAB1_MEDICAMENTO + 'buscando_medicamentos' ) );
  oAjaxRequest.execute();
}

$('btnSalvar').observe('click', function () {

  if (!validaCampos()) {
    return;
  }

  var oParamentros          = {exec: 'salvar'};
  oParamentros.iCodigo      = $F('la43_sequencial');
  oParamentros.sNome        = encodeURIComponent( tagString( $F('la43_nome') ) );
  oParamentros.sAbreviatura = encodeURIComponent( tagString( $F('la43_abreviatura') ) );
  var oAjaxRequest = new AjaxRequest(sRPC, oParamentros, recarregarDados);
  oAjaxRequest.setMessage( _M(MSG_LAB1_MEDICAMENTO + 'salvando_medicamentos' ) );
  oAjaxRequest.execute();
});

function recarregarDados(oRetorno, lErro) {

  alert(oRetorno.sMessage.urlDecode());

  if (lErro) {
    return;
  }

  limparForm();
  buscarMedicamentos();
}

function validaCampos() {

  if ( $F('la43_nome') == '' ) {

    alert( _M(MSG_LAB1_MEDICAMENTO + 'informe_nome') );
    return false;
  }
  if ( $F('la43_abreviatura') == '' || $F('la43_abreviatura') == '-' ) {

    alert( _M(MSG_LAB1_MEDICAMENTO + 'informe_abreviatura') );
    return false;
  }

  return true;
}

function limparForm() {

  $('la43_sequencial').value = '';
  document.form1.reset();
}
</script>
</html>