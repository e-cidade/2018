<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

<br><br>
<center>
  <form name="form1" method="post" action="">
    <table width="70%">
      <tr>
        <td align="center">
          <fieldset style="width:70%"><legend><b>Período:</b></legend>
            <table  border="0"  align="center" width="100%">
              <tr>
                <td align="left" style="padding-bottom: 2px;" width="2%" nowrap> 
                  <b>Início:</b>
                </td>
                <td>
                  <?
                  $dData       = date('d/m/Y', db_getsession('DB_datausu'));
                  $aData       = explode('/', $dData);
                  $dataini_dia = $aData[0];
                  $dataini_mes = $aData[1];
                  $dataini_ano = $aData[2];
                  db_inputdata('dataini', @$dataini_dia, @$dataini_mes, @$dataini_ano, true, 'text', 1, '');
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <b>Fim:</b>
                </td>
                <td>
                  <?
                  $datafim_dia = $aData[0];
                  $datafim_mes = $aData[1];
                  $datafim_ano = $aData[2];
                  db_inputdata('datafim', @$datafim_dia, @$datafim_mes, @$datafim_ano, true, 'text', 1, '');
                  ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>

      <tr>
        <td align="center">
          <fieldset style="width:70%"><legend align="left"><b>Unidades</b></legend>
            <table  border="0"  align="center" width="100%">
              <tr>
                <td nowrap width="50%" valign="top" align="right">
                  <select multiple id="unidadesEsq" name="unidadesEsq" style=" width: 100%;" size="10" 
                    onDblClick="js_moverOption($('unidadesEsq'), $('unidadesDir'));">
                </td>
                <td nowrap align="center">
                  <input type="button" onclick="js_moverOption($('unidadesEsq'), $('unidadesDir'));" value=">">
                  <br><br>
                  <input type="button" 
                    onclick="js_selecionarTudo($('unidadesEsq')); js_moverOption($('unidadesEsq'), $('unidadesDir'));"
                    value=">>">
                  <br><br>
                  <input type="button" 
                    onclick="js_selecionarTudo($('unidadesDir')); js_moverOption($('unidadesDir'), $('unidadesEsq'));"
                    value="<<">
                  <br><br>
                  <input type="button" onclick="js_moverOption($('unidadesDir'), $('unidadesEsq'));" value="<">
                </td>
                <td nowrap width="50%" valign="top">
                  <select multiple id="unidadesDir" name="unidadesDir[]" style=" width: 100%;" size="10"
                    onDblClick="js_moverOption($('unidadesDir'), $('unidadesEsq'));">
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>

      <tr>
        <td align="center">
          <fieldset style="width:70%"><legend align="left"><b>Situações</b></legend>
            <table  border="0"  align="center" width="100%">
              <tr>
                <td nowrap width="50%" valign="top" align="right">
                  <select multiple id="situacoesEsq" name="situacoesEsq" style=" width: 100%;" size="10" 
                    onDblClick="js_moverOption($('situacoesEsq'), $('situacoesDir'));">
                </td>
                <td nowrap align="center">
                  <input type="button" onclick="js_moverOption($('situacoesEsq'), $('situacoesDir'));" value=">">
                  <br><br>
                  <input type="button" 
                    onclick="js_selecionarTudo($('situacoesEsq')); 
                    js_moverOption($('situacoesEsq'), $('situacoesDir'));" value=">>">
                  <br><br>
                  <input type="button" 
                    onclick="js_selecionarTudo($('situacoesDir')); 
                    js_moverOption($('situacoesDir'), $('situacoesEsq'));" value="<<">
                  <br><br>
                  <input type="button" onclick="js_moverOption($('situacoesDir'), $('situacoesEsq'));" value="<">
                </td>
                <td nowrap width="50%" valign="top">
                  <select multiple id="situacoesDir" name="situacoesDir[]" style=" width: 100%;" size="10"
                    onDblClick="js_moverOption($('situacoesDir'), $('situacoesEsq'));">
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>

      <tr>
        <td align="center">
          <table width="70%" border="0">
            <tr>
              <td width="50%" align="center">
                <input name="relatorio" id="relatorio" type="button" value="Relatório" onclick="js_mandaDados();">
              </td>
              <td width="50%" align="center">
                <input name="limpar" id="limpar" type="button" value="Limpar" onclick="js_limpar();">
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

  </form>
