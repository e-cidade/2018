<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

$clrotulo = new rotulocampo();

$clrotulo->label("it06_matric");
$clrotulo->label("it04_descr");

$clitbi->rotulo->label();
$clitbidadosimovel->rotulo->label();
$clitbiavalia->rotulo->label();

$tipo  		 = $oGet->tipo;

if ( $tipo == "urbano") {
  $sPrefix     = "do ";
  $sTerraLabel = "Terreno";
  $sMedida     = "m²";
} else {
  $sPrefix     = "da ";
  $sTerraLabel = "Terra";
  $sMedida     = "ha";
}

if (isset($it14_guia) && !empty($it14_guia)) {
  $db_botao = true;
}
?>
<form name="form1" method="post" action="" id="frm1">
  <table>
    <tr>
      <td>
        <fieldset>
          <legend>
            <b>Liberação de ITBI</b>
          </legend>
          <table>
		    <tr>
			  <td title="<?=@$Tit14_guia?>">
			    <?=@$Lit14_guia?>
			  </td>
			  <td>
				<?
				  db_input('it14_guia',10,$Iit14_guia,true,'text',3," onchange='js_pesquisait14_guia(false);'");
				  db_input('tipo',10,"",true,'hidden',3);
				  db_input('listaFormas',10,"",true,'hidden',3);
				  db_input('desconto_avalia',10,"",true,'hidden',3);
				?>
			  </td>
			  <td>
			    <?=@$Lit01_data?>
			  </td>
			  <td>
			    <?
				  db_inputdata('it01_data',@$it01_data_dia,@$it01_data_mes,@$it01_data_ano,true,'text',3,"");
			    ?>
			  </td>
			  <td>
			    <?=@$Lit01_id_usuario?>
			  </td>
			  <td>
			    <?
				  db_input('it01_id_usuario',10,"",true,'hidden',3,"");
				  db_input('nome',50,"",true,'text'  ,3,"");
			    ?>
			  </td>
			</tr>
			<tr>
			  <td colspan="6">
			    <fieldset>
			      <legend>
			        <b>Identificação do Imóvel:</b>
			      </legend>
			      <table>
			        <tr>
			          <td>
			            <b>Matrícula RI:</b>
			          </td>
			          <td>
			            <?
				  	 	  db_input('it22_matricri',10,$Iit22_matricri,true,'text',3,"");
			            ?>
			          </td>
			          <? if ( $tipo == "urbano" ) {?>
			          <td>
			            <b>Matrícula:</b>
			          </td>
			          <td>
			            <?
				  	 	  db_input('it06_matric',10,$Iit06_matric,true,'text',3,"");
			            ?>
			            <input type="button" name="verMatric" value="Ver" onClick="js_verMatric();" <?=($db_botao==false?"disabled":"")?>/>
			          </td>
			          <td>
			            <b>Ref. Anterior:</b>
			          </td>
			          <td align="right">
			            <?
				  	 	  db_input('j40_refant',10,"",true,'text',3,"");
			            ?>
			          </td>
			          <? } else { ?>
			          <td>
			            <b>Distância da Cidade:</b>
			          </td>
			          <td align="right" colspan="3">
			            <?
				  	 	  db_input('it18_distcidade	',10,"",true,'text',3,"");
			            ?>
			            <b>Km</b>
			          </td>
			          <? }?>
			        </tr>
			        <tr>
			          <td>
			            <b>Setor/Bairro:</b>
			          </td>
			          <td colspan="5">
			            <?
				  	 	  db_input('it22_setor',100,"",true,'text',3,"");
			            ?>
			          </td>
			        </tr>
			        <tr>
			          <td>
			            <b>Logradouro:</b>
			          </td>
			          <td colspan="5">
			            <?
				  	 	  db_input('it22_descrlograd',100,"",true,'text',3,"");
			            ?>
			          </td>
			        </tr>
					<? if ( $tipo == "urbano" ) {?>
					<tr>
					  <td>
					    <?=@$Lit22_numero?>
					  </td>
					  <td>
					    <?
					  	  db_input('it22_numero',20,$Iit22_numero,true,'text',3,"");
						?>
					  </td>
					  <td align="right" colspan="2">
					    <?=@$Lit22_compl?>
					  </td>
					  <td align="right" colspan="2">
					    <?
						  db_input('it22_compl',20,$Iit22_compl,true,'text',3,"");
						?>
					  </td>
				  	</tr>
					<tr>
					  <td>
					    <?=@$Lit22_quadra?>
					  </td>
					  <td>
					    <?
					   	  db_input('it22_quadra',20,$Iit22_quadra,true,'text',3,"");
						?>
					  </td>
					  <td align="right" colspan="2">
					    <?=@$Lit22_lote?>
					  </td>
					  <td align="right" colspan="2">
						<?
						  db_input('it22_lote',20,$Iit22_lote,true,'text',3,"");
						?>
					  </td>
					</tr>
					<? } ?>
                    <tr>
                      <td>
                        <b>Área:</b>
                      </td>
                      <td>
                        <?
                          db_input('it01_areaterreno',20,$Iit01_areaterreno,true,'text',3,"");
                        ?>
                        <b><?=$sMedida?></b>
                      </td>
                      <td  align="right" colspan="2">
                        <b>Área Transmitida:</b>
                      </td>
                      <td  align="right" colspan="2">
                        <?
                          db_input('it01_areatrans',20,$Iit01_areatrans,true,'text',3,"");
                        ?>
                        <b><?=$sMedida?></b>
                      </td>
                    </tr>
			        <tr>
			          <td>
			            <b>Transmitente Princ:</b>
			          </td>
			          <td colspan="5">
			            <?
				  	 	  db_input('transmitenteprinc',100,"",true,'text',3,"");
			            ?>
			          </td>
			        </tr>
			        <tr>
			          <td>
			            <b>Adquirente Princ:</b>
			          </td>
			          <td colspan="5">
			            <?
				  	 	  db_input('adquirenteprinc',100,"",true,'text',3,"");
			            ?>
			          </td>
			        </tr>
			      </table>
			    </fieldset>
			  </td>
			</tr>
			<tr>
			  <td colspan="6">
			    <fieldset>
			      <legend>
			        <b>Valores Declarados:</b>
			      </legend>
			      <table>
					<tr>
					  <td title="<?=@$Tit01_tipotransacao?>">
					    <?=@$Lit01_tipotransacao?>
					  </td>
					  <td colspan="5">
						<?
						  db_input('it01_tipotransacao',20,"",true,'hidden',3);
						  db_input('it04_descr',100,$Iit04_descr,true,'text',3,'');
					    ?>
					  </td>
					</tr>
					<tr>
					  <td>
					    <b>Valor  <?=$sPrefix.$sTerraLabel?>:</b>
					  </td>
					  <td>
					    <?
					      db_input('it01_valorterreno',15,$Iit01_valorterreno,true,'text',3);
					    ?>
					  </td>
					  <td>
					    <b>Valor das Benfeitorias:</b>
					  </td>
					  <td>
					    <?
					      db_input('it01_valorconstr',15,$Iit01_valorconstr,true,'text',3);
					    ?>
					  </td>
					  <td>
					    <b>Valor Total:</b>
					  </td>
					  <td align="right">
						<?
						  db_input('it01_valortransacao',15,$Iit01_valortransacao,true,'text',3);
					    ?>
					  </td>
					</tr>
					<tr>
					  <td colspan="6">
					    <div id="listaFormasPgto"></div>
					  </td>
					</tr>
			      </table>
			    </fieldset>
			  </td>
			</tr>
			<tr align="center">
			  <td colspan="6">
			    <input type="button" name="concordaValores" value="Concordar com Valores" onClick="js_concordaValores();" <?=($db_botao==false?"disabled":"")?>>
			  </td>
			</tr>
			<tr>
			  <td colspan="6">
			    <fieldset>
			      <legend>
			        <b>Avaliação:</b>
			      </legend>
			      <table>
					<tr>
					  <td title="<?=@$Tit01_tipotransacao?>">
			      		<?
			        	  db_ancora(@$Lit01_tipotransacao,"js_pesquisait01_tipotransacao(true);",3);
			      		?>
					  </td>
					  <td colspan="5">
						<?
						  db_input('it01_tipotransacao_avalia',20,"",true,'hidden',3);
						  db_input('it04_descr_avalia',100,$Iit04_descr,true,'text',3,'');
					    ?>
					  </td>
					</tr>
					<tr>
					  <td>
					    <b>Valor <?=$sPrefix.$sTerraLabel?>:</b>
					  </td>
					  <td>
					    <?
					      db_input('it01_valorterreno_avalia',15,$Iit01_valorterreno,true,'text',$db_opcao,"onChange='js_validaValores(this)'");
					    ?>
					  </td>
					  <td>
					    <b>Valor das Benfeitorias:</b>
					  </td>
					  <td>
					    <?
					      db_input('it01_valorconstr_avalia',15,$Iit01_valorconstr,true,'text',$db_opcao,"onChange='js_validaValores(this)'");
					    ?>
					  </td>
					  <td>
					    <b>Valor Total:</b>
					  </td>
					  <td align="right">
						<?
						  db_input('it01_valortransacao_avalia',15,$Iit01_valortransacao,true,'text',$db_opcao,"onChange='js_validaValores(this)'");
					    ?>
					  </td>
					</tr>
					<tr>
					  <td colspan="6">
					    <div id="listaFormasPgtoAvalia"></div>
					  </td>
					</tr>
			        <tr>
					  <td>
					    <b>Valor do Imposto R$:</b>
					  </td>
					  <td>
					    <?
					      db_input('imposto_avalia',15,"",true,'text',3,"");
					    ?>
					  </td>
					  <td align="right" colspan="2">
					    <b>Vencimento:</b>
					  </td>
					  <td align="right" colspan="2">
						<?
				 		  db_inputdata('it14_dtvenc',@$it14_dtvenc_dia,@$it14_dtvenc_mes,@$it14_dtvenc_ano,true,'text',$db_opcao,"");
					    ?>
					  </td>
			        </tr>
			      </table>
			    </fieldset>
			  </td>
			</tr>
	        <tr>
	          <td colspan="6">
	            <fieldset>
	              <legend>
	                <b>Observações</b>
	              </legend>
	              <table>
				    <tr>
				      <td>
	 			        <?
						  db_textarea('it14_obs',3,120	,$Iit01_obs,true,'text',$db_opcao,"");
				        ?>
				      </td>
				    </tr>
	              </table>
	            </fieldset>
	          </td>
	        </tr>
          </table>
        </fieldset>
      </td>
    </tr>
    <tr align="center">
      <td colspan="6">
		 <input name="liberar"   type="submit" id="liberar"   value="Liberar Guia" <?=($db_botao==false?"disabled":"")?> onClick=" return js_validaCampos();">
		 <input name="visualizar" type="button" id="visualizar" value="Visualizar Guia" onclick="js_visualizar(<?php echo !empty($it14_guia) ? $it14_guia : ''; ?>);" <?=($db_botao==false?"disabled":"")?>>
		 <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
      </td>
    </tr>
  </table>
