<?
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

//MODULO: Acordos
include(modification("dbforms/db_classesgenericas.php"));

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clacordoitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ac16_sequencial");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("ac20_acordo");

$db_opcao = 1;
$aParam   = db_stdClass::getParametro("empparametro",array(db_getsession("DB_anousu")));
$iCasasDecimais = 2;
if (count($aParam) > 0) {
  $iCasasDecimais = $aParam[0]->e30_numdec;
}

$oDaoAcordo = db_utils::getDao('acordo');
$sSqlAcordo = $oDaoAcordo->sql_query_file($ac20_acordo);
$rsAcordo   = $oDaoAcordo->sql_record($sSqlAcordo);
if ($rsAcordo !== false && $oDaoAcordo->numrows > 0) {
  $oAcordo = db_utils::fieldsMemory($rsAcordo, 0);
}
?>
<style>
 .fracionado input[type='checkbox']{display: none;height: 15px}
 .fracionado input[type='button']{height: 15px;}
 .fracionado {background-color: #FFFFFF}
 .fracionado td {empty-cells: show;}
 .fracionadoinvalido{background-color: #FF4649}

 .fieldsetinterno {
  border:0px;
  border-top:2px groove white;
  border-bottom:2px groove white;
 }
 fieldset.fieldsetinterno table {

  width: 100%;
  table-layout:auto;
 }
 fieldset.fieldsetinterno table tr TD:FIRST-CHILD {

  width: 80px;
  white-space: nowrap;
 }
 select {
  width: 100%;
 }
 fieldset.fieldsetinterno table tr TD {
   white-space: nowrap;
 }
 legend {
   font-weight: bold;
 }
</style>

<script type="text/javascript" src="scripts/classes/DBViewAcordoPrevisao.classe.js"></script>
<script type="text/javascript" src="scripts/classes/DBViewAcordoExecucao.classe.js"></script>
<?
db_app::load("scripts.js, strings.js, datagrid.widget.js, windowAux.widget.js,dbautocomplete.widget.js, DBHint.widget.js");
db_app::load("dbmessageBoard.widget.js, prototype.js, dbtextField.widget.js, dbcomboBox.widget.js,dbtextFieldData.widget.js");
db_app::load("time.js");
db_app::load("estilos.css, grid.style.css");
?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="40%">
  <tr height="20px;">
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
      <fieldset id='FormularioManual'>
        <legend><b>Informações do Item</b></legend>
        <table border="0" width="100%">
          <tr style='display: none'>
            <td nowrap title=>
               Acordo
            </td>
            <td>
              <?
              db_input('ac20_acordo', 10, $Iac20_acordo, true, 'text', $db_opcao, " onchange='js_pesquisaac20_acordo(false);'");
              db_input('ac16_sequencial', 10, $Iac16_sequencial, true, 'text', 3, '');
              ?>
            </td>
          </tr>
          <tr>
            <td id='tdMatMater' nowrap title="<?= $Tac20_pcmater ?>">
              <?
                db_ancora($Lac20_pcmater, "js_pesquisaac20_pcmater(true);", $db_opcao);
              ?>
            </td>
            <td colspan="4">
              <?
                db_input('ac20_pcmater', 10, $Iac20_pcmater, true, 'text', $db_opcao, " onchange='js_pesquisaac20_pcmater(false);'");
                db_input('pc01_descrmater', 40, $Ipc01_descrmater, true, 'text', 3, '');
              ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?= $Tac20_quantidade ?>">
               <?= $Lac20_quantidade ?>
            </td>
            <td>
            <?
            db_input('ac20_quantidade', 10, $Iac20_quantidade, true, 'text', $db_opcao, "");
            ?>
            </td>

            <td nowrap title="<?= $Tac20_matunid ?>">
               <?= $Lac20_matunid ?>
            </td>
            <td>
            <?
            $oDaoMatUnid  = db_utils::getDao("matunid");
            $sSqlUnidades = $oDaoMatUnid->sql_query_file(null,
                                                         "m61_codmatunid,substr(m61_descr,1,20) as m61_descr",
                                                         "m61_descr");
            $rsUnidades      = $oDaoMatUnid->sql_record($sSqlUnidades);
            $iNumRowsUnidade = $oDaoMatUnid->numrows;
            $aUnidades       = array(0 => "Selecione");
            for ($i = 0; $i < $iNumRowsUnidade; $i++) {

              $oUnidade = db_utils::fieldsMemory($rsUnidades, $i);
              $aUnidades[$oUnidade->m61_codmatunid] = $oUnidade->m61_descr;
            }
            db_select("ac20_matunid", $aUnidades, true, 1, "onchange='js_desabilitaItemSelecionar();' style='width:100%'");
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?= $Tac20_valorunitario ?>">
               <?= $Lac20_valorunitario ?>
            </td>
            <td colspan="3">
            <?
            db_input('ac20_valorunitario', 10, $Iac20_valorunitario, true, 'text', $db_opcao, "");
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?= $Tac20_elemento ?>">
               <?= $Lac20_elemento ?>
            </td>
            <td colspan="3">
            <?
            $aDesdobramento = array("0" => "Selecione");
            db_select("ac20_elemento", $aDesdobramento, true, $db_opcao, "onchange='js_desabilitaItemSelecionar();' style='width:100%'");
            ?>
            </td>
          </tr>

        </table>
        <table style="width: 100%;">
          <tr>
           <td colspan="4">
            <fieldset class='fieldsetinterno'>
              <legend>
                <b>Previsão de execução</b>
              </legend>
              <table cellpadding="0" border="0" width="100%" >
                <tr>
                  <td width="1%">
                    <b>De:</b>
                  </td>
                  <td>
                    <?php

                    //Define valores para a data inicial, deixando vazio caso não existam valores.
                    $sDiaDataInicial = isset($ac41_datainicial_dia) ? $ac41_datainicial_dia : '';
                    $sMesDataInicial = isset($ac41_datainicial_mes) ? $ac41_datainicial_mes : '';
                    $sAnoDataInicial = isset($ac41_datainicial_ano) ? $ac41_datainicial_ano : '';

                    db_inputdata('ac41_datainicial',
                                 $sDiaDataInicial,
                                 $sMesDataInicial,
                                 $sAnoDataInicial,
                                 true,
                                 'text',
                                 $db_opcao);
                    ?>
                  </td>
                  <td>
                    <b>Até:</b>
                  </td>
                  <td>
                    <?php

                    //Define valores para a data final, deixando vazio caso não existam valores.
                    $sDiaDataFinal = isset($ac41_datafinal_dia) ? $ac41_datafinal_dia : '';
                    $sMesDataFinal = isset($ac41_datafinal_mes) ? $ac41_datafinal_mes : '';
                    $sAnoDataFinal = isset($ac41_datafinal_ano) ? $ac41_datafinal_ano : '';

                    db_inputdata('ac41_datafinal',
                                 $sDiaDataFinal,
                                 $sMesDataFinal,
                                 $sAnoDataFinal,
                                 true,
                                 'text',
                                 $db_opcao);
                    ?>
                  </td>
                  <td>
                    <input type="button" id='addPerido' value='Adicionar' onclick="addPeriodo();">
                  </td>

                </tr>
              </table>
              <div id="gridPeriodos">
              </div>
            </fieldset>
          </td>
          </tr>
        </table>
        <table style="width: 100%;">
          <tr>
            <td nowrap colspan="4">
            <fieldset><legend><?= $Lac20_resumo ?></legend>
            <?
            db_textarea("ac20_resumo", 3, 50, $Iac20_resumo, true, 'text', 1, "style='width:100%'");
            ?>
            </fieldset>
            </td>
          </tr>
          </table>
       </fieldset>
     </td>
   </tr>
   <tr>
     <td colspan="2" align="center">
       <input name="incluir" type="button" id="db_opcao" value="Incluir">
       <input name="importar" type="button" onclick="js_getItensLicitacao();" id='importar_lic' value="Importar"
              style='display:none'>
       <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_limparFormulario();"
              style='display:none'>
      </td>
   </tr>
 </table>
 <fieldset>
  <legend><b>Itens</b></legend>
   <div id='cntgriditens'></div>
  </fieldset>
  <?php
  $ac20_tipocontrole = '1';
  db_input('ac20_tipocontrole', 10, 0, 0, 'hidden');
  ?>
  <input name="verificaritens" type="button" id="verificarItens" value="Verificar Itens" onclick='js_getItensOrigem()'>
  <input type='button' onclick='js_verificaTipoAcordo();' value = 'Itens do Empenho' id='itensEmpenho'
         style='display:none;' />
</center>
</form>
<script>
var sURL         = "con4_contratos.RPC.php";
iCodigoItem      = '';
iElementoDotacao = '';
iCasasDecimais   = <?= $iCasasDecimais ?>;
sTipoOrigem      = null;
var aLicitacoes  = null;

function js_pesquisaac20_acordo(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordoitem',
                        'db_iframe_acordo',
                        'func_acordo.php?funcao_js=parent.js_mostraacordo1|ac16_sequencial|ac16_sequencial',
                        'Pesquisa',true,'0','1','775','390');
  } else {
     if (document.form1.ac20_acordo.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordoitem',
                            'db_iframe_acordo',
                            'func_acordo.php?pesquisa_chave='+document.form1.ac20_acordo.value+
                            '&funcao_js=parent.js_mostraacordo','Pesquisa',false);
     }else{
       document.form1.ac16_sequencial.value = '';
     }
  }
}

function js_mostraacordo(chave, erro) {
  document.form1.ac16_sequencial.value = chave;
  if(erro==true){
    document.form1.ac20_acordo.focus();
    document.form1.ac20_acordo.value = '';
  }
}

function js_mostraacordo1(chave1,chave2){
  document.form1.ac20_acordo.value = chave1;
  document.form1.ac16_sequencial.value = chave2;
  db_iframe_acordo.hide();
}

function js_pesquisaac20_pcmater(mostra) {
  if (mostra) {

    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordoitem',
                        'db_iframe_pcmater',
                        'func_pcmatercontratos.php?funcao_js=parent.js_mostrapcmater1|pc01_codmater|pc01_descrmater',
                        'Pesquisar Materiais',
                        true,'0'
                        );
  } else {

     if (document.form1.ac20_pcmater.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordoitem',
                            'db_iframe_pcmater',
                            'func_pcmatercontratos.php?pesquisa_chave='+document.form1.ac20_pcmater.value+
                            '&funcao_js=parent.js_mostrapcmater',
                            'Pesquisar materiais',
                            false, 0);
     }else{
       document.form1.pc01_descrmater.value = '';
     }
  }
}