</center>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), 
        db_getsession("DB_anousu"), db_getsession("DB_instit")
       );
?>

</body>
</html>
<script>

js_getUnidadesMedicos();
js_getSituacoesAnulacaoAgendamento();

function js_ajax(oParam, jsRetorno, sUrl, lAsync) {

  var mRetornoAjax;

  if (sUrl == undefined) {
    sUrl = 'sau4_agendamento.RPC.php';
  }

  if (lAsync == undefined) {
    lAsync = true;
  }
	
  var oAjax = new Ajax.Request(sUrl, 
                               {
                                 method: 'post', 
                                 asynchronous: lAsync,
                                 parameters: 'json='+Object.toJSON(oParam),
                                 onComplete: function(oAjax) {
                                    
                                               var evlJS    = jsRetorno+'(oAjax);';
                                               return mRetornoAjax = eval(evlJS);
                                               
                                           }
                              }
                             );

  return mRetornoAjax;

}

function js_limpar() {

  $('dataini').value             = '';
  $('datafim').value             = '';
  js_getUnidadesMedicos();
  js_getSituacoesAnulacaoAgendamento();

}

/*** funções dos selects  */
function js_esvaziaSelect(oSel) {

  while (oSel.length != 0) {
    oSel.options[0] = null;
  }

}

function js_coloreSelect(oSel) {

  for (var iCont = 0; iCont < oSel.options.length; iCont++) {

    if (iCont % 2 == 0) {
      sColor = 'rgb(248, 236, 7)';
    } else {
      sColor = 'rgb(215, 204, 6)';
    }

    oSel.options[iCont].style.backgroundColor = sColor;

  }

}

function js_selecionarTudo(oSel) {

  for (iCont = 0; iCont < oSel.length; iCont++) {
    
    oSel.options[iCont].selected = true;

  }

  return true;

}

function js_moverOption(oSelOrig, oSelDest) {

  var iCont;

  for (iCont = 0; iCont < oSelOrig.length; iCont++) {

    if (oSelOrig.options[iCont].selected) {
  
      oSelDest.options[oSelDest.length] = oSelOrig.options[iCont];
      iCont--; // Diminuo iCont pq o tamanho de oSelOrig diminui, já que o option foi movido

    }

  }

  js_coloreSelect(oSelOrig);
  js_coloreSelect(oSelDest);

}

function js_getUnidadesMedicos() {

  js_esvaziaSelect($('unidadesEsq'));
  js_esvaziaSelect($('unidadesDir'));

  var oParam   = new Object();
	oParam.exec  = 'getUnidadesMedicos';

  js_ajax(oParam, 'js_retornoGetUnidadesMedicos', 'sau4_ambulatorial.RPC.php');

}

function js_retornoGetUnidadesMedicos(oRetorno) {
  
  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {

    alert(oRetorno.sMessage.urlDecode());
    return false;

  } else {

    var iTam   = oRetorno.aUnidades.length;
    oSel       = $('unidadesEsq');

    for (iCont = 0; iCont < iTam; iCont++) {

      oSel.options[iCont] = new Option(oRetorno.aUnidades[iCont].iCodigo + ' - ' +
                                       oRetorno.aUnidades[iCont].sDescr.urlDecode(),
                                       oRetorno.aUnidades[iCont].iCodigo
                                      );
    }
    js_coloreSelect(oSel);

  }

}

