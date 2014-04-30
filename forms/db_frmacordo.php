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

require_once("libs/db_libdicionario.php");
require_once("libs/db_utils.php");

//MODULO: Acordos
$clacordo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ac17_sequencial");
$clrotulo->label("descrdepto");
$clrotulo->label("ac02_sequencial");
$clrotulo->label("ac08_descricao");
$clrotulo->label("ac50_descricao");
$clrotulo->label("z01_nome");

if ($db_opcao == 1) {
  $db_action="aco1_acordo004.php";
} else if ($db_opcao == 2 || $db_opcao == 22) {
  $db_action="aco1_acordo005.php";
} else if ($db_opcao == 3 || $db_opcao == 33) {
  $db_action="aco1_acordo006.php";
}
db_app::load("dbtextFieldData.widget.js");

?>
<style>
.fieldsetinterno {
  border:0px;
  border-top:2px groove white;
  border-bottom:2px groove white;
}

td {
  white-space: nowrap
}

.table-vigencia td {
  width: 8%;
  white-space: nowrap
}

#ac02_descricao, #nomecontratado, #descrdepto, #ac08_descricao {
  width: 75%;
}

#ac16_objeto {
  width: 100%;
}
</style>
<form name="form1" method="post" action="<?=$db_action?>" >
<center>
<table border="0" cellspacing="0" cellpadding="0" width="36%">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
      <fieldset>
        <table border="0" cellspacing="0" cellpadding="0" width="100%">
          <tr>
            <td>
              <fieldset style='border:0px'>
                <table border="0" cellpadding="0" width="100%">
                  <tr>
                    <td nowrap title="<?=@$Tac16_sequencial?>">
                      <?=@$Lac16_sequencial?>
                    </td>
                    <td>
			                <?
			                  db_input('ac16_sequencial', 10, $Iac16_sequencial, true, 'text', 3, "");
			                ?>
                    </td>
                  </tr>
                  <tr>
                    <td nowrap title="<?=@$Tac16_origem?>">
                      <?=@$Lac16_origem?>
                    </td>
	                  <td>
				              <?
				                $aValores = array(
				                  0 => 'Selecione',
				                  1 => 'Processo de Compras',
				                  2 => 'Licitação',
				                  3 => 'Manual' ,
				                  6 => 'Empenho'
				                );

				                db_select('ac16_origem', $aValores, true, $db_opcao,
				                          " onchange='js_desabilitaselecionar();js_exibeBotaoJulgamento();' style='width:100%;'");
				              ?>
	                  </td>
                  </tr>
			            <tr>
			              <td nowrap title="<?=@$Tac16_acordogrupo?>">
			                <?
			                  db_ancora(@$Lac16_acordogrupo, "js_pesquisaac16_acordogrupo(true);", $db_opcao);
			                ?>
			              </td>
			              <td>
			                <?
			                  db_input('ac16_acordogrupo', 10, $Iac16_acordogrupo, true, 'text', $db_opcao,
			                           "onchange='js_pesquisaac16_acordogrupo(false);'");
			                  db_input('ac02_descricao', 30, $Iac02_sequencial, true, 'text', 3);
			                ?>
			              </td>
			            </tr>
				          <tr>
				            <td nowrap title="<?=@$Tac16_numero?>">
				              <?=@$Lac16_numero?>
				            </td>
				            <td>
				              <?
				                db_input('ac16_numero', 10, $Iac16_numero, true, 'text', $db_opcao);
				              ?>
				            </td>
				          </tr>
				          <tr>
				            <td  nowrap title="<?=@$Tac16_contratado?>">
				              <?
				                db_ancora(@$Lac16_contratado, "js_pesquisaac16_contratado(true);", $db_opcao);
				              ?>
				            </td>
				            <td>
				              <?
				                db_input('ac16_contratado', 10, $Iac16_contratado, true, 'text', $db_opcao,
				                         "onchange='js_pesquisaac16_contratado(false);'");
				                db_input('nomecontratado', 30, $Iz01_nome, true, 'text', 3);
				              ?>
				            </td>
				          </tr>
		              <tr>
		                <td nowrap title="<?=@$Tac16_deptoresponsavel?>">
		                  <?
		                    db_ancora("<b>Depto Responsável: </b>", "js_pesquisaac16_deptoresponsavel(true);",
		                              $db_opcao);
		                  ?>
		                </td>
		                <td>
		                  <?
		                    db_input('ac16_deptoresponsavel', 10, $Iac16_deptoresponsavel, true, 'text',
		                             $db_opcao, "onchange='js_pesquisaac16_deptoresponsavel(false)';");
		                    db_input('descrdepto', 30, $Idescrdepto, true, 'text', 3);
		                  ?>
		                </td>
		              </tr>
		              <tr>
		                <td nowrap title="<?=@$Tac16_acordocomissao?>">
		                  <?
		                    db_ancora('<b>Comissão:</b>', "onchange=js_pesquisaac16_acordocomissao(true)",
		                              $db_opcao);
		                  ?>
		                </td>
		                <td>
		                  <?
		                    db_input('ac16_acordocomissao', 10, $Iac16_acordocomissao, true, 'text', $db_opcao,
		                             "onchange=js_pesquisaac16_acordocomissao(false)");
		                    db_input('ac08_descricao', 30, $Iac08_descricao, true, 'text', 3);
		                  ?>
		                </td>
		              </tr>
		              <tr>
		                <td nowrap title="<?=@$Tac16_lei?>">
		                   <?=@$Lac16_lei?>
		                </td>
		                <td>
		                  <?
		                    db_input('ac16_lei', 50, $Iac16_lei, true, 'text', $db_opcao);
		                  ?>
		                </td>
		              </tr>
		              <tr>
		                <td nowrap title="<?=@$Tac16_numeroprocesso?>">
		                  <?=@$Lac16_numeroprocesso?>
		                </td>
		                <td>
		                  <?
		                    db_input('ac16_numeroprocesso', 50, $Iac16_numeroprocesso, true, 'text', $db_opcao);
		                  ?>
		                </td>
		              </tr>
		              <tr>
		                <td nowrap title="<?=@$Tac16_qtdrenovacao?>">
		                  <?=$Lac16_qtdrenovacao;?>
		                </td>
		                <td>
		                  <?
		                    db_input('ac16_qtdrenovacao', 2, @$Iac16_qtdrenovacao, true, 'text', $db_opcao,
		                             "", "", "");
		                    db_select("ac16_tipounidtempo", getValoresPadroesCampo("ac16_tipounidtempo"),
		                              true, $db_opcao);
		                  ?>
		                </td>
		              </tr>
		              <tr>
		                <td>
		                   <b>Contrato Emergencial:</b>
		                </td>
		                <td>
		                  <?
		                    $aEmergencial = array("f" => "Não", "t" => "Sim");
		                    db_select("ac26_emergencial", $aEmergencial, true, $db_opcao, "style='width:100%'");
		                  ?>
		                </td>
		              </tr>

		              <tr>
		                <td nowrap title="<?=@$Tac16_dataassinatura?>">
		                  <?=@$Lac16_dataassinatura?>
		                </td>

		                <td>
		                  <?
		                    db_inputdata('ac16_dataassinatura', @$ac16_dataassinatura_dia, @$ac16_dataassinatura_mes,
		                                 @$ac16_dataassinatura_ano, true, 'text', $db_opcao);
		                  ?>
		                </td>
		              </tr>

		              <tr>
		              	<td>
		              		<b>Períodos por Mês Comercial:</b>
		              	</td>
		              	<td>
		              		<?
		              		$iCampo    = 1;
		              		$sDisabled = ($db_opcao != 1 || $db_opcao != "1") ? " disabled " : "";
		              		if ($db_opcao == 2 || $db_opcao == 22) {
			                  $iCampo = 3;
		                 	}

		              		$aDivisaoPeriodos = array("false" => "NÃO", "true" => "SIM");
		              		db_select("ac16_periodocomercial", $aDivisaoPeriodos, true, $db_opcao, "style='width:100%' {$sDisabled}");
		              		?>
		              	</td>
		              </tr>
		              <tr>
		                <td nowrap title="<?=@$Tac50_descricao?>">
		                  <?
		                    db_ancora('<b>Categoria:</b>', "onchange=js_pesquisaac50_descricao(true)", $db_opcao);
		                  ?>
		                </td>
		                <td>
		                  <?
		                    db_input('ac50_sequencial', 10, $Iac50_descricao, true, 'text', $db_opcao,
		                             "onchange=js_pesquisaac50_descricao(false)");
		                    db_input('ac50_descricao', 30, $Iac50_descricao, true, 'text', 3);
		                  ?>
		                </td>
		              </tr>
                </table>
              </fieldset>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <fieldset class='fieldsetinterno'>
                <legend>
                  <b>Vigência</b>
                </legend>
                <table cellpadding="0" border="0" width="100%" class="table-vigencia">
		              <tr>
		                <td width="1%">
		                  <b>Inicio:</b>
		                </td>
		                <td>
		                  <?
			                $iCampo = 1;

			                if ($db_opcao == 2 || $db_opcao == 22){
			                	$iCampo = 3;
			                }

		                    db_inputdata('ac16_datainicio', @$ac16_datainicio_dia, @$ac16_datainicio_mes,
		                                 @$ac16_datainicio_ano, true, 'text', $iCampo,
		                                 "onchange='return js_somardias();'", "", "",
		                                 "return parent.js_somardias();");
		                  ?>
		                </td>
		                <td>
		                  <b>Fim:</b>
		                </td>
		                <td>
		                  <?

		                  db_inputdata('ac16_datafim', @$ac16_datafim_dia, @$ac16_datafim_mes, @$ac16_datafim_ano,
		                                 true, 'text', $iCampo, "onchange='return js_somardias();'",
		                                 "", "", "return parent.js_somardias();");
		                  ?>
		                </td>
		                <td>
		                  <b>Dias:</b>
		                </td>
		                <td>
		                  <?
		                    db_input('diasvigencia', 10, "", true, 'text', 3);
		                  ?>
		                </td>
		              </tr>
		              <tr>
		                <td nowrap title="Período">
		                  <strong>Período:</strong>
		                </td>
		                <td>
		                  <?
		                    db_input('ac16_qtdperiodo', 2, @$Iac16_qtdperiodo, true, 'text', $db_opcao,
		                             "", "", "");
		                    db_select("ac16_tipounidtempoperiodo", getValoresPadroesCampo("ac16_tipounidtempoperiodo"),
		                              true, $db_opcao);
		                  ?>
		                </td>
		              </tr>
                </table>
              </fieldset>
            </td>
          </tr>
		      <tr>
		        <td>&nbsp;</td>
		      </tr>
		      <tr>
		        <td nowrap title="<?=@$Tac16_objeto?>" colspan="2">
		          <fieldset>
		            <legend><?=@$Lac16_objeto?></legend>
		            <?
		              db_textarea('ac16_objeto', 3, 58, $Iac16_objeto, true, 'text', $db_opcao, "");
		            ?>
		          </fieldset>
		        </td>
		      </tr>
		      <tr>
		        <td nowrap title="<?=@$Tac16_resumoobjeto?>" colspan="2">
		          <fieldset>
		            <legend><?=@$Lac16_resumoobjeto?></legend>
					        <?
					          db_input('ac16_resumoobjeto', 40, $Iac16_resumoobjeto, true, 'text', $db_opcao, "", "",
					                   "", "width:100%");
					        ?>
		          </fieldset>
		        </td>
		      </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>