</form>
<script>
function js_visualizar(guia) {
  var iGuia  = guia;
  var sParam = "toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,height="+
                (screen.height-100)+",width="+(screen.width-100);
  window.open('reciboitbi.php?itbi='+iGuia,"",sParam);
}

function js_somaValores(){


  var aObjGrid 	    = gridFormasPgtoAvalia.getSelection("object");
  var nTotalImposto = 0;

  for ( var iInd=0; iInd < aObjGrid.length; iInd++ ) {

	var nValorAliquota    = js_strToFloat(aObjGrid[iInd].aCells[2].getValue());
	var nValorForma       = new Number(aObjGrid[iInd].aCells[3].getValue());
	var nValorImposto     = nValorForma * ( nValorAliquota / 100 );
	var nValorDescImposto = nValorImposto * ( document.form1.desconto_avalia.value / 100 );
			nValorImposto     = nValorImposto - nValorDescImposto;
			nTotalImposto     = nTotalImposto + nValorImposto;

  }

  document.form1.imposto_avalia.value = new Number(nTotalImposto).toFixed(2);

}


function js_validaCampos(){

  var aObjFormasPgto = js_getElementbyClass(document.all,'formasPgto');
  var sQuery 		 = "";

  if (aObjFormasPgto.length == 0) {

    alert('Nenhuma forma de pagamento informada!')
    return false;

  } else {

    var sPrefix = "";
    for ( var iInd=0; iInd < aObjFormasPgto.length; iInd++ ) {
      sQuery += sPrefix+aObjFormasPgto[iInd].id+"X"+aObjFormasPgto[iInd].value;
      sPrefix = "|";
    }

    document.form1.listaFormas.value = sQuery;

  }

}



