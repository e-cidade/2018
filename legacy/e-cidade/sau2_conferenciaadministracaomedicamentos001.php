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
require_once("libs/db_utils.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
    <form name="formConferencia" action="#">
      <fieldset>
        <legend>Conferência de Administração de Medicamentos</legend>
        <table class="form-container">
          <tr>
            <td>
              <label for="periodoInicial">Período: </label>
            </td>
            <td>
              <?php
                db_inputdata( 'periodoInicial', null, null, null, true, 'text', 1 );
              ?>
              <label for="periodoFinal"> até </label>
              <?php
                db_inputdata( 'periodoFinal',  null, null, null, true, 'text', 1 );
              ?>
            </td>
          </tr>
          <tr>
            <td id="lancadorMedicamentos" colspan="2"></td>
          </tr>
        </table>
      </fieldset>
      <input id="imprimir" type="button" value="Imprimir" disabled="disabled" />
    </form>
  </div>
</body>
<?php
db_menu( db_getsession( "DB_id_usuario" ), db_getsession( "DB_modulo" ), db_getsession( "DB_anousu" ), db_getsession( "DB_instit" ) );
?>
<script>
const MENSAGENS_SAU2_CONFERENCIAMEDICAMENTOS = 'saude.ambulatorial.sau2_conferenciaadministracaomedicamentos001.';

var oLancadorMedicamentos                = new DBLancador( 'oLancadorMedicamentos' );
    oLancadorMedicamentos.sTextoFieldset = 'Adicionar Medicamento';
    oLancadorMedicamentos.setGridHeight( 150 );
    oLancadorMedicamentos.setLabelAncora( 'Medicamento:' );
    oLancadorMedicamentos.setNomeInstancia( 'oLancadorMedicamentos' );
    oLancadorMedicamentos.setParametrosPesquisa( 'func_matmateralt.php', [ 'm60_codmater', 'm60_descr' ] );
    oLancadorMedicamentos.show( $('lancadorMedicamentos') );

$('imprimir').onclick = function() {
  imprimirRelatorio();
};

/**
 * Verifica se a unidade é uma UPS
 */
function validaDepartamentoUPS() {

  var oParametros           = {};
      oParametros.sExecucao = 'verificarCompetencia';

  var oAjaxRequest = new AjaxRequest( "far4_exportacaohorus.RPC.php", oParametros, retornoValidaDepartamentoUPS );
      oAjaxRequest.setMessage( _M( MENSAGENS_SAU2_CONFERENCIAMEDICAMENTOS + "validando_unidade" )  );
      oAjaxRequest.execute();
}

function retornoValidaDepartamentoUPS( oRetorno, lErro ) {

  if( lErro ) {
    alert( oRetorno.sMensagem.urlDecode() );
  } else {
    $('imprimir').removeAttribute( "disabled" );
  }
}

function imprimirRelatorio() {

  if( empty( $F('periodoInicial') ) ) {

    alert( _M( MENSAGENS_SAU2_CONFERENCIAMEDICAMENTOS + 'informe_periodo_inicial' ) );
    return false;
  }

  if( empty( $F('periodoFinal') ) ) {

    alert( _M( MENSAGENS_SAU2_CONFERENCIAMEDICAMENTOS + 'informe_periodo_final' ) );
    return false;
  }

  if( !empty( $F('periodoInicial') ) && !empty( $F('periodoFinal') ) 
      && js_comparadata($F('periodoInicial'), $F('periodoFinal'), '>') ) {

    alert( _M( MENSAGENS_SAU2_CONFERENCIAMEDICAMENTOS + 'periodo_inicial_maior_final' ) );
    return false;
  }

  var aMaterial = [];
  oLancadorMedicamentos.getRegistros().each(function( oMedicamento ) {
    aMaterial.push( oMedicamento.sCodigo );
  });

  var sUrl         = "sau2_conferenciaadministracaomedicamento002.php";
  var sParametros  = "?dtInicial=" + $F('periodoInicial') + "&dtFinal=" + $F('periodoFinal');
      sParametros += "&aMaterial=" + aMaterial;

  var oJanela = window.open(
                             sUrl + sParametros,
                             '',
                             'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
                           );
  oJanela.moveTo(0,0);
}

validaDepartamentoUPS();
</script>