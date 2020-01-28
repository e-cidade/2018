
<div class="container">

  <form name="form1">
    <fieldset>
      <legend>Relações de Trabalho</legend>
      <table class="form-container" style="width: 580px;">
        <tr>
          <td class="field-size3"><label for="sProfissional">Profissional:</label></td>
          <td>
            <input type="text" class='readonly field-size10' name="sProfissional" id='sProfissional' value='<?=$oGet->sNome?>' />
            <input type="hidden" name="iVinculoEscola" id='iVinculoEscola' value='' />
            <input type="hidden" name="iCodigoRelacao" id='iCodigoRelacao' value='' />
          </td>
        </tr>
        <tr>
          <td>
            <label id="labelRegimeTrabalhoExclusao" style="display: none;" for='ed24_i_codigo'>Regime de Trabalho:</label>
            <label id="labelRegimeTrabalho" for='ed24_i_codigo'>
              <a href="#" id="ancoraRegimeTrabalho" >Regime de Trabalho:</a> 
            </label>
          </td>
          <td>
            <input type="text" name="ed24_i_codigo" id='ed24_i_codigo' value='' class="field-size2" />
            <input type="text" name="ed24_c_descr" id='ed24_c_descr' value='' class='readonly field-size8'  />
          </td>
        </tr>
        <tr>
          <td>
            <label for='cboFuncao'>Função Exercida:</label>
          </td>
          <td>
            <select id='cboFuncao' style="width: 424px;" onchange="buscarTipoHora(null);">
              <option>Selecione</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <label for='cboTipoHora'>Tipo Hora:</label>
          </td>
          <td>
            <select id='cboTipoHora' style="width: 424px;">
              <option>Selecione</option>
            </select>
          </td>
        </tr>
        <tr class='linhasReferenteEnsino' style="display: none;">
          <td>
            <label id="labelNivelEnsinoExclusao" for="ancoraNivelEnsino" style="display: none;"> Nível de Ensino: </label>
            <label id="labelNivelEnsino" for="ancoraNivelEnsino">
              <a href="#" id="ancoraNivelEnsino" >Nível de Ensino:</a>
            </label>
          </td>
          <td>
            <input type="text" name="ed10_i_codigo" id="ed10_i_codigo" value="" style="width:83px" />
            <input type="text" name="ed10_c_descr"  id="ed10_c_descr"  value=""  class='readonly' style="width:341px"  />
          </td>
        </tr>
        <tr class='linhasReferenteEnsino' style="display: none;">
          <td>
            <label id="labelTrabalhoExclusao" for="ancoraAreaTrabalho" style="display: none;">Área de Trabalho:</label>
            <label id="labelTrabalho" for="ancoraAreaTrabalho">
              <a href="#" id="ancoraAreaTrabalho" >Área de Trabalho:</a>
            </label>
          </td>
          <td>
            <input type="text" name="ed25_i_codigo" id="ed25_i_codigo" value="" style="width:83px" />
            <input type="text" name="ed25_c_descr"  id="ed25_c_descr" value="" class='readonly' style="width:341px" />
          </td>
        </tr>
      </table>
      <fieldset id='disciplinas' style="display: none;" class="separator">
        <legend>Disciplinas</legend>
        <table id='tblDisciplinas' class="form-container"></table>
      </fieldset>
    </fieldset>
    <input type="button" name="btnSalvar"   id='btnSalvar' value='Salvar' />
    <input type="button" name="btnExcluir"  id='btnExcluir' value='Excluir' style="display: none;" />
    <input type="button" name="btnCancelar" id='btnCancelar' value='Cancelar' disabled="disabled" />
  </form>
</div>
<div class="subcontainer" style="width: 1000px;">
  <fieldset>
    <legend>Registros</legend>
    <div id='ctnGridRelacaoTrabalho'></div>
  </fieldset>
</div>


<script type="text/javascript">

const MENSAGEM_FRMRELACAOTRABALHO = 'educacao.escola.db_frmrelacaotrabalho.';

var oGet = js_urlToObject();

/**
 * Array com as funcoes do profissional
 */
var aFuncoes = [];

var oCollection  = new Collection().setId("codigo");
var oGridRelacao = new DatagridCollection(oCollection).configure({order : false, height : 120 });