function js_validaValores(obj){

  var sNomeCampo		= obj.name;
      obj.value			= new String(obj.value).replace(",",".");
      obj.value			= new Number(obj.value).toFixed(2);
  var doc				= document.form1;
  var nValorTotal 	    = new Number(doc.it01_valortransacao_avalia.value);
  var nValorTerreno 	= new Number(doc.it01_valorterreno_avalia.value);
  var nValorBenfeitoria = new Number(doc.it01_valorconstr_avalia.value);


  if ( nValorTerreno != 0 || nValorBenfeitoria != 0 ) {
	doc.it01_valortransacao_avalia.disabled = true;
    doc.it01_valortransacao_avalia.value    = new Number(nValorTerreno + nValorBenfeitoria).toFixed(2);
  } else if ( nValorTerreno == 0 && nValorBenfeitoria == 0 && sNomeCampo == "it01_valortransacao_avalia" && nValorTotal != 0) {
    doc.it01_valorterreno_avalia.disabled   = true;
    doc.it01_valorconstr_avalia.disabled    = true;
  } else if ( nValorTerreno == 0 && nValorBenfeitoria == 0 && sNomeCampo != "it01_valortransacao_avalia") {
    doc.it01_valortransacao_avalia.value    = 0;
    doc.it01_valortransacao_avalia.disabled = false;
  } else {
    doc.it01_valorterreno_avalia.disabled   = false;
    doc.it01_valorconstr_avalia.disabled    = false;
    doc.it01_valortransacao_avalia.disabled = false;
  }


  if ( doc.primeiro_avalia != undefined ) {
    js_limpaValorFormaPgto();
    doc.primeiro_avalia.value = new Number(doc.it01_valortransacao_avalia.value).toFixed(2);
  }

  js_somaValores();
}

