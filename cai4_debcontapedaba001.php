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
include("dbforms/db_classesgenericas.php");
$clcriaabas     = new cl_criaabas;
$db_opcao = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="790" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form name="formaba">
<table valign="top" marginwidth="0" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td  align="left" valign="top" bgcolor="#CCCCCC"> 
     <?
       if (isset($z01_numcgm)&&$z01_numcgm!=""){
       	$codtipo=$z01_numcgm;
       	$tipo="CGM";
       }else if (isset($j01_matric)&&$j01_matric!=""){
       	$codtipo=$j01_matric;
       	$tipo="MATRIC";
       }else if (isset($x01_matric)&&$x01_matric!=""){
       	$codtipo=$x01_matric;
       	$tipo="MATRIC";
       }else if (isset($q02_inscr)&&$q02_inscr!=""){
       	$codtipo=$q02_inscr;
       	$tipo="INSCR";
       }
	   $clcriaabas->identifica = array("pedido"=>"Pedido","debito"=>"Débitos");
	   $clcriaabas->sizecampo  = array("pedido"=>"20","debito"=>"20");
	   $clcriaabas->title      = array("pedido"=>"Pedido","debito"=>"Débitos");
	   $clcriaabas->src        = array("pedido"=>"cai1_debcontapedido001.php?tipo=$tipo&codtipo=$codtipo","debito"=>"");
	   $clcriaabas->disabled   =  array("debito"=>"true"); 
	   $clcriaabas->cria_abas(); 
	 ?> 
	 </td>
      </tr>
    </table>
    </form>
	<? 
	  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
	?>
    </body>
    </html>