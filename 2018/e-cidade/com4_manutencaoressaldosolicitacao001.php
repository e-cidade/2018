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
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_solicita_classe.php");

$clsolicita = new cl_solicita;
db_postmemory($HTTP_POST_VARS);

$clsolicita->rotulo->label();

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("widgets/windowAux.widget.js");
  db_app::load("datagrid.widget.js");
  db_app::load("strings.js");
  db_app::load("grid.style.css");
  db_app::load("estilos.css");
  db_app::load("widgets/dbmessageBoard.widget.js");  
  db_app::load("dbcomboBox.widget.js");   
?>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<style>
.msg { border-bottom: 2px groove white;
       padding:5px;background-color:white;
       vertical-align:bottom;
       font-weight:bold;
       width:98%;
       height:50px;
       text-align:left'
 }      

</style>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<center>
<form name="form1" method="post" action="">

  <fieldset style="margin-top:50px; width: 500px;">
    <legend><strong>Filtros para Pesquisa</strong></legend>
    
    <table  align="left"  cellpadding="2" cellspacing="2" border="0">
    
		  <tr> 
		    <td  align="left" nowrap title="<?=$Tpc10_numero?>"> <b>
		      <? db_ancora("Solicitações de : ","js_solicitade(true);",1);?>  
		    </td>
		    <td align="left" nowrap>
		      <?
		         db_input("pc10_numerode",10,$Ipc10_numero,true,"text",4,"onchange='js_solicitade(false);'"); 
		      ?>
		      </b>
		    </td>
		    
        <td  align="left" nowrap title="<?=$Tpc10_numero?>"> 
          <? db_ancora("Até : ","js_solicitaate(true);",1);?>  
        </td>
        <td align="left" nowrap>
          <?
             db_input("pc10_numeroate",10,$Ipc10_numero,true,"text",4,"onchange='js_solicitaate(false);'"); 
          ?>
        </td>		    
		  </tr>    
    
		  <tr>
		    <td align="left" nowrap title="Emissão de:">
		       <b>Data de Emissão de : </b>
		    </td>
		    <td align="left"> 
		      <?
		        db_inputdata('datainicial',null ,null, null,true,'text',1);
		      ?>
		    </td>
        <td align="left" nowrap title="Emissão Até:">
           <b>Até : </b>
        </td>
        <td align="left"> 
          <?
            db_inputdata('datafinal',null ,null, null,true,'text',1);
          ?>
        </td>		    
		  </tr>

      <tr>
        <td colspan="1" align="left" nowrap title="Emissão de:">
           <b> 
                <? 
                 db_ancora("Dotação : ","js_pesquisarh72_coddot(true);", 1);
               ?>           
           
           </b>
        </td>
        <td colspan="3" align="left"> 
          <?
            db_input("dotacao",10,"",true,"text",1, "onchange='js_pesquisarh72_coddot(false);'");
          ?>
        </td>
      </tr>

    </table>
    
  </fieldset> 
</form>   
 
 <input type="button" value="Pesquisar" onclick="js_pesquisar();" />

</center>   
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
var sURLRPC = "com4_manutencaoreservasaldo.RPC.php";

/**
 * Array com os itens da solicitação selecionada
 */
var aItensSolicitacao = new Array();


function js_detalhes(iSolicitacao, sDotacoes) {

	  oGridSolicitacoesDetalhes .clearAll(true);
	  

	  var oParametros              = new Object();
	  var msgDiv                   = "Carregando Lista de Itens \n Aguarde ...";
	  oParametros.exec             = 'pesquisarSolicitacaoDetalhes';
	  oParametros.iSolicitacao     = iSolicitacao;
	  oParametros.sDotacoes        = sDotacoes;
	  
	  js_divCarregando(msgDiv,'msgBox');
   var oAjaxLista  = new Ajax.Request(sURLRPC,
                                      {method: "post",
	                                              parameters:'json='+Object.toJSON(oParametros),
	                                              onComplete: js_retornoItens
	                                             }); 
	
}


