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

//MODULO: pessoal

$clpontofx->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("rh27_limdat");
$clrotulo->label("rh27_descr");
$clrotulo->label("rh27_form");
$clrotulo->label("r29_tpp");
$clrotulo->label("r70_descr");
$clrotulo->label("DBtxt23");
$clrotulo->label("DBtxt25");
$clrotulo->label("rh27_valorlimite");
$clrotulo->label("rh27_quantidadelimite");
$clrotulo->label("rh27_tipobloqueio");

if($ponto == "fx"){
  $sDescrPonto = " Ponto Fixo";
}else if($ponto == "fs"){
  $sDescrPonto = " Ponto de Salário";
}


?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td>
      <fieldset>
        <legend>
          <b><?=$sDescrPonto?></b>
        </legend>
        <table align="center">
          <tr>
            <td align="right" nowrap title="Digite o Ano / Mes de competência" >
              <strong>Ano / Mês :</strong>
            </td>
            <td>
              <?
                db_input('DBtxt23', 4, $IDBtxt23, true, 'text', 3, "onchange='js_submita();'", 'r90_anousu');
              ?>
              &nbsp;/&nbsp;
              <?
                db_input('DBtxt25'         , 2, $IDBtxt25, true, 'text', 3, "onchange='js_submita();'", 'r90_mesusu');
                db_input('ponto'           , 15, 0, true, 'hidden', 3, "");
                db_input('data_de_admissao', 15, 0, true, 'hidden', 3, "");
                db_input('rh27_form'       , 15, $Irh27_form, true, 'hidden', 3, "");

                $iPonto = 1;
                if ( $ponto == 'fx' ) {
                  echo "<input name='rubricasautomaticas' type='button' value='Rubricas Automáticas' onclick='js_rubricasAutomaticas();'>&nbsp;";
                  $iPonto = 10;
                }
              ?>

              <input type='button' name='calcular' id='calcular' value='Calcular' onclick='js_calcular(r90_regist.value, <?=$iPonto?>)'>
              <input type='button' id='consultar' value='Consultar' name='consultar' onclick="js_consultar(r90_regist.value)" />

            </td>
          </tr>
          <tr>
            <td align="right" nowrap title="<? echo (isset($Tr90_regist) && !empty($Tr90_regist)) ? $Tr90_regist : '' ?>">
              <? echo (isset($Lr90_regist) && !empty($Lr90_regist)) ? $Lr90_regist : '' ?>
            </td>
            <td>
              <?
                db_input('r90_regist', 8, $Ir90_regist, true, 'text',3, " onchange='js_pesquisar90_regist(false);' tabIndex=1 ");
                db_input('z01_nome', 60, $Iz01_nome, true, 'text', 3, '');
              ?>
            </td>
          </tr>
          <tr>
            <td align="right" nowrap title="<? echo (isset($Tr90_lotac) && !empty($Tr90_lotac)) ? $Tr90_lotac : '' ?>">
              <?
                db_ancora(@$Lr90_lotac, "js_pesquisar90_lotac(true);", 3);
              ?>
            </td>
            <td>
              <?
                db_input('r90_lotac', 8, $Ir90_lotac, true, 'text', 3, " onchange='js_pesquisar90_lotac(false);'");
                db_input('r70_descr', 60, $Ir70_descr, true, 'text', 3, '');
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2">
      <fieldset>
      <table border="0">
        <tr>
          <td id="ancoraEnable" align="left" nowrap title="<? echo (isset($Tr90_rubric) && !empty($Tr90_rubric)) ? $Tr90_rubric : '' ?>">
            <?
              db_ancora(@$Lr90_rubric, "js_pesquisar90_rubric(true);", (($db_opcao==1)?"1":"3"));
            ?>
          </td>
          <td id="ancoraDisable" align="left" nowrap title="<? echo (isset($Tr90_rubric) && !empty($Tr90_rubric)) ? $Tr90_rubric : '' ?>" style="display:none">
            <? echo (isset($Lr90_rubric) && !empty($Lr90_rubric)) ? $Lr90_rubric : '' ?>
          </td>
          <td align='left' nowrap title="<? echo (isset($Tr90_datlim) && !empty($Tr90_datlim)) ? $Tr90_datlim : '' ?>">
             <?=$Lr90_datlim?>
          </td>
          <td align="left" nowrap title="<? echo (isset($Tr90_quant) && !empty($Tr90_quant)) ? $Tr90_quant : '' ?>">
            <? echo (isset($Lr90_quant) && !empty($Lr90_quant)) ? $Lr90_quant : '' ?>
          </td>
          <td align="left" nowrap title="<? echo (isset($Tr90_valor) && !empty($Tr90_valor)) ? $Tr90_valor : '' ?>">
            <? echo (isset($Lr90_valor) && !empty($Lr90_valor)) ? $Lr90_valor : '' ?>
          </td>
        </tr>
        <tr>
          <td>
            <?
               db_input('r90_rubric', 8, $Ir90_rubric, true, 'text', (($db_opcao==1)?"1":"3"), " onchange='js_pesquisar90_rubric(false);' tabIndex=2 ");
               db_input('rh27_descr', 30, $Irh27_descr, true, 'text', 3, '');
            ?>
          </td>
          <td>
             <?
               db_input('r90_datlim', 15, $Ir90_datlim, true, 'text', 3, "onChange='js_calculaQuant(this.value);' onKeyUp='js_mascaradata(this.value);' tabIndex=3 ");
               db_input('rh27_limdat',15, '', true, 'hidden', 3, "");
               db_input('rh27_tipo'  ,15,'', true, 'hidden', 3, "");
             ?>
          </td>
          <td>
            <?
              if(!isset($r90_quant) || (isset($r90_quant) && trim($r90_quant)=="")){
                $r90_quant = '0';
              }
              db_input('r90_quant', 15, $Ir90_quant, true, 'text', $db_opcao, "onchange='js_calculaDataLimit();' tabIndex=4 ");
              db_input('rh27_presta',10,'',true,'hidden',3);

            ?>
          </td>
          <td>
            <?
              if(!isset($r90_valor) || (isset($r90_valor) && trim($r90_valor)=="")){
                $r90_valor = '0';
              }

              db_input('r90_valor', 15, $Ir90_valor, true, 'text', $db_opcao, " tabIndex=5");
              db_input('rh27_quantidadelimite', 15, $Irh27_quantidadelimite, true, 'hidden', 3);
              db_input('rh27_valorlimite', 15, $Irh27_valorlimite, true, 'hidden', 3);
              db_input('rh27_tipobloqueio', 15, $Irh27_tipobloqueio, true, 'hidden', 3);
            ?>
          </td>
        </tr>
      </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center" height="5%">
      <br>
        <input name="acao" type="button"  id="acao" value="Incluir" onClick="js_verificaAcao(this.value);">
        <input name="novo" type="button"  id="novo" value="Novo"    onclick="js_novo();" disabled>
      <br>
    </td>
  </tr>
  <tr>
    <td>
      <fieldset>
        <legend align="center">
          <b>Lista Rubricas:</b>
        </legend>
        <table cellspacing="0" style="border:2px inset white;" >
          <tr id='cabListaRubricas'>
              <th class="table_header" width="100px;">Rubrica</th>
              <th class="table_header" width="170px;">Descrição</th>
              <th class="table_header" width="100px;">Ano/Mês</th>
              <th class="table_header" width="100px;">Quantidade</th>
              <th class="table_header" width="100px;">Valor</th>
              <th class="table_header" width="50px;">&nbsp;</th>
              <th class="table_header" width="50px;">&nbsp;</th>
              <th class="table_header" width="15px;">&nbsp;</th>
          </tr>
          <tbody id="listaRubricas" style=" height:200px; overflow:scroll; overflow-x:hidden; background-color:white"  >
          </tbody>
        </table>
      </fieldset>
    </td>
  <tr>
    <td colspan="2" width="100%" valign="top" align="center" id="caixa_de_texto" height="15%" valign="top">
    </td>
  </tr>
 </table>
  </center>
