<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_solicitem_classe.php"));
include(modification("classes/db_empautitem_classe.php"));
include(modification("classes/db_empempitem_classe.php"));
include(modification("dbforms/db_funcoes.php"));
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clsolicitem = new cl_solicitem;
$clempautitem = new cl_empautitem;
$clempempitem = new cl_empempitem;
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
  <tr>
    <td colspan=6 align=center>
    <br>
    <br>
    <br>
    </td>
  </tr>
<?
db_input("mostra",10,"",true,"hidden",3);
db_input("pc01_codmater",10,"",true,"hidden",3);
if (!isset($filtroquery)){
	if ($mostra=='sol'){	
		$sql=$clsolicitem->sql_query_pcmater(null,"pc10_numero,pc10_data,descrdepto,nome,pc11_seq, pc11_quant,pc11_vlrun,pc11_vlrun*pc11_quant as dl_Valor","pc10_numero desc","pc16_codmater=$pc01_codmater");
	}else if ($mostra=='aut'){
		$sql=$clempautitem->sql_query(null,null,"e54_autori,e54_emiss,e54_anulad,descrdepto,e55_quant,e55_vlrun,e55_vltot",null,"e55_item=$pc01_codmater");
	}else if ($mostra=='emp'){
		$sql=$clempempitem->sql_query(null,null,"e60_numemp,e60_codemp,e60_emiss,z01_nome,e62_quant,e62_vlrun,e62_vltot",null,"e62_item=$pc01_codmater");
	}
}
db_lovrot(@$sql,15,"()","","");
?>     
</table>

</td>
</tr>
</table>
<script>
</script>
</body>
</html>