<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
       type="button"
       id="db_opcao"
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
       <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">

<input name="pesquisarlicitacoes" type="button" id="pesquisarlicitacoes" style="display: none;"
             value="Julgamentos" onclick="oContrato.verificaLicitacoes();">

<input  name="pesquisarEmpenhos" type="button" id="pesquisarEmpenhos" value="Empenhos"
       onclick="js_MostraEmpenhos();" style="display: none; ">

<input name="" type="button" id="btImprimirContrato" value="Imprimir" onclick="js_imprimirContrato()">

</center>
</form>

<style>

  .filtroEmpenho {

     width : 91px;
  }

</style>

<script>

var sCaminhoMensagens = "patrimonial.contratos.db_frmacordo";

/*
 * funcao que ira realizar vinculo dos empenhos selecionados
 */
function js_vincularEmpenhos(iNumCgm){

  var sListaEmpenhos = js_getEmpenhosSelecionados();
  var iOrigem        = $F('ac16_origem');
  var iAcordo        = $F('ac16_sequencial');
  var oParametros    = new Object();

  oParametros.exec           = 'vincularEmpenhos';
  oParametros.iNumCgm        = iNumCgm;
  oParametros.sListaEmpenhos = sListaEmpenhos;
  oParametros.iOrigem        = iOrigem;
  oParametros.iAcordo        = iAcordo;

  js_divCarregando(_M(sCaminhoMensagens + '.vincular_empenho') , 'msgBox');

  var oAjaxLista  = new Ajax.Request("ac4_acordoinclusao.rpc.php",
                          {method: "post",
                           parameters:'json='+Object.toJSON(oParametros),
                           onComplete: js_retornoVincularEmpenhos
                          });

}

