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

$oGet = db_utils::postMemory( $_GET );


$oRotulo = new rotulocampo;
$oRotulo->label("z01_v_nome");
$oRotulo->label("sd24_i_codigo");
$oRotulo->label("sd03_i_codigo");

$iProntuario   = $oGet->iProntuario;
$sPaciente     = $oGet->sNomePaciente;
$iProfissional = $oGet->iProfissional ;
$sProfissional = $oGet->sNomeProfissional ;
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link rel="stylesheet" type="text/css" href="estilos.css" />
  <link rel="stylesheet" type="text/css" href="estilos/grid.style.css" />
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>

</head>
<body class='body-default'>

  <div class="container" style="width:600px;">
    <form>

      <fieldset>
        <legend>Requisição de exames</legend>
        <table>
          <tr>
            <td nowrap="nowrap" class="bold"><?=$Lsd24_i_codigo?></td>
            <td nowrap="nowrap" >
              <?php
                db_input("iRequisicao", 10, "", true, "hidden", 3 );
                db_input("iProntuario", 10, "", true, "text", 3 );
                db_input("sPaciente",   40, "", true, "text", 3 );
              ?>
            </td>
          </tr>

          <tr>
            <td nowrap="nowrap" class="bold"><?=$Lsd03_i_codigo?></td>
            <td nowrap="nowrap" >
              <?php
                db_input("iProfissional", 10, "", true, "text", 3 );
                db_input("sProfissional", 40, "", true, "text", 3 );
              ?>
            </td>
          </tr>
        </table>

        <fieldset class='separator'>
          <legend>Observação</legend>
          <textarea  id='observacao' rows="2" cols="40"  style="min-height:47px !important;" maxlength="550"> </textarea>
        </fieldset>

        <div id ='ctnExames' > </div>

      </fieldset>

      <input type="button" name='salvar'   id='salvarRequisicao' value="Salvar" />
      <input type="button" name='imprimir' id='imprimirRequisicao' value="Imprimir" disabled="disabled" />
    </form>
  </div>

  <div class="subcontainer" style="width:600px;" >
    <fieldset >
      <legend>Exames inclusos</legend>
        <div style="width:100%" id='ctnExamesInclusos'> </div>
    </fieldset>
  </div>

</body>
<script type="text/javascript">

const MSG_SAU4_REQUISICAOEXAME = 'saude.ambulatorial.sau4_requisicaoexame.';

var oGet         = js_urlToObject();
var iTotalExames = 0;

var oGridExames = new DBGrid('gridExames');
oGridExames.nameInstance = 'oGridExames';
oGridExames.setCellWidth(['90%', '10%']);
oGridExames.setCellAlign(['left', 'center']);
oGridExames.setHeader(['Exame', 'Ação']);
oGridExames.setHeight(140);
oGridExames.show($('ctnExamesInclusos'));


var oLancadorExame = new DBLancador("oLancadorExame");
oLancadorExame.setNomeInstancia("oLancadorExame");
oLancadorExame.setLabelAncora("Exame:");
oLancadorExame.setTextoFieldset("Exames");
oLancadorExame.setParametrosPesquisa("func_lab_exame.php", ['la08_i_codigo', 'la08_c_descr']);
oLancadorExame.setGridHeight("120");
oLancadorExame.setTipoValidacao('3');

/**
 * Quando for exclusão, desabilita grid para lançar atividades
 */
oLancadorExame.setHabilitado( true );
oLancadorExame.show($("ctnExames"));


/**
 * Busca os exames solicitados para o prontuário
 */
function js_carregarRequisicaoProntuario (argument) {

  var oParametro    = {'sExecucao': 'buscarRequisicaoProntuario','iProntuario': oGet.iProntuario};
  var oAjaxRequest  = new AjaxRequest('sau4_requisicaoexameprontuario.RPC.php', oParametro, callBackBuscaExames);
  oAjaxRequest.setMessage( _M(MSG_SAU4_REQUISICAOEXAME + 'verificando_exames') );
  oAjaxRequest.execute();
}

/**
 * Retorno da busca os exames solicitados para o prontuário
 */
