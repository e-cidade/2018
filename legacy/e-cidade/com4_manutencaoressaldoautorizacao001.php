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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
require_once(modification("classes/db_empautoriza_classe.php"));

$clempautoriza = new cl_empautoriza;
db_postmemory($HTTP_POST_VARS);

$clempautoriza->rotulo->label();

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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<center>

<div id='ficha' style="position: absolute; float:left;background-color:#ccc; width: 100%; height: 100%; display: none; padding-top: 10px;">
</div>

<form name="form1" method="post" action="">

  <fieldset style="margin-top:50px; width: 500px;">
    <legend><strong>Filtros para Pesquisa</strong></legend>
    
    <table  align="left"  cellpadding="2" cellspacing="2" border="0">
    
      <tr> 
        <td  align="left" nowrap title=""> <b>
          <? db_ancora("Autorizações de : ","js_autorizacao(1,true);",1);?>  
        </td>
        <td align="left" nowrap>
          <?
             db_input("autorizacao1",10,"",true,"text",4,"onchange='js_autorizacao(1,false);'"); 
          ?>
          </b>
        </td>
        
        <td  align="left" nowrap title=""> <b>
          <? db_ancora("Até : ","js_autorizacao(2,true);",1);?>  
        </td>
        <td align="left" nowrap>
          <?
             db_input("autorizacao2",10,"",true,"text",4,"onchange='js_autorizacao(2,false);'"); 
          ?>
          </b>
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
<br>  
<input type="button" value="Pesquisar" name="pequisar" onclick="js_pesquisar();">  
   
</form>  


</center>   
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
var sURLRPC = "com4_manutencaoreservasaldo.RPC.php";

/**
 * Array com os itens da autorizacao selecionada
 */
var aItensAutorizacao = new Array();

//autorizacao1 (de)
function js_autorizacao(tp, mostra) {

	if(mostra==true){
		if (tp == 1) {
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_autoriza','func_empautoriza.php?funcao_js=parent.js_mostraempautorizacao1|e54_autori&param_depart=<?=db_getsession("DB_coddepto")?>','Pesquisa',true);
		} else {
			js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_autoriza','func_empautoriza.php?funcao_js=parent.js_mostraempautorizacao2|e54_autori&param_depart=<?=db_getsession("DB_coddepto")?>','Pesquisa',true);
		}	
  }else{
	   if (tp == 1) {
       if(document.form1.autorizacao1.value != ''){
          js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_autoriza','func_empautoriza.php?pesquisa_chave='+document.form1.autorizacao1.value+'&funcao_js=parent.js_mostraempautorizacao11&param_depart=<?=db_getsession("DB_coddepto")?>','Pesquisa',false);
       }else{
         document.form1.autorizacao1.value = '';
       }
	   } else {
	     if(document.form1.autorizacao2.value != ''){
	        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_autoriza','func_empautoriza.php?pesquisa_chave='+document.form1.autorizacao2.value+'&funcao_js=parent.js_mostraempautorizacao21&param_depart=<?=db_getsession("DB_coddepto")?>','Pesquisa',false);
	     }else{
	       document.form1.autorizacao1ate.value = '';
	     }		   
	   }  
  }
}

function js_mostraempautorizacao1(chave,erro){
	document.form1.autorizacao1.value = chave; 
  if(erro==true){
    document.form1.autorizacao1.focus();
    document.form1.autorizacao1.value = '';
  }
  db_iframe_autoriza.hide();
}

function js_mostraempautorizacao11(chave1, erro) {
	if(erro == true) {
		alert(chave1);
		document.form1.autorizacao1.value = '';
	}	
  db_iframe_autoriza.hide();
}

function js_mostraempautorizacao2(chave,erro){
	document.form1.autorizacao2.value = chave; 
  if(erro==true){
    document.form1.autorizacao2.focus();
    document.form1.autorizacao2.value = '';
  }
  db_iframe_autoriza.hide();
}

function js_mostraempautorizacao21(chave1, erro) {
	if(erro == true) {
		alert(chave1);
		document.form1.autorizacao2.value = '';
	}	
  db_iframe_autoriza.hide();
}

//pesquisa dotacao
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
  }
  
}
function js_mostraorcdotacao1(chave1) {

  document.form1.dotacao.value = chave1;
  db_iframe_orcdotacao.hide();
}

function js_montaWindow() {


	  var sContent  = "<div id='gridAutorizacoes' style='width:99%; float:left;'> </div>                                  ";
	      sContent += "<div style='width:99%; float:left;'>                                                               ";
	      sContent += " <table border = '0' align='center' style='margin-top:20px;'>                                      ";
	      sContent += "   <tr> ";
	      sContent += "     <td> <input type='button' value='Confirmar' onclick='js_modificarReservas();' /></td>        ";
	      sContent += "     <td> <input type='button' value='Fechar' onclick='windowAutorizacoes.destroy();' />  </td>    ";
	      sContent += "   </tr>                                                                                           ";
	    	sContent += " </table>                                                                                          "; 
	      sContent += "</div>                                                                                             ";

	     
	  windowAutorizacoes  = new windowAux('wndAutorizacoes', 'Lista de Autorizações', (screen.availWidth - 130), (screen.availHeight-250));
	  windowAutorizacoes.setContent(sContent);
	  windowAutorizacoes.allowCloseWithEsc(false);
	  windowAutorizacoes.setShutDownFunction(function(){
	    windowAutorizacoes.destroy();
		});
		oMsgBoardAutorizacoes = new DBMessageBoard('msgboardAutorizacoes',
		                                           'Autorizações disponíveis para manutenção de reserva de saldo',
		                                           'Selecione as Autorizações que deseja cancelar a reserva de dotação, clique em confirmar.',
		                                           windowAutorizacoes.getContentContainer()).show();
	  windowAutorizacoes.show(50,50);
	  js_gridAutorizacoes();

	}