function js_retornoItens(oAjax) {
    
    js_removeObj('msgBox');
    var oRetorno      = eval("("+oAjax.responseText+")");
    var iLinha        = 0;
    var iTotalItens   = 0;
    aItensSolicitacao = oRetorno.dados; 
    oGridSolicitacoesDetalhes.clearAll(true);
    if (oRetorno.status == 1) {

      oRetorno.dados.each( 
           function (oDado, iInd) {       

              var aRow = new Array();                                                              
              aRow[0]  = oDado.ordem;
              aRow[1]  = oDado.descricao.urlDecode();
              aRow[2]  = js_formatar(oDado.nValorTotal, 'f');
            	aRow[3]  = ""; 
            	aRow[4]  = ""; 
            	aRow[5]  = ""; 
            	aRow[6]  = "";  	
            	aRow[7]  = ""; 	    	      
              oGridSolicitacoesDetalhes.addRow(aRow);
              oGridSolicitacoesDetalhes.aRows[iLinha].sStyle ='background-color:#eeeee2;';
              oGridSolicitacoesDetalhes.aRows[iLinha].aCells.each(function(oCell, id) {
              aItensSolicitacao[iInd].modificado = false; 
                if (id > 0) {  
                   
                  oCell.sStyle +=';border-right: 1px solid #eeeee2;';
                  oCell.sStyle += 'font-weight:bold;';
                } else {
                
                  oCell.sStyle  = "background-color:#DED5CB; font-weight:bold;padding:1px";
                }
              });
              iTotalItens++;
        	    iLinha++;
              oDado.dotacoes.each(
            		  function (oDotacao, iIndDot) {
                    oDotacao.modificado = false;  
                    aRow = new Array();                                                              
                    aRow[0]  = "";
                    aRow[1]  = "";
                    aRow[2]  = "";
                    aRow[3]  = "<a onclick='js_saldoDotacao("+oDotacao.codigo+");return false;' href='#'>"+oDotacao.codigo+"</a>";
                  	aRow[4]  = js_formatar(oDotacao.nValorDotacao, 'f') ;
                  	aRow[5]  = js_criaText(iInd, iIndDot, js_formatar(oDotacao.nValorReserva, 'f')); //oDotacao.codigoreserva ; 
                  	aRow[6]  = "<span class='saldodotacao"+oDotacao.codigo+"'>"+js_formatar(oDotacao.saldofinal, 'f')+"</span>" ; 
                  	aRow[7]  = oDotacao.codigoreserva ;
                  	oGridSolicitacoesDetalhes.addRow(aRow);
                  	var sEstiloCelula1 = "background-color:#DED5CB; font-weight:bold;padding:1px";
                  	oGridSolicitacoesDetalhes.aRows[iLinha].aCells[0].sStyle = sEstiloCelula1;    
                  	iLinha++;
                  	      
              });
              
           });
      
      oGridSolicitacoesDetalhes.renderRows();  
      oGridSolicitacoesDetalhes.setNumRows(iTotalItens);
    } else {
     alert(oRetorno.message.urlDecode());
	  }
}

function js_pesquisar(){

  var iSolicitacaoDe  = $F('pc10_numerode');
  var iSolicitacaoAte = $F('pc10_numeroate');
  var sDataIni        = $F('datainicial');
  var sDataFim        = $F('datafinal');
  var iDotacao        = $F('dotacao');
      js_montaWindow();
      oGridSolicitacoes .clearAll(true);
  

  var oParametros              = new Object();
  var msgDiv                   = "Carregando Lista de Solicitações \n Aguarde ...";
  oParametros.exec             = 'pesquisarSolicitacao';
  oParametros.iSolicitacaoDe   = iSolicitacaoDe;
  oParametros.iSolicitacaoAte  = iSolicitacaoAte;
  oParametros.sDataIni         = sDataIni;
  oParametros.sDataFim         = sDataFim;
  oParametros.iDotacao         = iDotacao;
    
  
  js_divCarregando(msgDiv,'msgBox');
   
   var oAjaxLista  = new Ajax.Request(sURLRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoSolicitacoes
                                             });   

  

}


