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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("libs/db_utils.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/json2.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<style>
.marcado { 
           border-colappse:collapse;
           border-right:1px inset black;
           border-bottom:1px inset black;
           cursor:normal;
           font-family: Arial, Helvetica, sans-serif;
           font-size: 12px;
           background-color:#CCCDDD
         }
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1; parent.lOrdem = true;" bgcolor="#cccccc">
<center>
<br><br>
<?
  if (db_getsession("DB_id_usuario") == 1) {
?>
<form name="form1" method="post" action="">
<fieldset style="width: 900px;">
<legend><strong>Transferência de NUMPRE entre CGM</strong></legend>
  <table  border="0" cellspacing="0" cellpadding="0">
	<tr>
	  <td>
	 	<table>
		  <tr>
	   	    <td style="padding-top:20px;"> 
	  		  <fieldset>
	    	    <table 	cellspacing="0" style="border:2px inset white; width:480px;" >
			      <tr>
			        <th>
			          <? 
			             db_ancora("CGM de Origem", "js_pesquisa_cgmorigem(true)", 1);
			             db_input("cgmorigem", 7, 1, true, "text",1,"onChange='js_pesquisa_cgmorigem(false)'");
			             db_input("nomecgmorigem", 30, 3, true,"text",3); 
			          ?>
			        </th>
			      </tr>
			      <tr>  
			      	<th class="table_header"><b>Numpres do CGM:</b></th>
			        <th class="table_header" width="12px" ><b>&nbsp;</b></th>
			      </tr>
		      	  <tbody id="listaCampos" style=" height:220px; overflow:scroll; overflow-x:hidden; background-color:white">
		     	    </tbody>
			    </table> 		
		 	  </fieldset>
		    </td>
		  <tr> 
	  	</table>
	  </td>
	  <td>
        <table>
          <tr>
      	    <td>
      	  	  <input name="incluiTodos"  type="button" value=">>" style='width:30px;' onClick="js_incluiOrdemMarcada(true);">
      	    </td>
      	  </tr>
      	  <tr>
      	    <td>
      	      <input name="inclui"   	 type="button" value=">"  style='width:30px;' onClick="js_incluiOrdemMarcada(false);">
      	    </td>
      	  </tr>
      	  <tr>
      	    <td>
      	      <input name="exclui" 	     type="button" value="<"  style='width:30px;' onClick="js_excluiOrdemMarcada(false);">
      	    </td>
      	  </tr>	
          <tr>
      	    <td>
      	  	  <input name="excluiTodos"  type="button" value="<<" style='width:30px;' onClick="js_excluiOrdemMarcada(true);">
      	    </td>
      	  </tr>      	
        </table>
      </td>
	  <td>
	 	<table>
		  <tr>
	   	    <td style="padding-top:20px;"> 
	  		  <fieldset>
	    	    <table 	cellspacing="0" style="border:2px inset white; width:480px;" >
			      <tr>
			        <th>
			          <? 
			             db_ancora("CGM de Destino", "js_pesquisa_cgmdestino(true)", 1);
			             db_input("cgmdestino", 7, 1, true, "text",1,"onChange='js_pesquisa_cgmdestino(false)'");
			             db_input("nomecgmdestino", 30, 3, true,"text",3); 
			          ?>
			        </th>
			      </tr>	    	    
			      <tr>
			      	<th class="table_header" width="150px" ><b>Numpres à serem transferidos:</b></th>
			        <th class="table_header" width="4px" ><b>&nbsp;</b></th>
			      </tr>
		      	  <tbody id="listaOrdem" style=" height:220px; overflow:scroll; overflow-x:hidden; background-color:white">
		      	   <tr id='ultimaLinhaHackOrdem'><td style='height:100%;'>&nbsp;</td></tr>
		     	  </tbody>
			    </table>
		 	  </fieldset>
		    </td>
		  <tr> 
	  	</table>
	  </td>	  
	</tr>	
  </table>
  <table>
   <tr>
    <td> Transferir a origem: </td>
    <td> 
      <select name="lProcessaOrigem">
       <option value=false> Não </option>
       <option value=true> Sim </option>
      </select>
    </td>
   </tr>
  </table>
</fieldset>

<p align="center"><input type="button" name="btnProcessarCGM" value="Processar" onclick="js_processaFormTransferencia();" /></p>
</form>
<? } else {
      echo "<br><br><font size=3><b>Procedimento não liberado!</b></font>";
   } 
?>
</center>
	 <?
			db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
	 ?>
</body>
</html>
<script>
  sUrl = "cai4_transferenciaNumpre.RPC.php";
  function js_processaFormTransferencia() {
    
    var iNumCgmOrigem  = $('cgmorigem').value;
    var iNumCgmDestino = $('cgmdestino').value;
    var aNumPreSelecionado = $$('#listaOrdem tr');
    
    if ( iNumCgmDestino == "" ) {
      alert ("Informe um CGM Destino para realizar a transferência.");
      return false;
    }
    
    if ( iNumCgmOrigem == iNumCgmDestino ) {
      alert ("Os CGMs devem ser diferentes.");
      return false;
    }
    

    if ( aNumPreSelecionado.length == 1 || aNumPreSelecionado[0].id == 'ultimaLinhaHackOrdem' ) {
      alert ("Selecione um numpre.");
      return false;
    }
    
    var sNumPreSelecionados = "";
    
    aNumPreSelecionado.each (
      function (oNumPreSel) {
        
        if ( oNumPreSel.id != "ultimaLinhaHackOrdem" ) {
        
          var sLinhaOrdem = new String(oNumPreSel.id).replace('linhaCampoOrdem','');
        
          if ( sNumPreSelecionados == "" ) {
            sNumPreSelecionados += sLinhaOrdem;
          } else {
            sNumPreSelecionados += ','+sLinhaOrdem;
          }            
        }
      }
    );
    
    
    
    if (!confirm("Você confirma a transferência de numpres? \n\n De: "+$('cgmorigem').value+" - "+$('nomecgmorigem').value+"\nPara: "+$('cgmdestino').value+" - "+$('nomecgmdestino').value)) {
      return false;
    } else {
      js_processaTransferencia(iNumCgmOrigem, iNumCgmDestino, sNumPreSelecionados);
    }
  }

  function js_pesquisa_cgmorigem(mostra){
	  if(mostra==true){
	    js_OpenJanelaIframe('top.corpo','db_iframe_cgmorigem','func_nome.php?funcao_js=parent.js_mostracgmorigem1|z01_numcgm|z01_nome','Pesquisa',true);
	  }else{
	     if(document.form1.cgmorigem.value != ''){
	       js_OpenJanelaIframe('top.corpo','db_iframe_cgmorigem','func_nome.php?pesquisa_chave='+document.form1.cgmorigem.value+'&funcao_js=parent.js_mostracgmorigem','Pesquisa',false);
	     }else{
	       document.form1.cgmorigem.value = ''; 
	     }
	  }
	}

	function js_mostracgmorigem(erro,chave){
	  document.form1.nomecgmorigem.value = chave; 
	  if(erro==true){ 
	    document.form1.cgmorigem.focus(); 
	    document.form1.cgmorigem.value = ''; 
	  } else {
		  js_pesquisaNumpres(document.form1.cgmorigem.value);		  
	  } 
	}
	
	function js_mostracgmorigem1(chave1,chave2){
	  document.form1.cgmorigem.value = chave1;
	  document.form1.nomecgmorigem.value = chave2;
	  db_iframe_cgmorigem.hide();
	  js_pesquisaNumpres(chave1);
	}

	function js_pesquisa_cgmdestino(mostra){
		if(mostra==true){
		    js_OpenJanelaIframe('top.corpo','db_iframe_cgmdestino','func_nome.php?funcao_js=parent.js_mostracgmdestino1|z01_numcgm|z01_nome','Pesquisa',true);
		} else {
		   if (document.form1.cgmdestino.value != '' && document.form1.cgmdestino.value != $('cgmorigem').value ){ 
		     js_OpenJanelaIframe('top.corpo','db_iframe_cgmdestino','func_nome.php?pesquisa_chave='+document.form1.cgmdestino.value+'&funcao_js=parent.js_mostracgmdestino','Pesquisa',false);
		   } else {
		     document.form1.cgmdestino.value = ''; 
		   }
		}
	}

	function js_mostracgmdestino(erro,chave){
		document.form1.nomecgmdestino.value = chave; 
		if (erro==true) { 
		  document.form1.cgmdestino.focus(); 
		  document.form1.cgmdestino.value = ''; 
		}  
	}
	
	function js_mostracgmdestino1(chave1,chave2){
		document.form1.cgmdestino.value = chave1;
		document.form1.nomecgmdestino.value = chave2;
		db_iframe_cgmdestino.hide();
	}	

	function js_pesquisaNumpres(iNumcgm) {
		js_divCarregando('Aguarde, buscando numpres',"msgBox"); 
		var oParam         = new Object();
    		oParam.exec    = "getNumpresTipoCgm";     
    		oParam.iNumcgm = iNumcgm;
		var oAjax          = new Ajax.Request(sUrl,
		                                       {
		                                          method: "post",
		                                          parameters:'json='+Object.toJSON(oParam),
		                                          onComplete: js_retornoNumpres
		                                         });
	}
	
  function js_processaTransferencia(iCgmOrigem,iCgmDestino,sNumPres) {
  
    js_divCarregando('Aguarde, processando...',"msgBox"); 
    var oParam                 = new Object();
        oParam.exec            = "processaTransferenciaNumpre";     
        oParam.iOrigem         = iCgmOrigem;
        oParam.iDestino        = iCgmDestino;
        oParam.sNumPres        = sNumPres;
        oParam.lProcessaOrigem = document.form1.lProcessaOrigem.value;
        
    var oAjax           = new Ajax.Request(sUrl,
                                           {
                                              method: "post",
                                              parameters:'json='+Object.toJSON(oParam),
                                              onComplete: js_retornoTransferencia 
                                             });
  }
  
  function js_retornoTransferencia(oAjax) {
    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");

    alert(oRetorno.message.urlDecode());
    if (oRetorno.status == 2) {
		  return false;
		} else {
      location='';
		}
  }

	
	function js_retornoNumpres(oAjax){
	
		js_removeObj('msgBox');
		
		var oRetorno = eval("("+oAjax.responseText+")");
		
		if (oRetorno.iStatus == 2) {
		  alert(oRetorno.sMensagem);
		  return false;
		}
		
		var sLinha  = "";	
	 	
	 	for ( var iInd = 0; iInd < oRetorno.aRegistros.length; iInd++ ) {
			  
			  iNumpre  = oRetorno.aRegistros[iInd]["k00_numpre"];
			  sObs    = "("+oRetorno.aRegistros[iInd]["k00_tipo"]+")"+oRetorno.aRegistros[iInd]["k00_descr"]+" - "+oRetorno.aRegistros[iInd]["obs"];
			  iIdLinha = oRetorno.aRegistros[iInd]["k00_numpre"];
			  
		  	sLinha += "<tr id='linhaCampo"+iIdLinha+"' class='linhagrid' >                               ";		  	
		  	sLinha += "  <td class='linhagrid' onDblClick='js_incluiOrdem("+Object.toJSON(oRetorno.aRegistros[iInd])+");'  "; 
		  	sLinha += "                        onClick='js_marcaLinha(\"linhaCampo"+iIdLinha+"\");'      ";
		  	sLinha += "                        style='text-align:left;'>"+iNumpre+" - "+sObs;
		  	sLinha += "    <input type='hidden' name='"+iNumpre+"' id='obj"+iIdLinha+"' value='"+Object.toJSON(oRetorno.aRegistros[iInd])+"'>";
		  	sLinha += "  </td>";
		  	sLinha += "</tr>";
	 	}
	 	
	  sLinha += "<tr id='ultimaLinhaHackCampos'><td style='height:100%;'>&nbsp;</td></tr>";
	 	
  	$('listaCampos').innerHTML = sLinha;		
	}
	

  function js_confereCampos(aObjCamposOrdem,aObjCamposConfigurados){
    
    js_carregaCamposDisponiveis(aObjCamposOrdem);
	  
    for( var i=0; i < aObjCamposOrdem.length; i++ ){
	    for (var x=0; x < aObjCamposConfigurados.length; x++ ){
	      if ( aObjCamposOrdem[i].iId == aObjCamposConfigurados[x].iId ) {
		    	$('linhaCampo'+aObjCamposOrdem[i].iId).style.display = "none";
			    $('linhaCampo'+aObjCamposOrdem[i].iId).className 	   = "linhagrid";		       
		    }
      }    	
    }
    
    js_carregaOrdem(aObjCamposConfigurados);
      
  }
 

	function js_marcaLinha(idCampo){
    if ( $(idCampo).className == 'linhagrid') {
  	  $(idCampo).className = 'marcado';
 	  } else {
 	    $(idCampo).className = 'linhagrid';
    }
	}
	
	function js_marcaLinhaOrdem(idCampo) {
    if ( $(idCampo).className == 'linhagrid') {
      $(idCampo).className = 'marcado';
    } else {
      $(idCampo).className = 'linhagrid';
    }
	}
	
	function js_incluiOrdem(oDadosNumPre){
	
    var oRowHack          = document.createElement('tr');
    oRowHack.id           = "ultimaLinhaHackOrdem";
    oRowHack.style.height = "auto";
    oRowHack.innerHTML    = "<td style='height:auto'>&nbsp;</td>";
    
    var oNumPre   = eval(oDadosNumPre);
    var iNumPre   = oNumPre['k00_numpre'];
    var iTipo     = oNumPre['k00_tipo'];
    var sTipoDesc = oNumPre['k00_descr'];
    var sObs      = oNumPre['obs'];
    

    $('listaOrdem').removeChild($('ultimaLinhaHackOrdem'));
	  
	  $("linhaCampo"+iNumPre).style.display = "none";
	  $("linhaCampo"+iNumPre).className     = "linhagrid";

    // Cria um Elemento TR
    var oRow          = document.createElement('tr');
    oRow.id           = "linhaCampoOrdem"+iNumPre;
    oRow.className    = "linhagrid";
    oRow.style.height = "1em";
    // Cria um Elemento TD
    oCell             = document.createElement("td");
    oCell.innerHTML   = iNumPre+" - ("+iTipo+") "+sTipoDesc+" - "+sObs;
    
    // Cria funções para observar as ações do usuario
    oCell.observe('dblclick', function() {
      js_removeOrdem("linhaCampoOrdem"+iNumPre);
    });
    oCell.className ='linhagrid';
    oCell.observe('click', function() {          
      js_marcaLinhaOrdem("linhaCampoOrdem"+iNumPre);
    });
    oCell.style.textAlign ='left';

    oRow.appendChild(oCell);
    $('listaOrdem').appendChild(oRow);
    $('listaOrdem').appendChild(oRowHack);
	}
	
	
	/* Remove Ordem com dblClick */
	function js_removeOrdem (sExcluirLinha){
	 
    // Configura o Nome para alterar na table #listaCampo
    var sLinhaOrdem = new String(sExcluirLinha).replace('linhaCampoOrdem','linhaCampo');
    // Mostra ao NUMPRE na coluna listaCampo e REMOVE da coluna listaOrdem
    $(sLinhaOrdem).style.display = "";
    $(sLinhaOrdem).className     = "linhagrid";
    $('listaOrdem').removeChild($(sExcluirLinha));
	}
			
  function js_incluiOrdemMarcada(lTodos){
    
      var aMarcados   = new Array();
      var objCampo    = "";
            
      if (lTodos) {
        var aObjMarcados = $$('#listaCampos tr');
      } else {
        var aObjMarcados = $$('#listaCampos tr.marcado');
      }
      
      
      aObjMarcados.each(
        function (oLinha) {

          if ( oLinha.id != 'ultimaLinhaHackCampos' && oLinha.style.display == '' ) {
          
            var sIdObj    = new String(oLinha.id).replace('linhaCampo','obj');
            var oObjLinha = ($(sIdObj).value).evalJSON();
            //alert(oLinha.id);
            $(oLinha.id).style.display = "none";
            $(oLinha.id).className     = "linhagrid";            
           
            aMarcados.push(oObjLinha);           
          }        
        }      
      );
      
      
      if ( aMarcados.length == 0 ) {
        alert("Nenhum campo selecionado");
        return false;
      }
      
      js_addGridTransferido(aMarcados);
    
    } 
	
	
	
  function js_addGridTransferido(aObj){

    var oRowHack          = document.createElement('tr');
    oRowHack.id           = "ultimaLinhaHackOrdem";
    oRowHack.style.height = "auto";
    oRowHack.innerHTML    = "<td style='height:auto'>&nbsp;</td>";

    $('listaOrdem').removeChild($('ultimaLinhaHackOrdem'));
    
    aObj.each(
      function (oLinha) {
      
        sTipo    = "("+oLinha.k00_tipo+") "+oLinha.k00_descr+" - "+oLinha.obs;
        var iIdLinha = oLinha.k00_numpre;          
        
        // Cria um Elemento TR
        var oRow          = document.createElement('tr');
        oRow.id           = "linhaCampoOrdem"+iIdLinha;
        oRow.className    = "linhagrid";
        oRow.style.height = "1em";
        // Cria um Elemento TD
        oCell             = document.createElement("td");
        oCell.innerHTML   = iIdLinha+" - "+sTipo;
        
        // Cria funções para observar as ações do usuario
        oCell.observe('dblclick', function() {
          js_removeOrdem("linhaCampoOrdem"+iIdLinha);
        });
        oCell.className ='linhagrid';
        oCell.observe('click', function() {          
          js_marcaLinhaOrdem("linhaCampoOrdem"+iIdLinha);
        });
        oCell.style.textAlign ='left';
        oCell.setAttribute('valor', Object.toJSON(oLinha));

        oRow.appendChild(oCell);
        $('listaOrdem').appendChild(oRow);
      }    
    );
    $('listaOrdem').appendChild(oRowHack);
        
  }
  
  /**
   *  Função que remove os NUMPRES selecionados de LISTAORDEM
   */
  function js_excluiOrdemMarcada(lTodos) {
  
    if ( lTodos ) {
      var aObjMarcados = $$('#listaOrdem tr');
    } else {
      var aObjMarcados = $$('#listaOrdem tr.marcado');
    }
    
    aObjMarcados.each(
      function(oLinhaSel) {
        
        if ( oLinhaSel.id != "ultimaLinhaHackOrdem" ) {
        
          // Configura o Nome para alterar na table #listaCampo
          var sLinhaOrdem = new String(oLinhaSel.id).replace('linhaCampoOrdem','linhaCampo');
          // Mostra ao NUMPRE na coluna listaCampo e REMOVE da coluna listaOrdem
          $(sLinhaOrdem).style.display = "";
          $(sLinhaOrdem).className     = "linhagrid";
          $('listaOrdem').removeChild(oLinhaSel);
        }
      }    
    );
    
    if ( aObjMarcados.length == 0 ) {
    
      alert("Nenhum campo selecionado!");
      return false;
    }
  }
</script>
