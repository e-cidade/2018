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
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_pagordem_classe.php");
include("classes/db_pactovalor_classe.php");
include("classes/db_pactovalormovsolicitem_classe.php");
include("classes/db_pactovalormov_classe.php");
include("classes/db_pactovalormovempempitem_classe.php");
include("classes/db_pactovalorsaldo_classe.php");
include("libs/JSON.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);
$oJson = new services_json();

$clpactovalor              = new cl_pactovalor();
$clpactovalormovsolicitem  = new cl_pactovalormovsolicitem();
$clpactovalormov           = new cl_pactovalormov();
$clpactovalormovempempitem = new cl_pactovalormovempempitem();
$clpactovalorsaldo         = new cl_pactovalorsaldo();
$clpagordem                = new cl_pagordem();


if ( isset($oPost->incluir) ) {
	
	$lSqlErro = false;
	
	db_inicio_transacao();

	$aObjItens = $oJson->decode(str_replace("\\","",$oPost->listaitensemp));
	
	$aListaEmp = array();
	
	$rsConsultaItemEmp = $clpactovalormovempempitem->sql_record($clpactovalormovempempitem->sql_query(null,"*",null," o88_pactovalor = {$oPost->codpacto} "));
        $iLinhasItemEmp    = $clpactovalormovempempitem->numrows; 
  
  if ( $iLinhasItemEmp > 0 ) {
    
    for ( $iInd=0; $iInd < $iLinhasItemEmp; $iInd++) {
      
      $oItem = db_utils::fieldsMemory($rsConsultaItemEmp,$iInd);
      
      $clpactovalormovempempitem->excluir($oItem->o105_sequencial);
      
      if ( $clpactovalormovempempitem->erro_status == 0 ) {
        $lSqlErro = true;
      }
      
      $sMsgErro = $clpactovalormovempempitem->erro_msg;
      
    }
    
    if ( !$lSqlErro ) {
      
      $sWhere = "o101_pactovalormov in ( select o88_sequencial 
    	                                     from pactovalormov 
    	                                    where o88_pactovalor = {$oPost->codpacto} ) ";
      $clpactovalormovsolicitem->excluir(null,$sWhere);
      
      if ( $clpactovalormovsolicitem->erro_status == 0 ) {
        $lSqlErro = true;
      }
      
      $sMsgErro = $clpactovalormovsolicitem->erro_msg;     
      
    }
    
    if ( !$lSqlErro ) {
      
      $clpactovalormov->excluir(null," o88_pactovalor = {$oPost->codpacto} ");
      
      if ( $clpactovalormov->erro_status == 0 ) {
        $lSqlErro = true;
      }
      
      $sMsgErro = $clpactovalormov->erro_msg;     
      
    }

    if ( !$lSqlErro ) {
    	
      /**
       * excluimos o vinculo dos dados da tabela pactovalorsaldopagordem 
       */
      
      $sDeleteOP  = "delete from";
      $sDeleteOP .= "       pactovalorsaldopagordem ";
      $sDeleteOP .= " using pactovalorsaldo ";
      $sDeleteOP .= " where o110_pactovalorsaldo = o103_sequencial        ";
      $sDeleteOP .= "   and o103_pactovalor = {$oPost->codpacto}";
      $sDeleteOP .= "   and o103_pactovalorsaldotipo = 2    ";
      $rsDeleteOP = db_query($sDeleteOP);
      if (!$rsDeleteOP) {
        
        $sMsgErro  = "Erro ao alterar dados do item do pacto\\n";
        $sMsgErro .= pg_last_error();
        $lSqlErro = true;
      }
      $clpactovalorsaldo->excluir(null," o103_pactovalor = {$oPost->codpacto} and o103_pactovalorsaldotipo = 2");
      if ( $clpactovalorsaldo->erro_status == 0 ) {
        $lSqlErro = true;
      }
      $sMsgErro = $clpactovalorsaldo->erro_msg;     
      
    }    
    
  }
  
	if ( !$lSqlErro ) {
	
		foreach ($aObjItens as $iInd => $oItem ){
	  
			$clpactovalormov->o88_pactovalor = $oPost->codpacto;   
			$clpactovalormov->o88_quantidade = $oItem->iQtd;
			$clpactovalormov->incluir(null);
			
			if ( $clpactovalormov->erro_status == 0) {
		    $lSqlErro = true;		
			}
			
	    $sMsgErro = $clpactovalormov->erro_msg;		
			
	    
	    if ( !$lSqlErro ) {
	       
	    	$clpactovalormovempempitem->o105_pactovalormov = $clpactovalormov->o88_sequencial;
	    	$clpactovalormovempempitem->o105_empempitem    = $oItem->iSeq;
	      $clpactovalormovempempitem->incluir(null);
	    	
		    if ( $clpactovalormovempempitem->erro_status == 0) {
		      $lSqlErro = true;   
		    }
		    
		    $sMsgErro = $clpactovalormovempempitem->erro_msg;      
	    }
			
	    if ( isset($aListaEmp[$oItem->iNumEmp])){ 
	      $aListaEmp[$oItem->iNumEmp]['nTotal'] += $oItem->nTotal; 
	    } else {
	      $aListaEmp[$oItem->iNumEmp]['nTotal']  = $oItem->nTotal;
	    }
	    
		}
		
		foreach ( $aListaEmp as $iCodEmp => $aValor ) {
			
			$sCampos  = " e50_codord, ";
			$sCampos .= " e50_numemp, ";
			$sCampos .= " e50_data,   ";
			$sCampos .= " e53_vlrpag  ";
			
		  $rsConsultaPag = $clpagordem->sql_record($clpagordem->sql_query_emp(null,$sCampos,null," e50_numemp = {$iCodEmp} "));
		  $iLinhasPag    = $clpagordem->numrows;
		  
		  if ( $iLinhasPag > 0 ) {
		  	
	      for ( $iInd=0; $iInd < $iLinhasPag; $iInd++ ) {
	      	
	      	$oPag = db_utils::fieldsMemory($rsConsultaPag,$iInd);
	      	
	      	if ( $oPag->e53_vlrpag > 0 ) {
	      		
	      		// Verifica o percentual pago sobre os itens selecionados de cada empenho e grava um registro 
	      		// na tabela pactovalorsaldo para cada OP.   
	      		
	      		$nPercentualPag = ($oPag->e53_vlrpag*100)/$aValor['nTotal'];
	      		$nValorPercPag  = ($aValor['nTotal']/100)*$nPercentualPag;
	
	      		$sSqlLanc  = " select c82_reduz,                                          ";
	      		$sSqlLanc .= "        c70_data                                            ";
	      		$sSqlLanc .= "   from conlancamord                                        "; 
	      		$sSqlLanc .= "        inner join conlancam    on c80_codlan = c70_codlan  "; 
	      		$sSqlLanc .= "        inner join conlancampag on c82_codlan = c80_codlan  ";
	      		$sSqlLanc .= "        inner join conlancamdoc on c71_codlan = c70_codlan  ";
	      		$sSqlLanc .= "        inner join conhistdoc   on c71_coddoc = c53_coddoc  ";
	      		$sSqlLanc .= "  where c80_codord = {$oPag->e50_codord}                    ";
	      		$sSqlLanc .= "    and c53_tipo   = 30                                     ";
	      		$sSqlLanc .= "  order by c80_data desc limit 1;                           ";
	
	      		$rsLanc      = db_query($sSqlLanc);
	      		$iLinhasLanc = pg_num_rows($rsLanc);
	      		
	      		if ( $iLinhasLanc > 0 ) {
	      			
	      			$oLanc = db_utils::fieldsMemory($rsLanc,0);
	      			
	      			$rsConsultaConv = $clpactovalor->sql_record($clpactovalor->sql_query($oPost->codpacto,"o16_saltes"));
	      			$oConvenio      = db_utils::fieldsMemory($rsConsultaConv,0);

	        		$aDataPag = explode("-",$oLanc->c70_data);
	        		
              if ($aDataPag[0] == 2013){
                $iContaBird = 21443;
              }else{
                $iContaBird = 17964;
              }

	      			if ( $oConvenio->o16_saltes == $oLanc->c82_reduz || $oLanc->c82_reduz == $iContaBird) {
	      			  $lContrapartida = 'false';
	      			} else {
                $lContrapartida = 'true';
	      			}
	      			
			        $clpactovalorsaldo->o103_pactovalor          = $oPost->codpacto;
			        $clpactovalorsaldo->o103_anousu              = $aDataPag[0];
			        $clpactovalorsaldo->o103_mesusu              = $aDataPag[1];
			        $clpactovalorsaldo->o103_pactovalorsaldotipo = 2;
			        $clpactovalorsaldo->o103_valor               = $nValorPercPag;
			        $clpactovalorsaldo->o103_contrapartida       = $lContrapartida;
			        $clpactovalorsaldo->incluir(null);
		          
              if ( $clpactovalorsaldo->erro_status == 0) {
                $lSqlErro = true;   
              }
				
              $sMsgErro = $clpactovalorsaldo->erro_msg;
              if (!$lSqlErro) {
				  
                $oDaoPactoSaldoORdem = db_utils::getDao("pactovalorsaldopagordem");
                $oDaoPactoSaldoORdem->o110_pactovalorsaldo =  $clpactovalorsaldo->o103_sequencial;	           
                $oDaoPactoSaldoORdem->o110_pagordem        =  $oPag->e50_codord;
                $oDaoPactoSaldoORdem->incluir(null);
                if ($oDaoPactoSaldoORdem->erro_status == 0) {
                  
                  $lSqlErro = true;
                  $sMsgErro = $oDaoPactoSaldoORdem->erro_msg;   
                }
				      }
	      		}
	      	}
	      }
		  }
		}
	}
	db_fim_transacao($lSqlErro);
  
} else if (isset($oGet->codpacto)) {
	
   $codpacto = $oGet->codpacto;

   $sCampos  = " distinct e62_numemp, ";          
   $sCampos .= " e62_sequencial,  "; 
   $sCampos .= " e62_item,        ";
   $sCampos .= " e62_item,        ";
   $sCampos .= " pc01_descrmater, ";
   $sCampos .= " e62_descr,       ";
   $sCampos .= " e62_vlrun,       ";
   $sCampos .= " e62_quant,       ";
   $sCampos .= " e62_vltot        ";
   
   $sWhere   = " o87_sequencial = {$oGet->codpacto} ";
   
   $rsConsultaItens = $clpactovalormovempempitem->sql_record($clpactovalormovempempitem->sql_query(null,$sCampos,"e62_numemp",$sWhere));
   $iLinhasItens    = $clpactovalormovempempitem->numrows;
   $aLinhasItens    = array();
   
   if ( $iLinhasItens > 0 ) {
   	
   	 for ( $iInd=0; $iInd < $iLinhasItens; $iInd++ ) {
   	 	
           $oItens         = db_utils::fieldsMemory($rsConsultaItens,$iInd);
           $aLinhasItens[] = $oItens;	 	
   	 }
   	 
     $listaitensemp = str_replace('"',"'",$oJson->encode($aLinhasItens));
     
   }

}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<!--<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>-->
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_verificaLista();">
<form name="form1" method="post" action="" >
<table style="padding-top:25px;" align="center" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td>  
      <fieldset>
        <legend>
          <b>Selecionar Empenho</b>
        </legend>
        <table>
          <tr>
            <td width="110px;">
              <b>
                <?
                  db_ancora("Empenho:","js_listaEmpenhos();",1);
                ?>
              </b>
            </td>
            <td>
              <?
                db_input("numemp"       ,10,""  ,true,"text"  ,1,"onChange='js_habilitaBotaoItemEmp();'");
                db_input("listaitensemp",5000,"",true,"hidden",1);
                db_input("codpacto"     ,50,""  ,true,"hidden",1);
              ?>
              <input type="button" id="listaItensEmp" name="listaItensEmp" value="Lista Itens" onClick="js_listaItensEmpenho();" disabled="true">          
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>  
  <tr> 
	  <td>
	    <fieldset>
	      <legend>
	        <b>Itens Selecionados:</b>
	      </legend>
	      <table  cellspacing="0" style="border:2px inset white;" >
	        <tr>
	          <th class="table_header" width="70px" ><b>Empenho</b></th>
            <th class="table_header" width="70px"><b>Cód. Item</b></th>
            <th class="table_header" width="250px"><b>Descrição</b></th>
            <th class="table_header" width="250px"><b>Obs</b></th>            
            <th class="table_header" width="85px"><b>Vlr. Unitário </b></th>
            <th class="table_header" width="70px" ><b>Qtd.</b></th>
            <th class="table_header" width="85px"><b>Vlr. Total</b></th>
            <th class="table_header" width="60px"><b>&nbsp;</b></th>
	          <th class="table_header" width="12px" ><b>&nbsp;</b></th>
	        </tr>  
	        <tbody id="listaItens" style=" height:300px; overflow:scroll; overflow-x:hidden; background-color:white"  >
          </tbody>
	      </table>    
	    </fieldset>
	  </td>  
  </tr>
  <tr align="center">
    <td>
      <input type="submit" name="incluir" value="Incluir" onClick="return js_incluirItens();">
    </td>
  </tr>
