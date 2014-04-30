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
include("classes/db_diversos_classe.php");
db_postmemory($HTTP_POST_VARS);
$cldiversos = new cl_diversos;

$dbwhere = " dv05_instit = ".db_getsession('DB_instit');

if(isset($dv05_coddiver) && $dv05_coddiver!=""){
  $dbwhere.= " and dv05_coddiver= $dv05_coddiver ";
}elseif(isset($dv05_numcgm) && $dv05_numcgm!=""){
  $dbwhere.= " and k00_numcgm= $dv05_numcgm ";
}elseif(isset($j01_matric) && $j01_matric!=""){
  $dbwhere.= " and k00_matric = $j01_matric ";
}elseif(isset($q02_inscr) && $q02_inscr!=""){
  $dbwhere.= " and k00_inscr = $q02_inscr  ";
}elseif(isset($dv09_procdiver) && $dv09_procdiver !=""){
  $dbwhere.= " and dv05_procdiver = $dv09_procdiver ";
}
if(isset($dataini_dia) && $dataini_dia!="" && isset($dataini_mes) && $dataini_mes!="" &&   isset($dataini_ano) && $dataini_ano!=""){
  $dbwhere.= " and dv05_dtinsc > '$dataini_ano-$dataini_mes-$dataini_dia' ";
  $ini="ok";
}
if(isset($datafim_dia) && $datafim_dia!="" && isset($datafim_mes) && $datafim_mes!="" &&   isset($datafim_ano) && $datafim_ano!=""){
  if(isset($ini)){
    $dbwhere.=" and dv05_dtinsc < '$datafim_ano-$datafim_mes-$datafim_dia'";
  }else{  
    $dbwhere.= "and dv05_dtinsc < '$datafim_ano-$datafim_mes-$datafim_dia' ";
  }  
}
//db_msgbox($dbwhere);
$dbwhere = base64_encode($dbwhere);
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table height="430" width="790" border="1" valign="top" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
  <tr> 
  <td align="center" valign="top" bgcolor="#cccccc">     
  <form name="form1" method="post">
   <table border="0" cellspacing="0" cellpadding="0">
   <br>
   <br>
     <tr>
       <td colspan="2" align="center">
         <input type="button" name="voltar" value="Voltar" onclick="location.href='dvr3_consdiversos001.php'">
       </td>   	 
     </tr>	 
     <tr>
       <td>
       <iframe name="diversos" id="diversos" src="dvr3_consdiversos003.php?dbwhere=<?=$dbwhere?>" width="750" height="310">
       </iframe>
       </td>
     </tr>
    </table> 	 
  </form>
  </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>