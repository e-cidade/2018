<?php
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

$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("o56_elemento");
$clrotulo->label("e69_numero");

$clorctiporec->rotulo->label();
$clempempenho->rotulo->label();
$db_opcao_inf=1;
?>
<center>
<form name='frmDesconto' id='frmDesconto' action="" method="POST">
<table width='80%' cellspacing='0' style='padding:0px' border='0'>
<tr><td  style='padding:0px' valign="top">
 <fieldset><legend><b>&nbsp;Empenho&nbsp;</b></legend>
    <table >
          <tr>
            <td><?=db_ancora($Le60_codemp,"js_JanelaAutomatica('empempenho',\$F('e60_numemp'))",$db_opcao_inf)?></td>
            <td><? db_input('e60_codemp', 13, $Ie60_codemp, true, 'text', 3)?> </td>
            <td width='20'><?=db_ancora($Le60_numemp,"js_JanelaAutomatica('empempenho',\$F('e60_numemp'))",$db_opcao_inf)?></td>
            <td><? db_input('e60_numemp', 13, $Ie60_numemp, true, 'text', 3,"")?> </td>
          </tr>
          <tr>
            <td><?=db_ancora($Le60_numcgm,"js_JanelaAutomatica('cgm',\$F('e60_numcgm'))",$db_opcao_inf)?></td>
            <td><? db_input('e60_numcgm', 13, $Ie60_numcgm, true, 'text', 3); ?> </td>
            <td colspan=3><? db_input('z01_nome', 54, $Iz01_nome, true, 'text', 3, "");?></td>
          </tr>
          <tr>
            <td><?=db_ancora($Le60_coddot,"js_JanelaAutomatica('orcdotacao',\$F('e60_coddot'),'".@$e60_anousu."')",$db_opcao_inf)?></td>
            <td nowrap ><? db_input('e60_coddot', 13, $Ie60_coddot, true, 'text', 3); ?></td>
            <td width="20"><?=db_ancora($Lo15_codigo,"",3)?></td>
            <td nowrap><? db_input('o15_codigo', 5, $Io15_codigo, true, 'text', 3); db_input('o15_descr', 33, $Io15_descr, true, 'text', 3)?></td>
          </tr>
          <tr>
            <td><b>Motivo:</b></td>
            <td nowrap colspan='3'><textarea name='motivo' rows='3' cols='70'  id='motivo' ></textarea></td> 
         </tr> 
         <tr>
           <td></td>
           <td>
            <input type='checkbox' name='imprimir' id='imprimir' value='1'><label for='imprimir'>Imprimir Documento</label>
           </td>
         </tr>     
       </table>
    </fieldset>
   </td>
 <td valign='top'>
  <table cellspacing='0'>
    <tr>
       <td>
         <fieldset><legend><b>Saldos</b></legend>
         <table style="width:200px" >
            <tr><td nowrap><?=@$Le60_vlremp?></td><td align=right><? db_input('e60_vlremp', 12, $Ie60_vlremp, true, 'text', 3, '','','','text-align:right')?></td></tr>  
            <tr><td nowrap><?=@$Le60_vlranu?></td><td align=right><? db_input('e60_vlranu', 12, $Ie60_vlranu, true, 'text', 3, '','','','text-align:right')?></td></tr>
            <tr><td nowrap><?=@$Le60_vlrliq?></td><td align=right><? db_input('e60_vlrliq', 12, $Ie60_vlrliq, true, 'text', 3, '','','','text-align:right')?></td></tr>
            <tr><td nowrap><?=@$Le60_vlrpag?></td><td align=right><? db_input('e60_vlrpag', 12, $Ie60_vlrpag, true, 'text', 3, '','','','text-align:right')?></td></tr>
            <tr><td colspan='2' class='table_header'>Saldo</td></tr>
            <tr><td nowrap><b>Saldo:</></td><td align=right><? db_input('saldo_dis', 12,'', true, 'text', 3, '','','','text-align:right')?></td></tr>
          <tr>
          </table>       
         </fieldset>
       </table>
       </td>
    </tr>
  <tr>
     <td colspan='2' style='padding:0px;'>
     <fieldset><legend><b>Ordens de Pagamento</b></legend>
       <div id="gridNotas">
       </div>
      </fieldset>
     </td>
   </tr>
