<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");

$oDaoUnidades         = db_utils::getdao('unidades');

define("URL_MENSAGEM_TFD4_TDF_BPAMAGNETICO", "saude.tfd.tfd4_tfd_bpamagnetico001.");
$dataSistema = date("d/m/Y", db_getsession("DB_datausu"));
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
    db_app::load("scripts.js, strings.js, prototype.js, DBDownload.widget.js");
    db_app::load("estilos.css, grid.style.css");
  ?>
</head>
<body bgcolor=#CCCCCC >
  <div class='container'>
    <form name="form1" action="" >
      <fieldset >
        <legend>Gerador de Arquivo BPA - TFD</legend>
        <fieldset class="separator">
          <legend>Competência</legend>
          <table class='form-container'>
            <tr>
              <td class="bold field-size4" nowrap="nowrap">
              <?
                db_ancora("<b>Competência:</b>", "js_pesquisaCompetencia(true);", 1 );
              ?>
              </td>
              <td nowrap="nowrap">
              <?
                db_input ('iCodigoFechamento', 2, '', true, 'hidden', 3, "" );
                db_input("mesCompetencia", 3, 1, true, "text", 3, "");
                echo " <b>/</b> ";
                db_input("anoCompetencia", 5, 1, true, "text", 3, "");
              ?>
              </td>
            </tr>
            
            <tr>
              <td class="bold field-size4" nowrap="nowrap">Período de Fechamento:</td>
              <td nowrap="nowrap">
              <?php 
                db_inputdata('dataInicio', '','', '', true, 'text', 3, "");
                echo " <b>à<b> ";
                db_inputdata('dataFim', '','', '', true, 'text', 3, "");
              ?>
              </td>
            </tr>
            
            <tr>
              <td class="bold field-size4" nowrap="nowrap">Tipo de Financiamento:</td>
              <td nowrap="nowrap">
              <?php
                db_input("iFinanciamento", 10, '', true, "hidden", 3);
                db_input("sFinanciamento", 58, '', true, "text", 3);
              ?>
              </td>
            </tr>          
          </table>
        </fieldset>
        
        <fieldset class="separator">
          <legend>UPS</legend>
          <?
            $sSql       = $oDaoUnidades->sql_query("","sd02_i_codigo,descrdepto");
            $rsUnidades = $oDaoUnidades->sql_record($sSql);
            db_multiploselect("sd02_i_codigo", "descrdepto", "nSelecionados", "sSelecionados", $rsUnidades,
                              array(), 5, 250);
          ?>
        </fieldset>
        
        <fieldset class="separator">
          <legend>Órgão responsável</legend>
          <table class='form-container'>
            <tr>
              <td class="bold field-size4" nowrap="nowrap">Nome:</td>
              <td nowrap="nowrap">
                <?php 
                  db_input("sInstituicao", 58, '', true, 'text', 3);
                ?>
              </td>
            </tr>
            
            <tr>
              <td class="bold field-size4" nowrap="nowrap">Sigla:</td>
              <td nowrap="nowrap">
              <?php
                db_input("sigla", 10, '', true, "text", 3); 
              ?>
              </td>
            </tr>
            
            <tr>
              <td class="bold field-size4" nowrap="nowrap">CNPJ</td>
              <td nowrap="nowrap">
                <?php 
                  db_input('cnpj', 20, '', true, 3);
                ?>
              </td>
            </tr>          
          </table>
        </fieldset>
        
        <fieldset class="separator">
          <legend>Secretaria da Saúde de destino do(s) BPA(s)</legend>
          <table class='form-container'>
            <tr>
              <td class="bold field-size4" nowrap="nowrap">Sec. de Destino:</td>
              <td nowrap="nowrap">
              <?php 
                db_input('sSecretaria', 58, '', true, 'text', 3);
              ?>
              </td>
            </tr>
          </table>
        </fieldset>
        
        <fieldset class="separator">
          <legend>Arquivo de Produção</legend>
          <table>
            <tr>
              <td class="bold field-size4" nowrap="nowrap">Arquivo:</td>
              <td nowrap="nowrap">
                <label>PA</label>
                <?php 
                  db_input("sNomeArquivo", 15, '', true, 'text', 1);  
                ?>
                <label id='extencao'></label>
              </td>
            </tr>
          </table>
        </fieldset>
      </fieldset>
      <input type="button" id='gerarArquivo' name='gerarArquivo' value='Gerar Arquivo' onclick="js_gerarArquivo();">
      <input type="button" id='gerarRecibo'  name='gerarRecibo'  value='Gerar Recibo'  onclick="js_gerarRecibo();" disabled="disabled">
    </form>  
  </div>
