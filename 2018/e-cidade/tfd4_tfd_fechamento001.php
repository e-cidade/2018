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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
 
define("URL_MENSAGEM_TFD4_TDF_FECHAMENTO", "saude.tfd.tfd4_tfd_fechamento.");
$dataSistema = date("d/m/Y", db_getsession("DB_datausu"));

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
    db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js");
    db_app::load("estilos.css, grid.style.css");
  ?>
</head>
<body bgcolor=#CCCCCC >

  <div class='container'>
    <form name="form1" action="">
      <fieldset >
        <legend>Fechamento de Competência</legend>
        
        <table class='form-container'>
          <tr style="display: none;">
            <td><input type="text" value='' id='codigoFechamento' /></td>
          </tr>
          
          <tr title="<?php echo _M(URL_MENSAGEM_TFD4_TDF_FECHAMENTO . "title_competencia"); ?>" >
            <td class="bold" nowrap="nowrap"> Competência Mês/Ano:</td>
            <td nowrap="nowrap">
              <?php
                db_input("mesCompetencia", 3, 1, true, "text", 1, "onchange='js_validaMes(); js_sugereDescricao();'", "", "", "", 2);
                echo " <b>/</b> ";
                db_input("anoCompetencia", 5, 1, true, "text", 1, "onchange='js_validaAno(); js_sugereDescricao();'", "", "", "", 4);
              ?>
            </td>
          </tr>

          <tr title="<?php echo _M(URL_MENSAGEM_TFD4_TDF_FECHAMENTO . "title_periodo"); ?>">
            <td class="bold" nowrap="nowrap">Período de Fechamento:</td>
            <td nowrap="nowrap">
              <?php 
              db_inputdata('dataInicio', '','', '', true, 'text', 1, "onchange='validaIntervaloDatas();'","","","parent.validaIntervaloDatas();");
              echo " <b>à<b> ";
              db_inputdata('dataFim', '','', '', true, 'text', 1, "onchange='validaIntervaloDatas();'","","","parent.validaIntervaloDatas();");
              ?>
            </td>
          </tr>
          
          <tr title="<?php echo _M(URL_MENSAGEM_TFD4_TDF_FECHAMENTO . "title_data_sistema"); ?>">
            <td class="bold" nowrap="nowrap">Data Sistema:</td>
            <td nowrap="nowrap">
              <?php db_input("dataSistema", 10, "", true, "text", 3); ?>
            </td>
          </tr>
          
          <tr title='<?php echo _M(URL_MENSAGEM_TFD4_TDF_FECHAMENTO . "title_financiamento"); ?>'>
            <td class="bold" nowrap="nowrap">Tipo Financiamnto:</td>
            <td nowrap="nowrap">
              <select id='financiamento' name='financiamento'>
                <option selected="selected" value='0'> 00 - Todos</option>
              </select>
            </td>
          </tr>
          
          <tr title=" <?php echo _M(URL_MENSAGEM_TFD4_TDF_FECHAMENTO . "title_descricao_fechamento"); ?>">
            <td class="bold" nowrap="nowrap">Descrição:</td>
            <td nowrap="nowrap">
              <?php db_input("descricao", 60, "", false, "text"); ?>
            </td>
          </tr>
        </table>
        
      </fieldset>
      <input type="button" name="salvar" id="salvar" value="Salvar" onclick="js_salvar();"/>
      <input type="button" name="cancelar" id="cancelar" disabled="disabled" value="Cancelar" onclick="js_limpar();"/>
    </form>  
  </div>
  <div class="subcontainer">
      <fieldset style="width: 980px;">
        <legend>Competências Fechadas</legend>
        <div id='ctnGrid'>
        </div>
      </fieldset>
  </div>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">

const URL_MENSAGEM_TFD4_TFD_FECHAMENTO = "saude.tfd.tfd4_tfd_fechamento.";

const RPC = "tfd4_bpamagnetico.RPC.php";

var aMes = new Array('JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ');

var oGridFechamento          = new DBGrid("gridFechamento");
oGridFechamento.nameInstance = "oGridFechamento";

