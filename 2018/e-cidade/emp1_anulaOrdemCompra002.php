<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//echo ($HTTP_SERVER_VARS['QUERY_STRING']);exit;
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

include("classes/db_cgm_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_matordem_classe.php");
include("classes/db_matordemanu_classe.php");
include("classes/db_matordemitem_classe.php");
include("classes/db_empempenho_classe.php");
include("classes/db_matestoqueitemoc_classe.php");

$clmatordem         = new cl_matordem;
$clmatordemanu      = new cl_matordemanu;
$clmatordemitem     = new cl_matordemitem;
$clempempenho       = new cl_empempenho;
$clcgm              = new cl_cgm;
$cldbdepart         = new cl_db_depart;
$clmatestoqueitemoc = new cl_matestoqueitemoc;
$oDaoEmpParam       = db_utils::getDao("empparametro");
$rsParam            = $oDaoEmpParam->sql_record($oDaoEmpParam->sql_query(db_getsession("DB_anousu")));
$sDisplay           = 'none';
if ($oDaoEmpParam->numrows > 0) {

  $oParam           = db_utils::fieldsMemory($rsParam, 0);
  if ( $oParam->e30_notaliquidacao != '') {
    $sDisplay = '';
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/notaliquidacao.js"></script>
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
<center>
<table  border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td  align="left" valign="top" bgcolor="#CCCCCC">
    <center>
    <?
      include("forms/db_frmmatordemajax.php");
    ?>
   </center>
   </td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>