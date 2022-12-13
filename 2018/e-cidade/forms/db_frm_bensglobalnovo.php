<?php
/*
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
 * Buscamos as informações de rótulo e etc...
 */
$clrotulo = new rotulocampo;

$clrotulo->label("t30_descr");
$clrotulo->label("t33_divisao");
$clrotulo->label("o40_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("t41_placa");
$clrotulo->label("t42_descr");
$clrotulo->label("t45_sequencial");
$clrotulo->label("t45_descricao");
$clrotulo->label("t52_depart");
$clrotulo->label("t52_bem");
$clrotulo->label("t52_descr");
$clrotulo->label("t52_dtaqu");
$clrotulo->label("t52_numcgm");
$clrotulo->label("t64_descr");
$clrotulo->label("t64_class");
$clrotulo->label("t64_codcla");
$clrotulo->label("t04_sequencial");
$clrotulo->label("z01_nome_convenio");
$clrotulo->label("t53_codbem");
$clrotulo->label("t53_ntfisc");
$clrotulo->label("t53_empen");
$clrotulo->label("t53_ordem");
$clrotulo->label("t53_garant");
$clrotulo->label("t54_codbem");
$clrotulo->label("t54_idbql");
$clrotulo->label("t54_obs");
$clrotulo->label("t56_situac");
$clrotulo->label("t70_descr");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("z01_nome");
$clrotulo->label("descrdepto");
$clrotulo->label("e60_numemp");
$clrotulo->label("t44_vidautil");

$oDataAtual   = new DBDate(date("d/m/Y", db_getsession("DB_datausu")));
$oInstituicao = new Instituicao(db_getsession("DB_instit"));

$lPossuiIntegracaoPatrimonial = ParametroIntegracaoPatrimonial::possuiIntegracaoPatrimonio($oDataAtual, $oInstituicao);
?>

<fieldset style="text-align: left; width: 800px;">
  <legend class="bold">Inclusão de Bens Global</legend>
  <div style="overflow:auto;" id="fieldsetInclusaoBensGlobal">
	  <form id='form1' name="form1" method="post" action="" >
	    <fieldset style="border:none; border-top:2px groove #FFF;">
	      <legend class="bold">Dados Lote:</legend>
	      <table>
	        <tr>
	          <td><b>Lote:</b></td>
	          <td><? db_input('cod_lote', 10, '', true, 'text', 3, '') ?></td>
	          <td><b>Quantidade:</b></td>
	          <td><? db_input('quant_lote', 10, '', true, 'text', 1, "onkeypress=\"return js_mask(event,'0-9');\"") ?></td>
	        </tr>
	        <tr>
	          <td><b>Descrição:</b></td>
	          <td colspan="3"><? db_input('descr_lote',
                                         80,
                                        '',
                                        true,
                                        'text',
                                        1,
                                        "onblue='js_ValidaMaiusculo(this,'t',event);'",
                                        "",
                                        "",
                                        "text-transform: uppercase;", 40 ); ?>
            </td>
	        </tr>
	      </table>
	    </fieldset>
	    <fieldset style="border:none; border-top:2px groove #FFF;">
	      <legend class="bold">Informações do Bem</legend>
	      <table>
	        <tr>
	          <td title="<?=$Tt52_bem?>">
	            <?=$Lt52_bem?>
	          </td>
	          <td>
	            <?
	              db_input('t52_bem',10,$It52_bem,true,"text",3,"");
	            ?>
	          </td>
	          <td  id='placa' title="<?="$Tt41_placa"?>">
	            <?=$Lt41_placa?>
	          </td>
	          <td>
	            <?
	              db_input('sPlaca', 10, "text", true, "text", 1, "onblue='js_ValidaMaiusculo(this,'t',event);'",
                         "", "", "text-transform: uppercase;" );
	              db_input('t41_placa', 10, "", true, "text", 1, "onkeypress=\"return js_mask(event, '0-9')\"", "", "", "", 10);
	            ?>
	          </td>
	          <td colspan="2"></td>
	        </tr>
	        <tr >
	          <td title="<?=$Tt52_dtaqu?>"><?=$Lt52_dtaqu?></td>
	          <td colspan="6">
	            <?
	              db_inputdata('t52_dtaqu',@$t52_dtaqu_dia,@$t52_dtaqu_mes,@$t52_dtaqu_ano,true,'text',$db_opcao,"");
	            ?>
	          </td>
	        </tr>

	        <tr>
	          <td title="<?=$Tt52_descr?>"><?=$Lt52_descr?></td>
	          <td colspan="6">
	            <?
	              db_input('t52_descr',81,$It52_descr,true,'text',$db_opcao);
	            ?>
	          </td>
	        </tr>
	        <tr>
	          <td nowrap="nowrap" title="<?=@$Tt64_class?>">
	            <?
	              db_ancora(@$Lt64_class,"js_pesquisaClasse(true);",(($db_opcao == 2 && $lPossuiIntegracaoPatrimonial) ? 3 : $db_opcao));
	            ?>
	          </td>
	          <td colspan="6">
	            <?
	              db_input('t64_codcla',10,"",true,'hidden',$db_opcao);
	              db_input('t64_class',10,$It64_class,true,'text',(($db_opcao == 2 && $lPossuiIntegracaoPatrimonial) ? 3 : $db_opcao),"onchange='js_pesquisaClasse(false);'");
	              db_input('t64_descr',67,$It64_descr,true,'text',3,'');
	             ?>
	          </td>
	        </tr>
	        <tr>
	          <td nowrap="nowrap" id='td-fornecedor' title="<?=@$Tt52_numcgm?>">
	            <?
	              db_ancora(@$Lt52_numcgm,"js_pesquisaFornecedor(true);",$db_opcao);
	            ?>
	          </td>
	          <td colspan="6">
	            <?
	              db_input('t52_numcgm', 10, $It52_numcgm, true, 'text', $db_opcao, "onchange='js_pesquisaFornecedor(false);'");
	              db_input('z01_nome', 67, $Iz01_nome, true, 'text', 3, '');
	             ?>
	          </td>
	        </tr>
	        <tr>
	          <td nowrap="nowrap" title="<?=@$Tt45_sequencial?>">
	            <?
	              db_ancora(@$Lt45_descricao,"js_pesquisaTipoAquisicao(true);",$db_opcao);
	            ?>
	          </td>
	          <td colspan="6">
	            <?
	              db_input('t45_sequencial', 10, $It45_sequencial, true, 'text', $db_opcao,
	                       "onchange='js_pesquisaTipoAquisicao(false);'");
	              db_input('t45_descricao', 67, $It45_descricao, true, 'text', 3, '');
	             ?>
	          </td>
	        </tr>
	        <tr id='orgao' style="display: none">
	          <td><b>Orgão:</b></td>
	          <td colspan="5">
	            <?
	              db_input('o40_descr', 81, "", true, 'text', 3);
	            ?>
	          </td>
	        </tr>
	        <tr id='unidade' style="display: none">
	          <td><b>Unidade:</b></td>
	          <td colspan="5">
	            <?
	              db_input('o41_descr', 81, "", true, 'text', 3);
	            ?>
	          </td>
	        </tr>
	        <tr>
	          <td nowrap="nowrap" title="<?=@$Tt52_depart?>">
	            <?
	              db_ancora(@$Lt52_depart, "js_pesquisaDepartamento(true);", $db_opcao);
	            ?>
	          </td>
	          <td colspan="3">
	            <?
	              db_input('t52_depart', 10, $It52_depart, true, 'text', $db_opcao, "onchange='js_pesquisaDepartamento(false);'");
	              db_input('descrdepto', 40, $Idescrdepto, true, 'text', 3, '');
	             ?>
	          </td>
	          <td id="l-divisao" style="display: none;" title="<?=$Tt52_dtaqu?>">
	            <b>Divisão</b>
	          </td>
	          <td id="c-divisao" style="display: none;">
	            <?
	              $x = array("0" => "Selecione");
	              db_select('divisao',$x,true,$db_opcao,"");
	            ?>
	          </td>
	        </tr>
	        <tr>
	          <td nowrap="nowrap" title="Convênio">
	            <?
	              db_ancora("<b>Convênio</b>","js_pesquisaConvenio(true);",$db_opcao);
	            ?>
	          </td>
	          <td nowrap="nowrap" colspan="6">
	            <?
	              db_input('t04_sequencial', 10, $It04_sequencial, true, 'text', $db_opcao, "onchange='js_pesquisaConvenio(false);'");
	              db_input('z01_nome_convenio', 67, '', true, 'text', 3, '');
	             ?>
	          </td>
	        </tr>
	        <tr>
	          <td nowrap="nowrap" title="<?=@$Tt56_situac?>">
	            <?
	              db_ancora(@$Lt56_situac,"js_pesquisaSituacaoBem(true);",1);
	            ?>
	          </td>
	          <td nowrap="nowrap" colspan="6">
	            <?
	              db_input('t56_situac',10,$It56_situac,true,'text',1," onchange='js_pesquisaSituacaoBem(false);'");
	              db_input('t70_descr',67,$It70_descr,true,'text',3,'');
	              db_input("tipo_inclui",40,"0",true,"hidden",3,"");
	             ?>
	          </td>
	        </tr>
	      </table>
	    </fieldset>
	    <fieldset style="border:none; border-top:2px groove #FFF;">
	      <legend class="bold">Dados Financeiros</legend>
	      <table>
	        <tr>
	          <td><b>Valor de Aquisição:</b></td>
	          <td>
	            <?
	              db_input('vlAquisicao',10,$It64_descr,true,'text',$db_opcao,
	                       'onchange = "js_calculaValorTotal();"
                          onkeypress="return js_mask(event, \'0-9|.\')"');
	            ?>
	          </td>
	          <td style="text-align: right;">
	            <b>Valor de Residual:</b>
	          </td>
	          <td style="text-align: left ;">
	            <?
	              db_input('vlResidual', 10,
	                       $It64_descr,
	                       true,
	                       'text',
	                       $db_opcao,
	                       'onchange = "js_calculaValorTotal();"
                          onkeypress="return js_mask(event, \'0-9|.\')"');
	            ?>
	          </td>
	          <td style="text-align: right;">
	            <b>Valor Depreciável:</b>
	          </td>
	          <td>
	            <?
	              db_input('vlTotalDepreciavel',10,$It64_descr,true,'text',3,'');
	            ?>
	          </td>
	        </tr>
	        <tr>
            <td><b>Valor Atual:</b>
            </td>
            <td>
    	        <?
    	          db_input('vlTotal',10,$It64_descr,true,'text',3,'');
    	        ?>
            </td>
          </tr>
	        <tr>
	          <td id="tdLookupTipoDepreciacao" nowrap="nowrap" title=" alterar  <?=@$Tt64_class?>">
	            <b><?
	              db_ancora("Tipo Depreciação","js_pesquisaTipoDepreciacao(true);",$db_opcao);
	            ?></b>
	          </td>
	          <td nowrap="nowrap" colspan="3">
	            <?
	              db_input('cod_depreciacao',10,$It64_class,true,'text',$db_opcao,"onchange='js_pesquisaTipoDepreciacao(false);'");
	              db_input('descr',40,$It64_descr,true,'text',3,'');
	             ?>
	          </td>
	          <td nowrap="nowrap" title = "Vida util do bem em anos.">
	            <b>Vida Util:</b>
	          </td>
	          <td title = "Vida util do bem em anos.">
	            <?
                db_input('vidaUtil',10,$It44_vidautil,true,'text',$db_opcao,'');
	            ?>
	          </td>
	        </tr>
	      </table>
	    </fieldset>
	    <fieldset id='outros-dados'>
	      <legend class='bold'>Outros Dados</legend>
	      <table style="width: 100%">
	        <tr>
	          <td><b>Medida:</b></td>
	          <td colspan="5">
	            <?
	              $rsBensMedida = $oDaoBensMedida->sql_record($oDaoBensMedida->sql_query());
	              db_selectrecord('t67_sequencial',$rsBensMedida,'true',$db_opcao);
	            ?>
	          </td>
	        </tr>
	        <tr>
	          <td style="width: "><b>Modelo:</b></td>
	          <td colspan="5">
	            <?
	              $rsBensModelo = $oDaoBensModelo->sql_record($oDaoBensModelo->sql_query());
	              db_selectrecord('t66_sequencial',$rsBensModelo,'true',$db_opcao, "");
	            ?>
	          </td>
	        </tr>
	        <tr>
	          <td><b>Marca:</b></td>
	          <td colspan="5">
	          <?
	            $rsBensMarca = $oDaoBensMarca->sql_record($oDaoBensMarca->sql_query());
	            db_selectrecord('t65_sequencial',$rsBensMarca,'true',$db_opcao);
	          ?>
	          </td>
	        </tr>
	      </table>
	    </fieldset>
	    <fieldset id="dadosdoimovel">
	      <legend class='bold' onclick="js_mostraToogleDadosImovel();">Dados do imóvel</legend>
	      <table style="width: 100%;">
	        <tr>
	          <td><b><? db_ancora(@$Lt54_idbql,"js_pesquisaCodigoLote(true);",$db_opcao); ?></b></td>
	          <td><? db_input('t54_itbql',10,'',true,'text',$db_opcao,"") ?></td>
	        </tr>
	        <tr>
	          <td colspan="2">
	           <fieldset>
	             <legend><b>Observações:</b></legend>
	             <? db_textarea('observacoesimovel', 5, 85, '', true, 'text', 2) ?>
	           </fieldset>
	          </td>
	        </tr>
	      </table>
	    </fieldset>
	    <fieldset id="dadosdomaterial">
	      <legend class="bold" onclick="js_mostraToogleDadosMaterial();">Dados do Material</legend>
	      <table>
	        <tr>
	          <td><b>Nota Fiscal:</b></td>
	          <td><? db_input('cod_notafiscal', 40, $It53_ntfisc, 'text', $db_opcao, '') ?></td>
	        </tr>
	        <tr>
	          <td><b>Empenho do Sistema</b></td>
	          <td>
	            <select id="emp_sistema" name="emp_sistema" style="width: 80px;" onChange='js_mudaProc(this.value);'>
                <option value="s">Sim</option>
                <option value="n">Não</option>
              </select>
            </td>
	        </tr>
	        <tr>
	          <td><b>
	            <span id="procAdm"><? db_ancora(@$Le60_numemp,"js_pesquisaEmpenho(true);",$db_opcao) ?></span>
	            <span id="procAdm1" style="display:none;"><?php echo @$Le60_numemp?>:</span>
	          </b></td>
	          <td>
	            <?
                db_input('t53_empen', 10, $It53_empen, true, 'text', $db_opcao, " onchange='js_pesquisaEmpenho(false);'");
              ?>
              <span id="campoDescricao">
                <?db_input('z01_nome_empenho',30,$Iz01_nome,true,'text',3,""); ?>
              </span>
	          </td>
	        </tr>
	        <tr>
	          <td><b>Ordem de compra:</b></td>
	          <td><? db_input('cod_ordemdecompra', 10, '', true, 'text', $db_opcao, "") ?></td>
	        </tr>
	        <tr>
	          <td><b>Garantia:</b></td>
	          <td><? db_inputdata('garantia', '', '', '', true, 'text', $db_opcao, ""); ?></td>
	        </tr>
	      </table>
	    </fieldset>
	    <fieldset id='observacoes'>
        <legend class='bold'>Observações</legend>
        <?
          db_textarea('obser', 5, 98, "", true, "text", 2);
        ?>
      </fieldset>
	  </form>
  </div>
</fieldset>

<div>
  <?php
    /**
     * Efetuamos a validação da $db_opcao para setar o rótulo e nome do "db_opcao"
     */
    if ($db_opcao == 1) {

    	$sNmDbOpcao = 'incluir';
    	$sVlDbOpcao = 'Incluir';
    } else if ($db_opcao == 2 || $db_opcao == 22) {

    	$sNmDbOpcao = 'alterar';
    	$sVlDbOpcao = 'Alterar';
    } else {

    	$sNmDbOpcao = 'excluir';
    	$sVlDbOpcao = 'Excluir';
    }
  ?>
  <input type="button" id="db_opcao" name="<?= $sNmDbOpcao ?>" value="<?= $sVlDbOpcao ?>" onclick="salvarDados();" />
  <input type="button" id="pesquisar" name="pesquisar" value="Pesquisar" onclick="js_pesquisa();" />
</div>
<script type="text/javascript">

$("form1").reset();
var iParametro;
var dbOpcao = <?= $db_opcao ?>;
var iParametroPlaca = null;
var lPossuiIntegracaoPatrimonial = false;

/**
 * Função chamada ao iniciar
 */
function js_carregaDadosForm(iDbOpcao) {

  var url             = 'pat1_bensnovo.RPC.php';
  var oObject         = new Object();
  oObject.exec        = "carregaInclusao";
  oObject.dbOpcao     = iDbOpcao;

  if (iDbOpcao == 2) {

    $("quant_lote").setAttribute("readonly", "readonly");
    $("quant_lote").setAttribute("class", "leiutura");
    $("sPlaca").setAttribute("readonly", "readonly");
    $("sPlaca").setAttribute("class", "leiutura");
    js_pesquisa();
    return false;
  }
  js_divCarregando(_M('patrimonial.patrimonio.db_frm_bensglobalnovo.buscando'),'msgBox');
  var objAjax   = new Ajax.Request (url,{
                                         method:'post',
                                         asynchronous:false,
                                         parameters:'json='+Object.toJSON(oObject),
                                         onComplete:js_retornoBusca
                                        }
                                   );
}
/**
 * Retorno do js_carregaDadosForm
 */
function js_retornoBusca(oJson) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oJson.responseText+")");

  if (oRetorno.status == 2 && oRetorno.dados.parametro == 1) {

    alert(oRetorno.message.urlDecode());
  } else {

    iParametroPlaca = oRetorno.dados.parametro;
    dbOpcao         = oRetorno.dbOpcao;

    if (oRetorno.dbOpcao == 1 && iParametroPlaca != 3) {
      js_Inclusao(oRetorno.dados);
    }
    if (iParametroPlaca == 3) {
      js_buscaPlacaString();
    }
  }
}
/**
 * Formularo em modo Inclusão
 */
function js_Inclusao(oDados) {

  if (oDados.bloqueia) {
    $("sPlaca").style.display = "none";
  }

  switch (oDados.parametro) {

    case '1':

      $("t41_placa").setAttribute("readonly", "readonly");
      $("t41_placa").value = oDados.t41_placa;
      break;
  }

  $("impressa").innerHTML = "Não";
  if (oDados.lImpressa) {
    $("impressa").innerHTML = "Sim";
  }
}

function js_buscaPlacaString() {

  var oPlaca = $("placa");
  var a      = new Element('a', {'class': 'ancora', href: '#', onclick: "js_pesquisaPlacaString(true)"}).update("Placa:");
  oPlaca.innerHTML = "";
  oPlaca.appendChild(a);

  //$("sPlaca").setAttribute("onchange", "buscaPlacaStringDigitda(this.value)");
}

$("sPlaca").observe('change', js_buscaPlacaStringDigitda);

function js_buscaPlacaStringDigitda(){

  var url             = 'pat1_bensnovo.RPC.php';
  var oObject         = new ObjoDaoBensMedidaect();
  oObject.exec        = "buscaPlacaString";
  oObject.sPlaca      = $F("sPlaca");
  oObject.iParametro  = iParametroPlaca;
  js_divCarregando(_M('patrimonial.patrimonio.db_frm_bensglobalnovo.buscando'),'msgBox');
  var objAjax   = new Ajax.Request (url,{
                                         method:'post',
                                         parameters:'json='+Object.toJSON(oObject),
                                         onComplete:js_retornoPlacaStringDigitda
                                        }
                                   );
}

function js_retornoPlacaStringDigitda(oJson) {
  js_removeObj("msgBox");
  var oRetorno = eval("("+oJson.responseText+")");

  if (oRetorno.status == 1) {
    $("t41_placa").value = oRetorno.dados.t41_placa;
  }
}

/** ***********************************************************************************************************
 *
 */
function js_pesquisaPlacaString(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_bensplaca',
                      'func_bensplacatext.php?funcao_js=parent.js_mostratext|t41_placa','Pesquisa',true);
}

