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
 * Representa a tela da geração do empenho da folha.
 * 
 * @author $Author: dbmarcos $
 * @version $Revision: 1.27 $
 */

$clrotulo  = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
?>
<center>
	<form name="form1" method="post" action="">
  <input type="hidden" value="<?php echo  DBPessoal::verificarUtilizacaoEstruturaSuplementar() ? '1' : '0'; ?>" id="db_complementar" name = 'db_complementar' >
	<table>
	  <tr>
	  <td>
	  <fieldset style="width: 350px">
	    <legend align="center">Geração de Empenhos</legend>
	    <table align="center" width="300">
			  <tr>
			    <td align="left" nowrap>
			      <strong>Ano / Mês:</strong>
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
			      <strong>Ponto:</strong>
			    </td>
			    <td>
			     <?php
           
			        $aSigla = array( "r14"=>"Salário",
					                     "r48"=>"Complementar",
					                     "r35"=>"13o. Salário",
					                     "r20"=>"Rescisão",
					                     "r22"=>"Adiantamento",
                               "sup"=>"Suplementar"
                             );

              if (!DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
                unset($aSigla['sup']);
              }
              
			        db_select('ponto',$aSigla,true,4,"onChange='js_validaTipoPonto()'");
			     ?>
			    </td>
        <tr style="display: none;" id="ComboContainer">
          <td align='left' title="Número da folha de pagamento">
            <strong>Número:</strong>
          </td>
          <td id="ComboContent">
          </td>
        </tr>
		  </table>
	  </fieldset>

    <fieldset id="filtroRescisao" style="display: none;">

      <legend align="center">Filtrar por data de Rescisão</legend>
      <table border="0" width="300px" align="center">
        <tr>
          <td>
            <strong>Data Inicial:</strong>
          </td>
          <td>
            <?php
              db_inputdata("sDataInicial", null, null, null, true, 'text', 1);
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <strong>Data Final:</strong>
          </td>
          <td>
            <?php
              db_inputdata("sDataFinal", null, null, null, true, 'text', 1);
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            <input type="button" name="filtrar" value="Filtrar" onclick="js_getRescisoes()" />
          </td>
        </tr>
      </table>
    </fieldset>

	  </table>
	  <div style='width:50%; display: none;' id='linhaRescisoes'>
	    <fieldset>
	      <legend>Rescisões</legend>
	      <div id='ctnGridRescisoes'>
	    </fieldset>
	  </div>
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

 var sUrl     = 'pes1_rhempenhofolhaRPC.php';
 var MENSAGEM = 'recursoshumanos/pessoal/db_frmrhempenhofolha.';

  function js_consultaFolhaSuplementar(){
  
    js_divCarregando(_M( MENSAGEM + 'carregando'), 'msgBox', true);
  
    var oParam           = new Object();
        oParam.sMethod   = "consultaSuplementaresFechadas";
        oParam.iAnoFolha = $F('anofolha');
        oParam.iMesFolha = $F('mesfolha');
  
    new Ajax.Request( sUrl, {
                              method    : 'post',
	                            parameters: oParam,
	                            onComplete: js_retornoFolhaPagamento
	                          }
	                  );
  }

  function js_consultaFolhaComplementar(){

    js_divCarregando( _M( MENSAGEM + 'carregando'),'msgBox', true);
    
    var oParam           = new Object();
        oParam.iAnoFolha = $F('anofolha');
        oParam.iMesFolha = $F('mesfolha');
        
    if ($F("db_complementar") == "1"){
        oParam.sMethod   = "consultaComplementaresFechadas";
    } else {
        oParam.sMethod   = "consultaPontoComplementar";
        oParam.sSigla    = $F('ponto');
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
      
      $('gera').disabled = true;
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
      $('gera').disabled          = true;
      
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
      
      $('gera').disabled = true;
      alert(_M(MENSAGEM + 'competencia_invalida'));
      return false;
    }
    
    if ( $F('ponto') == 'r48') {
      js_consultaFolhaComplementar();
    } else if ($F('ponto') == 'r20') {
      js_getRescisoes();
    } else if ( $F('ponto') == 'sup') {
      js_consultaFolhaSuplementar();
    }
  
  }

  function js_verifica(){
 
    if ( $F('anofolha') == '' || $F('mesfolha') == '' ) {
 
      alert( _M( MENSAGEM + 'campo_obrigatorio', {sCampo: 'Ano / Mês'} ) );
      return false;
    }
    if ($F('ponto') == 'r20') {
 
     if (oGridrescisoes.getSelection().length == 0) {
 
       alert( _M( MENSAGEM + 'selecione_rescisao' ) );
       return false;
     }
    }
    
    if ($F('ponto') == 'r48') {
      if (!$('semestre') || $F('semestre') == "0") {
        
        alert('Complementar em aberto. Execute o fechamento');
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
    
    js_consultaEmpenhos();
 
  }


 function js_consultaEmpenhos(){

   js_divCarregando(  _M( MENSAGEM + 'verificando_empenhos' ) ,'msgBox');
   js_bloqueiaTela(true);

   var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post',
                                            parameters: js_getQueryTela('consultarEmpenhos'),
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

       if (confirm( _M( MENSAGEM + 'reprocessa_empenhos' ) )) {
         js_geraEmpenhos();
       }

     } else {
       js_geraEmpenhos();
     }

   }

 }

 function js_geraEmpenhos(){

   js_divCarregando( _M( MENSAGEM + 'gerando_empenhos' ), 'msgBox' );
   js_bloqueiaTela(true);
   if ($F('ponto') == 'r20') {

     if (oGridrescisoes.getSelection().length == 0) {

       alert( _M( MENSAGEM + 'selecione_rescisao' ) );
       return false;
     }
   }
   var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post',
                                            parameters: js_getQueryTela('gerarEmpenhos'),
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
     alert( _M( MENSAGEM + 'empenhos_gerados' ) );
   }

 }

 function js_bloqueiaTela(lBloq){

   if ( lBloq ) {

     $('anofolha').disabled = true;
     $('mesfolha').disabled = true;
     $('ponto').disabled    = true;
     $('gera').disabled     = true;

     if ($F('ponto') == 'r48') {

       if ($('semestre')) {
         $('semestre').disabled = true;
       }
     }

   } else {

     $('anofolha').disabled = false;
     $('mesfolha').disabled = false;
     $('ponto').disabled    = false;
     $('gera').disabled     = false;

     if ($F('ponto') == 'r48') {

       if ($('semestre')) {
         $('semestre').disabled = false;
       }
     }

   }

 }

 function js_getQueryTela(sMethod){

   var sQuery  = 'sMethod='       + sMethod;
       sQuery += '&iAnoFolha='    + $F('anofolha');
       sQuery += '&iMesFolha='    + $F('mesfolha');
       sQuery += '&sSigla='       + $F('ponto');
       sQuery += '&sDataInicial=' + $F('sDataInicial');
       sQuery += '&sDataFinal='   + $F('sDataFinal');

   if ( $F('ponto') == 'r48' || $F('ponto') == 'sup' ) {

     if ($('semestre')) {
       sQuery += '&sSemestre='+$F('semestre');
     }
   }

   if ($F('ponto') == 'r20') {

     var aRescisoes = oGridrescisoes.getSelection("object");
     var sVirgula   = "";
     var sRescisoes = "";
     aRescisoes.each(function(oRescisao, id) {

       sRescisoes += sVirgula+oRescisao.aCells[0].getValue();
       sVirgula  = ", ";
     });

     sQuery += '&sRescisoes='+sRescisoes;
     sQuery += '&iTipo=1';
   }

   return sQuery;
 }


 function js_getRescisoes() {

  $('filtroRescisao').style.display    = '';

   var sDataInicial = $F('sDataInicial'),
       sDataFinal   = $F('sDataFinal');

  if (js_comparadata(sDataInicial, sDataFinal, '>')) {

    alert ( _M( MENSAGEM + 'data_final_menor_que_inicial' ) );
    return false;
  }

  if ((sDataInicial || sDataFinal) && (!sDataInicial || !sDataFinal)) {

    alert( _M( MENSAGEM + 'campo_obrigatorio', {sCampo: "Data " + (sDataInicial ? 'Final' : 'Inicial') } ) );
    return false;
  }

   $('linhaRescisoes').style.display = '';
   js_divCarregando(_M( MENSAGEM + 'pesquisando_rescisoes' ),'msgBox');
   js_bloqueiaTela(true);

   var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post',
                                            parameters: js_getQueryTela('getRescisoesNaoEmpenhadas'),
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

   oGridrescisoes              = new DBGrid('gridRescisoes');
   oGridrescisoes.nameInstance = "oGridrescisoes";
   oGridrescisoes.setCheckbox(0);
   oGridrescisoes.setCellAlign(new Array("center","center","Left","center"));
   oGridrescisoes.setCellWidth(new Array("10%","10%","70%","10%"));
   oGridrescisoes.setHeader(new Array("Seqpes","Matrícula","Nome","Data"));
   oGridrescisoes.show($('ctnGridRescisoes'));
 }

  /**
   * Método responsável por limpar as DIV da tela. 
   */
  function js_limparLayout() {
    
    $('gera').disabled                = false;
    $('ComboContainer').style.display = 'none';
    $('linhaRescisoes').style.display = 'none';
    $('filtroRescisao').style.display = 'none';
    $('sDataInicial').value           = '';
    $('sDataFinal').value             = '';
  }
  
 js_montaGrid();
</script>