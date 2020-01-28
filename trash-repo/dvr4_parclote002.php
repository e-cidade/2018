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
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt15');
$clrotulo->label('DBtxt16');
$clrotulo->label('DBtxt17');
$clrotulo->label('DBtxt18');
$clrotulo->label('procdiver');
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><strong><font size="5" face="Arial, Helvetica, sans-serif">PROCESSANDO 
      ... AGUARDE.</font></strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?
if (isset($processar)){
$datpri    = $DBtxt15_ano.'-'.$DBtxt15_mes.'-'.$DBtxt15_dia;
$diapar    = $DBtxt16;
$valpar    = $DBtxt17;
$numpar    = $DBtxt18;

   $sql1 = "select * from procdiver where procdiver = $procdiver";
   db_fieldsmemory(pg_exec($sql1),0);

   $sql = " select j01_matric,
				j01_numcgm 
			from loteloteam 
				inner join iptubase on j01_idbql = j34_idbql 
			where j34_loteam = $loteam 
			and j01_baixa is null";
	$result = pg_exec($sql);
   	db_fieldsmemory($result,0);
	for($i = 0;$i < pg_numrows($result);$i++) {
    	db_fieldsmemory($result,$i);
		$valortotal = $numpar * $valpar;
		$numpre = pg_result(pg_exec("select nextval('numpref_k03_numpre_seq')"),0);
		$coddiver = pg_result(pg_exec("select nextval('diversos_coddiver_seq')"),0);
   	$sqlins = "insert into diversos values($coddiver,$j01_numcgm,'".date('Y-m-d',db_getsession("DB_datausu"))."',".db_getsession("DB_anousu").",$numpre,1,1,0,$valortotal,$procdiver,'PARCELAMENTO DE LOTEAMENTO REFERENTE AO EXERCICIO - ".db_getsession("DB_anousu")."','$datpri','$datpri',$valortotal)";
   	pg_exec($sqlins);
    $sqlins3 = "insert into divermatric values($coddiver,$j01_matric)";
		pg_exec($sqlins3);
		$sqlins4 = "insert into arrematric  values($numpre,$j01_matric)";
		pg_exec($sqlins4);
    	for($ii = 0 ; $ii < $numpar ; $ii++) {
  			$parcelas = $ii+1;
			if( $ii == 0 ) {
			   $vencimento = date('Y-m-d',mktime (0, 0, 0, $DBtxt15_mes,$DBtxt15_dia,$DBtxt15_ano));
			}else {
    		   $vencimento = date('Y-m-d',mktime (0, 0, 0, substr($datpri,6,2)+$ii, $diapar,db_getsession("DB_anousu")));
			}
    		$sqlins2 = "insert into arrecad values($j01_numcgm,'".date('Y-m-d',db_getsession("DB_datausu"))."',$receita,$k00_hist,$valpar,'$vencimento',$numpre,$parcelas,$numpar,0,25,0)";
    		pg_exec($sqlins2);
		}
	}
	pg_exec("commit");
    echo "<script> alert('Processamento Concluído.')</script>";
	echo "<script> location.href='dvr4_parclote001.php'</script>";
}else{
    echo "<script> alert('Esta Operação Deverá Ser Executada Somente Pelo Sistema.')</script>";
}

?>