function callBackBuscaExames(oRetorno, lErro) {

  if (lErro) {

    alert ( oRetorno.sMensagem.urlDecode() );
    return false;
  }

  $('iRequisicao').value    = oRetorno.iRequisicao;
  $('observacao').innerHTML = oRetorno.sObservacao.urlDecode();

  if (oRetorno.aExames.length > 0) {
    $('imprimirRequisicao').removeAttribute('disabled');
  }

  iTotalExames = oRetorno.aExames.length;
  oGridExames.clearAll(true);

  oRetorno.aExames.each( function ( oExame ) {

    var oBotao   = document.createElement('input');
    oBotao.type  = 'button';
    oBotao.value = 'E';
    oBotao.id    = 'excluirExame' + oExame.iExameRequisicao;
    oBotao.setAttribute('iExameRequisicao', oExame.iExameRequisicao);

    var aLinha = [];
    aLinha.push(oExame.sExame.urlDecode());
    aLinha.push(oBotao.outerHTML);
    oGridExames.addRow(aLinha);
  });

  oGridExames.renderRows();

  oRetorno.aExames.each( function ( oExame ) {

    $('excluirExame'+oExame.iExameRequisicao).onclick = function() {
      js_removerExame(oExame.iExameRequisicao)
    };
  });

};

js_carregarRequisicaoProntuario();

/**
 * Requisita a exclusão de um exame do prontuário
 * @param  {integer} iExameRequisicao código do vinculo do exame vinculado a requisicao do prontuario
 */
function js_removerExame(iExameRequisicao) {

  if ( !confirm( _M(  MSG_SAU4_REQUISICAOEXAME + 'confirmar_exclusao' ) ) ) {
    return;
  }


  var oParametro    = {'sExecucao': 'removerExame','iExameRequisicao': iExameRequisicao};
  var oAjaxRequest  = new AjaxRequest('sau4_requisicaoexameprontuario.RPC.php', oParametro, callBackExcluir);
  oAjaxRequest.setMessage( _M(MSG_SAU4_REQUISICAOEXAME + 'excluindo_exame') );
  oAjaxRequest.execute();

}

/**
 * Retorno da exlcusão do exame solicitado
 */
function callBackExcluir (oRetorno, lErro) {

  alert ( oRetorno.sMensagem.urlDecode() );
  if (lErro) {
    return false;
  }
  js_carregarRequisicaoProntuario();
}


$('salvarRequisicao').observe('click', function() {

  if ($F('iProntuario') == '' ) {

    alert( _M(MSG_SAU4_REQUISICAOEXAME + 'prontuario_inesistente') );
    return;
  }

  if ( iTotalExames == 0 && oLancadorExame.getRegistros().length == 0 ) {

    alert( _M(MSG_SAU4_REQUISICAOEXAME + 'informe_exames') );
    return;
  }

  var oParametro         = {'sExecucao': 'salvarRequisicaoExame', 'iRequisicaoExameProntuario': $F('iRequisicao')};
  oParametro.iProntuario = $F('iProntuario');
  oParametro.iMedico     = $F('iProfissional');
  oParametro.sObservacao = encodeURIComponent(tagString( $F('observacao') ));
  oParametro.aExames     = [];

  oLancadorExame.getRegistros().each( function(oExameLancado) {

    oParametro.aExames.push(oExameLancado.sCodigo);
  });


  var oAjaxRequest  = new AjaxRequest('sau4_requisicaoexameprontuario.RPC.php', oParametro, callBackSalvar);
  oAjaxRequest.setMessage( _M(MSG_SAU4_REQUISICAOEXAME + 'salvando_requisicao')  );
  oAjaxRequest.execute();


});

function callBackSalvar (oRetorno, lErro) {

  alert ( oRetorno.sMensagem.urlDecode() );
  if (lErro) {
    return false;
  }
  oLancadorExame.clearAll();
  js_carregarRequisicaoProntuario();
}

$('imprimirRequisicao').observe('click', function () {

  var sUrl = "sau2_requisicaoexame004.php?iProntuario=" + $F('iProntuario');
  var oJanela = window.open(sUrl,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  oJanela.moveTo(0,0);
});

</script>
</html>