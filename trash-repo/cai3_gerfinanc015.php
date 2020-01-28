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
include("classes/db_fiscal_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clfiscal = new cl_fiscal;
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="parent.document.getElementById('processando').style.visibility = 'hidden'" >
<center>
  <table width="100%">
    <tr> 
      <td align="center">&nbsp;
	  <?
	  $where=" y30_instit = ".db_getsession('DB_instit') ;
	   
	   if ($tipo=='CGM'){
	     $where .= " and fiscalcgm.y36_numcgm = $cod";
     }else if ($tipo=='MATRICULA'){
	     $where .= " and fiscalmatric.y35_matric = $cod";
     }else if ($tipo=='INSCRICAO'){
       $where .= " and fiscalinscr.y34_inscr = $cod";
     }
     $sql=$clfiscal->sql_query_cons(null,"fiscal.*",null,$where);
 	  db_lovrot($sql,8);
	  ?>
	  </td>
    </tr>
  </table>
</center>
</body>
</html>