var aWidth = new Array('8%', '13%', '8%', '8%', '20%', '8%', '8%', '21%', '6%');
var aAlign = new Array('center', 'left', 'center', 'center', 'left', 'center', 'center', 'left', 'center');
var aHeader = new Array("Competência", "Financiamento", "Dt. Inicio", "Dt. Fim", "Descrição", "Dt. Sistema", 
                        "Hora Sistema", "Usuário", "Ação");

oGridFechamento.setCellWidth(aWidth);
oGridFechamento.setCellAlign(aAlign);
oGridFechamento.setHeader(aHeader);
oGridFechamento.setHeight(100);
oGridFechamento.show($('ctnGrid'));

function validaIntervaloDatas() {

  if ($F('dataFim') != '' && $F('dataInicio') != '') {

    if (js_comparadata($F('dataInicio'), $F('dataFim'), " > ")) {
      
      alert(_M(URL_MENSAGEM_TFD4_TFD_FECHAMENTO + "periodo_inicial_maior_final"));
      $('dataFim').value = '';
      return false;
    }
  }
  
  return true;
}

/**
 * Valida os dados do formulário
 */
function js_validaDados() {

  if ($F('mesCompetencia') == '') {

    alert(_M(URL_MENSAGEM_TFD4_TFD_FECHAMENTO+"mes_competencia_vazio"));
    return false;
  }

  if ($F('anoCompetencia') == '') {

    alert(_M(URL_MENSAGEM_TFD4_TFD_FECHAMENTO+"ano_competencia_vazio"));
    return false;
  }

  if ($F('dataInicio') == '' && $F('dataFim') == '') {
    
    alert(_M(URL_MENSAGEM_TFD4_TFD_FECHAMENTO+"periodo_fechamento_vazio"));
    return false;
   }
  
  if ($F('dataInicio') == '' && $F('dataFim') != '') {
    
   alert(_M(URL_MENSAGEM_TFD4_TFD_FECHAMENTO+"periodo_inicial_vazio"));
   return false;
  }
  
  if ($F('dataFim') == '' && $F('dataInicio') != '') {
   
   alert(_M(URL_MENSAGEM_TFD4_TFD_FECHAMENTO+"periodo_final_vazio"));
   return false;
  }

  if ($F('descricao') == '') {
    
    alert(_M(URL_MENSAGEM_TFD4_TFD_FECHAMENTO+"descricao_fechamento_vazio"));
    return false; 
  }

  return true;  
}

function js_validaMes() {

  if ( parseInt($F('mesCompetencia'), 10) < 1 || parseInt($F('mesCompetencia'), 10) > 12 ) {

    $('mesCompetencia').value = '';
    alert(_M(URL_MENSAGEM_TFD4_TFD_FECHAMENTO+"mes_competencia_invalido"));
    return false;
  }
  
  $('mesCompetencia').value = js_strLeftPad($F('mesCompetencia'), 2, '0');
  return true;
}

function js_validaAno() {

  if ( parseInt($F('anoCompetencia'), 10) < 1800) {

    $('anoCompetencia').value = '';
    alert(_M(URL_MENSAGEM_TFD4_TFD_FECHAMENTO+"ano_competencia_invalido"));
    return false;
  }
  return true;
}

function js_sugereDescricao() {

  if ($F('mesCompetencia') == '' || $F('anoCompetencia') == '') {
    return false;
  }
  
  var sMes = aMes[ parseInt($F('mesCompetencia'), 10) -1 ];

  $('descricao').value = "COMP " + sMes + "/" + $F('anoCompetencia');  
}

/**
 * Busca os tipos de Financiamento cadastrado no sistema para montar o combobox.
 * Só é retornado os financiamentos do ultimo ano e mês cadastrado no sistema
 */
function js_buscaDadosFinanciamentos() {

  var oParametros = {};
  oParametros.exec = "getFinanciamentosAtualizados";

  var oRequest = {};
  oRequest.method     = 'post';                            
  oRequest.parameters = 'json='+Object.toJSON(oParametros); 
  oRequest.onComplete = js_retornoBuscaDadosFinanciamentos; 

  js_divCarregando(_M(URL_MENSAGEM_TFD4_TFD_FECHAMENTO+"aguarde_busca_finaciamentos"), "db_msgBox");
  new Ajax.Request(RPC, oRequest);
}


