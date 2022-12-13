<?php
/**
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
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
$aTipos = array(
  'T'=>'Dia de Trabalho',
  'F'=>'Folga',
  'D'=>'Descanso Semanal Remunerado'
);
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
  db_app::load("scripts.js");
  db_app::load("strings.js");
  db_app::load("prototype.js");
  db_app::load("estilos.css");
  db_app::load("AjaxRequest.js");
  db_app::load("DBAbas.widget.js");
  db_app::load("DBLancador.widget.js");
  db_app::load("DBInputHora.widget.js");
  ?>
</head>
<body>
<div style="margin-top: 20px;" id='ctnAbas'></div>

<div id="ctnJornada" class="container">

  <fieldset>
    <legend>Cadastro de Jornadas</legend>
    <table>
      <tr>
        <td>
          <label for="tipo" class="bold">Tipo:</label>
        </td>
        <td>
          <?php db_select('tipo', $aTipos, '', 1) ?><br/>
        </td>
      </tr>
      <tr>
        <td>
          <label for="descricao" class="bold">Descrição:</label>
        </td>
        <td>
          <input type="hidden" autocomplete="off" onkeydown="return js_controla_tecla_enter(this,event);" oninput="js_ValidaCampos(this,1,'Código Jornada','t','f',event);" onblur="js_ValidaMaiusculo(this,'f',event);" maxlength="10" size="10" id="sequencial" name="sequencial" title="Código Sequencial: sequencial"/>
          <input type="text"   autocomplete="off" onkeydown="return js_controla_tecla_enter(this,event);" oninput="js_ValidaCampos(this,0,'Descrição','f','t',event);"      onblur="js_ValidaMaiusculo(this,'t',event);" style="text-transform:uppercase;" maxlength="40" size="62" value="" id="descricao" name="descricao" title="Descrição da Jornada Campo:descricao" >
        </td>
      </tr>
    </table>
  </fieldset>

  <input type="button" name="salvarJornada"    id="salvarJornada"    value="Salvar"    onclick="js_salvarJornada()"    />
  <input type="button" name="pesquisar" id="pesquisar" value="Pesquisar" onclick="js_pesquisar()" />
  <input type="button" name="novaJornada" id="novaJornada" value="Nova Jornada" onclick="js_novaJornada()">
  <input type="button" name="excluirJornada" id="excluirJornada" value="Excluir" onclick="js_excluirJornada()">

</div>

<div id="ctnJornadaHoras" class="container">

  <fieldset>

    <?php
    $rsTipoRegistro = db_query("select * from tiporegistro");
    $aTiposRegistro = array();

    for ($iTipoRegistro = 0; $iTipoRegistro < pg_num_rows($rsTipoRegistro); $iTipoRegistro++) {

      $oTipoRegistro = db_utils::fieldsmemory($rsTipoRegistro, $iTipoRegistro);

      $aTiposRegistro[$oTipoRegistro->rh187_sequencial] = $oTipoRegistro->rh187_descricao;
    }

    ?>
    <strong>Tipo: </strong>

    <?php
    db_select ('tiporegistros', $aTiposRegistro, true, 1);
    ?>

    <strong>Hora:</strong>

    <input type="text"   autocomplete="off" onkeydown="return js_controla_tecla_enter(this,event);" oninput="js_ValidaCampos(this,0,'Descrição','f','t',event);"      onblur="js_ValidaMaiusculo(this,'t',event);" style="text-transform:uppercase;" maxlength="5" size="10" value="" id="hora" name="hora" title="Hora Campo:hora" />

    <input type="button" name="lancar" id="lancar" value="Lançar" onclick="js_lancar()" />

    <div id="grid_registros" style="margin-top: 10px; width:500px"></div>

  </fieldset>

  <input type="button" name ="salvarJornadaHoras" id="salvarJornadaHoras" value="Salvar" onclick="js_salvarJornadaHoras()" />

</div>
</body>
<?php db_menu(); ?>
<script>

  var oDBAba                      = new DBAbas( $('ctnAbas') );
  var oAbaJornada                 = oDBAba.adicionarAba( 'Cadastro de Jornada', $('ctnJornada') );
  var oAbaJornadaHoras            = oDBAba.adicionarAba( 'Horas da Jornada'   , $('ctnJornadaHoras') );
  oAbaJornadaHoras.lBloqueada = true;

  var oHoraEntradaSaida           = new DBInputHora($('hora'));

  var sUrl                        = 'rec4_cadastrojornadas.RPC.php';
  var aJornadaHorarios            = new Array;

  js_montaGrid();

  function js_novaJornada() {

    if (!confirm('Deseja incluir nova jornada de trabalho?')) {
      return false;
    }
    window.location = 'rec4_cadastrojornadas001.php';
  }

  function js_pesquisar() {
    js_OpenJanelaIframe(
      '',
      'db_iframe_jornada',
      'func_jornada.php?lMostraFixos=false&funcao_js=parent.js_retornoPesquisa|rh188_sequencial|rh188_descricao|rh188_tipo',
      'Pesquisa Jornada',
      true
    );
  }

  function js_retornoPesquisa(iCodigoJornada, sDescricaoJornada, sTipo) {

    $('sequencial').value = iCodigoJornada;
    $('descricao').value  = sDescricaoJornada;
    $('tipo').value       = sTipo;

    db_iframe_jornada.hide();

    oAbaJornadaHoras.lBloqueada = false;

    js_carregarJornadas(iCodigoJornada);
  }

  function js_carregarJornadas (iCodigoJornada) {

    var oParametros                = new Object();
    oParametros.exec           = 'carregarJornadas';
    oParametros.iCodigoJornada = iCodigoJornada;
    aJornadaHorarios           = new Array();
    var oAjaxRequest = new AjaxRequest(sUrl, oParametros,
      function(oAjax, lErro){

        for (iJornada = 0; iJornada < oAjax.aRetornoJornadas.length; iJornada++) {
          aJornadaHorarios.push(oAjax.aRetornoJornadas[iJornada]);
        }

        js_registrosGrid();
      }
    );

    oAjaxRequest.setMessage("Salvando");
    oAjaxRequest.execute();
  }

  function js_lancar() {

    var iCodigoTipo      = $F('tiporegistros');
    var sHora            = $F('hora');

    var oTipoRegistros   = $('tiporegistros');
    var sDescricaoTipo   = oTipoRegistros.options[oTipoRegistros.selectedIndex].text;

    if (sHora == '') {
      alert('Hora da entrada/saída não informada.');
      return false;

    }

    for (var iJornadaHorario = 0; iJornadaHorario < aJornadaHorarios.length; iJornadaHorario++) {

      oJornadaHorario = aJornadaHorarios[iJornadaHorario];

      if(iCodigoTipo == oJornadaHorario.iCodigoTipo) {
        alert('Tipo de lançamento "'+ sDescricaoTipo +'" já existente na jornada.');
        return false;
      }

    }

    $('hora').value = '';
    $('hora').focus();

    iProximoIndice = oTipoRegistros.selectedIndex + 1;

    if (iProximoIndice >= (oTipoRegistros.length - 1)) {
      iProximoIndice = oTipoRegistros.length - 1;
    }

    oTipoRegistros.options[iProximoIndice].selected = true;

    oJornada                = new Object();
    oJornada.iCodigoTipo    = iCodigoTipo;
    oJornada.sDescricaoTipo = sDescricaoTipo;
    oJornada.sHora          = sHora;

    aJornadaHorarios.push(oJornada);

    js_registrosGrid();

  }

  function js_excluir(iCodigoTipo) {

    var aJornadaHorariosExcluir = new Array();
    iNovoIndice = 0;

    if (!confirm('Excluir o registro selecionado?')) {
      return false;
    }

    for (var iJornadaHorario = 0; iJornadaHorario < aJornadaHorarios.length; iJornadaHorario++) {

      oJornadaHorario = aJornadaHorarios[iJornadaHorario];

      if (oJornadaHorario.iCodigoTipo != iCodigoTipo) {
        aJornadaHorariosExcluir[iNovoIndice++] = oJornadaHorario;
      }
    }

    aJornadaHorarios = aJornadaHorariosExcluir;

    js_registrosGrid();
  }

  function js_registrosGrid() {

    oGridJornadas.clearAll(true);

    aBotoes = new Array();

    for (var iJornadaHorario = 0; iJornadaHorario < aJornadaHorarios.length; iJornadaHorario++) {

      oJornadaHorario  = aJornadaHorarios[iJornadaHorario];
      oGridJornadas.addRow([oJornadaHorario.iCodigoTipo, oJornadaHorario.sDescricaoTipo, oJornadaHorario.sHora, '']);

      oBotaoExcluir       = document.createElement('input');
      oBotaoExcluir.type  = 'button';
      oBotaoExcluir.value = 'Excluir';
      oBotaoExcluir.setAttribute('onclick', 'js_excluir('+oJornadaHorario.iCodigoTipo+')');

      oDadosBotoes                  = new Object();
      oDadosBotoes.oBotaoExcluir    = oBotaoExcluir;
      oDadosBotoes.sIdCelulaExcluir = oGridJornadas.aRows[iJornadaHorario].aCells[3].sId;

      aBotoes.push(oDadosBotoes);
    }

    oGridJornadas.renderRows();

    for ( var iBotoes = 0; iBotoes < aBotoes.length; iBotoes++ ) {

      oBotoes              = aBotoes[iBotoes];
      oCelulaBotaoExcluir  = document.getElementById(oBotoes.sIdCelulaExcluir);
      oCelulaBotaoExcluir.appendChild(oBotoes.oBotaoExcluir);
    }
  }

  function js_salvarJornada() {

    oParametros                = new Object();
    oParametros.exec           = 'salvarJornada';
    oParametros.iCodigoJornada = $F('sequencial');
    oParametros.sTipo          = encodeURIComponent( tagString( $F('tipo') ) );
    oParametros.sDescricao     = encodeURIComponent( tagString( $F('descricao') ) );

    var oAjaxRequest = new AjaxRequest(sUrl, oParametros,

      function (oAjax, lErro) {

        alert(oAjax.message.urlDecode().replace(/\\n/g, '\n'));

        if (lErro == false) {

          $('sequencial').value = oAjax.iCodigoJornada;

          oAbaJornada.setVisibilidade(false);

          oAbaJornadaHoras.setVisibilidade(true);
          oAbaJornadaHoras.lBloqueada = false;

          js_carregarJornadas(oAjax.iCodigoJornada);
        }
      }
    );

    oAjaxRequest.setMessage("Salvando");
    oAjaxRequest.execute();
  }

  function js_excluirJornada() {

    oParametros                = new Object();
    oParametros.exec           = 'excluirJornada';
    oParametros.iCodigoJornada = $F('sequencial');
    oParametros.sDescricao     = encodeURIComponent( tagString( $F('descricao') ) );

    if( oParametros.iCodigoJornada == '' || !confirm('Deseja excluir a jornada código '+ oParametros.iCodigoJornada +'?')) {
      return false;
    }

    var oAjaxRequest = new AjaxRequest(sUrl, oParametros,

      function (oAjax, lErro) {

        alert(oAjax.message.urlDecode().replace(/\\n/g, '\n'));

        if (lErro == false) {
          window.location = 'rec4_cadastrojornadas001.php';
        }
      }
    );

    oAjaxRequest.setMessage("Excluíndo jornada.");
    oAjaxRequest.execute();
  }

  function js_montaGrid() {

    oGridJornadas              = new DBGrid("dataGridJornadas");
    oGridJornadas.sName        = "dataGridJornadas";
    oGridJornadas.nameInstance = "oGridJornadas";
    oGridJornadas.setHeader(["Código Tipo", "Tipo","Hora", "Excluir"]);
    oGridJornadas.setCellWidth(["100px","100px", "100px", "100px"]);
    oGridJornadas.setCellAlign(["center", "center", "center", "center"]);
    oGridJornadas.show( $('grid_registros') );
    oGridJornadas.showColumn(false, 1);
  }

  function js_salvarJornadaHoras() {

    var aDados      = new Array;
    var oParametros = new Object();

    oGridJornadas.getRows().each( function (oLinha, iIndice) {

      oDados                     = new Object();
      oDados.iCodigoTipoRegistro = oLinha.aCells[0].content;
      oDados.sHora               = oLinha.aCells[2].content;

      aDados[iIndice]            = oDados;
    });

    oParametros.exec           = 'salvarHorarios';
    oParametros.iCodigoJornada = $F('sequencial');
    oParametros.aDados         = aDados;

    var oAjaxRequest = new AjaxRequest(sUrl, oParametros,
      function (oAjax, lErro) {
        alert(oAjax.message.urlDecode().replace(/\\n/g, '\n'));
      }
    );

    oAjaxRequest.setMessage("Salvando");
    oAjaxRequest.execute();
  }
</script>
</html>