function js_mostrapcmater(chave,erro){
  document.form1.pc01_descrmater.value = chave;
  if(erro==true){
    document.form1.ac20_pcmater.focus();
    document.form1.ac20_pcmater.value = '';
  } else {
    js_getElementosMateriais();
  }
}

function js_mostrapcmater1(chave1, chave2) {

  document.form1.ac20_pcmater.value    = chave1;
  document.form1.pc01_descrmater.value = chave2;
  db_iframe_pcmater.hide();
  js_getElementosMateriais();
}

function js_pesquisaMaterial(mostra) {
  if (mostra) {

    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordoitem',
                        'db_iframe_pcmater',
                        'func_pcmater.php?funcao_js=parent.js_mostraMaterial|pc01_codmater|pc01_descrmater',
                        'Pesquisar Materiais',
                        true,'0'
                        );
  } else {

     if (document.form1.ac20_pcmater.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordoitem',
                            'db_iframe_pcmater',
                            'func_pcmater.php?pesquisa_chave='+document.form1.ac20_pcmater.value+
                            '&funcao_js=parent.js_mostrapcmater',
                            'Pesquisar materiais',
                            false, 0);
     }else{
       document.form1.pc01_descrmater.value = '';
     }
  }
}

function js_mostrapcmater(chave,erro){
  document.form1.pc01_descrmater.value = chave;
  if(erro==true){
    document.form1.ac20_pcmater.focus();
    document.form1.ac20_pcmater.value = '';
  } else {
    js_getElementosMateriais();
  }
}

function js_mostraMaterial(chave1,chave2){

  oTxtMaterial.setValue(chave1);
  oTxtDescrMaterial.setValue(chave2);
  db_iframe_pcmater.hide();
}

function js_pesquisao47_coddot(mostra){
  query='';
  if (iElementoDotacao != '') {
    query="elemento="+iElementoDotacao+"&";
  }

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordoitem',
                        'db_iframe_orcdotacao',
                        'func_permorcdotacao.php?'+query+'funcao_js=parent.js_mostraorcdotacao1|o58_coddot',
                        'Pesquisar Dotações',
                        true,0);

    $('Jandb_iframe_orcdotacao').style.zIndex='100000000';
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordoitem',
                        'db_iframe_orcdotacao',
                        'func_permorcdotacao.php?'+query+'pesquisa_chave='+document.form1.o47_coddot.value+
                        '&funcao_js=parent.js_mostraorcdotacao',
                        'Pesquisar Dotações',
                        false
                        );
  }
}

function js_mostraorcdotacao(chave,erro){
  if(erro==true){
    document.form1.o47_coddot.focus();
    document.form1.o47_coddot.value = '';
  }
  getSaldoDotacao(chave);
}

function js_mostraorcdotacao1(chave1) {

  oTxtDotacao.setValue(chave1);
  db_iframe_orcdotacao.hide();
  $('Jandb_iframe_orcdotacao').style.zIndex='0';
  $('oTxtQuantidadeDotacao').focus();
  getSaldoDotacao(chave1);
}

function js_showGrid(){
  oGridItens              = new DBGrid('gridItens');
  oGridItens.nameInstance = 'oGridItens';
  oGridItens.setCellAlign(new Array("center","center","left",'right','right','right',"left","center",
                                    "center","center","center"));
  oGridItens.setCellWidth(new Array("5%","5%","10%",'8%','8%','8%',"26%","7%","10%","8%"));
  oGridItens.setHeader(new Array("Ordem", "Código", "Material", "Quantidade",
                                  "Vlr Un", "Total", "Elemento","Períodos", "Dotações", "Ação"
                                  )
                        );
  oGridItens.hasTotalizador = true;
  oGridItens.show($('cntgriditens'));
}

function js_init() {

  if(oGridItens) {
    js_showGrid();
  }

  js_getItens();
}

function js_getItens() {

   var oParam     = new Object();
   oParam.iAcordo = $F('ac20_acordo');
   oParam.exec    = "getItensAcordo";
   js_divCarregando('Aguarde, pesquisando Itens', 'msgBox');
   var oAjax   = new Ajax.Request(
                             sURL,
                             {
                              method    : 'post',
                              parameters: 'json='+Object.toJSON(oParam),
                              onComplete: js_retornoGetItens
                              }
                            );
}

function js_retornoGetItens(oAjax) {
  js_removeObj('msgBox');
  oGridItens.clearAll(true);
  var oRetorno  = eval("("+oAjax.responseText+")");
  iTipoContrato = oRetorno.iTipoContrato;

  if (oRetorno.status == 2) {

    alert(oRetorno.message.urlDecode());
    return false;
  }
  if (oRetorno.iTipoContrato == 2 || oRetorno.iTipoContrato == 1) {
    $('FormularioManual').style.display = '';
    $('db_opcao').style.display         = 'none';
    if (oRetorno.itens.length == 0) {
      js_getItensOrigem();
    }

  } else {
    $('FormularioManual').style.display='';
  }
  if (oRetorno.status == 1) {

    if (oRetorno.itens.length > 0) {

      with ((window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acordo) {

        $('ac16_origem').disabled      = true;
        $('ac16_contratado').readOnly  = true;
        $('ac16_contratado').style.backgroundColor='rgb(222, 184, 135)';
      }

    } else {

      with ((window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acordo) {

        $('ac16_origem').disabled      = true;
        $('ac16_contratado').readOnly  = false;
        $('ac16_contratado').className = "";
      }
    }

    var nTotal= 0;
    oRetorno.itens.each(function (oLinha, id) {

      with (oLinha) {

        var sCor   = 'red';
        if (valortotal == totaldotacoes) {
          var sCor   = 'green';
        }
        var aLinha = new Array();
        aLinha[0]  = ordem;
        aLinha[1]  = codigo;
        aLinha[2]  = material.urlDecode();
        aLinha[3]  = quantidade;
        var iCasas = js_getNumeroCasasDecimais(valorunitario);
        if (iCasas < 2) {
          iCasas = 2;
        }
        aLinha[4]  = js_formatar(valorunitario, 'f', iCasas);
        aLinha[5]  = js_formatar(valortotal, 'f');
        aLinha[6]  = elementocodigo+' - '+elementodescricao;
        aLinha[7]  = "<input type='button' value='Ver' id='Periodos' onclick='js_mostraPeriodos("+codigo+");'>";
        aLinha[8]  = '';
        aLinha[9]  = "<input type='button' style='width:50%' value='A' onclick='js_editar("+codigo+", "+oRetorno.iTipoContrato+")'>";
        if (oRetorno.iTipoContrato != 6) {
           aLinha[9] += "<input type='button' style='width:50%' value='E' onclick='js_excluir("+codigo+")'>";
        }

        nTotal = nTotal + parseFloat(valortotal);
        oGridItens.addRow(aLinha);
        oGridItens.aRows[id].sStyle  += ';padding:1px;';
      }
    });

    oGridItens.renderRows();
    $('TotalForCol5').innerHTML = js_formatar(nTotal.toFixed(2), 'f');
  }
}

function js_saveItem() {

  var aPeriodo          = new Array();
  var aGridPeriodosRows = oGridPeriodos.aRows;
  var iMaterial         = $F('ac20_pcmater');
  var iAcordo           = $F('ac20_acordo');
  var nValorUnitario    = $F('ac20_valorunitario');
  var nQuantidade       = $F('ac20_quantidade');
  var sResumo           = $F('ac20_resumo');
  var iElemento         = $F('ac20_elemento');
  var iUnidade          = $F('ac20_matunid');
  var iTipoControle     = 1;

  aGridPeriodosRows.each(function (oPeriodos, iLinha) {

    var oPeriodo = new Object;

    oPeriodo.dtDataInicial   = oPeriodos.aCells[0].getValue();
    oPeriodo.dtDataFinal     = oPeriodos.aCells[1].getValue();
    oPeriodo.ac41_sequencial = "";
    aPeriodo.push(oPeriodo);
  });

  var oParam = new Object();
  oParam.exec = "adicionarItem";

  if (iMaterial == '') {

    alert('Informe um material!');
    return false;
  }

  if (nQuantidade == '') {

    alert('Informe a quantidade!');
    return false;
  }

  if (iUnidade == 0) {

    alert('Selecione uma unidade!');
    return false;
  }

  if (nValorUnitario == '') {

    alert('Informe um valor unitário!!');
    return false;
  }

  if (iElemento == 0) {

    alert('Selecione um desdobramento!');
    return false;
  }

  oParam.material                = new Object();
  oParam.material.iCodigo        = '';
  if (iCodigoItem != "") {

    oParam.exec = "alterarItem";
    oParam.material.iCodigo      = iCodigoItem;
  }

  oParam.material.iMaterial      = iMaterial;
  oParam.material.iAcordo        = iAcordo;
  oParam.material.iUnidade       = iUnidade;
  oParam.material.iElemento      = iElemento;
  oParam.material.nQuantidade    = nQuantidade;
  oParam.material.nValorUnitario = nValorUnitario;
  oParam.material.sResumo        = encodeURIComponent(tagString(sResumo));
  oParam.material.aPeriodo       = aPeriodo;
  oParam.material.iTipoControle  = iTipoControle;

  js_divCarregando('Aguarde, salvando itens', 'msgBox');
  var oAjax   = new Ajax.Request(
                             sURL,
                             {
                              method    : 'post',
                              parameters: 'json='+Object.toJSON(oParam),
                              onComplete: js_retornoSaveItem
                              }
                            );
}

function js_retornoSaveItem(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.status == 1) {

    if (iCodigoItem != "") {

      if (oRetorno.lAlterarDotacao) {

        sFunction = $('openDotacoes'+iCodigoItem).onclick;
        sFunction();
      }
    }
    iCodigoItem = '';
    js_limparFormulario();
    js_getItens();
    js_desabilitaItemSelecionar();
  } else {
    alert(oRetorno.message.urlDecode());
  }
  oGridPeriodos.clearAll(true);
}

function js_excluir(iCodigo) {

  var sMsgConfirma = 'Confirma a exclusão do Item?\nClique OK para Confirmar.';
  if (!confirm(sMsgConfirma)) {
    return false;
  }
  var oParam = new Object();
  oParam.exec = "excluirItem";
  oParam.material                = new Object();
  oParam.material.iCodigo        = iCodigo;
  js_divCarregando('Aguarde, excluindo itens', 'msgBox');
  var oAjax   = new Ajax.Request(
                             sURL,
                             {
                              method    : 'post',
                              parameters: 'json='+Object.toJSON(oParam),
                              onComplete: js_retornoExcluirItem
                              }
                            );
}

function js_retornoExcluirItem(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

    alert('Item Excluido com sucesso!');
    js_getItens();
    js_desabilitaItemSelecionar();
  } else {
    alert(oRetorno.message.urlDecode());
  }
}

