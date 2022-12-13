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

/**
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
  db_app::load("scripts.js, strings.js, prototype.js, estilos.css, windowAux.css");
  db_app::load("widgets/dbcomboBox.widget.js, widgets/DBViewInstituicao.widget.js, datagrid.widget.js");
  db_app::load("widgets/datagrid/plugins/DBHint.plugin.js, windowAux.widget.js");
  db_app::load("widgets/DBDownload.widget.js");
?>
</head>

<body style="margin-top: 30px; background-color: #CCCCCC;">

<center>
  <fieldset style="width: 500px">
    <legend><b>Consulta de Consistência Contábil</b></legend>
    <table style="width: 100%">
      <tr>
        <td style="width: 100px;" nowrap="nowrap"><b>Conferência:</b></td>
        <td>
          <div id="ctnComboBoxConferencia"></div>
        </td>
      </tr>
      <tr>
        <td><b>Período:</b></td>
        <td>
          <div id="ctnComboBoxPeriodo"></div>
        </td>
      </tr>
    </table>
    <div style="width: 100%" id="ctnGridInstituicao">
    </div>
  </fieldset>
  <p>
    <input type="button" id="btnProcessar" value="Processar" onclick="processar();"/>
  </p>

</center>

<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

<script type="application/javascript">

  var oComboBoxConferencia = new DBComboBox('oComboBoxConferencia', 'oComboBoxConferencia', ['Selecione'], '100%');
  var oComboBoxPeriodo     = new DBComboBox('oComboBoxPeriodo', 'oComboBoxPeriodo', ['Selecione'], '100%');
  var oViewInstituicao     = new DBViewInstituicao('oViewInstituicao', $('ctnGridInstituicao'));
  var sCaminhoRPC          = "con4_relatorioslegais.RPC.php";
  var oContainerRelatorio  = $('ctnComboBoxConferencia');
  var oContainerPeriodo    = $('ctnComboBoxPeriodo');
  var sArquivoDownload     = null;
  const URL_MENSAGEM       = "financeiro.contabilidade.con3_consistenciacontabil001.";
  var oBotaoProcessar      = $('btnProcessar');
  oBotaoProcessar.disabled = true;

  oComboBoxConferencia.addEvent('onChange', 'getPeriodosRelatorio()');
  oComboBoxConferencia.show(oContainerRelatorio);
  oComboBoxPeriodo.show(oContainerPeriodo);
  oViewInstituicao.show();


  /**
   * Busca os relatórios do grupo 4
   */
  function getRelatoriosConferencia() {

    js_divCarregando("Aguarde, carregando relatórios...", "msgBox");

    new Ajax.Request(sCaminhoRPC,
                    {method: 'post',
                      asynchronous: false,
                      parameters: 'json='+Object.toJSON({"exec":"getRelatorios", "iTipo":4}),
                      onComplete: function(oAjax) {

                        js_removeObj("msgBox");
                        var oRetorno = eval("("+oAjax.responseText+")");
                        
                        if (oRetorno.aRelatorios.length == 0) {

                          alert( _M(URL_MENSAGEM + "relatorios_grupo_conferencia")); 
                          return false;
                        }
                        oRetorno.aRelatorios.each(function(oRelatorio) {
                          oComboBoxConferencia.addItem(oRelatorio.iCodigo, oRelatorio.sNome.urlDecode());
                        });
                        oComboBoxConferencia.show(oContainerRelatorio);
                        return true;
                      }
                    });
  }

  /**
   * Busca os periodos disponíveis para emissão da consulta
   * @returns {boolean}
   * @returns {boolean}
   */
  function getPeriodosRelatorio() {

    if ($F('oComboBoxConferencia') == "0") {

      oComboBoxPeriodo.clearItens();
      oComboBoxPeriodo.addItem("0", "Selecione");
      oComboBoxPeriodo.show(oContainerPeriodo);
      oBotaoProcessar.disabled = true;
      return false;
    }
    oBotaoProcessar.disabled = false;
    js_divCarregando("Aguarde, carregando os períodos disponíveis...", "msgBox");

    var oParametro = {"exec":"getPeriodosDoRelatorio", "iCodigo":$F('oComboBoxConferencia')};
    new Ajax.Request(sCaminhoRPC,
                    {method: 'post',
                      asynchronous: false,
                      parameters: 'json='+Object.toJSON(oParametro),
                      onComplete: function(oAjax) {

                        js_removeObj("msgBox");
                        oComboBoxPeriodo.clearItens();
                        var oRetorno = eval("("+oAjax.responseText+")");
                        if (oRetorno.aPeriodos.length == 0) {
                          
                          alert( _M(URL_MENSAGEM + "nenhum_periodo_encontrado" ) ); 
                          return false;
                        }
                        oRetorno.aPeriodos.each(function(oPeriodo) {
                          oComboBoxPeriodo.addItem(oPeriodo.iCodigo, oPeriodo.sDescricao.urlDecode());
                        });
                        oComboBoxPeriodo.show(oContainerPeriodo);
                        return true;
                      }
                    });
  }

  /**
   * Dispara um RPC que retorna os dados para serem apresentados na grid
   * @returns {boolean}
   */
  function processar() {

    if (oComboBoxConferencia.getValue() == "0") {
      
      alert(_M(URL_MENSAGEM+"relatorio_nao_selecionado")); 
      return false;
    }

    if (oViewInstituicao.getInstituicoesSelecionadas().length == 0) {
      
      alert(_M(URL_MENSAGEM+"instituicao_nao_selecionada")); 
      return false;
    }

    oBotaoProcessar.disable = true;
    js_divCarregando("Aguarde, processando conferência...", "msgBox");
    var iCodigoRelatorio = oComboBoxConferencia.getValue();
    var iCodigoPeriodo   = oComboBoxPeriodo.getValue();
    var aInstituicoes    = oViewInstituicao.getInstituicoesSelecionadas(true);

    var oParametro = {"exec" : "processarConferencia",
                      "iCodigoRelatorio": iCodigoRelatorio,
                      "iCodigoPeriodo"  : iCodigoPeriodo,
                      "aInstituicoes"   : aInstituicoes}

    new Ajax.Request(sCaminhoRPC,
                    {method: 'post',
                      asynchronous: false,
                      parameters: 'json='+Object.toJSON(oParametro),
                      onComplete: montarJanela});
  }

  /**
   * Monta a janela que apresentará os dados ao usuário
   */
  function montarJanela(oAjax) {

    oBotaoProcessar.disable = false;
    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");
    sArquivoDownload = oRetorno.arquivo.urlDecode();

    oWindow = new windowAux("oWindow1", "Resultado da Conferência Contábil", 1024, 600);
    var sConteudo  = "<fieldset style='width: 97%'>";
    sConteudo     += "  <legend><b>Resultado</b></legend>";
    sConteudo     += "  <div id='ctnGridResultado'></div>";
    sConteudo     += "</fieldset>";
    sConteudo     += "<p align='center'>";
    sConteudo     += "  <input type='button' id='btnDownloadCSV' value='Baixar CSV' onclick='downloadArquivo()' />&nbsp;";
    sConteudo     += "  <input type='button' id='btnFecharJanela' value='Fechar' onclick='oWindow.destroy()' />";
    sConteudo     += "</p>";
    oWindow.setContent(sConteudo);

    var sAjudaJanela = "Resultado encontrado para o relatório: "+oComboBoxConferencia.getValue()+" - "+oComboBoxConferencia.getDescricao();
    var oMessageBoard = new DBMessageBoard('oMessageBoard',
                                           'Consulta conferência contábil',
                                           sAjudaJanela,
                                           $('windowoWindow1_content'));
    oMessageBoard.show();
    oWindow.show();

    oWindow.setShutDownFunction(function() {
      oWindow.destroy();
    });
    preencherGrid(oRetorno);
  }

  /**
   * Preenche a grid com os dados encontrados no RPC
   */
  function preencherGrid(oRetorno) {

    var iTotalColunas;
    var aColunasPrimeiroRetorno;
    for (var iIndice in oRetorno.aLinhasConsistencia) {

      var oDadoColuna         = oRetorno.aLinhasConsistencia[iIndice];
      iTotalColunas           = oDadoColuna.colunas.length;
      aColunasPrimeiroRetorno = oDadoColuna.colunas;
    }

    var aCellWidth            = ["60%"];
    var aCellAlign            = ["left"];
    var aCellHeaders          = ["Descrição"];
    var iDivisaoColunaValores = (40 / iTotalColunas).toFixed();

    /**
     * Descobrimos os títulos do cabeçalho e os alinhamentos a serem aplicados
     */
    for (var iRow = 0; iRow < iTotalColunas; iRow++) {

      aCellWidth.push(iDivisaoColunaValores+"%");
      aCellAlign.push("right");
      aCellHeaders.push(aColunasPrimeiroRetorno[iRow].descricao.urlDecode());
    }

    var oDBGridResultado = new DBGrid("oDBGridResultado");
    oDBGridResultado.nameInstance = "oDBGridResultado";
    oDBGridResultado.setHeight(300);
    oDBGridResultado.setCellWidth(aCellWidth);
    oDBGridResultado.setCellAlign(aCellAlign);
    oDBGridResultado.setHeader(aCellHeaders);
    oDBGridResultado.show($('ctnGridResultado'));
    oDBGridResultado.clearAll(true);

    /**
     * Percorremos os dados do objeto para preencher a grid
     */
    var iLinha = 0;
    for (var iIndice in oRetorno.aLinhasConsistencia) {

      var oDadoLinha = oRetorno.aLinhasConsistencia[iIndice];

      var aLinha = [strRepeat("&nbsp;&nbsp;", oDadoLinha.nivel)+oDadoLinha.descricao.urlDecode()];
      oDadoLinha.colunas.each(function (oColuna) {
        aLinha.push(js_formatar(oColuna.valor, "f"));
      });
      oDBGridResultado.addRow(aLinha);
      if (oDadoLinha.totalizar) {
        oDBGridResultado.aRows[iLinha].sStyle += ";font-weight:bold;";
      }
      iLinha++;
    }
    oDBGridResultado.renderRows();

  }

  function downloadArquivo() {

    var oDownload = new DBDownload();
    oDownload.addFile(sArquivoDownload, "Download Conferência Contábil");
    oDownload.show();
    oDownload.oWindowAux.setChildOf(oWindow);
  }

  getRelatoriosConferencia();
</script>
</body>
</html>