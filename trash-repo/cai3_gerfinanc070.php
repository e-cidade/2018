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

$sql = "
		select distinct v07_parcel,v07_numpre,k00_numpre,v07_dtlanc,z01_nome,v07_totpar,v07_valor,v09_data,v09_hora,nome,k00_tipo
		from termo 
		inner join cgm         on z01_numcgm = v07_numcgm
		inner join termoanu    on v09_parcel = v07_parcel
		inner join db_usuarios on id_usuario = v09_usuario
		inner join arreold     on k00_numpre = v07_numpre
		where v07_situacao = 2 and v07_numcgm = $cgm and v07_instit =	".db_getsession('DB_instit') ;

$result = pg_query($sql);
$linhas = pg_num_rows($result);


?>

<html>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<style>

th {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: #000000;
}
input {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 11px;
	color: black;
	height: 17px;
}
a {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 11px;
	color: black;
	text-decoration: none;
}
a:hover {
  text-decoration: underline;
}
td {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 11px;
	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: #000000;
}
.tabs {
  border: none;
}

</style>
<form name="form1" method="post" action="">
<?
if ($linhas>0){
?>
<table border= "0 " cellspacing= "0 " cellpadding= "3 " id= "tabdebitos ">
	<tr bgcolor= "#FFCC66 ">
		<th title= "Outras Informações "    		   class= "borda " style= "font-size:12px " nowrap>O</th>
		<th title= "Código do Parcelamento " 	 	   class= "borda " style= "font-size:12px " nowrap>Cód. do Parcel.</th>
		<th title= "Código de Arrecadação "  		   class= "borda " style= "font-size:12px " nowrap>Numpre</th>
		<th title= "Total de Parcelas "                class= "borda " style= "font-size:12px " nowrap>T</th>
		<th title= "Data do Parcelamento "             class= "borda " style= "font-size:12px " nowrap>Data do Parcel.</th>
		<th title= "Responsavel "                      class= "borda " style= "font-size:12px " nowrap>Responsavel</th>
		<th title= "Valor do Parcelamento "            class= "borda " style= "font-size:12px " nowrap>Valor do Parcel.</th>
		<th title= "Data da anulação do Parcelamento " class= "borda " style= "font-size:12px " nowrap>Data da Anulação</th>
		<th title= "Hora da anulação do Parcelamento " class= "borda " style= "font-size:12px " nowrap>Hora da anulação</th>
		<th title= "Usuário que efetuou a anulação "   class= "borda " style= "font-size:12px " nowrap>Usuário</th>
	</tr>
	<?
	for($i = 0;$i < $linhas;$i++) {
		db_fieldsmemory($result,$i);
		if($i % 2 == 0){
	  		$cor = "#E4F471"; 
	    }else{
	    	$cor = "#EFE029";
		}
      ?>
	<tr bgcolor="<?=$cor?>">
	<?
	
	echo "<td class= \"borda \" style= \"font-size:11px \" nowrap align =\"center\"onclick=\"parent.js_mostradetalhes('cai3_gerfinanc005.php?".base64_encode($k00_tipo."#".$v07_numpre."#"."0")."&mostra=nao','','width=600,height=500,scrollbars=1')\">
		  <a href=\"\" onclick=\"return false;\">MI</a></td>";
    
	?> 
        
      <td class= "borda " style= "font-size:11px " nowrap align ="center"><?=$v07_parcel?></td>
      <td class= "borda " style= "font-size:11px " nowrap align ="center"><?=$v07_numpre?></td>	  
      <td class= "borda " style= "font-size:11px " nowrap align ="center"><?=$v07_totpar?></td>
      <td class= "borda " style= "font-size:11px " nowrap align ="center"><?=db_formatar($v07_dtlanc,"d")?></td>	  
      <td class= "borda " style= "font-size:11px " nowrap align ="center"><?=$z01_nome?></td>
      <td class= "borda " style= "font-size:11px " nowrap align ="right" ><?=db_formatar($v07_valor,"f")?></td>	  
      <td class= "borda " style= "font-size:11px " nowrap align ="center"><?=db_formatar($v09_data,"d")?></td>
      <td class= "borda " style= "font-size:11px " nowrap align ="center"><?=$v09_hora?></td>	  
      <td class= "borda " style= "font-size:11px " nowrap align ="center"><?=$nome?></td>
     </tr>
     <?
    }
	 ?>
   
</table>
</form>
</html>
<?
}
?>