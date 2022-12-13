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

$cldb_depusu->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("descrdepto");
$nome = stripslashes($nome);
?>
<table border="0">
  <tr colspan="4">
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">
     <?
       db_ancora(@$Lid_usuario,"",3);echo "&nbsp";
       db_input('id_usuario',10,$Iid_usuario,true,'text',3,"");
       db_input('nome',40,$Inome,true,'text',3,'');
      ?>    
    </td>    
  </tr>
  <tr>
    <td>
      <fieldset>
        <legend>
          <b>Departamentos não vinculados ao usuário:</b>
        </legend>
        <div style="height: 400px; overflow: auto;">
        <table cellspacing="0" style="border:2px inset white; width:400px;" >
          <tr>
            <th class="table_header"><b>Código</b></th>
            <th class="table_header"><b>Descrição</b></th>
            <th class="table_header" width="12px" ><b>&nbsp;</b></th>
          </tr>
            <tbody id="listaDptos" style=" background-color:white">
            </tbody>
          <tr>
            <td class="table_footer" colspan="2" class="gridtotalizador">
              <div align="left">
                <table>
                  <tr>
                    <td><b>Total:</b></td>
                    <td id="totalLinhas" style="color: #2111f9"></td>
                  </tr>
                </table>
              </div>
            </td>
          </tr>            
        </table>    
        </div>
      </fieldset>    
    </td>
    <td>
      <table>
        <tr>
          <td>
            <input name="enviaTodos" id="enviaTodos" type="button" value=">>" title="Enviar Todos"  style='width:30px;' 
                   onClick="js_enviaTodosCampos();">
          </td>
        </tr>
        <tr>
          <td>
            <input name="envia" id="envia" type="button" value=">" title="Enviar Selecionados" style='width:30px;' 
                   onClick="js_enviaCamposMarcados();">
          </td>
        </tr>
        <tr>
          <td>
            <input name="retorna" id="retorna" type="button" value="<" title="Retirar Selecionados" style='width:30px;' 
                   onClick="js_retiraCamposMarcados();">
          </td>
        </tr> 
        <tr>
          <td>
            <input name="retornaTodos" id="retornaTodos" type="button" value="<<" title="Retirar Todos" style='width:30px;' 
                   onClick="js_retiraTodosCampos();">
          </td>
        </tr>       
      </table>    
    </td>
    <td>
      <fieldset>
      <legend>
        <b>Departamentos vinculados ao usuário:</b>
      </legend>
      <div style="height: 400px; overflow: auto;">
      <table cellspacing="0" style="border:2px inset white; width:400px;" >
        <tr>
          <th class="table_header"><b>Código</b></th>
          <th class="table_header"><b>Descrição</b></th>
          <th class="table_header" width="12px" ><b>&nbsp;</b></th>
        </tr>  
        <tbody id="dptosSelecionados" style=" height:300px; overflow:scroll; overflow-x:hidden; background-color:white">
        </tbody>
        <tr>
          <td class="table_footer" colspan="2" class="gridtotalizador">
            <div align="left">
              <table>
                <tr>
                  <td><b>Total:</b></td>
                  <td id="totalLinhasSel" style="color: #2111f9"></td>
                </tr>
              </table>
            </div>
          </td>
        </tr>         
      </table>   
      </div> 
    </fieldset>    
    </td>
    <td>
      <table>
        <tr>
          <td>
            <input name="movercima" type="button" value="^" title="Ordenar para Cima" style='width:30px;' onClick="js_moveUp();">
          </td>
        </tr>
        <tr>
          <td>
            <input name="moverbaixo" type="button" value="v" title="Ordenar para Baixo" style='width:30px;' onClick="js_moveDown();">
          </td>
        </tr>   
      </table>    
    </td>            
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr> 
  <tr>
    <td colspan="4" align="center">
      <input name="atualizar" type="button" value="Atualizar" onClick="js_AtualizaDepartamentos(<?=$iCodUsuario?>);">
    </td>
  </tr>  
</table>
<script>
function js_pesquisaDepartamentos(iCodUsuario) {

  js_divCarregando('Aguarde, Pesquisando Departamentos...','msgBoxListaDepartamentos');

  var oParam            = new Object();
      oParam.exec       = "listarDepartamentos";
      oParam.codusuario = iCodUsuario;
  var oAjax             = new Ajax.Request(
                       "con1_departamentos.RPC.php",
                      {
                        method    : 'post',
                        parameters: 'json='+Object.toJSON(oParam), 
                        onComplete: js_retornoPesquisaDepartamentos
                      }
                    );
}