function js_retornoSolicitacoes(oAjax) {
	    
	    js_removeObj('msgBox');
	    var oRetorno = eval("("+oAjax.responseText+")");

	    if (oRetorno.status == 1) {

	      oRetorno.dados.each( 
	           function (oDado, iInd) {       
    
	              aRow = new Array();                                                              
	              aRow[0]  = oDado.solicitacao;
	              aRow[1]  = oDado.dtEmis;
	              aRow[2]  = oDado.dotacoes;
		            aRow[3]  = oDado.resumo.urlDecode();
	              oGridSolicitacoes.addRow(aRow);
	              oGridSolicitacoes.aRows[iInd].sEvents += "ondblclick='js_montaWindowDetalhes("+oDado.solicitacao+",\""+oDado.dotacoes+"\")'";
	           });
          
	      oGridSolicitacoes.renderRows();  
          
	    } else {
         alert(oRetorno.message.urlDecode());
		  }
	}


	function js_montaWindowDetalhes(iSolicitacao, sDotacao) {

	    if($('msgm')){
			  windowSolicitacoesDetalhes.destroy();
	    }		
      iSolicitacaoAtiva = iSolicitacao;
		  var sContent  = "<div  style='width:98.9%;'> ";
		      sContent += "<fieldset id='gridSolicitacoesDetalhes'> </fieldset>";
		      sContent += "</div>";  
		      sContent += "<div style='width:99%; float:left;'>                                                              			 ";
		      sContent += " <table border = '0' align='center' style='margin-top:20px;'>                                    		   ";
		      sContent += "   <tr>                                                                                           			 ";
		      sContent += "     <td> <input type='button' value='Confirmar' onclick='js_modificarReservas();' /></td>";
		      sContent += "     <td> <input type='button' value='Fechar' onclick='windowSolicitacoesDetalhes.destroy();' />  </td> ";
		      sContent += "   </tr>                                                                                                ";
		    	sContent += " </table>                                                                                               "; 
  		  
		  windowSolicitacoesDetalhes  = new windowAux('wndSolicitacoesDetalhes', 'Itens da Solicitação '+iSolicitacao, (screen.availWidth - 300), 500);
		  windowSolicitacoesDetalhes.setContent(sContent);
		  windowSolicitacoesDetalhes.setShutDownFunction(function(){
		    windowSolicitacoesDetalhes.destroy();
			});
			var sMsgHelp  = 'Informe o novo valor a reservar para o item da solicitação. ';
			sMsgHelp     += 'A edição poderá ser realizada na coluna <b>Vlr. Reservar</b>';
			oMsgBoardSolicitacoesItem = new DBMessageBoard('msgboardSolitacoesItem',
                                             'Itens com reserva de saldo.',
                                             sMsgHelp,
                                             windowSolicitacoesDetalhes.getContentContainer()).show();
		  windowSolicitacoesDetalhes.setChildOf(windowSolicitacoes);
		  windowSolicitacoesDetalhes.show(80,100);
		  windowSolicitacoesDetalhes.allowCloseWithEsc(false);
		  js_gridSolicitacoesDetalhes();
		  js_detalhes(iSolicitacao, sDotacao);

		}

		function js_gridSolicitacoesDetalhes() {

		  oGridSolicitacoesDetalhes = new DBGrid('SolicitacoesDetalhes');
		  oGridSolicitacoesDetalhes.nameInstance = 'oGridSolicitacoesDetalhes';
		  oGridSolicitacoesDetalhes.setCellWidth(new Array( '20px',
						                                            '200px',
						                                            '70px',
						                                            '70px',
						                                            '50px',
						                                            '50px',
						                                            '50px',
						                                            '20px'
		                                                   ));
		  
		  oGridSolicitacoesDetalhes.setCellAlign(new Array( 'right'  ,
		                                            'left'  ,
		                                            'right'  ,
		                                            'right',
		                                            'right' ,
		                                            'right',
		                                            'right',
		                                            'right' 
		                                           ));
		  
		  
		  oGridSolicitacoesDetalhes.setHeader(new Array( 'Item',
		                                         'Descrição',
		                                         'Vlr.Total do Item',
		                                         'Dotação',
		                                         'Vlr. Reservado',
		                                         'Vlr. Reservar',
		                                         'Saldo Dot.',
		                                         'Reserva'
		                                        ));
		                                       
		  oGridSolicitacoesDetalhes.setHeight(300);
		  oGridSolicitacoesDetalhes.show($('gridSolicitacoesDetalhes'));

		  
		}