function js_mostratext(placa) {

  db_iframe_bensplaca.hide();
  js_buscplaca(placa);
}
function js_buscplaca(classif) {
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_bp','pat1_retseqplaca.php?classif='+classif,'',false);
}

function js_retplaca(placa,seq) {

  $("sPlaca").value    = placa;
  $("t41_placa").value = seq;
  $("t41_placa").setAttribute("readonly", "readonly");
}

/** ***********************************************************************************************************
  * Função de Pesquisa da classe
  */
function js_pesquisaClasse(mostra) {

  if (mostra) {
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_clabens',
                        'func_clabens.php?funcao_js=parent.js_mostraclabens1|t64_class|t64_descr|t64_codcla&analitica=true',
                        'Pesquisa',true);
  } else {

     testa = new String($F("t64_class"));

     if (testa != '' && testa != 0) {

       i = 0;
       for (i = 0; i < $("t64_class").value.length; i++){
         testa = testa.replace('.','');
       }
       js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_clabens',
                           'func_clabens.php?pesquisa_chave='+testa+'&funcao_js=parent.js_mostraclabens&analitica=true',
                           'Pesquisa',false);
     } else {

       if (iParametro == 2 && dbOpcao == 1) {
         $("t64_class").value = "";
       }
       $("t64_descr").value = '';
     }
  }
}