function js_retornoPesquisaDepartamentos(oAjax) {

  js_removeObj('msgBoxListaDepartamentos');
  
  $('listaDptos').innerHTML         = "";
  $('dptosSelecionados').innerHTML  = "";
  
  var aRetorno = eval("("+oAjax.responseText+")");
  
  $('listaDptos').innerHTML  = js_carregaGridDeptos(aRetorno.aItensDptos);

  js_carregaGridDeptosSelecionados(aRetorno.aItensDptosSel); 
  js_verificaTotalLinhas();

}

function js_AtualizaDepartamentos(iCodUsuario) {


  var iOrdem            = 1;
  var oParam            = new Object();
      oParam.exec       = "atualizarDepartamentos";
      oParam.codusuario = iCodUsuario;
      oParam.aDptoSel   = new Array();
      
  var aItensSel = $('dptosSelecionados').rows;      
    
  if ( aItensSel.length == 1 ) {
    alert('Nenhum departamento selecionado!');
    return false;
  } 
   
  for (var i = 0; i < ( aItensSel.length -1 ); i++) {
   
    var oDptosel           = new Object();
        oDptosel.iDptoSel  = aItensSel[i].cells[0].innerHTML;
        oDptosel.iOrdem    = iOrdem++;
        oParam.aDptoSel.push(oDptosel);
       
  }   

  js_divCarregando('Aguarde, Atualizando Departamentos...','msgBoxAtualizaDepartamentos');
      
  var oAjax           = new Ajax.Request(
                       "con1_departamentos.RPC.php",
                      {
                        method    : 'post',
                        parameters: 'json='+Object.toJSON(oParam), 
                        onComplete: js_retornoAtualizaDepartamentos
                      }
                    );
}

function js_retornoAtualizaDepartamentos(oAjax) {

  js_removeObj('msgBoxAtualizaDepartamentos');

  var iCodUsuario = $F('id_usuario'); 
  var aRetorno    = eval("("+oAjax.responseText+")");
  
  if (aRetorno.erro == 1) {
  
    alert(aRetorno.msg.urlDecode());
    return false;
  } else {
    
    sMsg = "Usuário: \nInclusao efetuada com Sucesso \nAdministrador: \n";
    alert(sMsg);
    js_pesquisaDepartamentos(iCodUsuario);
  }  
}

function js_carregaGridDeptos(aItens){
    
  var sLinha   = "";
  var iNumRows = aItens.length;
  
  if(iNumRows > 0){
    aItens.each(
      function (oItens){

        var iCod        = oItens.coddepto;
        var sDescrDpto  = oItens.descrdepto; 
        var sAtributos  = " class='linhagrid'"; 
            sAtributos += " onDblClick='js_enviaCampo(\"linhaCampo"+iCod+"\");'";   
            sAtributos += " onClick='js_marcaLinha(\"linhaCampo"+iCod+"\",\"marcaEnvia\",true);'";  
            sAtributos += " style='text-align:left; -moz-user-select:none;'";
            
            sLinha += " <tr id='linhaCampo"+iCod+"'>";        
            sLinha += "   <td "+sAtributos+" >"+iCod+"</td> ";
            sLinha += "   <td "+sAtributos+" >"+sDescrDpto.urlDecode()+"</td> ";
            sLinha += " </tr> ";        
        
      }
    );
    
    sLinha += "<tr id='ultimaLinha' ><td colspan='2' style='height:100%;'>&nbsp;</td></tr>";
  }
    
  return sLinha;
}

