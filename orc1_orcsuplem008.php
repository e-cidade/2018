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
include("classes/db_orcsuplemtipo_classe.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_orcprojeto_classe.php");

db_postmemory($HTTP_POST_VARS);

$clorcsuplem = new cl_orcsuplem;
$clorcprojeto = new cl_orcprojeto;
$clorcsuplemtipo = new cl_orcsuplemtipo;
$clcriaabas     = new cl_criaabas;

if (isset($codsup) && $codsup !=""){
  // alteração
  // procura o tipo de suplementação da suplementação para liberar as abas
  $rr = $clorcsuplem->sql_record($clorcsuplem->sql_query_file($codsup,"o46_tiposup"));
  db_fieldsmemory($rr,0);
  $res = $clorcsuplemtipo->sql_record($clorcsuplemtipo->sql_query_file($o46_tiposup));
  db_fieldsmemory($res,0); // é obrigatorio retornar 1 registro
 

} else {  
  // inclusão
  // este fonte recebe  projeto, tiposup
  // de acordo com o tiposup ( tipo de suplementação ) carrega as abas
  $res = $clorcsuplemtipo->sql_record($clorcsuplemtipo->sql_query_file($tiposup));
  db_fieldsmemory($res,0); // é obrigatorio retornar 1 registro
  // gera um codigo na tabela orcsuplem
  db_inicio_transacao();
   $erro = false;
   $clorcsuplem->o46_codlei = $projeto ; // codigo do projeto
   $clorcsuplem->o46_data   = date("Y-m-d",db_getsession("DB_datausu"));   
   $clorcsuplem->o46_instit = db_getsession("DB_instit");
   $clorcsuplem->o46_tiposup = $tiposup;
   $clorcsuplem->incluir(null);  
   $codsup = $clorcsuplem->o46_codsup;
   if ($clorcsuplem->erro_status == "0" ){
         db_msgbox($clorcsuplem->erro_msg);
	 $erro = true;
   }  
  db_fim_transacao($erro);
}	

$identifica = array();
$src        = array();
$sizecampo  = array();

if (isset($o48_coddocsup) && $o48_coddocsup > 0){
  $identifica["suplementacao"] = "Suplementações";
  $src["suplementacao"]        = "orc1_orcsuplemval007.php?o39_codproj=$projeto&o46_codsup=$codsup";
  $sizecampo["suplementacao"]   = 23;
}  
if (isset($o48_coddocred) && $o48_coddocred > 0){
  $identifica["reducao"] = "Reduções";
  $src["reducao"]        = "orc1_orcsuplemval001.php?o39_codproj=$projeto&o46_codsup=$codsup";
  $sizecampo["reducao"]  = 23;
}  
if (isset($o48_arrecadmaior) && $o48_arrecadmaior > 0){
  $identifica["receita"] = "Receitas";
  $src["receita"]        = "orc1_orcsuplemrec007.php?o39_codproj=$projeto&o46_codsup=$codsup";
  $sizecampo["receita"]  = 23;
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
<table width="99%" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
<tr> 
  <td height="20px" align="left" valign="top" bgcolor="#CCCCCC"> 

    <?
    $clcriaabas->identifica = $identifica;
    $clcriaabas->src        = $src;
    $clcriaabas->sizecampo  = $sizecampo;
    $clcriaabas->cria_abas();    
   ?>

</td>
<td valign=top>
 <input style="border:1px solid #999999;width:100px;height:25px" type=button onclick="parent.js_fechar();" value=Fechar>
</td>
</tr>

</table>

</body>
</html>