oGridRelacao.addColumn("regime",     {label : "Regime",     width : '20%'});
oGridRelacao.addColumn("ensino",     {label : "Ensino",     width : '25%'});
oGridRelacao.addColumn("area",       {label : "Área",       width : '20%'});
oGridRelacao.addColumn("disciplina", {label : "Disciplina", width : '20%'});

oGridRelacao.addAction("A", null, function(oEvento, oDados) {

  oDados.datagridRow.selectLine();
  preenchForm(oDados, false);
});

oGridRelacao.addAction("E", null, function(oEvento, oDados) {

  oDados.datagridRow.selectLine();
  preenchForm(oDados, true);
});

oGridRelacao.show( $('ctnGridRelacaoTrabalho') );

var oLookRegime = new DBLookUp( $('ancoraRegimeTrabalho'), $('ed24_i_codigo'), $('ed24_c_descr'), {
  sArquivo: 'func_regimetrabalho.php',
  sLabel: ' Pesquisa de Regimes de Trabalho',
  sObjetoLookUp: 'db_iframe_regimetrabalho'
});


$('ed10_i_codigo').addEventListener( 'change', function(){
    changeEnsino( false, [] );
});

var oLookEnsino = new DBLookUp( $('ancoraNivelEnsino'), $('ed10_i_codigo'), $('ed10_c_descr'), {
  sArquivo: 'func_ensino.php',
  sLabel: ' Pesquisa de Ensinos',
  sObjetoLookUp: 'db_iframe_ensino'
});


oLookEnsino.setCallBack('onClick', function(aCampos) {

  $('ed25_i_codigo').value = '';
  $('ed25_c_descr').value  = '';
  buscaDisciplinas();
});

oLookEnsino.setCallBack('onChange', changeEnsino);

function changeEnsino( lErro, aCampos ) {

  $('ed25_i_codigo').value = '';
  $('ed25_c_descr').value  = '';

  if ( !lErro ) {
    buscaDisciplinas();
  }
}

function validaSeEnsinoEstaSelecionado() {

  if ( $F('ed10_i_codigo') == '') {
    return false;
  }
  return true;
}

$('ancoraAreaTrabalho').addEventListener('click', adicionaEventoAreaTrabalho);
$('ed25_i_codigo').addEventListener('change', adicionaEventoAreaTrabalho);

var oLookAreaTrabalho = new DBLookUp( $('ancoraAreaTrabalho'), $('ed25_i_codigo'), $('ed25_c_descr'), {
    sArquivo: 'func_areatrabalho.php',
    sLabel: ' Pesquisa Área de Trabalho',
    sObjetoLookUp: 'db_iframe_areatrabalho'
  });

function adicionaEventoAreaTrabalho(event) {

  if ( !validaSeEnsinoEstaSelecionado() ) {

    alert(_M(MENSAGEM_FRMRELACAOTRABALHO + "informe_ensino" ) );
    event.preventDefault();
    event.stopPropagation();
    return;
  }
  
  oLookAreaTrabalho.setParametrosAdicionais( ['ensino=' + $F('ed10_i_codigo')] );
}


function buscarTipoHora(iTipoHoraSelecionar) {

  if ( iTipoHoraSelecionar == null) {

    $('ed10_i_codigo').value = '';
    $('ed10_c_descr').value  = '';
    $('ed25_i_codigo').value = '';
    $('ed25_c_descr').value  = '';

    if ( $('ed12_i_codigo') ) {

      $('ed12_i_codigo'). value = '';
      $('ed232_c_descr'). value = '';
    }
  }

  $('cboTipoHora').options.length = 0;
  $('cboTipoHora').add(new Option('Selecione', ''));

  var aTipoHoraAdicionado = [];
  for ( var oAtividade of aFuncoes ) {

    if ( oAtividade.iCodigo != $F('cboFuncao') ) {
      continue;
    }

    liberaNivelEnsino(oAtividade.lPermiteVincularEnsino);
    for( var sIndex in oAtividade.aResumoTurno) {

      var oTipoHora = oAtividade.aResumoTurno[sIndex]
      if (aTipoHoraAdicionado.in_array(oTipoHora.iTipoHoraTrabalho) ){
        continue;
      }
      aTipoHoraAdicionado.push(oTipoHora.iTipoHoraTrabalho);
      $('cboTipoHora').add(new Option(oTipoHora.sTipoHoraTrabalho, oTipoHora.iTipoHoraTrabalho));
    }
  }

  if (iTipoHoraSelecionar != null) {
    $('cboTipoHora').value = iTipoHoraSelecionar;
  }
}