function js_getElementosMateriais(iValorDefault) {

  iValorElemento = '';
  if (iValorDefault != null) {
    iValorElemento = iValorDefault;
  }
  js_divCarregando('Aguarde, pesquisando elementos do material', 'msgBox');
  var oParam       = new Object();
  oParam.iMaterial = $F('ac20_pcmater');
  oParam.exec      = "getElementosMateriais";
  var oAjax   = new Ajax.Request(
                             sURL,
                             {
                              method    : 'post',
                              parameters: 'json='+Object.toJSON(oParam),
                              onComplete: js_retornoGetElementosMaterias
                              }
                            );
}

function js_getItensLicitacao(){

   js_montaWindowLicitacaoItens();
   var oParam     = new Object();
   oParam.iAcordo = $F('ac20_acordo');
   oParam.exec    = "getLicitacaoItensPorFornecedor";
   js_divCarregando('Aguarde, pesquisando Itens', 'msgBox');
   var oAjax      = new Ajax.Request(
                    sURL,
                    {
                      method    : 'post',
                      parameters: 'json='+Object.toJSON(oParam),
                      onComplete: js_retornoGetItensLicitacao
                    }
                  );
}

function js_retornoGetItensLicitacao(oAjax) {

  aLicitacoes = null;
  js_removeObj('msgBox');
  var oRetorno  = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 2) {

    alert(oRetorno.message.urlDecode());
    return false;
  }
  aLicitacoes = oRetorno;
  js_preencheLicitacao();
}

function js_retornoGetElementosMaterias(oAjax) {

  js_removeObj('msgBox');
  $('ac20_elemento').options.length = 1;
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

    oRetorno.itens.each(function (oItem, id) {

       var oOption = new Option(oItem.descricao.urlDecode(), oItem.codigoelemento);
       $('ac20_elemento').add(oOption, null);
    });
    if (oRetorno.itens.length > 0) {
      $('ac20_elemento').options[1].selected = true;
    }
    if (iValorElemento != '') {
      $('ac20_elemento').value = iValorElemento;
    }
    js_desabilitaItemSelecionar();
  }
}

function js_limparFormulario() {

   $('ac20_pcmater').value           = '';
   $('pc01_descrmater').value        = '';
   $('ac20_valorunitario').value     = '';
   $('ac20_quantidade').value        = '';
   $('ac20_elemento').options.length = 1;
   $('ac20_matunid').value           = '';
   $('ac41_datainicial').value       = '';
   $('ac41_datafinal').value         = '';
   $('ac20_resumo').value            = '';
   $('db_opcao').value               = 'Incluir';
   $('cancelar').style.display       = 'none';
   aPeriodoItem                      = new Array();
   oGridItens.clearAll(true);

   js_desabilitaItemSelecionar();
}

function js_bloqueiaCampos (iTipoContrato) {

  var CONTRATO_PROCESSO_COMPRAS = 1;
  var CONTRATO_LICITACAO        = 2;
  var CONTRATO_MANUAL           = 3;
  var CONTRATO_EMPENHO          = 6;

  if (iTipoContrato == CONTRATO_EMPENHO) {
    $('itensEmpenho').style.display = '';
  }

  if (iTipoContrato == CONTRATO_MANUAL) {
    $('importar_lic').style.display = '';
  }

  if (iTipoContrato != CONTRATO_PROCESSO_COMPRAS && iTipoContrato != CONTRATO_LICITACAO) {
    $('verificarItens').style.display = 'none';
  }

  if (iTipoContrato == CONTRATO_LICITACAO || iTipoContrato == CONTRATO_PROCESSO_COMPRAS || iTipoContrato == CONTRATO_EMPENHO) {

    $('cancelar').style.display = 'none';

    $('ac20_pcmater').readOnly         = true;
    $('ac20_pcmater').style.background = '#DEB887';

    $('ac20_quantidade').readOnly         = true;
    $('ac20_quantidade').style.background = '#DEB887';

    $('ac20_matunid').disabled         = true;
    $('ac20_matunid').style.background = '#DEB887';

    $('ac20_valorunitario').readOnly         = true;
    $('ac20_valorunitario').style.background = '#DEB887';

    $('ac20_elemento').disabled         = true;
    $('ac20_elemento').style.background = '#DEB887';

    $('db_opcao').style.display = '';

    $('tdMatMater').innerHTML = "<strong>Código do Item:</strong>";

  } else {

    $('cancelar').style.display='';

    var sAncora  = "<a class='dbancora' onclick='js_pesquisaac20_pcmater(true);' style='text-decoration: underline;' href='#'>";
        sAncora += "<strong>Código do Item:</strong>";
        sAncora += "</a>";
        $('tdMatMater').innerHTML = sAncora;
  }
}

function js_editar(iCodigo, iTipoContrato) {

  js_bloqueiaCampos (iTipoContrato);

  js_divCarregando('Aguarde, pesquisando material', 'msgBox');
  var oParam         = new Object();
  oParam.iCodigoItem = iCodigo;
  oParam.exec        = "getItensAcordo";

  var oAjax   = new Ajax.Request(
                             sURL,
                             {
                              method    : 'post',
                              parameters: 'json='+Object.toJSON(oParam),
                              onComplete: js_retornoEditar
                              }
                            );
}

function js_retornoEditar(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
    with (oRetorno.item) {

      iCodigoItem  = codigo;
      $('ac20_pcmater').value       = codigomaterial;
      $('pc01_descrmater').value    = material.urlDecode();
      $('ac20_valorunitario').value = valorunitario;
      $('ac20_quantidade').value    = quantidade;
      $('ac20_elemento').value      = elemento;
      $('ac20_matunid').value       = unidade;
      $('ac20_resumo').value        = resumo.urlDecode();
      $('ac20_tipocontrole').value  = tipocontrole;

      $('db_opcao').value           = 'Alterar';
      js_getElementosMateriais(elemento);
      js_desabilitaItemSelecionar();
    }
    aPeriodoItem = oRetorno.item.aPeriodosItem;
    oGridPeriodos.clearAll(true);
    getPeriodos();
  }
}

function js_adicionarDotacao(iElemento, iLinha, iItem) {

  oDadosItem  =  oGridItens.aRows[iLinha];
  var iHeight = js_round((window.innerHeight/1.3), 0);
  var iWidth  = document.width/2;
  windowDotacaoItem = new windowAux('wndDotacoesItem',
                                    'Dotações Item '+oDadosItem.aCells[1].getValue(),
                                    iWidth,
                                    iHeight
                                   );
  var sContent  = "<div>";
  sContent     += "<fieldset><legend><b>Adicionar Dotação</b>";
  sContent     += "  <table>";
  sContent     += "   <tr>";
  sContent     += "     <td>";
  sContent     += "     <a href='#' class='dbancora' style='text-decoration: underline;'";
  sContent     += "       onclick='js_pesquisao47_coddot(true);'><b>Dotação:</b></a>";
  sContent     += "     </td>";
  sContent     += "     <td id='inputdotacao'></td>";
  sContent     += "     <td>";
  sContent     += "      <b>Saldo Dotação:</b>";
  sContent     += "     </td>";
  sContent     += "     <td id='inputsaldodotacao'></td>";
  sContent     += "   </tr>";
  sContent     += "   <tr>";
  sContent     += "     <td>";
  sContent     += "      <b>Quantidade:</b>";
  sContent     += "     </td>";
  sContent     += "     <td id='inputquantidadedotacao'></td>";
  sContent     += "     <td>";
  sContent     += "      <b>Valor:</b>";
  sContent     += "     </td>";
  sContent     += "     <td id='inputvalordotacao'></td>";
  sContent     += "    </tr>";
  sContent     += "    <tr>";
  sContent     += "     <td colspan='4' style='text-align:center'>";
  sContent     += "       <input type='button' value='Salvar' id='btnSalvarDotacao'>";
  sContent     += "     </td>";
  sContent     += "    </tr>";
  sContent     += "  </table>";
  sContent     += "</fieldset>";
  sContent     += "<fieldset>";
  sContent     += "  <div id='cntgridDotacoes'>";
  sContent     += "  </div>";
  sContent     += "</fieldset>";
  windowDotacaoItem.setContent(sContent);
  oMessageBoard = new DBMessageBoard('msgboard1',
                                    'Adicionar Dotacoes',
                                    'Dotações Item '+oDadosItem.aCells[1].getValue(),
                                    $('windowwndDotacoesItem_content')
                                    );
  windowDotacaoItem.setShutDownFunction(function() {


    if (js_strToFloat(oDadosItem.aCells[4].getValue()) == oGridDotacoes.sum(2, false)) {
      $('dotacoes'+oDadosItem.aCells[0].getValue()).style.color='green';
    } else {
      $('dotacoes'+oDadosItem.aCells[0].getValue()).style.color='red';
    }
    windowDotacaoItem.destroy();
  });
  oMessageBoard.show();

  $('btnSalvarDotacao').observe("click", js_saveDotacao);
  oTxtDotacao = new  DBTextField('oTxtDotacao', 'oTxtDotacao','', 10);
  oTxtDotacao.show($('inputdotacao'));
  oTxtDotacao.setReadOnly(true);

  oTxtValorDotacao = new  DBTextField('oTxtValorDotacao', 'oTxtValorDotacao','', 10);
  oTxtValorDotacao.show($('inputvalordotacao'));
  oTxtValorDotacao.setReadOnly(true);

  oTxtQuantidadeDotacao = new  DBTextField('oTxtQuantidadeDotacao', 'oTxtQuantidadeDotacao','', 10);
  var nValorMaximo   = oDadosItem.aCells[2].getValue();
  var nValorUnitario = js_strToFloat(oDadosItem.aCells[3].getValue()).valueOf();
  var sEvent         = ";js_validaValorDotacao(this,"+nValorMaximo+","+nValorUnitario+",\"oTxtValorDotacao\");";
  oTxtQuantidadeDotacao.addEvent("onChange", sEvent);
  oTxtQuantidadeDotacao.show($('inputquantidadedotacao'));

  oTxtSaldoDotacao = new  DBTextField('oTxtSaldoDotacao', 'oTxtSaldoDotacao','', 10);
  oTxtSaldoDotacao.show($('inputsaldodotacao'));
  oTxtSaldoDotacao.setReadOnly(true);

  oGridDotacoes              = new DBGrid('gridDotacoes');
  oGridDotacoes.nameInstance = 'oGridDotacoes';
  oGridDotacoes.setCellWidth(new Array('30%', '30%', '30%', '10%'));
  oGridDotacoes.setHeader(new Array("Dotação", "Qtd", "Valor", "Ação"));
  oGridDotacoes.setHeight(iHeight/3);
  oGridDotacoes.setCellAlign(new Array("center", "right", "right", "Center"));
  oGridDotacoes.show($('cntgridDotacoes'));

  js_getDotacoesItens(iItem);
}