</table>
</form>
</body>
</html>
<script>

aEmpenhos = new Array(); 

function js_objItem(iNumEmp,iSeq,iCodItem,iQtd,nTotal){
 
	this.iNumEmp  = iNumEmp;
	this.iSeq     = iSeq;
	this.iCodItem = iCodItem;
	this.iQtd     = iQtd;
	this.nTotal   = nTotal;

} 

function js_incluirItens(){
 
  var objLista    = document.getElementById('listaItens'); 
  var aListaItens = new Array();
  
  for ( var iInd=0; iInd < objLista.rows.length; iInd++ ) {
    
    var iSeq     = objLista.rows[iInd].id;
    var iNumEmp  = objLista.rows[iInd].cells[0].innerHTML;
    var iCodItem = objLista.rows[iInd].cells[1].innerHTML;
    var iQtd     = objLista.rows[iInd].cells[5].innerHTML;
    var nTotal   = js_strToFloat(objLista.rows[iInd].cells[6].innerHTML);
    var objItem  = new js_objItem(iNumEmp,iSeq,iCodItem,iQtd,nTotal.valueOf());
    
    aListaItens[iInd] = objItem;

  }

  document.form1.listaitensemp.value = aListaItens.toSource();
  
}

function js_excluiLinha(idLinha){
  
  var objLinha = document.getElementById(idLinha);
  document.getElementById('listaItens').removeChild(objLinha);
  
  for ( iInd in aEmpenhos ) {
    for ( iIndEmp in aEmpenhos[iInd] ) {
      if ( aEmpenhos[iInd][iIndEmp].e62_sequencial == idLinha ){
         aEmpenhos[iInd][iIndEmp] = null;
      }
    }
  }
    
}

