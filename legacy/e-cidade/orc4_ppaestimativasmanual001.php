<?
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_ppaestimativa_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet = db_utils::postMemory($_GET);
$oDaoPPalei = db_utils::getDao("ppaversao");
$sSqlPPalei = $oDaoPPalei->sql_query($oGet->o05_ppaversao);
$rsPpaLei = $oDaoPPalei->sql_record($sSqlPPalei);
$oPPaLei = db_utils::fieldsMemory($rsPpaLei, 0);
$o01_sequencial = $oGet->o01_sequencial;
$sLegenda = "";
if ($oPPaLei->o119_finalizada == "t") {

  $sLegenda = "P{$oPPaLei->o119_versao} - " . DB_formatar($oPPaLei->o119_datainicio, "d");
  $sLegenda .= " - " . DB_formatar($oPPaLei->o119_datatermino, "d");

} else {

  $sLegenda = "P{$oPPaLei->o119_versao} - " . DB_formatar($oPPaLei->o119_datainicio, "d");

}
$iAnoCorrente = db_getsession("DB_anousu");
/**
 * Verificamos se já nao foi exportado o ppa para o orcamento
 */
$sSqlIntegrado = "SELECT o123_ano, o123_sequencial ";
$sSqlIntegrado .= "  from ppaintegracao ";
$sSqlIntegrado .= " where o123_ppaversao  = {$oGet->o05_ppaversao}";
$sSqlIntegrado .= "   and o123_situacao   = 1 ";
$sSqlIntegrado .= "   and o123_instit     = " . db_getsession("DB_instit");
$sSqlIntegrado .= "   and o123_tipointegracao   = 1 ";
$sSqlIntegrado .= "   and exists(select 1 ";
$sSqlIntegrado .= "                from ppaintegracaodespesa ";
$sSqlIntegrado .= "               where o121_ppaintegracao = o123_sequencial)";
$rsAnoCancelar = DB_query($sSqlIntegrado);
$lBloquear = "true";
$sDisplay = "none";
$sMessage = "";

if (pg_num_rows($rsAnoCancelar) > 0) {

  $lBloquear = "false";
  $sDisplay = "";
  $sMessage = "<span style='float:left;overflow: hidden;text-align: left;'>";
  $sMessage .= "Já foram gerados os dados do orcamento para  essa perspectiva!<br>";
  $sMessage .= "Quadro sera apenas utilizado para consulta</span>";
  //$sMessage .= "<span vertical-align: middle;><img src='imagens/dialog_warning.png'></span>";
}

$iAnoImplantacaoPCASP = db_getsession("DB_anousu");

/**
 * Ano de implantacao do PCASP > ano atual
 */
if ( !empty($_SESSION['DB_ano_pcasp']) && $_SESSION['DB_ano_pcasp'] > $iAnoImplantacaoPCASP ) {
  $iAnoImplantacaoPCASP = $_SESSION['DB_ano_pcasp'];
}

