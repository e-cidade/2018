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

$clrotulo  = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
?>
<center>
	<form name="form1" method="post" action="">
	<table>
	  <tr>
	  <td>
	  <fieldset>
	    <legend align="center">
	      <b>Gerar Planilha</b>
	    </legend>
	    <table align="center">
			  <tr>
			    <td align="left" nowrap>
			      <b>Ano / Mês :</b>
			    </td>
			    <td>
			      <?
			        $anofolha = db_anofolha();
			        db_input('anofolha',4,$IDBtxt23,true,'text',2,"onChange='js_validaTipoPonto()'");
			      ?>
			      &nbsp;/&nbsp;
			      <?
			        $mesfolha = db_mesfolha();
			        db_input('mesfolha',2,$IDBtxt25,true,'text',2,"onChange='js_validaTipoPonto()'");
			      ?>
			    </td>
			  </tr>
			  <tr>
			    <td>
			      <b>Ponto:</b>
			    </td>
			    <td>
			     <?
			     
			       $aSigla = array( "r14"=>"Salário",
					                    "r48"=>"Complementar",
					                    "r35"=>"13o. Salário",
					                    "r20"=>"Rescisão",
					                    "r22"=>"Adiantamento");
			       
			       db_select('ponto',$aSigla,true,4,"onChange='js_validaTipoPonto()'");
			     ?>
			    </td>
		    </tr>
		    <tr id='linhaComplementar' style='display:none'>
		    </tr>
		  </table> 
	  </fieldset>
	  </td>
	  </tr>
	  </table>
	  <table>  
		  <tr>
		    <td align = "center"> 
		      <input name="gerar" id="gerar" type="button" value="Gerar" onClick="js_gerarPlanilha();">
		    </td>
		  </tr>
	  </table>
	</form>
	<div style='width:50%; display: none' id='linhaRescisoes'>
          <fieldset>
            <legend>Rescisões</legend>
            <div id='ctnGridRescisoes'> 
          </fieldset>
        </div>
