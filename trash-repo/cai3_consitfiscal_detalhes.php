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
include("classes/db_vistorias_classe.php");
include("classes/db_auto_classe.php");
include("classes/db_fiscal_classe.php");
include("classes/db_levanta_classe.php");
db_postmemory($HTTP_GET_VARS,0);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clvistorias = new cl_vistorias;
$clauto = new cl_auto;
$clfiscal = new cl_fiscal;
$cllevanta = new cl_levanta;
$where = "";
?>
<html>
<head>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_vist(cod){
	js_OpenJanelaIframe('top.corpo','db_iframe_consulta','fis3_consultavist002.php?y70_codvist='+cod,'Consulta Vistoria',true);
}
function js_auto(cod){
	js_OpenJanelaIframe('top.corpo','db_iframe','fis3_consautoinf002.php?codauto='+cod,'Consulta Auto de Infração',true);
}
function js_notific(cod){
	js_OpenJanelaIframe('top.corpo','db_iframe','fis3_consnotificinf002.php?codfiscal='+cod,'Consulta Notificação',true);
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?
$pesquisaLocalizada = false;
if ($solicitacao == "VISTORIA") {
   $funcao_js="js_vist|y70_codvist";
   if ($tipo=="CGM"){
	$where = "(vistcgm.y73_numcgm = $cod or y80_numcgm = $cod or q02_numcgm = $cod) and y70_instit = ".db_getsession('DB_instit') ;
  }else if ($tipo=="MATRICULA"){
	$where = "vistmatric.y72_matric = $cod and y70_instit = ".db_getsession('DB_instit');
  }else if ($tipo=="INSCRICAO"){
	$where = "vistinscr.y71_inscr = $cod and y70_instit = ".db_getsession('DB_instit');
  }
  $sql=$clvistorias->sql_query_cons(null,"vistorias.*",null,$where);
  $pesquisaLocalizada = true;
} else if ($solicitacao == "AUTO") {
  $funcao_js="js_auto|y50_codauto";	
  if ($tipo=="CGM"){
	$where = "(autocgm.y54_numcgm = $cod or sanitario.y80_numcgm = $cod or q02_numcgm = $cod) and y50_instit = ".db_getsession('DB_instit') ;
  }else if ($tipo=="MATRICULA"){
	$where = "automatric.y53_matric = $cod and y50_instit = ".db_getsession('DB_instit') ;
  }else if ($tipo=="INSCRICAO"){
	$where = "autoinscr.y52_inscr = $cod and y50_instit = ".db_getsession('DB_instit') ;
  }
  $sql=$clauto->sql_query_info(null,"auto.*",null,$where);
  $pesquisaLocalizada = true;
} else if ($solicitacao == "NOTIFICA") {
  $funcao_js="js_notific|y30_codnoti";
  if ($tipo=="CGM"){
	$where = "(fiscalcgm.y36_numcgm = $cod or sanitario.y80_numcgm = $cod or q02_numcgm = $cod) and y30_instit = ".db_getsession('DB_instit') ;
  }else if ($tipo=="MATRICULA"){
	$where = "fiscalmatric.y35_matric = $cod and y30_instit = ".db_getsession('DB_instit') ;
  }else if ($tipo=="INSCRICAO"){
	$where = "fiscalinscr.y34_inscr = $cod and y30_instit = ".db_getsession('DB_instit') ;
  }
  $sql=$clfiscal->sql_query_cons(null,"fiscal.*",null,$where);
  $pesquisaLocalizada = true;
} else if ($solicitacao == "LEVANTA") {
  $funcao_js="";
  if ($tipo=="CGM"){
	$where = "levcgm.y93_numcgm = $cod";
  }else if ($tipo=="MATRICULA"){
	$where = "1=2";	
  }else if ($tipo=="INSCRICAO"){
	$where = "levinscr.y62_inscr = $cod";
  }
  $sql=$cllevanta->sql_query_inf(null,"levanta.*",null,$where);
  $pesquisaLocalizada = true;
}
if ($pesquisaLocalizada==true) {
  $result = pg_exec($sql);
  if(pg_numrows($result) == 0){
    echo "<br><br><b>Nenhum Registro Cadastrado!!<b>";
  }else{  	
      db_lovrot($sql,20,"()","",$funcao_js,"","NoMe",array (),false);
  }
}
?>
</body>
</html>