</form>
<script>

var MENSAGENS_VALIDA_LIMITE_RUBRICA = "recursoshumanos.pessoal.pes4_valida_limite_rubrica.";

var sUrl       = 'pes1_rhpessoalpontoRPC.php';
var sTipoPonto = $F('ponto');

/**
 * Variavel global para armazenar o status da rubrica
 * - true: pode ser inserida no ponto.
 * - false: não pode ser inserida no ponto.
 *
 * @var boolean lTestarRegraPonto
 * @access public
 */
var lTestarRegraPonto;

/**
 * Realiza uma consulta no RPC para cada vez que uma Rubrica é adicionada,
 * para verificar se a mesma possui alguma regra de lançamento.
 * - lTestarRegraPonto recebe true quando a rubrica pode ser adicionada e false quando não pode ser adicionada
 *
 * @access public
 * @return boolean lTestarRegraPonto.
 */
function js_testarRegraPonto() {

  var aRubricas  = [$F('r90_rubric')];
  var sTabela    = "<?=$ponto?>";
  var iMatricula = $F('r90_regist');

  var sUrlRPC    = 'pes1_rhrubricas.RPC.php';

  var oParametros = Object();
      oParametros.sExecucao  = 'testarRegistroPonto';
      oParametros.aRubricas  = aRubricas;
      oParametros.sTipoPonto = sTabela;
      oParametros.iMatricula = iMatricula;

  var oAjax  = new Ajax.Request(sUrlRPC,
                                {
                                method: 'post',
                                asynchronous: false,
                                parameters : 'json=' + Object.toJSON(oParametros),
                                onComplete: js_retornoTestarRegraPonto
                                }
                               );

  return lTestarRegraPonto;
}

/**
 * Trata o retorno da função js_testarRegraPonto
 * - se for somente aviso, exibe um alert com a mensagem solicitando se deseja adicionar a rubrica ou não
 * - se for bloqueio não permite adicionar a rubrica
 * - lTestarRegraPonto recebe true quando a rubrica pode ser adicionada e false quando não pode ser adicionada ao ponto
 *
 * @param object oRetorno.
 * @access public
 */
function js_retornoTestarRegraPonto(oRetorno) {

  lTestarRegraPonto = true;

  var oRetorno = eval("("+oRetorno.responseText+")");
  var sMensagem = oRetorno.sMensagem.urlDecode().replace(/\\n/g, "\n");

  /**
   * Erro no RPC
   */
  if ( oRetorno.iStatus > 1 ) {

    alert(sMensagem);
    return false;
  }

  /**
   * Se haver uma mensagem de bloqueio, exibe a mensagem para o usuario a mensgem e lTestarRegraPonto
   * recebe o valor false, para a rubrica não ser adicionada ao ponto
   */
  if ( oRetorno.sMensagensBloqueio != '' ) {

    lTestarRegraPonto = false;
    alert( oRetorno.sMensagensBloqueio.urlDecode().replace(/\n/g, "\n") );
    return false;
  }

  /**
   * Se haver uma mensagem de aviso, exibe a mensagem para o usuario perguntando
   * se a rubrica deve ser adicionada ao ponto ou não.
   * - lTestarRegraPonto recebe false se o usuario clicar em cancelar
   */
  if ( oRetorno.sMensagensAviso != '' ) {

    lConfirmarAviso = confirm( oRetorno.sMensagensAviso.urlDecode().replace(/\n/g, "\n") );

    /**
     * Clicou em cancelar
     * - Nao permite adicionar ao ponto
     */
    if ( !lConfirmarAviso ) {
      lTestarRegraPonto = false;
    }
  }
}

function js_novo(){
  js_limpaCampos();
  $('acao').value = 'Incluir';
}