function getSaldoDotacao(iDotacao) {

  var oParam         = new Object();
  oParam.exec        = "getSaldoDotacao";
  oParam.iDotacao    = iDotacao;
  js_divCarregando('Aguarde, pesquisando saldo Dotações', 'msgBox');
  var oAjax   = new Ajax.Request(
                             sURL,
                             {
                              method    : 'post',
                              parameters: 'json='+Object.toJSON(oParam),
                              onComplete: js_retornoGetSaldotacao
                              }
                            );
}

function js_retornoGetSaldotacao(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  oTxtSaldoDotacao.setValue(js_formatar(oRetorno.saldofinal ,"f"));
  js_desabilitaItemSelecionar();
}

function js_getDotacoesItens(iItem) {

  var oParam         = new Object();
  oParam.exec        = "getDotacoesItens";
  oParam.iCodigoItem = iItem;
  js_divCarregando('Aguarde, pesquisando Dotações', 'msgBox');
  var oAjax   = new Ajax.Request(
                             sURL,
                             {
                              method    : 'post',
                              parameters: 'json='+Object.toJSON(oParam),
                              onComplete: js_retornoGetDotacoes
                              }
                            );
}

function js_retornoGetDotacoes(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");
  js_removeObj('msgBox');
  js_preencheDotacoes(oRetorno.dotacoes);
  windowDotacaoItem.show();
  iElementoDotacao = oRetorno.iElementoDotacao;
}

function js_preencheDotacoes(oDados) {

  oGridDotacoes.clearAll(true);
  oDados.each(function (oRow, iSeq) {

    var aLinha = new Array();
    aLinha[0]  = oRow.dotacao;
    aLinha[1]  = oRow.quantidade;
    aLinha[2]  = js_formatar(oRow.valor, 'f');
    aLinha[3]  = "<input type='button' value='E' onclick='js_excluirDotacao("+oRow.dotacao+")' style='width:100%'>";
    oGridDotacoes.addRow(aLinha);
  });
  oGridDotacoes.renderRows();
}

function js_validaValorDotacao(obj, iQuantMax, nValUnitario, oValorTotal) {

   if (new Number(obj.value) > iQuantMax) {
     obj.value = iQuantMax;
   } else if (obj.value == 0) {
     obj.value = iQuantMax;
   }

   var nValorTotal      =  obj.value*nValUnitario;
   $(oValorTotal).value = js_formatar(nValorTotal, 'f');
}

function js_saveDotacao() {

  if (oTxtDotacao.getValue() == "") {

    alert('Informe a dotação!');
    js_pesquisao47_coddot(true);
    return false;

  }
  if (new Number(oTxtQuantidadeDotacao.getValue()) == 0 ) {

    alert('Informe uma quantidade para o item!');
    $('oTxtQuantidadeDotacao').focus();
    return false;
  }
  var oParam         = new Object();
  oParam.exec        = "saveDotacaoItens";
  oParam.iCodigoItem = oDadosItem.aCells[0].getValue();
  oParam.iDotacao    = oTxtDotacao.getValue();
  oParam.nQuantidade = oTxtQuantidadeDotacao.getValue();
  oParam.nValor      = js_strToFloat(oTxtValorDotacao.getValue()).valueOf();
  js_divCarregando('Aguarde, salvando Dotações', 'msgBox');
  var oAjax   = new Ajax.Request(
                             sURL,
                             {
                              method    : 'post',
                              parameters: 'json='+Object.toJSON(oParam),
                              onComplete: js_retornoSaveDotacoes
                              }
                            );
}

function js_retornoSaveDotacoes(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

    oTxtValorDotacao.setValue('');
    oTxtQuantidadeDotacao.setValue('');
    oTxtDotacao.setValue('');
    oTxtSaldoDotacao.setValue('');
    js_preencheDotacoes(oRetorno.dotacoes);
  } else  {
    alert(oRetorno.message.urlDecode());
  }
}

function js_excluirDotacao(iDotacao) {

  var oParam         = new Object();
  oParam.exec        = "excluirDotacaoItens";
  oParam.iDotacao    = iDotacao;
  oParam.iCodigoItem = oDadosItem.aCells[0].getValue();
  js_divCarregando('Aguarde, excluindo Dotações', 'msgBox');
  var oAjax   = new Ajax.Request(
                             sURL,
                             {
                              method    : 'post',
                              parameters: 'json='+Object.toJSON(oParam),
                              onComplete: js_excluirSaveDotacoes
                              }
                            );
}

function js_excluirSaveDotacoes(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
    js_preencheDotacoes(oRetorno.dotacoes);
  } else  {
    alert(oRetorno.message.urlDecode());
  }
}

function js_montaGridItensOrigens(){

  oGridItensOrigem              = new DBGrid('gridItensOrigem');
  oGridItensOrigem.nameInstance = 'oGridItensOrigem';
  oGridItensOrigem.setCheckbox(0);

  oGridItensOrigem.setCellAlign(new Array("right",
                                          "right",
                                          "right",
                                          "left",
                                          'right',
                                          'right',
                                          'right',
                                          "left",
                                          "center",
                                          "center",
                                          "center",
                                          "center"
                                       ));

  oGridItensOrigem.setCellWidth(new Array("0%",
                                          '0%',
                                          "10%",
                                          "28%",
                                          '8%',
                                          '8%',
                                          '0%',
                                          "10%" ,
                                          "0%",
                                          "18%",
                                          "18%",
                                          "0%",
                                          "0%"
                                        ));

  oGridItensOrigem.setHeader(new Array("Código",
                                       '',
                                       "Cod.Mater",
                                       "Material",
                                       "Quantidade",
                                       "Vlr Un",
                                       "Total",
                                       "Tipo",
                                       "",
                                       "Previsão Inicial",
                                       "Previsão Final",
                                       "Serviço",
                                       "Elemento"
                                     ));

  oGridItensOrigem.aHeaders[1].lDisplayed = false;
  oGridItensOrigem.aHeaders[2].lDisplayed = false;
  oGridItensOrigem.aHeaders[7].lDisplayed = false;
  oGridItensOrigem.aHeaders[9].lDisplayed = false;
  oGridItensOrigem.aHeaders[12].lDisplayed = false;
  oGridItensOrigem.aHeaders[13].lDisplayed = false;
  oGridItensOrigem.show($('ctngridItensOrigem'));

  /**
   * Verifica o 'click' do botao Replicar. Percorre as linhas da Grid, verificando quais estao selecionadas e quais
   * campos devem ser replicados para estas linhas selecionadas, substituindo os valores
   */
  $('btnReplicar').observe("click", function() {

    var iLinhasSelecionadas = 0;
    oGridItensOrigem.aRows.each(function(oLinha, iSeq) {

      if (oLinha.isSelected == true) {

        if (oTxtPrevisaoInicial.getValue() != '') {

          var aItens = oGridItensOrigem.getElementsByClass('classDataEmissaoInicial');

          for (var i = 0; i < aItens.length; i++) {
           if (aItens[i].parentNode.parentNode.childNodes[0].childNodes[0].checked == true) {
             aItens[i].value = oTxtPrevisaoInicial.getValue();
           }
          }
        }

        if (oTxtPrevisaoFinal.getValue() != '') {

          var aItens = oGridItensOrigem.getElementsByClass('classDataEmissaoFinal');

          for (var i = 0; i < aItens.length; i++) {
           if (aItens[i].parentNode.parentNode.childNodes[0].childNodes[0].checked == true) {
             aItens[i].value = oTxtPrevisaoFinal.getValue();
           }
          }
        }

        iLinhasSelecionadas++;
      }
    });

    if (iLinhasSelecionadas == 0) {

      alert('Nenhuma linha foi selecionada.');
      return false;
    }
  });
}

function js_montaWindowItensOrigens(){

  var iHeight = js_round((window.innerHeight/1.3), 0);
  var iWidth  = document.width-60;

  if (document.getElementById('wndItensOrigem') != null) {

    windowItensOrigem.destroy();
  }

  windowItensOrigem = new windowAux('wndItensOrigem',
                                    'Selecionar Itens para Contrato',
                                    900,
                                    iHeight
                                   );

  var sContent  = "<div>";
  sContent     += "<fieldset><legend><b>Replicar Informações</b></legend>";
  sContent     += "  <div id='ctnReplicaInformacoes'>";
  sContent     += "    <table>";
  sContent     += "      <tr>";
  sContent     += "        <td><label><b>Previsão Inicial:<b></label></td>";
  sContent     += "        <td id='ctnPrevisaoInicial'>";
  sContent     += "        </td>";
  sContent     += "      </tr>";
  sContent     += "      <tr>";
  sContent     += "        <td><label><b>Previsão Final:<b></label></td>";
  sContent     += "        <td id='ctnPrevisaoFinal'>";
  sContent     += "        </td>";
  sContent     += "      </tr>";
  sContent     += "    </table>";
  sContent     += "  </div>";
  sContent     += "</fieldset>";
  sContent     += "<center>";
  sContent     += "  <input type='button' value='Replicar' id='btnReplicar'>";
  sContent     += "</center>";
  sContent     += "<fieldset><legend><b>Escolha os Itens</b></legend>";
  sContent     += "  <div id='ctngridItensOrigem'>";
  sContent     += "  </div>";
  sContent     += "</fieldset>";
  sContent     += "</div>";
  sContent     += "<center>";
  sContent     += "  <input type='button' value='Salvar' id='btnVincularItens'>";
  sContent     += "</center>";
  windowItensOrigem.setContent(sContent);
  windowItensOrigem.allowCloseWithEsc(false);

  var sAjuda = 'Selecione abaixo os itens que irão fazer parte do contrato. Caso não seja exibido nenhum item, ' +
               'selecione na aba <b>Acordo</b> o empenho, processo de compra ou licitação que possui os itens.';
  oMessageBoard = new DBMessageBoard('msgboardItensOrigem',
                                     'Escolha os Itens que farão parte do contrato',
                                     sAjuda,
                                     $('windowwndItensOrigem_content')
                                    );

  windowItensOrigem.setShutDownFunction(function() {
    windowItensOrigem.destroy();
  });

  $('btnVincularItens').onclick = js_vincularItens;

  oTxtPrevisaoInicial = new DBTextFieldData('txtPrevisaoInicial','oTxtPrevisaoInicial', null);
  oTxtPrevisaoInicial.show($('ctnPrevisaoInicial'));

  oTxtPrevisaoFinal = new DBTextFieldData('txtPrevisaoFinal','oTxtPrevisaoFinal', null);
  oTxtPrevisaoFinal.show($('ctnPrevisaoFinal'));

  windowItensOrigem.show();
  js_montaGridItensOrigens();
}

