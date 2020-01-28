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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <form class="container">
      <fieldset>
        <legend>Configuração do cálculo da carga horária</legend>
        <table class="form-container">
          <tr>
            <td>
              Ano:
            </td>
            <td>
              <input type="text" id="inputAno" class="field-size2" maxlength="4" readonly style="background-color: rgb(222, 184, 135);" />
            </td>
          </tr>
          <tr>
            <td>
              Cálculo da Carga Horária
            </td>
            <td>
              <select id="cboCalculaDuracaoPeriodo">
                <option value="false">Soma aulas dadas / Dias letivos</option>
                <option value="true">( Aulas Dadas x Duração do Período ) / 60</option>
              </select>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="hidden" id="inputCodigo" />
      <input type="button" id="btn_salvar" name="salvar" value="Salvar"/>
      <input type="button" id="btn_pequisar" name="pesquisar" value="Pesquisar" onclick="buscarCalculo();" />
    </form>
  </body>
</html>
<script>

const MENSAGEM_CALCULOCARGAHORARIA001 = 'educacao.escola.edu4_calculocargahoraria001.';

var sCalculoCargaHorariaRPC = "edu4_calculocargahoraria.RPC.php";

(function () {

  $('btn_salvar').disabled = 'disabled';
  buscarCalculo();
}());

$('btn_salvar').onclick = function() {

  if ( empty($F('inputCodigo')) ) {

    alert(  _M( MENSAGEM_CALCULOCARGAHORARIA001 + 'codigo_nao_informado')  );
    return;
  }

  var oParametros                    = {};
  oParametros.sExecucao              = "alterarCalculoCargaHoraria";
  oParametros.iCalculoCargaHoraria   = $F('inputCodigo');
  oParametros.iAnoCargaHoraria       = $F('inputAno');
  oParametros.lCalculaDuracaoPeriodo = $F('cboCalculaDuracaoPeriodo') == 'true';

  var oAjaxRequest  = new AjaxRequest( sCalculoCargaHorariaRPC, oParametros, callBackRetornoSalvar);
      oAjaxRequest.setMessage( _M( MENSAGEM_CALCULOCARGAHORARIA001 + 'alterando_calculocargahoraria') );
      oAjaxRequest.execute();
}

function callBackRetornoSalvar(oRetorno, lErro) {

  alert ( oRetorno.sMensagem.urlDecode() );

  if (lErro) {
    return false;
  }
}

function buscarCalculo() {

  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_calculocargahoraria',
                      'func_calculocargahoraria.php?funcao_js=parent.retornoBuscarCalculo|ed127_codigo|ed127_ano|2|ed127_escola',
                      'Pesquisa',
                      true
                     );
}

function retornoBuscarCalculo( iCodigo, iAno, sFormaCalculo, iEscola ) {

  $('inputCodigo').value              = iCodigo;
  $('inputAno').value                 = iAno;


  $('cboCalculaDuracaoPeriodo').value = sFormaCalculo == '( Aulas Dadas x Duração do Período ) / 60';
  db_iframe_calculocargahoraria.hide();
  validarAlteracao();
}

function validarAlteracao() {

  var oParametros           = {};
      oParametros.sExecucao = "validarAlteracaoParametro";
      oParametros.iAno      = $('inputAno').value;


  var oAjaxRequest  = new AjaxRequest( sCalculoCargaHorariaRPC, oParametros, callBackRetornoValidarAlteracao);
      oAjaxRequest.setMessage( _M( MENSAGEM_CALCULOCARGAHORARIA001 + 'validando_calculocargahoraria') );
      oAjaxRequest.execute();
}

function callBackRetornoValidarAlteracao(oRetorno, lErro) {

  $('btn_salvar').disabled = 'disabled';
  
  if (lErro) {
    return false;
  }

  if ( oRetorno.lBloquearAlteracao ) {

    alert( oRetorno.sMensagem.urlDecode() );
    return;
  }

  $('btn_salvar').disabled = '';

}
</script>