function js_mostraclabens(chave, erro, chave2) {

  $("t64_descr").value  = chave;
  $("t64_codcla").value = chave2;
  if(erro) {

    $("t64_class").value = "";
    $("t64_class").focus();
    $("t64_codcla").value = "";
  } else {

    if (iParametroPlaca == 2 && dbOpcao == 1) {
      js_buscaPlaca($F("t64_class"));
    }
  }
}

function js_mostraclabens1(chave1, chave2, chave3) {

  $("t64_class").value  = chave1;
  $("t64_descr").value  = chave2;
  $("t64_codcla").value = chave3;

  db_iframe_clabens.hide();
  if (iParametroPlaca == 2 && dbOpcao == 1) {
    js_buscaPlaca($F("t64_class"));
  }
}

function js_buscaPlaca(iClasse) {

  var url             = 'pat1_bensnovo.RPC.php';
  var oObject         = new Object();
  oObject.exec        = "carregaPlacaClasse";
  oObject.iClasse     = iClasse;
  oObject.iParametro  = iParametroPlaca;
  js_divCarregando(_M('patrimonial.patrimonio.db_frm_bensglobalnovo.buscando'),'msgBox');
  var objAjax   = new Ajax.Request (url,{
                                         method:'post',
                                         parameters:'json='+Object.toJSON(oObject),
                                         onComplete:js_retornoPlaca
                                        }
                                   );


}
/**
 * Retorno do js_carregaDadosForm
 */
