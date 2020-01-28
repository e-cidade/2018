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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$oGet = db_utils::postMemory($_GET);

if ($sigla == "") {
	echo "SELECIONE ALGUMA OPÇÃO";
	die();
}

$sSql = "select distinct 
                rh01_regist      as r01_regist,
                z01_nome,
                {$oGet->sigla}_valor as valor,
                {$oGet->sigla}_quant as quant,
                r70_codigo       as r13_codigo,
                r70_descr        as r13_descr
          from {$oGet->arquivo}
               inner join rhrubricas   on rhrubricas.rh27_rubric    = {$oGet->arquivo}.{$oGet->sigla}_rubric
										                  and rhrubricas.rh27_instit    = {$oGet->arquivo}.{$oGet->sigla}_instit
               inner join rhpessoalmov on rhpessoalmov.rh02_anousu  = {$oGet->arquivo}.{$oGet->sigla}_anousu
                                      and rhpessoalmov.rh02_mesusu  = {$oGet->arquivo}.{$oGet->sigla}_mesusu
                                      and rhpessoalmov.rh02_regist  = {$oGet->arquivo}.{$oGet->sigla}_regist
                                      and rhpessoalmov.rh02_instit  = ".db_getsession("DB_instit")."
               inner join rhpessoal    on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist
               inner join rhlota       on rhlota.r70_codigo         = rhpessoalmov.rh02_lota  
										                  and rhlota.r70_instit         = rhpessoalmov.rh02_instit   
               inner join cgm          on cgm.z01_numcgm            = rhpessoal.rh01_numcgm
         where {$oGet->sigla}_rubric = '{$oGet->rubrica}' 
           and {$oGet->sigla}_anousu = {$oGet->ano} 
           and {$oGet->sigla}_mesusu = {$oGet->mes}
					 and {$oGet->sigla}_instit = ".db_getsession("DB_instit")."
          order by z01_nome";
$rsDadosPonto = db_query($sSql);

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
</head>
<body bgcolor="#CCCCCC">
<center>
 <form name="form1" method="post">
   <table border="0" width="100%" cellspacing="2" cellpadding="4">
   <?
   if (trim($sigla) != '') {
   ?>
      <tr bgcolor="#D3D3D3">
        <th nowrap>Registro</th>
        <th nowrap>Nome</th>
        <th nowrap>Lotação</th>
        <th nowrap>Descrição</th>
        <th nowrap>Quantidade</th>
        <th nowrap>Valor</th>
      </tr>
       <?
   
   
   	$sCor = "#DDDDDD";
   	$totalvalor = 0;
   	$totalquant = 0;
   	$totalregis = 0;
   	for ($iInd = 0; $iInd < pg_numrows($rsDadosPonto); $iInd ++) {
   		
   		$oDados = db_utils::fieldsMemory($rsDadosPonto, $iInd, true);
   		
   		if ($sCor=="#DDDDDD") {
   			$sCor = "#FFFFFF";
   		} else if ($sCor=="#FFFFFF") {
   			$sCor = "#DDDDDD";
   		}
   		
   		$totalvalor += $oDados->valor;
   		$totalquant += $oDados->quant;
   		$totalregis ++;
       ?>
       <tr onmouseover="bgcolor: '#000000';" onmouseout="bgcolor: '#FFFFFF'";>
         <td align="center" style="font-size:12px" nowrap bgcolor="<?=$sCor?>">
           <?db_ancora($oDados->r01_regist,"js_consultaregistro('$oDados->r01_regist','$oGet->rubrica');","1");?>
         </td>
         <td align="left" bgcolor="<?=$sCor?>">
           <?=$oDados->z01_nome?>
         </td>
         <td align="right" bgcolor="<?=$sCor?>">
           <?=$oDados->r13_codigo?>
         </td>
         <td align="left" bgcolor="<?=$sCor?>">
           <?=$oDados->r13_descr?>
         </td>
         <td align="right" bgcolor="<?=$sCor?>">
           <?=db_formatar($oDados->quant,'f')?>
         </td>
         <td align="right" bgcolor="<?=$sCor?>">
           <?=db_formatar($oDados->valor,'f')?>
         </td>
       </tr>
   <?
   	}
   ?>
   	<tr bgcolor="#D3D3D3">
     <td align="right" colspan="3">
       <strong>Totais</strong>
     </td>
     <td align="right" >
       <strong><?=$totalregis?></strong>
     </td>
     <td align="right" >
       <strong><?=db_formatar($totalquant,'f')?></strong>
     </td>
     <td align="right" >
       <strong><?=db_formatar($totalvalor,'f')?></strong>
     </td>
   	</tr>
  <?
   }
  ?>
   </table>
   
   <table>
     <tr>
       <td colspan="6" >&nbsp;&nbsp;</td>
     </tr>
   </table>
 </form>
</center>
</body>
<script>
function js_consultaregistro(registro,rubrica){
  js_OpenJanelaIframe('top.corpo','func_nome','pes3_conspessoal002.php?regist='+registro,'Visualização das matriculas cadastradas',true);
}
</script>
</html>