<?
 db_menu ( db_getsession ( "DB_id_usuario" ), 
           db_getsession ( "DB_modulo" ),
           db_getsession ( "DB_anousu" ),
           db_getsession ( "DB_instit" ) 
          ); 
?>
  </body>
</html>
<script type="text/javascript">

const URL_MENSAGEM_TFD4_TFD_BPAMAGNETICO = "saude.tfd.tfd4_tfd_bpamagnetico001.";

const RPC = "tfd4_bpamagnetico.RPC.php";

var aMes = new Array('JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ');

function js_pesquisaCompetencia() {

  var sUrl  = 'func_tfd_fechamento.php?funcao_js=parent.js_dadosCompetencia|';
      sUrl += 'tf32_i_codigo|tf32_i_mescompetencia|tf32_i_anocompetencia|tf32_d_datainicio|tf32_d_datafim|';
      sUrl += 'tf32_i_financiamento|sd65_c_nome';
   
  js_OpenJanelaIframe('top.corpo', 'db_iframe_tfd_fechamento', sUrl, 'Pesquisa Competências Encerradas', true);
  
}

function js_dadosCompetencia(iCodigo, iMes, iAno, dtInicio, dtFim, iFinanciamento, sFinanciamento) {

  $('iCodigoFechamento').value = iCodigo; 
  $('mesCompetencia').value    = iMes;
  $('anoCompetencia').value    = iAno;
  $('dataInicio').value        = js_formatar(dtInicio, 'd');
  $('dataFim').value           = js_formatar(dtFim, 'd');
  $('iFinanciamento').value    = iFinanciamento;
  $('sFinanciamento').value    = sFinanciamento;

  $('extencao').innerHTML = "." + aMes[iMes -1];
  db_iframe_tfd_fechamento.hide();

}

function buscaDadosIniciais() {

  var oParametros = {'exec' : 'dadosFormGerarArquivo'};

  var oRequest = {};
  oRequest.method = 'post';
  oRequest.parameters = 'json='+Object.toJSON(oParametros); 
  oRequest.onComplete = js_retornoBuscaDadosIniciais; 

  js_divCarregando(_M(URL_MENSAGEM_TFD4_TFD_BPAMAGNETICO+"aguarde_carregando_formulario"), "db_msgBoxB");
  new Ajax.Request(RPC, oRequest);
}

function js_retornoBuscaDadosIniciais(oAjax) {

  js_removeObj('db_msgBoxB');
  var oRetorno = eval('(' + oAjax.responseText + ')');
  if (oRetorno.status == 2) {
    
    alert(oRetorno.message.urlDecode());
    return false;
  }
          
  $('sInstituicao').value = oRetorno.sInstituicao.urlDecode();
  $('cnpj').value         = oRetorno.iCnpj;
  $('extencao').innerHTML = "." + aMes[oRetorno.iMesAtual -1];
  $('sSecretaria').value  = oRetorno.sBpaDestino.urlDecode();
  $('sigla').value        = oRetorno.sBpaSigla.urlDecode()
}


(function () {
  buscaDadosIniciais();
  
})();