function js_montaListaManual(aObjItens){
 
  aEmpenhos[aObjItens[0].e62_numemp] = aObjItens;
  
  var sLinha = "";
  
  for ( iInd in aEmpenhos ) {
    
    for ( iIndEmp in aEmpenhos[iInd] ) {
    
	    with( aEmpenhos[iInd][iIndEmp] ){
	      	    
	      sLinha += "<tr id='"+e62_sequencial+"'>                                 ";
	      sLinha += "  <td class='linhagrid'>"+e62_numemp+"</td>                  ";   
	      sLinha += "  <td class='linhagrid'>"+e62_item+"</td>                    ";
	      sLinha += "  <td class='linhagrid'>"+pc01_descrmater+"</td>             ";
	      sLinha += "  <td class='linhagrid'>"+e62_descr+"&nbsp;</td>             ";
	      sLinha += "  <td class='linhagrid'>"+js_formatar(e62_vlrun,'f')+"</td>  ";
	      sLinha += "  <td class='linhagrid'>"+e62_quant+"</td>                   ";
	      sLinha += "  <td class='linhagrid'>"+js_formatar(e62_vltot,'f')+"</td>  ";                        
	      sLinha += "  <td class='linhagrid'>                                     ";
	      sLinha += "    <input type='button' value='Excluir' onClick='js_excluiLinha("+e62_sequencial+");'>";
	      sLinha += "  </td>                                                      ";
	      sLinha += "</tr>                                                        ";
		      
	    }
	  }
  }
  
  document.getElementById('listaItens').innerHTML = sLinha;

}




