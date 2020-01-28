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
require_once(modification("classes/db_ppaestimativareceita_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
$clppaestimativa = new cl_ppaestimativa();
$clppaestimativareceita = new cl_ppaestimativareceita();
$oPost           = db_utils::postMemory($_POST);
$clppaestimativa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o01_descricao");
$clrotulo->label("o01_anoinicio");
$clrotulo->label("o01_anofinal");
$clrotulo->label("o01_descricao");
$clrotulo->label("o01_sequencial");
$clrotulo->label("o01_numerolei");
$db_opcao = 1;
$lProcessaManual = true;
if (isset($oPost->o05_ppalei) && $oPost->o05_ppalei != "") {

  $oDaoPPALei = db_utils::getDao("ppalei");
  $sSqlLei    = $oDaoPPALei->sql_query($oPost->o05_ppalei);
  $rsLei      = $oDaoPPALei->sql_record($sSqlLei);
  if ($oDaoPPALei->numrows > 0) {

     $oLei          = db_utils::fieldsMemory($rsLei, 0);
     $o01_anoinicio = $oLei->o01_anoinicio;
     $o01_anofinal  = $oLei->o01_anofinal;
     $o01_descricao = $oLei->o01_descricao;
     $o01_numerolei = $oLei->o01_numerolei;

  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/ppaUserInterface.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <center>
  <form name='form1' method='post'>
  <table>
    <tr>
      <td>
        <fieldset>
          <legend>
            <b>Estimativas de Receita</b>
          </legend>
          <table>
            <tr>
              <td nowrap title="<?=@$To05_ppalei?>">
                <?
                db_ancora("<b>Lei do PPA</b>","js_pesquisao05_ppalei(true);",$db_opcao);
                ?>
              </td>
              <td>
                <?
                db_input('o05_ppalei',10,$Io01_sequencial,true,'text',$db_opcao," onchange='js_pesquisao05_ppalei(false);'")
                ?>
                <?
                db_input('o01_descricao',40,$Io01_descricao,true,'text',3,'')
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$To05_ppaversao?>">
                <b>Perspectiva:</b>
              </td>
              <td id='verppa'>

              </td>
            </tr>
              <tr>
                <td nowrap title="<?=@$To01_anoinicio?>">
                 <?=@$Lo01_anoinicio?>
                </td>
                <td>
                <?
                  db_input('o01_anoinicio',10,$Io01_anoinicio,true,'text',3,"")
                ?>
               </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$To01_anofinal?>">
                <?=@$Lo01_anofinal?>
              </td>
              <td>
                <?
                  db_input('o01_anofinal',10,$Io01_anofinal,true,'text',3,"")
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$To01_numerolei?>">
                 <?=@$Lo01_numerolei?>
              </td>
              <td>
                <?
                  db_input('o01_numerolei',10,$Io01_numerolei,true,'text',3,"")
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td colspan='2' align="center">
        <input name="processar" type="button" id="processar" value="Processar"
               onclick='js_processaPPAReceita()'>
         <input name="resprocessar" type="button" id="reprocessar" disabled value="Reprocessar Receita"
               onclick='js_reprocessaValor()'>
      </td>
    </tr>
  </table>
  </form>
  </center>
</body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
sUrlRPC      = 'orc4_ppaRPC.php';
iProcessado  = 0;

function js_pesquisao05_ppalei(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_ppalei',
                        'func_ppalei.php?funcao_js=parent.js_mostrappalei1|o01_sequencial|o01_descricao',
                        'Pesquisa de Leis para o PPA',
                        true,
                        25,
                       0
                       );
  }else{
     if(document.form1.o05_ppalei.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo',
                            'db_iframe_ppalei',
                            'func_ppalei.php?pesquisa_chave='
                            +document.form1.o05_ppalei.value+'&funcao_js=parent.js_mostrappalei',
                            'Leis PPA',
                            false,
                            25,
                            0
                            );
     }else{
       document.form1.o01_descricao.value = '';
     }
  }
}
function js_mostrappalei(chave, erro) {

  document.form1.o01_descricao.value = chave;
  if(erro==true){
    document.form1.o05_ppalei.focus();
    document.form1.o05_ppalei.value = '';
    js_limpaComboBoxPerspectivaPPA();
  } else {
    document.form1.submit();
  }

}
function js_mostrappalei1(chave1,chave2){

  document.form1.o05_ppalei.value = chave1;
  document.form1.o01_descricao.value = chave2;
  db_iframe_ppalei.hide();
  document.form1.submit();

}

function js_processaPPAReceita() {

  var iCodigoVersao = $F('o05_ppaversao');
  if (iCodigoVersao == "0") {

    alert('Selecione uma versão do PPA');
    return false;

  }
  if (!iProcessado) {
     iProcessado = $('o05_ppaversao').options[$('o05_ppaversao').selectedIndex].processadoreceita;
  }
  if (iProcessado == 1) {

    js_ppaEstimativaManual();
    return false;

  }
  var iCodigoLei = $('o05_ppalei');
  if (iCodigoLei == "") {

    alert('Informe o código da lei!');
    return false;

  }
  if (!confirm('Confirma o Processamento da estimativa das Receitas?')){
    return false;
  }
  /**
   *Verificamos se o usuário deseja importar as projeções de outro periodo.
   */
  var oParam                  = new Object();
  oParam.iCodigoLei          = $F('o05_ppalei');
  oParam.iCodigoVersao       = $F('o05_ppaversao');
  oParam.iAnoInicio          = $F('o01_anoinicio');
  oParam.iAnoFim             = $F('o01_anofinal');
  oParam.exec                = "ProcessaEstimativa";
  oParam.iTipo               = 1;
  oParam.aParametros         = new Array();
  oParam.lProcessaBase       = true;
  oParam.lProcessaEstimativa = true;
  oParam.lImportar           = false;
  oParam.iPerspectiva        = '';
  var sMsg  = 'Deseja importar as projeções de outro Período de Referência?\n';
      sMsg += ' [Ok] para escolher o período\n';
      sMsg += ' [Cancelar] para processar as projeções normalmente.';

  if (confirm(sMsg)) {
     js_selecionaPeriodoPPa(oParam);
  } else {

    js_divCarregando("Aguarde, Processando PPA..","msgbox");
    var oAjax   = new Ajax.Request(
                           sUrlRPC,
                           {
                            method    : 'post',
                            parameters: 'json='+js_objectToJson(oParam),
                            onComplete: js_retornoProcessamento
                            }
                          );
  }
}

function js_retornoProcessamento (oAjax) {

  js_removeObj("msgbox");
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 2) {
    alert(oRetorno.message.urlDecode());
  } else {

    alert('Processamento efetuado com sucesso.');
    iProcessado = 1;
    js_ppaEstimativaManual();

  }
}

