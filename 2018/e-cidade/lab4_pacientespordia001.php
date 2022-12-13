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

$oRotulo = new rotulocampo();
$oRotulo->label("la09_i_codigo");
$oRotulo->label("la09_i_exame");
$oRotulo->label("la08_c_descr");

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputInteger.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
<body  >
  <form class="container" id="frmCotasAtendimento">
    <fieldset>
      <legend>Cotas de Atendimento</legend>
      <table class="form-container">
        <tr>
          <td class="field-size3"><label for="cboLaboratorio">Laborat�rio:</label></td>
          <td>
            <select id='cboLaboratorio'>
              <option value="">Selecione</option>
            </select>
          </td>
        </tr>
        <tr>
          <td><label>Limite por dia:</label></td>
          <td><input type="text" id="limiteDiario" name="limiteDiario" value="" class="field-size2" /></td>
        </tr>
      </table>
      <fieldset class="separator">
        <legend>Por Exame</legend>
        <table class="form-container">
          <tr>
            <td>
              <label for=""><a href="#" id="ancoraExame">Exame:</a></label>
            </td>
            <td>
              <?php
                db_input( 'la09_i_exame',  10, $Ila09_i_exame,  true, 'text', 1 );
                db_input( 'la08_c_descr',  30, $Ila08_c_descr,  true, 'text', 3 );
                db_input( 'la09_i_codigo',  0, $Ila09_i_codigo, true, 'hidden', 3 );
              ?>
            </td>
          </tr>
          <tr>
            <td><label>Limite:</label></td>
            <td><input type="text" id="limiteExame" name="limiteExame" value="" class="field-size2" /></td>
          </tr>
        </table>
        <input type="button" name="adicionar" id="btnAdicionar" value="Adicionar" />

        <fieldset class="subcontainer separator" style="width: 475px;">
          <legend>Exames com Cotas</legend>
          <div id='ctnGridExames'></div>
        </fieldset>

      </fieldset>

    </fieldset>
    <input type="button" name="salvar"  id="btnSalvar"  value="Salvar" />
    <input type="button" name="excluir" id="btnExcluir" value="Excluir" />
  </form>
<?php
  db_menu();
?>
</body>
</html>
<script type="text/javascript">

$('la09_i_exame').addClassName('field-size2');

new DBInputInteger($('limiteDiario'));
new DBInputInteger($('limiteExame'));
new DBInputInteger($('la09_i_exame'));

var sRPC      = "lab4_cotasatendimento.RPC.php";
var sFonteMsg = "saude.laboratorio.lab4_pacientespordia001.";

/**
 * Laboratorios => esse array contén uma estrutura com todos dados do formulário. Todas informações são armazenadas nele
 * e atualizada a partir dele.
 */
var aLaboratorios = [];

var oCollection   = new Collection().setId("iExame");
var oGridExames   = new DatagridCollection(oCollection).configure({
  order    : false,
  height   : 120
});

oGridExames.addColumn("sExame", {
  label : "Exame",
  align : "left",
  width : "60%"
});
oGridExames.addColumn("iLimiteExame", {
  label : "Limite",
  align : "left",
  width : "20%"
});

oGridExames.addAction("E", 'Excluir', function(oEvento, oRegistro) {
  excluirExame(oRegistro);
});

oGridExames.show( $('ctnGridExames') );


/**
 * ação change ao trocar o laboratório
 */
$('cboLaboratorio').addEventListener('change', function(event) {

  if ( empty(this.value) ) {
    resetForm();
    return;
  }

  var oLaboratorio        = getLaboratorio(this.value);
  $('limiteDiario').value = oLaboratorio.iLimiteDiario;

  oGridExames.getCollection().clear();
  for (var oExame of oLaboratorio.aExames ) {
    oGridExames.getCollection().add(Object.assign({}, oExame));
  }
  oGridExames.reload();
});

/**
 * Valida se o laboratório esta informado antes de pesquisar os exames
 */