function buscaFuncoesProfissional() {

  var oParam = {exec: 'buscaAtividadesProfissional', iVinculoEscola : $F('iVinculoEscola')};
  new AjaxRequest('edu4_rechumanoatividade.RPC.php', oParam, function(oRetorno, lErro) {

    if (lErro) {
      alert(oRetorno.sMessage);
      return;
    }

    if ( oRetorno.aAtividades.length == 0 ) {

      // alert ( _M(MENSAGEM_FRMRELACAOTRABALHO + 'cadastre_funcao_profissional') );
      return;
    }

    aFuncoes = oRetorno.aAtividades;
    $('cboFuncao').options.length = 0;
    $('cboFuncao').add(new Option('Selecione', ''));
    $('cboTipoHora').options.length = 0;
    $('cboTipoHora').add(new Option('Selecione', ''));

    for ( var oAtividade of aFuncoes ) {
      $('cboFuncao').add(new Option( oAtividade.sDescricao, oAtividade.iCodigo ));
    }

  }).setMessage( _M(MENSAGEM_FRMRELACAOTRABALHO + 'buscando_funcoes') ).execute();
}



(function () {

  var oParam = {'exec' : 'buscaRechumanoEscola', iRecHumano : oGet.ed75_i_rechumano };
  new AjaxRequest('edu4_relacaotrabalho.RPC.php', oParam, function(oRetorno, lErro) {

    if ( lErro ) {

      alert(oRetorno.sMessage);
      return;
    }

    $('iVinculoEscola').value = oRetorno.iVinculoEscola;

    buscaFuncoesProfissional();
    buscaRelacoesTrabalho();
  }).execute();

})();


/**
 * Busca as reelações de trabalho já inclusas
 * @return {void}
 */
function buscaRelacoesTrabalho() {

  oCollection.clear();
  var oParam = {'exec' : 'buscaRelacoesTrabalho', iVinculoEscola : $F('iVinculoEscola') };
  new AjaxRequest('edu4_relacaotrabalho.RPC.php', oParam, function(oRetorno, lErro) {

    if ( lErro ) {

      alert(oRetorno.sMessage);
      return;
    }

    for ( var oRelacao of oRetorno.aRelacoes ) {
      oCollection.add(oRelacao);
    }

    oGridRelacao.reload();
  }).execute();
}

function liberaNivelEnsino(lLiberar) {

  $('tblDisciplinas').innerHTML = '';

  $$('.linhasReferenteEnsino').each(function (oElement) {

    oElement.style.display = 'none';
    if ( lLiberar ) {
      oElement.style.display = 'table-row';
    }
  });

  $('disciplinas').style.display = 'none';
  if (lLiberar) {
    $('disciplinas').style.display = '';
  }
}


function validarInformacoesFormulario() {

  if ( empty($F('ed24_i_codigo')) ) {

    alert( _M(MENSAGEM_FRMRELACAOTRABALHO + 'informe_regime_trabalho') );
    return false;
  }

  if ( empty($F('cboFuncao')) ) {

    alert( _M(MENSAGEM_FRMRELACAOTRABALHO + 'informe_funcao') );
    return false;
  }

  if ( empty($F('cboTipoHora')) ) {

    alert( _M(MENSAGEM_FRMRELACAOTRABALHO + 'informe_tipo_hora') );
    return false;
  }

  /**
   * quando iCodigoRelacao esta vazio, é inclusão
   */
    var aElementos = $$('input[type="checkbox"]:checked');

    for ( var oAtividade of aFuncoes ) {

      if ( oAtividade.iCodigo == $F('cboFuncao') && oAtividade.lPermiteVincularEnsino) {

        if ( empty($F('ed10_i_codigo')) ) {

          alert( _M(MENSAGEM_FRMRELACAOTRABALHO + 'informe_nivel_ensino') );
          return false;
        }

        if ( empty($F('ed25_i_codigo')) ) {

          alert( _M(MENSAGEM_FRMRELACAOTRABALHO + 'informe_area_trabalho') );
          return false;
        }

        if ( !empty( $F('iCodigoRelacao') ) ) {

          if ( $('ed12_i_codigo') && empty($F('ed12_i_codigo')) && !oAtividade.lAtividadeSemRegencia ){

            alert( _M(MENSAGEM_FRMRELACAOTRABALHO + 'informe_disciplina') );
            return false;
          }
        } else {

          if ( aElementos.length == 0 && !oAtividade.lAtividadeSemRegencia ) {

            alert( _M(MENSAGEM_FRMRELACAOTRABALHO + 'marque_uma_disciplina') );
            return false;
          }
        }
      }
    }

  return true;
}