function js_verificaAcao(sAcao){

  if (sAcao == 'Incluir' || sAcao == 'Alterar') {

    if (!js_validaLimiteRubrica() || !js_testarRegraPonto()) {
      return false;
    }
  }

  if( sAcao == 'Incluir' ){
    js_validaRubrica();
  } else if ( sAcao == 'Alterar' ) {
    js_alterarRubrica(false);
  } else if ( sAcao == 'Excluir' ){
    js_excluirRubrica();
  }

}

function js_getDadosTela(){

  var oDadosRubrica = new Object();

  oDadosRubrica.r90_anousu = $F('r90_anousu');
  oDadosRubrica.r90_mesusu = $F('r90_mesusu');
  oDadosRubrica.r90_regist = $F('r90_regist');
  oDadosRubrica.r90_rubric = $F('r90_rubric');
  oDadosRubrica.r90_valor  = $F('r90_valor');
  oDadosRubrica.r90_quant  = $F('r90_quant');
  oDadosRubrica.r90_lotac  = $F('r90_lotac');
  oDadosRubrica.r90_datlim = $F('r90_datlim');

  return oDadosRubrica;

}

function js_consultaRubricas(){

  js_divCarregando('Consultando Rubricas...','msgBox');

  var sQuery  = 'sMethod=consultaRubricas';
      sQuery += '&sTipoPonto='+sTipoPonto;
      sQuery += '&iMatric='+$F('r90_regist');
      sQuery += '&iAnoUsu='+$F('r90_anousu');
      sQuery += '&iMesUsu='+$F('r90_mesusu');
  var oAjax   = new Ajax.Request( sUrl, {
                                          method: 'post',
                                          parameters: sQuery,
                                          onComplete: js_retornoConsultaRubricas
                                        }
                                );

}


function js_retornoConsultaRubricas(oAjax){

  js_removeObj("msgBox");

  var aRetorno = eval("("+oAjax.responseText+")");
  var sExpReg  = new RegExp('\\\\n','g');


  if ( aRetorno.lErro ) {
    alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
    return false;
  }

  js_carregaGridRubricas(aRetorno.aRubricas)

}


function js_carregaGridRubricas(aRubricas){

  var iLinhasRubricas  = aRubricas.length;

  if ( iLinhasRubricas == 0 ) {
    var sLinhaGrid  = '<tr>';
        sLinhaGrid += '  <td colspan="8" class="linhagrid" width="700px;" >Nenhum registro encontrado!</td>';
        sLinhaGrid += '<tr>';
        sLinhaGrid += '<tr>';
        sLinhaGrid += '  <td style="height:100%">&nbsp;</td>';
        sLinhaGrid += '<tr>';
    $('listaRubricas').innerHTML = sLinhaGrid;
    return false;
  }

  var sLinhasRubricas  = '';

  for ( var iInd=0; iInd < iLinhasRubricas; iInd++ ) {

    sLinhasRubricas += '<tr id="linha'+aRubricas[iInd].r90_rubric.urlDecode()+'">';
    sLinhasRubricas += '  <td style="text-align:center" class="linhagrid" nowrap>'+aRubricas[iInd].r90_rubric.urlDecode()+'</td>';
    sLinhasRubricas += '  <td style="text-align:left"   class="linhagrid" nowrap>'+aRubricas[iInd].rh27_descr.urlDecode()+'&nbsp;</td>';
    sLinhasRubricas += '  <td style="text-align:center" class="linhagrid" nowrap>'+aRubricas[iInd].r90_datlim.urlDecode()+'&nbsp;</td>';
    sLinhasRubricas += '  <td style="text-align:center" class="linhagrid" nowrap>'+aRubricas[iInd].r90_quant.urlDecode()+'&nbsp;</td>';
    sLinhasRubricas += '  <td style="text-align:right"  class="linhagrid" nowrap>'+js_formatar(aRubricas[iInd].r90_valor.urlDecode(),'f')+'&nbsp;</td>';
    sLinhasRubricas += '  <td class="linhagrid">';
    sLinhasRubricas += '    <input type="button" name="alterar" value="Alterar" onClick="js_telaAlterarExcluir(\'linha'+aRubricas[iInd].r90_rubric.urlDecode()+'\',\'Alterar\')">';
    sLinhasRubricas += '  </td>';
    sLinhasRubricas += '  <td class="linhagrid">';
    sLinhasRubricas += '    <input type="button" name="excluir" value="Excluir" onClick="js_telaAlterarExcluir(\'linha'+aRubricas[iInd].r90_rubric.urlDecode()+'\',\'Excluir\')">';
    sLinhasRubricas += "    <input type='hidden' id='obj"+aRubricas[iInd].r90_rubric.urlDecode()+"'  value='"+aRubricas[iInd].toSource()+"'";
    sLinhasRubricas += '  </td>';
    sLinhasRubricas += '</tr>';

  }

  sLinhasRubricas += '<tr>';
  sLinhasRubricas += '  <td style="height:100%">&nbsp;</td>';
  sLinhasRubricas += '<tr>';

  $('listaRubricas').innerHTML = sLinhasRubricas;

}

function js_telaAlterarExcluir(sId,sAcao){

  var doc          = document.form1;
  var sIdObj       = sId.replace('linha','obj');
  var oDadosRubric = eval($(sIdObj).value);

  doc.r90_rubric.value    = oDadosRubric.r90_rubric;
  doc.rh27_descr.value    = oDadosRubric.rh27_descr;
  doc.rh27_limdat.value   = oDadosRubric.rh27_limdat;
  doc.rh27_tipo.value     = oDadosRubric.rh27_tipo;
  doc.r90_datlim.value    = oDadosRubric.r90_datlim;
  doc.r90_quant.value     = oDadosRubric.r90_quant;
  doc.r90_valor.value     = oDadosRubric.r90_valor;
  doc.rh27_quantidadelimite.value = oDadosRubric.rh27_quantidadelimite
  doc.rh27_valorlimite.value      = oDadosRubric.rh27_valorlimite
  doc.rh27_tipobloqueio.value     = oDadosRubric.rh27_tipobloqueio;

  doc.r90_rubric.disabled = true;
  doc.novo.disabled       = false;

  $('ancoraDisable').style.display = '';
  $('ancoraEnable').style.display = 'none';


  if ( sAcao == 'Excluir') {
    doc.r90_quant.disabled  = true;
    doc.r90_valor.disabled  = true;
    doc.r90_datlim.disabled = true;
  }

  $(sId).style.display    = 'none';
  doc.acao.value          = sAcao;

  $$("input[name='alterar'],input[name='excluir']").each(function(elem){elem.disabled = true});

  if ( $F('rh27_limdat') == 't') {
    js_desabilita(false);
  } else {
    js_desabilita(true);
  }

}

