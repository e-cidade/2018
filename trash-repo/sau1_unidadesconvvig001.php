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
include("classes/db_unidades_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_sau_vinculosus_classe.php");

db_postmemory($HTTP_POST_VARS);
$clcriaabas     = new cl_criaabas;
$clunidades = new cl_unidades;
$clsau_vinculosus = new cl_sau_vinculosus;
$db_opcao = 2;
$db_botao = true;
$result = $clunidades->sql_record($clunidades->sql_query("","sd02_i_codigo,descrdepto",""," sd02_i_codigo = $chavepesquisa"));
db_fieldsmemory($result,0);

$result2 = $clsau_vinculosus->sql_record($clsau_vinculosus->sql_query("","",""," sd50_i_unidade=$sd02_i_codigo"));
if( $result2  ){
  db_fieldsmemory($result2,0);
  $arquivo = "sau1_sau_vinculosus002.php?chavapesquisa=$sd50_i_unidade";
}else{
  $arquivo = "sau1_sau_vinculosus001.php?";
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <?
   $clcriaabas->abas_top   = "50";
   $clcriaabas->identifica = array("b1"=>"Vínculo com o SUS","b2"=>"Vigilância Sanitária");
   $clcriaabas->sizecampo  = array("b1"=>"30","b2"=>"30");
   $clcriaabas->src        = array("b1"=>$arquivo."sd50_i_unidade=$sd02_i_codigo&descrdepto=$descrdepto","b2"=>"sau1_unidadesalvara001.php?chavepesquisa=$sd02_i_codigo&descrdepto=$descrdepto");
   $clcriaabas->iframe_height= "350";
   $clcriaabas->iframe_width= "90%";
   $clcriaabas->scrolling     = "no";
   $clcriaabas->cria_abas();
   ?>
   </center>
  </td>
 </tr>
</table>
</body>
</html>