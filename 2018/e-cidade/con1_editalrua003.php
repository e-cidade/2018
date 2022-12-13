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
include("classes/db_contlot_classe.php");
include("classes/db_contrib_classe.php");
include("classes/db_contricalc_classe.php");
include("classes/db_contlotv_classe.php");
include("classes/db_editalrua_classe.php");
include("classes/db_editalproj_classe.php");
include("classes/db_editalruaproj_classe.php");
include("classes/db_editalserv_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clcontlot = new cl_contlot;
$clcontrib = new cl_contrib;
$clcontricalc = new cl_contricalc;
$clcontlotv = new cl_contlotv;
$cleditalrua = new cl_editalrua;
$cleditalproj = new cl_editalproj;
$cleditalruaproj = new cl_editalruaproj;
$cleditalserv = new cl_editalserv;
$db_botao = false;
$db_opcao = 33;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
  db_inicio_transacao();
  $db_opcao = 3;
  $clcontrib->sql_record($clcontrib->sql_query_file($d02_contri,"","d07_contri"));
  if($clcontrib->numrows>0){
    $clcontrib->d07_contri=$d02_contri;
    $clcontrib->excluir($d02_contri);
    if($clcontrib->erro_status=="0"){
       $sqlerro=true;
     }
  }  
  $clcontlot->sql_record($clcontlot->sql_query_file($d02_contri,"","d05_contri"));
  if($clcontlot->numrows>0){
    $clcontlotv->d06_contri=$d02_contri;
    $clcontlotv->excluir($d02_contri);
    if($clcontlotv->erro_status=="0"){
       $sqlerro=true;
     }
    $clcontlot->d05_contri=$d02_contri;
    $clcontlot->excluir($d02_contri);
    if($clcontlot->erro_status=="0"){
       $sqlerro=true;
    }
  }	
  $results=$cleditalruaproj->sql_record($cleditalruaproj->sql_query($d02_contri,"","d11_codproj"));
  if($cleditalruaproj->numrows>0){
    $cleditalruaproj->d11_contri=$d02_contri;
    $cleditalruaproj->excluir($d02_contri);
    if($cleditalruaproj->erro_status=='0'){
      $sqlerro = true;
    }
  } 
  $cleditalserv->d04_contri=$d02_contri;
  $cleditalserv->excluir($d02_contri);
  if($cleditalserv->erro_status=="0"){
     $sqlerro=true;
  }
  $cleditalrua->excluir($d02_contri);
  if($cleditalrua->erro_status=="0"){
     $sqlerro=true;
  }
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $cleditalrua->sql_record($cleditalrua->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);

   $result = $cleditalserv->sql_record($cleditalserv->sql_query($d02_contri,"","d04_tipos,d04_quant,d04_vlrcal,d04_vlrval,d04_mult,d04_forma,d03_descr,d04_vlrobra")); 
   $numi= $cleditalserv->numrows;
   $dados="";
   for($x=0;$x<$numi;$x++){
     db_fieldsmemory($result,$x);
     $dados .= $d04_tipos."-".$d04_quant."-".$d04_vlrcal."-".$d03_descr."-".$d04_vlrval."-".$d04_mult."-".$d04_forma."-".$d04_vlrobra."XX";
   }
   $numedital=$d02_codedi;
 $clcontlot->sql_record($clcontlot->sql_query($d02_contri,"","d05_contri"));
 if($clcontlot->numrows>0){
   $db_botao = false;
   $db_opcao="33";
   $calcnops="nops";
   $naopod="nao";
 }else{
   $db_botao = true;
 }  
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmeditalruaalt.php");
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
<?
if(isset($naopod)){
  db_msgbox("Exclusão não permitida. Os lotes já foram selecionados.");
}
if($cleditalrua->erro_status=="0"){
  $cleditalrua->erro(true,false);
}else{
  $cleditalrua->erro(true,true);
};
?>