</table>

  <span><b>* clique duplo na linha para escolher os itens</b></span>
  <p>
    <input name="confirmar" type="button" id="btnConfirmar" value="Confirmar" onclick="return js_efetuadesconto();" >
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </p>
</form>
</center>
<script>

/**
 * Dados das notas e dos itens!
 */
var aNotas = new Array();

function js_pesquisa(){
 
 js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_preenchepesquisa|e60_numemp','Pesquisa',true);
}
function js_preenchepesquisa(chave) {
  
  js_limpa();
  db_iframe_empempenho.hide();
  js_consultaEmpenho(chave);
}



function js_consultaEmpenho(iEmpenho,operacao){
   
   js_divCarregando("Aguarde, efetuando pesquisa","msgBox");
   strJson = '{"method":"getDadosNotas","iEmpenho":"'+iEmpenho+'"}';
   $('gridNotas').innerHTML    = '';
   $('pesquisar').disabled = true;
   var url     = 'emp4_liquidacao004.php';
   var oAjax   = new Ajax.Request(
                            url, 
                              {
                               method: 'post', 
                               parameters: 'json='+strJson, 
                               onComplete: js_montaGrid
                              }
                             );

}

/**
 * Fun��o monta grid com as notas do empenho
 */
function js_montaGrid(oAjax) {
  
  $('pesquisar').disabled = false;
  js_removeObj("msgBox");
  var oRetorno = eval("(" + oAjax.responseText + ")");
  
  if (oRetorno.status && oRetorno.status == 2) {
    
    alert(oRetorno.sMensagem.urlDecode());
    return false;
  }
  
  /**
   * Preenche os dados do formul�rio padr�o.
   */
  $('e60_codemp').value = oRetorno.e60_codemp+"/"+oRetorno.e60_anousu;
  $('e60_numemp').value = oRetorno.e60_numemp.urlDecode();
  $('e60_coddot').value = oRetorno.e60_coddot.urlDecode();
  $('e60_numcgm').value = oRetorno.e60_numcgm.urlDecode();
  $('z01_nome').value   = oRetorno.z01_nome.urlDecode();
  $('o15_codigo').value = oRetorno.o58_codigo.urlDecode();
  $('o15_descr').value  = oRetorno.o15_descr.urlDecode();
  $('e60_vlremp').value = js_formatar(oRetorno.e60_vlremp,"f");
  $('e60_vlranu').value = js_formatar(oRetorno.e60_vlranu,"f");
  $('e60_vlrpag').value = js_formatar(oRetorno.e60_vlrpag,"f");
  $('e60_vlrliq').value = js_formatar(oRetorno.e60_vlrliq,"f");
  $('saldo_dis').value  = js_formatar(oRetorno.e60_vlremp - oRetorno.e60_vlrpag - oRetorno.e60_vlranu,"f");
  saida                 = '';
  $('gridNotas').innerHTML  = '';
  
  
  /**
   * Cria uma instancia do DATAGRID para mostrar as NOTAS do empenho
   */  
  oGridNotas = new DBGrid("gridNotas");
  oGridNotas.nameInstance = "oGridNotas";
  
  oGridNotas.setCheckbox(0);
  oGridNotas.hasTotalizador = true;
  oGridNotas.allowSelectColumns(true);
  
  var aCellAlign = new Array("center",
                             "center",
                             "center",
                             "center",
                             "center",
                             "right",
                             "right",
                             "right",
                             "right",
                             "right");
  oGridNotas.setCellAlign(aCellAlign);
  
  var aTitleHeader = new Array("Ordem",          // 0
                               "Nota",           // 1
                               "Data da Nota",   // 2
                               "Mov. Config.",   // 3
                               "Reten��o",       // 4
                               "Valor",          // 5
                               "Liquidado",      // 6
                               "Anulado",        // 7
                               "Pago",           // 8
                               "� Pagar");       // 9
  oGridNotas.setHeader(aTitleHeader);
  oGridNotas.show($("gridNotas"));
  
  oGridNotas.clearAll(true);
  
  /**
   * Percorre o array de notas retornadas e imprime dentro da grid
   */
  aNotas = oRetorno.aNotas;
  aNotas.each(
  
    function(oLinha, iId) {
     
    var nValorAPagar = (oLinha.e70_vlrliq - oLinha.e53_vlrpag);
    oLinha.aItens = new Array();  
    /**
     * Configura vari�vel para SIM ou N�O caso a nota tenha movimento configurado
     */
    var sTemMovConfigurado = "N�o";
    if (oLinha.temMovimentoConfigurado == true) {
      sTemMovConfigurado = "Sim";
    }
    /**
     * Configura vari�vel para SIM ou N�O caso a nota tenha reten��o
     */
    var sTemRetencao = "N�o";
    if (oLinha.temRetencao == true) {
      sTemRetencao = "Sim";
    }

   
    var aLinha = new Array();
    aLinha[0]  = oLinha.e50_codord;
    aLinha[1]  = oLinha.e69_codnota;
    aLinha[2]  = js_formatar(oLinha.e69_dtnota, "d");
    aLinha[3]  = sTemMovConfigurado;
    aLinha[4]  = sTemRetencao;
    aLinha[5]  = js_formatar(oLinha.e70_valor, 'f', 2);
    aLinha[6]  = js_formatar(oLinha.e70_vlrliq, 'f', 2); 
    aLinha[7]  = js_formatar(oLinha.e70_vlranu, 'f', 2);
    aLinha[8]  = js_formatar(oLinha.e53_vlrpag, 'f', 2);
    aLinha[9]  = js_formatar(nValorAPagar, 'f', 2);
    lBloqueio = false;
    
    if (nValorAPagar == 0) {
      lBloqueio = true;
    }
    oGridNotas.addRow(aLinha, false, lBloqueio);
    
    oGridNotas.aRows[iId].sEvents += "ondblclick='js_buscaItensNota("+oLinha.e69_codnota+", "+iId+")'";
    
  });
  
  oGridNotas.renderRows();
}



