<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_ppaestimativa_classe.php");
require_once("classes/db_ppaestimativadespesa_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_liborcamento.php");
$clppaestimativa        = new   cl_ppaestimativa();
$clppaestimativadespesa = new cl_ppaestimativadespesa();
$oPost           = db_utils::postMemory($_POST);
$clppaestimativa->rotulo->label();
$clrotulo = new rotulocampo;
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
            <b>Estimativas de Despesa</b>
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
               onclick='js_processaPPA()'>
         <input name="resprocessar" type="button" id="reprocessar" disabled value="Reprocessar Despesa"
               onclick='js_reprocessarDespesa()'>
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
sUrlRPC       = 'orc4_ppaRPC.php';
iProcessado   = 0;

function js_pesquisao05_ppalei(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_ppalei',
                        'func_ppalei.php?funcao_js=parent.js_mostrappalei1|o01_sequencial|o01_descricao',
                        'Pesquisa de Leis para o PPA',
                        true,24,
                        0,
                        document.body.getWidth()-10,
                        document.body.scrollHeight-25);
  }else{
     if(document.form1.o05_ppalei.value != ''){
        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_ppalei',
                            'func_ppalei.php?pesquisa_chave='
                            +document.form1.o05_ppalei.value+'&funcao_js=parent.js_mostrappalei',
                            'Leis PPA',
                            false,
                            24,
                            0,
                            document.body.getWidth()-10,
                            document.body.scrollHeight-25);
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

function js_processaPPA() {

  var iCodigoVersao = $F('o05_ppaversao');
  if (iCodigoVersao == "0") {

    alert('Selecione uma vers�o do PPA');
    return false;

  }
  if (iProcessado == 0) {
     iProcessado = $('o05_ppaversao').options[$('o05_ppaversao').selectedIndex].processadodespesa;
  }
  if (iProcessado == 1) {

    js_ppaEstimativaManual();
    return false;

  }

  var iCodigoLei = $('o05_ppalei');
  if (iCodigoLei == "") {

    alert('Informe o c�digo da lei!');
    return false;

  }
  if (!confirm('Confirma o Processamento da estimativa das Despesas?')){
    return false;
  }
  var oParam                 = new Object();
  oParam.iCodigoLei          = $F('o05_ppalei');
  oParam.iAnoInicio          = $F('o01_anoinicio');
  oParam.iCodigoVersao       = $F('o05_ppaversao');
  oParam.iAnoFim             = $F('o01_anofinal');
  oParam.iTipo               = 2;
  oParam.exec                = "ProcessaEstimativa";
  oParam.lProcessaBase       = true;
  oParam.lProcessaEstimativa = true;
  oParam.lImportar           = false;
  oParam.iPerspectiva        = '';
  oParam.aParametros         = new Array();
  $('processar').disabled    = true;
  $('reprocessar').disabled  = true;
  var sMsg  = 'Deseja importar as proje��es de outro Per�odo de Refer�ncia?\n';
      sMsg += ' [Ok] para escolher o per�odo\n';
      sMsg += ' [Cancelar] para processar as proje��es normalmente.';

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

    alert('Processamento Efetuado com sucesso');
    iProcessado               = 1;
    $('processar').disabled   = true;
    $('reprocessar').disabled = true;
    js_ppaEstimativaManual();

  }
}

function js_objectToJson(oObject) {

   var sJson = oObject.toSource();
   sJson     = sJson.replace("(","");
   sJson     = sJson.replace(")","");
   return sJson;

}

function js_ppaEstimativaManual() {

   js_OpenJanelaIframe('top.corpo',
                       'db_iframe_ppaestimativa',
                       'orc4_ppaestimativasmanual001.php?o01_sequencial='+$F('o05_ppalei')+'&iTipo=2&'+
                       "o05_ppaversao="+$F('o05_ppaversao'),
                       'Quadro de Estimativas de Despesa PPA - '+$F('o01_anoinicio')+'/'+$F('o01_anofinal'),
                       true,
                       24,
                       0,
                       document.body.getWidth()-10,
                       document.body.scrollHeight-25);

}

function js_reprocessarDespesa() {

  var iCodigoLei  = $F('o05_ppalei');
  js_OpenJanelaIframe('',
                       'db_iframe_reprocessa',
                       'orc4_ppareprocessamentogeral.php?o01_sequencial='+iCodigoLei+
                       "&o05_ppaversao="+$F('o05_ppaversao')+
                       "&iTipo=2",
                       'Reprocessamento Geral das Despesas',
                       true,
                       ((screen.availHeight-700)/2),
                       ((screen.availWidth-500)/2),
                       650,
                       350);



}

<?
if ($lProcessaManual) {
  echo "$('reprocessar').disabled = false;";
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
   echo "js_getVersoesPPA({$oPost->o05_ppalei})\n";
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