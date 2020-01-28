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
include("classes/db_procfiscal_classe.php");
include("classes/db_procfiscalinscr_classe.php");
include("classes/db_procfiscalmatric_classe.php");
include("classes/db_procfiscalsani_classe.php");
include("classes/db_procfiscalcgm_classe.php");
include("classes/db_procfiscalprot_classe.php");
include("classes/db_procfiscalfiscais_classe.php");
$clprocfiscal       = new cl_procfiscal;
$clprocfiscalinscr  = new cl_procfiscalinscr;
$clprocfiscalmatric = new cl_procfiscalmatric;
$clprocfiscalsani   = new cl_procfiscalsani;
$clprocfiscalcgm    = new cl_procfiscalcgm;
$clprocfiscalprot   = new cl_procfiscalprot;
$clprocfiscal->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
$clrotulo->label("nomeinst");
$clrotulo->label("y33_descricao");
$clrotulo->label("j01_matric");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
$clrotulo->label("q02_inscr");
$clrotulo->label("y80_codsani");
$clrotulo->label("p58_codproc");
$clrotulo->label("y100_sequencial");
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = true;

$sql = "
				select  y100_sequencial,
				        y101_numcgm,
								z01_nome,
								y100_dtinicial,
								y100_dtfinal,
								y100_obs,
								y100_coddepto, 
								descrdepto,
								y100_procfiscalcadtipo,
								y33_descricao,
								y103_inscr,
				        y102_matric
				from procfiscal 
				inner join db_depart     on db_depart.coddepto = procfiscal.y100_coddepto
				inner join procfiscalcgm on y101_procfiscal    = y100_sequencial
				inner join cgm           on cgm.z01_numcgm     = y101_numcgm
				inner join procfiscalcadtipo on y100_procfiscalcadtipo = y33_sequencial
				left  join procfiscalmatric on y102_procfiscal = y100_sequencial 
    		left  join procfiscalinscr  on y103_procfiscal = y100_sequencial
				where y100_sequencial = $procfiscal
       ";
			
$result = pg_query($sql);
$linhas = pg_num_rows($result);
db_fieldsmemory($result,0);

$sqlvistoria = "								
								select y71_inscr,y73_numcgm,y72_matric,y70_codvist,y70_tipovist,y77_descricao,y70_data,y70_parcial
								from procfiscalvistorias 
								inner join vistorias on y70_codvist = y109_codvist 
								inner join tipovistorias on y77_codtipo = y70_tipovist 
								left join vistmatric on y72_codvist=y70_codvist 
								left join vistcgm on y73_codvist=y70_codvist 
								left join vistinscr on y71_codvist=y70_codvist 
								where y109_procfiscal =	$procfiscal					";
								
$rsvistorias     = pg_query($sqlvistoria);								
$linhasvistorias = pg_num_rows($rsvistorias);			


$sqlnotificacao = "
									 select y36_numcgm,y35_matric,y34_inscr,y30_codnoti,y30_data,y30_nome 
									 from procfiscalnotificacao 
									 inner join fiscal on y30_codnoti = y110_notificacaofiscal 
									 left join fiscalcgm on y36_codnoti=y30_codnoti 
									 left join fiscalmatric on y35_codnoti=y30_codnoti 
									 left join fiscalinscr on y34_codnoti=y30_codnoti
									 where y110_procfiscal =$procfiscal ";
$rsnotificacao     = pg_query($sqlnotificacao);								
$linhasnotificacao = pg_num_rows($rsnotificacao);

$sqlauto = "select y54_numcgm,y53_matric,y52_inscr,y50_codauto,y50_data,y50_nome 
            from procfiscalauto 
						inner join auto       on y50_codauto = y111_auto 
						left  join autocgm    on y54_codauto = y50_codauto 
						left  join automatric on y53_codauto = y50_codauto 
						left  join autoinscr  on y52_codauto = y50_codauto 
						where y111_procfiscal = $procfiscal ";
$rsauto     = pg_query($sqlauto);								
$linhasauto = pg_num_rows($rsauto);	
	
$sqllevanta = "select y62_inscr,y93_numcgm,y60_codlev,y60_data 
               from procfiscallevanta 
							 inner join levanta  on y60_codlev = y112_levanta 
							 left  join levcgm   on y93_codlev = y60_codlev 
							 left  join levinscr on y62_codlev = y60_codlev 
							 where y112_procfiscal = $procfiscal";
$rslevanta     = pg_query($sqllevanta);								
$linhaslevanta = pg_num_rows($rslevanta);		

