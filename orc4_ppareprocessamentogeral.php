<?php
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("std/db_stdClass.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_ppaestimativa_classe.php");
include("dbforms/db_funcoes.php");
$oGet           = db_utils::postMemory($_GET);
$oDaoPPalei     = db_utils::getDao("ppaversao");
$sSqlPPalei     = $oDaoPPalei->sql_query($oGet->o05_ppaversao);
$rsPpaLei       = $oDaoPPalei->sql_record($sSqlPPalei);
$oPPaLei        = db_utils::fieldsMemory($rsPpaLei, 0);
$sTextoTipo     = "Escolha o que deseja reprocessar";
if ($iTipo == 1) {
  $sTextoTipo    .= " de receita.";
} else {
  $sTextoTipo .= " de despesa."; 
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
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC onLoad="js_init()" style='margin:0'>
<center>
<form name='frmReprocessa'>
  <table width="100%" height="100%"; cellspacing="0" cellpadding="0">
    <tr>
      <td height="10%" align="center" valign='center' bgcolor="white" style=';border-bottom:2px groove white'>
      <b>  
     <?=$sTextoTipo?>
      </b> 
      </td>
    </tr>
    <tr>
      <td valign="top">
         <fieldset>
            <table width='100%' cellspacing="0" cellpadding="0">
             <tr>
               <td style='border-bottom:2px groove white;'><b>Reprocessar:</b></td>
             </tr>
             <tr>
              <td valign="top" style='padding:2px'> 
              <input type='checkbox' value='1' id='basecalculo'><label for='basecalculo'>Base de Cálculo</label><br>
              <input type='checkbox' value='2' id='estimativas'><label for='estimativas'>Estimativas</label><br>
             </td>
             </tr>  
            </table>
            <table>
              <tr>
              </tr>
            </table>
         </fieldset>
      </td>
    </tr>
    <tr>
       <td align="center" colspan="2">
         <input type='button' value='Reprocessar' id='reprocessar' onclick='js_reprocessa()'>
       </td>
    </tr>
  </table>
  </form>
</center>
</body>
</html>
<script>
sUrlRPC    = 'orc4_ppaRPC.php';
function js_reprocessa() {


  oParam                     = new Object();
  oParam.exec                = "ProcessaEstimativa";
  oParam.iTipo               = <?= $oGet->iTipo."\n"?>;
  oParam.iAnoInicio          = <?= $oPPaLei->o01_anoinicio."\n"?>;
  oParam.iAnoFim             = <?= $oPPaLei->o01_anofinal."\n"?>;
  oParam.iCodigoLei          = <?= $oGet->o01_sequencial."\n"?>;
  oParam.iCodigoVersao       = <?= $oGet->o05_ppaversao."\n"?>;
  oParam.lProcessaBase       = $('basecalculo').checked;
  oParam.lProcessaEstimativa = $('estimativas').checked;
  var sMsg = "Essa Rotina ira reprocessar as Operações selecionadas,\n";
  sMsg    += "Todas as informações cadastradas para o ppa\n";
  sMsg    += "no ano corrente, serão recalculadas.\nProsseguir?";
  if (!confirm(sMsg)) {
    return false;
  }
  $('reprocessar').disabled = true;
  parent.js_divCarregando("Aguarde, Reprocessando PPA..","msgbox");
  var oAjax   = new Ajax.Request(
                         sUrlRPC, 
                         {
                          method    : 'post', 
                          parameters: 'json='+js_objectToJson(oParam), 
                          onComplete: js_retornoReprocessa
                          }
                        );
}

function js_retornoReprocessa(oAjax) {
  
  parent.js_removeObj("msgbox");
  $('reprocessar').disabled = false;
  var iTipo = <?=$oGet->iTipo?> 
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
  
    alert('Reprocessamento Realizado com Sucesso!');
    parent.db_iframe_reprocessa.hide();
    
  } else {
    alert(oRetorno.message.urlDecode());
  }
}
</script>