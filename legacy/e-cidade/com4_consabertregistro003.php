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

require ("libs/db_stdlib.php");
require ("libs/db_utils.php");
require ("libs/db_app.utils.php");
require ("std/db_stdClass.php");
require ("libs/db_conecta.php");
require ("libs/db_sessoes.php");
require ("libs/db_usuariosonline.php"); 
require ("dbforms/db_funcoes.php");
require ("dbforms/verticalTab.widget.php");
require_once ("model/aberturaRegistroPreco.model.php");

$clrotulo = new rotulocampo;
$db_opcao = 3;
$clrotulo->label("pc10_numero");
$clrotulo->label("pc10_depto");
$clrotulo->label("descrdepto");
$clrotulo->label("pc67_motivo");

$oGet = db_utils::postMemory($_GET);

$clAbertRegPreco = new aberturaRegistroPreco($oGet->pc10_numero);  

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<?
db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js, widgets/windowAux.widget.js,
             classes/infoLancamentoContabil.classe.js,dbmessageBoard.widget.js");
db_app::load("estilos.css, grid.style.css,tab.style.css");
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<style>

 .fora {background-color: #d1f07c;}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
  <center>
  <table width="100%">
  <tr>
  <td>
  <fieldset><legend><b>Dados da Abertura Registro Preço</b></legend>
    <table width="780">
      <tr>
        <td width="200">
          <b>Abertura de Registro de preço:</b>
        </td>
        <td style="background-color:#FFFFFF;color: #000000">  
          <?=$clAbertRegPreco->getCodigoSolicitacao(); ?>
        </td>
        <td width="120" style="text-align: right;">
          <b>Vigência de:</b>
        </td>
        <td width="" style="background-color:#FFFFFF;color: #000000">  
          <?=db_formatar($clAbertRegPreco->getDataInicio(),'d'); ?>
        </td>
        <td width="20" style="text-align: center;">
          <b>A</b>
        </td>
        <td style="background-color:#FFFFFF;color: #000000">  
          <?=db_formatar($clAbertRegPreco->getDataTermino(),'d'); ?>
        </td>
      </tr>
      <tr>
        <td width="200">
          <b>Departamento:</b>
        </td>
        <td colspan="5" style="background-color:#FFFFFF;color: #000000">  
          <?=$clAbertRegPreco->getDescricaoDepartamento(); ?>          
        </td>
      </tr>
      <tr>
        <td width="200">
          <b>Data Inclusão:</b>
        </td>
        <td style="background-color:#FFFFFF;color: #000000">  
          <?=db_formatar($clAbertRegPreco->getDataInicio(),'d'); ?>
        </td>
        <td width="120" style="text-align: right;">
          <b>data da anulação:</b>
        </td>
        <td width="" style="background-color:#FFFFFF;color: #000000">  
          <?=db_formatar($clAbertRegPreco->getDataAnulacao(),'d'); ?>
        </td>
        <td width="20" style="text-align: center;">
          &nbsp;
        </td>
        <td >  
          &nbsp;
        </td>
      </tr>
      <tr>
        <td width="200">
          <b>Forma de Controle:</b>
        </td>
        <td style="background-color:#FFFFFF;color: #000000">
          <?=$clAbertRegPreco->getFormaDeControle() == aberturaRegistroPreco::CONTROLA_VALOR ?" Por Valor":"Por Quantidade";?>
        </td>
      </tr>
      <tr>
        <td width="200">
          <b>Resumo:</b>
        </td>
        <td colspan="5" style="background-color:#FFFFFF;color: #000000">  
          <?=$clAbertRegPreco->getResumo(); ?>
        </td>
      </tr>     
    </table>
 </fieldset>
 </td>
 </tr>
 </table>
 <fieldset>
    <?
    $oTabDetalhes = new verticalTab("detalhesemp",300);
    $oTabDetalhes->add("estimativas", "Estimativas",
                       "com4_consabertregistodetalhes001.php?pc10_numero={$oGet->pc10_numero}&exec=estimativa");
    $oTabDetalhes->add("compilacao" , "Compilação" ,
                       "com4_consabertregistodetalhes001.php?pc10_numero={$oGet->pc10_numero}&exec=compilacao");
    $oTabDetalhes->add("compilacao" , "Ítens" ,
                       "com4_consabertregistodetalhes001.php?pc10_numero={$oGet->pc10_numero}&exec=itens");
        
    $oTabDetalhes->show();
    ?>
    
    </fieldset>
</center>
<div style='position:absolute;top: 200px; left:15px;
            border:1px solid black;
            text-align: left;
            padding:3px;
            background-color: #FFFFCC;
            display:none;' id='ajudaItem'>

</div>
</body>
</html>

<script>

var sUrlRC                      = 'com4_solicitacaoComprasRegistroPreco.RPC.php';
var iFormaControleRegistroPreco = '<?=$clAbertRegPreco->getFormaDeControle()?>';
function js_completaPesquisa(iSolicitacao) {

   var oParam          = new Object();
   oParam.exec         = "pesquisarAbertura";
   oParam.iSolicitacao = iSolicitacao;
   oParam.tipo         = 6;
   db_iframe_estimativaregistropreco.hide();
   var oAjax           = new Ajax.Request(sUrlRC,
                                         {
                                          method: "post",
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: js_retornoCompletaPesquisa
                                         });
}

function js_retornoCompletaPesquisa(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
  
    $('pc54_datainicio').value  = oRetorno.datainicio;
    $('pc54_datatermino').value = oRetorno.datatermino;
    $('pc10_resumo').value      = oRetorno.resumo.urlDecode();
    $('pc10_numero').value      = oRetorno.solicitacao;
    $('pc54_solicita').value    = oRetorno.codigoabertura;
    js_preencheGrid(oRetorno.itens);
    $('btnProcessar').disabled = false;
    
  } else {
	  
	  if(oRetorno.message != ''){
	  
    	alert(oRetorno.message.urlDecode());
	  }
  }
  
}