function js_carregaGridDeptosSelecionados(aItens){
  
  var sLinha      = "";
  var iNumRows    = aItens.length;

  if ($('ultimaLinhaDisp') === null) {
  
    sUltimaLinha = "<tr id='ultimaLinhaDisp'><td colspan='2' style='height:100%;'>&nbsp;</td></tr>";
    $('dptosSelecionados').innerHTML  += sUltimaLinha;
  }

  if(iNumRows > 0) {
    aItens.each(
      function (oItens,iInd){
     
        var iCodDptoSel = oItens.coddepto;

        if ( iCodDptoSel != "" ) {
          
	        var sDescrDpto  = oItens.descrdepto; 
	        var sClassName  = 'linhagrid'; 
	            
	        var oLinha = document.createElement("TR");
	            oLinha.id  ='linhaCampoSel'+iCodDptoSel;
	            
	            var oCelula1             = document.createElement("TD");
	            oCelula1.className       = sClassName;
	            oCelula1.style.textAlign = 'text-align:left;';
	            oCelula1.id              = "codigo"+iCodDptoSel;
	            
	            oCelula1.onclick         = function() {
	               js_marcaLinha("linhaCampoSel"+iCodDptoSel, "marcaRetira",false);
	            }
	            
	            oCelula1.ondblclick     = function() {
                 js_retiraCampoSel(new Array(oItens));
              }
	            
	            oCelula1.innerHTML += iCodDptoSel;
              oLinha.appendChild(oCelula1);
              	
              	
              var oCelula2          = document.createElement("TD");
              oCelula2.className    = sClassName;
              oCelula2.id           = "codigo"+iCodDptoSel;
              
              oCelula2.onclick      = function() {
                 js_marcaLinha("linhaCampoSel"+iCodDptoSel, "marcaRetira",false);
              }
              
              oCelula2.ondblclick   = function() {
                 js_retiraCampoSel(new Array(oItens));
              }
                
              oCelula2.innerHTML    = sDescrDpto.urlDecode();
              oLinha.appendChild(oCelula2);
               
              var oUltimaLinha      =   $('ultimaLinhaDisp'); 
              $('dptosSelecionados').insertBefore(oLinha, oUltimaLinha);
	            
	            $("linhaCampo"+iCodDptoSel).style.display = "none";
	               
	            js_marcaLinha('linhaCampoSel'+iCodDptoSel,'marcaSel',false); 
	            js_verificaTotalLinhas();
        
        }
      }
    );  
  }
    
  return true;
}

function js_marcaLinha(iCod,sTipoMarca,lDesmarca) {

  if ($(iCod).className != sTipoMarca) {
    $(iCod).className = sTipoMarca; 
  } else {
  
    if (lDesmarca) {
      $(iCod).className = 'linhagrid';         
    } else {
      $(iCod).className = 'marcaSel';
    }
  }
}

function js_enviaCampo(idCampo){
    
  var aObjCampos = new Array($(idCampo));
  js_enviaCamposSel(aObjCampos);
        
} 

function js_enviaCamposMarcados(){
    
  var aObjCampos = js_getElementbyClass($('listaDptos').rows,'marcaEnvia');
  js_enviaCamposSel(aObjCampos);
        
}

function js_enviaTodosCampos() {
    
  var objMarcados      = $('listaDptos').rows;  
  var aMarcados        = new Array();
  var iLinhasMarcados  = new Number(objMarcados.length); 

  if ( iLinhasMarcados > 0 ) {
   
    for (var iInd=0; iInd < iLinhasMarcados; iInd++ ) {

      var sIdLinha = $(objMarcados[iInd]).id;
      
      if ( sIdLinha != 'ultimaLinha' ) {
      
        var sDisplay = $(objMarcados[iInd]).style.display;   
        
        if ( sDisplay != 'none' ) {
        
		      aMarcados[iInd] = new Object();
		      aMarcados[iInd].coddepto   = $(objMarcados[iInd]).cells[0].innerHTML;
		      aMarcados[iInd].descrdepto = $(objMarcados[iInd]).cells[1].innerHTML;
		      
        }   
      }
    }   
  
  } else {
    alert('Nenhum campo selecionado!');
    return false;
  }
  
  js_carregaGridDeptosSelecionados(aMarcados);
  js_verificaTotalLinhas();

}

function js_enviaCamposSel(objMarcados){
  
  var aMarcados       = new Array();
  var iLinhasMarcados = new Number(objMarcados.length); 

  if ( iLinhasMarcados > 0 ) {
   
    for (var iInd=0; iInd < iLinhasMarcados; iInd++ ) {

      $(objMarcados[iInd].id).style.display = "none";
      $(objMarcados[iInd].id).className     = "linhagrid";

      aMarcados[iInd] = new Object();
      aMarcados[iInd].coddepto   = $(objMarcados[iInd]).cells[0].innerHTML;
      aMarcados[iInd].descrdepto = $(objMarcados[iInd]).cells[1].innerHTML; 
    }
    
    js_carregaGridDeptosSelecionados(aMarcados);
    js_verificaTotalLinhas();

  } else {
    alert('Nenhum campo selecionado!');
    return false;
  } 
}