function js_montaWindowLicitacaoItens(){

  var iHeight = js_round((window.innerHeight/1.3), 0);
  var iWidth  = document.width-60;

  if (document.getElementById('wndLicitacaoItens') != null) {

    windowLicitacaoItens.destroy();
  }

  windowLicitacaoItens = new windowAux('wndLicitacaoItens',
                                    'Selecionar Itens da Licitação',
                                    900,
                                    iHeight
                                   );

  var sContent  = "<div>";
  sContent     += "<fieldset><legend><b>Selecione a Licitação</b></legend>";
  sContent     += "  <div id='ctnLicitacoes'>";
  sContent     += '     <select name="optlicitacoes" id="optlicitacoes" onchange="js_preencheLicitacaoItens();" style="width:100%">';
  sContent     += '       <option value="0" selected="">Selecione</option>';
  sContent     += '     </select>';
  sContent     += "  </div>";
  sContent     += "</fieldset>";
  sContent     += "<fieldset><legend><b>Escolha os Itens</b></legend>";
  sContent     += "  <div id='ctngridLicitacaoItens'>";
  sContent     += "  </div>";
  sContent     += "</fieldset>";
  sContent     += "</div>";
  sContent     += "<center>";
  sContent     += "  <input type='button' value='Salvar' id='btnVincularItens'>";
  sContent     += "</center>";

  windowLicitacaoItens.setContent(sContent);
  windowLicitacaoItens.allowCloseWithEsc(false);

  var sAjuda = 'Selecione abaixo os itens que irão fazer parte do contrato.';
  oMessageBoard = new DBMessageBoard('msgboardLicitacaoItens',
                                     'Escolha os Itens que farão parte do contrato',
                                     sAjuda,
                                     $('windowwndLicitacaoItens_content')
                                    );

  windowLicitacaoItens.setShutDownFunction(function() {
    windowLicitacaoItens.destroy();
  });

  $('btnVincularItens').onclick = js_vincularLicitacaoItens;

  windowLicitacaoItens.show();
  js_montaGridLicitacaoItens();
}

function js_preencheLicitacao(){

  var optionsLicitacoes = $('optlicitacoes');

  aLicitacoes.licitacoes.each(function(oLicitacao, iLic){
    // Cria a opcao da licitacao
    var oOption = new Option(oLicitacao.numero + " - Modalidade: " + oLicitacao.modalidade.urlDecode(), oLicitacao.licitacao);
    // Adiciona a opcao da licitacao
    optionsLicitacoes.add(oOption);
  });
}

function js_preencheLicitacaoItens() {

  oGridLicitacaoItens.clearAll(true);
  $('btnVincularItens').disabled = false;

  var iTotalLinhas          = 0;
  var aCodigos              = new Array();
  var iLicitacaoSelecionada = $('optlicitacoes').value;

  aLicitacoes.licitacoes.each(function(oLicitacao, iLic){

    if(iLicitacaoSelecionada == oLicitacao.licitacao){

      oLicitacao.itens.each(function (oRow, iSeq) {

        var aLinha = new Array();

        aCodigos.push(oRow.codigo);
        oTxtDataEmissaoInicial = new DBTextFieldData('oTxtDataEmissaoInicial'+oRow.codigo,'oTxtDataEmissao', null);
        oTxtDataEmissaoFinal   = new DBTextFieldData('oTxtDataEmissaoFinal'+oRow.codigo,'oTxtDataEmissao', null);
        aLinha[0]              = oRow.codigo;
        aLinha[1]              = iTotalLinhas;
        aLinha[2]              = oLicitacao.licitacao;
        aLinha[3]              = oRow.codigomaterial;
        aLinha[4]              = oRow.material.urlDecode();
        aLinha[5]              = oRow.quantidade;
        aLinha[6]              = js_formatar(oRow.valorunitario, 'f');
        aLinha[7]              = js_formatar(oRow.valortotal, 'f');
        aLinha[8]              = oRow.servico=='t'?'Serviço':'Material';
        aLinha[9]              = "";
        aLinha[10]             = oTxtDataEmissaoInicial.toInnerHtml();
        aLinha[11]             = oTxtDataEmissaoFinal.toInnerHtml();
        aLinha[12]             = oRow.servico;
        aLinha[13]             = oRow.elemento;

        var lMarcado              = false;
        var lDisabled             = false;
        var nValorTotalItem       = new Number(oRow.quantidade) * new Number(oRow.valorunitario);
        var nValorTotalFracionado = 0;

        oGridLicitacaoItens.addRow(aLinha, true, lDisabled, lMarcado);

        iLinhaAtual =  iTotalLinhas;
        iTotalLinhas++;
      });
    }
  });
  oGridLicitacaoItens.renderRows();

  /**
   * Percorremos o array com os codigos para criar um className padrão para todos inputs existentes na Grid
   */
  aCodigos.each(function(oLinha, iSeq) {

    $('oTxtDataEmissaoInicial'+oLinha).setValue(aLicitacoes.dtInicialAcordo);
    $('oTxtDataEmissaoInicial'+oLinha).className = 'classDataEmissaoInicial';

    $('oTxtDataEmissaoFinal'+oLinha).setValue(aLicitacoes.dtFinalAcordo);
    $('oTxtDataEmissaoFinal'+oLinha).className   = 'classDataEmissaoFinal';
  });
}

function js_montaGridLicitacaoItens(){

  oGridLicitacaoItens              = new DBGrid('gridLicitacaoItens');
  oGridLicitacaoItens.nameInstance = 'oGridLicitacaoItens';
  oGridLicitacaoItens.setCheckbox(0);

  oGridLicitacaoItens.setCellAlign(new Array("right",
                                          "right",
                                          "right",
                                          "right",
                                          "left",
                                          'right',
                                          'right',
                                          'right',
                                          "left",
                                          "center",
                                          "center",
                                          "center",
                                          "center"
                                       ));

  oGridLicitacaoItens.setCellWidth(new Array("10%",
                                          '1%',
                                          '1%',
                                          "10%",
                                          "40%",
                                          '10%',
                                          '10%',
                                          '10%',
                                          "10%" ,
                                          "1%",
                                          "20%",
                                          "20%",
                                          "15%",
                                          "15%"
                                        ));

  oGridLicitacaoItens.setHeader(new Array("Código",
                                       '',
                                       'Licitação',
                                       "Cod.Mater",
                                       "Material",
                                       "Quantidade",
                                       "Vlr Un",
                                       "Total",
                                       "Tipo",
                                       "",
                                       "Previsão Inicial",
                                       "Previsão Final",
                                       "Serviço",
                                       "Elemento"
                                     ));

  oGridLicitacaoItens.aHeaders[1].lDisplayed = false;
  oGridLicitacaoItens.aHeaders[2].lDisplayed = false;
  oGridLicitacaoItens.aHeaders[3].lDisplayed = false;
  oGridLicitacaoItens.aHeaders[7].lDisplayed = false;
  oGridLicitacaoItens.aHeaders[9].lDisplayed = false;
  oGridLicitacaoItens.aHeaders[12].lDisplayed = false;
  oGridLicitacaoItens.aHeaders[13].lDisplayed = false;
  oGridLicitacaoItens.show($('ctngridLicitacaoItens'));

}

function js_getItensOrigem() {

  js_montaWindowItensOrigens();

  var oParam         = new Object();
  oParam.exec        = "getItensOrigem";
  js_divCarregando('Aguarde, pesquisando itens do contratante', 'msgBox');
  var oAjax   = new Ajax.Request(
                             sURL,
                             {
                              method    : 'post',
                              parameters: 'json='+Object.toJSON(oParam),
                              onComplete: js_getRetornoGetItens
                              }
                            );
}

function js_getRetornoGetItens(oAjax) {

  js_removeObj('msgBox');

  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
    js_preencheItensOrigem(oRetorno);
  } else  {
    alert(oRetorno.message.urlDecode());
  }
}

oTxtDataEmissao   = new DBTextFieldData('oTxtDataEmissao','oTxtDataEmissao', null);

