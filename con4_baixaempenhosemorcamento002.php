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
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
//db_app::import("contabilidade.lancamento.*");
/**
 * @todo modificara para (!USE_PCASP) 
 */
if (!USE_PCASP) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Este menu só é acessível com o PCASP ativado.");
}
$db_opcao = 1;
db_app::import("configuracao.UsuarioSistema");
$oUsuario       = new UsuarioSistema(db_getsession('DB_id_usuario'));
$sNomeUsuario   = $oUsuario->getNome();
$sCodigoUsuraio = $oUsuario->getIdUsuario();

$oGet = db_utils::postMemory($_GET);

$c36_sequencial = $oGet->iCodigoInscricao;

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
    db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js");
    db_app::load("estilos.css, grid.style.css");
  ?>
  <style type="text/css">
    .tamanho-primeira-col{
      width:100px;
    }
    .field-border-topo {
      border: none;
      border-top: inset 1px #FFF;
    }
    .oculto {
      display: none;
    }
    #servico{
      color: #fe3b10;
      font-weight: bold;
    }
    #tipoCompra {
      width: 95px;
    }
    #tipoLicitacao {
      width: 95px;
    }
    #historico {
      width: 95px;
    }
    #evento {
      width: 95px;
    }
    #desdobramento {
      width: 95px;
    }        
    #peculiar {
      width: 95px;
    }
    #lLiquidar {
      width: 95px;
    }    
    #tipoCompradescr {
      width: 410px;
    }
    #tipoLicitacaodescr{
      width: 410px;
    }
    #historicodescr {
      width: 410px;
    }
    #eventodescr  {
      width: 410px;
    }
    #desdobramentodescr  {
      width: 410px;
    }
    #peculiardescr  {
      width: 410px;
    }
    #empenho {
      width: 95px;
    }    
    #empenhodescr {
      width: 410px;
    }    
  </style>  
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1;js_getDadosInscricao();" >
  <form action="" id ='inscricao-passiva'> 

  <center>
      <fieldset style="width: 750px; margin-top: 50px;">
        <legend><strong>Empenho de Passivo sem Suporte Orçamentário</strong></legend>
        
          <table >
          
            
            <tr>
              <td nowrap="nowrap" class='tamanho-primeira-col'>
                <?php
                  db_input('c36_sequencial',   5, '', true, 'hidden', 3, "");
                  db_input('nValorTotalInscricao',   5, '', true, 'hidden', 3, "");
                ?>
                <b>Favorecido:</b>
              </td>
              <td nowrap="nowrap"> 
                <?php
                  db_input('numcgm',     10, '', true, 'text', 3, " onchange='js_pesquisaFavorecido(false);'");
                  db_input('favorecido', 56, '', true, 'text', 3);
                ?>
              </td>
            </tr>
            
            <tr>
              <td nowrap="nowrap" class='tamanho-primeira-col'>
               <strong><?db_ancora('Dotação:',"js_pesquisa_dotacao(true);",1);?></strong>
              </td>
              <td nowrap="nowrap"> 
              
                <?php
                
                  db_input("o58_coddot",10,"",true,"text",3,"onchange='js_pesquisa_dotacao(false);'");
                  db_input("estruturalDotacao",56,"",true,"text",3); 

                  db_input("valorDotacao",10,"",true,"hidden",3);
                ?>
              </td>
            </tr>        
            
            <tr>
              <td nowrap="nowrap" class='tamanho-primeira-col'>
              <b>Tipo de Compra:</b>
              </td>
              <td nowrap="nowrap"> 
                <?php 
                  $sSqlTipoCompra = "select pc50_codcom, pc50_descr from pctipocompra order by pc50_codcom";
                  $rsTipoCompra   = db_query($sSqlTipoCompra);
                  db_selectrecord('tipoCompra',$rsTipoCompra, true, $db_opcao, '', '', '', '', '');  
                ?>
              </td>
            </tr>            
            
 
 
            <tr>
              <td nowrap="nowrap" class='tamanho-primeira-col'>
                <strong>Tipo de Licitação:</strong>
              </td>
              <td nowrap="nowrap"> 
                <?php
                  $sSqlTipoLicitacao = "select l03_codigo, l03_descr from cflicita order by l03_codigo";
                  $rsTipoLicitacao   = db_query($sSqlTipoLicitacao);
                  db_selectrecord('tipoLicitacao',$rsTipoLicitacao, true, $db_opcao, '', '', '', '', '');  
                ?>
              </td>
            </tr> 

            <tr>
              <td nowrap="nowrap" class='tamanho-primeira-col'>
                <strong>Licitação</strong>
              </td>
              <td nowrap="nowrap"> 
              <?php 
                  db_input('Licitação', 10, '', true, 'text', 1);
                ?>
              </td>
            </tr>             
            
            
 
            <tr>
              <td nowrap="nowrap" class='tamanho-primeira-col'>
                <b>Tipo de Empenho:</b>
              </td>
              <td nowrap="nowrap"> 
                <?php 
                  $sSqlTipoEmpenho = "select e41_codtipo, e41_descr from emptipo order by e41_codtipo";
                  $rsTipoEmpenho   = db_query($sSqlTipoEmpenho);
                  db_selectrecord('empenho',$rsTipoEmpenho, true, $db_opcao, '', '', '', '', '');  
                ?>
              </td>
            </tr>  
 
 
                
            <tr>
              <td nowrap="nowrap" class='tamanho-primeira-col' title="">
                <b>Histórico:</b>
              </td>
              <td>
                <?php 
                  
                  $sSqlHistorico = "select c50_codhist, c50_descr from conhist order by c50_codhist";
                  $rsHistorico   = db_query($sSqlHistorico);
                  db_selectrecord('historico',$rsHistorico, true, $db_opcao, '', '', '', '', '');
                ?>
              </td>
            </tr>

  
  
            <tr>
              <td nowrap="nowrap" class='tamanho-primeira-col' title="">
                <b>Evento:</b>
              </td>
              <td>
                <?php 
                  $sSqlEvento = "select e44_tipo, e44_descr from empprestatip order by e44_tipo";
                  $rsEvento   = db_query($sSqlEvento);
                  db_selectrecord('evento',$rsEvento, true, $db_opcao, '', '', '', '', '');  
                ?>
              </td>
            </tr>  
  
 
            <tr>
              <td nowrap="nowrap" class='tamanho-primeira-col' title="">
                <b>Desdobramento:</b> 
              </td>
              <td>
                <?php 
                  db_input('iDesdobramento', 10, '', true, 'text', 3);
                  db_input('o56_descr'     , 56, '', true, 'text', 3);
                  db_input('c36_anousu'    , 10, '', true, 'hidden', 3);
                  db_input('iDesdobramentoElemento', 10, '', true, 'hidden', 3);
                ?>
              </td>
            </tr>   
 
  
            <tr>
              <td nowrap="nowrap" class='tamanho-primeira-col' title="">
              <b>Destino:</b>
              </td>
              <td>
                <?php 
                  db_input('sDestino',   69, '', true, 'text', $db_opcao);    
                ?>
              </td>
            </tr>  
 
 
    
            <tr>
              <td nowrap="nowrap" class='tamanho-primeira-col' title="">
                <b>CP / CA</b>
              </td>
              <td>
                <?php 
                  $sSqlPeculiar = "select c58_sequencial, c58_descr from concarpeculiar order by c58_sequencial";
                  $rsPeculiar   = db_query($sSqlPeculiar);
                  db_selectrecord('peculiar',$rsPeculiar, true, $db_opcao, '', '', '', '', '');  
                ?>
              </td>
            </tr>    
    
    
            <tr>
              <td nowrap="nowrap" class='tamanho-primeira-col' title="">
                <strong>Liquidar:</strong> 
              </td>
              <td>
                <?php 
                  $aLiquidar = array('0' => 'NÃO' , '1' => 'SIM');
                  db_select('lLiquidar', $aLiquidar, true, $db_opcao, "onchange='js_mostraLiquidar();'");    
                ?>
              </td>
            </tr>  
            

            <tr id='ctmLiquidar' style="display: none;">
              <td nowrap="nowrap" class='tamanho-primeira-col'>
                <strong>Número da Nota:</strong>
              </td>
              <td nowrap="nowrap"> 
                <?php
                  db_input('numeroNota',   10, '', true, 'text', $db_opcao);
                  
                  
                  
                  echo "&nbsp;&nbsp;&nbsp;<b>Data da Nota:</b>";
                  //db_input('Licitação', 15, '', true, 'text', 3);
                  db_inputdata('dataNota', null, null, null, true, null, $db_opcao);
                ?>
              </td>
            </tr>             
            
            <tr id='ctmOrdemPagamento' style="display:none;">
              <td colspan="4">
                <fieldset style="margin-top: 10px;"> 
                  <legend><b>Informações da Ordem de Pagamento</b></legend>
                  <?php 
                    db_textarea("infoPagamento", 4, 80, "", true, 'text', 1); 
                  ?>
                </fieldset>
              </td>
            </tr>           
            
            
            <tr>
              <td colspan="2">
                <fieldset style="margin-top: 10px;"> 
                  <legend><b>Resumo</b></legend>
                  <?php 
                    db_textarea("resumo", 4, 80, "", true, 'text', $db_opcao); 
                  ?>
                </fieldset>
              </td>
            </tr>
            

            
          </table>
          
      </fieldset>
      
      
      <div style="margin-top: 10px;">
        <input type="button" name="empenhar" id="empenhar" value='Empenhar' onclick="js_gerarEmpenhoPassivo(false);"/>
      
        <input type="button" name="empenharimprimir" id="empenharimprimir" value='Empenhar e Imprimir' 
               onclick="js_gerarEmpenhoPassivo(true);"/>
             
      </div>
  </center>
