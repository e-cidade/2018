<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  
  <?php 
  	db_app::load("scripts.js, prototype.js, datagrid.widget.js, strings.js, grid.style.css");
  	db_app::load("estilos.css, widgets/windowAux.widget.js, dbmessageBoard.widget.js, dbtextField.widget.js");
  ?>
  
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="margin-top:25px;" onload="js_init();">

<center>
    <div style="width: 820px" id='ctnMessageCompilacao'>
    </div> 
  	<fieldset style="width: 800px">
		<legend><b>Compilação</b></legend>
		<div id="ctnGridCompilacao">
		</div>
	</fieldset>
	<span style='font-weight:bold'>
	</span>  
</center>
<div style='position:absolute;top: 200px; left:15px;
            border:1px solid black;
            text-align: left;
            padding:3px;
            background-color: #FFFFCC;
            display:none;' id='ajudaItem'>

</div>
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>


<script>
  
  var sMsgTitle = "Registros de Preço que o Departamento Participa "
  var sMsgHelp  = "Duplo Clique sob o Registro de Preço para escolher o departamento que deseja ceder materiais.";
  //oMsgBoardDepart = new DBMessageBoard("msgBoard_comp", sMsgTitle, sMsgHelp, $('ctnMessageCompilacao'));
  //oMsgBoardDepart.show();
	var sUrlRPC             = "com4_cedenciamateriais.RPC.php";
  var iCompilacao         = 0;
  var iEstimativaRecebe   = 0;
  var iEstimativaCedente  = 0;
  var iDepartamentoRecebe = 0;
  var lWindowCompilacoes  = false;
	/**
	 *  Função JS executada no momento em que a página é aberta
	 */
	function js_init() {
		
		oGridCompilacao = new DBGrid('ctnGridCompilacao');
		oGridCompilacao.nameInstance = "oGridCompilacao";
		oGridCompilacao.setHeight(250);
		oGridCompilacao.setCellAlign(new Array("center", "center"));
		oGridCompilacao.setCellWidth(new Array("10%","10%","80%"));
		oGridCompilacao.setHeader(new Array("Compilação","Licitação","Resumo"));
		oGridCompilacao.show($('ctnGridCompilacao'));
		oGridCompilacao.setStatus('*Duplo Clique sob o Registro de Preço para escolher o departamento que deseja ceder materiais.');
		js_getCompilacao();
	}

	/**
	 *  Busca os dados de compilações existentes no sistema
	 */
	function js_getCompilacao() {
    
    js_divCarregando('Aguarde, pesquisando.', 'msgBox');
		var oParam  = new Object();
		oParam.exec = "getCompilacao";
    var oAjax   = new Ajax.Request(sUrlRPC,
                                   {
                                    method: "post",
                                    parameters:'json='+Object.toJSON(oParam),
                                    onComplete: js_preencheGridCompilacao
                                   });
	}

	/**
	 * Função que preenche a grid com os dados da compilação
	 */
	function js_preencheGridCompilacao(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");
    oGridCompilacao.clearAll(true);

    if (oRetorno.status == 2) {
			alert(oRetorno.message.urlDecode());
    } else {

			if (oRetorno.aCompilacao.length == 0) {
				alert("Nenhuma compilação encontrada.");
				return false;
			}

			oRetorno.aCompilacao.each(
			  function(oDado, iIdLinha) {

			  var aLinha = new Array();
	          aLinha[0]  = oDado.pc10_numero;                 
	          aLinha[1]  = oDado.l20_codigo;                 
		        aLinha[2]  = oDado.pc10_resumo.urlDecode().substring(0,70);
	          oGridCompilacao.addRow(aLinha);
	          oGridCompilacao.aRows[iIdLinha].sEvents += "onDblClick='js_openCompilacao("+oDado.pc10_numero+")'";
			});
      oGridCompilacao.renderRows();
    }
	}

	/**
	 *  Abre a WindowAux contendo os departamentos da compilação
	 */
	function js_openCompilacao(iIdSolicita) {
    
    if (lWindowCompilacoes) {
      return false;
    }
	  iCompilacao      = iIdSolicita;  
		var sHtmlDepart  = "<fieldset>";
		sHtmlDepart     += "  <legend><b>Departamentos da Compilação<b></legend>";
		sHtmlDepart     += "  <div id='ctnDepartCompilacao'></div>";
		sHtmlDepart     += "</fieldset>";
    lWindowCompilacoes = true;
	  /**
	   *  Window Aux
	   */
		oWindowDepart = new windowAux("winId_"+iIdSolicita, "Departamentos Participantes", 900, 500);
		oWindowDepart.setContent(sHtmlDepart);
		oWindowDepart.allowCloseWithEsc(false);
    $("windowwinId_"+iIdSolicita+"_btnclose").onclick= function () {
     
      lWindowCompilacoes = false;
     	oWindowDepart.destroy();
    	
    }

	  /**
	   *  Message Board
	   */
		var sMsgTitle = "Departamentos da Compilação "+iIdSolicita;
		var sMsgHelp  = "Duplo Clique sob o departamento para escolher os itens que deseja ceder.";
		oMsgBoardDepart = new DBMessageBoard("msgBoard_"+iIdSolicita, sMsgTitle, sMsgHelp, oWindowDepart.getContentContainer());

		oMsgBoardDepart.show();
		oWindowDepart.show();

		/**
		 *  Monta grid com os departamentos da compilação
		 */
		oGridDepartamento = new DBGrid('ctnDepartCompilacao');
		oGridDepartamento.nameInstance = "oGridDepartamento";
		oGridDepartamento.setHeight(300);
		oGridDepartamento.setCellAlign(new Array("center", "left", "center"));
		oGridDepartamento.setCellWidth(new Array("10%","80%","10%"));
		oGridDepartamento.setHeader(new Array("Departamento","Descrição","Estimativa"));
		oGridDepartamento.show($('ctnDepartCompilacao'));
		js_getDepartamentos(iIdSolicita);
  }

	/**
	 *  Executa um AJAX buscando os dados do departamento e o código da estimativa
	 */
	function js_getDepartamentos(iCompilacao) {
		
    js_divCarregando('Aguarde, pesquisando.', 'msgBox');
		var oParam         = new Object();
		oParam.exec 			 = "getDepartamento";
		oParam.iCompilacao = iCompilacao;
		
    var oAjax   = new Ajax.Request(sUrlRPC,
                                   {
                                    method: "post",
                                    parameters:'json='+Object.toJSON(oParam),
                                    onComplete: js_preencheGridDepartamento
                                   });
	}

  /**
   *  Preenche a grid criada na função 'js_openCompilacao' com os dados retornados pela função 'js_getDepartamentos'
   */
	function js_preencheGridDepartamento(oAjax) {
		
    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.aEstimativa.length == 0) {
			alert("Nenhuma estimativa foi localizada");
			return false;
    }

    oGridDepartamento.clearAll(true);
		oRetorno.aEstimativa.each(
		  function(oEstimativa, iIdLinha) {

		  var aLinha = new Array();
          aLinha[0]  = oEstimativa.iDepartamento;                 
          aLinha[1]  = oEstimativa.sDescrDepartamento.urlDecode().substring(0,70);                 
	        aLinha[2]  = oEstimativa.iEstimativa;
	        oGridDepartamento.addRow(aLinha);
	    var sEvents = "onDblClick='js_openItensDepartamento("+oEstimativa.iDepartamento+",";
	        sEvents +="\""+oEstimativa.sDescrDepartamento+"\", "+oRetorno.iEstimativaDptoAtual+", "+oEstimativa.iEstimativa+")'";
	        oGridDepartamento.aRows[iIdLinha].sEvents += sEvents;
		});
		oGridDepartamento.renderRows();
	}

  /**
   * Abre uma window contendo os itens que podem ser cedidos para outros departamentos
   */
	function js_openItensDepartamento(iCodDepartamento, sDepartamento, iCodEstimativaCede, iEstimativaRecebe) {

	  iEstimativaCedente = iCodEstimativaCede;
	  
		var sHtmlItens  = "<fieldset>";
		sHtmlItens     += "  <legend><b>Itens</b></legend>";
		sHtmlItens     += "  <div id='ctnItensDepartamento'></div>";
		sHtmlItens     += "</fieldset>";
		sHtmlItens     += "<center>";
		sHtmlItens     += "<br><input type='button' id='btnSalvarItens' name='btnSalvarItens' value='Salvar' >";
		sHtmlItens     += "</center>";

	  /**
	   *  Window Aux
	   */
		oWindowItensDepart = new windowAux("winId_"+iCodDepartamento, "Itens do Departamento", 800, 400);
		oWindowItensDepart.setContent(sHtmlItens);
		oWindowItensDepart.allowCloseWithEsc(false);
		oWindowItensDepart.allowDrag(false);
    $("windowwinId_"+iCodDepartamento+"_btnclose").onclick= function () {
    
    	destroyDivModal(oWindowDepart.getContentContainer());
    	oWindowItensDepart.destroy();
    }
    
	  /**
	   *  Message Board
	   */
		var sMsgTitleItens  = "Itens disponíveis para o departamento "+sDepartamento.urlDecode();
		var sMsgHelpItens   = "Digite a quantidade de cada item que deseja ceder no campo <b>Qtde. ";
		sMsgHelpItens      += "Ceder</b>. Após concluir clique em salvar";
		oMsgBoardItensDepart = new DBMessageBoard("msgBoard_"+iCodDepartamento, 
		                                          sMsgTitleItens, 
		                                          sMsgHelpItens, 
		                                          oWindowItensDepart.getContentContainer()
		                                          );

		oMsgBoardItensDepart.show();
		oWindowItensDepart.setChildOf(oWindowDepart);
		oWindowItensDepart.show(30, 35);
		createDivModal(oWindowDepart.getContentContainer());
		
		$('btnSalvarItens').observe("click", js_confirmaCedencia);
		/**
		 * Monta a grid que receberá os itens do departamento selecionado
		 */
		oGridItensDepart = new DBGrid('ctnItensDepartamento');
		oGridItensDepart.nameInstance = "oGridItensDepart";
		oGridItensDepart.setHeight(200);
		oGridItensDepart.setCellAlign(new Array("right", "center", "left", "right", "right", "right"));
		oGridItensDepart.setCellWidth(new Array("5%", "10%","55%","10%","10%","10%"));
		oGridItensDepart.setHeader(new Array("Ordem","Material","Descrição","Qtd. Disp.", 
				                                 "Cedido", 
				                                 "Qtd. Ceder", 
				                                 "Item Doa",
				                                 "Item Recebe")
                                         );
    oGridItensDepart.aHeaders[6].lDisplayed = false;
    oGridItensDepart.aHeaders[7].lDisplayed = false;   
		oGridItensDepart.show($('ctnItensDepartamento'));
		js_getItensDepartamento(iCodDepartamento, iCodEstimativaCede, iEstimativaRecebe);
	}

  function js_getItensDepartamento(iCodDepartamento, iCodEstimativa, iCodigoEstimativaRecebe) {

	  iDepartamentoRecebe = iCodDepartamento;
    js_divCarregando('Aguarde, pesquisando.', 'msgBox');
		var oParam                   = new Object();
		oParam.exec 					       = "getItens";
		oParam.iCodDepartamento      = iCodDepartamento;
		oParam.iCodEstimativa        = iCodEstimativa;
		oParam.iCodEstimativaRecebe  = iCodigoEstimativaRecebe;
		
    var oAjax   = new Ajax.Request(sUrlRPC,
                                   {
                                    method: "post",
                                    parameters:'json='+Object.toJSON(oParam),
                                    onComplete: js_preencheGridItensDepartamento
                                   });
  }

	function js_preencheGridItensDepartamento(oAjax) {
		
    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");

		if (oRetorno.aItens.length == 0) {

			alert("Nenhum item encontrado.");
			return false;
		}

		
		oGridItensDepart.clearAll(true);
		oRetorno.aItens.each(
		  function(oItem, iIdLinha) {

			  var lDisabled = false;
		    if (oItem.iQtdSaldo == 0 || oItem.iItemRecebe == '') {
	        lDisabled = true;
		    }   
		  var aLinha    = new Array();
		      aLinha[0] = iIdLinha+1;
          aLinha[1] = oItem.iCodMaterial;                 
          aLinha[2] = oItem.sDescrMaterial.urlDecode().substring(0,70);                 
	        aLinha[3] = new String(oItem.iQtdSaldo).valueOf();
	        aLinha[4] = oItem.iQtdCedida;
	        if (lDisabled) {
	        	aLinha[5] = '0';  
	        } else { 

	  	      aLinha[5] = new DBTextField("iValor_"+oItem.iCodigoItem, "iValor_"+oItem.iCodigoItem, 0, 10);
            aLinha[5].addStyle("text-align","right");
            aLinha[5].addStyle("height","100%");
            aLinha[5].addStyle("width","100%");
            aLinha[5].addStyle("border","1px solid transparent;");
            aLinha[5].addEvent("onBlur","js_bloqueiaDigitacao(this);");  
            aLinha[5].addEvent("onBlur","iValor_"+oItem.iCodigoItem+".sValue=this.value;");  
            aLinha[5].addEvent("onFocus","js_liberaDigitacao(this);");  
            aLinha[5].addEvent("onKeyPress","return js_mask(event,\"0-9|.|-\")");  
	        }
	        aLinha[6] = oItem.iCodigoItem;
	        aLinha[7] = oItem.iItemRecebe;
          //aLinha[5].addEvent("onKeyDown","return js_verifica(this,event,false)");
	        oGridItensDepart.addRow(aLinha,false, lDisabled);
	        oGridItensDepart.aRows[iIdLinha].aCells[0].sStyle +="background-color:#DED5CB;font-weight:bold;padding:1px";
	        if (lDisabled) {
	        	oGridItensDepart.aRows[iIdLinha].setClassName('disabled');
	        }
	        oGridItensDepart.aRows[iIdLinha].aCells[2].sEvents  = "onmouseover='js_setAjuda(\""+oItem.sResumo+"\",true)'";
	        oGridItensDepart.aRows[iIdLinha].aCells[2].sEvents += "onmouseOut='js_setAjuda(null,false)'";
		});
		oGridItensDepart.renderRows();
    
	}

	/**
	 * Libera  o input passado como parametro para a digitacao.
	 * é Retirado a mascara do valor e liberado para Edição
	 * é Colocado a Variavel nValorObjeto no escopo GLOBAL
	 */
	function js_liberaDigitacao(object) {
	  
	  nValorObjeto        = object.value; 
	  object.value        = object.value;
	  object.style.border = '1px solid black';
	  object.readOnly     = false;
	  object.style.fontWeight = "bold";
	  object.select();
	}

	/**
	 * bloqueia  o input passado como parametro para a digitacao.
	 * É colocado  a mascara do valor e bloqueado para Edição
	 */
	function js_bloqueiaDigitacao(object, iBold) {

	  
	  object.readOnly         = true;
	  object.style.border     ='0px';
	  object.style.fontWeight = "normal";
	  if (iBold) {
	    object.style.fontWeight = "bold";
	  }
	  object.value            = object.value;
	}

