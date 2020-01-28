<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_ouvidor_classe.php");
require_once ("classes/db_db_depart_classe.php");


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load ( 'strings.js,scripts.js,datagrid.widget.js,prototype.js' );
db_app::load ( 'estilos.css,grid.style.css' );
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_frmListaDados();">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 20px;">
  <tr align="center">
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <center>
      
        <form action="" name="form1">
          <table width="680" style="margin-top: 20px;">
            <tr>
              <td>
                <fieldset>
                  <legend><b>Consulta Ouvidoria</b></legend>
                  <table>
                    <tr>
                      <td align="left"><b>Data de criação:</b></td>
                      <td align="left">
                        <?
                        db_inputdata('dt_inicio', '', '', '', true, 'text', 1);
                        echo "&nbsp;à&nbsp;";
                        db_inputdata('dt_fim', '', '', '', true, 'text', 1);
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <?
                        db_ancora('<b>Tipo de Processo</b>', ' js_pesquisaTipoProcesso(true); ', '');
                        ?>
                      </td>
                      <td>
                        <?
                        db_input('p58_codigo', 5, 1, true, 'text', 1, ' onchange="js_pesquisaTipoProcesso(false); " ');
                        db_input('p51_descr', 50, 0, true, 'text', 3, '');
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <?
                        db_ancora('<b>Número do Atendimento:</b>', ' js_pesquisaNumeroAtendimento(true); ', '');
                        ?>
                      </td>
                      <td>
                        <?
                        db_input('ov01_numero', 5, "", true, 'text', 1, ' onchange="js_pesquisaNumeroAtendimento(false); "');
                        db_input('ov01_solicitacao', 50, 0, true, 'text', 3, '');
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <?
                        db_ancora('<b>Número do Processo:</b>', ' js_pesquisaNumeroProcesso(true); ', '');
                        ?>
                      </td>
                      <td>
                        <?
                        db_input('ov09_protprocesso', 5, 1, true, 'text', 1, ' onchange="js_pesquisaNumeroProcesso(false); "');
                        db_input('ov09_ouvidoriaatendimento', 50, 0, true, 'text', 3, '');
                        ?>
                      </td>
                    </tr>
                  </table>
                </fieldset>
              </td>
            </tr>
            <tr align="center">
              <td>
                <input type="button" value="Atualizar" onclick="js_atualizaDados();" />
                <input type="button" value="Imprimir" onclick="js_imprimeAtendimentos();" />
              </td>
            </tr>
            <tr>
              <td>
                <fieldset>
                  <legend><b>Lista de atendimentos</b></legend>
                  <div id="listaResultados"></div>
                </fieldset>
              </td>
            </tr>
          </table>
        </form>
        
      </center>
    </td>
  </tr>