$sqlvarfix = "select q33_codigo,q33_inscr,z01_nome as nome_lanc ,q33_data 
              from procfiscalvarfix 
							inner join varfix on q33_codigo=y113_varfix 
							inner join issbase on q33_inscr=q02_inscr 
							inner join cgm on q02_numcgm=z01_numcgm 
							where y113_procfiscal = $procfiscal ";
$rsvarfix     = pg_query($sqlvarfix);								
$linhasvarfix = pg_num_rows($rsvarfix);		
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<br>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
	<form name="form1" method="post" action="<?=$db_action?>">
<table width="790" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td colspan = "2">
     &nbsp;
    </td>
  </tr> 

	<tr>
    <td colspan = "2">
    	<fieldset><Legend align="center"><b>Dados do Processo Fiscal</b></legend>
        <table border="0" width="100%" >
        	<tr>
        		<td width="20%" ><b>Processo fiscal:</b></td>
					  <td><?=$procfiscal?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Tipo:</b> <?=$y100_procfiscalcadtipo?> - <?=$y33_descricao?> </td>
					</tr>
        	<tr>
        		<td><b>Data inicial:</b></td>
					  <td><?=db_formatar($y100_dtinicial,"d")?> </td>
					</tr>
					<tr>
        		<td><b>Data final:</b></td>
					  <td><?=db_formatar($y100_dtfinal,"d")?></td>
					</tr>
					<tr>
        		<td><b>Departamento:</b></td>
					  <td><?=$y100_coddepto?> - <?=$descrdepto?></td>
					</tr>
					<tr>
        		<td><b>Observação:</b></td>
					  <td><?=$y100_obs?></td>
					</tr>
			
        </table>
			</fieldset>
			<fieldset><Legend align="center"><b>Dados do Contribuinte</b></legend>
        <table border="0" width="100%" >
        	<tr>
        		<td  width="20%"><b>Contribuinte:</b></td>
					  <td><?=$z01_nome?> </td>
					</tr>
        	<tr>
        		<td><b>CGM:</b></td>
					  <td><?=$y101_numcgm?> </td>
					</tr>	
					<tr>
        		<td><b>Matrícula:</b></td>
					  <td><?=$y102_matric?> </td>
					</tr>	
					<tr>
        		<td><b>Inscrição:</b></td>
					  <td><?=$y103_inscr?> </td>
					</tr>	

        </table>
			</fieldset>
			<fieldset><Legend align="center"><b>Vistorias</b></legend>
			  
				<? 
				 if($linhasvistorias > 0){
				 	echo "
					<table border='0' width='100%' class='tab_cinza'>
				 	 <tr>
					   <th>Vistoria</th>
						 <th>Tipo de vistoria</th>
						 <th>Data</th>
						 <th>Parcial</th>
						 <th>Vistoria por</th>
					 </tr>";
				 	 for($v=0 ; $v<$linhasvistorias ; $v++){
				 	 	 db_fieldsmemory($rsvistorias, $v);
						 
						 if($y70_parcial=="t"){
						 	$y70_parcial = "Sim";
						 }else{
						 	$y70_parcial = "Não";
						 }
						 	echo "
						 	 <tr>
							   <td>$y70_codvist</td>
								 <td>$y77_descricao</td>
								 <td>".db_formatar($y70_data,"d")."</td>
								 <td>$y70_parcial</td>";
							 if($y73_numcgm!=""){
							 	 echo "<td>CGM - $y73_numcgm</td>";
							 }elseif($y72_matric!=""){
							 	 echo "<td>Matricula - $y72_matric</td>";
							 }elseif($y71_inscr!=""){
							 	 echo "<td>Inscrição - $y71_inscr</td>";
							 }
							 
							 	 
							echo "	 
							 </tr>";
						 
				 	 }
					
				 }else{
				 	echo "
				 	 <table border='0' width='100%' >
					   <tr><td align='center'> <b>Nenhuma vistoria encontrada para este processo fiscal!.</b></td></tr>
						 ";
				 }
		?>
   		</table>
			</fieldset>
			
			<fieldset><Legend align="center"><b>Notificação fiscal</b></legend>
				<? 
				 if($linhasnotificacao > 0){
				 	echo "
					<table border='0' width='100%' class='tab_cinza'>
				 	 <tr>
					   <th>Codigo da Notificação</th>
						 <th>Nome</th>
						 <th>Data</th>
						 <th>Notificação por</th>
					 </tr>";
				 	 for($n=0 ; $n<$linhasnotificacao ; $n++){
				 	 	 db_fieldsmemory($rsnotificacao, $n);
						 echo "
						 	 <tr>
							   <td>$y30_codnoti</td>
								 <td>$y30_nome</td>
								 <td>".db_formatar($y30_data,"d")."</td>";
								if($y36_numcgm!=""){
							 	 echo "<td>CGM - $y36_numcgm</td>";
							 }elseif($y35_matric!=""){
							 	 echo "<td>Matricula - $y35_matric</td>";
							 }elseif($y34_inscr!=""){
							 	 echo "<td>Inscrição - $y34_inscr</td>";
							 }
							 echo "
							 </tr>";
				 	 }
				 }else{
				 	echo "
				 	 <table border='0' width='100%' >
					   <tr><td align='center'> <b>Nenhuma notificação encontrada para este processo fiscal!.</b></td></tr>
						 ";
				 }
		?> 
   		</table>
			</fieldset>
			<fieldset><Legend align="center"><b>Auto de infração</b></legend>
				<? 
				 if($linhasauto > 0){
				 	echo "
					<table border='0' width='100%' class='tab_cinza'>
				 	 <tr>
					   <th>Codigo do auto</th>
						 <th>Nome</th>
						 <th>Data</th>
						 <th>Auto de infração por</th>
					 </tr>";
				 	 for($a=0 ; $a<$linhasauto ; $a++){
				 	 	 db_fieldsmemory($rsauto, $a);
						 echo "
						 	 <tr>
							   <td>$y50_codauto</td>
								 <td>$y50_nome</td>
								 <td>".db_formatar($y50_data,"d")."</td>";
								if($y54_numcgm!=""){
							 	 echo "<td>CGM - $y54_numcgm</td>";
							 }elseif($y53_matric!=""){
							 	 echo "<td>Matricula - $y53_matric</td>";
							 }elseif($y52_inscr!=""){
							 	 echo "<td>Inscrição - $y52_inscr</td>";
							 }
							 echo "
							 </tr>";
				 	 }
				 }else{
				 	echo "
				 	 <table border='0' width='100%' >
					   <tr><td align='center'> <b>Nenhum auto de infração encontrada para este processo fiscal!.</b></td></tr>
						 ";
				 }
		?>
   		</table>
			</fieldset>
			<fieldset><Legend align="center"><b>Levantamento fiscal</b></legend>
				<?  
				 if($linhaslevanta > 0){
				 	echo "
					<table border='0' width='100%' class='tab_cinza'>
				 	 <tr>
					   <th>Codigo</th>
						 <th>Data</th>
						 <th>Auto de infração por</th>
					 </tr>";
				 	 for($l=0 ; $l<$linhaslevanta ; $l++){
				 	 	 db_fieldsmemory($rslevanta, $l);
						 echo "
						 	 <tr>
							   <td>$y60_codlev</td>
								 <td>".db_formatar($y60_data,"d")."</td>";
								if($y93_numcgm!=""){
							 	 echo "<td>CGM - $y93_numcgm</td>";
							 }elseif($y62_inscr!=""){
							 	 echo "<td>Inscrição - $y62_inscr</td>";
							 }
							 echo "
							 </tr>";
				 	 }
				 }else{
				 	echo "
				 	 <table border='0' width='100%' >
					   <tr><td align='center'> <b>Nenhum Levantamento fiscal encontrado para este processo fiscal!.</b></td></tr>
						 ";
				 }
		?>
   		</table>
			</fieldset>
			</fieldset>
			<fieldset><Legend align="center"><b>Lançamento de estimativa</b></legend>
				<?   
				 if($linhasvarfix > 0){
				 	echo "
					<table border='0' width='100%' class='tab_cinza'>
				 	 <tr>
					   <th>Codigo</th>
						 <th>Data</th>
						 <th>Inscrição</th>
						 <th>Nome</th>
					 </tr>";
				 	 for($e=0 ; $e<$linhasvarfix ; $e++){
				 	 	 db_fieldsmemory($rsvarfix, $e);
						 echo "
						 	 <tr>
							   <td>$q33_codigo</td>
								 <td>".db_formatar($q33_data,"d")."</td>
								 <td>$q33_inscr</td>
								 <td>$nome_lanc</td>
							 </tr>";
				 	 }
				 }else{
				 	echo "
				 	 <table border='0' width='100%' >
					   <tr><td align='center'> <b>Nenhum Lançamento de estimativa encontrado para este processo fiscal!.</b></td></tr>
						 ";
				 }
		?>
   		</table>
			</fieldset>
    </td>
  </tr> 
  <tr>
    <td colspan = "2">
     &nbsp;
    </td>
  </tr> 
  

</table>
</form>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

</body>
</html>

<script>
		
</script>