$('btnSalvar').addEventListener('click', function() {

  if ( !validarInformacoesFormulario() ) {
    return;
  }

  var oParam = {
    'exec'         : 'salvar',
    iCodigoRelacao : $F('iCodigoRelacao'),
    iVinculoEscola : $F('iVinculoEscola'),
    iRegime        : $F('ed24_i_codigo'),
    iFuncao        : $F('cboFuncao'),
    iTipoHora      : $F('cboTipoHora'),
    iEnsino        : $F('ed10_i_codigo'),
    iArea          : $F('ed25_i_codigo'),
    aDisciplinas   : [],
    iDisciplina    : ''
  };

  if ( !empty(oParam.iCodigoRelacao) && $('ed12_i_codigo') ) {
    oParam.iDisciplina = $F('ed12_i_codigo');
  } else {

    var aElementos = $$('input[type="checkbox"]:checked');
    for (var oElemento of aElementos ) {

      if ( oElemento.id == 'todas' ){
        continue;
      }
      oParam.aDisciplinas.push(oElemento.value);
    }
  }


  new AjaxRequest('edu4_relacaotrabalho.RPC.php', oParam, function(oRetorno, lErro) {

    alert(oRetorno.sMessage);
    if ( lErro ) {
      return;
    }

    limparForm();
    buscaRelacoesTrabalho();
  }).setMessage( _M(MENSAGEM_FRMRELACAOTRABALHO + 'aguarde_salvando') ).execute();
});


$('btnExcluir').addEventListener('click', function() {

  if ( !confirm(_M(MENSAGEM_FRMRELACAOTRABALHO + 'confirma_exclusao')) ) {
    return;
  }
  var oParam = { 'exec' : 'excluir', iCodigoRelacao : $F('iCodigoRelacao') };

  new AjaxRequest('edu4_relacaotrabalho.RPC.php', oParam, function(oRetorno, lErro) {

    alert(oRetorno.sMessage);
    if ( lErro ) {
      return;
    }

    limparForm();
    buscaRelacoesTrabalho();
  }).setMessage( _M(MENSAGEM_FRMRELACAOTRABALHO + 'aguarde_excluindo') ).execute();
});


function limparForm() {

  $('labelRegimeTrabalhoExclusao').style.display = 'none';
  $('labelRegimeTrabalho').style.display         = '';
  $('labelNivelEnsinoExclusao').style.display    = 'none';
  $('labelNivelEnsino').style.display            = '';
  $('labelTrabalhoExclusao').style.display       = 'none';
  $('labelTrabalho').style.display               = '';

  $('ed24_i_codigo').removeAttribute('disabled');
  $('ed24_c_descr').removeAttribute('disabled');
  $('ed10_i_codigo').removeAttribute('disabled');
  $('ed10_c_descr').removeAttribute('disabled');
  $('ed25_i_codigo').removeAttribute('disabled');
  $('ed25_c_descr').removeAttribute('disabled');
  $('cboFuncao').removeAttribute('disabled');
  $('cboTipoHora').removeAttribute('disabled');

  $('btnSalvar').style.display  = '';
  $('btnExcluir').style.display = 'none';

  $('iCodigoRelacao').value  = '';
  $('ed24_i_codigo').value   = '';
  $('ed24_c_descr').value    = '';
  $('ed10_i_codigo').value   = '';
  $('ed10_c_descr').value    = '';
  $('ed25_i_codigo').value   = '';
  $('ed25_c_descr').value    = '';
  $('cboFuncao').value       = '';

  $('tblDisciplinas').innerHTML = '';
  liberaNivelEnsino(false);
  var oEvent = new Event('change');
  $('cboFuncao').dispatchEvent(oEvent);
  $('btnCancelar').setAttribute('disabled', 'disabled');
}

