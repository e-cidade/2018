<?php
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
	
	$sqltab ="select * from pg_tables where tablename = 'temp_classeatualiza'";
	$resulttab = pg_query($sqltab);
	$linhatab = pg_num_rows($resulttab);
	if($linhatab>0){
		$sql = "select * from temp_classeatualiza";
		$result = pg_query($sql);
		$linha = pg_num_rows($result);
    if(pg_num_rows($result)>0){
		  db_fieldsmemory($result,0);
    }
	}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style >
table.tab {
	border-collapse: collapse;
}
table.tab th {
	border:1px solid #000000;
	background-color: #666666;
	color: #FFFFFF;
}
table.tab td {
	border:1px solid #000000;
	background-color: #e4e4e4;
	color: #000000;
}
</style>
</head>
<script type="text/javascript">
function js_codigo(seq){
	js_OpenJanelaIframe('top.corpo','db_iframe_codigo','func_codigo.php?seq='+seq,'Código',true);
}

function js_marca(obj){ 
   var OBJ = document.form1;
   for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].type == 'checkbox' && OBJ.elements[i].disabled==false){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
     }
   }
<?   
    if(isset($js_marcador)){
       echo str_replace(";","",$js_marcador).";";
    }
?>	  
   return false;
}

</script>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<form name="form1" action="">
<table width="90%" border="1" cellpadding="0" cellspacing="0" class="tab" align="center">
  <tr>
  	<th> Seq.
    </th> 
    <th> Arquivo
    </th>
    <th> Método
    </th>
    <th> Operação
    </th>
    <th> <a title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a>
    </th>
    <th> Código
    </th>
  </tr>
  <?
  if($linhatab>0){
	  if ($linha>0){
	  	for($i = 0;$i<$linha;$i++){
	  		db_fieldsmemory($result,$i);
	  		echo" 
	  		<tr> 
				<td align = 'center' width='5%'> $seq
			    </td>
			    <td width='25%'> $nomearq
			    </td>
			    <td width='25%'> $metodo
			    </td>
			    <td align = 'center' width='20%'> $operacao
			    </td>
			    <td width='5%' align = 'center'> <input id='CHECK_$i' name='CHECK_$i' type='checkbox' value=$seq >
			    </td>
			    <td width='15%' align = 'center'> <input name='vercodigo' type='button' value='Código' onclick = 'js_codigo($seq);'>
			    </td>
			  </tr>";
	  	}
	  }
  }
  ?>
</table>
</form>
</body>
</html>