/**
 * Busca o c�digo na nota no array aNotas
 */
function js_getNotaByCodigo(iNota) {
  
  var oNotaRetorno = new Object();
  aNotas.each(function(oNota, id) {
    
    
    if (oNota.e69_codnota == iNota) {
      oNotaRetorno = oNota;
    }
  });
  return oNotaRetorno;
}

/**
 * Abre uma WINDOWAUX para mostrar os itens da NOTA
 */
function js_buscaItensNota(iCodNota, iIdLinha) {
  
  js_divCarregando("Aguarde, buscando itens...","msgBox");
    
  iIdLinhaGridCorrente = iIdLinha;
  oGridNotas.aRows[iIdLinha].select(true);
  var oParamNota      = new Object();
  oParamNota.method   = "getItensNota";
  oParamNota.iCodNota = iCodNota;
  oParamNota.iEmpenho = $F('e60_numemp');
  var oAjax   = new Ajax.Request(
                            "emp4_liquidacao004.php", 
                              {
                               method: 'post', 
                               parameters: 'json='+js_objectToJson(oParamNota), 
                               onComplete: js_abreWindowItens
                              }
                             );
}


/**
 * Fun��o que destr�i a janela de itens e sua grid existente
 */
function js_fechaJanelaItens() {

  oWindowItens.destroy();
//  oGridNotas.aRows[iIdLinhaGridCorrente].select(false);
  delete oGridItens;
}

/**
 * Abre a window da nota contendo os itens e seus dados
 */
