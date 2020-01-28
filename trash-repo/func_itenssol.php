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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("classes/db_solicitem_classe.php");
include("classes/db_pcmater_classe.php");
include("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clsolicitem = new cl_solicitem;
$clpcmater   = new cl_pcmater;
$clsolicitem->rotulo->label();

$disabled   = "";
$disabledSN = false;
$nome       = "Enviar dados";
if (!isset($db_opcao)) {
  $db_opcao = 1;
} else if(isset($db_opcao) && ($db_opcao==3 || $db_opcao==33)) {
	
  $disabled   = "disabled";
  $disabledSN = true;
  $nome       = "Fechar";
}

if (isset($oGet->pc16_codmater) && !empty($oGet->pc16_codmater)) {
	
	$sWhere       = "pc01_codmater = {$oGet->pc16_codmater}";
	$sSqlPcMater  = $clpcmater->sql_query(null, "pcmater.*", null, $sWhere);
	$rsSqlPcMater = $clpcmater->sql_record($sSqlPcMater);
	$iNumRows     = $clpcmater->numrows;
	if ($iNumRows > 0) {
		
		$oPcMater = db_utils::fieldsMemory($rsSqlPcMater, 0);
		if ($oPcMater->pc01_liberaresumo == 'f') {
	    $db_opcao   = 3;
		}
	  $pc11_resum = $oPcMater->pc01_complmater;
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="js_buscaval();">
<form name="form1">
<table height="100%" width="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
      <table width="35%" border="0" align="center" cellspacing="0">
	      <tr>
	        <td nowrap title="<?=@$Tpc11_prazo?>">
	          <?=@$Lpc11_prazo?>
	        </td>
	        <td>
	          <?
		          db_textarea('pc11_prazo',3,30,$Ipc11_prazo,true,'text',$db_opcao)
		        ?>
		      </td>
		    </tr>
	      <tr>
	        <td nowrap title="<?=@$Tpc11_pgto?>">
	          <?=@$Lpc11_pgto?>
	        </td>
	        <td>
	          <?
		          db_textarea('pc11_pgto',3,30,$Ipc11_pgto,true,'text',$db_opcao)
		        ?>
		      </td>
		    </tr>
	      <tr>
	        <td nowrap title="<?=@$Tpc11_resum?>">
	          <?=@$Lpc11_resum?>
	        </td>
	        <td>
	          <?
		          db_textarea('pc11_resum',10,80,$Ipc11_resum,true,'text',$db_opcao)
		        ?>
		      </td>
		    </tr>
	      <tr>
	        <td nowrap title="<?=@$Tpc11_just?>">
	          <?=@$Lpc11_just?>
	        </td>
	        <td>
	          <?
		          db_textarea('pc11_just',3,30,$Ipc11_just,true,'text',$db_opcao)
		        ?>
		      </td>
		    </tr>
      </table>
			<br><br>
		  <input <?=($disabled)?> name="conf" type="button" id="conf" value=<?=($nome)?> onclick='js_passadados("<?=$db_opcao?>");'>
    </td>
  </tr>
</table>
</form>
</body>
</html>
<script>
function js_passadados(x){
  if(x!=3 && x!=33){
    parent.document.form1.pc11_quant.value = parent.document.form1.pc11_quant.value;
    parent.document.form1.pc11_prazo.value = document.form1.pc11_prazo.value;
    parent.document.form1.pc11_pgto.value  = document.form1.pc11_pgto.value;
    parent.document.form1.pc11_resum.value = document.form1.pc11_resum.value;
    parent.document.form1.pc11_just.value  = document.form1.pc11_just.value;
    parent.document.form1.digitouresumo.value = "true";
  }
  parent.db_iframe.hide();
}

function js_buscaval(){
  document.form1.pc11_prazo.value = parent.document.form1.pc11_prazo.value;
  document.form1.pc11_pgto.value  = parent.document.form1.pc11_pgto.value;
  if (document.form1.pc11_resum.value == '') {
    document.form1.pc11_resum.value = parent.document.form1.pc11_resum.value;
  }
  document.form1.pc11_just.value  = parent.document.form1.pc11_just.value;
}
</script>