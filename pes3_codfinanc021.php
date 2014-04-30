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

if(!isset($opcao)){
  $opcao = "";
}
$xtipo = "'x'";
if ($opcao == 'salario') {
	$sigla = 'r14_';
	$arquivo = 'gerfsal';
}
elseif ($opcao == 'ferias') {
	$sigla = 'r31_';
	$arquivo = 'gerffer';
	$xtipo = ' r31_tpp ';
}
elseif ($opcao == 'rescisao') {
	$sigla = 'r20_';
	$arquivo = 'gerfres';
	$xtipo = ' r20_tpp ';
}
elseif ($opcao == 'adiantamento') {
	$sigla = 'r22_';
	$arquivo = 'gerfadi';
}
elseif ($opcao == '13salario') {
	$sigla = 'r35_';
	$arquivo = 'gerfs13';
}
elseif ($opcao == 'complementar') {
	$sigla = 'r48_';
	$arquivo = 'gerfcom';
}
elseif ($opcao == 'fixo') {
	$sigla = 'r53_';
	$arquivo = 'gerffx';
} else {
	echo "SELECIONE ALGUMA OPÇÃO";
}

if (trim($opcao) != '') {
	$sql = "
	          select distinct rh01_regist as r01_regist,z01_nome,".$sigla."valor as valor,".$sigla."quant as quant,r70_codigo as r13_codigo,r70_descr as r13_descr
	          from ".$arquivo."
                       inner join rhrubricas   on rhrubricas.rh27_rubric      = ".$arquivo.".".$sigla."rubric
											                        and rhrubricas.rh27_instit      = ".$arquivo.".".$sigla."instit
                       inner join rhpessoalmov on rhpessoalmov.rh02_anousu    = ".$arquivo.".".$sigla."anousu
                                              and rhpessoalmov.rh02_mesusu    = ".$arquivo.".".$sigla."mesusu
                                              and rhpessoalmov.rh02_regist    = ".$arquivo.".".$sigla."regist
                                              and rhpessoalmov.rh02_instit    = ".db_getsession("DB_instit")."
                       inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                       inner join rhlota       on rhlota.r70_codigo           = rhpessoalmov.rh02_lota  
											                        and rhlota.r70_instit           = rhpessoalmov.rh02_instit   
	               	   inner join cgm          on cgm.z01_numcgm                = rhpessoal.rh01_numcgm
	          where     ".$sigla."rubric = '$rubric' 
	                and ".$sigla."anousu = $ano 
	                and ".$sigla."mesusu = $mes
									and ".$sigla."instit = ".db_getsession("DB_instit")."
	          order by z01_nome
	  ";

	$result = pg_exec($sql);
}
/*
flush();
echo "<script>parent.document.getElementById('processando').style.visibility = 'hidden'</script>";
die($opcao);
echo $sql;die();
*/
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

<tr>
<!--<td colspan="5" align="center"><font face="Arial" size="3"><strong>Outras Matrículas</strong><font><br></td>-->
</tr>
<table border="1" cellpadding="0" cellspacing="0">
<?


if (trim($opcao) != '') {
?>
   <tr bgcolor="#FFCC66">
     <th class="borda" style="font-size:12px" nowrap>Registro</th>
     <th class="borda" style="font-size:12px" nowrap>Nome</th>
     <th class="borda" style="font-size:12px" nowrap>Lotação</th>
     <th class="borda" style="font-size:12px" nowrap>Descrição</th>
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
        <?db_ancora($r01_regist,"js_consultaregistro('$r01_regist','$rubric');","1");?>
        &nbsp;
      </td>
      <td align="left" style="font-size:12px" bgcolor="<?=$cor?>">&nbsp;<?=$z01_nome?></td>
      <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$r13_codigo?></td>
      <td align="left" style="font-size:12px" bgcolor="<?=$cor?>">&nbsp;<?=$r13_descr?></td>
      <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=db_formatar($quant,'f')?></td>
      <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=db_formatar($valor,'f')?></td>
    </tr>
    <?
	}
	?>
	<tr>
      <td align="right" style="font-size:12px" nowrap bgcolor="#FFCC66" colspan="3">&nbsp;<strong>Totais</strong></td>
      <td align="right" style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;<strong><?=$totalregis?></strong></td>
      <td align="right" style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;<strong><?=db_formatar($totalquant,'f')?></strong></td>
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
function js_consultaregistro(registro,rubrica){
  js_OpenJanelaIframe('top.corpo','db_iframe_conspessoal','pes3_conspessoal002.php?regist='+registro,'Visualização das matriculas cadastradas',true);
//  parent.location.href = "pes3_gerfinanc001.php?voltarcorreto=pes3_codfinanc001.php&ano=<?=($ano)?>&mes=<?=($mes)?>&matricula="+registro+"&rubric="+rubrica+"&pesquisar=Atualizar&xopcao=<?=$opcao?>";
}
</script>
</html>