function js_imprimeEstimativa ( iSolicita ){
    var oJanela = window.open('com2_relatorioitensestimativaregistrodepreco002.php?pc10_numero='+iSolicita,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    oJanela.moveTo(0,0);
}

function js_showItens(iTipo, iSolicita, sDepartamento) {

	if ($('wndAuxiliar')) {
    windowAuxiliar.destroy();
  }
  var sTituloJanela = 'Itens';
  switch (iTipo) {
    case 1: 
      sTituloJanela += " estimados pelo departamento "+sDepartamento.urlDecode();
      break;
  }

  var sLarguraJanela = screen.width / 1.3;
  windowAuxiliar = new windowAux('wndAuxiliar', sTituloJanela, sLarguraJanela, 500);

  var sConteudo  = "<fieldset>                                                                                                                           ";
	  	sConteudo += "  <div id='itens'></div>                                                                                                             ";
	  	sConteudo += "</fieldset>                                                                                                                         ";
	  	sConteudo += "<fieldset>                                                                                                                          ";
	  	sConteudo += " 	<center>                                                                                                                           ";
	  	sConteudo += " 		<input name='imprimir' value='Imprimir' id='imprimir' type='button' onclick='js_imprimeEstimativa("+iSolicita+"); return false;'>";
	  	sConteudo += " 	</center>                                                                                                                          ";
	  	sConteudo += "</fieldset>                                                                                                                         ";
	  							 
  windowAuxiliar.setContent(sConteudo);
		  
  windowAuxiliar.setShutDownFunction(function() {
     windowAuxiliar.destroy();
  });

  var sMsgHelpItens = "Itens lançados para a estimativa "+iSolicita+".";
  oMessagaBoard = new DBMessageBoard ('sMessageBoard', sTituloJanela, sMsgHelpItens, windowAuxiliar.getContentContainer());
  oMessagaBoard.show();
  oGrvDetalhes = new DBGrid('detalhes');
  oGrvDetalhes.nameInstance = 'oGrvDetalhes';
  oGrvDetalhes.setCellWidth(new Array("3%","10%","20%",'10%', "10%","10%",'10%','10%','10%','10%', "10%"));
  oGrvDetalhes.setCellAlign(new Array('right',
                                      'center',
                                      'left',
                                      'right',
                                      'right',
                                      'right',
                                      'right',
                                      'right',
                                      'right',
                                      'right',
                                      'right'
                                      ));
                                          
      oGrvDetalhes.setHeader(new Array('Seq',
                                       'Código',
                                       'Descricao',
                                       'Unidade',
                                       'Quantidade',
                                       'Cedida',
                                       'Recebida',
                                       'Solicitada',
                                       'Empenhada',
                                       'Exec.',
                                       'Saldo',
                                       'CodItemSol'
                                      ));
      
			oGrvDetalhes.aHeaders[11].lDisplayed = false;
      oGrvDetalhes.setHeight(230);
      oGrvDetalhes.show($('itens'));
      oGrvDetalhes.clearAll(true);
      oGrvDetalhes.renderRows();
      getItensTipo(iTipo, iSolicita);
      windowAuxiliar.show();
}

function getItensTipo(iTipo, iSolicita) {

   js_divCarregando('Aguarde, carregando itens...', 'msgBox');
   var oParam           = new Object();
   oParam.exec         = "consAberturaDetalhes";
   oParam.pc10_numero  = iSolicita;
   oParam.detalhe      = 'getItensEstimativa';
   var oAjax           = new Ajax.Request(sUrlRC,
                                         {
                                          method: "post",
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: js_retornoCompletaPesquisaItens
                                         });
}

function js_retornoCompletaPesquisaItens(oAjax) {
    
    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    oGrvDetalhes.clearAll(true);
    if (oRetorno.dados != false) {
       
      var iNumDados = oRetorno.dados.length;  
      if (iNumDados > 0) {
        
        oRetorno.dados.each( 
          function (oDado, iInd) {
          
            var aRow = new Array();
            aRow[0] = iInd+1;
            aRow[1] = oDado.codigo;
            aRow[2] = oDado.material.urlDecode();
            aRow[3] = oDado.unidade;
            aRow[4] = oDado.quantidade;

            /**
             * Caso o departamento tenha cedido itens a outro departamento, será mostrado um link
             * que abrirá uma window contendo os registros da cedência
             */
            if (oDado.iQtdCedida == 0) {
              aRow[5] = oDado.iQtdCedida;
            } else {
                
            	aRow[5]  = "<a href='#' onclick='js_showItensCedidosRecebidos("+oDado.iCodItemSol+", \""+oDado.material+"\", 1)'>";
            	aRow[5] += oDado.iQtdCedida+"</a>";
            }

            if (oDado.iQtdRecebida == 0) {
            	aRow[6] = oDado.iQtdRecebida;
            } else {
                
            	aRow[6]  = "<a href='#' onclick='js_showItensCedidosRecebidos("+oDado.iCodItemSol+", \""+oDado.material+"\", 2)'>";
            	aRow[6] += oDado.iQtdRecebida+"</a>";
            }
            
            aRow[7]  = oDado.iQtdSolicitada;
            aRow[8]  = oDado.iQtdEmpenhada;
            aRow[9]  = oDado.iQtdeExecedido;
            aRow[10] = oDado.iQtdSaldo;
            aRow[11] = oDado.iCodItemSol;

            oGrvDetalhes.addRow(aRow);
            oGrvDetalhes.aRows[iInd].aCells[0].sStyle +="background-color:#DED5CB;font-weight:bold;padding:1px";
            var sHtmlTxtAjuda = "<b>Descrição: </b>"+oDado.material.urlDecode()+"<br>";
            sHtmlTxtAjuda += "<b>Resumo: </b>"+oDado.resumo.urlDecode();
            oGrvDetalhes.aRows[iInd].aCells[2].sEvents  = "onmouseover='js_setAjuda(\""+encodeURIComponent(sHtmlTxtAjuda)+"\",true)'";
            oGrvDetalhes.aRows[iInd].aCells[2].sEvents += "onmouseOut='js_setAjuda(null,false)'";
          }     
        );
        oGrvDetalhes.renderRows();
      }
    }
}

/**
 * Monta uma WindowAux e uma Grid para mostrar os itens de uma compilação
 */
function js_showItensCompilacao(iCompilacao) {

  if ($('wndCompilacao')) {
    windowCompilacao.destroy();
  }
  var sLarguraJanela = screen.availWidth / 1.3;
  windowCompilacao   = new windowAux('wndCompilacao', 'Itens da Compilação', sLarguraJanela, 400);
  windowCompilacao.setContent("<fieldset><div  id='griditens'></div></fieldset>");
  windowCompilacao.setShutDownFunction(function() {
     windowCompilacao.destroy();
  });

  var aHeader = ["Seq",
                 "Codigo",
                 "Descrição",
                 "Vlr Uni.",
                 "Fornecedor",
                 "Unidade",
                 "Qtd. Min.",
                 "Qtd. Max.",
                 "Ativo"
               ];

  if (iFormaControleRegistroPreco == 2) {

    aHeader[3] = 'Desc.(%)';
    aHeader[6] = 'Vlr Sol.';
  }
  oMessagaBoard = new DBMessageBoard ('sMessageBoard2', "Itens da Compilação "+iCompilacao, 'Itens lançados',
                                      $('windowwndCompilacao_content'));
  oMessagaBoard.show();
  oGridItens 					    = new DBGrid('gridItens');
  oGridItens.nameInstance = "oGridItens";
  oGridItens.setHeight(200);
  oGridItens.setCellAlign(new Array("right","right","Left","right","left","right","right", "right"));
  oGridItens.setCellWidth(new Array("4%","10%","45%",'10%', "30%","5%",'16%','16%','16%'));
  oGridItens.setHeader(aHeader);
  if (iFormaControleRegistroPreco == 2) {
    oGridItens.aHeaders[7].lDisplayed = false;
  }
  oGridItens.show($('griditens'));
  js_getItensCompilacao(iCompilacao); 
  windowCompilacao.show();
}

/**
 * Utiliza o AJAX para resgatar os itens de uma compilação
 */
function js_getItensCompilacao(iCompilacao) {
  
   js_divCarregando('Aguarde, carregando itens...', 'msgBox');
   var oParam           = new Object();
   oParam.exec         = "consAberturaDetalhes";
   oParam.pc10_numero  = iCompilacao;
   oParam.detalhe      = 'getItensCompilacao';
   var oAjax           = new Ajax.Request(sUrlRC,
                                         {
                                          method: "post",
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: js_preencheGridCompilacao
                                         });
}

/**
 * Preenche a grid dos itens com o retorno do ajax
 */
function js_preencheGridCompilacao(oAjax) {
  
  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  oGridItens.clearAll(true);
  for(var i = 0; i < oRetorno.dados.length; i++) {

    var oItem = oRetorno.dados[i];

    if (iFormaControleRegistroPreco == 2) {

      oItem.valorunitario = oItem.percentual;
      oItem.quantmin      = js_formatar(oItem.valortotal, 'f');
    }
    var aLinha = new Array();
    aLinha[0]  = i+1;
    aLinha[1]  = oItem.codigo;
    aLinha[2]  = oItem.material.urlDecode();
    aLinha[3]  = js_formatar(oItem.valorunitario,"f");
    aLinha[4]  = oItem.fornecedor.urlDecode();
    aLinha[5]  = oItem.unidade.urlDecode();
    aLinha[6]  = oItem.quantmin;
    aLinha[7]  = oItem.quantmax;
    aLinha[8]  = oItem.ativo?"Sim":"Não";
    oGridItens.addRow(aLinha);
    if (!oItem.automatico) {
       oGridItens.aRows[i].setClassName("fora");
    }
    oGridItens.aRows[i].aCells[0].sStyle +="background-color:#DED5CB;font-weight:bold;padding:1px;";
    for (iCell = 0; iCell < aLinha.length; iCell++) {
      oGridItens.aRows[i].aCells[iCell].sStyle += ";padding: 1px;";
    }
  }
  oGridItens.renderRows();
}

function js_showItensCedidosRecebidos(iCodItemCedido, sDescrItem, iTipoBusca) {

	var sTitleMsgBoard = "Item: "+sDescrItem.urlDecode();
	var sHelpMsgBoad   = "";
	if (iTipoBusca == 1) {
		sHelpMsgBoad = "Departamentos que receberam os itens.";
	} else {
		sHelpMsgBoad = "Departamentos que cederam os itens.";
	}

	var sHtmlItensCedidos = "<fieldset><div id='ctnDadosItensCedidosRecebidos'></div></fieldset>";
	oWinItensCedidos = new windowAux('winItensCede_'+iCodItemCedido, sTitleMsgBoard, 800, 400);
	oWinItensCedidos.setContent(sHtmlItensCedidos);
	oWinItensCedidos.setShutDownFunction(function() {
		oWinItensCedidos.destroy();
  });

  oMsgBoardCede = new DBMessageBoard ('msgBoardCede_'+iCodItemCedido, sTitleMsgBoard, sHelpMsgBoad,
		  																oWinItensCedidos.getContentContainer());
  oMsgBoardCede.show();
  oWinItensCedidos.setChildOf(windowAuxiliar);
  oWinItensCedidos.show(30, 200);
	createDivModal(oWinItensCedidos.getContentContainer());


	oGridItensDepart = new DBGrid('ctnDadosItensCedidosRecebidos');
	oGridItensDepart.nameInstance = "oGridItensDepart";
	oGridItensDepart.setHeight(200);
	oGridItensDepart.setCellAlign(new Array("center", "right", "left", "right", "center", "left"));
	oGridItensDepart.setCellWidth(new Array("10%", "10%","30%","10%","10%","30%"));
	oGridItensDepart.setHeader(new Array("Cedência","Depart.","Descrição","Qtd.", "Data", "Resumo"));
	oGridItensDepart.show($('ctnDadosItensCedidosRecebidos'));
	
	js_getItensCedidosRecebidos(iCodItemCedido, iTipoBusca);
}

function js_getItensCedidosRecebidos(iCodItemCedido, iTipo) {

	js_divCarregando("Aguarde, pesquisando...", "msgBox");
	var oParam = new Object();
	
	if (iTipo == 1) {
  	oParam.exec = "getItensCedidos";
	} else {
		oParam.exec = "getItensRecebidos";
	}
	oParam.iCodItemSol = iCodItemCedido;
	
  var oAjax = new Ajax.Request(sUrlRC,
           											{
            											method: "post",
            											parameters:'json='+Object.toJSON(oParam),
            											onComplete: js_preencheItensCedidosRecebidos
           											});
}

function js_preencheItensCedidosRecebidos(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.aDados.length == 0) {

		alert("Nenhum registro encontrado.");
		return false;
  }

  oGridItensDepart.clearAll(true);
  oRetorno.aDados.each (
	  function(oDadosDepart, iIdLinha) {

		  var aLinha = new Array();
		  aLinha[0] = oDadosDepart.codigocedencia;
		  aLinha[1] = oDadosDepart.coddpto;
		  aLinha[2] = oDadosDepart.descrdepto.urlDecode();
		  aLinha[3] = oDadosDepart.quantidade;
		  aLinha[4] = js_formatar(oDadosDepart.datacedencia, 'd');
		  aLinha[5] = oDadosDepart.resumo.urlDecode();

		  oGridItensDepart.addRow(aLinha);
	});
  oGridItensDepart.renderRows();
}



