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
include("classes/db_orcimpacto_classe.php");

$clorcimpacto = new cl_orcimpacto;
include("classes/db_orcdotacao_classe.php");
include("classes/db_orcdotacaocontr_classe.php");
include("classes/db_orcelemento_classe.php");
include("classes/db_orcparametro_classe.php");
include("classes/db_orcorgao_classe.php");
include("classes/db_orcunidade_classe.php");
include("classes/db_orcfuncao_classe.php");
include("classes/db_orcsubfuncao_classe.php");
include("classes/db_orcprograma_classe.php");
include("classes/db_orcprojativ_classe.php");
include("classes/db_orcproduto_classe.php");
include("classes/db_orcimpactoperiodo_classe.php");

$clorcprojativ = new cl_orcprojativ;
$clorcdotacao = new cl_orcdotacao;
$clorcdotacaocontr = new cl_orcdotacaocontr;
$clorcelemento = new cl_orcelemento;
$clorcparametro = new cl_orcparametro;
$clorcorgao = new cl_orcorgao;
$clorcunidade = new cl_orcunidade;
$clorcfuncao = new cl_orcfuncao;
$clorcsubfuncao = new cl_orcsubfuncao;
$clorcprograma = new cl_orcprograma;
$clorcproduto = new cl_orcproduto;
$clorcimpactoperiodo = new cl_orcimpactoperiodo;


db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

if(isset($incluir) || isset($alterar)){
  $result=$clorcimpactoperiodo->sql_record($clorcimpactoperiodo->sql_query_file($o90_codperiodo,"o96_anoini"));
  db_fieldsmemory($result,0);

  $dbwhere  = " o90_codperiodo  =  $o90_codperiodo and o90_anoexe = $o96_anoini and o90_orgao = $o90_orgao "; 
  $dbwhere .= " and o90_unidade = $o90_unidade     and o90_funcao = $o90_funcao and o90_subfuncao=$o90_subfuncao ";
  $dbwhere .= " and o90_programa= $o90_programa    and o90_acao   = $o90_acao";
}

if(isset($alterar)){
  $sqlerro=false;
  db_inicio_transacao();
  $clorcimpacto->sql_record($clorcimpacto->sql_query_file(null,"o90_codimp","",$dbwhere));
  if($clorcimpacto->numrows>1){
    $sqlerro  = true;
    $erro_msg = "Já existe..."; 
    $jaexiste=true;
  } 
  if($sqlerro == false){
    $clorcimpacto->alterar($o90_codimp);
    if($clorcimpacto->erro_status==0){
      $sqlerro=true;
    } 
    $erro_msg = $clorcimpacto->erro_msg; 
  }  
  db_fim_transacao($sqlerro);
   $db_opcao = 2;
   $db_botao = true;
}elseif(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $clorcimpacto->sql_record($clorcimpacto->sql_query_file(null,"o90_codimp","",$dbwhere));
  if($clorcimpacto->numrows>0){
    $sqlerro  = true;
    $erro_msg = "Já existe..."; 
    $jaexiste=true;
  } 
  if($sqlerro == false){
    $clorcimpacto->o90_anoexe = $o96_anoini;
    $clorcimpacto->incluir(null);
    $erro_msg = $clorcimpacto->erro_msg; 
    if($clorcimpacto->erro_status==0){
      $sqlerro=true;
    }else{
      $o90_codimp = $clorcimpacto->o90_codimp;
    } 
  }  
  db_fim_transacao($sqlerro);
   $db_opcao = 1;
   $db_botao = true;
}else if(isset($chavepesquisa)|| (isset($chave_nova) && $chave_nova != '')){
   if(isset($chave_nova)){
     $db_opcao = 1;
     $o90_codimp='';
     $chavepesquisa = $chave_nova;
   }else{
     $db_opcao = 2;
     $db_botao = true;
   }   
   $result = $clorcimpacto->sql_record($clorcimpacto->sql_query_compl($chavepesquisa)); 
   db_fieldsmemory($result,0);
   if(isset($chave_nova)){
        unset($o90_codimp);
   }  
}else{
  if(isset($o90_codimp) && $o90_codimp !=''){
    $db_opcao = 2;
  }else{
    $db_opcao = 1;
  }  
}
$db_botao = true;
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="300" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmorcimpacto.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir) || isset($alterar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
  }else{
    //db_msgbox($erro_msg);
    if(isset($incluir)){
      db_redireciona("orc1_orcimpacto004.php?liberaaba=true&chavepesquisa=$o90_codimp");
    } 
  }
}
if(isset($chavepesquisa) && empty($chave_nova)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.orcimpactoval.disabled=false;
         top.corpo.iframe_orcimpactoval.location.href='orc1_orcimpactoval001.php?o91_codimp=".@$o90_codimp."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('orcimpactoval');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
 if(isset($jaexiste)){
    echo "<script>document.form1.consultar.click();</script>";
 }

?>