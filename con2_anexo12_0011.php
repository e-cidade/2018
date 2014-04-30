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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_liborcamento.php");
require_once("dbforms/db_funcoes.php");

$oGet     = db_utils::postMemory($_GET);

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');

$iAnoUsu  = db_getsession("DB_anousu");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
function js_buscaEdicaoLrf(iAnousu, sFontePadrao) {
  
  var sUrlRPC    = 'con4_lrfbuscaedicaoRPC.php';
  var sParametro = 'ianousu='+iAnousu+'&sfontepadrao='+sFontePadrao;
  var objAjax    = new Ajax.Request (sUrlRPC, { 
                                                method: 'post',
                                                parameters: sParametro, 
                                                onComplete: js_setNomeArquivo
                                              }
                                     );  
}

function js_setNomeArquivo(oResposta) {
  sNomeArquivoEdicao = oResposta.responseText;
}

js_buscaEdicaoLrf(<?php echo $iAnoUsu; ?>, 'con2_anexo12_002');

var variavel = 1;
function js_emite(anousu) {
  
  var oDocument  = document.form1;
  var iSelInstit = new Number(oDocument.db_selinstit.value);
  if (iSelInstit == 0) {
  
    alert('Você não escolheu nenhuma Instituição. Verifique!');
    return false;
  }
  
  if ($('mes').value == 0) {
  
    alert('Você não escolheu nenhum período. Verifique!');
    return false;
  }

  var sFonte = sNomeArquivoEdicao;
  var jan    = window.open('', 'relatorioanexo12'+variavel, 'width='+(screen.availWidth-5)+
                                                            ',height='+(screen.availHeight-40)+
                                                            ',scrollbars=1,location=0 ');
  oDocument.target = 'relatorioanexo12'+variavel++;
  oDocument.action = sFonte+"?mes"+oDocument.mes.value+
                            "&tipoprevisao="+oDocument.tipoprevisao.value+
                            "$tipofixacao="+oDocument.tipofixacao.value+
                            "&iCodRel="+<?=$oGet->codrel?>;
  setTimeout("document.form1.submit()", 1000);
  return true;
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
 <form name="form1" method="post" action="" >
  <table align="center" border="0" cellpadding="0" cellspacing="0">
   <tr>
    <td >&nbsp;</td>
   </tr>
   <tr>
    <td colspan=3  class='table_header'>
      Balanço Orçamentário (Anexo12)
    </td>
   </tr>  
   <tr>
    <td>
      <fieldset>
        <legend>
          <b>Filtros</b>
        </legend>
         <table  align="center">
            <tr>
              <td align="center" colspan="3">
                <? db_selinstit('', 300, 100); ?>
              </td>
            </tr>
            <tr>
              <td style="width: 10px;">
                <strong>Período:</strong>
              </td>
              <td>
	              <?
	                $aMes = array("0"  => "Selecione",
 	                              "1"  => "Janeiro",
	                              "2"  => "Fevereiro",
	                              "3"  => "Março",
	                              "4"  => "Abril",
	                              "5"  => "Maio",
	                              "6"  => "Junho",
	                              "7"  => "Julho",
	                              "8"  => "Agosto",
	                              "9"  => "Setembro",
	                              "10" => "Outubro",
	                              "11" => "Novembro",
	                              "12" => "Dezembro");
	                db_select("mes", $aMes, true, 2);
	             ?>
              </td> 
            </tr>  
	          <tr>
	            <td style="width: 10px;" nowrap="nowrap">
	              <strong>Previsão da Receita:</strong>
	            </td>
	            <td>
	              <?
	                $aTipoPrevisao = array("1" => "Inicial",
	                                       "2" => "Atualizada");
	                db_select("tipoprevisao", $aTipoPrevisao, true, 2);
	              ?>
	            </td>
	          </tr>     
	          <tr>
	            <td style="width: 10px;" nowrap="nowrap">
	              <strong>Fixação da Despesa:</strong>
	            </td>
	            <td>
	              <?
	                $aTipoFixacao = array("1" => "Inicial",
	                                      "2" => "Atualizada");
	                db_select("tipofixacao", $aTipoFixacao, true, 2);
	              ?>
	            </td>
	          </tr>     
            <tr>
              <td colspan=2 align="center">
            </td>
           </tr>
         </table>
      </fieldset>
     <table align="center">
       <tr>
        <td>&nbsp;</td>
       </tr>     
       <tr>
        <td>
          <input  name="imprimir" id="imprimir" type="button" value="Imprimir" onclick="js_emite(<?=$iAnoUsu?>);">
        </td>
       </tr>     
     </table>      
    </td>
   </tr>
  </table>       
 </form>
</body>
</html>