function js_abreWindowItens (oAjax) {

  js_removeObj("msgBox");

  var oRetornoItens = eval("(" + oAjax.responseText + ")");
  
  var sContentItens  = "<fieldset>";
  sContentItens     += "<legend><b>Itens da Nota "+oRetornoItens.iCodNota+"</b></legend>";
  sContentItens     += "  <div id='ctnItensNota'>";
  sContentItens     += "  </div>";
  sContentItens     += "</fieldset>";
  sContentItens     += "<p align='center'>";
  sContentItens     += "  <input type='button' name='btnSalvarItens' ";
  sContentItens     += "         id='btnSalvarItens' value='Salvar' onclick='js_salvarItens("+oRetornoItens.iCodNota+")' />";
  sContentItens     += "</p>";
  
  oWindowItens = new windowAux('oWindowItens_'+oRetornoItens.iCodNota, "Itens da Nota: "+oRetornoItens.iCodNota, 700, 450);
  oWindowItens.allowCloseWithEsc(false);
  oWindowItens.setContent(sContentItens);
    
  
  var sMsgBoardId    = "oMsgBoard_"+oRetornoItens.iCodNota;
  var sMsgBoardTitle = "Itens da Nota "+oRetornoItens.iCodNota;
  var sMsgBoardHelp  = "Informe o desconto a ser efetuado para cada item da nota "+oRetornoItens.iCodNota;
  var oMessageBoard  = new messageBoard(sMsgBoardId, 
                                        sMsgBoardTitle, 
                                        sMsgBoardHelp, 
                                        oWindowItens.getContentContainer());
  oMessageBoard.show();
  oWindowItens.setShutDownFunction(js_fechaJanelaItens);
  oWindowItens.show();
  
  
  /**
   * Cria a grid que armazena os itens da nota selecionada.
   */
  oGridItens                = new DBGrid("ctnItensNota");
  oGridItens.nameInstance   = "oGridItens";
  oGridItens.allowSelectColumns(false);
  
  var aCellAlign   = new Array("left","center","right","right","right","right","right");
  oGridItens.setCellAlign(aCellAlign);
  
  var aTitleHeader = new Array("Descri��o",
                               "Qtd.",
                               "Valor",
                               "V. Liquidado",
                               "V. Anulado",
                               "Saldo",
                               "Desconto",
                               "Seq");
  oGridItens.setHeader(aTitleHeader);
  oGridItens.aHeaders[7].lDisplayed = false;
  oGridItens.show($("ctnItensNota"));
 
  oGridItens.clearAll(true);
  
  var oNota  = js_getNotaByCodigo(oRetornoItens.iCodNota);
  var aItens = oRetornoItens.aItens;
  if (oNota.aItens.length == 0) {
  
    oNota.aItens = oRetornoItens.aItens;
    aItens.each (function(oItem, iSeq) {
      oItem.nTotalDesconto = 0;
    });
  }
  
  oNota.aItens.each(function(oItem, iSeq) {

     var aLinha        = new Array();
     var nSaldoItem    = oItem.e72_vlrliq - oItem.e72_vlranu;
     var nDescontoDado = js_formatar(oItem.nTotalDesconto, "f");
     
     aLinha[0]      = oItem.pc01_descrmater.urlDecode();
     aLinha[1]      = oItem.e72_qtd;
     aLinha[2]      = js_formatar(oItem.e72_valor, 'f', 2);
     aLinha[3]      = js_formatar(oItem.e72_vlrliq, 'f', 2);
     aLinha[4]      = js_formatar(oItem.e72_vlranu, 'f', 2);
     aLinha[5]      = js_formatar(nSaldoItem, 'f', 2);
     
     /**
      * Configura o INPUT para que o usu�rio digitar o valor de desconto por item da nota
      */
     var sInputDesconto = "desconto_"+oItem.e72_sequencial;
     aLinha[6] = eval(sInputDesconto+" = new DBTextField('"+sInputDesconto+"', '"+sInputDesconto+"', '"+nDescontoDado+"','0');");
     aLinha[6].addStyle("text-align","right");
     aLinha[6].addStyle("height","100%");
     aLinha[6].addStyle("width","100%");
     aLinha[6].addStyle("border","1px solid transparent;");
     aLinha[6].addEvent("onKeyPress","return js_mask(event,\"0-9|.|-\");");
     aLinha[6].addStyle("border","1px solid transparent;");
     aLinha[6].addEvent("onBlur","js_bloqueiaDigitacao(this);");  
     //aLinha[6].addEvent("onBlur",sInputDesconto+".sValue=this.value;");  
     aLinha[6].addEvent("onFocus","js_liberaDigitacao(this);");  
     aLinha[6].addEvent("onKeyDown","return js_verifica(this,event,false);");
     aLinha[7] = oItem.e72_sequencial;
     oGridItens.addRow(aLinha);

  });
  
  oGridItens.renderRows();
}