</table>
<?
db_menu ( db_getsession ( "DB_id_usuario" ), db_getsession ( "DB_modulo" ), db_getsession ( "DB_anousu" ), db_getsession ( "DB_instit" ) );
?>
</body>
</html>
<script type="text/javascript">

  /**
   * Efetua a pesquisa de tipos de processo.
   */
  function js_pesquisaTipoProcesso(mostra) {
    
    if (document.getElementById('p58_codigo').value == '' && mostra == false) {
    
      document.getElementById('p58_codigo').value = '';
      document.getElementById('p51_descr').value  = '';
    } else {
      if (mostra == true) {
      
        var sUrlLookUp = 'func_tipoproc.php?funcao_js=parent.js_mostraTipoProcesso|p51_codigo|p51_descr'; 
        js_OpenJanelaIframe('', 'db_iframe', sUrlLookUp, 'Pesquisa Tipo Processo', true);
      } else {
      
        var sValorPesquisa = document.getElementById('p58_codigo').value;
        var sUrlLookUp     = 'func_tipoproc.php?pesquisa_chave='+sValorPesquisa+'&funcao_js=parent.js_mostraTipoProcesso';
        js_OpenJanelaIframe('', 'db_iframe', sUrlLookUp, 'Pesquisa Tipo Processo', false);
      }
    }
  }
  
  /**
   * Insere no formulário o retorno da pesquisa de tipos de processo.
   */
  function js_mostraTipoProcesso() { 

    if (arguments[1] === true) {
      
      document.getElementById('p58_codigo').value = '';
      document.getElementById('p51_descr').value  = arguments[0];
    } else {
      
      document.getElementById('p58_codigo').value = arguments[0];
      document.getElementById('p51_descr').value  = arguments[1];
    }
    db_iframe.hide();
  }
  
  /**
   * Efetua a pesquisa de número de atendimento.
   */
  function js_pesquisaNumeroAtendimento(mostra) {
   
    if (document.getElementById('ov01_numero').value == '' && mostra == false) {
       
      document.getElementById('ov01_numero').value      = '';
      document.getElementById('ov01_solicitacao').value = '';
    } else {
      if (mostra == true) {
       
        var sUrlLookUp = 'func_ouvidoriaatendimento.php?funcao_js=parent.js_mostraNumeroAtendimento|ov01_numero|ov01_requerente';
        js_OpenJanelaIframe('', 'db_iframe', sUrlLookUp, 'Pesquisa Número Atendimento', true);
      } else {
       
        var sValorPesquisa = document.getElementById('ov01_numero').value;
        var sUrlLookUp     = 'func_ouvidoriaatendimento.php?requer=1&pesquisa_chave='+sValorPesquisa+'&funcao_js=parent.js_mostraNumeroAtendimento';
        js_OpenJanelaIframe('', 'db_iframe', sUrlLookUp, 'Pesquisa Número Atendimento', false);
      }
    }
  }
   
  /**
   * Insere no formulário o retorno da pesquisa de numero de atendimento.
   */
  function js_mostraNumeroAtendimento() { // tem que buscar qual o parâmetro correto pra esse método

    if (arguments[1] === true) {
      
      document.getElementById('ov01_numero').value      = '';
      document.getElementById('ov01_solicitacao').value = arguments[0];
    } else {
      document.getElementById('ov01_numero').value      = arguments[0];
      document.getElementById('ov01_solicitacao').value = arguments[1];
    }
    db_iframe.hide();
  }
  
  /**
   * Efetua a pesquisa do numero do processo.
   */
  function js_pesquisaNumeroProcesso(mostra) {
    
    if (document.getElementById('ov09_protprocesso').value == '' && mostra == false) {
      
      document.getElementById('ov09_protprocesso').value         = '';
      document.getElementById('ov09_ouvidoriaatendimento').value = '';
    } else {
      if (mostra == true) {
        
        var sUrlLookUp = 'func_protprocesso.php?funcao_js=parent.js_mostraNumeroProcesso|p58_codproc|z01_nome';
        js_OpenJanelaIframe('', 'db_iframe', sUrlLookUp, 'Pesquisa Número Protocolo', true);
      } else {
      
        var sValorPesquisa = document.getElementById('ov09_protprocesso').value;
        var sUrlLookUp     = 'func_protprocesso.php?pesquisa_chave='+sValorPesquisa+'&funcao_js=parent.js_mostraNumeroProcesso';
        js_OpenJanelaIframe('', 'db_iframe', sUrlLookUp, 'Pesquisa Número Protocolo', false);
      }
    }
  }
  
  /**
   * Insere no formulário o retorno da pesquisa do número do processo.
   */
  function js_mostraNumeroProcesso () {
    
    if (arguments[1] === false) {
      
      document.getElementById('ov09_protprocesso').value         = '';
      document.getElementById('ov09_ouvidoriaatendimento').value = arguments[0];
    } else {
      
      document.getElementById('ov09_protprocesso').value         = arguments[0];
      document.getElementById('ov09_ouvidoriaatendimento').value = arguments[1];
    }
    db_iframe.hide();
  }

  /**
   *
   */
  function js_frmListaDados() {
    
     oDBGridListaResultados = new DBGrid('gridoDBGridListaResultados');
     oDBGridListaResultados.nameInstance = 'oDBGridListaResultados';
     oDBGridListaResultados.setHeader(new Array('Número',
                                                'Tipo de Processo',
                                                'Requerente',
                                                'Depto Atual',
                                                'Data de Criação'));
     oDBGridListaResultados.setHeight(new Array(50, 
                                                150, 
                                                150, 
                                                150, 
                                                150));
     oDBGridListaResultados.setCellAlign(new Array('center',
                                                   'left',
                                                   'left',
                                                   'left',
                                                   'center'));
     oDBGridListaResultados.show($('listaResultados'));
  }
  
  /**
   * Efetua a busca com as informações inseridas nos filtros
   */
  function js_atualizaDados() {

    js_divCarregando('Aguarde consultando dados do Processo...','msgBox');
  
    var aAtendimento       =  $F('ov01_numero').split('/');
    var iNumeroAtendimento = aAtendimento[0];    
    var iAnoAtendimento    = "";  
    
    if (aAtendimento.length > 1) {
      iAnoAtendimento = aAtendimento[1];
    }
     
    // recolhemos os dados que serão enviados ao RPC
    var oPesquisar = new Object();
        oPesquisar.acao              = 'pesquisa';
        oPesquisar.data_inicial      = $F('dt_inicio') !== '' ? js_formatar($F('dt_inicio'), 'd') : '';
        oPesquisar.data_final        = $F('dt_fim')    !== '' ? js_formatar($F('dt_fim'), 'd') : '';
        oPesquisar.tipoProcesso      = $F('p58_codigo');
        oPesquisar.numeroAtendimento = iNumeroAtendimento;
        oPesquisar.anoAtendimento    = iAnoAtendimento;
        oPesquisar.numeroProcesso    = $F('ov09_protprocesso');

    
    // Limpamos a grid    
    oDBGridListaResultados.clearAll(true);
    oDBGridListaResultados.renderRows();

    // Fazemos a requisição Ajax ao RPC    
    var sUrl   = 'ouv1_consatendimentos.RPC.php';
    var sQuery = 'dados='+Object.toJSON(oPesquisar);
    var oAjax  = new Ajax.Request(sUrl, {method: 'post',
                                         parameters: sQuery,
                                         onComplete: js_atualizaGrid
                                        });
  }
  
  /**
   * Trata o resultado da pesquisa disparada através do filtro.
   */
  function js_atualizaGrid(oAjax) {
  
    js_removeObj("msgBox");
    
    var aRetorno = eval("("+oAjax.responseText+")");
    var sExpReg  = new RegExp('\\\\n','g');
    
    if ( aRetorno.status == 0) {
   
      alert(aRetorno.message.urlDecode().replace(sExpReg,'\n'));
      return false;
    }else{
      js_preencheGrid(aRetorno.resultados);
    }
  }
  
  /**
   * Atualiza a Grid com  os novos dados retornados pela js_atualizaGrid
   */
  function js_preencheGrid(aResultados) {
    
    oDBGridListaResultados.clearAll(true);
    
    var iNumRows = aResultados.length;
    
    if (iNumRows > 0) {
      
      aResultados.each(
        function (oResultados) {

          var fc_onClick = 'js_detalhesAtendimento('+oResultados.ov01_sequencial+')';
          var aRow    = new Array();
              aRow[0] = '<a href="#" onclick="'+fc_onClick+'">'+oResultados.ov01_numero.urlDecode()+'</a>';
              aRow[1] = oResultados.p51_descr.urlDecode();
              aRow[2] = oResultados.ov01_requerente.urlDecode();
              aRow[3] = oResultados.descrdepto.urlDecode();
              aRow[4] = js_formatar(oResultados.ov01_dataatend, 'd');
          
          oDBGridListaResultados.addRow(aRow);
        }
      );
    }
    oDBGridListaResultados.renderRows();
  }
  
  /**
   * Mostra os detalhes do atendimento em um iframe
   */
  function js_detalhesAtendimento(iSeqAtendimento) {
    var sUrl = 'func_detalhesprocessoouvidoria.php?iAtendimento='+iSeqAtendimento;
    js_OpenJanelaIframe('', 'db_iframe_detalhesProc', sUrl, 'Detalhes Atendimento',  true);
  }
  
  /**
   * Imprime o Relatório do resultado atual da pesquisa
   */
  function js_imprimeAtendimentos() {
   
    if (oDBGridListaResultados.getNumRows() == 0) {
     
      alert('Usuário:\n\n Nenhum atendimento encontrado para emissão de relatorio!\n\nAdministrador:\n\n');
      return false;
    } else {
     
      var sUrl  = "ouv1_consatendimentos002.php?";
          sUrl += "data_inicial="+$F('dt_inicio');
          sUrl += "&data_final="+$F('dt_fim');
          sUrl += "&tipoProcesso="+$F('p58_codigo');
          sUrl += "&numeroAtendimento="+$F('ov01_numero');
          sUrl += "&numeroProcesso="+$F('ov09_protprocesso');
          //sUrl += "&codigoDepart="+$F('coddepto');
      var tamanhoJanela = 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ';
          
      jan = window.open(sUrl, '', tamanhoJanela);
      jan.moveTo(0, 0);
      
      
    }
  }
  
</script>