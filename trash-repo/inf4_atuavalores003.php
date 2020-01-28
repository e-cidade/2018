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

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

//echo $dtoper."<br>";
//echo $dtvenc."<br>";
//echo $valor."<br>";
//echo $dtbase."<br>";
//echo $receit."<br>";

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td >Processando. Aguarde ...</td>
  </tr>
</table>
</body>
</html>
<?
$result = pg_query("select fc_corre(".$receit.",'".$dtoper."',".$valor.",'".$dtbase."',".db_getsession("DB_anousu").",'".$dtvenc."') as correcao");
db_fieldsmemory($result,0);

$result = pg_query("select fc_juros(".$receit.",'".$dtvenc."','".$dtbase."','".$dtoper."','f',".db_getsession("DB_anousu").") as juro");
db_fieldsmemory($result,0);
$juro = $correcao * $juro;

$result = pg_query("select fc_multa(".$receit.",'".$dtvenc."','".$dtbase."','".$dtoper."',".db_getsession("DB_anousu").") as multa");
db_fieldsmemory($result,0);
$multa = $correcao * $multa;

$total = $correcao + $juro + $multa;

$correcao = $correcao - $valor;

?>
<script>
parent.js_gravavalor(<?=$correcao?>,<?=$juro?>,<?=$multa?>,<?=$total?>);
parent.db_iframe.hide();
</script>