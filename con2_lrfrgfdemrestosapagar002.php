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
require_once("libs/db_utils.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_liborcamento.php");
require_once("model/relatorioContabil.model.php");

$oGet     = db_utils::postMemory($_GET);
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');

$oRelatorio = new relatorioContabil($oGet->codrel);
$iAnoUsu    = db_getsession("DB_anousu");
$sLabelMsg  = "Anexo VI - Dem. dos Restos a Pagar";
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

	var sFonte     = 'con2_lrfrgfdemrestosapagar002_2010.php';
	var oDocument  = document.form1;
  var iSelInstit = new Number(oDocument.db_selinstit.value);
  if (iSelInstit == 0) {
  
    alert('Você não escolheu nenhuma instituição. Verifique!');
    return false;
  }
  
  if ($('o116_periodo').value == 0) {
  
    alert('Você não escolheu nenhum período. Verifique!');
    return false;
  }

  var sGetParam = sFonte+'?db_selinstit='+oDocument.db_selinstit.value+'&periodo='+$('o116_periodo').value;
  var jan       = window.open(sGetParam,'',
                              'width='+(screen.availWidth-5)+
                              ',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
 <form name="form1" method="post" action="con2_lrfrgfdemrestosapagar002.php" >
  <table align="center" border="0" cellpadding="0" cellspacing="0">
   <tr>
    <td >&nbsp;</td>
   </tr>
   <tr>
    <td colspan=3  class='table_header'>
     <?=$sLabelMsg?>
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
                <? db_selinstit('',300,100); ?>
              </td>
            </tr>
            
            <tr>
                <td colspan=2 nowrap><b>Período:&nbsp;</b>
                    <?
	                    if ($iAnoUsu < 2010 ) {
	              
	                      $aListaPeriodos = array("1B" => "1 º Bimestre",
	                                              "2B" => "2 º Bimestre",
	                                              "3B" => "3 º Bimestre",
	                                              "4B" => "4 º Bimestre",
	                                              "5B" => "5 º Bimestre",
	                                              "6B" => "6 º Bimestre");
	                    } else {
	        
	                      $aPeriodos         = $oRelatorio->getPeriodos();
	                      $aListaPeriodos    = array();
	                      $aListaPeriodos[0] = "Selecione";
	                      foreach ($aPeriodos as $oPeriodo) {
	                        $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
	                      }
	                    }
	                    
	                    db_select("o116_periodo", $aListaPeriodos, true, 1);
                    ?>
                  </td>
                </td> 
            </tr>         
            <tr>
                <td colspan=2>&nbsp;</td>
            </tr>        
            <tr>
              <td colspan=2 align="center">
            </td>
            </tr>
         </table>
      </fieldset>
     <table align="center">
       <tr>
        <td>&nbsp; </td>
       </tr>     
       <tr>
        <td>
         <input  name="imprimir" id="imprimir" type="button" value="Imprimir" onclick="js_emite();">
        </td>
       </tr>     
     </table>      
    </td>
   </tr>
  </table>       
 </form>
</body>
</html>