</form>
  <?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<script type="text/javascript">

var sUrlRpc    = 'con4_empenhopassivo.RPC.php';
var aItens     = new Array();
var oParam     = new Object();


/**
 * função para gerar o empenho
 */
function js_gerarEmpenhoPassivo(lImprimir) {

  var iInscricaoPassivo        = $F('c36_sequencial');
  var iFavorecido              = $F('numcgm');
  var iDotacao                 = $F('o58_coddot');
  var iTipoCompra              = $F('tipoCompra');
  var iTipoLicitacao           = $F('tipoLicitacao');
  var iLicitacao               = $F('Licitação');
  var iTipoEmpenho             = $F('empenho');
  var iHistorico               = $F('historico');
  var iEvento                  = $F('evento');
  var iCodEle                  = $F('iDesdobramento');
  var sDestino                 = $F('sDestino');
  var iCaracteristicaPeculiar  = $F('peculiar');
  var sResumo                  = $F('resumo');
  var lLiquidar                = $F('lLiquidar');
  var nValorDotacao            = $F('valorDotacao');
  // se escolher liquidar      
  var iNota                    = $F('numeroNota');
  var dNota                    = $F('dataNota');
  var sInfoPagamento           = $F('infoPagamento');

  if (lLiquidar == 1) {

    if (iNota == "") {

      alert("Informe o número da nota.");
      return false;
    }
    if (dNota == "") {

      alert("Informe a data da nota.");
      return false;
    }
  } 

  
  oParam.exec                    = "gerarEmpenhoPassivo"    ;
  oParam.iInscricaoPassivo       = iInscricaoPassivo;
  oParam.iFavorecido             = iFavorecido              ;
  oParam.iDotacao                = iDotacao                 ;
  oParam.nValorDotacao           = nValorDotacao            ;
  oParam.iTipoCompra             = iTipoCompra              ;
  oParam.iTipoLicitacao          = iTipoLicitacao           ;
  oParam.iLicitacao              = iLicitacao               ;
  oParam.iTipoEmpenho            = iTipoEmpenho             ;
  oParam.iHistorico              = iHistorico               ;
  oParam.iEvento                 = iEvento                  ;
  oParam.iCodEle                 = iCodEle                  ;
  oParam.sDestino                = sDestino                 ;
  oParam.iCaracteristicaPeculiar = iCaracteristicaPeculiar  ;
  oParam.sResumo                 = sResumo                  ;
  oParam.lLiquidar               = lLiquidar                ;
  oParam.iNota                   = iNota         ;
  oParam.dNota                   = dNota         ;
  oParam.sInfoPagamento          = sInfoPagamento;
  oParam.lImprimir               = lImprimir;
  
  

  js_divCarregando('Aguarde...','msgBox');
  var oAjax   = new Ajax.Request (sUrlRpc,{
                                         method     : 'post',
                                         parameters : 'json='+Object.toJSON(oParam),
                                         onComplete : js_retornoGeraEmpenhoPassivo
                                        }
                                    );

  
}
function js_retornoGeraEmpenhoPassivo(oJson) {

  js_removeObj("msgBox");  
  var oRetorno = eval("("+oJson.responseText+")");
  alert(oRetorno.sMessage.urlDecode());

  if (oRetorno.iStatus == 1) {

    if (oRetorno.lImprimir == true) {
      var jan = window.open("emp2_emitenotaemp002.php?e60_numemp="+oRetorno.iNumeroEmpenho,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    }
    js_voltar();
  }
}


function js_voltar() {
  location.href = 'con4_baixaempenhosemorcamento001.php';
}




// retorna dados da inscrição selecionada
function js_getDadosInscricao() {

  var iInscricao    = $F('c36_sequencial');
  oParam.exec       = "getDadosInscricao";
  oParam.iInscricao = iInscricao;

  js_divCarregando('Aguarde...','msgBox');
  var oAjax   = new Ajax.Request (sUrlRpc,{
                                         method:'post',
                                         parameters:'json='+Object.toJSON(oParam),
                                         onComplete: js_retornoBuscaInscricao
                                        }
                                    );
}

function js_retornoBuscaInscricao(oJson) {

  js_removeObj("msgBox");  
  var oRetorno = eval("("+oJson.responseText+")");

  if (oRetorno.iStatus == 2) {

    alert(oRetorno.sMessage.urlDecode());
    window.location = 'con4_baixaempenhosemorcamento001.php';
  }

  $('iDesdobramento').value         = oRetorno.iCodigoElemento;
  $('iDesdobramentoElemento').value = oRetorno.iElemento;
  $('numcgm').value                 = oRetorno.iCodigoFavorecido;
  $('favorecido').value             = oRetorno.sNomeFavorecido;
  $('o56_descr').value              = oRetorno.sDescricaoElemento;
  $('c36_anousu').value             = oRetorno.iAnoDesdobramento;
  $('nValorTotalInscricao').value   = oRetorno.nValorTotalInscricao;
}

/*
 * função para verificar se a dotação selecionada possui saldo 
 */
function js_verificaSaldoDotacao() {

  var oParam        = new Object();  
  oParam.exec       = "getSaldoDotacao";
  oParam.iDotacao   = $F('o58_coddot');

  js_divCarregando('Aguarde...','msgBox');
  var oAjax   = new Ajax.Request (sUrlRpc,{
                                         method:'post',
                                         parameters:'json='+Object.toJSON(oParam),
                                         onComplete:js_retornoSaldoDotacao
                                        }
                                    );  
  
}

function js_retornoSaldoDotacao(oJson) {

  js_removeObj("msgBox");  
  var oRetorno = eval("("+oJson.responseText+")");

  if (new Number($F('nValorTotalInscricao')) > new Number(oRetorno.iSaldoDotacao)) {

    alert("A dotação selecionada não possui saldo suficiente para a empenhar a inscrição.");
    return false;
  }
  $('valorDotacao').value = oRetorno.iSaldoDotacao;
}

function js_mostraLiquidar(){

  var lLiquidar = $F('lLiquidar');

  if (lLiquidar == "1") {

    $("ctmLiquidar").style.display = '';
    $("ctmOrdemPagamento").style.display = '';
  } else {
    $("ctmLiquidar").style.display = "none";
    $("ctmOrdemPagamento").style.display = "none";
  }  
  
}


//======================================= pesquisa de dotacao

function js_pesquisa_dotacao(mostra){

  var iElemento  = $F('iDesdobramentoElemento');
  var iAnoUsu    = $F('c36_anousu');
  var iInscricao = $F('c36_sequencial');
  
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_permorcdotacao.php?iInscricao='+iInscricao+'&elemento='+iElemento+'&iAnoUsu='+iAnoUsu+'&funcao_js=parent.js_mostradotacao1|o58_coddot|o50_estrutdespesa','Pesquisa',true);
  }else{
     if(document.form1.o58_coddot.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_permorcdotacao.php?iInscricao='+iInscricao+'&elemento='+iElemento+'&iAnoUsu='+iAnoUsu+'&pesquisa_chave='+$F('o58_coddot')+'&funcao_js=parent.js_mostradotacao','Pesquisa',false);
     }else{
       document.form1.o40_descr.value = ''; 
     }
  }
}
function js_mostradotacao(chave,erro){
  
  document.form1.o40_descr.value = chave; 
  if(erro==true){ 
    document.form1.o58_coddot.focus(); 
    document.form1.o58_coddot.value = ''; 
  }
}
function js_mostradotacao1(chave1, chave2){

  $('o58_coddot')       .value = chave1;
  $('estruturalDotacao').value = chave2;
  db_iframe_orcdotacao.hide();

  js_verificaSaldoDotacao();
}

/**
  * Deixamos o select "liquidar" com valor sim e chamamos a função para mostrar os outros campos necessários quando é liquidação
  */
var iLiquidar = $("lLiquidar");
iLiquidar.options[1].selected = true;
js_mostraLiquidar();
</script>