/**
 * Fun��o que valida e salva os dados alterados dentro de uma window!
 */
function js_salvarItens() {

  var lErro    = false;
  /**
   * Total de Saldo A Pagar de uma NOTA
   */
  var nSaldoAPagar     = js_strToFloat(oGridNotas.aRows[iIdLinhaGridCorrente].aCells[10].getValue());
  var nTotalADescontar = new Number(0);
  var sMsgErro         = "";
  var sItensErro       = "";
  var sVirgula         = "";
  
  /**
   * Percorre o array de ITENS da nota para validar os desconto informado.
   * - A soma dos valores deve ser inferior ao total do "saldo � pagar"
   *
   * Muito importante termos o conhecimento que os valores mostrados na Grid possuem tratamento de formata��o e o 
   * valor utilizado aqui � o valor sem formata��o.
   * Por este motivo � utilizado o js_formatar() na oRow.aCells[6].getValue() que possui o valor de desconto do item
   *
	 *  
   */
  oGridItens.aRows.each (
    function (oRow, iIdRow) {

    	var sNomeProduto    = oRow.aCells[0].getContent();
    	
      /*
       * Validamos se o valor do item � v�lido.
       */
      if ( js_countOccurs(oRow.aCells[6].getValue(), '.') > 1 ) {
          
    	  sItensErro += sVirgula+sNomeProduto;
    	  sVirgula  = ", ";
    	  
    	  sMsgErro = "Verifique o valor digitado para desconto!\nMais de um decimal informado no valor de desconto!";
    	  lErro    = true;
    	  
        return false;
                   
      }

      //console.log(oRow.aCells[5].getValue() + " ---- " + oRow.aCells[6].getValue());    
      var nSaldoItem      = js_strToFloat(oRow.aCells[5].getValue());
      var nValorDesconto  = js_strToFloat(js_formatar(oRow.aCells[6].getValue(),"f"));
      
      /**
       *  Efetua a subtra��o do VALOR LIQUIDADO pelo VALOR ANULADO
       *  O usu�rio n�o pode informar um desconto maior que o resultado desta subtra��o
       */
      //console.log(nValorDesconto + " ---- " + nSaldoItem);
      if (nValorDesconto > nSaldoItem) {
        
    	  sItensErro += sVirgula+sNomeProduto;
        sVirgula  = ", ";
        
        sMsgErro  = "O valor � ser descontado deve ser inferior ao saldo";
        lErro     = true;
        
      }
      
      nTotalADescontar += nValorDesconto;
      
      /**
       * Guarda o valor digitado para o item em quest�o
       */
      var oDadosNota = js_getNotaByCodigo(oGridNotas.aRows[iIdLinhaGridCorrente].aCells[2].getValue());
      oDadosNota.aItens[iIdRow].nTotalDesconto = nValorDesconto.valueOf();
    });
  
  
  
  if (lErro) {
	  sMsgErro += "\nItens: "+sItensErro;
	  alert(sMsgErro); 
  }
  
  /**
   * Verifica se o valor de desconto � inferior ao saldo devedor
   */
  if (nTotalADescontar > nSaldoAPagar) {
    
    alert("O valor do desconto deve ser inferior ao saldo � pagar: "+js_formatar(nSaldoAPagar, 'f', 2)+".");
    lErro = true;
    return false;
  }
  
  if (!lErro ) {
  
    alert("Informa��es salvas com sucesso!");
    $("btnConfirmar").disabled = false;
    js_fechaJanelaItens();
  }
}



