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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
require("libs/db_utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_rhestagioagendadata_classe.php");
include("classes/db_rhestagioresultado_classe.php");

$clrhestagioresultado  = new cl_rhestagioresultado;
$clrhestagioagendadata = new cl_rhestagioagendadata();
$oGet                  = db_utils::postMemory($_GET);

(string)$sWhere = '';
(string)$sAnd   = '';
if (isset($oGet->dataInicial) && $oGet->dataInicial != null){
   $dataIniAux  = explode ("/",$oGet->dataInicial);
   $dataFimAux  = explode ("/",$oGet->dataFinal);
   $timeInicial = mktime(0,0,0,(int)$dataIniAux[1],(int)$dataIniAux[0],(int)$dataIniAux[2]);
   $timeFinal   = mktime(0,0,0,(int)$dataFimAux[1],(int)$dataFimAux[0],(int)$dataFimAux[2]);
   if ($timeInicial > $timeFinal){

      db_msgbox('A data Inicial é maior que a data final.');
      echo "<script>\n";
      echo "   parent.\$('h64_dataini').focus();\n";
      echo "   parent.db_iframe_resultado.hide();\n";
      echo "</script>";
   }else{
     $sWhere .= " {$sAnd} h65_data between  '{$dataIniAux[2]}-{$dataIniAux[1]}-{$dataIniAux[0]}' and '{$dataFimAux[2]}-{$dataFimAux[1]}-{$dataFimAux[0]}'";
     $sAnd   = "and";
   }
}
if (isset($oGet->iMatricula) && $oGet->iMatricula != null){

  $sWhere .= " {$sAnd} h57_regist = {$oGet->iMatricula}";
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<fieldset>
<?

$campos    = " h57_sequencial, h57_regist, z01_nome, rh01_admiss,h55_nroaval, ";
$campos   .= " count(distinct h56_sequencial) as \"dl_total realizadas\",fc_calculapontosestagio(h57_sequencial,'t') as dl_Pontos";
$sOrder    = "h57_regist";
$sGroup    = "group by h55_nroaval, ";
$sGroup   .= "         h57_regist, ";
$sGroup   .= "         h57_sequencial, ";
$sGroup   .= "         z01_nome, ";
$sGroup   .= "         rh01_admiss "; 
$js_funcao = "js_mostraBoletim|h57_sequencial";
$sSql      = $clrhestagioresultado->sql_query_resultado(null,$campos,"{$sOrder}","$sWhere {$sGroup}");
db_lovrot($sSql,15,"()","",$js_funcao,"","NoMe");

?>
</fieldset>
</center>
</body>
</html>
<script>
function js_mostraBoletim(iCodExame){
   window.open('rec2_estagioBoletim002.php?iCodExame='+iCodExame,'','location=0');
}
</script>