/**
 * Botão Cancelar
 */
$('btnCancelar').addEventListener('click', function(){

  oGridRelacao.reload();
  limparForm();
});

/**
 * Preenche os dados da grid no form quando clicado em Alterar ou Excluir
 * @param  {Object}  oDados    dados da collection
 * @param  {boolean} lBloqueia se deve bloquear o from (só em exclusão)
 * @return {void}
 */
function preenchForm(oDados, lBloqueia) {

  limparForm();

  $('iCodigoRelacao').value  = oDados.codigo;
  $('ed24_i_codigo').value   = oDados.regime_codigo;
  $('ed24_c_descr').value    = oDados.regime;
  $('ed10_i_codigo').value   = oDados.ensino_codigo;
  $('ed10_c_descr').value    = oDados.ensino;
  $('ed25_i_codigo').value   = oDados.area_codigo;
  $('ed25_c_descr').value    = oDados.area;

  if ( oDados.funcao_codigo != '') {

    $('cboFuncao').value = oDados.funcao_codigo;
    buscarTipoHora(oDados.tipo_hora_codigo);
  }

  if ( lBloqueia ) {

    $('btnSalvar').style.display  = 'none';
    $('btnExcluir').style.display = '';

    $('labelRegimeTrabalhoExclusao').style.display = '';
    $('labelRegimeTrabalho').style.display         = 'none';
    $('labelNivelEnsinoExclusao').style.display    = '';
    $('labelNivelEnsino').style.display            = 'none';
    $('labelTrabalhoExclusao').style.display       = '';
    $('labelTrabalho').style.display               = 'none';

    $('ed24_i_codigo').setAttribute('disabled', 'disabled');
    $('ed24_c_descr').setAttribute('disabled', 'disabled');
    $('ed10_i_codigo').setAttribute('disabled', 'disabled');
    $('ed10_c_descr').setAttribute('disabled', 'disabled');
    $('ed25_i_codigo').setAttribute('disabled', 'disabled');
    $('ed25_c_descr').setAttribute('disabled', 'disabled');
    $('cboFuncao').setAttribute('disabled', 'disabled');
    $('cboTipoHora').setAttribute('disabled', 'disabled');
  }

  if ( !empty(oDados.ensino_codigo) ) {
    liberaNivelEnsino(true);
  }

  if ( !empty(oDados.disciplina_codigo) || !empty( $F('iCodigoRelacao') ) ) {

    adicionaAncoraDisciplina(oDados);

    if ( lBloqueia ) {

      $('labelDisciplinaExclusao').style.display = '';
      $('labelDisciplina').style.display         = 'none';
      $('ed12_i_codigo').setAttribute('disabled', 'disabled');
    }
  }

  $('btnCancelar').removeAttribute('disabled');
}

