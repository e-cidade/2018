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
include("classes/db_orcsuplem_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_orcsuplemtipo_classe.php");


$clcriaabas      = new cl_criaabas;
$clorcsuplemtipo = new cl_orcsuplemtipo;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$abas    = array();
$titulos = array();
$fontes  = array();
$sizecp  = array();

$o48_coddocsup ="";
$o48_coddocred ="";
$o48_arrecadmaior="";
$o48_superavit   =false;

$res = $clorcsuplemtipo->sql_record($clorcsuplemtipo->sql_query_file("$tipo_sup"));
db_fieldsmemory($res,0);

 // casos:
 // 1 suplementaçao + redução
 // 2 suplementação + receita
 // 3 suplementação //casos em que só existe suplementação como superavit ou operações de credito
if (($o48_coddocsup > 0 ) &&  ($o48_coddocred > 0 )){
     $modelo = 1;
} else if (($o48_coddocsup > 0 ) && ($o48_arrecadmaior > 0 )){
     $modelo= 2;
//} else if (($o48_coddocsup > 0 ) && ($o48_superavit == 't')){
} else { 
     $modelo= 3;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
     <tr> 
        <td width="360" height="18">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="140">&nbsp;</td>
     </tr>
   </table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
     <?
      if (!isset($alteracao)){
	if ($modelo == 1){
           $clcriaabas->identifica = array("reduz"=>"Reduções","suplem"=>"Suplementação");
	   $clcriaabas->title      = array("reduz"=>"Reduções","suplem"=>"Suplementação");
           $clcriaabas->src  = array("suplem"=>"orc1_orcsuplemval007.php?o47_codsup=$codsup","reduz"=>"orc1_orcsuplemval001.php?o47_codsup=$codsup");
	   $clcriaabas->sizecampo= array("suplem"=>"23","reduz"=>"23");
           $clcriaabas->cria_abas();    
	} else if ($modelo == 2){
           $clcriaabas->identifica = array("suplem"=>"Suplementação","receita"=>"Receitas");
	   $clcriaabas->title      = array("suplem"=>"Suplementação","receita"=>"Receitas");
           $clcriaabas->src  = array("suplem"=>"orc1_orcsuplemval007.php?o47_codsup=$codsup","receita"=>"orc1_orcsuplemrec007.php?o85_codsup=$codsup");
	   $clcriaabas->sizecampo= array("suplem"=>"23","receita"=>"23");
           $clcriaabas->cria_abas();    
	} else {
           $clcriaabas->identifica = array("suplem"=>"Suplementação");
	   $clcriaabas->title      = array("suplem"=>"Suplementação");
           $clcriaabas->src  = array("suplem"=>"orc1_orcsuplemval007.php?o47_codsup=$codsup");
	   $clcriaabas->sizecampo= array("suplem"=>"23");
           $clcriaabas->cria_abas();    
	}  
     }  else  { // // se for alteração/exclusão
     	if ($modelo == 1){
           $clcriaabas->identifica = array("dados"=>"Dados","reduz"=>"Reduções","suplem"=>"Suplementação");
	   $clcriaabas->title      = array("dados"=>"Dados","reduz"=>"Reduções","suplem"=>"Suplementação");
           $clcriaabas->src  = array("dados"=>"orc1_orcsuplem002.php?chavepesquisa=$codsup","suplem"=>"orc1_orcsuplemval007.php?o47_codsup=$codsup","reduz"=>"orc1_orcsuplemval001.php?o47_codsup=$codsup");
	   $clcriaabas->sizecampo= array("dados"=>"23","suplem"=>"23","reduz"=>"23");
           $clcriaabas->cria_abas();    
	} else if ($modelo == 2){
           $clcriaabas->identifica = array("dados"=>"Dados","suplem"=>"Suplementação","receita"=>"Receitas");
	   $clcriaabas->title      = array("dados"=>"Dados","suplem"=>"Suplementação","receita"=>"Receitas");
           $clcriaabas->src  = array("dados"=>"orc1_orcsuplem002.php?chavepesquisa=$codsup","suplem"=>"orc1_orcsuplemval007.php?o47_codsup=$codsup","receita"=>"orc1_orcsuplemrec007.php?o85_codsup=$codsup");
	   $clcriaabas->sizecampo= array("dados"=>"23","suplem"=>"30","receita"=>"23");
           $clcriaabas->cria_abas();    
	} else {
           $clcriaabas->identifica = array("dados"=>"Dados","suplem"=>"Suplementação");
	   $clcriaabas->title      = array("suplem"=>"Suplementação");
           $clcriaabas->src  = array("dados"=>"orc1_orcsuplem002.php?chavepesquisa=$codsup","suplem"=>"orc1_orcsuplemval007.php?o47_codsup=$codsup");
	   $clcriaabas->sizecampo= array("dados"=>"23","suplem"=>"23");
           $clcriaabas->cria_abas();    
	}  


     }
     ?>
    </center>
	</td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>