function js_retornoPlaca(oJson) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oJson.responseText+")");

  if (oRetorno.status == 2) {
    alert(oRetorno.message);
  } else {

    if (oRetorno.dados.bloqueia) {
      $("sPlaca").style.display = "none";
    }

    switch (oRetorno.dados.parametro) {

      case '2':

        var sPlacaClasse     = new String($F("t64_class"));
        $("t41_placa").setAttribute("readonly", "readonly");
        $("t41_placa").value = sPlacaClasse+""+oRetorno.dados.t41_placa;
        $("sPlaca").value    = sPlacaClasse;
        break;
    }
  }
}

/** ***********************************************************************************************************
 * Função de Pesquisa do Fornecedor
 */
function js_pesquisaFornecedor(mostra) {

   if (mostra == true) {
     js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_forne',
                         'func_nome.php?funcao_js=parent.js_mostraforne1|z01_numcgm|z01_nome','Pesquisa',true);
   } else {

      if (document.form1.t52_numcgm.value != '') {
         js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_forne',
                             'func_nome.php?pesquisa_chave='+document.form1.t52_numcgm.value+'&funcao_js=parent.js_mostraforne',
                             'Pesquisa',false);
      } else {
        $("z01_nome").value = '';
      }
   }
}
function js_mostraforne(erro, chave) {

  $("z01_nome").value = chave;
  if(erro == true){
    $("t52_numcgm").focus();
    $("t52_numcgm").value = '';
  }
}
function js_mostraforne1(chave1, chave2) {

  $("t52_numcgm").value = chave1;
  $("z01_nome").value   = chave2;
  db_iframe_forne.hide();
}

/** ***********************************************************************************************************
 * Função de Pesquisa da Aquisição
 */
