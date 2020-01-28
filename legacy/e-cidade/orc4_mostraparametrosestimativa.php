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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("std/db_stdClass.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_ppaestimativa_classe.php");
include("dbforms/db_funcoes.php");
$oGet           = db_utils::postMemory($_GET);
$oDaoPPalei     = db_utils::getDao("ppalei");
$sSqlPPalei     = $oDaoPPalei->sql_query($oGet->o01_sequencial);
$rsPpaLei       = $oDaoPPalei->sql_record($sSqlPPalei);
$oPPaLei        = db_utils::fieldsMemory($rsPpaLei, 0);
$sTextoTipo     = "Parametros utilizados para o calculo das estimativas";
if ($iTipo == 1) {
  
  $o01_sequencial = $oGet->o01_sequencial;
  $sSqlFonte      = "select o57_fonte as estrutural, o57_descr as descricao,
                            o57_codfon as codigo from orcfontes
                      where o57_codfon = {$oGet->iCodCon} and o57_anousu = {$oPPaLei->o01_anoinicio}";
  $sTextoTipo    .= " de receita.";
} else {
  
  $sSqlFonte   = "select c60_estrut as estrutural,"; 
  $sSqlFonte  .= "       o56_descr as descricao,"; 
  $sSqlFonte  .= "       o56_codele as codigo"; 
  $sSqlFonte  .= "  from orcelemento ";
  $sSqlFonte  .= "       inner join  conplano on c60_codcon = o56_codele";
  $sSqlFonte  .= "                           and c60_anousu = o56_anousu";
  $sSqlFonte  .= " where o56_codele = {$oGet->iCodCon} ";
  $sSqlFonte  .= "   and o56_anousu = ".($oPPaLei->o01_anoinicio);
  $sSqlFonte  .= " limit 1";
  $sTextoTipo .= " de despesa."; 
  
}
$rsFonte       = db_query(analiseQueryPlanoOrcamento($sSqlFonte));
$oFonte        = db_utils::fieldsMemory($rsFonte,0);                    
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
<body bgcolor=#CCCCCC style='margin:0'>
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
            <legend> <b><?=$oFonte->estrutural."--".$oFonte->descricao?></legend>
            <table width='100%' cellspacing="0" cellpadding="0">
             <tr>
               <td colspan='2' style='border-bottom:2px groove white;border-left:2px groove white;'>
                  &nbsp;<b>Parâmetros usados:</b>
                </td>
             </tr>
             <tr>
               <td rowspan='5' valign="top" style='padding:2px; border-left:2px groove white;height:150px;overflow:scroll'>
               <table cellspacing="0" cellpadding="0" width='100%' >
               <?

                $oDaoCenarioConplano = db_utils::getDao("orccenarioeconomicoconplano");
                $nValorparametro     = 0;
                $sSqlParametros      = $oDaoCenarioConplano->sql_query(null,
                                                                       "o03_descricao, o03_valorparam,o03_anoreferencia",
                                                                       "o03_anoreferencia",
                                                                       "o04_conplano   = {$oFonte->codigo}
                                                                       and o03_instit     = ".db_getsession("DB_instit")
                                                                       );
                $rsParametros     = $oDaoCenarioConplano->sql_record(analiseQueryPlanoOrcamento($sSqlParametros));
                if ($oDaoCenarioConplano->numrows > 0) {
                  
                  $aParametros = db_utils::getColectionByRecord($rsParametros);
                  echo "<tr>";
                  echo "  <td style='border-bottom:1px solid black;text-align:center'><b>Descriçao</b></td>";
                  echo "  <td style='border-bottom:1px solid black;text-align:center'><b>Ano</b></td>";
                  echo "  <td style='border-bottom:1px solid black;text-align:center'><b>Percentual</b></td>";
                  echo "</tr>";
                  foreach ($aParametros as $oParametro) {
                    
                    echo "<tr style='background-color:white'>";
                    echo "  <td style='border-right:1px solid black;'>{$oParametro->o03_descricao}</td>";
                    echo "  <td style='border-right:1px solid black;'align='right'>{$oParametro->o03_anoreferencia}</td>";
                    echo "  <td align='right'>{$oParametro->o03_valorparam}</td>";
                    echo "</tr>";  
                    
                  }
                }
               ?>
               </table>
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
  </table>
  </form>
</center>
</body>
</html>
<script>
sUrlRPC    = 'orc4_ppaRPC.php';
function js_reprocessa() {

  var aAnos          = js_getElementbyClass(document.frmReprocessa,'anos',"checked==true");
  if (aAnos.length == 0) {
  
    alert('Escolha algum ano');
    return false;
    
  } 
  var aAnosEscolhidos = new Array;
  for (var i = 0 ;i < aAnos.length;i++) {
    aAnosEscolhidos.push(aAnos[i].value);
  }
  oParam             = new Object();
  oParam.exec        = "reprocessaEstimativa";
  oParam.iCodCon     = <?= $oGet->iCodCon."\n"?>;
  oParam.iTipo       = <?= $oGet->iTipo."\n"?>;
  oParam.iEstrutural = '<?=$oFonte->estrutural;?>';
  oParam.aAnos       = aAnosEscolhidos;
  oParam.iCodigoLei  = <?= $oGet->o01_sequencial."\n"?>;
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
  
  var iTipo = <?=$oGet->iTipo?> 
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
  
    parent.db_iframe_reprocppaestimativa.hide();
    if (iTipo == 2) {
      if (oRetorno.itens.length > 0) {
      
        for (var i = 0; i < oRetorno.itens.length; i++) {
          
          if (parent.document.getElementById('ano'+oRetorno.itens[i].ano+'cta'+oRetorno.iEstrutural)) {
            parent.$('ano'+oRetorno.itens[i].ano+'cta'+oRetorno.iEstrutural).value = js_formatar(oRetorno.itens[i].valor,'f');
          }
        }
      }
    } else {
     parent.js_getEstimativas();
    } 
    
    
  } else {
    alert(oRetorno.message.urlDecode());
  }
}
</script>