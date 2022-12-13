<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_liborcamento.php");
require_once("model/relatorioContabil.model.php");

db_postmemory($_POST);

$oGet               = db_utils::postMemory($_GET);
$oRelatorioContabil = new relatorioContabil($oGet->codrel);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
function js_emite() {

  obj              = document.form1;
  sel_instit       = document.form1.db_selinstit.value;
  aCheckbox        = new Array();
  if (document.db_selinstit_iframe) {
    var aCheckbox    = db_selinstit_iframe.$('form1').getInputs('checkbox');
  }
  var lConsolidado = 0;
  var iOrigemFase  = document.form1.iOrigemFase.value;
  var iPeriodo     = document.form1.o116_periodo.value;
  
  
  var sUrl        = "";
  
  if (aCheckbox.length == sel_instit.split('-').length) {
    lConsolidado = 1;
  }  
  
  if (sel_instit == 0) {
  
    alert('Você não escolheu nenhuma Instituição. Verifique!');
    return false;
  }
  
  if (iOrigemFase != 1) {
    if (iPeriodo == 0) {
    
      alert('Para esta opção de \"Origem/Fase\" é necessário selecionar um Periodo!');
      return false;
    }
  } else {
    iPeriodo = 17; // Corresponde ao mês de Janeiro
  }

  sUrl  = 'iOrigemFase='+iOrigemFase+'&lConsolidado='+lConsolidado+'&db_selinstit='+document.form1.db_selinstit.value;
  sUrl += '&iPeriodo='+iPeriodo;
  
  jan = window.open('orc2_anexoorcamentofiscal002.php?'+sUrl,
                    '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<div style="margin-top: 25px;"></div>
<center>
  <div style="width: 350px; margin-top: 5px;">
    <div style="font-weight: bold; background-color: #EFF0F2; width: 330px; height: 15px; padding: 5px">
      Relatório do Orçamento Físcal
    </div>
    <fieldset>
      <form name="form1" method="post" action="">
        <table  align="center" border=0>
          <tr>
             <td align="center" colspan="2">
             <?
               db_selinstit('', 300, 140);
             ?>
             </td>
          </tr>
          <tr>
            <td><b>Origem/Fase:</b></td>
            <td>
              <?
                $aOrigemFase = array(0 => "Selecione", 
                                     1 => "Orçamento", 
                                     2 => "Empenhado", 
                                     3 => "Liquidado",
                                     4 => "Pago");
                db_select("iOrigemFase", $aOrigemFase, false, 1, 'onchange="js_verificaPeriodo(this.value);"');
              ?>
            </td>
          </tr>
          <tr id='periodos' style="display: none;">
                <td width="80"><b>Período:</b></td>
                <td colspan="2">
                  <?
                    $aPeriodos         = $oRelatorioContabil->getPeriodos();                  
                    $aListaPeriodos    = array();
                    $aListaPeriodos[0] = "Selecione";
                    foreach ($aPeriodos as $oPeriodo) {
                      $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
                    }
                    
                    db_select("o116_periodo", $aListaPeriodos, true, 1);
                  ?>
                </td>
              </tr>
        </table>
      </form>
    </fieldset>
  </div>
  <div >
    <input type="submit" value="Imprimir" onClick="js_emite();">
  </div>
</center>
<script type="text/javascript">

function js_verificaPeriodo(iOrigem) {

  if (iOrigem != 1) {

    $("periodos").style.display = "table-row";
    $("periodos").style.display = "table-row";
  } else {
    $("periodos").style.display = "none";
    $("periodos").style.display = "none";
  }
}


</script>

</body>
</html>