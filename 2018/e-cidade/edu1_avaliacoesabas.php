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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
$clcriaabas = new cl_criaabas;
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<form name="formaba">
<table valign="top" marginwidth="0" width="790" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <?
   MsgAviso(db_getsession("DB_coddepto"),"escola");
   $clcriaabas->identifica = array("b1"=>"Avaliac�es Peri�dicas","b2"=>"Resultados");
   $clcriaabas->sizecampo  = array("b1"=>"20","b2"=>"20");
   $clcriaabas->src        = array("b1"=>"edu1_procavaliacao001.php?ed41_i_procedimento=$procedimento&ed40_c_descr=$ed40_c_descr","b2"=>"edu1_procresultadoabas.php?ed43_i_procedimento=$procedimento&ed40_c_descr=$ed40_c_descr&forma=".trim($forma)."");
   $clcriaabas->cortextoselecionado = "#004F9D";
   //$clcriaabas->deslocadireita = 135;
   $clcriaabas->abas_top   = 25;
   $clcriaabas->cria_abas();
   ?>
  </td>
 </tr>
</table>
</form>
</body>
</html>