function js_retornoVincularEmpenhos(oAjax){

  oGridEmpenhos.clearAll(true);
  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.iStatus == 2) {
    alert(oRetorno.sMessage.urlDecode());
    return false;
  }
  alert(oRetorno.sMessage.urlDecode());
  js_getEmpenhosVinculados();
}

/*
 * funcao que ira retornar os empenhos selecionados
 */
function js_getEmpenhosSelecionados() {

  var aListaCheckbox     = oGridEmpenhos.getSelection();
  var aListaEmpenhos = new Array();

  aListaCheckbox.each(
    function ( aRow ) {
      aListaEmpenhos.push(aRow[0]);
   }
  )

  return aListaEmpenhos;
}


function js_getEmpenhosVinculados() {

  var iAcordo = $F("ac16_sequencial");

  if (iAcordo == "") {
    return false;
  }

  js_divCarregando(_M(sCaminhoMensagens + '.empenhos_vinculados') , 'msgBox');

  var oParametros = new Object();
  oParametros.iAcordo = iAcordo;
  oParametros.exec    = "getEmpenhosVinculadosAcordo";

  var oAjax = new Ajax.Request("ac4_acordoinclusao.rpc.php",
                               {method: "post",
                                parameters: 'json=' + Object.toJSON(oParametros),
                                onComplete: js_retornoCompletaEmpenhos
                               });

}

