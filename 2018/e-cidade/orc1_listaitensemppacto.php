<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
require("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_empempitem_classe.php");

$oGet = db_utils::postMemory($_GET);

$clempempitem = new cl_empempitem;
$numemp       = $oGet->numemp;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post" action="" >
<table style="padding-top:25px;" align="center" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
	  <td>
	    <fieldset>
	      <legend align="center">
	        <b>Itens do Empenho : <?=$oGet->numemp?></b>
	      </legend>
	      <table  cellspacing="0" style="border:2px inset white;" >
	        <tr>
	          <th class="table_header" width="20px" ><a href="#" onclick="js_marcaTodos();">M</a></th>
	          <th class="table_header" width="100px"><b>Cód. Item</b></th>
	          <th class="table_header" width="270px"><b>Descrição</b></th>
	          <th class="table_header" width="270px"><b>Obs</b></th>
	          <th class="table_header" width="100px"><b>Valor Unitário </b></th>
	          <th class="table_header" width="70px" ><b>Qtd.</b></th>
	          <th class="table_header" width="100px"><b>Valor Total</b></th>
	          <th class="table_header" width="12px" ><b>&nbsp;</b></th>
	        </tr>  
	        <tbody id="listaItensEmp" style=" height:300px; overflow:scroll; overflow-x:hidden; background-color:white"  >
			      <?
		 		      if(isset($oGet->numemp)){
					      	
					      if ( isset($oGet->numemp) && trim($oGet->numemp) != "" ) {
					          $sWhere = " e62_numemp = {$oGet->numemp} ";        	
					      }
					
					      $rsListaItensEmp = $clempempitem->sql_record($clempempitem->sql_query("","","*","e62_sequen",$sWhere));
		            $iLinhasItensEmp = $clempempitem->numrows;
		            
					      if ( $iLinhasItensEmp > 0 ) {
					      	
					      	for ( $iInd=0; $iInd < $iLinhasItensEmp; $iInd++) {
					      		
					      		$oItensEmp = db_utils::fieldsMemory($rsListaItensEmp,$iInd);
					      		
					      		echo "<tr id='{$oItensEmp->e62_sequencial}' >";
					      		echo "  <td class='linhagrid' ><input type='checkbox' name='chk_{$oItensEmp->e62_sequencial}'/></td>";
                    echo "  <td class='linhagrid' >{$oItensEmp->e62_item}</td>";
                    echo "  <td class='linhagrid' >{$oItensEmp->pc01_descrmater}</td>";
                    echo "  <td class='linhagrid' >{$oItensEmp->e62_descr}</td>";                    
                    echo "  <td class='linhagrid' >".db_formatar($oItensEmp->e62_vlrun,"f")."</td>";
                    echo "  <td class='linhagrid' >{$oItensEmp->e62_quant}</td>";
                    echo "  <td class='linhagrid' >".db_formatar($oItensEmp->e62_vltot,"f")."</td>";                    					      		
					      		echo "</tr>";
					      		
					      	}
					      }
					        
					    }
					    db_input('numemp',  10,'',true,'hidden');
					    db_input('codpacto',10,'',true,'hidden');
			      ?>
          </tbody>
	      </table>    
	    </fieldset>
	  </td>  
  </tr>
  <tr align="center">
    <td>
       <input type="button" value="Enviar" onClick="js_enviaItens();"/>
    </td>
  </tr>
</table>
</body>
</html>
<script>

  function objItemEmp(iNumEmp,iSeq,iCodItem,sDescr,sObs,nVlrUni,iQtd,nVlrTot){
  
    this.e62_numemp      = iNumEmp; 
    this.e62_sequencial  = iSeq;
    this.e62_item        = iCodItem;
    this.e62_item        = iCodItem;
    this.pc01_descrmater = sDescr;
    this.e62_descr       = sObs;
    this.e62_vlrun       = nVlrUni; 
    this.e62_quant       = iQtd;
    this.e62_vltot       = nVlrTot;
  
  }
  
  function js_enviaItens(){
  
    var objInput    = document.getElementsByTagName('input');      
    var aListaItens = new Array();
    var iNumEmp     = document.form1.numemp.value;
    var x           = new Number(0);
    var lErro       = true; 
         
    for (var iInd=0; iInd < objInput.length; iInd++ ) {
    
      if ( objInput[iInd].type == 'checkbox' && objInput[iInd].checked == true ) {
         
        var iSeq     = objInput[iInd].name.replace('chk_','');
        var objLinha = document.getElementById(iSeq);
        
        var iCodItem = new String(objLinha.cells[1].innerHTML);
        var sDescr   = new String(objLinha.cells[2].innerHTML);
        var sObs     = new String(objLinha.cells[3].innerHTML);
        var nVlrUni  = new js_strToFloat(objLinha.cells[4].innerHTML);
        var iQtd     = new String(objLinha.cells[5].innerHTML);
        var nVlrTot  = new js_strToFloat(objLinha.cells[6].innerHTML);
        
        var objItem  = new objItemEmp(iNumEmp,iSeq,iCodItem,sDescr,sObs,nVlrUni.valueOf(),iQtd,nVlrTot.valueOf());
        
        aListaItens[x]= objItem;
        x++;
        lErro = false;
      }
      
    }
    
    if ( lErro ) {
      alert('Nenhum item selecionado!');
      return false;
    }
    parent.document.form1.numemp.value = '';
    parent.js_montaListaManual(aListaItens);
    parent.db_iframe_listaitememp.hide();
    
  }
  
  function js_marcaTodos(){
  
    var objInput = document.getElementsByTagName('input');
    
    for (var iInd=0; iInd < objInput.length; iInd++ ) {
      if ( objInput[iInd].type == 'checkbox' && objInput[iInd].checked == true ) {
        objInput[iInd].checked = false;
      } else {
        objInput[iInd].checked = true;
      }
    }    
  
  }
  
</script>