function js_ppaEstimativaManual() {

   js_OpenJanelaIframe('CurrentWindow.corpo',
                       'db_iframe_ppaestimativa',
                       'orc4_ppaestimativasmanual001.php?o01_sequencial='+$F('o05_ppalei')+"&iTipo=1&"+
                       "o05_ppaversao="+$F('o05_ppaversao'),
                       'Quadro de Estimativas PPA - '+$F('o01_anoinicio')+'/'+$F('o01_anofinal'),
                       true,
                       25,
                       0,
                       document.body.getWidth()-10,
                       document.body.scrollHeight-25);

}
function js_reprocessarReceita() {

  var iCodigoVersao = $F('o05_ppaversao');
  if (iCodigoVersao == "0") {

    alert('Selecione uma versão do PPA');
    return false;

  }
  var sMsg = "Essa Rotina ira reprocessar todas as estimativas da Receita,\n";
  sMsg    += "Todas as informações cadastradas com base nas receitas já existentes\n";
  sMsg    += "no ano corrente, serão recalculadas.\nProsseguir?";
  if (confirm(sMsg)) {

    js_divCarregando("Aguarde, Reprocessando PPA..","msgbox");
    var oParam              = new Object();
    oParam.iCodigoLei       = $F('o05_ppalei');
    oParam.iCodigoVersao    = $F('o05_ppaversao');
    oParam.iAnoInicio       = $F('o01_anoinicio');
    oParam.iAnoFim          = $F('o01_anofinal');
    oParam.exec             = "reProcessaEstimativaGlobal";
    oParam.iTipo            = 1;
    oParam.aParametros      = new Array();
    var oAjax   = new Ajax.Request(
                           sUrlRPC,
                           {
                            method    : 'post',
                            parameters: 'json='+js_objectToJson(oParam),
                            onComplete: js_retornoProcessamento
                            }
                          );
  }
}
function js_reprocessaValor() {

  var iCodigoLei    = $F('o05_ppalei');
  var iCodigoVersao = $F('o05_ppaversao');
  if (iCodigoVersao == "0") {

    alert('Selecione uma versão do PPA');
    return false;

  }
  js_OpenJanelaIframe('',
                       'db_iframe_reprocessa',
                       'orc4_ppareprocessamentogeral.php?o01_sequencial='+iCodigoLei+
                       "&iTipo=1&o05_ppaversao="+iCodigoVersao ,

                       'Reprocessamento Geral das Receitas',
                       true,
                       ((screen.availHeight-700)/2),
                       ((screen.availWidth-500)/2),
                       650,
                       350);



}
<?
if ($lProcessaManual) {
  echo "$('reprocessar').disabled = false\n";
}
?>
function bloqueiaPPA(lValido, message) {

  if (!lValido) {

    alert(message);
    $('reprocessar').disabled = true;
  } else {

    $('reprocessar').disabled = false;
  }

}
js_drawSelectVersaoPPA($('verppa'));
<?
 if (isset($oPost->o05_ppalei) && $oPost->o05_ppalei != "") {
   echo "js_getVersoesPPA({$oPost->o05_ppalei});\n";
   echo "js_validaLeiPPAPeriodo({$oPost->o05_ppalei}, bloqueiaPPA);\n";
 }
?>

function js_importarDadosPPA(oParam) {

  if ($F('cboperspectivas') == '0') {
    alert('Escolha uma perspectiva para continuar.');
  }
  oParam.lImportar    = true;
  oParam.iPerspectiva = $F('cboperspectivas');
  wndPeriodosPPA.destroy();
  js_divCarregando("Aguarde, Processando PPA..","msgbox");
  var oAjax   = new Ajax.Request(
                           sUrlRPC,
                           {
                            method    : 'post',
                            parameters: 'json='+js_objectToJson(oParam),
                            onComplete: js_retornoProcessamento
                            }
                          );

}
</script>