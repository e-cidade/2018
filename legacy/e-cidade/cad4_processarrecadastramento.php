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
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/classes/cadastro/civitas/DBViewAtualizacaoCadastral.classe.js"></script>
  <style>
   .codigo_matricula{
     cursor : pointer;
   }
   .codigo_matricula:hover{
     text-decoration: underline;
   }
  </style>
</head>
<body class='body-default'>

  <div class='container' style='width:400px;'>
    <form action="post" name='form1'>
      <fieldset>
        <legend>Parâmetros de pesquisa</legend>
        <table class='form-container'>
          <tr>
            <td class='field-size2'><label for="cboSchema" >Importação:</label></td>
            <td>
              <select name="schema" id="cboSchema">
                <option value="">Selecione...</option>
              </select>
            </td>
          </tr>
          <tr>
            <td class='field-size2'><label for="cboSetor">Setor:</label></td>
            <td>
              <select name="setor" id="cboSetor">
                <option value="">Selecione...</option>
              </select>
            </td>
          </tr>
          <tr>
            <td class='field-size2'><label for="cboQuadra">Quadra:</label></td>
            <td>
              <select name="quadra" id="cboQuadra">
                <option value="">Selecione...</option>
              </select>
            </td>
          </tr>
          <tr>
            <td class='field-size2'><label for="cboFiltro">Filtro:</label></td>
            <td>
              <select name="filtro" id="cboFiltro">
                <option value="0">Visualizar todos</option>
                <option value="1">Somente com aumento de IPTU</option>
                <option value="2">Somente com diminuição de IPTU</option>
              </select>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" value="Pesquisar" id='btnPesquisar' disabled />
    </form>
  </div>

  <div class='subcontainer' style='width:1000px;' >
    <fieldset >
      <legend>Matrículas</legend>
      <div id='ctnGrid'></div>
    </fieldset>
    <input type="button" value="Atualizar" id='btnAtualizarGeral' />
    <input type="button" value="Rejeitar"  id='btnRejeitarGeral' />
  </div>
<?php
  db_menu();
?>

<script type='text/javascript'>

var sRPC = 'cad4_recadastramento.RPC.php';

var oCollection     = new Collection().setId("iMatricula");
var oGridMatriculas = new DatagridCollection(oCollection).configure({
  order    : false,
  height   : 300
});

oGridMatriculas.getGrid().setCheckbox(1);
oGridMatriculas.addColumn("iMatricula", {
  label : "Matrícula",
  align : "right",
  width : "10%"
}).transformCallback = function( iMatricula ) {
  return "<span class='codigo_matricula' onclick='detalhamento("+iMatricula+")'>" + iMatricula +" </span>";
};
oGridMatriculas.addColumn("sRazao", {
  label : "Nome / Razão Social",
  align : "left",
  width : "50%"
});
oGridMatriculas.addColumn("nValorAtual", {
  label : "Valor Atual",
  align : "right",
  width : "18%"
}).transform('number');
oGridMatriculas.addColumn("nValorNovo", {
  label : "Novo Valor",
  align : "right",
  width : "20%"
}).transform('number');


oGridMatriculas.show($('ctnGrid'));

(function(){

  $('cboSchema').options.length = 0;
  $('cboSetor').options.length  = 0;
  $('cboSchema').add(new Option('Selecione...', '') );
  $('cboSetor').add(new Option('Selecione...', '') );
  new AjaxRequest(sRPC, {exec : 'buscarFiltros'}, function(oRetorno, lErro) {

    if ( lErro ) {

      alert(oRetorno.sMessage);
      return;
    }

    for (var oSchema of oRetorno.aSchemas) {

      var oOption       = document.createElement('option');
      oOption.value     = oSchema.j142_sequencial;
      oOption.innerHTML = oSchema.sDescricao;
      oOption.setAttribute('data-schema', oSchema.j142_schema);
      $('cboSchema').appendChild(oOption);
    }

    if (oRetorno.aSchemas.length > 0) {
      $('btnPesquisar').removeAttribute('disabled');
    }
  }).setMessage('Buscando schemas...').execute();

  $('cboSchema').addEventListener('change', function() {

    oGridMatriculas.clear();
    $('cboFiltro').value = 0;
    $('cboSetor').innerHTML = '';
    $('cboSetor').add(new Option('Selecione...', '') );
    $('cboQuadra').innerHTML = '';
    $('cboQuadra').add(new Option('Selecione...', '') );

    if (empty(this.value)) {
      return false;
    }

    var oParametros = {
      'exec'    : 'buscarSetores',
      'iSchema' : $('cboSchema').value,
      'sSchema' : getSchema()
    }

    new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

      for (var oSetor of oRetorno.aSetores) {

        var oOption = document.createElement('option');
        oOption.setAttribute('value', oSetor.j30_codi);
        oOption.appendChild(document.createTextNode(oSetor.j30_descr));

        $('cboSetor').appendChild(oOption);
      }
    }).setMessage('Buscando setores...').execute();
  });

  $('cboSetor').addEventListener('change', function() {

    oGridMatriculas.clear();
    $('cboFiltro').value = 0;
    $('cboQuadra').innerHTML = '';
    $('cboQuadra').add(new Option('Selecione...', '') );

    if (empty(this.value)) {
      return false;
    }

    var oParametros = {
      'exec'    : 'buscarQuadras',
      'iSchema' : $('cboSchema').value,
      'sSetor' : $('cboSetor').value,
      'sSchema' : getSchema()
    }

    new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

      for (var oQuadra of oRetorno.aQuadras) {

        var oOption = document.createElement('option');
        oOption.setAttribute('value', oQuadra.j34_quadra);
        oOption.appendChild(document.createTextNode(oQuadra.j34_quadra));

        $('cboQuadra').appendChild(oOption);
      }
    }).setMessage('Buscando quadras...').execute();
  });

  $('cboQuadra').addEventListener('change', function() {

    oGridMatriculas.clear();
    $('cboFiltro').value = 0;
  });

  $('cboFiltro').addEventListener('change', function() {
    oGridMatriculas.clear();
  });

})();