/*
 * função para retornar os empenhos do cgm do contratado selecionado
 *  e os possiveis filtros selecionados na windowaux
 */
function js_getEmpenhos(iNumCgm) {

   var iCodigoEmpenho = $F("e60_numemp");
   var iNumeroEmpenho = $F("e60_codemp");
   var dtInicial      = $F("oTxtDataEmissaoInicial");
   var dtFinal        = $F("oTxtDataEmissaoFinal");
   var iAcordo        = $F("ac16_sequencial");

   //var msgDiv         = "Pesquisando Empenhos<br>Aguarde ...";
   var oParametros    = new Object();

   oParametros.exec           = 'getEmpenhos';
   oParametros.iNumCgm        = iNumCgm;
   oParametros.iCodigoEmpenho = iCodigoEmpenho;
   oParametros.iNumeroEmpenho = iNumeroEmpenho;
   oParametros.dtInicial      = dtInicial     ;
   oParametros.dtFinal        = dtFinal       ;
   oParametros.iAcordo        = iAcordo;

   //js_divCarregando(msgDiv,'msgBox');
   js_divCarregando(_M(sCaminhoMensagens + '.pesquisando_empenhos') , 'msgBox');

   var oAjaxLista  = new Ajax.Request("ac4_acordoinclusao.rpc.php",
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoCompletaEmpenhos
                                             });

}

 // função monta grid com empenhos retornados do cgm
 function js_retornoCompletaEmpenhos(oAjax) {

   
   js_removeObj('msgBox');
   var oRetorno = eval("("+oAjax.responseText+")");


   if (oRetorno.iStatus == 1) {
		oGridEmpenhos.clearAll(true);
     oRetorno.aDadosRetorno.each(
                   function (oDado, iInd) {

                       var lVinculado = false;
                       if (oDado.lVinculado == 'true') {
                         lVinculado = true;
                       }

                       var aRow    = new Array();
                           aRow[0] = oDado.e60_numemp ;
                           aRow[1] = oDado.e60_codemp ;
                           aRow[2] = oDado.e60_emiss  ;
                           aRow[3] = oDado.e60_vlremp ;
                           aRow[4] = oDado.e60_resumo.urlDecode() ;
                           oGridEmpenhos.addRow(aRow, true, false, lVinculado);

                      });
     oGridEmpenhos.renderRows();
   } else {
     alert(oRetorno.sMessage.urlDecode());
   }
}

 //---    WINDOW AUX para exibição de Empenhos ======================//