function js_validaRubrica(){

  if ( js_verificaCampos() ) {

    var oDadosRubrica = js_getDadosTela();

    js_divCarregando('Validando Rubricas...','msgBox');

    var sQuery  = 'sMethod=validarRubricas';
        sQuery += '&sTipoPonto='+sTipoPonto;
        sQuery += '&oDadosRubrica='+oDadosRubrica.toSource();
    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post',
                                            parameters: sQuery,
                                            onComplete: js_retornoValidaInclusao
                                          }
                                  );

  }

}

function js_retornoValidaInclusao(oAjax){

  js_removeObj("msgBox");

  var aRetorno = eval("("+oAjax.responseText+")");
  var sExpReg  = new RegExp('\\\\n','g');


  if ( aRetorno.lErro ) {
    alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
    return false;
  } else {

    if ( aRetorno.lExiste ) {

      var sMsgConfirm  = "Rubrica "+$F('r90_rubric')+" "+$F('rh27_descr')+" já cadastrada para a matrícula ";
          sMsgConfirm += $F('r90_regist')+" "+$F('z01_nome')+".\n\nSomar com valor e quantidade informados?";
          sMsgConfirm += "\n\nOK para somar e CANCELAR para substituir valores.";

      if ( confirm(sMsgConfirm) ){
        js_alterarRubrica(true);
      } else {
        js_alterarRubrica(false);
      }
    } else {
      js_incluirRubricas(false);
    }

  }

}


function js_incluirRubricas(){

  if ( js_verificaCampos() ) {

    var oDadosRubrica = js_getDadosTela();

    js_divCarregando('Incluindo Rubricas...','msgBox');

    var sQuery  = 'sMethod=incluirRubricas';
        sQuery += '&sTipoPonto='+sTipoPonto;
        sQuery += '&oDadosRubrica='+oDadosRubrica.toSource();
    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post',
                                            parameters: sQuery,
                                            onComplete: js_retornoInclusaoRubricas
                                          }
                                  );
  }

}



function js_retornoInclusaoRubricas(oAjax){

  js_removeObj("msgBox");

  var aRetorno = eval("("+oAjax.responseText+")");
  var sExpReg  = new RegExp('\\\\n','g');


  if ( aRetorno.lErro ) {
    alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
    return false;
  } else {

    alert('Inclusão feita com sucesso!');
    js_validaRepasse(true,false);

  }
}


function js_repassarValores(){

  if ( js_verificaCampos() ) {

    var oDadosPonto = js_getDadosTela();

    js_divCarregando('Repassando Valores...','msgBox');

    var sQuery  = 'sMethod=repassarValores';
        sQuery += '&sTipoPonto='+sTipoPonto;
        sQuery += '&oDadosRubrica='+oDadosPonto.toSource();
        sQuery += '&dtDataAdm='+document.form1.data_de_admissao.value;
    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post',
                                            parameters: sQuery,
                                            onComplete: js_retornoRepasseValores
                                          }
                                  );
  }

}

function js_retornoRepasseValores(oAjax){

  js_removeObj("msgBox");

  var aRetorno = eval("("+oAjax.responseText+")");
  var sExpReg  = new RegExp('\\\\n','g');


  if ( aRetorno.lErro ) {
    alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
    return false;
  } else {

    alert('Repasse concluído com sucesso!');

    if ( sTipoPonto == 'fx') {
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_rhpontosalario.js_consultaRubricas();
    } else if ( sTipoPonto == 'fs') {
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_rhpontofixo.js_consultaRubricas();
    }

    js_limpaCampos();
  }

}


function js_limpaCampos(){

  var doc = document.form1;

  doc.r90_rubric.value    = '';
  doc.r90_valor.value     = '';
  doc.rh27_descr.value    = '';
  doc.r90_quant.value     = '';
  doc.r90_datlim.value    = '';
  doc.rh27_limdat.value   = '';
  doc.rh27_tipo.value     = '';
  
  doc.rh27_quantidadelimite.value = '';
  doc.rh27_valorlimite.value      = '';
  doc.rh27_tipobloqueio.value     = '';

  doc.r90_rubric.disabled = false;
  doc.r90_quant.disabled  = false;
  doc.r90_valor.disabled  = false;
  doc.r90_datlim.disabled = false;

  doc.novo.disabled       = true;

  $('ancoraDisable').style.display = 'none';
  $('ancoraEnable').style.display = '';

  $$("input[name='alterar'],input[name='excluir']").each(function(elem){elem.disabled = false});
  doc.acao.value = 'Incluir';

  js_consultaRubricas();
  js_desabilita(true);
}


function js_alterarRubrica(lSoma){

  if ( js_verificaCampos() ) {

    var oDadosRubrica = js_getDadosTela();

    js_divCarregando('Alterando Rubrica...','msgBox');

    var sQuery  = 'sMethod=alterarRubricas';
        sQuery += '&sTipoPonto='+sTipoPonto;
        sQuery += '&oDadosRubrica='+oDadosRubrica.toSource();
        sQuery += '&lSoma='+lSoma;
    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post',
                                            parameters: sQuery,
                                            onComplete: js_retornoAlteracaoRubricas
                                          }
                                  );
  }

}

function js_retornoAlteracaoRubricas(oAjax){

  js_removeObj("msgBox");

  var aRetorno = eval("("+oAjax.responseText+")");
  var sExpReg  = new RegExp('\\\\n','g');


  if ( aRetorno.lErro ) {
    alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
    return false;
  } else {

    alert('Alteração feita com sucesso!');
    js_validaRepasse(aRetorno.lExisteValRepasse,false);

  }
}

