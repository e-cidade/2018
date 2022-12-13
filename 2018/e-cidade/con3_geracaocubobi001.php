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



require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/dbtreeview.style.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBInputHora.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
</head>

<body class='body-default'>

<div class="container" >
  <form>

    <fieldset>
      <legend>Agenda Geração de Cubo BI</legend>
      <div class="subcontainer">
        <fieldset class='separator'>
          <legend>Cubos BI</legend>

          <div style="width:600px" id='ctnGridCubos'>

          </div>
        </fieldset>
      </div>
      <div class="subcontainer">
        <fieldset  class="separator" style="width:600px;" >
          <legend>Informe a Periodicidade</legend>

          <table class="form-container">
            <tr >
              <td class="bold" nowrap="nowrap">Periodicidade:</td>
              <td nowrap="nowrap">
                <select id='periodicidade'>
                  <option value="" selected="selected">Selecione</option>
                  <option value="1">Diária</option>
                  <option value="2">Semanal</option>
                  <option value="3">Mensal</option>
                </select>
              </td>
            </tr>
          </table>

          <!-- ************************************
            ** Container da Periodicidade Diária **
            ************************************ -->
          <div id='cntPeriodicidadeDiaria' style="display:none;" >
            <fieldset class="separator">
              <legend>Informe os Horários</legend>
              <table class="form-container">
                <tr>
                  <td class="bold">Horário:</td>
                  <td nowrap="nowrap">
                    <input type='text' id='sHorario' name='sHorario' maxlength='5' />
                    <input type='button' id='adicionar' value='Adicionar' />
                  </td>
                </tr>
              </table>
              <div id='ctnLancadorHorario'> </div>
            </fieldset>
          </div>

          <!-- ***********************************
          ** Container da Periodicidade Semanal **
          ************************************* -->
          <div id='cntPeriodicidadeSemanal' style="display: none;" >

            <fieldset class='separator'>
              <legend>Selecione os dias</legend>
              <table class="form-container">
                <tr>
                  <td nowrap="nowrap" class="bold">
                    <input type="checkbox" id='domingo' value="0" > <label  for='domingo'>Domingo</label>
                  </td>
                  <td nowrap="nowrap" class="bold">
                    <input type="checkbox" id='segunda' value="1"> <label for='segunda'>Segunda-feira</label>
                  </td>
                </tr>
                <tr>
                  <td class="bold">
                    <input type="checkbox" id='terca'  value="2" > <label for='terca'>Terça-feira</label>
                  </td>
                  <td class="bold">
                    <input type="checkbox" id='quarta' value="3" > <label for='quarta'>Quarta-feira</label>
                  </td>
                </tr>
                <tr>
                  <td class="bold">
                   <input type="checkbox" id='quinta' value="4" > <label for='quinta'>Quinta-feira</label>
                  </td>
                 <td class="bold">
                   <input type="checkbox" id='sexta'  value="5" > <label for='sexta'>Sexta-feira</label>
                  </td>
                </tr>
                <tr>
                  <td class="bold" colspan="2">
                   <input type="checkbox" id='sabado' value="6" > <label for='sabado'>Sábado</label>
                  </td>
                </tr>
              </table>
            </fieldset>
          </div>


          <!-- ************************************
            ** Container da Periodicidade Mensal **
            ************************************ -->
          <div id='cntPeriodicidadeMensal' style="display:none;" >
            <fieldset class="separator">
              <legend>Informe os Dias do Mês</legend>
              <h4 style="background-color:#FCFFCD; margin:5px 0; padding:5px 0;" >Informe dias entre 01 e 28</h4>
              <table class="form-container">
                <tr>
                  <td class="bold">Dia:</td>
                  <td nowrap="nowrap">
                    <input type='text' id='sDia' name='sDia' maxlength='2' />
                    <input type='button' id='adicionar_dia' value='Adicionar' />
                  </td>
                </tr>
              </table>
              <div id='ctnLancadorDiaMes'> </div>
            </fieldset>
          </div>
        </fieldset>
      </div>
    </fieldset>

    <input type="button"  id='salvar' name='salvar' value="Salvar" />
  </form>
</div>

<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">

const MSGCON3_GERACAOCUBOBI001 = "configuracao.configuracao.con3_geracaocubobi001.";

var sRPC = "con4_geracaocubobi.RPC.php";

var oGridHorarios = new DBGrid( "gridHorario" );
var oGridDiaMes   = new DBGrid( "gridDiaMes" );
var oGridCubosBI  = new DBGrid( "gridCubosBI" );