function js_montaWindow() {


  var sContent  = "<div id='gridSolicitacoes' style='width:99%; float:left;'> </div>                                  ";
      sContent += "<div style='width:99%; float:left;'>                                                               ";
      sContent += " <table border = '0' align='center' style='margin-top:20px;'>                                      ";
      sContent += "   <tr>                                                                                            ";
      sContent += "     <td> <input type='button' value='Fechar' onclick='windowSolicitacoes.destroy();' />  </td>    ";
      sContent += "   </tr>                                                                                           ";
    	sContent += " </table>                                                                                          "; 
      sContent += "</div>                                                                                             ";

     
  
  windowSolicitacoes  = new windowAux('wndSolicitacoes', 'Lista de Solicitações', (screen.availWidth - 130), 600);
  windowSolicitacoes.setContent(sContent);
  windowSolicitacoes.allowCloseWithEsc(false);
  windowSolicitacoes.setShutDownFunction(function(){
    windowSolicitacoes.destroy();
	});
	oMsgBoardSolicitacoes = new DBMessageBoard('msgboardSolitacoes',
	                                           'Solicitações disponíveis para manutenção de reserva de saldo',
	                                           'Duplo Clique na solicitação para visualizar os itens.',
	                                           windowSolicitacoes.getContentContainer()).show();
  windowSolicitacoes.show(50,50);
  js_gridSolicitacoes();

}

function js_gridSolicitacoes() {

  oGridSolicitacoes              = new DBGrid('Solicitacoes');
  oGridSolicitacoes.nameInstance = 'oGridSolicitacoes';
  oGridSolicitacoes.setCellWidth(new Array( '20px',
                                            '20px',
                                            '50px',
                                            '150px'
                                           ));
  
  oGridSolicitacoes.setCellAlign(new Array( 'right'  ,
                                            'center'  ,
                                            'left',
                                            'left'  
                                           ));
  
  
  oGridSolicitacoes.setHeader(new Array( 'Solicitação',
                                         'Data de Emissão',
                                         'Dotações',
                                         'Resumo'
                                        ));
                                       
  oGridSolicitacoes.setHeight(400);
  oGridSolicitacoes.show($('gridSolicitacoes'));

  
}



//  Solicitacao DE
function js_solicitade(mostra) {

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_solicita',
                        'func_solicitamanutencaoreserva.php?funcao_js=parent.js_mostrasolicitade1|'+
                        'pc10_numero&param_depart=<?=db_getsession("DB_coddepto")?>','Pesquisa',true);
  }else{
     if(document.form1.pc10_numerode.value != ''){
        js_OpenJanelaIframe('top.corpo', 
                            'db_iframe_solicita',
                            'func_solicitamanutencaoreserva.php?pesquisa_chave='+document.form1.pc10_numerode.value+
                            '&funcao_js=parent.js_mostrasolicitade&param_depart=<?=db_getsession("DB_coddepto")?>',
                            'Pesquisa',false);
     }else{
       document.form1.pc10_numerode.value = '';
     }
  }
}

function js_mostrasolicitade(chave,erro){
  if(erro==true){
    document.form1.pc10_numerode.focus();
    document.form1.pc10_numerode.value = '';
  }
}

function js_mostrasolicitade1(chave1,x){
  document.form1.pc10_numerode.value = chave1;
  db_iframe_solicita.hide();
}

// solicitacao ATE
function js_solicitaate(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_solicitaate','func_solicita.php?funcao_js=parent.js_mostrasolicitaate1|pc10_numero&param_depart=<?=db_getsession("DB_coddepto")?>','Pesquisa',true);
  }else{
     if(document.form1.pc10_numeroate.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_solicitaate','func_solicita.php?pesquisa_chave='+document.form1.pc10_numeroate.value+'&funcao_js=parent.js_mostrasolicitaate&param_depart=<?=db_getsession("DB_coddepto")?>','Pesquisa',false);
     }else{
       document.form1.pc10_numeroate.value = '';
     }
  }
}

