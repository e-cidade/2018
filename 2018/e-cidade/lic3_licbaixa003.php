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
include("classes/db_licbaixa_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cllicbaixa = new cl_licbaixa;
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
<form name='form1'>
<table border='0'>  
<?
$where="";
$and="";
if (isset($l20_codigo)&&$l20_codigo!="") {
	$where .= $and." l28_liclicita=$l20_codigo ";
	$and= " and ";
}  

    $result=$cllicbaixa->sql_record($cllicbaixa->sql_query(null,"*",null,$where));
    $numrows = $cllicbaixa->numrows;
    if($numrows>0){
      echo "<tr class='bordas'>		
		<td class='bordas' align='center'><b><small>Código Sequencial</small></b></td>
		<td class='bordas' align='center'><b><small>Nome</small></b></td>
<td class='bordas' align='center'><b><small>CNPJ/CPF</small></b></td>
		<td class='bordas' align='center'><b><small>E-mail</small></b></td>
		<td class='bordas' align='center'><b><small>Endereço</small></b></td>
		<td class='bordas' align='center'><b><small>Cidade</small></b></td>
		<td class='bordas' align='center'><b><small>Telefone</small></b></td>
		<td class='bordas' align='center'><b><small>Data</small></b></td>		
	    </tr>";
    }else echo"<b>Nenhum registro encontrado...</b>";
    for($i=0; $i<$numrows; $i++){
      db_fieldsmemory($result,$i);
       echo "
	     <tr>	       		    
	       <td class='bordas_corp' align='center'><small>$l28_sequencial </small></td>
<td class='bordas_corp' align='center'><small>$l28_nome </small></td>
<td class='bordas_corp' align='center'><small>$l28_cnpj </small></td>
<td class='bordas_corp' align='center'><small>$l28_email </small></td>
<td class='bordas_corp' align='center'><small>$l28_endereco </small></td>
<td class='bordas_corp' align='center'><small>$l28_cidade </small></td>
<td class='bordas_corp' align='center'><small>$l28_fone </small></td>
 <td class='bordas_corp' align='center'><small>".db_formatar($l28_data,"d")."</small></td>
	     </tr>
	     ";
    }
  

?>     
</table>
</form> 
</td>
</tr>
</table>
<script>
</script>
</body>
</html>