function js_pesquisaTipoAquisicao(mostra) {

  if (mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_aquisicao',
                        'func_benstipoaquisicao.php?funcao_js=parent.js_mostraAquisicao1|t45_sequencial|t45_descricao','Pesquisa',true);
  } else {

     if ($F("t45_sequencial") != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_aquisicao',
                            'func_benstipoaquisicao.php?pesquisa_chave='+$F("t45_sequencial")+'&funcao_js=parent.js_mostraAquisicao',
                            'Pesquisa',false);
     } else {
       $("t45_descricao").value = '';
     }
  }
}

function js_mostraAquisicao(chave, erro) {

  $("t45_descricao").value = chave;
  if(erro == true){
    $("t45_sequencial").focus();
    $("t45_sequencial").value = '';
  }
}
function js_mostraAquisicao1(chave1, chave2) {

  $("t45_sequencial").value = chave1;
  $("t45_descricao").value   = chave2;
  db_iframe_aquisicao.hide();
}
/** ***********************************************************************************************************
 * Função de Pesquisa do Departamento
 */
function js_pesquisaDepartamento(mostra) {

  if (mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_db_depart',
                        'func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true);
  } else {

     if (document.form1.t52_depart.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo',
                            'db_iframe_db_depart',
                            'func_db_depart.php?pesquisa_chave='+$F("t52_depart")+'&funcao_js=parent.js_mostradb_depart',
                            'Pesquisa',false);
     } else {
       $("descrdepto").value = '';
     }
  }
}
function js_mostradb_depart(chave, erro) {

  $("descrdepto").value = chave;
  if (erro == true) {

    $("t52_depart").focus();
    $("t52_depart").value = '';
  } else {

    js_carregaOrgaoUnidade($F("t52_depart"));
    js_carregaDadosDivisao($F("t52_depart"));
  }
}
function js_mostradb_depart1(chave1, chave2) {

  $("t52_depart").value = chave1;
  $("descrdepto").value = chave2;
  db_iframe_db_depart.hide();
  js_carregaDadosDivisao(chave1);
  js_carregaOrgaoUnidade(chave1);
}

/** ***********************************************************************************************************
 * Busca Orgao/Unidade, se o Departamento tiver divisão
 */
function js_carregaOrgaoUnidade(iDepartamento) {

  var url              = 'pat1_bensnovo.RPC.php';
  var oObject          = new Object();
  oObject.exec         = "buscaOrgaoUnidade";
  oObject.departamento = iDepartamento;

  js_divCarregando(_M('patrimonial.patrimonio.db_frm_bensglobalnovo.buscando_divisao'),'msgBox');
  var objAjax   = new Ajax.Request (url,{
                                         method:'post',
                                         parameters:'json='+Object.toJSON(oObject),
                                         onComplete:js_retornoOrgaoUnidade
                                        }
                                   );
}

function js_retornoOrgaoUnidade(oJson) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oJson.responseText+")");

  if (oRetorno.status == 2) {

    alert(oRetorno.message);
  } else {

    if (oRetorno.dados.libera == "t") {

      $("orgao").style.display   = "table-row";
      $("unidade").style.display = "table-row";
      $("o40_descr").value = oRetorno.dados.o40_descr.urlDecode();
      $("o41_descr").value = oRetorno.dados.o41_descr.urlDecode();
    }
  }
}

/** ***********************************************************************************************************
 * Busca Divisão, se o Departamento tiver divisão
 */
function js_carregaDadosDivisao(iDepartamento) {

  var url              = 'pat1_bensnovo.RPC.php';
  var oObject          = new Object();
  oObject.exec         = "buscaDivisao";
  oObject.departamento = iDepartamento;

  js_divCarregando(_M('patrimonial.patrimonio.db_frm_bensglobalnovo.buscando_divisao'),'msgBox');
  var objAjax   = new Ajax.Request (url,{
                                         method:'post',
                                         asynchronous:false,
                                         parameters:'json='+Object.toJSON(oObject),
                                         onComplete:js_retornoBuscaDivisao
                                        }
                                   );
}

function js_retornoBuscaDivisao(oJson) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oJson.responseText+")");
  if (oRetorno.status == 2) {

    $("l-divisao").style.display = "none";
    $("c-divisao").style.display = "none";
  } else {

    $("divisao").options.length = 1;
    for (i = 0; i < oRetorno.departamento.length; i++) {

      var oOption = new Element('option', {'value': ''+oRetorno.departamento[i].t30_codigo+''}).
                                                      update(oRetorno.departamento[i].t30_descr.urlDecode());
      $("divisao").appendChild(oOption);
      $("divisao").style.width = "100px";
    }
    $("l-divisao").style.display = "table-cell";
    $("c-divisao").style.display = "table-cell";
  }
}

/** ***********************************************************************************************************
 * Função de Pesquisa do Convenio
 */
 function js_pesquisaConvenio(mostra) {

   if(mostra == true) {
     js_OpenJanelaIframe('CurrentWindow.corpo',
                         'db_iframe_benscadcedente',
                         'func_benscadcedente.php?funcao_js=parent.js_mostraconvenio1|t04_sequencial|z01_nome',
                         'Pesquisa',true);
   } else {

      if ($F("t04_sequencial") != '') {
         js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_benscadcedente',
                             'func_benscadcedente.php?pesquisa_chave='+$F("t04_sequencial")+'&funcao_js=parent.js_mostraconvenio',
                             'Pesquisa',false);
      } else {
        $("z01_nome_convenio").value = '';
      }
   }
 }
 function js_mostraconvenio(chave, erro) {

   $("z01_nome_convenio").value = chave;
   if (erro == true) {

     $("t04_sequencial").focus();
     $("t04_sequencial").value = '';
   }
 }
function js_mostraconvenio1(chave1, chave2) {

  $("t04_sequencial").value    = chave1;
  $("z01_nome_convenio").value = chave2;
  db_iframe_benscadcedente.hide();
}

/** ***********************************************************************************************************
 * Função de Pesquisa do SituacaoBem
 */