if ($oPPaLei->o01_anoinicio - 1 != db_getsession("DB_anousu") && db_getsession("DB_anousu") < $iAnoImplantacaoPCASP) {

  $lBloquear = "false";
  $sDisplay = "";
  $sMessage = "Para o período de referência selecionado será permitido somente consulta. ";
  $sMessage .= "Se você pretende alterar ou processar dados visando a preparação da próxima LDO,";
  $sMessage .= "deverá criar um novo período de referência através do menu:<br>";
  $sMessage .= "Orcamento>Cadastros>PPA>Período de referencia PPA/LOA>Inclusao.";
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
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_init()" >
<div id='messageBoard' style="border-bottom: 2px groove white; padding:5px;
                                background-color: white; font-weight:bold;
             width: 99%;height: 50px;text-align: left;display: <?=$sDisplay ?>">
      <?=$sMessage ?>
   </div>
<fieldset>
  <legend>
    <b>Filtros</b>
  </legend>
  <table>
    <tr>
      <td>
         <b>Estrutural</b>
      </td>
      <td>
         <?
db_input('fonte', 15, "", true, 'text', 1);
db_input('o01_sequencial', 15, "", true, 'hidden', 3);
         ?>
      </td>
    </tr>
    <tr>
      <td>
        <input type='button' value='Filtrar' onclick="js_getEstimativas()">
      </td>
    </tr>
  </table>
</fieldset>
<fieldset>

  <legend><b>Dados <?=$sLegenda ?></b></legend>
  <form name='ppa'>
  <div id='gridQuadroEstimativas'>
  </div>
  </form>
  <input type="checkbox" id='notificar' checked="checked">
  <label for='notificar'><b>Notificar alterações na base</b></label>
</fieldset>
<pre id='teste'>
</pre>
</body>
</html>
<script>

sUrlRPC      = 'orc4_ppaRPC.php';
iAnoInicio   = <?=$oPPaLei->o01_anoinicio ?>;
iAnoFinal    = <?=$oPPaLei->o01_anofinal ?>;
iAnoCorrente = <?=$oPPaLei->o01_anoinicio - 1; ?>;

/**
 * iTipo Define o tipo do programa.
 * 1 - receita
 * 2 - Despesa
 */
iTipo        = <?=$oGet->iTipo ?>;

/**
 * Define a propriedade ReadOnly dos inputs do valor.
 *
 * Carrega o valor como false quando o ppa já foi exportado
 * para o Orçamento
 */
lReadOnly    = <?=$lBloquear ?>;

function js_init() {

  gridPPA              = new DBGrid("gridPPA");
  gridPPA.nameInstance = "gridPPA";
  gridPPA.allowSelectColumns(true);
  gridPPA.setHeight(350);
  if (iTipo == 1) {

    gridPPA.setCellWidth(new Array("10%","25%"));
    gridPPA.setCellAlign(new Array("left", "right","right", "right", "right", "right", "right", "right",
                                   "right", "right","right","right","right"));
    gridPPA.setHeader(new Array("Estrutural",
                                "Descrição",
                                "Código",
                                "&nbsp;CP&nbsp;",
                                "Recurso",
                                "Arrec "+(iAnoInicio-4),
                                "Arrec "+(iAnoInicio-3),
                                "Arrec "+(iAnoInicio-2),
                                "Arrec "+(iAnoInicio-1),
                                "Media Arrec",
                                iAnoInicio,
                                iAnoInicio+1,
                                iAnoInicio+2,
                                iAnoInicio+3
                                  ));

    gridPPA.aHeaders[5].lDisplayed = false;
    gridPPA.aHeaders[6].lDisplayed = false;
    gridPPA.aHeaders[7].lDisplayed = false;

  } else {

    gridPPA.setCellWidth(new Array("35%"));
    gridPPA.setCellAlign(new Array("right", "right", "right", "right", "right", "right", "right", "right",
                                   "right", "right","right","right","right"));
    gridPPA.setHeader(new Array("Estrutural",
                                "Código",
                                "Liq "+(iAnoInicio-4),
                                "Liq "+(iAnoInicio-3),
                                "Liq "+(iAnoInicio-2),
                                "Liq "+(iAnoInicio-1),
                                "Media Liq",
                                iAnoInicio,
                                iAnoInicio+1,
                                iAnoInicio+2,
                                iAnoInicio+3
                                  ));
    gridPPA.aHeaders[2].lDisplayed = false;
    gridPPA.aHeaders[3].lDisplayed = false;
  }
  gridPPA.show(document.getElementById('gridQuadroEstimativas'));
}

/**
 * Retorna o Quadro das estimativas conforme parametro
 */
function js_getEstimativas() {

  var oParam           = new Object();
  oParam.exec          =  "getQuadroEstimativa";
  oParam.estrutural    = $F("fonte");
  oParam.iTipo         = <?=$oGet->iTipo ?>;
  oParam.iCodigoLei    = <?=$_GET["o01_sequencial"] . "\n" ?>;
  oParam.iCodigoVersao = <?=$_GET["o05_ppaversao"] ?>;
  js_divCarregando("Aguarde, Carregando estimativas.","MsgBox");
  var oAjax   = new Ajax.Request(
                         sUrlRPC,
                         {
                          method    : 'post',
                          parameters: 'json='+js_objectToJson(oParam),
                          onComplete: js_retornoProcessaQuadro
                          }
                        );
}
/**
 * Preenchemos a grid com os dados da receita
 */
function js_retornoProcessaQuadro(oAjax){

  js_removeObj("MsgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  gridPPA.clearAll(true);
  if (oRetorno.status == 2) {

    alert(oRetorno.message.urlDecode());
    return false;

  }
  for (var i = 0; i < oRetorno.itens.length; i++) {

    with (oRetorno.itens[i]) {

      var lTemDesbobrar = false;
      if (aDesdobramentos.length > 0) {
        lTemDesbobrar = true;
      }
      var aLinha = new Array();

      if (iTipo == 1) {

        /*
         * Montamos as descrições do estrutural para a receita
         */
        aLinha[0]  = iEstrutural+"&nbsp;";
        if ((iReduz != "" && !lDesdobra)|| aDesdobramentos.length > 0) {

          aLinha[1]  = "<span style='position:relative;float:left;overflow:hidden'>"+sDescricao.urlDecode().substring(0,30);
          aLinha[1]  += "</span><span style='position:relative;top:0px;right:0px;text-align:right'>";
          if (lReadOnly) {

            aLinha[1]  +=" <input class='' type='button' value='...' style='width:12px;font-weight:bold;border:2px outset white;background-color:#cccccc'";
            aLinha[1]  +="              onclick='js_reprocessaValor("+iCodigo+",\""+iConcarPeculiar+"\")'></span>";

          }

        } else {
           aLinha[1]  = "<span style='position:relative;float:left;overflow:hidden'>"+sDescricao.urlDecode().substring(0,30)+"</span>";
        }
        aLinha[2]  = iCodigo;
        aLinha[3]  = iConcarPeculiar;
        aLinha[4]  = iRecurso;

         var j           = 5;

      } else {
       /*
        * Quadro da despesa
        */
        aLinha[0]  = "<span style='position:relative;float:left;overflow:hidden'>"+iEstrutural
        aLinha[0]  += "</span>";
        if (lReadOnly) {

          aLinha[0]  +=" <input class='' type='button' value='...' style='width:12px;font-weight:bold;border:2px outset white;background-color:#cccccc'";
          aLinha[0]  +="              onclick='js_reprocessaValor("+iCodigo+",\""+iConcarPeculiar+"\")'>";

        }
        if (iCodigo != "") {
           aLinha[1]  = "<a href='#' onclick='js_mostraSaldo("+iCodigo+");return false'>"+iCodigo+"</a>";
        } else {
           aLinha[1]  = "&nbsp;";
        }
        var j      = 2;
      }
      var iTotMedias  = 0;
      var nValorMedia = 0;

      /**
       * Criamos as colunas dos anos anterios (ano de inicio do ppa - 4 anos)
       */
      var iTotalAnos = (iAnoInicio-4);
      for (var iAno = iTotalAnos; iAno < iAnoInicio; iAno++) {

        var sStyle  = '';
        if (iReduz == "") {
         sStyle = 'font-weight:bold;';
        }
        /**
         * Liberamos para o usuário alterar as estimativas da base de calculo do ano anterior
         * ao ano de inicio do PPA (apenas estimativas de receita)
         */
        if (iTipo == 1 && iAno == iAnoCorrente) {

           var nValor  =  js_formatar(aBaseCalculo[iAno],'f');
           aLinha[j]   = "<input readonly type='text' style='width:100%;text-align:right;border:1px solid white;"+sStyle+"'";
           if ( lReadOnly) {

             if ((iReduz != "" && !lDesdobra && iCodigo != "") || aDesdobramentos.length > 0) {

               aLinha[j]  += " onfocus='js_liberaDigitacao(this)' onblur='js_bloqueiaDigitacao(this,"+lTemDesbobrar+");";
               aLinha[j]  += " js_salvarPPa("+iCodigo+","+iAno+",this.value,\""+iEstrutural+"\","+lTemDesbobrar+",\""+lDeducao+"\",\""+iConcarPeculiar+"\",true)'";
               aLinha[j]  += " onkeyDown='js_verifica(this,event,"+lTemDesbobrar+")' "
               if (lDeducao == "t") {
                 aLinha[j]  += "onKeyPress=\"return js_mask(event,'0-9|.|-')\"";
               } else {
                 aLinha[j]  += "onKeyPress=\"return js_mask(event,'0-9|.')\"";
               }
             }
           }

           aLinha[j]  += "  value='"+nValor+"' id='ano"+iAno+"cta"+iEstrutural+"cp"+iConcarPeculiar+"'";
           aLinha[j]  += "  >&nbsp;";

         } else {

           aLinha[j]   = "<span class='basecalculo"+iEstrutural+"'>"+js_formatar(aBaseCalculo[iAno],'f')+"</span>";
         }

         nValorMedia += new Number(aBaseCalculo[iAno]);
         if (aBaseCalculo[iAno] != 0) {
           iTotMedias++;
         }
         j++;
      }

      var nMediaBase = 0;
      if (nMediaBase == 0) {

        if (iTotMedias > 0) {
          nMediaBase = js_round((nValorMedia)/4,2);
        }

      }
      if (iTipo == 1) {

       aLinha[9]  = "<b><span id='media"+iEstrutural+"'>"+js_formatar(nMediaBase, "f")+"</span></b>";
       var j      = 10;

      } else {

        aLinha[6]  = "<b><span id='media"+iEstrutural+"'>"+js_formatar(nMediaBase, "f")+"</span></b>";
        var j      = 7;

      }
      /**
       * Criamos inputs para as estimativas do ppa
       */
      for (var iAno = iAnoInicio; iAno <= iAnoFinal;iAno++) {

        iCodigo = (iTipo == 2) ? aCodigoEstimativa[iAno] : iCodigo;

        var nValor  =  js_formatar(aEstimativas[iAno],'f');
        var sStyle  = '';

        if (iReduz == "") {
          sStyle = 'font-weight:bold;';
        }

        aLinha[j]   = "<input readonly type='text' style='width:100%;text-align:right;border:1px solid white;"+sStyle+"'";
        if ( lReadOnly) {

         if ((iReduz != "" && !lDesdobra && iCodigo != "") || aDesdobramentos.length > 0) {

            aLinha[j]  += " onfocus='js_liberaDigitacao(this)' onblur='js_bloqueiaDigitacao(this,"+lTemDesbobrar+");";
            aLinha[j]  += " js_salvarPPa("+iCodigo+","+iAno+",this.value,\""+iEstrutural+"\","+lTemDesbobrar+",\""+lDeducao+"\",\""+iConcarPeculiar+"\", false)'";
            aLinha[j]  += " onkeyDown='js_verifica(this,event,"+lTemDesbobrar+")' "

            if (lDeducao == "t") {
              aLinha[j]  += "onKeyPress=\"return js_mask(event,'0-9|.|-')\"";
            } else {
              aLinha[j]  += "onKeyPress=\"return js_mask(event,'0-9|.')\"";
            }
          }
        }
        aLinha[j]  += "  value='"+nValor+"' id='ano"+iAno+"cta"+iEstrutural+"cp"+iConcarPeculiar+"'";
        aLinha[j]  += "  >&nbsp;";
        j++;

      }

      gridPPA.addRow(aLinha);

      if (iTipo == 1) {
        gridPPA.aRows[i].aCells[9].sStyle="background-color:#CAE0FF";
      } else {
        gridPPA.aRows[i].aCells[6].sStyle="background-color:#CAE0FF";
      }

      if (iReduz == "") {
        gridPPA.aRows[i].sStyle = "font-weight:bold";
      }
    }
  }
  gridPPA.renderRows();
}

/**
 * Libera  o input passado como parametro para a digitacao.
 * é Retirado a mascara do valor e liberado para Edição
 * é Colocado a Variavel nValorObjeto no escopo GLOBAL
 */
function js_liberaDigitacao(object) {

  nValorObjeto        = object.value;
  object.value        = js_strToFloat(object.value).valueOf();
  object.style.border = '1px solid black';
  object.readOnly     = false;
  object.style.fontWeight = "bold";
  object.select();

}

/**
 * bloqueia  o input passado como parametro para a digitacao.
 * É colocado  a mascara do valor e bloqueado para Edição
 */
function js_bloqueiaDigitacao(object, iBold) {

  object.readOnly         = true;
  object.style.border     ='0px';
  object.style.fontWeight = "normal";
  if (iBold) {
    object.style.fontWeight = "bold";
  }
  object.value            = js_formatar(object.value,'f');

}
/**
 * Verifica se  o usuário cancelou a digitação dos valores.
 * Caso foi cancelado, voltamos ao valor do objeto, e
 * bloqueamos a digitação
 */
function js_verifica(object,event,iBold) {

  var teclaPressionada = event.which;
  if (teclaPressionada == 27) {
      object.value = nValorObjeto;
     js_bloqueiaDigitacao(object,iBold);
  }
}

/**
 * Salva a informacao da estimativa informada
 */
function js_salvarPPa(iCodigo, iAno, nValor,iEstrutural, lDesdobrar, lDeducao, iConcarPeculiar, lBase) {

  nValor = js_strToFloat(nValor).valueOf();
  if (nValor == js_strToFloat(nValorObjeto)) {
    return ;
  }

  var oParam                  = new Object();
  oParam.exec                 = "saveEstimativa";
  oParam.iCodCon              = iCodigo;
  oParam.iAno                 = iAno;
  oParam.lDesdobrar           = lDesdobrar;
  oParam.lDeducao             = lDeducao;
  oParam.iEstrutural          = iEstrutural;
  oParam.nValor               = nValor;
  oParam.lBase                = lBase;
  oParam.iConcarPeculiar      = iConcarPeculiar;
  oParam.nValorOriginal       = js_strToFloat(nValorObjeto).valueOf();
  oParam.iTipo                = <?=$oGet->iTipo ?>;
  oParam.iCodigoLei           = <?=$_GET["o01_sequencial"] ?>;
  oParam.iCodigoVersao        = <?=$_GET["o05_ppaversao"] ?>;
  oParam.lAtualizaEstimativas = false;
  /**
   * Caso for base de calculo, devemos informar o usuário, e alterar os valores das medias de calculo
   */
  if (lBase) {

    var nValorBase = new Number(0);
    var aItens = gridPPA.getElementsByClass("basecalculo"+iEstrutural);
    aItens.each(function(oCell, Id){
       nValorBase += js_strToFloat(oCell.innerHTML);
    });
    nValorBase += nValor;
    nValorBase = js_round((nValorBase/4),2);

    $("media"+iEstrutural).innerHTML = js_formatar(nValorBase,"f");
    if ($('notificar').checked) {

      var sMsg  = "Você acaba de alterar a média de arrecadações desta receita e isto pode refletir em ";
          sMsg += "mudanças nas projeções do PPA.\n";
          sMsg += "Deseja reprocessar as projeções desta Receita ?";
      if (confirm(sMsg)) {
         oParam.lAtualizaEstimativas = true;
      }

    }
  }
  var oAjax   = new Ajax.Request(
                         sUrlRPC,
                         {
                          method    : 'post',
                          parameters: 'json='+js_objectToJson(oParam),
                          onComplete: js_retornoSalvar
                          }
                        );

}

function js_retornoSalvar(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 2) {
    alert(oRetorno.message.urlDecode());
  } else {

    atualizaValores(oRetorno.iAno, oRetorno.itens, oRetorno.valor,
                    oRetorno.nValorOriginal,oRetorno.lDeducao, oRetorno.lBase
                    );

    if (oRetorno.lAtualizaEstimativas) {
      js_reprocessaValor(oRetorno.iCodCon, oRetorno.iConcarPeculiar);
    }

  }
}
/**
 * Abre uma lookup com as informações para o reprocessamento dos saldos
 * da conta informada
 */
function js_reprocessaValor(iCodCon, iConcarPeculiar) {

  var iCodigoLei  = $F('o01_sequencial');
  js_OpenJanelaIframe('',
                       'db_iframe_reprocppaestimativa',
                       'orc4_reprocessaestimativa.php?o01_sequencial='+iCodigoLei+'&iCodCon='+iCodCon+
                       "&iTipo=<?=$oGet->iTipo ?>&o05_ppaversao=<?=$oGet->o05_ppaversao ?>&iConcarPeculiar="+iConcarPeculiar,
                       'Reprocessamento das estimativas',
                       true,
                       ((screen.availHeight-700)/2),
                       ((screen.availWidth-500)/2),
                       650,
                       350);

}

/**
 * Realiza a atualizacao dos valores das contas com desdobramento e contas analiticas na grid.
 * faz a atualizacao conforme contas passadas no parametro aContas;
 */
function atualizaValores(iAno, aContas, valorAdicionar,nValorOriginal, lDeducao, lBase) {

  for (var i = 0;i < aContas.length;i++) {

    //aContas[i].iConcarPeculiar = 0;
    if (document.getElementById('ano'+iAno+'cta'+aContas[i].iEstrutural+"cp"+aContas[i].iConcarPeculiar)) {
      var valor = js_strToFloat($('ano'+iAno+'cta'+aContas[i].iEstrutural+"cp"+aContas[i].iConcarPeculiar).value);

      var nValorBase = 0;
      if (lBase) {
        var aItens = gridPPA.getElementsByClass("basecalculo"+aContas[i].iEstrutural);
        aItens.each(function(oCell, Id){
            nValorBase += js_strToFloat(oCell.innerHTML);
         });
      }
      if (aContas[i].valor == 0) {

        if (valor <  nValorOriginal && lDeducao == "f") {
          valor  = 0;
        } else {

          valor -= new Number(nValorOriginal);
          valor += new Number(valorAdicionar);

        }

      } else {
        valor = aContas[i].valor;
      }
      $('ano'+iAno+'cta'+aContas[i].iEstrutural+"cp"+aContas[i].iConcarPeculiar).value = js_formatar(valor,'f');
      nValorBase  += valor;

      if (lBase) {
         nValorBase = js_round((nValorBase/4),2);
         $("media"+aContas[i].iEstrutural).innerHTML = js_formatar(nValorBase,"f");
      }
    }
  }
}

/**
 * Abre uma loopkup com a pesquisa dos saldos da Dotacao do Ano corrente
 */
function js_mostraSaldo(chave){

  arq = 'func_saldoorcdotacao.php?o58_coddot='+chave
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_saldos',arq,'Saldo da dotação',true);

}
</script>