function js_validaRepasse(lRepasse,lExclusao){


  if ( (sTipoPonto == 'fx' || sTipoPonto == 'fs') && lRepasse ) {

    if ( sTipoPonto == 'fx' ) {
      var sDescrTipoPonto = 'Ponto de Salário';
    } else {
      var sDescrTipoPonto = 'Ponto Fixo';
    }

    if (lExclusao) {
        var sMsgConfirm = 'Deseja repassar exclusão para '+sDescrTipoPonto+' ?';
    } else {
        var sMsgConfirm = 'Deseja repassar para '+sDescrTipoPonto+' ?';
    }

    if ( sTipoPonto == 'fx' ) {

      if ( confirm(sMsgConfirm)) {
        if ( lExclusao ) {
          js_repassarExclusao();
        } else {
          js_repassarValores();
        }
      } else {
        js_limpaCampos();
      }

    } else {

      if ( $F('rh27_limdat') == 't' ||  $F('rh27_tipo') == 1 ) {

        if ( confirm(sMsgConfirm)) {
          if ( lExclusao ) {
            js_repassarExclusao();
          } else {
            js_repassarValores();
          }
        } else {
          js_limpaCampos();
        }
      } else {
        js_limpaCampos();
      }
    }
  } else {
    js_limpaCampos();
  }

}

function js_excluirRubrica(sRubric){

    var oDadosRubrica = js_getDadosTela();

    js_divCarregando('Excluindo Rubrica...','msgBox');

    var sQuery  = 'sMethod=excluirRubricas';
        sQuery += '&sTipoPonto='+sTipoPonto;
        sQuery += '&oDadosRubrica='+oDadosRubrica.toSource();
    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post',
                                            parameters: sQuery,
                                            onComplete: js_retornoExclusaoRubricas
                                          }
                                  );
}

function js_retornoExclusaoRubricas(oAjax){

  js_removeObj("msgBox");

  var aRetorno = eval("("+oAjax.responseText+")");
  var sExpReg  = new RegExp('\\\\n','g');


  if ( aRetorno.lErro ) {
    alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
    return false;
  } else {
    alert('Exclusão feita com sucesso!');
    js_validaRepasse(aRetorno.lExisteValRepasse,true);
  }

}


function js_repassarExclusao(){

    var oDadosRubrica = js_getDadosTela();

    if ( sTipoPonto == 'fx' ) {
      var sValorTipoPonto = 'fs';
    } else if ( sTipoPonto == 'fs' ) {
      var sValorTipoPonto = 'fx';
    } else {
      alert('Tipo de Ponto Inválido!')
      return false;
    }

    js_divCarregando('Excluindo Rubrica...','msgBox');

    var sQuery  = 'sMethod=excluirRubricas';
        sQuery += '&sTipoPonto='+sValorTipoPonto;
        sQuery += '&oDadosRubrica='+oDadosRubrica.toSource();
    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post',
                                            parameters: sQuery,
                                            onComplete: js_retornoRepasseExclusao
                                          }
                                  );
}


function js_retornoRepasseExclusao(oAjax){

  js_removeObj("msgBox");

  var aRetorno = eval("("+oAjax.responseText+")");
  var sExpReg  = new RegExp('\\\\n','g');

  if ( aRetorno.lErro ) {
    alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
    return false;
  } else {

    alert('Repasse feito com sucesso!');

    if ( sTipoPonto == 'fx') {
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_rhpontosalario.js_consultaRubricas();
    } else if ( sTipoPonto == 'fs') {
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_rhpontofixo.js_consultaRubricas();
    }

    js_limpaCampos();
  }

}


function js_rubricasAutomaticas(){

  js_divCarregando('Gerando Rubricas Automáticas...','msgBox');

    var sQuery  = 'sMethod=rubricasAutomaticas';
        sQuery += '&sTipoPonto='+sTipoPonto;
        sQuery += '&iMatric='+$F('r90_regist');
        sQuery += '&iAnoUsu='+$F('r90_anousu');
        sQuery += '&iMesUsu='+$F('r90_mesusu');
    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post',
                                            parameters: sQuery,
                                            onComplete: js_retornoRubricasAutomaticas
                                          }
                                  );

}

function js_retornoRubricasAutomaticas(oAjax){

  js_removeObj("msgBox");

  var aRetorno = eval("("+oAjax.responseText+")");
  var sExpReg  = new RegExp('\\\\n','g');

  if ( aRetorno.lErro ) {

    alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
    return false;

  } else {

    var iLinhasAutomaticas = aRetorno.aRubricas.length;
    var iLinhasCadastradas = aRetorno.aRubricasCadastradas.length;

    var aListaRubricas = new Array();

    for ( var iIndX=0; iIndX < iLinhasAutomaticas; iIndX++ ) {

      aListaRubricas.push(aRetorno.aRubricas[iIndX]);

      for ( var iIndY=0; iIndY < iLinhasCadastradas; iIndY++ ) {
        if ( aRetorno.aRubricas[iIndX].r73_rubric == aRetorno.aRubricasCadastradas[iIndY].r90_rubric ) {
          if(!confirm("Rubrica "+aRetorno.aRubricas[iIndX].r73_rubric+" já cadastrada,'Cancelar' para manter ou 'OK' para substituir.") ) {
            aListaRubricas.reverse().shift();
          }
        }
      }

    }

    if ( sTipoPonto == 'fx' ) {
      var sDescrTipoPonto = 'Ponto de Salário';
    } else {
      var sDescrTipoPonto = 'Ponto Fixo';
    }

    if ( aListaRubricas.length > 0 ) {
      if ( confirm('Deseja repassar para '+sDescrTipoPonto+' ?')) {
        js_incluirRubricasAutomaticas(aListaRubricas,true);
      } else {
        js_incluirRubricasAutomaticas(aListaRubricas,false);
      }
    }

  }
}

