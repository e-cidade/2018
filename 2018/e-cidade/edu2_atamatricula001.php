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
  <script language="JavaScript" type="text/javascript" src="scripts/classes/educacao/escola/ListaCalendario.classe.js"></script>
</head>

<body class='body-default'>
  <div class='container'>

    <form name="form1" id='form1'>
      <fieldset>
        <legend>Ata de Matrículas</legend>
        <table class="form-container">
          <tr>
            <td>Calendário:</td>
            <td id='ctnCalendario'></td>
          </tr>
          <tr>
            <td>Data de Corte:</td>
            <td>
              <input type="text" name="datacorte" id='datacorte' class="readonly field-size2" disabled="disabled" />
            </td>
          </tr>
          <tr>
            <td>Ensino:</td>
            <td >
              <select id='ensino'>
                <option value="">Selecione </option>
              </select>
            </td>
          </tr>
          <tr>
            <td>Modelo:</td>
            <td>
              <select id="modelo" name="modelo">
                <option value="" >Selecione o Modelo</option>
                <option value="I">Matrícula Inicial</option>
                <option value="E">Matrícula Especial</option>
              </select>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type ='button' value='Imprimir' id='imprimir' />
    </form>
  </div>
<?php
  db_menu();
?>
<script type="text/javascript">

var MSG_EDU2_ATAMATRICULA001 = "educacao.escola.edu2_atamatricula001.";

var oCalendario = new DBViewFormularioEducacao.ListaCalendario();
oCalendario.setOnChangeCallBack(getCalendarioSelecionado);

function getCalendarioSelecionado () {

  $('imprimir').removeAttribute('disabled');
  var iCalendario = oCalendario.getSelecionados().iCalendario;

  if( iCalendario == '' ) {
    limpaForm();
    return;
  }

  calculaDataCorte(iCalendario);
  buscaEnsinos(iCalendario);
}

oCalendario.getCalendarios();
oCalendario.show($('ctnCalendario'));

function limpaForm () {

  $('form1').reset();
  $('ensino').options.length = 0;
  $('ensino').add(new Option('Selecione', ''));
}

function calculaDataCorte(iCalendario) {

  var oParametros  = {'exec' : 'calculaDataCorte', 'iCalendario' : iCalendario };
  var oAjaxRequest = new AjaxRequest('edu4_calendario.RPC.php', oParametros, retornoDataCorteMatricula);
  oAjaxRequest.setMessage(_M(MSG_EDU2_ATAMATRICULA001 + "calculando_data_corte"));
  oAjaxRequest.execute();
}

function retornoDataCorteMatricula (oRetorno, lErro){

  if (lErro) {

    alert(oRetorno.message.urlDecode());
    $('imprimir').setAttribute('disabled', 'disabled');
    $('datacorte').value = '';
    return false;
  }

  $('datacorte').value = oRetorno.dataCorteMatriculaCalculada;
}


function buscaEnsinos(iCalendario) {

  var oParametros  = {'exec' : 'pesquisaEnsinoCalendario', 'iCalendario' : iCalendario };
  var oAjaxRequest = new AjaxRequest('edu_educacaobase.RPC.php', oParametros, retornoEnsino);
  oAjaxRequest.setMessage(_M(MSG_EDU2_ATAMATRICULA001 + "carregando_ensinos"));
  oAjaxRequest.execute();

}

function retornoEnsino(oRetorno, lErro) {

  if (lErro) {

    alert(oRetorno.message.urlDecode());
    return false;
  }

  $('ensino').options.length = 0;
  $('ensino').add(new Option('Selecione', ''));
  $('ensino').add(new Option('Todos', 'T'));

  oRetorno.aEnsinos.each( function( oDadoEnsino ) {
    $('ensino').add( new Option(oDadoEnsino.descricao.urlDecode(), oDadoEnsino.ed10_i_codigo) );
  });
}

$('imprimir').observe('click', function () {

  if ( $F('cboCalendario') == '' ) {

    alert( _M(MSG_EDU2_ATAMATRICULA001 + "informe_calendario") );
    return ;
  }

  if ( $F('ensino') == '' ) {

    alert( _M(MSG_EDU2_ATAMATRICULA001 + "informe_ensino") );
    return ;
  }

  if ( $F('modelo') == '' ) {

    alert( _M(MSG_EDU2_ATAMATRICULA001 + "informe_modelo") );
    return ;
  }

  var sUrl  = 'edu2_atamatricula002.php?iCalendario=' + $F('cboCalendario');
      sUrl += '&iEnsino=' + $F('ensino');
      sUrl += '&iModelo=' + $F('modelo');
      sUrl += '&dtBase=' + $F('datacorte');

  var oJanela = window.open(sUrl, '', 'scrollbars=1,location=0');
  oJanela.moveTo(0,0);
});

</script>
</body>
</html>