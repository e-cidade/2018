<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_benscorlanc_classe.php");
include("classes/db_benscorr_classe.php");
$clbenscorlanc = new cl_benscorlanc;
$clbenscorr = new cl_benscorr;
db_postmemory($HTTP_POST_VARS);
$db_opcao = 33;
$db_botao = false;

if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  if($sqlerro==false){
    $clbenscorr->t63_codcor=$t62_codcor;
    $clbenscorr->excluir($t62_codcor);
    if($clbenscorr->erro_status==0){
      $sqlerro=true;
    } 
    $erro_msg = $clbenscorr->erro_msg; 
  }
  if($sqlerro==false){
    $clbenscorlanc->excluir($t62_codcor);
    if($clbenscorlanc->erro_status==0){
      $sqlerro=true;
    } 
    $erro_msg = $clbenscorlanc->erro_msg;
  }
  db_fim_transacao($sqlerro);
}else if(isset($chavepesquisa)){
  $db_opcao = 3;
  $db_botao = true;
  $result = $clbenscorlanc->sql_record($clbenscorlanc->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
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
    <center>
	<?
	include("forms/db_frmbenscorlanc.php");
	?>
    </center>
</body>
</html>
<?
if(isset($excluir)){
  db_msgbox($erro_msg);
  if($sqlerro==true){
    if($clbenscorlanc->erro_campo!=""){
      echo "<script> document.form1.".$clbenscorlanc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbenscorlanc->erro_campo.".focus();</script>";
    };
  }else{
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='pat1_benscorlanc003.php';
    }\n
    js_db_tranca();
  </script>\n
 ";
  }
}
if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.benscorr.disabled=false;
         top.corpo.iframe_benscorr.location.href='pat1_benscorr001.php?db_opcaoal=33&t63_codcor=".@$t62_codcor."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('benscorr');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>