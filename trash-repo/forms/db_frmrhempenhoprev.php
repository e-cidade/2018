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

$clrotulo  = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
?>
<center>
	<form name="form1" method="post" action="">
	  <fieldset>
	    <legend align="center">
	      <b>Geração de Empenhos - Previdência </b>
	    </legend>
	    <table align="center">
			  <tr>
			    <td align="right" nowrap>
			      <b>Ano / Mês :</b>
			    </td>
			    <td>
			      <?
			        $anofolha = db_anofolha();
			        db_input('anofolha',4,$IDBtxt23,true,'text',2,"");
			      ?>
			        &nbsp;/&nbsp;
			      <?
			        $mesfolha = db_mesfolha();
			        db_input('mesfolha',2,$IDBtxt25,true,'text',2,"");
			      ?>
			    </td>
			  </tr>
			  <tr>
			    <td align="right" nowrap>
			      <b>Ponto:</b>
			    </td>
			    <td>
			     <?
			     
			       $aSigla = array( "m"=>"Mensal",
					                    "d"=>"13o. Salário");
			       
			       db_select('ponto',$aSigla,true,4,"");
			     ?>
			    </td>
		    </tr>
	      <tr>
	        <td align="center" colspan="2" >
	         <?
		         $sql  = "select distinct r33_codtab,              ";
		         $sql .= "                r33_nome                 ";
		         $sql .= "           from inssirf                  ";
		         $sql .= "          where r33_anousu = {$anofolha} "; 
		         $sql .= "            and r33_mesusu = {$mesfolha} ";
		         $sql .= "            and r33_codtab > 2           ";
		         $sql .= "            and r33_instit = ".db_getsession('DB_instit') ;
		         
		         $rsPrev = db_query($sql);
		         
		         db_multiploselect("r33_codtab", "r33_nome", "nselecionados", "selecionados", $rsPrev, array(), 4, 250);
	         ?>
	        </td>
	      </tr>		    
		  </table> 
	  </fieldset>
	  <table>  
		  <tr>
		    <td align = "center"> 
		      <input name="gera" id="gera" type="button" value="Processar" onClick="js_verifica();">
		    </td>
		  </tr>
	  </table>
	</form>
</center>	
<script>

 var sUrl = 'pes1_rhempenhofolhaRPC.php';
  
 function js_verifica(){
 
   if ( $F('anofolha') == '' || $F('mesfolha') == '' ) {
     alert('Ano / Mês não informado!');
     return false;
   }

   if(document.form1.selecionados.length == 0 ){
	   alert('Você deve selecionar pelo menos 1 item!');
	   return;
	 }
   
   js_consultaEmpenhosPrev();    
 
 }
 
 
 function js_consultaEmpenhosPrev(){
 
   js_divCarregando('Verificando empenhos existentes...','msgBox');
   js_bloqueiaTela(true); 
       
   var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: js_getQueryTela('consultarEmpenhosPrev'), 
                                            onComplete: js_retornoConsultaEmpenhos
                                          }
                                  );         
 
 } 
 
 function js_retornoConsultaEmpenhos(oAjax){

   js_removeObj("msgBox");
   js_bloqueiaTela(false);
  
   var aRetorno = eval("("+oAjax.responseText+")");
   var sExpReg  = new RegExp('\\\\n','g');
  
   if ( aRetorno.lErro ) {
     alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
     return false;
   } else {
      
     if ( aRetorno.lExiste ) {
       
       if (confirm('Empenhos já gerados para este período.\nReprocessar?')) {
         js_geraEmpenhos();
       }
       
     } else {
       js_geraEmpenhos();
     }
           
   }

 } 
 

 function js_geraEmpenhos(){
 
   js_divCarregando('Gerando Empenhos...','msgBox');
   js_bloqueiaTela(true); 
    
   var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: js_getQueryTela('gerarEmpenhosPrev'), 
                                            onComplete: js_retornoGeraEmpenhos
                                          }
                                  );         
 
 }

 function js_retornoGeraEmpenhos(oAjax){

   js_removeObj("msgBox");
   js_bloqueiaTela(false);
  
   var aRetorno = eval("("+oAjax.responseText+")");
   var sExpReg  = new RegExp('\\\\n','g');
    
  
   if ( aRetorno.lErro ) {
     alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
     return false;
   } else {
     alert('Empenhos gerados com sucesso!');
   }

 }
 
 function js_bloqueiaTela(lBloq){
 
   if ( lBloq ) {
     $('anofolha').disabled = true;         
     $('mesfolha').disabled = true;
     $('ponto').disabled    = true;
     $('gera').disabled     = true;
   } else {
     $('anofolha').disabled = false;         
     $('mesfolha').disabled = false;
     $('ponto').disabled    = false;
     $('gera').disabled     = false;
   }
 
 }
 
 function js_getQueryTela(sMethod){
 
   var sQuery  = 'sMethod='+sMethod;
       sQuery += '&iAnoFolha='+$F('anofolha');
       sQuery += '&iMesFolha='+$F('mesfolha');
       sQuery += '&sTipo='+$F('ponto');

   var sSelecionados = "";
   var sVirg         = "";
   
   for(var i=0; i<document.form1.selecionados.length; i++){
     sSelecionados += sVirg+document.form1.selecionados.options[i].value;
     sVirg          = ",";
   }
   
   sQuery += '&sPrev='+sSelecionados;          
          
   return sQuery;    
 
 }
 
</script>