function js_pesquisaSituacaoBem(mostra) {

  if(mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_situabens',
                        'func_situabens.php?funcao_js=parent.js_mostrasituabens1|t70_situac|t70_descr','Pesquisa',true);
  } else {

    if ($F("t56_situac") != '') {
       js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_situabens',
                           'func_situabens.php?pesquisa_chave='+$F("t56_situac")+'&funcao_js=parent.js_mostrasituabens',
                           'Pesquisa',false);
    }else{
      $("t70_descr").value = '';
    }
  }
}
function js_mostrasituabens(chave,erro) {

  $("t70_descr").value = chave;
  if(erro == true) {

    $("t56_situac").focus();
    $("t56_situac").value = '';
  }
}
function js_mostrasituabens1(chave1,chave2) {

  $("t56_situac").value = chave1;
  $("t70_descr").value  = chave2;
  db_iframe_situabens.hide();
}

/** ***********************************************************************************************************
 * Função de Pesquisa do TipoDepreciacao
 */
function js_pesquisaTipoDepreciacao(mostra) {

  if(mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_deprecBem',
                         'func_benstipodepreciacao.php?funcao_js=parent.js_mostraDepreciacao1|t46_sequencial|t46_descricao&limita=true','Pesquisa',true);
  } else {

    if ($F("cod_depreciacao") != '') {
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_deprecBem',
                          'func_benstipodepreciacao.php?pesquisa_chave='+$F("cod_depreciacao")+'&funcao_js=parent.js_mostraDepreciacao&limita=true',
                          'Pesquisa',false);
    }else{
       $("descr").value = '';
    }
  }
}
function js_mostraDepreciacao(chave, erro) {
  $("descr").value = chave;
  if(erro == true) {

    $("cod_depreciacao").focus();
    $("cod_depreciacao").value = '';
  }
}
function js_mostraDepreciacao1(chave1, chave2) {

  $("cod_depreciacao").value = chave1;
  $("descr").value           = chave2;
  db_iframe_deprecBem.hide();
}

/** ***********************************************************************************************************
 * Salva os Dados do Formulário
 */
function salvarDados() {

   var url     = 'pat1_benslotenovo.RPC.php';
   var oObject = new Object();

   oObject.exec              = "salvar";
   oObject.cod_lote          = $F("cod_lote");
   oObject.quant_lote        = $F("quant_lote");
   oObject.descr_lote        = $F("descr_lote");
   oObject.t52_bem           = $F("t52_bem");
   oObject.sPlaca            = $F("sPlaca");
   oObject.t41_placa         = $F("t41_placa");
   oObject.t52_dtaqu         = $F("t52_dtaqu");
   oObject.t52_descr         = encodeURIComponent(tagString($F("t52_descr")));
   oObject.t64_codcla        = $F("t64_codcla");
   oObject.t52_numcgm        = $F("t52_numcgm");
   oObject.t45_sequencial    = $F("t45_sequencial");
   oObject.t52_depart        = $F("t52_depart");
   oObject.divisao           = $F("divisao");
   oObject.t04_sequencial    = $F("t04_sequencial");
   oObject.t56_situac        = $F("t56_situac");
   oObject.vlAquisicao       = $F("vlAquisicao");
   oObject.vlResidual        = $F("vlResidual");
   oObject.vlTotal           = $F("vlAquisicao");
   oObject.cod_depreciacao   = $F("cod_depreciacao");
   oObject.vidaUtil          = $F("vidaUtil");
   oObject.t67_sequencial    = $F("t67_sequencial");
   oObject.t66_sequencial    = $F("t66_sequencial");
   oObject.t65_sequencial    = $F("t65_sequencial");
   oObject.t54_itbql         = $F("t54_itbql");
   oObject.observacoesimovel = encodeURIComponent(tagString($F("observacoesimovel")));
   oObject.cod_notafiscal    = $F("cod_notafiscal");
   oObject.t53_empen         = $F("t53_empen");
   oObject.cod_ordemdecompra = $F("cod_ordemdecompra");
   oObject.garantia          = $F("garantia");
   oObject.obser             = encodeURIComponent(tagString($F("obser")));
   oObject.empenhosistema    = $F("emp_sistema");

   if (oObject.t41_placa.trim() == "") {

     alert(_M('patrimonial.patrimonio.db_frm_bensglobalnovo.informe_placa_bem'));
     return false;
   }

   if (typeof lViewNotasPendentes != "undefined" && !lViewNotasPendentes) {
     oObject.iCodigoNotaItem = oUrl.iCodigoEmpNotaItem;
   }

   js_divCarregando('Salvando, aguarde...','msgBox');
   var objAjax = new Ajax.Request (url,{method:'post',
                                        parameters:'json='+Object.toJSON(oObject),
                                        onComplete:js_retornoBuscaSalvar
                                       });
 }
 /**
  * Retorno do js_carregaDadosForm
  */
 function js_retornoBuscaSalvar(oJson) {

   js_removeObj("msgBox");
   var oRetorno = eval("("+oJson.responseText+")");

   if (oRetorno.status == 2) {
     alert(oRetorno.mesage.urlDecode());
   } else {

     alert(_M('patrimonial.patrimonio.db_frm_bensglobalnovo.bens_cadastrados'));
     $('form1').reset();
     js_carregaDadosForm(1);
   }
 }

function js_calculaValorTotal() {

  var vlAquisicao = new Number($F("vlAquisicao"));
  var vlResidual  = new Number($F("vlResidual"));

  if (vlResidual > vlAquisicao) {

    alert(_M("patrimonial.patrimonio.db_frm_bensglobalnovo.residual_maior_que_aquisicao"));
    $("vlResidual").value = "" ;
    $("vlAquisicao").focus();
    return false;
  } else {
    $("vlTotalDepreciavel").value = (vlAquisicao - vlResidual);
    $("vlTotal").value            = (vlAquisicao);
  }
}

function js_pesquisaEmpenho(mostra) {
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empempenho',
                        'func_empempenho.php?funcao_js=parent.js_mostraEmpenho|e60_numemp|z01_nome',
                        'Pesquisa',true);
  }else{
     if(document.form1.t53_empen.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empempenho',
                            'func_empempenho.php?pesquisa_chave='+document.form1.t53_empen.value+'&funcao_js=parent.js_mostraEmpenho',
                            'Pesquisa',false);
     } else {
       document.form1.t53_empen.value        = '';
       document.form1.z01_nome_empenho.value = '';
     }
  }
}