var aHorarios = [];
var aDiaMes   = [];

$("periodicidade").observe('change', function() {

  $('cntPeriodicidadeDiaria').style.display  = 'none';
  $('cntPeriodicidadeSemanal').style.display = 'none';
  $('cntPeriodicidadeMensal').style.display  = 'none';

  aHorarios = [];
  aDiaMes   = [];

  $$("#cntPeriodicidadeSemanal input[type='checkbox']").each(function(oElemento){
    oElemento.checked = false;
  });

  oGridHorarios.clearAll(true);
  oGridDiaMes.clearAll(true);

  switch(this.value) {

    case '1':
      $('cntPeriodicidadeDiaria').style.display  = '';
      break;
    case '2':
      $('cntPeriodicidadeSemanal').style.display = '';
      break;
    case '3':
      $('cntPeriodicidadeMensal').style.display  = '';
      break;
  }
});

/**  *********************************************** Periodicidade Hora ********************************************* */

new DBInputHora( $('sHorario') );

oGridHorarios.nameInstance = 'oGridHorarios';
oGridHorarios.setHeight(100);
oGridHorarios.setHeader( new Array( "Codigo", "Horário", "Ação" ) );
oGridHorarios.setCellAlign( new Array( "center", "center", "center" ) );
oGridHorarios.setCellWidth( new Array( "20%", "70%", "10%" ) );
oGridHorarios.aHeaders[0].lDisplayed = false;
oGridHorarios.show( $('ctnLancadorHorario') );

$('adicionar').observe('click', function() {

  oGridHorarios.clearAll(true);

  if ( $F('sHorario') != '' ) {
    aHorarios.push($F('sHorario'));
  }

  for( var iPosicao = 0; iPosicao < aHorarios.length; iPosicao++ ) {

    var aLinha = new Array();
    aLinha[0]  = '';
    aLinha[1]  = aHorarios[iPosicao];
    aLinha[2]  = '<input type="button" value="E" id="excluir" onClick="excluirHorario(' + iPosicao +');">';
    oGridHorarios.addRow(aLinha);
  }

  oGridHorarios.renderRows();

  $('sHorario').value = '';
  $('sHorario').focus();
});

function excluirHorario( iPosicao ) {

  aHorarios.splice( iPosicao, 1);
  $('adicionar').click();
}

/**  ************************************************ Periodicidade Mes ********************************************* */


oGridDiaMes.nameInstance = 'oGridDiaMes';
oGridDiaMes.setHeight(100);
oGridDiaMes.setHeader( new Array( "Dia do Mês", "Ação" ) );
oGridDiaMes.setCellAlign( new Array( "left", "center" ) );
oGridDiaMes.setCellWidth( new Array( "90%", "10%" ) );
oGridDiaMes.show( $('ctnLancadorDiaMes') );

$('adicionar_dia').observe('click', function() {

  if ( $F('sDia') != '' ) {

    if ( parseInt( $F('sDia') ) < 0 || parseInt( $F('sDia') ) > 28 ) {

      $('sDia').value = '';
      alert( 'Dia deve ser maior que zero e menor ou igual a 28' );
      return false;
    }
  }

  oGridDiaMes.clearAll(true);

  if ( $F('sDia') != '' ) {
    aDiaMes.push($F('sDia'));
  }

  for( var iPosicao = 0; iPosicao < aDiaMes.length; iPosicao++ ) {

    var aLinha = new Array();
    aLinha[0]  = aDiaMes[iPosicao];
    aLinha[1]  = '<input type="button" value="E" id="excluir" onClick="excluirDiaMes(' + iPosicao +');">';
    oGridDiaMes.addRow(aLinha);
  }

  oGridDiaMes.renderRows();

  $('sDia').value = '';
  $('sDia').focus();
});

function excluirDiaMes( iPosicao ) {

  aDiaMes.splice( iPosicao, 1);
  $('adicionar_dia').click();
}

/** ******************************************** Busca cubos ********************************************************* */

oGridCubosBI.nameInstance = 'oGridCubosBI';
oGridCubosBI.setHeight(150);
oGridCubosBI.setCheckbox(3);
oGridCubosBI.setHeader( new Array( "Cubo", "Agendado", "Ação", 'codigo_cubo' ) );
oGridCubosBI.setCellAlign( new Array( "left", "center", "center" ) );
oGridCubosBI.setCellWidth( new Array( "70%", "20%", "10%" ) );
oGridCubosBI.aHeaders[4].lDisplayed = false;
oGridCubosBI.show( $('ctnGridCubos') );