function verificaLaboratorioSelecionado( event ) {

  if ( empty($F('cboLaboratorio')) ) {

    event.preventDefault();
    event.stopImmediatePropagation();
    alert( _M(sFonteMsg + "selecione_laboratorio") );
    $('la09_i_exame').value  = '';
    $('la09_i_codigo').value = '';
    return false;
  }

  oLookUp.setParametrosAdicionais(['iLaboratorio='+$F('cboLaboratorio')]);
  return true;
}


$('la09_i_exame').addEventListener('change', function(event) {
  verificaLaboratorioSelecionado(event);
});

$('ancoraExame').addEventListener('click', function(event) {
  verificaLaboratorioSelecionado(event);
});

/**
 * Cria a lookup para pesquisa dos exames
 * @type {DBLookUp}
 */
var oLookUp = new DBLookUp( $('ancoraExame'), $('la09_i_exame'), $('la08_c_descr'), {
  sArquivo: 'func_exameslaboratorio.php',
  sLabel: 'Pesquisa Exames do Laboratório',
  sObjetoLookUp: 'db_iframe_lab_exame',
  aCamposAdicionais: ['db_la09_i_codigo']
});

oLookUp.setCallBack('onClick', function(aCampos) {

  $('la09_i_codigo').value = aCampos[2];
});

oLookUp.setCallBack('onChange', function(lErro, aCampos) {

  $('la09_i_codigo').value = '';
  $('la08_c_descr').value  = aCampos[0];
  if ( lErro ) {
    $('la09_i_exame').value  = '';
    return;
  }

  $('la08_c_descr').value  = aCampos[0];
  $('la09_i_codigo').value = aCampos[2];
});


$('btnAdicionar').addEventListener('click', function() {

  if ( empty($F('la09_i_exame')) ) {

    alert( _M(sFonteMsg + 'informe_exame') );
    return false;
  }

  if ( empty($F('limiteExame')) ) {

    alert( _M(sFonteMsg + 'informe_limite') );
    return false;
  }

  if ( new Number($F('limiteDiario')) < new Number($F('limiteExame')) ) {

    alert( _M(sFonteMsg + 'limite_exame_menor_limite_diario') );
    return false;
  }

  for (var oExameCollection of oGridExames.getCollection().get()) {

    if ( !empty(oExameCollection.iCodigoCotaExame) && oExameCollection.ID == $F('la09_i_exame') ) {

      alert( _M(sFonteMsg + 'exame_ja_existe') );
      return false;
    }
  }

  oGridExames.getCollection().add({
    iCodigoCotaExame : '',
    iExame           : $F('la09_i_exame'),
    sExame           : $F('la08_c_descr'),
    iSetorExame      : $F('la09_i_codigo'),
    iLimiteExame     : $F('limiteExame')
  });

  oGridExames.reload();
  limparExames();
});


function limparExames() {

  $('la09_i_exame').value  = '';
  $('la08_c_descr').value  = '';
  $('la09_i_codigo').value = '';
  $('limiteExame').value   = '';
}

/**
 * Exclui as cotas do exame
 * @param  {Object} oRegistro [description]
 * @return {[type]}           [description]
 */
function excluirExame(oRegistro) {

  // quando ainda n�o foin incluso no banco
  if ( empty(oRegistro.iCodigoCotaExame) ) {

    oGridExames.getCollection().remove(oRegistro.iExame);
    oGridExames.reload();
    return;
  }

  var oParametros  = Object.assign({}, oRegistro.build());
  oParametros.exec = 'excluirCotasExame';

  // quando j� foi incluso no banco
  new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

    alert(oRetorno.sMessage);
    if ( lErro ) {
      return;
    }

    var oLaboratorio = getLaboratorio($F('cboLaboratorio'));
    for (var i = 0; i < oLaboratorio.aExames.length; i++) {

      if ( oLaboratorio.aExames[i].iCodigoCotaExame == oRetorno.oExameRemovido.iCodigoCotaExame ){

        oLaboratorio.aExames.splice(i, 1);
        break;
      }
    }

    oGridExames.getCollection().remove(oRegistro.iExame);
    oGridExames.reload();
  }).setMessage(_M( sFonteMsg + 'excluindo_exame', oRegistro.build() )).execute();
}


/**
 * Salva
 */