function adicionaAncoraDisciplina(oDados) {

  $('tblDisciplinas').innerHTML = '';

  var oLink       = document.createElement('a');
  oLink.href      = '#';
  oLink.id        = 'ancoraDisciplina';
  oLink.innerHTML = 'Disciplina:';

  var oLabelDisciplinaExclusao           = document.createElement('label');
  oLabelDisciplinaExclusao.id            = "labelDisciplinaExclusao";
  oLabelDisciplinaExclusao.innerHTML     = "Disciplina:";
  oLabelDisciplinaExclusao.style.display = "none";
  oLabelDisciplinaExclusao.setAttribute( "for", "ed12_i_codigo" );

  var oLabelDisciplina           = document.createElement('label');
  oLabelDisciplina.id            = "labelDisciplina";
  oLabelDisciplina.for           = "ed12_i_codigo";
  oLabelDisciplina.style.display = "";
  oLabelDisciplina.setAttribute( "for", "ed12_i_codigo" );

  var oInputCodigo         = document.createElement('input');
  oInputCodigo.type        = 'text';
  oInputCodigo.name        = 'ed12_i_codigo';
  oInputCodigo.id          = 'ed12_i_codigo';
  oInputCodigo.value       = oDados.disciplina_codigo;
  oInputCodigo.style.width = '83px';

  var oInputDescricao         = document.createElement('input');
  oInputDescricao.type        = 'text';
  oInputDescricao.name        = 'ed232_c_descr';
  oInputDescricao.id          = 'ed232_c_descr';
  oInputDescricao.style.width = '341px';
  oInputDescricao.value       = oDados.disciplina;
  oInputDescricao.addClassName('readonly' );
  oInputDescricao.setAttribute('disabled', 'disabled' );

  var oRow = $('tblDisciplinas').insertRow(0);

  var oCcell1 = oRow.insertCell(0);
  oCcell1.addClassName("field-size3");
  oCcell1.appendChild(oLabelDisciplinaExclusao);
  oLabelDisciplina.appendChild(oLink);
  oCcell1.appendChild(oLabelDisciplina);
  var oCcell2 = oRow.insertCell(1);
  oCcell2.appendChild(oInputCodigo);
  oCcell2.appendChild(oInputDescricao);


  var aDisciplinasAdicionadas = [];
  for ( var oItem of oCollection.get() ) {

    if ( !empty(oItem.disciplina_codigo) ) {
      aDisciplinasAdicionadas.push(oItem.disciplina_codigo);
    }
  }

  if ( validaSeEnsinoEstaSelecionado() ) {

    var oLookDisciplina = new DBLookUp( oLink, oInputCodigo, oInputDescricao, {
      sArquivo: 'func_disciplinarelacao.php',
      sLabel: ' Pesquisa de Disciplinas',
      sObjetoLookUp: 'db_iframe_disciplina',
      aParametrosAdicionais : ['ensino=' + $F('ed10_i_codigo'), 'disciplinas='+aDisciplinasAdicionadas.join()]
    });
  }
}


/**
 * Busca Disciplinas
 * Mesmo código utilizado no código anterior
 */
function buscaDisciplinas() {

  if ( empty($F('ed10_i_codigo')) ) {

    $('tblDisciplinas').innerHTML = '';
    return false;
  }

  if ( !empty($F('iCodigoRelacao')) ) {

    adicionaAncoraDisciplina( {'disciplina_codigo': null, 'disciplina': ''} );
    return false;
  }

  var sAction = 'PesquisaDisciplina';
  var url     = 'edu1_relacaotrabalhoRPC.php';
  parametros  = 'sAction='+sAction+'&ensino='+$F('ed10_i_codigo');

  new Ajax.Request(url,{method    : 'post',
                                    parameters: parametros,
                                    onComplete: js_retornaPesquisaDisciplina
                                   });
}

function js_retornaPesquisaDisciplina( oAjax ) {

  var oRetorno = eval("("+oAjax.responseText+")");

  if( oRetorno.length == 0 ) {

    $('tblDisciplinas').innerHTML = '<td><b>Nenhuma disciplina disponível.</b></td>';
    return;
  }

  var todas = '<input type="checkbox" name="todas" id="todas" value="" onclick="js_todas();">Todas';
  sHtml     = '<tr><td><b>' + todas + '</b></td>';

  sHtml += '<td>';
  sHtml += ' <table><tr>';
  cont   = 0;

  for (var oDisciplina of oRetorno ) {

    cont++;
    sHtml += '<td><input type="checkbox" name="coddisciplina[]" id="coddisciplina" value="'+oDisciplina.ed12_i_codigo+'"> '+oDisciplina.ed232_c_descr.urlDecode()+'</td>';
    if( cont % 3 == 0 ) {
      sHtml += ' </tr><tr>';
    }
  }
  sHtml += ' </tr></table>';
  sHtml += '</td>';


  sHtml += '</tr>';
  $('tblDisciplinas').innerHTML = sHtml;
}


function js_todas() {

  tam = document.form1.coddisciplina.length;

  if( tam == undefined ) {

    if( document.form1.todas.checked == true ) {
      document.form1.coddisciplina.checked = true;
    } else {
      document.form1.coddisciplina.checked = false;
    }
  } else {

    for( t = 0; t < tam; t++ ) {

      if( document.form1.todas.checked == true ) {
        document.form1.coddisciplina[t].checked = true;
      } else {
        document.form1.coddisciplina[t].checked = false;
      }
    }
  }
}

</script>