function js_mostraEmpenho() {

  if (arguments[1] === false) {

    $("z01_nome_empenho").value = arguments[0];
  } else {
    $("t53_empen").value        = arguments[0];
    $("z01_nome_empenho").value = arguments[1];
  }
  db_iframe_empempenho.hide();
}

function js_pesquisaCodigoLote(mostra) {
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_lote',
                      'func_lote.php?funcao_js=parent.js_mostraCodigoLote|j34_idbql',
                      'Pesquisa',true);
}

function js_mostraCodigoLote() {

  if (arguments[1] === false) {
    document.form1.t54_idbql.value = '';
  } else {
    document.form1.t54_itbql.value = arguments[0];
  }
  db_iframe_lote.hide();
}

function js_mostraToogleDadosImovel() {

  if (oDadosMaterial.isDisplayed()) {

    oDadosMaterial.show(false);
  }
}

function js_mostraToogleDadosMaterial() {

  if (oDadosImovel.isDisplayed()) {

    oDadosImovel.show(false);
  }
}

function js_mudaProc(sValor) {


  $('t53_empen').value = '';
  $('z01_nome').value = '';
  if (sValor == 's') {

	  $('t53_empen').setAttribute('onChange', 'js_pesquisaEmpenho(false)');

    $('campoDescricao').style.display = '';
    $('procAdm1').style.display = 'none';
    $('procAdm').style.display = '';
  } else {
	  $('t53_empen').onchange = function(){};

    $('campoDescricao').style.display = 'none';
    $('procAdm').style.display = 'none';
    $('procAdm1').style.display = '';
  }
}

// Configura Form
var oOutrosDados                     = new DBToogle('outros-dados', false);
var oObservacoes                     = new DBToogle('observacoes', false);
var oDadosImovel                     = new DBToogle('dadosdoimovel', false);
var oDadosMaterial                   = new DBToogle('dadosdomaterial', false);
$("t67_sequencial").style.width      = "50px";
$("t66_sequencial").style.width      = "50px";
$("t65_sequencial").style.width      = "50px";
$("t67_sequencialdescr").style.width = "150px";
$("t66_sequencialdescr").style.width = "150px";
$("t65_sequencialdescr").style.width = "150px";
$("observacoesimovel").style.width   = "100%";
$("obser").style.width               = "100%";

/**
 * configuramos o fieldset para assumir a altura correta
 */
var alturaJanela = document.body.clientHeight;
$('fieldsetInclusaoBensGlobal').style.height = alturaJanela - 170;
function js_pesquisa() {
  js_OpenJanelaIframe('CurrentWindow.corpo',
                      'db_iframe_benslote',
                      'func_benslotealt.php?funcao_js=parent.js_preenchepesquisa|t42_codigo',
                      'Pesquisa',
                      true
                     );
}
function js_preenchepesquisa(chave){

  db_iframe_benslote.hide();

  $("t41_placa").setAttribute("readonly", "readonly");
  $("t41_placa").setAttribute("class", "leiutura");

  var oObject         = new Object();
  oObject.exec        = 'getDadosLote';
  oObject.iCodigoLote = chave;
  //js_divCarregando('Buscando ...', 'msgBox');
  var objAjax = new Ajax.Request ('pat1_benslotenovo.RPC.php',
                                 {method:'post',
                                  parameters:'json='+Object.toJSON(oObject),
                                  onComplete:js_retornoBuscaDadoLote
                                 }
                                 );

}

function js_retornoBuscaDadoLote(oAjax) {

  var oRetorno = eval('('+oAjax.responseText+')');

  lPossuiIntegracaoPatrimonial = oRetorno.lPossuiIntegracaoPatrimonial;

  if (oRetorno.status == 1) {

    js_bloquearValores(oRetorno.dados.dadosbem.bemComCalculo);
    if (oRetorno.dados.parametro != 3) {
       $("sPlaca").style.display = "none";
    }
    var aFields = $('form1').elements;

    $("cod_depreciacao").value = oRetorno.dados.dadosbem.cod_depreciacao;
    $("descr").value           = oRetorno.dados.dadosbem.descr.urlDecode();

    $('vidaUtil').value = new Number(oRetorno.dados.dadosbem.vidaUtil);
    $('vlTotal').value  = js_formatar(oRetorno.dados.dadosbem.vlTotal, "f");

    if (oRetorno.dados.dadosbem.t54_itbql) {
    	$('t54_itbql').value 				 = oRetorno.dados.dadosbem.t54_itbql;
    }

    if(oRetorno.dados.dadosbem.observacoesimovel){
    	$('observacoesimovel').value = oRetorno.dados.dadosbem.observacoesimovel.urlDecode();
    }

    if(oRetorno.dados.dadosbem.cod_notafiscal){
    	$('cod_notafiscal').value    = oRetorno.dados.dadosbem.cod_notafiscal;
    }

    if(oRetorno.dados.dadosbem.t53_empen){
    	$('t53_empen').value         = oRetorno.dados.dadosbem.t53_empen;
    }

    if(oRetorno.dados.dadosbem.cod_ordemdecompra){
    	$('cod_ordemdecompra').value = oRetorno.dados.dadosbem.cod_ordemdecompra;
    }

    if(oRetorno.dados.dadosbem.garantia){
    	$('garantia').value          = oRetorno.dados.dadosbem.garantia;
    }

    if(oRetorno.dados.dadosbem.obser){
    	$('obser').value             = oRetorno.dados.dadosbem.obser.urlDecode();
    }

    if(oRetorno.dados.dadosbem.empenhosistema){
    	$('emp_sistema').value       = oRetorno.dados.dadosbem.empenhosistema;
    }

    if(oRetorno.dados.dadosbem.z01_nome_empenho){
    	$('z01_nome_empenho').value  = oRetorno.dados.dadosbem.z01_nome_empenho.urlDecode();
    }

    if (oRetorno.dados.dadosbem.emp_sistema == 's') {

      $("cod_notafiscal").disabled    = true;
      $("emp_sistema").disabled       = true;
      $("t53_empen").disabled         = true;
      $("cod_ordemdecompra").disabled = true;
      $("procAdm").innerHTML          = "<b>Seq. Empenho:</b>";

      $("cod_notafiscal").style.backgroundColor    = "#DEB887";
      $("emp_sistema").style.backgroundColor       = "#DEB887";
      $("t53_empen").style.backgroundColor         = "#DEB887";
      $("cod_ordemdecompra").style.backgroundColor = "#DEB887";

      $("cod_notafiscal").style.color    = "#000";
      $("emp_sistema").style.color       = "#000";
      $("t53_empen").style.color         = "#000";
      $("cod_ordemdecompra").style.color = "#000";

    }


    for (var iField = 0; iField < aFields.length; iField++) {

      with (aFields[iField]) {
        if (oRetorno.dados[id]) {

          if (oRetorno.dados[id].urlDecode) {
            oRetorno.dados[id] = oRetorno.dados[id].urlDecode();
          }
          value = oRetorno.dados[id];
        } else if (oRetorno.dados.dadosbem[id]) {

          if (oRetorno.dados.dadosbem[id].urlDecode) {
            oRetorno.dados.dadosbem[id] = oRetorno.dados.dadosbem[id].urlDecode();
          }
          value = oRetorno.dados.dadosbem[id];
        }

      }
    }

  } else {

    alert(oRetorno.message.urlDecode());
    js_pesquisa();
  }
}