function js_MostraEmpenhos() {


var iNumCgm        = $F('ac16_contratado');
var sContratado    = $F("nomecontratado");
var iLarguraJanela = screen.availWidth - 80;
var iAlturaJanela  = screen.availHeight - 250;
var iAcordo        = $F('ac16_sequencial');

if (iNumCgm == '' || iNumCgm == null) {

  alert(_M(sCaminhoMensagens + '.mostrar_empenhos'));//'Selecione o Cgm do Contratado.');
  return false;
}

windowEmpenhos   = new windowAux( 'windowEmpenhos',
                                  'Empenhos',
                                  iLarguraJanela,
                                  iAlturaJanela
                                  );

var sConteudoEmpenhos  = "<div>";
    sConteudoEmpenhos += "<div id='sTituloWindow'></div> "; // container do message box

    sConteudoEmpenhos +=  "<div>";
    sConteudoEmpenhos +=  " <fieldset><legend><strong>Filtros para Seleção de Empenho</strong></legend>";
    sConteudoEmpenhos +=  "  <table border ='0'>";

    sConteudoEmpenhos +=  "    <tr>";
    sConteudoEmpenhos +=  "      <td>";
    sConteudoEmpenhos +=  "        <strong>Contratado:</strong>";
    sConteudoEmpenhos +=  "      </td>";
    sConteudoEmpenhos +=  "      <td style='color: blue;'>";
    sConteudoEmpenhos +=         iNumCgm + " - " + sContratado ;
    sConteudoEmpenhos +=  "      </td>";
    sConteudoEmpenhos +=  "    </tr>";

    sConteudoEmpenhos +=  "    <tr>";
    sConteudoEmpenhos +=  "      <td>";
    sConteudoEmpenhos +=  "        <strong>Acordo:</strong>";
    sConteudoEmpenhos +=  "      </td>";
    sConteudoEmpenhos +=  "      <td>";
    sConteudoEmpenhos +=  "        <input  type='text' class='filtroEmpenho' value='" + iAcordo + "'  id='iAcordo' readonly='readonly' style='background-color: #DEB887 ' \>";
    sConteudoEmpenhos +=  "      </td>";
    sConteudoEmpenhos +=  "    </tr>";
    
    sConteudoEmpenhos +=  "    <tr>";
    sConteudoEmpenhos +=  "      <td>";
    sConteudoEmpenhos +=  "        <strong>Código do Empenho:</strong>";
    sConteudoEmpenhos +=  "      </td>";
    sConteudoEmpenhos +=  "      <td>";
    sConteudoEmpenhos +=  "        <input class='filtroEmpenho' type='text' id='e60_numemp' onkeyup='js_ValidaCampos(this,1,\"Código do Empenho\",\"f\",\"f\",event);' >";
    sConteudoEmpenhos +=  "      </td>";
    sConteudoEmpenhos +=  "    </tr>";
    sConteudoEmpenhos +=  "    <tr>";
    sConteudoEmpenhos +=  "      <td>";
    sConteudoEmpenhos +=  "        <strong>Número do Empenho:</strong>";
    sConteudoEmpenhos +=  "      </td>";
    sConteudoEmpenhos +=  "      <td>";
    sConteudoEmpenhos +=  "        <input class='filtroEmpenho' type='text' id='e60_codemp' onkeyup='js_ValidaCampos(this,1,\"Número do Empenho\",\"f\",\"f\",event);'>";
    sConteudoEmpenhos +=  "      </td>";
    sConteudoEmpenhos +=  "    </tr>";
    sConteudoEmpenhos +=  "    <tr>";
    sConteudoEmpenhos +=  "      <td>";
    sConteudoEmpenhos +=  "        <strong>Período de Emissão:</strong>";
    sConteudoEmpenhos +=  "      </td>";
    sConteudoEmpenhos +=  "      <td>";
    sConteudoEmpenhos +=  "       <label id='ctnDataInicial' style='float:left;'></label>";
    sConteudoEmpenhos +=  "         <strong style='float:left;'>&nbsp;até&nbsp;</strong>";
    sConteudoEmpenhos +=  "       <div id='ctnDataFinal' style='float:left;' ></div>";
    sConteudoEmpenhos +=  "      </td>";
    sConteudoEmpenhos +=  "    </tr>";
    sConteudoEmpenhos +=  "    <tr>";
    sConteudoEmpenhos +=  "      <td colspan='2' align='center'>";
    sConteudoEmpenhos +=  "        <input style='margin-top:10px;' type='button' id='filtraEmpenho' name='filtraEmpenho' value='Filtrar' onclick='js_getEmpenhos("+iNumCgm+");'>";
    sConteudoEmpenhos +=  "      </td>";
    sConteudoEmpenhos +=  "    </tr>";
    sConteudoEmpenhos +=  "  </table>";
    sConteudoEmpenhos +=  " ";
    sConteudoEmpenhos +=  "<div>";
    sConteudoEmpenhos += "<div id='sContGrid' style='width: 100%;'></div></fieldset> ";    // container da grid com empenhos;
    sConteudoEmpenhos += "<center style='margin-top:10px;'>";
    sConteudoEmpenhos += "  <input type='button' value='Salvar' onclick='js_vincularEmpenhos("+iNumCgm+");' />";
    sConteudoEmpenhos += "  <input type='button' value='Cancelar' onclick='windowEmpenhos.destroy();' />";
    sConteudoEmpenhos += "<center>";

    sConteudoEmpenhos += "</div>";

 windowEmpenhos.setContent(sConteudoEmpenhos);

 //============  MESAGE BORD PARA TITULO da JANELA de Empenhos
var sTextoMessageBoard  = 'Procedimento para vincular ou desvincular empenhos ao acordo. <br> ';
    messageBoard        = new DBMessageBoard('msgboard1',
                                             'Empenhos Vinculados e a Vincular',
                                              sTextoMessageBoard,
                                              $('sTituloWindow'));

  // instancia dos objetos db_inputdata
  oTxtDataEmissaoInicial = new DBTextFieldData('oTxtDataEmissaoInicial','oTxtDataEmissaoInicial', null);
  oTxtDataEmissaoFinal   = new DBTextFieldData('oTxtDataEmissaoFinal','oTxtDataEmissaoFinal', null);


  //    funcao para corrigir a exibição do window aux, apos fechar a primeira vez
  windowEmpenhos.setShutDownFunction(function () {
    windowEmpenhos.destroy();
  });

 windowEmpenhos        .show();
 messageBoard          .show();                    // chamada que monta message board
 oTxtDataEmissaoInicial.show($('ctnDataInicial')); // chamada que monta input data inicial
 oTxtDataEmissaoFinal  .show($('ctnDataFinal'));   // chamada que monta input data final
 js_montaGridEmpenhos();                           // chamada função que monta a grid

 js_getEmpenhosVinculados();
}

