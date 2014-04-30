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
?>
<form name="form1" method="post" action="">
<center>
  <table>
    <tr>
      <td>
        <fieldset>
		      <legend align="center">
		        <b>Bens:</b>
		      </legend>
		      <table  cellspacing="0" style="border:2px inset white;" >
		        <tr>
		          <th class="table_header" width="15px" ><a href='#' onClick='js_marcaTodos();'>M</a></th>
		          <th class="table_header" width="150px"><b>Cód. Bem       </b></th>
		          <th class="table_header" width="100px" ><b>Classificação </b></th>
		          <th class="table_header" width="150px"><b>Descrição      </b></th>
		          <th class="table_header" width="85px" ><b>Observação     </b></th>
		          <th class="table_header" width="85px" ><b>Placa          </b></th>
		          <th class="table_header" width="85px" ><b>Data Aquisição </b></th>
		          <th class="table_header" width="12px" ><b>&nbsp;         </b></th>
		        </tr>  
		        <tbody id="listaBens" style=" height:300px; overflow:scroll; overflow-x:hidden; background-color:white"  >
		          </tbody>
		      </table>    
        </fieldset>      
      </td>
    </tr>  
    <tr align="center">
      <td>
        <input name="transf" type="submit"  value="Transferência em Lote" onClick="return js_valida();"    >
        <input name="rel"    type="button"  value="Relatório"             onClick="js_imprime();" disabled >
        <?
          db_input("t95_codtran",10,"",true,"hidden");
          db_input("lista"      ,10,"",true,"hidden");
        ?>
      </td>
    </tr>  
  </table>
</center>
</form>

<script>

function js_pesquisaBens(){

	
  js_divCarregando(_M('patrimonial.patrimonio.db_frmbenstransfcodigolote.aguarde'),'msgBox');
   
  var url          = "pat4_consultaBensDeptoRPC.php";
  var sQuery       = "iCodTransf="+document.form1.t95_codtran.value;
  var oAjax        = new Ajax.Request( url, {
                                              method: 'post', 
                                              parameters: sQuery, 
                                              onComplete: js_retornoBens
                                            }
                                      );  

}

function js_retornoBens(oAjax){
  
  js_removeObj("msgBox");
  var objListaBens = eval("("+oAjax.responseText+")");
    
  if ( objListaBens.lErro && objListaBens.lErro == true ){
    alert(objListaBens.sMensagem.urlDecode());
    return false ;
  }
  
  
  var iLinhasBens = objListaBens.length;
  var sLinha      =  "";
  
  
  for ( var iInd = 0; iInd < iLinhasBens; iInd++ ) {
  
    with (objListaBens[iInd]) {
      
      var sChecked = "";
      
      if ( transf == "t" ) {
        
        sChecked = "checked";
        document.form1.rel.disabled = false;
        document.form1.transf.value = "Alterar";
      }
      
      sCheck = "<input class='chk' type='checkbox' id='"+t52_bem.urlDecode()+"|"+situacao.urlDecode()+"' "+sChecked+">";
      
      sLinha +=  "<tr>";
      sLinha +=  "  <td class='linhagrid'>"+sCheck+"                                      </td>";
      sLinha +=  "  <td class='linhagrid'>"+t52_bem.urlDecode()+"&nbsp;                   </td>"; 
      sLinha +=  "  <td class='linhagrid'>"+t64_descr.urlDecode()+"&nbsp;                 </td>";
      sLinha +=  "  <td class='linhagrid'>"+t52_descr.urlDecode()+"&nbsp;                 </td>";
      sLinha +=  "  <td class='linhagrid'>"+t52_obs.urlDecode()+"&nbsp;                   </td>";
      sLinha +=  "  <td class='linhagrid'>"+t52_ident.urlDecode()+"&nbsp;                 </td>";
      sLinha +=  "  <td class='linhagrid'>"+js_formatar(t52_dtaqu.urlDecode(),"d")+"&nbsp;</td>";
      sLinha +=  "</tr>";          
      
    }
  }
    
  $('listaBens').innerHTML = sLinha;
    
  js_removeObj("msgBox");
  
}

function js_valida(){

  var aObjChk    = js_getElementbyClass(document.form1,"chk");
  var iLinhas    = aObjChk.length;
  var aListaBens = new Array();
  var iIndice    = 0; 
  document.form1.lista.value = "";
   
  for ( var iInd=0; iInd < iLinhas; iInd++ ) {
  
    if ( aObjChk[iInd].checked == true  ) {
       aListaBens[iIndice] = aObjChk[iInd].id;
       iIndice++;  
    }
    
  }
  

  if ( aListaBens.length > 0 ) {
    document.form1.lista.value = aListaBens.toString();
  } else {
    alert(_M('patrimonial.patrimonio.db_frmbenstransfcodigolote.selecione_bem'));
    return false;
  }


}



function js_imprime(){

  jan = window.open('pat2_relbenstransf002.php?t96_codtran='+document.form1.t95_codtran.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);

}


function js_marcaTodos(){
  
  var aObjChk = js_getElementbyClass(document.form1,"chk");
  var iLinhas = aObjChk.length;
  
  for ( var iInd=0; iInd < iLinhas; iInd++ ) {
    if ( aObjChk[iInd].checked == true  ) {
      aObjChk[iInd].checked = false;
    } else {
      aObjChk[iInd].checked = true;
    }
  }
  
}
</script>