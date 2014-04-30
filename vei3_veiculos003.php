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
include("classes/db_veicretirada_classe.php");
include("classes/db_veicmanut_classe.php");
include("classes/db_veicabast_classe.php");
include("classes/db_veiculos_classe.php");
include("classes/db_veictipoabast_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clveicretirada  = new cl_veicretirada;
$clveicmanut     = new cl_veicmanut;
$clveicabast     = new cl_veicabast;
$clveiculos      = new cl_veiculos;
$clveictipoabast = new cl_veictipoabast;

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
if (isset($veiculo)&&$veiculo!="") {
  $result=$clveiculos->sql_record($clveiculos->sql_query($veiculo));
  db_fieldsmemory($result,0);

  $result_veictipoabast = $clveictipoabast->sql_record($clveictipoabast->sql_query($ve01_veictipoabast,"ve07_sigla"));
  if ($clveictipoabast->numrows > 0){
    db_fieldsmemory($result_veictipoabast,0);
  } 

  if ($tipo=="R"){
    $result=$clveicretirada->sql_record($clveicretirada->sql_query_info(null,"distinct ve60_codigo,z01_nome,ve60_datasaida,ve60_horasaida,ve60_medidasaida,ve60_destino,ve73_veicabast,ve65_veicmanut,ve61_datadevol","ve60_datasaida,ve60_horasaida"," ve60_veiculo = $veiculo"));
    $numrows = $clveicretirada->numrows;
    if($numrows>0){
      echo "<tr class='bordas'>
		<td class='bordas' align='center'><b><small>Retirada</small></b></td>
		<td class='bordas' align='center'><b><small>Motorista</small></b></td>
		<td class='bordas' align='center'><b><small>Data Retirada</small></b></td>
		<td class='bordas' align='center'><b><small>Hora Retirada</small></b></td>
		<td class='bordas' align='center'><b><small>Medida</small></b></td>
		<td class='bordas' align='center'><b><small>Destino</small></b></td>
		<td class='bordas' align='center'><b><small>Abastecimento</small></b></td>
		<td class='bordas' align='center'><b><small>Manutenção</small></b></td>
		<td class='bordas' align='center'><b><small>Data Devolução</small></b></td>
	    </tr>";
    }else echo"<b>Nenhum registro encontrado...</b>";
    for($i=0; $i<$numrows; $i++){
      db_fieldsmemory($result,$i);
       echo "
	     <tr>	    
	       <td class='bordas_corp' align='center'><small><a href='vei3_veicretirada002.php?codigo=$ve60_codigo' >$ve60_codigo</a> </small></td>		    
	       <td class='bordas_corp' align='center'><small>$z01_nome </small></td>		    
	       <td class='bordas_corp' align='center'><small>".db_formatar($ve60_datasaida,"d")."</small></td>		    
	       <td class='bordas_corp' align='center'><small>$ve60_horasaida </small></td>		    
	       <td class='bordas_corp' align='center'><small>$ve60_medidasaida&nbsp;&nbsp;$ve07_sigla</small></td>		    
	       <td class='bordas_corp' align='center'><small>$ve60_destino </small></td>		    
		   <td class='bordas_corp' align='center'><small><a href='vei3_veicabast002.php?codigo=$ve73_veicabast' >$ve73_veicabast</a>&nbsp; </small></td>
		   <td class='bordas_corp' align='center'><small><a href='vei3_veicmanut002.php?codigo=$ve65_veicmanut' >$ve65_veicmanut</a> &nbsp;</small></td>
		   <td class='bordas_corp' align='center'><small>".db_formatar($ve61_datadevol,"d")."</small></td>
	     </tr>
	     ";
    }
  }else if ($tipo=="A"){
    $result=$clveicabast->sql_record($clveicabast->sql_query_info(null,"*","ve70_dtabast,ve70_medida,ve70_codigo"," ve70_veiculos= $veiculo "));
    $numrows = $clveicabast->numrows;
    if($numrows>0){
      echo "<tr class='bordas'>
		<td class='bordas' align='center'><b><small>Abastecimento</small></b></td>
		<td class='bordas' align='center'><b><small>Combustível</small></b></td>
		<td class='bordas' align='center'><b><small>Data Abastecimento</small></b></td>
	        <td class='bordas' align='center'><b><small>Litros</small></b></td>
		<td class='bordas' align='center'><b><small>Valor do Litro</small></b></td>
		<td class='bordas' align='center'><b><small>Valor Abastecido</small></b></td>
		<td class='bordas' align='center'><b><small>Medida</small></b></td>
		<td class='bordas' align='center'><b><small>Retirada</small></b></td>
		<td class='bordas' align='center'><b><small>Data Anulação</small></b></td>

	    </tr>";
    }else echo"<b>Nenhum registro encontrado...</b>";
    for($i=0; $i<$numrows; $i++){
      db_fieldsmemory($result,$i);
       echo "
	     <tr>	    
	       <td class='bordas_corp' align='center'><small><a href='vei3_veicabast002.php?codigo=$ve70_codigo' >$ve70_codigo</a></small></td>		    
	       <td class='bordas_corp' align='center'><small>$ve26_descr </small></td>		    
	       <td class='bordas_corp' align='center'><small>".db_formatar($ve70_dtabast,"d")."</small></td>		    
	       <td class='bordas_corp' align='center'><small>$ve70_litros</small></td>		    
	       <td class='bordas_corp' align='center'><small>".db_formatar($ve70_vlrun,"f","0",strlen($ve70_vlrun),"d",3)."</small></td>		    
	       <td class='bordas_corp' align='center'><small>".db_formatar($ve70_valor,"f")."</small></td>		    
	       <td class='bordas_corp' align='center'><small>$ve70_medida&nbsp;&nbsp;$ve07_sigla</small></td>
		   <td class='bordas_corp' align='center'><small><a href='vei3_veicretirada002.php?codigo=$ve73_veicretirada' >$ve73_veicretirada</a> &nbsp;</small></td>
		   <td class='bordas_corp' align='center'><small>".db_formatar($ve74_data,"d")."</small></td>		    
	     </tr>
	     ";
    }
  }else if ($tipo=="M"){
    $result=$clveicmanut->sql_record($clveicmanut->sql_query_info(null,"distinct ve62_codigo,ve62_dtmanut,ve62_vlrmobra,ve62_vlrpecas,ve62_descr,ve62_notafisc,ve62_medida,ve28_descr,ve65_veicretirada","ve62_dtmanut,ve62_codigo"," ve62_veiculos= $veiculo "));
    $numrows = $clveicmanut->numrows;
    if($numrows>0){
      echo "<tr class='bordas'>
		<td class='bordas' align='center'><b><small>Manutenção</small></b></td>
		<td class='bordas' align='center'><b><small>Data</small></b></td>
		<td class='bordas' align='center'><b><small>Valor Mão de Obra</small></b></td>
		<td class='bordas' align='center'><b><small>Valor em Peças</small></b></td>
		<td class='bordas' align='center'><b><small>Serviço Executado</small></b></td>
		<td class='bordas' align='center'><b><small>Nº Nota Fiscal</small></b></td>
		<td class='bordas' align='center'><b><small>Medida</small></b></td>
		<td class='bordas' align='center'><b><small>Tipo de Serviço</small></b></td>
		<td class='bordas' align='center'><b><small>Retirada</small></b></td>		
	    </tr>";
    }else echo"<b>Nenhum registro encontrado...</b>";
    for($i=0; $i<$numrows; $i++){
      db_fieldsmemory($result,$i);
       echo "
	     <tr>	    
	       <td class='bordas_corp' align='center'><small><a href='vei3_veicmanut002.php?codigo=$ve62_codigo' >$ve62_codigo</a> </small></td>
		 <td class='bordas_corp' align='center'><small>".db_formatar($ve62_dtmanut,"d")."</small></td>		    
	      <td class='bordas_corp' align='center'><small>".db_formatar($ve62_vlrmobra,"f")."</small></td>
		<td class='bordas_corp' align='center'><small>".db_formatar($ve62_vlrpecas,"f")."</small></td>
	       <td class='bordas_corp' align='center'><small>$ve62_descr</small></td>
	       <td class='bordas_corp' align='center'><small>$ve62_notafisc</small></td>
	       <td class='bordas_corp' align='center'><small>$ve62_medida&nbsp;&nbsp;$ve07_sigla</small></td>
	       <td class='bordas_corp' align='center'><small>$ve28_descr</small></td>
<td class='bordas_corp' align='center'><small><a href='vei3_veicretirada002.php?codigo=$ve65_veicretirada' >$ve65_veicretirada</a> &nbsp;</small></td>
	     </tr>
	     ";
    }
  }
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