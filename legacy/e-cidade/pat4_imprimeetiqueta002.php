<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");
include ("libs/db_app.utils.php");

db_postmemory($HTTP_GET_VARS);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<? 
	db_app::load('strings.js,scripts.js,datagrid.widget.js,prototype.js');
	db_app::load('estilos.css,grid.style.css');
?>

<script>
function js_frmListaBens(t52_depart,t30_codigo,t52_beminicial,t52_bemfinal){

		oDBGridListaBens = new DBGrid('bens');
		oDBGridListaBens.nameInstance = 'oDBGridListaBens';
		oDBGridListaBens.setCheckbox(0);
		oDBGridListaBens.setHeader(new Array('Código','Descrição','Placa'));
		oDBGridListaBens.setHeight(180);
		oDBGridListaBens.setCellAlign(new Array('right','left','center'));
		oDBGridListaBens.setCellWidth(new Array('15%','70%','15%'));
		//oDBGridListaCheques.aHeaders[9].lDisplayed = false;
		oDBGridListaBens.show($('listabens'));
		oDBGridListaBens.renderRows();
		js_pesquisa(t52_depart,t30_codigo,t52_beminicial,t52_bemfinal);
}


function js_pesquisa(t52_depart,t30_codigo,t52_beminicial,t52_bemfinal){
	
	var oPesquisa = new Object();
	
	oPesquisa.t52_depart     = t52_depart; 
	oPesquisa.t30_codigo     = t30_codigo;
	oPesquisa.t52_beminicial = t52_beminicial; 
	oPesquisa.t52_bemfinal   = t52_bemfinal; 
	oPesquisa.exec           = 'pesquisa';
	
	var sDados = Object.toJSON(oPesquisa);
	//alert(sDados);

	//return false;
	
	var msgDiv = _M('patrimonial.patrimonio.pat4_imprimeetiqueta002.aguarde');
	js_divCarregando(msgDiv,'msgBox');
	
	sUrl = 'pat4_consultabensetiquetas.RPC.php';
	var sQuery = 'dados='+sDados;
	var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoPesquisaBens
                                          }
                                  );
	
}

function js_retornoPesquisaBens(oAjax){
	js_removeObj("msgBox");
	//alert(oAjax.responseText);	
	var aRetorno = eval("("+oAjax.responseText+")");
	
	var sExpReg  = new RegExp('\\\\n','g');
  if(aRetorno.status == 2 ){
  	alert(aRetorno.message.urlDecode().replace(sExpReg,'\n'));
  	return false;
  }
  
  js_RenderGridBens(aRetorno.dados);
  
}

function js_RenderGridBens(aDados){
	
	oDBGridListaBens.clearAll(true);
	
	var iNumRows = aDados.length;
	
		if(iNumRows > 0){
			aDados.each(
				function (oDado,iInd){
											
						var aRow	= new Array();
										
						aRow[0] 	= oDado.t52_bem;
						aRow[1] 	= oDado.t52_descr.urlDecode();
						aRow[2] 	= oDado.t52_ident.urlDecode();
						
	 					oDBGridListaBens.addRow(aRow);
	 						 										
				}
			);
		}
		
	oDBGridListaBens.renderRows();

}
function js_imprimir(){
  var aLinhasSelecionadas = oDBGridListaBens.getSelection();
  var iNumRows = aLinhasSelecionadas.length;
  
  if (iNumRows == 0){
    alert(_M('patrimonial.patrimonio.pat4_imprimeetiqueta002.nenhum_item_selecionado'));
    return false;
  }
  
  //var bensSelecionados = "(";
  var bensSelecionados = "";
  //alert(iNumRows);
  var sSeparador = "";
  for (var iInd = 0; iInd < iNumRows; iInd++){
    bensSelecionados += sSeparador+aLinhasSelecionadas[iInd][0];
    sSeparador = ','; 
  }
  //bensSelecionados += ')';
  //alert(bensSelecionados);
  var oImprimir = new Object();
  
  oImprimir.bens  = bensSelecionados;
  oImprimir.exec  = 'imprimir';
  
  var sDados = Object.toJSON(oImprimir);
  //alert(sDados);

  //return false;
  
  var msgDiv = _M('patrimonial.patrimonio.pat4_imprimeetiqueta002.aguarde_emitindo_etiquetas');
  js_divCarregando(msgDiv,'msgBox');
  
  sUrl = 'pat4_consultabensetiquetas.RPC.php';
  var sQuery = 'dados='+sDados;
  var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoImprimirEtiqueta
                                          }
                                  );
}

function js_retornoImprimirEtiqueta(oAjax){
  
  js_removeObj("msgBox");
    
  var aRetorno = eval("("+oAjax.responseText+")");
  var sExpReg  = new RegExp('\\\\n','g');
  if(aRetorno.status == 2 ){
    alert(aRetorno.message.urlDecode().replace(sExpReg,'\n'));
    return false;
  }else if(aRetorno.status == 0){
    var sMsg = aRetorno.message.urlDecode().replace(sExpReg,'\n');
    if(confirm(sMsg)){
      // abre relatorio
      //alert(aRetorno.icodigoetiqueta);
      var sQuery = "icodigoetiqueta="+aRetorno.icodigoetiqueta;
      jan = window.open('pat2_etiquetasemitidas002.php?'+sQuery,'',
                     'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
    }
  }

}


</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc" 
  onLoad="js_frmListaBens('<?=$t52_depart?>','<?=$t30_codigo?>','<?=$t52_beminicial?>','<?=$t52_bemfinal?>');">
<table width="95%" align="center" style="margin-top: 20px;"><tr align="center"><td>
 	<tr>
 	 <td colspan="2" align="center" valign="top">
 	  <fieldset>
 			<legend><b>Bens Etiquetas Imprimir</b></legend>
 			<div id="listabens">
 			
 			</div>
 		</fieldset> 
 	</td>
 </tr>
 <tr align="center">
  <td>
    <input value='Imprimir' type='button' id='imprimir' onclick='js_imprimir();'>
    <input value='Fechar' type='button' id='fechar' onclick='parent.js_fechar();'>
  </td>
 </tr>
</table>
    
<?
  //db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>