function js_mostrasolicitaate(chave,erro){
  if(erro==true){
    document.form1.pc10_numeroate.focus();
    document.form1.pc10_numeroate.value = '';
  }
}

function js_mostrasolicitaate1(chave1,x){
  document.form1.pc10_numeroate.value = chave1;
  db_iframe_solicitaate.hide();
}



// pesquisa dotacao

function js_pesquisarh72_coddot(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('', 
                        'db_iframe_orcdotacao', 
                        'func_orcdotacao.php?funcao_js=parent.js_mostraorcdotacao1|o58_coddot', 
                        'Pesquisar Dotações',true);
  }else{
    js_OpenJanelaIframe('',
                        'db_iframe_orcdotacao',
                        'func_orcdotacao.php?pesquisa_chave='+document.form1.dotacao.value+'&funcao_js=parent.js_mostraorcdotacao',
                        'Pesquisa de Dotacoes',
                        false
                        );
  }
}
function js_mostraorcdotacao(chave,erro) {
  
  if (erro == true) {
   
    document.form1.dotacao.focus(); 
    document.form1.dotacao.value = ''; 
  } else {
   //js_getDotacoes();
  }
}

function js_mostraorcdotacao1(chave1) {

  document.form1.dotacao.value = chave1;
  db_iframe_orcdotacao.hide();
  //js_getOrigemDotacao(chave1);
}

/**
 * Altera os dados da reserva de saldo da dotacao de um determinado item.
 * realiza as mudanças dentro do objeto Global aItensSolicitacao.
 */
function js_modificarReserva(iIndiceItem, iIndiceDotacao, object) {
  
  var sValor = object.value;
  var nValor = Number(sValor);

  if (empty(nValor) || isNaN(nValor)) {

    nValor = 0;
    object.value = 0;
  }

  if (aItensSolicitacao[iIndiceItem].dotacoes[iIndiceDotacao]) {
     
    var oDotacao       = aItensSolicitacao[iIndiceItem].dotacoes[iIndiceDotacao];
    var nValorAnterior = Number(oDotacao.nValorReserva);

    /**
     * procuramos em todas as dotaçoes dos itens, se existe algum item que usa a mesma dotação 
     * do item corrente. Quando modificamos um valor de reserva, devemos modificar o valor de saldo final de
     * todos os itens (dotação) que utilizam a mesma dotação.
     */ 
    var aItensDotacoes = $$('.saldodotacao'+oDotacao.codigo);
    var nSaldoDotacao  = js_strToFloat(aItensDotacoes[0].innerHTML);
    var nDiferenca     = js_round(nValorAnterior - nValor, 2);

    if ((nValor) > nSaldoDotacao + nValorAnterior) {
      $("valor_"+iIndiceItem+"_"+iIndiceDotacao).value = nValorAnterior;
    } else {
    
      aItensSolicitacao[iIndiceItem].dotacoes[iIndiceDotacao].nValorReserva = nValor;
      aItensSolicitacao[iIndiceItem].dotacoes[iIndiceDotacao].modificado    = true;
      aItensSolicitacao[iIndiceItem].modificado                             = true;
      aItensDotacoes.each(function (oDotacao, iIndice) {
         oDotacao.innerHTML = js_formatar(js_round((nSaldoDotacao + nDiferenca), 2), 'f');
      });  
    }
  } else {
    alert('Dotação não encontrada.');
  }
}

function js_criaText(iCodigoItem, iCodigoReserva, sValor) {

	 sHtml  = "<input type='text' id='valor_"+iCodigoItem+"_"+iCodigoReserva+"' ";
	 sHtml += "value = '"+sValor+"' style='width:99%;text-align: right;border:1px solid transparent;border-left:1px solid blue'";
	 sHtml += "onFocus='js_liberaDigitacao(this)'  onblur='js_bloqueiaDigitacao(this, false)' ";
	 sHtml += "onkeydown='js_verifica(this,event, false)' onkeypress='return js_teclas(event)'";
	 sHtml += "onChange='js_modificarReserva("+iCodigoItem+","+iCodigoReserva+", this)'/> ";
	 return sHtml;
}

