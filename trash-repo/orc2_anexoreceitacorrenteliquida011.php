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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_liborcamento.php");
require_once("model/relatorioContabil.model.php");

$oGet               = db_utils::postMemory($_GET);
$oRelatorioContabil = new relatorioContabil($oGet->codrel);
$iPeriodo           = $oRelatorioContabil->getPeriodos();
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc" style="margin-top: 25px;">

<center>
  <form name="form1" id="form1">
    <table width="300px">
      <tr>
        <td colspan="3" nowrap="nowrap" class="table_header">
          Demonstrativo da Receita Corrente Liquida
        </td>
      </tr>
      <tr>
        <td colspan="3" align="center">
          <fieldset>
      	    <input type="hidden" name="o116_periodo" id="o116_periodo" value="<?=$iPeriodo[0]->o114_sequencial?>" />
    				<br>
    				<input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" />
          </fieldset>
        </td>
      </tr>  
    </table>

  </form>
</center>


<script>
  $("btnImprimir").observe("click", function() {
  
    var sUrlWindow  = "orc2_anexoreceitacorrenteliquida002.php?";
        sUrlWindow += "iCodRel=119";
        sUrlWindow += "&iPeriodo="+$F('o116_periodo');
        
    var oJan = window.open(sUrlWindow, '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    oJan.moveTo(0,0);   
  });
</script>
</body>
</html>