function js_preencheItensOrigem(oRetorno) {

  oGridItensOrigem.clearAll(true);
  var iTotalLinhas = 0;
  $('btnVincularItens').disabled = false;

  var aCodigos = new Array();
  oRetorno.itens.each(function (oRow, iSeq) {

    var aLinha = new Array();

    aCodigos.push(oRow.codigo);
    oTxtDataEmissaoInicial     = new DBTextFieldData('oTxtDataEmissaoInicial'+oRow.codigo,'oTxtDataEmissao', null);
    oTxtDataEmissaoFinal       = new DBTextFieldData('oTxtDataEmissaoFinal'+oRow.codigo,'oTxtDataEmissao', null);

    var oInputQuantidade   = document.createElement('input');
    oInputQuantidade.type  = 'text';
    oInputQuantidade.id    = 'quantidade_' + oRow.codigo;
    oInputQuantidade.name  = 'quantidade_' + oRow.codigo;
    oInputQuantidade.size  = 5;
    oInputQuantidade.setAttribute('value', oRow.quantidade);
    oInputQuantidade.setAttribute('dado-quantidade-base', oRow.quantidade);

    if ( !oRetorno.lLiberaEdicaoQuantidade ) {
      oInputQuantidade.setAttribute('disabled', 'disabled');
    }

    aLinha[0]   = oRow.codigo;
    aLinha[1]   = iTotalLinhas;
    aLinha[2]   = oRow.codigomaterial;
    aLinha[3]   = oRow.material.urlDecode();
    aLinha[4]   = oInputQuantidade.outerHTML;
    aLinha[5]   = js_formatar(oRow.valorunitario, 'f');
    aLinha[6]   = js_formatar(oRow.valortotal, 'f');
    aLinha[7]   = oRow.servico=='t'?'Serviço':'Material';
    aLinha[8]   = "";
    aLinha[9]  = oTxtDataEmissaoInicial.toInnerHtml();
    aLinha[10]  = oTxtDataEmissaoFinal.toInnerHtml();
    aLinha[11]  = oRow.servico;
    aLinha[12]  = oRow.elemento;

    var lMarcado  = false;
    var lDisabled = false;
    var nValorTotalItem       = new Number(oRow.quantidade) * new Number(oRow.valorunitario);
    var nValorTotalFracionado = 0;

    oGridItensOrigem.addRow(aLinha, false, lDisabled, lMarcado);

    iLinhaAtual =  iTotalLinhas;
    iTotalLinhas++;

  });

  oGridItensOrigem.renderRows();

  for ( var oRow of oRetorno.itens) {

    $('quantidade_' + oRow.codigo).addEventListener('input', function (event) {
      js_ValidaCampos(this, 1, 'Quantidade', 't', 't', event);
    });

    $('quantidade_' + oRow.codigo).addEventListener('change', function () {

      var iQuantidadeMax = new Number(this.getAttribute ('dado-quantidade-base'));
      if (new Number(this.value) > iQuantidadeMax) {

        alert("O campo Quantidade não deve ser maior que: " + iQuantidadeMax);
        this.value = iQuantidadeMax;
        return;
      }

    });
  }

  /**
   * Percorremos o array com os codigos para criar um className padrão para todos inputs existentes na Grid
   */
  aCodigos.each(function(oLinha, iSeq) {

    $('oTxtDataEmissaoInicial'+oLinha).setValue(oRetorno.dtInicialAcordo);
    $('oTxtDataEmissaoInicial'+oLinha).className = 'classDataEmissaoInicial';

    $('oTxtDataEmissaoFinal'+oLinha).setValue(oRetorno.dtFinalAcordo);
    $('oTxtDataEmissaoFinal'+oLinha).className   = 'classDataEmissaoFinal';
  });
}

function js_vincularItens() {

  var aItens = oGridItensOrigem.getSelection("object");
  if (aItens.length == 0) {

    alert('Selecione um Item!');
    return false;
  }

  var aListaCheckbox = oGridItensOrigem.getSelection();
  var aListaItens = new Array();
  var oDadosItens;
  var lErro = false;

  aListaCheckbox.each(function ( aRow ) {

    oDadosItens = new Object();
    oDadosItens.codigo          = aRow[0];
    oDadosItens.codigomaterial  = aRow[3];
    oDadosItens.quantidade      = aRow[5];
    oDadosItens.valorunitario   = aRow[6];
    oDadosItens.valortotal      = aRow[7];
    oDadosItens.iFormaControle  = 1;
    oDadosItens.dtInicial       = aRow[10];
    oDadosItens.dtFinal         = aRow[11];
    oDadosItens.servico         = aRow[12];
    oDadosItens.elemento        = aRow[13];

    if (oDadosItens.dtInicial == null || oDadosItens.dtInicial == '') {

      alert('Preencha as datas iniciais do período de execução.');
      lErro = true;
      throw $break;
    }

    if (oDadosItens.dtFinal == null || oDadosItens.dtFinal == '') {

      alert('Preencha as datas finais do período de execução.');
      lErro = true;
      throw $break;
    }

    aListaItens.push(oDadosItens);
  });

  if (lErro) {
    return false;
  }

  var oParam         = new Object();
  oParam.exec        = "adicionarItensOrigem";
  oParam.itens       = new Array();
  oParam.aLista      = aListaItens;

  aItens.each(function(oRow, id) {
    oParam.itens.push(oRow.aCells[0].getValue());
  });

  js_divCarregando('Aguarde, Vinculando itens selecionados..', 'msgBox');
  var oAjax   = new Ajax.Request(
                             sURL,
                             {
                              method    : 'post',
                              parameters: 'json='+Object.toJSON(oParam),
                              onComplete: js_getRetornoVincularItens
                              }
                            );
}

function js_vincularLicitacaoItens() {

  var aItens = oGridLicitacaoItens.getSelection("object");

  if (aItens.length == 0) {

    alert('Selecione um Item!');
    return false;
  }

  var aListaCheckbox = oGridLicitacaoItens.getSelection();
  var aListaItens = new Array();
  var oDadosItens;
  var lErro = false;

  aListaCheckbox.each(function ( aRow ) {

    oDadosItens = new Object();
    oDadosItens.codigo          = aRow[0];
    oDadosItens.codigomaterial  = aRow[4];
    oDadosItens.quantidade      = aRow[6];
    oDadosItens.valorunitario   = aRow[7];
    oDadosItens.valortotal      = aRow[8];
    oDadosItens.iFormaControle  = 1;
    oDadosItens.dtInicial       = aRow[11];
    oDadosItens.dtFinal         = aRow[12];
    oDadosItens.servico         = aRow[13];
    oDadosItens.elemento        = aRow[14];

    if (oDadosItens.dtInicial == null || oDadosItens.dtInicial == '') {

      alert('Preencha as datas iniciais do período de execução.');
      lErro = true;
      throw $break;
    }

    if (oDadosItens.dtFinal == null || oDadosItens.dtFinal == '') {

      alert('Preencha as datas finais do período de execução.');
      lErro = true;
      throw $break;
    }

    aListaItens.push(oDadosItens);
  });

  if (lErro) {
    return false;
  }

  var oParam         = new Object();
  oParam.exec        = "adicionarLicitacaoItens";
  oParam.itens       = new Array();
  oParam.aLista      = aListaItens;

  aItens.each(function(oRow, id) {
    oParam.itens.push(oRow.aCells[0].getValue());
  });

  js_divCarregando('Aguarde, Vinculando itens selecionados..', 'msgBox');
  var oAjax   = new Ajax.Request(
                             sURL,
                             {
                              method    : 'post',
                              parameters: 'json='+Object.toJSON(oParam),
                              onComplete: js_getRetornoVincularLicitacaoItens
                              }
                            );
}

function js_getRetornoVincularLicitacaoItens(oAjax) {

 js_removeObj('msgBox');
 var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

    windowLicitacaoItens.destroy();
    js_getItens();
  } else  {
    alert(oRetorno.message.urlDecode());
  }
}

function js_getRetornoVincularItens(oAjax) {

 js_removeObj('msgBox');
 var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

    windowItensOrigem.destroy();
    js_getItens();
  } else  {
    alert(oRetorno.message.urlDecode());
  }
}

function js_fracionarItensOrigem(iRow) {

  var oRow    = oGridItensOrigem.aRows[iRow];
  var iHeight = js_round((window.innerHeight/1.5), 0);
  var iWidth  = document.width/2;
  windowItensFraciona = new windowAux('windowItensFraciona',
                                    'Fracionar Itens',
                                    iWidth,
                                    iHeight
                                   );
  var sContent  = "<center><table><tr><td>";
  sContent     += "<fieldset><legend><b>Fracionar Item</b></legend>";
  sContent     += "<table>";
  sContent     += "  <tr>";
  sContent     += "   <td>";
  sContent     += "      <b><a onclick='js_pesquisaMaterial(true);return false' href='#'>Material:</a></b>";
  sContent     += "   </td>";
  sContent     += "   <td id='cntTxtIdMaterial'>";
  sContent     += "   </td>";
  sContent     += "   <td id='cntTxtDescrMaterial'>";
  sContent     += "   </td>";
  sContent     += "  </tr>";
  sContent     += "  <tr>";
  sContent     += "   <td>";
  sContent     += "      <b>Quantidade:</b>";
  sContent     += "   </td>";
  sContent     += "   <td id='cntTxtQuantidade'>";
  sContent     += "   </td>";
  sContent     += "  </tr>";
  sContent     += "  <tr>";
  sContent     += "   <td>";
  sContent     += "      <b>Valor Unitário:</b>";
  sContent     += "   </td>";
  sContent     += "   <td id='cntTxtValorUnitario'>";
  sContent     += "   </td>";
  sContent     += "  </tr>";
  sContent     += "  <tr>";
  sContent     += "   <td>";
  sContent     += "      <b>Valor Total:</b>";
  sContent     += "   </td>";
  sContent     += "   <td id='cntTxtValor'>";
  sContent     += "   </td>";
  sContent     += "  </tr>";
  sContent     += "  <tr>";
  sContent     += "   <td>";
  sContent     += "      <b>Unidade:</b>";
  sContent     += "   </td>";
  sContent     += "   <td id='cntCboUnidade' colspan=2>";
  sContent     += "   </td>";
  sContent     += "  </tr>";
  sContent     += "  <tr>";
  sContent     += "    <td colspan='4'>";
  sContent     += "    <fieldset><legend><b>Observação</b></legend>";
  sContent     += "     <textarea id='oMemoObservacao' rows='3' style='width:100%'></textarea>";
  sContent     += "    </fieldset>";
  sContent     += "    </td>";
  sContent     += "  </tr>";
  sContent     += "</table>";
  sContent     += "</fieldset>";
  sContent     += "</td></tr></table>";
  sContent     += "  <input type='button' value='Salvar' id='btnSalvarItens'>";
  sContent     += "</center>";
  windowItensFraciona.setContent(sContent);
  windowItensFraciona.allowCloseWithEsc(false);
  oTxtMaterial = new DBTextField('oTxtMaterial', 'oTxtMaterial', '', 10);
  oTxtMaterial.show($('cntTxtIdMaterial'));


  $('btnSalvarItens').stopObserving("click");
  $('btnSalvarItens').observe("click", function () {

    js_fracionarItem(iRow);
  });
  oTxtDescrMaterial = new DBTextField('oTxtDescrMaterial', 'oTxtDescrMaterial', '', 40);
  oTxtDescrMaterial.show($('cntTxtDescrMaterial'));


  oTxtQuantidade = new DBTextField('oTxtQuantidade', 'oTxtQuantidade', oRow.aCells[5].getValue(),  10);
  oTxtQuantidade.addEvent("onKeyPress", "return js_mask(event,\"0-9|.\")");
  oTxtQuantidade.addEvent("onBlur", "js_validaValorUnitario()");
  oTxtQuantidade.show($('cntTxtQuantidade'));

  var nValorUnitario = js_strToFloat(oRow.aCells[6].getValue());
  oTxtValorUnitario  = new DBTextField('oTxtValorUnitario', 'oTxtValorUnitario', nValorUnitario,  10);
  oTxtValorUnitario.addEvent("onKeyPress", "return js_mask(event,\"0-9|.\")");
  oTxtValorUnitario.addEvent("onBlur", "js_validaValorTotal()");
  oTxtValorUnitario.show($('cntTxtValorUnitario'));

  oTxtValor = new DBTextField('oTxtValor', 'oTxtValor', js_strToFloat(oRow.aCells[7].getValue()), 10);
  oTxtValor.addEvent("onKeyPress", "return js_mask(event,\"0-9|.\")");
  oTxtValor.addEvent("onBlur", "js_validaValor(this,"+js_strToFloat(oRow.aCells[7].getValue())+")");
  oTxtValor.setReadOnly(true);
  oTxtValor.show($('cntTxtValor'));

  oCboUnidade  = new DBComboBox('oCboUnidade', 'oCboUnidade');
  oCboUnidade.show($('cntCboUnidade'));

  var aUnidades = $('ac20_matunid').options;
  for (var i = 0; i < aUnidades.length; i++) {
    oCboUnidade.addItem(aUnidades[i].value, aUnidades[i].innerHTML);
  };
  windowItensFraciona.setShutDownFunction(function() {
    windowItensFraciona.destroy();
  });
  windowItensFraciona.setChildOf(windowItensOrigem);
  oMessageBoardFraciona = new DBMessageBoard('msgboardItensFraciona',
                                    'Informe os dados do Item',
                                    'Informe o Material, e as novas quantidades para o item '+oRow.aCells[4].getValue(),
                                    $('windowwindowItensFraciona_content')
                                    );
  windowItensFraciona.show();
  oAutoComplete = new dbAutoComplete($('oTxtDescrMaterial'),'com4_pesquisamateriais.RPC.php');
  oAutoComplete.setTxtFieldId(document.getElementById('oTxtMaterial'));
  oAutoComplete.show();
}