function js_setAjuda(sTexto, lShow) {

  if (lShow) {
  
    el =  $('ctnItensDepartamento');
    var x = 0;
    var y = el.offsetHeight;
    while (el.offsetParent && el.tagName.toUpperCase() != 'BODY') {

     x += el.offsetLeft;
     y += el.offsetTop;
     el = el.offsetParent;

   }
   x += el.offsetLeft;
   y += el.offsetTop;
   $('ajudaItem').innerHTML     = sTexto.urlDecode();
   $('ajudaItem').style.display = '';
   $('ajudaItem').style.top     = y+10;
   $('ajudaItem').style.left    = x;
   $('ajudaItem').style.zIndex  = 100000;
   
  } else {
   $('ajudaItem').style.display = 'none';
  }
}


function js_confirmaCedencia() {

	if (!confirm('Confirma a cedência dos Itens?')) {
    return false;
	} 
  var aItens            = oGridItensDepart.aRows;
  var iItensComCedencia = 0;
  var aItensCedidos     = new Array();
  var sErro             = ''; 
  aItens.each(function(oItem, iIndice) {

	  var nQuantidade = oItem.aCells[5].getValue();
	  var lCorreto    = true;  
    if (new Number(oItem.aCells[3].getValue()) > 0) {
      if (new Number(oItem.aCells[5].getValue()) > new Number(oItem.aCells[3].getValue())) {
          
        sErro += " Item "+oItem.aCells[0].getValue()+" - "+oItem.aCells[2].getValue()+" sem saldo para Transferencias\n";
        lCorreto = false;
      }
    }
    if (lCorreto && nQuantidade > 0) {

      oItemCedido = new Object();
      oItemCedido.itemrecebe  = oItem.aCells[7].getValue().trim();
      if (oItemCedido.itemrecebe.trim() == "") {
        oItemCedido.itemrecebe = '';
      }
      oItemCedido.itemcedente = oItem.aCells[6].getValue();
      oItemCedido.quantidade  = nQuantidade; 
      aItensCedidos.push(oItemCedido);
    }
  });
  
  if (sErro.trim() != "") {

	  alert(sErro);
  } else {

	  var oParam 					 = new Object();
	  oParam.exec 				 = "cederItens";
	  oParam.iEstimativa   = iEstimativaCedente;
	  oParam.iDepartRecebe = iDepartamentoRecebe;
	  oParam.aItensCedidos = aItensCedidos

	  js_divCarregando("Aguarde, processando...", "msgBox");
    var oAjax   = new Ajax.Request(sUrlRPC,
                                  {
                                   method: "post",
                                   parameters:'json='+Object.toJSON(oParam),
                                   onComplete: js_retornoCederItens
                                  });
  }
}

function js_retornoCederItens(oAjax) {

  js_removeObj("msgBox");

	var oRetorno = eval("("+oAjax.responseText+")");
	if (oRetorno.status == 2) {

	  alert(oRetorno.message.urlDecode());
	  return false;
	} else {

	  alert('Cedencia Realizada com sucesso.');
	  oWindowItensDepart.destroy();
		oWindowDepart.destroy();
	}
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
</script>
</body>
</html>