function js_efetuadesconto() {

  if ($F('motivo').trim() == '' )  {

    alert('Preencha o motivo do desconto.');
    return false;

  }
  
  sMsgConfirma  = 'A confirma��o desta opera��o de desconto implicar� em anula��o do empenho neste valor.';
  sMsgConfirma += " Este processo � irrevers�vel.\nVoc� tem certeza que deseja prosseguir?";
  if (!confirm(sMsgConfirma)) {
    return false;
  }
  
  //verificamos se o usu�rio escolheu uma nota para dar desconto . 
  var aNotas     = oGridNotas.getSelection("object");
  var sJsonNotas = '';
  var isError    = false;
  var sMessage   = '';

  var oParam      = new Object();
  oParam.exec     = "desconto";
  oParam.aNotas   = new Array();
  oParam.sMotivo  = encodeURIComponent(tagString($F('motivo'))); 
  var lConfirmar  = false;
  var lRetencao   = false;
  sUrlRpc = "emp4_notaliquidacao.php";

  /**
   * Verificamos se existem notas selecionadas
   */
  if (aNotas.length > 0 ) {

    var nValorTotalDesconto = 0;
    var lConfirmar = false;
    var lRetencao  = false;
    
    /**
     * Percorremos o array de notas para validar as notas existentes e os valores a serem
     * descontados dos itens selecionados.
     */
    aNotas.each(function(oNotaGrid, iNota) {

       oNotaGrid.z01_nome  = encodeURIComponent(tagString(oNotaGrid.z01_nome)); 
       var iCodigoNota     = oNotaGrid.aCells[2].getValue();
       var oNota           = js_getNotaByCodigo(iCodigoNota);
       oNota.z01_nome      = '';
       
       /**
        * Valida itens selecionados
        */
       if (!isError) {
         
         if (oNota.aItens.length == 0) {
           
           isError  = true;
           sMessage = 'Nota '+iCodigoNota+' N�o possui itens selecionados.\nPara continuar selecione algum Item.';
         }
       }
       
       /**
        * Valida o valor total a ser descontado
        */
       if (!isError) {
         
         var iTotalItensSelecionados = oNota.aItens.length;
         var iTotalItensErro         = 0;
         oNota.aItens.each(function (oItemGrid, iItem) {
         
           oItemGrid.pc01_descrmater = encodeURIComponent(tagString(oItemGrid.pc01_descrmater.urlDecode()));
           nValorTotalDesconto += oItemGrid.nTotalDesconto;
         });
         
         if (nValorTotalDesconto == 0) {

           isError   = true;
           sMessage  = "Valor do desconto n�o informado para os itens da nota "+iCodigoNota+".\n";
           sMessage += "Para informar o desconto, de um clique duplo sob a nota e informe os valores.";
         }
       }
       
       /**
        * Verifica se o valor a ser descontado � superior ao permitido (LIQUIDO - PAGO)
        */
       if (!isError) {

         /**
          *  Corrige os valores para executar a compara��o de forma correta.
          */
         var nCalculaLiquidoPago = js_round( (new Number(oNota.e70_vlrliq) - new Number(oNota.e53_vlrpag)), 2);
         if ( new Number(nValorTotalDesconto) > nCalculaLiquidoPago ) {
           
           isError   = true;
           sMessage  = "Valor total do desconto da nota "+iCodigoNota+".\n";
           sMessage += "n�o pode ser maior que o saldo a pagar da Nota.";
         }
       
       }
       
       /**
        * N�o havendo erro, � anexado a nota ao parametro que ser� enviado ao RPC
        */
       if (!isError) {
       
         oNota.nValorDesconto = nValorTotalDesconto;
         oParam.aNotas.push(oNota);
       }
       if (oNota.temRetencao) {
         lRetencao = true;
       }
       if (oNota.temMovimentoConfigurado) {
         lConfirmar = true;
       }       
    });
    
  }
  if (isError) {
    alert(sMessage);
  } else {
   
    if (lConfirmar) {
     
      var sMsg  = "Voc� est� realizando uma opera��o de desconto em  Ordem(s) j� configurada(s) para pagamento.\n";
          sMsg += "� aconselh�vel consultar a Tesouraria antes de confirmar o procedimento.\n";
          sMsg += "Tem certeza de que deseja continuar ?";
           
      if (!confirm(sMsg)) {
        return false;
      }    
    }
    
    if (lRetencao) { 
      var sMsg  = "Voc� est� realizando uma opera��o de desconto em Ordem(s) com reten��o lan�ada.\n";
          sMsg += "Tem certeza de que deseja continuar ?";
          
      if (!confirm(sMsg)) {
        return false;
      }        
    }
    js_divCarregando('Aguarde, efetuando desconto do empenho', 'msgBox');
    var oAjax = new Ajax.Request(
                                 sUrlRpc, 
                                 {
                                   method: 'post', 
                                   parameters: 'json='+js_objectToJson(oParam),
                                   onComplete: js_saidaProcedimento
                                 }
                                 );
  }
}