function js_limpaValorFormaPgto(){

  var aObjFormasPgto = js_getElementbyClass(document.all,'formasPgto');
  for ( var iInd=0; iInd < aObjFormasPgto.length; iInd++ ) {
     aObjFormasPgto[iInd].value = 0;
  }

}


function js_concordaValores(){

  var doc = document.form1;

  doc.it01_valortransacao_avalia.value = new Number(doc.it01_valortransacao.value).toFixed(2);
  doc.it01_valorterreno_avalia.value   = new Number(doc.it01_valorterreno.value).toFixed(2);
  doc.it01_valorconstr_avalia.value    = new Number(doc.it01_valorconstr.value).toFixed(2);

  js_consultaFormaPgtoCadastrada(document.form1.it14_guia.value,js_retornoFormaPgtoAvaliaCadastrada);

  if ( doc.it01_valorterreno_avalia.value != 0 ){
	js_validaValores(doc.it01_valorterreno_avalia);
	doc.it01_valorterreno_avalia.focus();
  } else if ( doc.it01_valorconstr_avalia.value != 0 ){
	js_validaValores(doc.it01_valorconstr_avalia);
	doc.it01_valorconstr_avalia.focus();
  } else {
  	js_validaValores(doc.it01_valortransacao_avalia);
  	doc.it01_valortransacao_avalia.focus();
  }

}