/**
 * Libera  o input passado como parametro para a digitacao.
 * é Retirado a mascara do valor e liberado para Edição
 * é Colocado a Variavel nValorObjeto no escopo GLOBAL
 */
function js_liberaDigitacao(object) {
  
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
  object.style.border     ='1px solid transparent';
  object.style.borderLeft ='1px solid blue';
  object.style.fontWeight = "normal";
  if (iBold) {
    object.style.fontWeight = "bold";
  }
  object.value = js_formatar(object.value,'f');
}

/**
 * Verifica se  o usuário cancelou a digitação dos valores.
 * Caso foi cancelado, voltamos ao valor do objeto, e 
 * bloqueamos a digitação
 */
function js_verifica(object,event,iBold) {

  var teclaPressionada = event.which;
  if (teclaPressionada == 27) {
      object.value     = nValorObjeto;
     js_bloqueiaDigitacao(object, iBold);
  }
}

/**
 * Percorre os itens da solicitação envia para a manutenção das reservas.
 */
function js_modificarReservas() {


  var aItens = new Array();
  aItensSolicitacao.each(function(oItemSolicitacao, iSeq) {
     
    /**
     * Apenas realizamos as modificacoes dos itens que houveram mudanças nas dotacoes.
     */
    if (oItemSolicitacao.modificado) {
       
      var oItem              = new Object();
      oItem.iCodigoItem      = oItemSolicitacao.item;
      oItem.aDotacoesReserva = new Array();
      oItemSolicitacao.dotacoes.each(function(oDotacao, iSeqDot) {
       
        if (oDotacao.modificado) {
          
          var oReserva            = new Object();
          oReserva.iCodigoReserva = oDotacao.codigoreserva;
          oReserva.iCodigoDotacao = oDotacao.codigo; 
          oReserva.nValorReserva  = oDotacao.nValorReserva;
          oItem.aDotacoesReserva.push(oReserva); 
        }
      });
      aItens.push(oItem);
    }
  });
  
  if (aItens.length > 0) {
    
    var oParametros    = new Object();
    oParametros.exec   = 'modificarReservas';
    oParametros.aItens = aItens;
    oParametros.iSolicitacaoAtiva = iSolicitacaoAtiva;
     
    js_divCarregando('Aguarde, processando...', 'msgBox');
    new Ajax.Request(sURLRPC,
                     {method: "post",
                      parameters:'json='+Object.toJSON(oParametros),
                      onComplete: js_retornoModificarReserva
                     });  
  } else {
    alert('Nenhuma modificação nas reservas de dotações.');
  } 
}

function js_retornoModificarReserva(oAjax) {

  js_removeObj('msgBox');
  
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

    var sMsgRetorno  = "Reservas modificadas com sucesso.\n\n";
    sMsgRetorno     += "Deseja emitir a nota de bloqueio?";
    if (confirm(sMsgRetorno)) {

    	var sGetUrl  = "?iSolicitaInicio="  + oRetorno.iCodigoSolicitacao  ;
		    	sGetUrl += "&iSolicitaFim=" + oRetorno.iCodigoSolicitacao ;
      var jan = window.open('com2_emitenotabloqueio002.php'+sGetUrl, '', 'location=0, width='+(screen.availWidth - 5)+
          'width='+(screen.availWidth - 5)+', scrollbars=1');
    }
    js_detalhes(iSolicitacaoAtiva);
  } else {
    alert(oRetorno.message.urlDecode());
  }
}

function js_saldoDotacao(iDotacao) {
  js_OpenJanelaIframe('',
                      'db_iframe_saldo_dotacao',
                      'func_saldoorcdotacao.php?o58_coddot='+iDotacao,
                      true
                     );
 $('Jandb_iframe_saldo_dotacao').style.zIndex='100000';                     
}
</script>