function js_listaItensEmpenho(){
  js_OpenJanelaIframe('','db_iframe_listaitememp','orc1_listaitensemppacto.php?numemp='+document.form1.numemp.value+'&funcao_js=parent.js_retornaLitaItens','Pesquisa',true);
}


function js_retornaLitaItens(sListItensEmp){
  document.form1.listaitensemp.value = sListItensEmp;
  db_iframe_listaitememp.hide();
}


function js_habilitaBotaoItemEmp(){

  var iNumEmp = new Number(document.form1.numemp.value);
  if ( iNumEmp > 0 ) {
    document.getElementById('listaItensEmp').disabled = false;
  } else {
    document.getElementById('listaItensEmp').disabled = true;
  }
  
}


function js_listaEmpenhos(){
  js_OpenJanelaIframe('','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_mostraempempenho|e60_numemp','Pesquisa',true);
}


function js_mostraempempenho(chave1){
  document.form1.numemp.value = chave1;
  db_iframe_empempenho.hide();
  js_habilitaBotaoItemEmp();
}

function js_verificaLista(){
  
  if ( document.form1.listaitensemp.value != "" ) {
    var aObjItens = eval('('+document.form1.listaitensemp.value+')');
    js_montaListaManual(aObjItens);
  }
  
  if ( aEmpenhos.length > 0 ) {
    document.form1.incluir.value = "Alterar";
  }    
  
}


</script>
<?
  
  if ( isset($oPost->incluir)) {
    db_msgbox($sMsgErro);  	
  	if (!$lSqlErro) {
  		echo "<script>";
  		echo "  parent.iframe_itememp.location.href = 'orc1_empempitempacto001.php?codpacto=".$oPost->codpacto."'; ";
  		echo "</script>";
  	}
  }

?>