function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_itbi','func_itbinaoliberado.php?funcao_js=parent.js_preenchepesquisa|it01_guia','Pesquisa',true);
}


function js_preenchepesquisa(chave){
  db_iframe_itbi.hide();
  location.href = 'itb1_itbiavalia001.php?chavepesquisa='+chave;
}

function js_criaGrid() {

  gridFormasPgto              = new DBGrid("listaFormasPgto");
  gridFormasPgto.nameInstance = "gridFormasPgto";

  gridFormasPgto.setCellAlign( new Array("center","left","center","right") );
  gridFormasPgto.setHeader   ( new Array("Código","Descrição","Alíquota %","Valor"));
  gridFormasPgto.setCellWidth( new Array("10%","50%","20%","20%"));

  gridFormasPgto.setHeight(80);
  gridFormasPgto.show(document.getElementById('listaFormasPgto'));

}

function js_criaGridAvalia() {

  gridFormasPgtoAvalia              = new DBGrid("listaFormasPgtoAvalia");
  gridFormasPgtoAvalia.nameInstance = "gridFormasPgtoAvalia";

  gridFormasPgtoAvalia.setCellAlign( new Array("center","left","center","right") );
  gridFormasPgtoAvalia.setHeader   ( new Array("Código","Descrição","Alíquota %","Valor"));
  gridFormasPgtoAvalia.setCellWidth( new Array("10%","50%","20%","20%"));

  gridFormasPgtoAvalia.setHeight(80);
  gridFormasPgtoAvalia.show(document.getElementById('listaFormasPgtoAvalia'));

}


function js_consultaFormaPgto(iCodTransacao){

  js_divCarregando('Aguarde...','msgBox');

  var url          = "itb4_consultaformaPagamentoRPC.php";
  var sQuery	   = "codtransacao="+iCodTransacao;
      sQuery	  += "&tipoPesquisa=formasDisponiveis";
      sQuery	  += "&tipoITBI="+document.form1.tipo.value;
  var oAjax        = new Ajax.Request( url, {
                                              method: 'post',
                                              parameters: sQuery,
                                              onComplete: js_retornoFormaPgtoAvalia
                                            }
                                      );

}


function js_consultaFormaPgtoCadastrada(iGuia,sCallback){

  js_divCarregando('Aguarde...','msgBox');

  var url          = "itb4_consultaformaPagamentoRPC.php";
  var sQuery	   = "codguia="+iGuia;
      sQuery	  += "&tipoPesquisa=formasCadastradas";
  var oAjax        = new Ajax.Request( url, {
                                              method: 'post',
                                              parameters: sQuery,
                                              onComplete: sCallback
                                            }
                                      );

}


function js_retornoFormaPgtoCadastrada(oAjax){

  var objListaForma = eval("("+oAjax.responseText+")");
  var nValor		= 0;

  gridFormasPgto.clearAll(true);

  if ( objListaForma.iStatus && objListaForma.iStatus == 2){
   	js_removeObj("msgBox");
   	alert(objListaForma.sMensagem.urlDecode());
   	return false ;
  }

  for ( var iInd = 0; iInd < objListaForma.length; iInd++ ) {

    with (objListaForma[iInd]) {

      var sDisabled  = "disabled";
      if ( iInd == 0 ) {
        var sNomeCampo = "name='primeiro'";
      } else {
        var sNomeCampo = "";
      }

      var sInputValor  = "<input type='text' id='teste_"+it25_sequencial.urlDecode()+"' value='"+it26_valor.urlDecode()+"'";
    	  sInputValor += "style='width:100%;text-align:right;height:100%;border:1px inset' "+sDisabled+" "+sNomeCampo+">";

      var aLinha	= new Array();
          aLinha[0] = it25_sequencial.urlDecode();
   	      aLinha[1] = it27_descricao.urlDecode();
    	  aLinha[2] = js_formatar(it27_aliquota.urlDecode(),'f');
    	  aLinha[3] = sInputValor;

      gridFormasPgto.addRow(aLinha);
      gridFormasPgto.renderRows();

    }
  }

  js_removeObj("msgBox");

}