function js_fracionarItem(iRow) {

   oRow            = oGridItensOrigem.aRows[iRow];
   var nQuantidade = oTxtQuantidade.getValue();
   var nValor      = oTxtValor.getValue();
   var nValorUnit  = oTxtValorUnitario.getValue();
   var iItem       = oTxtMaterial.getValue();
   var iUnidade    = oCboUnidade.getValue();
   var sObservacao = $F('oMemoObservacao');
   if (new Number(nQuantidade) == 0) {
     alert('Informe uma quantidade valida!');
     return false;
   }
   if (new Number(nValor) == 0) {

     alert('Informe um valor válido!');
     return false;
   }
   if (iItem == "") {

     alert('Indique um item para a inclusão!');
     return false;
   }
   if (iUnidade == "") {

     alert('Informe a unidade do material!');
     return false;
   }

   var oParam  = new Object();
   oParam.exec = 'fracionarItemOrigem';

   oParam.iItemOriginal             = oRow.aCells[1].getValue();
   oParam.fracionamento             = new Object();
   oParam.fracionamento.iItem       = iItem;
   oParam.fracionamento.nValor      = nValor;
   oParam.fracionamento.nQuantidade = nQuantidade;
   oParam.fracionamento.nValorUnit  = nValorUnit;
   oParam.fracionamento.iUnidade    = iUnidade;
   oParam.fracionamento.sObservacao = sObservacao;
   var oAjax   = new Ajax.Request(
                             sURL,
                             {
                              method    : 'post',
                              parameters: 'json='+Object.toJSON(oParam),
                              onComplete: js_retornoFracionarItens
                              }
                            );
}

function js_retornoFracionarItens(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

    if (typeof(windowItensFraciona) != "undefined" ) {

       windowItensFraciona.destroy();
       delete windowItensFraciona;
    }
    js_preencheItensOrigem(oRetorno.itens);

  } else {
    alert(oRetorno.message.urlDecode());
  }
}

function js_validaValor(Obj, valorMaximo) {

   if (Obj.value == "" || Obj.value < 0) {
      Obj.value = valorMaximo;
   } else if (Obj.value > valorMaximo) {
     Obj.value = valorMaximo;
   }
}

function js_validaValorUnitario() {

   if (oTxtQuantidade.getValue() != oRow.aCells[5].getValue()) {

     var nValorUnitario = oTxtValor.getValue()/oTxtQuantidade.getValue();
     oTxtValorUnitario.setValue(nValorUnitario.toFixed(iCasasDecimais));
     js_validaValorTotal();
   }
}

function js_validaValorTotal() {

   var nValorTotal = oTxtValorUnitario.getValue() * oTxtQuantidade.getValue();
   oTxtValor.setValue(nValorTotal.toFixed(2));
}

function js_excluirFracionamento(iItem, iFracionamento) {

  var oParam  = new Object();
  oParam.exec = 'excluirFracionamento';

  oParam.iItem           = iItem;
  oParam.iFracionamento  = iFracionamento;
  var oAjax   = new Ajax.Request(
                             sURL,
                             {
                              method    : 'post',
                              parameters: 'json='+Object.toJSON(oParam),
                              onComplete: js_retornoFracionarItens
                              }
                            );
}

function js_getNumeroCasasDecimais(nValor) {

   var iNumeroCasasDecimais = 0;
   var sString = new String(nValor);
   if (sString.indexOf('.') > 0) {

     var sParteDecimal    = sString.split('\.');
     iNumeroCasasDecimais = sParteDecimal[1].length;
   }
   return iNumeroCasasDecimais;
}

function js_desabilitaItemSelecionar() {

  var iMaterialUnidade = $('ac20_matunid').value;
  if (iMaterialUnidade != 0) {
    $('ac20_matunid').options[0].disabled = true;
  }

  var iMaterialDesdobramento = $('ac20_elemento').value;
  if (iMaterialDesdobramento != 0) {
    $('ac20_elemento').options[0].disabled = true;
  }
}

/**
 * Cria uma grid dos Periodos do Item
 */
oGridPeriodos              = new DBGrid('gridPeriodos');
oGridPeriodos.nameInstance = 'oGridPeriodos';
oGridPeriodos.setCellAlign(new Array("center","center", "center"));
oGridPeriodos.setCellWidth(new Array("35%","35%", "30%"));
oGridPeriodos.setHeader(new Array("Data Inicial", "Data Final", "Ação"));
oGridPeriodos.setHeight(70);
oGridPeriodos.show($('gridPeriodos'));

var aPeriodoItem  = new Array();

/**
 * Renderiza os Periodos
 */
function showPeriodos() {

	oGridPeriodos.clearAll(true);

	if (aPeriodoItem.length > 0) {
		getPeriodos();
	}
}

/**
 * Itera sobre o array de periodos e add as linhas na grid
 */
function getPeriodos() {

  oGridPeriodos.clearAll(true);
  aPeriodoItem.each(function ( oPeriodos, iLinha) {

    var aRow 		 = new Array();
				aRow[0]  = oPeriodos.dtDataInicial;
        aRow[1]  = oPeriodos.dtDataFinal;
				aRow[2]  = "<input type='button' value='Excluir' onclick='js_excluiPeriodo("+iLinha+");'>";
		oGridPeriodos.addRow(aRow);
	});
	oGridPeriodos.renderRows();
}

/**
 * Adiciona um objeto de periodo no array de periodos
 */
function addPeriodo() {

  var oPeriodo   = new Object();
  var oDtInicial = $("ac41_datainicial");
  var oDtFinal   = $("ac41_datafinal");

  if (oDtInicial.value == "" || oDtFinal.value == "") {

    alert("Ambos os Periodos devem serem preenchidos.");
    return false;
  }

  /**
   * Valida se as datas informadas não conflitam com a data de vigência do contrato
   */
  var dtContratoInicio = parent.iframe_acordo.document.form1.ac16_datainicio.value;
  var dtContratoFim    = parent.iframe_acordo.document.form1.ac16_datafim.value;

  if ( js_comparadata(oDtInicial.value, dtContratoInicio, "<") || js_comparadata(oDtFinal.value, dtContratoFim, ">") ||
       js_comparadata(oDtInicial.value, dtContratoFim, ">")    || js_comparadata(oDtFinal.value, dtContratoInicio, "<")) {
    alert("Há conflito entre as datas do item e a data de vigência do contrato.");
    return false;
  }

  if (!js_validaData(oDtInicial.value, oDtFinal.value)) {
    return false;
  }
  oPeriodo.dtDataInicial = oDtInicial.value;
  oPeriodo.dtDataFinal   = oDtFinal.value;
  aPeriodoItem.push(oPeriodo);

  oDtInicial.value = "";
  oDtFinal.value   = "";

  showPeriodos();
}

/**
 * Valida conflitos nos periodos digitados
 */
function js_validaData(sDtInicial, sDtFinal) {

  var lErro = false;
  if (js_comparadata(sDtInicial, sDtFinal, ">")){

    alert("Data inicial não pode ser maior que a data final.");
    lErro = true;
  }

  if (aPeriodoItem.length > 0 && !lErro) {

    for (var i = 0; i < aPeriodoItem.length; i++) {

      if (js_comparaPeriodo(sDtInicial, sDtFinal, aPeriodoItem[i].dtDataInicial, aPeriodoItem[i].dtDataFinal)) {

        var sMsgErro  = "Há conflito entre os períodos informados. Confira:\n";
            sMsgErro += sDtInicial +" - "+sDtFinal +"\n";
            sMsgErro += aPeriodoItem[i].dtDataInicial + " - " + aPeriodoItem[i].dtDataFinal;
        alert(sMsgErro);
        lErro = true;
        break;
      }
    }
  }

  if ( lErro ) {
    return false;
  }
  return true;
}

/**
 * Exclui do array de periodos um periodo especifico
 */
function js_excluiPeriodo(iLinha) {

  aPeriodoItem.splice(iLinha, 1);
  getPeriodos();
}

function js_mostraPeriodos(iCodigoItem) {

  var oParam         = new Object();
  oParam.exec        = "buscaPeriodosItem";
  oParam.iCodigoItem = iCodigoItem;
  js_divCarregando('Aguarde, pesquisando Períodos do Item', 'msgBox');
  var oAjax   = new Ajax.Request(
                            sURL,
                            {
                             method    : 'post',
                             parameters: 'json='+Object.toJSON(oParam),
                             onComplete: js_retornoPeriodoItem
                             }
                           );
}

