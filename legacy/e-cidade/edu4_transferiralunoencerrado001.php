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
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
</head>
<body class='body-default'>
  <div class='container'>

    <form name="form1" method="post" action="">
      <fieldset>
        <legend>Transferência de Alunos Encerrados</legend>
        <fieldset class="separator">
          <legend>Destino</legend>
          <table class="form-container">
            <tr>
              <td class="field-size2"><label for='tipoEscola'>Tipo: </label></td>
              <td>
                <select id='tipoEscola'>
                  <option value="">Selecione...</option>
                  <option value="1">Escola Fora</option>
                  <option value="2">Escola Rede</option>
                </select>
              </td>
            </tr>
            <tr>
              <td>
                <label for="iEscolaDestino" id="labelEscolaDestino" >Escola:</label>
                <a href="#" id="ancoraEscolaDestino" style="display: none">Escola:</a>
              </td>
              <td>
                <input type="text" id="iEscolaDestino" class="field-size2 readonly" disabled="" />
                <input type="text" id="sEscolaDestino" style="width: 399px"  class="readonly" disabled="" />
              </td>
            </tr>
          </table>
        </fieldset>

        <fieldset class="separator">
          <legend>Origem</legend>
          <div class="text-left">Selecione o curso e a etapa para listar os alunos encerrados.</div>
          <table class="form-container">
            <tr>
              <td class="field-size2"><label for='cboCurso'>Curso:</label></td>
              <td>
                <select id='cboCurso'>
                  <option value="">Selecione...</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><label for='cboEtapa'>Etapa:</label></td>
              <td>
                <select id='cboEtapa'>
                  <option value="">Selecione...</option>
                </select>
              </td>
            </tr>
          </table>

          <fieldset style="width:550px;">
            <legend>Selecione os alunos</legend>
            <div id='ctnGrid'></div>
          </fieldset>

        </fieldset>
        <fieldset class="separator">
          <legend>Informação para a Guia de Transferência</legend>
          <table class="form-container">
            <tr>
              <td class="field-size2">
                <label for="cboEmissor">Emissor:</label>
              </td>
              <td>
                <select id="cboEmissor">
                  <option value="">Selecione...</option>
                </select>
              </td>
            </tr>
          </table>
        </fieldset>
      </fieldset>

      <input type="button" name="salvar" value="Salvar" id='btnSalvar' />
    </form>

  </div>
<?php db_menu(); ?>
</body>
<script type="text/javascript">


var sRPCTransferencia = "edu4_transferiralunosencerrados.RPC.php";
var sArquivoMensagem  = "educacao.escola.edu4_transferiralunoencerrado001.";
var aEmissores        = [];

var oCollection = new Collection().setId("iMatricula");
var oGridAlunos = new DatagridCollection(oCollection).configure({
  order    : false,
  height   : 120
});

oGridAlunos.getGrid().setCheckbox(0);
oGridAlunos.addColumn("sAluno", {
  label : "Aluno",
  align : "left",
  width : "75%"
});
oGridAlunos.addColumn("sSituacao", {
  label : "Situação",
  align : "left",
  width : "25%"
}).transformCallback = function( sTexto ) {
  return "<label title ='"+ sTexto +"' >" + sTexto +" </label>";
};
oGridAlunos.show($('ctnGrid'));


/**
 * define os callbacks
 */
$('tipoEscola').addEventListener('change', buscaEscolas);
$('cboCurso').addEventListener('change', pesquisaEtapaEnsino);
$('cboEtapa').addEventListener('change', buscaAlunos);

function buscaEscolas() {

  var iTipo = $F('tipoEscola');

  $('labelEscolaDestino').style.display  = '';
  $('ancoraEscolaDestino').style.display = 'none';
  $('iEscolaDestino').addClassName('readonly');
  $('iEscolaDestino').setAttribute('disabled', '');
  $('iEscolaDestino').value = '';
  $('sEscolaDestino').addClassName('readonly');
  $('sEscolaDestino').setAttribute('disabled', '');
  $('sEscolaDestino').value = '';

  if ( empty(iTipo) ) {
    return;
  }

  $('iEscolaDestino').removeAttribute('disabled');
  $('iEscolaDestino').removeClassName('readonly');
  $('labelEscolaDestino').style.display  = 'none';
  $('ancoraEscolaDestino').style.display = '';

  var sArquivo = 'func_escola.php';
  $('iEscolaDestino').setAttribute( 'lang', 'ed18_i_codigo' );
  $('sEscolaDestino').setAttribute( 'lang', 'ed18_c_nome' );

  var aParametrosAdicionais = ["lRemoverEscolaLogada=true"];
  if ( iTipo == 1 ) {

    $('iEscolaDestino').setAttribute( 'lang', 'ed82_i_codigo' );
    $('sEscolaDestino').setAttribute( 'lang', 'ed82_c_nome' );
    aParametrosAdicionais = ["lRetornoPadrao=true"];
    sArquivo = 'func_escolaproc.php';
  }


  oLookUpEscola = new DBLookUp( $('ancoraEscolaDestino'), $('iEscolaDestino'), $('sEscolaDestino'), {
    sArquivo: sArquivo,
    sLabel: 'Pesquisa de Escolas',
    sObjetoLookUp: 'db_iframe_escolas',
    aParametrosAdicionais: aParametrosAdicionais,
    zIndex: '1500'
  });
}

/**
 * Busca as etapas do ensino selecionado
 */