function js_incluirRubricasAutomaticas(aListaRubricas,lRepasse){

  js_divCarregando('Incluindo Rubricas Automáticas...','msgBox');

    var sQuery  = 'sMethod=incluirRubricasAutomaticas';
        sQuery += '&sTipoPonto='+sTipoPonto;
        sQuery += '&iMatric='+$F('r90_regist');
        sQuery += '&iAnoUsu='+$F('r90_anousu');
        sQuery += '&iMesUsu='+$F('r90_mesusu');
        sQuery += '&iLotac='+$F('r90_lotac');
        sQuery += '&lRepasse='+lRepasse;
        sQuery += '&dtDataAdm='+document.form1.data_de_admissao.value;
        sQuery += '&aObjListaRubricas='+aListaRubricas.toSource();

    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post',
                                            parameters: sQuery,
                                            onComplete: js_retornoInclusaoRubricasAutomaticas
                                          }
                                  );

}

function js_retornoInclusaoRubricasAutomaticas(oAjax){

  js_removeObj("msgBox");

  var aRetorno = eval("("+oAjax.responseText+")");
  var sExpReg  = new RegExp('\\\\n','g');

  if ( aRetorno.lErro ) {
    alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
    return false;
  } else {

    alert('Rubricas automáticas incluídas com sucesso!');

    if ( sTipoPonto == 'fx') {
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_rhpontosalario.js_consultaRubricas();
    } else if ( sTipoPonto == 'fs') {
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_rhpontofixo.js_consultaRubricas();
    }

    js_limpaCampos();
  }

}

function js_calculaDataLimit(){

  var doc       = document.form1;
  var iQuant    = new Number(doc.r90_quant.value);

  if ( doc.rh27_presta.value == 't' && doc.rh27_limdat.value == 't' ) {

    var iMesAtu   = new Number(doc.r90_mesusu.value);
    var iAnoLimit = new Number(doc.r90_anousu.value);
    var iMesLimit = iMesAtu + (iQuant-1);

    while ( iMesLimit > 12  ) {
      iMesLimit -= 12;
      iAnoLimit++;
    }

    if ( iMesLimit.toString().length < 2 ) {
      iMesLimit = '0'+iMesLimit;
    }

    doc.r90_datlim.value = iAnoLimit+'/'+iMesLimit;

  }


  doc.r90_valor.select();
  doc.r90_valor.focus();

}


function js_calculaQuant(sDataLimit){

  var doc        = document.form1;
  var aDataLimit = sDataLimit.split('/');
  var iAnoLimit  = new Number(aDataLimit[0]);
  var iMesLimit  = new Number(aDataLimit[1]);
  var iAnoAtu    = new Number(doc.r90_anousu.value);
  var iMesAtu    = new Number(doc.r90_mesusu.value);

  if ( doc.rh27_presta.value == 't' && doc.rh27_limdat.value == 't' ) {

    var iQuant     = new Number(0);

    if ( iAnoLimit > iAnoAtu ) {

      while ( iAnoLimit > (iAnoAtu+1)  ) {
        iQuant += 12;
        --iAnoLimit;
      }

      var iMesRest  = new Number(12 - iMesAtu);

      iQuant += iMesRest + iMesLimit;

    } else {
      iQuant += iMesLimit - iMesAtu;
    }

    doc.r90_quant.value = iQuant+1;

  }
}


function js_verificaCampos(){

  var doc = document.form1;

  if (doc.r90_regist.value == ""){
    alert("Código do funcionário não informado");
    doc.r90_regist.focus();
    return false;
  } else if(doc.r90_lotac.value == ""){
    alert("Lotação do funcionário não informada");
    doc.r90_lotac.focus();
    return false;
  } else if(doc.r90_rubric.value == ""){
    alert("Rubrica não informada");
    doc.r90_rubric.focus();
    return false;
  } else if((doc.r90_quant.value == "" || doc.r90_quant.value == "0") && (doc.rh27_form.value == "T" || doc.rh27_form.value == "t")){
    alert("Quantidade não informada");
    doc.r90_quant.select();
    doc.r90_quant.focus();
    return false;
  } else if(doc.r29_tpp && doc.r29_tpp.value == ""){
    alert("Tipo não informado");
    doc.r29_tpp.focus();
    return false
  } else if((doc.r90_valor.value == "" || doc.r90_valor.value == "0") && (doc.rh27_form.value == "F" || doc.rh27_form.value == "f")){
    alert("Valor não informado");
    doc.r90_valor.select();
    doc.r90_valor.focus();
    return false;
  }


  return js_verificaposicoes(doc.r90_datlim.value,"true");

  if ( doc.r90_quant.value == "" ){
    doc.r90_quant.value = 0;
  }

  if ( doc.r90_valor.value == "" ){
    doc.r90_valor.value = 0;
  }

  return true;

}


function js_submita(){
  location.href = "pes1_pontofx001.php?r90_anousu="+document.form1.r90_anousu.value+"&r90_mesusu="+document.form1.r90_mesusu.value+"&r90_regist="+document.form1.r90_regist.value+"&ponto="+document.form1.ponto.value;
}

// Função para tornar ou não o campo datlim READONLY.
function js_desabilita(TrueORFalse){

  var sAcao = $F('acao');
  var doc   = document.form1;

  if(doc.r90_regist.value != ""){
    if( TrueORFalse==true || sAcao == 'Excluir' ) {
      if( sAcao != "Excluir" ){
        doc.r90_datlim.value = "";
      }
      doc.r90_datlim.readOnly = true;
      doc.r90_datlim.style.backgroundColor = "#DEB887";
      if(doc.r90_rubric.value != ""){
        doc.r90_quant.select();
        doc.r90_quant.focus();
      }else{
        doc.r90_rubric.select();
        doc.r90_rubric.focus();
      }
    }else{
      if(doc.r90_rubric.value != ""){
        doc.r90_datlim.readOnly              = false;
        doc.r90_datlim.style.backgroundColor ="";
        doc.r90_datlim.select();
        doc.r90_datlim.focus();
      }
    }
  }else{
    doc.r90_regist.select();
    doc.r90_regist.focus();
  }

}

