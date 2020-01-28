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


require ("../libs/db_stdlib.php");
require ("../libs/db_conecta.php");
include ("../libs/db_sessoes.php");
include ("../libs/db_usuariosonline.php");
include ("../dbforms/db_funcoes.php");
include ("../classes/db_pagordemrec_classe.php");
include ("../classes/db_pagordemnota_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clpagordemrec = new cl_pagordemrec;
$clpagordemnota = new cl_pagordemnota;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="../scripts/scripts.js"></script>
<link href="../estilos.css" rel="stylesheet" type="text/css">
<style>
.table_header{
    border: 1px solid #cccccc;
    border-top-color: #999999;
    border-right-color: #999999;
    border-left-color: #999999;
    border-bottom-color: #999999;
    background-color: #999999;
    font-size: 10px;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table border=0 width="100%" style="border:1px solid #7C7C7C" id="tabreceitas">
  <tr id="cabecalho">
    <td colspan=2 ><b>RECEITA  </b></td>
    <td width=20px><b>VALOR</b></td>
  </tr>
<?



if (isset ($codop) && $codop != "false") {

	$res = $clpagordemnota->sql_record($clpagordemnota->sql_query_file(null, $codop, "e71_codord"));
	if ($clpagordemnota->numrows > 0) {
		db_fieldsmemory($res, 0);
		$res = $clpagordemrec->sql_record($clpagordemrec->sql_query($e71_codord));
		if ($clpagordemrec->numrows > 0) {
			$cont = 0;
			for ($x = 0; $x < $clpagordemrec->numrows; $x ++) {
				db_fieldsmemory($res, $x);
		         ?> 
        		  <tr id="ret_<?=$e52_receit?>_<?=$cont ?>"
		             <td><?=$e52_receit?></td>
		             <td><?=$k02_descr?></td>
		             <td align=right>
		             <?

				$campovalor = "val_".$e52_receit."_".$cont;
				$$campovalor = $e52_valor;
				db_input($campovalor, 8, 0, true, "text", 3, "readonly", '', '', 'text-align:right');
				
						
			     ?>
		             </td>
			     <td>
                               <input type='button' 
			              value='E' 
				      onclick='js_deleteRow("ret_<?=$e52_receit?>_<?=$cont?>")'>
                             </td>
		          </tr>
		            <?
		           $cont++;    
			}// end loop
		} // end if		 
	} else {
	?>
	    <tr>
	      <td id="semretencoes" colspan=2>Sem retenções Lançadas </td>
	    </tr>
	 <?
	}
}
?>
</table>
</body>
</html>
<script>
function js_deleteRow(campoID){
  var tab = document.getElementById("tabreceitas");
  for(i=1;i<tab.rows.length;i++){
    if(tab.rows[i].id == campoID){
      document.getElementById("tabreceitas").deleteRow(i);
      break;
    }
  }
}
</script>