$('btnPesquisar').addEventListener('click', function() {

  if ( empty($F('cboSchema')) || empty($F('cboSetor')) ) {

    alert('Selecione uma data de Importação e um Setor para pesquisar as matrículas.');
    return;
  }

  oCollection.clear();
  oGridMatriculas.reload();
  var oParametros = {
    exec : 'buscarMatriculas',
    sSchema : getSchema(),
    iSchema : $F('cboSchema'),
    iSetor  : $F('cboSetor'),
    sQuadra : $F('cboQuadra'),
    iFiltro : $F('cboFiltro')
  }
  new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

    if ( lErro ) {

      alert(oRetorno.sMessage);
      return;
    }
    for (var oMatricula of oRetorno.aMatriculas) {
      oCollection.add(oMatricula);
    }
    oGridMatriculas.reload();

    oGridMatriculas.getGrid().getRows().forEach(function(oRow, iItem) {

      var oDados = oGridMatriculas.getCollection().get()[iItem];

      if (oDados.nValorAtual != oDados.nValorNovo) {

        oRow.removeClassName('normal');
        oRow.addClassName('sucess');
      }
    });
  }).setMessage('Buscando matrículas...').execute();

})

var oViewDetalhamento = null;

/**
 * Abre a window de detalhamento da matrícula
 * @param {integer} iMatricula matrícula selecionada
 */
function detalhamento(iMatricula) {

  if ( oViewDetalhamento != null ) {
    return;
  }

  var oSchema = {
    'sSchema' : getSchema(),
    'iSchema' : $F('cboSchema')
  };

  oViewDetalhamento = new DBViewAtualizacaoCadastral( oCollection.get(iMatricula).build(), oSchema);
  oViewDetalhamento.matriculasSelecionadas(oCollection.build());
  oViewDetalhamento.setCallbackFechar(function(){

    oViewDetalhamento = null;
    $('btnPesquisar').click();

  });
  oViewDetalhamento.show();
}

/**
 * Monta um array com as matrículas selecionadas na grid
 * @return {void}
 */
function buscarSelecionados() {

  var aMatriculasSelecionadas = [];
  var aLinhasGrid             = oGridMatriculas.getGrid().aRows;

  for ( var oLinha of aLinhasGrid) {

    if (oLinha.isSelected ) {
      aMatriculasSelecionadas.push( oLinha.itemCollection.iMatricula );
    }
  }
  return aMatriculasSelecionadas;
}

/**
 * Envia a uma requisição para Atualizar/Rejeitar as matrículas
 * @return {void}
 */
function enviarRequisicao(lAtualizar) {

  var sMsg             = 'Atualizando matrículas selecionadas...';
  var acao             = 'atualizar';
  var sMsgConfirmacao  = "Tem certeza que deseja Atualizar o cadastro das matrículas selecionadas?";

  if ( !lAtualizar ) {

    sMsg             = 'Rejeitando matrículas selecionadas...';
    acao             = 'rejeitar';
    sMsgConfirmacao  = "Tem certeza que deseja Rejeitar a atualização do cadastro das matrículas selecionadas?";
  }

  var aMatriculas = buscarSelecionados();
  if ( aMatriculas.length == 0 ) {

    alert('Selecione ao menos uma matrícula.');
    return false;
  }

  if (!confirm( sMsgConfirmacao )) {
    return false;
  }

  var oParametros = {
    exec              : acao,
    aMatriculas       : aMatriculas,
    sNomeImportacao   : getSchema(),
    iCodigoImportacao : $F('cboSchema')
  };
  new AjaxRequest(sRPC, oParametros, function (oRetorno, lErro){

    alert(oRetorno.sMessage);
    if ( lErro ) {
      return false;
    }

    $('btnPesquisar').click();

  }).setMessage(sMsg).execute();
};

$('btnAtualizarGeral').addEventListener('click', function(){
  enviarRequisicao(true);
});

$('btnRejeitarGeral').addEventListener('click', function(){
  enviarRequisicao(false);
});

function getSchema() {
  return $('cboSchema').options[$('cboSchema').selectedIndex].getAttribute("data-schema");
}

</script>
</body>
</html>