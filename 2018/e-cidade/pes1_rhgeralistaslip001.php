<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script                       type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<link href="estilos.css"            rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table align="center" style="padding-top:28px;width: 65%">
  <tr>
    <td>
		  <center>
			  <form name="form1" method="post" action="">
			    <fieldset>
			      <legend align="center">
			        <b>Geração de SLIP</b>
			      </legend>
			      <table align="center" width="100%">
				      <tr >
				      	<td>
				      		<div id='listaSLIP'></div>
				      	</td>
				      </tr>
				 	  </table> 
			    </fieldset>
			    <table>  
				    <tr>
				      <td align = "center"> 
					      <input name="fechar"    id="fechar "   type="button" value="Fechar"    onClick="js_fechar();">
					      <input name="processar" id="processar" type="button" value="Processar" onClick="js_processar();" disabled>
					    </td>
		        </tr>
		      </table>
		    </form>
			</center>
		</td>
	</tr>		
</table>	 
</body>
</html>
<script>


  var sUrl        = 'pes1_rhempenhofolhaRPC.php';
  var lSelect     = false;
  var oDBGridSLIP = new DBGrid('gridSLIP');
	    oDBGridSLIP.nameInstance = 'oDBGridSLIP';
	    oDBGridSLIP.setHeader( new Array('Recurso',
	                                     'Descrição do Recurso',
	                                     'Conta Débito',
	                                     'Conta Crédito',
	                                     'Valor',
	                                     'Codigo Slip',
	                                     'Cgm',
	                                     'Retencao',
	                                     'Característica Peculiar'));
	                                     
	    oDBGridSLIP.setCellWidth( new Array('7%','20%','30%','30%','13%'));
	    oDBGridSLIP.setCellAlign( new Array('center','left','center','center','right'));
	    oDBGridSLIP.setHeight('300');
	        
      oDBGridSLIP.aHeaders[5].lDisplayed = false;
      oDBGridSLIP.aHeaders[6].lDisplayed = false;
      oDBGridSLIP.aHeaders[7].lDisplayed = false;
      oDBGridSLIP.aHeaders[8].lDisplayed = false;
     	            
     	oDBGridSLIP.hasTotalizador = true;                                     
     	                                     
	    oDBGridSLIP.show($('listaSLIP'));


  function js_processar(){
    
    js_divCarregando('Aguarde...','msgBox');
     
    var aObjSlips = new Array();                       
    var aSlips    = oDBGridSLIP.getSelection();
    
        aSlips.each( function ( aRow, iId ){
          aRow[4] =  js_strToFloat(aRow[4]).valueOf();
          aRow[6] =  new String(aRow[6]).trim();
        });
        
    var sQuery  = 'sMethod=geraSLIP';
        sQuery += '&iAnoFolha='+iAnoFolha;
        sQuery += '&iMesFolha='+iMesFolha;
        sQuery += '&sSigla='+sSigla;
        sQuery += '&sSemestre='+sSemestre;
        sQuery += '&aSlips='+Object.toJSON(aSlips);
    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoProcessaSLIP
                                          }
                                  );    
    
    
  }

  function js_retornoProcessaSLIP(oAjax){

    js_removeObj("msgBox");
   
    var aRetorno  = eval("("+oAjax.responseText+")");
    var sExpReg   = new RegExp('\\\\n','g');
    var sMensagem = aRetorno.sMsg.urlDecode().replace(sExpReg,'\n');
    
    if ( aRetorno.lErro ) {
      alert(sMensagem);
      return false;
    } else {
    
      if ( confirm(sMensagem+"\nDeseja Imprimir?") ) {
        window.open('cai3_emiteslips002.php?slips='+aRetorno.sListaSlips,'','location=0');
      }
    
      js_fechar();   
    }

  }
  


  function js_fechar(){
    parent.db_iframe_geraslip.hide();;
  }


  function js_verificaSelect() {
  
    if ( lSelect ) {    
      clearTimeout(temporizador);
      js_consultaSLIPs();
    } else {
      temporizador = setTimeout('js_verificaSelect()',500);
    }
      
  }


  function js_consultaSLIPs(){
    
    if ( !lSelect ) {
      js_consultaSelectContas();
      js_verificaSelect();
      
    } else {  
  
	    js_divCarregando('Aguarde...','msgBox');
	
	    var sQuery  = 'sMethod=consultarDadosGeracaoSLIP';
	        sQuery += '&iAnoFolha='+iAnoFolha;
	        sQuery += '&iMesFolha='+iMesFolha;
	        sQuery += '&sSigla='+sSigla;
	        sQuery += '&sSemestre='+sSemestre;
	        if (sSigla == 'r20') {
	          sQuery += '&sRescisoes='+sRescisoes;
	        }
	        
	        
		  var oAjax   = new Ajax.Request( sUrl, {
		                                          method: 'post', 
		                                          parameters: sQuery, 
		                                          onComplete: js_retornoConsultaSLIP
		                                        }
		                                );         
    }
  }


  function js_retornoConsultaSLIP(oAjax){

    js_removeObj("msgBox");
   
    var aRetorno = eval("("+oAjax.responseText+")");
    var sExpReg  = new RegExp('\\\\n','g');
  
    if ( aRetorno.lErro ) {
      alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
      js_fechar();
      return false;
    } else {
      if (aRetorno.lLiberada) {
        $('processar').disabled = false;    
      } 
      js_montaGrid(aRetorno.aSlips);   
    }

  }  
  
  
  function js_montaGrid(aSlips){

    var iNroLinhas = aSlips.length;
    var nTotal     = new Number();
    
    oDBGridSLIP.clearAll(true);
    
    for ( var iInd=0; iInd < iNroLinhas; iInd++ ) {
      with( aSlips[iInd] ){
      
        var aParametros = new Array( recurso,
                                     descrrecurso.urlDecode(),
                                     js_getSelect('contaDeb'+recurso+retencao+folhaslip,contadebito),
                                     js_getSelect('contaCred'+recurso+retencao+folhaslip,contacredito),
                                     js_formatar(valor,'f'),
                                     folhaslip,
                                     cgm,
                                     retencao,
                                     concarpeculiar); 
                                          
        oDBGridSLIP.addRow(aParametros);
        
        nTotal += new Number(valor);
        
      }
      
    }
    
    oDBGridSLIP.renderRows();
    
    $('TotalForCol4').innerHTML  = js_formatar(nTotal,'f');
    
    oDBGridSLIP.aRows.each(function(oRow, id) {
      oRow.isSelected=true;
    });
    
    for ( var iInd=0; iInd < iNroLinhas; iInd++ ) {
      with( aSlips[iInd] ){
        if ( contacredito == '' ) {
          alert(' Recurso '+recurso+' - '+descrrecurso.urlDecode()+' sem conta crédito configurada! Verifique.');
          js_fechar();
          return false;
        }
        $('contaDeb'+recurso+retencao+folhaslip).value  = contadebito; 
        $('contaCred'+recurso+retencao+folhaslip).value = contacredito;
      }
    }    
                
        
  }



  function js_consultaSelectContas(){
  
    js_divCarregando('Aguarde...','msgBox');
    
    var sQuery = 'sMethod=consultarSelectContas';
    var oAjax  = new Ajax.Request( sUrl, {
                                           method: 'post', 
                                           parameters: sQuery, 
                                           onComplete: js_retornoConsultaSelectContas
                                         }
                                 );         
  
  }


  function js_retornoConsultaSelectContas(oAjax){
    
    js_removeObj("msgBox");
    
    var aRetorno = eval("("+oAjax.responseText+")");
    var sExpReg  = new RegExp('\\\\n','g');
  
    if ( aRetorno.lErro ) {
      alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
      return false;
    } else {
    
      var iNroLinhas = aRetorno.aContas.length;
      sOpcoes        = '';
    
      if ( iNroLinhas > 0 ) {
	      for ( var iInd=0; iInd < iNroLinhas; iInd++ ) {
	        with(aRetorno.aContas[iInd]){
	          sOpcoes += "<option value='"+reduz+"'>"+descr.urlDecode()+"</option>";           
	        }  
	      }
      }
      
      lSelect = true;
      
    }

  }


  function js_getSelect(sNome,sDefault){
  
    var sSelect  = "<select name='"+sNome+"' id='"+sNome+"' style='width:100%'>"; 
        sSelect += sOpcoes;
        sSelect += "</select>";
    
    return sSelect;    
        
  }
  
</script>
<?

  if ( trim($oGet->iAnoFolha) != '' && trim($oGet->iMesFolha) != '' && trim($oGet->sSigla) != '' ) {

    
   	if ( isset($oGet->sSemestre) ) {
   		$sSemestre = $oGet->sSemestre;
   	} else {
   		$sSemestre = '';
   	}
   	echo "<script>
   	       var iAnoFolha  =  {$oGet->iAnoFolha};
   	       var iMesFolha  =  {$oGet->iMesFolha};
   	       var sSigla     = '{$oGet->sSigla}';
   	       var sSemestre  = '{$sSemestre}';
   	       var sRescisoes = '".@$oGet->sRescisoes."';
   	     </script>";
   	     
   	echo "<script>js_consultaSLIPs();</script>";
     	 
   }
  ?>