function buscaCubos() {

  var oAjaxCuboBI = new AjaxRequest(sRPC, {sExecuta: 'getCubos'}, retornoCubos);
  oAjaxCuboBI.setMessage( _M( MSGCON3_GERACAOCUBOBI001 + "buscando_cubos" ) );
  oAjaxCuboBI.execute();
}

function retornoCubos( oRetorno, lErro ) {

  if ( lErro ) {

    alert( oRetorno.sMensagem.urlDecode() );
    return;
  }

  oGridCubosBI.clearAll(true);
  oRetorno.aCubos.each(function (oCubo, iLinha) {

    var sId         = 'cubo#'+oCubo.iCubo;
    var oBtnExcluir = new Element('input', {'type':'button', 'value':'E', 'id':sId, 'name':sId} );
    oBtnExcluir.setAttribute('onclick', 'removeCubo('+oCubo.iCubo+')');

    var sChecked = '<span class="delete-icon"></span>';
    if ( oCubo.lPossuiPeriodicidade ) {
      sChecked = '<span><img border="0" src="imagens/gtk_ok.png"></span>';
    }
    var aLinha      = [];
    aLinha.push(oCubo.sCubo.urlDecode());
    aLinha.push(sChecked);
    aLinha.push(oBtnExcluir.outerHTML);
    aLinha.push(oCubo.iCubo);

    oGridCubosBI.addRow(aLinha);
  });
  oGridCubosBI.renderRows();

}
buscaCubos();


function removeCubo(iCubo) {

  var oAjaxRemoverCubo = new AjaxRequest(sRPC, {sExecuta: 'excluir', iCubo : iCubo}, retornoExcluirCubo);
  oAjaxRemoverCubo.setMessage( _M( MSGCON3_GERACAOCUBOBI001 + "buscando_cubos" ) );
  oAjaxRemoverCubo.execute();
}

function retornoExcluirCubo( oRetorno, lErro ) {

  alert( oRetorno.sMensagem.urlDecode() );
   if ( lErro ) {
    return;
  }
  buscaCubos();
}

$("salvar").observe('click', function() {

  var oParametros    = {'sExecuta' : 'salvar'};
  oParametros.aCubos = [];

  var aCubosSelecionados = oGridCubosBI.getSelection();

  if (aCubosSelecionados.length == 0) {

    alert( _M(MSGCON3_GERACAOCUBOBI001 + "selecione_cubos_grade") );
    return;
  }

  var aPeriodicidade = [];

  switch( $F('periodicidade') ) {

    case '1':

      if ( aHorarios.length == 0 ) {

        alert( _M(MSGCON3_GERACAOCUBOBI001 + "informe_horario") );
        return;
      }
      aPeriodicidade = aHorarios;
      break;
    case '2':

      $$("#cntPeriodicidadeSemanal input[type='checkbox']:checked").each(function(oElemento){
        aPeriodicidade.push( oElemento.value );
      });
      if ( aPeriodicidade.length == 0 ) {

        alert( _M(MSGCON3_GERACAOCUBOBI001 + "informe_dia_semana") );
        return;
      }
      break;
    case '3':

      if ( aDiaMes.length == 0 ) {

        alert( _M(MSGCON3_GERACAOCUBOBI001 + "informe_dia_mes") );
        return;
      }
      aPeriodicidade = aDiaMes;
      break;
  }

  for ( var i = 0; i < aCubosSelecionados.length; i++) {

    var oCubo                = {};
    oCubo.iCubo              = aCubosSelecionados[i][0];
    oCubo.sCubo              = encodeURIComponent(tagString( aCubosSelecionados[i][1] ));
    oCubo.iTipoPeriodicidade = $F('periodicidade')
    oCubo.aPeriodicidade     = aPeriodicidade;
    oParametros.aCubos.push(oCubo);
  }

  var oAjaxSalvar = new AjaxRequest(sRPC, oParametros, retornoSalvar);
  oAjaxSalvar.setMessage( _M( MSGCON3_GERACAOCUBOBI001 + "salvar_cubos" ) );
  oAjaxSalvar.execute();
  buscaCubos();

})

function retornoSalvar(oRetorno, lErro) {

   alert( oRetorno.sMensagem.urlDecode() );
   if ( lErro ) {
    return;
  }
  location.href = "con3_geracaocubobi001.php";
}
</script>