function js_verificaposicoes(valor,TorF){

  var expr = new RegExp("[^0-9]+");
  localbarra = valor.search("/");
  erro = 0;
  errm = "";
  if(localbarra == -1){
    if(valor.match(expr)){
      erro ++;
    }else if(TorF == "true" && document.form1.r90_datlim.readOnly == false){
      erro ++;
    }
  }else{
    ano = valor.substr(0,4);
    mes = valor.substr(5,2);
    anoi = new Number(ano);
    mesi = new Number(mes);
    anot = new Number(document.form1.r90_anousu.value);
    mest = new Number(document.form1.r90_mesusu.value);

    if(ano.match(expr)){
      erro ++;
    }else if(mes.match(expr)){
      erro ++;
    }else if(anoi < anot || (anoi <= anot && mesi < mest)){
      if(mesi > 1 || anoi < anot || TorF == 'true'){
        errm = "\nAno e mês devem ser maior ou igual ao corrente da folha.";
        erro ++;
      }
    }else if(mesi > 12){
      errm = "\nMês inexistente.";
      erro ++;
    }else if(TorF == 'true' && mes == 0){
      errm = "\nMês não informado.";
      erro ++;
    }
  }

  if( erro > 0 || (document.form1.rh27_limdat.value == 't' && document.form1.r90_datlim.value == "")){
    alert("Campo Ano/mês deve ser preenchido com números e uma '/' no seguinte formato (aaaa/mm)! " + errm);
    document.form1.r90_datlim.select();
    document.form1.r90_datlim.focus();
    return false;
  }

//   return false;
  return true;

}
function js_mascaradata(valor){

  total = valor.length;
  if(total > 0){
    digit = valor.substr(total-1,1);
    if(digit != "/"){
      if(total == 4){
        valor += "/";
      }
    }
  }

  document.form1.r90_datlim.value = valor;
  return js_verificaposicoes(valor,'false');

}
function js_pesquisar90_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=<?=($ponto == "fs" ? "raf" : ($ponto == "fr" ? "fa" : "ra"))?>&funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome&instit=<?=(db_getsession("DB_instit"))?>&chave_r01_mesusu='+document.form1.r90_mesusu.value+'&chave_r01_anousu'+document.form1.r90_anousu.value,'Pesquisa',true);
  }else{
     if(document.form1.r90_regist.value != ''){
        js_OpenJanelaIframe('','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=<?=($ponto == "fs" ? "raf" : ($ponto == "fr" ? "fa" : "ra"))?>&pesquisa_chave='+document.form1.r90_regist.value+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
       location.href = "pes1_pontofx001.php?ponto="+document.form1.ponto.value;
     }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.r90_regist.focus();
    document.form1.r90_regist.value = '';
  }else{
    js_submita();
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.r90_regist.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe_rhpessoal.hide();
  js_submita();
}

function js_getDadosPadroes() {

  var sUrl         = 'pes1_rhrubricas.RPC.php';
  var oParametros  = new Object();
  var msgDiv       = "Pesquisando dados padrão da rubrica. Aguarde...";

  oParametros.sExecucao      = 'BuscaPadroesRubrica';
  oParametros.sCodigoRubrica = $F('r90_rubric');

  js_divCarregando(msgDiv,'msgBox');

  var oAjax = new Ajax.Request(
                               sUrl,
                               {
                                 method     : 'post',
                                 parameters : 'json=' + Object.toJSON(oParametros),
                                 onComplete : js_retornoDadosPadroes
                               }
                              );

}

function js_retornoDadosPadroes(oAjax) {

  js_removeObj('msgBox');

  var oRetorno         = eval("("+oAjax.responseText+")");

  if (oRetorno.iStatus == 1) {

    $('r90_quant').value = oRetorno.nQuantidadePadrao;
    $('r90_valor').value = oRetorno.nValorPadrao;

  } else {
    alert(oRetorno.sMensagem.urlDecode());
    $('r90_rubric').value = '';
  }

}

function js_pesquisar90_rubric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_rhrubricas','func_rhrubricascadserv.php?funcao_js=parent.js_mostrarubricas1|rh27_rubric|rh27_descr|rh27_limdat|formula|rh27_obs|rh27_presta|rh27_tipo|rh27_valorlimite|rh27_quantidadelimite|rh27_tipobloqueio&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
  }else{
     if(document.form1.r90_rubric.value != ''){
       quantcaracteres = document.form1.r90_rubric.value.length;
       for(i=quantcaracteres;i<4;i++){
         document.form1.r90_rubric.value = "0"+document.form1.r90_rubric.value;
       }
       js_OpenJanelaIframe('','db_iframe_rhrubricas','func_rhrubricascadserv.php?pesquisa_chave='+document.form1.r90_rubric.value+'&funcao_js=parent.js_mostrarubricas&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
     }else{
       document.form1.rh27_descr.value = '';
       document.form1.rh27_form.value  = '';
       document.form1.r90_rubric.value = '';
       document.form1.r90_valor.value  = '0';
       document.form1.r90_quant.value  = '0';
       document.getElementById('caixa_de_texto').innerHTML = "";
       js_desabilita(true);
     }
  }
}
function js_mostrarubricas(chave,chave2,chave3,chave4,chave5,chave6,erro,chave7,chave8,chave9){ console.log(chave7,chave8,chave9);

  document.form1.rh27_descr.value  = chave;
  document.form1.rh27_limdat.value = chave2;
  document.form1.rh27_tipo.value   = chave6;
  document.form1.rh27_valorlimite.value      = chave7;
  document.form1.rh27_quantidadelimite.value = chave8;
  document.form1.rh27_tipobloqueio.value     = chave9;

  if(erro==true){
    document.getElementById('caixa_de_texto').innerHTML = "";
    document.form1.rh27_form.value = '';
    document.form1.r90_rubric.value = '';
    document.form1.r90_rubric.focus();
  }else{
    document.form1.rh27_presta.value = chave5;
    document.getElementById('caixa_de_texto').innerHTML = "<font color='red'><b>"+chave4+"</b></font>";
    document.form1.rh27_form.value  = chave3;
  }
  if(chave2 == 'f' || chave2 == ''){
    js_desabilita(true);
  }else{
    js_desabilita(false);
  }

  js_getDadosPadroes();
}
function js_mostrarubricas1(chave1,chave2,chave3,chave4,chave5,chave6,chave7,chave8,chave9,chave10){ console.log(chave8,chave9,chave10);
  document.form1.r90_rubric.value  = chave1;
  document.form1.rh27_descr.value  = chave2;
  document.form1.rh27_form.value   = chave4;
  document.form1.rh27_presta.value = chave6;
  document.form1.rh27_tipo.value   = chave7;
  document.getElementById('caixa_de_texto').innerHTML = "<font color='red'><b>"+chave5+"</b></font>";
  document.form1.rh27_limdat.value = chave3;
  document.form1.rh27_valorlimite.value      = chave8;
  document.form1.rh27_quantidadelimite.value = chave9;
  document.form1.rh27_tipobloqueio.value     = chave10;

  if(chave3 == 'f' || chave3 == ""){
    js_desabilita(true);
  }else{
    js_desabilita(false);
  }
  db_iframe_rhrubricas.hide();

  js_getDadosPadroes();
}