function js_retiraTodosCampos() {
    
  var objMarcados      = js_getElementbyClass($('dptosSelecionados').rows,'marcaSel');
  var aMarcados        = new Array();
  var iLinhasMarcados  = new Number(objMarcados.length); 

  if ( iLinhasMarcados > 0 ) {
   
    for (var iInd=0; iInd < iLinhasMarcados; iInd++ ) {

      aMarcados[iInd] = new Object();
      aMarcados[iInd].coddepto   = $(objMarcados[iInd]).cells[0].innerHTML;
    }   
  
  } else {
    alert('Nenhum campo selecionado!');
    return false;
  }
  
  js_retiraCampoSel(aMarcados);

}

function js_retiraCamposMarcados(){
    
  var objMarcados      = js_getElementbyClass($('dptosSelecionados').rows,'marcaRetira');
  var aMarcados        = new Array();
  var iLinhasMarcados  = new Number(objMarcados.length); 

  if ( iLinhasMarcados > 0 ) {
   
    for (var iInd=0; iInd < iLinhasMarcados; iInd++ ) {

      aMarcados[iInd] = new Object();
      aMarcados[iInd].coddepto   = $(objMarcados[iInd]).cells[0].innerHTML;
    }   
  
  } else {
    alert('Nenhum campo selecionado!');
    return false;
  }
  
  js_retiraCampoSel(aMarcados);
        
}

function js_retiraCampoSel(objMarcados) {  
  
  var iNumRows    = objMarcados.length;

  if(iNumRows > 0) {
    objMarcados.each(
      function (oItens,iInd){
     
        var iCodDptoSel = oItens.coddepto;

            $("linhaCampo"+iCodDptoSel).style.display = "";
            $('dptosSelecionados').removeChild($("linhaCampoSel"+iCodDptoSel));

      }
    );  
  }
  
  js_verificaTotalLinhas(); 
    
}

function js_moveUp(){
    
  var objMarcados = js_getElementbyClass($('dptosSelecionados').rows,'marcaRetira');

  if (objMarcados.length > 1 ) {
    alert("Favor escolha apenas uma linha");
    return false;
  } else if (objMarcados.length == 0) {
    return false;
  } 
    
    
  var iRow    = objMarcados[0];
  var tbody  = $('dptosSelecionados');
  var iRowId  = iRow.rowIndex;
  var hTable = tbody.parentNode;
  var nextId = iRowId-1;
    
  if (nextId == 0)  {
    return false;
  }
      
  var next = hTable.rows[nextId];
  tbody.removeChild(iRow);
  tbody.insertBefore(iRow, next);
    
} 

function js_moveDown(){
  
  var objMarcados = js_getElementbyClass($('dptosSelecionados').rows,'marcaRetira'); 

  if (objMarcados.length > 1 ) {
    alert("Favor escolha apenas uma linha");
    return false;
  } else if (objMarcados.length == 0) {
    return false;     
  } 
      
  var iRow   = objMarcados[0];
  var tbody  = $('dptosSelecionados');
  var iRowId = iRow.rowIndex;
  var hTable = tbody.parentNode;
  var nextId = parseInt(iRowId)+2;     
      
  if (nextId > hTable.rows.length-2 ) {
     return false;
  }
    
  var next = hTable.rows[nextId];
  tbody.removeChild(iRow);
  tbody.insertBefore(iRow, next);
   
}

function js_verificaTotalLinhas() {

  var iNumRowsTabDptos = new Number($('listaDptos').rows.length); 
  
  var objDptosSel      = js_getElementbyClass($('dptosSelecionados').rows,'marcaSel');
  var iNumRowsTabSel   = new Number(objDptosSel.length);
  
  var iNumRowsSel      = (iNumRowsTabSel);
  var iNumRowsDptos    = ((iNumRowsTabDptos-1)-iNumRowsSel);
  
  $('totalLinhasSel').innerHTML = "<b>"+iNumRowsSel+"</b>";
  $('totalLinhas').innerHTML    = "<b>"+iNumRowsDptos+"</b>";  
}   
</script>