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
include("classes/db_solicitemprot_classe.php");
include("classes/db_procandamint_classe.php");
include("classes/db_proctransand_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clsolicitemprot = new cl_solicitemprot;
$clprocandamint = new cl_procandamint;
$clproctransand = new cl_proctransand;
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
<?$cor="#999999"?>
.bordas{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
<?$cor="999999"?>
.bordas_corp{
         border: 1px solid #cccccc;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
       }
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"> 
<table  border="0" cellspacing="0" cellpadding="0" width='100%' height='100%'>
  <tr> 
    <td  align="left" valign="top" bgcolor="#CCCCCC"> 
      <form name='form1'>
      <center>
         
<? 
if (isset($codigo) && $codigo!= "") {
	$rec="";
	$despacho="";	
	$result = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_andam(null," distinct pc11_codigo,pc01_descrmater,p62_codtran,p62_dttran,p62_hora,p62_coddeptorec,descrdepto,p58_despacho",null,"pc11_codigo=$codigo"));
	$numrows=$clsolicitemprot->numrows;
      
      
	 if($numrows>0){
	 	db_fieldsmemory($result,0); 
	 	?>
	 <table align='center'>
	 <br>
	 <br>
	 <br>
      <tr>
      <td align='left'><b>Item:</b><?=@$pc11_codigo."-".$pc01_descrmater?></td>
      
      </tr>
     
      </table>
      <br>
	 <br>
      <table border='1' cellspacing="0" cellpadding="0">
	    <?  echo "
	      <tr class='bordas'>
	        <td class='bordas' align='center'><b><small>Cod. Transferência</small></b></td>
			<td class='bordas' align='center'><b><small>Data</small></b></td>
			<td class='bordas' align='center'><b><small>Hora</small></b></td>
			<td class='bordas' align='center'><b><small>Depart. Destino</small></b></td>
			<td class='bordas' align='center'><b><small>Recebida</small></b></td>
			<td class='bordas' align='center'><b><small>Despacho</small></b></td>
   	       ";
     }else echo"<b>Nenhum registro encontrado...</b>";
	 	 echo " </tr>";
         for($i=0; $i<$numrows; $i++){
	    	db_fieldsmemory($result,$i);
	    	
	    	$result_trans=$clproctransand->sql_record($clproctransand->sql_query_proc(null,"*","p61_dtandam,p61_hora","p64_codtran=$p62_codtran"));
//	    	echo $clproctransand->sql_query_proc(null,"*","p62_dttran,p62_hora","p64_codtran=$p62_codtran").";<br>";
	    	if ($clproctransand->numrows>0){	    	
	    		db_fieldsmemory($result_trans,0);
	    		$rec="Sim";
	    		$despacho=$p61_despacho;
	    	}else{
	    		$rec="Não";
	    		$despacho=$p58_despacho;
	    	}	    		    	
	       	echo " 
              <tr>	    
   	            <td	 class='bordas_corp' align='center'><small>$p62_codtran</small></td>
   	            <td	 class='bordas_corp' align='center'><small>".db_formatar($p62_dttran,'d')."</small></td>
				<td	 class='bordas_corp' align='center'><small>$p62_hora</small></td>
   	            <td	 class='bordas_corp' align='left'><small>$p62_coddeptorec-$descrdepto</small></td>
				<td	 class='bordas_corp' align='center'><small>$rec</small></td>
   	            <td	 class='bordas_corp' align='left'><small>$despacho&nbsp;</small></td>
	           </tr> ";
	          if ($clproctransand->numrows>0){
	        	$result_desp=$clprocandamint->sql_record($clprocandamint->sql_query_file(null,"*","p78_data,p78_hora"," p78_codandam=$p64_codandam "));
	        	for($x=0;$x<$clprocandamint->numrows;$x++){
	        		db_fieldsmemory($result_desp,$x);
	        		echo " 
		              <tr>	    
		   	            <td	 class='bordas_corp' align='center'><small>&nbsp;</small></td>
		   	            <td	 class='bordas_corp' align='center'><small>".db_formatar($p78_data,'d')."</small></td>
						<td	 class='bordas_corp' align='center'><small>$p78_hora</small></td>
		   	            <td	 class='bordas_corp' align='left'><small>$p62_coddeptorec-$descrdepto</small></td>
						<td	 class='bordas_corp' align='center'><small>&nbsp;</small></td>
		   	            <td	 class='bordas_corp' align='left'><small>$p78_despacho&nbsp;</small></td>
			           </tr> ";
	        	}
	    	}  
	           
	     }
 }
?>     
 	</table>
    </form> 
    </center>
    </td>
  </tr>
</table>
<script>
</script>
</body>
</html>