//função para chamada da grid que tera erros e avisos

function js_montaGridEmpenhos() {

  var sNameGrid = 'Empenhos';
  oGridEmpenhos = new DBGrid(sNameGrid);
  oGridEmpenhos.nameInstance = 'oGridEmpenhos';
  oGridEmpenhos.allowSelectColumns(false);
  oGridEmpenhos.setCheckbox(0);

  oGridEmpenhos.setCellWidth(new Array( '10%' ,
                                        '10%' ,
                                        '10%' ,
                                        '10%' ,
                                        '60%'
                                     ));

  oGridEmpenhos.setCellAlign(new Array( 'center'  ,
                                        'right'  ,
                                        'center' ,
                                        'right'  ,
                                        'left'
                                     ));

  oGridEmpenhos.setHeader(new Array('Código do Empenho'    ,
                                    'Número do Empenho'         ,
                                    'Emissão'         ,
                                    'Valor Empenhado' ,
                                    'Resumo'
                                  ));

  oGridEmpenhos.setHeight(200);
  oGridEmpenhos.show($('sContGrid'));
  oGridEmpenhos.clearAll(true);

  $('grid' + sNameGrid).style.width = '99%';
}

/*
 * funcao que controla a exibição
   dos botoes JULGAMENTOS e EMPENHOS
   se a origem for Licitacao || Processo compras exibimos os julgamentos
   se a origem for empenho exibimos o botao para selecionar empenhos
 */
function js_exibeBotaoJulgamento() {

  var iOrigem = $F('ac16_origem');
  var iAcordo = $F("ac16_sequencial");

  switch (iOrigem) {

    case  '1':
      $('pesquisarlicitacoes').style.display = 'inLine';
      $('pesquisarEmpenhos').style.display = 'none';
    break;

    case  '2':
      $('pesquisarlicitacoes').style.display = 'inLine';
      $('pesquisarEmpenhos').style.display = 'none';
    break;

    case '6' :
        
      if ($F('ac16_sequencial') != ''){
        $('pesquisarEmpenhos').style.display = 'inLine';
      } 
      
      $('pesquisarlicitacoes').style.display = 'none';
    break;

    default  :

      $('pesquisarlicitacoes').style.display = 'none';
      $('pesquisarEmpenhos').style.display   = 'none';
    break;

  }

  if (iAcordo != '' || iAcordo != null) {
    //alert(5555);
    //$('ac16_origem').disabled        = true;
  }

}



var oContrato = new contrato();
var sURL      = "con4_contratos.RPC.php";

function js_somardias() {

  var sDataInicio         = $('ac16_datainicio').value;
  var sDataFim            = $('ac16_datafim').value;

  if (js_somarDiasVigencia(sDataInicio, sDataFim) != false) {
    $('diasvigencia').value = js_somarDiasVigencia(sDataInicio, sDataFim);
  }
}

function js_desabilitaselecionar() {

  //Limpa opções de contratado previamente selecionadas
  $("ac16_contratado").value = '';
  $("nomecontratado").value = '';

  var iAcordoOrigem = $('ac16_origem').value;

  if (iAcordoOrigem != 0) {
    $('ac16_origem').options[0].disabled = true;
  }
}