function js_retornoBuscaDadosFinanciamentos(oAjax) {

  js_removeObj('db_msgBox');
  var oRetorno = eval("(" + oAjax.responseText + ")");

  if (oRetorno.aDados.length == 0) {

    alert(_M(URL_MENSAGEM_TFD4_TFD_FECHAMENTO+"sem_financiamentos_cadastrados"));
    return false;
  }
  oRetorno.aDados.each(function (oFinanciamentos) {

    var sDescricao = oFinanciamentos.financiamento + " - " + oFinanciamentos.descricao.urlDecode();
    $('financiamento').add(new Option(sDescricao, oFinanciamentos.codigo));
  });
}

/**
 * Busca as competencias que foram encerradas
 */
function js_buscaCompetenciasEncerradas() {

  var oParametros = {};
  oParametros.exec = "getDadosCompetenciaEncerrada";

  var oRequest = {};
  oRequest.method     = 'post';                            
  oRequest.parameters = 'json='+Object.toJSON(oParametros); 
  oRequest.onComplete = js_retornoBuscaUltimaCompetenciaEncerrada; 

  js_divCarregando(_M(URL_MENSAGEM_TFD4_TFD_FECHAMENTO+"aguarde_competencias_encerradas"), "db_msgBoxB");
  new Ajax.Request(RPC, oRequest);
}

function js_retornoBuscaUltimaCompetenciaEncerrada(oAjax) {

  js_removeObj('db_msgBoxB');
  var oRetorno = eval("(" + oAjax.responseText + ")");

  oGridFechamento.clearAll(true);
  oRetorno.aDados.each(function (oDados) {

    var idAlterar    = 'alterar'+ oDados.iCodigoFechamento;
    var idExcluir    = 'excluir'+ oDados.iCodigoFechamento;
    var sCompetencia = js_strLeftPad(oDados.iMesCompetencia, 2, '0') + ' - ' + oDados.iAnoCompetencia;

    var sFinanciamento = '';
    for (var i = 0; i < $('financiamento').options.length; i++) {

      if ($('financiamento').options[i].value == oDados.iFinanciamento) {
        sFinanciamento = $('financiamento').options[i].innerHTML;
      }
    }
    
    var btnAlterar = new Element('input', {'type':'button', 'value':'A', 'name':idAlterar, 'id':idAlterar, 'codigo': oDados.iCodigoFechamento});
    var btnExcluir = new Element('input', {'type':'button', 'value':'E', 'name':idExcluir, 'id':idExcluir, 'codigo': oDados.iCodigoFechamento});
    var aLinha     = new Array();
    aLinha.push(sCompetencia);
    aLinha.push(sFinanciamento);
    aLinha.push(oDados.dtInicio);
    aLinha.push(oDados.dtFim);
    aLinha.push(oDados.sDescricao.urlDecode());
    aLinha.push(oDados.dtSistema);
    aLinha.push(oDados.sHoraSistema);
    aLinha.push(oDados.sUsuario.urlDecode());
    aLinha.push(btnAlterar.outerHTML + btnExcluir.outerHTML);
    oGridFechamento.addRow(aLinha);
    
  });
  
  oGridFechamento.renderRows();

  oRetorno.aDados.each(function (oDados) {

    var oBtbAlterar = $('alterar'+oDados.iCodigoFechamento);
    var oBtbExcluir = $('excluir'+oDados.iCodigoFechamento);

    oBtbAlterar.onclick = function () {
                          js_alterarFechamento(oDados);
                        };

    oBtbExcluir.onclick = function () {
                          js_excluirFechamento(oDados);
                        };
  });
  
};

/**
 * Função de carga inicial dos dados
 */
(function() {

  js_buscaDadosFinanciamentos();
  js_buscaCompetenciasEncerradas();
})();


/**
 * Seta os dados do fechamento no formulário disponibilizando alteração
 */