function js_retornoFormaPgtoAvalia(oAjax){


  var objListaForma = eval("("+oAjax.responseText+")");
  var nValor		= 0;

  gridFormasPgtoAvalia.clearAll(true);

  if ( objListaForma.iStatus && objListaForma.iStatus == 2){
   	js_removeObj("msgBox");
   	alert(objListaForma.sMensagem.urlDecode());
   	return false ;
  }

  for ( var iInd = 0; iInd < objListaForma.length; iInd++ ) {

    with (objListaForma[iInd]) {

      if ( new Number(document.form1.it01_valortransacao_avalia.value) != 0 && it28_sequencial == 1 ){
		var nValor = new Number(document.form1.it01_valortransacao_avalia.value);
	  } else {
	    var nValor = 0;
	  }

      if ( iInd == 0 ) {
        var sDisabled  = "disabled";
        var sNomeCampo = "name='primeiro_avalia'";
      } else {
		var sDisabled  = "";
        var sNomeCampo = "";
      }

      var sInputValor  = "<input type='text' id='"+it25_sequencial.urlDecode()+"' class='formasPgto' value='"+nValor+"'";
    	  sInputValor += "style='width:100%;text-align:right;height:100%;border:1px inset' "+sDisabled+" "+sNomeCampo+"";
    	  sInputValor += " onChange='js_controlaValoresFormaPgto(this);'>";

      var aLinha	= new Array();
   	      aLinha[0] = it25_sequencial.urlDecode();
   	      aLinha[1] = it27_descricao.urlDecode();
    	  aLinha[2] = js_formatar(it27_aliquota.urlDecode(),'f');
    	  aLinha[3] = sInputValor;

      gridFormasPgtoAvalia.addRow(aLinha);
      gridFormasPgtoAvalia.aRows[iInd].isSelected = true;
      gridFormasPgtoAvalia.renderRows();

    }
  }

  js_removeObj("msgBox");
  js_somaValores();
}


function js_retornoFormaPgtoAvaliaCadastrada(oAjax){


  var objListaForma = eval("("+oAjax.responseText+")");
  var nValor		= 0;

  gridFormasPgtoAvalia.clearAll(true);

  if ( objListaForma.iStatus && objListaForma.iStatus == 2){
   	js_removeObj("msgBox");
   	alert(objListaForma.sMensagem.urlDecode());
   	return false ;
  }

  for ( var iInd = 0; iInd < objListaForma.length; iInd++ ) {

    with (objListaForma[iInd]) {

      if ( iInd == 0 ) {
        var sDisabled  = "disabled";
        var sNomeCampo = "name='primeiro_avalia'";
      } else {
		var sDisabled  = "";
        var sNomeCampo = "";
      }

      var sInputValor  = "<input type='text' id='"+it25_sequencial.urlDecode()+"' class='formasPgto' value='"+it26_valor.urlDecode()+"'";
    	  sInputValor += "style='width:100%;text-align:right;height:100%;border:1px inset' "+sDisabled+" "+sNomeCampo+"";
    	  sInputValor += " onChange='js_controlaValoresFormaPgto(this);'>";

      var aLinha	= new Array();
   	      aLinha[0] = it25_sequencial.urlDecode();
   	      aLinha[1] = it27_descricao.urlDecode();
    	  aLinha[2] = js_formatar(it27_aliquota.urlDecode(),'f');
    	  aLinha[3] = sInputValor;

      gridFormasPgtoAvalia.addRow(aLinha);
      gridFormasPgtoAvalia.aRows[iInd].isSelected = true;
      gridFormasPgtoAvalia.renderRows();

    }
  }

  js_removeObj("msgBox");
  js_somaValores();
}




