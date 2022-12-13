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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

db_postmemory( $_POST );

$dia1 = null;
$mes1 = null;
$ano1 = null;
$dia2 = null;
$mes2 = null;
$ano2 = null;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class='body-default'>
  <div class="container">
    <form class='form-container' method="post" name='form1'>
      <fieldset>
        <legend>Atendimentos</legend>
        <table>
          <tr>
            <td class="bold field-size1">Período:</td>
            <td>
              <?db_inputdata('dt_inicial', $dia1, $mes1, $ano1, true, 'text', 1, "")?>
              <span class="bold"> até </span>
              <?db_inputdata('dt_final', $dia2, $mes2, $ano2, true, 'text', 1, "")?>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <div id="oLancadorUPS"></div>
            </td>
          </tr>
          <tr>
            <td colspan="2" >
              <div id="oLancadorProfissional"></div>
            </td>
          </tr>
          <tr>
            <td class="field-size1">
              <input type="checkbox" id="lListarProcedimentos" name="lListarProcedimentos" value="true" />
            </td>
            <td>
              <label class="bold" for="lListarProcedimentos">Listar procedimentos</label>
            </td>
          </tr>
          <tr>
            <td class="field-size1">
              <input type="checkbox" id="lVerProfissional" name="lVerProfissional" value="sem" />
            </td>
            <td>
              <label class="bold" for="lVerProfissional">Não listar profissionais selecionados</label>
            </td>
          </tr>
          <tr>
            <td class="field-size1">
              <input type="checkbox" id="lQuebraUPS" name="lQuebraUPS" value="true" />
            </td>
            <td>
              <label class="bold" for="lQuebraUPS">Quebra de página por UPS</label>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" id="btn_imprimir" name="imprimir" value="Imprimir">
    </form>
  </div>
</body>
<?php
  db_menu();
?>
</html>
<script>

  const MENSAGEM_ATENDIMENTOMEDICO001 = "saude.ambulatorial.sau2_atendimentomedico001.";

  var oLancadorUPS            = new DBLancador( 'oLancadorUPS' );
  oLancadorUPS.sTextoFieldset = 'UPS';
  oLancadorUPS.sTituloJanela  = 'Pesquisa UPS';
  oLancadorUPS.setGridHeight( 150 );
  oLancadorUPS.setLabelAncora( 'UPS:' );
  oLancadorUPS.setNomeInstancia( 'oLancadorUPS' );
  oLancadorUPS.setParametrosPesquisa( 'func_unidades.php', [ 'sd02_i_codigo', 'descrdepto' ] );
  oLancadorUPS.setTamanhoInputDescricao( 45 );
  oLancadorUPS.setIdBotaoAdicionar("adicionar_ups");
  oLancadorUPS.show( $('oLancadorUPS') );

  var oLancadorProfissional            = new DBLancador( 'oLancadorProfissional' );
  oLancadorProfissional.sTextoFieldset = 'Profissionais';
  oLancadorProfissional.sTituloJanela  = 'Pesquisa Profissional';
  oLancadorProfissional.setGridHeight( 150 );
  oLancadorProfissional.setLabelAncora( 'Profissional:' );
  oLancadorProfissional.setNomeInstancia( 'oLancadorProfissional' );
  oLancadorProfissional.setParametrosPesquisa( 'func_medicosclassegenericas.php', [ 'sd03_i_codigo', 'z01_nome' ] );
  oLancadorProfissional.setIdBotaoAdicionar("adicionar_profissional");
  oLancadorProfissional.show( $('oLancadorProfissional') );

  oLancadorUPS.setCallbackBotao(function() {
    callbackUPS();
  });

  oLancadorUPS.setCallbackRemover(function() {
    callbackUPS();
  });

  var iTotalRegistrosUPS = oLancadorUPS.getRegistros().length;


  // Busca o primeiro fieldset do lançador e adiciona a class separator
  $$('#oLancadorUPS fieldset').first().addClassName('separator');
  $$('#oLancadorProfissional fieldset').first().addClassName('separator');

  /**
  * Define os parametros para impressão do relátorio de atendimentos.
  */
  $('btn_imprimir').onclick = function() {

    if ( !validaData() ) {
      return;
    }

    var aProfissionais       = [];
    var lVerProfissional     = ($F('lVerProfissional') == null)     ? 'com'   : $F('lVerProfissional');
    var lQuebraUPS           = ($F('lQuebraUPS') == null)           ? 'false' : $F('lQuebraUPS');
    var lListarProcedimentos = ($F('lListarProcedimentos') == null) ? 'false' : $F('lListarProcedimentos');

    oLancadorProfissional.getRegistros().each(function( oProfissional ) {
      aProfissionais.push( oProfissional.sCodigo );
    });

    var sUrl  = "sau2_atendimentomedico002.php";
        sUrl += "&listaups="             + getCodigosUPS();
        sUrl += "?listaprof="            + aProfissionais;
        sUrl += "&verprof="              + lVerProfissional;
        sUrl += "&data1="                + $F('dt_inicial');
        sUrl += "&data2="                + $F('dt_final');
        sUrl += "&lQuebraUPS="           + lQuebraUPS;
        sUrl += "&lListaProcedimentos=" + lListarProcedimentos;

    var oJanela = window.open( sUrl,
                               '',
                               'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0' );
    oJanela.moveTo(0,0);
  };

  /**
  * Retorna um array de códigos UPS de acordo com os lançados na grid.
  * @return array
  */
  function getCodigosUPS() {

    var aUPS = [];

    oLancadorUPS.getRegistros().each(function( oUPS ) {
      aUPS.push( oUPS.sCodigo );
    });

    return aUPS;
  }


  /**
  * Define callback passado após adição e remoção da grid de UPSs, limpando a grid de profissionais e adicionando
  * filtros na função de pesquisa dos profissionais.
  */
  function callbackUPS() {

    if ( iTotalRegistrosUPS == oLancadorUPS.getRegistros().length ) {
      return;
    }

    iTotalRegistrosUPS = oLancadorUPS.getRegistros().length;

    oLancadorProfissional.getRegistros().each(function( oProfissional ) {
      oLancadorProfissional.removerRegistro( oProfissional.sCodigo );
    });

    var sParametrosUPS = "chave_sd06_i_unidade=" + getCodigosUPS();

    oLancadorProfissional.setParametrosPesquisa( 'func_medicosclassegenericas.php', [ 'sd03_i_codigo', 'z01_nome' ], sParametrosUPS );
    oLancadorProfissional.renderizarRegistros();
    $('txtCodigooLancadorProfissional').value    = '';
    $('txtDescricaooLancadorProfissional').value = '';
  }

  /**
  * Valida a obrigatoriedade da data e verifica se data inicial é menor que a final.
  * @return boolean
  */
  function validaData() {

    if ( $F('dt_inicial') == '' ) {

      alert( _M( MENSAGEM_ATENDIMENTOMEDICO001 + "data_inicial_nao_informada" ) );
      return false;
    }

    if ( $F('dt_final') == '' ) {

      alert( _M( MENSAGEM_ATENDIMENTOMEDICO001 + "data_final_nao_informada" ) );
      return false;
    }

    if ( js_comparadata($F('dt_inicial'), $F('dt_final'), '>') ) {

      alert( _M( MENSAGEM_ATENDIMENTOMEDICO001 + "data_inicial_maior_final" ) );
      return false;
    }

    return true;
  }

$('dt_inicial').addClassName('field-size2');
$('dt_final').addClassName('field-size2');
</script>