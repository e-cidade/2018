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
db_postmemory($_SERVER);
db_postmemory($_POST);

if(isset($ativar)){

  db_putsession("DB_traceLog",true);

} else if (isset($ativar_acount)) {

  db_putsession("DB_traceLogAcount",true);

} else if (isset($desativar_acount)) {

  db_destroysession("DB_traceLogAcount");

} else if (isset($desativar)) {

  db_destroysession("DB_traceLog");

} else if(isset($testaTrace)) {

  for($i=0; $i<6; $i++){
    db_query("select 'teste ".($i + 1)."' ", null, "Teste Trace Log ".($i + 1));
  }
  db_query("select 'TRACE LOG OK' ", null, "Trace Log OK");
}



$lMostrarMenu = true;
if (!empty($lParametroExibeMenu) && $lParametroExibeMenu === "false") {
  $lMostrarMenu = false;
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
    .form-container {
      width: 300px !important;
    }
    .form-container > tbody > tr > td:nth-child(even) {
      text-align: right !important;
    }
    </style>
  </head>
  <body style="background-color: #cccccc; " leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div class="container">
  <fieldset>
    <legend>Opções do Trace Log </legend>

    <fieldset class="separator">
      <legend>DML</legend>
      <table>
        <tr>
          <td>
            <input type="checkbox" id="mostra_select" class="comandos" checked />
          </td>
          <td>
            <label for="mostra_select" class="bold">Select</label>
          </td>

          <td>
            <input type="checkbox" id="mostra_insert" class="comandos" checked />
          </td>
          <td>
            <label for="mostra_insert" class="bold">Insert</label>
          </td>

          <td>
            <input type="checkbox" id="mostra_update" class="comandos" checked />
          </td>
          <td>
            <label for="mostra_update" class="bold">Update</label>
          </td>

          <td>
            <input type="checkbox" id="mostra_delete" class="comandos" checked />
          </td>
          <td>
            <label for="mostra_delete" class="bold">Delete</label>
          </td>
        </tr>
      </table>
    </fieldset>

    <fieldset class="separator">
      <legend>DDL</legend>
      <table>
        <tr>
          <td>
            <input type="checkbox" id="mostra_create" class="comandos" checked />
          </td>
          <td>
            <label for="mostra_create" class="bold">Create</label>
          </td>

          <td>
            <input type="checkbox" id="mostra_alter" class="comandos" checked />
          </td>
          <td>
            <label for="mostra_alter" class="bold">Alter</label>
          </td>

          <td>
            <input type="checkbox" id="mostra_drop" class="comandos" checked />
          </td>
          <td>
            <label for="mostra_drop" class="bold">Drop</label>
          </td>
        </tr>
      </table>
    </fieldset>

    <fieldset class="separator">
      <legend>Consultas SQL:</legend>

      <table class="form-container">
        <tr>
          <td><label for="mostra_account">Exibe Consultas do Account?</label></td>
          <td><input type="CHECKBOX" id="mostra_account" /></td>
        </tr>

        <tr>
          <td><label for="mostra_consultas_padrao">Exibe Consultas Padrão?</label></td>
          <td><input type="CHECKBOX" id="mostra_consultas_padrao" /></td>
        </tr>
      </table>

    </fieldset>

    <fieldset class="separator">
      <legend>Dados Adicionais:</legend>

      <table class="form-container">
        <tr>
          <td><label for="mostra_nome_fonte">Exibe fonte/linha da chamada?</label></td>
          <td><input type="CHECKBOX" id="mostra_nome_fonte" /></td>
        </tr>
        <tr>
          <td><label for="mostra_funcao">Exibe função de Chamada?</label></td>
          <td><input type="CHECKBOX" id="mostra_funcao" /></label></td>
        </tr>
        <tr>
          <td><label for="mostra_horario">Exibe Data/Horário do Evento?</td>
          <td><input type="CHECKBOX" id="mostra_horario" /></label></td>
        </tr>
        <tr>
          <td><label for="mostra_backtrace_completo">Exibe BackTrace completo?</label></td>
          <td><input type="CHECKBOX" id="mostra_backtrace_completo" /></label></td>
        </tr>
      </table>
    </fieldset>

    <fieldset class="separator">
      <legend>Arquivo TraceLog:</legend>

      <table class="form-container">
        <tr>
          <td style='text-align: center !important;'><a id="link_tracelog" href="#">Download TRACELOG</a></td>
        </tr>
      </table>

    </fieldset>

  </fieldset>

  <input type="BUTTON" id="ativar"    value="Ativar"   />
  <input type="BUTTON" id="desativar" value="Desativar"/>
  <input type="BUTTON" id="testar"    value="Testar"   />
  <input type="BUTTON" id="btnAcompanharTraceLog" value="Acompanhar TraceLog"   />
</div>

  <?
  if ($lMostrarMenu) {
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  }
  ?>

  </body>
<script>
  var sNomeArquivo = "";
try {

  (
    function() {


      var oBotaoAtivar     = document.getElementById('ativar');
      var oBotaoDesativar  = document.getElementById('desativar');
      var oBotaoTestar     = document.getElementById('testar');
      var oBotaoAcompanhar = document.getElementById('btnAcompanharTraceLog');

      oBotaoAtivar.onclick = function() {
        salvarDados(true);
      };

      oBotaoDesativar.onclick = function() {
        salvarDados(false);
      };

      oBotaoAcompanhar.onclick = function () {

        oBotaoTestar.click();
        verificarTracelog();
        acompanharTraceLog();
      };

      oBotaoTestar.onclick = function() {
        new Ajax.Request(
          "con1_ativatrace.RPC.php",
          {
            method      : 'post',
            asynchronous : false,
            parameters   : 'json='+Object.toJSON({"sExec":"testar"}),
            onComplete   : function(oAjax) {

              var oResposta = JSON.parse(oAjax.responseText);

              if ( oResposta.iStatus == 2 ) {
                throw "Erro ao buscar estado do tracelog";
              }
              return;
            }
          }
        );
      };

      $('mostra_select').onclick = function() {

        $('mostra_account').disabled          = false;
        $('mostra_consultas_padrao').disabled = false;
        if( !$('mostra_select').checked ) {

          $('mostra_account').disabled = true;
          $('mostra_account').checked  = false;

          $('mostra_consultas_padrao').disabled = true;
          $('mostra_consultas_padrao').checked  = false;
        }
      };

      /**
       * Valida de tracelog está ativo
       */
      verificarTracelog();

      /**
       * verificarTracelog
       *
       * @access public
       * @return void
       */
      function verificarTracelog() {

        var lAtivo = false;
        new Ajax.Request(
          "con1_ativatrace.RPC.php",
          {method      : 'post',
          asynchronous : false,
          parameters   : 'json='+Object.toJSON({"sExec":"retornarStaus"}),
          onComplete   : function(oAjax) {

            var oResposta = JSON.parse(oAjax.responseText);

            if ( oResposta.iStatus == 2 ) {
              throw "Erro ao buscar estado do tracelog";
            }

            document.getElementById('mostra_account').checked            = oResposta.oTracelog.lShowAccount;
            document.getElementById('mostra_consultas_padrao').checked   = oResposta.oTracelog.lShowDefault;
            document.getElementById('mostra_nome_fonte').checked         = oResposta.oTracelog.lShowSourceInfo;
            document.getElementById('mostra_funcao').checked             = oResposta.oTracelog.lShowFunctionName;
            document.getElementById('mostra_horario').checked            = oResposta.oTracelog.lShowTime;
            document.getElementById('mostra_backtrace_completo').checked = oResposta.oTracelog.lShowBackTrace;

            document.getElementById('ativar').disabled                   = oResposta.oTracelog.lActive;
            document.getElementById('desativar').disabled                = !oResposta.oTracelog.lActive;
            document.getElementById('testar').disabled                   = !oResposta.oTracelog.lActive;
            document.getElementById('btnAcompanharTraceLog').disabled    = !oResposta.oTracelog.lActive;

            if( oResposta.oTracelog.lActive === true ) {

              $$('.comandos').each(function( oElemento ) {

                oElemento.checked  = false;
                oElemento.disabled = true;
              });

              oResposta.oTracelog.aComandos.each( function( sComando ) {
                $('mostra_' + sComando).checked = true;
              });
            }

            document.getElementById('mostra_account').disabled            = oResposta.oTracelog.lActive;
            document.getElementById('mostra_consultas_padrao').disabled   = oResposta.oTracelog.lActive;
            document.getElementById('mostra_nome_fonte').disabled         = oResposta.oTracelog.lActive;
            document.getElementById('mostra_funcao').disabled             = oResposta.oTracelog.lActive;
            document.getElementById('mostra_horario').disabled            = oResposta.oTracelog.lActive;
            document.getElementById('mostra_backtrace_completo').disabled = oResposta.oTracelog.lActive;
            document.getElementById('link_tracelog').href                 = '#';

            if ( oResposta.oTracelog.sFilePath != '' ) {

              sNomeArquivo = oResposta.oTracelog.sFilePath;
              document.getElementById('link_tracelog').onclick = function() {
                js_arquivo_abrir( oResposta.oTracelog.sFilePath );
              };
            }

          }}
        );

        return lAtivo;
      }

      function salvarDados( lAtivo ) {

        var oDadosTraceLog               = new Object();
        oDadosTraceLog.sExec             = "salvar";
        oDadosTraceLog.lActive           = lAtivo;
        oDadosTraceLog.lShowAccount      = document.getElementById('mostra_account').checked;
        oDadosTraceLog.lShowDefault      = document.getElementById('mostra_consultas_padrao').checked;
        oDadosTraceLog.lShowSourceInfo   = document.getElementById('mostra_nome_fonte').checked;
        oDadosTraceLog.lShowFunctionName = document.getElementById('mostra_funcao').checked;
        oDadosTraceLog.lShowTime         = document.getElementById('mostra_horario').checked;
        oDadosTraceLog.lShowBackTrace    = document.getElementById('mostra_backtrace_completo').checked;
        oDadosTraceLog.aComandos         = new Array();

        $$('.comandos').each(function( oElemento ) {

          if( oElemento.checked ) {

            var aComandos = oElemento.id.split( '_' );
            oDadosTraceLog.aComandos.push( aComandos[1] );
          }
        });

        if( lAtivo && oDadosTraceLog.aComandos.length == 0 ) {

          alert( 'É necessário selecionar ao menos um comando a ser exibido.' );
          return;
        }

        new Ajax.Request(
          "con1_ativatrace.RPC.php",
          {
            method      : 'post',
            asynchronous : false,
            parameters   : 'json='+Object.toJSON(oDadosTraceLog),
            onComplete   : function(oAjax) {

              var oResposta = JSON.parse(oAjax.responseText);

              if ( oResposta.iStatus == 2 ) {
                throw "Erro ao buscar estado do tracelog";
              }

              window.location.reload();
              return;
            }
          }
        );
      }

      function acompanharTraceLog() {

        var oJanela = window.open("con1_acompanhatracelog001.php?sNomeArquivo="+sNomeArquivo,
                                  "Acompanhamento do TraceLog",
                                  'width=1024,' +
                                  'height=768,' +
                                  'fullscreen=0,' +
                                  'toolbar=0,' +
                                  'location=0,' +
                                  'directories=0,' +
                                  'status=0,' +
                                  'menubar=0,' +
                                  'scrollbars=1,' +
                                  'resizable=0');
        oJanela.moveTo(0,0);
      }

    }
  )();
} catch ( mErro ) {
  console.log(mErro);
}

  </script>
</html>