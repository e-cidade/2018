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
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_pcfornecon_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clpcfornecon = new cl_pcfornecon;
$clrotulo = new rotulocampo;
$clrotulo->label("");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
</script>
<style>
<?//$cor="#999999"?>
.bordas{
    border: 2px solid #cccccc;
    border-top-color: #999999;
    border-right-color: #999999;
    border-left-color: #999999;
    border-bottom-color: #999999;
    background-color: #999999;
}
.bordas_corp{
    border: 1px solid #cccccc;
    border-top-color: #999999;
    border-right-color: #999999;
    border-left-color: #999999;
    border-bottom-color: #999999;
    background-color: #cccccc;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"> 
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
<tr> 
<td  align="center" valign="top" > 

<table border='0'>
<form name='form1'>  
  <tr>
    <td colspan=6  align=center >
      <input type='button' value='Voltar' onclick="parent.db_iframe_contas.hide();" >
    <br>
    </td>
  </tr>
  <tr>
  <td colspan=6 align=center>
  
<?
  db_input('pc60_numcgm',10,'',true,'hidden',3);

if (isset($pc60_numcgm)&&$pc60_numcgm!="") {
    $sql = $clpcfornecon->sql_query_lefpadrao(null,"pc63_contabanco,pc63_cnpjcpf,pc63_numcgm,pc63_banco,pc63_agencia||' - '||pc63_agencia_dig as pc63_agencia,pc63_conta||' - '||pc63_conta_dig as pc63_conta,pc63_dataconf,case when pc64_contabanco is not null then 'Padrão' else '' end as pc64_contabanco","pc63_contabanco"," pc63_numcgm = $pc60_numcgm "); 
}
$repassa = array('dblov'=>'0');
?>
</form>
<?
  $repassa = array('dblov'=>'0');
  db_lovrot(@$sql,10,"()","","","","NoMe",$repassa);
?>     
</td>
</tr>
</table>
</td>
</tr>
</table>
<script>
</script>
</body>
</html>