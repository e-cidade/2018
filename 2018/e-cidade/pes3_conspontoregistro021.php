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

set_time_limit(0);
require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_sql.php");
include ("dbforms/db_funcoes.php");

/*
include("classes/db_gerfsal_classe.php");
include("classes/db_gerfadi_classe.php");
include("classes/db_gerffx_classe.php");
include("classes/db_gerfcom_classe.php");
include("classes/db_gerffer_classe.php");
include("classes/db_gerfs13_classe.php");
include("classes/db_gerfres_classe.php");
*/

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);

$xtipo = "'x'";
if ($opcao == 'salario') {
	$sigla = 'r10_';
	$arquivo = 'pontofs';
}
elseif ($opcao == 'ferias') {
	$sigla = 'r29_';
	$arquivo = 'pontofe';
	$xtipo = ' r29_tpp ';
}
elseif ($opcao == 'rescisao') {
	$sigla = 'r19_';
	$arquivo = 'pontofr';
	$xtipo = ' r19_tpp ';
}
elseif ($opcao == 'adiantamento') {
	$sigla = 'r21_';
	$arquivo = 'pontofa';
}
elseif ($opcao == '13salario') {
	$sigla = 'r34_';
	$arquivo = 'pontof13';
}
elseif ($opcao == 'complementar') {
	$sigla = 'r47_';
	$arquivo = 'pontocom';
}
elseif ($opcao == 'fixo') {
	$sigla = 'r90_';
	$arquivo = 'pontofx';
} else {
	echo "SELECIONE ALGUMA OPÇÃO";
}

if (trim($opcao) != '') {
	$sql = "
	          select distinct
                   rh27_rubric,
                   rh27_descr,
                   case 
                     when rh27_pd = 1 then 'PROVENTO' 
                     when rh27_pd = 2 then 'DESCONTO'
                     else 'BASE' 
                   end as pd,
                   ".$sigla."valor as valor,
                   ".$sigla."quant as quant 
	          from ".$arquivo." 
	               inner join rhrubricas on rh27_rubric    = ".$arquivo.".".$sigla."rubric
								                      and rh27_instit    = ".$arquivo.".".$sigla."instit 
	               inner join rhpessoalmov on rh02_regist  = ".$arquivo.".".$sigla."regist
	                                      and rh02_anousu  = ".$arquivo.".".$sigla."anousu
	                                      and rh02_mesusu  = ".$arquivo.".".$sigla."mesusu
																			  and rh02_instit  = ".$arquivo.".".$sigla."instit
                 inner join rhpessoal    on rh01_regist  = rh02_regist                         
	               inner join rhlota       on r70_codigo   = rh02_lota
																			  and r70_instit   = ".$arquivo.".".$sigla."instit
	               inner join cgm        on cgm.z01_numcgm =rh01_numcgm
	          where     ".$sigla."regist = $matricula 
	                and ".$sigla."anousu = $ano 
	                and ".$sigla."mesusu = $mes
									and ".$sigla."instit = ".db_getsession("DB_instit")."
	  ";
//	  die($sql);
	$result = db_query($sql);
}
//echo $sql;die();
//db_criatabela($result);exit;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style>
.fonte {
  font-family:Arial, Helvetica, sans-serif;
  font-size:12px;
}
td {
  font-family:Arial, Helvetica, sans-serif;
  font-size:12px;

}
th {
  font-family:Arial, Helvetica, sans-serif;
  font-size:12px;
}
</style>

<script>
function MM_reloadPage(init){  //reloads the window if Nav4 resized
  if(init==true) with (navigator){
    if((appName=="Netscape")&&(parseInt(appVersion)==4)) {
      document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; 
  }
}else if(innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH)
   location.reload();
}
MM_reloadPage(true);
</script>
</head>
<body bgcolor=#CCCCCC onload="parent.document.getElementById('processando').style.visibility = 'hidden'">
<center>

<form name="form1" method="post">
<table border="1" cellpadding="0" cellspacing="0">
<?


if (trim($opcao) != '') {
?>
   <tr bgcolor="#FFCC66">
     <th class="borda" style="font-size:12px" nowrap>Rubrica</th>
     <th class="borda" style="font-size:12px" nowrap>Descrição</th>
     <th class="borda" style="font-size:12px" nowrap>Prov / Desc</th>
     <th class="borda" style="font-size:12px" nowrap>Quantidade</th>
     <th class="borda" style="font-size:12px" nowrap>Valor</th>
   </tr>
    <?


	$cor = "#EFE029";
	$totalvalor = 0;
	$totalquant = 0;
	$totalregis = 0;
	for ($x = 0; $x < pg_numrows($result); $x ++) {
		db_fieldsmemory($result, $x, true);
		if ($cor == "#EFE029")
			$cor = "#E4F471";
		else
			if ($cor == "#E4F471")
				$cor = "#EFE029";

		$totalvalor += $valor;
		$totalquant += $quant;
		$totalregis ++;
?>
    <tr>
      <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>">
        <?db_ancora($rh27_rubric,"js_consultarubric('$rh27_rubric','$matricula');","1");?>
        &nbsp;
      </td>
      <td align="left" style="font-size:12px" bgcolor="<?=$cor?>">&nbsp;<?=$rh27_descr?></td>
      <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$pd?></td>
      <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=db_formatar($quant,'f')?></td>
      <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=db_formatar($valor,'f')?></td>
    </tr>
    <?
	}
	?>
	<tr>
      <td align="right" style="font-size:12px" nowrap bgcolor="#FFCC66" colspan="2">&nbsp;<strong>Totais</strong></td>
      <td align="right" style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;<strong><?=$totalregis?></strong></td>
      <td align="right" style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;<strong><?=db_formatar($totalquant,"f")?></strong></td>
      <td align="right" style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;<strong><?=db_formatar($totalvalor,'f')?></strong></td>
	</tr>
<?
}
?>
</table>
<table
<tr>
   <td colspan="6" >&nbsp;&nbsp;</td>
</tr>
<tr>
<input type="hidden" name="matricula" value="<?=@$matricula?>">
<input type="hidden" name="numcgm" value="<?=@$numcgm?>">
<!--<td colspan="6" align="center"><input type="button" value="Imprimir" title="Imprime Relatório" onClick="js_relatorio();"></td>-->
</tr>
</table>
</form>
</center>
</body>
<script>

function js_consultarubric(rubrica,registro){
	var sUrl = "pes3_codfinanc002.php?";
      sUrl = sUrl+"iAno=<?=($ano)?>";
      sUrl = sUrl+"&iMes=<?=($mes)?>";
      sUrl = sUrl+"&rubrica="+rubrica;
      sUrl = sUrl+"&sigla=<?=str_replace("_","",$sigla)?>";
      sUrl = sUrl+"&arquivo=<?=$arquivo?>";
      sUrl = sUrl+"&r01_regist=<?=$matricula?>";
      sUrl = sUrl+"&xopcao=<?=$opcao?>";
      sUrl = sUrl+"&chamada_origem=pes3_conspontoregistro002.php";
      parent.location.href = sUrl;
}
</script>
</html>