function js_alterarFechamento(oDadosFechamento) {
  
  $('codigoFechamento').value = oDadosFechamento.iCodigoFechamento;
  $('mesCompetencia').value   = js_strLeftPad(oDadosFechamento.iMesCompetencia, 2, '0');
  $('anoCompetencia').value   = oDadosFechamento.iAnoCompetencia;
  $('dataInicio').value       = oDadosFechamento.dtInicio;
  $('dataFim').value          = oDadosFechamento.dtFim;
  $('dataSistema').value      = oDadosFechamento.dtSistema;
  $('financiamento').value    = oDadosFechamento.iFinanciamento;
  $('descricao').value        = oDadosFechamento.sDescricao.urlDecode();

  $('cancelar').removeAttribute('disabled');
}

/**
 * Exclui o fechamento
 */
function js_excluirFechamento(oDadosFechamento) {

  if (confirm(_M(URL_MENSAGEM_TFD4_TFD_FECHAMENTO+"confirma_exclusao",
                 {'descricaoFechamento': oDadosFechamento.sDescricao.urlDecode()}))) {

    if (oDadosFechamento.lGerouArquivo && !confirm(_M(URL_MENSAGEM_TFD4_TFD_FECHAMENTO+"arquivo_ja_gerou_arquivo",
                                                   {'descricaoFechamento': oDadosFechamento.sDescricao.urlDecode()}))) {
      return false;
    }
    
    var oParametros     = {};
    oParametros.exec    = "remover";
    oParametros.iCodigo = oDadosFechamento.iCodigoFechamento

    var oRequest        = {};
    oRequest.method     = 'post';                            
    oRequest.parameters = 'json='+Object.toJSON(oParametros); 
    oRequest.onComplete = js_retornoExcluiFechamento; 

    js_divCarregando(_M(URL_MENSAGEM_TFD4_TFD_FECHAMENTO+"aguarde_excluindo"), "db_msgBoxC");
    new Ajax.Request(RPC, oRequest);
  }
}

function js_retornoExcluiFechamento(oAjax) {

  js_removeObj('db_msgBoxC');
  var oRetorno = eval ('(' + oAjax.responseText + ')');

  if (oRetorno.status == 1) {
    
    js_buscaCompetenciasEncerradas()
    alert(_M(URL_MENSAGEM_TFD4_TFD_FECHAMENTO+"exclusao_competencia_com_sucesso"));
  } else {
    alert(oRetorno.message.urlDecode());
  }
}

/**
 * Limpa os dados do formulário
 */
function js_limpar() {

   $('codigoFechamento').value = "";
   $('mesCompetencia').value   = "";
   $('anoCompetencia').value   = "";
   $('dataInicio').value       = "";
   $('dataFim').value          = "";
   $('dataSistema').value      = "<?php echo $dataSistema;?>";
   $('financiamento').value    = "0";
   $('descricao').value        = "";
   $('cancelar').setAttribute('disabled', 'disabled');
}

/**
 * Salva os dados do formulário
 */
function js_salvar() {

  if (!js_validaDados()) {
    return false;
  }

  var oParametros             = {};
  oParametros.exec            = "processar";
  oParametros.iCodigo         = $F('codigoFechamento'); 
  oParametros.iMesCompetencia = $F('mesCompetencia');   
  oParametros.iAnoCompetencia = $F('anoCompetencia');   
  oParametros.dtInicio        = $F('dataInicio');       
  oParametros.dtFim           = $F('dataFim');          
  oParametros.dtSistema       = $F('dataSistema');      
  oParametros.iFinanciamento  = $F('financiamento');    
  oParametros.sDescricao      = encodeURIComponent(tagString($F('descricao')));        

  var oRequest        = {};
  oRequest.method     = 'post';                            
  oRequest.parameters = 'json='+Object.toJSON(oParametros); 
  oRequest.onComplete = js_retornoSalvar; 

  js_divCarregando(_M(URL_MENSAGEM_TFD4_TFD_FECHAMENTO+"aguarde_salvando"), "db_msgBoxD");
  new Ajax.Request(RPC, oRequest);
}

 
function js_retornoSalvar(oAjax) {

  js_removeObj('db_msgBoxD');
  var oRetorno = eval( "(" + oAjax.responseText + ")");

  if (oRetorno.status == 1) {

    alert(_M(URL_MENSAGEM_TFD4_TFD_FECHAMENTO+"competencia_encerrada_com_sucesso"));
    js_limpar();
    js_buscaCompetenciasEncerradas();
  } else {
    alert(oRetorno.message.urlDecode());  
  }
  
};
</script>