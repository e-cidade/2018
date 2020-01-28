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
          <fieldset style="width:70%"><legend align="left"><b>Validade</b></legend>
            <table  border="0"  align="center" width="100%">
              <tr>
                <td width="1%" align="left" style="padding-bottom: 2px;" nowrap> 
                  <b>Início:</b>
                </td>
                <td style="padding-bottom: 2px;" nowrap> 
                  <?
                  db_inputdata('dataini', @$dataini_dia, @$dataini_mes, @$dataini_ano, true, 'text', 1, '');
                  ?>
                </td>
              </tr>
              <tr>
                <td align="left" style="padding-bottom: 2px;" nowrap> 
                  <b>Fim:</b>
                </td>
                <td style="padding-bottom: 2px;" nowrap> 
                  <?
                  db_inputdata('datafim', @$datafim_dia, @$datafim_mes, @$datafim_ano, true, 'text', 1, '');
                  ?>
                </td>
              </tr>
              <tr>
                <td align="left" nowrap>
                  <b>Situação:</b>
                </td>
                <td nowrap>
                  <?
                  $aX = array('1' => 'ATIVO', '2' => 'INATIVO');
                  db_select('situacao', $aX, true, 1, '');
                  ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>

      <tr>
        <td align="center">
          <fieldset style="width:70%"><legend align="left"><b>Profissionais</b></legend>
            <table  border="0"  align="center" width="100%">
              <tr>
                <td width="15%" align="right">
                  <?
                  db_ancora('<b>Profissional:</b>', 'js_pesquisaProfissional(true);', '');
                  ?>
                </td>
                <td nowrap>
                  <?
                  db_input('iProfissional', 10, '', true, 'text', 1, 
                           'onchange="js_pesquisaProfissional(false);" '.
                           'onkeydown="return js_controla_tecla_enter(this, event);" '.
                           'onkeyup="js_ValidaCampos(this, 1, \'profissional\', \'t\', \'f\', event);" '.
                           'onblur="js_ValidaMaiusculo(this, \'f\', event);"'
                          );
                  db_input('z01_nome', 50, '', true, 'text', 3, '');
                  ?>
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="button" value="Lan&ccedil;ar" name="lancar_profissional" id="lancar_profissional">
                </td>
              </tr>
              <tr>
                <td>
                  &nbsp;
                </td>
                <td>
                 <select multiple size="8" name="select_profissional[]" id="select_profissional" style="width: 80%;" 
                   onDblClick="js_excluir_item_profissional();">
                 </select>
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
                    onDblClick="js_moveDireita();">
                </td>
                <td nowrap align="center">
                  <input type="button" onclick="js_moveDireita();" value=">">
                  <br><br>
                  <input type="button" onclick="js_selecionarTudo($('unidadesEsq')); js_moveDireita();" value=">>">
                  <br><br>
                  <input type="button" onclick="js_selecionarTudo($('unidadesDir')); js_moveEsquerda();" value="<<">
                  <br><br>
                  <input type="button" onclick="js_moveEsquerda();" value="<">
                </td>
                <td nowrap width="50%" valign="top">
                  <select multiple id="unidadesDir" name="unidadesDir[]" style=" width: 100%;" size="10"
                    onDblClick="js_moveEsquerda();">
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

function js_ajax(oParam, jsRetorno, sUrl) {

  if (sUrl == undefined) {
    sUrl = 'sau4_ambulatorial.RPC.php';
  }
	var objAjax = new Ajax.Request(
                         sUrl, 
                         {
                          method: 'post',
                          asynchronous: false,
                          parameters: 'json='+Object.toJSON(oParam),
                          onComplete: function(objAjax) {
                          			        var evlJS = jsRetorno+'(objAjax);';
                                        return eval(evlJS);
                          		        }
                         }
                        );

}

function js_limpar() {

  $('dataini').value          = '';
  $('datafim').value          = '';
  $('iProfissional').value    = '';
  $('z01_nome').value         = '';
  $('situacao').selectedIndex = 0;
  js_esvaziaSelect($('select_profissional'));
  js_getUnidadesMedicos();

}

/*** funções do select do profissional  */
function js_incluir_item_profissional() {

  var sTexto = document.form1.z01_nome.value;
  var sValor = document.form1.iProfissional.value;
  if (sTexto != '' && sValor != '') {

    var oSel              = document.getElementById('select_profissional');
    var iIndiceNovoOption = oSel.length;
    var lTesta            = false;
    var iCont             = 0;
    for (iCont = 0; iCont < oSel.length; iCont++) {

      if (oSel.options[iCont].value == sValor) {

        lTesta = true;
        break;

      }

    }

    if (lTesta == false) {

      oSel.options[iIndiceNovoOption] = new Option(sTexto, sValor);
      js_getUnidadesMedicos();
      for (iCont = 0; iCont < oSel.length; iCont++) {
        oSel.options[iCont].selected = false;
      }
      oSel.options[iIndiceNovoOption].selected = true;

    }

  }
  sTexto = document.form1.iProfissional.value = '';
  sValor = document.form1.z01_nome.value  = '';
  document.form1.lancar_profissional.onclick  = '';

}

function js_excluir_item_profissional() {

  var oSel = document.getElementById('select_profissional');
  if (oSel.length == 1) {
    oSel.options[0].selected = true;
  }
  var iSelInd = oSel.selectedIndex;
  if (oSel.selectedIndex != -1 && oSel.length > 0) {

    oSel.options[iSelInd] = null;
    js_getUnidadesMedicos();
    if (iSelInd <= (oSel.length - 1)) {
      oSel.options[iSelInd].selected = true;
    }

  }

}
/* funções do select do profissional  ***/