$('btnSalvar').addEventListener('click', function () {

  if ( !validarDados() ) {
    return;
  }

  var oParametros           = Object.assign( {}, getLaboratorio($F('cboLaboratorio')));
  oParametros.exec          = 'salvar';
  oParametros.aExames       = oGridExames.getCollection().build();
  oParametros.iLimiteDiario = $F('limiteDiario'),

  new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

    alert(oRetorno.sMessage);
    if ( lErro ) {
      return;
    }
    oLaboratorio               = getLaboratorio(oRetorno.oCotasLaboratorio.iLaboratorio);
    oLaboratorio.iLimiteDiario = oRetorno.oCotasLaboratorio.iLimiteDiario;
    oLaboratorio.iCodigoCota   = oRetorno.oCotasLaboratorio.iCodigoCota;
    oLaboratorio.aExames       = oRetorno.oCotasLaboratorio.aExames;

    resetForm();
  }).setMessage(_M( sFonteMsg + 'buscandoLaboratorios' )).execute();
});



$('btnExcluir').addEventListener('click', function () {

  if ( empty($F('cboLaboratorio')) ) {

    alert( _M(sFonteMsg + "selecione_laboratorio") );
    return false;
  }

  var oParametros  = Object.assign( {}, getLaboratorio($F('cboLaboratorio')) );
  oParametros.exec = 'excluir';
  new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

    alert(oRetorno.sMessage);
    if ( lErro ) {
      return;
    }

    var oLaboratorio           = getLaboratorio(oRetorno.oLaboratorioExcluido.iLaboratorio);
    oLaboratorio.iCodigoCota   = '';
    oLaboratorio.iLimiteDiario = '';
    oLaboratorio.aExames       = [];

    resetForm();
  }).setMessage(_M( sFonteMsg + 'buscandoLaboratorios' )).execute();
});


function getLaboratorio(iCodigo) {

  for ( var oLaboratorio of aLaboratorios ) {
    if (oLaboratorio.iLaboratorio == iCodigo) {
      return oLaboratorio;
    }
  }
}


function validarDados() {

  if ( empty($F('cboLaboratorio')) ) {

    alert( _M(sFonteMsg + "informe_laboratorio") );
    return false;
  }

  if ( empty($F('limiteDiario')) ) {

    alert( _M(sFonteMsg + "informe_limite_diario") );
    return false;
  }

  var iLimiteDiario = new Number( $F('limiteDiario') );
  var aExames       = oGridExames.getCollection().build();
  for (var oExame of aExames ) {

    if ( new Number(oExame.iLimiteExame) > iLimiteDiario ) {

      alert( _M(sFonteMsg + 'limite_exame_menor_limite_diario') );
      return false;
    }
  }

  return true;
}

/**
 * Limpa os dados do formul�rio
 */
function resetForm() {

  $('cboLaboratorio').value = '';
  $('limiteDiario').value   = '';

  limparExames();
  oGridExames.getCollection().clear();
  oGridExames.reload();
}

function buscaDadosLaboratorio() {

  new AjaxRequest(sRPC, {exec: 'buscarDadosLaboratorios'}, function(oRetorno, lErro) {

    if(lErro){
      alert(oRetorno.sMessage);
      return;
    }

    aLaboratorios = oRetorno.aLaboratorios;
    for ( var oLaboratorio of aLaboratorios ) {
      $('cboLaboratorio').add(new Option(oLaboratorio.sNome, oLaboratorio.iLaboratorio));
    }

  }).setMessage(_M( sFonteMsg + 'buscandoLaboratorios' )).execute();
}

(function() {

  new AjaxRequest(sRPC, {exec: 'verificaUsoCotas'}, function(oRetorno, lErro) {

    if(lErro){
      alert(oRetorno.sMessage);
      return;
    }

    if ( oRetorno.tipo == 1) {

      setFormReadOnly( $('frmCotasAtendimento'), true );
      alert( _M( sFonteMsg + 'configuracao_financeiro_cadastrado' ) );
      return;
    }

    buscaDadosLaboratorio();
  }).setMessage( _M( sFonteMsg + 'validando_parametros' ) ).execute();

})();

</script>
