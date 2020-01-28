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
include("classes/db_baseserie_classe.php");
include("classes/db_serie_classe.php");
include("dbforms/db_classesgenericas.php");
$clcriaabas = new cl_criaabas;
$clbaseserie = new cl_baseserie;
$clserie = new cl_serie;
$db_opcao = 1;
$sql = $clbaseserie->sql_query("","si.ed11_i_sequencia as inicial,sf.ed11_i_sequencia as final,si.ed11_i_ensino as ensino",""," ed87_i_codigo = $ed34_i_base");
$result = $clbaseserie->sql_record($sql);
db_fieldsmemory($result,0);
$sql1 = $clserie->sql_query_file("","ed11_i_codigo,ed11_c_descr","ed11_i_sequencia"," ed11_i_sequencia >= $inicial AND ed11_i_sequencia <= $final AND ed11_i_ensino = $ensino");
$result1 = $clserie->sql_record($sql1);
for($x=0;$x<$clserie->numrows;$x++){
 db_fieldsmemory($result1,$x);
 $num = $x+1;
 $ident["b$num"] = $ed11_c_descr;
 $tamcampo["b$num"] = 11;
 $pagina["b$num"] = "edu1_basemps001.php?ed34_i_base=$ed34_i_base&ed31_c_descr=$ed31_c_descr&curso=$curso&ed34_i_serie=$ed11_i_codigo&ed11_c_descr=$ed11_c_descr&discglob=$discglob&qtdper=$qtdper";
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<form name="formaba">
<table valign="top" marginwidth="0" width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="center" valign="top" bgcolor="#CCCCCC">
   <fieldset style="width:95%"><legend><b>Disciplinas da Base Curricular <?=@$ed31_c_descr?></b></legend>
   <?
   $clcriaabas->identifica    = $ident;
   $clcriaabas->sizecampo     = $tamcampo;
   $clcriaabas->src           = $pagina;
   $clcriaabas->iframe_width  = '100%';
   $clcriaabas->iframe_height = 430;
   $clcriaabas->scrolling     = "no";
   $clcriaabas->cria_abas();
   ?>
   </fieldset>
  </td>
 </tr>
</table>
</form>
</body>
</html>