/*** funções do select das unidades  */
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
function js_getUnidadesMedicos() {

  js_esvaziaSelect($('unidadesEsq'));
  js_esvaziaSelect($('unidadesDir'));

  var oParam   = new Object();
  var oSel     = $('select_profissional');
  var sMedicos = '';
  var sSep     = '';
	oParam.exec  = 'getUnidadesMedicos';

  for (iCont = 0; iCont < oSel.length; iCont++) {

    sMedicos += sSep+oSel.options[iCont].value;
    sSep      = ', ';

  }
  
	oParam.sMedicos = sMedicos;

  js_ajax(oParam, 'js_retornoGetUnidadesMedicos');

}

function js_retornoGetUnidadesMedicos(oRetorno) {
  
  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {

    alert(oRetorno.sMessage.urlDecode());
    return false;

  } else {

    var iTam   = oRetorno.aUnidades.length;
    var sColor = '';
    oSel       = $('unidadesEsq');

    for (iCont = 0; iCont < iTam; iCont++) {

      oSel.options[iCont] = new Option(oRetorno.aUnidades[iCont].iCodigo + ' - ' +
                                       oRetorno.aUnidades[iCont].sDescr.urlDecode(),
                                       oRetorno.aUnidades[iCont].iCodigo
                                      );
      if (iCont % 2 == 0) {
        sColor = 'rgb(248, 236, 7)';
      } else {
        sColor = 'rgb(215, 204, 6)';
      }

      oSel.options[iCont].style.backgroundColor = sColor;

    }

  }

}

function js_selecionarTudo(oSel) {

  for (iCont = 0; iCont < oSel.length; iCont++) {
    
    oSel.options[iCont].selected = true;

  }

  return true;

}


function js_moveDireita() {

  var iCont;
  var oEsq = $('unidadesEsq');
  var oDir = $('unidadesDir');

  for (iCont = 0; iCont < oEsq.length; iCont++) {

    if (oEsq.options[iCont].selected) {
  
      oDir.options[oDir.length] = oEsq.options[iCont];
      iCont--;

    }

  }

  js_coloreSelect(oEsq);
  js_coloreSelect(oDir);

}

function js_moveEsquerda() {

  var iCont;
  var oEsq = $('unidadesEsq');
  var oDir = $('unidadesDir');

  for (iCont = 0; iCont < oDir.length; iCont++) {

    if (oDir.options[iCont].selected) {

      oEsq.options[oEsq.length] = oDir.options[iCont];
      iCont--;

    }

  }

  js_coloreSelect(oEsq);
  js_coloreSelect(oDir);

}

/* funções do select das unidades  ***/
  
function js_pesquisaProfissional(lMostra) {

  if (lMostra == true) {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_medicos', 'func_medicos.php?'+
                        'funcao_js=parent.js_mostraProfissional1|sd03_i_codigo|z01_nome', 
                        'Pesquisa', true
                       );

  } else {

    if (document.form1.iProfissional.value != '') { 

      js_OpenJanelaIframe('top.corpo', 'db_iframe_medicos', 'func_medicos.php?pesquisa_chave='+
                          document.form1.iProfissional.value+'&funcao_js=parent.js_mostraProfissional', 
                          'Pesquisa', false
                         );

    } else {
      document.form1.z01_nome.value = ''; 
    }

  }

}

function js_mostraProfissional(sChave, lErro) {

  document.form1.z01_nome.value = sChave; 
  if (lErro == true) {

    document.form1.iProfissional.focus(); 
    document.form1.iProfissional.value = '';

  } else {
    document.form1.lancar_profissional.onclick = js_incluir_item_profissional;
  }

}

function js_mostraProfissional1(sChave1, sChave2) {

  document.form1.iProfissional.value         = sChave1;
  document.form1.z01_nome.value              = sChave2;
  document.form1.lancar_profissional.onclick = js_incluir_item_profissional;
  db_iframe_medicos.hide();

}

function js_validaEnvio() {

  if (document.form1.dataini.value != '' && document.form1.datafim.value != '') {

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

  }

  if ($('unidadesDir').length == 0) {

    alert('Selecione pelo menos uma unidade.');
    return false;

	}

  return true;						

}

function js_mandaDados() {
 
  if (js_validaEnvio()) {

    var sVir           = '';
    var sProfissionais = 'sProfissionais=';
    var sUnidades      = '&sUnidades=';
    var sDatas         = '&sDatas='+$F('dataini')+','+$F('datafim');
    var iSituacao      = '&iSituacao='+$F('situacao');
    var oSelMedicos    = $('select_profissional');
    var oSelUnidades   = $('unidadesDir');
 
    for (iCont = 0; iCont < oSelMedicos.length; iCont++) {

      sProfissionais += sVir + oSelMedicos.options[iCont].value;
      sVir            = ',';

    }

    sVir = '';
    for (iCont = 0; iCont < oSelUnidades.length; iCont++) {

      sUnidades += sVir + oSelUnidades.options[iCont].value;
      sVir       = ',';

    }

    oJan = window.open('sau2_profissionaissaude002.php?'+sProfissionais+sUnidades+sDatas+iSituacao, '',
                       'width='+(screen.availWidth - 5)+',height='+(screen.availHeight - 40)+
                       ',scrollbars=1,location=0 '
                      );
    oJan.moveTo(0, 0);

  }

}

</script>