function js_pesquisaac16_acordogrupo(mostra) {

  if (mostra == true) {

    var sUrl = 'func_acordogrupo.php?funcao_js=parent.js_mostraacordogrupo1|ac02_sequencial|ac02_descricao';
    js_OpenJanelaIframe('top.corpo.iframe_acordo',
                        'db_iframe_acordogrupo',
                        sUrl,
                        'Pesquisar Grupos de Acordo',
                        true,
                        '0');
  } else {

    if ($('ac16_acordogrupo').value != '') {

      js_OpenJanelaIframe('top.corpo.iframe_acordo',
                          'db_iframe_acordogrupo',
                          'func_acordogrupo.php?pesquisa_chave='+$('ac16_acordogrupo').value+
                          '&funcao_js=parent.js_mostraacordogrupo',
                          'Pesquisar Grupos de Acordo',
                          false,
                          '0');
     } else {
       $('ac02_sequencial').value = '';
     }
  }
}

function js_mostraacordogrupo(chave,erro) {

  $('ac02_descricao').value = chave;
  if (erro == true) {

    $('ac16_acordogrupo').focus();
    $('ac16_acordogrupo').value = '';
  } else {
	  
	  var oGet = js_urlToObject();

	  /*
	   * Verifica se está sendo setada a variavel chavepesquisa na url. Caso sim, quer dizer que é um procedimento de alteração ou exclusão,
	   * sendo assim o programa não pode chamar a nova numeração
	   *
	   */
	  if (!oGet.chavepesquisa) {
			oContrato.getNumeroAcordo(); 
	  }
    
  }

}

function js_mostraacordogrupo1(chave1,chave2) {

  $('ac16_acordogrupo').value = chave1;
  $('ac02_descricao').value   = chave2;
  $('ac16_acordogrupo').focus();

  var oGet = js_urlToObject();

  /*
   * Verifica se está sendo setada a variavel chavepesquisa na url. Caso sim, quer dizer que é um procedimento de alteração ou exclusão,
   * sendo assim o programa não pode chamar a nova numeração
   *
   */
  if (!oGet.chavepesquisa) {
		oContrato.getNumeroAcordo(); 
  }
  
  db_iframe_acordogrupo.hide();
}

function js_pesquisa() {

  var sUrl = 'func_acordo.php?funcao_js=parent.js_preenchepesquisa|ac16_sequencial'+
                            '&iTipoFiltro=1,4&lAtivo=1&lComExecucao=false';
  js_OpenJanelaIframe('top.corpo.iframe_acordo',
                      'db_iframe_acordo',
                      sUrl,
                      'Pesquisar Acordos',
                      true,
                      '0',
                      '1'
                      );
}

function js_preenchepesquisa(chave) {

  db_iframe_acordo.hide();
  <?
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
  ?>

}

function js_pesquisaac16_contratado(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo.iframe_acordo',
                        'db_iframe_contratado',
                        'func_nome.php?funcao_js=parent.js_mostracontratado1|z01_nome|z01_numcgm',
                        'Pesquisar CGM',
                        true,
                        '0');
  } else {

    if ($('ac16_contratado').value != '') {

      js_OpenJanelaIframe('top.corpo.iframe_acordo',
                          'db_iframe_acordogrupo',
                          'func_nome.php?pesquisa_chave='+$F('ac16_contratado')+
                          '&funcao_js=parent.js_mostracontratado',
                          'Pesquisa',
                          false,
                          '0');
    } else {
      $('nomecontratado').value = '';
    }
  }
}

function js_mostracontratado(erro, chave) {

  $('nomecontratado').value = chave;
  if (erro == true) {

    $('ac16_contratado').focus();
    $('ac16_contratado').value = '';
  } else {
    oContrato.verificaLicitacoes();

    if ($('ac16_origem').value == 6) {
    	//js_MostraEmpenhos();
    }

  }
}

function js_mostracontratado1(chave1, chave2) {

  $('ac16_contratado').value = chave2;
  $('nomecontratado').value  = chave1;
  oContrato.verificaLicitacoes();

  if ($('ac16_origem').value == 6 ) {
  	//js_MostraEmpenhos();
  }

  db_iframe_contratado.hide();
}

