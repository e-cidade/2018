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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));

db_postmemory($HTTP_POST_VARS);

$db_opcao   = 1;
$clrhferiasperiodo = new cl_rhferiasperiodo;
$clrhferiasperiodo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh110_datainicial");
$clrotulo->label("rh110_datafinal");
$clrotulo->label("rh109_regist");

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js, strings.js, prototype.js, AjaxRequest.js, DBLookUp.widget.js, datagrid.widget.js, estilos.css");
    ?>
  </head>
  <body style="background-color: #ccc; margin-top: 30px">
    <fieldset class="container" style="max-width: 820px;">
      <legend>Autorização de Férias</legend>
      <form id="form1" name="form1" action="" method="POST" onsubmit="return js_validacampos()" class="container" style="margin-top: 0">  
        <fieldset>
          <legend>Filtrar</legend>
          <table class="container-form">
            <tr>
              <td nowrap title="<?php echo $Trh110_datainicial; ?>">
                <label id="lbl_rh110_datainicial" for="rh110_datainicial"><?php echo $Lrh110_datainicial; ?></label>
              </td>
              <td>
                <?php
                  db_inputdata('rh110_datainicial', '', '', '', true, 'text', $db_opcao);
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Trh110_datafinal; ?>">
                <label id="lbl_rh110_datafinal" for="rh110_datafinal"><?php echo $Lrh110_datafinal; ?></label>
              </td>
              <td>
                <?php
                  db_inputdata('rh110_datafinal', '', '', '', true, 'text', $db_opcao);
                ?>
              </td>
            </tr>
            
            <tr>
              <td nowrap title="<?php echo $Trh109_regist; ?>">
                <label for="rh109_regist"><a href="" id="lbl_rh109_regist"><?php echo $Lrh109_regist; ?></a></label>
              </td>
              <td>
                <?php
                  db_input('rh109_regist', 10, $Irh109_regist, true, "text", $db_opcao, 'data="rh01_regist"');
                  db_input('z01_nome', 40, '', true, "text", 3);
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </form>
      <input type="button" id="pesquisar" name="pesquisar" value="Pesquisar" onclick="js_carregarEscalasFerias()" />
      <div id="gridEscalasFerias" style="max-width: 800px; margin: 15px auto; display: block;"></div>
      <input type="button" id="autorizar" name="autorizar" value="Autorizar" onclick="js_processarEscalas()" />
    </fieldset>

    <script type="text/javascript">

      var MENSAGEM = 'recursoshumanos/rh/rec4_autorizarferias001.';

      (function(oWindow){

        oWindow.oGridEscalasFerias              = new DBGrid("escalasFerias");
        oWindow.oGridEscalasFerias.nameInstance = "window.oGridEscalasFerias";

        oWindow.oGridEscalasFerias.setCheckbox(0);
        oWindow.oGridEscalasFerias.setHeader([   "Codigo", "Matrícula", "Servidor", "Data Início", "Data Término", "Dias de Gozo", "Dias Abono"]);
        oWindow.oGridEscalasFerias.setCellWidth([ null,    "60px",      "350px",    "70px",        "85px",          null,           null       ]);
        oWindow.oGridEscalasFerias.setCellAlign(["center", "center",    "left",     "center",      "center",       "center",       "center"    ]);
        oWindow.oGridEscalasFerias.setHeight("350");
        oWindow.oGridEscalasFerias.aHeaders[1].lDisplayed = false;
        oWindow.oGridEscalasFerias.show( $('gridEscalasFerias') );
        
        /**
         * Criação da Ancora para a matrícula.
         * @type  {DBLookUp}
         */
        var oMatricula = new DBLookUp($('lbl_rh109_regist'), $('rh109_regist'), $('z01_nome'), {
            'sArquivo'               : 'func_rhpessoal.php',
            'sObjetoLookUp'          : 'db_iframe_pessoal',
            'sLabel'                 : 'Pesquisar Matrícula',
            'aParametrosAdicionais'  : ['sAtivos=true']
        });

      })(window);

      function js_carregarEscalasFerias () {

        console.clear();

        var sDataInicio, sDataFinal, iMatricula = null;

        if(document.form1.rh110_datainicial.value != '') {
          sDataInicio = document.form1.rh110_datainicial.value;
        }

        if(document.form1.rh110_datafinal.value != '') {
          sDataFinal = document.form1.rh110_datafinal.value;
        }

        if(document.form1.rh109_regist.value.trim() != '') {
          iMatricula = document.form1.rh109_regist.value;
        }

        if (!sDataInicio && !sDataFinal && !iMatricula) {
          alert(_M(MENSAGEM +"informe_pelo_menos_um_filtro"));
          return false;
        }


        iMatricula = document.form1.rh109_regist.value;

        var oParametros = {
          'exec'        : 'getEscalasFerias',
          'iMatricula'  : iMatricula,
          'sDataInicio' : sDataInicio,
          'sDataFinal'  : sDataFinal
        };

        var oAjaxRequest = new AjaxRequest(
          'rec4_autorizarferias.RPC.php',
          oParametros,
          function (oAjax, lErro) {

            if(lErro) {
              alert(oAjax.message.urlDecode());
            } else {
              if(oAjax.aEscalasFerias.length == 0) {
                alert(_M(MENSAGEM + "nenhum_registro_encontrado"));
              }
              
              js_carregarGridEscalaFerias(oAjax.aEscalasFerias);
            }
          }
        );

        oAjaxRequest.setMessage('Buscando Escalas de Férias...');
        oAjaxRequest.execute();
      }

      function js_carregarGridEscalaFerias(aEscalasFerias) {

        window.oGridEscalasFerias.clearAll(true);
        
        for (var i = 0; i < aEscalasFerias.length; i++) {
          
          var oEscala      = aEscalasFerias[i];
          var aDadosEscala = [
            oEscala.iCodigo,
            oEscala.iMatricula,
            oEscala.sNome,
            oEscala.sDataInicio,
            oEscala.sDataFinal,
            oEscala.nDiasGozo,
            oEscala.nDiasAbono
          ];
          window.oGridEscalasFerias.addRow(aDadosEscala);
        };
        window.oGridEscalasFerias.renderRows();

        document.getElementById('autorizar').disabled = false;
      }

      function js_processarEscalas () {

        document.getElementById('autorizar').disabled = true;

        var oParametros = {
          'exec'     : 'processarEscalasFerias',
          'aEscalas' : window.oGridEscalasFerias.getSelection('array')
        };

        var oAjaxRequest = new AjaxRequest(
          'rec4_autorizarferias.RPC.php',
          oParametros,
          function (oAjax, lErro) {

            alert(oAjax.sMessage.urlDecode());

            if (lErro) {
              document.getElementById('autorizar').disabled = false;
            } else {
              js_carregarEscalasFerias();
            }
          }
        );

        oAjaxRequest.setMessage('Processando Escalas de Férias...');
        oAjaxRequest.execute();
      }

    </script>
    <?php db_menu() ?>
  </body>
</html>