function js_saidaProcedimento(oAjax) {

  //$("btnConfirmar").disabled = true;
  js_removeObj("msgBox");
  var obj = eval("(" + oAjax.responseText + ")");
  alert(obj.message.urlDecode());
  if (obj.status == 1 ) {
    if ($('imprimir').checked) {
    
         jan = window.open('emp2_anulemp002.php?e60_numemp='+$F('e60_numemp'),'',
                           'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+
                           ',scrollbars=1,location=0 ');
         jan.moveTo(0,0);
     }
    js_consultaEmpenho($F('e60_numemp'));
  }
}


function js_bloqueiaDigitacao(object, iBold) {

  object.readOnly         = true;
  object.style.border     ='1px';
  object.style.fontWeight = "normal";
  if (iBold) {
    object.style.fontWeight = "bold";
  }
  object.value            = js_formatar(object.value,"f");
  
  //Se existir mais de um ponto...
  if( js_countOccurs(object.value, '.') > 1 ) {
    // Erro e retorna valor anterior
    alert("Verifique o valor digitado para desconto!\nMais de um decimal informado no valor de desconto!");
    js_liberaDigitacao(object);
    return false;
  }
   
}
/**
 * Libera  o input passado como parametro para a digitacao.
 * � Retirado a mascara do valor e liberado para Edi��o
 * � Colocado a Variavel nValorObjeto no escopo GLOBAL
 */
function js_liberaDigitacao(object) {
  
  nValorObjeto        = object.value; 
  //object.value        = js_strToFloat(object.value).valueOf();
  object.style.border = '1px solid black';
  object.readOnly     = false;
  object.style.fontWeight = "bold";
  object.select();
   
}
/**
 * Verifica se  o usu�rio cancelou a digita��o dos valores.
 * Caso foi cancelado, voltamos ao valor do objeto, e 
 * bloqueamos a digita��o
 */
function js_verifica(object,event,iBold) {

  var teclaPressionada = event.which;
  if (teclaPressionada == 27) {
     object.value = nValorObjeto;
     js_bloqueiaDigitacao(object,iBold);
  }
  
}
 
function js_limpa(){
  
//  oGridNotas.clearAll(true);
  $('frmDesconto').reset();
   
}
//js_consultaEmpenho(147890);
js_pesquisa();


</script>