<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_rhdirfparametros_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$oDaoRhdirfparametros = new cl_rhdirfparametros;
$db_opcao    = 1;
$db_botao    = true;
$sPosScripts = "";

if (isset($incluir)) {

  db_inicio_transacao();
  $oDaoRhempenhoelementopcasp->incluir($rh119_sequencial);
  db_fim_transacao();

  $sPosScripts .= 'alert("' . $oDaoRhempenhoelementopcasp->erro_msg . '");' . "\n";

  if ($oDaoRhempenhoelementopcasp->erro_status == '0') {

    $db_botao = true;
    $sPosScripts .= "document.form1.db_opcao.disabled = false;\n";

    if ($oDaoRhempenhoelementopcasp->erro_campo != "") {
      $sPosScripts .= "document.form1.{$oDaoRhempenhoelementopcasp->erro_campo}.classList.add('form-error');\n";
      $sPosScripts .= "document.form1.{$oDaoRhempenhoelementopcasp->erro_campo}.focus();\n";
    }
  } else {
    $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
  }
}

$sPosScripts .=  'js_tabulacaoforms("form1", "rh119_rhelementoempdef", true, 1, "rh119_rhelementoempdef", true);';

$oDaoRhdirfparametros->rotulo->label();

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/numbers.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>

    <script type="text/javascript">

      function js_onlyNumbers(oObj) {
        oObj.value = oObj.value.replace(/[^0-9]/g, '')
      }

      function js_removeAspas(oObj) {
        oObj.value = oObj.value.replace(/"/, '');
      }

    </script>

    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>

  <body class="body-default">
    <div class="container">

      <form name="form1" method="post" action="" >

        <fieldset>
          <legend>Manutenção de Parâmetros da DIRF</legend>

          <table>

            <tr>
              <td nowrap title="<?php echo $Trh132_anobase; ?>" >
                <label class="bold" for="rh132_anobase" id="lbl_rh132_anobase"><?php echo $Lrh132_anobase; ?></label>
              </td>
              <td>
                <?php
                  db_input('rh132_sequencial', 4, 0, true,'hidden', 3);
                  db_input('rh132_anobase',4,'',true,'text',2, ' onBlur="js_onlyNumbers(this)" onKeyUp="js_onlyNumbers(this)"');
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Trh132_valorminimo; ?>" >
                <label class="bold" for="rh132_valorminimo" id="lbl_rh132_valorminimo"><?php echo $Lrh132_valorminimo; ?></label>
              </td>
              <td>
                <?php
                  db_input( 'rh132_valorminimo',
                            10,
                            '',
                            true,
                            'text',
                            2,
                            ' onKeyUp="js_ValidaCampos(this, 4, ' . "'{$LSrh132_valorminimo}'" . ', \'t\', \'f\', event);"' );
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Trh132_codigoarquivo; ?>" >
                <label class="bold" for="rh132_codigoarquivo" id="lbl_rh132_codigoarquivo"><?php echo $Lrh132_codigoarquivo; ?></label>
              </td>
              <td>
                <?php
                  db_input('rh132_codigoarquivo', 10, '', true, 'text', 2, ' onBlur="js_removeAspas(this)" onKeyUp="js_removeAspas(this)"');
                ?>
              </td>
            </tr>

          </table>

        </fieldset>

        <input  name="salvar" id="salvar" type="button" value="Salvar" onclick="js_salvarParametro()" >
        <input  name="excluir" id="excluir" type="button" value="Excluir" onclick="js_excluirParametro()" >
      </form>
    </div>
    <div class="Container">
      <div id="ctnParametrosDIRF" style="width: 500px"></div>
    </div>
    <?php
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
  </body>

  <script type="text/javascript">
    var oGridParametros = new DBGrid('oGridParametros'),
        sUrlRPC         = "pes4_rhdirfparametros.RPC.php",
        sMensagens      = "recursoshumanos.pessoal.pes4_rhdirfparametros.",
        aRows           = new Array();

    oGridParametros.nameInstance = 'oGridParametros';
    oGridParametros.setCellAlign(new Array('right', 'center', 'right', "center", 'center'));
    oGridParametros.setHeader(new Array("seq", 'Ano Base', 'Valor Mínimo', 'Código do Arquivo', 'Opções'));
    oGridParametros.aHeaders[0].lDisplayed = false;
    oGridParametros.setHeight(200);
    oGridParametros.setCellWidth(new Array("0%", "20%", "25%", "45%", "10%"));

    oGridParametros.show($('ctnParametrosDIRF'));

    function js_montaGrid() {

      var oParam = {
            exec : "getParametros"
          }

      js_divCarregando('Aguarde, Atualizando...', 'oCarregando');

      var oAjax = new Ajax.Request( sUrlRPC, {  method : 'post',
                                                parameters : 'json=' + Object.toJSON(oParam),
                                                onComplete : function(oAjax) {

                                                  var oRetorno = JSON.parse( oAjax.responseText.urlDecode() );

                                                  js_removeObj('oCarregando');
                                                  aRows = new Array();

                                                  if (oRetorno.iStatus == 1) {
                                                    oRetorno.oDados.each(function (oParametro, iLinha) {

                                                      aRows.push({
                                                        'rh132_sequencial'    : oParametro.rh132_sequencial,
                                                        'rh132_anobase'       : oParametro.rh132_anobase,
                                                        'rh132_valorminimo'   : oParametro.rh132_valorminimo,
                                                        'rh132_codigoarquivo' : oParametro.rh132_codigoarquivo
                                                      })
                                                    });

                                                    js_renderizaGrid()
                                                  } else {
                                                    alert( oRetorno.sMessage );
                                                  }
                                                }
                                              });
    }

    function js_visualizaParametro(iSequencial, lAlterar) {

      aRows.each(function(oObj) {

        if (oObj.rh132_sequencial == iSequencial) {

          $('rh132_sequencial').value    = oObj.rh132_sequencial;
          $('rh132_anobase').value       = oObj.rh132_anobase;
          $('rh132_valorminimo').value   = oObj.rh132_valorminimo;
          $('rh132_codigoarquivo').value = oObj.rh132_codigoarquivo;
        }

      })

      js_renderizaGrid(iSequencial)
      js_alternaBotoes(lAlterar)
    }

    function js_alternaBotoes(lAlterar) {

      if (!lAlterar) {
        $('salvar').hide()
        $('excluir').show()

        return true;
      }

      $('salvar').show()
      $('excluir').hide()
    }

    function js_limpaCampos() {

      $('rh132_sequencial').value    = '';
      $('rh132_anobase').value       = '';
      $('rh132_valorminimo').value   = '';
      $('rh132_codigoarquivo').value = '';
    }

    function js_salvarParametro() {

      if ($F('rh132_anobase').trim() == '') {

        alert( _M(sMensagens + "campo_obrigatorio", { sCampo : "Ano Base"}) )
        return false
      }

      if ($F('rh132_valorminimo').trim() == '') {

        alert( _M(sMensagens + "campo_obrigatorio", { sCampo : "Valor Mínimo"}) )
        return false
      }

      if ( !isNumeric( $('rh132_valorminimo').value ) ){

        alert( _M(sMensagens + "valor_minimo_invalido") )
        return false
      }

      if ($F('rh132_codigoarquivo').trim() == '') {

        $('rh132_codigoarquivo').value = $F('rh132_codigoarquivo').trim();
        alert( _M(sMensagens + "campo_obrigatorio", { sCampo : "Código do Arquivo"}) )
        return false
      }

      var oParam = {
            exec           : "salvar",
            iSequencial    : $F('rh132_sequencial'),
            iAnoBase       : $F('rh132_anobase'),
            iValorMinimo   : $F('rh132_valorminimo'),
            sCodigoArquivo : $F('rh132_codigoarquivo')
          }

      js_divCarregando('Aguarde, Lançando registro...', 'oCarregando');

      var oAjax = new Ajax.Request( sUrlRPC, {  method : 'post',
                                                parameters : 'json=' + encodeURIComponent( Object.toJSON(oParam) ),
                                                onComplete : function(oAjax) {

                                                  var oRetorno = JSON.parse( oAjax.responseText.urlDecode() );

                                                  js_removeObj('oCarregando');

                                                  alert( oRetorno.sMessage );

                                                  if (oRetorno.iStatus == 1) {

                                                    js_limpaCampos()
                                                    js_montaGrid()
                                                  }
                                                }
                                              });
    }

    function js_excluirParametro(iSequencial) {

      if (!confirm( _M( sMensagens + "confirma_exclusao" ) )) {
        return false;
      }

      if ($F('rh132_sequencial') == '') {
        alert( _M(sMensagens + "parametro_nao_informado") )
        return false;
      }

      var oParam = { 
            exec           : "excluir",
            iSequencial    : $F('rh132_sequencial')
          }

      js_divCarregando('Aguarde, Excluindo registro...', 'oCarregando');

      var oAjax = new Ajax.Request( sUrlRPC, {  method : 'post',
                                                parameters : 'json=' + Object.toJSON(oParam),
                                                onComplete : function(oAjax) {

                                                  var oRetorno = JSON.parse( oAjax.responseText.urlDecode() );

                                                  js_removeObj('oCarregando');

                                                  alert( oRetorno.sMessage );

                                                  if (oRetorno.iStatus == 1) {
                                                    
                                                    js_limpaCampos()
                                                    js_alternaBotoes(true)
                                                    js_montaGrid()
                                                  }
                                                }
                                              });

    }

    function js_renderizaGrid(iSequencialHide) {
      oGridParametros.clearAll(true);

      aRows.each(function(oRow) {

        if (iSequencialHide != oRow.rh132_sequencial) {

          oGridParametros.addRow([
              oRow.rh132_sequencial,
              oRow.rh132_anobase,
              js_formatar(oRow.rh132_valorminimo, 'f'),
              oRow.rh132_codigoarquivo,
              "<a href='#' onclick='js_visualizaParametro("
              + oRow.rh132_sequencial
              + ", true);return false;' title='Alterar'>A</a>"
              + "&nbsp;<a href='#' onclick='js_visualizaParametro(" 
              + oRow.rh132_sequencial 
              + ", false);return false;' title='Excluir'>E</a>"
            ]);
        }
      })

      oGridParametros.renderRows();
    }

    js_alternaBotoes(true);
    js_montaGrid();
  </script>

</html>