</center>	
<script>

			     
$('mesfolha').maxLength = 2;
$('anofolha').maxLength = 4;

 var sUrl = 'pes1_rhempenhofolhaRPC.php';
  
 function js_consultaPontoComplementar(){
 
   js_divCarregando('Consultando ponto complementar...','msgBox');
   js_bloqueiaTela(true);
   
   var sQuery  = 'sMethod=consultaPontoComplementar';
       sQuery += '&iAnoFolha='+$F('anofolha');
       sQuery += '&iMesFolha='+$F('mesfolha');
       sQuery += '&sSigla='+$F('ponto');   
   
	 var oAjax   = new Ajax.Request( sUrl, {
	                                          method: 'post', 
	                                          parameters: sQuery, 
	                                          onComplete: js_retornoPontoComplementar
	                                        }
	                                );      
 
 }

 function js_retornoPontoComplementar(oAjax){

   js_removeObj("msgBox");
   js_bloqueiaTela(false);
   
   var aRetorno = eval("("+oAjax.responseText+")");
   var sExpReg  = new RegExp('\\\\n','g');
    
  
   if ( aRetorno.lErro ) {
     alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
     return false;
   }

   var sLinha          = "";
   var iLinhasSemestre = aRetorno.aSemestre.length;
   
   if ( iLinhasSemestre > 0 ) {
   
   
     sLinha += " <td align='left' title='Nro. Complementar'> ";
     sLinha += "   <strong>Nro. Complementar:</strong>       ";
     sLinha += " </td>                                       ";
     sLinha += " <td>                                        ";
     sLinha += "   <select id='semestre' name='semestre'>    ";
     
     for ( var iInd=0; iInd < iLinhasSemestre; iInd++ ) {
       with( aRetorno.aSemestre[iInd] ){
         sLinha += " <option value = '"+semestre+"'>"+semestre+"</option>";
       }  
     }
     
     sLinha += " </td>                                       ";
   
   } else {
   
     sLinha += " <td colspan='2' align='center'>                                ";
     sLinha += "   <font color='red'>Sem complementar para este período.</font> ";
     sLinha += " </td>                                                          ";
   
   }
   
   $('linhaComplementar').innerHTML     = sLinha;
   $('linhaComplementar').style.display = '';

 }

 function js_validaTipoPonto(){
 
   if ( $F('ponto') == 'r48') {
   
     $('linhaRescisoes').style.display = 'none';
     js_consultaPontoComplementar();     
   } else if ($F('ponto') == 'r20') {
	   $('linhaComplementar').style.display = 'none';
     js_getRescisoes();   
   } else {
     
     $('linhaRescisoes').style.display = 'none';
     $('linhaComplementar').style.display = 'none';
   }
   
 }
 
 
 function js_gerarPlanilha(){

	 if ($F('ponto') == 'r48') {
		 if ($F('semestre') == "0") {
			 alert('Complementar em aberto. Execute o fechamento');
			 return false;
		 } 
	 }

   if ($F('mesfolha') > 12) {
     
     alert("Mês inválido. Informe corretamente");
     return false;
   }
   
   if ($F('ponto') == 'r20') {
     
     var sListarescisoes = ""; 
     var aRescisoes      = oGridrescisoes.getSelection("object")
     var sVirgula        = "";
     if (oGridrescisoes.getSelection().length == 0) {
   
       alert('selecione alguma rescisão para continuar.');
       return false;
      }
    }
    js_divCarregando(' Aguarde ...','msgBox');
    js_bloqueiaTela(true); 
          
    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: js_getQueryTela('geraPlanilha'), 
                                            onComplete: js_retornoGerarPlanilha
                                          }
                                  );         
 
 } 
 
 function js_retornoGerarPlanilha(oAjax){

   js_removeObj("msgBox");
   js_bloqueiaTela(false);
  
   var aRetorno = eval("("+oAjax.responseText+")");
   var sExpReg  = new RegExp('\\\\n','g');
  
   alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
   
   if ( aRetorno.lErro ) {
     return false;
   } else {
     var sQuery  = 'sListaPla='+aRetorno.sListaPla;
     jan = window.open('cai2_emiteplanilha002.php?'+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');   
   }

 } 

 
 function js_bloqueiaTela(lBloq){
 
   if ( lBloq ) {
     $('anofolha').disabled = true;         
     $('mesfolha').disabled = true;
     $('ponto').disabled    = true;
     $('gerar').disabled    = true;
     
     if ($F('ponto') == 'r48') {
       if ($('semestre')) {
         $('semestre').disabled = true;
       } 
     }     
     
   } else {
     $('anofolha').disabled = false;         
     $('mesfolha').disabled = false;
     $('ponto').disabled    = false;
     $('gerar').disabled    = false;
     
     if ($F('ponto') == 'r48') {
       if ($('semestre')) {
         $('semestre').disabled = false;
       }
     }
        
   }
 
 }
 
 function js_getQueryTela(sMethod){
 
   var sQuery  = 'sMethod='+sMethod;
       sQuery += '&iAnoFolha='+$F('anofolha');
       sQuery += '&iMesFolha='+$F('mesfolha');
       sQuery += '&sSigla='+$F('ponto');
        
   if ( $F('ponto') == 'r48' ) {
     if ($('semestre')) {
       sQuery += '&sSemestre='+$F('semestre');
     }
   }
   if ($F('ponto') == 'r20') {
     
       var sListarescisoes = ""; 
       var aRescisoes      = oGridrescisoes.getSelection("object")
       var sVirgula        = "";
       if (oGridrescisoes.getSelection().length == 0) {
     
         alert('selecione alguma rescisão para continuar.');
         return false;
     } else {
       aRescisoes.each(function(oRescisao, id) {
       
         sListarescisoes += sVirgula+oRescisao.aCells[0].getValue();
         sVirgula  = ",";
       });
     }
     sQuery += "&sRescisoes="+sListarescisoes;       
   }          
   return sQuery;    
 
 }
 
  function js_geraSlip() {
   
   js_OpenJanelaIframe('top.corpo',
                       'db_iframe_geraslip',
                       'pes1_rhgeralistaslip001.php?'+js_getQueryTela(''),
                       'Gera SLIP - '+$F('mesfolha')+'/'+$F('anofolha'),
                       true,
                       25,
                       (document.width-(document.width-200))/2,
                       document.width-200,
                       600
                       );
                       
  }
   function js_getRescisoes() {
   
   $('linhaRescisoes').style.display = '';
   js_divCarregando('Pesquisando Rescisoes','msgBox');
   js_bloqueiaTela(true); 
   var sQuery  = 'sMethod=getRescisoesPlanilhas';
       sQuery += '&iAnoFolha='+$F('anofolha');
       sQuery += '&iMesFolha='+$F('mesfolha');
       sQuery += '&sSigla='+$F('ponto');    
   var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoGetRescisoes
                                          }
                                  );    
 
 }
 
 function js_retornoGetRescisoes(oAjax) {
 
   js_removeObj('msgBox');
   js_bloqueiaTela(false);
   oGridrescisoes.clearAll(true);
   var oRetorno = eval("("+oAjax.responseText+")");
   oRetorno.sListaRescisoes.each(function (oRescisao, id) {
   
      var aLinha = new Array();
      aLinha[0]  = oRescisao.seqpes;
      aLinha[1]  = oRescisao.matricula;
      aLinha[2]  = oRescisao.nome.urlDecode();  
      aLinha[3]  = js_formatar(oRescisao.datarescisao,'d');
      oGridrescisoes.addRow(aLinha);  
   });
   oGridrescisoes.renderRows();
 }
 function js_montaGrid() {
 
   oGridrescisoes     = new DBGrid('gridRescisoes');
   oGridrescisoes.nameInstance = "oGridrescisoes";
   oGridrescisoes.setCheckbox(0);
   oGridrescisoes.setCellAlign(new Array("right","right","Left","center"));
   oGridrescisoes.setCellWidth(new Array("4%","20%","66%","20%"));
   oGridrescisoes.setHeader(new Array("Seq","Mátricula","Nome","Data"));
   oGridrescisoes.show($('ctnGridRescisoes'));
 }
 js_montaGrid(); 
 
 
</script>