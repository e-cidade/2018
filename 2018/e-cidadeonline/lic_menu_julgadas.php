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

include("libs/db_stdlib.php");
require_once("libs/db_utils.php");
?>
<html>
<head>
<title>Licitações</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<style type="text/css">
<?
db_estilosite()
?>
</style>
</head>

<?

db_logs(0,0,0,"Licitações em Aberto.");

/*
 * T. 43860
 * Separar as Licit. Julgadas, e indicar o numero do empenho
 * 
 */

$sSqlParam  = " select l12_tipoliberacaoweb,    ";
$sSqlParam .= "        l12_qtdediasliberacaoweb "; 
$sSqlParam .= "   from licitaparam              ";
$sSqlParam .= "  where l12_instit = ".db_getsession('DB_instit');

$rsSqlParam = db_query($sSqlParam);
$iNumRows   = pg_num_rows($rsSqlParam);
if ($iNumRows > 0) {

  $l12_tipoliberacaoweb     = db_utils::fieldsmemory($rsSqlParam, 0)->l12_tipoliberacaoweb; 
  $l12_qtdediasliberacaoweb = db_utils::fieldsmemory($rsSqlParam, 0)->l12_qtdediasliberacaoweb;
}

$data = date("Y-m-d");

$sWhere = "where l29_datapublic<='$data' and  l20_dataaber>='$data' ";

if ($l12_tipoliberacaoweb == 2) {

  $sWhere  = "left join liclicitasituacao on l11_liclicita = l20_codigo                 "; 
  $sWhere .= "      and l20_licsituacao =  l11_licsituacao ";
  $sWhere .= "where l29_datapublic<='$data'                                             ";
  $sWhere .= "      and  ( l11_licsituacao = 1                  ";
  $sWhere .= "      and l11_data +'$l12_qtdediasliberacaoweb days'::interval >='$data') ";
}

$sql = "select distinct  l03_descr,l03_codigo
		      from liclicita
		           inner join cflicita on l03_codigo=l20_codtipocom 
		           inner join liclicitaweb on l29_liclicita=l20_codigo
		           $sWhere
       ";
		           	       
$result = db_query($sql);                   
$linhas = pg_num_rows($result);
?>
<table width="100%" border="0" align= "top" cellpadding="0" cellspacing="0">
	<form name="form1" method="post" action="">
	<tr>
		<td width="20%" align= "top" valign="top">
			<table width="100%" border="0" align= "center" cellpadding="0" cellspacing="0">
				<tr><td>&nbsp;</td></tr>
				<tr><td >&nbsp;</td></tr>	
				<tr><td  align="center" class="texto" bgcolor="<?=$w01_corfundomenu?>"><b>LICITAÇÕES JULGADAS</b></td></tr>
				<tr><td >&nbsp;</td></tr>	
				<?
				for ($i = 0; $i < $linhas; $i++){
			    	db_fieldsmemory($result,$i);
			    	echo " 
				    <tr>
						<td width='100%' ><img src='imagens/seta.gif'><a class='links' href='licitacao.php?tipo=$l03_codigo&julgada=1' target='lic' >$l03_descr</a></td>
					</tr>
					<tr><td>&nbsp;</td></tr>";
			    }     	       
				?>
			</table>
		</td>
		<td width="80%" valign="top">
			<table width="100%" border="0" align= "center" cellpadding="0" cellspacing="0">
				<tr>
					<td><iframe name="lic" width="100%" height="800px" align="center"  marginheight="8" marginwidth="8" frameborder="0"  src="licitacao.php">
					</td>
				</tr>
			</table>
		</td>
	</tr>
	</form>
</table>
</body>
</html>