function js_validaDados() {

  if ($F('iCodigoFechamento') == '') {
    
    alert(_M(URL_MENSAGEM_TFD4_TFD_BPAMAGNETICO+"erro_selecione_uma_competencia"));
    return false;
  }
  
  if ($('sSelecionados').length == 0) {

    alert(_M(URL_MENSAGEM_TFD4_TFD_BPAMAGNETICO+"erro_selecione_uma_ups"));
    return false;
  }

  return true;
}

function js_gerarArquivo () {

  if (!js_validaDados()) {
    return false;
  }
  
  var iUpsSelecionados = $('sSelecionados').length;
  var aUpsSelecionado  = new Array();

  for (var i = 0; i < iUpsSelecionados; i++) {
    aUpsSelecionado.push($('sSelecionados').options[i].value);
  }
  
  var oParametros          = {};
  oParametros.exec         = 'gerarArquivo';
  oParametros.iCompetencia = $F('iCodigoFechamento');
  oParametros.sUps         = aUpsSelecionado.implode(',');
  oParametros.sNomeArquivo = "PA"+$F('sNomeArquivo')+$('extencao').innerHTML;

  var oRequest = {};
  oRequest.method = 'post';
  oRequest.parameters = 'json='+Object.toJSON(oParametros); 
  oRequest.onComplete = js_retornoGerarArquivo; 

  js_divCarregando(_M(URL_MENSAGEM_TFD4_TFD_BPAMAGNETICO+"aguarde_gerandoArquivo"), "db_msgBoxA");
  new Ajax.Request(RPC, oRequest);
}

var oDadoRecibo = new Object();

function js_retornoGerarArquivo(oAjax) {

  js_removeObj('db_msgBoxA');
  var oRetorno = eval('(' + oAjax.responseText + ')');

  if (oRetorno.status == 2) {
    
    alert(oRetorno.message.urlDecode());
    return false;
  }

  oDadoRecibo = oRetorno.oDadosBPA;
  /*
  oDadosBPA":{"iFolhas":"000000","iLinhas":"000004","nControle":"0335"},"sNomeArquivo":"%2Ftmp%2FPAaaa.SET","lTemInconsistencia":false,"sArquivoInconsistencia":"tmp%2Ferro_bpa_magnetico.json"
  */
  if (!oRetorno.lTemInconsistencia) {

    $('gerarRecibo').removeAttribute("disabled");
    
    alert(_M(URL_MENSAGEM_TFD4_TFD_BPAMAGNETICO+"arquivo_gerado"));
    var oArquivoBPA = new DBDownload();
    oArquivoBPA.addFile(oRetorno.sNomeArquivo.urlDecode(), "Download arquivo TXT (BPA TFD)");
    oArquivoBPA.show();
  } else {

    alert(_M(URL_MENSAGEM_TFD4_TFD_BPAMAGNETICO+"erro_ao_gerar_arquivo"));
    
    sUrl = "sau2_bpainconsistencia002.php";
    jan  = window.open(sUrl, '',
                       'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    jan.moveTo(0,0);
  }
}


function js_gerarRecibo() {

  var sUrl  = 'sau2_recibobpa001.php?';
      sUrl += 'linhas='+oDadoRecibo.iLinhas;
      sUrl += '&sd97_i_compmes='+$F('mesCompetencia');
      sUrl += '&iTFD=' + +oDadoRecibo.iLinhas;
      sUrl += '&sNomeorg='+$F('sInstituicao');
      sUrl += '&sSigla='+$F('sigla');
      sUrl += '&iOrgao=1';
      sUrl += '&sNomearq='+$F('sNomeArquivo');
      sUrl += '&iCnpj='+$F('cnpj');
      sUrl += '&sDestino='+$F('sSecretaria');
      sUrl += '&iCntrl='+oDadoRecibo.nControle;
      sUrl += '&sd97_i_compano='+$F('anoCompetencia');

  jan = window.open(sUrl, '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
} 


function js_limpar() {
  
  document.form1.reset();
  buscaDadosIniciais();
};
</script>