function js_retornoPeriodoItem(oAjax) {

  js_removeObj('msgBox');
  var oRetorno   = eval("("+oAjax.responseText+")");

  /**
   * Construir Windown AUX
   */
   var windowPeriodos     = new windowAux('janelaPeriodo','Período do Item '+ oRetorno.nomeItem.urlDecode() ,500,350);
   windowPeriodos.setContent("<div id='periodosItem'></div>");
   windowPeriodos.setShutDownFunction(function(){
     windowPeriodos.destroy();
   });
   windowPeriodos.show();

   var oMessageBoard = new DBMessageBoard('msgboard1',
                                          'Períodos do Item ' + oRetorno.nomeItem .urlDecode(),
                                          'Periodos cadastrados para o item.',
                                          windowPeriodos.getContentContainer());
   oMessageBoard.show();

  /**
   * View da
   */
  oGridViewPeriodos              = new DBGrid('gridViewPeriodos');
  oGridViewPeriodos.nameInstance = 'oGridViewPeriodos';
  oGridViewPeriodos.setCellAlign(new Array("center","center"));
  oGridViewPeriodos.setCellWidth(new Array("45%","45%"));
  oGridViewPeriodos.setHeader(new Array("Data Inicial", "Data Final"));
  oGridViewPeriodos.show($('periodosItem'));

  oGridPeriodos.clearAll(true);

  oRetorno.periodos.each(function ( oPeriodos, iLinha) {

    /**
     * Split para configurar as datas no padrão brasileiro
     */
    var aPeriodoInicial = oPeriodos.dtDataInicial.split("-");
    var aPeriodoFinal   = oPeriodos.dtDataFinal.split("-");

    var aRow     = new Array();
        aRow[0]  = aPeriodoInicial[2]+"/"+aPeriodoInicial[1]+"/"+aPeriodoInicial[0];
        aRow[1]  = aPeriodoFinal[2]+"/"+aPeriodoFinal[1]+"/"+aPeriodoFinal[0];
    oGridViewPeriodos.addRow(aRow);
  });
  oGridViewPeriodos.renderRows();
}

function js_windowItensEmpenho() {

  var sTituloWindowAux     = "Configuração de Itens do Empenho";
	    oWindowAuxEmpenho    = new windowAux("oWindowAuxEmpenho", sTituloWindowAux, 950, 500);
	var sConteudoWindow      = "<fieldset>";
	sConteudoWindow     		+= "<legend><b>Itens de Empenhos</b></legend>";
	sConteudoWindow     		+= "<div id='ctnGridItensEmpenho'></div>";
	sConteudoWindow     		+= "</fieldset>";
  sConteudoWindow         += "<center>";
  sConteudoWindow         += "<input";
  sConteudoWindow         += "  type='button'";
  sConteudoWindow         += "  name='btnSalvarItens'";
  sConteudoWindow         += "  id='btnSalvarItens'";
  sConteudoWindow         += "  value='Salvar'";
  sConteudoWindow         += "  onclick='js_salvaVinculoItensEmpenho();'";
  sConteudoWindow         += ">";
  sConteudoWindow         += "</center>";

  oWindowAuxEmpenho.setContent(sConteudoWindow);

  oWindowAuxEmpenho.setShutDownFunction(function() {
    alert("Necessário vincular os itens de Empenho que ainda não possuem vinculo com este contrato.");
  });

  var sTituloMsgBoard     = "";
  var sHelpMsgBoard = "";
  var oMsgBoardEmpenho 		= new DBMessageBoard("oWindowAuxEmpenho",
                                              sTituloMsgBoard,
                                              sHelpMsgBoard,
                                              oWindowAuxEmpenho.getContentContainer()
                                              );
  oMsgBoardEmpenho.show();
	oGridItensEmpenhos              = new DBGrid('ctnGridItensEmpenho');
	oGridItensEmpenhos.nameInstance = 'oGridItensEmpenhos';

	var aHeaders  = new Array("Empenho",
                            "Item",
                            "Código Material",
                            "Descrição",
                            "Data Inicial",
                            "Data Final");

	var aAlign    = new Array("right",
                            "right",
                            "right",
                            "right",
                            "center",
                            "center");

	var aWidth    = new Array("8%",
                             "7%",
                            "10%",
                            "45%",
                            "15%",
                            "15%");


  oGridItensEmpenhos.hasCheckbox = true;
	oGridItensEmpenhos.setCellAlign(aAlign);
	oGridItensEmpenhos.setCellWidth(aWidth);
	oGridItensEmpenhos.setHeader(aHeaders);
  oGridItensEmpenhos.aHeaders[2].lDisplayed = false;
	oGridItensEmpenhos.setHeight(200);
  oWindowAuxEmpenho.hide();
}

/**
 * Função que executa o ajax e preenche a grid na windowAux com os lançamentos encontrados
 */
function js_carregaItensEmpenho() {

  var oParam 				= new Object();
  oParam.exec       = "getItensEmpenhosAindaNaoVinculados";
  oGet							= js_urlToObject();
  oParam.iAcordo    = oGet.ac20_acordo;

	js_divCarregando("Aguarde, buscando itens dos empenhos...", "msgBox");
  var oAjax = new Ajax.Request("ac4_acordoinclusao.rpc.php",
                              {method:'post',
       											  parameters:'json='+Object.toJSON(oParam),
                              onComplete: js_preencheGridItensEmpenhos});
}

//Cria as funções em escopo global, para serem referenciadas em qualquer escopo
var oDBTextFieldData  = new DBTextFieldData("oDBTextFieldData", "oDBTextFieldData","",10);

/**
 * Função que preenche a grid com itens de empenhos
 */
function js_preencheGridItensEmpenhos(oAjax) {


  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");

	if (oRetorno.status == 2) {

	  alert(oRetorno.message.urlDecode());
	  return false;
	}

  /*
   * Caso não exista item a ser inserido no contrato, esconde window
   * Caso exista itens a serem incluidos, mostra window e gera grid
   */
  if (oRetorno.aItensEmpenhos == null) {

    oWindowAuxEmpenho.hide();
    js_mostrarTelaModificacaoItens();
    return false;

  } else if (oRetorno.aItensEmpenhos.length > 0) {

    oWindowAuxEmpenho.show();
    oGridItensEmpenhos.show($('ctnGridItensEmpenho'));
    oGridItensEmpenhos.clearAll(true);


	  //Preenche cada linha da grid, com o item a ser vinculado ao contrato
    oRetorno.aItensEmpenhos.each(function (oItem, iLinha) {

	    var aLinha = new Array();
			aLinha[0]  = oItem.iEmpenho;
      aLinha[1]  = oItem.iEmpenhoItem;
	    aLinha[2]  = oItem.iCodigoMaterial;
			aLinha[3]  = oItem.sDescricao.urlDecode();

      var oDBTextFieldDataInicial = new DBTextFieldData("oDBTextFieldDataInicial" + iLinha, "oDBTextFieldData","",10);
      var oDBTextFieldDataFinal   = new DBTextFieldData("oDBTextFieldDataFinal" + iLinha, "oDBTextFieldData","",10);

      aLinha[4]  = oDBTextFieldDataInicial.toInnerHtml();
	    aLinha[5]  = oDBTextFieldDataFinal.toInnerHtml();

	    oGridItensEmpenhos.addRow(aLinha, true, true, true);
	  });

	  oGridItensEmpenhos.renderRows();
	}
}

/**
 *  Verifica inicialmente o tipo de acordo com a finalidade de, em caso de empenho,
 *  com a finalidade de definir a necessidade de pesquisar itens de empenho ainda não vinculados
 */
function js_verificaTipoAcordo() {

  var oGet           = js_urlToObject();
  var oParam         = new Object();
      oParam.iAcordo = oGet.ac20_acordo;
      oParam.exec    = "verificaTipoAcordo";

  js_divCarregando("Aguarde, verificando Tipo de Acordo...", "msgBox");
  var oAjax = new Ajax.Request("ac4_acordoinclusao.rpc.php",
                              {method:'post',
                              parameters:'json='+Object.toJSON(oParam),
                              onComplete: js_finalizaVerificacaoTipoAcordo});
}

function js_finalizaVerificacaoTipoAcordo(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.status == 2) {

    alert(oRetorno.message.urlDecode());
    return false;
  }

  //Caso Tipo Empenho, verificar os itens ainda não vinculados ao contrato
  if (oRetorno.iTipoAcordo == 6 ) {
    js_carregaItensEmpenho();
  } else {
    //mostra tela, com os outros tipos de origem de contrato
    js_mostrarTelaModificacaoItens();
  }
}

function js_mostrarTelaModificacaoItens() {

  oWindowAuxEmpenho.hide();
  $('db_opcao').onclick = js_saveItem;
  js_init();
  js_desabilitaItemSelecionar();
}

$('db_opcao').onclick = js_saveItem;

function js_salvaVinculoItensEmpenho() {

  var oGet           = js_urlToObject();
  var aItensEmpenhos = oGridItensEmpenhos.getSelection();
  var aItensContrato = new Array();

  aItensEmpenhos.each(function(oItem, iIndice) {

    var aNovoItem             = new Object();
    aNovoItem.iEmpenho        = oItem[1];
    aNovoItem.iEmpenhoItem    = oItem[2];
    aNovoItem.iCodigoMaterial = oItem[3];
    aNovoItem.dtInicial       = oItem[5];
    aNovoItem.dtFinal         = oItem[6];
    aNovoItem.iTipoControle   = 1;
    aItensContrato.push(aNovoItem);
  });

  var oParam      = new Object();
  oParam.aItens   = aItensContrato;
  oParam.iAcordo  = oGet.ac20_acordo;
  oParam.exec     = "salvaVinculoItensEmpenho";

  js_divCarregando("Aguarde, salvando vinculos com os itens configurados...", "msgBox");
  var oAjax = new Ajax.Request("ac4_acordoinclusao.rpc.php",
                              {method:'post',
                              parameters:'json='+Object.toJSON(oParam),
                              onComplete: js_finalizaVinculoItensEmpenho});
}

function js_finalizaVinculoItensEmpenho(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.iStatus == 2) {

    alert(oRetorno.sMessage.urlDecode());
    return false;
  }
  js_mostrarTelaModificacaoItens();
}

js_showGrid();
js_windowItensEmpenho();
js_verificaTipoAcordo();

</script>
<?php
  if ($oDaoAcordo->numrows > 0) {

    echo "<script>js_bloqueiaCampos (".$oAcordo->ac16_origem.");</script>";
  }
?>