function pesquisaEtapaEnsino( ) {

  oCollection.clear();
  oGridAlunos.reload();

  $('cboEtapa').options.length = 0;
  $('cboEtapa').add( new Option('Selecione...', '') );

  if ( empty($F('cboCurso')) ) {
    return;
  }

  var oParametros  = {'exec' : 'pesquisaEtapa', iCurso : $F('cboCurso'), lFiltarMatriculaConcluida : 'true'};
  new AjaxRequest("edu_educacaobase.RPC.php", oParametros, function(oRetorno, lErro) {

    if (lErro) {

      alert('Não foi encontrada nenhuma etapa com alunos encerrados para o curso selecionado.');
      return;
    }

    for ( var oEtapa of oRetorno.dados) {
      $('cboEtapa').add(new Option(oEtapa.ed11_c_descr.urlDecode(), oEtapa.ed11_i_codigo));
    }
  }).setMessage( _M( sArquivoMensagem + 'buscando_etapas' ) ).execute();
}

/**
 * Busca os alunos encerrados para
 */
function buscaAlunos() {

  oCollection.clear();
  oGridAlunos.reload();
  if ( empty($F('cboEtapa')) ) {
    return;
  }

  var oParametros = {'exec' : 'buscarAlunosDisponiveisParaTransferencia', 'iEtapa' : $F('cboEtapa')};
  new AjaxRequest( sRPCTransferencia, oParametros, function(oRetorno, lErro) {

    if (lErro) {

      alert(oRetorno.sMessage);
      return;
    }

    for (var oAluno of oRetorno.aAlunos ) {
      oCollection.add(oAluno);
    }

    oGridAlunos.reload();
  }).setMessage( _M( sArquivoMensagem + 'buscando_alunos' ) ).execute();
}


(function() {

  var oParametros  = {'exec' : 'buscarCursosEscola'};
  new AjaxRequest("edu_educacaobase.RPC.php", oParametros, function(oRetorno, lErro) {

    if (lErro) {

      alert(oRetorno.message.urlDecode());
      return;
    }

    // o valor do curso é o código do ensino
    for ( var oCurso of oRetorno.aCursos) {
      $('cboCurso').add(new Option(oCurso.sDescricao.urlDecode(), oCurso.iEnsino));
    }
  }).setMessage( _M( sArquivoMensagem + 'buscando_cursos' ) ).execute();
})();


function validarDados() {

  if ( empty($F('iEscolaDestino')) ) {

    alert( _M( sArquivoMensagem + 'selecione_escola_destino' ) );
    return false;
  }

  if ( empty($F('cboCurso')) ) {

    alert( _M( sArquivoMensagem + 'selecione_curso' ) );
    return false;
  }

  if ( empty($F('cboEtapa')) ) {

    alert( _M( sArquivoMensagem + 'selecione_etapa_origem' ) );
    return false;
  }

  return true;
}


$('btnSalvar').addEventListener('click', function() {

  if ( !validarDados() ) {
    return
  }

  var aAlunosSelecionados = [];
  var aLinhasGrid         = oGridAlunos.getGrid().aRows;
  for ( var oLinha of aLinhasGrid) {

    if (oLinha.isSelected ) {
      aAlunosSelecionados.push( oLinha.itemCollection.iMatricula );
    }
  }

  if ( aAlunosSelecionados.length == 0 ) {
    alert( _M( sArquivoMensagem + 'selecione_aluno' ) );
    return;
  }

  var oParametros            = {'exec' : 'salvar', 'aMatriculas' : aAlunosSelecionados };
  oParametros.iTipoDestino   = $F('tipoEscola');
  oParametros.iEscolaDestino = $F('iEscolaDestino');

  new AjaxRequest( sRPCTransferencia, oParametros, function(oRetorno, lErro) {

    alert(oRetorno.sMessage);
    if (lErro) {
      return;
    }

    oCollection.clear();
    oGridAlunos.reload();
    emitirGuiaTransferencia(oRetorno.iTransferencia);
    document.form1.reset();
    buscaEscolas();

  }).setMessage( _M( sArquivoMensagem + 'transferindo_alunos' ) ).execute();
});

function emitirGuiaTransferencia( iTransferencia ) {


  var sUrl  = 'edu2_guiatransferenciaencerrados002.php';
      sUrl += '?iTransferencia='+iTransferencia;

  if ( $F('cboEmissor') != '') {

    var oEmissor          = aEmissores[$F('cboEmissor')];
    sUrl += "&sEmissor="  + btoa(oEmissor.nome);
    sUrl += "&sFuncao="   + btoa(oEmissor.funcao);
    sUrl += "&sAtoLegal=" + btoa(oEmissor.atolegal);
  }

  window.open( sUrl, '', 'scrollbars=1,location=0');
}

function buscarEmissor() {

  new AjaxRequest( sRPCTransferencia, {'exec' : 'buscarEmissor'}, function(oRetorno, lErro) {

    if (lErro) {
      alert(oRetorno.sMessage);
      return;
    }

    aEmissores = oRetorno.aEmissores;
    aEmissores.forEach(function (oEmissor, iIndex) {

      var sOption = oEmissor.funcao + ' - ' + oEmissor.nome;

      if ( !empty(oEmissor.atolegal) ) {
        sOption += ' (' + oEmissor.atolegal + ')';
      }

      $('cboEmissor').add(new Option(sOption, iIndex));
    });

  }).setMessage( _M( sArquivoMensagem + 'buscando_emissor' ) ).execute();
}
buscarEmissor();

</script>
</html>