function js_bloquearValores(lBloquear) {

  var sCor = 'white';
  if (lBloquear) {
    $('tdLookupTipoDepreciacao').innerHTML = "<b>Tipo Depreciação<b>";
    sCor = '#DEB887';
  }

  $("vlAquisicao").classList.remove('readonly');
  $("vlResidual").classList.remove('readonly');

  /**
   * Caso seja alteração bloqueia os campos vlr aquisição e vlr residual
   */
  if (dbOpcao != 1 && lPossuiIntegracaoPatrimonial) {

    $("vlAquisicao").disabled = true;
    $("vlResidual").disabled  = true;

    $("vlAquisicao").classList.add('readonly');
    $("vlResidual").classList.add('readonly');

  } else {

	  $("vlAquisicao").disabled                  = lBloquear;
	  $("vlAquisicao").style.backgroundColor     = sCor;
	  $("vlAquisicao").style.color               = "#000000";
	  $("vlResidual").disabled                   = lBloquear;
	  $("vlResidual").style.backgroundColor      = sCor;
	  $("vlResidual").style.color                = "#000000";
  }

  $("vlTotal").disabled                      = lBloquear;
  $("vlTotal").style.backgroundColor         = '#DEB887';
  $("vlTotal").style.color                   = "#000000";
  $("cod_depreciacao").disabled              = lBloquear;
  $("cod_depreciacao").style.backgroundColor = sCor;
  $("cod_depreciacao").style.color           = "#000000";
  $("descr").disabled                        = lBloquear;
  $("descr").style.color                     = "#000000";
  $("vidaUtil").disabled                     = lBloquear;
  $("vidaUtil").style.backgroundColor        = sCor;
  $("vidaUtil").style.color                  = "#000000";
  $('t52_dtaqu').style.backgroundColor       = sCor;
  $('t52_dtaqu').disabled                    = lBloquear;
  $("t52_dtaqu").style.color                 = "#000000";
}


function js_carregaDadosNota() {


  if (typeof lViewNotasPendentes != "undefined" && !lViewNotasPendentes) {
    getDadosNota();
  }
}

function js_retornoDadosNota(oAjax) {

  var oRetorno = eval('('+oAjax.responseText+')');

  $('vlAquisicao').value      = oRetorno.dados.e62_vlrun;
  $('quant_lote').value       = oRetorno.dados.e72_qtd;
  $('t52_descr').value        = oRetorno.dados.pc01_descrmater.urlDecode();
  $('t52_dtaqu').value        = oRetorno.dados.e69_dtnota;
  $('t52_numcgm').value       = oRetorno.dados.e60_numcgm;
  $('z01_nome').value         = oRetorno.dados.z01_nome.urlDecode();
  $('cod_notafiscal').value   = oRetorno.dados.e69_numero;
  $('t53_empen').value        = oRetorno.dados.e60_numemp;
  $('t53_empen').disabled     = true;
  $('t53_empen').style.color  = "#000";
  $('t53_empen').style.backgroundColor = "#DEB887";
  $('z01_nome_empenho').value = oRetorno.dados.z01_nome.urlDecode();
  $("emp_sistema").disabled   = true;
  $("procAdm").innerHTML      = "<b>Seq. Empenho:</b>";
  $("cod_ordemdecompra").value = oRetorno.dados.m52_codordem;
  $("cod_ordemdecompra").setAttribute("readonly", "readonly");
  $("cod_ordemdecompra").style.backgroundColor = "#DEB887";



  $("vlAquisicao").setAttribute("readonly", "readonly")
  $("quant_lote").setAttribute("readonly", "readonly");
  $('t52_dtaqu').setAttribute("readonly", "readonly");
  $('t52_numcgm').setAttribute("readonly", "readonly");
  $('z01_nome').setAttribute("readonly", "readonly");
  $('cod_notafiscal').setAttribute("readonly", "readonly");
  $('t53_empen').setAttribute("readonly", "readonly");
  $('z01_nome_empenho').setAttribute("readonly", "readonly");

  $("vlAquisicao").setAttribute("class", "leitura");
  $("quant_lote").setAttribute("class", "leitura");
  $('t52_dtaqu').setAttribute("class", "leitura");
  $('t52_numcgm').setAttribute("class", "leitura");
  $('z01_nome').setAttribute("class", "leitura");
  $('cod_notafiscal').setAttribute("class", "leitura");
  $('t53_empen').setAttribute("class", "leitura");
  $('z01_nome_empenho').setAttribute("class", "leitura");

  $('td-fornecedor').innerHTML = "<b>Fornecedor</b>";
}

/**
 * Busca os dados da nota
 */
function getDadosNota () {

  var oGet = js_urlToObject();
  if (oGet.iCodigoEmpNotaItem != "") {

    var oObject          = new Object();
    oObject.exec         = 'getDadosNota';
    oObject.iEmpNotaItem = oGet.iCodigoEmpNotaItem;

    new Ajax.Request ('pat1_benslotenovo.RPC.php',
                      {method:'post',
                      parameters:'json='+Object.toJSON(oObject),
                      onComplete:js_retornoDadosNota
                      }
    );
  }
}
getDadosNota();
</script>