function js_getSituacoesAnulacaoAgendamento() {

  js_esvaziaSelect($('situacoesEsq'));
  js_esvaziaSelect($('situacoesDir'));
/*
  var oParam   = new Object();
	oParam.exec  = 'getUnidadesMedicos';

  js_ajax(oParam, 'js_retornoGetUnidadesMedicos', 'sau4_ambulatorial.RPC.php');
*/

  var oRetorno                   = new Object();
  oRetorno.iStatus               = 1;
  oRetorno.sMessage              = '';
  oRetorno.aSituacoes            = new Array();
  oRetorno.aSituacoes[0]         = new Object();
  oRetorno.aSituacoes[0].iCodigo = 1;
  oRetorno.aSituacoes[0].sDescr  = 'Cancelado';
  oRetorno.aSituacoes[1]         = new Object();
  oRetorno.aSituacoes[1].iCodigo = 2;
  oRetorno.aSituacoes[1].sDescr  = 'Faltou';
  oRetorno.aSituacoes[2]         = new Object();
  oRetorno.aSituacoes[2].iCodigo = 3;
  oRetorno.aSituacoes[2].sDescr  = 'Outros';

  js_retornoGetSituacoesAnulacaoAgendamento(oRetorno);

}

function js_retornoGetSituacoesAnulacaoAgendamento(oRetorno) {
  
  // oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {

    alert(oRetorno.sMessage.urlDecode());
    return false;

  } else {

    var iTam   = oRetorno.aSituacoes.length;
    oSel       = $('situacoesEsq');

    for (iCont = 0; iCont < iTam; iCont++) {

      oSel.options[iCont] = new Option(oRetorno.aSituacoes[iCont].iCodigo + ' - ' +
                                       oRetorno.aSituacoes[iCont].sDescr.urlDecode(),
                                       oRetorno.aSituacoes[iCont].iCodigo
                                      );
    }
    js_coloreSelect(oSel);

  }

}

/* funções do select das unidades  ***/

function js_validaEnvio() {

  if (document.form1.dataini.value == '' || document.form1.datafim.value == '') {

    alert('Informe o período.');
    return false;

  }

  aIni = document.form1.dataini.value.split('/');
  aFim = document.form1.datafim.value.split('/');
  dIni = new Date(aIni[2], aIni[1], aIni[0]);
  dFim = new Date(aFim[2], aFim[1], aFim[0]);

  if (dFim < dIni) {
  			
    alert('Data final não pode ser menor que a data inicial.');
    document.form1.datafim.value = '';
    document.form1.datafim.focus();
    return false;
  
  }

  if ($('unidadesDir').length == 0) {

    alert('Selecione pelo menos uma unidade.');
    return false;

	}

  if ($('situacoesDir').length == 0) {

    alert('Selecione pelo menos uma situação.');
    return false;

	}

  return true;						

}

function js_mandaDados() {
 
  if (js_validaEnvio()) {

    var sVir              = '';
    var sUnidades         = '&sUnidades=';
    var sSituacoes        = '&sSituacoes=';
    var sDatas            = '&sDatas='+$F('dataini')+','+$F('datafim');
    var oSelUnidades      = $('unidadesDir');
    var oSelSituacoes     = $('situacoesDir');
    var sChave            = ''

    for (iCont = 0; iCont < oSelUnidades.length; iCont++) {

      sUnidades += sVir + oSelUnidades.options[iCont].value;
      sVir       = ',';

    }

    sVir = '';
    for (iCont = 0; iCont < oSelSituacoes.length; iCont++) {

      sSituacoes += sVir + oSelSituacoes.options[iCont].value;
      sVir        = ',';

    }

    sGet = sUnidades+sDatas+sSituacoes;
    oJan = window.open('sau2_agendamentosanulados002.php?'+sGet, '',
                       'width='+(screen.availWidth - 5)+',height='+(screen.availHeight - 40)+
                       ',scrollbars=1,location=0 '
                      );
    oJan.moveTo(0, 0);

  }

}

</script>