function js_pesquisar90_lotac(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframelotacao','func_lotacao.php?funcao_js=parent.js_mostralotacao1|r70_codigo|r70_descr&instit=<?=(db_getsession("DB_instit"))?>&chave_r70_mesusu='+document.form1.r90_mesusu.value+'&chave_r70_anousu'+document.form1.r90_anousu.value,'Pesquisa',true);
  }else{
     if(document.form1.r90_lotac.value != ''){
       js_OpenJanelaIframe('','db_iframelotacao','func_lotacao.php?pesquisa_chave='+document.form1.r90_lotac.value+'&funcao_js=parent.js_mostralotacao&instit=<?=(db_getsession("DB_instit"))?>&chave_r70_mesusu='+document.form1.r90_mesusu.value+'&chave_r70_anousu'+document.form1.r90_anousu.value,'Pesquisa',false);
     }else{
       document.form1.r70_descr.value = '';
     }
  }
}
function js_mostralotacao(chave,erro){
  document.form1.r70_descr.value = chave;
  if(erro==true){
    document.form1.r90_lotac.focus();
    document.form1.r90_lotac.value = '';
  }
}
function js_mostralotacao1(chave1,chave2){
  document.form1.r90_lotac.value = chave1;
  document.form1.r70_descr.value = chave2;
  db_iframelotacao.hide();
}


function js_calcular(iMatricula, iPonto) {

  /*
    REQUISITOS PARA CALCULO de FOLHA:
    - tipo de folha  = iPonto
    - tipo de resumo = m (matricula)
    - tipo de filtro = s (selecionados)
    - db_debug       = 'false'
  */

  if ( document.getElementById('r90_regist').value == null || document.getElementById('r90_regist').value == '') {

    alert('Selecione uma Matricula');
    return false;

  } else {
    var sQuery  = "?campo_auxilio_carg=";
        sQuery += "&campo_auxilio_loca=";
        sQuery += "&campo_auxilio_orga=";
        sQuery += "&campo_auxilio_recu=";
        sQuery += "&campo_auxilio_rubr=";
        sQuery += "&faixa_lotac=";
        sQuery += "&opcao_gml=m";
        sQuery += "&opcao_filtro=s";
        sQuery += "&faixa_regis=<?php echo $sMatriculasParametro?>";
        sQuery += "&opcao_geral="+iPonto;
        console.log(sQuery);
	  js_OpenJanelaIframe('','db_iframe_ponto','pes4_gerafolha002.php'+sQuery,'Cálculo Financeiro',true);
  }

}

<?

$lDesabilitaData = "true";

if(isset($rh27_limdat)){
  if( $rh27_limdat == "t" ){
    $lDesabilitaData = "false";
  }
}

echo "js_desabilita($lDesabilitaData);";

if (isset($r90_regist)) {
  echo "js_consultaRubricas();";
}



?>

function js_consultar(iMatricula){

     //alert(iMatricula);
     //pes3_gerfinanc001.php
     if ( document.getElementById('r90_regist').value == null || document.getElementById('r90_regist').value == '') {
       alert('Selecione uma Matricula');
       return false;
     } else {
        parent.window.location = 'pes3_gerfinanc001.php?lConsulta=1&iMatricula='+iMatricula;
     }
  }

function js_validaLimiteRubrica () {

  var nValor       = $F('r90_valor');
  var iQuantidade  = $F('r90_quant');
  
  /**
   * Verifica se deve bloquear ou alertar o lançamento
   * caso o valor/quantidade da rubrica seja maior que
   * o limite configurado
   */
  if($F("rh27_tipobloqueio").toLowerCase() != 'n') { 

    var lBloqueia = false;

    if(parseFloat($F("rh27_valorlimite")) > 0 && parseFloat(nValor) > parseFloat($F("rh27_valorlimite"))) {
      alert(_M( MENSAGENS_VALIDA_LIMITE_RUBRICA + 'limite_valor_excedido', { 'valor' : $F("rh27_valorlimite") } ));
      lBloqueia = true;
    }
    
    if(parseFloat($F("rh27_quantidadelimite")) > 0 && parseFloat(iQuantidade) > parseFloat($F("rh27_quantidadelimite"))) {
      alert(_M( MENSAGENS_VALIDA_LIMITE_RUBRICA + 'limite_quantidade_excedido', { 'quantidade' : $F("rh27_quantidadelimite") } ));
      lBloqueia = true;
    }

    //Bloqueia o lançamento
    if($F("rh27_tipobloqueio").toLowerCase() == 'b' && lBloqueia) { 
      return false;
    }
  }
  return true;
}
</script>
