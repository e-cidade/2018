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
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
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
<!--      <input type='button' value='Voltar' onclick="parent.db_iframe_lanca.hide();" >    -->
    <br>
    <br>
    </td>
  </tr>
<?
if (!isset($filtroquery)){
if (isset($codigo)&&$codigo!="") {
  $sql= "select m71_codlanc as \"dl_Código Lanc.\",
	       	    m60_descr,
                m71_data,
                m77_lote,
     	        m77_dtvalidade,
     	        m76_nome,
                m71_quant,
		         m71_valor,
     	        m71_quantatend,
		m75_quant as \"dl_Quant. de Unidade\" ,
		m61_descr as \"dl_Unidade\",
		m75_quantmult as \"dl_Quant. por Unid.\",
		m82_quant as \"dl_Quantidade Executada\"
	from matestoqueinimei 
		inner join matestoqueitem on matestoqueinimei.m82_matestoqueitem = matestoqueitem.m71_codlanc 
		inner join matestoque on matestoqueitem.m71_codmatestoque = matestoque.m70_codigo
		inner join matmater on matestoque.m70_codmatmater = m60_codmater
		left join matestoqueitemunid on matestoqueitemunid.m75_codmatestoqueitem = matestoqueitem.m71_codlanc
		left join matunid on  matestoqueitemunid.m75_codmatunid = matunid.m61_codmatunid
		left join matestoqueitemlote on  m71_codlanc = m77_matestoqueitem
		left join matestoqueitemfabric on  m71_codlanc = m78_matestoqueitem
		left join matfabricante on  m76_sequencial = m78_matfabricante
		left  join matestoqueitemlanc on m95_codlanc = m71_codlanc
	where m95_codlanc is null and m82_matestoqueini=$codigo";
}
}
  db_lovrot($sql,15,"()","","");
/*	
  $result=pg_exec($sql);
  $numrows = pg_numrows($result);
  if($numrows>0){
    echo "<tr class='bordas'>
	      <td class='bordas' align='center'><b><small>Data</small></b></td>
	      <td class='bordas' align='center'><b><small>Quantidade</small></b></td>
	      <td class='bordas' align='center'><b><small>Valor</small></b></td>
	      <td class='bordas' align='center'><b><small>Quant. Atend</small></b></td>
	      <td class='bordas' align='center'><b><small>Quant. Executada </small></b></td>
          </tr>";
  }else echo"<b>Nenhum registro encontrado...</b>";
  for($i=0; $i<$numrows; $i++){
    db_fieldsmemory($result,$i);
     echo "
           <tr>	    
	     <td class='bordas_corp' align='center'><small>".db_formatar($m71_data,'d')."</small></td>		    
	     <td class='bordas_corp' align='center'><small>$m71_quant</small></td>
	     <td class='bordas_corp' align='center'><small>".db_formatar($m71_valor,'f')."</small></td>
	     <td class='bordas_corp' align='center'><small>$m71_quantatend</small></td>
	     <td class='bordas_corp' align='center'><small>$m82_quant</small></td>
	   </tr>
	   ";
  }*/

?>     
</table>

</td>
</tr>
</table>
<script>
</script>
</body>
</html>