function js_pesquisaac16_deptoresponsavel(mostra) {

  if (mostra == true) {

    var sUrl = 'func_db_depart.php?funcao_js=parent.js_mostradeptoresponsavel1|coddepto|descrdepto';
    js_OpenJanelaIframe('top.corpo.iframe_acordo',
                        'db_iframe_deptoresponsavel',
                        sUrl,
                        'Pesquisar CGM',
                        true,
                        '0');
  } else {

    if ($('ac16_deptoresponsavel').value != '') {

      js_OpenJanelaIframe('top.corpo.iframe_acordo',
                          'db_iframe_acordogrupo',
                          'func_db_depart.php?pesquisa_chave='+$F('ac16_deptoresponsavel')+
                          '&funcao_js=parent.js_mostradeptoresponsavel',
                          'Pesquisa',
                          false,
                          '0');
     } else {
       $('descrdepto').value = '';
     }
  }
}

function js_mostradeptoresponsavel(chave, erro) {

  $('descrdepto').value = chave;
  if (erro == true) {

    $('ac16_deptoresponsavel').focus();
    $('ac16_deptoresponsavel').value = '';
  }
}

function js_mostradeptoresponsavel1(chave1, chave2) {

  $('ac16_deptoresponsavel').value = chave1;
  $('descrdepto').value            = chave2;
  $('ac16_deptoresponsavel').focus();

  db_iframe_deptoresponsavel.hide();
}

function js_pesquisaac16_acordocomissao(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo.iframe_acordo',
                        'db_iframe_comissao',
                        'func_acordocomissao.php?funcao_js=parent.js_mostracomissao1|'+
                        'ac08_sequencial|ac08_descricao',
                        'Pesquisar Comissões de Vistoria',
                        true,
                        '0');
  } else {

    if ($('ac16_acordocomissao').value != '') {

      js_OpenJanelaIframe('top.corpo.iframe_acordo',
                          'db_iframe_comissao',
                          'func_acordocomissao.php?pesquisa_chave='+$F('ac16_acordocomissao')+
                          '&funcao_js=parent.js_mostracomissao',
                          'Pesquisar Comissões de Vistoria',
                          false,
                          '0');
     } else {
       $('ac08_descricao').value = '';
     }
  }
}

function js_mostracomissao(chave, erro) {

  $('ac08_descricao').value = chave;
  if (erro ) {

    $('ac16_acordocomissao').focus();
    $('ac16_acordocomissao').value = '';
  }
}

function js_mostracomissao1(chave1, chave2) {

  $('ac16_acordocomissao').value = chave1;
  $('ac08_descricao').value      = chave2;
  $('ac16_acordocomissao').focus();

  db_iframe_comissao.hide();
}

function js_pesquisaac50_descricao(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo.iframe_acordo',
                        'db_iframe_acordocategoria',
                        'func_acordocategoria.php?funcao_js=parent.js_mostraacordocategoria1|'+
                        'ac50_sequencial|ac50_descricao',
                        'Pesquisar Categorias de Acordo',
                        true,
                        '0');
  } else {

    if ($('ac50_sequencial').value != '') {

      js_OpenJanelaIframe('top.corpo.iframe_acordo',
                          'db_iframe_acordocategoria',
                          'func_acordocategoria.php?pesquisa_chave='+$F('ac50_sequencial')+
                          '&funcao_js=parent.js_mostraacordocategoria',
                          'Pesquisar Categorias de Acordo',
                          false,
                          '0');
     } else {
       $('ac50_descricao').value = '';
     }
  }
}

function js_mostraacordocategoria(chave1, chave2) {

  $('ac50_descricao').value  = chave1;
  $('ac50_sequencial').focus();

  db_iframe_acordocategoria.hide();
}

function js_mostraacordocategoria1(chave1, chave2) {

  $('ac50_sequencial').value = chave1;
  $('ac50_descricao').value  = chave2;
  $('ac50_sequencial').focus();

  db_iframe_acordocategoria.hide();
}

function js_imprimirContrato() {

var iContrato   = $('ac16_sequencial').value;
var iTipoOrigem = $('ac16_origem').value;

var sUrl = 'aco2_impressaoacordo001.php?iContrato='+iContrato+'&iTipoOrigem='+iTipoOrigem;
js_OpenJanelaIframe('top.corpo.iframe_acordo',
                    'db_iframe_impressaocontrato',
                    sUrl,
                    'Impressão do Contrato',
                    true,
                    '0');
}

$('db_opcao').onclick = oContrato.saveContrato;
<?
  if ($db_opcao == 2) {
    echo "\noContrato.getContrato({$chavepesquisa});\n";
  } else {
  	echo "\njs_somardias();\n";
  }
?>

//js_exibeBotaoJulgamento();
</script>