createDivModal = function (oNode) {
	   
	   var oDiv          = document.createElement('div');
	   oDiv.id           = "modalfor"+oNode.id;
	   oDiv.style.width  = oNode.clientWidth;
	   oDiv.style.height = oNode.clientHeight-5;
	   oDiv.style.height = oNode.clientHeight-5;
	   oDiv.style.backgroundImage  = "url(imagens/transparencia.png)";
	   oDiv.style.backgroundRepeat = "repeat";
	   oDiv.style.position         = 'absolute';
	   oDiv.style.top              = '25px';
	   oDiv.style.left             = '2px';
	   oDiv.style.zIndex           = oNode.style.zIndex + 1;
	   oNode.appendChild(oDiv);
	   oNode.setAttribute('modal', oDiv.id);
	   
	   
	}

destroyDivModal = function (oNode) {

  if (oNode.getAttribute('modal')) {
    
    var oDiv = $(oNode.getAttribute('modal'));
    oDiv.parentNode.removeChild(oDiv);
    oNode.setAttribute('modal',''); 
  }
}

function js_setAjuda(sTexto, lShow) {

	  if (lShow) {
	  
	    el =  $('itens');
	    var x = 0;
	    var y = el.offsetHeight;
	    while (el.offsetParent && el.tagName.toUpperCase() != 'BODY') {

	     x += el.offsetLeft;
	     y += el.offsetTop;
	     el = el.offsetParent;

	   }
	   x += el.offsetLeft;
	   y += el.offsetTop;
	   $('ajudaItem').innerHTML     = decodeURIComponent(sTexto);
	   $('ajudaItem').style.display = '';
	   $('ajudaItem').style.top     = y+10;
	   $('ajudaItem').style.left    = x;
	   $('ajudaItem').style.zIndex  = 100000;
	   
	  } else {
	   $('ajudaItem').style.display = 'none';
	  }
	}

</script>