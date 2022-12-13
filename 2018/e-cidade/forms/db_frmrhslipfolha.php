<?php

/**
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

/**
 * Representa a tela da consulta de slip.
 * 
 * @author $Author: dbmarcos $
 * @version $Revision: 1.13 $
 */

$clrotulo  = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
?>
<center>
	<form name="form1" method="post" action="">
    <input type="hidden" value="<?php echo DBPessoal::verificarUtilizacaoEstruturaSuplementar() ? '1' : '0'; ?>" id="db_complementar" name = 'db_complementar' >
	  
    <table>
	  <tr>
	    <td>
	  <fieldset>
	    <legend align="center">
	      <b>Consulta de SLIP</b>
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
					                    "r22"=>"Adiantamento",
                              "sup"=>"Suplementar");
			       
             if (!DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
               unset($aSigla['sup']);
             }
             
			       db_select('ponto',$aSigla,true,4,"onChange='js_validaTipoPonto()'");
			     ?>
			    </td>
		    </tr>
		    <tr style="display: none;" id="ComboContainer">
          <td align='left' title="Número da folha de pagamento">
            <strong>Número:</strong>
          </td>
          <td id="ComboContent">
          </td>
        </tr>
		  </table> 
	  </fieldset>
	  </td>
	  </tr>
	  </table>
	  <table>  
		  <tr>
		    <td align = "center"> 
		      <input name="consulta" id="consulta" type="button" value="Consultar" onClick="js_verifica();">
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

  var sUrl     = 'pes1_rhempenhofolhaRPC.php';
  var MENSAGEM = 'recursoshumanos/pessoal/db_frmrhslipfolha.';
  
  function js_consultaFolhaPagamento(iTipoFolha){
  
    js_divCarregando(_M( MENSAGEM + 'carregando'), 'msgBox', true);
  
    var oParam            = new Object();
        oParam.iAnoFolha  = $F('anofolha');
        oParam.iMesFolha  = $F('mesfolha');
        oParam.iTipoFolha = iTipoFolha;
        
    if ($F("db_complementar") == "1"){
        oParam.sMethod    = "getFolhasComPreSlipGerado";
    } else {
        oParam.sMethod    = "consultaPontoComplementar";
        oParam.sSigla     = $F('ponto');
    }
  
    new Ajax.Request( sUrl, {
                              method    : 'post',
	                            parameters: oParam,
	                            onComplete: js_retornoFolhaPagamento
	                          }
	                  );
  }

  function js_retornoFolhaPagamento(oAjax){
  
    js_removeObj("msgBox");
  
    var aRetorno = eval("("+oAjax.responseText+")");
    
    if (aRetorno.lErro) {
      
      $('consulta').disabled = true;
      alert(aRetorno.sMsg.urlDecode());
      return false;
    }
    
    var iLinhasSemestre = aRetorno.aSemestre.length;
  
    if (iLinhasSemestre > 0) {
      
      var oDBComboBox = new DBComboBox('semestre', null, []);
      
      for (var iIndice = 0 ; iIndice < iLinhasSemestre; iIndice++) {
       
        var oSemestre   = aRetorno.aSemestre[iIndice];
       
        if ($F("db_complementar") == "1"){    
          oDBComboBox.addItem(oSemestre, oSemestre);
        } else {
          oDBComboBox.addItem(oSemestre.semestre, oSemestre.semestre);
        }
      }
      
      oDBComboBox.sStyle = "width: 105px;";  
      oDBComboBox.show($('ComboContent'));
      
    } else {
      
      var sLinha  = " <td> ";
          sLinha += "   <font color='red'>Sem folha.</font> ";
          sLinha += " </td> ";
      $('ComboContent').innerHTML = sLinha;
      $('consulta').disabled          = true;
      
    }
  
    $('ComboContainer').style.display = '';
    
  }

  function js_validaTipoPonto(){
 
    js_limparLayout();
    
    var iAnoInformado  = $("anofolha").getValue();
    var iMesInformado  = $("mesfolha").getValue();
    var oCompetencia   = new DBViewFormularioFolha.CompetenciaFolha(false);
    var lCompetencia   = oCompetencia.isCompetenciaValida(iAnoInformado, iMesInformado);
    
    if (!lCompetencia) {
      
      $('consulta').disabled = true;
      alert(_M(MENSAGEM + 'competencia_invalida'));
      return false;
    }
    
    if ( $F('ponto') == 'r48') {
      js_consultaFolhaPagamento(3);
    } else if ($F('ponto') == 'r20') {
      js_getRescisoes();
    } else if ( $F('ponto') == 'sup') {
      js_consultaFolhaPagamento(6);
    }
    
  }
 
  function js_verifica(){
  
    if ( $F('anofolha') == '' || $F('mesfolha') == '' ) {
      alert('Ano / Mês não informado!');
      return false;
    }
 
    if ($F('ponto') == 'r48') {
 		 if (!$('semestre') || $F('semestre') == "0") {
 			 alert('Complementar em aberto. Execute o fechamento');
 			 return false;
 		 } 
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
   
    if ($F("db_complementar") == "1" && $F('ponto') == 'r14') {
      
      var iMesFolha = $F('mesfolha'); 
      var iAnoFolha = $F('anofolha');
         
      var oFolhaPagamento = new DBViewFormularioFolha.ValidarFolhaPagamento();
      var lFolhaSalario   = oFolhaPagamento.verificarFolhaPagamentoAberta(oFolhaPagamento.TIPO_FOLHA_SALARIO, iAnoFolha, iMesFolha);
         
      if (lFolhaSalario == true){
        
        alert(_M(MENSAGEM + 'folha_salario_fechada'));
        return false;
      }
    } 
   
    js_consultaSLIP();    
 
  }
 
 
 function js_consultaSLIP(){
 
   js_divCarregando(' Aguarde ...','msgBox');
   js_bloqueiaTela(true); 
       
   var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: js_getQueryTela('consultarSLIP'), 
                                            onComplete: js_retornoConsultaSLIP
                                          }
                                  );         
 
 } 
 
 function js_retornoConsultaSLIP(oAjax){

   js_removeObj("msgBox");
   js_bloqueiaTela(false);
  
   var aRetorno = eval("("+oAjax.responseText+")");
   var sExpReg  = new RegExp('\\\\n','g');
  
   if ( aRetorno.lErro ) {
     alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
     return false;
   } else {
      
     if ( aRetorno.lGerados ) {
        js_geraSlip();
     } else {
       if ( aRetorno.lSlipGerados ) {
	       alert('Slips já gerados para os filtros selecionados!');
	       return false;       
       } else {
         alert('Nenhum registro encontrado para os filtros selecionados!');
         return false;
       }
     }
           
   }

 } 

 
 function js_bloqueiaTela(lBloq){
 
   if ( lBloq ) {
     $('anofolha').disabled = true;         
     $('mesfolha').disabled = true;
     $('ponto').disabled    = true;
     $('consulta').disabled     = true;
     
     if ($F('ponto') == 'r48') {
       if ($('semestre')) {
         $('semestre').disabled = true;
       } 
     }     
     
   } else {
     $('anofolha').disabled = false;         
     $('mesfolha').disabled = false;
     $('ponto').disabled    = false;
     $('consulta').disabled     = false;
     
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
        
       if ( $F('ponto') == 'r48' || $F('ponto') == 'sup' ) {
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
                       50,
                       0,
                       document.body.clientWidth ,
                       document.body.scrollHeight
                       );
                       
  }
  function js_getRescisoes() {
   
   $('linhaRescisoes').style.display = '';
   js_divCarregando('Pesquisando Rescisoes','msgBox');
   js_bloqueiaTela(true); 
   var sQuery  = 'sMethod=getRescisoesSlips';
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
 
  /**
   * Método responsável por limpar as DIV da tela. 
   */
  function js_limparLayout() {
    
    $('consulta').disabled            = false;
    $('ComboContainer').style.display = 'none';
    $('linhaRescisoes').style.display = 'none';
  }
  
 js_montaGrid(); 
 
</script>