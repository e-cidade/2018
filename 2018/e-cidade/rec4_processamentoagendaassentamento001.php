<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
  require_once(modification("classes/db_agendaassentamento_classe.php"));
  require_once(modification("libs/db_utils.php"));
  require_once(modification("libs/db_app.utils.php"));
  require_once(modification("dbforms/db_classesgenericas.php"));
  require_once(modification("dbforms/db_funcoes.php"));

  db_postmemory($HTTP_POST_VARS);

  $oDaoAgendaassentamento = new cl_agendaassentamento;
  $oDaoAgendaassentamento->rotulo->label();
  $oRotulo = new rotulocampo;

?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js, strings.js, prototype.js, DBLookUp.widget.js, estilos.css");
    ?>
    <script src="scripts/datagrid.widget.js"></script>
    <script src="scripts/AjaxRequest.js"></script>
    <style type="text/css">
      form table.form-container{
        margin-bottom: 10px;
      }
    </style>
  </head>
  <body style="background-color: #ccc; margin-top: 30px">
    
    <form id="form1" name="form1" action="" method="POST" class="container">  
      <fieldset>
        <legend>Autoriza��o de Assentamentos</legend>
        <table class="form-container">
          <tr>
            <td nowrap title="<?php echo $Th82_tipoassentamento; ?>">
              <a href="" id="lbl_h82_tipoassentamento"><?php echo $Lh82_tipoassentamento; ?></a>
            </td>
            <td>
              <?php 
                $h82_tipoassentamento = (isset($iTipoAssentamento) && !empty($iTipoAssentamento)) ? $iTipoAssentamento : '';
                $h12_assent           = (isset($sCodigoTipoAssentamento) && !empty($sCodigoTipoAssentamento)) ? $sCodigoTipoAssentamento : '';
                $h12_descr            = (isset($sDescricaoTipoAssentamento) && !empty($sDescricaoTipoAssentamento)) ? $sDescricaoTipoAssentamento : '';
              ?>
              <?php db_input('h82_tipoassentamento', 10, $Ih82_tipoassentamento, true, "hidden", 1); ?>
              <?php db_input('h12_assent', 10, '', true, "text", 1); ?>
              <?php db_input('h12_descr', 60, '', true, "text", 3); ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?php echo $Th82_selecao; ?>">
              <label id="lbl_h82_selecao"><?php echo $Lh82_selecao; ?></label>
            </td>
            <td>
              <?php 
                $aSelecao = array();

                if(isset($iTipoAssentamento) && !empty($iTipoAssentamento)) {
                  $oTipoAssentamento   = TipoAssentamentoRepository::getInstanciaPorCodigo($h82_tipoassentamento);
                  $oAgendaAssentamento = AgendaAssentamentoRepository::getInstanciaPorTipoAssentamento($oTipoAssentamento);
                  $oAgendaAssentamento = AgendaAssentamentoRepository::getListaSelecaoParaTipo($oAgendaAssentamento);

                  foreach ($oAgendaAssentamento->getListaSelecao() as $oSelecao) {
                    $aSelecao[$oSelecao->getCodigo()] = $oSelecao->getDescricao();
                  }
                }

                db_select('h82_selecao', $aSelecao, true, 1, "onChange='carregarServidores()'");
              ?>
            </td>
          </tr>
        </table>
        <div id="grid_servidores_agenda_assentamentos"></div>
      </fieldset>
      <input type="submit" id="processar" name="processar" onclick="return processarAssentamentos()" value="Processar" />
    </form>

  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  </body>
  <script>

  <?php if(!isset($iTipoAssentamento) || empty($iTipoAssentamento)) : ?>

      var oJanela = js_OpenJanelaIframe(
        "",
        "db_func_iframe_tipoasse",
        "func_tipoasse.php?sAcao=agendaAssentamento&funcao_js=parent.redirecionar|h12_codigo|h12_assent|h12_descr",
        "Agenda por Tipos de Assentamento",
        true
      );
      oJanela.setModal(true);

  <?php endif; ?>

  function redirecionar( iTipoAssentamento, sCodigoTipoAssentamento, sDescricaoTipoAssentamento ) {
    window.location.href = 'rec4_processamentoagendaassentamento001.php?iTipoAssentamento='+iTipoAssentamento+'&sCodigoTipoAssentamento='+sCodigoTipoAssentamento+'&sDescricaoTipoAssentamento='+sDescricaoTipoAssentamento;
  }

  <?php if(isset($iTipoAssentamento) && !empty($iTipoAssentamento)) : ?>

    (function(oWindow){

      /**
       * Cria��o da Ancora para tipo de assentamento.
       * @type  {DBLookUp}
       */
      var oTipoAssentamento = new DBLookUp($('lbl_h82_tipoassentamento'), $('h12_assent'), $('h12_descr'), {
          'sArquivo'              : 'func_tipoasse.php',
          'sObjetoLookUp'         : 'db_iframe_tipoasse',
          'sLabel'                : 'Pesquisar Tipo Assentamento',
          'aCamposAdicionais'     : ['h12_codigo'],
          'aParametrosAdicionais' : ['sAcao=agendaAssentamento']
      });

      oTipoAssentamento.callBackChange = function(){

        $('h82_tipoassentamento').value = arguments[3];
        $('h12_descr').value            = arguments[1];

        carregarSelecao();
      };

      oTipoAssentamento.callBackClick = function(){

        Jandb_iframe_tipoasse.remove();

        $('h82_tipoassentamento').value = arguments[2];
        $('h12_assent').value           = arguments[0];
        $('h12_descr').value            = arguments[1];

        carregarSelecao();
      };

      oWindow.oGridServidoresAgendaAssentamentos = new DBGrid("servidoresAgendaAssentamentos");
      oWindow.oGridServidoresAgendaAssentamentos.nameInstance = "window.oGridServidoresAgendaAssentamentos";

      oWindow.oGridServidoresAgendaAssentamentos.setCheckbox(0);
      oWindow.oGridServidoresAgendaAssentamentos.setHeader(["Matr�cula", "Nome"]);
      oWindow.oGridServidoresAgendaAssentamentos.setCellWidth(["80px"]);
      oWindow.oGridServidoresAgendaAssentamentos.setCellAlign(["center",  "left"]);
      oWindow.oGridServidoresAgendaAssentamentos.setHeight("450");      
      oWindow.oGridServidoresAgendaAssentamentos.show( $('grid_servidores_agenda_assentamentos') );

      carregarServidores();
    })(window);

    /**
     * Carrega os servidores
     */
    function carregarServidores() {

      var oParametros  = { 
        'exec'              : 'buscarServidoresAssentamento',
        'iTipoAssentamento' : $F('h82_tipoassentamento'),
        'iCodigoSelecao'    : $F('h82_selecao')
      };

      var oAjaxRequest = new AjaxRequest(
        'rec4_agendaassentamento.RPC.php', 
        oParametros,
        function (oAjax, lErro) {
          carregarGridServidores(oAjax.aServidores);
        }
      );
      oAjaxRequest.setMessage('Buscando Servidores...');
      oAjaxRequest.execute();
    }

    function carregarGridServidores(aServidores) {  

      window.oGridServidoresAgendaAssentamentos.clearAll(true);

      var iCounterLinha = 0;
      var oTipoAssentamento, sCabecalho, oServidor;
      
      if(aServidores.length == 0) {
        alert("N�o h� servidores com direitos para esta sele��o e tipo de assentamento.");
      }

      for (var iIndServidor = 0; iIndServidor < aServidores.length; iIndServidor++) {

          oServidor = aServidores[iIndServidor];
          window.oGridServidoresAgendaAssentamentos.addRow([oServidor.iMatricula,
                                                            oServidor.sNome]);

      };

      window.oGridServidoresAgendaAssentamentos.renderRows();

    }

  function processarAssentamentos() { 

    var aListaServidores = window.oGridServidoresAgendaAssentamentos.getSelection();
    var aServidores      = Array();

    aListaServidores.each(function(oAssentamento, iIndice){

      aServidores.push(oAssentamento[0]);
    });    

    var oParametros = {
      'exec'              : 'processarAssentamentos',
      'iTipoAssentamento' : $F('h82_tipoassentamento'),
      'iSelecao'          : $F('h82_selecao'),
      'aServidores'       : aServidores
    };

    var oAjaxRequest = new AjaxRequest(
      'rec4_agendaassentamento.RPC.php',
      oParametros,
      function (oAjax, lErro) {

        alert(oAjax.sMessage.urlDecode());

        if(!lErro) {
          carregarServidores();
        }
      }
    );
    oAjaxRequest.setMessage('Autorizando assentamentos...');
    oAjaxRequest.execute();

    return false;
  }

  function carregarSelecao() {

    var oParametros = {
      'exec'              : 'carregarSelecao',
      'iTipoAssentamento' : $F('h82_tipoassentamento')
    };

    var oAjaxRequest = new AjaxRequest(
      'rec4_agendaassentamento.RPC.php',
      oParametros,

      function (oAjax, lErro) {

        if(lErro) {

          alert(oAjax.sMessage.urlDecode());
          window.oGridServidoresAgendaAssentamentos.clearAll(true);

        } else {

          $('h82_selecao').childElements().each(function(oItem, iIndex){
            oItem.remove();
          });

          for (var iIndSelecao = 0; iIndSelecao < oAjax.aSelecao.length; iIndSelecao++) {

            var oItem = oAjax.aSelecao[iIndSelecao];

            var oOption       = document.createElement('option');
            oOption.value     = oItem.iCodigo
            oOption.innerHTML = oItem.sDescricao;

            $('h82_selecao').add(oOption);
          };
          carregarServidores();
        }
      }
    );
    oAjaxRequest.setMessage("Buscando Sele��es...");
    oAjaxRequest.execute();
  }

  <?php endif; ?>

  </script>
</html>