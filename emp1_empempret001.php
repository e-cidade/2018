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
include("dbforms/db_funcoes.php");
include("classes/db_pagordemtiporec_classe.php");
include("classes/db_empautret_classe.php");
include("classes/db_empempret_classe.php");
include("classes/db_empretencao_classe.php");
include("classes/db_empautitem_classe.php");
$clpagordemtiporec = new cl_pagordemtiporec;
$clempautret = new cl_empautret;
$clempempret = new cl_empempret;
$clempretencao = new cl_empretencao;
$clempautitem = new cl_empautitem;
db_postmemory($HTTP_POST_VARS);
$nao_mostrar_botao = true;

$e66_autori = $chavepesquisa;
$retult_valoritens = $clempautitem->sql_record($clempautitem->sql_query_file($chavepesquisa,null,"sum(e55_vltot) as e55_vltot"));
if($clempautitem->numrows > 0){
  db_fieldsmemory($retult_valoritens, 0);
}
if (!isset($op)){
 $op = 1;
}
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
        <?
        include("forms/db_frmempautret.php");
        ?>
      </center>
    </td>
  </tr>
</table>
</body>
</html>
<script>
setValorNota("<?=$e55_vltot?>");
</script>