function js_gridAutorizacoes() {

	  oGridAutorizacoes              = new DBGrid('Autorizacoes');
	  oGridAutorizacoes.nameInstance = 'oGridAutorizacoes';
	  oGridAutorizacoes.setCheckbox(0);
	  oGridAutorizacoes.setCellWidth(new Array( '20px',
	                                            '20px',
	                                            '50px',
	                                            '50px',
	                                            '150px',
	                                            '20px'
	                                           ));
	  
	  oGridAutorizacoes.setCellAlign(new Array( 'right'  ,
	                                            'center'  ,
	                                            'left',
	                                            'right',
	                                            'left',
	                                            'right'  
	                                           ));
	  
	  
	  oGridAutorizacoes.setHeader(new Array( 'Autorização',
	                                         'Data de Emissão',
	                                         'Credor',
	                                         'Dotações',
	                                         'Resumo',
	                                         'Valor'
	                                        ));
	                                       
	  oGridAutorizacoes.setHeight((screen.availHeight-450));
	  oGridAutorizacoes.show($('gridAutorizacoes'));

	  
	}
	
function js_pesquisar(){
	
	  var iAutorizacao1  = $F('autorizacao1');
	  var iAutorizacao2  = $F('autorizacao2');
	  var sDataIni       = $F('datainicial');
	  var sDataFim       = $F('datafinal');
	  var iDotacao       = $F('dotacao');
	   
	  js_montaWindow();
	  
	  oGridAutorizacoes .clearAll(true);
	  

	  var oParametros              = new Object();
	  var msgDiv                   = "Carregando Lista de Autorizações\n Aguarde ...";
	  oParametros.exec             = 'pesquisarAutorizacao';
	  oParametros.iAutorizacao1    = iAutorizacao1;
	  oParametros.iAutorizacao2    = iAutorizacao2;
	  oParametros.sDataIni         = sDataIni;
	  oParametros.sDataFim         = sDataFim;
	  oParametros.iDotacao         = iDotacao;
	    
	  
	  js_divCarregando(msgDiv,'msgBox');
	   
	   var oAjaxLista  = new Ajax.Request(sURLRPC,
	                                             {method: "post",
	                                              parameters:'json='+Object.toJSON(oParametros),
	                                              onComplete: js_retornoAutorizacoes
	                                             });   

	  

	}

function js_retornoAutorizacoes(oAjax) {
    
  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
   
  if (oRetorno.status == 1) {

    oRetorno.dados.each( 
         function (oDado, iInd) {       

            aRow = new Array();                                                              
            aRow[0]  = oDado.autorizacao;
            aRow[1]  = oDado.dtEmis;
            aRow[2]  = oDado.credor;
            aRow[3]  = "<a onclick='js_saldoDotacao("+oDado.dotacoes+");return false;' href='#'>"+oDado.dotacoes+"</a>";;
            aRow[4]  = oDado.resumo.urlDecode();
            aRow[5]  = js_formatar(oDado.valor, 'f');	            
            oGridAutorizacoes.addRow(aRow);
         });
    
    oGridAutorizacoes.renderRows();  
    if (oRetorno.dados.length == 0) {
      oGridAutorizacoes.setStatus('Nenhuma Autorização encontrada!');
    }  
  } else {
   alert(oRetorno.message.urlDecode());
  }
}
/**
 *Realiza o cancelamento de saldo das Autorizacoes
 */
function js_modificarReservas() {

  var aAutorizacoesSelecionadas = oGridAutorizacoes.getSelection('object');
  if (aAutorizacoesSelecionadas.length == 0) {
    
    alert('Nenhuma Autorização foi escolhida.');
    return false;
  }
  var sMsg  = 'Confirma a anulação das reservas de saldo das ';
  sMsg     += 'Autorizações Selecionadas('+aAutorizacoesSelecionadas.length+')?';
  if (!confirm(sMsg)) {
    return false;
  }
  js_divCarregando('Aguarde, processando', 'msgBox');
  var aAutorizacoes = new Array();
  aAutorizacoesSelecionadas.each(function(oAutorizacaoSelecionada, iSeq) {
    aAutorizacoes.push(oAutorizacaoSelecionada.aCells[1].getValue());
  });
  var oParametros           = new Object();
  oParametros.exec          = 'removerReservaAutorizacao';
  oParametros.aAutorizacoes = aAutorizacoes;
  var oAjaxLista  = new Ajax.Request(sURLRPC,
                                     {method: "post",
                                      parameters:'json='+Object.toJSON(oParametros),
                                      onComplete: js_retornoModificarAutorizacoes
                                     });   
}

function js_retornoModificarAutorizacoes(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');
  if (oRetorno.status == 1) {
  
    alert('Reservas de saldo canceladas com sucesso');
    js_pesquisar();
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
function js_pesquisaAutorizacao(iAutorizacao) {
  js_OpenJanelaIframe('',
                      'db_iframe_autorizacao',
                      'func_empempenhoaut001.php?fechar=parent.CurrentWindow.corpo.db_iframe_autorizacao&e54_autori='+iAutorizacao,
                      true
                     );
 $('Jandb_iframe_autorizacao').style.zIndex='100000';                     
}

</script>
