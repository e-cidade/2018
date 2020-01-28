<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_retencaotiporec_classe.php"));
require_once(modification("classes/empenho.php"));
require_once(modification("model/retencaoNota.model.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet = db_utils::postMemory($_GET);
$clrotulo        = new rotulocampo;
$clrotulo->label("e69_numero");
$clrotulo->label("e69_codnota");
$clrotulo->label("e50_codord");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("z01_nome");
$clrotulo->label("e70_valor");
$clrotulo->label("e70_vlrliq");
$clrotulo->label("e70_vlranu");
$clrotulo->label("e53_vlrpag");
$clrotulo->label("e21_sequencial");
$clrotulo->label("e21_descricao");
$clrotulo->label("e21_aliquota");
$clrotulo->label("e23_valorbase");
$clrotulo->label("e23_deducao");
$clrotulo->label("e23_valorretencao");
if (empty($oGet->iNumEmp)) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Número do Empenho não Informado.');
}
$oEmpenho    = new empenho($oGet->iNumEmp);
$rsDadosNota = $oEmpenho->getNotas($oGet->iNumEmp,"e69_codnota = {$oGet->iNumNota}",false);
if ($oEmpenho->iNumRowsNotas == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Nota não Encontrada.');
}
$oNota         = db_utils::fieldsMemory($rsDadosNota,0);
/**
 * Verificamos se o usuario está calculando a retencao para um cgm diferente do empenho
 * Caso relatado pelo Cliente GUAIBA
 *
 */
if (isset($oGet->iNumCgm) && $oGet->iNumCgm != "") {

  $sSqlDadosCgm = "select z01_nome,z01_cgccpf from cgm  where z01_numcgm = {$oGet->iNumCgm}";
  $rsDadosCgm   = db_query($sSqlDadosCgm);
  if ($rsDadosCgm && pg_num_rows($rsDadosCgm) == 1) {

    $oCgm              = db_utils::fieldsMemory($rsDadosCgm,0);
    $oNota->z01_nome   = $oCgm->z01_nome;
    $oNota->z01_cgccpf = $oCgm->z01_cgccpf;

  }
}
$e69_codnota   = $oNota->e69_codnota;
$e69_numero    = $oNota->e69_numero;
$z01_nome      = $oNota->z01_nome;
$z01_cgccpf    = $oNota->z01_cgccpf;
$e70_valor     = $oNota->e70_valor;
$e70_vlrliq    = $oNota->e70_vlrliq;
$e70_vlranu    = $oNota->e70_vlranu;
$e53_vlrpag    = $oNota->e53_vlrpag;
$e50_codord    = $oNota->e50_codord;
$e60_numemp    = $oNota->e60_numemp;

if (isset($oGet->nValorBase) && $oGet->nValorBase != "") {

  $valorpagar    = round($oGet->nValorBase,2);
  $e23_valorbase = $valorpagar;
  $valormovimento = $valorpagar;

} else {

  $e23_valorbase = $oNota->e70_vlrliq - $oNota->e70_vlranu - $oNota->e53_vlrpag;
  $valorpagar    = $e23_valorbase;
  $valormovimento = $valorpagar;

}

if (isset($oGet->iCodMov)) {
  $e81_codmov = $oGet->iCodMov;

  if (empty($oGet->iCodMov)) {

    $sSqlBuscaMovimento = " select e82_codmov from empord where e82_codord = {$oNota->e50_codord} ";
    $rsBuscaMovimento = db_query($sSqlBuscaMovimento);
    if ($rsBuscaMovimento) {
      $e81_codmov = db_utils::fieldsMemory($rsBuscaMovimento, 0)->e82_codmov;
    }
  }

}
if (isset($oGet->lSession) && $oGet->lSession == "false") {
  $oRetencaoNota = new retencaoNota($oNota->e69_codnota);
  $oRetencaoNota->unsetSession();
}
/*
 * Verificamos se o emepnho nao é um empenho da folha
 */
$lEmpenhoFolha             = false;
$oDaoRHEmpenhofolhaEmpenho = db_utils::getDao("rhempenhofolhaempenho");
$sSqlEmpenhoFolha          = $oDaoRHEmpenhofolhaEmpenho->sql_query_file(null,"*",null, "rh76_numemp = {$e60_numemp}");
$rsEmpenhoFolha            = $oDaoRHEmpenhofolhaEmpenho->sql_record($sSqlEmpenhoFolha);
if ($oDaoRHEmpenhofolhaEmpenho->numrows > 0) {

  $lEmpenhoFolha = true;
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("datagrid.widget.js");
  db_app::load("strings.js");
  db_app::load("grid.style.css");
  db_app::load("estilos.css");
  db_app::load("AjaxRequest.js");
  ?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"
      marginheight="0" onLoad="a=1">
<center>
  <form name='form1'>
    <table>
      <tr>
        <td>
          <fieldset><legend><b>Dados da Nota</b></legend>
            <table>
              <tr>
                <td><b>Código da Nota:</b></td>
                <td>
                  <?
                  db_input('e69_codnota', 13, $Ie69_codnota, true, 'text', 3);
                  ?>
                </td>
                <td><b>Número:</b></td>
                <td>
                  <?
                  db_input('e69_numero', 13, $Ie69_numero, true, 'text', 3);
                  ?>
                </td>
                <td><b><?php echo $Le60_numemp?>:</b></td>
                <td>
                  <?
                  db_input('e60_numemp', 13, $Ie60_codemp, true, 'text', 3);
                  ?>
                </td>
                <td><b>Ordem:</b></td>
                <td>
                  <?
                  db_input('e50_codord', 13, $Ie50_codord, true, 'text', 3);
                  ?>
                </td>
              </tr>
              <tr>
                <td><b><?=$Lz01_nome?></b></td>
                <td colspan='8'>
                  <?
                  db_input('z01_nome', 70, $Lz01_nome, true, 'text', 3);
                  db_input('z01_cgccpf', 20, $Lz01_nome, true, 'text', 3);
                  ?>
                </td>
              </tr>
              <tr>
                <td><b>Valor:</b></td>
                <td>
                  <?
                  db_input('e70_valor', 13, $Ie70_valor, true, 'text', 3);
                  ?>
                </td>
                <td><b>Valor Liquidado:</b></td>
                <td>
                  <?
                  db_input('e70_vlrliq', 13, $Ie70_vlrliq, true, 'text', 3);
                  ?>
                </td>
                <td><b>Valor Anulado: </b></td>
                <td>
                  <?
                  db_input('e70_vlranu', 13, $Ie70_vlranu, true, 'text', 3);
                  ?>
                </td>
                <td><b>Valor Pago:</b></td>
                <td>
                  <?
                  db_input('e53_vlrpag', 13, $Ie53_vlrpag, true, 'text', 3);
                  ?>
                </td>
                <td>
                  <?
                  db_input('valorpagar', 13, $Ie53_vlrpag, true, 'hidden', 3);
                  db_input('valormovimento', 13,0, true, 'text', 3);
                  db_input('e81_codmov', 14, '', true, 'hidden', 3);
                  ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td>
          <fieldset><legend><b>Dados das Retenções</b></legend>
            <table>
              <tr>
                <td><strong>
                    <?
                    db_ancora('Retenção',"js_pesquisaRetencoes();",2);
                    ?>
                  </strong></td>
                <td class="retencoes-info">
                  <?
                  $rsInicio = db_query("select '' as codigo, 'Selecione' as descr");
                  db_selectrecord("e21_sequencial", $rsInicio, true, 1, "",null,"","","js_setAliquota(\$('e21_sequencial').selectedIndex)");

                  ?>
                </td>
                <td><b>Aliquota:</b></td>
                <td>
                  <?
                  db_input('e21_aliquota',15,$Ie21_aliquota,true,'text', 1,"onBlur='js_calculaRetencao()'");
                  ?>
                </td>
              </tr>
              <tr>
                <td><b><?=$Le23_deducao?></b></td>
                <td>
                  <?
                  db_input('e23_deducao',10,$Ie23_deducao,true,'text', 1,'onblur="js_calculaRetencao()"');
                  ?>
                </td>
                <td><b><?=$Le23_valorbase?></b></td>
                <td>
                  <?
                  db_input('e23_valorbase',15,$Ie23_valorbase,true,'text', 3,'');
                  ?>
                </td>
              </tr>
              <tr>
                <td><b><?=$Le23_valorretencao?></b></td>
                <td>
                  <?
                  db_input('e23_valorretencao', 10, $Ie23_valorretencao, true, 'text', 1, '');
                  ?>
                </td>
                <td>
                  <b>Data Calculo</b>
                </td>
                <td>
                  <?
                  if (isset($Get->dtPagamento) && $oGet->dtPagamento != "") {
                    $dtpagamento = $oGet->dtPagamento;
                  }else {
                    $dtpagamento = date("d/m/Y",db_getsession("DB_datausu"));
                  }
                  $aDataBase = explode("/", $dtpagamento);
                  $iMes      = $aDataBase[1];
                  $iAno      = $aDataBase[2];
                  db_input("dtpagamento",15,'',true,"text");
                  ?>
                </td>
              </tr>
              <tr>
                <td colspan='4' style='text-align: center'>
                  <input type='button' id='lancarretencao'      value='Lançar'      onclick='js_addRetencao()'>
                  <input type='button' id='alterarretencao'     value='Alterar'     onclick='js_alterarRetencao()' disabled>
                  <input type='button' id='apagarretencao'      value='Excluir'      onclick='js_apagar()' disabled>
                  <input type='button' id='limparretencao'      value='Limpar'      onclick='js_limpar()'>
                  <input type='button' id='recalcularretencao'  value='Recalcular'  onclick='js_calculaRetencao()'>
                  <input type='button' id='mostraoutrosmov'     value='Composição da Base'  onclick='js_mostraInfo()'>
                  <input type='button' id='tabelas        '     value='Tabelas'     onclick='js_tabelas()'>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td>
          <fieldset><legend><b>Retenções lançadas</b></legend>
            <div id='gridRetencoes'></div>
          </fieldset>
        </td>
      </tr>
    </table>
    <?
    if (isset($oGet->lSession) && $oGet->lSession == "false") {

      echo " <input type='button' id='confirmarretencoes'  value='Confirmar'  onclick='js_confirmar()'>";
      if (isset($oGet->iCodMov) && $oGet->iCodMov != "") {
        echo " <input type='button' id='configurarretencoes'  value='Configurar Pagamento'  onclick='js_confirmarPagamento()'>";
      }
    }
    ?>
  </form>
</center>
</body>
</html>

<script>
  <?
  if (isset($oGet->lSession) && $oGet->lSession == "false") {
    echo "lSession = false;\n";
  } else {
    echo "lSession = true;\n";
  }
  if (isset($oGet->callback) && $oGet->callback) {
    echo "lCallBack = true;\n";
  } else {
    echo "lCallBack = false;\n";
  }
  ?>
  function js_pesquisaretencao(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('','db_iframe_retencaotiporec',
        'func_retencaotiporec.php?funcao_js=parent.js_mostraretencao1|e21_sequencial|e21_aliquota|e21_descricao',
        'Retenções Cadastradas',true,0);
    } else {

      if (document.form1.e21_sequencial.value != ''){

        js_OpenJanelaIframe('','db_iframe_retencaotiporec',
          'func_retencaotiporec.php?pesquisa_chave='+$F('e21_sequencial')+
          '&funcao_js=parent.js_mostraretencao',
          '',false,0);
      } else {

        document.form1.e21_descricao.value = '';
        document.form1.e21_aliquota.value  = '';

      }
    }
  }

  function js_mostraretencao1(iCodigoRetencao, nAliquota, sDescricao) {

    $('e21_descricao').value  = sDescricao; iCodigoRetencao;
    $('e21_aliquota').value   = nAliquota;
    $('e21_descricao').value  = sDescricao;
    db_iframe_retencaotiporec.hide();

  }

  function js_mostraretencao(sDescricao, lErro, nAliquota) {

    $('e21_descricao').value  = sDescricao;
    if (lErro) {

      $('e21_sequencial').value  = '';
      $('e21_descricao').focus();
      $('e21_aliquota').value  = '';

    } else {

      $('e21_aliquota').value  = nAliquota;
      $('e23_deducao').focus();

    }
  }

  function js_init() {

    gridRetencoes              = new DBGrid("gridRetencoes");
    gridRetencoes.nameInstance = "gridRetencoes";
    gridRetencoes.setCellAlign(new Array("Right", "left", "right", "right","right", "right","right"));
    gridRetencoes.setHeader(new Array("Id",
        "Retenção",
        'Dedução',
        "Base de Calculo",
        "Aliquota",
        "Valor Retido",
        "Tipo",
        "Grupo"
      )
    );
    gridRetencoes.aHeaders[7].lDisplayed = false;
    gridRetencoes.show(document.getElementById('gridRetencoes'));
    closeOnSave = false;
    aBaseDeCalculo = new Array();

  }


  function js_setValorBase() {

    var nDeducao   = new Number($F('e23_deducao'));
    var nValorBase = new Number($F('e70_valor'));
    nValorBase     = (nValorBase - nDeducao);
    var nReducao   = new Number();
    if (nValorBase < 0) {
      nValorBase = 0;
    } else {

      nReducao                     = nValorBase *(new Number($F('e21_aliquota'))/100);
      $('e23_valorretencao').value = nReducao;
    }
    $('e23_valorbase').value = nValorBase;
  }

  function js_addRetencao() {

    /* Algumas regras:
     *  - caso o usuario cadastrou uma retencao de tipo 1, ou 2 (Imposto de Renda)
     *    ele não pode mais cadastrar uma retencao do tipo 3 ou 4 (INSS), pois as
     *    retencoes do tipo 3, 4 deduzem da base de cálculo.
     *  - Não Podemos lancar uma retencao duas vezes.
     *  - Sempre devemos ver o calculo da retencao por CGM(cnpj/cpf (quando for pessoa fisica)) dentro do mes , nunca por nota.
     *  - Valor da retencao nao pode ser maior que o valor da nota.
     */

    var nValorRetencao = new Number($F('e23_valorretencao'));
    var nTotalRetido   = getTotalRetido(null) + nValorRetencao;
    var nValorNota    = new Number($F('e70_valor'));

    if (nTotalRetido.toFixed(2) > nValorNota.toFixed(2)) {
      alert('Valor da retenção maior que o valor da nota!');
      return false;
    }

    if ($F('e21_sequencial') == '') {

      alert('Retenção não informada!');
      $('e21_sequencial').focus();
      return false;
    }

    if ($F('e23_valorretencao') == '') {

      alert('valor da retenção não informado!');
      return false;

    }
    isSave = true;
    var oRetencao = {};
    oRetencao.iCodigoRetencao = $F('e21_sequencial');
    oRetencao.nValorDeducao   = new Number($F('e23_deducao')).toString();
    oRetencao.nValorNota      = $F('e70_valor');
    oRetencao.iCodNota        = $F('e69_codnota');
    oRetencao.nAliquota       = $F('e21_aliquota');
    oRetencao.iCpfCnpj        = $F('z01_cgccpf');
    oRetencao.nValorRetencao  = $F('e23_valorretencao');
    oRetencao.nValorbase      = $F('e23_valorbase');
    oRetencao.aMovimentos     = new Array();
    if (typeof(aBaseDeCalculo[$F('e21_sequencial')]) != "undefined") {
      oRetencao.aMovimentos = aBaseDeCalculo[$F('e21_sequencial')];
    }
    var soRetencaojson        = oRetencao.toSource();
    soRetencaojson            = soRetencaojson.replace("(","");
    soRetencaojson            = soRetencaojson.replace(")","");
    js_divCarregando("Aguarde, Calculando retenção","msgBox");

    var oParametros = {
      "exec" : "addRetencao",
      "params" : [
        {
          "oRetencao" : oRetencao,
          "inSession" : true,
          "isUpdate"  : false
        }
      ]
    };

//    var sJson   = '{"exec":"addRetencao","params":[{"oRetencao":'+soRetencaojson+',"inSession":true,"isUpdate":false}]}';
//    url         = 'emp4_retencaonotaRPC.php';

    new AjaxRequest(
      'emp4_retencaonotaRPC.php',
      oParametros,
      function (oRetorno, lErro) {
        js_retornoaddRetencao(oRetorno);
      }
    ).setMessage('Aguarde, configurando retenção...').execute();
  }

  function js_retornoaddRetencao(oAjax) {

    js_removeObj("msgBox");
    var oRetencoes = oAjax;
    if (oRetencoes.status == 2) {

      alert(oRetencoes.message.urlDecode());
      return false;

    }
    gridRetencoes.clearAll(true);
    //preenchemos a grid com as retencoes;
    if (oRetencoes.aRetencoes.length > 0) {


      for (var iRet = 0; iRet < oRetencoes.aRetencoes.length; iRet++) {

        with (oRetencoes.aRetencoes[iRet]) {

          var aLinha = new Array();
          aLinha[0]  = e21_sequencial;
          aLinha[1]  = e21_descricao.urlDecode();
          aLinha[2]  = js_formatar(e23_deducao,'f');
          aLinha[3]  = js_formatar(e23_valorbase,'f');
          aLinha[4]  = js_formatar(e23_aliquota,'f');
          aLinha[5]  = js_formatar(e23_valorretencao,'f');
          aLinha[6]  = e21_retencaotipocalc;
          aLinha[7]  = e21_retencaotiporecgrupo;
          gridRetencoes.addRow(aLinha);
          gridRetencoes.aRows[iRet].sEvents = "onDBLClick='js_atualizaCampos("+iRet+")'";
          gridRetencoes.aRows[iRet].sValue  = e21_sequencial;
          aBaseDeCalculo[e21_sequencial]  = "";
          if (aMovimentos.length > 0) {

            var aMov = new Array();
            for (var iMov = 0; iMov < aMovimentos.length; iMov++) {
              aMov.push(aMovimentos[iMov]);
            }
            aBaseDeCalculo[e21_sequencial] = aMov;
          }
        }
      }

      gridRetencoes.renderRows();
    }
    $('valorpagar').value    = $F('valormovimento');
    $('e23_valorbase').value = $F('valormovimento');
    js_limpar();
  }

  function js_apagar() {

    isSave = true;
    js_divCarregando("Aguarde, Apagando retenções","msgBox");

    var oParametro = {
      "exec" : "apagarRetencao",
      "params" : [
        {
          "iCodNota": $F('e69_codnota'),
          "iRetencao": $F('e21_sequencial'),
          "iNotaLiquidacao": $F('e50_codord')
        }
      ]
    };

    new AjaxRequest(
      'emp4_retencaonotaRPC.php',
      oParametro,
      function (oRetorno) {
        js_retornoaddRetencao(oRetorno);
      }
    ).execute();
  }

  function js_consultaRetencoesNota() {

    js_divCarregando("Aguarde, Pesquisando retenções","msgBox");
    var iCodOrd    = $F('e50_codord');
    var iCodMov    = $F('e81_codmov');
    var dtRetencao = $F('dtpagamento');

    var oParametro = {
      "exec" : "getRetencoes",
      "params": [
        {
          "iCodNota" : $F('e69_codnota'),
          "iCodOrd"  :  iCodOrd,
          "iCodMov"  : iCodMov,
          "dtCalculo": dtRetencao
        }
      ]
    };

    new AjaxRequest(
      'emp4_retencaonotaRPC.php',
      oParametro,
      function (oRetorno, lErro) {
        js_retornoaddRetencao(oRetorno);
      }
    ).execute();

  }
  /**
   * preenche os campos com os valores da retencao atual.
   * @param integer iNumRow linha selecionada da grid
   */
  function js_atualizaCampos (iNumRow) {

    gridRetencoes.aRows[iNumRow].isSelected = true;
    /*
     * Aqui pegamos todos os valores da linha selecionada,
     * onde cada indice da array é uma coluna da grid.
     * so permitimos o usuario alterar retencoes do grupo 1 (fornecedore)
     */

    var aSelecionados                       = gridRetencoes.getSelection();
    if (aSelecionados[0][7] == 1) {

      $('e21_sequencial').value               = aSelecionados[0][0];//Codigo da retencao
      $('e21_sequencialdescr').value          = aSelecionados[0][0];//descricao da retencao
      $('e23_deducao').value                  = js_strToFloat(aSelecionados[0][2]);//valor da deducao
      $('e21_aliquota').value                 = js_strToFloat(aSelecionados[0][4]);//aliquota da retencao
      $('e23_valorretencao').value            = js_strToFloat(aSelecionados[0][5]);//valor retido da retencao
      $('lancarretencao').disabled            = true;
      $('alterarretencao').disabled           = false;
      $('apagarretencao').disabled            = false;
      $('e21_sequencial').disabled            = true;
      $('e21_sequencialdescr').disabled       = true;

    }
    gridRetencoes.aRows[iNumRow].isSelected = false;

  }

  function js_limpar() {
    <? if ($lEmpenhoFolha) { ?>
    $('lancarretencao').disabled      = true;
    <? } else { ?>
    $('lancarretencao').disabled      = false;
    <? } ?>
    $('alterarretencao').disabled     = true;
    $('apagarretencao').disabled      = true;
    $('e21_sequencial').disabled      = false;
    $('e21_sequencialdescr').disabled = false;
    $('e21_sequencial').value         = '';//Codigo da retencao
    $('e21_sequencialdescr').value    = '';//descricao da retencao
    $('e23_deducao').value            = '';//valor da deducao
    $('e21_aliquota').value           = '';//aliquota da retencao
    $('e23_valorretencao').value      = '';//valor retido da retencao
    $('e23_valorbase').value          = $F('valorpagar');

  }
  /**
   * Atualiza as informacoes da retenção.
   */
  function js_alterarRetencao() {

    var oRetencao      = new Object();
    var nValorRetencao = new Number($F('e23_valorretencao'));
    var nTotalRetido   = getTotalRetido($('e21_sequencial').value) + nValorRetencao;
    var nValorNota     = new Number($F('e70_valor'));

    if (nTotalRetido.toFixed(2) > nValorNota.toFixed(2)) {
      alert('Valor da retenção maior que o valor da nota!');
      return false;
    }

    if ($F('e23_valorretencao') == '') {

      alert('valor da retenção não informado!');
      return false;
    }

    isSave = true;
    oRetencao.iCodigoRetencao = $F('e21_sequencial');
    oRetencao.nValorDeducao   = new Number($F('e23_deducao')).toString();
    oRetencao.nValorNota      = $F('e70_valor');
    oRetencao.iCodNota        = $F('e69_codnota');
    oRetencao.nAliquota       = $F('e21_aliquota');
    oRetencao.iCpfCnpj        = $F('z01_cgccpf');
    oRetencao.nValorRetencao  = $F('e23_valorretencao');
    oRetencao.nValorbase      = $F('e23_valorbase');
    oRetencao.aMovimentos     = new Array();
    if (aBaseDeCalculo[$F('e21_sequencial')] != "undefined") {
      oRetencao.aMovimentos = aBaseDeCalculo[$F('e21_sequencial')];

    }

    js_divCarregando("Aguarde, Calculando retenção","msgBox");
    var oParametro = {
      "exec" : "addRetencao",
      "params" : [
        {
          "oRetencao" : oRetencao,
          "inSession" : true,
          "isUpdate"  : true
        }
      ]
    };

    new AjaxRequest(
      'emp4_retencaonotaRPC.php',
      oParametro,
      function(oRetorno, lErro) {
        js_retornoaddRetencao(oRetorno);
      }
    ).execute();
  }
  /**
   * Confirmar as alterações realizadas nas retencoes.
   */
  function js_confirmar() {

    js_divCarregando("Aguarde, salvando modificações","msgBox");

    var oParametro = {
      "exec" : "saveRetencoes",
      "params" : [
        {
          "iCodNota" : $F('e69_codnota'),
          "iCodOrd" : $F('e50_codord'),
          "iCodMov" : $F('e81_codmov'),
          "aMovimentos" : aBaseDeCalculo
        }
      ]
    };

    new AjaxRequest(
      'emp4_retencaonotaRPC.php',
      oParametro,
      function(oRetorno, lErro) {
        js_retornoConfirma(oRetorno);
      }
    ).execute();

  }
  function js_retornoConfirma(oAjax) {

    isSave = false;
    js_removeObj("msgBox");
//    var oRetorno = eval("("+oAjax.responseText+")");
    var oRetorno = oAjax;
    alert(oRetorno.message.urlDecode());
    aBaseDeCalculo = new Array();
    if (oRetorno.status == 1) {

      if (lCallBack) {

        js_bloqueiaMenus(false);
        var nTotalRetencoes = gridRetencoes.sum(5, false);
        parent.js_atualizaValorRetencao($F('e81_codmov'),
          nTotalRetencoes,
          $F('e69_codnota'),
          $F('e50_codord'),
          oRetorno.lMesAnterior
        );

      }
      if (closeOnSave) {

        js_bloqueiaMenus(false);
        parent.db_iframe_retencao.hide();


      }
    }
  }

  oBtnFechar            = parent.$('fechardb_iframe_retencao');
  oBtnFechar.onclick    = js_sair ;
  oBtnMinimizar         = parent.$('minimizardb_iframe_retencao');
  oBtnMinimizar.onclick = '';

  function js_sair() {

    if (isSave && !lSession) {

      if (confirm('Há Operações não salvas.\nSalvar antes de sair?')) {

        closeOnSave = true;
        js_confirmar();

      } else {

        js_bloqueiaMenus(false);
        parent.db_iframe_retencao.hide();

      }
    } else {

      js_bloqueiaMenus(false);
      if (lCallBack) {

        var nTotalRetencoes = gridRetencoes.sum(5, false);
        parent.js_atualizaValorRetencao($F('e81_codmov'), nTotalRetencoes, $F('e69_codnota'), $F('e50_codord'), null);

      }
      parent.db_iframe_retencao.hide();
    }

  }

  function adicionaRetencao(sValor, sChave, iTipoCalc,nAliquota, lLiberado) {

    var oCmbRetencao      = $('e21_sequencial');
    var oCmbRetencaoDescr = $('e21_sequencialdescr');
    var oOptionKey        = new Option(sChave, sChave);
    oOptionKey.tipocalc   = iTipoCalc;
    oOptionKey.aliquota   = nAliquota;
    oCmbRetencao.add(oOptionKey, null);
    var oOptionValue      = new Option(sValor, sChave);
    oOptionValue.tipocalc = iTipoCalc;
    oOptionValue.aliquota = nAliquota;
    if (!lLiberado) {
      oOptionValue.disabled = true;
    }
    oCmbRetencaoDescr.add(oOptionValue, null);

  }

  function js_setAliquota(selectedIndex) {

    $('e21_aliquota').value = $('e21_sequencial').options[selectedIndex].aliquota;
    $('valorpagar').value    = $F('valormovimento');
    $('e23_valorbase').value = $F('valormovimento');
    js_calculaRetencao();

  }

  function js_calculaRetencao() {

    var sCpfCnpj    = $F('z01_cgccpf');
    var iTipoCalc   = $('e21_sequencial').options[$('e21_sequencial').selectedIndex].tipocalc;
    var nAliquota   = new Number($F('e21_aliquota'));
    var nDeducao    = new Number($F('e23_deducao'));
    var nValorBase  = new Number($F('e23_valorbase'));
    var nValorNota  = new Number($F('valorpagar'));
    var dtPagamento = $F("dtpagamento");
    var aMovimentos = new Array();
    if (nDeducao > nValorNota) {

      alert('Valor da dedução não pode ser maior que o valor da base de Cálculo');
      return false;

    }
    if (aBaseDeCalculo[$F('e21_sequencial')] != null) {

      aMovimentos = aBaseDeCalculo[$F('e21_sequencial')];

    }
    if ($F('e21_sequencial') != '') {

      js_divCarregando("Aguarde, calculando..","msgBox");
      var oParametro = {
        "exec" : "calculaRetencao",
        "params" : [
          {
            "iTipoCalc"     : iTipoCalc,
            "iCpfCnpj"      : sCpfCnpj,
            "nAliquota"     : nAliquota,
            "dtPagamento"   : dtPagamento,
            "aMovimentos"   : aMovimentos,
            "nValorDeducao" : nDeducao,
            "nValorBase"    : nValorBase,
            "iCodNota"      : $F('e69_codnota'),
            "nValorNota"    : nValorNota
          }
        ]
      };
      new AjaxRequest(
        'emp4_retencaonotaRPC.php',
        oParametro,
        function (oRetorno, lErro) {
          js_retornoCalculo(oRetorno);
        }
      ).execute();
    }
  }

  function js_retornoCalculo(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = oAjax;
    if (oRetorno.status == 1) {

      $('e23_valorretencao').value = new Number(oRetorno.nValorRetencao).toFixed(2);
      $('e21_aliquota').value      = oRetorno.nAliquota;
      $('e23_valorbase').value     = oRetorno.nValorBase;

    } else if (oRetorno.status == 2) {
      alert(oRetorno.message.urlDecode());
    }
  }

  function js_confirmarPagamento() {


    if (isSave) {

      alert('Antes de pagar as retenções , confirme as modificações realizadas.');
      return false;

    }
    if (!confirm('Antes de pagar as retenções, revise se já salvou as últimas modificações.' )) {
      return false;
    }
    js_divCarregando("Aguarde, salvando modificações","msgBox");
//    var sJson   = '{"exec":"configurarRetencoes","params":[{"iCodNota":'+$F('e69_codnota')+',';
//    sJson      += '"iCodOrd":'+($F('e50_codord'))+',"iCodMov":'+$F('e81_codmov')+',"aMovimentos":'+aBaseDeCalculo.toSource()+'}]}';
//    var url     = 'emp4_retencaonotaRPC.php';

    var oParametro = {
      "exec" : "configurarRetencoes",
      "params" : [
        {
          "iCodNota": $F('e69_codnota'),
          "iCodOrd" : $F('e50_codord'),
          "iCodMov" : $F('e81_codmov'),
          "aMovimentos" : aBaseDeCalculo
        }
      ]
    };

    new AjaxRequest(
      'emp4_retencaonotaRPC.php',
      oParametro,
      function (oRetorno) {
        js_retornoConfirmaPagamento(oRetorno);
      }
    ).execute();

  }

  function js_retornoConfirmaPagamento(oAjax) {

    isSave = false;
    js_removeObj("msgBox");
    var oRetorno = oAjax;
    alert(oRetorno.message.urlDecode());
    if (oRetorno.status == 1) {

      js_bloqueiaMenus(false);
      parent.db_iframe_retencao.hide();
      parent.js_pesquisarOrdens();

    }
  }

  js_bloqueiaMenus(true);
  js_init();

  $('e21_sequencial').style.width      = '50px';
  $('e21_sequencialdescr').style.width = '200px';
  <?
  /*
   * Consultamos as retencoes Existes e adicionamos os tipos conforme o tipo de pessoa
   * do CGM.
   * se for fisica, apenas disponibilizamos as retencoes do tipo 1,3,5
   * se for juridica, apenas disponibilizamos as retencoes do tipo 2,4,5
   * Caso for pessoa juridica, devemos verificar se o movimento nao está incluido em outra
   * retencao.
   */
  $oDaoRetencao = new cl_retencaotiporec;
  $sSQLRetencao = $oDaoRetencao->sql_query(null,
                                           "e21_sequencial,
                                            e21_descricao,
                                            e21_retencaotipocalc,
                                            e21_aliquota",
                                            "e21_sequencial",
                                            "e21_instit=".db_getsession("DB_instit")."
                                            and e21_retencaotiporecgrupo = 1");
  $rsRetencao   = $oDaoRetencao->sql_record($sSQLRetencao);
  if (strlen($oNota->z01_cgccpf) == 14) {
    $sPessoa  = "J";
  } else if (strlen($oNota->z01_cgccpf) == 11){
    $sPessoa = "F";
  } else {
    $sPessoa = null;
  }
  if ($oDaoRetencao->numrows > 0) {

     $aRetencoes = db_utils::getCollectionByRecord($rsRetencao);
     foreach ($aRetencoes as $oRetencao) {

       $lAdiciona  = true;
       switch ($oRetencao->e21_retencaotipocalc) {

         case 1:
          if ($sPessoa != "F") {
            $lAdiciona = false;
          }
          break;
         case 2:

           if ($sPessoa != "J") {
            $lAdiciona = false;
          }
          break;

         case 3:
         if ($sPessoa != "F") {
           $lAdiciona = false;
         }
         break;

         case 4:
         if ($sPessoa != "J") {
           $lAdiciona = false;
         }
         break;

        case 7:
         if ($sPessoa != "F") {
           $lAdiciona = false;
         }
         break;
         default:
           $lAdiciona = true;
         break;
       }

       if ($lAdiciona) {

          $lLiberado = "true";
          if ($oGet->iCodMov != "") {

            $oRetencaoAtiva = $oRetencaoNota->getRetencoesByMovimento($oGet->iCodMov,$oRetencao->e21_sequencial);
            if ($oRetencaoAtiva && $sPessoa == "J"){
                if (isset($oRetencaoAtiva->e27_principal) && $oRetencaoAtiva->e27_principal == "f") {
                $lLiberado = "false";
              }
            }
          }
          echo "adicionaRetencao('{$oRetencao->e21_descricao}','{$oRetencao->e21_sequencial}','{$oRetencao->e21_retencaotipocalc}',";
          echo "'$oRetencao->e21_aliquota', {$lLiberado});\n";
       }

     }

  }

  ?>
  function js_chkcpf(vcic){

    expr  = new  RegExp("0{11}|1{11}|2{11}|3{11}|4{11}|5{11}|6{11}|7{11}|8{11}|9{11}");
    if (vcic.value.match(expr)){

      vcic.value = "";
      vcic.focus();
      return false;
    }
    if (isNaN(vcic.value) || vcic.value.length != 11){
      return false;
    }

    for (var vdigpos = 10; vdigpos < 12; vdigpos++ ){

      var vdig = 0;
      var vpos = 0;
      for (var vfator = vdigpos;vfator >= 2; vfator-- ){

        vdig = eval(vdig + vcic.value.substr(vpos,1) * vfator);
        vpos++;

      }
      vdig  = eval(11 -(vdig % 11)) < 10 ? eval(11 - vdig % 11) : 0;
      if (vdig != eval(vcic.value.substr(vdigpos-1,1))) {

        vcic.value = "";
        vcic.focus();
        return false;
      }
    }
    return true;
  }
  //validação de cnpj
  function js_chkcnpj(vcnpj) {

    if (isNaN(vcnpj.value) || vcnpj.value.length != 14){
      return false;
    }
    for (var vdigpos = 13; vdigpos < 15; vdigpos++ ){

      var vdig = 0;
      var vpos = 0;
      for (var vfator = vdigpos - 8 ;vfator >= 2; vfator-- ){

        vdig = eval(vdig + vcnpj.value.substr(vpos,1) * vfator);
        vpos++;

      }
      for (var vfator = 9 ;vfator >= 2; vfator-- ){

        vdig = eval(vdig + vcnpj.value.substr(vpos,1) * vfator);
        vpos++;

      }
      vdig  = eval(11 -(vdig % 11)) < 10 ? eval(11 - vdig % 11) : 0;
      if (vdig != eval(vcnpj.value.substr(vdigpos-1,1))) {
        return false;
      }
    }
    return true;
  }
  /*
   * Validamos o cpf/cnpj do credor, caso esteja incorreto, não deixamos o usuário
   * cadastrar retenções.
   */

  sMsgCnpjIncorreto  = "Não será possível incluir retenções para esta nota porque seu credor apresenta ";
  sMsgCnpjIncorreto += "código de CPF/CNPJ nulo ou inválido.\n";
  sMsgCnpjIncorreto += "Será necessário corrigir seu cadastro no CGM para realizar esta operação.";
  if ($F('z01_cgccpf').length == 14) {

    if (!js_chkcnpj($('z01_cgccpf'))) {

      alert(sMsgCnpjIncorreto);
      js_desabilitaBotoes(true);

    } else {
      js_consultaRetencoesNota();
    }
  } else if ($F('z01_cgccpf').length == 11) {

    if (!js_chkcpf($('z01_cgccpf'))) {

      alert(sMsgCnpjIncorreto);
      js_desabilitaBotoes(true);
    } else {
      js_consultaRetencoesNota();
    }
  } else if ($F('z01_cgccpf').length != 11 || $F('z01_cgccpf').length != 14 ) {

    alert(sMsgCnpjIncorreto);
    js_desabilitaBotoes(true);
  }
  js_consultaRetencoesNota();

  function js_mostraInfo() {

    var iCodigoRetencao = $F('e21_sequencial');
    var iCpfCGC         = $F('z01_cgccpf');
    var iTipoCalculo    = 0;
    var sValorFiltro    = "";

    if (iCodigoRetencao == "") {

      alert('Informe a Retenção.');
      return false;

    }
    if (iCpfCGC.length == 14) {

      iTipoCalculo = 1; //apenas calculos sobre a nota de liquidacao
      sValorFiltro = $F('e50_codord');

    } else if (iCpfCGC.length == 11) {

      iTipoCalculo = 2; //calculos sobre valores pagos no mes
      sValorFiltro = iCpfCGC;
    } else {
      alert("CNPJ/CPF inválido.");
    }
    if (iTipoCalculo != 0) {

      js_OpenJanelaIframe('', 'db_iframe_inforetencao',
        'emp4_inforetencao.php?iTipoCalculo='+iTipoCalculo+'&sValorFiltro='+sValorFiltro+
        '&iNumEmp='+$F('e60_numemp')+"&iCodMov="+$F('e81_codmov')+"&iCodRetencao="+iCodigoRetencao+
        '&iCodNota='+$F('e69_codnota'),
        'Outras Informações (Base de Cálculo)',
        true,10,10,750,500);
    }
  }

  function js_tabelas() {

    js_OpenJanelaIframe('', 'db_iframe_tabelas',
      'emp4_tabelasretencao.php',
      'Tabelas de Cálculo Pessoa Fisica',
      true,10,10,750,500);
  }

  function setBaseDeCaculo(aBaseDeCalculo1,iCodigoRetencao) {
    aBaseDeCalculo[iCodigoRetencao] = aBaseDeCalculo1;
  }

  function js_desabilitaBotoes(lDesabilita) {

    $('lancarretencao').disabled      = lDesabilita;
    $('alterarretencao').disabled     = lDesabilita;
    $('apagarretencao').disabled      = lDesabilita;
    $('mostraoutrosmov').disabled     = lDesabilita;
    $('recalcularretencao').disabled  = lDesabilita;
    $('limparretencao').disabled      = lDesabilita;

  }
  //controle de tela, caso false, o usuário nao fez nenhuma modificação, caso true, a modificações.
  isSave = false;
  /**
   * o Empenho eh da folha bloqueados todos os botoes da tela. o usuário pode apenas consultar o empenho
   */
  <?
  if ($lEmpenhoFolha) {
  ?>
  $('lancarretencao').disabled      = true;
  $('alterarretencao').disabled     = true;
  $('apagarretencao').disabled      = true;
  $('mostraoutrosmov').disabled     = true;
  $('recalcularretencao').disabled  = true;
  $('limparretencao').disabled      = true;
  $('confirmarretencoes').disabled  = true;
  <?
  }
  ?>
  /*
   * passa para lookup todos os options da combobox de retençoes
   */
  function js_pesquisaRetencoes(){

    var aElemOpcao = $$("class='retencoes-info' select[name='e21_sequencial'] option");
    var sLista     = new String(['']);
    var sListaNova     = new String(['']);
    aElemOpcao.each(
      function (oElemOpcao, iInd) {
        sLista += oElemOpcao.value + ',';
      }
    );

    var tam = sLista.length-1;
    sLista = sLista.slice(1, tam);

    var sUrl = 'func_retencaotiporec.php?chave_pesquisa_in=+'+sLista+'&funcao_js=parent.js_mostra|e21_sequencial';
    js_OpenJanelaIframe('', 'db_iframe_retencaotiporec', sUrl, 'Pesquisa', true, 5, 5, 1500, 500);
  }

  function js_mostra(chave1) {

    $('e21_sequencial').value = chave1;

    js_ProcCod_e21_sequencial('e21_sequencial','e21_sequencialdescr');
    js_setAliquota($('e21_sequencial').selectedIndex);

    db_iframe_retencaotiporec.hide();
  }

  /**
   * Cálcula o total retido na tabela de retenções lançadas.
   * @param {int} iRetencaoSelecionada Código da retenção selecionada, caso seja alteração.
   * @returns {Number} Total da tabela de retenções lançadas.
   */
  function getTotalRetido(iRetencaoSelecionada) {

    var nTotalRetido = new Number(0);

    var aRetencoes = gridRetencoes.getRows();
    for (var iIndice = 0; iIndice < aRetencoes.length; iIndice++) {

      if (aRetencoes[iIndice].aCells[0].getValue() == iRetencaoSelecionada) {
        continue;
      }
      nTotalRetido += js_strToFloat(aRetencoes[iIndice].aCells[5].getValue());
    }

    return nTotalRetido;
  }


</script>