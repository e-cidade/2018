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
include("classes/db_diversos_classe.php");
include("classes/db_cgm_classe.php");
include("libs/db_sql.php");
db_postmemory($HTTP_SERVER_VARS);
$db_botao=1;
$db_opcao=1;
$cldiversos = new cl_diversos;
$clcgm = new cl_cgm;
$clrotulo = new rotulocampo;
$clrotulo->label("dv09_descr");
$clrotulo->label("z01_nome");
$clrotulo->label("k00_matric");
$clrotulo->label("k00_inscr");
$cldiversos->rotulo->label();
//echo($cldiversos->sql_pesquisa("","dv05_coddiver","dv05_coddiver=$dv05_coddiver and dv05_instit = ".db_getsession('DB_instit').""));
$result01 = $cldiversos->sql_record($cldiversos->sql_pesquisa("","dv05_coddiver,dv05_numpre","dv05_coddiver=$dv05_coddiver and dv05_instit = ".db_getsession('DB_instit').""));
db_fieldsmemory($result01,0);

$result02 = debitos_numpre($dv05_numpre,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"),0,"1");
// debitos_numpre retorna false em caso esteja vazio
if ($result02){
  db_fieldsmemory($result02,0);
}

/* total pago */
$result03= pg_query("select sum(k00_valor) from arrepaga where k00_numpre = $dv05_numpre");
if(pg_numrows($result03)>0){
  db_fieldsmemory($result03,0);
}else{
  $sum="0,00"; 	
}
/* total devido */
$result04= pg_query("select sum(k00_valor) as total from arrecad where k00_numpre = $dv05_numpre");
if(pg_numrows($result04)>0){
  db_fieldsmemory($result04,0);
}else{
  $total="0,00"; 	
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post" >
<table>
<tr>
  <td nowrap><?=$Lz01_nome?><?=$z01_nome?>&nbsp;&nbsp;&nbsp;&nbsp;
  <?=($k00_tipo=="INSCRICAO"?"<b>$Lk00_inscr $k00_inscr</b>":($k00_tipo=="MATRICULA"?"$Lk00_matric $k00_matric":""))?> &nbsp;</td>
</tr>
<tr>
  <td>
    <table height="" width="" border="1" valign="top" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
      <tr>
        <td colspan="2" align="center" style='background-color:#cccc99;'>
	  <b>Dados dos diversos</b>
	</td>
      </tr>
      <tr>
        <td nowrap><?=$Ldv05_coddiver?></td>
        <td><?=$dv05_coddiver?>&nbsp;</td>
      </tr>
      <tr>
        <td nowrap><?=$Ldv05_numcgm?></td>
        <td><?=$dv05_numcgm?>&nbsp;</td>
      </tr>
        <td nowrap><?=$Ldv05_dtinsc?></td>
        <td><?=$dv05_dtinsc?>&nbsp;</td>
      </tr>
      <tr>
        <td nowrap><?=$Ldv05_exerc?></td>
        <td><?=$dv05_exerc?>&nbsp;</td>
      </tr>
      <tr>
        <td nowrap><?=$Ldv05_numpre?></td>
        <td><?=$dv05_numpre?>&nbsp;</td>
      </tr>
      <tr>
        <td nowrap><?=$Ldv05_vlrhis?></td>
        <td><?=db_formatar($dv05_vlrhis,'f')?>&nbsp;</td>
      </tr>
      <tr>
        <td nowrap><?=$Ldv09_descr?></td>
        <td><?=$dv09_descr?>&nbsp;</td>
      </tr>
      <tr>
        <td nowrap><?=$Ldv05_numtot?></td>
        <td><?=$dv05_numtot?>&nbsp;</td>
      </tr>
      <tr>
        <td nowrap><?=$Ldv05_privenc?></td>
        <td><?=$dv05_privenc?>&nbsp;</td>
      </tr>
      <tr>
        <td nowrap><?=$Ldv05_provenc?></td>
        <td><?=$dv05_provenc?>&nbsp;</td>
      </tr>
      <tr>
        <td nowrap><?=$Ldv05_diaprox?></td>
        <td><?=$dv05_diaprox?>&nbsp;</td>
      </tr>
      <tr>
        <td nowrap><?=$Ldv05_oper?></td>
        <td><?=$dv05_oper?>&nbsp;</td>
      </tr>
      <tr>
        <td nowrap><?=$Ldv05_valor?></td>
        <td><?=db_formatar($dv05_valor,'f')?>&nbsp;</td>
      </tr>
      <tr>
        <td nowrap><?=$Ldv05_obs?></td>
        <td><?=$dv05_obs?>&nbsp;</td>
      </tr>
    </table>
  </td>
  <td valign="top" width="300">
    <table height="" width="" border="1" valign="top" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
       <tr>
         <td colspan="2" align="center" style='background-color:#cccc99;'>
	   <b>Valores do diversos</b>
         </td>
       </tr>
       <tr> 
         <td align="left" width="15%" nowrap>
           <b>Valor pago:</b>
         </td>
         <td align="left" nowrap>
           <?=db_formatar($sum,'f')?>
         </td>
       </tr>
       <tr> 
         <td align="left" width="15%" nowrap>
           <b>Valor devido:</b>
         </td>
         <td align="left" nowrap>
           <?=db_formatar($total,'f')?>
         </td>
       </tr>
     </table>
  </td>
</tr>
</table>
</form>
</body>
</html>