function js_controlaValoresFormaPgto(obj){

  var doc 	          = document.form1;
      obj.value 	  = new String(obj.value).replace(",",".");
      obj.value		  = new Number(obj.value).toFixed(2);
  var aObjFormasPgto  = js_getElementbyClass(document.all,'formasPgto');
  var nValorTotal	  = new Number(doc.it01_valortransacao_avalia.value);
  var nValorAlterado  = new Number(obj.value);
  var nValorResto	  = new Number();


  for ( var iInd=0; iInd < aObjFormasPgto.length; iInd++ ) {
    if ( aObjFormasPgto[iInd].name != "primeiro_avalia" ) {
     var nValLinha = new Number(aObjFormasPgto[iInd].value);
	 nValorResto  += nValLinha;
	}
  }

  var nValorAvista = new Number( nValorTotal - nValorResto );

  if ( nValorAvista < 0 ) {

    nValorAvista = nValorTotal - ( nValorResto - new Number(obj.value));
    alert("A soma dos valores das formas de pagamento não conferem com o valor total do imóvel!");
    obj.value         = 0;

  }

  doc.primeiro_avalia.value = new Number(nValorAvista).toFixed(2);

  js_somaValores();

}


function js_pesquisait01_tipotransacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_itbitransacao','func_itbitransacao.php?validadata=true&funcao_js=parent.js_mostraitbitransacao1|it04_codigo|it04_descr','Pesquisa',true);
  }else{
     if(document.form1.it01_tipotransacao.value != ''){
        js_OpenJanelaIframe('','db_iframe_itbitransacao','func_itbitransacao.php?validadata=true&pesquisa_chave='+document.form1.it01_tipotransacao.value+'&funcao_js=parent.js_mostraitbitransacao','Pesquisa',false);
     }else{
       document.form1.it04_descr_avalia.value = '';
     }
  }
}

function js_mostraitbitransacao(chave,erro){

  document.form1.it04_descr_avalia.value = chave;

  if(erro==true){
    document.form1.it01_tipotransacao.focus();
    document.form1.it01_tipotransacao.value = '';
  } else {
    js_consultaFormaPgto(document.form1.it01_tipotransacao.value);
  }

}

function js_mostraitbitransacao1(chave1,chave2){

  document.form1.it01_tipotransacao.value = chave1;
  document.form1.it04_descr_avalia.value  = chave2;
  db_iframe_itbitransacao.hide();

  js_consultaFormaPgto(chave1);

}



function js_verMatric(){
  js_OpenJanelaIframe('CurrentWindow.corpo',"db_iframe_consulta",'cad3_conscadastro_002.php?cod_matricula='+document.form1.it06_matric.value,'Detalhes da Pesquisa',true);
}

js_criaGrid();
js_criaGridAvalia();

<?
  if ( isset($oGet->chavepesquisa) && !isset($oPost->liberar) ) {
	echo "js_consultaFormaPgtoCadastrada(".$oGet->chavepesquisa.",js_retornoFormaPgtoCadastrada);";
	echo "js_pesquisait01_tipotransacao(false)";
  }
?>

function js_limpaForm(){
  $('it14_guia').value        = "";
  $('it01_data').value        = "";
  $('nome').value             = "";
  $('it22_matricri').value    = "";
  $('it22_setor').value       = "";
  $('it22_descrlograd').value = "";
  $('it01_areaterreno').value = "";
  $('it01_areatrans').value   = "";
  $('it22_matricri').value    = "";
  $('adquirenteprinc').value  = "";
  $('transmitenteprinc').value = "";
  $('it04_descr').value = "";
  $('it01_valorterreno').value = "";
  $('it01_valorconstr').value = "";
  $('it01_valortransacao').value = "";
  $('it04_descr_avalia').value = "";
  $('it01_valorterreno_avalia').value = "";
  $('it01_valorconstr_avalia').value = "";
  $('it01_valortransacao_avalia').value = "";
  $('imposto_avalia').value = "";
  $('it14_dtvenc').value = "";
  gridFormasPgtoAvalia.clearAll(true);
  //gridFormasPgtoAvalia.renderRows();
  gridFormasPgto.clearAll(true);
  //gridFormasPgto.renderRows();
}

</script>
